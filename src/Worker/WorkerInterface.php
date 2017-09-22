<?php

namespace Mqueue\Worker;

interface WorkerInterface
{
    /**
     * Process message.
     *
     * @param $message
     * @return bool Return true on success, otherwise message will be requeued
     */
    public function work($message);
}
