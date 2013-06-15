<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Cache\Driver;

/**
 * Simple array cache driver.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class SimpleArray implements DriverInterface
{
    /**
     * Items array.
     *
     * @var array
     */
    protected $items = array();

    /**
     * Clears all items.
     *
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function clear()
    {
        $this->items = array();

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
        if (!isset($this->items[$key])) {
            $this->set($key, $callback());
        }

        return $this->items[$key];
    }

    /**
     * Removes an item.
     *
     * @param  string $key
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function remove($key)
    {
        unset($this->items[$key]);

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
        $this->items[$key] = $value;

        return true;
    }
}
