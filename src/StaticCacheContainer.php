<?php

namespace StaticCacheManager;

use Psr\Container\ContainerInterface;

/**
 * Class StaticCacheContainer
 * @package StaticCacheManager
 *
 * https://www.php-fig.org/psr/psr-11/
 */
class StaticCacheContainer implements ContainerInterface
{
    /**
     * @var array Singelton for caches
     */
    private static $caches = [];

    /**
     * @var StaticCacheContainer Singelton
     */
    private static $instance;

    /**
     * StaticCacheManger constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return StaticCacheContainer
     */
    public static function getInstance()
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Returns the Static Cache on the position of the $cacheKey
     *
     * @param string $cacheKey
     * @return StaticCache
     */
    public function get($cacheKey)
    {
        if (!isset(self::$caches[$cacheKey])) {
            self::$caches[$cacheKey] = new StaticCache();
        }

        return self::$caches[$cacheKey];
    }

    /**
     * @param string $cacheKey
     * @return bool
     */
    public function has($cacheKey)
    {
        return isset(self::$caches[$cacheKey]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count(self::$caches);
    }

    /**
     * Clears all Containers
     */
    public function clear()
    {
        self::$caches = [];
    }
}