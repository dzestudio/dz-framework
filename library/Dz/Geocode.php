<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Geocode
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id$
 */

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
 * $latLng = \Dz_Geocode::getLatLng($address);
 *
 * echo 'Latitude: ', $latLng->lat, PHP_EOL;
 * echo 'Longitude: ', $latLng->lng, PHP_EOL;
 * </code>
 *
 * @category   Dz
 * @package    Dz_Geocode
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Geocode
{
    /**
     * Converts address in latitude/longitude coordinates.
     *
     * @uses   \Dz_Http_Client::getData()
     * @param  string $address The address, as complete as possible.
     * @return object Object containing "lat" and "lng" properties.
     */
    public static function getLatLng($address)
    {
        $uri = 'http://maps.googleapis.com/maps/api/geocode/json?address='
             . rawurlencode($address)
             . '&sensor=true';

        /**
         * @see \Dz_Http_Client
         */
        require_once 'Dz/Http/Client.php';

        $data = \Dz_Http_Client::getData($uri);
        $json = json_decode($data);

        return $json->results[0]->geometry->location;
    }
}