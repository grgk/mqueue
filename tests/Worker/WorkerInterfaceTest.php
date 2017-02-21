<?php
namespace Mqueue\Tests\Worker;

use Mqueue\Worker\ExampleWorker;
use Mqueue\Worker\WorkerInterface;
use PHPUnit\Framework\TestCase;

class WorkerInterfaceTest extends TestCase
{
    /**
     * @dataProvider workDataProvider
     * @param $worker WorkerInterface
     */
    public function testWork(WorkerInterface $worker)
    {
        $this->assertInstanceOf('Mqueue\Worker\WorkerInterface', $worker);
        $message = 'example string message';
        $worker->work($message);
        $this->assertEquals('example string message', $worker->message);
    }

    public function workDataProvider()
    {
        return [
            [new ExampleWorker()]
        ];
    }
}
