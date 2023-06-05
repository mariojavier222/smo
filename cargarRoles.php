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
			password: {
				minlength: 6,
				password: "#username"
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
			vSedID = $("#SedID").val();
			$.post("cargarOpciones.php", {opcion: 'guardarUsuario', Usuario: vUsuario, Persona: vPersona, Clave: vClave, SedID: vSedID}, function (data){
				mostrarAlerta(data, "Agregar Usuario");
				limpiarDatos();
			});
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


	
	$("#mostrarRoles").hide();
	$("#mostrarPermisos").hide();
	$("#mostrarPermisosRoles").hide();
	$(".error_buscador").hide();
	$("#mostrar").empty();
	$("#nombre_nuevo_menu").focus();
	$('#username').tipsy({gravity: 'w'});
	$('#password').tipsy({gravity: 'w'});
	$('#generar_clave').tipsy({gravity: 'w'});
	
	
	function limpiarDatos(){
		$('#username').val("");
		$('#nombre_apellido').val("");
		$('#password').val("");
		$('#password_confirm').val("");
		$('#mostrarClave').empty();
	}
	$("#barraUsuario").click(function(evento){
		evento.preventDefault();				
		limpiarDatos();
		$("#mostrarUsuario").fadeIn();
		$("#mostrar").empty();		
		$("#mostrarRoles").fadeOut();
		$("#mostrarPermisos").fadeOut();
		$("#mostrarPermisosRoles").fadeOut();
		$("#nombre_nuevo_menu").focus();
		$("#nombre_nuevo_menu").attr("value","");
	});
	
	$("#barraRoles").click(function(evento){
		evento.preventDefault();				
		//$("#mostrarUsuario").hide("bounce",500,callback('mostrarUsuario'));
		$("#mostrarUsuario").fadeOut();
		$("#mostrarRoles").fadeIn();
		$("#mostrarPermisos").fadeOut();
		$("#mostrarPermisosRoles").fadeOut();
		$("#mostrar").empty();
		//$("#mostrar").load("buscarMenuOrdenar.php");
	});
	$("#barraOpcionOrdenar").click(function(evento){
		evento.preventDefault();				
/*		$("#mostrarUsuario").fadeOut();
		$("#mostrarRoles").fadeOut();
		$("#mostrar").empty();//*/
		$.post("cargarOpciones.php", {opcion: 'Prueba'});
		//$("#mostrar").load("buscarOpcionOrdenar.php");
	});
	function guardarUsuarios(){
 		$("#signupform").submit();
	}//fin guardarUsuarios
	

	function guardarRoles(){
		$("#mostrar").empty();
		vUsuID = $("#ListUsuID").val();
		vRolID = $("#ListRolID").val();
		//mostrarAlerta("Ok");
		if (vUsuID > 0 && vRolID > 0){
			$.post("cargarOpciones.php", {opcion: 'guardarRol', UsuID: vUsuID, RolID: vRolID}, function(data){
				mostrarAlerta(data, "Resultado de guardar el rol");
				llenarRoles(vUsuID);
			});//*/
		}else{
			mostrarAlerta("Antes de guardar debe seleccionar un Rol y un Usuario", "Atención");
		}//fin del if
	}//fin funcion guardarRoles
	
	$("#actualizarRoles").click(function(evento){
		evento.preventDefault();				
		$("#mostrar").empty();		
		vRolID = $("#ListRolID").val();
		//mostrarAlerta("Ok");
		if (vRolID > 0){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'actualizarRoles', RolID: vRolID},
				url: 'cargarOpciones.php',
				success: function(data){
					  mostrarAlerta(data, "Resultado de la operación");
					  $("#cargando").hide();					 
				  }//fin success
			});//fin ajax//*/
		}else{
			mostrarAlerta("Antes de actualizar debe seleccionar un Rol", "Atención");
		}//fin del if
	});//fin actualizarRoles
	
	function guardarPermisosUsuarios(){
		usuario = $("#ListPermisoUsuID").val();
		$("#cargando").show();
		//alert(usuario);
		if (usuario<0){
			mostrarAlerta("Antes de guardar debe seleccionar un <strong>Usuario</strong>", "Atención");
			
		}else{
			//$.post("cargarOpciones.php", {opcion: 'eliminarPermisoUsuario', UsuID: usuario});
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'eliminarPermisoUsuario', UsuID: usuario},
				url: 'cargarOpciones.php'
				,success: function(msg){ //alert(msg)
				;}
			});//fin ajax//*/

			//return;
			
			$("input option:checked[id^='Unidad" + "_']").each(function(index){

			alert($(this).val());
			var uni = $(this).val();
			
			$("input option:checked[id^='Menu_" + uni + "_']").each(function(index){

				//alert($(this).val());
				var menu = $(this).val();
				$("input option:checked[id^='Opcion_" + uni + "_" + menu + "_']").each(function(index){

					//alert($(this).val());
					var opcion = $(this).val();
					$("input option:checked[id^='Botones_" + uni + "_" + menu + "_" + opcion + "']").each(function(index){

						//alert($(this).val());
						boton = $(this).val();
						usuario = $("#ListPermisoUsuID").val();
						//alert(this.id);
						//alert(boton);

						$.ajax({
							type: "POST",
							cache: false,
							async: false,
							error: function (XMLHttpRequest, textStatus){
								alert(textStatus);},
							data: {opcion: 'guadarPermisoUsuario', UsuID: usuario, UniID: uni, MenID: menu, OpcID: opcion, Boton: boton},
							url: 'cargarOpciones.php'
							,success: function(msg){ 
							alert(msg);
							}
						});//fin ajax//*/
					});//fin each botones

				});//fin each opciones

			});//fin each menu

			
			});//fin each unidad
			mostrarAlerta("Se guardaron correctamente los permisos del Usuario", "Resultado");
		}//fin del else
		$("#cargando").hide();
	}//fin guardarPermisosUsuarios
	
	function mostrarError(txt){
		$('#mostrar').text(txt);
	}

	function guardarPermisoRoles(){
		rol = $("#ListPermisoRolID").val();
		$("#cargando").show();
			//alert(usuario);
		if (rol<0){
				mostrarAlerta("Antes de guardar debe seleccionar un <strong>Rol</strong>", "Atención");
				
		}else{
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'eliminarPermisoRol', RolID: rol},
					url: 'cargarOpciones.php'
				});//fin ajax//*/
				//$.post("cargarOpciones.php", {opcion: 'eliminarPermisoRol', RolID: rol});
				
				$("input:checked[id^='Unidad2" + "_']").each(function(index){
				//$("input:checked#Unidad2_").each(function(){
				//alert($(this).val());
				uni = $(this).val();
				
				$("input:checked[id^='Menu2_" + uni + "_']").each(function(index){
				//$("input:checked#Menu2_" + uni).each(function(){
					//alert($(this).val());
					menu = $(this).val();
					$("input:checked[id^='Opcion2_" + uni + "_" + menu + "_']").each(function(index){
					//$("input:checked#Opcion2_" + uni + "_" + menu).each(function(){
						//alert($(this).val());
						opcion = $(this).val();
						$("input:checked[id^='Botones2_" + uni + "_" + menu + "_" + opcion + "']").each(function(index){
						//$("input:checked#Botones2_" + uni + "_" + menu + "_" + opcion).each(function(){
							//alert($(this).val());
							boton = $(this).val();
							rol = $("#ListPermisoRolID").val();
							//alert(uni);
							$.ajax({
								type: "POST",
							  	cache: false,
								async: false,
								data: {opcion: 'guadarPermisoRol', RolID: rol, UniID: uni, MenID: menu, OpcID: opcion, Boton: boton},
								url: 'cargarOpciones.php'							  	
							});//fin ajax
						});//fin each botones

					});//fin each Opcions

				});//fin each Menu

				});//fin each Unidad
			mostrarAlerta("Se guardaron correctamente los permisos del Rol", "Resultado");
			}//fin del else
			$("#cargando").hide();
	}//fin funcion guardarPermisoRoles
	
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		if ($('#mostrarUsuario').is (':visible') && $('#mostrarUsuario').parents (':hidden').length == 0){
			//alert("Mostrar Usuarios");
			guardarUsuarios();			
		}//fin del if
		
		if ($('#mostrarRoles').is (':visible') && $('#mostrarRoles').parents (':hidden').length == 0){
			guardarRoles();
			//alert("Mostrar Roles");
		}//fin del if
		
		if ($('#mostrarPermisos').is (':visible') && $('#mostrarPermisos').parents (':hidden').length == 0){
		//estamos viendo el mostrarPermisos
			guardarPermisosUsuarios();
			//alert("Mostrar Permisos Usuarios");
		}//fin del if
		
		if ($('#mostrarPermisosRoles').is (':visible') && $('#mostrarPermisosRoles').parents (':hidden').length == 0){
		//estamos viendo el mostrar Permisos Roles
			guardarPermisoRoles();
			//alert("Mostrar Permisos Roles");
		}//fin if
		
	});//fin del boton guardar
	$("#barraPermisos").click(function(evento){
		evento.preventDefault();
		$("#cargando").show();
		$("#mostrar").empty();
		$("#mostrarRoles").fadeOut();
		$("#mostrarUsuario").fadeOut();
		$("#mostrarPermisosRoles").empty();
		$("#mostrarPermisos").empty();
		$("#mostrarPermisosRoles").fadeOut();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: 'cargarPermisosSimples'},
			url: 'cargarOpciones.php',
			success: function (data){
					$("#mostrarPermisos").fadeIn();
					$("#mostrarPermisos").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	});
	$("#barraPermisosRoles").click(function(evento){
		evento.preventDefault();
		$("#cargando").show();
		$("#mostrar").empty();
		$("#mostrarRoles").fadeOut();
		$("#mostrarUsuario").fadeOut();
		$("#mostrarPermisos").empty();
		$("#mostrarPermisosRoles").empty();
		$("#mostrarPermisos").fadeOut();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: 'cargarPermisosRoles'},
			url: 'cargarOpciones.php',
			success: function (data){
					$("#mostrarPermisosRoles").fadeIn();
					$("#mostrarPermisosRoles").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	});


 //---------------------------
    $("#ListUsuID").change(function () {
   		$("#ListUsuID option:selected").each(function () {
			//alert($(this).val());
				vOpcion=$(this).val();
				llenarRoles(vOpcion);
        });
   });
   function llenarRoles(vUsuario){
		$.post("cargarOpciones.php", { opcion: 'llenarRolesUsuario', Usuario: vUsuario },	function(data){
     			$("#mostrar").html(data);
   		});
	}
	
	
	$("#actualizarRoles").button();

});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48" valign="top"><div align="center" class="barra_boton"><a href="#" id="barraUsuario"> <img src="botones/user_agregar.png" alt="Ingresar un nuevo Usuario" width="48" height="48" border="0" title="Ingresar un nuevo Usuario" /><br />
       Usuarios </a></div> </td>
        <td width="48" valign="top"><div align="center" class="barra_boton"><a href="#" id="barraRoles"> <img src="botones/users_roles.png" alt="Asignar Roles" width="48" height="48" border="0" title="Asignar Roles" /><br />
       Roles</a></div> </td>
        <td width="48" valign="top"><div align="center" class="barra_boton"><a href="#" id="barraPermisosRoles"> <img src="botones/users_permisos_roles.png" alt="Asignar Permisos a los Roles" width="48" height="48" border="0" title="Asignar Permisos a los Roles" /><br />
       Permisos Roles</a></div> </td>

          <td width="48" valign="top"><div align="center" class="barra_boton" id="estudios"><a href="#" id="barraPermisos">  <img src="botones/users_permisos.png" alt="Asignar permisos a los usuarios" width="48" height="48" border="0" title="Asignar permisos a los usuarios" />  
          Permisos Usuarios </a>		  
	    </div></td>

            <td width="48" valign="top"><div align="center" class="barra_boton" id="guardar"><a href="#" id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" title="Guardar" />  Guardar</a>
		  
	    </div></td>
          		 

      </tr>
</table>
	
	<div id="mostrarUsuario">
	<form id="signupform" autocomplete="off" method="get" action="">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia"><img src="botones/user_agregar.png" width="48" height="48" align="absmiddle" />Cargar Nuevo Usuario </div></td>
      </tr>
	  <tr>
	  <td class="texto"><div align="right">Nombre de usuario   :</div></td>
          <td><input name="username" type="text" id="username" title="Escriba un nombre de usuario"/>          </td>
          <td>&nbsp;</td>
	  </tr>
	  <tr>
	  <td class="texto"><div align="right">Nombre y Apellido   :</div></td>
          <td><input name="nombre_apellido" type="text" id="nombre_apellido"/>          </td>
          <td>&nbsp;</td>
	  </tr>

		<tr>
		  <td align="right" class="texto">Sede: </td>
		  <td><?php cargarListaSede("SedID", $_SESSION['sesion_SedID']);?></td>
		  <td>&nbsp;</td>
	  </tr>
		<tr>
	  <td class="texto"><div align="right">Contrase&ntilde;a   :</div></td>
          <td><input name="password" type="password" id="password" value="" title="La clave debe contener letras y números" /><div class="password-meter">
	  					<div class="password-meter-message">&nbsp;</div>
	  					<div class="password-meter-bg">
		  					<div class="password-meter-bar"></div>
	  					</div>
	  				</div>          </td>
          <td>&nbsp;</td>
	  </tr>      
		<tr>
	  <td class="texto"><div align="right">Repetir contrase&ntilde;a   :</div></td>
          <td><input name="password_confirm" type="password" id="password_confirm" value="" />          </td>
          <td>&nbsp;</td>
	  </tr>      

      <tr>
        <td class="texto"><div align="right" id="mostrarClave">
        </div></td>
        <td class="texto"><div style="width:170px; cursor:pointer" id="generar_clave" title="Generar clave aleatoria">
        <img src="botones/generar_clave.png" width="32" height="32" align="absmiddle"  /> Generar clave aleatoria </div></td>
        <td class="texto">&nbsp;</td>
      </tr>
    </table>
	</form>
	</div>
	<div id="mostrarRoles">
        <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="botones/users_roles.png" width="48" height="48" align="absmiddle" /> Asignar Roles</div></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Seleccione un usuario  :</div></td>
            <td><?php echo cargarListaUsuarios("ListUsuID", true);?></td>
          </tr>
		  <tr>
            <td class="texto"><div align="right">Seleccione un rol    :</div></td>
            <td><?php echo cargarListaRoles("ListRolID");?></td>
          </tr>
		  <tr>
		    <td class="texto">&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
		    <td class="texto">&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <tr>
		    <td class="texto">&nbsp;</td>
		    <td>&nbsp;</td>
	      </tr>
		  <!-- <tr>
		    <td class="texto" align="center"><button id="actualizarRoles">Actualizar Roles de usuarios</button></td>
		    <td>&nbsp;</td>
	      </tr> -->    
          <tr>
            <td colspan="2" class="texto"><div align="center"> </div></td>
          </tr>
        </table>
    <br />
	    <br />
</div>
<div id="mostrarPermisosRoles">
       
</div>
<div id="mostrarPermisos">
       
</div>

	<div id="mostrar"></div>
	<p>&nbsp;</p>
	