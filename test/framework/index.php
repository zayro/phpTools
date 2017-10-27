<?php

header('Content-Type: application/json');

require_once '../../vendor/autoload.php';

use framework\admininstrar;
/*
$object = new admininstrar($table = 'informacion', $field = '', $condition = '');
$object->todos();
*/

Flight::route(' GET|POST|PUT|DELETE /@clase/@object(/@table(/@field(/@condition)))', function($clase, $object, $table, $field, $condition){

  $invoke = "\\framework\\$clase";

  call_user_func( array(new $invoke($table, $field, $condition), $object),  $_REQUEST );

});

Flight::start();
