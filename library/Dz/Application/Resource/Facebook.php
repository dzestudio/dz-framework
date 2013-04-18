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
     * @var \Dz_Service_Facebook
     */
    protected $_service;

    /**
     * Initializes Facebook service.
     *
     * @return \Dz_Service_Facebook
     */
    public function init()
    {
        $config = $this->getOptions();

        /**
         * @see \Dz_Service_Facebook
         */
        require_once 'Dz/Service/Facebook.php';

        $this->_service = new \Dz_Service_Facebook($config);

        /**
         * @see \Zend_Registry
         */
        require_once 'Zend/Registry.php';

        // Add to Zend Registry
        \Zend_Registry::set('facebook', $this->_service);

        return $this->_service;
    }

    /**
     * Retrieve Facebook service.
     *
     * @return \Dz_Service_Facebook
     */
    public function getService()
    {
        return $this->_service;
    }
}