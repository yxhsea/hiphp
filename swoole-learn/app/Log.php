<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/27
 * Time: 下午5:54
 */
namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Swoole\Mysql\Exception;
use App\FileHandler;
use App\FluentHandler;

class Log
{
    protected static $_logger = null;

    public function __construct()
    {
        $logger = new Logger("");
        $logger->pushHandler(new FileHandler());
        $logger->pushHandler(new FluentHandler());
        self::$_logger = $logger;
    }

    public static function __callStatic($name, $arguments)
    {
        if (is_null(self::$_logger)) {
            new static();
        }
        call_user_func([self::$_logger, $name], "aaa");
    }
}
