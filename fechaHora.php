<?php
date_default_timezone_set ('America/Argentina/San_Juan');

$DateAndTime = date('d-m-Y h:i:s a', time());  
echo "The current date and time are $DateAndTime.";
echo "<br>" ;
// Output — 11:03:37 AM
echo date('H:i:s A');
echo "<br>" ;
// Output — Thursday, 11:04:09 AM
echo date('l, h:i:s A');
echo "<br>" ; 
// Output — 13 September 2018, 11:05:00 AM
echo date('d F Y, h:i:s A');
echo "<br>" ;

?>