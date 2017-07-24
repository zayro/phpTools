<?php

error_reporting(1);

require_once __DIR__.'/../../vendor/autoload.php';

use PhPdOrm\DBMS;
use Testify\Testify;

// all methods
#print_r($_REQUEST);
// only method post, update, delete
#print(file_get_contents("php://input"));
$tf = new Testify("PDO class Test");

$tf->beforeEach(function ($tf) {
    $tf->data->instance = new DBMS('mysql', '127.0.0.1', '', 'marlon', 'zayro', '3307');
    $tf->data->instance->connect();
    $tf->data->instance->query('use demo;');
});


$tf->afterEach(function ($tf) {
    $tf->data->instance->disconnect();
});

$tf->test("Testing process() method", function ($tf) {
    $db = $tf->data->instance;

    $tf->assert($db->connect() == true, "conexion a la base de datos");

    $db->query('CREATE DATABASE IF NOT EXISTS demo;');

    $db->query('use demo;');

    $db->query('DROP TABLE IF EXISTS `TB_DEMO`;');

    $db->query('DROP PROCEDURE IF EXISTS GetDemo;');
    
    $db->query('DROP PROCEDURE IF EXISTS GetAllDemo;');

    $query_create_table = "CREATE TABLE TB_DEMO (
    ID INT NOT NULL AUTO_INCREMENT,
    NAME VARCHAR(100) NOT NULL,
    ADDRESS VARCHAR(100) NOT NULL,
    COMPANY VARCHAR(100) NOT NULL,
    PRIMARY KEY (ID));
    ";

    $quert_create_procedure_param = "CREATE PROCEDURE GetDemo(IN parametro INT(11))
    BEGIN
    SELECT * 
    FROM TB_DEMO
    WHERE id = parametro;
    END";

    $query_create_procedure = "CREATE PROCEDURE GetAllDemo()
    BEGIN
    SELECT * FROM TB_DEMO;
    END";

    $process =  $db->query($query_create_table);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear tabla TB_DEMO".$msg);
    
    $process =  $db->query($quert_create_procedure_param);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear procedimiento almacenado con parametros".$msg);

    $process =  $db->query($query_create_procedure);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear procedimiento almacenado ".$msg);

    $process = $db->query("truncate TB_DEMO;");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "limpia la tabla".$msg);
});

$tf->test("Testing insert() method", function ($tf) {
    $db = $tf->data->instance;

    $process = $db->insert('TB_DEMO', "NAME='Evert Ulises German',ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Freelancer'");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear registros metodo insert".$msg);
         
    $result = $db->insertSingle('TB_DEMO', array(  'NAME' => 'dato 4.1',  'ADDRESS' => 'dato 4.2',  'COMPANY' => 'dato 4.3'  ));
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "crear registros metodo insertSingle".$msg);

    $result = $db->insertMultiple('TB_DEMO', array( array(  'NAME' => 'dato 4.1',  'ADDRESS' => 'dato 4.2',  'COMPANY' => 'dato 4.3'  ), array(  'NAME' => 'dato 4.1',  'ADDRESS' => 'dato 4.2',  'COMPANY' => 'dato 4.3'  )));
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

    $process = $db->query('SELECT NAME, ADDRESS FROM TB_DEMO', 'obj');
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert(is_object($process), "selecciona registros modo object".$msg);
    
    $process = $db->query('SELECT NAME, ADDRESS FROM TB_DEMO', 'assoc');
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert(is_array($process), "selecciona registros modo assoc".$msg);

    $process = $db->query('SELECT NAME, ADDRESS FROM TB_DEMO', 'both');
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert(is_array($process), "selecciona registros modo both".$msg);

    $process = $db->query('SELECT NAME, ADDRESS FROM TB_DEMO', 'named');
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert(is_array($process), "selecciona registros modo named".$msg);
});

$tf->test("Testing call() method", function ($tf) {
    $db = $tf->data->instance;

    $process = $db->StoredProcedure("call GetAllDemo();");
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert($process == true, "ejecuta procedimiento almacenado".$msg);

    
    $process = $db->StoredProcedure("call GetDemo(?);", array(20), true);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert(is_array($process), "ejecuta procedimiento almacenado metodo 1".$msg);

    $params = array(20);
    $process = $db->query_secure("call GetDemo(?);", $params, true, true);
    $msg = ($db->getError() == null) ? '': '<br><hr><br>'.json_encode($db->getError(), JSON_PRETTY_PRINT);
    $tf->assert(is_array($process), "ejecuta procedimiento almacenado metodo 2".$msg);
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
