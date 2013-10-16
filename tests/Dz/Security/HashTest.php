<?php
namespace Test\Dz;

include_once(__DIR__ . '/../../../vendor/autoload.php');

use Dz\Security;
use PHPUnit_Framework_TestCase;
use Dz\Security\Hash;

class HashTest extends PHPUnit_Framework_TestCase
{
    public function testCryptSaltBaseShouldBeEquals()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';

        $saltBase = md5('Kynodontas#' . $email);

        $hash = new Hash(array('saltBase' => $saltBase));

        $passwordHash = $hash->crypt($password);

        $this->assertEquals('$6$rounds=1024$e70f3198ffd1a251$xwqXIj.23nQqP9W7soD3rPeM7.l4vdlz4S3kgLQNI7A0N1EAz6CGqnFtSh4a/zIEukN8Fq/925a44d5rBN7aC0', $passwordHash);
    }

    public function testCryptMissingArgumentsShouldBeEquals()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';

        $hash = new Hash();

        $passwordHash = $hash->crypt($password);

        $this->assertEquals('$6$rounds=1024$                $2S6IJfrNGbrVoRYVKYOHMAtY8mZyxSKklK/jFKM9KfVMQYl7KYEavOG0AH2N/3Fowwwq/mEuZjQf.PHg/IUq5.', $passwordHash);
    }

    /**
     * @expectedException Exception
     */
    public function testCryptNullArgumentsShouldBeException()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';

        $hash = new Hash(null);

        $passwordHash = $hash->crypt($password);
    }

    public function testCryptCryptTypeMD5ShouldBeEquals()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';

        $hash = new Hash(array('cryptType' => Hash::CRYPT_MD5));

        $passwordHash = $hash->crypt($password);

        $this->assertEquals('$1$        $gxDpno6N7vhrpX11TmTgG1', $passwordHash);
    }

    public function testCheckSaltBaseShouldBeTrue()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';

        $saltBase = md5('Kynodontas#' . $email);

        $hash = new Hash(array('saltBase' => $saltBase));

        $passwordHash = $hash->crypt($password);

        $this->assertTrue($hash->check($passwordHash, $password));
    }

    public function testCheckSaltBaseShouldBeFalse()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';
        $wrongPassword = 'wR0NgP4S5W0Rd!';

        $saltBase = md5('Kynodontas#' . $email);

        $hash = new Hash(array('saltBase' => $saltBase));

        $passwordHash = $hash->crypt($password);

        $this->assertFalse($hash->check($passwordHash, $wrongPassword));
    }

    public function testCheckMissingArgumentsShouldBeTrue()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';

        $saltBase = md5('Kynodontas#' . $email);

        $hash = new Hash();

        $passwordHash = $hash->crypt($password);

        $this->assertTrue($hash->check($passwordHash, $password));
    }

    public function testCheckMissingArgumentsShouldBeFalse()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';
        $wrongPassword = 'wR0NgP4S5W0Rd!';

        $saltBase = md5('Kynodontas#' . $email);

        $hash = new Hash();

        $passwordHash = $hash->crypt($password);

        $this->assertFalse($hash->check($passwordHash, $wrongPassword));
    }

    public function testCheckCryptTypeMD5ShouldBeTrue()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';

        $saltBase = md5('Kynodontas#' . $email);

        $hash = new Hash(array('cryptType' => Hash::CRYPT_MD5));

        $passwordHash = $hash->crypt($password);

        $this->assertTrue($hash->check($passwordHash, $password));
    }

    public function testCheckCryptTypeMD5ShouldBeFalse()
    {
        $email = 'example@example.com';
        $password = 'mYs3cR3tP4S5W0Rd!';
        $wrongPassword = 'wR0NgP4S5W0Rd!';

        $saltBase = md5('Kynodontas#' . $email);

        $hash = new Hash();

        $passwordHash = $hash->crypt($password);

        $this->assertFalse($hash->check($passwordHash, $wrongPassword));
    }
}

