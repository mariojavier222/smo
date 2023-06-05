<?php
header("Cache-Control: no-cache, must-revalidate"); 
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch($opcion){

	case "marcarPago":
		marcarPago();
		break;
	case "anularPago":
		anularPago();
		break;	

	default: 
		echo "La opción elegida no es válida";
}//fin switch

function marcarPago(){

	$id = $_POST['id'];	
	$sql = "UPDATE informar_pagos SET marcado = 1 WHERE id = $id";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Se marcó correctamente este pago.";

}//fin funcion

function anularPago(){

	$id = $_POST['id'];	
	$sql = "UPDATE informar_pagos SET anulado = 1 WHERE id = $id";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Se anuló correctamente este pago.";

}//fin funcion



?>
