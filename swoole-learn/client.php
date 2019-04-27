<?php
/**
 * Created by PhpStorm.
 * User: yxhsea
 * Date: 2019/4/24
 * Time: 下午10:35
 */

$client = new Swoole\Client(SWOOLE_TCP | SWOOLE_KEEP);

if ($client->connect("127.0.0.1", 9502)) {
    $client->send("data");
} else {
    echo "connect failed";
}
