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
	$("#form_nuevo").ajaxForm(opciones_buscador) ;
	
	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];
    		if (!form.nombre_nuevoMasc.value) {
				$("#nombre_nuevoMasc_error").show();
				$("#cargando").fadeOut();
				return false;
			}
    		if (!form.nombre_nuevoFem.value) {
				$("#nombre_nuevoFem_error").show();
				$("#cargando").fadeOut();
				return false;
			}
			$("#nombre_nuevoMasc_error").hide();
			$("#nombre_nuevoFem_error").hide();			
			$("#cargando").fadeOut();
			return true;
	 };
	 
	   
	 
	 
	 function mostrarRespuesta (responseText){
				  $("#mostrar").html(responseText); // Aca utilizo la función append de JQuery para añadir el responseText  dentro del div "ajax_loader"
				  $("#cargando").fadeOut();
	 };

	
	$("#mostrarOpcion").hide();
	$(".error_buscador").hide();
	$("#mostrar").empty();
	$("#nombre_nuevo_menu").focus();
	
	$("#barraMenu").click(function(evento){
		evento.preventDefault();				
		$("#mostrarMenu").fadeIn();
		$("#mostrar").empty();		
		$("#mostrarOpcion").fadeOut();
		$("#nombre_nuevo_menu").focus();
		$("#nombre_nuevo_menu").attr("value","");
	});
	
	$("#barraMenuOrdenar").click(function(evento){
		evento.preventDefault();				
		$("#mostrarMenu").fadeOut();
		$("#mostrarOpcion").fadeOut();
		$("#mostrar").empty();
		$("#mostrar").load("buscarMenuOrdenar.php");
	});
	$("#barraOpcionOrdenar").click(function(evento){
		evento.preventDefault();				
		$("#mostrarMenu").fadeOut();
		$("#mostrarOpcion").fadeOut();
		$("#mostrar").empty();
		$("#mostrar").load("buscarOpcionOrdenar.php");
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		if ($('#mostrarMenu').is (':visible') && $('#mostrarMenu').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vNombre = $("#nombre_nuevo_menu").val();
			vDetalle = $("#detalle_menu").val();
			$.post("guardarMenuOpcion.php", {opcion: 'menu', Nombre: vNombre, Detalle: vDetalle}, function(data){
				$("#mostrar").html(data);
				$("#nombre_nuevo_menu").attr("value","");
				$("#detalle_menu").attr("value","");	
			});
		}else{
			$("#mostrar").empty();
			vNombre = $("#nombre_nuevo_opcion").val();
			vMenu = $("#OpcMenID").val();
			vDetalle = $("#detalle_opcion").val();			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'opcion', Nombre: vNombre, Menu: vMenu, Detalle: vDetalle},
				url: 'guardarMenuOpcion.php',
				success: function (data){
						$("#mostrar").html(data);
						$("#nombre_nuevo_opcion").attr("value","");
						$("#detalle_opcion").attr("value","");
						$.post("cargarOpciones.php", { opcion: 'llenarMenu' },	function(data){
							$("#MenID").html(data);
						});
					}//fin success
			});//fin ajax
			/*$.post("guardarMenuOpcion.php", {opcion: 'opcion', Nombre: vNombre, Menu: vMenu, Detalle: vDetalle}, function(data){
				$("#mostrar").html(data);
				$("#nombre_nuevo_opcion").attr("value","");
				$("#detalle_opcion").attr("value","");
				$.post("cargarOpciones.php", { opcion: 'llenarMenu' },	function(data){
     				$("#MenID").html(data);
   				});
			});//*/
		}//fin if
	});
	$("#barraOpcion").click(function(evento){
		evento.preventDefault();
		$("#mostrar").empty();
		$.post("cargarOpciones.php", { opcion: 'llenarMenu' },	function(data){
     			$("#OpcMenID").html(data);
   		});
		$("#mostrarOpcion").fadeIn();
		$("#mostrarMenu").fadeOut();
		$("#nombre_nuevo_opcion").focus();
	});

	$("#barraEstudios").click(function(evento){
		evento.preventDefault();
		$("#cargando").show();
		$("#divBuscador").fadeOut();
		$("#mostrarNuevo").fadeOut();
		$("#mostrar").load("cargarNuevoEstudio.php", function(){
				$("#cargando").hide();
		});
	});
	var vNombre;
	$("#nombre").keyup(function(event){
		event.preventDefault();
		vNombre = $("#nombre").get(0).value;

		if (vNombre.length>2 || event.keyCode == 13){
			$("#cargando").show();
			$('#mostrar').load("buscarNivelEstudio.php", {Nombre: vNombre});
			$("#cargando").hide();
		}
	});

 //---------------------------
    $("#OpcMenID").change(function () {
   		$("#OpcMenID option:selected").each(function () {
			//alert($(this).val());
				vMenu=$(this).val();
				llenarOpciones(vMenu);
        });
   });
   function llenarOpciones(vMenu){
		$.post("cargarOpciones.php", { opcion: 'llenarOpciones', Menu: vMenu },	function(data){
     			$("#OpcID").html(data);
   		});
	}

});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><div align="center" class="barra_boton"><a href="#" id="barraMenu"> <img src="botones/folder_add.png" alt="Ingresar un nuevo Menú" width="48" height="48" border="0" title="Ingresar un nuevo Menú" /><br />
       Menu </a></div> </td>
        <td width="90"><div align="center" class="barra_boton"><a href="#" id="barraMenuOrdenar"> <img src="botones/folder_explore.png" alt="Ordenar los Menúes" width="48" height="48" border="0" title="Ordenar los Menúes" /><br />
       Ordenar Men&uacute; </a></div> </td>

            <td width="48"><div align="center" class="barra_boton" id="guardar"><a href="#" id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo Nivel de Estudio" width="48" height="48" border="0" title="Guardar nuevo Nivel de Estudio" />  Guardar</a>
		  
		 </div></td>
          
          <td width="48"><div align="center" class="barra_boton" id="estudios"><a href="#" id="barraOpcion">  <img src="botones/folder_page.png" alt="Ingresar una nueva opción para un menú" width="48" height="48" border="0" title="Ingresar una nueva opción para un menú" />  
          Opciones </a>		  
		 </div></td>
        <td width="110"><div align="center" class="barra_boton"><a href="#" id="barraOpcionOrdenar"> <img src="botones/folder_explore.png" alt="Ordenar las Opciones" width="48" height="48" border="0" title="Ordenar las Opciones" /><br />
       Ordenar Opciones </a></div> </td>
		 

      </tr>
</table>
	
	<div id="mostrarMenu">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/folder_add.png" width="32" height="32" align="absmiddle" />Cargar Nuevo Men&uacute; </div></td>
      </tr>
	  <tr>
	  <td class="texto"><div align="right">Men&uacute;es cargados  :</div></td>
        <td><?php echo cargarListaMenu("MenID");?></td>
      </tr>
	  <tr>
	  <td class="texto"><div align="right">Nuevo men&uacute;  :</div></td>
          <td><input name="nombre_nuevo_menu" type="text" id="nombre_nuevo_menu"/> 
          </td>
      </tr>
		<tr>
	  <td class="texto"><div align="right">Detalles del men&uacute; (opcional)   :</div></td>
          <td><textarea name="detalle_menu" rows="3" id="detalle_menu"></textarea> 
          </td>
      </tr>      
      <tr>
        <td colspan="2" class="texto"><div align="center">
        </div></td>
      </tr>
    </table>

	</div>
	<div id="mostrarOpcion">
        <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/folder_page.png" width="32" height="32" align="absmiddle" />Cargar Nueva Opci&oacute;n </div></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Seleccionar un men&uacute;  :</div></td>
            <td><?php echo cargarListaMenu("OpcMenID");?></td>
          </tr>
		  <tr>
            <td class="texto"><div align="right">Opciones cargadas   :</div></td>
            <td><?php echo cargarListaOpcion("OpcID", 1);?></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Nueva opci&oacute;n  :</div></td>
            <td><input name="nombre_nuevo_opcion" type="text" id="nombre_nuevo_opcion"/>
                </td>
          </tr>
		  <tr>
	  <td class="texto"><div align="right">Comando (opcional)   :</div></td>
          <td><input name="detalle_opcion" type="text" id="detalle_opcion" value="" /> 
          </td>
      </tr>    
          <tr>
            <td colspan="2" class="texto"><div align="center"> </div></td>
          </tr>
        </table>
        <br />
	    <br />
</div>
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	