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
 * @see Zend_View_Helper_Partial
 */
require_once 'Zend/View/Helper/Partial.php';

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_View_Helper_RandomPartial extends Zend_View_Helper_Partial
{
    /**
     * Renders a template fragment within a variable scope distinct from the
     * calling View object.
     *
     * @param  array $names Names of view scripts to random through
     * @param  string|array $module If $model is empty, and $module is an array,
     *                              these are the variables to populate in the
     *                              view. Otherwise, the module in which the
     *                              partial resides
     * @param  array $model Variables to populate in the view
     * @return string|Zend_View_Helper_Partial
     */
    public function randomPartial(array $names, $module = null, $model = null)
    {
        if (count($names) === 0) {
            return $this;
        }

        $name = $names[array_rand($names)];

        return parent::partial($name, $module, $model);
    }
}