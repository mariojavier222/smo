<?php
header("Cache-Control: no-cache, must-revalidate");
//error_reporting(E_ALL); ini_set('display_errors', 1);

include_once("comprobar_sesion.php");
require_once("cargarFuncionesDivision.php");	
$opcion = '';
$opcion = $_POST['opcion'];
switch ($opcion) {

    case "guardarDivisionCurso":
        guardarDivisionCurso();
        break; 
    case "eliminarDivisionCurso":
        eliminarDivisionCurso();
        break; 
    case "llenarCursoTurnoST":
        llenarCursoTurnoST();
        break; 
    case "llenarListaCursoDivision":
        llenarListaCursoDivision();
        break; 
    case "cargarListaDivisionCurso":
        cargarListaDivisionCurso();
        break;    
    case "llenarListaCursosColegioTraerNivel":
        llenarListaCursosColegioTraerNivel();
        break; 

	default:
        //echo "La opción elegida no es válida";
}//fin switch
 
?>