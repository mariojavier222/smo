<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");
$sesion = md5(uniqid(rand()));
 
 $sql = "SET NAMES UTF8;";
 //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$Caja_ID = $_GET['id'];
$sql = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja_Usu_ID = Usu_ID) WHERE Caja_ID = $Caja_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);

//$Caja_ID=$row[Caja_ID];
$Caja_Apertura=$row[Caja_Apertura];
$Caja_Cierre=$row[Caja_Cierre];
$Caja_Rendida=$row[Caja_Rendida];
$Caja_Anulada=$row[Caja_Anulada];
$Caja_Observaciones=$row[Caja_Observaciones];
$Caja_Usu_ID=$row[Caja_Usu_ID];
$Caja_Importe_Total=$row[Caja_Importe_Total];
$Usuario=$row['Usu_Persona'];

if (empty($Caja_Cierre)) $Caja_Cierre= "Caja sin cerrar"; else $Caja_Cierre=cfecha(substr($Caja_Cierre,0,10)).substr($Caja_Cierre,10,10);

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
	$pdf->ezSetY(810);
	$pdf->setLineStyle(1,'','',array(1,0));	
	//Title, Author, Subject, Keywords, Creator, Producer, CreationDate, ModDate, Trapped
	$datacreator = array (
                    'Title'=>"PLANILLA DE INGRESOS DE CAJA DIARIA",
                    'Subject'=>'RESUMEN DE MOVIMIENTOS DIARIOS',
                    'Author'=>'COLEGIO SANTO TOMAS DE AQUINO',
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'http://www.docmedia.com.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	$pdf->ezStartPageNumbers(50,20,8,'right',"Caja Nº $Caja_ID - <i>Página {PAGENUM} de {TOTALPAGENUM}</i>",1);
	$pdf->ezSetMargins(30,30,50,50);//ezSetMargins(top,bottom,left,right)

	
	$yArriba = 810;
	//Texto arriba
	$ruta_images = "logos/logo_sta.jpg";		
	//$pdf->addJpegFromFile($ruta_images,50,$yArriba,120,0);
	//ezImage(image,[padding],[width],[resize],[justification],[array border])
	$pdf->ezSetY($yArriba);
	$pdf->ezImage($ruta_images,30,80,'none','right');
	
	//$ruta_images = "logo_gateway.jpg";
	//$pdf->ezImage($ruta_images);
	
	$pdf->ezSetY($yArriba);
	$fontSize = 12;
	//$pdf->ezSetY(810);
	$pdf->ezText("<b><c:uline>RESUMEN DE INGRESOS DE CAJA DIARIA </c:uline>\n</b>", $fontSize, array('justification'=>'center'));
	$fontSize = 10;
	$pdf->ezText("<b>CAJA DIARIA Nº $Caja_ID</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("<c:uline>Fecha de Apertura</c:uline>: <b>".cfecha(substr($Caja_Apertura,0,10)).substr($Caja_Apertura,10,10)."</b>", $fontSize, array('justification'=>'left'));
	//$pdf->ezSetMargins(30,30,100,100);//ezSetMargins(top,bottom,left,right)
	$pdf->ezText("<c:uline>Fecha de Cierre</c:uline>: <b>$Caja_Cierre</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("<c:uline>Usuario</c:uline>: <b>$Usuario</b>", $fontSize, array('justification'=>'left'));
	
	//Armamos un arreglo con las formas de pago y los tipos de Cuotas
	$arreTipoCuota=array();
	$arreFormaPago="";
	$sql = "SELECT DISTINCTROW For_ID, For_Nombre  FROM CajaCorriente INNER JOIN FormaPago 
        ON (CCC_For_ID = For_ID) WHERE CCC_Caja_ID = $Caja_ID ORDER BY CCC_ID";
	$resultArreglo = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($resultArreglo) > 0) {
		while ($rowA = mysqli_fetch_array($resultArreglo)){
			$arreFormaPago[] = array('For_ID'=>$rowA['For_ID'], 'For_Nombre'=>$rowA['For_Nombre'], 'Ingreso'=>0, 'Egreso'=>0);
			
		}//fin while
	}
		
		$sql_1 = "SELECT * FROM FacturaDetalle
    INNER JOIN Factura 
        ON (FDe_Fac_ID = Fac_ID)
    INNER JOIN FacturaTipo 
        ON (Fac_FTi_ID = FTi_ID)  
    INNER JOIN CuotaPago 
        ON (FDe_Fac_ID = CuP_Fac_ID) AND (FDe_Item = CuP_FDe_Item)
    INNER JOIN Caja 
        ON (CuP_Caja_ID = Caja_ID)
    INNER JOIN Persona 
        ON (Per_ID = CuP_Per_ID) WHERE Caja_ID=$Caja_ID GROUP BY Fac_ID;";
        $resultado = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
        $importeTotal = 0;
        if (mysqli_num_rows($resultado) > 0) {
            
			while ($row1 = mysqli_fetch_array($resultado)) {
				
				$Fac_ID=$row1['Fac_ID'];
				$Factura = $row1['FTi_Siglas']." ".$row1['Fac_Sucursal']."-".$row1['Fac_Numero'];
				if ($row1['Fac_Anulada']==1) $Factura .= "-ANU";
				$Alumno = $row1['Per_Apellido']." ".$row1['Per_Nombre'];
				$Importe = $row1['Fac_ImporteTotal'];

				if ($row1['Fac_Anulada']==0) $importeTotal += $row1['Fac_ImporteTotal'];
			    
			    $sql_3 = "SELECT * FROM FacturaDetalle WHERE FDe_Fac_ID=$Fac_ID;";
		        $result2 = consulta_mysql_2022($sql_3,basename(__FILE__),__LINE__);
		        $DetalleFactura = "";
			    while ($row2 = mysqli_fetch_array($result2)) { 
			      if (!empty($DetalleFactura)) $DetalleFactura .="\n";
			      $DetalleFactura .= $row2['FDe_Detalle'];
			      
			    }
				$FormaPago = utf8_decode(buscarFormaPagoFactura($Fac_ID, true));
				
				$listado = array('#'=>$Fac_ID, 'RECIBO'=>$Factura, 'ALUMNO'=>$Alumno, 'IMPORTE'=>number_format($Importe,2,",","."), 'Detalle'=>$DetalleFactura, 'FormaPago'=>$FormaPago);				
				$data[] = $listado;

				//Buscamos si la factura tiene una Nota de Crédito asociada
				if ($row1['Fac_Anulada']==1){
					$sqlAnu = "SELECT * FROM FacturaDetalle
				    INNER JOIN Factura 
				        ON (FDe_Fac_ID = Fac_ID)
				    INNER JOIN FacturaTipo 
				        ON (Fac_FTi_ID = FTi_ID)  
				    WHERE Fac_ID_Ndec=$Fac_ID GROUP BY Fac_ID;";
				    $resultAnu = consulta_mysql_2022($sqlAnu,basename(__FILE__),__LINE__);
				    if (mysqli_num_rows($resultAnu)>0){
				    	$rowAnu = mysqli_fetch_array($resultAnu);
				    	$Factura = $rowAnu['FTi_Siglas']." ".$rowAnu['Fac_Sucursal']."-".$rowAnu['Fac_Numero']."-Nota Crédito";
				    	$Alumno = utf8_decode($rowAnu['Fac_PersonaNombre']);
						$Importe = $rowAnu['Fac_ImporteTotal'];
				    	$NC_ID=$rowAnu['Fac_ID'];
				    	$sql_3 = "SELECT * FROM FacturaDetalle WHERE FDe_Fac_ID=$NC_ID;";
				        $result2 = consulta_mysql_2022($sql_3,basename(__FILE__),__LINE__);
				        $DetalleFactura = "";
					    while ($row2 = mysqli_fetch_array($result2)) { 
					      if (!empty($DetalleFactura)) $DetalleFactura .="\n";
					      $DetalleFactura .= $row2['FDe_Detalle'];
					      
					    }
						$FormaPago = utf8_decode(buscarFormaPagoFactura($NC_ID, true));
						
						$listado = array('#'=>$NC_ID, 'RECIBO'=>$Factura, 'ALUMNO'=>$Alumno, 'IMPORTE'=>"-".number_format($Importe,2,",","."), 'Detalle'=>$DetalleFactura, 'FormaPago'=>$FormaPago);				
						$data[] = $listado;
				    }
				}
				
				
				
				
			}//fin while

			$listado = array('#'=>"", 'RECIBO'=>"", 'ALUMNO'=>"<b>TOTAL INGRESOS</b>", 'IMPORTE'=>"<b>".number_format($importeTotal,2,",",".")."</b>", 'Detalle'=>"", 'FormaPago'=>"");				
				$data[] = $listado;
			
			
			$pdf->ezTable($data,array('#'=>'<b>#</b>', 'RECIBO'=>'<b>RECIBO</b>', 'ALUMNO'=>'<b>ALUMNO</b>', 'IMPORTE'=>'<b>IMPORTE</b>', 'Detalle'=>'<b>CONCEPTO</b>', 'FormaPago'=>'<b>F.PAGO</b>'),'MOVIMIENTOS DE COMPROBANTES', array('maxWidth'=>550, 'showLines'=>0, 'shaded'=>0, 'fontSize' => 6, 'cols'=>array('RECIBO'=>array('justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'), 'Detalle'=>array('justification'=>'left', 'xPos' => 'center', 'width'=>'200'), 'ALUMNO'=>array('justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'), 'IMPORTE'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'FormaPago'=>array('justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center', 'width'=>'40')) ));	
			//Importe total
			$pdf->ezText("\n", $fontSize, array('justification'=>'left'));
			$data = "";
			
			
		}//fin if

	//*/

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL RESUMEN DE LA CAJA DIARIA</b>");
	$pdf->addText(50,750,9,"Motivo: El número emitido no es válido");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>