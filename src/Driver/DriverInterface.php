<?php

namespace Mqueue\Driver;

interface DriverInterface
{
    public function pushMessage($queueName, $message);
    public function consume($queueName, $worker);
}
