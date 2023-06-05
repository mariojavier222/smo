<?php
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("listas.php");

echo $_SERVER['SERVER_NAME'];
echo "<br>";
echo $_SERVER['DOCUMENT_ROOT'];
exit;

$raiz = $_SERVER['DOCUMENT_ROOT']."/excellence/backups";
//echo $raiz;

$file = "$raiz/mytable".date("Ymd_His").".sql";
require_once("globales.php");
require_once("mysql_backup.php");
global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;

make_sql_backup("archivo1.sql");

?>