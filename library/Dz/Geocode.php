<?php
/**
 * DZ Framework
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

namespace Dz;

/**
 * Address to coordinates conversion class.
 *
 * This class uses Google Maps API to convert addresses
 * (like "1600 Amphitheatre Parkway, Mountain View, CA")
 * into geographic coordinates (like latitude 37.423021 and
 * longitude -122.083739), which you can use to
 * place markers or position a map.
 *
 * Usage:
 *
 * <code>
 * $address = 'Rua Vinte e Quatro de Outubro, 353';
 * $latLng = \Dz\Geocode::getLatLng($address);
 *
 * echo 'Latitude: ', $latLng->lat, PHP_EOL;
 * echo 'Longitude: ', $latLng->lng, PHP_EOL;
 * </code>
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author    LF Bittencourt <lf@dzestudio.com.br>
 */
class Geocode
{
    /**
     * Grabs contents from a URI.
     *
     * @param  string $uri
     * @return string|null
     */
    protected static function getContentsFromUri($uri)
    {
        $contents = null;

        if (($contents = @file_get_contents($uri)) === false) {
            $handler = curl_init();
            $defaultOptions = array(
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
            );

            curl_setopt($handler, CURLOPT_URL, $uri);
            curl_setopt_array($handler, $defaultOptions);

            $contents = curl_exec($handler);

            curl_close($handler);
        }

        return $contents;
    }

    /**
     * Converts address in latitude/longitude coordinates.
     *
     * @param  string $address The address, as complete as possible.
     * @return object Object containing "lat" and "lng" properties.
     * @assert (null) throws \InvalidArgumentException
     * @assert ('') throws \InvalidArgumentException
     * @assert (' ') throws \InvalidArgumentException
     */
    public static function getLatLng($address)
    {
        if (preg_match('/\S/', $address) === 0) {
            throw  new \InvalidArgumentException();
        }

        $uri = 'http://maps.googleapis.com/maps/api/geocode/json?address='
             . rawurlencode($address)
             . '&sensor=true';

        $data = self::getContentsFromUri($uri);
        $json = json_decode($data);

        return $json->results[0]->geometry->location;
    }
}
