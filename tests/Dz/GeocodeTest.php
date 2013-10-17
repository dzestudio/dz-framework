<?php

namespace Dz\Test;

use Dz\Geocode;
use PHPUnit_Framework_TestCase as TestCase;

class GeocodeTest extends TestCase
{
    /**
     * @covers Geocode::getLatLng
     */
    public function testGetLatLng()
    {
        $address = 'Rua Vinte e Quatro de Outubro, 353';
        $latLng = Geocode::getLatLng($address);

        $this->assertEquals(-30.0272452, $latLng->lat);
        $this->assertEquals(-51.2041206, $latLng->lng);
    }

    /**
     * @covers Geocode::getLatLng
     */
    public function testGetLatLngWithIntegerAddress()
    {
        $address = 123;
        $latLng = Geocode::getLatLng($address);

        $this->assertInternalType('float', $latLng->lat);
        $this->assertInternalType('float', $latLng->lng);
    }

    /**
     * Generated from @assert (null) throws \InvalidArgumentException.
     *
     * @covers Geocode::getLatLng
     * @expectedException \InvalidArgumentException     */
    public function testGetLatLngWithNullAddress()
    {
        Geocode::getLatLng(null);
    }

    /**
     * Generated from @assert ('') throws \InvalidArgumentException.
     *
     * @covers Geocode::getLatLng
     * @expectedException \InvalidArgumentException     */
    public function testGetLatLngWithEmptyAddress()
    {
        Geocode::getLatLng('');
    }

    /**
     * Generated from @assert (' ') throws \InvalidArgumentException.
     *
     * @covers Geocode::getLatLng
     * @expectedException \InvalidArgumentException     */
    public function testGetLatLngWithSpaceAddress()
    {
        Geocode::getLatLng(' ');
    }
}
