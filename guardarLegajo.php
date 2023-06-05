<?php
//echo $_POST['DNI'];exit;
require_once("conexion.php");
require_once("funciones_generales.php");
//require_once("cargar_opciones.php");
$DNI = $_POST['DNI'];

$PerID = trim($_POST['PerID']);
$FechaAlta = cambiaf_a_mysql($_POST['fechaAlta']);

$Motivo = $_POST['motivo'];
//$Legajo = $_POST['Legajo'];En Gateway el Legajo es igual al DNI
$Legajo = $DNI;
$SedID = $_POST['SedID'];
$TipoLegajo = $_POST['TipoLegajo'];
$Leg_Colegio = $_POST['Leg_Colegio'];

$Leg_Baja = $_POST['Leg_Baja'];
$FechaBaja = $_POST['fechaBaja'];

if (strlen($FechaBaja)<6) $FechaBaja ='0000-00-00';

$Fecha = date("Y-m-d");
$Hora = date("H:i:s");
$UsuID = $_POST['UsuID'];

if (empty($FechaAlta)) $Fecha;

//if ($TipoLegajo=="Colegio") $sqlLegajo = "Leg_Colegio = 1"; else $sqlLegajo = "Leg_StaMaria = 1";
$sql = "SELECT * FROM Legajo WHERE Leg_Per_ID = $PerID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	//Ya existe la persona con el legajo asignado
	if ($Leg_Baja==1){
		$FechaBaja = cambiaf_a_mysql($_POST['fechaBaja']);
		$sqlFechaBaja = ", Leg_Baja_Fecha = '$FechaBaja', Leg_Baja = 1, Leg_Baja_Motivo = '$Motivo', Leg_Baja_Usu_ID = '$UsuID'";
	}else{
		$sqlFechaBaja = ", Leg_Baja = 0, Leg_Baja_Motivo = '', Leg_Baja_Usu_ID = '' ";
	}
	/* 
	if (!empty($FechaBaja)){
		$FechaBaja = cambiaf_a_mysql($_POST['fechaBaja']);
		$sqlFechaBaja = ", Leg_Baja_Fecha = '$FechaBaja', Leg_Baja = 1, Leg_Baja_Motivo = '$Motivo' ";
	}*/
	//if ($TipoLegajo=="Colegio") $TipoLegajo = "Leg_Colegio = 1, Leg_StaMaria = 0"; else $TipoLegajo = "Leg_Colegio = 0, Leg_StaMaria = 1";
	$row = mysqli_fetch_array($result);
	$sql = "UPDATE Legajo SET Leg_Numero = '$Legajo', Leg_Sed_ID = '$SedID', Leg_Alta_Fecha = '$FechaAlta', Leg_Fecha = '$Fecha', Leg_Hora = '$Hora', Leg_Usu_ID = '$UsuID', Leg_Colegio = '$Leg_Colegio' $sqlFechaBaja WHERE Leg_ID = $row[Leg_ID]";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	gObtenerApellidoNombrePersona($DNI, $apellido, $nombre);
	guardarCuentaUsuario($DNI, $DNI, "$apellido, $nombre", $row['Leg_ID'], $SedID);
	echo "Los datos han sido actualizados correctamente.";//*/
}else{
	//if ($TipoLegajo=="Colegio") $TipoLegajo = "1, 0"; else $TipoLegajo = "0, 1";
	$Baja = 0;
	if (!empty($FechaBaja) && $FechaBaja=!'0000-00-00') {
		$FechaBaja = cambiaf_a_mysql($_POST['fechaBaja']);
		$Baja = 1;
	}
	$sql = "INSERT INTO Legajo(Leg_Sed_ID, Leg_Per_ID, Leg_Numero, Leg_Alta_Fecha, Leg_Usu_ID, Leg_Fecha, Leg_Hora, Leg_Colegio) VALUES ('$SedID', '$PerID', '$Legajo', '$FechaAlta', '$UsuID', '$Fecha', '$Hora', '$Leg_Colegio')";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	gObtenerApellidoNombrePersona($DNI, $apellido, $nombre);
	$sql = "SELECT * FROM Legajo WHERE Leg_Per_ID = $PerID";// AND $sqlLegajo";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	guardarCuentaUsuario($DNI, $DNI, "$apellido, $nombre", $row['Leg_ID'], $SedID);//*/
	echo "Los datos han sido insertados correctamente.";//*/
}//fin del esle
//*/
?>
