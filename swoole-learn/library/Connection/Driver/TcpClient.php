<?php
/**
 * Project: hiphp
 * File: TcpClient.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/20/19
 * Time: 12:09 PM
 */

namespace Library\Connection\Driver;

use Library\Connection\PoolInterface;
use Library\Connection\PoolTrait;
use Swoole\Coroutine as co;

class TcpClient implements PoolInterface
{
    use PoolTrait;
    protected $config = null;
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initPool($config);
    }

    protected function connect()
    {
        $connection = new co\Client(SWOOLE_SOCK_TCP);
        return $this->reconnect($connection);
    }

    public function reconnect($connection)
    {
        $r = $connection->connect($this->config['host'], $this->config['port'], 3);
        if ($r === false) {
            throw new \Exception(sprintf('Can not connect to %s:%s', $this->config['host'], $this->config['port']));
        }

        return $connection;
    }
}
