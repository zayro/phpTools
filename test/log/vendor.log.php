<?php

require '../../vendor/autoload.php';

$logger = new PHPTools\PHPErrorLog\PHPErrorLog();

$logger->write('Prueba de write...');

$logger->write('Prueba de write PEL_INFO...', PEL_INFO);

print $logger->write('Prueba de write PEL_INFO...', PEL_INFO, realpath('dev.log'));

print $logger->write('probando...', PEL_DEBUG, '/var/www/dev.log');
