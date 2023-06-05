<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
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
	$("#form_nuevaProvincia").ajaxForm(opciones_buscador) ;
	
	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
	
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];
			var vPais = $("#PaisID").get(0).value;
			if (vPais==0){
				$("#listaPais_error").show();
				$("#cargando").fadeOut();
				return false;
			}else $("#listaPais_error").hide();

    		if (!form.provincia.value) {
				$("#provincia_error").show();
				$("#cargando").fadeOut();
				return false;
			}
			$("#provincia_error").hide();
			$("#listaPais_error").hide();
			$("#cargando").fadeOut();
			return true;
	 };
	 function mostrarRespuesta (responseText){
			    //responseText es lo que devuelve la página buscarPersona.php. Si en buscarPersona.php hacemos echo "Hola" , la variable responseText = "Hola"				  
				  $("#mostrarDatos2").html(responseText); // Aca utilizo la función append de JQuery para añadir el responseText  dentro del div "ajax_loader"
				  $("#cargando").fadeOut();
	 };

//lugar donde defino las funciones que utilizo dentro de "opciones"
	 
	function cargarProvincias(){
  		$("#cargando").show();
		var vPais = $("#PaisID").get(0).value;
		$('#ListaProvincias').load("buscarProvincia.php", {opcion: 'cargarProvincia', Pais: vPais});
		$("#cargando").hide();

	}
	

	$("#mostrarProvincia").hide();
	$(".error_buscador").hide();
	$("#mostrarnuevaProvincia").hide();
	$("#mostrarDatos2").empty();
	$("#nombre").focus();
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();				
		$("#mostrarProvincia").fadeIn();
		$("#mostrarDatos2").empty();
		$("#divBuscador").fadeOut();
		$("#provincia").focus();
		$("#provincia").attr("value","");;
		$("#PaisID").attr("value",0);
		cargarProvincias();
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		if ($('#mostrarProvincia').is (':visible') && $('#mostrarProvincia').parents (':hidden').length == 0){			
			$("#mostrarDatos2").empty();
			$("#form_nuevaProvincia").submit();
			cargarProvincias();
		}else{
			//jAlert("No se puede guardar una búsqueda, haga click en el botón <strong>Nuevo</strong> primero","Alerta");
			jAlert("No se puede guardar una búsqueda, haga click en el botón <strong>Nuevo</strong> primero","Alerta");
		}//fin if
	});	
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();				
		$("#mostrarDatos2").empty();
		$("#divBuscador").fadeIn();
		$("#mostrarProvincia").fadeOut();
		$("#nombre").focus();
	});
	$("#PaisID").change(function() {
		cargarProvincias();
	});
	var vNombre;
	$("#nombre").keyup(function(event){	
		vNombre = $("#nombre").get(0).value;
		if (vNombre.length>2 || event.keyCode == '13'){
			$("#cargando").show();
			$('#mostrarDatos2').load("buscarProvincia.php", {Nombre: vNombre});//*/
			$("#cargando").hide();
		}
	});
});//fin de la funcion ready
</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar Nueva Provincia" width="48" height="48" border="0" title="Ingresar Nueva Provincia" /><br />
       Nuevo </button> </td>
        <td width="48"><button class="barra_boton" id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Provincias" width="48" height="48" border="0" title="Buscar Provincias" /><br />
        Buscar</button></td>
         <td width="48"><button class="barra_boton" id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nueva provincia" width="48" height="48" border="0" title="Guardar nueva provincia" /><br />Guardar</button></td>
<td width="48"><button class="barra_boton" id="barraEliminar">  <img src="botones/eliminar.png" alt="Eliminar provincias" width="48" height="48" border="0" title="Eliminar provincias" /><br />Eliminar </button></td>      </tr>
    </table>
	
	<div id="mostrarProvincia">
	<form action="guardarNuevaProvincia.php" method="post" enctype="multipart/form-data" id="form_nuevaProvincia">
	  <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
          <td colspan="2"><div align="center" class="titulo_noticia">Cargar Nueva Provincia </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Elegir Pais: </div></td>
          <td>
           <?php cargarListaPais("PaisID");    ?>	<label class="error_buscador" id="listaPais_error">* Seleccione un País</label>	    			  
		  </td>		 
        </tr>
        <tr>
          <td class="texto"><div align="right">Provincias cargadas: </div></td>
          <td><div id="ListaProvincias">
           <?php cargarListaProvincia("ProID", 0);    ?>		    			  
		   </div>
		  </td>		 
        </tr>

        <tr>
          <td class="texto"><div align="right">Nombre de la nueva provincia:: </div></td>
          <td><input name="provincia" type="text" id="provincia" class="mayuscula" />
            <label class="error_buscador" id="provincia_error">* Ingrese nueva provincia</label></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
          <!--  <input name="submit" type="submit" class="boton_buscador" value="Guardar" />-->
          </div></td>
        </tr>
      </table>
	</form>
	</div>
	<div id="divBuscador">
        <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia">Buscar  Provincia </div></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Escriba parte del nombre de la Provincia a buscar:</div></td>
            <td><input name="nombre" type="text" id="nombre"/>
                </td>
          </tr>
          <tr>
            <td colspan="2" class="texto"><div align="center">
                
            </div></td>
          </tr>
        </table>
</div>
		<div id="mostrarDatos2"></div>
	<p>&nbsp;</p>
	
