<?php

namespace Bookscraper\Cache\Driver;

class File implements DriverInterface
{
    /**
     * Output directory.
     *
     * @var string
     */
    protected $_outputDirectory = 'cache/';

    /**
     * Public constructor.
     *
     * @param array $options
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
        if (rmdir($this->_outputDirectory)) {
            return mkdir($this->_outputDirectory);
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
        $filename = $this->_outputDirectory . md5($key);

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
        return $this->_outputDirectory;
    }

    /**
     * Removes an item.
     *
     * @param  string $key
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function remove($key)
    {
        $filename = $this->_outputDirectory . md5($key);

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
        $filename = $this->_outputDirectory . md5($key);

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
            $this->_outputDirectory = $outputDirectory;
        }

        return $this;
    }
}