<?php
namespace Mqueue\Tests\Worker;

use Mqueue\Worker\ExampleWorker;
use PHPUnit\Framework\TestCase;

class ExampleWorkerTest extends TestCase
{
    public function testExampleWorkPass()
    {
        $message = 'example string message';
        $worker = new ExampleWorker();
        $worker->work($message);
        $this->assertEquals('example string message', $worker->message);
    }
}
