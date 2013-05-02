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
class Dz_View_Helper_RequestInfo extends \Zend_View_Helper_Abstract
{
    /**
     * @var \Zend_Controller_Request_Abstract
     */
    protected static $_request = null;

    /**
     * @return string
     */
    public function getActionName()
    {
        return self::$_request->getActionName();
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return self::$_request->getControllerName();
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        $moduleName = self::$_request->getModuleName();

        if ($moduleName === null) {
            $moduleName = 'default';
        }

        return $moduleName;
    }

    /**
     * @return \Zend_View_Helper_RequestInfo Fluent interface.
     */
    public function requestInfo()
    {
        if (self::$_request === null) {
            /**
             * @see \Zend_Controller_Front
             */
            require_once 'Zend/Controller/Front.php';

            self::$_request =
                \Zend_Controller_Front::getInstance()->getRequest();
        }

        return $this;
    }
}