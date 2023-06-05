<?
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$Lec_ID = 18;
$Lec_Anterior = 17;
$Cur_1 = 4;
$Cur_2 = 5;
$Ori_ID = 1;
$arrayClases = array();
$Cla_Doc_ID = 2;//David Argumosa
$Div_ID = 3;


$sql = "SELECT DISTINCTROW Cla_Sed_ID, Cla_Mat_ID, Cla_Lec_ID, Cla_Niv_ID, Cla_Cur_ID, Cla_Div_ID FROM Colegio_Clase
    WHERE Cla_Lec_ID = $Lec_ID AND Cla_Cur_ID = $Cur_1 AND Cla_Niv_ID=1 AND Cla_Div_ID=$Div_ID";
//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	$j=0;
	while ($row = mysqli_fetch_array($result)){
		
		//Creamos las clases
		$sql = "INSERT INTO Colegio_Clase (Cla_Sed_ID, Cla_Lec_ID, Cla_Mat_ID, Cla_Doc_ID, Cla_Niv_ID, Cla_Cur_ID, Cla_Div_ID, Cla_Fecha, Cla_Hora, Cla_Usu_ID) VALUES($row[Cla_Sed_ID], $Lec_Anterior, $row[Cla_Mat_ID], $Cla_Doc_ID, $row[Cla_Niv_ID], $row[Cla_Cur_ID], $row[Cla_Div_ID], '$fecha', '$hora', $UsuID);";
		echo "$sql<br>";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$Cla_ID =mysql_insert_id();
		$arrayClases[] = $Cla_ID;
		/*$j++;
		$arrayClases[] = $j;*/

		//

		//echo "------------------------------------<br />";
	}//fin while
	//echo "<br />....FIN....<br />";
	//echo "Total de registros: ".mysqli_num_rows($result);
}//fin if


$sql = "SELECT * FROM Colegio_Inscripcion
    WHERE Ins_Lec_ID = $Lec_ID AND Ins_Cur_ID = $Cur_2 AND Ins_Niv_ID=1 AND Ins_Div_ID = $Div_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//echo "Comienza el proceso de inscribir a las clases....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		$Leg_ID = $row['Ins_Leg_ID'];
		$Div_ID = $row['Ins_Div_ID'];
		//Inscribimos a los alumnos
		$sql = "INSERT INTO Colegio_Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora) VALUES($Leg_ID, $Lec_Anterior, $Cur_1, 1, $Div_ID, $UsuID, '$fecha', '$hora');";
		echo "$sql<br>";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		set_time_limit(0);
		for ($i=0; $i < count($arrayClases); $i++) { 
			$sql = "INSERT INTO Colegio_InscripcionClase (IMa_Leg_ID, IMa_Lec_ID, IMa_Cla_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora) VALUES($Leg_ID, $Lec_Anterior, ".$arrayClases[$i].", $UsuID, '$fecha', '$hora');";
			echo "$sql<br>";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}

		//

		//echo "------------------------------------<br />";
	}//fin while
	//echo "<br />....FIN....<br />";
	//echo "Total de registros: ".mysqli_num_rows($result);
}//fin if
?>