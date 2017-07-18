<?php

error_reporting(1);

require_once __DIR__.'/../../vendor/autoload.php';

use library\DBMS;
use Testify\Testify;

$tf = new Testify("PDO class Test");

$tf->beforeEach(function ($tf) {
    $tf->data->instance = new DBMS('mysql', '127.0.0.1', '', 'root', 'aite', '3306');
    $tf->data->instance->connect();
});


$tf->afterEach(function ($tf) {    
    $tf->data->instance->disconnect();
});
$tf->test("Testing process() method", function ($tf) {
    $db = $tf->data->instance;

    $tf->assert($db->connect() == true, "conexion a la base de datos");

    $db->query('DROP DATABASE IF EXISTS  demo;');

    $db->query('CREATE DATABASE demo;');

    $db->query('use demo;');

    $db->query('DROP TABLE IF EXISTS `TB_DEMO`;');

    $query_create_table = "
    CREATE TABLE TB_DEMO (
    ID INT NOT NULL AUTO_INCREMENT,
    NAME VARCHAR(100) NOT NULL,
    ADDRESS VARCHAR(100) NOT NULL,
    COMPANY VARCHAR(100) NOT NULL,
    PRIMARY KEY (ID));
    ";
    
    ///Execute the create table statement
    $process =  $db->query($query_create_table);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear tabla TB_DEMO".$msg);

    $process = $db->query("truncate TB_DEMO;");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "limpia la tabla".$msg);

    $db->disconnect();
});

$tf->test("Testing insert() method", function ($tf) {
    $db = $tf->data->instance;

    $db->query('use demo;');

    $process = $db->insert('TB_DEMO', "NAME='Evert Ulises German',ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Freelancer'");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear registros metodo insert".$msg);
         
    $result = $db->insertSingle('demo', 'TB_DEMO', array(  'NAME' => 'dato 4.1',  'ADDRESS' => 'dato 4.2',  'COMPANY' => 'dato 4.3'  ));
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear registros metodo insertSingle".$msg);

    $result = $db->insertMultiple('demo', 'TB_DEMO', array( array(  'NAME' => 'dato 4.1',  'ADDRESS' => 'dato 4.2',  'COMPANY' => 'dato 4.3'  ), array(  'NAME' => 'dato 4.1',  'ADDRESS' => 'dato 4.2',  'COMPANY' => 'dato 4.3'  )));
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear registros metodo insertMultiple".$msg);

    $params = array(":id|20|INT", ":name|Amy Julyssa German|STR", ":address|Internet #996 Culiacan Sinaloa|STR", ":company|Nothing|STR");
    $process = $db->query_secure("INSERT INTO TB_DEMO (ID,NAME,ADDRESS,COMPANY) VALUES(:id,:name,:address,:company);", $params, false, false);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear registros metodo query_secure 1".$msg);

    $params = array(22, "Amy Julyssa German", "Internet #996 Culiacan Sinaloa", "Nothing");
    $process = $db->query_secure("INSERT INTO TB_DEMO (ID,NAME,ADDRESS,COMPANY) VALUES(?,?,?,?);", $params, false, true);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear registros metodo query_secure 2".$msg);

    $process = $db->query("INSERT INTO TB_DEMO (NAME, ADDRESS, COMPANY) VALUES ('Evert Ulises German', 'Internet #996 Culiacan Sinaloa', 'Freelancer');");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assertFalse($process == true, "crear registros metodo query".$msg);

    $db->disconnect();
});

$tf->test("Testing update() method", function ($tf) {
    $db = $tf->data->instance;

    $db->query('use demo;');

    $process = $db->update("TB_DEMO", "NAME='wArLeY996',COMPANY='Freelancer MX'", "ID=1");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "actualizar registros metodo update".$msg);

    $params = array(":id|2|INT", ":address|Internet #996 Culiacan, Sinaloa, Mexico|STR", ":company|Nothing!|STR");
    $process = $db->query_secure("UPDATE TB_DEMO SET ADDRESS=:address,COMPANY=:company WHERE ID=:id;", $params, false, false);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "actualizar registros metodo query_secure 1".$msg);

    $params = array("Internet #996 Culiacan, Sinaloa, COLOMBIAQ", "Nothing!", 2);
    $process = $db->query_secure("UPDATE TB_DEMO SET ADDRESS=?,COMPANY=? WHERE ID=?;", $params, false, true);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "actualizar registros metodo query_secure 2".$msg);

    $process = $db->query("UPDATE TB_DEMO SET NAME='wArLeY996',COMPANY='Freelancer MX' WHERE ID=1;");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assertFalse($process == true, "actualizar registros metodo query".$msg);

    
});

$tf->test("Testing select() method", function ($tf) {
    $db = $tf->data->instance;
    $db->query('use demo;');

    
    $process = $db->query('SELECT NAME, ADDRESS FROM TB_DEMO', 'array');    
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);    
    $tf->assert(is_array($process), "selecciona registros modo array".$msg);

    $process = $db->query('SELECT NAME, ADDRESS FROM TB_DEMO', 'object'); 
    print_r($process);   
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);    
    $tf->assert(is_object($process), "selecciona registros modo object".$msg);    


    
});

$tf->test("Testing call() method", function ($tf) {
    $db = $tf->data->instance;
    $db->query('use demo;');


    $db->disconnect();
});

$tf->test("Testing delete() method", function ($tf) {
    $db = $tf->data->instance;
    $db->query('use demo;');

    $process = $db->delete('TB_DEMO', 'ID=1');
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "eliminacion registros metodo delete".$msg);

    $params = array(':id|2|INT');
    $result = $db->query_secure('DELETE FROM TB_DEMO WHERE ID=:id;', $params, false, false);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "eliminacion registros metodo query_secure 1".$msg);

    $params = array(2);
    $result = $db->query_secure('DELETE FROM TB_DEMO WHERE ID=?;', $params, false, true);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "eliminacion registros metodo query_secure 2".$msg);

    $process = $db->query('DELETE FROM TB_DEMO WHERE ID=1;');
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assertFalse($process == true, "eliminacion registros metodo query".$msg);


    $db->disconnect();
});

$tf->run();



