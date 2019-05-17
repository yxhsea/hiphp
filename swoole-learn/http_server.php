<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/26
 * Time: 下午10:30
 */

require_once './bootstrap/app.php';
require_once './routes/api.php';

use App\Http\Router;
use Library\Connection\Driver\Mysql;
use Library\Connection\Pool;

$http = new swoole_http_server("127.0.0.1", 9501,SWOOLE_BASE);
$http->set(['worker_num' => 1]);

$queue = new \SplQueue();

$http->on('WorkerStart', function () use ($queue) {
    Pool::init();

    go(function() use ($queue) {
        while (true) {
            if (!$queue->isEmpty()) {
                $element = $queue->dequeue();
                var_dump($element);
            }
            \Swoole\Coroutine::sleep(1);
        }
    });
});

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) use ($queue) {
    $swoole_mysql = new Swoole\Coroutine\MySQL();
    $swoole_mysql->connect([
        'host' => '10.11.1.172',
        'port' => 3307,
        'user' => 'root',
        'password' => 'root',
        'database' => 'guest',
    ]);
    var_dump($swoole_mysql);

/*
    $connection = new Mysql([
        'host'     => '10.11.1.172',
        'user'     => 'root',
        'password' => 'root',
        'database' => 'guest',
        'port'     => 3307,
    ]);*/
//    var_dump($connection);


    // $queue->enqueue($request->get["name"]);

    /*$method = $request->server["request_method"];
    $pathInfo = $request->server["path_info"];
    $key = $method . $pathInfo;
    if (isset(Router::$routerList[$key])) {
        $actionArr = explode("@", Router::$routerList[$key]);
        $controller = "return new App\Http\Controllers\\" . current($actionArr). "();";
        $result = call_user_func(array(eval($controller), end($actionArr)), $request);
    } else {
        $result = \GuzzleHttp\json_encode(["message"=>"router not found"]);
    }*/

    $result = \GuzzleHttp\json_encode(["message" => "OK"]);

//    \App\Log\Log::debug("hello world!", "abcd");

    //  $rabbitMQ = config("queue.rabbitMQ");
    //  var_dump($rabbitMQ['host']);

    $response->header('Content-Type', 'application/json');
    $response->end($result);
});

$http->start();
