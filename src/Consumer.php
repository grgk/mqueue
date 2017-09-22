<?php

namespace Mqueue;

use Mqueue\Driver\DriverInterface;
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
     * Queue driver.
     *
     * @var DriverInterface
     */
    public $driver;

    /**
     * Consumer constructor.
     *
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Start listening for messages - worker loop.
     *
     * @param WorkerInterface $worker Worker object implementing work() method for processing messages
     * @param string $queueName
     */
    public function consume(WorkerInterface $worker, $queueName = 'default')
    {
        return $this->driver->consume($queueName, $worker);
    }
}
