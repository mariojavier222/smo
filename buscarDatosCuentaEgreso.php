<?php
//$q = strtolower($_GET["suggest3"]);
$q = strtolower($_GET["q"]);
$distintos = $_GET['distintos'];
//if (!$q) return "{}"; 
require_once("conexion.php");
//include("mysql2json.class.php");
//header("Content-Type: text/plain");
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sqlWhere = "WHERE Cue_Nombre LIKE '$q%'";


$sql = "SELECT * FROM Egreso_Cuenta $sqlWhere ORDER BY Cue_Nombre";
//if (!empty($distintos)) $sql = "SELECT DISTINCTROW Per_Apellido FROM Persona $sqlWhere ORDER BY Per_Apellido, Per_Nombre";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

/*while($filas = $resultado_user->fetch_object()){
	$arr[] = $filas;
}//fin while
    
echo json_encode($arr); //*/
$total = mysqli_num_rows($result);
if (mysqli_num_rows($result)==0) {
	/*$sql = "SELECT * FROM Persona $sqlWhere ORDER BY Per_Apellido, Per_Nombre";
	if (!empty($distintos)) $sql = "SELECT DISTINCTROW Per_Apellido FROM Persona $sqlWhere ORDER BY Per_Apellido, Per_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);*/
	
	$datos = "{}";
}else{
	$datos = "[";
			$i=0;   
	while ($row = mysqli_fetch_array($result)){
			$i++;
			$datos .= "{\"Cue_ID\": \"" . $row[Cue_ID] . "\",\"";
			$datos .= "Cue_Nombre\": \"" . $row[Cue_Nombre] . "\",\"";
			$datos .= "Cue_Usu_ID\": \"" . $row[Cue_Usu_ID] . "\",\"";
			$datos .= "Cue_Fecha\": \"" . $row[Cue_Fecha] . "\",\"";
			$datos .= "Cue_Hora\": \"" . $row[Cue_Hora] . "\"}";
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