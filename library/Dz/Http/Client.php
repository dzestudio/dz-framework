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
     * @param  array $extraOptions cURL extra options.
     * @return string|null
     */
    public static function getData($uri, array $extraOptions = array())
    {
        $data = null;

        // If is just a GET, tries no-cURL calls.
        if (count($extraOptions) > 0 ||
            ($data = @file_get_contents($uri)) === false
        ) {
            $handler = curl_init();
            $timeout = 5;

            curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($handler, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handler, CURLOPT_URL, $uri);

            foreach ($extraOptions as $option => $value) {
                curl_setopt($handler, $option, $value);
            }

            $data = curl_exec($handler);

            curl_close($handler);
        }

        return $data;
    }
}