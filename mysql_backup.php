<?php
/******************************
Written by Keerthy (pkeerthy@gmail.com)
change the DB_HOST, DB_USER, DB_PASSWORD to your values
and call make_sql_backup(sql file path) function in your script.
******************************/
require_once("globales.php");
;
function connect_db(){
	global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;
	$conn = mysqli_connect( $gSQL_host, $gSQL_usuario, $gSQL_pass);
	//opening connection
	if(!$conn) {
		die ("Database connection failed : " . mysqli_error ());
	}
	//selecting db
	$select_db = mysql_select_db($gSQL_db, $conn);
	if(!$select_db) {
		die ("Database selection failed : " . mysqli_error ());
	}
}

function make_sql_backup($backupFile){
	$sql = "SHOW TABLES";
	$sql = "SELECT Bac_Tabla FROM TablasBackup WHERE Bac_Persona = 1";
	connect_db();
	$retval = mysql_query( $sql);
	if(! $retval )
	{
	  die('Could not retrive tables: ' . mysqli_error ());
	}else{
		while($row = mysqli_fetch_array($retval)){
			$tables[] = $row[0];
		}
	}
	$starttime = microtime(true);
	$headers = "-- MySql Data Dump\n\n";
	$headers .= "-- Database : " . db_name . "\n\n";
	$headers .= "-- Dumping started at : ". date("Y-m-d-h-i-s") .  "\n\n";

	for($t=0;$t<count($tables);$t++){
		$outputdata .= "\n\n-- Dumping data for table : $tables[$t]\n\n";
		$sql = "SELECT * FROM $tables[$t]";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)){
			$nor = count($row);
			$datas = array();
			foreach($row as $r){
				$datas[] = $r;
			}
			$lines .= "INSERT INTO $tables[$t] VALUES (";
			for($i=0;$i<$nor;$i++){
				if($datas[$i]===NULL){
					$lines .= "NULL";
				}else if((string)$datas[$i] == "0"){
					$lines .= "0";
				}else if(filter_var($datas[$i],FILTER_VALIDATE_INT) || filter_var($datas[$i],FILTER_VALIDATE_FLOAT)){
					$lines .= $datas[$i];
				}else{
					$lines .= "'" . str_replace("\n","\\n",$datas[$i]) . "'";
				}
				if($i==$nor-1){
					$lines .= ");\n";
				}else{
					$lines .= ",";
				}
			}
			$outputdata .= $lines;
			$lines = "";
		}
	}
	$headers .= "-- Dumping finished at : ". date("Y-m-d-h-i-s") .  "\n\n";
	$endtime = microtime(true);
	$diff = $endtime - $starttime;
	$headers .= "-- Dumping data of $db_name took : ". $diff .  " Sec\n\n";
	$headers .= "-- --------------------------------------------------------";
	$datadump = $headers . $outputdata;

	$file = fopen($backupFile,"w");
	$len = fwrite($file,$datadump);
	fclose($file);
	if($len != 0){
		return true;
	}else{
		return false;
	}
}
?>	