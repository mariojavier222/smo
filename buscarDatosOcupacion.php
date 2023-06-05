<?php
//$q = strtolower($_GET["suggest3"]);
$q = strtolower($_GET["q"]);
$distintos = $_GET['distintos'];
//if (!$q) return "{}"; 
require_once("conexion.php");
//include("mysql2json.class.php");
//header("Content-Type: text/plain");

$sqlWhere = "WHERE Dat_Ocupacion LIKE '%$q%'";

$sql = "SELECT DISTINCTROW Dat_Ocupacion FROM PersonaDatos $sqlWhere ORDER BY Dat_Ocupacion";

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

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
			$datos .= "{\"Dat_Ocupacion\": \"".$row[Dat_Ocupacion]."\"}";
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