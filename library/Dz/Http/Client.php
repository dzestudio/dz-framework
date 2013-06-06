<?php
/**
 * DZ Framework
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

namespace Dz\Http;

/**
 * Provides common methods for receiving data from a URI.
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author    LF Bittencourt <lf@dzestudio.com.br>
 */
class Client
{
    /**
     * Downloads the contents of the $uri.
     *
     * @param  string $uri
     * @param  array $options cURL options.
     * @return string|null
     */
    public static function getData($uri, array $options = array())
    {
        $data = null;

        if (count($options) > 0 || ($data = @file_get_contents($uri)) === false)
        {
            $handler = curl_init();

            curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($handler, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handler, CURLOPT_URL, $uri);
            curl_setopt_array($handler, $options);

            $data = curl_exec($handler);

            curl_close($handler);
        }

        return $data;
    }
}
