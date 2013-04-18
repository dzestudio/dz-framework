<?php

/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Mail
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id: Mail.php 16 2013-04-04 19:33:04Z lf $
 */

/**
 * @see Zend_Mail
 */
require_once 'Zend/Mail.php';

/**
 * Class for sending an email using a Zend_View template.
 *
 * @category   Dz
 * @package    Dz_Mail
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Mail extends Zend_Mail
{
    /**
     * Template file path
     *
     * @var string
     */
    protected $_templateFile;

    /**
     * Template view
     *
     * @var Zend_View
     */
    protected $_templateView;

    /**
     * Public constructor
     *
     * @param  string $templateFile Template file path
     * @param  string $charset
     * @return void
     * @throws Dz_Mail_Exception if template file does not exist
     */
    public function __construct($templateFile, $charset = null)
    {
        $templateFile = realpath($templateFile);

        if ($templateFile === false)
        {
            /**
             * @see Dz_Mail_Exception
             */
            require_once 'Dz/Mail/Exception.php';

            throw Dz_Mail_Exception('Template file does not exist.');
        }

        $scriptPath = dirname($templateFile);

        /**
         * @see Zend_View
         */
        require_once 'Zend/View.php';

        $this->_templateView = new Zend_View();

        $this->_templateView->setScriptPath($scriptPath);

        $this->_templateFile = basename($templateFile);

        parent::__construct($charset);
    }

    /**
     * Magic method to read a template view property
     *
     * @param string $key
     */
    public function __get($key)
    {
        return $this->_templateView->$key;
    }

    /**
     * Magic method to set a template view property
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->_templateView->$key = $value;
    }

    /**
     * Returns template file path
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->_templateFile;
    }

    /**
     * Returns template view
     *
     * @return Zend_View
     */
    public function getTemplateView()
    {
        return $this->_templateView;
    }

    /**
     * Return Zend_Mime_Part representing body HTML
     *
     * @param  bool $htmlOnly Whether to return the body HTML only, or the MIME part; defaults to false, the MIME part
     * @return false|Zend_Mime_Part|string
     */
    public function getBodyHtml($htmlOnly = false)
    {
        $html = $this->_templateView->render($this->_templateFile);

        parent::setBodyHtml($html);

        return parent::getBodyHtml($htmlOnly);
    }
}