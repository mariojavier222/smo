<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
 	$LecID = $_GET['LecID'];
	$LecNombre = Obtener_LectivoNombre($LecID);
	$CurID = $_GET['CurID'];
	$NivID = $_GET['NivID'];
	$DivID = $_GET['DivID'];
	$SedID = $_GET['SedID'];
	$criterioDeuda = $_GET['criterioDeuda'];
	
	$sql = "SET NAMES UTF8;";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID  AND Leg_Sed_ID = $SedID AND Leg_Baja = 0 ";	
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Persona.Per_Sexo DESC, Per_Apellido, Per_Nombre;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

/*$sql = "SELECT Leg_Colegio FROM CuotaPago
    INNER JOIN Legajo 
        ON (CuP_Per_ID = Leg_Per_ID)
WHERE (CuP_Fac_ID =$FacID)";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$Leg_Colegio=$row['Leg_Colegio'];
}*/
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


$i = 0;
//$imprimir = true;
//si viene el DNI imprimo
if ($imprimir){  

$total = $i;


	
	//Imprimimos el encabezado
	$pdf->ezSetY(800);
	$pdf->setLineStyle(1,'','',array(1,0));	
	$datacreator = array (
                    'Title'=>"PLAN DE PAGOS",
                    'Subject'=>'DEUDA PERSONALIZADA',
                    'Author'=>COLEGIO_SIGLAS,
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'http://www.docmedia.com.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	//$pdf->ezStartPageNumbers(50,20,8,'right',"Plan de Pago Nº $PPa_ID - <i>Página {PAGENUM} de {TOTALPAGENUM}</i>",1);
	$pdf->ezSetMargins(30,30,50,50);//ezSetMargins(top,bottom,left,right)
	
	$yArriba = 810;
	//Texto arriba
	$PPa_Fecha = date("Y-m-d");
	
while ($row = mysqli_fetch_array($result)){		
$Deuda = Obtener_Deuda_Sistema($row['Per_ID']);

if ($Deuda>=$criterioDeuda){	
	
	$i++;
	$fontSize = 12;
	$ruta_images = "logos/logo_cpsl.jpg";
	//$pdf->ezImage($ruta_images,10,40,'none','right');
	$pdf->ezText("<b>URGENTE - NOTIFICACIÓN DE DEUDA - PERDIDA DE DERECHO DE INSCRIPCION</b>", $fontSize, array('justification'=>'center'));
	$fontSize = 10;
	$dia = date("d", strtotime($PPa_Fecha));
	$m = date("n", strtotime($PPa_Fecha));
	$anio = date("Y", strtotime($PPa_Fecha));
	$mes = $gMes[$m];
	
	/*obtenerTutores($row['Per_ID'],$arrarTutores, $cant);
	$PPa_Tutor = utf8_decode($arrarTutores[1]['Per_Nombre']." ".$arrarTutores[1]['Per_Apellido']);
	$PerID_Tutor = $arrarTutores[1]['Per_ID'];
	$tutorSexo = $arrarTutores[1]['Per_Sexo'];
	$PPa_RolTutor = $arrarTutores[1]['FTi_Tipo'];
	$PPa_DNITutor = $arrarTutores[1]['Per_DNI'];*/
	$Curso = utf8_decode($row[Cur_Nombre].' '.$row[Div_Nombre]);
	$Nivel = $row['Niv_Nombre'];
	/*if ($tutorSexo=="M") {
		$tutorTitulo = "Sr.";
		$tutorArticulo = "al";
		$tutorArt = "el"; 
		$tutorVocal = "o"; 
	}else{ 
		$tutorTitulo = "Sra.";
		$tutorArticulo = "a la";
		$tutorArt = "la";
		$tutorVocal = "a";
	}*/
	$hijoSexo = $row['Per_Sexo'];
	if ($hijoSexo=="M") {
		$hijoTitulo = "alumno";
		$hijoTituloHijo = "hijo";
		$hijoArticulo = "del"; 
		$hijoVocal = "o"; 
	}else{ 
		$hijoTitulo = "alumna";
		$hijoTituloHijo = "hija";
		$hijoArticulo = "de la";
		$hijoVocal = "a";
	}
	//$PPa_DNITutor = number_format($PPa_DNITutor,0,',','.');
	$PPa_DNIAlumno = number_format($row['Per_DNI'],0,',','.');
	$PPa_Alumno = utf8_decode($row['Per_Nombre']." ".$row['Per_Apellido']);
	//$texto = "Estimad$tutorVocal <b>$PPa_Tutor</b>, $PPa_RolTutor $hijoArticulo $hijoTitulo <b>$PPa_Alumno</b> de <b>$Curso de $Nivel</b>. Se le informa que al día de la fecha, <b>$dia de $mes de $anio</b>, se encuentran impagas y vencidas las siguientes cuotas de su hij$hijoVocal, según se detallan:\n";
	$texto = "<b>Sres. Padres</b> $hijoArticulo $hijoTitulo <b>$PPa_Alumno</b> de <b>$Curso de $Nivel</b>. El día Martes 20 de Septiembre el Colegio les enviará la solicitud de Reserva de Vacante, a los efectos de reservar el lugar para la inscripción definitiva. La cual deberá completar y firmar. De no encontrarse al día con las cuotas <b>NO quedará efectuada dicha reserva</b> y su $hijoTituloHijo no podrá inscribirse para el Ciclo Lectivo 2018. Por favor evite inconvenientes.-\n";
	$pdf->ezText($texto, $fontSize, array('justification'=>'full'));	
	/*$sql = "SELECT * FROM CuotaPersona 
		INNER JOIN Lectivo 
			ON (Cuo_Lec_ID = Lec_ID)
		INNER JOIN Colegio_Nivel 
			ON (Cuo_Niv_ID = Niv_ID)
		INNER JOIN CuotaTipo 
			ON (Cuo_CTi_ID = CTi_ID)
		WHERE Cuo_Per_ID='".$row['Per_ID']."' AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento<'".date("Y-m-d")."'";//echo $sql;exit;
		$resultDeuda = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
	 	if (mysqli_num_rows($resultDeuda) > 0) {
			$data = array();
			$totalDeuda = 0;
			while ($rowDeuda = mysqli_fetch_array($resultDeuda)){
			
			$importe = $rowDeuda['Cuo_Importe'];
			$importeOriginal = $importe;
			$datosCuota = $rowDeuda['Cuo_Lec_ID'].";".$rowDeuda['Cuo_Per_ID'].";".$rowDeuda['Cuo_Niv_ID'].";".$rowDeuda['Cuo_CTi_ID'].";".$rowDeuda['Cuo_Alt_ID'].";".$rowDeuda['Cuo_Numero'];
			recalcularImporteCuota($datosCuota, $importe, true);
			$importeAbonado = buscarPagosTotales($datosCuota);
			$importeActual = $importeOriginal - $importeAbonado;
			$recargoCuota = $importe - $importeActual;
			$vencimiento = cfecha($rowDeuda['Cuo_1er_Vencimiento']);
			//concepto,lectivo, vencimiento, importe, recargo, total
			$concepto = "$rowDeuda[CTi_Nombre] (".buscarMes($rowDeuda['Cuo_Mes'])."/".$rowDeuda['Cuo_Anio'].")";
			$lectivo = $rowDeuda['Lec_Nombre'];
			$total = $importe;
			$totalDeuda += $total;
			$importe = $importeActual;
			$recargo = $recargoCuota;	
			$listado = array('Concepto'=>"$concepto", 'Lectivo'=>$lectivo, 'Vencimiento'=>$vencimiento, 'Importe'=>number_format($importe,2,",","."), 'Recargo'=>number_format($recargo,2,",","."), 'Total'=>number_format($total,2,",","."));				
			$data[] = $listado;		
			}//fin while deuda cuotas
		}//fin if
	*/
	//$pdf->ezTable($data,array('Concepto'=>'<b>Concepto adeudado</b>', 'Lectivo'=>'<b>Lectivo</b>', 'Vencimiento'=>'<b>Vencimiento</b>', 'Importe'=>'<b>Importe</b>', 'Recargo'=>'<b>Recargo</b>', 'Total'=>'<b>Total</b>'),'', array('maxWidth'=>550, 'showLines'=>2, 'fontSize' => 8, 'cols'=>array('Importe'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Recargo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Total'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	//$fontSize = 8;
	//$pdf->ezText("DEUDA TOTAL: <b>$".number_format($totalDeuda,2,",",".")."</b>", $fontSize, array('justification'=>'centre'));
	//$fontSize = 8;		
	//$texto = "\nEn caso de encontrar algún error, le pedimos que se acerque con los comprobantes de pago por <b>Administración</b> para regularizar su situación. Evite recargos.\n";
	//$pdf->ezText($texto, $fontSize, array('justification'=>'full'));
	$texto = "<b>$dia de $mes de $anio</b>\n<b><i>LA ADMINISTRACIÓN</i> - ".COLEGIO_NOMBRE."</b>\nHorario de atención: Lun a Vie de 08 a 16 hs.";
	$pdf->ezText($texto, $fontSize, array('justification'=>'right'));
	$pdf->ezText("<b>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</b>\n", 14, array('justification'=>'full'));
	
	if ($i%5==0) $pdf->ezNewPage();
}//fin if deuda
}//fin while

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR ESTA PLANILLA</b>");
	$pdf->addText(50,750,9,"Motivo: No se encontraron alumnos inscriptos");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>