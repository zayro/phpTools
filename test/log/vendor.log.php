<?php

require '../../vendor/autoload.php';

$logger = new PHPTools\PHPErrorLog\PHPErrorLog();

print $logger->write('Prueba de write PEL_INFO...', PEL_INFO, realpath('/tmp/dev.txt'));
print $logger->write('probando...', PEL_INFO, '/tmp/dev.txt');

$logger->write('Prueba de write...');

$logger->write('Prueba de write PEL_INFO...', PEL_INFO);
