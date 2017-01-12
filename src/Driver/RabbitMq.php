<?php

namespace Mqueue\Driver;

use Mqueue\Driver;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMq implements Driver
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Push message to the queue
     * @param string $queueName
     * @param string $message
     */
    public function pushMessage($queueName, $message)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);
        $msg = new AMQPMessage($message, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($msg, '', $queueName);
        $channel->close();
    }

    /**
     * @param string $queueName
     * @param object $worker Worker object (must implement work() method)
     */
    public function consume($queueName, $worker)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);
        $callback = function ($message) use ($worker) {
            if ($worker->work($message->body)) {
                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            }
        };
        $channel->basic_consume($queueName, '', false, false, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}
