<?php

require '../../vendor/autoload.php';

use Carbon\Carbon;

$dtToronto = Carbon::createFromDate(2012, 1, 1, 'America/Toronto');
$dtVancouver = Carbon::createFromDate(2012, 1, 1, 'America/Vancouver');

echo $dtVancouver->diffInHours($dtToronto); // 3

echo "<br>";
echo "<hr>";
echo "<br>";

$now = Carbon::now();

$nowInLondonTz = Carbon::now(new DateTimeZone('Europe/London'));

// or just pass the timezone as a string
$nowInLondonTz = Carbon::now('Europe/London');

// or to create a date with a timezone of +1 to GMT during DST then just pass an integer
echo Carbon::now(1)->tzName;

echo "<br>";
echo "<hr>";
echo "<br>";


echo (new Carbon('first day of December 2008'))->addWeeks(2);     // 2008-12-15 00:00:00
echo Carbon::parse('first day of December 2008')->addWeeks(2);


echo "<br>";
echo "<hr>";
echo "<br>";

echo $now;                               // 2016-06-24 15:18:34
$today = Carbon::today();
echo $today;                             // 2016-06-24 00:00:00
$tomorrow = Carbon::tomorrow('Europe/London');
echo $tomorrow;                          // 2016-06-25 00:00:00
$yesterday = Carbon::yesterday();
echo $yesterday;


echo "<br>";
echo "<hr>";
echo "<br>";
