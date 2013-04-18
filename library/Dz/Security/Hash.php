<?php

/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Security
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_Security
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Security_Hash
{
    const CRYPT_STD_DES  = 'CRYPT_STD_DES';
    const CRYPT_EXT_DES  = 'CRYPT_EXT_DES';
    const CRYPT_MD5      = 'CRYPT_MD5';
    const CRYPT_BLOWFISH = 'CRYPT_BLOWFISH';
    const CRYPT_SHA256   = 'CRYPT_SHA256';
    const CRYPT_SHA512   = 'CRYPT_SHA512';

    protected $_cost = 10;
    protected $_cryptType = CRYPT_STD_DES;
    protected $_saltBase = 'eeb95e4c295e0d9e77f523bab5ff81733fe222e74a5a10f8';
    protected $_saltLengths = array(
        self::CRYPT_STD_DES  => 2,
        self::CRYPT_EXT_DES  => 9,
        self::CRYPT_MD5      => 9,
        self::CRYPT_BLOWFISH => 22,
        self::CRYPT_SHA256   => 48,
        self::CRYPT_SHA512   => 48,
    );

    public function __construct($cryptType = self::CRYPT_STD_DES,
        $saltBase = null, $cost = 10)
    {
        $this->setCryptType($cryptType)
             ->setSaltBase($saltBase)
             ->setCost($cost);
    }

    protected function _generateSaltBase()
    {
        $saltLength = $this->_saltLengths[$this->_cryptType];
        $salt = '';

        while (strlen($salt) < $saltLength) {
            $salt .= sha1(mt_rand());
        }

        $this->_saltBase = substr($salt, 0, $saltLength);
    }

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

    protected function _padRight($value, $length)
    {
        $length = abs(intval($length));
        $format = sprintf('%%-%d.%ds', $length, $length);

        return sprintf($format, $value);
    }

    public function getCost()
    {
        return $this->_cost;
    }

    public function setCost($cost)
    {
        if ($cost >= 4 && $cost <= 31) {
            $this->_cost = $cost;
        }

        return $this;
    }

    public function getCryptType()
    {
        return $this->_cryptType;
    }

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

    public function getSaltBase()
    {
        return $this->_saltBase;
    }

    public function setSaltBase($saltBase = null)
    {
        if (!empty($saltBase) && strlen($saltBase) >= 2) {
            $this->_saltBase = $saltBase;
        } else {
            $this->_generateSaltBase();
        }

        return $this;
    }

    public function check($hashValue, $value)
    {
        return $hashValue ===  $this->crypt($value);
    }

    public function crypt($value)
    {
        return crypt($value, $this->_getSalt());
    }
}