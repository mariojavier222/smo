<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 $sql = "SET NAMES UTF8;";
 //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$PPa_ID = $_GET['id'];
$sql = "SELECT * FROM PlanPago WHERE PPa_ID = $PPa_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$PPa_Per_ID=$row[PPa_Per_ID];
$PPa_Fecha=$row[PPa_Fecha];
$PPa_Hora=$row[PPa_Hora];
$PPa_Usu_ID=$row[PPa_Usu_ID];
$PPa_CursoAlumo=$row[PPa_CursoAlumo];
$PPa_NivelAlumno=$row[PPa_NivelAlumno];
$PPa_Alumno=$row[PPa_Alumno];
$PPa_DNIAlumno=$row[PPa_DNIAlumno];
$PPa_RolTutor=$row[PPa_RolTutor];
$PPa_Tutor=$row[PPa_Tutor];
$PPa_DNITutor=$row[PPa_DNITutor];
$PPa_DomicilioTutor=$row[PPa_DomicilioTutor];
$PPa_DeudaTotal=$row[PPa_DeudaTotal];
$PPa_DetalleCompromiso=$row[PPa_DetalleCompromiso];
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

$sql = "SELECT * FROM CuotaPersona WHERE Cuo_PPa_ID = $PPa_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$c = 0;
while ($row = mysqli_fetch_array($result)){
	$c++;
	$arrayCuotaPlan[$c]['importe'] = '$'.number_format($row['Cuo_Importe'],2,",",".");
	$arrayCuotaPlan[$c]['vencimiento'] = cfecha($row['Cuo_1er_Vencimiento']);
	
}//fin while
$textoPlan = "";
for ($j=1;$j<=$c;$j++){
	$textoPlan .= $arrayCuotaPlan[$j]['importe']." con vencimiento el ".$arrayCuotaPlan[$j]['vencimiento']."; ";
}
$textoPlan = substr($textoPlan,0,-2);
$textoPlan = " $c documentos ($textoPlan)";
	
	//Imprimimos el encabezado
	$pdf->ezSetY(810);
	$pdf->setLineStyle(1,'','',array(1,0));	
	//Title, Author, Subject, Keywords, Creator, Producer, CreationDate, ModDate, Trapped
	$datacreator = array (
                    'Title'=>"PLAN DE PAGOS",
                    'Subject'=>'ACTA DE ENTREGA DE DOCUMENTACIÓN Y RECONOCIMIENTO DE DEUDA',
                    'Author'=>COLEGIO_NOMBRE,
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'http://www.docmedia.com.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	$pdf->ezStartPageNumbers(50,20,8,'right',"Plan de Pago ".date("Y")."/$PPa_ID - <i>Página {PAGENUM} de {TOTALPAGENUM}</i>",1);
	$pdf->ezSetMargins(30,30,50,50);//ezSetMargins(top,bottom,left,right)

	
	$yArriba = 810;
	//Texto arriba
	$ruta_images = "logos/".COLEGIO_LOGO;
	//$ruta_images = "logos/logo_gateway.png";	
	//$pdf->addJpegFromFile($ruta_images,50,$yArriba,120,0);
	//ezImage(image,[padding],[width],[resize],[justification],[array border])
	
	$pdf->ezImage($ruta_images,10,80,'none','center');
	
	//$ruta_images = "logo_gateway.jpg";
	//$pdf->ezImage($ruta_images);
	
	//$pdf->ezSetY(810);
	$fontSize = 14;
	
	$pdf->ezText("<b><c:uline>ACTA DE ENTREGA DE DOCUMENTACIÓN\n Y RECONOCIMIENTO DE DEUDA\n</c:uline></b>", $fontSize, array('justification'=>'center'));
	$fontSize = 10;
	$dia = date("d", strtotime($PPa_Fecha));
	$m = date("n", strtotime($PPa_Fecha));
	$anio = date("Y", strtotime($PPa_Fecha));
	$mes = $gMes[$m];
	$sql = "SELECT * FROM Persona WHERE Per_DNI = '$PPa_DNITutor'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$tutorSexo = $row['Per_Sexo'];
	if ($tutorSexo=="M") {
		$tutorTitulo = "Sr.";
		$tutorArticulo = "al";
		$tutorArt = "el"; 
		$tutorVocal = "o"; 
	}else{ 
		$tutorTitulo = "Sra.";
		$tutorArticulo = "a la";
		$tutorArt = "la";
		$tutorVocal = "a";
	}
	$sql = "SELECT * FROM Persona WHERE Per_ID = $PPa_Per_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$hijoSexo = $row['Per_Sexo'];
	if ($hijoSexo=="M") {
		$hijoTitulo = "alumno";
		$hijoArticulo = "del"; 
		$hijoVocal = "o"; 
	}else{ 
		$hijoTitulo = "alumna";
		$hijoArticulo = "de la";
		$hijoVocal = "a";
	}
	$PPa_DNITutor = number_format($PPa_DNITutor,0,',','.');
	$PPa_DNIAlumno = number_format($PPa_DNIAlumno,0,',','.');
	$texto = "En la Ciudad de San Juan, a los <b>$dia días del mes de $mes de $anio</b>, en el domicilio del ".COLEGIO_NOMBRE.", sito en calle ".COLEGIO_DOMICILIO.", el Sr. Roberto Segovia, en su carácter de Representante Legal del Colegio, procede a hacer entrega $tutorArticulo $tutorTitulo <b>$PPa_Tutor</b>, D.N.I. N° $PPa_DNITutor, domiciliad$tutorVocal en $PPa_DomicilioTutor, Provincia de San Juan, $PPa_RolTutor $hijoArticulo $hijoTitulo de <b>$PPa_CursoAlumo</b> del Ciclo Lectivo $anio de Educación $PPa_NivelAlumno de este colegio, <b>$PPa_Alumno</b>, D.N.I. N° $PPa_DNIAlumno, de la documentación oficial $hijoArticulo $hijoTitulo, la cual ha sido solicitada al colegio en el día de la fecha.\n 
En este mismo acto se deja constancia que $tutorArt representante legal del menor <b>$PPa_Tutor</b>, de acuerdo al contrato firmado entre $tutorArt responsable $hijoArticulo $hijoTitulo y el colegio, reconoce la existencia al día de la fecha, de deuda con el colegio de acuerdo al siguiente detalle:\n\n";
	$pdf->ezText($texto, $fontSize, array('justification'=>'full'));
//echo "<br />";
	$PPa_DetalleCompromiso = substr($PPa_DetalleCompromiso,0,-1);
	$detalle = explode("*", $PPa_DetalleCompromiso);///\/|-/
	
	foreach($detalle as $valor){
		//echo "Valor: $valor<br />";
		list($concepto,$lectivo, $vencimiento, $importe, $recargo, $total) = explode("|", $valor);
		//echo "$concepto,$lectivo, $vencimiento, $importe, $recargo, $total FIN<br />";
		$listado = array('Concepto'=>"$concepto", 'Lectivo'=>$lectivo, 'Vencimiento'=>$vencimiento, 'Importe'=>number_format($importe,2,",","."), 'Recargo'=>number_format($recargo,2,",","."), 'Total'=>number_format($total,2,",","."));				
		$data[] = $listado;	
	}
	$pdf->ezTable($data,array('Concepto'=>'<b>Concepto adeudado</b>', 'Lectivo'=>'<b>Lectivo</b>', 'Vencimiento'=>'<b>Vencimiento</b>', 'Importe'=>'<b>Importe</b>', 'Recargo'=>'<b>Recargo</b>', 'Total'=>'<b>Total</b>'),'DEUDA RECONOCIDA', array('maxWidth'=>550, 'showLines'=>2, 'fontSize' => 8, 'cols'=>array('Importe'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Recargo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Total'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	$fontSize = 12;
	$pdf->ezText("\nDEUDA TOTAL: <b>$".number_format($PPa_DeudaTotal,2,",",".")."</b>", $fontSize, array('justification'=>'centre'));
	$fontSize = 10;
	$texto = "\n\nDichos importes son validos hasta el día 31/12/".date("Y").", los que serán actualizados a la fecha de su efectivo pago.\n\n";
	$texto .= "Es por ello que en este acto $tutorArt representante legal $hijoArticulo $hijoTitulo se compromete a abonar lo adeudado, por lo que firma <b>$textoPlan</b> que suman el importe adeudado, dejando expresa constancia que en caso de incumplimiento por parte del responsable $hijoArticulo $hijoTitulo en el pago de lo adeudado, el ".COLEGIO_NOMBRE." inicie las acciones extrajudiciales y judiciales pertinentes, tendientes al cobro de lo adeudado, por lo que la presente tendrá valor de titulo ejecutivo.\n\n
Dando por finalizado el presente acto, se suscriben en el lugar y fecha antes mencionados, dos ejemplares de un mismo tenor y a un solo efecto, de los cuales cada parte recibe el suyo en este acto.";
	$pdf->ezText($texto, $fontSize, array('justification'=>'full'));

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