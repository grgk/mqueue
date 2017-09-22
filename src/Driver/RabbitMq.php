<?php

namespace Mqueue\Driver;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMq implements DriverInterface
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * Consume in loop or consume single message.
     *
     * @var bool
     */
    public $loop = true;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Push message to the queue.
     *
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
     * Consume queue.
     *
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
        do {
            $channel->wait();
        } while ($this->loop() && count($channel->callbacks));
    }

    /**
     * Consume in loop or consume single message.
     *
     * @return bool
     */
    public function loop()
    {
        return $this->loop;
    }
}
