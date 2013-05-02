<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Validate
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Dz
 * @package    Dz_Validate
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Validate_Cnpj extends \Zend_Validate_Abstract
{
    const MSG_LENGTH = 'msgLength';
    const MSG_ZEROS = 'msgZeros';
    const MSG_FIRST_CHECK_DIGIT = 'firstCheckDigit';
    const MSG_SECOND_CHECK_DIGIT = 'secondCheckDigit';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::MSG_LENGTH             => "'%value%' não tem 14 dígitos",
        self::MSG_ZEROS              => "O CNPJ não pode ser composto apenas de zeros",
        self::MSG_FIRST_CHECK_DIGIT  => "O primeiro dígito verificador está incorreto",
        self::MSG_SECOND_CHECK_DIGIT => "O segundo dígito verificador está incorreto"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if $value is a valid CNPJ
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        $value = preg_replace('/\D/', '', $value);

        if (strlen($value) != 14) {
            $this->_error(self::MSG_LENGTH);

            return false;
        }

        if ($value === '00000000000000') {
            $this->_error(self::MSG_ZEROS);

            return false;
        }

        $length = strlen($value) - 2;
        $root = substr($value, 0, $length);
        $digits = substr($value, $length);
        $index = $length - 7;
        $sum = 0;

        for ($i = $length; $i >= 1; $i--) {
            $sum += $root[$length - $i] * $index--;

            if ($index < 2) {
                $index = 9;
            }
        }

        $result = (($sum % 11) < 2) ? 0 : (11 - ($sum % 11));

        if ($result != $digits[0]) {
            $this->_error(self::MSG_FIRST_CHECK_DIGIT);

            return false;
        }

        $length++;

        $root = substr($value, 0, $length);
        $sum = 0;
        $index = $length - 7;

        for ($i = $length; $i >= 1; $i--) {
            $sum += $root[$length - $i] * $index--;

            if ($index < 2) {
                $index = 9;
            }
        }

        $result = (($sum % 11) < 2) ? 0 : (11 - ($sum % 11));

        if ($result != $digits[1]) {
            $this->_error(self::MSG_SECOND_CHECK_DIGIT);

            return false;
        }

        return true;
    }
}