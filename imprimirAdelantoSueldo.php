<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 $sql = "SET NAMES UTF8;";

$textoDetalle=$_GET['a'];
$Detalle=$_GET['b'];
$Fecha=$_GET['c'];
$Rec_Importe=$_GET['d'];

if (!empty($textoDetalle) && !empty($Detalle) && !empty($Fecha) && !empty($Rec_Importe)) $imprimir=true;else $imprimir=false;
 
error_reporting(E_ALL);
include ('clase_pdf/class.pdf.php');
include ('clase_pdf/class.ezpdf.php');
include ('clase_pdf/class.pdfbarcode.php');



$pdf = new Cezpdf('A4');
$pdf->selectFont('clase_pdf/fonts/Helvetica.afm');
$barcode_options = array (
    'scale'     => 1,
    'fontscale' => 0,
    'font'      => 'clase_pdf/fonts/Helvetica.afm',
    'rotation'  => 0
);

//$barcode = new PDFBarcode($pdf, $barcode_options);


$i = 1;
//$imprimir = false;
//si viene el DNI imprimo
if ($imprimir){  

$total = $i;


	
	//Imprimimos el encabezado
	$pdf->ezSetY(800);
	$pdf->setLineStyle(1,'','',array(1,0));
	

	$fontSize = 14;
	$pdf->ezText("<b>ADELANTO DE SUELDO</b>", $fontSize, array('justification'=>'center'));
	$fontSize = 12;
	$pdf->ezText(utf8_decode("\nEn San Juan, <b>$Fecha</b>, he recibido un adelanto de sueldo correspondiente al Mes <b>$textoDetalle</b> por el importe de <b>$".$Rec_Importe."</b> en <b>efectivo</b>.\n"), $fontSize, array('justification'=>'full'));		
	$fontSize = 10;
	$yArriba = 650;
	$pdf->rectangle(25,$yArriba,550,150);
	$pdf->ezText("\n\n\n----------------------------------\n<b>$Detalle</b>   ", $fontSize, array('justification'=>'right'));	
		//$pdf->ezSetY(600);
		$fontSize = 12;
		
	

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL COMPROBANTE DE ADELANTO DE SUELDO</b> \n Es posible que algunos de los campos se encuentre vacíos");
	//$pdf->addText(50,750,9,"Motivo: El número de Recibo $Sucursal-$Numero no es válido");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>