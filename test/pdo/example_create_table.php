<?php

require '../../library/pdo.class.php';

use library\DBMS;

$db = new DBMS('mysql', 'localhost', 'test', 'root', 'zayro', '3307');
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if ($dbCN == false) {
    die('Error: Cant connect to database.');
}
echo $db->getError(); //Show error description if exist, else is empty.

$db->query('DROP TABLE IF EXISTS TB_USERS;'); //drop table if exist

print_r($db->getError());

$query_create_table = '
CREATE TABLE TB_USERS (
`ID`  int NOT NULL AUTO_INCREMENT ,
`NAME`  varchar(255) NULL ,
`ADDRESS`  varchar(255) NULL ,
`COMPANY`  varchar(255) NULL ,
PRIMARY KEY (`ID`)
);
';

$result = $db->query($query_create_table);

print_r($db->getError());

$result = $db->query('ALTER TABLE TB_USERS ADD SEX CHAR(1);');

print_r($db->getError());

$db->disconnect();
