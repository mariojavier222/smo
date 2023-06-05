<?php
// 23012013 10 h

require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
//sleep(3);

$opcion = $_POST['opcion'];

if ($opcion=="guardarFormaPago"){

	//$id = $_POST['For_ID'];
	$Nombre = $_POST['Nombre'];
	$Sigla=$_POST['Sigla'];
	$For_ID=$_POST['For_ID'];
	$For_Cue_ID=$_POST['For_Cue_ID'];
        
    $sql = "SELECT * FROM FormaPago WHERE For_ID = '$For_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $valor = mysqli_num_rows($result);
    if($valor > 0){ 			
			$sql = "UPDATE FormaPago SET For_Cue_ID = $For_Cue_ID WHERE For_ID = '$For_ID';";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Los datos han sido modificacos correctamente.";
    }else{
       
        $sql1 = "INSERT INTO FormaPago (For_Nombre,For_Siglas, For_Cue_ID) VALUES ('$Nombre','$Sigla', '$For_Cue_ID')";
        consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
        echo "Los datos han sido insertados correctamente.";
}
}


if ($opcion=="eliminarFormaPago"){

	$id=$_POST['ID'];

	$sql = "DELETE FROM FormaPago WHERE For_ID = $id";

	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Los datos han sido eliminados correctamente.";
}




if ($opcion=="listado"){

	$sql = "SELECT * FROM CuotaBeneficio ORDER BY Ben_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<option value='0' selected='selected'> Seleccionar</option>";
	//echo "<select name='$sicopedagoga' id='$sicopedagoga' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Ben_ID]'>$row[Ben_Nombre]</option>";
	}//fin del while
	//echo "</select>";

	
}
?>
