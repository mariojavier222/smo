<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");
$sesion = md5(uniqid(rand()));

$sql = "SET NAMES UTF8;";
//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$Caja_ID = $_GET['id'];

$sql = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja_Usu_ID = Usu_ID) WHERE Caja_ID = $Caja_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);

//$Caja_ID=$row[Caja_ID];
$Caja_Apertura=$row[Caja_Apertura];
$Caja_Cierre=$row[Caja_Cierre];
$Caja_Rendida=$row[Caja_Rendida];
$Caja_Anulada=$row[Caja_Anulada];
$Caja_Observaciones=$row[Caja_Observaciones];
$Caja_Usu_ID=$row[Caja_Usu_ID];
$Caja_Importe_Total=$row[Caja_Importe_Total];
$Usuario=$row['Usu_Persona'];

if (empty($Caja_Cierre)) $Caja_Cierre= "Caja sin cerrar"; else $Caja_Cierre=cfecha(substr($Caja_Cierre,0,10)).substr($Caja_Cierre,10,10);

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


$i = 1;
//$imprimir = false;
//si viene el DNI imprimo
if ($imprimir){  

	$total = $i;
	//Imprimimos el encabezado
	$pdf->ezSetY(810);
	$pdf->setLineStyle(1,'','',array(1,0));	
	//Title, Author, Subject, Keywords, Creator, Producer, CreationDate, ModDate, Trapped
	$datacreator = array (
		'Title'=>"PLANILLA DE CAJA DIARIA",
		'Subject'=>'RESUMEN DE MOVIMIENTOS DIARIOS',
		'Author'=>'COLEGIO PARROQUIAL SANTA LUCIA',
					'Creator'=>'DOCMEDIA',//D:20150818031116-05'00' His
					'CreationDate'=>"D:".date("Ymd03is-H")."'00'",
					'Producer'=>'http://www.colegioparroquialsl.edu.ar'
				);
	$pdf->addInfo($datacreator);
	$pdf->ezStartPageNumbers(50,20,8,'right',utf8_decode("Caja Nº $Caja_ID de $Usuario - <i>Página {PAGENUM} de {TOTALPAGENUM}</i>"),1);
	$pdf->ezStartPageNumbers(50,815,8,'right',utf8_decode("Caja Nº $Caja_ID de $Usuario - <i>Página {PAGENUM} de {TOTALPAGENUM}</i>"),1);
	$pdf->ezSetMargins(30,30,50,50);//ezSetMargins(top,bottom,left,right)

	
	$yArriba = 810;
	//Texto arriba
	$ruta_images = "logos/logo_cpsl.jpg";
	$pdf->ezSetY($yArriba);
	$pdf->ezImage($ruta_images,10,60,'none','right');
	
	$pdf->ezSetY($yArriba);
	$fontSize = 14;

	$pdf->ezText("<b><c:uline>RESUMEN DE CAJA DIARIA\n</c:uline></b>", $fontSize, array('justification'=>'center'));
	$fontSize = 10;
	$pdf->ezText(utf8_decode("<b>CAJA DIARIA Nº $Caja_ID</b>"), $fontSize, array('justification'=>'left'));
	$pdf->ezText("<c:uline>Fecha de Apertura</c:uline>: <b>".cfecha(substr($Caja_Apertura,0,10)).substr($Caja_Apertura,10,10)."</b>", $fontSize, array('justification'=>'left'));

	$pdf->ezText("<c:uline>Fecha de Cierre</c:uline>: <b>$Caja_Cierre</b>", $fontSize, array('justification'=>'left'));
	$pdf->ezText("<c:uline>Usuario</c:uline>: <b>$Usuario</b>", $fontSize, array('justification'=>'left'));
	
	//Armamos un arreglo con las formas de pago y los tipos de Cuotas
	$arreTipoCuota=array();
	$arreFormaPago=array();
	$sql = "SELECT DISTINCTROW For_ID, For_Nombre  FROM CajaCorriente INNER JOIN FormaPago 
	ON (CCC_For_ID = For_ID) WHERE CCC_Caja_ID = $Caja_ID ORDER BY CCC_ID";
	$resultArreglo = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($resultArreglo) > 0) {
		while ($rowA = mysqli_fetch_array($resultArreglo)){
			$arreFormaPago[] = array('For_ID'=>$rowA['For_ID'], 'For_Nombre'=>$rowA['For_Nombre'], 'Ingreso'=>0, 'Egreso'=>0, 'Caja_Rendida'=>0);
			
		}//fin while
	}

	$sql_1 = "SELECT * FROM CajaCorriente INNER JOIN FormaPago 
	ON (CCC_For_ID = For_ID) WHERE CCC_Caja_ID = $Caja_ID";
	$resultado = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($resultado) > 0) {
		$debito = 0;
		$credito = 0;
		$debitoUltimo = 0;
		$creditoUltimo = 0;
		while ($row1 = mysqli_fetch_array($resultado)) {

			$CCC_ID=$row1['CCC_ID'];
			$CCC_Caja_ID=$row1['CCC_Caja_ID'];
			$CCC_Concepto=$row1['CCC_Concepto'];

			$CCC_Debito = $row1['CCC_Debito'];
			$CCC_Credito = $row1['CCC_Credito'];
			$CCC_Saldo = $row1['CCC_Saldo'];

			$Fecha=cfecha($row1['CCC_Fecha']);
			$Hora=$row1['CCC_Hora'];
			$CCC_Detalle=utf8_decode($row1['CCC_Detalle']);

			$FormaPago=$row1['For_Nombre'];
			$CCC_Referencia=$row1['CCC_Referencia'];
				
			if ($CCC_Debito != $CCC_Credito){	
				
				$listado = array('Fecha'=>"$Fecha $Hora", 'Concepto'=>utf8_decode($CCC_Concepto), 'Ingreso'=>number_format($CCC_Credito,2,",","."), 'Egreso'=>number_format($CCC_Debito,2,",","."), 'Saldo'=>number_format($CCC_Saldo,2,",","."), 'Detalle'=>$CCC_Detalle, 'FormaPago'=>$FormaPago);				
				$data[] = $listado;	

				$debito += $CCC_Debito;
				$debitoUltimo = $CCC_Debito;
				$credito += $CCC_Credito;
				$creditoUltimo = $CCC_Credito;
				for ($iA=0;$iA<count($arreFormaPago);$iA++){
					if ($arreFormaPago[$iA]['For_Nombre']==$FormaPago){
						$arreFormaPago[$iA]['Ingreso'] += $CCC_Credito;						
						if (substr($CCC_Concepto, 0, 12)=="CAJA RENDIDA"){
							$arreFormaPago[$iA]['Caja_Rendida'] += $CCC_Debito;
						}else{
							$arreFormaPago[$iA]['Egreso'] += $CCC_Debito;
						}
					}
				}
			}
			
			if (!empty($CCC_Referencia)){
				list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(';', $CCC_Referencia);
				$sql = "INSERT INTO CajaReporte (CRe_Sesion, CRe_Cti_ID, CRe_Ingreso) VALUES('$sesion', '$Cuo_CTi_ID', '$CCC_Credito')";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			}

			if (substr($CCC_Concepto, 0, 12)=='CAJA RENDIDA'){
				$debito -= $debitoUltimo;
			}
				
				
		}//fin while
		
		$importeTotal = $credito - $debito;
		$creditoSinRendir = $credito - $creditoUltimo;
		$debitoSinRendir = $debito - $debitoUltimo;
		$importeTotalSinRendir = $creditoSinRendir - $debitoSinRendir;
		
		$importeTotal = number_format($importeTotal,2,",",".");
		$importeTotalSinRendir = number_format($importeTotalSinRendir,2,",",".");
		$saldoUnificado = $credito - $debito;
		$credito = number_format($credito,2,",",".");
		$debito = number_format($debito,2,",",".");
		if ($Caja_Cierre=="Caja sin cerrar"){
			$pdf->ezText("\nSALDO TOTAL: <b>$".$importeTotal."</b>", $fontSize, array('justification'=>'left'));
		}else{
			$pdf->ezText("\nIMPORTE RECAUDADO: <b>$".number_format($Caja_Importe_Total,2,",",".")."</b>", $fontSize, array('justification'=>'left'));
		}
		$saldoUnificado = number_format($saldoUnificado,2,",",".");
		
		$listado = array('Fecha'=>"", 'Concepto'=>"TOTALES", 'Ingreso'=>$credito, 'Egreso'=>$debito, 'Detalle'=>"", 'FormaPago'=>"");
		$data[] = $listado;

		$dataTotalesDiscriminados = array();
		
		$listado = array('CAMPO1'=>'<b>TIPOS DE CUOTA</b>', 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'');				
		$dataTotalesDiscriminados[] = $listado;
		$sql = "SELECT CTi_Nombre, SUM(CRe_Ingreso)AS Suma FROM CajaReporte INNER JOIN CuotaTipo ON (CRe_CTi_ID = CTi_ID) WHERE CRe_Sesion='$sesion' GROUP BY CRe_Sesion, CRe_Cti_ID;";
		$resultado = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($resultado) > 0) {
			while ($row = mysqli_fetch_array($resultado)) {
				$listado = array('CAMPO1'=>utf8_decode($row['CTi_Nombre']), 'CAMPO2'=>$row['Suma'], 'CAMPO3'=>'', 'CAMPO4'=>'');				
				$dataTotalesDiscriminados[] = $listado;
			}
		}
		$listado = array('CAMPO1'=>'', 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'      ');				
		$dataTotalesDiscriminados[] = $listado;

		$dataFormaPago = array();

		$listado = array('CAMPO1'=>'<b>FORMAS DE PAGO</b>', 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'');				
		$dataTotalesDiscriminados[] = $listado;
		$dataFormaPago[] = $listado;
		for ($iA=0;$iA<count($arreFormaPago);$iA++){
			
			if ($arreFormaPago[$iA]['Ingreso']>0){
				$listado = array('CAMPO1'=>utf8_decode($arreFormaPago[$iA]['For_Nombre']), 'CAMPO2'=>number_format($arreFormaPago[$iA]['Ingreso'],2,",","."), 'CAMPO3'=>'', 'CAMPO4'=>'');				
				$dataTotalesDiscriminados[] = $listado;
				$dataFormaPago[] = $listado;
			}
			if ($arreFormaPago[$iA]['Egreso']>0){
				$listado = array('CAMPO1'=>"EGRESOS en ".utf8_decode($arreFormaPago[$iA]['For_Nombre']), 'CAMPO2'=>number_format($arreFormaPago[$iA]['Egreso'],2,",","."), 'CAMPO3'=>'', 'CAMPO4'=>'');				
				$dataTotalesDiscriminados[] = $listado;
				$dataFormaPago[] = $listado;
			}				
		}

		$pdf->ezText("\n", $fontSize, array('justification'=>'left'));


		$pdf->ezTable($dataTotalesDiscriminados,array('CAMPO1'=>'CAMPO1', 'CAMPO2'=>'<b>CAMPO2</b>', 'CAMPO3'=>'<b>CAMPO3</b>', 'CAMPO4'=>'<b>CAMPO4</b>'),'TOTALES DISCRIMINADOS', array('maxWidth'=>550, 'showLines'=>0, 'showHeadings'=>0, 'fontSize' => 10, 'shaded' => 0,'cols'=>array('CAMPO1'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'CAMPO2'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'CAMPO4'=>array('justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));

		if ($Caja_Cierre=="Caja sin cerrar"){
			$pdf->ezText("\nIMPORTE TOTAL: <b>$".$importeTotal."</b>", $fontSize, array('justification'=>'left'));
		}else{
			$pdf->ezText("\nIMPORTE TOTAL RECAUDADO: <b>$".number_format($Caja_Importe_Total,2,",",".")."</b>", $fontSize, array('justification'=>'left'));
		}
		$pdf->ezText("", $fontSize, array('justification'=>'left'));

		//Listado mostrando los ingresos y egresos sin saldo
		$pdf->ezTable($data,array('Fecha'=>'<b>Fecha Hora</b>', 'Concepto'=>'<b>Concepto</b>', 'Ingreso'=>'<b>Ingresos</b>','Egreso'=>'<b>Egresos</b>', 'Detalle'=>'<b>Detalle</b>', 'FormaPago'=>'<b>Forma Pago</b>'),'MOVIMIENTOS DIARIOS', array('maxWidth'=>550, 'showLines'=>2, 'fontSize' => 8,'shaded'=>0, 'cols'=>array('Ingreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Concepto'=>array('justification'=>'left', 'xPos' => 'center', 'width'=>'200'), 'Egreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Saldo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));	

		//Importe total
		$pdf->ezText("\n", $fontSize, array('justification'=>'left'));
	}//fin if

/*
	//Creamos y Mostramos los asientos ingreso de caja	
	guardarAsientoCajaCorriente($Caja_ID);
	$data = array();
	$sql = "SELECT * FROM AsientoCaja WHERE AsC_Caja_ID = $Caja_ID AND AsC_Tipo='ingreso' ORDER BY AsC_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		$listado = array('Concepto'=>utf8_decode($row['AsC_Concepto']), 'Debe'=>'$'.number_format($row['AsC_Debe'],2,",","."), 'Haber'=>'$'.number_format($row['AsC_Haber'],2,",","."));				
		$data[] = $listado;
	}
	$pdf->ezTable($data,array('Concepto'=>'Concepto', 'Debe'=>'<b>Debe</b>', 'Haber'=>'<b>Haber</b>'),'ASIENTOS DE INGRESOS A CAJA DIARIA', array('maxWidth'=>550, 'showLines'=>1, 'showHeadings'=>0, 'fontSize' => 10, 'shaded' => 0,'cols'=>array('Concepto'=>array('justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'), 'Debe'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Haber'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));
*/
}//del if si viene el DNI imprimo
else {
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	$pdf->addText(40,770,10,"<b>NO SE PUEDE IMPRIMIR EL RESUMEN DE LA CAJA DIARIA</b>");
	$pdf->addText(50,750,9,"Motivo: El número emitido no es válido");

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
}  

$pdf->ezStream();

?>