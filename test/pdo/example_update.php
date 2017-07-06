<?php

require '../../pdo.class.php';

use library\DBMS;

$db = new DBMS('mysql', 'localhost', 'test', 'root', 'zayro', '3307');
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if ($dbCN == false) {
    die('Error: Cant connect to database.');
}
echo $db->getError(); //Show error description if exist, else is empty.

$db->query("UPDATE TB_USERS SET NAME='wArLeY996',COMPANY='Freelancer MX' WHERE ID=1;");
$getAffectedRows = $db->update('TB_USERS', "NAME='Bling_Grillz',COMPANY='Freelancer MX'", 'ID=2');
$db->disconnect();
