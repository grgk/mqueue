<?php

namespace Mqueue;

use Mqueue\Driver\MockMq;

class ProducerMock extends Producer
{
    public function __construct()
    {
        parent::__construct(new MockMq());
    }

    public function produce($message, $queueName = 'default')
    {
        return null;
    }
}
