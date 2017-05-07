<?php
#iniciar sessiones
session_start();

$origen = isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'*';

header("Access-Control-Allow-Origin:  $origen ");

header("Access-Control-Allow-Credentials: true");

header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Toke');

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

# habilitar compresiones
#ini_set("zlib.output_compression", "On");


# activar o desactivar mensajes de error
error_reporting(E_ALL);
ini_set("display_errors", 1);

set_time_limit(0);


#require_once __DIR__.'/config/database.php';


# pruebas de testeo

/*
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_BAIL, 1);
assert_options(ASSERT_CALLBACK, 'testeo');

function testeo($script, $line, $message) {
  echo "<h1>Condicion fallo!</h1><br />
        Script: <strong>$script</strong><br />
        Linea: <strong>$line</strong><br />
        Condicion: <br /><pre>$message</pre>";
}
*/


# zona horaria
date_default_timezone_set('America/Bogota');

/*
$token = null;

$headers = apache_request_headers();

if(isset($headers['Authorization']) and !empty($headers['Authorization'])){

$token = $headers['Authorization'];

}
*/


extract($_REQUEST);

$request = strtoupper($_SERVER['REQUEST_METHOD']);

$returnValue = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

require_once __DIR__.'/vendor/autoload.php';
