<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

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
	$Niv_ID = $_POST['NivID'];
    $CTi_ID = $_POST['CTi_ID'];
    $whereLectivo = "";
	if ($Lec_ID!="999999") $whereLectivo = " AND Ins_Lec_ID = $Lec_ID";
	$whereNivel = "";
	if ($Niv_ID!="999999") $whereNivel = " AND Ins_Niv_ID = $Niv_ID";
	$whereCuota = "";
	if ($CTi_ID!="999999") $whereCuota = " AND Cuo_CTi_ID = $CTi_ID";

	 
$sql = "SELECT DISTINCTROW Niv_Siglas, Cur_Siglas, Div_Siglas, Per_Apellido, Per_Nombre, Per_DNI, Per_ID FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Per_ID)
     WHERE Ins_Lec_ID = $Lec_ID $whereNivel ORDER BY Niv_ID, Cur_ID, Div_ID, Per_Apellido, Per_Nombre;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
?>
 
 <table width="100px" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Nivel/Curso/Div</th>          
          <th align="center" class="fila_titulo">Alumno</th>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="center" class="fila_titulo">Vigencia</th>
          <th align="center" class="fila_titulo">Vencimiento</th>
          <!-- <th align="center" class="fila_titulo">Lectivo</th> -->
          <th align="center" class="fila_titulo">Tipo de Cuota</th>
          <th align="center" class="fila_titulo">Importe</th>
          <th align="center" class="fila_titulo">Recargo</th>
          <th align="center" class="fila_titulo">Total</th>
        </tr>
       </thead>
       <tbody>
	   <?php 
	   /*$acum = 0;
	   $acumAnulado = 0;
	   $acumCancelado = 0;
	   $acumPagado = 0;
	   $acumSinPagar = 0;*/
	   $totalDeudaVencida=0;
	  $totalDeudaVencer=0;
	while ($row = mysqli_fetch_array($result)){					
		$i++;		
?>
		<tr class="gradeA" height="25px">
		  <td><?php echo "$row[Niv_Siglas]/$row[Cur_Siglas]/$row[Div_Siglas]";?></td>
          <td><?php echo $row['Per_Apellido'].", ".$row['Per_Nombre'];?></td>
          <td><?php echo $row['Per_DNI'];?></td>
          <td><?php //echo "$row[Cuo_Mes]/$row[Cuo_Anio]";?></td>
          <td><?php //echo cfecha($row['Cuo_1er_Vencimiento']);?></td>		  
          <td><?php //echo $row['CTi_Nombre'];?></td>
          <td><?php //echo $EstadoNuevo;?></td>
          <td><?php ?></td>
		  <td><?php //echo $importe;//$row['Cuo_Importe'];?></td>
		  </tr>
	  <?php		
	  
	  //Primero buscamos las Deudas vencidas	
	  $fechaHoy = date("Y-m-d");
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
WHERE (Cuo_Per_ID = $row[Per_ID] $whereCuota AND Cuo_Pagado = 0 AND Cuo_Anulado = 0 AND Cuo_Cancelado = 0 AND Cuo_1er_Vencimiento<'$fechaHoy') ORDER BY Cuo_Pagado DESC, Cuo_Anulado DESC, Cuo_CTi_ID, Cuo_1er_Vencimiento, Cuo_Alt_ID  ASC, Cuo_Anio, Cuo_Mes;";
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result2)>0){
		while ($row2 = mysqli_fetch_array($result2)){					
		//$i++;	
		//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
		$datosCuota = $row2['Cuo_Lec_ID'].";".$row2['Cuo_Per_ID'].";".$row2['Cuo_Niv_ID'].";".$row2['Cuo_CTi_ID'].";".$row2['Cuo_Alt_ID'].";".$row2['Cuo_Numero'];
		//Calculamos el importe que deber�a pagar al d�a de hoy
		$importe = $row2['Cuo_Importe'];		
		recalcularImporteCuota($datosCuota, $importe);
		//if ($row['Cuo_Pagado']==1) $importe = buscarPagosTotales($datosCuota);
		$recargo2 = obtenerRecargoCuota($row2['Cuo_Per_ID'], $datosCuota); 
		$recargo2 = intval($recargo2);
?>
		<tr class="gradeA" height="25px">
		  <td>DEUDA VENCIDA</td><td></td><td></td>
          <td><?php echo "$row2[Cuo_Mes]/$row2[Cuo_Anio]";?></td>
          <td><?php echo cfecha($row2['Cuo_1er_Vencimiento']);?></td>		  
          <td><?php echo $row2['CTi_Nombre'];?></td>
          <td><?php echo $importe;?></td>
          <td><?php echo $recargo2;?></td>
		  <td><?php echo $importe + $recargo2;?></td>
		  </tr>
	  <?php
		 $totalDeudaVencida += $importe + $recargo2;
              
		}//fin del while 2
	}//fin if

	//Segundo buscamos las Deudas por vencer	
	  $fechaHoy = date("Y-m-d");
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
WHERE (Cuo_Per_ID = $row[Per_ID] $whereCuota AND Cuo_Pagado = 0 AND Cuo_Anulado = 0 AND Cuo_Cancelado = 0 AND Cuo_1er_Vencimiento>='$fechaHoy') ORDER BY Cuo_Pagado DESC, Cuo_Anulado DESC, Cuo_CTi_ID, Cuo_1er_Vencimiento, Cuo_Alt_ID  ASC, Cuo_Anio, Cuo_Mes;";
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result2)>0){
		while ($row2 = mysqli_fetch_array($result2)){					
		//$i++;	
		//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
		$datosCuota = $row2['Cuo_Lec_ID'].";".$row2['Cuo_Per_ID'].";".$row2['Cuo_Niv_ID'].";".$row2['Cuo_CTi_ID'].";".$row2['Cuo_Alt_ID'].";".$row2['Cuo_Numero'];
		//Calculamos el importe que deber�a pagar al d�a de hoy
		$importe = $row2['Cuo_Importe'];		
		recalcularImporteCuota($datosCuota, $importe);
		//if ($row['Cuo_Pagado']==1) $importe = buscarPagosTotales($datosCuota);
		$recargo2 = obtenerRecargoCuota($row2['Cuo_Per_ID'], $datosCuota); 
		$recargo2 = intval($recargo2);
?>
		<tr class="gradeA" height="25px">
		  <td>DEUDA A VENCER</td><td></td><td></td>
          <td><?php echo "$row2[Cuo_Mes]/$row2[Cuo_Anio]";?></td>
          <td><?php echo cfecha($row2['Cuo_1er_Vencimiento']);?></td>		  
          <td><?php echo $row2['CTi_Nombre'];?></td>
          <td><?php echo $importe;?></td>
          <td><?php echo $recargo2;?></td>
		  <td><?php echo $importe + $recargo2;?></td>
		  </tr>
	  <?php
		 $totalDeudaVencer += $importe + $recargo2;
              
		}//fin del while 2
	}//fin if
	}//fin del while 1

	$totalTotales = $totalDeudaVencer + $totalDeudaVencida;
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="11" class="fila_titulo">IMPORTE TOTAL GENERADO: $<?php echo number_format($totalTotales,0,",",".");?><br />
				IMPORTE TOTAL DEUDA VENCIDA: $<?php echo number_format($totalDeudaVencida,0,",",".");?><br />
                IMPORTE TOTAL DEUDA A VENCER: $<?php echo number_format($totalDeudaVencer,0,",",".");?><br /></th>
          
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
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a><a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a></div>  
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>

