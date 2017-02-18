<?php
namespace Mqueue\Tests;

use Mqueue\Tests\Helpers\DriverMock;
use Mqueue\Producer;
use PHPUnit\Framework\TestCase;

class ProducerTest extends TestCase
{
    /**
     * @var DriverMock;
     */
    private $driver;

    /**
     * @var Producer
     */
    private $producer;

    public function setUp()
    {
        parent::setUp();
        $this->driver = new DriverMock();
        $this->producer = new Producer($this->driver);
    }

    public function testProducePassSimple()
    {
        $this->assertTrue($this->producer->produce('lorem ipsum message'));
    }

    public function testProducePassWithDefaults()
    {
        $this->producer->produce('lorem ipsum message');
        $this->assertEquals('lorem ipsum message', $this->driver->message);
        $this->assertEquals('default', $this->driver->queue);
    }

    public function testProducePassWithCustomQueue()
    {
        $this->producer->produce('custom lorem ipsum message', 'custom-queue');
        $this->assertEquals('custom lorem ipsum message', $this->driver->message);
        $this->assertEquals('custom-queue', $this->driver->queue);
    }
}
