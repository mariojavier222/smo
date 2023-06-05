<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
require_once("conexion.php");
require_once("funciones_generales.php");
$DNI = $_POST['DNI'];
$PerID = $_POST['PerID'];
$DomProID = $_POST['DomProID'];
$DomPaiID = $_POST['DomPaisID'];
$DomLocID = $_POST['DomLocID'];
$NacProID = $_POST['NacProID'];
$NacPaiID = $_POST['NacPaisID'];
$NacLocID = $_POST['NacLocID'];
$FechaNac = cambiaf_a_mysql($_POST['fechaNac']);
$Domicilio = strtoupper(addslashes(utf8_decode($_POST['direccion'])));
$CP = $_POST['cp'];
$Email = $_POST['correo'];
$Telefono = $_POST['telefono'];
$Celular = $_POST['celular'];
$Ocupacion = arreglarCadenaMayuscula(trim(utf8_decode(strtoupper($_POST['Ocupacion']))));
$Observ = strtoupper(addslashes(utf8_decode($_POST['observ'])));
$Fecha = date("Y-m-d");
$Hora = date("H:i:s");


$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = $PerID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	//Ya existe la persona
	$sql = "UPDATE PersonaDatos SET Dat_Dom_Pai_ID = $DomPaiID, Dat_Dom_Pro_ID = $DomProID, Dat_Dom_Loc_ID = $DomLocID, Dat_Nac_Pai_ID = $NacPaiID, Dat_Nac_Pro_ID = $NacProID, Dat_Nac_Loc_ID = $NacLocID, Dat_Nacimiento = '$FechaNac', Dat_Domicilio = '$Domicilio', Dat_CP = '$CP', Dat_Email = '$Email', Dat_Telefono = '$Telefono', Dat_Celular = '$Celular', Dat_Ocupacion = '$Ocupacion', Dat_Observaciones = '$Observ', Dat_Fecha = '$Fecha', Dat_Hora = '$Hora' WHERE Dat_Per_ID = $PerID";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Los datos han sido actualizados correctamente.";
	}else{

	$sql = "INSERT INTO PersonaDatos(Dat_Per_ID, Dat_Dom_Pro_ID, Dat_Dom_Pai_ID, Dat_Dom_Loc_ID, Dat_Nac_Pro_ID, Dat_Nac_Pai_ID, Dat_Nac_Loc_ID, Dat_Nacimiento, Dat_Domicilio, Dat_CP, Dat_Email, Dat_Telefono, Dat_Celular, Dat_Ocupacion, Dat_Observaciones, Dat_Fecha, Dat_Hora) VALUES ($PerID, $DomProID, $DomPaiID, $DomLocID, $NacProID, $NacPaiID, $NacLocID, '$FechaNac', '$Domicilio', '$CP', '$Email', '$Telefono', '$Celular', '$Ocupacion',  '$Observ', '$Fecha', '$Hora')";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Los datos han sido insertados correctamente.";
}//fin del esle

?>
