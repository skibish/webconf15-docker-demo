<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pheanstalk\Pheanstalk;

require 'vendor/autoload.php';

date_default_timezone_set('Europe/Riga');

sleep(10);

$conn = new PDO('mysql:host=db;dbname=todo', 'root', 'root');

$pheanstalk = new Pheanstalk('beanstalkd');

$logger = new Monolog\Logger('worker');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

$tubeName = 'todotube';

$pheanstalk->watch($tubeName);

$logger->addInfo('Started to watch tube ' . $tubeName);

$stmt = $conn->prepare('INSERT INTO Todo (text, status, created_at, updated_at) VALUES (?, ?, ?, ?)');

while ($job = $pheanstalk->ignore('default')->reserve()) {

    $data = json_decode($job->getData(), true);

    $logger->addInfo('Got data', $data);

    $stmt->execute([$data['text'], $data['status'], $data['created_at'], '1999-12-12 12:12:12']);

    $logger->addInfo('Successfully inserted!');

    $pheanstalk->delete($job);
}
