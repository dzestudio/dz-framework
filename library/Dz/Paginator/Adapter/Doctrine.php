<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Paginator
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */

/**
 * @see Zend_Paginator_Adapter_Interface
 */
require_once 'Zend/Paginator/Adapter/Interface.php';

/**
 * Pagination adapters for Doctrine 2 ORM.
 *
 * @category   Dz
 * @package    Dz_Paginator
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Paginator_Adapter_Doctrine implements Zend_Paginator_Adapter_Interface
{
    /**
     * Query builder to paginate on.
     *
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $_queryBuilder;

    /**
     * Query builder clone.
     *
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $_queryBuilderClone;

    /**
     * Processing mode to be used during the hydration process.
     *
     * @var integer
     */
    protected $_hydrationMode = \Doctrine\ORM\Query::HYDRATE_OBJECT;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     */
    public function __construct(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $this->_queryBuilder = $queryBuilder;
    }

    /**
     * Gets a cached query builder clone.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function _getQueryBuilderClone()
    {
        if ($this->_queryBuilderClone === null) {
            $this->_queryBuilderClone = clone $this->_queryBuilder;
        }

        return $this->_queryBuilderClone;
    }

    /**
     * Gets the overall number of rows in the result set.
     *
     * @see Countable::count()
     * @return integer
     */
    public function count()
    {
        $queryBuilder = $this->_getQueryBuilderClone();
        $from = $queryBuilder->getDQLPart('from');
        $alias = array_shift($from)->getAlias();
        $select = sprintf('COUNT(%s)', $alias);

        $queryBuilder->select($select)
                     ->resetDQLPart('orderBy')
					 ->resetDQLPart('groupBy');

        $query = $queryBuilder->getQuery();
        $count = $query->getSingleScalarResult();

        return intval($count);
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->_queryBuilder->setMaxResults($itemCountPerPage)
                            ->setFirstResult($offset);

        $query = $this->_queryBuilder->getQuery();

        return $query->getResult($this->_hydrationMode);
    }

    /**
     * Gets hydration mode.
     *
     * @return integer
     */
    public function getHydrationMode()
    {
        return $this->_hydrationMode;
    }

    /**
     * Sets hydration mode.
     *
     * @param integer $hydrationMode
     * @return Dz_Paginator_Adapter_Doctrine Fluent interface.
     */
    public function setHydrationMode($hydrationMode)
    {
        $this->_hydrationMode = $hydrationMode;

        return $this;
    }
}