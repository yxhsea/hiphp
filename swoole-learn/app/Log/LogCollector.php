<?php
/**
 * Project: hiphp
 * File: LogCollector.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/9/19
 * Time: 3:52 PM
 */

use \Swoole\Coroutine as co;

class LogCollector
{
    protected static $fileHandler;
    protected static $fluentHandler;
    protected static $date;
    protected static $logQueue;

    public static function start()
    {
        self::setFileHandler();
        self::setLogQueue();
    }

    public static function setLogQueue()
    {
        if (!self::$logQueue instanceof \SplQueue) {
            self::$logQueue = new \SplQueue();
        }
    }

    public static function pushLog($record)
    {
        if (!self::$logQueue instanceof \SplQueue) {
            self::$logQueue = new \SplQueue();
        }
        self::$logQueue->enqueue($record);
    }

    public static function setFileHandler()
    {
        $date = date("%Y-%m-%d");
        self::$fileHandler = fopen(sprintf("log-%s", $date), "a");
    }

    public static function exec()
    {
        go(function(){
            while (true) {
                if (!self::$logQueue->isEmpty()) {
                    $record = self::$logQueue->dequeue();
                    self::write($record);
                } else {
                    if (self::$date != date("%Y-%m-%d")) {
                        self::setFileHandler();
                    }
                    co::sleep(0.1);
                }
            }
        });
    }

    public static function write(array $record)
    {
        co::fwrite(self::$fileHandler, (string) $record['formatted']);
        try {
            if (!self::$fluentHandler->connected) {
                self::reconnect();
            }
            self::$fluentHandler->send( (string) $record['formatted']);
        } catch (\Exception $e) {

        }
    }

    public static function connect()
    {
        $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
        if (!$client->connect('127.0.0.1', 24224)) {
            throw new \Exception("connect failed, Error: {$client->errCode}");
        }

        self::$fluentHandler = $client;
    }

    public static function reconnect()
    {
        if (is_null(self::$fluentHandler) || self::$fluentHandler->connected === false) {
            self::connect();
        }
    }
}
