<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Filter
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_Filter
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Filter_StripSmartQuotes implements \Zend_Filter_Interface
{
    /**
     * Defined by Zend_Filter_Interface
     * Based in http://www.toao.net/48-replacing-smart-quotes-and-em-dashes-in-mysql.
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        return str_replace(
            array(chr(145), chr(146), chr(147),
                  chr(148), chr(150), chr(151), chr(133)),
            array("'", "'", '"', '"', '-', '--', '...'), $value
        );
    }
}