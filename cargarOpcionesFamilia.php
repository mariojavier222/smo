<?php
header("Cache-Control: no-cache, must-revalidate");
//error_reporting(E_ALL); ini_set('display_errors', 1);

include_once("comprobar_sesion.php");
require_once("cargarFuncionesFamilia.php");	
$opcion = '';
$opcion = $_POST['opcion'];
switch ($opcion) {

    case "armarFamilia":
        armarFamilia();
        break; 

    case "eliminarFamilia":
        eliminarFamilia();
        break; 

    case "eliminarVinculo":
        eliminarVinculo();
        break; 

    case "guardarFamilia":
        $PerID=$_POST['PerID'];
        $PerID_Vinc=$_POST['PerID_Vinc'];
        $DNI=$_POST['DNI'];
        $DNI_Vinc=$_POST['DNI_Vinc'];
        $FTiID=$_POST['FTiID'];
        $UsuID=$_POST['UsuID'];
        guardarFamilia($PerID, $PerID_Vinc, $DNI, $DNI_Vinc, $FTiID, $UsuID);
        break;

	default:
        //echo "La opción elegida no es válida";
}//fin switch
 
?>