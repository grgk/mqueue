<?php
namespace Mqueue\Tests\Driver;

use Mqueue\Driver\File;
use Mqueue\Tests\Helpers\WorkerMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FileTest extends TestCase
{
    /**
     * @var string Queue path
     */
    protected $path;

    /**
     * @var File
     */
    protected $driver;

    /**
     * @var WorkerMock
     */
    protected $worker;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    public function setUp()
    {
        parent::setUp();
        $this->path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mqueue';
        $this->driver = $this->getMockBuilder(File::class)->getMock();
        $this->driver->expects($this->any())->method('loop')->willReturn(false);
        $this->worker = new WorkerMock();
        $this->fileSystem = new Filesystem();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->fileSystem->remove($this->path);
    }

    public function testPushMessage()
    {
        $driver = new File();
        $response = $driver->pushMessage('default-test', 'Lorem ipsum test message');
        $this->assertTrue($response);

        $finder = new Finder();
        $finder->files()->name('*.msg')->in($this->path . DIRECTORY_SEPARATOR . 'default-test');
        foreach ($finder as $file) {
            $message = $file->getContents();
            $this->assertEquals('Lorem ipsum test message', $message);
            $this->fileSystem->remove($file);
        }
    }

    public function testConsumeNoMessagePass()
    {
        $this->driver->consume('default', $this->worker);
        $this->assertObjectHasAttribute('message', $this->worker);
        $this->assertNull($this->worker->message);
    }

    public function testConsumeWithMessagePass()
    {
        $driver = new File();
        $driver->pushMessage('default-test', 'Lorem ipsum test message for consume');
        $driver->loop = false;
        $driver->consume('default-test', $this->worker);
        $this->assertObjectHasAttribute('message', $this->worker);
        $this->assertEquals('Lorem ipsum test message for consume', $this->worker->message);
    }
}
