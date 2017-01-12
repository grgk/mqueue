<?php

namespace Mqueue;

/**
 * Usage:
 * $publisher = new Producer();
 * $publisher->produce('test message');
 * or
 * $publisher->produce('test message', $queueName);
 */
class Producer
{
    /**
     * Queue driver
     * @var Driver
     */
    public $driver;

    /**
     * Producer constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Produce new message and push to the queue
     * @param string $queueName
     * @param string $message
     */
    public function produce($message, $queueName = 'default')
    {
        $this->driver->pushMessage($queueName, $message);
    }
}
