<?php

namespace Dz\Test\Maps;

use Dz\Maps\Converter;
use PHPUnit_Framework_TestCase as TestCase;

class ConverterTest extends TestCase
{
    /**
     * @covers Converter::fromAddressToLatLng
     */
    public function testFromAddressToLatLng()
    {
        $address = 'Rua Vinte e Quatro de Outubro, 353';
        $latLng = Converter::fromAddressToLatLng($address);

        $this->assertEquals(-30.0272452, $latLng->lat);
        $this->assertEquals(-51.2041206, $latLng->lng);
    }

    /**
     * @covers Converter::fromAddressToLatLng
     */
    public function testFromAddressToLatLngWithIntegerAddress()
    {
        $address = 123;
        $latLng = Converter::fromAddressToLatLng($address);

        $this->assertInternalType('float', $latLng->lat);
        $this->assertInternalType('float', $latLng->lng);
    }

    /**
     * Generated from @assert (null) throws \InvalidArgumentException.
     *
     * @covers Converter::fromAddressToLatLng
     * @expectedException \InvalidArgumentException     */
    public function testFromAddressToLatLngWithNullAddress()
    {
        Converter::fromAddressToLatLng(null);
    }

    /**
     * Generated from @assert ('') throws \InvalidArgumentException.
     *
     * @covers Converter::fromAddressToLatLng
     * @expectedException \InvalidArgumentException     */
    public function testFromAddressToLatLngWithEmptyAddress()
    {
        Converter::fromAddressToLatLng('');
    }

    /**
     * Generated from @assert (' ') throws \InvalidArgumentException.
     *
     * @covers Converter::fromAddressToLatLng
     * @expectedException \InvalidArgumentException     */
    public function testFromAddressToLatLngWithSpaceAddress()
    {
        Converter::fromAddressToLatLng(' ');
    }

    /**
     * @covers Converter::fromDmsToDecimal
     */
    public function testFromDmsToDecimalLat()
    {
        $dms = "30° 2' 54.0276'' S";

        $this->assertEquals(-30.048341, Converter::fromDmsToDecimal($dms));
    }

    /**
     * @covers Converter::fromDmsToDecimal
     */
    public function testFromDmsToDecimalLng()
    {
        $dms = "52° 53' 22.4592'' W";

        $this->assertEquals(-52.889572, Converter::fromDmsToDecimal($dms));
    }

    /**
     * @covers Converter::fromDmsToDecimal
     * @expectedException \InvalidArgumentException
     */
    public function testFromDmsToDecimalWithNullDms()
    {
        Converter::fromDmsToDecimal(null);
    }

    /**
     * @covers Converter::fromDmsToDecimal
     * @expectedException \InvalidArgumentException
     */
    public function testFromDmsToDecimalWithEmptyDms()
    {
        Converter::fromDmsToDecimal('');
    }

    /**
     * @covers Converter::fromDmsToDecimal
     * @expectedException \InvalidArgumentException
     */
    public function testFromDmsToDecimalWithSpaceDms()
    {
        Converter::fromDmsToDecimal(' ');
    }
}
