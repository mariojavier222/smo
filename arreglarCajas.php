<?php
require_once("conexion.php");
//require_once("funciones_generales.php");

//$nrocaja=5;
arreglarCaja(5);
arreglarCaja(17);

for ($j=33; $j<220; $j++){
	actualizarSCC($j);
}//fin for

function arreglarCaja($nrocaja){
	$sql = "SELECT * FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja ORDER BY CCC_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$CCC_ID = $row['CCC_ID'];
	$sql = "SELECT SUM(CCC_Credito - CCC_Debito) AS importe FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja AND CCC_ID <> $CCC_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$importe = $row['importe'];
	echo "Caja Numero $nrocaja<br />";
	echo "Saldo: $importe<br />";
	
	$sql = "UPDATE CajaCorriente SET CCC_Debito = '$importe', CCC_Saldo = '$importe' WHERE CCC_ID = $CCC_ID";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "$sql<br />";
	
	$sql = "SELECT * FROM SuperCajaCorriente WHERE SCC_CCC_ID = $CCC_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$SCC_ID = $row['SCC_ID'];
	actualizarSCCorriente($SCC_ID, $importe);
}//fin function

function actualizarSCCorriente($SCC_ID, $importe){
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
}//fin function

function actualizarSCC($SCC_ID){
	$SCC_SCa_ID = 3;
	$sql = "SELECT SUM(SCC_Debito)AS Debito, SUM(SCC_Credito)AS Credito FROM SuperCajaCorriente WHERE SCC_SCa_ID = $SCC_SCa_ID AND SCC_ID <= $SCC_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$Debito = $row['Debito'];
		$Credito = $row['Credito'];
		$Saldo = $Credito - $Debito;
	}else{
		$Saldo = 0;
	}
	$sql = "UPDATE SuperCajaCorriente SET SCC_Saldo = '$Saldo' WHERE SCC_ID = $SCC_ID";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "$sql<br />";
}//fin function

//<>
?>