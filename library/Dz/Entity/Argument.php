<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Entity
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_Entity
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Entity_Argument
{
    protected $_name;
    protected $_operator;
    protected $_value;

    public function __construct($name, $operator, $value = null)
    {
        $this->setName($name)
             ->setOperator($operator);

        if ($value !== null) {
            $this->setValue($value);
        }
    }

    public function __toString()
    {
        return $this->hasValue() ? $this->_value : '';
    }

    public function equals($value)
    {
        return $this->_value === $value;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public function getOperator()
    {
        return $this->_operator;
    }

    public function setOperator($operator)
    {
        $this->_operator = $operator;

        return $this;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;

        return $this;
    }

    public function hasValue()
    {
        return $this->_value !== null;
    }
}