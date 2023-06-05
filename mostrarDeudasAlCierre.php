<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$FechaVencimiento = $_POST['FechaVencimiento'];
	$Desde = $_POST['Desde'];
	$Hasta = $_POST['Hasta'];
	$MesDesde = $_POST['MesDesde'];
	$MesHasta = $_POST['MesHasta'];
	$ProID = $_POST['ProID'];
	if ( empty($Desde) || empty($Hasta) || ($ProID=="null") || empty($FechaVencimiento) ){
		echo "Antes de continuar, asegúrese de que ha completado todos los campos.";
		exit;
	}
	$sqlPro = "(";
	foreach ($ProID as $valor){
		if ( $sqlPro!="(" ) $sqlPro .= " OR ";
		$sqlPro .= " ChC_Pro_ID = $valor ";
	}
	$sqlPro .= ")";
	//exit;

	$sql = "SELECT * FROM
    siucc.Chequera_Cuota
    LEFT JOIN siucc.Chequera_Pago 
        ON (Chequera_Cuota.ChC_Anio = Chequera_Pago.ChP_Anio) AND (Chequera_Cuota.ChC_Mes = Chequera_Pago.ChP_Mes) AND (Chequera_Pago.ChP_ChS_ID = Chequera_Cuota.ChC_ChS_ID) AND (Chequera_Pago.ChP_Pro_ID = Chequera_Cuota.ChC_Pro_ID) AND (Chequera_Pago.ChP_TCh_ID = Chequera_Cuota.ChC_TCh_ID) AND (Chequera_Pago.ChP_Cuo_ID = Chequera_Cuota.ChC_Cuo_ID) AND (Chequera_Pago.ChP_Fac_ID = Chequera_Cuota.ChC_Fac_ID) AND (Chequera_Pago.ChP_Alt_ID = Chequera_Cuota.ChC_Alt_ID)
    INNER JOIN siucc.Chequera_Serie 
        ON (Chequera_Cuota.ChC_TCh_ID = Chequera_Serie.ChS_TCh_ID) AND (Chequera_Cuota.ChC_Fac_ID = Chequera_Serie.ChS_Fac_ID) AND (Chequera_Cuota.ChC_ChS_ID = Chequera_Serie.ChS_ID)
    INNER JOIN siucc.Chequera_Alternativa 
        ON (Chequera_Cuota.ChC_Alt_ID = Chequera_Alternativa.ChA_Alt_ID) AND (Chequera_Cuota.ChC_TCh_ID = Chequera_Alternativa.ChA_TCh_ID) AND (Chequera_Cuota.ChC_ChS_ID = Chequera_Alternativa.ChA_ChS_ID) AND (Chequera_Cuota.ChC_Pro_ID = Chequera_Alternativa.ChA_Pro_ID) AND (Chequera_Cuota.ChC_Fac_ID = Chequera_Alternativa.ChA_Fac_ID)
    INNER JOIN siucc.Facultad 
        ON (Chequera_Cuota.ChC_Fac_ID = Facultad.Fac_ID)
    INNER JOIN siucc.Tipo_Chequera 
        ON (Chequera_Cuota.ChC_TCh_ID = Tipo_Chequera.TCh_ID)
    INNER JOIN siucc.Producto 
        ON (Chequera_Cuota.ChC_Pro_ID = Producto.Pro_ID)
    INNER JOIN siucc.Persona 
        ON (Chequera_Serie.ChS_Per_ID = Persona.Per_ID) AND (Chequera_Serie.ChS_Doc_ID = Persona.Per_Doc_ID)
        WHERE $sqlPro AND ( (ChC_Pagado=1 AND ChP_Fecha >= '".cambiaf_a_mysql($FechaVencimiento)."')  OR ChC_Pagado=0 )";
	//Aqui comparo si la cuota fue pagada pero antes de la fecha de vencimiento
	$sql.=" AND ChC_Baja=0 AND  ( ( ChC_Cancelado=1 AND ChC_PFecha>= '".cambiaf_a_mysql($FechaVencimiento)."' )
     OR  ChC_Cancelado=0 )";
	//aqui comparo si fue cancelada por un Plan de Pago antes de la fecha vencimiento
	$sql.=" AND ChC_Mes >= $MesDesde AND ChC_Anio >= $Desde AND ChC_Mes <= $MesHasta AND ChC_Anio <= $Hasta AND ChA_Alt_Actual = 1";
	$sql.= " ORDER BY ChC_Anio, ChC_Pro_ID, ChC_Fac_ID, Per_Apellido, Per_Nombre, Per_ID, ChC_Mes";
//echo $sql;exit;
$result = consulta_mysql($sql);
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

	$('#listadoTabla').dataTable( {
			"bPaginate": true,
 			//"aaSorting": [[ 0, "asc" ], [ 1, "asc" ], [ 2, "asc" ]],
			/*"aoColumnDefs": [ 
						//{ "bSearchable": false, "bVisible": false, "aTargets": [ 2 ] },
						{ "bVisible": false, "aTargets": [ 0 ] }
					],//*/
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			//"bSort": true,
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
		"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
		"sPaginationType": "full_numbers",
//		"bJQueryUI": true,
		"aoColumnDefs": [
			{ "bVisible": false, "aTargets": [ 0 ] },
			{ "bVisible": false, "aTargets": [ 1 ] }
		],
		"aaSortingFixed": [[ 0, 'asc' ]],
		"aaSorting": [[ 1, 'asc' ]],
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
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
<div id="listado" >	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">A&ntilde;o</th>
          <th align="center" class="fila_titulo">Concepto</th>
          <th align="center" class="fila_titulo">Facultad</th>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="center" class="fila_titulo">Persona </th>
          <th align="center" class="fila_titulo">Mes</th>
          <th width="100" align="center" class="fila_titulo">Detalle</th>
          <th align="center" class="fila_titulo">3er Imp.</th>
          <th align="center" class="fila_titulo">#</th>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	while ($row = mysqli_fetch_array($result)){		
		$i++;

		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		$importeTotal += doubleval($row[ChC_3er_Importe]);
		
		
	?>
	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px" style="font-size:10px">
      <td align="center"><?php echo $row[ChC_Anio];?></td>
      <td><?php echo $row[Pro_Nombre];?></td>
      <td><?php echo $row[Fac_Nombre];?></td>
      <td align="center"><?php echo $row[Per_ID];?></td>     
      <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?>	  </td>
      <td align="center"><?php echo substr(buscarMes($row[ChC_Mes]),0,3);?></td>
      <td><?php echo "1&deg; ".doubleval($row[ChC_1er_Importe])." (".cfecha($row[ChC_1er_Vencimiento]).")<br />
	  2&deg; ".doubleval($row[ChC_2do_Importe])." (".cfecha($row[ChC_2do_Vencimiento]).")<br />
	  3&deg; ".doubleval($row[ChC_3er_Importe])." (".cfecha($row[ChC_3er_Vencimiento]).") ";?></td>
      <td align="right"><?php echo doubleval($row[ChC_3er_Importe]);?></td>
      <td align="center"><?php if (buscarIncobrable($row[Per_ID]))
	  						echo "?";else echo "*";
							?></td>
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
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total registros. <strong>Suma total = $importeTotal</strong>";?></div>
<br />
Referencia: ?: Incobrable - *: Normal<br /></fieldset>

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
