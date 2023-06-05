<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 $sql = "SET NAMES UTF8;";
 //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$Fac_Numero = $_GET['FacturaNumero'];
//echo $Fac_Numero."<br />";
list($Sucursal, $Numero) = explode("-",$Fac_Numero);
//echo $Numero;
$sql = "SELECT * FROM Factura WHERE Fac_Numero ='$Numero' AND Fac_Sucursal = '$Sucursal'";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$FacID=$row[Fac_ID];
if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

error_reporting(E_ALL);
include ('clase_pdf/class.pdf.php');
include ('clase_pdf/class.ezpdf.php');
include ('clase_pdf/class.pdfbarcode.php');



$pdf = new Cezpdf('A4');
$pdf->selectFont('clase_pdf/fonts/Times-Roman.afm');
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
	
	$pdf->addText(120,$yArriba,14,"COLEGIO PARROQUIAL");
	$pdf->addText(120,$yArriba - 15,14,"SANTA LUCIA");
	$pdf->addText(350,$yArriba,18,$row['Fac_Sucursal']."-".$row['Fac_Numero']);
	$pdf->addText(350,$yArriba - 22,12,"Fecha: ".cfecha($row['Fac_Fecha']));
	$pdf->addText(350,$yArriba - 50,8,"C.U.I.T.: 30-68168113-9");
	$pdf->addText(350,$yArriba - 60,8,"ING. BRUTOS: EXENTO");
	$pdf->addText(350,$yArriba - 70,8,"I.V.A.: EXENTO");
	$pdf->addText(350,$yArriba - 80,8,"APORTE ESTATAL 100%");
	$pdf->ezSetY(770);
	//$pdf->ezSetY(800);
	$fontSize = 10;
	$pdf->ezSetMargins(50,30,90,300);//ezSetMargins(top,bottom,left,right)
	$pdf->ezText("Gral. Paz 2098 Oeste - Santa Lucía\nJ5400 - San Juan\nTel. (0264) 425-3757", $fontSize, array('justification'=>'center'));
	$pdf->ezSetMargins(30,30,50,50);
	$pdf->addText(40,$yArriba - 112,12,"Alumno/a: <b>".$row['Fac_PersonaNombre']."</b>");
	$ruta_images = "logos/logo_cpsl.jpg";	
	$pdf->addJpegFromFile($ruta_images,40,$yArriba - 80,70,0);	
	$pdf->rectangle(25,$yArriba - 125,500,35);//rectangle(x1,y1,width,height)
	$pdf->rectangle(25,$yArriba - 345,500,220);
	$pdf->rectangle(25,$yArriba - 345,500,35);
	
	$pdf->setLineStyle(1,'','',array(1,0));	
	$pdf->line(315,$yArriba - 15,315,$yArriba - 90);
	$pdf->rectangle(300,$yArriba - 15,30,30);
	$pdf->addText(308,$yArriba - 5,18,"X");
	
	$pdf->setLineStyle(5,'round','',array(0,15));
	$pdf->line(5,$yArriba - 370,600,$yArriba - 370);//line(x1,y1,x2,y2)
	
	//Texto abajo
	$yAbajo = 410;
	
	$pdf->setLineStyle(1,'','',array(1,0));
	$pdf->addText(120,$yAbajo,14,"COLEGIO PARROQUIAL");
	$pdf->addText(120,$yAbajo - 15,14,"SANTA LUCIA");
	$pdf->addText(350,$yAbajo,18,$row['Fac_Sucursal']."-".$row['Fac_Numero']);
	$pdf->addText(350,$yAbajo - 22,12,"Fecha: ".cfecha($row['Fac_Fecha']));
	$pdf->addText(350,$yAbajo - 50,8,"C.U.I.T.: 30-68168113-9");
	$pdf->addText(350,$yAbajo - 60,8,"ING. BRUTOS: EXENTO");
	$pdf->addText(350,$yAbajo - 70,8,"I.V.A.: EXENTO");
	$pdf->addText(350,$yAbajo - 80,8,"APORTE ESTATAL 100%");
	
	$pdf->ezSetY(370);
	//$pdf->ezSetY(360);
	$fontSize = 10;
	$pdf->ezSetMargins(50,30,90,300);//ezSetMargins(top,bottom,left,right)
	$pdf->ezText("Laprida 348 Oeste - Catamarca 52 Sur\nJ5400 - San Juan\nTel. 4085271 - Cel 154434519", $fontSize, array('justification'=>'center'));
	$pdf->ezSetMargins(30,30,50,50);
	
	
	$pdf->addText(40,$yAbajo - 112,12,"Alumno/a: <b>".$row['Fac_PersonaNombre']."</b>");
	$pdf->addJpegFromFile($ruta_images,40,$yAbajo - 80,70,0);
	$pdf->rectangle(25,$yAbajo - 125,500,35);//rectangle(x1,y1,width,height)
	$pdf->rectangle(25,$yAbajo - 345,500,220);
	$pdf->rectangle(25,$yAbajo - 345,500,35);
	$pdf->setLineStyle(1,'','',array(1,0));	
	$pdf->line(315,$yAbajo - 15,315,$yAbajo - 90);
	$pdf->rectangle(300,$yAbajo - 15,30,30);
	$pdf->addText(308,$yAbajo - 5,18,"X");
	
	 $sql_1 = "SELECT * FROM FacturaDetalle WHERE FDe_Fac_ID =$FacID";
        $resultado = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($resultado) > 0) {
            $y = $yArriba - 155;
			$yy = $yAbajo - 155;//190;
			while ($row1 = mysqli_fetch_array($resultado)) {
				//Detalle
				$pdf->addText(40,$y,12,$row1['FDe_Detalle']);
				$pdf->addText(40,$yy,12,$row1['FDe_Detalle']);
				
				//Importe
				$pdf->addText(450,$y,12,"$".$row1['FDe_Importe']);
				$pdf->addText(450,$yy,12,"$".$row1['FDe_Importe']);
				
				$y = $y -20;
				$yy = $yy -20;
				
			}//fin while
			
			//Importe total
			$pdf->addText(410,$yArriba - 333,12,"TOTAL: $".$row['Fac_ImporteTotal']);
			$pdf->addText(410,$yAbajo - 333,12,"TOTAL: $".$row['Fac_ImporteTotal']);
			if ($row['Fac_Anulada']==1){
				$ruta_images = "imagenes/FACTURA-ANULADA.jpg";
				//Izquierda
				$pdf->addJpegFromFile($ruta_images,150,$yArriba - 133,100);
				$pdf->addJpegFromFile($ruta_images,150,$yAbajo - 133,100);
				$pdf->addJpegFromFile($ruta_images,200,$yArriba - 233,100);
				$pdf->addJpegFromFile($ruta_images,200,$yAbajo - 233,100);
				$pdf->addJpegFromFile($ruta_images,150,$yArriba - 333,100);
				$pdf->addJpegFromFile($ruta_images,150,$yAbajo - 333,100);
				//Derecha
				$pdf->addJpegFromFile($ruta_images,450,$yArriba - 133,100);
				$pdf->addJpegFromFile($ruta_images,450,$yAbajo - 133,100);
				$pdf->addJpegFromFile($ruta_images,500,$yArriba - 233,100);
				$pdf->addJpegFromFile($ruta_images,500,$yAbajo - 233,100);
				$pdf->addJpegFromFile($ruta_images,450,$yArriba - 333,100);
				$pdf->addJpegFromFile($ruta_images,450,$yAbajo - 333,100);
			}//fin if
			/*if ($row['Fac_Pagada']==1){
				$ruta_images = "imagenes/FACTURA-PAGADA.jpg";
				$pdf->addJpegFromFile($ruta_images,200,$yArriba - 333,100);
				$pdf->addJpegFromFile($ruta_images,200,$yAbajo - 333,100);
				//$pdf->addPngFromFile($ruta_images,0,0,100);
			}//fin if*/
			
		}//fin if
	

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