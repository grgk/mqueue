<?php

namespace Mqueue\Driver;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class File implements DriverInterface
{
    private $path;

    private $fileSystem;

    /**
     * Consume in endless loop or consume single message.
     *
     * @var bool
     */
    public $loop = true;

    public function __construct($path = null)
    {
        $this->fileSystem = new Filesystem();
        if (empty($path)) {
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mqueue';
        }
        $this->path = $path;
        if (!$this->fileSystem->exists($this->path)) {
            $this->fileSystem->mkdir($path);
        }
    }

    /**
     * @inheritdoc
     */
    public function pushMessage($queueName, $message)
    {
        $queuePath = $this->path . DIRECTORY_SEPARATOR . $queueName;
        if (!$this->fileSystem->exists($queuePath)) {
            $this->fileSystem->mkdir($queuePath);
        }
        $filename = $queuePath . DIRECTORY_SEPARATOR . uniqid(rand(), true) . '.msg';
        $this->fileSystem->dumpFile($filename, $message);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function consume($queueName, $worker)
    {
        do {
            $files = $this->getFiles($queueName);
            foreach ($files as $file) {
                $message = $file->getContents();
                if ($worker->work($message)) {
                    $this->fileSystem->remove($file);
                }
            }
            unset($files);
            sleep(1);
        } while ($this->loop());
    }

    /**
     * Consume in endless loop or consume single message.
     *
     * @return bool
     */
    public function loop()
    {
        return $this->loop;
    }

    /**
     * Read files from queue.
     *
     * @param $queueName
     * @return Finder
     */
    public function getFiles($queueName)
    {
        $finder = new Finder();
        $finder->files()->name('*.msg')->in($this->path . DIRECTORY_SEPARATOR . $queueName);
        return $finder;
    }
}
