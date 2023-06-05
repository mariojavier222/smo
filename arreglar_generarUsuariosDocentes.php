<?
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$sql = "SELECT * FROM
    Colegio_Docente
    INNER JOIN Persona 
        ON (Colegio_Docente.Doc_Per_ID = Persona.Per_ID)";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		$DNI = $row[Per_DNI];
		$Apellido = $row[Per_Apellido];
		$Nombre = $row[Per_Nombre];
		echo "Docente: $Nombre $Apellido<br />";
		guardarCuentaUsuario($DNI, $DNI, "$Nombre $Apellido");
		guardarRolUnico2($DNI, 6, true);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />....FIN....<br />";
	echo "Total de registros: ".mysqli_num_rows($result);
}//fin if


function guardarRolUnico2($Usuario="", $Rol="", $BuscarIDUsuario=false){
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