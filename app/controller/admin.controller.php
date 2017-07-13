<?php

require_once '../../run.php';


use library\system;
use library\auth;
use app\model\admin;
use library\csv;

$system = new system();
$auth = new auth();

if (isset($_SESSION['datos']['identificacion']) and isset($_SESSION['datos']['clave'])) {
    $admin_user  = $_SESSION['datos']['identificacion'];
    $admin_pass = $_SESSION['datos']['clave'];
    $objeto = new admin($admin_user, $admin_pass);
    $csv = new csv($admin_user, $admin_pass);
} elseif ($auth->getBearerToken() != null) {
    $auth::check($auth->getBearerToken());
    $payload = ($auth::GetData($auth->getBearerToken()));
    $admin_pass = base64_decode($payload->id);
    $admin_user = $payload->user;
    $objeto = new admin($admin_user, $admin_pass);
    $csv = new csv($admin_user, $admin_pass);
} else {
    if (isset($method) != 'OPTIONS') {
        print json_encode(array('error_autenticacion' => 'no existe un token ni session para validar datos'));
        system::cabeceras(401);
        exit();
    }
}

$url = explode(basename(__FILE__).'/', trim($returnValue, '/'));
$first = array_shift($url);
$id = array_pop($url);
$MaxArgs = count($url);

switch ($request) {
case 'GET':

if (isset($method) and $method == 'all_csv') {
    $csv->all_csv($table, $file);
}

if (isset($method) and $method == 'query_csv') {
    $csv->query_csv($sql, $file);
}

if (isset($method) and $method == 'all_excel') {
    $excel->all_excel($table, $file);
}

if (isset($method) and $method == 'search') {
    $result = $objeto->search($table, $id);
    print $result;
}

if (isset($method) and $method == 'column') {
    $result = $objeto->column_table($table);
    print $result;
}

if (isset($method) and $method == 'consult') {
    $result = $objeto->consult($field, $table);
    print $result;
}

if (isset($method) and $method == 'stored_procedure') {
    $result = $objeto->stored_procedure($procedure, $bdd);
    print $result;
}

if (isset($method) and $method == 'all_field') {
    $result = $objeto->all_field($table);
    print $result;
}

if (isset($method) and $method == 'exit') {
    $result = $objeto->exit();
    print $result;
}

break;

case 'POST':

$receives = json_decode(file_get_contents('php://input'), true);

if (isset($method) and $method == 'sql') {
    $result = $objeto->sql($receives['query']);
    print $result;
}

if (isset($method) and $method == 'filter') {
    $result = $objeto->filter($receives['field'], $receives['table'], $receives['condition']);
    print $result;
}


if (isset($method) and $method == 'add') {
    $process = '';

    foreach ($receives as $key => $val) {
        $process .= $key.'='."'$val',";
    }

    $data = substr($process, 0, -1);
    print $objeto->add($table, $data);
}

if (isset($method) and $method == 'add_last_insert') {
    $process = '';

    foreach ($receives as $key => $val) {
        $process .= $key.'='."'$val',";
    }

    $data = substr($process, 0, -1);
    print $objeto->add_last_insert($table, $data);
}

if (isset($method) and $method == 'transaction_json') {
    print $objeto->transaction_json($receives);
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
    print $objeto->change($table, $data, $id);
}

break;

case 'DELETE':

if (isset($method) and $method == 'remove') {
    print $objeto->remove($table, $id);
}

if (isset($method) and $method == 'remove_temp') {
    print $objeto->remove_temp($table, $id);
}

break;
}
