<?php
/**
 * Project: hiphp
 * File: PoolInterface.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/17/19
 * Time: 12:06 PM
 */
namespace Library\Connection;

interface PoolInterface {
    public function initPool($config);
    public function getConnection();
    public function freeConnection($connection);
    public function close();
    public function reconnect($connection);
}
