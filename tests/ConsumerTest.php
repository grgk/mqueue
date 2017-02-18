<?php
namespace Mqueue\Tests;

use Mqueue\Tests\Helpers\DriverMock;
use Mqueue\Consumer;
use Mqueue\Producer;
use Mqueue\Worker\ExampleWorker;
use PHPUnit\Framework\TestCase;

class ConsumerTest extends TestCase
{
    /**
     * @var DriverMock
     */
    private $driver;

    /**
     * @var Producer
     */
    private $producer;

    /**
     * @var ExampleWorker
     */
    private $worker;

    public function setUp()
    {
        parent::setUp();
        $this->driver = new DriverMock();
        $this->producer = new Producer($this->driver);
        $this->worker = new ExampleWorker();
    }

    public function testConsumePassSimple()
    {
        $this->producer->produce('lorem ipsum dolor sit');
        $consumer = new Consumer($this->driver);
        $this->assertTrue($consumer->consume($this->worker));
    }

    public function testConsumePassWithDefaults()
    {
        $this->producer->produce('lorem ipsum dolor sit');
        $consumer = new Consumer($this->driver);
        $consumer->consume($this->worker);
        $this->assertEquals('lorem ipsum dolor sit', $this->driver->message);
        $this->assertEquals('default', $this->driver->queue);
        $this->assertInstanceOf(ExampleWorker::class, $this->driver->worker);
    }

    public function testConsumePassWithCustomQueue()
    {
        $this->producer->produce('custom lorem ipsum dolor sit', 'custom-queue');
        $consumer = new Consumer($this->driver);
        $consumer->consume($this->worker, 'custom-queue');
        $this->assertEquals('custom lorem ipsum dolor sit', $this->driver->message);
        $this->assertEquals('custom-queue', $this->driver->queue);
    }
}
