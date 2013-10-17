<?php

namespace Dz\Test\Security;

use Dz\Security\Hash;
use PHPUnit_Framework_TestCase as TestCase;

class HashTest extends TestCase
{
    /**
     * @var Hash
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Hash;
    }

    /**
     * @covers Hash::check
     */
    public function testCheck()
    {
        $password = 'mYs3cR3tP4S5W0Rd!';
        $passwordHash = $this->object->crypt($password);

        $this->assertTrue($this->object->check($passwordHash, $password));
    }

    /**
     * @covers Hash::check
     */
    public function testCheckWithWrongPassword()
    {
        $password = 'mYs3cR3tP4S5W0Rd!';
        $wrongPassword = 'wR0NgP4S5W0Rd!';
        $passwordHash = $this->object->crypt($password);

        $this->assertFalse($this->object->check($passwordHash, $wrongPassword));
    }

    /**
     * @covers Hash::check
     */
    public function testCheckWithMd5CryptType()
    {
        $this->object->setCryptType(Hash::CRYPT_MD5);

        $password = 'mYs3cR3tP4S5W0Rd!';
        $passwordHash = $this->object->crypt($password);

        $this->assertTrue($this->object->check($passwordHash, $password));
    }

    /**
     * @covers Hash::crypt
     */
    public function testCrypt()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';
        $saltBase = md5('Kynodontas#' . $email);

        $this->object->setSaltBase($saltBase);

        $expectedHash = '$6$rounds=1024$e70f3198ffd1a251$xwqXIj.23nQqP9W7soD3rP'
                      . 'eM7.l4vdlz4S3kgLQNI7A0N1EAz6CGqnFtSh4a/zIEukN8Fq/925a4'
                      . '4d5rBN7aC0';

        $passwordHash = $this->object->crypt($password);

        $this->assertEquals($expectedHash, $passwordHash);
    }

    /**
     * @covers Hash::crypt
     */
    public function testCryptWithMd5CryptType()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';
        $saltBase = md5('Kynodontas#' . $email);

        $this->object->setSaltBase($saltBase)
                     ->setCryptType(Hash::CRYPT_MD5);

        $expectedHash = '$1$e70f3198$nzOR4gCS4GV31U5a1ejis1';
        $passwordHash = $this->object->crypt($password);

        $this->assertEquals($expectedHash, $passwordHash);
    }

    /**
     * @covers Hash::getCost
     */
    public function testGetCost()
    {
        $this->object->setCost(10);

        $this->assertEquals(10, $this->object->getCost());
    }

    /**
     * @covers Hash::getCryptType
     */
    public function testGetCryptType()
    {
        $this->object->setCryptType(Hash::CRYPT_BLOWFISH);

        $this->assertEquals(Hash::CRYPT_BLOWFISH, $this->object->getCryptType());
    }

    /**
     * @covers Hash::getSaltBase
     */
    public function testGetSaltBase()
    {
        $this->object->setSaltBase('saltbase1');

        $this->assertEquals('saltbase1', $this->object->getSaltBase());
    }

    /**
     * @covers Hash::setCost
     */
    public function testSetCost()
    {
        $this->object->setCost(5);

        $this->assertEquals(5, $this->object->getCost());
    }

    /**
     * @covers Hash::setCost
     * @expectedException \InvalidArgumentException
     */
    public function testSetCostWithNullCost()
    {
        $this->object->setCost(null);
    }

    /**
     * @covers Hash::setCost
     * @expectedException \InvalidArgumentException
     */
    public function testSetCostWithEmptyCost()
    {
        $this->object->setCost('');
    }

    /**
     * @covers Hash::setCost
     * @expectedException \InvalidArgumentException
     */
    public function testSetCostWithSpaceCost()
    {
        $this->object->setCost(' ');
    }

    /**
     * @covers Hash::setCost
     * @expectedException \InvalidArgumentException
     */
    public function testSetCostWithTooShortCost()
    {
        $this->object->setCost(1);
    }

    /**
     * @covers Hash::setCost
     * @expectedException \InvalidArgumentException
     */
    public function testSetCostWithTooLargeCost()
    {
        $this->object->setCost(35);
    }

    /**
     * @covers Hash::setCryptType
     */
    public function testSetCryptType()
    {
        $this->object->setCryptType(Hash::CRYPT_EXT_DES);

        $this->assertEquals(Hash::CRYPT_EXT_DES, $this->object->getCryptType());
    }

    /**
     * @covers Hash::setCryptType
     * @expectedException \InvalidArgumentException
     */
    public function testSetCryptTypeWithNullCryptType()
    {
        $this->object->setCryptType(null);
    }

    /**
     * @covers Hash::setCryptType
     * @expectedException \InvalidArgumentException
     */
    public function testSetCryptTypeWithEmptyCryptType()
    {
        $this->object->setCryptType('');
    }

    /**
     * @covers Hash::setCryptType
     * @expectedException \InvalidArgumentException
     */
    public function testSetCryptTypeWithSpaceCryptType()
    {
        $this->object->setCryptType(' ');
    }

    /**
     * @covers Hash::setCryptType
     * @expectedException \InvalidArgumentException
     */
    public function testSetCryptTypeWithInvalidCryptType()
    {
        $this->object->setCryptType('CRYPT_MD6');
    }

    /**
     * @covers Hash::setSaltBase
     */
    public function testSetSaltBase()
    {
        $this->object->setSaltBase('saltbase2');

        $this->assertEquals('saltbase2', $this->object->getSaltBase());
    }

    /**
     * @covers Hash::setSaltBase
     * @expectedException \InvalidArgumentException
     */
    public function testSetSaltBaseWithTooShortSaltBase()
    {
        $this->object->setSaltBase('s');
    }
}
