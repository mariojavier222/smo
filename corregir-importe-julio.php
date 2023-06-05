<?php

require_once("conexion.php");
require_once("funciones_generales.php");
require_once("funciones_beneficios.php");

//sleep(3);
$UsuID = 1;
$SedID = 1;
$Mes=7;
$Anio=2022;
$CTiID=26;
$LecID=21;
$NivID=3;
$ImporteCompara='130.00';

$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Lec_ID = $LecID AND Cuo_Niv_ID=$NivID and Cuo_CTi_ID = $CTiID AND Cuo_Mes = $Mes AND Cuo_Anio = $Anio AND Cuo_Pagado=0 AND Cuo_Anulado=0 AND Cuo_Cancelado=0 AND Cuo_MarcadaOnline=0"; //and Cuo_Importe=$ImporteCompara";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

//echo $sql;

$i=0;
while ($row = mysqli_fetch_array($result)){
	
	$PerID = $row['Cuo_Per_ID'];
	$Importe = $row['Cuo_Importe'];

	$ImporteNuevo='160.00';
	$PrimerRecargo='20.00';
	$RecargoMensual='20.00';
	$Cuo_Ben_ID=$row['Cuo_Ben_ID'];
	$Cuo_Numero=$row['Cuo_Numero'];

//	if ($Importe==$ImporteCompara){
		
		$sqlp = "UPDATE CuotaPersona SET Cuo_Importe='$ImporteNuevo', Cuo_ImporteOriginal='$ImporteNuevo', Cuo_1er_Recargo='$PrimerRecargo', Cuo_Recargo_Mensual='$RecargoMensual' where Cuo_Per_ID= $PerID AND Cuo_Lec_ID = $LecID AND Cuo_Niv_ID=$NivID and Cuo_CTi_ID = $CTiID AND Cuo_Mes = $Mes AND Cuo_Anio = $Anio AND Cuo_Pagado=0 AND Cuo_Anulado=0 AND Cuo_Cancelado=0 AND Cuo_MarcadaOnline=0;";
		$res=consulta_mysql_2022($sqlp,basename(__FILE__),__LINE__);	
		echo "$sqlp<br>";

		if ($res){
			
		//	aplicarBeneficioCuota($LecID, $PerID, $NivID, $CTiID, 1, $Cuo_Numero, $Cuo_Ben_ID);
	
			echo "PerID: ".$PerID." - Importe original: ".$Importe." - Importe Nuevo: ".$ImporteNuevo."<br>";
			$i++;

		}

//	}

	//echo $row['Cuo_Per_ID']."<br>";

}//fin del while

echo "<br>Total de registros: ".mysqli_num_rows($result)."<br>";
echo "<br>Total de registros modificados: ".$i."<br>";
echo "<br>FIN";

exit;

?>