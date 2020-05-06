<?php

///1.- You need include the class file.
require './../../library/file.class.php';

use library\file;

$instance = new file();

print "<br>";print "METODO crear_carpeta(): ";print "<br>";
echo $instance->crear_carpeta('nueva');
print "<br>";print "<HR>";print "<br>";
