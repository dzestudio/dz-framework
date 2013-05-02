<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_View_Helper_Truncate extends \Zend_View_Helper_Abstract
{
    /**
     * @param string $value
     * @param array $options \Dz_Filter_StringTruncate options.
     * @return string The truncated value.
     */
    public function truncate($value, array $options = array())
    {
        $truncate = new \Dz_Filter_StringTruncate($options);

        return $truncate->filter($value);
    }
}