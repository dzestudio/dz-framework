<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Cache\Driver;

/**
 * Memcached cache driver.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class Memcached implements DriverInterface
{
    /**
     * Expiration seconds.
     *
     * @var integer
     */
    protected $expiration = 3600;

    /**
     * Memcached instance.
     *
     * @var \Memcached
     */
    protected $memcached;

    /**
     * Public constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->memcached = new \Memcached();

        $this->memcached->addServer('localhost', 11211);

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
        return $this->memcached->flush();
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
        if (($value = $this->memcached->get($key)) === false) {
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
        return $this->expiration;
    }

    /**
     * Removes an item.
     *
     * @param  string $key
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function remove($key)
    {
        return $this->memcached->delete($key);
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
        return $this->memcached->set($key, $value, $this->expiration);
    }

    /**
     * Sets expiration seconds.
     *
     * @param  integer $expiration
     * @return Memcached
     */
    public function setExpiration($expiration)
    {
        $this->expiration = (int) $expiration;

        return $this;
    }
}
