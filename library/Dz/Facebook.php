<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Facebook
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id$
 */

/**
 * We need to implement P3P header to tell the browser that cookies
 * for your application inside iframe are OK for user privacy.
 */
if (!headers_sent()) {
    header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
}

/**
 * @see Facebook
 */
require_once 'facebook.php';

/**
 * Provides methods for simplify Facebook Graph API calls.
 *
 * @category   Dz
 * @package    Dz_Facebook
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Facebook extends Facebook
{
    const APP_DATA_SEPARATOR = '|';

    const COVER_TYPE_ALBUM = 'album';
    const COVER_TYPE_SMALL = 'small';
    const COVER_TYPE_THUMBNAIL = 'thumbnail';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    const PHOTO_TYPE_UPLOADED = 'uploaded';
    const PHOTO_TYPE_TAGGED = 'tagged';
    const PHOTO_TYPE_HIGHLIGHTED = 'highlighted';

    /**
     * Application's URL on Facebook.
     *
     * @var string
     */
    protected $_appUrl;

    /**
     * Post-authentication URL (workaround for Safari).
     *
     * @var string
     */
    protected $_redirectUrl;

    /**
     * Additional permissions.
     *
     * @var array
     * @see https://developers.facebook.com/docs/authentication/permissions/
     */
    protected $_scope = array();

    /**
     *
     * @var NULL|array
     */
    protected $_userProfile = null;

    /**
     * Identical to the parent constructor, except that
     * we start a PHP session to store the user ID and
     * access token if during the course of execution
     * we discover them.
     *
     * @param array $config the application configuration.
     * @see BaseFacebook::__construct in facebook.php
     */
    public function __construct($config)
    {
        parent::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;

        if (key_exists('scope', $config)) {
            if (is_array($config['scope'])) {
                $this->_scope = $config['scope'];
            }

            unset($config['scope']);
        }

        if (key_exists('appUrl', $config)) {
            $this->_appUrl = $config['appUrl'];

            unset($config['appUrl']);
        }

        if (key_exists('redirectUrl', $config)) {
            $this->_redirectUrl = $config['redirectUrl'];

            unset($config['redirectUrl']);
        }

        parent::__construct($config);

        $this->_setUserProfile();
    }

    /**
     * Redirect to authentication URL.
     *
     * @return void
     */
    protected function _authenticate()
    {
        $loginUrl = $this->getLoginUrl();

        $this->redirect($loginUrl);
    }

    protected function _fql($query)
    {
        $params = array(
            'method' => 'fql.query',
            'query'  => $query,
        );

        return $this->api($params);
    }

    /**
     * Invoke the Graph API.
     *
     * When querying connections, there are several useful parameters
     * that enable you to filter and page through connection data.
     *
     * @param string $path The path (required)
     * @param string $method The http method (default 'GET')
     * @param array $params The query/post data
     *
     * @return mixed The decoded response object
     * @throws FacebookApiException
    */
    protected function _graph($path, $method = self::METHOD_GET,
        $limit = null, $since = null, $params = array())
    {
        if (is_array($limit)) {
            $params = $limit;
        } else {
            if ($limit !== null) {
                $params['limit'] = $limit;
            }

            if ($since !== null) {
                $params['since'] = $since;
            }
        }

        return parent::_graph($path, $method, $params);
    }

    /**
     *
     * @return void
     */
    protected function _setUserProfile()
    {
        if ($this->getUser() !== 0) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $this->_userProfile = $this->_graph('/me');
            } catch (\FacebookApiException $e) {
                // Shhhh!
            }
        }
    }

    /**
     * Get an URL with app_data parameter.
     *
     * @param string $url
     * @param array $appData
     * @return string
     */
    public function appendAppData($url, $appData = null)
    {
        if (is_array($appData) && count($appData) > 0) {
            $appData = http_build_query($appData, null, '|');

            if (strpos($url, '?') === false) {
                $url .= '?app_data=' . $appData;
            } else {
                $url .= '&app_data=' . $appData;
            }
        }

        return $url;
    }

    /**
     * @return void
     */
    public function checkAuthentication()
    {
        if (!$this->isAuthenticated()) {
            $this->_authenticate();
        }

        $permissions = $this->_graph('/me/permissions');
        $currentPermissions = array_keys(array_shift($permissions['data']));

        if (count(array_diff($this->_scope, $currentPermissions)) > 0) {
            $this->_authenticate();
        }
    }

    public function getAlbums($coverType = self::COVER_TYPE_ALBUM)
    {
        if ($coverType !== self::COVER_TYPE_ALBUM &&
            $coverType !== self::COVER_TYPE_SMALL &&
            $coverType !== self::COVER_TYPE_THUMBNAIL) {
            $coverType = self::COVER_TYPE_ALBUM;
        }

        $albums = $this->_graph('/me/albums');
        $returnAlbums = array();
        $coverFormat = 'https://graph.facebook.com/%s/picture?type=%s'
                     . '&access_token=%s';

        foreach ($albums['data'] as $album) {
            $returnAlbums[] = array(
                'id'    => $album['id'],
                'name'  => $album['name'],
                'cover' => sprintf($coverFormat,
                    $album['id'], $coverType, $this->getAccessToken())
            );
        }

        return $returnAlbums;
    }

    public function getAlbumPhotos($albumId)
    {
        $params = array(
            'fields' => array('id', 'from', 'picture', 'source',
                              'tags', 'likes', 'comments', 'sharedposts'),
        );

        $photos = $this->_graph('/' . $albumId . '/photos',
            self::METHOD_GET, $params);

        return $photos['data'];
    }

    /**
     * @return array
     */
    public function getAppData()
    {
        $signedRequest = $this->getSignedRequest();
        $appData = array();

        if ($signedRequest !== null && key_exists('app_data', $signedRequest)) {
            $stringAppData = $signedRequest['app_data'];
            $appDataPairs = explode(self::APP_DATA_SEPARATOR, $stringAppData);

            foreach ($appDataPairs as $appDataPair) {
                list($key, $value) = explode('=', $appDataPair);

                $appData[$key] = $value;
            }
        }

        return $appData;
    }

    /**
     * @return string
     */
    public function getAppUrl()
    {
        return $this->_appUrl;
    }

    public function getFriends()
    {
        $friends = $this->_graph('/me/friends');

        return $friends['data'];
    }

    /**
     *
     * @param string $since
     * @return array
     */
    public function getInbox($limit = 25, $since = null)
    {
        $threads = $this->_graph('/me/inbox', self::METHOD_GET, $limit, $since);

        return $threads['data'];
    }

    /**
     * Get authentication URL.
     *
     * @return string
     */
    public function getLoginUrl($appData = null)
    {
        $redirectUri = $this->appendAppData($this->_redirectUrl, $appData);

        return parent::getLoginUrl(
            array(
                'scope' => $this->_scope,
                'redirect_uri' => $redirectUri,
            )
        );
    }

    public function getMostMutualFriendsFriend()
    {
        $query = 'SELECT uid, name, mutual_friend_count FROM user WHERE uid '
               . 'IN (SELECT uid2 FROM friend WHERE uid1 = me()) '
               . 'ORDER BY mutual_friend_count DESC LIMIT 1';

        $data = $this->_fql($query);

        return array_pop($data);
    }

    public function getMutualFriends($userId)
    {
        $userId = preg_replace('/\D+/', '', $userId);

        $params = array(
            'user' => $userId,
        );

        $mutualFriends = $this->_graph('/me/mutualfriends',
            self::METHOD_GET, $params);

        return $mutualFriends['data'];
    }

    public function getNamesAndGenders(array $uids)
    {
        $uids = array_unique($uids);

        $query = 'SELECT uid, name, sex FROM user '
               . 'WHERE uid IN (' . join(', ', $uids) . ')';

        $result = $this->_fql($query);
        $namesAndGenders = array();

        foreach ($result as $user) {
            $namesAndGenders[$user['uid']] = array(
                'name'   => $user['name'],
                'gender' => $user['sex'],
            );
        }

        return $namesAndGenders;
    }

    public function getPhotos($type = self::PHOTO_TYPE_UPLOADED,
        $limit = 25, $since = null
    ) {
        $params = array(
            'type'   => $type,
            'fields' => array('id', 'from', 'picture', 'source',
                              'tags', 'likes', 'comments', 'sharedposts'),
        );

        $photos = $this->_graph('/me/photos', self::METHOD_GET,
            $limit, $since, $params);

        return $photos['data'];
    }

    /**
     *
     * @param string $since
     * @return array
     */
    public function getPosts($limit = 100, $since = null)
    {
        $query = 'SELECT message, likes.friends, comments.comment_list.fromid '
               . 'FROM stream WHERE source_id = me() '
               . 'AND message != "" AND type != "" ';

        if ($since !== null) {
            $query .= 'AND created_time >= ' . strtotime($since) . ' ';
        }

        if (is_int($limit)) {
            $query .= 'LIMIT ' . $limit;
        }

        return $this->_fql($query);
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->_redirectUrl;
    }

    /**
     *
     * @return
     */
    public function getScope()
    {
        return $this->_scope;
    }

    /**
     *
     * @param string $since
     * @return array
     */
    public function getTaggedInPosts($limit = 25, $since)
    {
        $posts = $this->_graph('/me/tagged', self::METHOD_GET, $limit, $since);

        return $posts['data'];
    }

    /**
     * @return NULL|array
     */
    public function getUserProfile()
    {
        return $this->_userProfile;
    }

    public function hasLikedCurrentPage()
    {
        $signedRequest = $this->getSignedRequest();

        return $signedRequest !== null &&
            isset($signedRequest['page']) &&
            $signedRequest['page']['liked'] === true;
    }

    public function isAuthenticated()
    {
        return is_array($this->_userProfile);
    }

    /**
     * Sends free-form messages to users.
     *
     * @param integer|string $uid User who will receive the
     *                            notification UID or username.
     * @param string $template
     * @param string $href
     * @return mixed The decoded response object.
     */
    public function notificate($uid, $template, $href)
    {
        $path = '/' . $uid . '/notifications';
        $params = array(
            'template'     => $template,
            'href'         => $href,
            'access_token' => $this->getAppId() . '|' . $this->getAppSecret(),
        );

        return $this->_graph($path, self::METHOD_POST, $params);
    }

    /**
     * Publish directly to a profile's timeline without interaction
     * on the part of someone using the app.
     *
     * @param string $link
     * @param string $picture
     * @param string $title
     * @param string $description
     * @return mixed The decoded response object.
     */
    public function postToFeed($link, $picture, $title, $description)
    {
        $params = array(
            'link'        => $link,
            'picture'     => $picture,
            'name'        => $title,
            'description' => $description,
        );

        return $this->_graph('/me/feed', self::METHOD_POST, $params);
    }

    /**
     *
     * @param string $image
     * @param string $message
     * @return array|null
     */
    public function publishImage($image, $message)
    {
        $imageDir = realpath(dirname($image));
        $image = basename($image);

        if ($imageDir !== false && in_array('publish_stream', $this->_scope)) {
            $params = array(
                'message' => $message,
                'image'   => '@' . $imageDir . '/' . $image,
            );

            return $this->_graph('/me/photos', self::METHOD_POST, $params);
        }

        return null;
    }

    public function redirect($url)
    {
        $scriptFormat = '<script type="text/javascript">'
                      . 'top.location.href = "%s"'
                      . '</script>';
        $scriptTag = sprintf($scriptFormat, $url);

        die($scriptTag);
    }

    /**
     * @param $appUrl
     */
    public function setAppUrl($appUrl)
    {
        $this->_appUrl = $appUrl;
    }

    /**
     * @param $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->_redirectUrl = $redirectUrl;
    }

    /**
     *
     * @param $scope
     */
    public function setScope($scope)
    {
        $this->_scope = $scope;
    }

    /**
     *
     * @param unknown_type $imageId
     * @param unknown_type $userToTag
     * @param int $x The horizontal position of the tag, as a percentage from 0 to 100, from the left of the photo.
     * @param int $y The vertical position of the tag, as a percentage from 0 to 100, from the top of the photo.
     * @return bool
     */
    public function tagImage($imageId, $userToTag, $x = 50, $y = 50)
    {
        $urlFormat = 'https://graph.facebook.com/%s/tags/%s?access_token=%s'
                       . '&x=%d&y=%d&method=POST';
        $url = sprintf($urlFormat, $imageId, $userToTag,
                       $this->getAccessToken(), $x, $y);

        /**
         * @see \Dz_Http_Client
         */
        require_once 'Dz/Http/Client.php';

        $data = \Dz_Http_Client::getData($url);

        return $data === 'true';
    }
}