<?php
/**
 * DZ Framework
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

namespace Dz\Security;

/**
 * Hash generator class.
 *
 * Highly based on {@link http://goo.gl/ck7YV}.
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author    LF Bittencourt <lf@dzestudio.com.br>
 */
class Hash
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
    protected $cost = 10;

    /**
     * Current hash type.
     *
     * @var string
     */
    protected $cryptType = self::CRYPT_SHA512;

    /**
     * Bunch of random characters to prevent exploits.
     *
     * @var string
     */
    protected $saltBase;

    /**
     * Salt lengths by hash type.
     *
     * @var array
     */
    protected $saltLengths = array(
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
     * @param array $options $cryptType Can contain cryptType, saltBase
     *                                  and cost options.
     */
    public function __construct(array $options = array())
    {
        if (isset($options['cost'])) {
            $this->setCost($options['cost']);
        }

        if (isset($options['cryptType'])) {
            $this->setCryptType($options['cryptType']);
        }

        if (isset($options['saltBase'])) {
            $this->setSaltBase($options['saltBase']);
        }
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
        return crypt($value, $this->getSalt());
    }

    /**
     * Generates random salt base based on choosen hash type.
     *
     * @return string
     */
    protected function generateSaltBase()
    {
        $saltLength = $this->saltLengths[$this->cryptType];
        $salt = '';

        while (strlen($salt) < $saltLength) {
            $salt .= sha1(mt_rand());
        }

        return substr($salt, 0, $saltLength);
    }

    /**
     * Gets cost.
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Gets current hash type.
     *
     * @return string
     */
    public function getCryptType()
    {
        return $this->cryptType;
    }

    /**
     * Gets a salt string to base the hashing on.
     *
     * @return string
     */
    protected function getSalt()
    {
        $saltBase = $this->getSaltBase();

        switch ($this->cryptType) {
            case self::CRYPT_EXT_DES:
                return str_pad($saltBase, 9);
            case self::CRYPT_MD5:
                $format = '$1$%-9.9s';

                return sprintf($format, $saltBase);
            case self::CRYPT_BLOWFISH:
                $format = "$2a$%02.2s%-'z22.22s";

                return sprintf($format, $this->cost, $saltBase);
            case self::CRYPT_SHA256:
            case self::CRYPT_SHA512:
                $cost = pow(2, $this->cost);
                $length = 60 - 10 - strlen($cost) - 1;
                $saltBase = $this->padRight($saltBase, $length);
                $prefix = $this->cryptType === self::CRYPT_SHA256 ? 5 : 6;
                $format = '$%d$rounds=%d$%s';

                return sprintf($format, $prefix, $cost, $saltBase);
            default:
                return str_pad($saltBase, 2);
        }
    }

    /**
     * Gets salt base.
     * If salt base is still null, it will be automatically generated.
     *
     * @return string
     */
    public function getSaltBase()
    {
        if ($this->saltBase === null) {
            $this->saltBase = $this->generateSaltBase();
        }

        return $this->saltBase;
    }

    /**
     * Cuts a value properly to use as salt base.
     *
     * @param  string $value
     * @param  integer $length
     * @return string The formatted value.
     */
    protected function padRight($value, $length)
    {
        $length = abs(intval($length));
        $format = sprintf('%%-%d.%ds', $length, $length);

        return sprintf($format, $value);
    }

    /**
     * Sets cost.
     *
     * @param  integer $cost
     * @return Hash Provides fluent interface.
     * @throws \InvalidArgumentException If cost is not in 4-31 range.
     */
    public function setCost($cost)
    {
        $cost = (int) $cost;

        if ($cost < 4 || $cost > 31) {
            throw new \InvalidArgumentException(
                'Cost must be an integer between 4 and 31.'
            );
        }

        $this->cost = $cost;

        return $this;
    }

    /**
     * Sets hash type.
     *
     * @param  string $cryptType
     * @return Hash Provides fluent interface.
     * @throws \InvalidArgumentException If crypt type is not valid.
     */
    public function setCryptType($cryptType)
    {
        if (!array_key_exists($cryptType, $this->saltLengths)
            || !defined($cryptType)
            || constant($cryptType) !== 1
        ) {
            $message = sprintf('"%s" is not a valid crypt type.', $cryptType);

            throw new \InvalidArgumentException($message);
        }

        $this->cryptType = $cryptType;

        return $this;
    }

    /**
     * Sets salt base.
     *
     * @param  string $saltBase
     * @return Hash Provides fluent interface.
     * @throws \InvalidArgumentException If salt base is too short.
     */
    public function setSaltBase($saltBase)
    {
        if (strlen($saltBase) < 2) {
            throw new \InvalidArgumentException('Minimum salt base length is 2.');
        }

        $this->saltBase = $saltBase;

        return $this;
    }
}
