<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

//TIPOS DE CUOTAS O CONCEPTOS************
/*
2 Mensual Primario			 2 Cuota Mensual
3 Mensual Secundario		 2 Cuota Mensual	
*/

$SedID = 1;
$Fecha = date("Y-m-d");
$Hora = date("H:i:s");
$UsuID = 2;
$LecID = 21;

$CuotaI= 3;
$CTi_ID = 2;
$CMo_Mes = 12;
$CMo_Anio =2022;
$i=0;

//busco los alumnos del 2023
$sql = "SELECT * FROM
    alumnos_2023
    INNER JOIN Persona 
        ON (alumnos_2023.DNI = Persona.Per_DNI)
    INNER JOIN cuotas_dic_2022 
        ON (alumnos_2023.MATRICULA = cuotas_dic_2022.MATRICULA)
    INNER JOIN Legajo 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID) WHERE cuotas_dic_2022.CUALFEC1VT=2022 AND CUOTAI=$CuotaI ;";// limit 2";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

echo $sql;

if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){		
		echo "<br>";
		$DNI = $row['DNI'];
		$PerID = $row['Per_ID'];
		$Matricula = $row['MATRICULA'];
		$LegID = $row['Leg_ID'];

		$Alt_ID = 1;
		$CTi_ID = $CTi_ID;

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
			 
			  Cuo_CTi_ID = $CTi_ID AND
			  Cuo_Alt_ID = $Alt_ID AND
			  Cuo_Mes = $CMo_Mes AND
			  Cuo_Anio  = $CMo_Anio AND 
			  Cuo_Pagado=0 AND 
			  Cuo_Anulado=0 AND 
			  Cuo_Cancelado=0 AND 
			  Cuo_MarcadaOnline= 0";
			$resCuota= consulta_mysql_2022($sqlCuota,basename(__FILE__),__LINE__);
	    	
			echo $sqlCuota;
	    	if (mysqli_num_rows($resCuota)==1) {
				
				$sqlc="UPDATE CuotaPersona SET
				Cuo_Anulado = 1, 
				Cuo_Motivo = 'pagado en sistema anterior'
				WHERE  Cuo_Lec_ID = $LecID AND
				  Cuo_Per_ID = $PerID AND
				
				  Cuo_CTi_ID = $CTi_ID AND
				  Cuo_Alt_ID = $Alt_ID AND
				  Cuo_Mes = $CMo_Mes AND
				  Cuo_Anio  = $CMo_Anio"; 

				echo "<br>".$sqlc;
				$res=ejecutar_2022($sqlc,basename(__FILE__),__LINE__);
				if ($res["success"] == true){
					echo "Cuota anulada. ".$DNI."<br>";
					$i++;
				}    			
			}	
		
		}else {
			echo "ERROR NO INSCRIPTO! ".$DNI."<br>";	
			}

	}//while	

}

echo "Total de cuotas anuladas: $i";


?>