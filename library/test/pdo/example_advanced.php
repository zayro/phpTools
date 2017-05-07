<?php

require '../../pdo.class.php';

use library\DBMS;

$db = new DBMS('mysql', 'localhost', 'test', 'root', 'zayro', '3307');
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if ($dbCN == false) {
    die('Error: Cant connect to database.');
}
echo $db->getError(); //Show error description if exist, else is empty.

echo $db->properties();
echo '<hr>';

/*
******************************************************
* @brief How To Implement Transactions.
*******************************************************/
$db->transaction('B'); //Begin the Transaction

$db->delete('TB_USERS', 'ID=1');
if ($db->getError() != '') {
    print_r($db->getError());
    $db->transaction('R');
    exit();
}

$db->delete('TB_USERS', 'ID=3');
if ($db->getError() != '') {
    print_r($db->getError());
    $db->transaction('R');
    exit();
}

$db->insert('TB_USERS', "ID=1, NAME='Yusef German',ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Aluminium'");
if ($db->getError() != '') {
    print_r($db->getError());
    $db->transaction('R');
    exit();
}

$db->insert('TB_USERS', "ID=3 | NAME='Yusef German'| ADDRESS='Tetameche #3035 Culiacan Sin. Mexico' | COMPANY= (select max(id) from TB_USERS  as alias)", '|');
if ($db->getError() != '') {
    print_r($db->getError());
    echo $db->sql;
    $db->transaction('R');
    exit();
}

$latestInserted = $db->getLatestId('TB_USERS', 'ID');

$db->insert('TB_USERS', "NAME='$latestInserted Yusef German',ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Aluminium'");
if ($db->getError() != '') {
    print_r($db->getError());
    $db->transaction('R');
    exit();
}

$db->transaction('C');

echo '<hr>';
/**
 ******************************************************.
 *
 * @brief How To Get The Latest Id.
 *******************************************************/
// getLatestId(table_name, field_id);
$latestInserted = $db->getLatestId('TB_USERS', 'ID');
echo "last inser id  $latestInserted ";
echo '<br>';
//IMPORTANT: For getting the latest id inserted is neccessary define the id column how autoincrement.

echo '<hr>';

/**
 ******************************************************.
 *
 * @brief How To Get All Databases.
 *******************************************************/
$rs = $db->ShowDBS();  //Depending of your database type you can get results

foreach ($rs as $row => $value) {
    $tmp_table = $value['Database'];
    echo "Database named: $tmp_table";
    echo '<br>';
}

echo '<hr>';
/**
 ******************************************************.
 *
 * @brief How To Get All Tables From Database.
 *******************************************************/
$rs = $db->ShowTables('test');  //Depending of your database type you can specify the database
foreach ($rs as $row => $value) {
    $tmp_table = $row[0];
    echo "The table from database is: $tmp_table<br/>";
}
echo '<hr>';

/**
 ******************************************************.
 *
 * @brief How To Get Columns Name From Table.
 *******************************************************/
$column_array = $db->columns('TB_USERS');
if ($column_array != false) {
    foreach ($column_array as $column) {
        echo "$column";
        echo '<br>';
    }
} else {
    echo $db->getError();
}
