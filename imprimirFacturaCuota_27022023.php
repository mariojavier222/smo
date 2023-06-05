<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("class.letras.php");
 
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

if (isset($_GET['FacturaNumero'])){
	$Fac_Numero = $_GET['FacturaNumero'];
	//echo $Fac_Numero."<br />";
	list($Sucursal, $Numero) = explode("-",$Fac_Numero);
	//echo $Numero;
	$sql = "SELECT * FROM Factura INNER JOIN Usuario ON (Fac_Usu_ID = Usu_ID) WHERE Fac_Numero ='$Numero' AND Fac_Sucursal = '$Sucursal'";
}	

if (isset($_GET['Fac'])){
	$Fac_ID = $_GET['Fac'];
	
	$sql = "SELECT * FROM Factura INNER JOIN Usuario ON (Fac_Usu_ID = Usu_ID) WHERE Fac_ID ='$Fac_ID'";
}
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$FacID=$row[Fac_ID];
$Fac_Numero = $row[Fac_Sucursal]."-".$row[Fac_Numero];
$FTi_ID = $row[Fac_FTi_ID];
$Fac_ID_Ndec = $row[Fac_ID_Ndec];;//comprobante asociado Tipo FAc suc y num
$Usuario=$row[Usu_Persona];
$FormaPago = "Forma de Pago: ".utf8_decode(buscarFormaPagoFactura($FacID));
$FormaPagoDetalle = utf8_decode(buscarFormaPagoFacturaDetalle($FacID));


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
	//$pdf->ezSetMargins(30,30,50,50);

	
	$yArriba = 810;
	//Texto arriba
	
	$fontSize = 10;

	$fechaCompleta = cfecha($row['Fac_Fecha']);
	$dia = substr($fechaCompleta, 0,2);
	$mes = substr($fechaCompleta, 3,2);
	$anio = substr($fechaCompleta, 8,2);


	$pdf->addText(500,$yArriba - 50,$fontSize,$Fac_Numero);

	$pdf->addText(490,$yArriba - 75,$fontSize,$dia);
	$pdf->addText(515,$yArriba - 75,$fontSize,$mes);
	$pdf->addText(535,$yArriba - 75,$fontSize,$anio);

	$pdf->addText(100,$yArriba - 130,$fontSize,utf8_decode($row['Fac_PersonaNombre']));

	
 
	$letrasTotal = new EnLetras();
	$valorLetras = $letrasTotal->ValorEnLetras($row['Fac_ImporteTotal'], "");
	$pdf->addText(100,$yArriba - 400,$fontSize,$valorLetras);
	
	//Texto abajo
	$yAbajo = 390;	
	
	/*$pdf->addText(500,$yAbajo - 70,$fontSize,$Fac_Numero);
	$pdf->addText(490,$yAbajo - 95,$fontSize,$dia);
	$pdf->addText(515,$yAbajo - 95,$fontSize,$mes);
	$pdf->addText(535,$yAbajo - 95,$fontSize,$anio);
	$pdf->addText(40,$yAbajo - 130,$fontSize,$row['Fac_PersonaNombre']);
	$pdf->addText(200,$yAbajo - 150,$fontSize,$valorLetras);*/
	
	
	
	 $sql_1 = "SELECT * FROM FacturaDetalle WHERE FDe_Fac_ID =$FacID";
        $resultado = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($resultado) > 0) {
            $y = $yArriba - 215;
			$yy = $yAbajo - 175;//190;
			$fontSize = 8;
			while ($row1 = mysqli_fetch_array($resultado)) {
				//Detalle
				$pdf->addText(100,$y,$fontSize,utf8_decode($row1['FDe_Detalle']));
				//$pdf->addText(40,$yy,$fontSize,$row1['FDe_Detalle']);
				
				//Importe
				$pdf->addText(500,$y,$fontSize,"$".$row1['FDe_Importe']);
				//$pdf->addText(480,$yy,$fontSize,"$".$row1['FDe_Importe']);
				
				$y = $y -10;
				$yy = $yy -10;
				
			}//fin while
			
			$fontSize = 10;
			//Importe total
			$pdf->addText(500,$yArriba - 350,$fontSize,"$".$row['Fac_ImporteTotal']);
			//$pdf->addText(480,$yAbajo - 323,$fontSize,"$".$row['Fac_ImporteTotal']);

			//Nota de Crédito
			if (is_numeric($Fac_ID_Ndec)){
				$InfoFactura = utf8_decode(buscarInfoFactura($Fac_ID_Ndec));
				$pdf->addText(100,$yArriba - 280,$fontSize,"Comprobante asociado ".$InfoFactura);
				//$pdf->addText(40,$yAbajo - 280,$fontSize,"Comprobante asociado ".$InfoFactura);
			}
			

			//Forma de Pago
			if (!empty($FormaPagoDetalle)){
				$FormaPago .= " ($FormaPagoDetalle)";
			}
			$pdf->addText(100,$yArriba - 175,$fontSize,$FormaPago);
			//$pdf->addText(40,$yAbajo - 300,$fontSize,$FormaPago);

			
			//Usuario que cobró
			$fontSize = 8;
			$pdf->addText(100,$yArriba - 190,$fontSize,"Atendido por $Usuario");
			//$pdf->addText(40,$yAbajo - 315,$fontSize,"Atendido por $Usuario");
			
			
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