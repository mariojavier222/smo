<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

function importeAbonadoCuota($datosCuota){
	//$seguir = true;
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
		
	$sql = "SELECT SUM(CuP_Importe) AS Total FROM CuotaPago WHERE CuP_Lec_ID = $Cuo_Lec_ID AND CuP_Per_ID = $Cuo_Per_ID AND CuP_Niv_ID = $Cuo_Niv_ID AND CuP_CTi_ID=$Cuo_CTi_ID AND CuP_Alt_ID=$Cuo_Alt_ID AND CuP_Numero=$Cuo_Numero AND CuP_Anulada =0";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//if ($Cuo_CTi_ID==17) echo $sql;
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$importeAbonado = $row['Total'];
	}else{
		$importeAbonado = 0;
	}
	return $importeAbonado;
	
}//fin function

?>

	<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="js/ui.jqgrid.css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-sp.js" type="text/javascript"></script>
	<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;
	</script>

<script language="javascript">
$(document).ready(function(){

	
 	$(".botones").button();
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado',overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
		});
		
		$("#cargando").hide();
	 });//fin evento click//*/
 
 		
	$("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
    $('#listadoTabla2').dataTable( {
    "bPaginate": true,
    //"aaSorting": [[ 1, "asc" ]],
    "aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
    "bLengthChange": true,
    "bFilter": true,
    //"bSort": true,
    "bInfo": true,
    "bAutoWidth": true } );//*/
		
	
	
});//fin de la funcion ready

function fnExcelReport()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('listadoTabla'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"Guardar como archivo.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}

</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" >	
<br />
<br />
<?php

$sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $Lec_ID = $_POST['Lec_ID'];
    $sql = "SELECT Lec_Nombre FROM Lectivo WHERE Lec_ID = ".$Lec_ID.";";
		//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Anio = $row['Lec_Nombre'];
	}//fin if
	$Niv_ID = $_POST['NivID'];
    $CTi_ID = $_POST['CTi_ID'];
	$whereNivel = "";
	if ($Niv_ID!="999999") $whereNivel = " AND Cuo_Niv_ID = $Niv_ID";
	$whereCuota = "";
	if ($CTi_ID!="999999") $whereCuota = " AND Cuo_CTi_ID = $CTi_ID";

//SELECT DISTINCTROW Niv_Siglas, Cur_Siglas, Div_Siglas, Per_Apellido, Per_Nombre, Per_DNI, Per_ID	 
$sql = "SELECT * FROM
	CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
    INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID)
	INNER JOIN Legajo 
        ON (Per_ID = Leg_Per_ID)        
    INNER JOIN Colegio_Inscripcion
    	ON (Leg_ID = Ins_Leg_ID)    
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)        
     WHERE Cuo_Anio = $Anio AND Cuo_Lec_ID = Ins_Lec_ID $whereNivel $whereCuota ORDER BY Cuo_Anio, Cuo_Mes, Niv_ID, Cur_ID, Div_ID, Per_Apellido, Per_Nombre;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;exit;
?>
 
 <table width="100px" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Nº Comp</th>
          <th align="center" class="fila_titulo">Nivel/Curso/Div</th>          
          <th align="center" class="fila_titulo">Alumno</th>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="center" class="fila_titulo">Vigencia</th>
          <th align="center" class="fila_titulo">Vencimiento</th>          
          <th align="center" class="fila_titulo">Tipo de Cuota</th>
          <th align="center" class="fila_titulo">Beneficio</th>
          <th align="center" class="fila_titulo">Importe Original</th>
          <th align="center" class="fila_titulo">Importe Recalculado</th>          
          <th align="center" class="fila_titulo">Recargo</th>
          <th align="center" class="fila_titulo">Total</th>
          <th align="center" class="fila_titulo">Abonado</th>
          <th align="center" class="fila_titulo">Estado</th>
        </tr>
       </thead>
       <tbody>
	   <?php 
	   /*$acum = 0;
	   $acumAnulado = 0;
	   $acumCancelado = 0;
	   $acumPagado = 0;
	   $acumSinPagar = 0;*/
	   $totalGenerado=0;
	   $totalPagado=0;
	   $totalAnulado=0;
	   $totalCancelado=0;
	   $totalDeuda=0;
	while ($row = mysqli_fetch_array($result)){					
		$i++;	
		$datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
		$Comprobante = str_replace(";", "", $datosCuota);
		//Calculamos el importe que deber�a pagar al d�a de hoy
		$importe = $row['Cuo_Importe'];		
		$importeOriginal = $row['Cuo_ImporteOriginal'];		
		$importeAbonado = importeAbonadoCuota($datosCuota);		
		if ($row['Cuo_Pagado']==0 && $row['Cuo_Anulado']==0 && $row['Cuo_Cancelado']==0){
			$estado = "DEUDA";
			recalcularImporteCuota($datosCuota, $importe);
			//if ($row['Cuo_Pagado']==1) $importe = buscarPagosTotales($datosCuota);
			$recargo2 = obtenerRecargoCuota($row['Cuo_Per_ID'], $datosCuota); 
			$recargo2 = intval($recargo2);
			$totalDeuda += $importe + $recargo2;
		}
		if ($row['Cuo_Pagado']==1 && $row['Cuo_Anulado']==0 && $row['Cuo_Cancelado']==0){
			$estado = "PAGADO";
			$importe = $row['Cuo_Importe'];
			$recargo2 = 0;
			$totalPagado += $importe + $recargo2;

		}
		if ($row['Cuo_Pagado']==0 && $row['Cuo_Anulado']==1 && $row['Cuo_Cancelado']==0){
			$estado = "ANULADO";
			$importe = $row['Cuo_Importe'];
			$recargo2 = 0;
			$totalAnulado += $importe + $recargo2;
		}
		if ($row['Cuo_Pagado']==0 && $row['Cuo_Anulado']==0 && $row['Cuo_Cancelado']==1){
			$estado = "CANCELADO P/P";
			$importe = $row['Cuo_Importe'];
			$totalCancelado += $importe + $recargo2;
		}
		//$importe = $row['Cuo_Importe'];		
		$totalGenerado += $importe + $recargo2;

?>
		<tr class="gradeA" height="25px">
		  <td><?php echo $Comprobante;?></td>
		  <td><?php echo "$row[Niv_Siglas]/$row[Cur_Siglas]/$row[Div_Siglas]";?></td>
          <td><?php echo $row['Per_Apellido'].", ".$row['Per_Nombre'];?></td>
          <td><?php echo $row['Per_DNI'];?></td>
          <td><?php echo "$row[Cuo_Mes]/$row[Cuo_Anio]";?></td>
          <td><?php echo cfecha($row['Cuo_1er_Vencimiento']);?></td>		            
          <td><?php echo $row['CTi_Nombre'];?></td>
          <td><?php echo $row['Ben_Nombre'];?></td>          
          <td><?php echo intval($importeOriginal);?></td>
          <td><?php echo intval($importe);?></td>          
          <td><?php echo intval($recargo2);?></td>
		  <td><?php echo intval($importe + $recargo2);?></td>
		  <td><?php echo intval($importeAbonado);?></td>
		  <td><?php echo $estado;?></td>
		  </tr>  
	  <?php
		}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="14" class="fila_titulo">
          		IMPORTE TOTAL GENERADO: $<?php echo number_format($totalGenerado,0,",",".");?><br />
				IMPORTE TOTAL DEUDA: $<?php echo number_format($totalDeuda,0,",",".");?><br />
                IMPORTE TOTAL PAGADO: $<?php echo number_format($totalPagado,0,",",".");?><br />
                IMPORTE TOTAL ANULADO: $<?php echo number_format($totalAnulado,0,",",".");?><br />
                IMPORTE TOTAL CANCELADO: $<?php echo number_format($totalCancelado,0,",",".");?><br />
          </th>
          
        </tr>
        </tfoot>
        <tr></tr>
</table>
</div><?php
//echo $sql;exit;
?>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a><a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
<a href="#" id="barraExportar2" onclick="fnExcelReport();"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
</div>  
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>

