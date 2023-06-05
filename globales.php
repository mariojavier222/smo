<?php
include_once("globalesConstantes.php");

//Variables globales del sistema

$gNombreSesion = md5(uniqid(rand()));
$gIndex = "index2.php";


$gSQL_host = "localhost";
$gSQL_usuario = "napta"; // Usuario de Mysql
$gSQL_pass = "napta"; // contrase a de Mysql
$gSQL_db = "smo"; // Base de datos que se usará en producción.*/

//para test
//$gSQL_db_test="napta_smo_test";     // Base de datos que se usará para test.*/

$gMes[1] = "Enero";
$gMes[2] = "Febrero";
$gMes[3] = "Marzo";
$gMes[4] = "Abril";
$gMes[5] = "Mayo";
$gMes[6] = "Junio";
$gMes[7] = "Julio";
$gMes[8] = "Agosto";
$gMes[9] = "Septiembre";
$gMes[10] = "Octubre";
$gMes[11] = "Noviembre";
$gMes[12] = "Diciembre";
?>