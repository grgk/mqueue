<?php

namespace Mqueue\Driver;

use Mqueue\Driver;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class File implements Driver
{
    private $path;

    private $fs;

    public function __construct($path = null)
    {
        $this->fs = new Filesystem();
        if (empty($path)) {
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mqueue';
        }
        $this->path = $path;
        if (!$this->fs->exists($this->path)) {
            $this->fs->mkdir($path);
        }
    }

    /**
     * Push message to the queue
     * @param string $queueName
     * @param string $message
     */
    public function pushMessage($queueName, $message)
    {
        if (!$this->fs->exists($this->path . DIRECTORY_SEPARATOR . $queueName)) {
            $this->fs->mkdir($this->path . DIRECTORY_SEPARATOR . $queueName);
        }
        $filename = uniqid(rand(), true) . '.msg';
        $this->fs->dumpFile($filename, $message);
    }

    /**
     * @param string $queueName
     * @param object $worker Worker object (must implement work() method)
     */
    public function consume($queueName, $worker)
    {
        $finder = new Finder();
        while (true) {
            $finder->files()->in($this->path . DIRECTORY_SEPARATOR . $queueName);
            foreach ($finder as $file) {
                $message = $file->getContents();
                if ($worker->work($message)) {
                    $this->fs->remove($file);
                }
            }
            sleep(3);
        }
    }
}
