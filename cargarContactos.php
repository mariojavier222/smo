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

	
	limpiarDatos();	
	function limpiarDatos(){
		$('#clave').val("");
	}
	

	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		vAutID = $("#Para").val();
		vAsunto = $("#Asunto").val();
		vConsulta = $("#Consulta").val();
		//alert(vAutID + "-" + vAsunto + "-" + vConsulta);
		//return;
		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  data: {opcion: 'EnviarCorreoAutoridad', AutID: vAutID, Asunto: vAsunto, Consulta: vConsulta},
		  url: 'cargarOpciones.php',
		  success: function(data){
			  mostrarAlerta(data, "Resultado de enviar la consulta");
			  $("#cargando").hide();
			 
		  }//fin success
		});//fin ajax	
	});//fin del boton guardar

});//fin de la funcion ready


</script>

<div id="mostrarUsuario">
  <form id="signupform" autocomplete="off" onsubmit="return false;">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Contactos</div></td>
      </tr>
	  <tr>
	    <td colspan="2"><p>&nbsp;</p></td>
      </tr>
		<tr>
		  <td width="50%" align="right" class="texto"><span class="texto">Para: </span></td>
		  <td width="49%">
		    <?php cargarListadoContactos("Para");?>            
	      </td>
      </tr>
		<tr>
		  <td align="right" class="texto">Asunto:</td>
		  <td>
		    <input name="Asunto" type="text" id="Asunto" size="50" />
	      </td>
	  </tr>
		<tr>
		  <td align="right" class="texto">Consulta:</td>
		  <td rowspan="2" valign="top"><textarea name="Consulta" id="Consulta" cols="45" rows="7"></textarea></td>
	  </tr>
		<tr>
		  <td align="right" class="texto">&nbsp;</td>
	  </tr>      
		<tr>
		  <td colspan="2" class="texto">&nbsp;</td>
	  </tr>      

      <tr>
        <td class="texto">&nbsp;</td>
        <td class="texto"><button align="center" class="barra_boton" id="barraGuardar">  Enviar consulta <img src="imagenes/mail-forward.png" alt="Guardar la clave nueva" width="32" height="32" border="0" align="absmiddle" title="Guardar la clave nueva" /></button>
        <br /></td>
      </tr>
    </table>
</form>
	</div>
<p>&nbsp;</p>
	