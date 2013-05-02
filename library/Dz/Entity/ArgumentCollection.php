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
class Dz_Entity_ArgumentCollection implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array of Dz_Entity_Argument
     */
    protected $_arguments = array();

    public function __clone()
    {
        // Avoid that arguments still remain as references.
        foreach ($this->_arguments as &$argument) {
            $argument = clone $argument;
        }
    }

    public function applyTo(\Doctrine\ORM\QueryBuilder $queryBuilder) {
        foreach ($this as $key => $argument) {
            $placeholder = sprintf(':%s', $key);
            $operator = $argument->getOperator();
            $value = $argument->getValue();

            if ($operator === 'LIKE') {
                $value = sprintf('%%%s%%', $value);
            }

            $queryBuilder->andWhere(new \Doctrine\ORM\Query\Expr\Comparison(
                $argument->getName(), $operator, $placeholder))
                         ->setParameter($placeholder, $value);
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->_arguments[$offset]);
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->_arguments[$offset];
        }

        return null;
    }

    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->_arguments[$offset]->setValue($value);
        }
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->_arguments[$offset]->setValue(null);
        }
    }

    public function getIterator()
    {
        $validArguments = array_filter($this->_arguments, function($argument)
        {
            return $argument->hasValue();
        });

        return new \ArrayIterator($validArguments);
    }
}