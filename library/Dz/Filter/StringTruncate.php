<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Filter
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_Filter
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Filter_StringTruncate implements Zend_Filter_Interface
{
    /**
     * @var integer
     */
    protected $_length = 80;

    /**
     * @var string
     */
    protected $_etc = '...';

    /**
     * @var bool
     */
    protected $_breakWords = false;

    /**
     * @var bool
     */
    protected $_middle = false;

    /**
     * Sets filter options
     *
     * @return void
     */
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (!is_array($options)) {
            $options = func_get_args();
        }

        if (isset($options['length']) && is_int($options['length'])) {
            $this->setLength($options['length']);
        }

        if (isset($options['etc']) && is_string($options['etc'])) {
            $this->setEtc($options['etc']);
        }

        if (isset($options['breakWords']) && is_bool($options['breakWords'])) {
            $this->setBreakWords($options['breakWords']);
        }

        if (isset($options['middle']) && is_bool($options['middle'])) {
            $this->setBreakWords($options['middle']);
        }
    }

    public function getLength()
    {
        return $this->_length;
    }

    public function setLength($length)
    {
        $this->_length = $length;
    }

    public function getEtc()
    {
        return $this->_etc;
    }

    public function setEtc($etc)
    {
        $this->_etc = $etc;
    }

    public function getBreakWords()
    {
        return $this->_breakWords;
    }

    public function setBreakWords($breakWords)
    {
        $this->_breakWords = $breakWords;
    }

    public function getMiddle()
    {
        return $this->_middle;
    }

    public function setMiddle($middle)
    {
        $this->_middle = $middle;
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        if ($this->_length <= 0) {
            return '';
        }

        if (isset($value[$this->_length])) {
            $this->_length -= min($this->_length, strlen($this->_etc));

            if (!$this->_breakWords && !$this->_middle) {
                $value = preg_replace('/\s+?(\S+)?$/', '',
                    substr($value, 0, $this->_length + 1));
            }

            if (!$this->_middle) {
                return substr($value, 0, $this->_length) . $this->_etc;
            }

            return substr($value, 0, $this->_length / 2) .
                   $this->_etc .
                   substr($value, - $this->_length / 2);
        }

        return $value;
    }
}