<?php

namespace Mqueue\Worker;

class ExampleWorker implements WorkerInterface
{
    public $message;

    /**
     * @inheritdoc
     */
    public function work($message)
    {
        $this->message = $message;
        echo $message;
        return true;
    }
}
