<?php
/**
 * Bookscraper
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 */

namespace Bookscraper\Cache\Driver;

/**
 * Cache driver interface.
 *
 * @copyright Copyright (c) 2013 LF Bittencourt (http://www.lfbittencourt.com)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
interface DriverInterface
{
    /**
     * Clears all items.
     *
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function clear();

    /**
     * Retrieves an item.
     *
     * @param  string $key
     * @param  callback $callback
     * @return mixed The value stored in the cache.
     */
    public function get($key, $callback);

    /**
     * Removes an item.
     *
     * @param  string $key
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function remove($key);

    /**
     * Stores an item.
     *
     * @param  string $key
     * @param  mixed The value stored in the cache.
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function set($key, $value);
}
