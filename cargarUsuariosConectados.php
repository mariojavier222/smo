<?php
include_once("comprobar_sesion.php");
header("Cache-Control: no-cache, must-revalidate"); 
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

	$UsuID = $_SESSION['sesion_UsuID'];
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");
	if (!empty($UsuID)){
		$sql = "SELECT DISTINCTROW Usu_ID, Usu_Nombre, Usu_Persona FROM
    AccesoOpcion
    INNER JOIN Login 
        ON (AccesoOpcion.Acc_Log_ID = Login.Log_ID)
    INNER JOIN Usuario 
        ON (Login.Log_Usu_ID = Usuario.Usu_ID) WHERE Log_Usu_ID = Usu_ID AND Log_Fecha = '$Fecha' AND DATE_SUB(NOW(),INTERVAL 5 MINUTE) <= Acc_Hora ";//AND Log_Usu_ID <> '$UsuID'
	}

$result = consulta_mysql($sql);
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
			"bAutoWidth": true } 
	);//*/
	$("a[id^='enviarMensaje']").click(function(evento){											   
		evento.preventDefault();
		i = this.id.substr(13,10);
		vUsuID = $("#UsuID" + i).val();
		vUsuNombre = $("#UsuNombre" + i).val();
		vUsuPersona = $("#UsuPersona" + i).val();
		//alert(vUsuNombre);
		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  data: {UsuID: vUsuID, UsuNombre: vUsuNombre, UsuPersona: vUsuPersona},
		  url: 'cargarMensajes.php',
		  success: function(data){
			  $("#principal").html(data);
			  $("#cargando").hide();
			 
		  }//fin success
		});//fin ajax	
	});


});//fin de la funcion ready
</script>


<?php
if ($total>0){	
	?>
<div class="texto">
<div align="center" class="titulo_noticia">
  <p><img src="imagenes/Users.png" width="32" height="32" align="absmiddle" /> Listado de usuarios en l&iacute;nea</p>
</div>
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
<div id="listado" >	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display texto">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Nombre </th>
          <th align="center" class="fila_titulo">Roles</th>
          <th align="center" class="fila_titulo">Acci&oacute;n</th>
        </tr>
      </thead>
       <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		      
				  ?>
      	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      <td><?php echo $row[Usu_Persona];?></td>
      <td><?php echo traerRolUsuario($row[Usu_ID]);?></td>
      <td><input type="hidden" id="UsuNombre<?php echo $i;?>" value="<?php echo $row[Usu_Nombre];?>"/>
      <input type="hidden" id="UsuPersona<?php echo $i;?>" value="<?php echo $row[Usu_Persona];?>"/>
        <input id="UsuID<?php echo $i;?>" type="hidden" value="<?php echo $row[Usu_ID];?>" />
        <a href="#" id="enviarMensaje<?php echo $i;?>"><img src="imagenes/comment.png" alt="Enviar mensaje" name="enviarMensaje" width="32" height="32" border="0" align="absmiddle" title="Enviar mensaje" /></a></td>
      </tr>
    
		  <?php		 
			
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="3" class="fila_titulo"></th>
        </tr>
      </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total usuarios conectados";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron usuarios conectados en este momento.</span>
<?php
}
?>
</div>