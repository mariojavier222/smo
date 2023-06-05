<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

//if (isset($_GET['PerID'])){
	$LecID = gLectivoActual($LecNombre);
	$PerID=$_GET['PerID'];
	//$LecNombre = Obtener_LectivoNombre($LecID);
//}

//$sql = "SET NAMES UTF8;";
//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "SELECT * FROM Persona where Per_ID='$PerID'";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$imprimir=true;
	$row = mysqli_fetch_array($result);
	$Apellido=$row['Per_Apellido'];
	$Nombre=$row['Per_Nombre'];
	$DNI=$row['Per_DNI'];
}else $imprimir=false;


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

$i = 0;
//si viene el DNI imprimo
if ($imprimir){  

	$total = $i;
	$LegID=0;
	
	//busco los datos si es alumno
	$sql = "SELECT Leg_ID FROM Legajo WHERE Leg_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$LegID=$row['Leg_ID'];
	}

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	//si es alumno muestro curso y división en el ciclo lectivo
	if ($LegID>0){
		$sql = "SELECT * FROM Colegio_Inscripcion
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
		WHERE (Per_Baja = 0 AND Leg_Baja = 0 AND Colegio_Inscripcion.Ins_Lec_ID = $LecID AND Leg_ID = $LegID)
 ORDER BY Ins_Niv_ID, Ins_Cur_ID, Ins_Div_ID, Per_Apellido, Per_Nombre;";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			$Curso=utf8_decode($row['Cur_Nombre']);
			$Division=utf8_decode($row['Div_Nombre']);
			$Nivel=utf8_decode($row['Niv_Nombre']);
		}	
	}
	
	//echo $sql;

	//Imprimimos el encabezado
	$pdf->ezSetY(800);
	$pdf->setLineStyle(1,'','',array(1,0));	
	$datacreator = array (
                    'Title'=>"INFORME DE DATOS PERSONALES",
                    'Subject'=>'DATOS PERSONALES',
                    'Author'=>'NAPTA',
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
                    'Producer'=>'https://www.naptagestion.com.ar'
                    );
	$pdf->addInfo($datacreator);//*/
	//$pdf->ezStartPageNumbers(50,20,8,'right',"Plan de Pago Nº $PPa_ID - <i>Página {PAGENUM} de {TOTALPAGENUM}</i>",1);
	$pdf->ezSetMargins(30,30,50,50);//ezSetMargins(top,bottom,left,right)
	
	$yArriba = 810;
	//Texto arriba
	$PPa_Fecha = date("Y-m-d");
	
	$fontSize = 12;
	$ruta_images = "logos/".COLEGIO_LOGO;	
	$pdf->ezImage($ruta_images,10,75,'none','left');

	$pdf->ezText("<b>Ciclo Lectivo $LecNombre</b>", $fontSize, array('justification'=>'center'));
	$pdf->ezText("\n<b><c:uline>DATOS PERSONALES DEL ALUMNO</c:uline></b>", $fontSize, array('justification'=>'center'));
	$fontSize = 10;
		
	$pdf->ezText("\n\nAPELLIDO Y NOMBRE: <b>$Apellido, $Nombre</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("\nD.N.I. Nº: <b>$DNI</b>                       EDAD: <b>".obtenerEdad($PerID, $fechaNac)." años</b>", $fontSize, array('justification'=>'left'));

	$pdf->ezText("\nCURSO Y DIVISIÓN: <b>".$Curso." ".$Division."</b>                       NIVEL: <b>$Nivel</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("\nFECHA DE NACIMIENTO: <b>".$fechaNac."</b>                       LUGAR: <b>............................................................................</b>", $fontSize, array('justification'=>'left'));
	
	$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = $PerID";
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
	$pdf->ezText("\nTELÉFONO PARTICULAR: <b>$telefono</b> OTROS: <b>$Celular</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("\nEMAIL: <b>$Email</b>\n\n", $fontSize, array('justification'=>'left'));
	$pdf->ezText("<b>SITUACIÓN ECONÓMICA</b>\n", $fontSize, array('justification'=>'full'));

	$totalDeuda = 0;
	$sql = "SELECT * FROM CuotaPersona 
		INNER JOIN Lectivo 
			ON (Cuo_Lec_ID = Lec_ID)
		INNER JOIN Colegio_Nivel 
			ON (Cuo_Niv_ID = Niv_ID)
		INNER JOIN CuotaTipo 
			ON (Cuo_CTi_ID = CTi_ID)
		WHERE Cuo_Per_ID='$PerID' AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento<'".date("Y-m-d")."'";//echo $sql;exit;
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
	$texto = "\n\n\nLAS FIRMAS DEL PADRE - MADRE - TUTOR - DEBERÁN SER REGISTRADASEN PRESENCIA DE LA AUTORIDAD ESCOLAR.\n";
	//$pdf->ezText($texto, $fontSize, array('justification'=>'left'));
	$fontSize = 8;
	$texto = "\n\n\n\n\n\n------------------------------------------                                  ----------------------------------------                        ----------------------------------------\nFIRMA PADRE - MADRE - TUTOR                                             ACLARACIÓN                                                                D.N.I. Nº              .";
	
	$pdf->ezText($texto, $fontSize, array('justification'=>'right'));
	//$pdf->ezText("<b>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</b>\n", 14, array('justification'=>'full'));
	//if (mysqli_num_rows($result)>1)	$pdf->ezNewPage();
	//if ($i%2==0) $pdf->ezNewPage();

}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR ESTA PLANILLA</b>");
	$pdf->addText(50,750,9,"Motivo: No se encontraron datos!");
	}  

$pdf->ezStream();

?>