<?php

//error_reporting(E_ERROR | E_PARSE); ini_set('display_errors', 1);

include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("class.letras.php");

 
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

if (!empty($_GET['FacturaNumero'])){
	$Fac_Numero = $_GET['FacturaNumero'];
	//echo $Fac_Numero."<br />";
	list($Sucursal, $Numero) = explode("-",$Fac_Numero);
	//echo $Numero;
	$sql = "SELECT * FROM Factura INNER JOIN Usuario ON (Fac_Usu_ID = Usu_ID) WHERE Fac_Numero ='$Numero' AND Fac_Sucursal = '$Sucursal'";	
}else {
	if (!empty($_GET['Fac'])){
		$Fac = $_GET['Fac'];
		//echo $Numero;
		$sql = "SELECT * FROM Factura INNER JOIN Usuario ON (Fac_Usu_ID = Usu_ID) WHERE Fac_ID = '$Fac'";	
	}
}
//echo $sql;

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$FacID=$row['Fac_ID'];
$Usuario=$row['Usu_Persona'];
$FormaPago = "Forma de Pago: ".utf8_decode(buscarFormaPagoFactura($FacID));
$FormaPagoDetalle = utf8_decode(buscarFormaPagoFacturaDetalle($FacID));

if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

//error_reporting(E_ALL);
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

	$yArriba = 810;
	//Texto arriba

	$ruta_images = "logos/logo_smo.jpg";	
	$pdf->addJpegFromFile($ruta_images,40,$yArriba - 60,60,0);	
	
	$pdf->addText(110,$yArriba,10,"<b>COLEGIO SANTISIMO SACRAMENTO</b>");
	$pdf->addText(110,$yArriba - 20,9,"Juan Pablo D'Marco 5756 (oeste) Bo. Camus ");
	$pdf->addText(110,$yArriba - 35,9,"Rivadavia - San Juan - C.P. 5407");
	$pdf->addText(110,$yArriba - 50,9,"Tel. (0264) 4331198");
	$pdf->addText(110,$yArriba - 65,9,"www.santisimosacramento.edu.ar");

	$pdf->addText(370,$yArriba,11,"<b>RECIBO DE COBRANZAS</b>");
	$pdf->addText(370,$yArriba - 20,11,"<b>Recibo N°:</b> C".$row['Fac_Sucursal']."-".$row['Fac_Numero']);
	$pdf->addText(370,$yArriba - 35,8,"C.U.I.T.: 30-70705151-1");
	$pdf->addText(370,$yArriba - 45,8,"ING. BRUTOS: 00-071159-0  EXENTO");
	$pdf->addText(370,$yArriba - 55,8,"Inicio de actividades: 01/03/2000");
	$pdf->addText(370,$yArriba - 65,8,"IVA EXENTO");

	$pdf->setLineStyle(1,'','',array(1,0));	
	//$pdf->line(315,$yArriba - 15,315,$yArriba - 90);
	$pdf->rectangle(310,$yArriba - 15,30,30);
	$pdf->addText(318,$yArriba - 5,18,"C");
	
	$pdf->ezSetY(790);
	
	$pdf->addText(370,$yArriba - 97,11,"<b>Fecha de pago:</b> ".cfecha($row['Fac_Fecha']));

	$pdf->ezSetMargins(30,30,50,50);
	$pdf->addText(40,$yArriba - 112,10,"Alumno/a: <b>".utf8_decode($row['Fac_PersonaNombre'])."</b>");

	$fontSize = 10;

	$pdf->rectangle(30,$yArriba - 125,520,45);//rectangle(x1,y1,width,height)
	$pdf->rectangle(30,$yArriba - 345,520,220);
	$pdf->rectangle(30,$yArriba - 345,520,35);
	
	//linea de puntos para cortar
	$pdf->setLineStyle(5,'round','',array(0,15));
	$pdf->line(5,$yArriba - 370,600,$yArriba - 370);//line(x1,y1,x2,y2)
		
	//Texto abajo
	$yAbajo = 410;
	
	$sql_1 = "SELECT * FROM FacturaDetalle WHERE FDe_Fac_ID =$FacID";
    $resultado = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($resultado) > 0) {
        $y = $yArriba - 140;
		$yy = $yAbajo - 155;//190;
		while ($row1 = mysqli_fetch_array($resultado)) {
			//Detalle
			$pdf->addText(40,$y,10,utf8_decode($row1['FDe_Detalle']));
			//$pdf->addText(40,$yy,12,$row1['FDe_Detalle']);
			
			//Importe
			$pdf->addText(450,$y,10,"$".number_format($row1['FDe_Importe'],2,',','.'));
			//$pdf->addText(450,$yy,12,"$".$row1['FDe_Importe']);
			
			$y = $y -10;
			//$yy = $yy -20;
			
		}//fin while
		
		//Forma de Pago
		if (!empty($FormaPagoDetalle)){
			//$FormaPago .= " ($FormaPagoDetalle)";
		}
		$pdf->addText(40,$yArriba - 300,$fontSize,$FormaPago);
		//$pdf->addText(40,$yAbajo - 300,$fontSize,$FormaPago);

		if ($row['Fac_Anulada']==1){
			$ruta_images = "imagenes/FACTURA-ANULADA.jpg";
			//Izquierda
			$pdf->addJpegFromFile($ruta_images,150,$yArriba - 133,100);
			//$pdf->addJpegFromFile($ruta_images,150,$yAbajo - 133,100);
			$pdf->addJpegFromFile($ruta_images,200,$yArriba - 233,100);
			//$pdf->addJpegFromFile($ruta_images,200,$yAbajo - 233,100);
			$pdf->addJpegFromFile($ruta_images,150,$yArriba - 333,100);
			//$pdf->addJpegFromFile($ruta_images,150,$yAbajo - 333,100);
			//Derecha
			$pdf->addJpegFromFile($ruta_images,450,$yArriba - 133,100);
			//$pdf->addJpegFromFile($ruta_images,450,$yAbajo - 133,100);
			$pdf->addJpegFromFile($ruta_images,500,$yArriba - 233,100);
			//$pdf->addJpegFromFile($ruta_images,500,$yAbajo - 233,100);
			$pdf->addJpegFromFile($ruta_images,450,$yArriba - 333,100);
			//$pdf->addJpegFromFile($ruta_images,450,$yAbajo - 333,100);
		}//fin if
		if ($row['Fac_Pagada']==1){
			$ruta_images = "imagenes/FACTURA-PAGADA.jpg";
			$pdf->addJpegFromFile($ruta_images,300,$yArriba - 300,100);
			//$pdf->addJpegFromFile($ruta_images,300,$yAbajo - 300,100);
			//$pdf->addPngFromFile($ruta_images,0,0,100);
		}//fin if
		
	}//fin if


	$letrasTotal = new EnLetras();
	$valorLetras = $letrasTotal->ValorEnLetras($row['Fac_ImporteTotal'], "");
	$pdf->addText(40,$yArriba - 330,$fontSize,$valorLetras);

	//Usuario que cobró
	$fontSize = 8;
	$pdf->addText(40,$yArriba - 355,$fontSize,"Atendido por ".$Usuario." - ".date("d-m-Y H:i:s"));
	//$pdf->addText(40,$yAbajo - 315,$fontSize,"Atendido por $Usuario");

	//Importe total
	$pdf->addText(380,$yArriba - 333,12,"<b>TOTAL Recibo:</b> $".number_format($row['Fac_ImporteTotal'],2,',','.'));
	//$pdf->addText(410,$yAbajo - 333,12,"TOTAL: $".$row['Fac_ImporteTotal']);
	
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