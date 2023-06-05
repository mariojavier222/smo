<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFuncionesInscripcion.php");

//error_reporting(E_ALL); ini_set('display_errors', 1);

session_name("sesion_abierta");
// incia sessiones
session_start();	

$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch ($opcion) {

    case "guardarInscripcionLectivo":
        guardarInscripcionLectivo();
        break;
    case "borrarInscripcionLectivo":
        borrarInscripcionLectivo();
        break;
    case "datosRegistroInscripcion":
        datosRegistroInscripcion();
        break;
        
	default:
//        echo "La opción elegida no es válida";
}//fin switch

?> 