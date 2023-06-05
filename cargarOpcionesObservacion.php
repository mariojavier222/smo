<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();	
 
$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch ($opcion) {

	case "observarPersona";
        observarPersona();
        break;
    case "quitarObservaPersona";
        quitarObservaPersona();
        break;
    case "obtenerObserva";
        obtenerObserva();
        break;
        
	default:
//        echo "La opción elegida no es válida";
}//fin switch

function observarPersona(){
    $PerID = $_POST['PerID'];
    $Observ = $_POST['Observ'];
    $sql = "UPDATE Persona SET Per_Observada = 1, Per_Observaciones='$Observ' where Per_ID = '$PerID';";
    if (consulta_mysql_2022($sql,basename(__FILE__),__LINE__)){
        echo "Se cargaron las observaciones a esta persona.";
    }
}//fin function

function quitarObservaPersona(){
    $PerID = $_POST['PerID'];
    $sql = "UPDATE Persona SET Per_Observada = 0, Per_Observaciones='' where Per_ID = '$PerID';";
    if (consulta_mysql_2022($sql,basename(__FILE__),__LINE__)){
        echo "Se eliminaron las observaciones a esta persona.";
    }
}//fin function

function obtenerObserva(){
    $PerID = trim($_POST['PerID']);
    if (empty($PerID)) exit;
    $sql = "SELECT Per_Observada,Per_Observaciones FROM Persona WHERE Per_ID = '$PerID' and Per_Observada=1;";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        echo $row['Per_Observaciones'];
    }else{
        echo '';
    }
}//fin function
?>