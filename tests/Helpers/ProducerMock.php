<?php

namespace Mqueue\Tests\Helpers;

use Mqueue\Producer;

class ProducerMock extends Producer
{
    public $message;

    public $queue;

    public function __construct()
    {
        parent::__construct(new DriverMock());
    }

    public function produce($message, $queueName = 'default')
    {
        $this->message = $message;
        $this->queue = $queueName;
        return true;
    }
}
