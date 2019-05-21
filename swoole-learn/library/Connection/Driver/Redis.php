<?php
/**
 * Project: hiphp
 * File: Redis.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/20/19
 * Time: 10:49 AM
 */

namespace Library\Connection\Driver;

use Library\Connection\PoolInterface;
use Library\Connection\PoolTrait;
use Swoole\Coroutine as co;

class Redis implements PoolInterface
{
    use PoolTrait;
    private $options = [];
    protected $config = null;
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initPool($config);
    }

    protected function connect()
    {
        $connection = new co\Redis();
        $r = $connection->connect($this->config['host'], $this->config['port']);
        if ($r === false) {
            throw new \Exception('Can not connect to database server, ' . $connection->errMsg, $connection->errCode);
        }

        if (!empty($this->config['password'])) {
            $r = $connection->auth($this->config['password']);
            if ($r === false) {
                throw new \Exception('Authenticate failed, ' . $connection->errMsg, $connection->errCode);
            }
        }

        if (isset($this->config['index'])) {
            $r = $connection->select(intval($this->config['index']));
            if ($r === false) {
                throw new \Exception('Select database failed, ' . $connection->errMsg, $connection->errCode);
            }
        }

        foreach ($this->options as $key => $value) {
            $connection->setOption($key, $value);
        }

        return $connection;
    }

    public function __call($name, $arguments)
    {
        $connection = $this->getConnection();
        if (!method_exists($connection, $name)) {
            $this->freeConnection($connection);
            throw new \Exception('Method ' . $name . 'not exists');
        }
        $tryAgain = true;
REDIS_START_EXECUTE:
        $result = $connection->$name(...$arguments);
        if ($connection->errCode !== 0) {
            if (!$connection->connected && $tryAgain) {
                @$connection->close();
                $tryAgain = false;
                $connection = $this->reconnect($connection);
                goto REDIS_START_EXECUTE;
            } else {
                $error = $connection->errMsg;
                $errno = $connection->errCode;
                $this->freeConnection($connection);
                throw new \Exception($error, $errno);
            }
        }

        $this->freeConnection($connection);
        return $result;
    }

    public function setOption($name, $value)
    {
        $all = [];
        for ($i = 1; $i <= $this->connection_count; $i++) {
            $connection = $this->getConnection();
            $connection->setOption($name, $value);
            $all[] = $connection;
        }

        foreach ($all as $value) {
            $this->freeConnection($value);
        }
    }
}
