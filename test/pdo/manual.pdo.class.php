<?php

/**
 ******************************************************.
 *
 * @example How To Connect To Database.
 *******************************************************/

///1.- You need include the class file.
require './../../library/pdo.class.php';

use PhPdOrm\DBMS;

///2.- Instantiate the class with the server parameters.
// object = new DBMS(shortcut_database_type, server, database_name, user, password, port);
$db = new DBMS('mysql', '127.0.0.1', '', 'root', 'aite', '3306');

///3.- Connect to database.
$dbCN = $db->connect(); //This step is really neccesary for create connection to database, and getting the errors in methods.

///4.- Check if connection are succesful or return error.
if ($dbCN == false) {
    die('Error: Cant connect to database.');
}

///5.- If connection fail you can print the error... Note: Every operation you execute can try print this line, for get the latest error ocurred.
$db->getError(); //Show error description if exist, else is empty.

///Extras: Information about server and connection only execute this:
$db->properties();

// create database
$db->query('CREATE DATABASE demo;');

$db->query('USE demo;');

/*
******************************************************
* @example How To Create Tables.
*******************************************************/
$db->query('DROP TABLE IF EXISTS TB_DEMO;'); //drop table if exist
// Instruction SQL in variable
$query_create_table = "
CREATE TABLE TB_DEMO (
  ID INT NOT NULL AUTO_INCREMENT,
  NAME VARCHAR(100) NOT NULL,
  PHONE INT(11) NULL,
  ADDRESS VARCHAR(100) NOT NULL,
  COMPANY VARCHAR(100) NOT NULL,
  PRIMARY KEY (ID));
";
///Execute the create table statement
$db->query($query_create_table);
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

///Execute alter table statement
$db->query('ALTER TABLE TB_DEMO ADD SEX CHAR(1);');
print $db->getSql();
print "<br>";print "<hr>";print "<br>";
/**
 ******************************************************.
 *
 * @example How To Insert Rows.
 *******************************************************/
///Option 1:
$result = $db->query("INSERT INTO TB_DEMO (NAME, PHONE, ADDRESS, COMPANY) VALUES ('Evert Ulises German', 10,  'Internet #996 Culiacan Sinaloa', 'Freelancer');");
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

// $result false if operation fail.


///Option 2: Method insert(table_name, data_to_insert[field=data]);
$result = $db->insert('TB_DEMO', "NAME='Evert Ulises German', PHONE=10, ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Freelancer'");
print $db->getSql();
print "<br>";print "<hr>";print "<br>";
// $result have the inserted id or false if operation fail. IMPORTANT: For getting the currently id inserted is neccessary define the id field how primary key autoincrement.

/*
******************************************************
* @example How To Update Rows.
*******************************************************/
///Option 1:
$db->query("UPDATE TB_DEMO SET NAME='wArLeY996',COMPANY='Freelancer MX' WHERE ID=1;");
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

///Option 2: Method update(table_name, set_new_data[field=data], condition_if_need_but_not_required);
$getAffectedRows = $db->update('TB_DEMO', "NAME='wArLeY996',COMPANY='Freelancer MX'", 'ID=1'); //With Condition
$getAffectedRows = $db->update('TB_DEMO', "NAME='wArLeY996',COMPANY='Freelancer MX'"); //Without Condition (must be careful!)

/**
 ******************************************************.
 *
 * @example How To Retrieve Result Set.
 *******************************************************/
$row = $db->query('SELECT * FROM TB_DEMO', 'assoc');
print_r($row);


print "<br>";print "<hr>";print "<br>";

$row = $db->query('SELECT * FROM TB_DEMO', 'both');
print_r($row);
print "<br>";print "<hr>";print "<br>";

$row = $db->query('SELECT * FROM TB_DEMO', 'obj');
print_r($row);
print "<br>";print "<hr>";print "<br>";

$row = $db->query('SELECT * FROM TB_DEMO', 'parsing');
print_r($row);
print "<br>";print "<hr>";print "<br>";


/*
******************************************************
* @example How To Get The Total Rows.
*******************************************************/
// Once that you have execute any query, you can get total rows.
print 'Total rows: '.$db->rowcount().'<br/>';

/**
 ******************************************************.
 *
 * @example How To Get The Latest Id.
 *******************************************************/
// getLatestId(table_name, field_id);
$latestInserted = $db->getLatestId('TB_DEMO', 'ID');
//IMPORTANT: For getting the latest id inserted is neccessary define the id column how autoincrement.

/*
******************************************************
* @example How To Disconnect Database.
*******************************************************/
$db->disconnect();

/*
******************************************************
* @example How To Implement Transactions.
*******************************************************/
$db->transaction('B'); //Begin the Transaction
$db->delete('TB_DEMO', 'ID=1');
$db->delete('TB_DEMO', 'ID=2');
$db->transaction('C'); //Commit and apply changes
$db->transaction('R'); //Or you can Rollback and undo changes like Ctrl+Z
print $db->getSql();
print "<br>";print "<hr>";print "<br>";
/**
 * ------------------------------------------ SECURE METHODS PREVENT AND AVOID SQL INJECTIONS ---------------------------------------------------
 * METHOD: query_secure, "first_param": query statement, "second_param": array with params, "third_param": if you specify true, you can get the recordset, else you get true, "fourth_param": unnamed or named placeholders is your choice, "fifth_param": for change your delimiter.
 * Note: the third_param, fourth_param and fifth_param not are required, have a default values: false, false, "|" relatively.
 * IMPORTANT: the delimiter default is "|" (pipe), is neccessary change this delimiter if exist in your data.
 * ----------------------------------------------------------------------------------------------------------------------------------------------*/
/**
 ******************************************************.
 *Error: Connection to database lost.
 * @example How To Retrieve Result Set.
 *******************************************************/
///Option 1: SELECT Statement With "NAMED PLACEHOLDERS":
$params = array(':id|2|INT');
$rows = $db->query_secure('SELECT NAME FROM TB_DEMO WHERE ID=:id;', $params, true, false);
if ($rows != false) {
    foreach ($rows as $row) {
        print 'User: '.$row['NAME'].'<br />';
    }
}
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

$rows = null;
///Option 2: SELECT Statement With "UNNAMED PLACEHOLDERS":
$params = array(2);
$rows = $db->query_secure('SELECT NAME FROM TB_DEMO WHERE ID=?;', $params, true, true);
if ($rows != false) {
    foreach ($rows as $row) {
        print 'User: '.$row['NAME'].'<br />';
    }
}
$rows = null;
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

/**
 ******************************************************.
 *
 * @example How To Insert Rows.
 *******************************************************/

///Option 1: INSERT Row With "NAMED PLACEHOLDERS":
$params = array(':id|2|INT', ':name|Amy Julyssa German|STR', ':address|Internet #996 Culiacan Sinaloa|STR', ':company|Nothing|STR');
$result = $db->query_secure('INSERT INTO TB_DEMO (ID,NAME,ADDRESS,COMPANY) VALUES(:id,:name,:address,:company);', $params, false, false);
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

///Option 2: INSERT Row With "UNNAMED PLACEHOLDERS":
$params = array(2, 'Amy Julyssa German', 'Internet #996 Culiacan Sinaloa', 'Nothing');
$result = $db->query_secure('INSERT INTO TB_DEMO (ID,NAME,ADDRESS,COMPANY) VALUES(?,?,?,?);', $params, false, true);
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

///Option 3: INSERT Simple":
print "<strong>insertSingle</strong>";
print "<br>";
$db->connect();
$db->query('USE demo;');
$result = $db->insertSingle(
  'demo',
  'TB_DEMO',
  array(
  'NAME' => 'dato 4.1',
  'ADDRESS' => 'dato 4.2',
  'COMPANY' => 'dato 4.3'
  )
);

print $db->getSql();
print "<br>";
print_r($db->getError());
print "<br>";print "<hr>";print "<br>";

/**
 ******************************************************.
 *
 * @example How To Update Rows.
 *******************************************************/
///Option 1: UPDATE Rows With "NAMED PLACEHOLDERS":
$params = array(':id|2|INT', ':address|Internet #996 Culiacan, Sinaloa, Mexico|STR', ':company|Nothing!|STR');
$result = $db->query_secure('UPDATE TB_DEMO SET ADDRESS=:address,COMPANY=:company WHERE ID=:id;', $params, false, false);
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

///Option 2: UPDATE Rows With "UNNAMED PLACEHOLDERS":
$params = array('Internet #996 Culiacan, Sinaloa, Mexico', 'Nothing!', 2);
$result = $db->query_secure('UPDATE TB_DEMO SET ADDRESS=?,COMPANY=? WHERE ID=?;', $params, false, true);
print $db->getSql();
print "<br>";print "<hr>";print "<br>";
/**
 ******************************************************.
 *
 * @example How To Delete Rows.
 *******************************************************/

///Option 1:
$result = $db->query('DELETE FROM TB_DEMO WHERE ID=1;');
print $db->getSql();
print "<br>";print "<hr>";print "<br>";
// $result false if operation fail.

///Option 2: Method delete(table_name, condition_if_need_but_not_required);
$getAffectedRows = $db->delete('TB_DEMO', 'ID=1'); //With Condition
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

///Option 3: DELETE Rows With "NAMED PLACEHOLDERS":
$params = array(':id|2|INT');
$result = $db->query_secure('DELETE FROM TB_DEMO WHERE ID=:id;', $params, false, false);
print $db->getSql();
print "<br>";print "<hr>";print "<br>";

///Option 4: DELETE Rows With "UNNAMED PLACEHOLDERS":
$params = array(2);
$result = $db->query_secure('DELETE FROM TB_DEMO WHERE ID=?;', $params, false, true);
print $db->getSql();
///IMPORTANT: UPDATE and DELETE works fine but not return the affected rows, just return false if fails.
print 'AFFECTEDS -> '.($result === false) ? print_r($db->getError()) : 'YES';
print "<br>";print "<hr>";print "<br>";
/**
 ******************************************************.
 *
 * @example How To Get All Databases.
 *******************************************************/
$db->connect();
$rs = $db->ShowDBS();  //Depending of your database type you can get results
if ($rs === false) {
    print_r($db->getError());
}
foreach ($rs as $row) {
    # print_r($row);
    print "Database named: ".$row['Database']." <br/>";
}
print "<br>";print "<hr>";print "<br>";
/**
 ******************************************************.
 *
 * @example How To Get All Tables From Database.
 *******************************************************/
 $db->connect();
 $db->query('USE demo;');
$rs = $db->ShowTables('demo');  //Depending of your database type you can specify the database
foreach ($rs as $row) {
    print_r($row);
}
print "<br>";print "<hr>";print "<br>";
/**
 ******************************************************.
 *
 * @example How To Get Columns Name From Table.
 *******************************************************/
$db->connect();
$db->query('USE demo;');
$column_array = $db->columns('TB_DEMO');
if ($column_array != false) {
    foreach ($column_array as $column) {
        print "$column<br/>";
    }
} else {
    print_r($db->getError());
}
