<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");
//echo "De 10";
//exit;
 
 $sql = "SET NAMES UTF8;";
 //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$Per_ID = $_GET['id'];


$sql = "SELECT * FROM Persona WHERE Per_ID ='$Per_ID' ";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);


if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

$Deuda = Obtener_Deuda_Sistema($Per_ID);

//if ($Deuda>0) $imprimir=false;
//echo $textoDia."<br />";exit;

//echo $Numero;

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


	$row = mysqli_fetch_array($result);
	$Sexo=$row['Per_Sexo'];
	$Alumno=$row['Per_Apellido'].", ".$row['Per_Nombre'];
	$DNI=number_format($row['Per_DNI'],0,",",".");
	$textoDia = date("d");
	require_once("globales.php");
	global $gMes;
	$mes = date("n");
	$textoMes = $gMes[$mes];
	$textoAnio = date("Y");
	//Imprimimos el encabezado
	$pdf->ezSetY(800);
	$pdf->setLineStyle(1,'','',array(1,0));
	

	$yArriba = 810;
	//Texto arriba
	$ruta_images = "logos/logo_cpsl_informe.jpg";
	
	//Imprimimos el encabezado
	$pdf->setLineStyle(1,'','',array(1,0));
	$datacreator = array (
                    'Title'=>"SOLICITUD DEL GRUPO FAMILIAR DEL ALUMNO",
                    'Subject'=>'SOLICITUD DEL GRUPO FAMILIAR DEL ALUMNO',
                    'Author'=>'COLEGIO PARROQUIAL SANTA LUCIA',
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'http://www.santalucia.edu.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	//logo del colegio
	$pdf->addJpegFromFile($ruta_images,250,$yArriba - 40,140,0);
	
	//linea divisoria
	$pdf->setLineStyle(1);
	//$pdf->line(20,$yArriba-50,575,$yArriba-50);
	//$pdf->line(20,$yArriba-50,575,$yArriba-50);
	$pdf->addText(0,$yArriba - 50,12,"______________________________________________________________________________________________");
	$pdf->ezSetY(750);
	$fontSize = 14;
	$textoTitulo = "CERTIFICADO DE LIBRE DE DEUDA";
	$pdf->ezText("<b><i>".utf8_decode($textoTitulo)."</i></b>", $fontSize, array('justification'=>'center'));
	$fontSize = 12;	
	$pdf->ezText("\nLa Administración del Colegio Parroquial Santa Lucía, certifica que <b>$Alumno</b>, D.N.I. Nº <b>$DNI</b>, no registra deuda alguna con esta Institución al día de la fecha.-\n", $fontSize, array('justification'=>'full'));	

	$pdf->ezText("\nA solicitud del interesado, y para ser presentado ante quien corresponda, se extiende el presente en Santa Lucia, San Juan, a los <b>$textoDia</b> días del mes de <b>$textoMes</b> de <b>$textoAnio</b>.-\n", $fontSize, array('justification'=>'full'));	
	$fontSize = 12;
	$pdf->ezText("\n____________________________________________________________________________",$fontSize, array('justification'=>'left'));
	$fontSize = 10;
	$pdf->ezText("Córdoba 2199 (Este) esq. Ramón Franco – Santa Lucía – San Juan\nTeléfonos: (0264) 425-3757 / 425-0390",$fontSize, array('justification'=>'center'));
	
	
	

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL CERTIFICADO DE REGULARIDAD</b>");
	$pdf->addText(50,750,9,"Motivo: Adeuda $Deuda pesos");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();




?>