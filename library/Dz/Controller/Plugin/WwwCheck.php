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
 * @see \Zend_Controller_Plugin_Abstract
 */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * @see \Zend_Controller_Request_Abstract
 */
require_once 'Zend/Controller/Request/Abstract.php';

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_Controller
 * @subpackage Plugin
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Controller_Plugin_WwwCheck
    extends \Zend_Controller_Plugin_Abstract
{
    public function preDispatch(
        \Zend_Controller_Request_Abstract $request)
    {
        $server = $this->getRequest()->getServer();
        $httpHost = $server['HTTP_HOST'];
        $ipOrLocalhostPattern = '/^(?:\d+(\.\d+){3}|localhost)(?::\d+)?$/';

        if (preg_match($ipOrLocalhostPattern, $httpHost) === 0 // IP or localhost
            && preg_match('/^www\./', $httpHost) === 0
        ) {
            $url = sprintf('http://www.%s%s', $httpHost, $server['REQUEST_URI']);

            /**
             * @see \Zend_Controller_Action_HelperBroker
             */
            require_once 'Zend/Controller/Action/HelperBroker.php';

            $redirector =
                \Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');

            $options = array('code' => 301, 'exit' => true);

            $redirector->gotoUrl($url, $options);
        }
    }
}