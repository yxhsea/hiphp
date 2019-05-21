<?php
/**
 * Project: hiphp
 * File: Mysql.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/17/19
 * Time: 12:25 PM
 */

namespace Library\Connection\Driver;

use Library\Connection\PoolInterface;
use Library\Connection\PoolTrait;
use Swoole\Coroutine as co;

class Mysql implements PoolInterface
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
        $connection = new co\MySQL();
        return $this->reconnect($connection);
    }

    public function reconnect($connection)
    {
        var_dump($this->config);
        $reconnect = $connection->connect([
            'host'     => $this->config['host'],
            'user'     => $this->config['user'],
            'password' => $this->config['password'],
            'database' => $this->config['database'],
            'port'     => $this->config['port'],
            'timeout'  => 3,
            'charset'  => 'utf8'
        ]);

        if ($reconnect ===  false) {
            throw new \Exception('Can not connect to database server');
        }

        return $connection;
    }
}
