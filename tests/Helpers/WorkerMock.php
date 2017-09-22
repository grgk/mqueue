<?php

namespace Tests\Helpers;

use Mqueue\Worker\WorkerInterface;

class WorkerMock implements WorkerInterface
{
    public $message;

    /**
     * @inheritdoc
     */
    public function work($message)
    {
        $this->message = $message;
        return $this;
    }
}
