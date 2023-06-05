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
	
	$sql = "SELECT DISTINCTROW Per_Apellido, Per_Nombre, Per_DNI, Doc_Fecha, Niv_Nombre, Cur_Siglas, Div_Siglas, Cur_Nombre, Div_Nombre, Mat_Nombre, Mat_Siglas, Cla_ID, Ori_Nombre, Doc_ID FROM 
	Colegio_Clase
    INNER JOIN Colegio_Docente 
        ON (Cla_Doc_ID = Doc_ID)
    INNER JOIN Colegio_Materia 
        ON (Cla_Mat_ID = Mat_ID)
    INNER JOIN Colegio_Orientacion 
        ON (Mat_Ori_ID = Ori_ID)    
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
	$sql.=") ORDER BY Niv_ID, Ori_ID, Cur_ID, Div_ID, Mat_Orden";
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
	
	$("button").button();
	
	$(".listado").hide();
	$(".guardar").hide();
	$(".cancelar").hide();
	//$(".docente").hide();
	$("button[id^='editar']").click(function(evento){
	  evento.preventDefault();
	  i = this.id.substr(6,10);
	  vClaID = $("#id" + i).val();	  
	  vDocID = $("#doc_actual" + i).val();	  
	 $.ajax({
		type: "POST",
		cache: false,
		async: false,
		error: function (XMLHttpRequest, textStatus){
			alert(textStatus);},
		data: {opcion: "cargarListadoDocentesActivo", i: i, Doc_ID: vDocID},
		url: 'cargarOpciones.php',
		success: function(data){ 
			//alert(data);
			if (data){
				$("#listado"+i).html(data);
				$("#listado"+i).show();
				$("#docente"+i).hide();
				$("#editar"+i).hide();
				$("#guardar"+i).show();
				$("#cancelar"+i).show();
			}
			
		}
	  });//fin ajax//*/		   	
		 
	});//fin evento click

	$("button[id^='cancelar']").click(function(evento){
		  evento.preventDefault();
		  i = this.id.substr(8,10);
		  $("#listado"+i).hide();
		  $("#docente"+i).show();
		  $("#editar"+i).show();
		  $("#guardar"+i).hide();
		  $("#cancelar"+i).hide();  	
		 
	  });//fin evento click

	$("button[id^='guardar']").click(function(evento){
	  evento.preventDefault();
	  i = this.id.substr(7,10);
	  vClaID = $("#id" + i).val();	  	  
	  vDocID = $("#Doc_ID" + i + " option:selected").val();
	  vDocente = $("#Doc_ID" + i + " option:selected").text();
	  $("#doc_actual" + i).val(vDocID);	  
	  //alert(vDocID);
	  //return;
		 $.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cambiarDocenteClase", Cla_ID: vClaID, Doc_ID: vDocID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				if (data){
					$("#docente"+i).html(vDocente);
					$("#listado"+i).hide();
					$("#docente"+i).show();
					$("#editar"+i).show();
					$("#guardar"+i).hide();
					$("#cancelar"+i).hide();
				}
				
			}
		  });//fin ajax//*/		   	
		 
	  });//fin evento click
	


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

 
 <table width="100%" border="0" id="listadoTabla2" class="display texto">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Clase</th>
          <th align="center" class="fila_titulo">Materia</th>
          <th align="center" class="fila_titulo">Nivel/Orientaci&oacute;n</th>
          <th align="center" class="fila_titulo">Curso/Div</th>
          <th align="center" class="fila_titulo">Docente</th>
          <th align="center" class="fila_titulo"></th>
        </tr>
      </thead>
       <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";		      
	    ?>
      	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
	      <td align="center"><?php echo $row['Cla_ID'];?></td>
	      <td align="center"><?php echo $row['Mat_Nombre'];?></td>
	      <td><?php echo "$row[Niv_Nombre]->$row[Ori_Nombre]";?></td>
	      <td><?php echo "$row[Cur_Siglas] $row[Div_Siglas]";?></td>
	      <td><div id="docente<?php echo $i;?>" class="docente"><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></div><div id="listado<?php echo $i;?>" class="listado"></div></td>      
	      <td><button id="editar<?php echo $i; ?>" title="Cambiar el Docente de la Clase">Cambiar Docente</button>
	      	<button id="guardar<?php echo $i; ?>" class="guardar" title="Guardar los cambios">Guardar</button>
	      	<button id="cancelar<?php echo $i; ?>" class="cancelar" title="Cancelar los cambios">Cancelar</button>
	      	<input type="hidden" id="id<?php echo $i; ?>" value="<?php echo $row['Cla_ID']; ?>" />
            <input type="hidden" id="doc_actual<?php echo $i; ?>" value="<?php echo $row['Doc_ID']; ?>" />
	      </td>
      
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
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron clases en la consulta.</span>
<?php
}
?>
</div>