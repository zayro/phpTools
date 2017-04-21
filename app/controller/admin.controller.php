<?php

require_once '../../run.php';

use app\model\admin;
use library\system;

$objeto = new admin();
$system = new system();

$system->validar_session();

$url = explode(basename(__FILE__).'/', trim($returnValue, '/'));
$first = array_shift($url);
$id = array_pop($url);
$MaxArgs = count($url);

switch ($request) {
case 'GET':

if (isset($method) and $method == 'all_csv') {
    $csv->all_csv($table, $file);
}

if (isset($method) and $method == 'all_excel') {
    $excel->all_excel($table, $file);
}

if (isset($method) and $method == 'search') {
    $result = $objeto->search($table, $id);
    echo $result;
}

if (isset($method) and $method == 'column') {
    $result = $objeto->column_table($table);
    echo $result;
}

if (isset($method) and $method == 'consult') {
    $result = $objeto->consult($fields, $table);
    echo $result;
}

if (isset($method) and $method == 'sql') {
    $result = $objeto->sql($query);
    echo $result;
}

if (isset($method) and $method == 'filter') {
    $result = $objeto->filter($fields, $table, $condition);
    echo $result;
}

if (isset($method) and $method == 'stored_procedure') {
    $result = $objeto->stored_procedure($procedure, $bdd);
    echo $result;
}

if (isset($table) and !isset($method)) {
    $result = $objeto->all_field($table);
    echo $result;
}

break;

case 'POST':

    $receives = json_decode(file_get_contents('php://input'), true);

if (isset($method) and $method == 'add') {
      $process = '';

    foreach ($receives as $key => $val) {
        $process .= $key.'='."'$val',";
    }

    $data = substr($process, 0, -1);
    echo $objeto->add($table, $data);
}

if (isset($method) and $method == 'add_last_insert') {
      $process = '';

    foreach ($receives as $key => $val) {
        $process .= $key.'='."'$val',";
    }

    $data = substr($process, 0, -1);
    echo $objeto->add_last_insert($table, $data);
}

if (isset($method) and $method == 'transaction_json') {
    $objeto->transaction_json($receives);
}

break;

case 'PUT':

$receives = json_decode(file_get_contents('php://input'), true);

$process = '';

foreach ($receives as $key => $val) {
    $process .= $key.'='."'$val', ";
}

$data = substr($process, 0, -2);

if (isset($method) and $method == 'change') {
    echo $objeto->change($table, $data, $id);
}

break;

case 'DELETE':

if (isset($method) and $method == 'remove') {
    echo $objeto->remove($table, $id);
}

if (isset($method) and $method == 'remove_temp') {
    echo $objeto->remove_temp($table, $id);
}

break;
}
