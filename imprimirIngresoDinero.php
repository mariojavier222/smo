<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 $sql = "SET NAMES UTF8;";
 //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$SCC_ID = $_GET['id'];
$sql = "SELECT * FROM SuperCajaCorriente INNER JOIN Usuario ON SCC_Usu_ID = Usu_ID INNER JOIN CajaCorriente 
        ON (SCC_CCC_ID = CCC_ID)
    INNER JOIN FormaPago 
        ON (CCC_For_ID = For_ID) WHERE SCC_ID ='$SCC_ID'";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$SCC_Caja_ID=$row[SCC_Caja_ID];
$SCC_CCC_ID=$row[SCC_CCC_ID];
$SCC_SCa_ID=$row[SCC_SCa_ID];
$SCC_Concepto=$row[SCC_Concepto];
$SCC_Debito=$row[SCC_Debito];
$SCC_Credito=$row[SCC_Credito];
$SCC_Saldo=$row[SCC_Saldo];
$SCC_FechaHora=$row[SCC_FechaHora];
$Fecha = cfecha(substr($SCC_FechaHora,0,10));
$Hora = substr($SCC_FechaHora,11,10);
$SCC_Detalle=$row[SCC_Detalle];
$SCC_Usu_ID=$row[SCC_Usu_ID];
$Usuario=$row[Usu_Persona];
$FormaPago=$row[For_Nombre];

if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

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
//$imprimir = true;
//si viene el DNI imprimo
if ($imprimir){  

$total = $i;


	
	//Imprimimos el encabezado
	$pdf->ezSetY(800);
	$pdf->setLineStyle(1,'','',array(1,0));
	
	//Texto 
	//$pdf->addText(120,780,8,"<b>UNIVERSIDAD CATÓLICA DE CUYO</b>");
	
	
	$yArriba = 650;
	//Texto arriba
	
	global $gMes;
	$ruta_images = "logos/logo_gateway.jpg";
	//$pdf->addJpegFromFile($ruta_images,280,$yArriba,60,0);
	//$pdf->addJpegFromFile($ruta_images,160,790,60,0);
	$pdf->ezSetY(800);
	$fontSize = 14;
	//$pdf->ezText("\n\n<b>RECIBO Nº $Rec_ID</b> (original)", $fontSize, array('justification'=>'center'));	
	$pdf->ezSetY(800);
	$pdf->ezText("<b>COMPROBANTE DE INGRESO DE DINERO A LA SUPER CAJA</b>", $fontSize, array('justification'=>'center'));
	$fontSize = 12;
	$pdf->ezText(utf8_decode("\nEn San Juan, <b>$Fecha</b> siendo las <b>$Hora</b>, se ha recibido la suma de <b>$".$SCC_Credito."</b> en <b>$FormaPago</b> en concepto de <b>$SCC_Concepto</b>. $SCC_Detalle\n"), $fontSize, array('justification'=>'full'));		
	$fontSize = 10;
	$pdf->rectangle(25,$yArriba,550,150);
	$pdf->ezText("\n\n\n----------------------------------\n<b>Firma conforme</b>   ", $fontSize, array('justification'=>'right'));	
		//$pdf->ezSetY(600);
		$fontSize = 12;
		
	

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL RECIBO DE PAGO</b>");
	$pdf->addText(50,750,9,"Motivo: El número de Recibo $Sucursal-$Numero no es válido");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>