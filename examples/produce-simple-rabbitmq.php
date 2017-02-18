<?php
/**
 * Example
 *
 * Produces simple message to default queue
 * Uses RabbitMq driver
 * Before executing, please set up your RabbitMq connection details below.
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use Mqueue\Driver\RabbitMq;
use Mqueue\Producer;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$message = [
    'data' => [
        'title' => 'lorem ipsum dolor sit'
    ]
];

// Connection configuration
$connection = new AMQPStreamConnection(
    'localhost', // host
    5672, // port
    'user', // user
    'pass' // password
);
$queueDriver = new RabbitMq($connection);
$producer = new Producer($queueDriver);
$producer->produce(json_encode($message));
