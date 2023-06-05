<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Imprimir Cuota</title>
</head>

<body>
<?php
include_once('clase_pdf/class.ezpdf.php');
$pdf =& new Cezpdf('A4');
$pdf->selectFont('clase_pdf/fonts/Helvetica.afm');
$pdf->ezText('Hola Mundo $cuota1!',50);
$pdf->ezStream();



/*

//Aqui quito caracteres que estan demas en el filtro que viene de otra pagina
$sqlr = str_replace("`", "", $sqlr);
$sqlr = str_replace("\'", '"', $sqlr);

require('qs_functions.php');  //esta funcion la pueden quitar, solo es para el formato de las fechas
error_reporting(E_ALL);
include('class.ezpdf.php');

$pdf = & new Cezpdf('letter','landscape');
$pdf->selectFont('./fonts/Helvetica');
// Se inicializa el contador de paginas en 1 y se especifica en que lugar se va a imprimir
$pdf->ezStartPageNumbers(500,18,10,'','Pagina : {PAGENUM} de {TOTALPAGENUM}',1);

// coloca una linea arriba y abajo de todas las paginas
$fechs = date("d/m/y");
$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(20,30,750,30);
$pdf->line(20,585,750,585);
$pdf->addText(20,590,10,'Industrial Mexicana, SA de CV - Control de Contratos');
$pdf->addText(650,590,10,'Depto. Finanzas');
$pdf->addText(20,18,10,$fechs);
$pdf->restoreState();
$pdf->closeObject();
// termina las lineas
$pdf->addObject($all,'all');
//--------
//
$host = 'localhost';
$user = 'root';
$password = '';

$database = 'indmex';
// El siguiente query utiliza un filtro que viene de otra pagina
//$query = 'select producto,contrato,cliente,fechareg,fechacomp,tipo_cte,importe,oficina,anticipo,fechaant,factura_ant,pagado,fecha_pag,factura_pag,fecha_lib,vendedor from contratos where '.$sqlr.' order by producto,contrato';
// Este query esta sin filtro y va a utilizar todos los registros de la BD
$query = 'select producto,contrato,cliente,fechareg,fechacomp,tipo_cte,importe,oficina,anticipo,fechaant,factura_ant,pagado,fecha_pag,factura_pag,fecha_lib,vendedor from contratos order by producto,contrato';
//--------

// abrir la conexion
$link = mysql_connect($host,$user,$password);
//cambio de database
mysql_select_db($database);
//inicializa array
$data = array();
// hacer query
$result = mysql_query ($query ) or die (mysql_error());
//
//Aqui se coloca el header de la Tabla
$cols = array('producto'=>'Prod',
              'contrato'=>'Contrato',
              'cliente'=>'Cliente',
              'fechareg'=>'Registro',
              'fechacomp'=>'Compromiso',
              'tipo_cte'=>'Tipo Cte',
              'importe'=>'Importe',
              'oficina'=>'Ofna',
              'anticipo'=>'Anticipo',
              'fechaant'=>'Fech.Ant',
              'factura_ant'=>'Fact Ant',
              'pagado'=>'Pagado',
              'fecha_pag'=>'Fech.Pag',
              'factura_pag'=>'Fact Pag',
              'fecha_lib'=>'Fech Lib',
              'vendedor'=>'Vend');
//
$smc = 0;
$tim = 0;  // Total del Importe
$tan = 0;  // Total Anticipo
while ($row = mysql_fetch_row($result)) {
    $pro = $row[0];    // Producto
    $con = $row[1];    // Contrato
    $cte = $row[2];    // Cliente
    if ($row[3]=="0000-00-00") {$fhr = " "; }  // Fecha Registro
    else {$fhr = "" . date("d/m/y",  qs_string_to_timestamp($row[3])) . ""; }
    if ($fhr == "") {$fhr = " "; }
    if ($row[4]=="0000-00-00") {$fhc = " "; }  //Fecha Compromiso
    else {$fhc = "" . date("d/m/y",  qs_string_to_timestamp($row[4])) . ""; }
    if ($fhc == "") {$fhc = " "; }
    $tct = $row[5];    // Tipo de Cliente
    $tim = $tim + $row[6];
    $imp = "" . number_format($row[6],2,".",",") . "";  // Importe
    if ($imp == "") {$imp = " ";}
    $ofn = $row[7];  //Oficina
    $tan = $tan + $row[8];
    $ant = "" . number_format($row[8],2,".",",") . "";  // Anticipo
    if ($row[9]=="0000-00-00") {$fan = " "; }  // Fecha Registro
    else {$fan = "" . date("d/m/y",  qs_string_to_timestamp($row[9])) . ""; }
    $fca = $row[10]; // Factura Anticipo
    $pag = $row[11]; // Pagado
    if ($row[12]=="0000-00-00") {$ffp = " "; }  // Fecha Registro
    else {$ffp = "" . date("d/m/y",  qs_string_to_timestamp($row[12])) . ""; }
    $fap = $row[13]; // Factura Anticipo
    if ($row[14]=="0000-00-00") {$flb = " "; }  // Fecha Registro
    else {$flb = "" . date("d/m/y",  qs_string_to_timestamp($row[14])) . ""; }
    $vnd = $row[15]; // Vendedor
    $smc = $smc + 1;
    // Aqui se agregan las variables formateadas al array
    $data[] = array('producto'=>$pro,
                    'contrato'=>$con,
                    'cliente'=>$cte,
                    'fechareg'=>$fhr,
                    'fechacomp'=>$fhc,
                    'tipo_cte'=>$tct,
                    'importe'=>$imp,
                    'oficina'=>$ofn,
                    'anticipo'=>$ant,
                    'fechaant'=>$fan,
                    'factura_ant'=>$fca,
                    'pagado'=>$pag,
                    'fecha_pag'=>$ffp,
                    'factura_pag'=>$fap,
                    'fecha_lib'=>$flb,
                    'vendedor'=>$vnd);
}
// Se agrega una linea en blanco como separador de datos y totales
    $data[] = array('producto'=>'',
                    'contrato'=>'',
                    'cliente'=>'',
                    'fechareg'=>'',
                    'fechacomp'=>'',
                    'tipo_cte'=>'',
                    'importe'=>'',
                    'oficina'=>'',
                    'anticipo'=>'',
                    'fechaant'=>'',
                    'factura_ant'=>'',
                    'pagado'=>'',
                    'fecha_pag'=>'',
                    'factura_pag'=>'',
                    'fecha_lib'=>'',
                    'vendedor'=>'');
$nreg = 'Numero de Registros : '.$smc ;
$timp = "" . number_format($tim,2,".",",") . "";  // Importe
$tan = "" . number_format($tan,2,".",",") . "";  // Importe
// Se agrega la linea que contiene los totales de Registros, Importe y Anticipo
    $data[] = array('producto'=>'',
                    'contrato'=>'',
                    'cliente'=>$nreg,
                    'fechareg'=>'',
                    'fechacomp'=>'',
                    'tipo_cte'=>'',
                    'importe'=>$timp,
                    'oficina'=>'',
                    'anticipo'=>$tan,
                    'fechaant'=>'',
                    'factura_ant'=>'',
                    'pagado'=>'',
                    'fecha_pag'=>'',
                    'factura_pag'=>'',
                    'fecha_lib'=>'',
                    'vendedor'=>'');
$pdf->ezTable($data,$cols,'',array('fontSize'=>6,
'cols'=>array(
                'producto'=>array('justification'=>'center')
                ,'contrato'=>array('justification'=>'left')
                ,'cliente'=>array('justification'=>'left')
                ,'fechareg'=>array('justification'=>'center')
                ,'fechacomp'=>array('justification'=>'center')
                ,'tipo_cte'=>array('justification'=>'center')
                ,'importe'=>array('justification'=>'right')
                ,'oficina'=>array('justification'=>'center')
                ,'anticipo'=>array('justification'=>'right')
                ,'fechaant'=>array('justification'=>'center')
                ,'factura_ant'=>array('justification'=>'left')
                ,'pagado'=>array('justification'=>'center')
                ,'fecha_pag'=>array('justification'=>'center')
                ,'factura_pag'=>array('justification'=>'left')
                ,'fecha_lib'=>array('justification'=>'center')
                ,'vendedor'=>array('justification'=>'center'))
));// salida
//
//
if (isset($d) && $d){
    $pdfcode = $pdf->ezOutput();
    $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));
    echo '<html><body>';
    echo trim($pdfcode);
    echo '</body></html>';
} else {
    $pdf->ezStream();
} 
//*/
?>
</body>
</html>
