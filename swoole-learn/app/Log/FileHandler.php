<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/27
 * Time: 下午6:12
 */
namespace App\Log;

use Monolog\Logger;
use \Monolog\Handler\AbstractHandler;

class FileHandler extends AbstractHandler
{
    protected static $_resource = null;
    protected static $_date = null;
    protected static $_async;

    public function __construct($level = Logger::DEBUG, $async = true, $bubble = true)
    {
        self::$_async = $async;
        parent::__construct($level, $bubble);
    }

    public function handle(array $record)
    {
        var_dump($record);
        $fh = self::getInstance();
        switch (self::$_async) {
            case true:
                \Swoole\Coroutine::fwrite($fh, \GuzzleHttp\json_encode($record) . "\n");
                break;
            case false:
                fwrite($fh, (string) $record['formatted']);
        }
    }

    protected static function getInstance()
    {
        $nowDate = date("Y-m-d");
        if (self::$_date == $nowDate && !is_null(self::$_resource)) {
            return self::$_resource;
        }
        $fh = fopen(sprintf("%s.log", date("Y-m-d")), "a");

        self::$_resource = $fh;
        self::$_date = $nowDate;
        return self::$_resource;
    }
}
