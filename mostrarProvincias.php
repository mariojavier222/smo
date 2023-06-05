<?php
require_once("conexion.php");

$Pais = $_POST['Pais'];
//echo "Alg$Pais o"; exit;
if (!empty($Pais)){
	$sql = "SELECT Pro_Nombre FROM Provincia WHERE Pro_Pai_ID = $Pais ORDER BY Pro_Nombre";
//	echo $sql;exit;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<hr />";
	while ($row = mysqli_fetch_array($result)){
		echo "$row[Pro_Nombre]<br />";
	}//fin while

}
?>
