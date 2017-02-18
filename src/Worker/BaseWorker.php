<?php

namespace Mqueue\Worker;

use Mqueue\Exception\InvalidMessageException;

class BaseWorker implements WorkerInterface
{
    /**
     * @inheritdoc
     */
    public function work($message)
    {
        $decoded = json_decode($message, true);
        if ($decoded === null || empty($decoded['worker'])) {
            throw new InvalidMessageException('Message can not be decoded');
        }
        $worker = $this->getWorker($decoded['worker']);
        return $worker->work($decoded['data']);
    }

    /**
     * Factory
     * @param $className
     * @return mixed
     *
     */
    private function getWorker($className)
    {
        $class = $className;
        return new $class();
    }
}
