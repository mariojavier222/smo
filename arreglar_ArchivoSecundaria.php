<?
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$Lec_ID = 18;
$sql = "SELECT Per_ID, UPPER(Per_Apellido)AS Per_Apellido, Per_Nombre, Cur_Curso, Div_Nombre FROM
    Colegio_Inscripcion
    INNER JOIN Legajo
    ON (Ins_Leg_ID = Leg_ID)
		INNER JOIN Persona 
			ON (Legajo.Leg_Per_ID = Persona.Per_ID) 
	 INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
	WHERE Ins_Lec_ID = $Lec_ID AND Ins_Niv_ID = 2 AND Ins_Fecha = '2019-03-26' ORDER BY Ins_Cur_ID, Ins_Div_ID"; //echo "$sql<br>";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br /><br /><br />";
if (mysqli_num_rows($result)>0){
	$linea="";
	while ($row = mysqli_fetch_array($result)){
		$codAlu = substr("00000".$row['Per_ID'],-5);
		$importe1 = "00075000";
		$vencimiento1="290319";
		$importe2 = "00080000";
		$vencimiento2="050419";
		$alumno = substr($row['Per_Apellido'],0,10).", ".substr($row['Per_Nombre'],0,15);
		$alumno = substr($alumno,0,20);
		$cont = strlen($alumno);
		$dif = 20 - $cont;
		if ($dif>0){
			for ($i=0; $i <= $dif; $i++) { 
				$alumno."*";
			}			
		}
		//&nbsp;
		$alumno = str_replace("*", "&nbsp;", $alumno);

		$cuota = "****"."SECU-".$row['Cur_Curso']."-".$row['Div_Nombre']."[MAR/19]";
		$cuota = str_replace("*", "&nbsp;", $cuota);


		$linea = $codAlu.$importe1.$vencimiento1.$importe2.$vencimiento2.$alumno.$cuota."<br>";
		echo $linea;
	}//fin while
	echo "<br />....FIN....<br />";
	echo "Total de registros: ".mysqli_num_rows($result);
}//fin if
?>