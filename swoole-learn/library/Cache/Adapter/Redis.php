<?php
/**
 * Project: hiphp
 * File: Redis.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/21/19
 * Time: 3:55 PM
 */

namespace Library\Cache\Adapter;

use Library\Connection\PoolInterface;
use Psr\SimpleCache\CacheInterface;

class Redis implements CacheInterface
{
    private $pool;

    public function __construct(PoolInterface $pool)
    {
        $this->pool = $pool;
    }

    public function get($key, $default = null)
    {
        $result = $this->pool->get($key);
        return ($result === false || $result === null) ? $default : unserialize($result);
    }

    public function set($key, $value, $ttl = null)
    {
        if ($ttl !== null) {
            return $this->pool->setEx($key, $ttl * 100, serialize($value));
        } else {
            return $this->pool->set($key, serialize($value));
        }
    }

    public function delete($key)
    {
        return $this->pool->delete($key);
    }

    public function clear()
    {
        $this->pool->flushdb();
    }

    public function getMultiple($keys, $default = null)
    {
        $result = $this->pool->mGet($keys);
        foreach ($result as $key => $value) {
            if ($value === false || $value === null) {
                $result[$key] = $default;
            } else {
                $result[$key] = unserialize($value);
            }
        }

        return array_combine($keys, $result);
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $values[$key] = serialize($value);
        }

        $this->pool->mSet($values);
        if ($ttl !== null) {
            foreach ($values as $key => $value) {
                $this->pool->expire($key, $ttl * 100);
            }
        }
    }

    public function deleteMultiple($keys)
    {
        return $this->pool->delete($keys);
    }

    public function has($key)
    {
        return $this->pool->exists($key);
    }
}
