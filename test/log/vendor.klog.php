<?php

require '../../vendor/autoload.php';

use Psr\Log\LogLevel;

$users = [
    [
        'name' => 'Kenny Katzgrau',
        'username' => 'katzgrau',
    ],
    [
        'name' => 'Dan Horrigan',
        'username' => 'dhrrgn',
    ],
];

$logger = new Katzgrau\KLogger\Logger(__DIR__.'/logs');
$logger->info('Returned a million search results');
$logger->error('Oh dear.');
$logger->debug('Got these users from the Database.', $users);

$logger = new Katzgrau\KLogger\Logger(__DIR__.'/logs', Psr\Log\LogLevel::WARNING);
$logger->error('Uh Oh!'); // Will be logged
$logger->info('Something Happened Here'); // Will be NOT logged
