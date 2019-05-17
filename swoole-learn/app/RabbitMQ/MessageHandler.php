<?php
/**
 * Project: hiphp
 * File: MessageHandler.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/10/19
 * Time: 10:54 AM
 */

namespace App\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageHandler
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
    }

    protected function connect()
    {
        $this->connection = new AMQPStreamConnection("", "", "", "", "");
        $this->channel = $this->connection->channel();
        $this->channel->basic_qos(0, 0, false);
        $this->channel->queue_declare("", false, true, false, false);
        $this->channel->exchange_declare("", "topic", true, true, false);
        $this->channel->queue_bind("", "", "");

    }

    public function run()
    {
        $this->channel->basic_consume(
            "queue",
            "",
            false,
            false,
            false,
            false,
            function (AMQPMessage $AMQPMessage) {
                $deliveryInfo = $AMQPMessage->delivery_info;
                $messageBody = $AMQPMessage->body;
            }
        );

        while (count($this->channel->callbacks)) {
            try {
                $this->channel->wait();
            } catch (\Exception $e) {
                $this->connection->close();
                $this->channel->close();
            }
        }
    }
}
