<?php
require_once("conexion.php"); 

error_reporting(E_ALL);
ini_set('display_errors','On');

$sql = "SET NAMES UTF8";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE CajaRendida;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE CajaCorriente;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "DELETE FROM CuotaPagoDetalle";// WHERE CPD_Lec_ID=$LecID;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "DELETE FROM CuotaPago";// WHERE CuP_Lec_ID=$LecID;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE Caja;";
$sql = "DELETE FROM Caja;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE FacturaDetalle;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE SuperCajaCorriente;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE SuperCaja;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE Factura;";
$sql = "DELETE FROM Factura;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "TRUNCATE CuotaPersonaCola;";
//$sql = "DELETE FROM Factura;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "DELETE FROM CuotaPersona";// WHERE Cuo_Lec_ID=$LecID;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

echo "FIN Deuda borrada<br>";
exit;
?>