<?php
include_once("conexion.php");

error_reporting(E_ALL);
ini_set('display_errors','On');

echo "<br>INICIO<br><br>";

$sql = "SELECT * FROM Opcionee ";
echo $sql;

consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

echo "<br><br>FIN";
?> 