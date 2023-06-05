<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");
//echo "De 10";
//exit;
 
 $sql = "SET NAMES UTF8;";
 //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$textoDia = $_POST['textoDia'];
$textoMes = $_POST['textoMes'];
$textoAnio = $_POST['textoAnio'];
$textoCurso = $_POST['textoCurso'];
$textoTitulo = $_POST['textoTitulo'];
$textoLineaAdicional = $_POST['textoLineaAdicional'];
$textoHabiendo = $_POST['textoHabiendo'];
$textoLectivo = $_POST['textoLectivo'];
$textoQuien = $_POST['textoQuien'];
$DNI = $_POST['DNI'];
$Persona = $_POST['persona'];
$PerID = $_POST['PerID'];

$sql = "SELECT * FROM Persona WHERE Per_ID ='$PerID' ";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$Sexo=$row[Per_Sexo];
if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

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
$imprimir = true;
//si viene el DNI imprimo
if ($imprimir){  

$total = $i;


	
	//Imprimimos el encabezado
	$pdf->ezSetY(800);
	$pdf->setLineStyle(1,'','',array(1,0));
	
	$Colegio = utf8_decode(COLEGIO_NOMBRE);
	$Logo = COLEGIO_LOGO;
	$Domicilio = COLEGIO_DOMICILIO;
	$Telefono = COLEGIO_TELEFONO;

	$yArriba = 810;
	//Texto arriba
	$ruta_images = "logos/$Logo";
	
	//Imprimimos el encabezado
	$pdf->setLineStyle(1,'','',array(1,0));
	$datacreator = array (
                    'Title'=>"CERTIFICADO DE ESCOLARIDAD",
                    'Subject'=>'CERTIFICADO DE ESCOLARIDAD',
                    'Author'=>$Colegio,
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'http://www.docmedia.com.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	//logo del colegio
	$pdf->addJpegFromFile($ruta_images,250,$yArriba - 40,100,0);
	//$pdf->addPngFromFile($ruta_images,250,$yArriba - 40,140,0);
	
	//linea divisoria
	$pdf->setLineStyle(1);
	//$pdf->line(20,$yArriba-50,575,$yArriba-50);
	//$pdf->line(20,$yArriba-50,575,$yArriba-50);
	$pdf->addText(0,$yArriba - 50,12,"______________________________________________________________________________________________");
	$pdf->ezSetY(750);
	$fontSize = 14;
	$pdf->ezText("<b><i>".utf8_decode($textoTitulo)."</i></b>", $fontSize, array('justification'=>'center'));
	$textoCompleto = "";	
	$pdf->ezText("\nSan Juan, <b>$textoDia</b> de <b>$textoMes</b> de <b>$textoAnio</b>\n", $fontSize, array('justification'=>'center'));	
	if ($Sexo=="M") $tipoSexo = "el alumno"; else $tipoSexo = "la alumna";
	//$Persona = str_replace(",","", $Persona);
	list($Apellido, $Nombre) = explode(",", $Persona);
	$Nombre = trim($Nombre);
	//$pdf->addText(50,$yArriba - 150,12,"La dirección del Colegio Parroquial SANTA LUCIA certifica que $tipoSexo, <b>".utf8_decode($Apellido)."</b>");
	$textoCompleto .= "La dirección del $Colegio certifica que $tipoSexo, <b>".utf8_decode($Apellido)."</b>, ";
	if ($Sexo=="M") $tipoSexo = "o"; else $tipoSexo = "a";
	$textoCurso = str_replace("\'",'"', $textoCurso);
	$textoCompleto .= utf8_decode("<b>$Nombre</b> con DNI <b>$DNI</b> es alumn$tipoSexo regular de <b>$textoCurso,</b> ");
	//$pdf->addText(50,$yArriba - 170,12,utf8_decode("<b>$Nombre</b> con DNI <b>$DNI</b> es alumn$tipoSexo regular de <b>$textoCurso,</b>"));
	$Lec_ID = gLectivoActual($Lec_Nombre);
	$sql = "SELECT * FROM Legajo
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID)
    INNER JOIN Colegio_Inscripcion 
        ON (Ins_Leg_ID = Leg_ID) WHERE Per_ID ='$PerID' AND Ins_Lec_ID = $Lec_ID";
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row2 = mysqli_fetch_array($result2);
	
	//if ($row2['Ins_Niv_ID'] == 1) $Nivel = "Primaria"; else $Nivel = "Nivel Inicial";
	switch ($row2['Ins_Niv_ID']) {
		case 1: $Nivel = "Primaria";break;
		case 2: $Nivel = "Secundaria";break;
		case 3: $Nivel = "Nivel Inicial";break;
		case 4: $Nivel = "Cursillo de Ingreso";break;
	}//fin switch
	$textoCompleto .= "<b>Educación $Nivel</b>".utf8_decode(", habiendo <b>$textoHabiendo</b> el ciclo lectivo <b>$textoLectivo</b>.-");
	//$pdf->addText(50,$yArriba - 190,12,"<b>Educación $Nivel</b>".utf8_decode(", habiendo <b>$textoHabiendo</b> el ciclo lectivo <b>$textoLectivo</b>.-"));
	$fontSize = 12;
	$pdf->ezText($textoCompleto, $fontSize, array('justification'=>'full'));
	$yResta = 230;
	
	if (!empty($textoLineaAdicional)){
		
		//$pdf->ezSetY($yArriba -$yResta);
		//$pdf->ezSetX(50);
		$pdf->ezText("\n".utf8_decode($textoLineaAdicional),12, array('justification'=>'left'));
		$yResta += 40;
	}
	//$pdf->selectFont('clase_pdf/fonts/Helvetica.afm');
	$pdf->ezText("\nDicha certificación se extiende a los efectos de ser presentados ante <b>".utf8_decode($textoQuien)."</b> .-",$fontSize, array('justification'=>'left'));
	//$pdf->addText(50,$yArriba - $yResta,12,"Dicha certificación se extiende a los efectos de ser presentados ante <b>".utf8_decode($textoQuien)."</b> .-");
	$yResta += 50;
	//$pdf->addText(0,$yArriba - $yResta,12,"______________________________________________________________________________________________");
	$pdf->ezText("\n____________________________________________________________________________",$fontSize, array('justification'=>'left'));
	$yResta += 12;
	$pdf->ezText("$Domicilio – San Juan\nTeléfono: $Telefono",$fontSize, array('justification'=>'center'));
	
	$yResta += 12;
	
	
	

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL CERTIFICADO DE REGULARIDAD</b>");
	$pdf->addText(50,750,9,"Motivo: El DNI ingresado no existe $sql");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();




?>