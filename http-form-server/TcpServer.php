<?php
/**
 * Project: hiphp
 * File: TcpServer.php
 *
 * Created by PhpStorm.
 * User: yxhsea
 * Email: xionghaiyang@hk01.com
 * Date: 12/4/19
 * Time: 11:07 AM
 */

class TcpServer
{
    private $port = 9501;
    private $addr = "127.0.0.1";
    private $socket_handle;
    private $back_log = 10;

    public function __construct($port = 9501, $addr = "127.0.0.1", $back_log = 10)
    {
        $this->port = $port;
        $this->addr = $addr;
        $this->back_log = $back_log;
    }

    private function createSocket()
    {
        $this->socket_handle = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$this->socket_handle) {
            throw new Exception(socket_strerror(socket_last_error($this->socket_handle)));
        } else {
            echo "create socket successful\r\n";
        }
    }

    private function bindAddr()
    {
        if (!socket_bind($this->socket_handle, $this->addr, $this->port)) {
            throw new Exception(socket_strerror(socket_last_error($this->socket_handle)));
        } else {
            echo "bind addr successful\r\n";
        }
    }

    private function listen()
    {
        if (!socket_listen($this->socket_handle, $this->back_log)) {
            throw new Exception(socket_strerror(socket_last_error($this->socket_handle)));
        } else {
            echo "socket listen successful\r\n";
        }
    }

    private function accept()
    {
        $client_socket_handle = socket_accept($this->socket_handle);
        if (!$client_socket_handle) {
            echo "socket accept call failed\r\n";
            exit(1);
        } else {
            while (true) {
                $bytes_num = socket_recv($client_socket_handle, $buffer, 2048, 0);
                var_dump($bytes_num);
                var_dump($buffer);
                if (!$bytes_num) {
                    echo "socket receive failed\r\n";
                    exit(1);
                }
            }
        }
    }

    public function startServer()
    {
        try {
            $this->createSocket();
            $this->bindAddr();
            $this->listen();
            $this->accept();
        } catch (Exception $exception) {
            echo $exception->getMessage() . "\r\n";
        }
    }
}

function parseRequestLine($requestLine): array
{
    list($method, $path, $httpVersion) = explode(" ", $requestLine);
    $requestLine = [
        'method'       => $method,
        'path'         => $path,
        'http_version' => $httpVersion
    ];

    return $requestLine;
}

function parseRequestHeader($header): array
{
    $headers = [];
    array_map(function ($val) use (&$headers) {
        list($k, $v) = explode(": ", $val);
        $headers[$k] = $v;
    }, explode("\r\n", $header));

    return $headers;
}

function parseFormData($body): array
{
    $form = [];
    array_map(function ($val) use (&$form) {
        list($k, $v) = explode("=", $val);
        $form[$k] = $v;
    }, explode("&", $body));

    return $form;
}

//$server = new TcpServer(9501, "0.0.0.0");
//$server->startServer();

$package = "POST /upload HTTP/1.1\r\nHost: 0.0.0.0:9501\r\nConnection: keep-alive\r\nContent-Length: 26\r\nCache-Control: max-age=0\r\nUpgrade-Insecure-Requests: 1\r\nOrigin: null\r\nContent-Type: application/x-www-form-urlencoded\r\nUser-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36\r\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8\r\nAccept-Encoding: gzip, deflate\r\nAccept-Language: zh-CN,zh;q=0.9\r\n\r\nname=member-analysis&pwd=1";

list($requestLine, $requestInfo) = explode("\r\n", $package, 2);
list($requestHeader, $requestBody) = explode("\r\n\r\n", $requestInfo);

print_r(parseRequestLine($requestLine));
echo "\r\n";

print_r(parseRequestHeader($requestHeader));
echo "\r\n";

print_r(parseFormData($requestBody));
echo "\r\n";
