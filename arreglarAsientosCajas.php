<?php
require_once("conexion.php");
//require_once("funciones_generales.php");


$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql = "TRUNCATE AsientoCajaDetalle;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql = "TRUNCATE AsientoCaja;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);


$sql = "SELECT * FROM Caja ORDER BY Caja_ID DESC LIMIT 0,5;";
$sql = "SELECT * FROM Caja WHERE Caja_ID=282";
$resultPrincipal = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
while ($rowPrincipal = mysqli_fetch_array($resultPrincipal)){
	$Caja_ID = $rowPrincipal['Caja_ID'];
	set_time_limit(20);
	echo "Caja Num: $Caja_ID<br>";
	//buscamos los detalles de cada caja
	$sql = "SELECT * FROM CajaCorriente WHERE CCC_Caja_ID = $Caja_ID ORDER BY CCC_ID";
	echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$Asi_Orden=0;
	while ($row = mysqli_fetch_array($result)){
		$Asi_Orden++;
		$Asi_For_ID = $row['CCC_For_ID'];
		$Asi_CCC_ID = $row['CCC_ID'];
		$datosCuota = $row['CCC_Referencia'];
		if (empty($datosCuota)){
			$Asi_CTi_ID = -1;
		}else{
			list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Asi_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
		}
		$Asi_Debe = $row['CCC_Debito'];
		$Asi_Haber = $row['CCC_Credito'];
		$Asi_Fecha = $row['CCC_Fecha'];
		$Asi_FechaHora = date("Y-m-d H:i:s");
		$Asi_Usu_ID = 1;
		
		set_time_limit(0);

		if ($Asi_Debe!=$Asi_Haber){
			$sql = "INSERT INTO AsientoCajaDetalle (Asi_Caja_ID, Asi_Fecha, Asi_For_ID, Asi_CTi_ID, Asi_Orden, Asi_Debe, Asi_Haber, Asi_Saldo, Asi_FechaHora, Asi_Usu_ID, Asi_CCC_ID) VALUES('$Caja_ID', '$Asi_Fecha', '$Asi_For_ID', '$Asi_CTi_ID', '$Asi_Orden', '$Asi_Debe', '$Asi_Haber', '$Asi_Saldo', '$Asi_FechaHora', '$Asi_Usu_ID', '$Asi_CCC_ID')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}

		

	}//fin while

}//fin while
//*/

$sql = "SELECT Asi_Caja_ID, Asi_For_ID, For_Nombre, SUM(Asi_Haber) AS Haber FROM AsientoCajaDetalle INNER JOIN FormaPago ON (For_ID = Asi_For_ID) GROUP BY Asi_Caja_ID, Asi_For_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
while ($row = mysqli_fetch_array($result)){
	$AsC_Caja_ID = $row['Asi_Caja_ID'];
	$AsC_Concepto = $row['For_Nombre'];
	$AsC_For_ID = $row['Asi_For_ID'];
	$AsC_Debe = floatval($row['Haber']);// - $row['Debe']);
	$AsC_Haber = 0;
	$AsC_CTi_ID = -1;
	$AsC_FechaHora = date("Y-m-d H:i:s");;
	$AsC_Usu_ID = 1;
	$AsC_Tipo = "ingreso";
	set_time_limit(20);
	$sql = "INSERT INTO AsientoCaja (AsC_Caja_ID, AsC_Concepto, AsC_Debe, AsC_Haber, AsC_For_ID, AsC_CTi_ID, AsC_FechaHora, AsC_Usu_ID, AsC_Tipo) VALUES('$AsC_Caja_ID', '$AsC_Concepto', '$AsC_Debe', '$AsC_Haber', '$AsC_For_ID', '$AsC_CTi_ID', '$AsC_FechaHora', '$AsC_Usu_ID', '$AsC_Tipo')";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT Asi_Caja_ID, Asi_For_ID, CTi_Nombre, Asi_CTi_ID, SUM(Asi_Haber) AS Haber FROM AsientoCajaDetalle INNER JOIN CuotaTipo ON (CTi_ID = Asi_CTi_ID) WHERE Asi_For_ID = $row[Asi_For_ID] AND Asi_Caja_ID = $AsC_Caja_ID GROUP BY Asi_Caja_ID, Asi_For_ID, Asi_CTi_ID";
	$sql = "SELECT CTi_Nombre, Asi_CTi_ID, SUM(Asi_Haber) AS Haber FROM AsientoCajaDetalle INNER JOIN CuotaTipo ON (CTi_ID = Asi_CTi_ID) WHERE Asi_For_ID = $row[Asi_For_ID] AND Asi_Caja_ID = $AsC_Caja_ID GROUP BY Asi_CTi_ID";
	//echo $sql;
	$resultDet = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($rowDet = mysqli_fetch_array($resultDet)){
		
		$AsC_Concepto = "    a ".$rowDet['CTi_Nombre'];
		$AsC_Haber = floatval($rowDet['Haber']);// - $row['Debe']);
		$AsC_Debe = 0;

		$sql = "INSERT INTO AsientoCaja (AsC_Caja_ID, AsC_Concepto, AsC_Debe, AsC_Haber, AsC_For_ID, AsC_CTi_ID, AsC_FechaHora, AsC_Usu_ID, AsC_Tipo) VALUES('$AsC_Caja_ID', '$AsC_Concepto', '$AsC_Debe', '$AsC_Haber', '$AsC_For_ID', '$AsC_CTi_ID', '$AsC_FechaHora', '$AsC_Usu_ID', '$AsC_Tipo')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}


}//fin while



echo "FIN";

/*function actualizarSaldo($SCC_ID, $importe){
	$SCC_SCa_ID = 3;
	$sql = "SELECT SUM(SCC_Debito)AS Debito, SUM(SCC_Credito)AS Credito FROM SuperCajaCorriente WHERE SCC_SCa_ID = $SCC_SCa_ID AND SCC_ID < $SCC_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$Debito = $row['Debito'];
		$Credito = $row['Credito'] + $importe;
		$Saldo = $Credito - $Debito;
	}else{
		$Saldo = 0;
	}
	$sql = "UPDATE SuperCajaCorriente SET SCC_Saldo = '$Saldo', SCC_Credito = '$importe' WHERE SCC_ID = $SCC_ID";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "$sql<br />";
}//fin function*/


//<>
?>