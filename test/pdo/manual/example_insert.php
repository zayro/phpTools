<?php

require '../../library/pdo.class.php';

use library\DBMS;

$db = new DBMS('mysql', 'localhost', 'test', 'root', 'zayro', '3307');
$dbCN = $db->connect(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if ($dbCN == false) {
    die('Error: Cant connect to database.');
}
echo $db->getError(); //Show error description if exist, else is empty.

$result = $db->query("INSERT INTO TB_USERS (NAME, ADDRESS, COMPANY) VALUES ('Evert Ulises German', 'Internet Culiacan Sinaloa', 'Freelancer');");
print_r($db->getError());

$result = $db->insert('TB_USERS', "NAME='Yusef German',ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Aluminium'");
print_r($db->getError());

$db->disconnect();
