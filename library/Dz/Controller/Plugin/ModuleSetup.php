<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Controller
 * @subpackage Plugin
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Module aware layout switcher.
 *
 * @category   Dz
 * @package    Dz_Controller
 * @subpackage Plugin
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Controller_Plugin_ModuleSetup
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * Switches layout according module name.
     *
     * @link http://returnsuccess.com/post/18-Zend-framework-Different-layout-for-every-module
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        /**
         * @see \Zend_Layout
         */
        require_once 'Zend/Layout.php';

        $layout = \Zend_Layout::getMvcInstance();

        if ($layout !== null) {
            $moduleName = $request->getModuleName();

            if ($moduleName !== null && $moduleName !== 'default') {
                $path = sprintf('/modules/%s/layouts/scripts', $moduleName);

                $layout->setLayoutPath(APPLICATION_PATH . $path);
            }
        }
    }
}