<?php
namespace Test\Dz;

include_once(__DIR__ . '/../../vendor/autoload.php');

use Dz\Geocode;
use PHPUnit_Framework_TestCase;

class GeocodeTest extends PHPUnit_Framework_TestCase
{
    public function testGetLatLngDzAddressShouldBeOK()
    {
        $address = 'Rua Vinte e Quatro de Outubro, 353';
        $latLng = \Dz\Geocode::getLatLng($address);

        $this->assertEquals(-30.0272452, $latLng->lat);
        $this->assertEquals(-51.2041206, $latLng->lng);
    }

    public function testGetLatLngIntegerShouldBeOK()
    {
        $address = 1;
        $latLng = \Dz\Geocode::getLatLng($address);

        $this->assertStringMatchesFormat('%f', (string)$latLng->lat);
        $this->assertStringMatchesFormat('%f', (string)$latLng->lng);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetLatLngEmptyShouldBeInvalidArgumentException()
    {
        $address = '';
        $latLng = \Dz\Geocode::getLatLng($address);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetLatLngSpaceShouldBeInvalidArgumentException()
    {
        $address = ' ';
        $latLng = \Dz\Geocode::getLatLng($address);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetLatLngNullShouldBeInvalidArgumentException()
    {
        $latLng = \Dz\Geocode::getLatLng(null);
    }

}