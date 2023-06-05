<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$fechaDesde = "2021-04-01";
$fechaHasta = "2021-12-31";
$Lec_ID = 20;
$mes = 8;
$anio = 2021;
$CTi_ID = 2;


$sql = "SELECT * FROM CuotaPersona INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CuotaTipo.CTi_ID) WHERE Cuo_Mes=$mes AND Cuo_Anio=$anio AND Cuo_CTi_ID = $CTi_ID AND Cuo_Ben_ID>1 ORDER BY Cuo_Per_ID";// LIMIT 0,100";    
echo "$sql<br>";     
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$i=0;
while ($row = mysqli_fetch_array($result)){
	set_time_limit(0);
	$i++;
	$Ben_ID = $row['Cuo_Ben_ID'];
	$Per_ID = $row['Cuo_Per_ID'];
	$sql = "INSERT INTO PersonaBeneficio (CBe_Ben_ID, CBe_Lec_ID, CBe_Per_ID, CBe_Desde, CBe_Hasta, CBe_CTi_ID) VALUES ($Ben_ID, $Lec_ID, $Per_ID, '$fechaDesde', '$fechaHasta', $CTi_ID)";
	echo "$sql<br>";     
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
}
echo "Total: $i registros";


?>