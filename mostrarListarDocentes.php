<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$SedID = $_POST['SedID'];
	$mostrarMaterias = $_POST['M'];
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT DISTINCTROW Per_Apellido, Per_Nombre, Per_DNI, Doc_Fecha, Niv_Nombre, Cur_Nombre, Div_Nombre, Mat_Nombre, Mat_Siglas, Cla_ID FROM 
	Colegio_Clase
    INNER JOIN Colegio_Docente 
        ON (Cla_Doc_ID = Doc_ID)";
    if ($mostrarMaterias){
	 	$sql .= "INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID)";
	}
    $sql .=" 
    INNER JOIN Curso 
        ON (Cla_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Cla_Div_ID = Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cla_Niv_ID = Niv_ID)
    INNER JOIN Sede 
        ON (Cla_Sed_ID = Sed_ID)
    INNER JOIN Persona 
        ON (Doc_Per_ID = Per_ID)
		WHERE (Cla_Lec_ID = $LecID AND Cla_Sed_ID = $SedID AND Cla_Baja=0 ";
    if ($CurID!=999999) $sql.=" AND Cla_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Cla_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Cla_Div_ID = $DivID";
	$sql.=") ORDER BY Doc_ID, Niv_ID, Cur_ID, Div_ID";
	//echo $sql;


$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
	<script src="js/jquery.printElement.js" language="javascript"></script>

    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
    <link href="css/general.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script language="javascript">
$(document).ready(function(){

	
 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		
		vLectivo = $("#LecID option:selected").text();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Inscriptos y Asegurados para el Ciclo Lectivo ' + vLectivo
		,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
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
			//"sPaginationType": "full_numbers",
			//"bJQueryUI": true,
			//"sDom": '<"toolbar">frtip',
			"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true } );//*/
	//$("div.toolbar").html('Custom tool bar! Text/images etc.');


	/*$('#listadoTabla').dataTable( {
					"aaSorting": [[ 4, "desc" ]]
				} );//*/


});//fin de la funcion ready
</script>


<?php
if ($total>0){	
	?>
<div class="texto">
<div align="center" class="titulo_noticia">
  <p><img src="imagenes/icono919.gif" width="32" height="33" align="absmiddle" /> Listado de profesores y clases asignadas</p>
</div>
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" >	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display texto">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Docente</th>
          <th align="center" class="fila_titulo">DNI </th>
          <th align="center" class="fila_titulo">Fecha Alta</th>
          <th align="center" class="fila_titulo">Nivel</th>
          <th align="center" class="fila_titulo">Curso</th>
          <th align="center" class="fila_titulo">Divisi&oacute;n.</th>
           <?php
      if ($mostrarMaterias){
	  ?>
          <th align="center" class="fila_titulo">Siglas</th>
          <th align="center" class="fila_titulo">Materia</th>
          <th align="center" class="fila_titulo">Clase</th>
          <?php
	  }
		  ?>
        </tr>
      </thead>
       <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		      
				  ?>
      	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>      
      <td><?php echo $row[Per_DNI];?></td>
      <td><?php echo cfecha($row[Doc_Fecha]);?></td>
      <td><?php echo $row[Niv_Nombre];?></td>
      <td><?php echo $row[Cur_Nombre];?></td>
      <td align="center"><?php echo $row[Div_Nombre];?></td>
      <?php
      if ($mostrarMaterias){
	  ?>
      <td align="center" title="<?php echo $row['Mat_Nombre'];?>"><?php echo $row['Mat_Siglas'];?></td>
      <td align="center"><?php echo $row['Mat_Nombre'];?></td>
      <td align="center"><?php echo $row['Cla_ID'];?></td>
      <?php
	  }//fin if
	  ?>
        </tr>
    
		  <?php		 
			
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="8" class="fila_titulo"></th>
        </tr>
      </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total clases para los profesores";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron docentes en la consulta.</span>
<?php
}
?>
</div>