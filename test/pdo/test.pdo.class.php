<?php

//require '../../../vendor/autoload.php';
require '../../../run.php';

use Testify\Testify;
use library\DBMS;

$tf = new Testify('Test pdo.class.php');

$tf->beforeEach(function ($tf) {
    $tf->data->dbms = new DBMS('mysql', 'localhost', 'test', 'root', 'zayro', '3307');
});

$tf->test('Testing the construct() method', function ($tf) {
    $dbms = $tf->data->dbms;

    $tf->assert($dbms->connect(), 'prueba de conexion:'.$dbms->getError());
});

$tf->test('Some tests', function ($tf) {
    $tf->assert(true);
    $tf->assertFalse(!true);
    $tf->assertEquals(1337, '1337');
    $tf->assertNotEquals(array('a', 'b', 'c'), array('a', 'c', 'd'), 'Not the same order');
    $tf->assertEquals(new stdClass(), new stdClass(), 'Classes are equals');
});

$tf->test(function ($tf) {
    $tf->assert(true, 'Always true !');
    $tf->assertSame(1024, pow(2, 10));
    $tf->assertNotSame(new stdClass(), new stdClass(), 'Not the same classes !');
});

$tf->run(); // run all tests
