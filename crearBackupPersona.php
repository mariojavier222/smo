<?php
//MySQL connection parameters
require_once("globales.php");
global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;

$dbhost = $gSQL_host;
$dbuser = $gSQL_usuario;
$dbpsw = $gSQL_pass;
$dbname = $gSQL_db;

//Connects to mysql server
$connessione = @mysqli_connect($dbhost,$dbuser,$dbpsw);
set_time_limit(0);
//Set encoding
mysql_query("SET CHARSET utf8");
mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");

//Includes class
require_once('DumpTablas/FKMySQLDump.php');


//Creates a new instance of FKMySQLDump: it exports without compress and base-16 file
$archivo = "DumpSistema/TodoPersonas".date("Ymd_His").".tar";
$dumper = new MySQLDump($dbname,$archivo,true,false, "Bac_Persona");

$params = array(
	//'skip_structure' => TRUE,
	//'skip_data' => TRUE,
);

//Make dump
$dumper->doDump($params);

?>