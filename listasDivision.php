<?php

require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();


function cargarListaDivisionCurso($nombre, $CurID, $LecID, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT Division.Div_Nombre, division_curso.DC_Div_ID, division_curso.DC_Cur_ID, division_curso.DC_Lec_ID 
	FROM Division INNER JOIN division_curso ON 
	(Division.Div_ID = division_curso.DC_Div_ID) where DC_Cur_ID='$CurID' AND DC_Lec_ID='$LecID';";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las divisiones...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[DC_Div_ID]'>$row[Div_Nombre]</option>";
	}//fin del while
 
	echo "</select>";

}//fin function
?> 