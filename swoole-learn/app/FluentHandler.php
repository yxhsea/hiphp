<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/27
 * Time: 下午9:34
 */

namespace App;

use Monolog\Logger;
use \Monolog\Handler\AbstractHandler;

class FluentHandler extends AbstractHandler
{
    protected static $_client = null;

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    public function handle(array $record)
    {
        self::getInstance();
        self::$_client->send("hello world\n");
    }

    protected static function getInstance()
    {
        if (is_null(self::$_client)) {
            $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
            if (!$client->connect('192.168.199.113', 24224, 5)) {
                exit('connect failed. Error: {$client->errCode}\n');
            }
            self::$_client = $client;
        }
    }
}
