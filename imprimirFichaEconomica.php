<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

 	if (isset($_GET['PerID'])){
 		$LecID = gLectivoActual($LecNombre);
 		$where = " AND Per_ID=".$_GET['PerID'];
 	}else{
 		$LecID = $_GET['LecID'];
 		$LecNombre = Obtener_LectivoNombre($LecID);
		$CurID = $_GET['CurID'];
		$NivID = $_GET['NivID'];
		$DivID = $_GET['DivID'];
		$SedID = $_GET['SedID'];
		$where = "AND Leg_Sed_ID = $SedID";	
	    if ($CurID!=999999) $where.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
	    if ($NivID!=999999) $where.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
	    if ($DivID!=999999) $where.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
 	}
 	
 	

	
	
	$sql = "SET NAMES UTF8;";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Lectivo
    	ON (Ins_Lec_ID = Lec_ID)
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
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID AND Leg_Baja = 0 $where";
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Persona.Per_Sexo DESC, Per_Apellido, Per_Nombre;";
	//echo $sql;exit;
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
                    'Title'=>"INFORME DE DATOS PERSONALES",
                    'Subject'=>'DATOS PERSONALES',
                    'Author'=>'GATEWAY',
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'http://www.docmedia.com.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	//$pdf->ezStartPageNumbers(50,20,8,'right',"Plan de Pago N� $PPa_ID - <i>P�gina {PAGENUM} de {TOTALPAGENUM}</i>",1);
	$pdf->ezSetMargins(30,30,50,50);//ezSetMargins(top,bottom,left,right)
	
	$yArriba = 810;
	//Texto arriba
	$PPa_Fecha = date("Y-m-d");
	
while ($row = mysqli_fetch_array($result)){		

	$Per_ID = $row['Per_ID'];
	
	$i++;
	$fontSize = 12;
	$ruta_images = "logos/logo_cpsl_informe.jpg";
	$pdf->ezImage($ruta_images,10,200,'none','center');

	//$pdf->ezText("<b>Ciclo Lectivo ...............</b>", $fontSize, array('justification'=>'center'));
	$pdf->ezText("\n<b><c:uline>DATOS PERSONALES DEL ALUMNO</c:uline></b>", $fontSize, array('justification'=>'center'));
	$fontSize = 10;
	
	$pdf->ezText("\n\nAPELLIDO Y NOMBRE: <b>$row[Per_Apellido], $row[Per_Nombre]</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("\nD.N.I. N�: <b>$row[Per_DNI]</b>                       EDAD: <b>".obtenerEdad($row['Per_ID'], $fechaNac)." a�os</b>", $fontSize, array('justification'=>'left'));

	$Curso = "$row[Cur_Nombre] $row[Div_Nombre]";
	$Nivel = $row['Niv_Nombre'];

	$pdf->ezText("\nCURSO Y DIVISI�N: <b>".$Curso."</b>                       NIVEL: <b>$Nivel</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("\nFECHA DE NACIMIENTO: <b>".$fechaNac."</b>                       LUGAR: <b>............................................................................</b>", $fontSize, array('justification'=>'left'));
	$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = $row[Per_ID]";
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	$datos = "";
	if (mysqli_num_rows($result2)>0){		
		$rowDat = mysqli_fetch_array($result2);
		$pdf->ezText("\nDOMICILIO: <b>".$rowDat['Dat_Domicilio']."</b>", $fontSize, array('justification'=>'left'));
	}else{
		$pdf->ezText("\nDOMICILIO (Donde habita el alumno): <b>............................................................................</b>", $fontSize, array('justification'=>'left'));
	}
	if (!empty($rowDat['Dat_Telefono'])){
		$telefono = $rowDat['Dat_Telefono'];
	}else{
		$telefono = "......................................";
	}
	if (!empty($rowDat['Dat_Celular'])){
		$Celular = $rowDat['Dat_Celular'];
	}else{
		$Celular = "......................................";
	}
	if (!empty($rowDat['Dat_Email'])){
		$Email = $rowDat['Dat_Email'];
	}else{
		$Email = "............................................................................";
	}
	$pdf->ezText("\nTEL�FONO PARTICULAR: <b>$telefono</b> OTROS: <b>$Celular</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("\nEMAIL: <b>$Email</b>\n\n", $fontSize, array('justification'=>'left'));
	$pdf->ezText("<b>SITUACI�N ECON�MICA</b>\n", $fontSize, array('justification'=>'full'));
	
	$totalDeuda = 0;
	$sql = "SELECT * FROM CuotaPersona 
		INNER JOIN Lectivo 
			ON (Cuo_Lec_ID = Lec_ID)
		INNER JOIN Colegio_Nivel 
			ON (Cuo_Niv_ID = Niv_ID)
		INNER JOIN CuotaTipo 
			ON (Cuo_CTi_ID = CTi_ID)
		WHERE Cuo_Per_ID='$Per_ID' AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento<'".date("Y-m-d")."'";//echo $sql;exit;
		$resultDeuda = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
	 	if (mysqli_num_rows($resultDeuda) > 0) {
			$data = array();
			
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
			$recargo = obtenerRecargoCuota($rowDeuda['Cuo_Per_ID'], $datosCuota);
			$total = $importe + $recargo;
			$totalDeuda += $total;
			$importe = $importeActual;
			//$recargo = $recargoCuota;
				
			$listado = array('Concepto'=>"$concepto", 'Lectivo'=>$lectivo, 'Vencimiento'=>$vencimiento, 'Importe'=>number_format($importe,2,",","."), 'Recargo'=>number_format($recargo,2,",","."), 'Total'=>number_format($total,2,",","."));				
			$data[] = $listado;		
			}//fin while deuda cuotas
		}//fin if
	
	$pdf->ezTable($data,array('Concepto'=>'<b>Concepto adeudado</b>', 'Lectivo'=>'<b>Lectivo</b>', 'Vencimiento'=>'<b>Vencimiento</b>', 'Importe'=>'<b>Importe</b>', 'Recargo'=>'<b>Recargo</b>', 'Total'=>'<b>Total</b>'),'', array('maxWidth'=>550, 'showLines'=>2, 'fontSize' => 8, 'cols'=>array('Importe'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Recargo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Total'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));
	//$fontSize = 8;
	$pdf->ezText("DEUDA TOTAL: <b>$".number_format($totalDeuda,2,",",".")."</b>", $fontSize, array('justification'=>'centre'));

	//$fontSize = 8;		
	$texto = "\n\n\nLAS FIRMAS DEL PADRE - MADRE - TUTOR - DEBER�N SER REGISTRADASEN PRESENCIA DE LA AUTORIDAD ESCOLAR.\n";
	//$pdf->ezText($texto, $fontSize, array('justification'=>'left'));
	$fontSize = 8;
	$texto = "\n\n\n\n\n\n------------------------------------------                                  ----------------------------------------                        ----------------------------------------\nFIRMA PADRE - MADRE - TUTOR                                             ACLARACI�N                                                                D.N.I. N�              .";
	
	$pdf->ezText($texto, $fontSize, array('justification'=>'right'));
	//$pdf->ezText("<b>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</b>\n", 14, array('justification'=>'full'));
	if (mysqli_num_rows($result)>1)	$pdf->ezNewPage();
	//if ($i%2==0) $pdf->ezNewPage();

}//fin while

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR ESTA PLANILLA</b>");
	$pdf->addText(50,750,9,"Motivo: No se encontraron alumnos inscriptos");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y s�bados de 8:00 a 12:00 hs.</i>");
	}  

$pdf->ezStream();

?>