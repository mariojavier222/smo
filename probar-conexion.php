<?php

require_once("conexion.php");

 
$sql = "SELECT * FROM Usuario";

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo mysql_errno() . ": " . mysqli_error () . "\n";
if (mysqli_num_rows($result) > 0) {        
	while ($row = mysqli_fetch_array($result)) {
		echo $row['Usu_Nombre'];
	}
}

?>