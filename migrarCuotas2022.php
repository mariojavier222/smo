<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

//TIPOS DE CUOTAS O CONCEPTOS************
/*
1 Inscripción 				23 Inscripción 2023
2 Mensual Primario			 2 Cuota Mensual
3 Mensual Secundario		 2 Cuota Mensual	
6 Materiales Nivel Inicial	29 Materiales Nivel Inicial
10 Materiales Primario		10 Materiales Primario
11 Libreta Comunicación NI  11 Libreta Comunicación NI     
72 Retroactivo				12 Retroactivo	
83 Retroactivo Julio 2022   13 Retroactivo Julio 2022
*/

$SedID = 1;
$Fecha = date("Y-m-d");
$Hora = date("H:i:s");
$UsuID = 2;
$LecID = 21;

$CuotaI= 2;
$CTi_ID = 2;

$i=0;

//busco los alumnos del 2023
$sql = "SELECT * FROM
    alumnos_2023
    INNER JOIN persona 
        ON (alumnos_2023.DNI = persona.Per_DNI)
    INNER JOIN cuotas_impagas_2022 
        ON (alumnos_2023.MATRICULA = cuotas_impagas_2022.MATRICULA)
    INNER JOIN legajo 
        ON (legajo.Leg_Per_ID = persona.Per_ID) WHERE cuotas_impagas_2022.CUALFEC1VT=2022 AND CUOTAI=$CuotaI ;";// limit 2";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

echo $sql;

if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){		
		echo "<br>";
		$DNI = $row['DNI'];
		$PerID = $row['Per_ID'];
		$Matricula = $row['MATRICULA'];
		$LegID = $row['Leg_ID'];
		$Importe = $row['CUAL1VTO'];
		$Cuo_Numero = $row['CUALNRO'];
		$CMo_1er_Vencimiento = substr($row['CUALFEC1VT'],0,10);
		//$CMo_1er_Vencimiento = '2022-12-25';
		$CMo_2do_Vencimiento = $CMo_1er_Vencimiento;
		$CMo_3er_Vencimiento = $CMo_1er_Vencimiento;
		$CMo_Mes = substr($row['CUALFECVIG'], -2,);
		$CMo_Anio = substr($row['CUALFECVIG'], 0,4);

		$CMo_1er_Recargo = 1000;
	    $CMo_2do_Recargo = 0;
		$CMo_Recargo_Mensual = 1000;
		$Cuo_PPa_ID = 0;
		$Cuo_Ben_ID=1;
		$Alt_ID = 1;
		$CTi_ID = $CTi_ID;
		$Cuo_Masivo = 'migrado-14-02-2023';

		//busco si está inscripto
		$sqli = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = $LegID AND Ins_Lec_ID = $LecID";
	    $resulti = consulta_mysql_2022($sqli,basename(__FILE__),__LINE__);
	    if (mysqli_num_rows($resulti)>0) {
	    	$rowi = mysqli_fetch_array($resulti);

	    	$NivID=$rowi['Ins_Niv_ID'];
		
			//VEO SI YA TIENE GENERADA LA CUOTA
			$sqlCuota="SELECT Cuo_Lec_ID,
			  Cuo_Per_ID,
			  Cuo_Niv_ID,
			  Cuo_CTi_ID,
			  Cuo_Alt_ID,
			  Cuo_Numero,
			  Cuo_Ben_ID,
			  Cuo_Mes,
			  Cuo_Anio  
			FROM
			  CuotaPersona where Cuo_Lec_ID = $LecID AND
			  Cuo_Per_ID = $PerID AND
			  Cuo_Niv_ID = $NivID AND
			  Cuo_CTi_ID = $CTi_ID AND
			  Cuo_Alt_ID = $Alt_ID AND
			  Cuo_Numero = $Cuo_Numero AND
			  Cuo_Ben_ID = $Cuo_Ben_ID AND
			  Cuo_Mes = $CMo_Mes AND
			  Cuo_Anio  = $CMo_Anio";
			$resCuota= consulta_mysql_2022($sqlCuota,basename(__FILE__),__LINE__);
	    	
			echo $sqlCuota;
	    	if (mysqli_num_rows($resCuota)==0) {
				
				$sqlc="INSERT INTO CuotaPersona 
				(Cuo_Lec_ID, 
				Cuo_Per_ID, 
				Cuo_Niv_ID,
				Cuo_CTi_ID, 
				Cuo_Alt_ID, 
				Cuo_Numero, 
				Cuo_Ben_ID, 
				Cuo_Usu_ID, 
				Cuo_Fecha, 
				Cuo_Hora, 
				Cuo_Importe, 
				Cuo_1er_Recargo, 
				Cuo_2do_Recargo, 
				Cuo_1er_Vencimiento, 
				Cuo_2do_Vencimiento, 
				Cuo_3er_Vencimiento, 
				Cuo_Mes, 
				Cuo_Anio, 
				Cuo_Pagado, 
				Cuo_Cancelado, 
				Cuo_Anulado, 
				Cuo_Recargo_Mensual,
				Cuo_ImporteOriginal,
				Cuo_PPa_ID,
				Cuo_Masivo)
				VALUES
				('$LecID', 
				'$PerID', 
				'$NivID', 
				'$CTi_ID', 
				'$Alt_ID', 
				'$Cuo_Numero', 
				'$Cuo_Ben_ID', 
				'$UsuID', 
				'$Fecha', 
				'$Hora', 
				'$Importe', 
				'$CMo_1er_Recargo', 
				'$CMo_2do_Recargo', 
				'$CMo_1er_Vencimiento', 
				'$CMo_2do_Vencimiento', 
				'$CMo_3er_Vencimiento', 
				'$CMo_Mes', 
				'$CMo_Anio', 
				'0', 
				'0', 
				'0', 
				'$CMo_Recargo_Mensual',
				'$Importe',
				'$Cuo_PPa_ID',
				'$Cuo_Masivo'
				);"; 

				echo $sqlc;
				//$res=ejecutar_2022($sqlc,basename(__FILE__),__LINE__);
				if ($res["success"] == true){
					echo "Cuota creada. ".$DNI."<br>";
					$i++;
				}    			
			}	
		
		}else {
			echo "ERROR NO INSCRIPTO! ".$DNI."<br>";	
			}

	}//while	

}

echo "Total de cuotas creadas: $i";


?>