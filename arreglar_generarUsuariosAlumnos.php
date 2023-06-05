<?php
set_time_limit(120);
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$Lec_ID = 19;//Ciclo Lectivo 2020
$SedID = 1;

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "SELECT DISTINCTROW Per_DNI, Per_Apellido, Per_Nombre, Leg_ID FROM Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID) WHERE Ins_Lec_ID = $Lec_ID";// LIMIT 16,1;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	$i=0;
	while ($row = mysqli_fetch_array($result)){
		$i++;
		$DNI = $row['Per_DNI'];
		$Apellido = $row['Per_Apellido'];
		$Nombre = $row['Per_Nombre'];
		echo "$i: $Apellido, $Nombre ($DNI) $Leg_ID<br />";
		guardarCuentaUsuario($DNI, $DNI, "$Apellido, $Nombre", $row['Leg_ID'], $SedID);
		//guardarRolUnico($DNI, 11, true);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />....FIN....<br />";
	echo "Total de registros: ".mysqli_num_rows($result);
}//fin if
?>