<?php
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("globales.php");
//global $NombreSesion;
if($_POST)
{
	$DNI_Padre = $_POST['DNI_Padre'];	
	$sql = "SELECT * FROM UsuarioWeb WHERE Usu_Nombre = '$DNI_Padre'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		//El usuario ya fue dado de alta en el sistema como usuario
		$row = mysqli_fetch_array($result);
		$Activo = $row[Usu_Activo];
		if ($Activo==0){
			$error = "Error: Usted ya se encuentra dado de alta como padre/madre. Para finalizar el registro debe acercarse por el Colegio.";
		}else{
			$error = "Error: Usted ya se encuentra dado de alta como padre/madre. Ingrese con su usuario y clave elegida desde la página principal del sistema";
		}
	}else{
		//No se encuentra dado de alta como usuario
		$error = "Siguiente";
	}
	echo $error;			

}//fin post

?>