<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 $sql = "SET NAMES UTF8;";
 $DRe_ID = "";
if (isset($_GET['DRe_ID'])){
	$sql = "SELECT * FROM
    DeudorRecibo
    INNER JOIN Deudor 
        ON (DRe_Deu_ID = Deu_ID)
    INNER JOIN Usuario 
        ON (DRe_Usu_ID = Usu_ID) WHERE DRe_ID = ".$_GET['DRe_ID'].";";
       
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {
			
			$row = mysqli_fetch_array($result);
			$DRe_ID = $row['DRe_ID'];
			$textoDetalle=$row['DRe_Detalle'];
			$Proveedor=$row['Deu_Nombre'];
			$CUIT = $row['Deu_CUIT'];
			$razonSocial=$row['Deu_RazonSocial'];
			$Fecha=cfecha($row['DRe_Fecha']);
			$FechaRecibo=cfecha($row['DRe_FechaRecibo']);
			$DRe_Importe=$row['DRe_Importe'];
			//$tipoRecibo=$row['DRe_TipoRecibo'];

			$Confecciono=$row['Usu_Persona'];

			$formaPago=$row['DRe_FormaPago'];
			$numeroRecibo=$row['DRe_ReciboNumero'];
			$Factura = $numeroRecibo;
			$tituloRecibo="ORDEN DE INGRESO";

			//$DRe_Autoriza=$row[DRe_Autoriza];
			$DRe_ChequeNumero=$row[DRe_ChequeNumero];
			$DRe_ChequeBanco=$row[DRe_ChequeBanco];
			$DRe_ChequeFecha=cfecha($row[DRe_ChequeFecha]);


			$imprimir=true;
			//$Cuenta = $row['Cue_Nombre'];
		}
}else{
	$imprimir=false;

}

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
	$pdf->ezSetY(800);$yArriba = 810;
	$pdf->setLineStyle(1,'','',array(1,0));

	$ruta_images = "logos/".COLEGIO_LOGO;
	$pdf->addJpegFromFile($ruta_images,50,$yArriba - 60,80,0);
	$pdf->addText(150,$yArriba - 20,10,COLEGIO_DOMICILIO);
	$pdf->addText(150,$yArriba - 30,10,COLEGIO_TELEFONO);

	$j = $pdf->ezStartPageNumbers(50,20,8,'right',utf8_decode("<i>Confeccionó $Confecciono</i>"),1);

	
	$fontSize = 14;
	$pdf->ezSetY(830);
	$pdf->ezText(utf8_decode("\n\nORDEN DE INGRESO\n<b>Nº ").substr("00000000".$DRe_ID,-8)."</b>", $fontSize, array('justification'=>'right'));	
	
	
	$pdf->rectangle(25,$yArriba - 70,550,90);//rectangle(x1,y1,width,height)
	$yArriba = 710;
	$pdf->rectangle(25,$yArriba - 100,550,120);
	
	
	//$pdf->ezColumnsStart();
	$pdf->ezSetY(720);
	$fontSize = 12;
	$pdf->ezText("<b><u>INGRESO DE DINERO</u></b>", $fontSize, array('justification'=>'center'));
	$pdf->ezText("\nFecha Pago: <b>".$FechaRecibo."</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("Deudor:<b> ".$Proveedor."</b>", $fontSize, array('justification'=>'left'));
	$fontSize = 10;
	$pdf->ezText("C.U.I.T.:<b> ".$CUIT."</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText(utf8_decode("Razón Social:<b> ").$razonSocial."</b>", $fontSize, array('justification'=>'left'));
	

	$fontSize = 10;
	$pdf->ezSetY(600);
	
	$listado = array('Factura'=>$Factura, 'Detalle'=>$textoDetalle, 'Total'=>$DRe_Importe);				
	$data[] = $listado;

	$pdf->ezTable($data,array('Factura'=>'<b>Comprobante</b>', 'Detalle'=>'<b>Detalle del Ingreso recibido</b>', 'Total'=>'<b>Total a recibir</b>'),"", array('gridlines'=>1,'innerLineThickness'=>1,'outerLineThickness'=>2, 'showHeadings'=>1, 'fontSize' => 10, 'showLines'=>2, 'titleFontSize'=>12, 'rowGap'=>3, 'colGap'=>3, 'shaded' => 1, 'cols'=>array('Factura'=>array('width'=>150,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center'),  'Detalle'=>array('width'=>300,'justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'),'Total'=>array('width'=>100,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	$data = "";
	$pdf->ezText("\n\n", $fontSize, array('justification'=>'left'));
	if ($formaPago=="Cheque"){
		
		$listado = array('DRe_ChequeBanco'=>$DRe_ChequeBanco, 'DRe_ChequeFecha'=>$DRe_ChequeFecha, 'DRe_ChequeNumero'=>$DRe_ChequeNumero, 'DRe_Importe'=>$DRe_Importe);				
		$data[] = $listado;

		$pdf->ezTable($data,array('DRe_ChequeBanco'=>'<b>Banco</b>', 'DRe_ChequeFecha'=>'<b>Fecha</b>', 'DRe_ChequeNumero'=>utf8_decode('<b>Número</b>'), 'DRe_Importe'=>'<b>Importe</b>'),"Forma de Pago: $formaPago", array('gridlines'=>1,'innerLineThickness'=>1,'outerLineThickness'=>2, 'showHeadings'=>1, 'fontSize' => 10, 'showLines'=>2, 'titleFontSize'=>12, 'rowGap'=>3, 'colGap'=>3, 'shaded' => 1, 'cols'=>array('DRe_ChequeBanco'=>array('width'=>150,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center'),  'DRe_ChequeFecha'=>array('width'=>100,'justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'),'DRe_ChequeNumero'=>array('width'=>100,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	}else{
		$listado = array('formaPago'=>$formaPago, 'DRe_Importe'=>$DRe_Importe);				
		$data[] = $listado;

		$pdf->ezTable($data,array('formaPago'=>'<b>Forma de Pago</b>', 'DRe_Importe'=>'<b>Importe</b>'),"", array('gridlines'=>1,'innerLineThickness'=>1,'outerLineThickness'=>2, 'showHeadings'=>1, 'fontSize' => 10, 'showLines'=>2, 'titleFontSize'=>12, 'rowGap'=>3, 'colGap'=>3, 'shaded' => 1, 'cols'=>array('formaPago'=>array('width'=>100,'justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'),'DRe_Importe'=>array('width'=>100,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	}
	


		
	$texto = utf8_decode("\n\n\n\n\n\n----------------------------------                        ----------------------------------------\nAclaración                                               Firma del Responsable");	
	$pdf->ezText($texto, $fontSize, array('justification'=>'right'));
	$pdf->ezText(utf8_decode("\n\nRecibí de conformidad aceptando la presente liquidación de pago"), $fontSize, array('justification'=>'right'));
	

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL RECIBO</b>");
	//$pdf->addText(50,750,9,"Motivo: El número de Recibo $Sucursal-$Numero no es válido");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>