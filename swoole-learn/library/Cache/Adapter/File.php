<?php
/**
 * Project: hiphp
 * File: File.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/21/19
 * Time: 2:34 PM
 */

namespace Library\Cache\Adapter;

use Psr\SimpleCache\CacheInterface;
use Swoole\Coroutine as co;

class File implements CacheInterface
{
    private $path;

    public function __construct()
    {
        $this->path = "";
        if ($this->path === '@TMP') {
            $this->path = sys_get_temp_dir() . '/' .uniqid();
        }

        if (strpos($this->path, '@APP') === 0) {
            $this->path = substr($this->path, 5);
        }

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }

        $this->path = str_replace('\\', '/', $this->path);
        if (substr($this->path, -1) !== '/') {
            $this->path .= '/';
        }
    }

    private function getKey($key)
    {
        return md5($key);
    }

    private function getPath($key)
    {
        $name = $this->getKey($key);
        return $this->path . $name;
    }

    public function get($key, $default = null)
    {
        $fullPath = $this->getPath($key);
        if (!is_file($fullPath)) {
            return $default;
        }

        $res = co::readFile($fullPath);
        if ($res === false) {
            return $default;
        }

        $expire = substr($res, 0, 10);
        if ($expire != 0 && $expire <= time()) {
            $this->delete($key);
            return $default;
        }

        return unserialize(substr($res, 10));
    }

    public function set($key, $value, $ttl = null)
    {
        if ($ttl === null) {
            $time = 0;
        } else {
            $time = time() + $ttl;
        }
        $path = $this->getPath($key);
        $content = str_pad($time, 10, '0', STR_PAD_LEFT) . serialize($value);
        co::writeFile($path, $content, FILE_APPEND);
    }

    public function delete($key)
    {
        @unlink($this->getPath($key));
    }

    public function clear()
    {
        $dh = opendir($this->path);
        while ($f = readdir($dh)) {
            if (!is_file($this->path . $f)) {
                continue;
            }
            @unlink($this->path . $f);
        }
        closedir($dh);
    }

    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $v) {
            $res = $this->get($v);
            if ($res === null) {
                $result[$v] = $default;
            } else {
                $result[$v] = $res;
            }
        }
        return $result;
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $value) {
            $this->delete($value);
        }
    }

    public function has($key)
    {
        return is_file($this->getPath($key));
    }
}
