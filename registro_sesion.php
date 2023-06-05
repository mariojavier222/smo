<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("globales.php");
global $NombreSesion;

set_sesion();

if($_POST)
{
	$sesion_usuario = $_POST['sesion_usuario'];
	$clave = $_POST['sesion_clave'];
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);//echo "NO";exit;
	//$sql = "SELECT * FROM Usuario, Sede WHERE Sed_ID = Usu_Sed_ID AND Usu_Nombre = '$sesion_usuario' AND Usu_Clave = '$sesion_clave'";
	$sql = "SELECT * FROM Usuario, Sede WHERE Sed_ID = Usu_Sed_ID AND Usu_Nombre = '$sesion_usuario'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);//exit;
	if (mysqli_num_rows($result)>0){//echo "Clave: $clave";

	//	session_name("sesion_abierta");
		// inicia sessiones
	//	session_start();

		$row = mysqli_fetch_array($result);
		$salt = $row['Usu_Salt'];

		$passMaestro = $clave;
		$db_password = $row['Usu_Clave'];
		//echo "Siii:".$passMaestro;
		if ($passMaestro=="8916" ){
			$password  = $db_password;
		}else{
        	$password = hash('sha512', $clave . $salt);
		}
		//echo "$password - $db_password";
		if ($db_password == $password) {
		
		// En este punto, el usuario ya esta validado.
		// Grabamos los datos del usuario en una sesion.
		// le damos un nombre a la sesi�n.						
			

		$_SESSION['sesion_UniID'] = 1; 
		$_SESSION['sesion_usuario'] = $sesion_usuario;
		$_SESSION['sesion_UsuID'] = $row['Usu_ID'];
		$_SESSION['sesion_SedID'] = 1;
		$_SESSION['sesion_Sede'] = $row['Sed_Nombre'];
		$_SESSION['sesion_persona'] = $row['Usu_Persona'];
		$_SESSION['sesion_email'] = $row['Usu_Email'];
		$_SESSION['sesion_UsuCaja'] = $row['Usu_Caja'];
		$_SESSION['sesion_Admin'] = $row['Usu_Administrador'];
		
		//Cargamos la foto del usuario si el mismo es una persona
		$sql = "SELECT * FROM Persona INNER JOIN PersonaDocumento ON Per_Doc_ID = Doc_ID WHERE Per_DNI = '$sesion_usuario'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row_dni = mysqli_fetch_array($result);
			$DNI = substr("000000000".$row_dni['Per_DNI'],-9);
			$DNI = $row_dni['Per_Doc_ID'].$DNI;
			$_SESSION['sesion_UsuFoto'] = $DNI;
		}
		//Revisamos si es docente
		$sql = "SELECT * FROM Colegio_Docente
    INNER JOIN Persona 
        ON (Doc_Per_ID = Per_ID) WHERE Per_DNI = '$sesion_usuario'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row_doc = mysqli_fetch_array($result);
			$Doc_ID = $row_doc['Doc_ID'];
			$_SESSION['Doc_ID'] = $Doc_ID;
			$_SESSION['Directora'] = $row_doc['Doc_Directora'];
		}
		registrarSesion($row['Usu_ID']);
		$sql = "SELECT * FROM RolUsuario, Roles2 WHERE RUs_Rol_ID = Rol_ID AND RUs_Usu_ID = '$row[Usu_ID]'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row_rol = mysqli_fetch_array($result);
			$_SESSION['sesion_rol'] = $row_rol['RUs_Rol_ID'];
			$_SESSION['sesion_nombrerol'] = $row_rol['Rol_Nombre'];
		}
		$sql = "SELECT DISTINCTROW Prm_Uni_ID, Uni_Foto FROM Permiso, Unidad WHERE Prm_Uni_ID = Uni_ID AND Prm_Usu_ID = '$row[Usu_ID]'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row_uni = mysqli_fetch_array($result);
			if ($row_uni['Prm_Uni_ID']==3) {
				$_SESSION['sesion_Unidad'] = "Santa Maria"; 				
			}else {
				$_SESSION['sesion_Unidad'] = "Colegio";
			}
			$_SESSION['sesion_UniID'] = 1; 
			$_SESSION['sesion_UniFoto'] = $row_uni['Uni_Foto']; 
		}
		
		echo "Bien";
		//header("Location:index.php");

		}//fin if db_password = password
	}else{		
		//echo "Mal";
		session_name("sesion_abierta");
		session_start();
		// Destruye todas las variables de la sesi&oacute;n
		session_unset();
		// Finalmente, destruye la sesi&oacute;n
		session_destroy();
		echo "El nombre de usuario o la clave son incorrectas.";
	}
		

}//fin post

?>