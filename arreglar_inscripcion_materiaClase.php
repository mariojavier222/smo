<?

require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$LecID = 17;
$Cla_ID = 610;
//$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $LecID LIMIT 0,10";
$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $LecID and Ins_Niv_ID = 2 AND Ins_Cur_ID = 15 AND Ins_Div_ID = 3";
echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		
		//agregarInscripcion($LecID, $row[Ins_Leg_ID], 288);
		agregarInscripcion($LecID, $row[Ins_Leg_ID], $Cla_ID);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />FIN<br />";
}//fin if



function agregarInscripcion($LecID, $LegID, $Cla_ID){
$fecha = date("Y-m-d");
$hora = date("H:i:s");
	
	$sql = "INSERT INTO Colegio_InscripcionClase (IMa_Lec_ID, IMa_Leg_ID, IMa_Cla_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora) VALUES ($LecID, $LegID, $Cla_ID, 1, '$fecha', '$hora');";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "$sql<br />";
	//echo "Se agregaron las inscripciones de la clase.<br />";

}//fin funcion

?>