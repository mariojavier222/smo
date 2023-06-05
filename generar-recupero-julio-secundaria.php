<?php

require_once("conexion.php");
require_once("funciones_generales.php");
require_once("funciones_beneficios.php");

$SedID = 1;
$Mes=7;
$Anio=2022;
$CTiID=2;
$LecID=21;
$NivID=2;
//$sqlFiltro= " and Cuo_Per_ID=3477 ";
$sqlFiltro= " and CuP_Fecha <= '2022-07-04'  ";
$ImporteCompara='4370.00';
$i=0;

//busco las cuotas pagadas de julio 2022
$sql = "SELECT * FROM CuotaPago
    INNER JOIN CuotaPersona 
        ON (CuotaPago.CuP_Lec_ID = CuotaPersona.Cuo_Lec_ID) AND (CuotaPago.CuP_Per_ID = CuotaPersona.Cuo_Per_ID) AND (CuotaPago.CuP_Niv_ID = CuotaPersona.Cuo_Niv_ID) AND (CuotaPago.CuP_CTi_ID = CuotaPersona.Cuo_CTi_ID) AND (CuotaPago.CuP_Alt_ID = CuotaPersona.Cuo_Alt_ID) AND (CuotaPago.CuP_Numero = CuotaPersona.Cuo_Numero) where Cuo_Lec_ID = $LecID AND Cuo_Niv_ID=$NivID and Cuo_CTi_ID = $CTiID AND Cuo_Mes = $Mes AND Cuo_Anio = $Anio AND Cuo_Pagado=1 and Cuo_Importe<='$ImporteCompara' $sqlFiltro";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

echo $sql;


while ($row = mysqli_fetch_array($result)){
	$Insertado=false;
	$Numero=1;
	$PerID = $row['Cuo_Per_ID'];
	$Ben_ID = $row['Cuo_Ben_ID'];
	$CTi_ID = 28;
	guardarAsignacionCuotaFaltante($PerID, $LecID, $NivID, $CTi_ID, $Mes, $Anio, $Numero, $Ben_ID, $Insertado);	
	if ($Insertado) $i++;
	
}//fin del while

echo "<br>Total de registros: ".mysqli_num_rows($result)."<br>";
echo "<br>Total de registros insertados: ".$i."<br>";
echo "<br>FIN";

//exit;


function guardarAsignacionCuotaFaltante($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $CTi_ID, $mes, $anio, $numero, $Cuo_Ben_ID,&$Insertado) {

	$Importe='1310.00';
	$Recargo1='0.00';
	$RecargoMensual='0.00';
	$Vto1='2022/08/10';
	$Vto2='2022/08/10';
	$Vto3='2022/08/10';

	$UsuID='2';
	$Fecha=date('Y-m-d');
	$Hora=date('H:i:s');

	$sqlv = "SELECT * FROM CuotaPersona WHERE 
	Cuo_Per_ID = $Cuo_Per_ID AND 
	Cuo_Niv_ID = $Cuo_Niv_ID AND 
	Cuo_Lec_ID = $Cuo_Lec_ID AND 
	Cuo_CTi_ID = $CTi_ID AND 
	Cuo_Mes = $mes AND 
	Cuo_Anio = $anio";
//	echo $sqlv;

	$result_verif = consulta_mysql_2022($sqlv,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result_verif)==0){

		$sqlv = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal, Cuo_Masivo, Cuo_Agrupa)
		values($Cuo_Per_ID, $Cuo_Niv_ID, $CTi_ID, $Cuo_Lec_ID, 1, '$numero', '$Cuo_Ben_ID', '$UsuID', '$Fecha', '$Hora', '$Importe', '$Recargo1', '0.00', '$Vto1', '$Vto2', '$Vto3', $mes, $anio, '0','0','0', '$RecargoMensual', '$Importe', '', '0')";
		
		//echo "1<br>";
//		echo "$sqlv<br>";
	
		$res=consulta_mysql_2022($sqlv,basename(__FILE__),__LINE__);
		
		if ($res){
			$Insertado=true;
		//	aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $CTi_ID, 1, '$numero', $Cuo_Ben_ID);

			echo "$Cuo_Per_ID: Se agregó correctamente la nueva cuota<br />";
		}

	}		
	
}
	
?>