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
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$SedID = $_POST['SedID'];

/*    INNER JOIN siucc.Curso 
        ON (Chequera_Serie.ChS_Cur_ID = Curso.Cur_ID)//*/
  $sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Legajo
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Colegio_Inscripcion 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
	INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)				
WHERE (Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID";
	 if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.= ") ORDER BY Per_DNI;";
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
          <th align="center" class="fila_titulo">Curso</th>
          <th align="center" class="fila_titulo">CurID</th>
          <th align="center" class="fila_titulo">Fecha</th>
          <th align="center" class="fila_titulo">Importe </th>
          <th align="center" class="fila_titulo">Nivel</th>
          <th align="center" class="fila_titulo">Div.</th>          
          <th align="center" class="fila_titulo">Persona</th>
          <th align="center" class="fila_titulo">DNI</th>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	while ($row = mysqli_fetch_array($result)){		
		$sql = "SELECT *
FROM
    CuotaPago
    INNER JOIN CuotaTipo 
        ON (CuotaPago.CuP_CTi_ID = CuotaTipo.CTi_ID)
    INNER JOIN CuotaPersona 
        ON (CuotaPago.CuP_Lec_ID = Cuo_Lec_ID) AND (CuotaPago.CuP_Per_ID = Cuo_Per_ID) AND (CuotaPago.CuP_Niv_ID = Cuo_Niv_ID) AND (CuotaPago.CuP_CTi_ID = Cuo_CTi_ID) AND (CuotaPago.CuP_Alt_ID = Cuo_Alt_ID) AND (CuotaPago.CuP_Numero = Cuo_Numero)
		WHERE CuP_Anulada = 0 AND CuP_Fecha >= '".cambiaf_a_mysql($fechaDesde)."' AND CuP_Fecha<='".cambiaf_a_mysql($fechaHasta)."' AND CuP_Per_ID = $row[Per_ID]";
		$resultPagos = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;
		while ($rowPagos = mysqli_fetch_array($resultPagos)){		
		
			$i++;
			if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
			$importeTotal += doubleval($rowPagos['CuP_Importe']);
			
			
		?>
		<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
		  <td><?php echo $row['Cur_Nombre'];?></td>
		  <td align="center"><?php echo $row['Cur_ID'];?></td>
		  <td align="center"><?php echo cfecha($rowPagos['CuP_Fecha']);?></td>    
		  <td><?php echo doubleval($rowPagos['CuP_Importe']);?></td>
		  <td><?php echo $row['Niv_Nombre'];?></td>
		  <td><?php echo $row['Div_Nombre'];?></td>      
		  <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
		  <td><?php echo $row['Per_DNI'];?></td>
		  </tr>
		
			  <?php		  
		}//fin while interno
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
