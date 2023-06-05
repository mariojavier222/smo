<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$SedID = 1;
$Fecha = date("Y-m-d");
$Hora = date("H:i:s");
$UsuID = 2;
$LecID = 23;

$l=0;
$i=0;

//busco los alumnos del 2023
$sql = "SELECT * FROM
    alumnos_2023
    INNER JOIN Persona 
        ON (alumnos_2023.DNI = Per_DNI)
    INNER JOIN cuotas_impagas_2023 
        ON (alumnos_2023.MATRICULA = cuotas_impagas_2023.MATRICULA)
    INNER JOIN Legajo 
        ON (Leg_Per_ID = Per_ID) WHERE cuotas_impagas_2023.CUALFEC1VT=2023;";// limit 2";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){		
		echo "<br>";
		$DNI = $row['DNI'];
		$PerID = $row['Per_ID'];
		$Matricula = $row['MATRICULA'];
		$LegID = $row['Leg_ID'];
		$Importe = $row['CUAL1VTO'];

		//busco si está inscripto
		$sqli = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = $LegID AND Ins_Lec_ID = $LecID";
	    $resulti = consulta_mysql_2022($sqli,basename(__FILE__),__LINE__);
	    if (mysqli_num_rows($resulti)>0) {
	    	$rowi = mysqli_fetch_array($resulti);

	    	$NivID=$rowi['Ins_Niv_ID'];
			$CTi_ID = 29;//MATERIALES NIVEL INICIAL
			$Alt_ID = 1;
			$Cuo_Numero = 1;
			$CMo_1er_Vencimiento = '2023-03-25';
		    $CMo_2do_Vencimiento = '2023-03-25';
		    $CMo_3er_Vencimiento = '2023-03-25';
		    $CMo_Mes = 12;
			$CMo_Anio = 2022;
			$CMo_1er_Recargo = 0;
		    $CMo_2do_Recargo = 0;
			$CMo_Recargo_Mensual = 0;
			$Cuo_PPa_ID = 0;
			$Cuo_Ben_ID=1;
			
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
				Cuo_PPa_ID)
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
				'$Cuo_PPa_ID'
				);"; 
				$res=ejecutar_2022($sqlc,basename(__FILE__),__LINE__);
				if ($res["success"] == true){
					echo "Cuota creada. ".$DNI."<br>";
					$i++;
				}    			
			}else {
				echo "ERROR NO INSCRIPTO! ".$DNI."<br>";	
			}	
		
		}

	}//while	

}

echo "Total de cuotas creadas: $i";


?>