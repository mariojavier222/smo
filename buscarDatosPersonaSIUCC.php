<?php
//$q = strtolower($_GET["suggest3"]);
$q = strtolower($_GET["q"]);
$distintos = $_GET['distintos'];
//if (!$q) return "{}"; 
require_once("conexion.php");
//include("mysql2json.class.php");
//header("Content-Type: text/plain"); 
$sql = "SELECT * FROM Persona WHERE Per_Apellido LIKE '$q%' ORDER BY Per_Apellido, Per_Nombre";
if (!empty($distintos)) $sql = "SELECT DISTINCTROW Per_Apellido FROM Persona WHERE Per_Apellido LIKE '$q%' ORDER BY Per_Apellido, Per_Nombre";
$result = consulta_mysql($sql);

/*while($filas = $resultado_user->fetch_object()){
	$arr[] = $filas;
}//fin while
    
echo json_encode($arr); //*/
$total = mysqli_num_rows($result);
if (mysqli_num_rows($result)==0) {
	$datos = "{}";
}else{
	$datos = "[";
			$i=0;   
	while ($row = mysqli_fetch_array($result)){
			$i++;
			$datos .= "{\"Per_ID\": \"".$row[Per_ID]."\", ";
			$datos .= "\"Per_Doc_ID\": \"".$row[Per_Doc_ID]."\", ";
			$datos .= "\"Per_DNI\": \"".$row[Per_ID]."\", ";
			$datos .= "\"Per_Apellido\": \"".utf8_decode($row[Per_Apellido])."\", ";
			$datos .= "\"Per_Nombre\": \"".utf8_decode($row[Per_Nombre])."\", ";
			$datos .= "\"Per_Sexo\": \"".$row[Per_Sexo]."\", ";
			$datos .= "\"Per_Foto\": \"".$row[Per_Foto]."\", ";
			$datos .= "\"Per_Fecha\": \"".$row[Per_Fecha]."\", ";
			$datos .= "\"Per_Hora\": \"".$row[Per_Hora]."\", ";
			$datos .= "\"Per_Alternativo\": \"".$row[Per_Alternativo]."\", ";
			$datos .= "\"Per_Extranjero\": \"".$row[Per_Extranjero]."\"}";
			if ($i<$total) 
				$datos .= ", ";
	}//fin while
	$datos .= "]";
}//fin else//*/

echo $datos;

/*$num=mysql_affected_rows();
//calling class
$objJSON=new mysql2json();
print(trim($objJSON->getJSON($result,$num)));
//*/

?>