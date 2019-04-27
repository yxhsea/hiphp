<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/24
 * Time: 下午10:17
 */

$server = new \Swoole\Server("127.0.0.1", 9501);
$server->set(['worker_num' => 1]);

// 增加监听端口
$server->addListener("127.0.0.1", 9502, SWOOLE_SOCK_TCP);

$process = new \Swoole\Process(function($process) use ($server) {
    while (true) {
        $msg = $process->read();
        echo "process => " . $msg . "\n";
        foreach ($server->connections as $conn) {
            $server->send($conn, $msg);
        }
    }
});

$server->addProcess($process);

$server->on('connect', function($server, $fd){
    echo "Client: Connect.\n";
});

$server->on('receive', function ($server, $fd, $reactor_id, $data) use ($process) {
    echo "receive => " . $data . "\n";
    $process->write($data);
    echo "Client: Receive.\n";
});

$server->on('close', function ($server, $fd){
    echo "Client: Close.\n";
});

$server->send("","","");

$server->start();
