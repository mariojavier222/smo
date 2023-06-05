<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$SedID = $_POST['SedID'];
	$Turno = $_POST['Turno'];
	$tituloRequisito = $_POST['tituloRequisito'];
	$tituloMostrarAlumnos = $_POST['tituloMostrarAlumnos'];
	$ReqID = $_POST['ReqID'];

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT * FROM Libro
    INNER JOIN Curso 
        ON (Lib_Cur_ID = Cur_ID)
    INNER JOIN LibroEncargado 
        ON (LEn_Lib_ID = Lib_ID) 
		
	 INNER JOIN Persona 
        ON (LEn_Per_ID = Per_ID)";//AND Leg_Baja = 0
   
   if ($CurID!=999999) $sql.=" WHERE Cur_ID =$CurID ";
   
	$sql.=" ORDER BY Per_Apellido, Per_Nombre;";

//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>

<script language="javascript">
$(document).ready(function(){

	
	 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();

		vLectivo = $("#LecID option:selected").text();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Inscriptos para el Ciclo Lectivo ' + vLectivo
		,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
										});
//		$("div#listado").printArea(options);
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
			//"aaSorting": [[ 2, "desc" ], [ 1, "asc" ]],
			//"sPaginationType": "full_numbers",
			//"bJQueryUI": true,
			//"sDom": '<"toolbar">frtip',
			//"sDom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
			"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			//"bSort": true,
			"bInfo": true,
			"bAutoWidth": true } );//*/
	//$("div.toolbar").html('Custom tool bar! Text/images etc.');


	/*$('#listadoTabla').dataTable( {
					"aaSorting": [[ 4, "desc" ]]
				} );//*/


});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
    <legend>Resultado de la b&uacute;squeda <?php //echo $SedID;?></legend>
<div id="listado" class="page-break">	
<h2>Libros que falta entregar</h2>

 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">#</th>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="center" class="fila_titulo">Alumno</th>
          <th align="center" class="fila_titulo">Curso</th>
          <th align="center" class="fila_titulo">Libro</th>
          <th align="center" class="fila_titulo">Fecha Pedido</th>
          <th align="center" class="fila_titulo">Fecha Entregado</th>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	while ($row = mysqli_fetch_array($result)){		
		
		
		//if ($i==10) echo "<p style='page-break-before:always'></p>";
		
	$visible = true;
	if ($tituloRequisito){
		$falta = buscarRequisitosFaltantePersona($LecID, $row[Niv_ID], $row[Per_ID], $ReqID);
		if (!$tituloMostrarAlumnos && !$falta){
			$visible = false;
		}
	}
	if ($visible){
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="gradeA" id="fila<?php echo $i;?>" height="40px">
	  <td align="center"><?php echo $i;?></td>
      <td align="center"><?php echo $row[Per_DNI];?></td>
      <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
      
      <td><?php echo $row[Cur_Siglas];?></td>
      <td><?php echo $row[Lib_Nombre];?></td>
      <td align="center" title="<?php echo gbuscarPersonaUsuID($row[LEn_Usu_ID]);?>"><?php echo cfecha($row[LEn_Fecha]);?></td>
      <td align="center" title="<?php echo gbuscarPersonaUsuID($row[LEn_UsuEntregado]);?>"><?php echo cfecha($row[LEn_FechaEntregado]);?></td>
      </tr>
    
		  <?php
	}//fin if visible
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
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $i libros vendidos";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron alumnos inscriptos.</span>
<?php
}

?>
