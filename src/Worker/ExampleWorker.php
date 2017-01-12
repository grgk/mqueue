<?php

namespace Mqueue\Worker;

class ExampleWorker implements WorkerInterface
{
    /**
     * @inheritdoc
     */
    public function work($message)
    {
        // logic
    }
}
