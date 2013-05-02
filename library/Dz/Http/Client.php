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
        	$curlHandler = curl_init();
        	$timeout = 5;

            curl_setopt($curlHandler, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curlHandler, CURLOPT_FOLLOWLOCATION, true);
        	curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandler, CURLOPT_URL, $uri);

        	$data = curl_exec($curlHandler);

        	curl_close($curlHandler);
        }

        return $data;
    }
}