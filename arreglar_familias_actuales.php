<?

require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$sql = "SELECT DISTINCTROW Fam_Per_ID FROM Familia WHERE Fam_Per_ID = 3422 or Fam_Per_ID = 3423 or Fam_Per_ID = 3602 LIMIT 0,1000";
$sql = "SELECT DISTINCTROW Fam_Per_ID FROM Familia";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		armarFamilia($row[Fam_Per_ID]);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />FIN<br />";
}//fin if

function guardarFamilia($PerID, $PerID_Vinc, $FTiID, $UsuID){
	//echo "Hola";exit;
	if (empty($PerID)){
		$DNI = $_POST['DNI'];
		$_SESSION['sesion_ultimoDNI'] = $DNI;
		$DNI_Vinc = $_POST['DNI_Vinc'];
		$FTiID = $_POST['FTiID'];
		$PerID = gbuscarPerID($DNI);
		$PerID_Vinc = gbuscarPerID($DNI_Vinc);
		$UsuID = $_POST['UsuID'];
	}
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");
	
	
	
	$sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		$sql = "INSERT INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID) VALUES ('$PerID', '$PerID_Vinc', '$FTiID', '$UsuID')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo "$sql<br />";		

		//echo "Se agregó correctamente la nueva relación familiar";
	}else{
		$sql = "UPDATE Familia SET Fam_FTi_ID = '$FTiID' WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo "$sql<br />";
		//echo "Se actualizó la relación familiar.";
	}
	//también relacionamos la vuelta de la relación
	$FTi_Relaciona = gbuscarFTiRelaciona($FTiID);
	if ($FTi_Relaciona>0){
			$sql = "INSERT IGNORE INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID) VALUES ('$PerID_Vinc', '$PerID', '$FTi_Relaciona', '$UsuID')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			//echo "$sql<br />";
		}

}//fin funcion
function armarFamilia($PerID){
	//echo "Hola";exit;
	$UsuID = 6;
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");
	
	//echo "$DNI-$PerID";
	
	$sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' ORDER BY Fam_FTi_ID";
	//echo "$sql<br />";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//existe
		$ip = 1;
		$ih = 1;
		$ihm = 1;
		$ihj = 1;
		while ($row = mysqli_fetch_array($result)){
			$FTiID = $row[Fam_FTi_ID];
			$VinPerID = $row[Fam_Vin_Per_ID];
			if ($FTiID==1)//es padre/madre
			{
				$padre[$ip] = $VinPerID;
				$ip++;
			}
			if ($FTiID==5)//es hermano
			{
				$hermano[$ih] = $VinPerID;
				$ih++;
			}
			if ($FTiID==8)//es hermano mayor
			{
				$hermanomayor[$ihm] = $VinPerID;
				$ihm++;
			}
		}//fin while		
		if (count($padre)>0 && count($hermano)>0){
			//Relacionamos los padres con los hermanos
			foreach($padre as $p){
				foreach($hermano as $h){
					guardarFamilia($h, $p, 1, $UsuID);
					//echo "$h-$p";
				}//fin foreach hermano				
			}//fin foreach padre
		}//fin if cont
		if (count($padre)>0 && count($hermanomayor)>0){
			//Relacionamos los padres con los hermanos mayores
			foreach($padre as $p){
				foreach($hermanomayor as $h){
					guardarFamilia($h, $p, 1, $UsuID);
					//echo "$h-$p";
				}//fin foreach hermano				
			}//fin foreach padre
		}//fin if cont
		if (count($hermano)>0){
			//Relacionamos los hermanos entre si
			foreach($hermano as $h1){
				foreach($hermano as $h2){
					if ($h1!=$h2){
						guardarFamilia($h1, $h2, 5, $UsuID);
						//echo "$h1-$h2";
					}
						
				}//fin foreach hermano2
			}//fin foreach hermano1
		}//fin if cont
		if (count($hermanomayor)>0){
			//Relacionamos los hermanos entre si
			foreach($hermanomayor as $h1){
				foreach($hermano as $h2){
					if ($h1!=$h2){
						guardarFamilia($h2, $h1, 8, $UsuID);
						//echo "$h1-$h2";
					}
						
				}//fin foreach hermano2
			}//fin foreach hermano1
		}//fin if cont
		if (count($padre)>0){
			//Relacionamos los padres entre si
			foreach($padre as $p1){
				foreach($padre as $p2){
					if ($p1!=$p2){
						guardarFamilia($p1, $p2, 9, $UsuID);
						//echo "$h1-$h2";
					}
						
				}//fin foreach hermano2
			}//fin foreach hermano1
		}//fin if cont
	}

}//fin funcion


?>