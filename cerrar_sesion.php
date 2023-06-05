<?php
//require_once("globales.php");
require_once("conexion.php");

// -------- Chequear sesión existe -------
// usamos la sesion de nombre definido.
session_name("sesion_abierta");
// Iniciamos el uso de sesiones
session_start();


$fecha = date("Y-m-d");
$hora = date("H:i:s");

 if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
{
  $ip=$_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
{
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
  $ip=$_SERVER['REMOTE_ADDR'];
}
$agent = $_SERVER['HTTP_USER_AGENT'];
$usuario = $_SESSION['sesion_UsuID'];

if (!empty($usuario)){
  //cierro la sesión en la tabla login
  $sql = "INSERT INTO Login (Log_Usu_ID, Log_Fecha, Log_Hora, Log_IP, Log_Agent, Log_Status) VALUES 
  ('$usuario', '$fecha', '$hora', '$ip', '$agent', 0)";
  consulta_mysql_2022($sql,basename(__FILE__),5);
}

// Destruye todas las variables de la sesi&oacute;n
session_unset();
// Destruye todas las variables de la sesi&oacute;n
$_SESSION = array();

// Borramos la sesion creada por el inicio de session anterior
session_destroy();

header("Location:index.php");

exit;
//echo "Se ha cerrado correctamente la sesión.";
?>
