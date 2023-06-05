<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$fechaDesde = "2023-03-01";
$fechaHasta = "2023-12-31";
$Lec_ID = 23;
$CTi_ID = 2;

/*
//armo la tabla de beneficios temporal
$sql = "SELECT * FROM
    PersonaBeneficio_2023
    INNER JOIN Alumno 
        ON (Alu_Matricula = Matr) ORDER BY Alu_DNI";

echo "$sql<br>";     

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$i=0;
while ($row = mysqli_fetch_array($result)){
	set_time_limit(0);
	$i++;
	$Ben_ID = $row['Ben_ID'];
	$Matr = $row['Alu_Matricula'];
	$DNI = $row['Alu_DNI'];
	$sql = "INSERT INTO PersonaBeneficio_tmp(Matr, DNI, Ben_ID, CBe_Desde, CBe_Hasta) VALUES ($Matr, $DNI, $Ben_ID, '$fechaDesde', '$fechaHasta')";
	echo "$sql<br>";     
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
}
echo "Total: $i registros";
*/

//asigno los beneficios
$sql = "SELECT * FROM
    PersonaBeneficio_tmp
    INNER JOIN Persona 
        ON (DNI = Per_DNI)  ORDER BY DNI";

echo "$sql<br>";     
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$i=0;
while ($row = mysqli_fetch_array($result)){
	set_time_limit(0);
	$i++;
	$Ben_ID = $row['Ben_ID'];
	$Per_ID = $row['Per_ID'];
	$sql = "INSERT INTO PersonaBeneficio (CBe_Ben_ID, CBe_Lec_ID, CBe_Per_ID, CBe_Desde, CBe_Hasta, CBe_CTi_ID) VALUES ($Ben_ID, $Lec_ID, $Per_ID, '$fechaDesde', '$fechaHasta', $CTi_ID)";
	echo "$sql<br>";     
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
}
echo "Total: $i registros";


?>