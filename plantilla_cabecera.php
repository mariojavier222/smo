<?php
require_once("globales.php");
global $gIndex;
?>
<script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script>
<link href="css/general.css" rel="stylesheet" type="text/css"  media="screen"/>
<link href="js/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
<link href="js/tipsy.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="JAjax.js"></script>
<script language="javascript" src="js/AjaxUpload.2.0.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>
<script src="js/jquery.alerts.js" type="text/javascript"></script>
<script src="js/jquery.tipsy.js" type="text/javascript"></script> 
<script type="text/javascript" src="js/jquery.purr.js"></script>
<script src="js/jquery.bpopup.js" language="javascript"></script>
<script language="javascript">
$(function(){
	// Tabs
	$("#tabs").tabs();
	// Datepicker
	$("#datepicker").datepicker({
		inline: true
	});//*/
	// Accordion
	$(".accordion").accordion({ header: "h1" });
	
	//Inicializamos y Ocultamos el cuadro de díalogo		
	//$("#dialog").dialog({ draggable: false, hide: 'slide' });
	//$("#dialog").dialog("destroy");
	$("#dialog").hide();
		
});//fin de la funcion

function cargarLogo(logo){
		//alert("logos/"+logo+".jpg");
		$("#logo").attr("src","logos/"+logo+".jpg");
		$("#logo").attr("width","90");
}
$(document).ready(function()
{
	$("#cerrarSesion").click(function(evento){
		evento.preventDefault();
		//document.location.href = "http://www.uccuyo.edu.ar/uccdigital/cerrar_sesion.php";
		document.location.href = "cerrar_sesion.php";
		//alert("Se cerro");
	});
	
	$("#sesion_clave").keyup(function(evento){
		evento.preventDefault();
		if (evento.keyCode == 13){
			
			$("#cargando").show();
			cargarSesion();		
			$("#cargando").hide();		
			
		}//fin if
	});//fin de prsionar enter		
	function cargarSesion(){
		if ($("#sesion_usuario").length > 0){
		
			usuario = $("#sesion_usuario").val();
			clave = $("#sesion_clave").val();
			if ( usuario.length==0 || clave.length==0 ){
				mostrarAlerta("Por favor escriba un <strong>nombre de usuario</strong> y/o <strong>clave</strong> antes de continuar","Falta usuario y/o contraseña");						
			}else{
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {sesion_usuario: usuario, sesion_clave: clave},
					url: 'registro_sesion2.php',
					success: function(data){
						if (data=="Bien"){
							//alert("Hola <?php //echo $_SESSION['sesion_usuario'];?>");
							document.location = "<?php echo $gIndex;?>";
							
						}else{							
							$("#sesion").html(data);
							$("#barraSesion").attr("src", "botones/cerrar_sesion.gif");			
							$("#barraSesion").attr("alt", "Cerrar Sesión");
							$("#barraSesion").attr("title", "Cerrar Sesión");
							$("#barraSesion").attr("style", "cursor:pointer");
						}
					}//fin success
						});//fin ajax			
			}	
		}else{//cerramos la sesion
			$("#cargando").hide();
			document.location.href = "cerrar_sesion.php";
		}	
	}//fin function
	$("#barraSesion").click(function(evento){
		evento.preventDefault();
		$("#cargando").show();
		cargarSesion();		
		$("#cargando").hide();
	});
	
	$("#barraCambiarClave").click(function(evento){
		evento.preventDefault();
		//alert("Cambiar clave");
		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  //data: {sesion_usuario: usuario, sesion_clave: clave},
		  url: 'cargarCambiarClave.php',
		  success: function(data){
			  $("#principal").html(data);
			  $("#cargando").hide();
			 
		  }//fin success
		});//fin ajax	
	});
	$("#sesionPadre").click(function(evento){
		evento.preventDefault();
		//alert("Cambiar clave");
		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  //data: {sesion_usuario: usuario, sesion_clave: clave},
		  url: 'cargarSesionPadre.php',
		  success: function(data){
			  $("#principal").html(data);
			  $("#cargando").hide();
			 
		  }//fin success
		});//fin ajax	
	});
	/*$("#logoGITECO").click(function(evento){
		evento.preventDefault();
		mostrarNotificacion("Titulo Mensake", "Hola");
	});*/
	function mostrarNotificacion(titulo, mensaje){
		var notice = '<div class="notice">'
			 + '<div class="notice-body">'
			 	+ '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">'+
  '<tr><td><img src="imagenes/info_notificacion.png" /></td>' +
    '<td>'	 + '<h3>' + titulo + '</h3>'
				 + '<p>' + mensaje + '</p></td></tr></table>'
			 + '</div>'
			 + '<div class="notice-bottom">'
			 + '</div>'
		 + '</div>';
		 $( notice ).purr();	
	
	}
	$(this).oneTime(1000, function() {	
    	//$(this).parent(".main-window").hide();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'VerUsuarioConectado'},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data)
					mostrarNotificacion("Usuarios conectados a GITeCo", data);
			}
		});//fin ajax///
  	});//*/
									 
	$(this).everyTime(500000, function() {								   
    	//$(this).parent(".main-window").hide();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'VerUsuarioConectado'},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data)
					mostrarNotificacion("Usuarios conectados a GITeCo", data);
			}
		});//fin ajax///
  	});//*/
	$(".botones").button();
	$("#cambiarUnidad").click(function(evento){
		vCambiarSedID = $("#CambiarSedID").val();
		vCambiarUniID = $("#CambiarUniID").val();
		vSede = $("#CambiarSedID option:selected").attr('id');
		vSede = $("#CambiarSedID option[value=" + vCambiarSedID + "]").text();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'cambiarUnidadUsuario', SedID: vCambiarSedID, UniID: vCambiarUniID, Sede: vSede},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data){
					//alert(data);
					document.location.href = "<?php echo $gIndex;?>";
				}
			}
		});//fin ajax///
  	});//*/
	//Funcion que marca el mensaje noticia como leido y lo quita de la vista
	$("a[id^='cerrarID']").click(function(evento){
		evento.preventDefault();		
		var ID = this.id.substr(8,10);
		$("#cargando").show();		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'marcarMensajeLeido', DesID: ID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data){
					//alert(data);
					$("#noticiaID" + ID).fadeOut();
				}
			}
		});//fin ajax///
		$("#cargando").hide();
	});
});//fin ready
</script>
	