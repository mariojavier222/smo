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
	$("#form_nuevaLocalidad").ajaxForm(opciones_buscador) ;
	$("#nombre").focus();
	
	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
	
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];
			var vPais = $("#PaisID").get(0).value;
			var vProv = $("#ProID").get(0).value;			
			if (vPais<=0){
				$("#listaPais_error").show();
				$("#cargando").fadeOut();
				return false;
			}else $("#listaPais_error").hide();
			if (vProv<=0){
				$("#listaProv_error").show();
				$("#cargando").fadeOut();
				return false;
			}else $("#listaProv_error").hide();

    		if (!form.localidad.value) {
				$("#localidad_error").show();
				$("#cargando").fadeOut();
				return false;
			}
			$(".error_buscador").hide();
			$("#cargando").fadeOut();
			return true;
	 };
	 function mostrarRespuesta (responseText){
			    //responseText es lo que devuelve la página buscarPersona.php. Si en buscarPersona.php hacemos echo "Hola" , la variable responseText = "Hola"				  
				  $("#mostrarDatos3").html(responseText); // Aca utilizo la función append de JQuery para añadir el responseText  dentro del div "ajax_loader"
				  $("#cargando").fadeOut();
	 };

//lugar donde defino las funciones que utilizo dentro de "opciones"
	 

	

	$("#mostrarLocalidad").hide();
	$(".error_buscador").hide();
	//$("#mostrarnuevaProvincia").hide();
	$("#mostrarDatos3").empty();
	$("#PaisID").attr("value","0");
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();				
		$("#mostrarLocalidad").fadeIn();
		$("#mostrarDatos3").empty();
		$("#divBuscador").fadeOut();
		$("#localidad").focus();
		$("#localidad").attr("value","");
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		if ($('#mostrarLocalidad').is (':visible') && $('#mostrarLocalidad').parents (':hidden').length == 0){			
			$("#mostrarDatos3").empty();
			$("#form_nuevaLocalidad").submit();
		}else{
			jAlert("No se puede guardar una búsqueda, haga click en el botón <strong>Nuevo</strong> primero","Alerta");
		}//fin if
	});
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();				
		$("#mostrarDatos3").empty();
		$("#divBuscador").fadeIn();
		$("#mostrarLocalidad").fadeOut();
		$("#nombre").focus();
	});
	
   
   //---------------------------
      $("#PaisID").change(function () {
   		$("#PaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia(vPais);
        });
   })
   	// Parametros para el combo2
	$("#ProID").change(function () {
   		$("#ProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#PaisID").val();
			llenarLocalidad(vProv, vPais);
        });
   })

	function llenarLocalidad(vProv, vPais){
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },		function(data){
     			$("#LocID").html(data);
   		});
	}
	function llenarProvincia(vPais){
		$.post("buscarLocalidad.php", { opcion: 'cargarProvincia', Pais: vPais },function(data){
     			$("#ProID").html(data);
				vProv = $("#ProID").val();
				llenarLocalidad(vProv, vPais);

   			});
	}
   //---------------------------
	var vNombre;
	$("#nombre").keyup(function(event){	
		vNombre = $("#nombre").get(0).value;
		if (vNombre.length>2 || event.keyCode == '13'){
			$("#cargando").show();
			$('#mostrarDatos3').load("buscarLocalidad.php", {Nombre: vNombre});//*/
			$("#cargando").hide();
		}
	});
});//fin de la funcion ready
</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar Nueva Localidad" width="48" height="48" border="0" title="Ingresar Nueva Localidad" /><br />
       Nuevo </button> </td>
        <td width="48"><button class="barra_boton" id="barraBuscar"> <img src="botones/Search.png" alt="Buscar localidades" width="48" height="48" border="0" title="Buscar localidades" /><br />
        Buscar</button></td>
         <td width="48"><button class="barra_boton" id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nueva localidad" width="48" height="48" border="0" title="Guardar nueva localidad" /><br />Guardar</button></td>
<td width="48"><button class="barra_boton" id="barraEliminar">  <img src="botones/eliminar.png" alt="Eliminar localidades" width="48" height="48" border="0" title="Eliminar localidades" /><br />Eliminar</button></td>      </tr>
    </table>
	
	<div id="mostrarLocalidad">
	<form action="guardarNuevaLocalidad.php" method="post" enctype="multipart/form-data" id="form_nuevaLocalidad">
	  <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
          <td colspan="2"><div align="center" class="titulo_noticia">Cargar Nueva Localidad </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Elegir Pais: </div></td>
          <td>
           <?php cargarListaPais("PaisID");    ?>	<label class="error_buscador" id="listaPais_error">* Seleccione un País</label>		  </td>		 
        </tr>
        <tr>
          <td class="texto"><div align="right">Elegir Provincia: </div></td>
          <td><select name="ProID" id="ProID"><option value="-1">NO HAY PROVINCIAS</option></select><label class="error_buscador" id="listaProv_error">* Seleccione una Provincia</label></td>		 
        </tr>
        <tr>
          <td class="texto"><div align="right">Localidades cargadas: </div></td>
          <td><select name="LocID" id="LocID"><option value="-1">NO HAY LOCALIDADES</option></select>	</td>		 
        </tr>

        <tr>
          <td class="texto"><div align="right">Nombre de la nueva localidad:: </div></td>
          <td><input name="localidad" type="text" id="localidad" class="mayuscula" />
            <label class="error_buscador" id="localidad_error">* Ingrese nueva localidad</label></td>
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
            <td colspan="2"><div align="center" class="titulo_noticia">Buscar  Localidad </div></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Escriba parte del nombre de la Localidad a buscar:</div></td>
            <td><input name="nombre" type="text" id="nombre"/>
                </td>
          </tr>
          <tr>
            <td colspan="2" class="texto"><div align="center">
                
            </div></td>
          </tr>
        </table>
</div>
		<div id="mostrarDatos3"></div>
	<p>&nbsp;</p>
	
