<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/27
 * Time: 下午6:12
 */
namespace App;

use Monolog\Logger;
use \Monolog\Handler\AbstractHandler;

class FileHandler extends AbstractHandler
{
    protected static $_resource = null;
    protected static $_date = null;

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    public function handle(array $record)
    {
        $fh = self::getInstance();
        \Swoole\Coroutine::fwrite($fh, json_encode($record) . "\n");
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
