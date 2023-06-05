<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once("conexion.php");
require_once("funciones_generales.php");
require_once("funciones_beneficios.php");

$SedID = 1;
$Mes=5;
$Anio=2023;
$CTiID=32;
$LecID=23;
$NivID=2;
//$sqlFiltro= " and Per_ID=8730 ";
$sqlFiltro= " ";

$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Lec_ID=$LecID AND Cuo_CTi_ID = 2 AND Cuo_Niv_ID=$NivID AND Cuo_Mes= $Mes AND Cuo_Anio=$Anio AND Cuo_Pagado=1;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo $sql."<br>";

$i=0;
while ($row = mysqli_fetch_array($result)){
	$Numero=1;
	$PerID = $row['Cuo_Per_ID'];
	$Cuo_Ben_ID=$row['Cuo_Ben_ID'];
	guardarAsignacionCuotaFaltante($PerID, $LecID, $NivID, $CTiID, $Mes, $Anio, $Numero, $Cuo_Ben_ID);	
	
}//fin del while

echo "<br>Total de registros: ".mysqli_num_rows($result)."<br>";
echo "<br>Total de registros insertados: ".$i."<br>";
echo "<br>FIN";

//exit;


function guardarAsignacionCuotaFaltante($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $CTi_ID, $mes, $anio, $numero, $Cuo_Ben_ID) {

	if ($Cuo_Niv_ID==2){
		
		$Importe='3370.00';
		//2 hermanos
		if ($Cuo_Ben_ID==7) $Importe='3150.00';
		//3 hermanos
		if ($Cuo_Ben_ID==8) $Importe='3085.00';
		//1 hijo
		if ($Cuo_Ben_ID==12) $Importe='3200.00';
		//2 hijos
		if ($Cuo_Ben_ID==13) $Importe='3100.00';
		//3 hijos
		if ($Cuo_Ben_ID==14) $Importe='2850.00';
		
		$ImporteOriginal='3370.00';
		$Recargo1='337.00';
	}
	
	if ($Cuo_Niv_ID==1 || $Cuo_Niv_ID==3){
		
		$Importe='3150.00';
		//2 hermanos
		if ($Cuo_Ben_ID==2) $Importe='3020.00';
		//3 ó 4 hermanos
		if ($Cuo_Ben_ID==3 || $Cuo_Ben_ID==4) $Importe='2900.00';
		//1 hijo
		if ($Cuo_Ben_ID==9) $Importe='3100.00';
		//2 hijos
		if ($Cuo_Ben_ID==10) $Importe='2850.00';
		//3 hijos
		if ($Cuo_Ben_ID==11) $Importe='2700.00';

		$ImporteOriginal='3150.00';
		$Recargo1='315.00';
	}

	$RecargoMensual='0.00';

	$Vto1='2023/05/25';
	$Vto2='2023/05/25';
	$Vto3='2023/05/25';

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
	//echo $sqlv;

	$result_verif = consulta_mysql_2022($sqlv,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result_verif)==0){

		$sqli = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal, Cuo_Masivo, Cuo_Agrupa)
		values($Cuo_Per_ID, $Cuo_Niv_ID, $CTi_ID, $Cuo_Lec_ID, 1, '$numero', '$Cuo_Ben_ID', '$UsuID', '$Fecha', '$Hora', '$Importe', '$Recargo1', '0.00', '$Vto1', '$Vto2', '$Vto3', $mes, $anio, '0','0','0', '$RecargoMensual', '$ImporteOriginal', 'manual 16-05-2023', '0')";
		
		echo $sqli."<br>";
		
		$res=consulta_mysql_2022($sqli,basename(__FILE__),__LINE__);
		
		
		if ($res){
			
			//aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $CTi_ID, 1, '$numero', $Cuo_Ben_ID);

			echo "$Cuo_Per_ID: Se agregó correctamente la nueva cuota<br />";
		}
		

	}		
	
}
	
?>