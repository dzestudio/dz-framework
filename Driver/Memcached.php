<?php

namespace Bookscraper\Cache\Driver;

class Memcached implements DriverInterface
{
    /**
     * Expiration seconds.
     *
     * @var integer
     */
    protected $_expiration = 3600;

    /**
     * Memcached instance.
     *
     * @var \Memcached
     */
    protected $_memcached;

    /**
     * Public constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->_memcached = new \Memcached();

        $this->_memcached->addServer('localhost', 11211);

        if (isset($options['expiration'])) {
            $this->setExpiration($options['expiration']);
        }
    }

    /**
     * Clears all items.
     *
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function clear()
    {
        return $this->_memcached->flush();
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
        if (($value = $this->_memcached->get($key)) === false) {
            $value = $callback();

            $this->set($key, $value);
        }

        return $value;
    }

    /**
     * Gets expiration seconds.
     *
     * @return integer
     */
    public function getExpiration()
    {
        return $this->_expiration;
    }

    /**
     * Removes an item.
     *
     * @param  string $key
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function remove($key)
    {
        return $this->_memcached->delete($key);
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
        return $this->_memcached->set($key, $value, $this->_expiration);
    }

    /**
     * Sets expiration seconds.
     *
     * @param  integer $expiration
     * @return Memcached
     */
    public function setExpiration($expiration)
    {
        $this->_expiration = (int) $expiration;

        return $this;
    }
}