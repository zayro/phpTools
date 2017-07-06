<?php

require '../../library/pdo.class.php';

use library\DBMS;

$db = new DBMS('mysql', 'localhost', 'test', 'root', 'zayro', '3307');
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if ($dbCN == false) {
    die('Error: Cant connect to database.');
}
echo $db->getError(); //Show error description if exist, else is empty.

$result = $db->query('DELETE FROM TB_USERS WHERE ID=2;');
$getAffectedRows = $db->delete('TB_USERS', 'ID=1');
$db->disconnect();
