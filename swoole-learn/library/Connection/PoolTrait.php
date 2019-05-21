<?php
/**
 * Project: hiphp
 * File: PoolTrait.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/17/19
 * Time: 12:26 PM
 */

namespace Library\Connection;

use function PHPSTORM_META\elementType;
use SplQueue;
use Swoole\Coroutine as co;

trait PoolTrait
{
    protected $connection = null;
    protected $connection_count = 0;
    protected $last_run_out_time = null;
    protected $wait = null;
    protected $min_client = PHP_INT_MAX;
    protected $max_client = PHP_INT_MAX;

    public function initPool($config)
    {
        if (!method_exists($this, 'getMinClient') || !method_exists($this, 'getMaxClient')) {
            throw new \Exception('Method getMinClient or getMaxClient not found');
        }

        $this->wait = new SplQueue;
        $this->connection = new SplQueue;
        $this->last_run_out_time = time();
        if (isset($config['min'])) {
            $this->min_client = intval($config['min']);
        }
        if (isset($config['max'])) {
            $this->max_client = intval($config['max']);
        }
        // 建立最小链接
        $count = $this->getMinClient();
        while ($count--) {
            $this->createConnection();
        }
    }

    protected function getMinClient()
    {
        return $this->min_client === PHP_INT_MAX ? Pool::getMin() : $this->min_client;
    }

    protected function getMaxClient()
    {
        return $this->max_client === PHP_INT_MAX ? Pool::getMax() : $this->max_client;
    }

    /**
     * 获取一个链接, 自动判断链接可不可用
     */
    public function getConnection()
    {
        // 判断 splQueue 中的元素数量是否是零
        if ($this->connection->count() === 0) {
            // 是否需要建立新的链接
            if ($this->getMaxClient() > $this->connection_count) {
                $this->last_run_out_time = time();
                return $this->reconnect();
            }
            // wait
            $uid = co::getUid();
            $this->wait->push($uid);
            co::suspend();
            return $this->connection->pop();
        }

        if ($this->connection->count() === 0) {
            $this->last_run_out_time = time();
        }

        return $this->connection->pop();
    }

    /**
     * 使用链接完成, 归还给链接池
     * @param $connection
     */
    public function freeConnection($connection)
    {
        $this->connection->push($connection);
        if ($this->wait->count() > 0) {
            $uid = $this->wait->pop();
            co::resume($uid);
        } else {
            // 若有链接处于空闲状态超过 15 秒, 则关闭一个链接
            if ($this->connection_count > $this->getMinClient() && time() - $this->last_run_out_time > 15) {
                $this->close();
            }
        }
    }

    /**
     * 关闭一个链接
     */
    public function close()
    {
        $this->connection_count--;
    }

    /**
     * 创建新的链接, 并压入连接池
     */
    protected function createConnection()
    {
        $this->connection->push($this->connect());
        $this->connection_count++;
    }

    /**
     * 创建新的链接并返回
     */
    protected function connect()
    {
    }

    /**
     * 重新建立链接
     * @param $connection
     */
    public function reconnect($connection)
    {
        $this->close();
        return $this->connect();
    }
}
