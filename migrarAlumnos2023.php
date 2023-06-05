<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$SedID = 1;
$FechaAlta = '2023-02-07';
$Leg_Colegio = 1;
$Fecha = date("Y-m-d");
$Hora = date("H:i:s");
$UsuID = 2;
$LecID = 23;

$l=0;
$i=0;

//busco los alumnos del 2023
$sql = "SELECT * FROM alumnos_2023_ok INNER JOIN Persona ON (DNI = Per_DNI)";// limit 2";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo $sql;
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){		
		echo "<br>";
		$DNI = $row['DNI'];
		$PerID = $row['Per_ID'];
		$Matricula = $row['MATRICULA'];
		$Curso = $row['ID_CURSO'];

		//nivel
		if ($Curso>=2 AND $Curso<=3) $NivID=3;
		if ($Curso>=4 AND $Curso<=9) $NivID=1;
		if ($Curso>=10 AND $Curso<=15) $NivID=2;

		$CurID=$Curso;
		$DivID=$row['ID_DIV'];

		$sqll='';
		//me fijo si tiene legajo
		$sqll = "SELECT * FROM Legajo WHERE Leg_Per_ID = $PerID";
		$resultl = consulta_mysql_2022($sqll,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($resultl)==0){
			//si no lo tiene lo genera
			$sqll = "INSERT INTO Legajo(Leg_Sed_ID, Leg_Per_ID, Leg_Numero, Leg_Alta_Fecha, Leg_Usu_ID, Leg_Fecha, Leg_Hora, Leg_Colegio, Leg_Matricula) VALUES ('$SedID', '$PerID', '$DNI', '$FechaAlta', '$UsuID', '$Fecha', '$Hora', '$Leg_Colegio', '$Matricula')";
			//echo $sqll;
			$res=ejecutar_2022($sqll,basename(__FILE__),__LINE__);
			if ($res["success"] == true){
    			$LegID=$res["id"];
				echo "Legajo creado. ".$DNI."<br>";
				$l++;
			}    			
		}else {
			$rowl = mysqli_fetch_array($resultl);	
			echo "Ya existe legajo! ".$DNI."<br>";	
			$LegID = $rowl['Leg_ID'];
		}	
		gObtenerApellidoNombrePersona($DNI, $apellido, $nombre);
		guardarCuentaUsuario($DNI, $DNI, "$apellido, $nombre", $LegID, $SedID);
	
		//me fijo si tiene inscripción en el lectivo
	    //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
	    $sqli = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = $LegID AND Ins_Lec_ID = $LecID";
	    $resulti = consulta_mysql_2022($sqli,basename(__FILE__),__LINE__);
	    if (mysqli_num_rows($resulti)==0) {
	  		//guardo la inscripción
	        $sqli = "INSERT INTO Colegio_Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora) VALUES ($LegID, $LecID, $CurID, $NivID, $DivID, $UsuID, '$Fecha', '$Hora')";
	        //echo $sqli;
	        $res=ejecutar_2022($sqli,basename(__FILE__),__LINE__);
			if ($res["success"] == true){
				//$LegID=$res["id"];
				echo "Se agregó correctamente la inscripción del alumno. ".$DNI."<br>";
				$i++;
			}    
		}
	  

	}//while	

}

echo "<br>Total: $l legajos<br>";
echo "Total: $i inscriptos";


?>