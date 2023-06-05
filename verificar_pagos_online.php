<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once("conexion.php");
require_once("funciones_generales.php");

$SedID = 1;
$Mes=5;
$Anio=2023;
$LecID=23;

//$sqlFiltro= " and Per_ID=8730 ";
$sqlFiltro= " ";

$sql = "SELECT * FROM pluspagos_ok";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo $sql."<br>";

$i=0;
while ($row = mysqli_fetch_array($result)){
	$ID= $row['ID'];

	$sqlb = "SELECT Rec_NotifiacionPluspagos FROM pluspagos_cuota WHERE Rec_Pagado= 1 AND Rec_NotifiacionPluspagos LIKE '%$ID%'";
	//echo $sqlb."<br>";

	$resultb = consulta_mysql_2022($sqlb,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($resultb)==0){
		echo $ID." - ".$row['Monto_Neto']." - ".$row['Fecha_Pago']." - ".$row['ID_ComercioTransaccion']." - ".$row['Informacion']."<br>";
		$i++;
	}	
	
}//fin del while

echo "<br>Total de registros: ".mysqli_num_rows($result)."<br>";
echo "<br>Total de pagos faltantes: ".$i."<br>";
echo "<br>FIN";

//exit;
?>