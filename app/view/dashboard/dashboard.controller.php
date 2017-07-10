<?php

require_once '../../../run.php';

use app\view\dashboard;
use library\system;

$system = new system();

$objeto = new dashboard();

$system->validar_session();

$url = explode(basename(__FILE__).'/', trim($returnValue, '/'));
$first = array_shift($url);
$id = array_pop($url);
$MaxArgs = count($url);

switch ($request) {
case 'GET':

if (isset($method) and $method == 'estadistica') {
    $result = $objeto->estadistica();
    echo $result;
}

if (isset($method) and $method == 'ventas') {
    $result = $objeto->ventas();
    echo $result;
}

if (isset($method) and $method == 'compras') {
    $result = $objeto->compras();
    echo $result;
}

if (isset($method) and $method == 'servicios') {
    $result = $objeto->servicios();
    echo $result;
}
break;
}
