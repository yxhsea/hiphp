<?php
/**
 * Project: hiphp
 * File: TcpClient.php
 *
 * Created by PhpStorm.
 * User: yxhsea
 * Email: xionghaiyang@hk01.com
 * Date: 12/2/19
 * Time: 11:21 AM
 */

class TcpClient
{
    private $server_port;
    private $server_addr;
    private $socket_handle;

    public function __construct($port = 8080, $addr = "127.0.0.1")
    {
        $this->server_port = $port;
        $this->server_addr = $addr;
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

    public function connectToServer()
    {
        $this->createSocket();
        if (!socket_connect($this->socket_handle, $this->server_addr, $this->server_port)) {
            echo socket_strerror(socket_last_error($this->socket_handle)) . "\r\n";
            exit(1);
        } else {
            while (true) {
                $data = fgets(STDIN);
                //
                if (strcmp($data, "quit") == 0) {
                    break;
                }
                socket_write($this->socket_handle, $data);
            }
        }
    }
}

$client = new TcpClient();
$client->connectToServer();
