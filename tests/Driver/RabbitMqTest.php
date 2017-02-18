<?php
namespace Mqueue\Tests\Driver;

use Mqueue\Driver\DriverInterface;
use Mqueue\Driver\RabbitMq;
use Mqueue\Tests\Helpers\WorkerMock;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;

class RabbitMqTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject AMQPStreamConnection
     */
    private $connection;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject AMQPChannel
     */
    private $channel;

    /**
     * @var RabbitMq
     */
    private $driver;

    protected function setUp()
    {
        $this->channel = $this->getMockBuilder(AMQPChannel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection = $this->getMockBuilder(AMQPStreamConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection
            ->expects($this->any())
            ->method('channel')
            ->willReturn($this->channel);
        $this->driver = new RabbitMq($this->connection);
    }

    public function testIfItImplementsDriverInterface()
    {
        $this->assertInstanceOf(DriverInterface::class, $this->driver);
    }

    public function testPushMessage()
    {
        $this->channel
            ->expects($this->once())
            ->method('basic_publish')
            ->with(
                $this->callback(function (AMQPMessage $message) {
                    return $message->body == 'test message';
                }),
                '',
                'default'
            );
        $this->driver->pushMessage('default', 'test message');
    }

    public function testConsume()
    {
        $this->driver->loop = false;
        $worker = new WorkerMock();
        $this->driver->consume('default', $worker);
        $this->assertObjectHasAttribute('message', $worker);
        $this->assertNull($worker->message);
    }
}
