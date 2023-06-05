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
	$("#form_nuevoPais").ajaxForm(opciones_buscador) ;
	
	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];
    		if (!form.pais.value) {
				$("#pais_error").show();
				$("#cargando").fadeOut();
				return false;
			}
			$("#pais_error").hide();
			$("#cargando").fadeOut();
			return true;
	 };
	 
	   
	 
	 
	 function mostrarRespuesta (responseText){
				  $("#mostrarDatos").html(responseText); // Aca utilizo la función append de JQuery para añadir el responseText  dentro del div "ajax_loader"
				  $("#cargando").fadeOut();
	 };

	
	$("#mostrarPais").hide();
	$("#pais_error").hide();
	$("#mostrarDatos").empty();
	$("#nombre").focus();
	$("#form_Buscador").unbind('submit');
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();				
		$("#mostrarPais").fadeIn();
		$("#mostrarDatos").empty();		
		$("#divBuscador").fadeOut();
		$("#pais").focus();
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		if ($('#mostrarPais').is (':visible') && $('#mostrarPais').parents (':hidden').length == 0){			
			$("#mostrarDatos").empty();
			$("#form_nuevoPais").submit();
		}else{
			jAlert("No se puede guardar una búsqueda, haga click en el botón <strong>Nuevo</strong> primero","Alerta");
		}//fin if
	});	
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#mostrarDatos").empty();
		$("#divBuscador").fadeIn();
		$("#mostrarPais").fadeOut();		
		$("#nombre").focus();
	});

	
	var vNombre;
	$("#nombre").keyup(function(event){
		event.preventDefault();
		vNombre = $("#nombre").get(0).value;
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13) {  
			//alert("Apretó enter");   		
			$("#cargando").show();
			$('#mostrarDatos').load("buscarPais.php", {Nombre: vNombre});
			$("#cargando").hide();
   		}

		if (vNombre.length>2){
			$("#cargando").show();
			$('#mostrarDatos').load("buscarPais.php", {Nombre: vNombre});
			$("#cargando").hide();
		}
	});

});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un País Nuevo" width="48" height="48" border="0" title="Ingresar un País Nuevo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />Buscar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
          <td width="48"><button class="barra_boton"  id="barraEliminar">  <img src="botones/eliminar.png" alt="Eliminar paises" width="48" height="48" border="0" title="Eliminar paises" /><br />Eliminar</button></td>
      </tr>
</table>
	
	<div id="mostrarPais">
	<form id="form_nuevoPais" name="form_nuevoPais" method="post" action="guardarNuevoPais.php">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/Globe1.png" alt="Paises" width="48" height="48" align="absmiddle" />Cargar Nuevo  Pais </div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre del Pais:</div></td>
          <td><input name="pais" type="text" id="pais"/> <label class="error_buscador" id="pais_error">falta cargar </label></td>
        </tr>
      
      <tr>
        <td colspan="2" class="texto"><div align="center">
<!--         <input name="submit" type="submit" class="boton_buscador" value="Guardar" />-->
        </div></td>
      </tr>
    </table>

    </form>
	</div>
	<div id="divBuscador">
      
        <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia">Buscar  Pais </div></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Escriba parte del nombre del Pais a buscar:</div></td>
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
	<div id="mostrarDatos"></div>
	<p>&nbsp;</p>
	