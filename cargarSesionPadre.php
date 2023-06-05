<?php
header("Cache-Control: no-cache, must-revalidate"); 
//include_once("comprobar_sesion.php");
require_once("conexion.php");
//include_once("guardarAccesoOpcion.php");
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

	
	limpiarDatos();	
	function limpiarDatos(){
		$('#clave').val("");
	}
	$("#DNI").keyup(function(evento){
		evento.preventDefault();
		if (evento.keyCode == 13){
		}//fin if
	});//fin de prsionar enter

	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		//alert(vClave);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpciones.php',
			data: {opcion: "verificarLongitudTexto", Texto: vDNI, Long: 0, Tipo: "Num"},
			success: function (data){
					if (data){
						$("#DNI").focus();
						mostrarAlerta("Por favor ingrese un documento v�lido", "ERROR");
						return;
					}else{
						$.ajax({
						  type: "POST",
						  cache: false,
						  async: false,
						  data: {DNI: vDNI},
						  url: 'cargarSesionPadrePaso2.php',
						  success: function(data){
							  $("#principal").html(data);
							  $("#cargando").hide();
							 
						  }//fin success
						});//fin ajax	
					}

					}//fin success
		});//fin ajax//*/
	});//fin del boton guardar

});//fin de la funcion ready


</script>

<div id="mostrarUsuario">
  <form id="signupform" autocomplete="off" onsubmit="return false;">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez</div></td>
      </tr>
	  <tr>
	    <td colspan="2" class="textoInformativo"><p>&nbsp;</p>
	      <p><strong>Estimado padre/madre: </strong></p>
	      <p>        Queremos darle la bienvenida a <strong>Napta</strong> (Colegio). Desde este sistema inform&aacute;tico Ud. podr&aacute; realizar las siguientes consultas que, esperamos, sean de su satisfacci&oacute;n:</p>
	      <ul>
	        <li>Imprimir Cuotas y Recibos de todos sus hijos.</li>
	        <li>Consultar la situaci&oacute;n financiera. <span class="texto"><em><strong>(pr&oacute;ximamente)</strong></em></span></li>
	        <li>Consultar datos personales. <em class="texto"><strong>(pr&oacute;ximamente)</strong></em></li>
	        <li>Configurar env&iacute;os de notificaciones a sus casillas de correo. <em class="texto"><strong>(pr&oacute;ximamente)</strong></em></li>
	        <li>Informarse sobre los horarios de las clases. <em class="texto"><strong>(pr&oacute;ximamente)</strong></em></li>
	        <li>Contactarse con las maestras y autoridades del Colegio. <em class="texto"><strong>(pr&oacute;ximamente)</strong></em></li>
          </ul>
	      <p>&nbsp;</p></td>
      </tr>
		<tr>
		  <td colspan="2" class="textoInformativo">Para comenzar con el inicio de sesi&oacute;n ingrese <strong>SU</strong> n&uacute;mero de documento. Si por alguna raz&oacute;n el sistema le da un error o le dice que &quot;<em>El n&uacute;mero de documento ingresado no corresponde...</em>&quot;, es posible que deba ingresar el n&uacute;mero de documento de la persona que firm&oacute; el <strong>CONTRATO EDUCATIVO</strong>.</td>
	  </tr>      
		<tr>
	  <td colspan="2" class="texto">&nbsp;</td>
      </tr>      
      
		<tr class="input_editar">
		  <td width="50%" align="right" class="texto"><strong>Ingrese su N&uacute;mero de Documento:<br />
</strong></td>
		  <td width="49%" class="texto"><input name="DNI" type="text" id="DNI" size="11" maxlength="11" />
		    *</td>
      </tr>
		<tr>
	  <td colspan="2" class="texto">&nbsp;</td>
      </tr>      

      <tr>
        <td class="texto">&nbsp;</td>
        <td class="texto"><button align="center" class="barra_boton" id="barraGuardar">  Continuar <img src="imagenes/go-next.png" alt="Guardar la clave nueva" width="22" height="22" border="0" align="absmiddle" title="Guardar la clave nueva" /></button>
        <br /></td>
      </tr>
    </table>
</form>
	</div>
<p>&nbsp;</p>
	