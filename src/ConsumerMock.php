<?php

namespace Mqueue;

use Mqueue\Driver\MockMq;
use Mqueue\Worker\WorkerInterface;

class ConsumerMock extends Consumer
{
    public function __construct()
    {
        parent::__construct(new MockMq());
    }

    public function consume(WorkerInterface $worker, $queueName = 'default')
    {
        return null;
    }
}
