<?php
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("globales.php");
global $NombreSesion;

session_name(self::$NombreSesion); // this is line 137
session_set_cookie_params(0, COOKIEPATH, null, self::$force_ssl_cookie, true);

if(session_id()) {
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time() - 42000, COOKIEPATH);
    }
    unset($_COOKIE[session_name()]);
    session_destroy();
}

session_name($NombreSesion);
// inicia sessiones
session_start();

if($_POST)
{
	$sesion_usuario = $_POST['sesion_usuario'];
	$sesion_clave = md5($_POST['sesion_clave']);
	$sql = "SELECT * FROM Usuario, Sede WHERE Sed_ID = Usu_Sed_ID AND Usu_Nombre = '$sesion_usuario' AND Usu_Clave = '$sesion_clave'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		
		
			session_name($NombreSesion);
			// inicia sessiones
			session_start();
		
		$row = mysqli_fetch_array($result);
		$_SESSION['sesion_usuario'] = $sesion_usuario;
		$_SESSION['sesion_UsuID'] = $row[Usu_ID];
		$_SESSION['sesion_SedID'] = $row[Usu_Sed_ID];
		$_SESSION['sesion_Sede'] = $row[Sed_Nombre];
		$_SESSION['sesion_persona'] = $row[Usu_Persona];
		registrarSesion($row[Usu_ID]);
		$sql = "SELECT * FROM RolUsuario, Roles WHERE RUs_Rol_ID = Rol_ID AND RUs_Usu_ID = '$row[Usu_ID]'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row_rol = mysqli_fetch_array($result);
			$_SESSION['sesion_rol'] = $row_rol[RUs_Rol_ID];
			$_SESSION['sesion_nombrerol'] = $row_rol[Rol_Nombre];
		}
		$sql = "SELECT DISTINCTROW Prm_Uni_ID, Uni_Foto FROM Permiso, Unidad WHERE Prm_Uni_ID = Uni_ID AND Prm_Usu_ID = '$row[Usu_ID]'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row_uni = mysqli_fetch_array($result);
			if ($row_uni[Prm_Uni_ID]==3) {
				$_SESSION['sesion_Unidad'] = "Santa Maria"; 				
			}else {
				$_SESSION['sesion_Unidad'] = "Colegio";
			}
			$_SESSION['sesion_UniID'] = $row_uni[Prm_Uni_ID]; 
			$_SESSION['sesion_UniFoto'] = $row_uni[Uni_Foto]; 
		}

		echo "Bien";
		//header("Location:index.php");
		/*$saludo = buscarSaludo();
		echo "<table border='0' align='left'>
              <tr><td>$saludo <strong>". $_SESSION['sesion_persona']."</strong></td></tr></table>";?>
			<?php
		//*/
	}else{		
		//echo "Mal";
		
		session_name($NombreSesion);
		session_start();
		// Destruye todas las variables de la sesi&oacute;n
		session_unset();
		// Finalmente, destruye la sesi&oacute;n
		session_destroy();
		echo "El nombre de usuario o la clave son incorrectas.";
	}
		

}//fin post

?>