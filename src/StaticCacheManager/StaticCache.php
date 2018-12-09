<?php

namespace StaticCacheManager;

use Psr\SimpleCache\CacheInterface;

/**
 * Class StaticCache
 * @package StaticCacheManager
 *
 * https://www.php-fig.org/psr/psr-16/
 */
class StaticCache implements CacheInterface
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param string $key
     * @param null   $default
     * @return mixed|null
     * @throws InvalidCacheArgumentException
     */
    public function get($key, $default = null)
    {
        $this->validKeyOrFail($key);

        return isset($this->cache[$key]) ? $this->cache[$key] : $default;
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param null   $ttl
     * @return bool
     * @throws InvalidCacheArgumentException
     */
    public function set($key, $value, $ttl = null)
    {
        $this->validKeyOrFail($key);

        $this->cache[$key] = $value;

        return true;
    }

    /**
     * @param string $key
     * @return bool
     * @throws InvalidCacheArgumentException
     */
    public function delete($key)
    {
        $this->validKeyOrFail($key);

        unset($this->cache[$key]);

        return true;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        $this->cache = [];

        return true;
    }

    /**
     * @param iterable $keys
     * @param null     $default
     * @return array|iterable
     * @throws InvalidCacheArgumentException
     */
    public function getMultiple($keys, $default = null)
    {
        $this->validKeysOrFail($keys);

        $return = [];

        foreach ($keys as $key) {
            $return[$key] = isset($this->cache[$key]) ? $this->cache[$key] : $default;
        }

        return $return;
    }

    /**
     * @param iterable $values
     * @param null     $ttl Not supported
     * @return bool
     * @throws InvalidCacheArgumentException
     */
    public function setMultiple($values, $ttl = null)
    {
        $this->validKeyValuesOrFail($values);

        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        return true;
    }

    /**
     * @param iterable $keys
     * @return bool|void
     * @throws InvalidCacheArgumentException
     */
    public function deleteMultiple($keys)
    {
        $this->validKeysOrFail($keys);

        foreach ($keys as $key) {
            $this->delete($key);
        }
    }

    /**
     * @param string $key
     * @return bool
     * @throws InvalidCacheArgumentException
     */
    public function has($key)
    {
        $this->validKeyOrFail($key);

        return isset($this->cache[$key]);
    }

    /**
     * @param $keys array|\Traversable
     * @throws InvalidCacheArgumentException
     */
    private function validKeysOrFail($keys)
    {
        if (!is_array($keys) && !($keys instanceof \Traversable)) {
            throw new InvalidCacheArgumentException('Keys has to be an array or instance of Traversable');
        }

        foreach ($keys as $key) {
            $this->validKeyOrFail($key);
        }
    }

    /**
     * @param $values
     * @throws InvalidCacheArgumentException
     */
    private function validKeyValuesOrFail($values)
    {
        if (!is_array($values) && !($values instanceof \Traversable)) {
            throw new InvalidCacheArgumentException('Values have to be an array or instance of Traversable');
        }

        $this->validKeysOrFail(array_keys($values));
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->cache);
    }

    /**
     * @param string $key
     * @throws InvalidCacheArgumentException
     */
    private function validKeyOrFail($key)
    {
        if (!preg_match('/^[A-Za-z0-9_.\-\$\@\[\]]+$/', $key) || strlen($key) < 1 || strlen($key) > 64) {
            throw new InvalidCacheArgumentException('Key for Cache is Invalid!');
        }
    }
}