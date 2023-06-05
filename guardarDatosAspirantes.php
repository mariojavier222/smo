<?php
//23012013 10 h

include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");


/* datos persona */
$DNI = $_POST['DNI'];
//$_SESSION['sesion_ultimoDNI'] = $DNI;
$Apellido = strtoupper(addslashes(utf8_decode($_POST['Apellidos'])));
$Nombre = ucwords(strtolower(addslashes(utf8_decode($_POST['Nombre']))));
$Sexo = $_POST['Sexo'];
$DocID=$_POST['DocID'];

//$Extranjero = $_POST['Extranjero'];
//$Alternativo = $_POST['Alternativo'];

// otros datos
//$EntID = $_POST['EntID'];
//$NivID = $_POST['NivID'];
$DomProID = $_POST['DomProID'];
$DomPaiID = $_POST['DomPaisID'];
$DomLocID = $_POST['DomLocID'];
$NacProID = $_POST['NacProID'];
$NacPaiID = $_POST['NacPaisID'];
$NacLocID = $_POST['NacLocID'];
$FechaNac =$_POST['fechaNac'];// cambiaf_a_mysql($_POST['fechaNac']);
$Domicilio = strtoupper(addslashes(utf8_decode($_POST['direccion'])));
$CP = $_POST['cp'];
$Email = $_POST['correo'];
$Telefono = $_POST['telefono'];
$Celular = $_POST['celular'];
$Ocupacion = arreglarCadenaMayuscula(trim(utf8_decode(strtoupper($_POST['Ocupacion']))));
$Observ = strtoupper(addslashes(utf8_decode($_POST['observ'])));
$Fecha = date("Y-m-d");
$Hora = date("H:i:s");

obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI ";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

while ($row = mysqli_fetch_array($result)){
$PerID=$row[Per_ID];
}

if (mysqli_num_rows($result)>0){
	
	//Ya existe el DNI a cargar
	
$sql = "UPDATE Persona SET Per_Doc_ID = $DocID, Per_Apellido = '$Apellido', Per_Nombre = '$Nombre', Per_Sexo = '$Sexo', Per_Alternativo = '$Alternativo', Per_Extranjero = '$Extranjero' WHERE Per_DNI = $DNI";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$sql = "UPDATE PersonaDatos SET  Dat_Dom_Pai_ID = $DomPaiID, Dat_Dom_Pro_ID = $DomProID, Dat_Dom_Loc_ID = $DomLocID, Dat_Nac_Pai_ID = $NacPaiID, Dat_Nac_Pro_ID = $NacProID, Dat_Nac_Loc_ID = $NacLocID, Dat_Nacimiento = '$FechaNac', Dat_Domicilio = '$Domicilio', Dat_CP = '$CP', Dat_Email = '$Email', Dat_Telefono = '$Telefono', Dat_Celular = '$Celular', Dat_Ocupacion = '$Ocupacion', Dat_Observaciones = '$Observ', Dat_Fecha = '$Fecha', Dat_Hora = '$Hora' WHERE Dat_Per_ID = $PerID";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Los datos han sido actualizados correctamente.";
	
	}
	else{
	// parte nueva
//        $sql_1 = "INSERT INTO Legajo (Leg_Usu_ID, Leg_Sed_ID, Leg_Per_ID, Leg_Numero, Leg_Alta_Fecha, Leg_Baja, Leg_Baja_Fecha, Leg_Baja_Motivo, Leg_Colegio, Leg_StaMaria, Leg_Fecha, Leg_Hora)
//            VALUES ($UsuID, $NivID, '1', $PerID, '1', $Fecha, '1', $Fecha, 'varios', '1', '','','' )";
//        consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
//        
//        echo "";
        
        // insertar persona nueva
        
  	$sql = "INSERT INTO Persona (Per_DNI, Per_Doc_ID, Per_Apellido, Per_Nombre, Per_Sexo,Per_Fecha, Per_Hora) VALUES ($DNI, $DocID, '$Apellido', '$Nombre', '$Sexo','$Fecha', '$Hora')";	
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	

 
/* recuperar id de la ultima persona  */

$personaID=mysql_insert_id();

/* datos personales */

$PerID = $personaID;

	$Asp_Per_ID=$PerID;
	$Asp_Lec_ID = $_POST['LecID2'];
	$Ins_Nivel = $_POST['EntNivID'];
	$Ins_Curso = $_POST['EntCurID'];
	$Ins_Legajo = $_POST['Legajo'];

	$Asp_Usu_ID = $_SESSION['sesion_UsuID'];//$UsuID;
	
	$Asp_Fecha=$Fecha;
	$Asp_Hora=$Hora;
	
	$sql = "INSERT INTO Aspirante (Asp_Per_ID,Asp_Lec_ID,Asp_Usu_ID,Asp_Fecha,Asp_Hora)
				  VALUES ($Asp_Per_ID,$Asp_Lec_ID,$Asp_Usu_ID, '$Asp_Fecha','$Asp_Hora')";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	

$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = $PerID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	if (mysqli_num_rows($result)>0){
		//'Ya existe la persona';
		$sql = "UPDATE PersonaDatos SET  Dat_Dom_Pai_ID = $DomPaiID, Dat_Dom_Pro_ID = $DomProID, Dat_Dom_Loc_ID = $DomLocID, Dat_Nac_Pai_ID = $NacPaiID, Dat_Nac_Pro_ID = $NacProID, Dat_Nac_Loc_ID = $NacLocID, Dat_Nacimiento = '$FechaNac', Dat_Domicilio = '$Domicilio', Dat_CP = '$CP', Dat_Email = '$Email', Dat_Telefono = '$Telefono', Dat_Celular = '$Celular', Dat_Ocupacion = '$Ocupacion', Dat_Observaciones = '$Observ', Dat_Fecha = '$Fecha', Dat_Hora = '$Hora' WHERE Dat_Per_ID = $PerID";
	        //echo $sql;
	        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        
        
	echo "Los datos han sido actualizados correctamente.";
	}else{
     // 'No existe la persona ';
	$sql_persona_datos = "INSERT INTO PersonaDatos(Dat_Per_ID, Dat_Dom_Pro_ID, Dat_Dom_Pai_ID, Dat_Dom_Loc_ID, Dat_Nac_Pro_ID, Dat_Nac_Pai_ID, Dat_Nac_Loc_ID, Dat_Nacimiento, Dat_Domicilio, Dat_CP, Dat_Email, Dat_Telefono, Dat_Celular, Dat_Ocupacion, Dat_Observaciones, Dat_Fecha, Dat_Hora) VALUES ($PerID, $DomProID, $DomPaiID, $DomLocID, $NacProID, $NacPaiID, $NacLocID, '$FechaNac', '$Domicilio', '$CP', '$Email', '$Telefono', '$Celular', '$Ocupacion',  '$Observ', '$Fecha', '$Hora')";
	//echo $sql;
	consulta_mysql_2022($sql_persona_datos,basename(__FILE__),__LINE__);
	
	// Inserta en Legajo	
	
	$sql_Legajo = "INSERT INTO Legajo (Leg_Usu_ID, Leg_Sed_ID, Leg_Per_ID, Leg_Numero, Leg_Alta_Fecha, Leg_Baja, Leg_Baja_Fecha, Leg_Baja_Motivo, Leg_Colegio, Leg_Fecha, Leg_Hora)
            VALUES ($UsuID, '1' , $PerID, '$Ins_Legajo', $Fecha, $Fecha, $Fecha, 'varios', '1' ,'$Fecha', '$Hora')";
        consulta_mysql_2022($sql_Legajo,basename(__FILE__),__LINE__);
		
	// Consulta ID de ultimo Legajo para insertar en Colegio_Inscripcion
	
		$sql_Leg_ID = "SELECT LAST_INSERT_ID() FROM Legajo";
		$resultDatos = consulta_mysql_2022($sql_Leg_ID,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($resultDatos);
		$legajo_id = $row[0];
	
	// Inserta en Colegio_Inscripcion
	
	$sql_inscripcion = "INSERT INTO Colegio_Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Provisoria, Ins_Fecha, Ins_Hora) 
	VALUES ($legajo_id, '12', $Ins_Curso, $Ins_Nivel, '0', $Asp_Usu_ID, '0' ,'$Fecha', '$Hora')";
    consulta_mysql_2022($sql_inscripcion,basename(__FILE__),__LINE__);
	echo "Los datos han sido insertados correctamente.";
}//fin del esle

}

?>
