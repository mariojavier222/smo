<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$fechaDesde = cambiaf_a_mysql($_POST['fechaDesde']);
	$fechaHasta = cambiaf_a_mysql($_POST['fechaHasta']);
	$LecID = $_POST['LecID'];
	$FacID = $_POST['FacID'];
	$TChID = $_POST['TChID'];

/*    INNER JOIN siucc.Curso 
        ON (Chequera_Serie.ChS_Cur_ID = Curso.Cur_ID)//*/

	$sql = "SELECT DISTINCTROW Persona.*, Doc_Nombre FROM
    siucc.Chequera_Serie
    INNER JOIN siucc.Persona 
        ON (Chequera_Serie.ChS_Per_ID = Persona.Per_ID) AND (Chequera_Serie.ChS_Doc_ID = Persona.Per_Doc_ID)
    INNER JOIN siucc.Documento 
        ON (Persona.Per_Doc_ID = Documento.Doc_ID) WHERE ChS_Lec_ID = $LecID AND ChS_Fac_ID = $FacID AND ChS_TCh_ID = $TChID ORDER BY Per_Apellido, Per_Nombre, Per_ID;";
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
	$(".link").click(function(evento){
		evento.preventDefault();
		vID = this.id;
		vPerID = $("#dni" + vID).val();
		vTCh = $("#tch" + vID).val();
		//alert(vTCh);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: "mostrarDetallePlanPagoSIUCC", TCh: vTCh},
			url: 'cargarOpciones.php',
			success: function(data){
					//alert(data);
					$("#popup").html(data);
			  }//fin success
		 });//fin ajax	
		$("#popup").bPopup();

		
	});

	$('#listadoTabla2').dataTable( {
			"bPaginate": true,
 			"aaSorting": [[ 4, "asc" ], [ 2, "asc" ]],
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
          <th align="center" class="fila_titulo">Persona</th>
          <th align="center" class="fila_titulo">Tipo de cuota</th>
          <th align="center" class="fila_titulo">Tipo Chequera</th>
          <th align="center" class="fila_titulo">Mes/A&ntilde;o</th>
          <th align="center" class="fila_titulo">1er Vencimiento</th>
          <th align="center" class="fila_titulo">Importe</th>
          <th align="center" class="fila_titulo">Acci&oacute;n</th>
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
		$DNI = $row[Per_ID];
		$Deuda = Obtener_Deuda_siucc($DNI);
		if ($Deuda>0) $Deudor = "<strong>Deudor</strong>"; else $Deudor = "Al día";
		
		
	?>

      
      
      <!-- COMIENZA EL BUCLE DE REGISTRO DE PAGOS -->
      <?php
      	$sql = "SELECT * FROM siucc.Chequera_Cuota
    INNER JOIN siucc.Producto 
        ON (Chequera_Cuota.ChC_Pro_ID = Producto.Pro_ID)
		INNER JOIN siucc.Chequera_Serie 
        ON (Chequera_Serie.ChS_Fac_ID = Chequera_Cuota.ChC_Fac_ID) AND (Chequera_Serie.ChS_TCh_ID = Chequera_Cuota.ChC_TCh_ID) AND (Chequera_Serie.ChS_ID = Chequera_Cuota.ChC_ChS_ID)
		INNER JOIN Tipo_Chequera ON (ChC_TCh_ID = TCh_ID)
		WHERE ChC_1er_Vencimiento >= '$fechaDesde' AND ChC_1er_Vencimiento <= '$fechaHasta' AND ChS_Per_ID = $DNI  AND ChC_Pagado = 0 AND ChC_Cancelado = 0 AND ChC_Baja = 0 ORDER BY ChC_1er_Vencimiento;";
		//ChS_Fac_ID = $FacID AND ChS_TCh_ID = $TChID AND
		$result_pagos = consulta_mysql($sql);
		if (mysqli_num_rows($result_pagos)>0){
			while ($row_pagos = mysqli_fetch_array($result_pagos)){
	  ?>
      	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      <td><?php echo "$row[Per_Apellido], $row[Per_Nombre] - $row[Doc_Nombre]: $row[Per_ID]";?></td>
      <td align="center"><?php echo $row_pagos[Pro_Nombre];?></td>
      <td align="center"><?php echo $row_pagos[TCh_Nombre];?></td>
      <td align="center"><?php echo "$row_pagos[ChC_Mes]/$row_pagos[ChC_Anio]";?></td>
      <td align="center"><?php echo cfecha($row_pagos[ChC_1er_Vencimiento]);?></td>
      <td align="center"><?php echo doubleval($row_pagos[ChC_1er_Importe]);?></td>
      <td>
      <?php
      if ($row_pagos[ChC_TCh_ID]==5){
	  ?>
      <a href="#" class="link" id="<?php echo $i;?>"><img src="imagenes/magnifier_zoom_in.png" title="Ver Detalle del Plan de Pago" alt="Ver Detalle del Plan de Pago" width="32" height="32" border="0" /></a><input name="dni<?php echo $i;?>" type="hidden" id="dni<?php echo $i;?>" value="<?php echo $row[Per_ID];?>" />
        <input name="tch<?php echo $i;?>" type="hidden" id="tch<?php echo $i;?>" value="<?php echo "$row_pagos[ChC_Fac_ID],$row_pagos[ChC_TCh_ID],$row_pagos[ChC_ChS_ID]";?>" />
        <?php
	  }//fin if
		?>
        </td> 
      <?php
			}//fin while
		}else{
		?>
        	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
        <td><?php echo "$row[Per_Apellido], $row[Per_Nombre] - $row[Doc_Nombre]: $row[Per_ID]";?></td>
        <td align="center">------</td>
        <td align="center">------</td>
        <td align="center">------</td>
        <td align="center">------</td>
        <td align="center">------</td>
         <td><!--<a href="#" class="link" id="<?php echo $i;?>"><img src="imagenes/magnifier_zoom_in.png" title="Ver Deuda" alt="Ver Deuda" width="32" height="32" border="0" /></a><input name="dni<?php echo $i;?>" type="hidden" id="dni<?php echo $i;?>" value="<?php echo $row[Per_ID];?>" />--></td>
         </tr>
        <?php	
		}
	  ?>
      <!-- FIN DEL BUCLE DE REGISTRO DE PAGOS -->
      
     

    
		  <?php		  
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="7" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>

</div>
<?php
//echo $sql;
?>
<div id="popup" class="borde_alerta">Ventana de popup</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total registros";?></div>
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
