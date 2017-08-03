<?php

require '../../library/pdo.class.php';

use library\DBMS;

$db = new DBMS('mysql', 'localhost', 'test', 'root', 'zayro', '3307');
$dbCN = $db->connect(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if ($dbCN == false) {
    die('Error: Cant connect to database.');
}
echo $db->getError(); //Show error description if exist, else is empty.

$rs = $db->query('SELECT NAME,ADDRESS FROM TB_USERS');
foreach ($rs as $row) {
    $tmp_name = $row['NAME'];
    $tmp_address = $row['ADDRESS'];
    echo "The user $tmp_name lives in: $tmp_address<br/>";
}

//But if you need retrieve rows in objects, not in array... you need specify like this...
$rs = $db->query('SELECT NAME,ADDRESS FROM TB_USERS', '', 'obj');

foreach ($rs as $row) {
    $tmp_name = $row->NAME;
    $tmp_address = $row->ADDRESS;
    echo "The user $tmp_name lives in: $tmp_address<br/>";
}

$rs = null;
$db->disconnect();
