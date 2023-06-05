<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

	$ClaID = $_POST['ClaID'];
	//echo "Dato. $ClaID"; exit;
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT DISTINCTROW * FROM 
	 Colegio_InscripcionClase
    INNER JOIN Colegio_Inscripcion 
        ON (IMa_Leg_ID = Ins_Leg_ID) AND (IMa_Lec_ID = Ins_Lec_ID)
    INNER JOIN Colegio_Clase 
        ON (IMa_Cla_ID = Colegio_Clase.Cla_ID)
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Leg_Per_ID = persona.Per_ID)
    INNER JOIN Lectivo 
        ON (Cla_Lec_ID = lectivo.Lec_ID)
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Mat_ID)
    INNER JOIN Curso 
        ON (Colegio_Clase.Cla_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Cla_Div_ID = Div_ID)
	
		WHERE (IMa_Cla_ID = $ClaID) ORDER BY Per_Sexo, Per_Apellido, Per_Nombre";

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
		$("#listado").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Alumnos Inscriptos'		,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
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
			//"aaSorting": [[ 0, "asc" ]],
			//"sPaginationType": "full_numbers",
			"bJQueryUI": true,
			//"sDom": '<"toolbar">frtip',
			/*"aoColumnDefs": [ 

						{ "bVisible": false, "aTargets": [ 0 ] }
					],//*/
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			//"bSort": false,
			//"bAutoWidth": true, 
			"bInfo": true
			
			} );//*/
	//$("div.toolbar").html('Custom tool bar! Text/images etc.');


	/*$('#listadoTabla').dataTable( {
					"aaSorting": [[ 4, "desc" ]]
				} );//*/


});//fin de la funcion ready
</script>


<?php
if ($total>0){	
	obtenerDatosClase($ClaID, $Nivel, $Materia, $Curso, $Division);
	?>
<div class="texto">
<div align="center" class="titulo_noticia">
  <p><img src="imagenes/group.png" width="32" height="32" align="absmiddle" /> Listado de alumnos inscriptos</p>  
</div>
<fieldset class="recuadro_simple" id="resultado_buscador">

<div id="listado" >	
<div align="center" class="titulo_noticia">
  <p>Materia: <?php echo $Materia;?></p>
  <p>Nivel: <?php echo $Nivel;?> - Curso: <?php echo $Curso;?> - Divisi&oacute;n: <?php echo $Division;?></p>  
</div>
 
 <table width="100%" border="0" id="listadoTabla" class="display texto">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">#</th>
          <th align="center" class="fila_titulo">&nbsp;</th>
          <th align="center" class="fila_titulo">DNI </th>
          <th align="center" class="fila_titulo">Apellido</th>
          <th align="center" class="fila_titulo">Nombre </th>
          <th width="15" align="center" nowrap="nowrap" class="fila_titulo">Sexo</th>
          <th align="center" class="fila_titulo">Fecha Nac.</th>
          <th width="15" align="center" nowrap="nowrap" class="fila_titulo">Edad</th>
          <th align="center" class="fila_titulo">Tel.</th>
          <th align="center" class="fila_titulo">Tutor</th>
        </tr>
      </thead>
       <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		      
				  ?>
      	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      	  <td><?php echo $i;?></td>
      	  <td><?php buscarTipoDoc($row[Per_DNI], $DocAluID);
	  	$foto = buscarFoto($row[Per_DNI], $DocAluID, 30);
		echo $foto;?></td>
      <td><?php echo $row[Per_DNI];?></td>
      <td><?php echo $row[Per_Apellido];?></td>
      <td><?php echo $row[Per_Nombre];?></td>
      <td align="center"><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td>
      <td align="center"><?php 
	  $Edad = obtenerEdad($row[Per_ID], $fechaNac);
	  echo $fechaNac; ?>
      </td>
      <td align="center"><?php echo $Edad;?></td>
      <td><?php 
	  $Telefono = obtenerTelefono($row[Per_ID], $Celular);
	  echo $Telefono;
	  if (!empty($Celular))echo $Celular;
	  ?></td>
      <td align="left"><?php 
	  $NombreTutor = obtenerTutor($row[Per_ID], $DNITutor, $PerIDTutor);
	  echo $NombreTutor;?></td>
      </tr>
    
		  <?php		 
			
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="10" class="fila_titulo"></th>
        </tr>
      </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total alumnos en esta clase";?></div>
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
</div>