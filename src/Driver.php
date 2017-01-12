<?php

namespace Mqueue;

interface Driver
{
    public function pushMessage($queueName, $message);
    public function consume($queueName, $worker);
}
