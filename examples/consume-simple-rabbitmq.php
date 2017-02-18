<?php
/**
 * Example
 *
 * Consumes messages from default queue (produced with 'produce-simple-rabbitmq.php')
 * Uses File driver
 * Before executing, please set up your RabbitMq connection details below.
 * After executing use CTRL+C to stop listening for messages
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Mqueue\Driver\RabbitMq;
use Mqueue\Consumer;
use Mqueue\Worker\ExampleWorker;

// Connection configuration
$connection = new AMQPStreamConnection(
    '192.168.11.11', // host
    5672, // port
    'user', // user
    'pass' // password
);
$queueDriver = new RabbitMq($connection);
$consumer = new Consumer($queueDriver);
$worker = new ExampleWorker();
$consumer->consume($worker);
