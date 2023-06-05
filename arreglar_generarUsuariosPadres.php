
<?
set_time_limit(120);
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$sql = "SELECT DISTINCTROW Per_DNI, Per_Apellido, Per_Nombre FROM Familia INNER JOIN Persona 
        ON (Fam_Vin_Per_ID = Per_ID)
WHERE (Familia.Fam_FTi_ID =1)";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		$DNI = $row[Per_DNI];
		$Apellido = $row[Per_Apellido];
		$Nombre = $row[Per_Nombre];
		echo "Padre: $Nombre $Apellido ($DNI)<br />";
		guardarCuentaUsuario($DNI, $DNI, "$Nombre $Apellido");
		guardarRolUnico($DNI, 11, true);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />....FIN....<br />";
	echo "Total de registros: ".mysqli_num_rows($result);
}//fin if


function guardarRolUnico($Usuario="", $Rol="", $BuscarIDUsuario=false){
	//echo "Hola";exit;
	if (empty($Usuario) && empty($Rol)){
		$Usuario = $_POST['UsuID'];
		$Rol = $_POST['RolID'];
	}
	if ($BuscarIDUsuario){
		$sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$Usuario'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			$Usuario = $row[Usu_ID];
		}
	}

	$sql = "SELECT * FROM RolUsuario WHERE RUs_Usu_ID = '$Usuario'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
		$sql = "INSERT INTO RolUsuario (RUs_Usu_ID, RUs_Rol_ID) VALUES ('$Usuario', '$Rol')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se agregó correctamente el rol al usuario.";
	}else{
		$sql = "UPDATE RolUsuario SET RUs_Rol_ID = '$Rol' WHERE RUs_Usu_ID = '$Usuario'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se actualizó el rol al usuario.";
	}
	
	$sql = "SELECT * FROM RolUnidad WHERE RUn_Rol_ID = '$Rol'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		$sql = "SELECT * FROM Permiso WHERE Prm_Usu_ID = '$Usuario' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
		$result_permiso = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result_permiso)>0){
			//ya existe, actualizamos
			$sql = "UPDATE Permiso SET Prm_Visible = '$row[RUn_Visible]', Prm_Bloqueado = '$row[RUn_Bloqueado]', Prm_Guardar = '$row[RUn_Guardar]', Prm_Modificar = '$row[RUn_Modificar]', Prm_Eliminar = '$row[RUn_Eliminar]', Prm_Imprimir = '$row[RUn_Imprimir]' WHERE Prm_Usu_ID = '$Usuario' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}else{
			//Insertamos el nuevo permiso
			$sql = "INSERT INTO Permiso (Prm_Usu_ID, Prm_Men_ID, Prm_Opc_ID, Prm_Uni_ID, Prm_Visible, Prm_Bloqueado, Prm_Guardar, Prm_Modificar, Prm_Eliminar, Prm_Imprimir) VALUES ('$Usuario', '$row[RUn_Men_ID]', '$row[RUn_Opc_ID]', '$row[RUn_Uni_ID]', '$row[RUn_Visible]', '$row[RUn_Bloqueado]', '$row[RUn_Guardar]', '$row[RUn_Modificar]', '$row[RUn_Eliminar]', '$row[RUn_Imprimir]')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}
		
	}//fin del while
	

}//fin funcion


?>