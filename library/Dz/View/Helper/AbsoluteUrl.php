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
 * @see \Zend_View_Helper_Url
 */
require_once 'Zend/View/Helper/Url.php';

/**
 * Helper for making easy links and getting absolute urls that depend on the routes and router
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_View_Helper_AbsoluteUrl extends \Zend_View_Helper_Url
{
    /**
     * Generates an absolute url given the name of a route.
     *
     * @param array|string $urlOptions
     * @param string $name
     * @param boolean $reset
     * @param boolean $encode
     */
    public function absoluteUrl($urlOptions = array(), $name = null,
        $reset = false, $encode = true
    ) {
        if (is_string($urlOptions)) {
            $requestUri = $urlOptions;
        } else {
            $requestUri = parent::url($urlOptions, $name, $reset, $encode);
        }

        return $this->view->serverUrl($requestUri);
    }
}