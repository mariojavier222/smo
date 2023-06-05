<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

	$fechaDesde = cambiaf_a_mysql($_POST['fechaDesde']);
	$fechaHasta = cambiaf_a_mysql($_POST['fechaHasta']);

    $sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT DISTINCTROW
    Factura.Fac_Sucursal
    , Factura.Fac_Numero
    , Factura.Fac_ImporteTotal
    , Factura.Fac_Pagada
    , Factura.Fac_Anulada
    , Caja.Caja_ID
    , Factura.Fac_Fecha
    , Factura.Fac_Hora
    , FacturaTipo.FTi_Nombre
    , FacturaTipo.FTi_ID
FROM
    FacturaDetalle
    INNER JOIN Factura 
        ON (FDe_Fac_ID = Fac_ID)
    INNER JOIN FacturaTipo 
        ON (Fac_FTi_ID = FTi_ID)  
    INNER JOIN CuotaPago 
        ON (FDe_Fac_ID = CuP_Fac_ID) AND (FDe_Item = CuP_FDe_Item)
    INNER JOIN Caja 
        ON (CuP_Caja_ID = Caja_ID)				
WHERE (Fac_Fecha >= '$fechaDesde' AND Fac_Fecha <= '$fechaHasta'";
	$sql.= ") ORDER BY FTi_ID, Fac_Sucursal, Fac_Numero, Fac_Fecha, Fac_Hora;";
//echo $sql;exit;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
	<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script language="javascript">
$(document).ready(function(){

	
 	$(".botones").button();
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Accesos a GITeCo',overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
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
 			"aaSorting": [[ 0, "asc" ]],
			"aoColumnDefs": [ 
						//{ "bSearchable": false, "bVisible": false, "aTargets": [ 2 ] },
						{ "bVisible": false, "aTargets": [ 0 ] }
					],//*/
			"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true //*/
		} );//*/
	$('#listadoTabla').dataTable( {
			/*"fnDrawCallback": function ( oSettings ) {
			if ( oSettings.aiDisplay.length == 0 )
			{
				return;
			}
			
			var nTrs = $('#listadoTabla tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for ( var i=0 ; i<nTrs.length ; i++ )
			{
				var iDisplayIndex = oSettings._iDisplayStart + i;
				var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
				if ( sGroup != sLastGroup )
				{
					var nGroup = document.createElement( 'tr' );
					var nCell = document.createElement( 'td' );
					nCell.colSpan = iColspan;
					nCell.className = "group";
					nCell.innerHTML = sGroup;
					nGroup.appendChild( nCell );
					nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
					sLastGroup = sGroup;
				}
			}
		},*/
		"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
		"sPaginationType": "full_numbers",
//		"bJQueryUI": true,
		/*"aoColumnDefs": [
			{ "bVisible": false, "aTargets": [ 0 ] },
			{ "bVisible": false, "aTargets": [ 1 ] }
		],*/
		/*"aaSortingFixed": [[ 0, 'asc' ]],
		"aaSorting": [[ 1, 'asc' ]],*/
		"sDom": 'lfr<"giveHeight"t>ip'

		} );//*/	
	//$("div.toolbar").html('Custom tool bar! Text/images etc.');


	/*$('#listadoTabla').dataTable( {
					"aaSorting": [[ 4, "desc" ]]
				} );//*/

	
});//fin de la funcion ready
function fnShowHide( iCol )
	{
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable = $('#listadoTabla').dataTable();
		
		var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
		oTable.fnSetColumnVis( iCol, bVis ? false : true );
	}
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
if ($total>0){	
	
	//echo "NAHUEL222";
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" >	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Comprobante</th>
          <th align="center" class="fila_titulo">Fecha emisión</th>
          <th align="center" class="fila_titulo">Hora Emisión</th>
          <th align="center" class="fila_titulo">Estado </th>
          <th align="center" class="fila_titulo">Importe</th>
          <th align="center" class="fila_titulo">Num. de Caja</th>                    
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
	$importeTotal=0;
	while ($row = mysqli_fetch_array($result)){
		$estado = '';
		if ($row['Fac_Pagada']==1) $estado = 'CANCELADO';
		if ($row['Fac_Anulada']==1) $estado = 'ANULADO';
		$importeTotal += $row['Fac_ImporteTotal'];
		?>
		<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
		  <td><?php echo $row['Fac_Sucursal'].'-'.$row['Fac_Numero'];?></td>		  
		  <td align="center"><?php echo cfecha($row['Fac_Fecha']);?></td>    
		  <td><?php echo substr($row['Fac_Hora'],0,5);?></td>		  
		  <td><?php echo $estado;?></td>
		  <td><?php echo doubleval($row['Fac_ImporteTotal']);?></td>		  
		  <td><?php echo $row['Caja_ID'];?></td>
		  </tr>
		
			  <?php		  		
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="9" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $i registros. <strong>Suma total = $importeTotal</strong>";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron datos relacionados con la b&uacute;squeda.</span>
<?php
}

?>
