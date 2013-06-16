<?php
/**
 * DZ Framework
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

namespace Dz\Cache\Driver;

/**
 * File based cache driver.
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author    LF Bittencourt <lf@lfbittencourt.com>
 */
class File implements DriverInterface
{
    /**
     * Output directory.
     *
     * @var string
     */
    protected $outputDirectory = 'cache/';

    /**
     * Public constructor.
     *
     * @param string|null $outputDirectory
     */
    public function __construct($outputDirectory = null)
    {
        $this->setOutputDirectory($outputDirectory);
    }

    /**
     * Clears all items.
     *
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function clear()
    {
        if (rmdir($this->outputDirectory)) {
            return mkdir($this->outputDirectory);
        }

        return false;
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
        $filename = $this->outputDirectory . md5($key);

        if (!file_exists($filename)) {
            $this->set($key, $callback());
        }

        return unserialize(file_get_contents($filename));
    }

    /**
     * Gets output directory.
     *
     * @return string
     */
    public function getOutputDirectory()
    {
        return $this->outputDirectory;
    }

    /**
     * Removes an item.
     *
     * @param  string $key
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function remove($key)
    {
        $filename = $this->outputDirectory . md5($key);

        return unlink($filename);
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
        $filename = $this->outputDirectory . md5($key);

        return file_put_contents($filename, serialize($value)) !== false;
    }

    /**
     * Sets output directory.
     *
     * @param  string $outputDirectory
     * @return File
     */
    public function setOutputDirectory($outputDirectory)
    {
        if (!empty($outputDirectory)) {
            $this->outputDirectory = $outputDirectory;
        }

        return $this;
    }
}
