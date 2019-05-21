<?php
/**
 * Project: hiphp
 * File: PostgreSQL.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/20/19
 * Time: 12:16 PM
 */

namespace Library\Connection\Driver;

use Library\Connection\PoolInterface;
use Library\Connection\PoolTrait;
use Swoole\Coroutine as co;

class PostgreSQL implements PoolInterface
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
        $connection = new co\PostgreSQL();
        return $this->reconnect($connection);
    }

    public function reconnect($connection)
    {
        $connection = new co\PostgreSQL();
        $r = $connection->connect(sprintf("host=%s port=%d dbname=%s user=%s password=%s",
            $this->config['host'],
            $this->config['port'],
            $this->config['database'],
            $this->config['user'],
            $this->config['password']));

        if ($r === false) {
            throw new \Exception('Can not connect to PostgreSQL server');
        }

        return $connection;
    }
}
