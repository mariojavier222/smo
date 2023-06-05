<?php



require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$fechaDesde = cambiaf_a_mysql($_POST['fechaDesde']);
	$fechaHasta = cambiaf_a_mysql($_POST['fechaHasta']);
	$Productos = $_POST['Productos'];
	$FacID = $_POST['FacID'];
	$Facu = $_POST['Facu'];
	$AltID = $_POST['AltID'];
	$ProID = $_POST['ProID'];
	$Importe = $_POST['Importe'];

	
	if (empty($Productos)){
		?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Debe seleccionar un concepto antes de continuar.</span>
	<?php
		exit;
	}else{
		$listaProductos = split(";", $Productos);
		foreach($listaProductos as $valor){
			if (!empty($valor)){
				if (!empty($textoProductos)) $textoProductos .= " OR ";
				$textoProductos .= " Pro_ID = $valor";
			}
		}//fin foreach
		$textoProductos = " AND ($textoProductos)";
	}
	
	if ($AltID) $textoAltID = ", ChP_Alt_ID";
	if ($ProID) $textoProID = ", Pro_Nombre";
	$sql = "SELECT Fac_Nombre $textoProID $textoAltID, SUM(ChP_Importe) AS Importe FROM
    siucc.Chequera_Pago
    INNER JOIN siucc.Facultad 
        ON (Chequera_Pago.ChP_Fac_ID = Facultad.Fac_ID)
    INNER JOIN siucc.Producto 
        ON (Chequera_Pago.ChP_Pro_ID = Producto.Pro_ID) 
	WHERE ChP_Fecha >= '$fechaDesde' AND ChP_Fecha <= '$fechaHasta' $textoProductos ";
	if ($FacID!=999999) $sql.=" AND Fac_ID = $FacID ";
	$sql .= "GROUP BY Fac_ID $textoProID $textoAltID;";
//echo $sql;exit;
$result = consulta_mysql($sql);
$total = mysqli_num_rows($result);

?>
	<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script src="js/jquery.bpopup.js" language="javascript"></script>
<script language="javascript">
$(document).ready(function(){

	
 	$("#popup").hide();
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
	

	$('#listadoTabla').dataTable( {
			"bPaginate": true,
 			"aaSorting": [[ 0, "asc" ]],
/*			"aoColumnDefs": [ 
						//{ "bSearchable": false, "bVisible": false, "aTargets": [ 2 ] },
						{ "bVisible": false, "aTargets": [ 0 ] }
					],//*/
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true //*/
		} );//*/
	
	$('#listadoTabla2').dataTable( {
			"fnDrawCallback": function ( oSettings ) {
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
		},
		"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
		"sPaginationType": "full_numbers",
//		"bJQueryUI": true,
		"aoColumnDefs": [
			{ "bVisible": false, "aTargets": [ 0 ] }
		],
		"aaSortingFixed": [[ 0, 'asc' ]],
		"aaSorting": [[ 1, 'asc' ]],
		"sDom": 'lfr<"giveHeight"t>ip'

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
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
<div id="listado" >	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <?php if ($Facu){?><th align="center" class="fila_titulo">Unidad Acad&eacute;mica</th><?php }?>
          <?php if ($ProID){?><th align="center" class="fila_titulo">Tipo de cuota</th><?php }?>
          <?php if ($AltID){?><th align="center" class="fila_titulo">Alternativa de Pago</th><?php }?>
          <?php if ($Importe){?><th align="center" class="fila_titulo">Importe</th><?php }?>
        </tr>
       </thead>
       <tbody>
	<?php 
	while ($row = mysqli_fetch_array($result)){		
		$i++;

		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		$importeTotal += doubleval($row[Importe]);
	
		
	?>

      	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      <?php if ($Facu){?><td><?php echo $row[Fac_Nombre];?></td><?php }?>
      <?php if ($ProID){?><td><?php echo $row[Pro_Nombre];?></td><?php }?>
      <?php if ($AltID){?><td align="center"><?php if ($row[ChP_Alt_ID]==1) echo "Contado"; else echo "Financiado ($row[ChP_Alt_ID])";?></td><?php }?>
      <?php if ($Importe){?><td align="right"><?php echo doubleval($row[Importe]);?></td><?php }?>
      </tr>
		  <?php		  
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="4" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>
</div>
<div id="popup" class="borde_alerta"></div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total registros - <strong>SUMA TOTAL $ $importeTotal</strong>";?></div>
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
