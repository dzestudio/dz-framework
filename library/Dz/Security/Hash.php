<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Security
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id$
 */

/**
 * Hash generator class.
 *
 * Highly based on {@link http://goo.gl/ck7YV}.
 *
 * @category   Dz
 * @package    Dz_Security
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Security_Hash
{
    /**
     * Standard DES-based hash constant representation.
     *
     * @var string
     */
    const CRYPT_STD_DES  = 'CRYPT_STD_DES';

    /**
     * Extended DES-based hash constant representation.
     *
     * @var string
     */
    const CRYPT_EXT_DES  = 'CRYPT_EXT_DES';

    /**
     * MD5 hashing constant representation.
     *
     * @var string
     */
    const CRYPT_MD5      = 'CRYPT_MD5';

    /**
     * Blowfish hashing constant representation.
     *
     * @var string
     */
    const CRYPT_BLOWFISH = 'CRYPT_BLOWFISH';

    /**
     * SHA-256 hash constant representation.
     *
     * @var string
     */
    const CRYPT_SHA256   = 'CRYPT_SHA256';

    /**
     * SHA-512 constant representation.
     *
     * @var string
     */
    const CRYPT_SHA512   = 'CRYPT_SHA512';

    /**
     * Base-2 logarithm of how many iterations it will run
     * (10 => 2^10 = 1024 iterations) for CRYPT_BLOWFISH hashes.
     * This number can range between 04 and 31.
     *
     * @var integer
     */
    protected $_cost = 10;

    /**
     * Current hash type.
     *
     * @var string
     */
    protected $_cryptType = self::CRYPT_STD_DES;

    /**
     * Bunch of random characters to prevent exploits.
     *
     * @var string
     */
    protected $_saltBase = 'eeb95e4c295e0d9e77f523bab5ff81733fe222e74a5a10f8';

    /**
     * Salt lengths by hash type.
     *
     * @var array
     */
    protected $_saltLengths = array(
        self::CRYPT_STD_DES  => 2,
        self::CRYPT_EXT_DES  => 9,
        self::CRYPT_MD5      => 9,
        self::CRYPT_BLOWFISH => 22,
        self::CRYPT_SHA256   => 48,
        self::CRYPT_SHA512   => 48,
    );

    /**
     * Public constructor.
     *
     * @param string $cryptType
     * @param string $saltBase
     * @param integer $cost
     */
    public function __construct($cryptType = self::CRYPT_STD_DES,
        $saltBase = null, $cost = 10)
    {
        $this->setCryptType($cryptType)
             ->setSaltBase($saltBase)
             ->setCost($cost);
    }

    /**
     * Generates random salt base based on choosen hash type.
     */
    protected function _generateSaltBase()
    {
        $saltLength = $this->_saltLengths[$this->_cryptType];
        $salt = '';

        while (strlen($salt) < $saltLength) {
            $salt .= sha1(mt_rand());
        }

        $this->_saltBase = substr($salt, 0, $saltLength);
    }

    /**
     * Gets a salt string to base the hashing on.
     *
     * @return string
     */
    protected function _getSalt()
    {
        switch ($this->_cryptType) {
            case self::CRYPT_EXT_DES:

                return str_pad($this->_saltBase, 9);

            case self::CRYPT_MD5:

                $format = '$1$%-9.9s';

                return sprintf($format, $this->_saltBase);

            case self::CRYPT_BLOWFISH:

                $format = "$2a$%02.2s%-'z22.22s";

                return sprintf($format, $this->_cost, $this->_saltBase);

            case self::CRYPT_SHA256:
            case self::CRYPT_SHA512:

                $cost = pow(2, $this->_cost);
                $length = 60 - 10 - strlen($cost) - 1;
                $saltBase = $this->_padRight($this->_saltBase, $length);
                $prefix = $this->_cryptType === self::CRYPT_SHA256 ? 5 : 6;
                $format = '$%d$rounds=%d$%s';

                return sprintf($format, $prefix, $cost, $saltBase);

            default:

                return str_pad($this->_saltBase, 2);
        }
    }

    /**
     * Cuts a value properly to use as salt base.
     *
     * @param string $value
     * @param integer $length
     * @return string The formatted value.
     */
    protected function _padRight($value, $length)
    {
        $length = abs(intval($length));
        $format = sprintf('%%-%d.%ds', $length, $length);

        return sprintf($format, $value);
    }

    /**
     * Gets cost.
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->_cost;
    }

    /**
     * Sets cost.
     *
     * @param  integer $cost
     * @return \Dz_Security_Hash Provides fluent interface.
     */
    public function setCost($cost)
    {
        if ($cost >= 4 && $cost <= 31) {
            $this->_cost = $cost;
        }

        return $this;
    }

    /**
     * Gets current hash type.
     *
     * @return string
     */
    public function getCryptType()
    {
        return $this->_cryptType;
    }

    /**
     * Sets hash type.
     *
     * @param  string $cryptType
     * @return \Dz_Security_Hash Provides fluent interface.
     */
    public function setCryptType($cryptType)
    {
        if (($cryptType === self::CRYPT_STD_DES
            || $cryptType === self::CRYPT_EXT_DES
            || $cryptType === self::CRYPT_MD5
            || $cryptType === self::CRYPT_BLOWFISH
            || $cryptType === self::CRYPT_SHA256
            || $cryptType === self::CRYPT_SHA512)
            && defined($cryptType)
            && constant($cryptType) === 1
        ) {
            $this->_cryptType = $cryptType;
        }

        return $this;
    }

    /**
     * Gets salt base.
     *
     * @return string
     */
    public function getSaltBase()
    {
        return $this->_saltBase;
    }

    /**
     * Sets salt base.
     *
     * @param string|null $saltBase If null, it will be automatically generated.
     * @return \Dz_Security_Hash Provides fluent interface.
     */
    public function setSaltBase($saltBase = null)
    {
        if (!empty($saltBase) && strlen($saltBase) >= 2) {
            $this->_saltBase = $saltBase;
        } else {
            $this->_generateSaltBase();
        }

        return $this;
    }

    /**
     * Compares a value against its hash.
     *
     * @param  string $hashValue
     * @param  string $value
     * @return boolean True of value equals hash, false otherwise.
     */
    public function check($hashValue, $value)
    {
        return $hashValue === $this->crypt($value);
    }

    /**
     * Crypts a value.
     *
     * @param  string $value
     * @return string The hashed string.
     */
    public function crypt($value)
    {
        return crypt($value, $this->_getSalt());
    }
}