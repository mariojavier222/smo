<?
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;

$sql = "SELECT * FROM migracion_rapidni"; //echo "$sql<br>";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br /><br /><br />";
if (mysqli_num_rows($result)>0){
	
	while ($row = mysqli_fetch_array($result)){
		$id = $row['id'];
		$buscar = buscarDNI($row['per_id'], $DNI, $alumno);
		if ($buscar){
			$sql = "UPDATE migracion_rapidni SET dni='$DNI', nombre = '$alumno' WHERE id = $id";
		}else{
			$sql = "UPDATE migracion_rapidni SET nombre='no se encontro' WHERE id = $id";
		}
		echo "$sql<br>";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}//fin while
	echo "<br />....FIN....<br />";
	echo "Total de registros: ".mysqli_num_rows($result);
}//fin if


function buscarDNI($PerID, &$DNI, &$alumno){
	//echo "Hola";exit;
	$sql = "SELECT * FROM Persona         
WHERE Per_ID=$PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;exit;
	if (mysqli_num_rows($result)>0){
			
		$row = mysqli_fetch_array($result);
		$DNI = $row['Per_DNI'];	
		$alumno = $row['Per_Apellido'].", ".$row['Per_Nombre'];	
		return true;	
	}else{
		return false;
	}//fin if

}//fin funcion
?>