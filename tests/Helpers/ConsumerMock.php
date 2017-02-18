<?php

namespace Mqueue\Tests\Helpers;

use Mqueue\Worker\WorkerInterface;
use Mqueue\Consumer;

class ConsumerMock extends Consumer
{
    public function __construct()
    {
        parent::__construct(new DriverMock());
    }

    public function consume(WorkerInterface $worker, $queueName = 'default')
    {
        return true;
    }
}
