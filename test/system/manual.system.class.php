<?php

///1.- You need include the class file.
require './../../library/system.class.php';

use PhPdOrm\system;

$instance = new system();

print "<br>";print "METODO getCurrentUri(): ";print "<br>";
echo $instance->getCurrentUri();
print "<br>";print "<HR>";print "<br>";

print "<br>";print "METODO obtener_ip(): ";print "<br>";
echo $instance->obtener_ip();
print "<br>";print "<HR>";print "<br>";

print "<br>";print "METODO ruta_actual(): ";print "<br>";
echo $instance->ruta_actual();
print "<br>";print "<HR>";print "<br>";

print "<br>";print "METODO detectBrowser(): ";print "<br>";
echo $instance->detectBrowser();
print "<br>";print "<HR>";print "<br>";

print "<br>";print "METODO detectOS(): ";print "<br>";
echo $instance->detectOS();
print "<br>";print "<HR>";print "<br>";
