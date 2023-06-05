<?php
//Datos de conexión a la base de datos
//error_reporting(E_ALL); ini_set('display_errors', 1);

include_once("globales.php");
include_once("globalesErrores.php");

function set_sesion(){
	$session_name = 'sesion_abierta';   // Set a custom session name 
    $secure = false;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=No se puede iniciar una sesión segura (ini_set)");
        exit();
    }


    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    //***********MODIFICADO FABRICIO*********************$cookieParams["domain"], $secure, $httponly);
	session_set_cookie_params(14400, $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
	//***********FIN MODIFICACION************************
	
    // Sets the session name to the one set above.
    session_name("sesion_abierta");

    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
	//echo session_name();
}

date_default_timezone_set ('America/Argentina/San_Juan');

//mario. 27/09/2022
//para guardar el error en los inserts o updates en una tabla y luego enviar un email
function guardarLogTable($error_nro,$error_detalle,$cadena,$archivo,$linea){
	$user_id='';
	if (empty($_SESSION['sesion_UsuID'])) $user_id='';
	else $user_id=$_SESSION['sesion_UsuID'];

	$colegio = COLEGIO_ABREVIATURA; 
	$sistema = COLEGIO_SISTEMA;
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$sql= "INSERT INTO error_sql (colegio, sistema, error_nro, error_detalle, cadena, archivo, linea, user_id, fecha, hora) VALUES (
	'$colegio',
	'$sistema',
    '$error_nro',
    '$error_detalle',
    '$cadena',
    '$archivo',
    '$linea',
    '$user_id',
    '$fecha',
    '$hora');";
//	echo $sql;
	consulta_error($sql);
}

function consulta_error($sql){
	global $gSQL_hostE, $gSQL_usuarioE, $gSQL_passE, $gSQL_dbE;
	date_default_timezone_set('America/Argentina/San_Juan');
	$connection = mysqli_connect($gSQL_hostE,$gSQL_usuarioE,$gSQL_passE, $gSQL_dbE);
	$result = mysqli_query($connection, $sql);
   
	if (mysqli_error($connection)) {

		//guardo el error
		$error="Error: ".mysqli_error($connection);	
		$nomArchLog = "log-err-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		guardarLog($error,$cadena,$nomArchLog);	
		
	}else {
		//echo "sql ok: ".$sql;//ok
	}
	
	return $result;
}


//mario. 03/10/2021
//para guardar un logo de errores en los inserts o updates
function guardarLog($error,$cadena,$nombre){
	
	$archivo = "logs/".$nombre;
	if (file_exists($archivo)){
		$ddf = fopen($archivo,'a');	
	}else $ddf = fopen($archivo,'w+');
	fwrite($ddf,"[".date("d-m-Y H:i:s")."] ERROR: $error - SQL: $cadena".PHP_EOL);
	fclose($ddf);	

}

//mario. 03/10/2021
//para guardar un logo de errores en los inserts o updates
function guardarAud($cadena,$nombre){ 
	$archivo = "aud/".$nombre;
	if (file_exists($archivo)){
		$ddf = fopen($archivo,'a');	
	}else $ddf = fopen($archivo,'w+');
	fwrite($ddf,"[".date("d-m-Y H:i:s")."] SQL: $cadena".PHP_EOL);
	fclose($ddf);	
}

function consulta_mysql($sql){
global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;
	date_default_timezone_set('America/Argentina/San_Juan');
	$conexion= mysqli_connect($gSQL_host,$gSQL_usuario,$gSQL_pass);	
	mysqli_select_db($gSQL_db,$conexion);//echo "NO";exit;	
	
	$result = mysqli_query($conexion,$sql);
	if (mysqli_error()){
		
		echo mysqli_error();
		//echo "ERROR <br>SQL: $sql<br>";

		//Mario. 03/10/2021
		//guardo el error
		$error=mysqli_error();	
		$nomArchLog = "log-err-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		guardarLog($error,$cadena,$nomArchLog);	
				
	}else {
		//guardo el log de auditoria
		$nomArchLog = "log-aud-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		//guardarAud($cadena,$nomArchLog);	
	}
	
	return $result;
}//fin de la funcion


function consulta_saga($sql){
$gSQL_host='localhost';
$gSQL_usuario='root';
$gSQL_pass='';
$gSQL_db='CesapColegio';
	
	$conexion= mysqli_connect($gSQL_host,$gSQL_usuario,$gSQL_pass);	
	mysqli_select_db($gSQL_db,$conexion);//echo "NO";exit;	
	$result = mysqli_query($conexion,$sql);
	if (mysqli_error ())
		{echo mysqli_error ();
		echo "ERROR<br/>SQL: $sql<br/>";
		}
	return $result;
}//fin de la funcion



function cargarOpcionesMenu($ID){

	$fechaHoy = date("d/m/Y");
	$mostrar = "<ul>";
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),5);
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $ID ORDER BY Opc_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),5);
	while ($row = mysqli_fetch_array($result)){
		$nuevo = "";
		$fechaIni = cfecha($row[Opc_Fecha]);
		$resta = restarFecha($fechaIni, $fechaHoy);
		if ($resta<20) $nuevo = " <img src='imagenes/asterisk_yellow.png' width='10' height='10' alt='Opción nueva' title='Opción nueva'>";
		$mostrar.= "<li class='lista'><a href='#' id='$row[Opc_Comando]' class='menu_opciones'>$row[Opc_Nombre]</a>$nuevo</li>";
	}//fin del while
	$mostrar .= "</ul>";
	return $mostrar;
}
function cargarOpcionesMenuUsuario($ID, $usuario){

	$fechaHoy = date("d/m/Y");
	$mostrar = "<ul>";
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),5);
	$sql = "SELECT DISTINCTROW Permiso.Prm_Usu_ID, Opcion.Opc_Nombre, Opcion.Opc_ID, Opcion.Opc_Comando, Prm_Visible, Prm_Bloqueado, Opc_Fecha FROM Permiso
    INNER JOIN Opcion 
        ON (Permiso.Prm_Men_ID = Opcion.Opc_Men_ID) AND (Permiso.Prm_Opc_ID = Opcion.Opc_ID)
WHERE Permiso.Prm_Usu_ID = '$usuario' AND Opc_Men_ID = $ID";// ORDER BY Opc_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),5);
	//echo $sql;
	while ($row = mysqli_fetch_array($result)){
		if ($row[Prm_Visible]==1){
			//echo "bien";
			$nuevo = "";
			$fechaIni = cfecha($row[Opc_Fecha]);
			$resta = restarFecha($fechaIni, $fechaHoy);
			if ($resta<20) $nuevo = " <img src='imagenes/asterisk_yellow.png' width='16' height='16' alt='Opción nueva' title='Opción nueva'>";
			//if ($row[Prm_Bloqueado]==0){//original
				$mostrar.="<li class='lista'><a href='#' id='$row[Opc_Comando]' class='menu_opciones'>$row[Opc_Nombre]</a>$nuevo</li>";
				
			//}
			/*else
				$mostrar.="<li>$row[Opc_Nombre] $nuevo</li>";*/

		}//fin if

	}//fin del while
	$mostrar .= "</ul>";
	return $mostrar;
}

function arreglarCadenaMayuscula($cadena){
	$cadena = str_replace("á","Á",$cadena);
	$cadena = str_replace("é","É",$cadena);
	$cadena = str_replace("í","Í",$cadena);
	$cadena = str_replace("ó","Ó",$cadena);
	$cadena = str_replace("ú","Ú",$cadena);
	$cadena = str_replace("ñ","Ñ",$cadena);
	return $cadena;
}

function arreglarCadenaMalEscrita($cadena){
	$cadena = str_replace("Ã‘","Ñ",$cadena);
	$cadena = str_replace("Ã©","é",$cadena);
	$cadena = str_replace("Ãº","ú",$cadena);
	$cadena = str_replace("Ã","í",$cadena);
	$cadena = str_replace("°","o",$cadena);	
	$cadena = str_replace("º","o",$cadena);
	/*$cadena = str_replace("ú","Ú",$cadena);
	$cadena = str_replace("ñ","Ñ",$cadena);*/
	return $cadena;
}

//****************************
//Mario 20-04-2022
function consulta_iniciar(){
	consulta_mysql("BEGIN");
}//fin de la funcion

function consulta_retroceder(){
	consulta_mysql("ROLLBACK");
}//fin de la funcion

function consulta_terminar(){
	consulta_mysql("COMMIT");
}//fin de la funcion
//****************************


//Mario. 27/04/2022. Guardo en el log el nombre del archivo y la línea donde se produce

function consulta_mysql_2022($sql,$archivo,$linea){
	global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;
	date_default_timezone_set('America/Argentina/San_Juan');
	$connection = mysqli_connect($gSQL_host,$gSQL_usuario,$gSQL_pass, $gSQL_db);
	$result = mysqli_query($connection, "SET NAMES UTF8");
	$result = mysqli_query($connection, $sql);
   
	if (mysqli_error($connection)) {
	
		//guardo el error
		$error=$archivo." - linea: ".$linea." - error: ".mysqli_error($connection);	
		$nomArchLog = "log-err-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		guardarLog($error,$cadena,$nomArchLog);	
		
		$error= addslashes(mysqli_error ($connection));
		guardarLogTable(mysqli_errno($connection),$error,$cadena,$archivo,$linea);

	}else {
		//guardo el log de auditoria
		$nomArchLog = "log-aud-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		//guardarAud($cadena,$nomArchLog);	
	}
	
	return $result;
}//fin de la funcion

//Mario. 
function ejecutar_2022($sql,$archivo,$linea){
	global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;
	date_default_timezone_set('America/Argentina/San_Juan');
	$connection = mysqli_connect($gSQL_host,$gSQL_usuario,$gSQL_pass, $gSQL_db);
	$result = mysqli_query($connection, "SET NAMES UTF8");
	$result = mysqli_query($connection, $sql);
    $data = array();
	if (mysqli_error($connection)) {
		$data["success"] = false;
		$data["error"] = "Se produjo un error en la sentencia [$sql]: " .mysqli_error($connection)." Num:" . mysqli_errno($connection);
			
		//guardo el error
		$error=$archivo." - linea: ".$linea." - error: ".mysqli_error($connection);	
		$nomArchLog = "log-err-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		guardarLog($error,$cadena,$nomArchLog);	
		
		$error= addslashes(mysqli_error ($connection));
		guardarLogTable(mysqli_errno($connection),$error,$cadena,$archivo,$linea);
	
	}else{
    	$data["success"] = true;
    	$data["msg"] = "Consulta ejecutada correctamente";
    	$data["id"] = $last_id = mysqli_insert_id($connection);

		//Mario. 22/03/2022
		//guardo la acción
		$nomArchLog = "log-aud-".date("Ymd").".txt";
		$cadena= addslashes($sql);
    	//guardarAud($cadena,$nomArchLog);	
    }
	return $data;
}

//Mario. 27/04/2022. Guardo en el log el nombre del archivo y la línea donde se produce

function consulta_mysql_2022_test($sql,$archivo,$linea){
	global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db_test;
	date_default_timezone_set('America/Argentina/San_Juan');
	$connection = mysqli_connect($gSQL_host,$gSQL_usuario,$gSQL_pass, $gSQL_db_test);
	$result = mysqli_query($connection, "SET NAMES UTF8");
	$result = mysqli_query($connection, $sql);
   
	if (mysqli_error($connection)) {
	
		//guardo el error
		$error=$archivo." - linea: ".$linea." - error: ".mysqli_error($connection);	
		$nomArchLog = "log-err-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		guardarLog($error,$cadena,$nomArchLog);	
		
		$error= addslashes(mysqli_error ($connection));
		//guardarLogTable(mysqli_errno($connection),$error,$cadena,$archivo,$linea);

	}else {
		//guardo el log de auditoria
		$nomArchLog = "log-aud-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		//guardarAud($cadena,$nomArchLog);	
	}
	
	return $result;
}//fin de la funcion

//Mario. 
function ejecutar_2022_test($sql,$archivo,$linea){
	global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db_test;
	date_default_timezone_set('America/Argentina/San_Juan');
	$connection = mysqli_connect($gSQL_host,$gSQL_usuario,$gSQL_pass, $gSQL_db_test);
	$result = mysqli_query($connection, "SET NAMES UTF8");
	$result = mysqli_query($connection, $sql);
    $data = array();
	if (mysqli_error($connection)) {
		$data["success"] = false;
		$data["error"] = "Se produjo un error en la sentencia [$sql]: " .mysqli_error($connection)." Num:" . mysqli_errno($connection);
			
		//guardo el error
		$error=$archivo." - linea: ".$linea." - error: ".mysqli_error($connection);	
		$nomArchLog = "log-err-".date("Ymd").".txt";
		$cadena= addslashes($sql);
		guardarLog($error,$cadena,$nomArchLog);	
		
		$error= addslashes(mysqli_error ($connection));
		//guardarLogTable(mysqli_errno($connection),$error,$cadena,$archivo,$linea);
	
	}else{
    	$data["success"] = true;
    	$data["msg"] = "Consulta ejecutada correctamente";
    	$data["id"] = $last_id = mysqli_insert_id($connection);

		//Mario. 22/03/2022
		//guardo la acción
		$nomArchLog = "log-aud-".date("Ymd").".txt";
		$cadena= addslashes($sql);
    	//guardarAud($cadena,$nomArchLog);	
    }
	return $data;
}
?>