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
    $('#listadoTabla').dataTable( {
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

    $fechaDesde = $_POST['fechaDesde'];
	$fechaHasta = $_POST['fechaHasta'];
	$Niv_ID = $_POST['NivID'];
    $CTi_ID = $_POST['CTi_ID'];
	$Estado = $_POST['Estado'];


if ($Estado>0){
	switch($Estado){
		case 1: $sqlWhere = " Cuo_Pagado = 0 AND Cuo_Anulado = 0 AND Cuo_Cancelado = 0";//Estado: Sin Pagar
				break;
		case 2: $sqlWhere = " Cuo_Pagado = 1 AND Cuo_Anulado=0 AND Cuo_Cancelado = 0";//Estado: Pagado
				break;
		case 3: $sqlWhere = " Cuo_Pagado = 0 AND Cuo_Anulado=1 AND Cuo_Cancelado = 0";//Estado: Anulado
				break;
		case 4: $sqlWhere = " Cuo_Pagado = 0 AND Cuo_Anulado=0 AND Cuo_Cancelado = 1";//Estado: Cancelado por Plan Pagos
				break;
	}//fin switch
}else{
	$sqlWhere = " 1 ";
}	//fin if
	 
$sql = "SELECT * FROM CuotaPersona
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID)
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)	
	INNER JOIN Usuario
		ON (Cuo_Usu_ID = Usu_ID)
	WHERE ($sqlWhere ";
	if ($Niv_ID!=999999) $sql.=" AND Niv_ID = $Niv_ID ";
	if ($CTi_ID!=999999) $sql.=" AND CTi_ID = $CTi_ID ";
	$sql .= " AND Cuo_1er_Vencimiento >= '".cambiaf_a_mysql($fechaDesde)."' AND Cuo_1er_Vencimiento<='".cambiaf_a_mysql($fechaHasta)."') ORDER BY Niv_ID, Per_Apellido, Per_Nombre";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//if ($_SESSION['sesion_UsuID']==2) echo $sql;


?>
 
 <table width="100px" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Nivel</th>
          <th align="center" class="fila_titulo">Per_Apellido</th>
          <th align="center" class="fila_titulo">Per_Nombre</th>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="center" class="fila_titulo">Periodo</th>
          <th align="center" class="fila_titulo">Vencimiento</th>
          <th align="center" class="fila_titulo">Lectivo</th>
          <th align="center" class="fila_titulo">Tipo de Cuota</th>
          <th align="center" class="fila_titulo">Estado</th>
          <th align="center" class="fila_titulo">Registro</th>
          <th align="center" class="fila_titulo">Importe</th>
          <th align="center" class="fila_titulo">Cobro Online</th>
        </tr>
       </thead>
       <tbody>
	   <?php 
	   $acum = 0;
	   $acumAnulado = 0;
	   $acumCancelado = 0;
	   $acumPagado = 0;
	   $acumSinPagar = 0;
	   $acumPagoACuenta = 0;
		while ($row = mysqli_fetch_array($result)){		
			
				$i++;
				//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
				$datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
				//Calculamos el importe que deber�a pagar al d�a de hoy
				$importe = $row['Cuo_Importe'];
				$acum = $acum + $importe;//$row['Cuo_Importe'];
				//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
				$importeOriginal = $importe;
				if ($row['Cuo_Pagado']==0 && $row['Cuo_Anulado']==0 && $row['Cuo_Cancelado']==0){
					recalcularImporteCuota($datosCuota, $importe);
					if ($row['Cuo_Importe'] > $importe) $acumPagoACuenta = $acumPagoACuenta + ($row['Cuo_Importe'] - $importe);
				}
				$aquiCobro = "-";
				if ($row['Cuo_Pagado']==1) {
					$importe = buscarPagosTotales($datosCuota);
					if ($row['Cuo_MarcadaOnline']==1) $aquiCobro = "SI";
					else $aquiCobro = "NO";
				}

				if ($row['Cuo_Pagado']==0 && $row['Cuo_Anulado']==0) {
					$EstadoNuevo = "Sin Pagar";
					$acumSinPagar = $acumSinPagar + $importe;
				}
				if ($row['Cuo_Pagado']==1 && $row['Cuo_Anulado']==0) {
					$EstadoNuevo = "Pagado";
					$acumPagado = $acumPagado + $importe;
				}
				if ($row['Cuo_Pagado']==0 && $row['Cuo_Anulado']==1) {
					$EstadoNuevo = "Anulado";
					$acumAnulado = $acumAnulado + $importe;
				}
				if ($row['Cuo_Pagado']==0 && $row['Cuo_Cancelado']==1) {
					$EstadoNuevo = "Cancelado por PP";
					$acumCancelado = $acumCancelado + $importe;
				}
				
				

		?>
				<tr class="gradeA" height="25px">
				  <td><?php echo $row['Niv_Nombre'];?></td>
                  <td><?php echo $row['Per_Apellido'];?></td>
                  <td><?php echo $row['Per_Nombre'];?></td>
                  <td><?php echo $row['Per_DNI'];?></td>
                  <td><?php echo "$row[Cuo_Mes]/$row[Cuo_Anio]";?></td>
                  <td><?php echo cfecha($row['Cuo_1er_Vencimiento']);?></td>
				  <td><?php echo $row['Lec_Nombre'];?></td>
                  <td><?php echo $row['CTi_Nombre'];?></td>
                  <td><?php echo $EstadoNuevo;?></td>
                  <td><?php echo "$row[Usu_Nombre]: ".cfecha($row['Cuo_Fecha'])." $row[Cuo_Hora]";?></td>
				  <td><?php //if ($importe != $row['Cuo_Importe']) 
				  echo $importe; //echo " - ".$row['Cuo_Importe'];?></td>
				  <td><?php echo $aquiCobro;?></td>
				  </tr>
		
			  <?php		  
              
		}//fin del while 
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="12" class="fila_titulo">IMPORTE TOTAL GENERADO: $<?php echo number_format($acum,0,",",".");?><br />
				IMPORTE TOTAL SIN PAGAR: $<?php echo number_format($acumSinPagar,0,",",".");?><br />
                IMPORTE TOTAL PAGADO: $<?php echo number_format($acumPagado,0,",",".");?><br />
                IMPORTE TOTAL ANULADO: $<?php echo number_format($acumAnulado,0,",",".");?><br />
				IMPORTE TOTAL CANCELADO POR PP: $<?php echo number_format($acumCancelado,0,",",".");?><br />
				IMPORTE TOTAL PAGADO A CUENTA: $<?php echo number_format($acumPagoACuenta,0,",",".");?></th>
          
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

