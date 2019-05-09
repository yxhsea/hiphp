<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/27
 * Time: 下午5:54
 */
namespace App\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Swoole\Mysql\Exception;
use App\Log\FileHandler;
use App\Log\FluentHandler;

class Log
{
    protected static $_logger = null;

    public function __construct()
    {
        $logger = new Logger("HiPHP");
        $logger->pushHandler(new FileHandler());
        $logger->pushHandler(new FluentHandler());
        self::$_logger = $logger;
    }

    public static function __callStatic(string $name, array $arguments): void
    {
        if (is_null(self::$_logger)) {
            new static();
        }

        var_dump($arguments);

        $message = $arguments[0];
        $context['extra_params'] = $arguments[1];
        $context['process_id'] = (new \Swoole\Process(function (){}))->pid;
        call_user_func_array([self::$_logger, $name], [$message, $context]);
    }
}
