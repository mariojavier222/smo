<?php
error_reporting(E_ALL); ini_set('display_errors', 1);

require_once("conexion.php");
require_once("funciones_generales.php");
require_once("funciones_beneficios.php");

//sleep(3);
$UsuID = 1;
$SedID = 1;
$MesInicio=3;
$MesFin=4;
$Anio=2023;
$CTiID=2;
$LecID=23;
$NivID=2;
//$sqlFiltro= " Cuo_Per_ID=6290 and ";
$sqlFiltro= " ";

$sql = "SELECT * FROM CuotaPersona WHERE $sqlFiltro Cuo_Lec_ID = $LecID AND Cuo_Niv_ID=$NivID and Cuo_CTi_ID = $CTiID AND (Cuo_Mes >= $MesInicio AND Cuo_Mes <= $MesFin) AND Cuo_Anio = $Anio AND Cuo_Pagado=0 AND Cuo_Anulado=0 AND Cuo_Cancelado=0 AND Cuo_MarcadaOnline=0"; //and Cuo_Importe=$ImporteCompara";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

echo $sql.'<br>';

$i=0;
while ($row = mysqli_fetch_array($result)){

	$Cuo_Ben_ID=1;		
	$Mes=$row['Cuo_Mes'];;	
	$PerID = $row['Cuo_Per_ID'];

	if ($CTiID==2){
		if ($NivID==1){
			$ImporteNuevo='8760.00';
			$PrimerRecargo='876.00';
		} 
		if ($NivID==2){
			$ImporteNuevo='9580.00';
			$PrimerRecargo='958.00';
		} 
		if ($NivID==3){
			$ImporteNuevo='8760.00';
			$PrimerRecargo='876.00';			
		}
	}	

	$Importe = $row['Cuo_Importe'];
	$Cuo_ImporteOriginal = $row['Cuo_ImporteOriginal'];
	
	$Cuo_Ben_ID=$row['Cuo_Ben_ID'];
	$Cuo_Numero=$row['Cuo_Numero'];

	if ($Cuo_ImporteOriginal<>$ImporteNuevo){
		
		$sqlp = "UPDATE CuotaPersona SET Cuo_Importe='$ImporteNuevo', Cuo_ImporteOriginal='$ImporteNuevo',Cuo_Masivo = 'act.15-05-2023', Cuo_1er_Recargo='$PrimerRecargo' where Cuo_Per_ID= $PerID AND Cuo_Lec_ID = $LecID AND Cuo_Niv_ID=$NivID and Cuo_CTi_ID = $CTiID AND Cuo_Mes = $Mes AND Cuo_Anio = $Anio AND Cuo_Pagado=0 AND Cuo_Anulado=0 AND Cuo_Cancelado=0 AND Cuo_MarcadaOnline=0;";
		$res=consulta_mysql_2022($sqlp,basename(__FILE__),__LINE__);	
		echo $sqlp."<br>";

		//$res=true;
		if ($res){
			//if ($Cuo_Ben_ID>1) aplicarBeneficioCuota($LecID, $PerID, $NivID, $CTiID, 1, $Cuo_Numero, $Cuo_Ben_ID);
			//echo "PerID: ".$PerID." - Importe original: ".$Importe." - Importe Nuevo: ".$ImporteNuevo."<br>";
			$i++;

		}

	}

	//echo $row['Cuo_Per_ID']."<br>";

}//fin del while

echo "<br>Total de registros: ".mysqli_num_rows($result)."<br>";
echo "<br>Total de registros modificados: ".$i."<br>";
echo "<br>FIN";

exit;

?>