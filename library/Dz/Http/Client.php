<?php
/**
 * DZ Framework
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

namespace Dz\Http;

use Dz\Cache\Driver\DriverInterface;

/**
 * Provides common methods for receiving data from a URI.
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author    LF Bittencourt <lf@dzestudio.com.br>
 */
class Client
{
    /**
     * Cache driver.
     *
     * @var DriverInterface
     */
    protected $cacheDriver;

    /**
     * Default cURL options.
     *
     * @var array
     */
    protected $defaultOptions = array(
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
    );

    /**
     * Public constructor.
     *
     * @param DriverInterface|null $cacheDriver
     */
    public function __construct(DriverInterface $cacheDriver = null)
    {
        $this->cacheDriver = $cacheDriver;
    }

    /**
     * Gets cache driver.
     *
     * @return DriverInterface
     */
    public function getCacheDriver()
    {
        return $this->cacheDriver;
    }

    /**
     * Retrieves content of an URI.
     *
     * @param  string $uri
     * @param  array $options cURL options.
     * @return string|null
     */
    public function request($uri, array $options = array())
    {
        $callback = function () use ($uri, $options) {
            $contents = null;

            if (count($options) > 0
                || ($contents = @file_get_contents($uri)) === false
            ) {
                $handler = curl_init();

                curl_setopt($handler, CURLOPT_URL, $uri);
                curl_setopt_array($handler, $this->defaultOptions);
                curl_setopt_array($handler, $options);

                $contents = curl_exec($handler);

                curl_close($handler);
            }

            return $contents;
        };

        if ($this->cacheDriver instanceof DriverInterface) {
            $key = base64_encode($uri . serialize($options));

            return $this->cacheDriver->get($key, $callback);
        }

        return $callback();
    }

    /**
     * Sets cache driver.
     *
     * @param  DriverInterface $cacheDriver
     * @return Crawler
     */
    public function setCacheDriver(DriverInterface $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;

        return $this;
    }
}
