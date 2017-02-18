<?php
/**
 * Example
 *
 * Consumes messages from custom queue (produced with 'produce-queue-file.php')
 * Uses File driver
 * After executing use CTRL+C to stop listening for messages
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use Mqueue\Driver\File;
use Mqueue\Consumer;
use Mqueue\Worker\ExampleWorker;

$queueDriver = new File(__DIR__ . '/mqueue');
$consumer = new Consumer($queueDriver);
$worker = new ExampleWorker();
$consumer->consume($worker, 'custom-queue-name');
