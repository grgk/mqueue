<?php

namespace Mqueue;

use Mqueue\Worker\WorkerInterface;

/**
 * Usage:
 * $consumer = new Consumer();
 * $consumer->consume($worker);
 * or
 * $consumer->consume($worker, $queueName);
 */
class Consumer
{
    /**
     * Queue driver
     * @var Driver
     */
    public $driver;

    /**
     * Consumer constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Start listening for messages - worker loop
     * @param string $queueName
     * @param WorkerInterface $worker Worker object implementing work() method for processing messages
     */
    public function consume(WorkerInterface $worker, $queueName = 'default')
    {
        $this->driver->consume($queueName, $worker);
    }
}
