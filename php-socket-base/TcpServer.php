<?php
/**
 * Project: hiphp
 * File: TcpServer.php
 *
 * Created by PhpStorm.
 * User: yxhsea
 * Email: xionghaiyang@hk01.com
 * Date: 12/2/19
 * Time: 11:09 AM
 */

class TcpServer
{
    private $port = 8080;
    private $addr = "127.0.0.1";
    private $socket_handle;
    private $back_log = 10;

    public function __construct($port = 8080, $addr = "127.0.0.1", $back_log = 10)
    {
        $this->port = $port;
        $this->addr = $addr;
        $this->back_log = $back_log;
    }

    /**
     * @throws Exception
     */
    private function createSocket()
    {
        $this->socket_handle = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$this->socket_handle) {
            throw new Exception(socket_strerror(socket_last_error($this->socket_handle)));
        } else {
            echo "create socket successful\r\n";
        }
    }

    /**
     * @throws Exception
     */
    private function bindAddr()
    {
        if (!socket_bind($this->socket_handle, $this->addr, $this->port)) {
            throw new Exception(socket_strerror(socket_last_error($this->socket_handle)));
        } else {
            echo "bind addr successful\r\n";
        }
    }

    /**
     * @throws Exception
     */
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
            echo "socket_accept call failed\r\n";
            exit(1);
        } else {
            while (true) {
                $bytes_num = socket_recv($client_socket_handle, $buffer, 100, 0);
                if (!$bytes_num) {
                    echo "socket_recv failed\r\n";
                    exit(1);
                } else {
                    echo "content from client:" . $buffer . "\r\n";
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

$server = new TcpServer();
$server->startServer();
