<?php 
require_once("globales.php");

function obtenerBeneficioAlumnoLectivoNuevo($Lec_ID, $Per_ID){

	$arrayBeneficios = array();
	$sql = "SELECT * FROM PersonaBeneficio
    INNER JOIN CuotaBeneficio 
        ON (CBe_Ben_ID = Ben_ID) WHERE 
        CBe_Lec_ID = '$Lec_ID' AND 
        CBe_Per_ID = $Per_ID AND 
        CBe_Activo = 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			
			$arrayBeneficios[] = array('CBe_ID' => $row['CBe_ID'], 'CBe_Ben_ID' => $row['CBe_Ben_ID'], 'CBe_Lec_ID' => $row['CBe_Lec_ID'], 'CBe_Desde' => $row['CBe_Desde'], 'CBe_Hasta' => $row['CBe_Hasta'], 'CBe_CTi_ID' => $row['CBe_CTi_ID'], 'Ben_Nombre' => $row['Ben_Nombre']);
		}
		
		
	}
	return $arrayBeneficios;
	
}//fin funcion

function obtenerBeneficioAlumnoNuevo($Lec_ID, $Per_ID, $CTi_ID, &$Cuo_Ben_ID=1){

	$sql = "SELECT DISTINCTROW Ben_Nombre, CBe_Ben_ID FROM PersonaBeneficio
	INNER JOIN CuotaBeneficio ON (CBe_Ben_ID = Ben_ID) WHERE 
	CBe_Lec_ID = '$Lec_ID' AND 
	CBe_Per_ID = $Per_ID AND 
	CBe_CTi_ID = $CTi_ID AND 
	CBe_Activo = 1";

	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Cuo_Ben_ID = $row['CBe_Ben_ID'];
		return $row['Ben_Nombre'];
	}else {
		$Cuo_Ben_ID=1;
		return false;
	}
	//echo $sql;
}//fin funcion

function obtenerBeneficioAlumnoSimple($Lec_ID, $Per_ID, &$Cuo_Ben_ID=1){

	$sql = "SELECT DISTINCTROW Ben_Nombre, CBe_Ben_ID FROM PersonaBeneficio
	INNER JOIN CuotaBeneficio ON (CBe_Ben_ID = Ben_ID) WHERE 
	CBe_Lec_ID = '$Lec_ID' AND 
	CBe_Per_ID = $Per_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Cuo_Ben_ID = $row['CBe_Ben_ID'];
		return $row['Ben_Nombre'];
	}else {
		return false;
	}
	
}//fin funcion

?>