<?php

date_default_timezone_set('America/Argentina/San_Juan');

$UsuID = $_SESSION['sesion_UsuID'];
$LogID = $_SESSION['sesion_LogID'];

if ((!empty($UsuID)) && (!empty($LogID))) {

	$Web = $_SERVER['SCRIPT_NAME'];
	$Web = strrchr($Web, "/");
	$Web = substr($Web, 1, strlen($Web)-5);
	$Hora = date("H:i:s");
	$sql = "SELECT * FROM Opcion WHERE Opc_Comando = '$Web'";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$MenID = $row['Opc_Men_ID'];
		$OpcID = $row['Opc_ID'];
		$sql = "INSERT IGNORE INTO AccesoOpcion (Acc_Log_ID, Acc_Men_ID, Acc_Opc_ID, Acc_Hora) VALUES ($LogID, $MenID, $OpcID, '$Hora')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	}//fin if

}else header("Location: index-nuevo.php");


?>