<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");


?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
        
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	
	<?php 
	if (isset($_SESSION['sesion_UsuID']))
		echo "vUsuID = ".$_SESSION['sesion_UsuID'];
	else
		echo "document.location = 'index.php'";//*/
	?>;
	$("#fechaDesde").datepicker($.datepicker.regional['es']);
	$("#fechaHasta").datepicker($.datepicker.regional['es']);

		
	$("#barraMostrar").click(function(evento){
		
		vFechaDesde = $("#fechaDesde").val();
		vFechaHasta = $("#fechaHasta").val();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta},
			url: 'mostrarAccesoEmpleado.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/

	});
	$("#barraMostrarEmpleados").click(function(evento){
		
		
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			url: 'mostrarEmpleados.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/

	});
	$("#barraImportarDocentes").click(function(evento){		
		
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'importarEmpleadoDocente'},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/

	});
	$("#barraEliminarDuplicados").click(function(evento){		
		
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'eliminarDocentesDuplicados'},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/

	});
	$(".botones").button();
	$("#barraVolver").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI_Volver").val();
		//alert(vDNI.length);
		if (vDNI.length>0){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarInscripcionLectivo.php',
				data: {DNI: vDNI},
				success: function (data){
						$("#principal").html(data);
						$("#cargando").hide();
						}
			});//fin ajax
		}//fin if
	});
		
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

  <p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia">
		  <p><img src="imagenes/group.png" width="32" height="32" align="absmiddle" /> Control de Asistencia
	      </p>
        </div></td>
      </tr>
	   <!-- <tr>
	     <td width="30%" class="texto">&nbsp;</td>
         <td class="texto"><div align="left">
           <input name="fechaDesde" type="text" id="fechaDesde" class="required fechaCompleta" />
           <br />
           <strong>Fecha Desde</strong></div></td>
         <td class="texto"><input name="fechaHasta" type="text" id="fechaHasta" class="required fechaCompleta" />
           <br /> 
         <strong>Fecha Hasta </strong>         
         <td width="30%" class="texto">         	   
         </tr> -->
      <tr>
        <td align="center" class="texto"><button class="botones" id="barraImportarDocentes">
        Importar Docentes</button>
        <button class="botones" id="barraEliminarDuplicados">
        Eliminar Empleados Duplicados</button>        
        </td>
        <td align="center" class="texto"><button class="botones" id="barraMostrarEmpleados">
        Ver todo el Personal</button>        
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	