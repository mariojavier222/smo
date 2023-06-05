<?php
require_once("conexion.php");
//require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql = "TRUNCATE LibroIvaVentas;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$mes=6;
$anio=2018;

$sql = "SELECT * FROM Factura ORDER BY Fac_Sucursal, Fac_Numero";
$sql = "SELECT * FROM Factura WHERE MONTH(Fac_Fecha)=$mes AND  YEAR(Fac_Fecha)=$anio ORDER BY Fac_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
while ($row = mysqli_fetch_array($result)){
	$lote=1;
	set_time_limit(0);
	$fechaEmision = $row['Fac_Fecha'];
	$mes = substr($fechaEmision, 5,2);
	$anio = substr($fechaEmision, 0,4);
	$cliente = str_replace("'", "", $row['Fac_PersonaNombre']);
	$posi = strpos($cliente, "(");
	$cliente = substr($cliente, 0, $posi-1);
	$importe = $row['Fac_ImporteTotal'];
	$estado="";
	if ($row['Fac_ID_Ndec']>0){
		$importe = -$importe;
		$estado = "nota de crédito";
	}
	if ($row['Fac_Pagado']=0){		
		$estado = "anulado";
	}
	$comprobante = $row['Fac_Sucursal']."-".$row['Fac_Numero'];
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	$usuario = 1;
	$sql = "INSERT INTO LibroIvaVentas (LVe_Mes, LVe_Anio, LVe_Comprobante, LVe_Lote, LVe_FechaEmision, LVe_Cliente, LVe_Importe, LVe_Fecha, LVe_Hora, LVe_Usu_ID, LVe_Estado) VALUES($mes, $anio, '$comprobante', $lote, '$fechaEmision', '$cliente', '$importe', '$fecha', '$hora', $usuario, '$estado')";
	//echo "$sql<br>";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
}


?>