<?

require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$LecID = 15;
$sql = "SELECT Per_DNI, Cuo_Lec_ID, Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Alt_ID
FROM
    CuotaPersona
    INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
WHERE (CTi_ID =2
    AND Cuo_Mes =3
    AND Cuo_Anio =2018)
    GROUP BY cuo_per_id
    HAVING COUNT(cuo_per_id)>1";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		set_time_limit(600);
		
		echo "$row[Per_DNI]<br />";
		arreglarCuota($row['Cuo_Lec_ID'], $row['Cuo_Per_ID'], $row['Cuo_Niv_ID'], $row['Cuo_CTi_ID'], $row['Cuo_Alt_ID']);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />FIN<br />";
}//fin if


function arreglarCuota($LecID, $PerID, $NivID, $CTiID, $AltID){
	//echo "Hola";exit;
	$sql = "SELECT * FROM
    CuotaPersona
    INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
WHERE (CTi_ID =2
    AND Cuo_Mes =3
    AND Cuo_Anio =2018 AND Cuo_Per_ID=$PerID AND Cuo_Lec_ID=$LecID AND Cuo_CTi_ID=$CTiID AND Cuo_Alt_ID=$AltID)";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;exit;
	if (mysqli_num_rows($result)>0){
		//echo "$sql<br />";
		$i = 0;	
		$p = 0;	
		while ($row = mysqli_fetch_array($result)){
		
			if ($row['Cuo_Pagado']==1){
				$p++;
			}else{
				
				if ($row['Cuo_Pagado']==0 && $row['Cuo_Cancelado']==0 && $row['Cuo_Anulado']==0){
					$Numero = $row['Cuo_Numero'];
					$Cuo_Niv_ID = $row['Cuo_Niv_ID'];
					$i++;
					if ($i==1) {
						$fecha1 = $row['Cuo_Fecha'];
						$Numero1 = $row['Cuo_Numero'];
						$Cuo_Niv_ID1 = $row['Cuo_Niv_ID'];
					}
					if ($i==2) {
						$fecha2 = $row['Cuo_Fecha'];
						$Numero2 = $row['Cuo_Numero'];
						$Cuo_Niv_ID2 = $row['Cuo_Niv_ID'];
					}
				}
			}
			

			///
		}//fin while///
		if ($i==1 && $p==1){
			$sql = "DELETE FROM CuotaPersona WHERE Cuo_Per_ID=$PerID AND Cuo_Lec_ID=$LecID AND Cuo_Niv_ID=$Cuo_Niv_ID AND Cuo_CTi_ID=$CTiID AND Cuo_Alt_ID=$AltID AND Cuo_Numero=$Numero";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "$sql<br />";
		}
		/*if ($i==2 && $p==0){
			restarFecha($fecha1, $fecha2);
			$sql = "DELETE FROM CuotaPersona WHERE Cuo_Per_ID=$PerID AND Cuo_Lec_ID=$LecID AND Cuo_Niv_ID=$Cuo_Niv_ID AND Cuo_CTi_ID=$CTiID AND Cuo_Alt_ID=$AltID AND Cuo_Numero=$Numero";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "$sql<br />";
		}*/
		echo "i: $i - p: $p<br>";
		
			
			
	}//fin if

}//fin funcion

?>