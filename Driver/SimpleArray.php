<?php

namespace Bookscraper\Cache\Driver;

class SimpleArray implements DriverInterface
{
    /**
     * Items array.
     *
     * @var array
     */
    protected $_items = array();

    /**
     * Clears all items.
     *
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function clear()
    {
        $this->_items = array();

        return true;
    }

    /**
     * Retrieves an item.
     *
     * @param  string $key
     * @param  callback $callback
     * @return mixed The value stored in the cache.
     */
    public function get($key, $callback)
    {
        if (!isset($this->_items[$key])) {
            $this->set($key, $callback());
        }

        return $this->_items[$key];
    }

    /**
     * Removes an item.
     *
     * @param  string $key
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function remove($key)
    {
        unset($this->_items[$key]);

        return true;
    }

    /**
     * Stores an item.
     *
     * @param  string $key
     * @param  mixed The value stored in the cache.
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function set($key, $value)
    {
        $this->_items[$key] = $value;

        return true;
    }
}