<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see \Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * DZ Application Resource Facebook class
 *
 * @category   Dz
 * @package    Dz_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Application_Resource_Facebook
    extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var \Dz_Facebook
     */
    protected $_service;

    /**
     * Initializes Facebook service.
     *
     * @return \Dz_Facebook
     */
    public function init()
    {
        $config = $this->getOptions();

        /**
         * @see \Dz_Facebook
         */
        require_once 'Dz/Facebook.php';

        $this->_service = new \Dz_Facebook($config);

        return $this->_service;
    }

    /**
     * Retrieve Facebook service.
     *
     * @return \Dz_Facebook
     */
    public function getService()
    {
        return $this->_service;
    }
}