<?php
$q = strtolower($_GET["msj_para"]);

require_once("conexion.php");

$sql = "SELECT * FROM Usuario WHERE Usu_Persona LIKE '$q%' ORDER BY Usu_Persona";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$total = mysqli_num_rows($result);
if (mysqli_num_rows($result)==0) {
	$datos = "{}";
}else{
	$datos = "[";
			$i=0;   
	while ($row = mysqli_fetch_array($result)){
			$i++;
			$datos .= "{\"Usu_ID\": \"".$row[Usu_ID]."\", ";
			$datos .= "\"Usu_Nombre\": \"".$row[Usu_Nombre]."\", ";
			$datos .= "\"Usu_Persona\": \"".$row[Usu_Persona]."\"}";
			if ($i<$total) 
				$datos .= ", ";
	}//fin while
	$datos .= "]";
}//fin else//*/

echo $datos;


?>