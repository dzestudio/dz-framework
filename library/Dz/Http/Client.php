<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Http
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id$
 */

/**
 * Provides common methods for receiving data from a URI.
 *
 * @category   Dz
 * @package    Dz_Http
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Http_Client
{
    /**
     * Downloads the contents of the $uri.
     *
     * @param   string $uri
     * @return  string|null
     */
    public static function getData($uri)
    {
        $data = null;

        if (($data = @file_get_contents($uri)) === false) {
            $handler = curl_init();
            $timeout = 5;

            curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($handler, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handler, CURLOPT_URL, $uri);
            curl_setopt($handler, CURLOPT_VERBOSE, true);

            $data = curl_exec($handler);

            curl_close($handler);
        }

        return $data;
    }
}