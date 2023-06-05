<?

require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$LecID = 21;
$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $LecID LIMIT 0,10";
$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $LecID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		set_time_limit(600);
		//validarInscripcion($LecID, $row[Ins_Leg_ID], $row[Ins_Niv_ID], $row[Ins_Cur_ID], $row[Ins_Div_ID]);
		agregarInscripcion($LecID, $row[Ins_Leg_ID], $row[Ins_Niv_ID], $row[Ins_Cur_ID], $row[Ins_Div_ID]);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />FIN<br />";
}//fin if

function validarInscripcion($LecID, $LegID, $NivID, $CurID, $DivID){
	//echo "Hola";exit;
	$sql = "SELECT * FROM Colegio_InscripcionClase WHERE IMa_Lec_ID = $LecID AND IMa_Leg_ID = $LegID AND NOT EXISTS (SELECT * FROM Colegio_Clase WHERE Cla_ID = IMa_Cla_ID AND Cla_Niv_ID = $NivID AND Cla_Cur_ID = $CurID AND Cla_Div_ID = $DivID)";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		echo "$sql<br />";		
		while ($row = mysqli_fetch_array($result)){
		
				$sql = "DELETE FROM Colegio_InscripcionClase WHERE IMa_Cla_ID = $row[IMa_Cla_ID]";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				//echo "$sql<br />";
				echo "Se eliminaron las inscripciones de la clase.";

			///
		}//fin while///
	}//fin if

}//fin funcion

function agregarInscripcion($LecID, $LegID, $NivID, $CurID, $DivID){
$fecha = date("Y-m-d");
$hora = date("H:i:s");
	
	//echo "Hola";exit;
	$sql = "SELECT * FROM Colegio_Clase WHERE Cla_Lec_ID = $LecID AND Cla_Niv_ID = $NivID AND Cla_Cur_ID = $CurID AND Cla_Div_ID = $DivID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		//echo "$sql<br />";		
		while ($row = mysqli_fetch_array($result)){
		
				$sql = "INSERT IGNORE INTO Colegio_InscripcionClase (IMa_Lec_ID, IMa_Leg_ID, IMa_Cla_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora) VALUES ($LecID, $LegID, $row[Cla_ID], 1, '$fecha', '$hora')";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				//echo "$sql<br />";
				//echo "Se agregaron las inscripciones de la clase.<br />";

			///
		}//fin while///
	}//fin if

}//fin funcion

?>