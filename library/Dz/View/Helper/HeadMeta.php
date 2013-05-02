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
 * @see \Zend_View_Helper_HeadMeta
 */
require_once 'Zend/View/Helper/HeadMeta.php';

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_View_Helper_HeadMeta extends \Zend_View_Helper_HeadMeta
{
    /**
     * Determine if item is valid
     *
     * @param  mixed $item
     * @return boolean
     */
    protected function _isValid($item)
    {
        if ((!$item instanceof stdClass)
            || !isset($item->type)
            || !isset($item->modifiers)
        ) {
            return false;
        }

        if (!isset($item->content)
            && (!$this->view->doctype()->isHtml5()
            || (!$this->view->doctype()->isHtml5() && $item->type !== 'charset'))
        ) {
            return false;
        }

        return true;
    }
}