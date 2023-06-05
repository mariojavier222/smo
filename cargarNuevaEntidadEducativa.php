<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){

		
// definimos las opciones del plugin AJAX FORM
	var opciones_buscador= {
					   beforeSubmit: validarForm, //funcion que se ejecuta antes de enviar el form
					   success: mostrarRespuesta //funcion que se ejecuta una vez enviado el formulario
	};
	

	 //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
	$("#form_nuevo").ajaxForm(opciones_buscador) ;
	
	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];
    		if (!form.nombre_nuevo.value) {
				$("#nombre_nuevo_error").show();
				$("#cargando").fadeOut();
				return false;
			}
			$("#nombre_nuevo_error").hide();
			$("#cargando").fadeOut();
			return true;
	 };
	 
	   
	 
	 
	 function mostrarRespuesta (responseText){
				  $("#mostrar").html(responseText); // Aca utilizo la función append de JQuery para añadir el responseText  dentro del div "ajax_loader"
				  $("#cargando").fadeOut();
	 };

	
	$("#mostrarNuevo").hide();
	$("#nombre_nuevo_error").hide();
	$("#mostrar").empty();
	$("#nombre").focus();
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();				
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		$("#nombre_nuevo").focus();
		$("#nombre_nuevo").attr("value","");
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			$("#form_nuevo").submit();
		}else{
			jAlert("No se puede guardar una búsqueda, haga click en el botón <strong>Nuevo</strong> primero","Alerta");
		}//fin if
	});
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#mostrar").empty();
		$("#divBuscador").fadeIn();
		$("#mostrarNuevo").fadeOut();
		$("#nombre").focus();
	});

	
	var vNombre;
	$("#nombre").keyup(function(event){
		event.preventDefault();
		vNombre = $("#nombre").get(0).value;

		if (vNombre.length>2 || event.keyCode == 13){
			$("#cargando").show();
			$('#mostrar').load("buscarEntidadEducativa.php", {Nombre: vNombre});
			$("#cargando").hide();
		}
	});

});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar una Entidad Nueva" width="48" height="48" border="0" title="Ingresar una Entidad Nueva" /><br />
       Nuevo </button> </td>
        <td width="48"><button class="barra_boton" id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Entidades" width="48" height="48" border="0" title="Buscar Entidades" /><br />
        Buscar</button></td>
          <td width="48"><button class="barra_boton" id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nueva Entidad" width="48" height="48" border="0" title="Guardar nueva Entidad" /><br />Guardar</button></td>
          <td width="48"><button class="barra_boton" id="barraEliminar">  <img src="botones/eliminar.png" alt="Eliminar entidades" width="48" height="48" border="0" title="Eliminar entidades" /><br />Eliminar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<form id="form_nuevo" name="form_nuevo" method="post" action="guardarNuevaEntidadEducativa.php">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2"><div align="center" class="titulo_noticia">Cargar Nueva Entidad Educativa  </div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre de la Entidad:</div></td>
          <td><input name="nombre_nuevo" type="text" id="nombre_nuevo"  class="mayuscula"/> 
          <label class="error_buscador" id="nombre_nuevo_error">falta cargar </label></td>
        </tr>
      
      <tr>
        <td colspan="2" class="texto"><div align="center">
        </div></td>
      </tr>
    </table>

    </form>
	</div>
	<div id="divBuscador">
      
        <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia">Buscar Entidades Educativas </div></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Escriba parte del nombre de la Entidad a buscar:</div></td>
            <td><input name="nombre" type="text" id="nombre"/>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="texto"><div align="center">
                
            </div></td>
          </tr>
        </table>
      
</div>
	<p><br />
	  <br />
    </p>
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	