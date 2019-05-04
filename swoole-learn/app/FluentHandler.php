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
use Fluent\Logger\FluentLogger;

class FluentHandler extends AbstractHandler
{
    const DEFAULT_LISTEN_PORT = 24224;
    const DEFAULT_ADDRESS     = "192.168.199.113";

    protected static $_client = null;
    protected static $_async;
    protected $host;
    protected $port;

    public function __construct($host = FluentHandler::DEFAULT_ADDRESS,
                                $port = FluentHandler::DEFAULT_LISTEN_PORT,
                                $level = Logger::DEBUG,
                                $async = true,
                                $bubble = true)
    {
        self::$_async = $async;
        $this->host = $host;
        $this->port = $port;
        parent::__construct($level, $bubble);
    }

    public function handle(array $record)
    {
        self::getInstance();
        switch (self::$_async) {
            case true:
                self::$_client->send(json_encode(array("info.abc", time(), array("swoole" => "hello swoole"))));
                break;
            case false:
                self::$_client->post("debug.test",array("hello"=>"world"));
                break;
        }
    }

    protected function getInstance()
    {
        if (is_null(self::$_client)) {
            switch (self::$_async) {
                case true:
                    self::$_client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
                    if (!self::$_client->connect($this->host, $this->port, 5)) {
                        exit('connect failed. Error: {$client->errCode}\n');
                    }
                    break;
                case false:
                    self::$_client = new FluentLogger($this->host, $this->port);
                    break;
            }
        }
    }
}
