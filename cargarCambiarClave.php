<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("funciones_generales.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="js/jquery.validate.password.css" />
<link rel="stylesheet" type="text/css" href="js/checkboxtree.css" charset="utf-8">
<script type="text/javascript" src="js/jquery.validate.password.js"></script>
<script type="text/javascript" src="js/jquery.checkboxtree.js"></script>
<script language="javascript">
$(document).ready(function(){

	$("#generar_clave").click(function (evento){
		evento.preventDefault();				
		$.post("cargarOpciones.php", {opcion: 'generarClave'}, function (data){
			$("#mostrarClave").html(data);
			$("#password").val(data);
			$("#password_confirm").val(data);
		})
		
	});

	// validate signup form on keyup and submit
	var validator = $("#signupform").validate({
		rules: {
			username: {
				required: true,
				minlength: 3
			},
			nombre_apellido: {
				required: true
			},
			clave_actual: {
				required: true,
				minlength: 6
			},

			password: {
				minlength: 6
				
			},
			password_confirm: {
				required: true,
				equalTo: "#password"
			}
		},
		messages: {
			username: {
				required: "Ingrese un nombre de usuario",
				minlength: jQuery.format("Ingrese al menos {0} caracteres")
			},
			nombre_apellido: {
				required: "Ingrese el nombre y apellido del usuario"
			},
			clave_actual: {
				required: "Ingrese la clave actual.",
				minlength: jQuery.format("Ingrese al menos {0} caracteres")
			},
			password: {
				required: "Ingrese una contraseña",
				minlength: jQuery.format("Ingrese al menos {0} caracteres")
			},
			password_confirm: {
				required: "Repita su contraseña",
				minlength: jQuery.format("Ingrese al menos {0} caracteres"),
				equalTo: "Debe ingresar la misma contraseña que arriba"
			}
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			error.prependTo( element.parent().next() );
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function() {
			//alert("Enviado!");
			vUsuario = $("#username").val();
			vPersona = $("#nombre_apellido").val();
			vClave = $("#password").val();
			vClaveActual = $("#clave_actual").val();
			$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,
			  data: {opcion: "comprobarClave", Clave: vClaveActual},
			  url: 'cargarOpciones.php',
			  success: function(data){
				  vResultado = data;
				  $("#cargando").hide();
				 
			  }//fin success
			});//fin ajax	
			if (vResultado == "Incorrecto") {
				mostrarAlerta("La clave ingresada no es igual a la clave actual.", "Error en la clave actual");
				return;
			}
			if (vResultado == "Correcto") {
				$.post("cargarOpciones.php", {opcion: 'guardarUsuario', Usuario: vUsuario, Persona: vPersona, Clave: vClave}, function (data){
					mostrarAlerta(data, "Resultado del cambio de clave");
					limpiarDatos();
					setTimeout("document.location.href = 'cerrar_sesion.php';",5000);
				});
			}
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});
	
	// propose username by combining first- and lastname
	$("#username").focus(function() {
		var firstname = $("#firstname").val();
		var lastname = $("#lastname").val();
		if(firstname && lastname && !this.value) {
			this.value = firstname + "." + lastname;
		}
	});


	
	$(".error_buscador").hide();
	$("#nombre_nuevo_menu").focus();
	$('#username').tipsy({gravity: 'w'});
	$('#clave_actual').tipsy({gravity: 'w'});
	$('#password').tipsy({gravity: 'w'});
	
	
	function limpiarDatos(){
		$('#username').val("");
		$('#nombre_apellido').val("");
		$('#password').val("");
		$('#clave_actual').val("");
		$('#password_confirm').val("");
	}

	function guardarUsuarios(){
 		$("#signupform").submit();
	}//fin guardarUsuarios
	


	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		guardarUsuarios();					
	});//fin del boton guardar

});//fin de la funcion ready


</script>

<div id="mostrarUsuario">
  <form id="signupform" autocomplete="off" method="get" action="">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia"><img src="botones/user_agregar.png" width="48" height="48" align="absmiddle" />Cambiar Clave de Usuario </div></td>
      </tr>
	  <tr>
	  <td class="texto"><div align="right">Nombre de usuario   :</div></td>
          <td><input name="username" type="text" disabled="disabled" id="username" title="Escriba un nombre de usuario" value="<?php echo $_SESSION['sesion_usuario'];?>"/>          </td>
          <td>&nbsp;</td>
	  </tr>
	  <tr>
	  <td class="texto"><div align="right">Nombre y Apellido   :</div></td>
          <td><input name="nombre_apellido" type="text" disabled="disabled" id="nombre_apellido" value="<?php echo $_SESSION['sesion_persona'];?>"/>          </td>
          <td>&nbsp;</td>
	  </tr>

		<tr>
		  <td align="right" class="texto">Contrase&ntilde;a actual :</td>
		  <td><input type="password" name="clave_actual" id="clave_actual" title="Escriba la clave actual que tiene designada" /></td>
		  <td>&nbsp;</td>
	  </tr>
		<tr>
	  <td class="texto"><div align="right">Contrase&ntilde;a nueva  :</div></td>
          <td><input name="password" type="password" id="password" value="" title="La clave debe contener letras y números" /><div class="password-meter">
	  					<div class="password-meter-message">&nbsp;</div>
	  					<div class="password-meter-bg">
		  					<div class="password-meter-bar"></div>
	  					</div>
	  				</div>          </td>
          <td>&nbsp;</td>
	  </tr>      
		<tr>
	  <td class="texto"><div align="right">Repetir contrase&ntilde;a nueva  :</div></td>
          <td><input name="password_confirm" type="password" id="password_confirm" value="" />          </td>
          <td>&nbsp;</td>
	  </tr>      

      <tr>
        <td class="texto">&nbsp;</td>
        <td class="texto"><table border="0" cellspacing="4">
          <tr>
            <td width="48" valign="top"><div align="center" class="barra_boton" id="guardar"><a href="#" id="barraGuardar"> <img src="botones/guardar.png" alt="Guardar la clave nueva" width="48" height="48" border="0" title="Guardar la clave nueva" /> Guardar</a> </div></td>
          </tr>
        </table></td>
        <td class="texto">&nbsp;</td>
      </tr>
    </table>
  </form>
	</div>
<p>&nbsp;</p>
	