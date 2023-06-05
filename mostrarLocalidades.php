<?php
require_once("conexion.php");

$Nombre = $_POST['Nombre'];
$Pais = $_POST['Pais'];
//echo "Alg$Pais o"; exit;
if (!empty($Nombre)){
	$sql = "SELECT Loc_Nombre FROM Localidad WHERE Loc_Pro_ID = $Nombre AND Loc_Pai_ID = $Pais ORDER BY Loc_Nombre";
//	$sql = "SELECT Loc_Nombre FROM Localidad WHERE Loc_Pro_ID = $Nombre ORDER BY Loc_Nombre";
	
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<hr />";
	while ($row = mysqli_fetch_array($result)){
		echo "$row[Loc_Nombre]<br />";
	}//fin while

}
?>
