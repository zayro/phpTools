<?php

require '../../vendor/autoload.php';


$arr = array(
    'first' => 'Jason',
    'last'  => 'Doolis',
    'phone' => array(5032612314,4512392014),
    'likes' => array('animal' => 'kitten', 'color' => 'purple'),
);

// Dump out the array, short and long versions
k($arr);
krumo($arr);

// Output the array and then exit();
kd($arr);

// Return the HTML output instead of printing it out
$my_html = krumo($arr, KRUMO_RETURN);


// Output the array with all nodes expanded
krumo($arr, KRUMO_EXPAND_ALL);

// The object based method
$krumo = new Krumo;
$krumo->dump($arr);
