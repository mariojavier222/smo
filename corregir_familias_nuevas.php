<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
//include_once("comprobar_sesion.php");

date_default_timezone_set('America/Argentina/San_Juan');


$f=0;//famlias armadas
$msj='';
$cantErr=0;

$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 2;//$_SESSION['sesion_UsuID'];
$cadena='';
$i=0;

//3312
//54150039

$sql = "SELECT * FROM Familia WHERE Fam_FTi_ID = 1";// and Fam_Per_ID = 3312;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

while ($row = mysqli_fetch_array($result)) {
    $i++;   
    $PerIDHijo=$row['Fam_Per_ID'];
    $PerIDPadre=$row['Fam_Vin_Per_ID'];
    
    //busco si tiene la relación hijo-padre
	$sqlb = "SELECT * FROM Familia WHERE Fam_Vin_Per_ID = '$PerIDHijo' and Fam_FTi_ID=2;";
	$resultb = consulta_mysql_2022($sqlb,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($resultb)==0){	
		//guardo la nueva relación en la tabla Familia
		$sqlc = "INSERT INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerIDPadre', '$PerIDHijo', 2, '$UsuID', '$fecha', '$hora')";
		consulta_mysql_2022($sqlc,basename(__FILE__),__LINE__);
		$f++;//sumo una familia
	}
}

echo "Se revisaron ".$i." familias.<br />";
echo "Se corrigieron ".$f." familias.<br /><br />";
	
?>