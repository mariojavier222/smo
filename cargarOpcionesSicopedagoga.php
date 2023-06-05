<?php
// 23012013 10 h

require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
//sleep(3);

$opcion = $_POST['opcion'];

if ($opcion=="guardarSicopedagoga"){

	//$id = $_POST['Sic_ID'];
	$nombre = $_POST['nombre'];
	$dni=$_POST['dni'];
        $tel=$_POST['tel']; 

	$sql = "INSERT INTO Sicopedagoga (Sic_Nombre,Sic_DNI, Sic_Tel) VALUES ('$nombre','$dni', '$tel')";

	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Los datos han sido insertados correctamente.";
}



if ($opcion=="eliminarSicopedagoga"){

	$id=$_POST['ID'];

	$sql = "DELETE FROM Sicopedagoga WHERE Sic_ID = $id";

	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Los datos han sido eliminados correctamente.";
}

if ($opcion=="listado"){

	$sql = "SELECT * FROM Sicopedagoga ORDER BY Sic_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<option value='0' selected='selected'> Seleccionar</option>";
	//echo "<select name='$sicopedagoga' id='$sicopedagoga' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Sic_ID]'>$row[Sic_Nombre]</option>";
	}//fin del while
	//echo "</select>";

	
}






?>
