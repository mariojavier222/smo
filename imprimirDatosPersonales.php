<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
$LecID=$_GET['a'];
$CurID=$_GET['b'];
$NivID=$_GET['c'];
$DivID=$_GET['d'];
$DivID=$_GET['d'];
$SedID = $_GET['e'];

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
WHERE (Per_Baja = 0 AND Leg_Baja = 0 AND Colegio_Inscripcion.Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID";
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Ins_Niv_ID, Ins_Cur_ID, Ins_Div_ID, Per_Apellido, Per_Nombre;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

error_reporting(E_ALL);
include ('clase_pdf/class.pdf.php');
include ('clase_pdf/class.ezpdf.php');
include ('clase_pdf/class.pdfbarcode.php');



$pdf = new Cezpdf('A4', 'landscape');
//$pdf = new Cezpdf('A4');
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
                    'Title'=>"LISTADO DE DATOS PERSONALES",
                    'Subject'=>'DATOS PERSONALES DE LOS ALUMNOS',
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
	$ruta_images = "logos/logo_cpsl_informe.jpg";
	//$ruta_images = "logos/logo_gateway.png";	
	//$pdf->addJpegFromFile($ruta_images,50,$yArriba,120,0);
	//ezImage(image,[padding],[width],[resize],[justification],[array border])
	
	$pdf->ezImage($ruta_images,10,120,'none','center');
	
	//$ruta_images = "logo_gateway.jpg";
	//$pdf->ezImage($ruta_images);
	
	//$pdf->ezSetY(810);
	$fontSize = 10;
	
	$pdf->ezText("<b><c:uline>DATOS PERSONALES DE LOS ALUMNOS\n</c:uline></b>", $fontSize, array('justification'=>'center'));
	$fontSize = 8;
	
	
	$cant = 0;	
	while ($row = mysqli_fetch_array($result)) {
				
		$cant++;
		$Dom = "----";
		$Nac = "----";
		$Tel = "----";
		$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = $row[Per_ID]";
		$resultDatos = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($resultDatos)>0){
			$rowDatos = mysqli_fetch_array($resultDatos);
			$Dom = utf8_decode(obtenerLugar($rowDatos['Dat_Dom_Pai_ID'], $rowDatos['Dat_Dom_Pro_ID'], $rowDatos['Dat_Dom_Loc_ID']));
			$Nac = cfecha($rowDatos['Dat_Nacimiento']);
			if (!empty($rowDatos['Dat_Telefono'])) $Tel = "Fijo: ".$rowDatos['Dat_Telefono']; else $Tel = "Fijo No tiene.";
			if (!empty($rowDatos['Dat_Celular'])) $Tel .= " Cel: ".$rowDatos['Dat_Celular']; else $Tel = " Celular No tiene.";
			if (!empty($rowDatos['Dat_Retira'])) $PerRetira = $rowDatos['Dat_Retira']; else $PerRetira = "No cargado";
		}
		$Tutor = utf8_decode(obtenerTutor($row['Per_ID'], $DNITutor, $PerIDTutor));		
		$DNI = $row['Per_DNI'];
		$Alumno = "$row[Per_Apellido], $row[Per_Nombre]";
		$Alumno = utf8_decode($Alumno);
		$Curso = "$row[Cur_Siglas] '$row[Div_Siglas]'";				
			
		$listado = array('DNI'=>$DNI, 'Alumno'=>$Alumno, 'Curso'=>$Curso, 'Nac'=>$Nac, 'Dom'=>$Dom, 'Tel'=>$Tel, 'Tutor'=>$Tutor, 'PerRetira'=>$PerRetira);				
		$data[] = $listado;	
		
		
	}//fin while
			
			$pdf->ezText("\nTOTAL ALUMNOS: <b>".$cant."</b>\n", $fontSize, array('justification'=>'left'));
			//$data[] = $listado;
			//$pdf->ezTable($data,array('DNI'=>'DNI', 'Alumno'=>'Alumno', 'Curso'=>'Curso/Div.', 'Nac'=>'Fecha Nac.', 'Dom'=>'Domicilio', 'Tel'=>'Teléfonos', 'Tutor'=>'Padre/Madre'),'Titulo', array('maxWidth'=>550, 'showLines'=>2, 'fontSize' => 8, 'cols'=>array('Ingreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Egreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Saldo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));	
			$pdf->ezTable($data,array('DNI'=>'DNI', 'Alumno'=>'Alumno', 'Curso'=>'Curso/Div.', 'Nac'=>'Fecha Nac.', 'Dom'=>'Domicilio', 'Tel'=>'Teléfonos', 'Tutor'=>'Padre/Madre', 'PerRetira'=>'Retira'),'', array('maxWidth'=>750, 'showLines'=>2, 'fontSize' => 8, 'shaded' => 0 ));	
			//, 'cols'=>array('Ingreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Egreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Saldo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'))
			//Importe total
			
			$pdf->ezText("\nTOTAL ALUMNOS: <b>".$cant."</b>", $fontSize, array('justification'=>'left'));

}//del if 
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL REPORTE</b>");
	$pdf->addText(50,750,9,"Motivo: El número emitido no es válido");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>