<?php

/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Validate
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Dz
 * @package    Dz_Validate
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Validate_Cpf extends Zend_Validate_Abstract
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
        self::MSG_LENGTH => "'%value%' não tem 11 dígitos",
        self::MSG_ZEROS => "O CPF não pode ser composto apenas de zeros",
        self::MSG_FIRST_CHECK_DIGIT => "O primeiro dígito verificador está incorreto",
        self::MSG_SECOND_CHECK_DIGIT => "O segundo dígito verificador está incorreto"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if $value is a valid CPF
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        $cpf = preg_replace('/\D/', '', $value);

        if (strlen($cpf) != 11) {
            $this->_error(self::MSG_LENGTH);

            return false;
        }

        if ($cpf === '00000000000') {
            $this->_error(self::MSG_ZEROS);

            return false;
        }

        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }

        $mod = $sum % 11;
        $digit = ($mod > 1) ? (11 - $mod) : 0;

        if ($cpf[9] != $digit) {
            $this->_error(self::MSG_FIRST_CHECK_DIGIT);

            return false;
        }

        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }

        $mod = $sum % 11;
        $digit = ($mod > 1) ? (11 - $mod) : 0;

        if ($cpf[10] != $digit) {
            $this->_error(self::MSG_SECOND_CHECK_DIGIT);

            return false;
        }

        return true;
    }
}