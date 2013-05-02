<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Controller
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';

/**
 * Provides extra funcionality to Zend_Controller_Action.
 *
 * @category   Dz
 * @package    Dz_Controller
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Controller_Action extends \Zend_Controller_Action
{
    /**
     * Caches _isDirectAccess() result.
     *
     * @see \Dz_Controller_Action::_isDirectAccess()
     * @var bool
     */
    protected $_isDirectAccess;

    /**
     * Checks if current request is not called by action view helper.
     * Depends on Dz_Controller_Plugin_ViewSetup.
     *
     * @see \Dz_Controller_Plugin_ViewSetup
     * @return bool
     */
    protected function _isDirectAccess()
    {
        if ($this->_isDirectAccess === null) {
            $this->_isDirectAccess = $this->getRequest()->getModuleName() === $this->view->moduleName
                && $this->getRequest()->getControllerName() === $this->view->controllerName
                && $this->getRequest()->getActionName() === $this->view->actionName;
        }

        return $this->_isDirectAccess;
    }
}