<?php

require_once("conexion.php");
require_once("funciones_generales.php");
require_once("funciones_beneficios.php");

$SedID = 1;
$Mes=8;
$Anio=2022;
$CTiID=5;
$LecID=21;
$NivID=1;
//$sqlFiltro= " and Per_ID=8730 ";
$sqlFiltro= " ";

$sql = "SELECT Persona.Per_ID FROM
    Legajo
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Colegio_Inscripcion 
        ON (Legajo.Leg_ID = Colegio_Inscripcion.Ins_Leg_ID) WHERE Ins_Lec_ID=$LecID AND Ins_Niv_ID=$NivID AND Ins_Baja = 0 AND Leg_Baja = 0 $sqlFiltro AND Per_ID 
    NOT IN (SELECT Cuo_Per_ID FROM CuotaPersona WHERE Cuo_Lec_ID=$LecID AND Cuo_CTi_ID = $CTiID AND Cuo_Niv_ID=$NivID AND Cuo_Mes= $Mes AND Cuo_Anio=$Anio);";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "$sql<br>";

$i=0;
while ($row = mysqli_fetch_array($result)){
	$Numero=0;
	$PerID = $row['Per_ID'];
	//busco el último número de cuota asignado
	$sqlu="SELECT MAX(Cuo_Numero) as Maxi FROM CuotaPersona WHERE Cuo_Lec_ID=$LecID AND Cuo_CTi_ID = $CTiID AND Cuo_Per_ID=$PerID AND Cuo_Anio=$Anio;";
	$resultu = consulta_mysql_2022($sqlu,basename(__FILE__),__LINE__);
	if ($resultu){
		$rowu = mysqli_fetch_array($resultu);
		$Numero=$rowu['Maxi']+1;
		guardarAsignacionCuotaFaltante($PerID, $LecID, $NivID, $CTiID, $Mes, $Anio, $Numero);	
		$i++;
	}
	
}//fin del while

echo "<br>Total de registros: ".mysqli_num_rows($result)."<br>";
echo "<br>Total de registros insertados: ".$i."<br>";
echo "<br>FIN";

//exit;


function guardarAsignacionCuotaFaltante($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $CTi_ID, $mes, $anio, $numero) {

	$Importe='200.00';
	$Recargo1='10.00';
	$RecargoMensual='10.00';
	//$Importe='5680.00';
	//$Recargo1='280.00';
	//$RecargoMensual='280.00';

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
	//echo $sql;

	$result_verif = consulta_mysql_2022($sqlv,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result_verif)==0){
		$Cuo_Ben_ID=1;
		
		$ben=obtenerBeneficioAlumnoNuevo($Cuo_Lec_ID, $Cuo_Per_ID, $CTi_ID, $Cuo_Ben_ID);

		$sqlv = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal, Cuo_Masivo, Cuo_Agrupa)
		values($Cuo_Per_ID, $Cuo_Niv_ID, $CTi_ID, $Cuo_Lec_ID, 1, '$numero', '$Cuo_Ben_ID', '$UsuID', '$Fecha', '$Hora', '$Importe', '$Recargo1', '0.00', '$Vto1', '$Vto2', '$Vto3', $mes, $anio, '0','0','0', '$RecargoMensual', '$Importe', '', '1')";
		
		echo "<br>$sqlv<br>";
		
		$res=consulta_mysql_2022($sqlv,basename(__FILE__),__LINE__);
		
		if ($res){
			
			aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $CTi_ID, 1, '$numero', $Cuo_Ben_ID);

			echo "$Cuo_Per_ID: Se agregó correctamente la nueva cuota<br />";
		}
	
	}		
	
}
	
?>