<?php
require_once("conexion.php");

	$sql = "SELECT * FROM CajaCorrienteNN WHERE CCC_Caja_ID = 1 ORDER BY CCC_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

?>