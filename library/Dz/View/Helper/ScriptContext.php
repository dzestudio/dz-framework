<?php

/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Helper for append Boilerplate.js context automatically in headScript.
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_View_Helper_ScriptContext extends \Zend_View_Helper_Abstract
{
    /**
     * @var \Zend_View_Interface
     */
    protected $_view;

    /**
     * Set the View object
     *
     * @param  \Zend_View_Interface $view
     * @return \Dz_View_Helper_ScriptContext
     */
    public function setView(\Zend_View_Interface $view)
    {
        $this->_view = $view;

        return $this;
    }

    public function scriptContext()
    {
        $script = "var Boilerplate = Boilerplate || {}; "
                . "Boilerplate.context = '/"
                . $this->_view->requestInfo()->getControllerName()
                . "/" . $this->_view->requestInfo()->getActionName() . "';";

        $this->_view->headScript()->appendScript($script);
    }
}