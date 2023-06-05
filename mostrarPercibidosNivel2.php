<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$fechaDesde = $_POST['fechaDesde'];
	$fechaHasta = $_POST['fechaHasta'];
	$LecID = $_POST['LecID'];
	$NivID = $_POST['NivID'];
	$ProID = $_POST['ProID'];

/*    INNER JOIN siucc.Curso 
        ON (Chequera_Serie.ChS_Cur_ID = Curso.Cur_ID)//*/

	$sql = "SELECT DISTINCTROW Per_DNI FROM  Persona 
	INNER JOIN Legajo
		ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Colegio_Inscripcion 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
	INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID) ";
    if ($NivID!=999999) $sql.=" WHERE Colegio_Inscripcion.Ins_Niv_ID = $NivID ";

		
	
echo $sql;//exit;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>

	<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="js/ui.jqgrid.css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-sp.js" type="text/javascript"></script>
<script language="javascript">

	$("#listadoTabla").jqGrid({ 
		url:'server.php?q=1', 
		datatype: "xml", 
		colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'], 
		colModel:[ 
			{name:'id',index:'id', width:75}, 
			{name:'invdate',index:'invdate', width:90}, 
			{name:'name',index:'name', width:100}, 
			{name:'amount',index:'amount', width:80, align:"right"}, 
			{name:'tax',index:'tax', width:80, align:"right"}, 
			{name:'total',index:'total', width:80,align:"right"}, 
			{name:'note',index:'note', width:150, sortable:false} 
		], 
		rowNum:10, 
		autowidth: true, 
		rowList:[10,20,30], 
		pager: $('#paginacion'), 
		sortname: 'id',
		viewrecords: true, 
		sortorder: "desc", 
		caption:"XML Example" }).navGrid('#pager1',{edit:false,add:false,del:false}); 

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
			{ "bVisible": false, "aTargets": [ 0 ] }
			//{ "bVisible": false, "aTargets": [ 1 ] }
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
REPORTE NO CONFIABLE. NO USAR POR FAVOR<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Nivel</th>
          <th align="center" class="fila_titulo">Valor</th>
          <th align="center" class="fila_titulo">Importe Total</th>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	while ($row = mysqli_fetch_array($result)){		
		

		$DNI = $row[Per_DNI];
		$sql = "SELECT TCh_Nombre, Pro_Nombre, SUM(ChP_Importe) AS Total FROM
 siucc.Chequera_Serie
	INNER JOIN siucc.Chequera_Pago 
        ON (Chequera_Serie.ChS_Fac_ID = Chequera_Pago.ChP_Fac_ID) AND (Chequera_Serie.ChS_TCh_ID = Chequera_Pago.ChP_TCh_ID) AND (Chequera_Serie.ChS_ID = Chequera_Pago.ChP_ChS_ID)
    INNER JOIN siucc.Producto
		ON (Chequera_Pago.ChP_Pro_ID = Producto.Pro_ID)
	INNER JOIN siucc.Tipo_Chequera 
        ON (Chequera_Pago.ChP_TCh_ID = Tipo_Chequera.TCh_ID)
WHERE (Chequera_Pago.ChP_Fecha >= '".cambiaf_a_mysql($fechaDesde)."' AND Chequera_Pago.ChP_Fecha<='".cambiaf_a_mysql($fechaHasta)."'";//*/// AND Persona.Per_DNI = Chequera_Serie.ChS_Per_ID ";
		if ($ProID!=999999) $sql.=" AND Pro_ID = $ProID";
		$sql.= " AND ChS_Per_ID = $DNI) GROUP BY TCh_Nombre, Pro_Nombre;";
		$result_siucc = consulta_mysql($sql);
		$total = mysqli_num_rows($result_siucc);
		//echo $sql;exit;
		while ($row_siucc = mysqli_fetch_array($result_siucc)){
			$i++;
			if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
			
			$importeTotal += doubleval($row_siucc[Total]);
		
	?>
            <tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
              <td><?php echo "$row_siucc[TCh_Nombre] - $row_siucc[Pro_Nombre]";?></td>
              <td>--</td>
              <td align="right"><?php echo doubleval($row_siucc[Total]);?></td>
              </tr>
    
		  <?php		  
		}//fin del while siucc
	}//fin del while uccdigital
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="3" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>
</div><?php
//echo $sql;exit;
?>
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
