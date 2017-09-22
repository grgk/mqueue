<?php
namespace Tests\Worker;

use Tests\Helpers\WorkerMock;
use Mqueue\Worker\BaseWorker;
use PHPUnit\Framework\TestCase;

class BaseWorkerTest extends TestCase
{
    public function testWorkPass()
    {
        $message = json_encode([
            'worker' => WorkerMock::class,
            'data' => 'lorem ipsum'
        ]);
        $worker = new BaseWorker();
        $workerMock = $worker->work($message);
        $this->assertEquals('lorem ipsum', $workerMock->message);
    }

    /**
     * @expectedException \Mqueue\Exception\InvalidMessageException
     */
    public function testWorkFailOnInvalidMessageJson()
    {
        $message = json_encode("{'invalid': 'json}");
        $worker = new BaseWorker();
        $worker->work($message);
        $this->markTestIncomplete('@todo');
    }
}
