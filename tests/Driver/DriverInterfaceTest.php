<?php
namespace Mqueue\Tests\Driver;

use Mqueue\Driver\DriverInterface;
use Mqueue\Driver\File;
use Mqueue\Tests\Helpers\WorkerMock;
use PHPUnit\Framework\TestCase;

class DriverInterfaceTest extends TestCase
{
    /**
     * @dataProvider pushDataProvider
     * @param $driver DriverInterface
     */
    public function testPushMessage(DriverInterface $driver)
    {
        $this->assertInstanceOf('Mqueue\Driver\DriverInterface', $driver);
        $this->assertTrue($driver->pushMessage('default', 'test message'));
    }

    /**
     * @dataProvider pushDataProvider
     * @param $driver DriverInterface
     */
    public function testConsumeMessage(DriverInterface $driver)
    {
        $worker = new WorkerMock();
        $driver->loop = false;
        $driver->consume('default', $worker);
        $this->assertInstanceOf('Mqueue\Driver\DriverInterface', $driver);
        $this->assertObjectHasAttribute('message', $worker);
    }

    public function pushDataProvider()
    {
        return [
            [new File()]
        ];
    }
}
