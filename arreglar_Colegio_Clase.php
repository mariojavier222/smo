<?
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$Lec_ID = 13;
$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Colegio_InscripcionClase 
        ON (Ins_Leg_ID = IMa_Leg_ID) AND (Ins_Lec_ID = IMa_Lec_ID) WHERE Ins_Lec_ID = $Lec_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		$sql = "UPDATE Colegio_Clase SET Cla_Niv_ID = $row[Ins_Niv_ID], Cla_Cur_ID = $row[Ins_Cur_ID], Cla_Div_ID = $row[Ins_Div_ID] WHERE Cla_ID = $row[IMa_Cla_ID]";
		echo "$sql<br>";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />....FIN....<br />";
	echo "Total de registros: ".mysqli_num_rows($result);
}//fin if
?>