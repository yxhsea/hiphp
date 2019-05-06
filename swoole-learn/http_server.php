<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/26
 * Time: 下午10:30
 */

require_once './bootstrap/app.php';

$http = new swoole_http_server("127.0.0.1", 9501,SWOOLE_BASE);
$http->set(['worker_num' => 4]);

$http->on('request', function ($request, swoole_http_response $response) {
    \App\Log::debug("hello world!");

    $rabbitMQ = config("queue.rabbitMQ");
    var_dump($rabbitMQ['host']);

    $response->header('Last-Modified', 'Thu, 18 Jun 2015 10:24:27 GMT');
    $response->header('E-Tag', '55829c5b-17');
    $response->header('Accept-Ranges', 'bytes');
    $response->end("<h1>\nHello Swoole.\n</h1>");
});

$http->start();
