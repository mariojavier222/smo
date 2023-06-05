<?php
//$q = strtolower($_GET["suggest3"]);
$q = strtolower($_GET["q"]);
$distintos = $_GET['distintos'];
//if (!$q) return "{}"; 
require_once("conexion.php");
require_once("funciones_generales.php");
//include("mysql2json.class.php");
//header("Content-Type: text/plain");
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sqlWhere = "WHERE Per_Apellido LIKE '$q%'";
$sqlWhere = "WHERE (Per_Apellido LIKE '$q%' OR (Per_Apellido LIKE '%$q%'))";
if (strpos($q, ",")){
	list($apellido, $nombre) = explode(",",$q);
	$sqlWhere = "WHERE Per_Apellido LIKE '$apellido%' AND Per_Nombre LIKE '$nombre%'";
}


$sql = "SELECT * FROM Persona $sqlWhere ORDER BY Per_Apellido, Per_Nombre";
if (!empty($distintos)) $sql = "SELECT DISTINCTROW Per_Apellido FROM Persona $sqlWhere ORDER BY Per_Apellido, Per_Nombre";
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
			//Buscamos si es alumno de este año
			$LecID = gLectivoActual($LecNombre);
			$relacion = "";
			if ($es = obtenerAlumno($row[Per_ID], $LecID))
				$relacion = "$es";//*/
			//$relacion = obtenerAlumno($row['Per_ID'], $LecID);
			//Buscamos si es padre
			if ($es = obtenerPadreMadre($row[Per_ID]))
				$relacion = "$es";
			//*/
			$i++;
			$datos .= "{\"Per_ID\": \"".$row[Per_ID]."\", ";
			$datos .= "\"Per_Doc_ID\": \"".$row[Per_Doc_ID]."\", ";
			$datos .= "\"Per_DNI\": \"".$row[Per_DNI]."\", ";
			$datos .= "\"Per_Apellido\": \"".$row[Per_Apellido]."\", ";
			$datos .= "\"Per_Nombre\": \"".$row[Per_Nombre]."\", ";
			$datos .= "\"Per_Sexo\": \"".$row[Per_Sexo]."\", ";
			$datos .= "\"Per_Foto\": \"".$row[Per_Foto]."\", ";
			$datos .= "\"Per_Fecha\": \"".$row[Per_Fecha]."\", ";
			$datos .= "\"Per_Hora\": \"".$row[Per_Hora]."\", ";
			$datos .= "\"Per_Alternativo\": \"".$row[Per_Alternativo]."\", ";
			$datos .= "\"Relacion\": \"".$relacion."\", ";
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