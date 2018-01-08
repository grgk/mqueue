<?php

namespace Tests\Helpers;

use Mqueue\Driver\DriverInterface;

class DriverMock implements DriverInterface
{
    public $queue;

    public $message;

    public $worker;

    public function pushMessage($queueName, $message)
    {
        $this->queue = $queueName;
        $this->message = $message;
        return true;
    }

    public function consume($queueName, $worker)
    {
        $this->queue = $queueName;
        $this->worker = $worker;
        return true;
    }
}
