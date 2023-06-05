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
$Caja_Apertura=$row['Caja_Apertura'];
$Caja_Cierre=$row['Caja_Cierre'];
$Caja_Rendida=$row['Caja_Rendida'];
$Caja_Anulada=$row['Caja_Anulada'];
$Caja_Observaciones=$row['Caja_Observaciones'];
$Caja_Usu_ID=$row['Caja_Usu_ID'];
$Caja_Importe_Total=$row['Caja_Importe_Total'];
$Usuario=$row['Usu_Persona'];

if (empty($Caja_Cierre)) $Caja_Cierre= "Caja sin cerrar"; else $Caja_Cierre=cfecha(substr($Caja_Cierre,0,10)).substr($Caja_Cierre,10,10);

if (mysqli_num_rows($result)>0) $imprimir=true;else $imprimir=false;

error_reporting(E_ALL);

require('fpdf/fpdf.php');

class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;

// Cabecera de página
function Header()
{
    // Logo
    include_once 'globales.php';
    $ruta_images = "logos/logo_cesap.png";
    $this->Image($ruta_images,10,8,10);
    // Arial bold 15
    //$this->SetFont('Arial','B',15);
    // Movernos a la derecha
    //$this->Cell(80);
    // Título
    //$this->Cell(30,10,'Title',1,0,'C');
    // Salto de línea
    //$this->Ln(20);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    //$this->SetY(10);
    // Arial italic 8
    $this->SetFont('Arial','',6);
    // Número de página
    $this->Cell(0,10,'Caja Nº '.$_GET['id'].' - '.utf8_decode('Página ').$this->PageNo(),0,0,'C');
    $this->SetFont('Arial','',6);
    $this->Cell(0,10,utf8_decode('Fecha de impresión: ').date("d/m/Y"),0,0,'R');
    //$this->Cell(0,100,'xxxxxxxxxxxxxxxxx',1,0,'C');
}

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}//fin de la extension de la clase


//$pdf = new FPDF('P','mm','A4');
$pdf = new PDF_MC_Table('P','mm','A4');

//$pdf->AliasNbPages();
$pdf->SetTitle("PLANILLA DE CAJA DIARIA");
//$pdf->SetMargins(0.5, 0.5);
$pdf->SetAutoPageBreak(true);

$pdf->SetFont('Arial','',10);


//$imprimir = true;
if ($imprimir){  


	$pdf->AddPage();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(190,4,'RESUMEN DE CAJA DIARIA Nº '.$Caja_ID,0,1,'C');	
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(190,4,'Fecha de Apertura: '.cfecha(substr($Caja_Apertura,0,10)).substr($Caja_Apertura,10,10),0,1,'R');
	$pdf->Cell(190,4,'Fecha de Cierre: '.$Caja_Cierre,0,1,'R');
	$pdf->Cell(190,4,'Usuario: '.$Usuario,0,1,'R');

	
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
	}//fin if
		
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

    			$listado = array('Fecha'=>$Fecha, 'Hora'=>$Hora, 'Concepto'=>utf8_decode($CCC_Concepto), 'Ingreso'=>number_format($CCC_Credito,2,",","."), 'Egreso'=>number_format($CCC_Debito,2,",","."), 'Saldo'=>number_format($CCC_Saldo,2,",","."), 'Detalle'=>$CCC_Detalle, 'FormaPago'=>$FormaPago);				
    			$data[] = $listado;	

    			$debito += $CCC_Debito;
    			$debitoUltimo = $CCC_Debito;
    			$credito += $CCC_Credito;
    			$creditoUltimo = $CCC_Credito;
    			for ($iA=0;$iA<count($arreFormaPago);$iA++){
					/*if ($arreFormaPago[$iA]['For_Nombre']==$FormaPago){
						$arreFormaPago[$iA]['Ingreso'] += $CCC_Credito;
						$arreFormaPago[$iA]['Egreso'] += $CCC_Debito;
					}*/
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
			//$pdf->ezText("\nSALDO TOTAL: <b>$".$importeTotal."</b>", $fontSize, array('justification'=>'left'));
			$pdf->Cell(190,4,'SALDO TOTAL: $'.$importeTotal,0,1,'C');
		}else{
			//$pdf->ezText("\nIMPORTE RECAUDADO: <b>$".number_format($Caja_Importe_Total,2,",",".")."</b>", $fontSize, array('justification'=>'left'));
			$pdf->Cell(190,4,'IMPORTE RECAUDADO: $'.number_format($Caja_Importe_Total,2,",","."),0,1,'C');
		}
		$pdf->Ln(5);

		
		//$saldoUnificado = $credito - $debito;
		$saldoUnificado = number_format($saldoUnificado,2,",",".");
		$listado = array('Fecha'=>"", 'Concepto'=>"TOTALES", 'Ingreso'=>$credito, 'Egreso'=>$debito, 'Saldo'=>$saldoUnificado, 'Detalle'=>"", 'FormaPago'=>"");			
		
		//Comentado para que calcule bien el saldo
		//$listado = array('Fecha'=>"", 'Concepto'=>"TOTALES", 'Ingreso'=>$credito, 'Egreso'=>$debito, 'Saldo'=>$importeTotalSinRendir, 'Detalle'=>"", 'FormaPago'=>"");				
		$data[] = $listado;
		
		$total_filas = count($data);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(190,4,"MOVIMIENTOS DIARIOS",0,1,'C');
		$pdf->SetFont('Arial','',8);

		$pdf->SetWidths(array(12,12,50,50,50));

		for ($i=0; $i < $total_filas; $i++) { 
			$pdf->SetFont('Arial','',6);
			$pdf->Row(array($data[$i]['Fecha'], $data[$i]['Hora'], $data[$i]['Concepto'],$data[$i]['Ingreso'], $data[$i]['Detalle']));
			/*
			$pdf->Cell(12,4,$data[$i]['Fecha'],1,0,'L');
			$pdf->Cell(12,4,$data[$i]['Hora'],1,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(50,4,$data[$i]['Concepto'],1,0,'L');
			$pdf->Cell(50,4,$data[$i]['Ingreso'],1,0,'L');
			$pdf->Cell(50,4,$data[$i]['Detalle'],1,1,'L');
			*/
		}

		/*$pdf->ezTable($data,array('Fecha'=>'<b>Fecha Hora</b>', 'Concepto'=>'<b>Concepto</b>', 'Ingreso'=>'<b>Ingreso</b>', 'Egreso'=>'<b>Egreso</b>', 'Saldo'=>'<b>Saldo</b>', 'Detalle'=>'<b>Detalle</b>', 'FormaPago'=>'<b>Forma Pago</b>'),'MOVIMIENTOS DIARIOS', array('maxWidth'=>550, 'showLines'=>2, 'fontSize' => 8, 'cols'=>array('Ingreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Concepto'=>array('justification'=>'left', 'xPos' => 'center', 'width'=>'200'), 'Egreso'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Saldo'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));	
		//Importe total
		$pdf->ezText("\n", $fontSize, array('justification'=>'left'));*/

		$data = array();
		
		$listado = array('CAMPO1'=>"", 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'--------------------------');				
		$data[] = $listado;
		$listado = array('CAMPO1'=>"", 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'Firma Responsable Caja');				
		$data[] = $listado;
		$listado = array('CAMPO1'=>'<b>TIPOS DE CUOTA</b>', 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'');				
		$data[] = $listado;
		$sql = "SELECT CTi_Nombre, SUM(CRe_Ingreso)AS Suma FROM CajaReporte INNER JOIN CuotaTipo ON (CRe_CTi_ID = CTi_ID) WHERE CRe_Sesion='$sesion' GROUP BY CRe_Sesion, CRe_Cti_ID;";
		$resultado = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($resultado) > 0) {
			while ($row = mysqli_fetch_array($resultado)) {
				$listado = array('CAMPO1'=>"INGRESOS por ".utf8_decode($row['CTi_Nombre']), 'CAMPO2'=>$row['Suma'], 'CAMPO3'=>'', 'CAMPO4'=>'');				
				$data[] = $listado;
			}
		}
		$listado = array('CAMPO1'=>'', 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'--------------------------');				
		$data[] = $listado;
		$listado = array('CAMPO1'=>'<b>FORMAS DE PAGO</b>', 'CAMPO2'=>'', 'CAMPO3'=>'', 'CAMPO4'=>'Firma Supervisor');				
		$data[] = $listado;
		for ($iA=0;$iA<count($arreFormaPago);$iA++){
			
			if ($arreFormaPago[$iA]['Ingreso']>0){
				$listado = array('CAMPO1'=>"INGRESOS en ".utf8_decode($arreFormaPago[$iA]['For_Nombre']), 'CAMPO2'=>number_format($arreFormaPago[$iA]['Ingreso'],2,",","."), 'CAMPO3'=>'', 'CAMPO4'=>'');				
				$data[] = $listado;
			}
			if ($arreFormaPago[$iA]['Egreso']>0){
				$listado = array('CAMPO1'=>"EGRESOS en ".utf8_decode($arreFormaPago[$iA]['For_Nombre']), 'CAMPO2'=>number_format($arreFormaPago[$iA]['Egreso'],2,",","."), 'CAMPO3'=>'', 'CAMPO4'=>'');				
				$data[] = $listado;
			}				
		}
		$total_filas = count($data);
		$pdf->Cell(190,4,"TOTALES DISCRIMINADOS",0,1,'C');
		for ($i=0; $i < $total_filas; $i++) { 
			$pdf->Cell(50,4,$data[$i]['CAMPO1'],0,0,'C');
			$pdf->Cell(50,4,$data[$i]['CAMPO2'],0,0,'C');
			$pdf->Cell(50,4,$data[$i]['CAMPO3'],0,0,'C');
			$pdf->Cell(50,4,$data[$i]['CAMPO4'],0,1,'C');
		}
		//$pdf->ezText("\nSALDO TOTAL: <b>$".$importeTotal."</b>", $fontSize, array('justification'=>'left'));
		/*$pdf->ezTable($data,array('CAMPO1'=>'CAMPO1', 'CAMPO2'=>'<b>CAMPO2</b>', 'CAMPO3'=>'<b>CAMPO3</b>', 'CAMPO4'=>'<b>CAMPO4</b>'),'TOTALES DISCRIMINADOS', array('maxWidth'=>550, 'showLines'=>0, 'showHeadings'=>0, 'fontSize' => 10, 'shaded' => 0,'cols'=>array('CAMPO1'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'CAMPO2'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'CAMPO4'=>array('justification'=>'center', 'xPos' => 'center', 'xOrientation'=>'center')) ));*/
		
		//print_r($arreFormaPago);
		//print_r($arreTipoCuota);
	}//fin if
	//*/
	//Creamos y Mostramos los asientos ingreso de caja	
	guardarAsientoCajaCorriente($Caja_ID);
	$data = array();
	$sql = "SELECT * FROM AsientoCaja WHERE AsC_Caja_ID = $Caja_ID AND AsC_Tipo='ingreso' ORDER BY AsC_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		$listado = array('Concepto'=>utf8_decode($row['AsC_Concepto']), 'Debe'=>'$'.number_format($row['AsC_Debe'],2,",","."), 'Haber'=>'$'.number_format($row['AsC_Haber'],2,",","."));				
		$data[] = $listado;
	}
	$total_filas = count($data);
	$pdf->Cell(190,4,"ASIENTOS DE INGRESOS A CAJA DIARIA",0,1,'C');
	for ($i=0; $i < $total_filas; $i++) { 
		$pdf->Cell(80,4,$data[$i]['Concepto'],0,0,'C');
		$pdf->Cell(50,4,$data[$i]['Debe'],0,0,'C');
		$pdf->Cell(50,4,$data[$i]['Haber'],0,1,'C');
	}
	/*$pdf->ezTable($data,array('Concepto'=>'Concepto', 'Debe'=>'<b>Debe</b>', 'Haber'=>'<b>Haber</b>'),'ASIENTOS DE INGRESOS A CAJA DIARIA', array('maxWidth'=>550, 'showLines'=>1, 'showHeadings'=>0, 'fontSize' => 10, 'shaded' => 0,'cols'=>array('Concepto'=>array('justification'=>'left', 'xPos' => 'center', 'xOrientation'=>'center'), 'Debe'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center'), 'Haber'=>array('justification'=>'right', 'xPos' => 'center', 'xOrientation'=>'center')) ));*/

}else{
	//muestro un mensaje que no se puede mostrar esta boleta porque faltan datos
	echo "<b>NO SE PUEDE IMPRIMIR EL RESUMEN DE LA CAJA DIARIA</b><br>";
	echo "Motivo: El número de caja no es válido";

	//escribe el 0800
	//$pdf->addText(40,720,9,"<i>Comunicarse sin cargo al <b>0800-2222-822</b> de lunes a viernes de 8:00 a 12:00 hs. y de 16:00 a 22:00 hs. y sábados de 8:00 a 12:00 hs.</i>");
}  

$pdf->Output();

?>