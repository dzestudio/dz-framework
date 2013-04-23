<?php

/**
 * @see \Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * DZ Application Resource Facebook class
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