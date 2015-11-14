<?php

require 'vendor/autoload.php';

date_default_timezone_set('Europe/Riga');

$faker = Faker\Factory::create();

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pheanstalk\Pheanstalk;

$pheanstalk = new Pheanstalk('beanstalkd');

$logger = new Monolog\Logger('worker');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

sleep(10);

while (true) {

    sleep(3);

    $originalData = [
        'text'       => $faker->realText(60),
        'status'     => (int)$faker->boolean(),
        'created_at' => date('Y-m-d H:i:s', time())
    ];

    $data = json_encode($originalData);

    $logger->addInfo('Data to put', $originalData);

    $pheanstalk
        ->useTube('todotube')
        ->put($data);

    $logger->addInfo('Done!');
}
