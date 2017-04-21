<?php

require_once '../../run.php';

use app\model\usuarios;

$url = explode(basename(__FILE__).'/', trim($returnValue, '/'));
$first = array_shift($url);
$id = array_pop($url);
$MaxArgs = count($url);

switch ($request) {
case 'GET':
{
    if ($method == 'exit') {
        $objeto->exit();
    }

    break;
}

case 'POST':

$records = json_decode(file_get_contents('php://input'), true);

if (isset($method) and $method == 'login') {
    $objeto = new usuarios($records['usuario'], $records['clave']);

    echo $objeto->login($records['usuario'], $records['clave']);
}

break;
}
