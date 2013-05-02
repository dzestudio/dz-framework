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
 * Helper for formatting dates with escape support.
 *
 * @category   Dz
 * @package    Dz_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_View_Helper_FormatDate extends \Zend_View_Helper_Abstract
{
    /**
     * Formats a $date given a $format.
     * In the view, you can use something like this
     * (note that backslashed characters are preserved):
     *
     * <code>
     * <?php echo $this->formatDate($article->getCreatedOn(), 'dd \d\e MMMM \d\e yyyy'); ?>
     * </code>
     *
     * The above example will output something like "07 de novembro de 2012".
     *
     * @param string|integer|Zend_Date|array|DateTime $date Date value to format
     * @param string $format
     * @return NULL|string
     */
    public function formatDate($date, $format)
    {
        if (!is_string($format)) {
            return null;
        }

        if ($date instanceof DateTime) {
            $date = $date->getTimestamp();
        }

        /**
         * @see \Zend_Date
         */
        require_once 'Zend/Date.php';

        $zendDate = new \Zend_Date($date);
        $escapedChars = array();

        while (($position = stripos($format, '\\')) !== false) {
            $escapedChars[] = substr($format, $position + 1, 1);
            $format = substr($format, 0, $position)
                    . sprintf('?%d', count($escapedChars) - 1)
                    . substr($format, $position + 2);
        }

        $formatted = $zendDate->toString($format);

        while (($position = stripos($formatted, '?')) !== false) {
            $formatted = substr($formatted, 0, $position)
                       . $escapedChars[intval(substr($formatted, $position + 1, 1))]
                       . substr($formatted, $position + 2);
        }

        return $formatted;
    }
}