<?php

namespace Mqueue\Driver;

use Mqueue\Driver;

class MockMq implements Driver
{
    public function pushMessage($queueName, $message)
    {
    }

    public function consume($queueName, $worker)
    {
    }
}
