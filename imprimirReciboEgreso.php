<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 $sql = "SET NAMES UTF8;";
 $Rec_ID = "";
if (isset($_GET['Rec_ID'])){
	$sql = "SELECT * FROM
    Egreso_Recibo
    INNER JOIN Egreso_Cuenta 
        ON (Rec_Cue_ID = Cue_ID)
    INNER JOIN Usuario 
        ON (Rec_Usu_ID = Usu_ID)
    INNER JOIN Egreso_Tipo 
        ON (Rec_ETi_ID = ETi_ID) WHERE Rec_ID = ".$_GET['Rec_ID'].";";
       
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {
			
			$row = mysqli_fetch_array($result);
			$Rec_ID = $row['Rec_ID'];
			$textoDetalle=$row['Rec_Detalle'];
			$Proveedor=$row['Cue_Nombre'];
			$CUIT = $row['Cue_CUIT'];
			$razonSocial=$row['Cue_RazonSocial'];
			$Fecha=cfecha($row['Rec_Fecha']);
			$FechaCompra=cfecha($row['Rec_FechaCompra']);
			$Rec_Importe=$row['Rec_Importe'];
			$tipoRecibo=$row['Rec_TipoRecibo'];

			$Confecciono=$row['Usu_Persona'];

			$formaPago=$row['Rec_FormaPago'];
			$numeroRecibo=$row['Rec_Numero'];
			$Factura = $tipoRecibo." ".$numeroRecibo;
			$tituloRecibo="ORDEN DE PAGO";

			$Rec_Autoriza=$row[Rec_Autoriza];
			$Rec_ChequeNumero=$row[Rec_ChequeNumero];
			$Rec_ChequeBanco=$row[Rec_ChequeBanco];
			$Rec_ChequeFecha=cfecha($row[Rec_ChequeFecha]);


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
	$pdf->ezText(utf8_decode("\n\nORDEN DE PAGO\n<b>Nº ").substr("00000000".$Rec_ID,-8)."</b>", $fontSize, array('justification'=>'right'));	
	
	
	$pdf->rectangle(25,$yArriba - 70,550,90);//rectangle(x1,y1,width,height)
	$yArriba = 710;
	$pdf->rectangle(25,$yArriba - 100,550,120);
	
	
	//$pdf->ezColumnsStart();
	$pdf->ezSetY(720);
	$fontSize = 12;
	$pdf->ezText("<b><u>PAGO A PROVEEDORES</u></b>", $fontSize, array('justification'=>'center'));
	$pdf->ezText("\nFecha Pago: <b>".$FechaCompra."</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("Proveedor:<b> ".$Proveedor."</b>", $fontSize, array('justification'=>'left'));
	$fontSize = 10;
	$pdf->ezText("C.U.I.T.:<b> ".$CUIT."</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText(utf8_decode("Razón Social:<b> ").$razonSocial."</b>", $fontSize, array('justification'=>'left'));
	

	$fontSize = 10;
	$pdf->ezSetY(600);
	
	$listado = array('Factura'=>$Factura, 'Detalle'=>$textoDetalle, 'Total'=>$Rec_Importe);				
	$data[] = $listado;

	$pdf->ezTable($data,array('Factura'=>'<b>Comprobante</b>', 'Detalle'=>'<b>Detalle del Pago</b>', 'Total'=>'<b>Total a pagar</b>'),"", array('gridlines'=>1,'innerLineThickness'=>1,'outerLineThickness'=>2, 'showHeadings'=>1, 'fontSize' => 10, 'showLines'=>2, 'titleFontSize'=>12, 'rowGap'=>3, 'colGap'=>3, 'shaded' => 1, 'cols'=>array('Factura'=>array('width'=>150,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center'),  'Detalle'=>array('width'=>300,'justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'),'Total'=>array('width'=>100,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	$data = "";

	//Si tiene items asociados, los mostramos acá
	$sql = "SELECT * FROM Egreso_ReciboItem WHERE RIt_Rec_ID = $Rec_ID";
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result2) > 0) {
		$item=0;
		while ($row2 = mysqli_fetch_array($result2)){
			$item++;
			$listado = array('item'=>$item, 'Detalle'=>$row2['RIt_Detalle'], 'Total'=>$row2['RIt_Importe']);				
			$data[] = $listado;
		}//fin while
		$pdf->ezTable($data,array('item'=>'<b>Item</b>', 'Detalle'=>'<b>Detalle</b>', 'Total'=>'<b>Importe</b>'),"Items asociados al Pago", array('gridlines'=>1,'innerLineThickness'=>1,'outerLineThickness'=>2, 'showHeadings'=>1, 'fontSize' => 10, 'showLines'=>2, 'titleFontSize'=>12, 'rowGap'=>3, 'colGap'=>3, 'shaded' => 1, 'cols'=>array('item'=>array('width'=>50,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center'),  'Detalle'=>array('width'=>400,'justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'),'Total'=>array('width'=>100,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	$data = "";
		
	}

	$pdf->ezText("\n\n", $fontSize, array('justification'=>'left'));
	if ($formaPago=="Cheque"){
		
		$listado = array('Rec_ChequeBanco'=>$Rec_ChequeBanco, 'Rec_ChequeFecha'=>$Rec_ChequeFecha, 'Rec_ChequeNumero'=>$Rec_ChequeNumero, 'Rec_Importe'=>$Rec_Importe);				
		$data[] = $listado;

		$pdf->ezTable($data,array('Rec_ChequeBanco'=>'<b>Banco</b>', 'Rec_ChequeFecha'=>'<b>Fecha</b>', 'Rec_ChequeNumero'=>utf8_decode('<b>Número</b>'), 'Rec_Importe'=>'<b>Importe</b>'),"Forma de Pago: $formaPago", array('gridlines'=>1,'innerLineThickness'=>1,'outerLineThickness'=>2, 'showHeadings'=>1, 'fontSize' => 10, 'showLines'=>2, 'titleFontSize'=>12, 'rowGap'=>3, 'colGap'=>3, 'shaded' => 1, 'cols'=>array('Rec_ChequeBanco'=>array('width'=>150,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center'),  'Rec_ChequeFecha'=>array('width'=>100,'justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'),'Rec_ChequeNumero'=>array('width'=>100,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	}else{
		$listado = array('formaPago'=>$formaPago, 'Rec_Importe'=>$Rec_Importe);				
		$data[] = $listado;

		$pdf->ezTable($data,array('formaPago'=>'<b>Forma de Pago</b>', 'Rec_Importe'=>'<b>Importe</b>'),"", array('gridlines'=>1,'innerLineThickness'=>1,'outerLineThickness'=>2, 'showHeadings'=>1, 'fontSize' => 10, 'showLines'=>2, 'titleFontSize'=>12, 'rowGap'=>3, 'colGap'=>3, 'shaded' => 1, 'cols'=>array('formaPago'=>array('width'=>100,'justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'),'Rec_Importe'=>array('width'=>100,'justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));
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