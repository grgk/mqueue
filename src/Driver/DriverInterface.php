<?php

namespace Mqueue\Driver;

interface DriverInterface
{
    /**
     * Push message to the queue.
     *
     * @param string $queueName
     * @param string $message
     * @return bool
     */
    public function pushMessage($queueName, $message);

    /**
     * Consume queue.
     *
     * @param string $queueName
     * @param object $worker Worker object (must implement work() method)
     */
    public function consume($queueName, $worker);
}
