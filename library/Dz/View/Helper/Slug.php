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
 * Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_View_Helper_Slug extends Zend_View_Helper_Abstract
{
    /**
     * @param string $value
     * @param string $separator
     */
    public function slug($value, $separator = '-')
    {
        /**
         * @see Gedmo\Sluggable\Util\Urlizer
         */
        require_once 'Gedmo/Sluggable/Util/Urlizer.php';

        return Gedmo\Sluggable\Util\Urlizer::urlize($value, $separator);
    }
}