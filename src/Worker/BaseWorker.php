<?php

namespace Mqueue\Worker;

class BaseWorker implements WorkerInterface
{
    /**
     * @inheritdoc
     */
    public function work($message)
    {
        $decoded = json_decode($message, true);
        if ($decoded === null || empty($decoded['worker'])) {
            // Unknown message type
            return true;
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
        $class = 'Queue\Worker\\' . $className . 'Worker';
        return new $class();
    }
}
