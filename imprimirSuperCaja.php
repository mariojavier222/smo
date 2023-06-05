<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 $sql = "SET NAMES UTF8;";
 //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$SCa_ID = $_GET['id'];
//echo $Fac_Numero."<br />";
//list($Sucursal, $Numero) = explode("-",$Fac_Numero);
//echo $Numero;
$sql = "SELECT * FROM SuperCaja WHERE SCa_ID = $SCa_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$SCa_ID=$row[SCa_ID];
$SCa_Apertura=$row[SCa_Apertura];
$SCa_Cierre=$row[SCa_Cierre];
if (empty($SCa_Cierre)) $SCa_Cierre= "Caja sin cerrar"; else $SCa_Cierre=cfecha(substr($SCa_Cierre,0,10)).substr($SCa_Cierre,10,10);
$SCa_Observaciones=$row[SCa_Observaciones];
$SCa_Usu_ID=$row[SCa_Usu_ID];
$SCa_ImporteTotal=$row[SCa_ImporteTotal];
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
                    'Title'=>"PLANILLA DE SUPERCAJA",
                    'Subject'=>'RESUMEN DE MOVIMIENTOS',
                    'Author'=>'CPSL',
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'http://www.docmedia.com.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	$pdf->ezStartPageNumbers(50,20,8,'right',"<i>Página {PAGENUM} de {TOTALPAGENUM}</i>",1);
	$pdf->ezSetMargins(30,30,50,50);//ezSetMargins(top,bottom,left,right)

	
	$yArriba = 810;
	//Texto arriba
	$ruta_images = "logos/logo_cpsl.jpg";
	//$ruta_images = "logos/logo_gateway.png";	
	//$pdf->addJpegFromFile($ruta_images,50,$yArriba,120,0);
	//ezImage(image,[padding],[width],[resize],[justification],[array border])
	
	$pdf->ezImage($ruta_images,10,60,'none','right');
	
	//$ruta_images = "logo_gateway.jpg";
	//$pdf->ezImage($ruta_images);
	
	//$pdf->ezSetY(810);
	$fontSize = 14;
	
	$pdf->ezText("<b><c:uline>RESUMEN DE CAJA\n</c:uline></b>", $fontSize, array('justification'=>'center'));
	$fontSize = 10;
	$pdf->ezText("<b>SUPER CAJA Nº $SCa_ID</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("<c:uline>Fecha de Apertura</c:uline>: <b>".cfecha(substr($SCa_Apertura,0,10)).substr($SCa_Apertura,10,10)."</b>", $fontSize, array('justification'=>'left'));
	//$pdf->ezSetMargins(30,30,100,100);//ezSetMargins(top,bottom,left,right)
	$pdf->ezText("<c:uline>Fecha de Cierre</c:uline>: <b>$SCa_Cierre</b>", $fontSize, array('justification'=>'left'));
	
		
	 $sql_1 = "SELECT * FROM SuperCajaCorriente INNER JOIN Usuario ON SCC_Usu_ID = Usu_ID INNER JOIN CajaCorriente 
        ON (SCC_CCC_ID = CCC_ID)
    INNER JOIN FormaPago 
        ON (CCC_For_ID = For_ID) WHERE SCC_SCa_ID = $SCa_ID";
        $resultado = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($resultado) > 0) {
            $debito = 0;
			$credito = 0;
			while ($row1 = mysqli_fetch_array($resultado)) {
				
				$SCC_ID=$row1['SCC_ID'];
				$SCC_Caja_ID=$row1['SCC_Caja_ID'];
				$SCC_CCC_ID=$row1['SCC_CCC_ID'];
				$SCC_SCa_ID=$row1['SCC_SCa_ID'];
				$SCC_Concepto=$row1['SCC_Concepto'];
				
				$SCC_Debito = $row1['SCC_Debito'];
				$SCC_Credito = $row1['SCC_Credito'];
				$SCC_Saldo = $row1['SCC_Saldo'];
				
				$Fecha=cfecha(substr($row1['SCC_FechaHora'],0,10));
				$Hora=substr($row1['SCC_FechaHora'],11,10);
				$SCC_Detalle=$row1['SCC_Detalle'];
				$Usuario=$row1['Usu_Nombre'];
				$FormaPago=$row1['For_Nombre'];
				$listado = array('Fecha'=>"$Fecha $Hora", 'Concepto'=>$SCC_Concepto, 'Ingreso'=>number_format($SCC_Credito,2,",","."), 'Egreso'=>number_format($SCC_Debito,2,",","."), 'Saldo'=>number_format($SCC_Saldo,2,",","."), 'Detalle'=>$SCC_Detalle, 'Usuario'=>$Usuario, 'FormaPago'=>$FormaPago);				
				$data[] = $listado;	
				$debito += $SCC_Debito;
				$credito += $SCC_Credito;
				//fecha, hora, concepto, ingreso, egreso, saldo, detalle, usuario, forma de pago
				
				
			}//fin while
			$importeTotal = $credito - $debito;
			$importeTotal = number_format($importeTotal,2,",",".");
			$pdf->ezText("\nSALDO TOTAL: <b>$".$importeTotal."</b>", $fontSize, array('justification'=>'left'));
			//$data[] = $listado;
			$pdf->ezTable($data,array('Fecha'=>'Fecha/Hora', 'Concepto'=>'Concepto', 'Ingreso'=>'Ingreso', 'Egreso'=>'Egreso', 'Saldo'=>'Saldo', 'Detalle'=>'Detalle', 'Usuario'=>'Usuario', 'FormaPago'=>'Forma Pago'),'MOVIMIENTOS', array('maxWidth'=>550, 'showLines'=>2, 'fontSize' => 8, 'cols'=>array('Ingreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Egreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Saldo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));	
			//Importe total
			
			$pdf->ezText("\nSALDO TOTAL: <b>$".$importeTotal."</b>", $fontSize, array('justification'=>'left'));
			
			
		}//fin if
	//*/

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL RESUMEN DE LA SUPERCAJA</b>");
	$pdf->addText(50,750,9,"Motivo: El número emitido no es válido");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>