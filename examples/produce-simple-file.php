<?php
/**
 * Example
 *
 * Produces simple message to default queue
 * Uses File driver
 * After execute you can find message file in 'examples\mqueue\default' directory
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use Mqueue\Driver\File;
use Mqueue\Producer;

$message = [
    'data' => [
        'title' => 'lorem ipsum dolor sit'
    ]
];

$queueDriver = new File(__DIR__ . '/mqueue');
$producer = new Producer($queueDriver);
$producer->produce(json_encode($message));
