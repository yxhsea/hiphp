<?php
/**
 * Project: hiphp
 * File: Pool.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/17/19
 * Time: 12:11 PM
 */
namespace Library\Connection;


class Pool
{
    protected static $connection_default;
    protected static $connection_task;
    protected static $driver = [];
    protected static $created_driver = [];
    protected static $adapter = [];
    protected static $created_adapter = [];

    public static function init()
    {
        // 加载 connection.default 配置
        $config = [];

        // 将配置设置到静态变量
        self::$connection_default = [
            'min' => isset($config['min']) ? intval($config['min']) : 1,
            'max' => isset($config['max']) ? intval($config['max']) : 1,
        ];

        var_dump(self::$connection_default);

        // 加载 connection.task 配置
        $taskConfig = [];

        // 将配置设置到静态变量
        self::$connection_task = [
            'min' => isset($taskConfig['min']) ? intval($taskConfig['min']) : 0,
            'max' => isset($taskConfig['max']) ? intval($taskConfig['max']) : 1,
        ];

        self::setDriver('mysql', \Library\Connection\Driver\Mysql::class);
    }

    public static function getMin()
    {
        return self::$connection_default['min'];
    }

    public static function getMax()
    {
        return self::$connection_default['max'];
    }

    public static function get($config = null)
    {
        if (!isset($config['driver'])) {
            throw new \Exception('Unknown driver');
        }
        if (!isset($config['host']) || !isset($config['port'])) {
            throw new \Exception('Host and Port is required');
        }

        $type = $config['driver'];
        $hash = md5($type . ':' . $config['host'] . ':' . $config['port']);
        if (!isset(self::$created_driver[$hash])) {
            if (isset(self::$driver[$type])) {
                $className = self::$driver[$type];
            } else {
                $className = __NAMESPACE__ . '\\Driver\\' . ucfirst($type);
            }
            self::$created_driver[$hash] = new $className($config);
        }

        return self::$created_driver[$hash];
    }

    public static function getAdapter($config = null)
    {
        if (is_string($config)) {
            //
        }
        if (!isset($config['adapter'])) {
            throw new \Exception('Unknown adapter');
        }
        $type = $config['adapter'];
        $connection = Pool::get($config);
        $hash = spl_object_hash($connection);
        if (!isset(self::$created_adapter[$hash])) {
            if (!isset(self::$adapter[$type])) {
                $className = self::$adapter[$type];
            } else {
                throw new \Exception('Unknown adapter');
            }
            $instance = new $className($connection);
            self::$created_adapter[$hash] = $instance;
            return $instance;
        } else {
            return self::$created_adapter[$hash];
        }
    }

    public static function setDriver($type, $className)
    {
        if (!is_subclass_of($className, \Library\Connection\PoolInterface::class)) {
            throw new \Exception("Class $className not implement PoolInterface");
        }
        self::$driver[$type] = $className;
    }

    public static function setAdapter($type, $className)
    {
        self::$adapter[$type] = $className;
    }
}
