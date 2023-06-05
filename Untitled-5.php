<?php
require_once("conexion.php");
//require_once("funciones_generales.php");

$nrocaja=5;
$sql = "SELECT SUM(CCC_Credito - CCC_Debito) AS importe FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$importe = $row['importe'];
echo "Caja Nº 5<br />";
echo "Saldo: $importe<br />";
?>