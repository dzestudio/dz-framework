<?php
/**
 * DZ Framework
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

namespace Dz\Cache\Driver;

/**
 * Cache driver interface.
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
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
