<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>

<script language="javascript">
$(document).ready(function(){
	

		<?php
	if (isset($_POST['DNI'])){
		$DNI_Asp = $_POST['DNI_Asp'];
		//echo "buscarDNI($DNI);";
		//echo "cargarFamilia($DNI);";

}
?>
	$("#barraSiguiente").click(function(evento){
		evento.preventDefault();
		vDNI_Asp = $("#DNI_Asp").val();
		vDNI_Padre = $("#DNI").val();
		vApellido_Asp = $("#Apellido_Asp").val();
		vNombre_Asp = $("#Nombre_Asp").val();
		vApellido_Padre = $("#Apellidos").val();
		vNombre_Padre = $("#Nombre").val();
		vFTiID = $("#FTiID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOtrosDatos.php',
				data: {DNI_Asp: vDNI_Asp, DNI_Padre: vDNI_Padre, Apellido_Asp: vApellido_Asp, Nombre_Asp: vNombre_Asp, Apellido_Padre: vApellido_Padre, Nombre_Padre: vNombre_Padre, FTiID: vFTiID, pag_Volver: "cargarDatosPersonales"},
				success: function (data){
						$("#principal").html(data);
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});	
	//$("#mostrarBuscador").hide();
	$("#mostrarNuevo").show();
	$("#mostrarNuevo").show();
	$("#mostrarEditar").hide();
	$('.error_buscador').hide();
	$('input.texto_buscador').css({backgroundColor:"#FFFFFF"});
	$('input.texto_buscador').focus(function(){
		$(this).css({backgroundColor:"#FFDDAA"});
	});
	$('input.texto_buscador').blur(function(){
		$(this).css({backgroundColor:"#FFFFFF"});
	});				
	$("#cargando").hide();

// definimos las opciones del plugin AJAX FORM
	var opciones_buscador= {
					   beforeSubmit: validarForm, //funcion que se ejecuta antes de enviar el form
					   success: mostrarRespuesta //funcion que se ejecuta una vez enviado el formulario
	};
	var opciones_nuevo= {
					   beforeSubmit: validarFormNuevo, //funcion que se ejecuta antes de enviar el form
					   success: mostrarRespuestaNuevo //funcion que se ejecuta una vez enviado el formulario
	};//*/


	 //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
	$("#form_persona").ajaxForm(opciones_buscador) ;
	//$("#form_nueva").ajaxForm(opciones_nuevo) ;

	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
	 var bError = false;
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];
    		if ((!form.DNI2.value) && (!form.Apellido.value)) {
				$("#dni_error").show();
				$("#apellido_error").show();
				$("#cargando").fadeOut();
				return false;
			}
			$("#dni_error").hide();
			$("#apellido_error").hide();
			$("#cargando").fadeOut();
			return true;
	 };
	 function mostrarRespuesta (responseText){
			    //responseText es lo que devuelve la p�gina buscarPersona.php. Si en buscarPersona.php hacemos echo "Hola" , la variable responseText = "Hola"				  
				  $("#mostrar").html(responseText); // Aca utilizo la funci�n append de JQuery para a�adir el responseText  dentro del div "ajax_loader"
				  $("#cargando").fadeOut();
	 };
		
		

//lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarFormNuevo(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];

			$("#mostrar").empty();
    		if ((form.Sexo.value=="-1") ) {
				$("#errorNuevoSexo").show();
				$("#cargando").fadeOut();
				return false;
			}else $("#errorNuevoSexo").hide();
			
    		if ((form.Extranjero.value=="1") ) {
	    		if ((!form.Alternativo.value) ) {
					$("#errorNuevoAlternativo").show();
					$("#cargando").fadeOut();
					return false;
				}
			}else $("#errorNuevoAlternativo").hide();

			$("#errorNuevoDNI").hide();
			$("#errorNuevoApellido").hide();
			$("#errorNuevoNombre").hide();
			$("#errorNuevoSexo").hide();
			$("#errorNuevoAlternativo").hide();
			$("#cargando").fadeOut();
			//return true;



	 };

	 function mostrarRespuestaNuevo (responseText){
			    //responseText es lo que devuelve la p�gina buscarPersona.php. Si en buscarPersona.php hacemos echo "Hola" , la variable responseText = "Hola"				  
				  $("#mostrar").html(responseText); // Aca utilizo la funci�n append de JQuery para a�adir el responseText  dentro del div "ajax_loader"
				  $("#cargando").fadeOut();
	 };

	$("#mostrarBuscador").hide();
	$("#mostrarNuevo").show();
	$("#mostrarEditar").hide();
	$("#mostrarEditar").empty();
	$("#mostrar").empty();
	$("#form_nueva2 #DNI").focus();
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#mostrarBuscador").fadeIn("slow");
		$("#mostrarNuevo").hide();
		$("#mostrarEditar").hide();
		$("#mostrarEditar").empty();
		$("#mostrar").empty();
		});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		$("#form_nueva2").submit();
		});
	$("#barraGuardar2").click(function(evento){
		evento.preventDefault();
		$("#form_nueva2").submit();
		});

	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		$("#mostrarBuscador").hide();
		$("#mostrarEditar").hide();
		$("#mostrarEditar").empty();
		$("#form_nueva2 #DNI").attr("value","");
		$("input#Apellidos").attr("value","");
		$("input#Nombre").attr("value","");
		$("select#Sexo").attr("value","-1");
		$("select#Extranjero").attr("value","0");
		$("select#DocID").attr("value","1");
		$("input#Alternativo").attr("value","");
		$("input#file").attr("value","");
		$("#upload_button").html("<img src='botones/Load.png' alt='Subir foto' title='Subir foto' /><br />Subir foto");
		$("#lista").empty();
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();
		});//fin evento click
//*/


	$("#form_nueva2").validate({
	   submitHandler: function(form) {   	
		$(form).ajaxForm(opciones_nuevo);
		return false;
	   }
	});

	//Evento que se ejecuta
	$("#form_nueva2 #DNI").blur(function() {
  		$('#lista').load("cargar_foto.php", {DNI: $("#form_nueva2 #DNI").get(0).value, DocID: $("#DocID").get(0).value});
		iDNI = $("#form_nueva2 #DNI").val();
		if (iDNI.length==0) return;
		if (iDNI.length>8 || iDNI.length<7)
			mostrarAlerta("Ha ingresado un n�mero de documento superior a <strong>8 d�gitos o menor a 7</strong>. Por favor verifique el n�mero. Igual puede continuar", "Atenci�n");
	});

//*/
//Script para subir la foto
    var button = $('#upload_button'), interval;
    var upload = new AjaxUpload('#upload_button', {
        action: 'subirArchivo.php',	
        onSubmit : function(file , ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
            // extensiones permitidas
            alert('Error: Solo se permiten imagenes');
            // cancela upload
            return false;
        } else {
            var valor = $("#form_nueva2 #DNI").get(0).value;
			if (valor.length==0){
				alert('Error: Escriba un n�mero de documento antes de subir un archivo.');
				return false;
			}else{
				//Cambio el texto del boton y lo deshabilito
				var vDNI = $('#form_nueva2 #DNI').get(0).value;
				var vDocID = $('#DocID').get(0).value;
				upload.setData({'DNI': vDNI, 'DocID': vDocID});
				button.text('Subiendo...');
				this.disable();
			}
        }
        },
        onComplete: function(file, response){
            button.text('Archivo subido');
			$('#lista').html(response);
        }  
    });
//fin de subir foto

	$("#boton_buscar").click(function(evento){
		evento.preventDefault();
		$("#form_persona").submit();
		
	});
	
	function buscarDatos(vDNI){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarDatosPersona", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data!="{}"){
					var obj = $.parseJSON(data);
					$("#Apellidos").val(obj.Per_Apellido);
					$("#Nombre").val(obj.Per_Nombre);
					$("#Sexo").val(obj.Per_Sexo);
					$("#Extranjero").val(obj.Per_Extranjero);
					$("#Alternativo").val(obj.Per_Alternativo);
					
				}else {
					//$("#mostrar").empty();					
					}
			}
		});//fin ajax//*/
	}
	$("#boton_DNI").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		
		$.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'buscarUltimoDNI', DNI_ul: vDNI},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
						$("#DNI").val(data);
                        //mostrarAlerta(data, "Resultado de guardar los datos");
                    }
                }
            });//fin ajax////
	});
	
	
	$("#DNI").keyup(function(evento){	
		evento.preventDefault();
		//alert("Hola");
		vDNI = $("#DNI").val();
		if (evento.keyCode == '13'){
			$("#mostrar").empty();
			$("#cargando").show();
			buscarDatos(vDNI);
			$("#cargando").hide();
		}
	});
	$("#DNI").focusout(function(evento){	
		evento.preventDefault();
		vDNI = $("#DNI").val();
		buscarDatos(vDNI);
	});
	
	$("#Apellido").keyup(function(event){
		event.preventDefault();
		vApellido = $("#Apellido").val();
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13 || vApellido.length>2) {  
			//alert("Apret� enter");   		
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {Apellido: vApellido},
				url: 'buscarPersona.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
   		}

	});
});//fin de la funcion ready

$(function() {
		$(".botones").button();
	});

</script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
 

	<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
       Nuevo </button> </td>
        <td width="48"><button class="barra_boton" id="barraBuscar"> <img src="botones/Search.png" alt="Buscar" width="48" height="48" border="0" title="Buscar Persona" /><br />
        Buscar</button></td>
        <td width="48"><button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Guardar</button></td>
       <!-- <?php if (isset($_POST['DNI'])){?>
<td width="48"><button style="width:48px" class="barra_boton" id="barraVolver"><img src="imagenes/go-previous.png" alt="Volver atr�s" title="Volver atr�s" width="22" height="22" border="0" /><br />
      Volver</button></td>
<?php }//fin if?>-->

 <?php 
 //if (isset($_POST['DNI'])){
	if (isset($_POST['DNI_Asp'])){ 
	 
	 ?>
<td width="48"><button style="width:48px" class="barra_boton" id="barraSiguiente"><img src="imagenes/go-next.png" alt="Siguiente" title="siguiente" width="22" height="22" border="0" /><br />
      Siguiente</button></td>
<?php }//fin if?>
      </tr>
    </table>
	<div id="mostrarBuscador">
	<form id="form_persona" method="post" action="buscarPersona.php">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2"><div align="center" class="titulo_noticia">Buscador de Personas </div></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">por N&ordm; de Documento: </div></td>
        <td><input name="DNI2" type="text" class="texto_buscador" id="DNI2" />
        <label class="error_buscador" for="DNI2" id="dni_error">* Por favor completar con un n�mero de documento.</label></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">por Apellido:</div></td>
        <td><input name="Apellido" type="text" class="texto_buscador" id="Apellido" />
		<label class="error_buscador" for="DNI2" id="apellido_error">* Por favor completar con un apellido.</label>		</td>
      </tr>
      <tr>
        <td colspan="2" class="texto"><div align="center">
        <button id="boton_buscar" class="botones"><img src="imagenes/edit-find.png" width="22" height="22" />Buscar</button></div></td>
      </tr>
    </table>

    </form>
	</div>
	<div id="mostrarNuevo">
	<form action="PersonaNueva.php" method="post" enctype="multipart/form-data" name="form_nueva2" id="form_nueva2" autocomplete="off">
	  <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
          <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/hombre-mujer.png" width="32" height="32" align="absmiddle" />Crear una persona 
            <input type="hidden" name="DNI_Asp" id="DNI_Asp" value="<?php echo $_POST['DNI_Asp'];?>" />
            <input type="hidden" name="Apellido_Asp" id="Apellido_Asp" value="<?php echo $_POST['Apellido_Asp'];?>" />
            <input type="hidden" name="Nombre_Asp" id="Nombre_Asp" value="<?php echo $_POST['Nombre_Asp'];?>" />
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Tipo de Documento: </div></td>
          <td>
            <?php cargarListaTipoDoc("DocID");?>         </td>
        </tr>
        <tr>
          <td class="texto"><div align="right">N&ordm; Documento: </div></td>
          <td><input name="DNI" type="text" id="DNI" value="" />
        <button id="boton_DNI" class="botones"><img src="imagenes/boton_mas.png" width="17" height="17" /></button>
          <label class="error_buscador" id="errorNuevoDNI">falta cargar </label>
          </td>
         <td colspan="2" class="texto"></div></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Apellidos:</div></td>
          <td><input name="Apellidos" type="text" id="Apellidos" class="required" /> 
          <label class="error_buscador" id="errorNuevoApellido">falta cargar </label></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Nombres:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" class="required"/> <label class="error_buscador" id="errorNuevoNombre">falta cargar </label></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Sexo:</div></td>
          <td><select name="Sexo" id="Sexo">
            <option value="-1">Elegir una opci&oacute;n</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
           <label class="error_buscador" id="errorNuevoSexo">debe elegir una opci�n </label></td>
        </tr>		
        <tr>
          <td class="texto"><div align="right">Elegir una foto:
            
</div></td>
          <td><table width="80" border="0">
              <tr>
                <td>
				<div align="center" id="upload_button" class="barra_boton" >
            <img src="imagenes/camera_add.png" alt="Subir foto" /><br />
              Subir foto</div><div id="lista"></div></td>
              </tr>
            </table>
          
</td>
        </tr>
        <tr>
          <td class="texto"><div align="right">&iquest;Es extranjero? </div></td>
          <td><select name="Extranjero" id="Extranjero">
            <option value="1">Si</option>
            <option value="0" selected="selected">No</option>
          </select>
		</td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Documento alternativo: </div></td>
          <td><input name="Alternativo" type="text" id="Alternativo" /><label class="error_buscador" id="errorNuevoAlternativo">falta cargar </label></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center"><button class="barra_boton" id="barraGuardar2"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Guardar</button></div></td>
        </tr>
      </table>
	</form>
	</div>
		<br /><br />
	<div id="mostrarEditar"></div>
	<br /><br />
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	
	

	
