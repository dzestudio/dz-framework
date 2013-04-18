<?php

/**
 * Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

class Dz_View_Helper_MaskNumber extends Zend_View_Helper_Abstract
{
    /**
     * @param string $number
     * @param string $mask
     */
    public function maskNumber($number, $mask)
    {
        if (!is_string($mask)) {
            return $number;
        }

        $number = preg_replace('/\D+/', '', (string) $number);
        $digitsCount = strlen(preg_replace('/\D+/', '', $mask));

        if (strlen($number) > $digitsCount) {
            $number = substr($number, 0, $digitsCount);
        } else {
            $number = str_pad($number, $digitsCount, '0', STR_PAD_LEFT);
        }

        $number = preg_split('//', $number, -1, PREG_SPLIT_NO_EMPTY);
        $mask = preg_split('//', $mask, -1, PREG_SPLIT_NO_EMPTY);
        $buffer = '';

        while (count($mask) > 0) {
            $maskChar = array_shift($mask);

            if (preg_match('/^\d$/', $maskChar) > 0) {
                $buffer .= array_shift($number);
            } else {
                $buffer .= $maskChar;
            }
        }

        return $buffer;
    }
}