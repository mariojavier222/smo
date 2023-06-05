<?php
include_once("comprobar_sesion.php");
header("Cache-Control: no-cache, must-revalidate"); 
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<link rel="stylesheet" href="js/jquery.cleditor.css" type="text/css" />
<script type="text/javascript" src="js/jquery.cleditor.js"></script>
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$(document).ready(function(){
	
	vUsuID = <?php echo $_SESSION['sesion_UsuID'];?>;
	$("#msj_para").autocomplete("buscarMsjUsuarios.php", {
		multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,		
		formatItem:function(item, index, total, query){
		   return item.Usu_Persona + ' (' + item.Usu_Nombre + ')';
       },
       formatMatch:function(item){
           return item.Usu_Persona + ' (' + item.Usu_Nombre + ')';
		   //return item.Usu_Persona;
       },
       formatResult:function(item){
           return item.Usu_Persona + ' (' + item.Usu_Nombre + ')';
		   //return item.Usu_Persona;
       },

		dataType: "json",
		//parse: prep_data,
		parse:function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.Usu_Persona,
					result: row.Usu_Persona + ' (' + row.Usu_Nombre + ')'
				}
				$("#cargando").hide();
			});
		},//*/
		selectFirst: true,
		autoFill: true
	});
	
	new $.cleditor("msj_cuerpo");
	
	$("#barraEnviar").click(function(evento){
		
		$("#msj_para option").attr("selected", "selected");
		vPara = $("#msj_para").val();
		vAsunto = $("#msj_asunto").val();
		vCuerpo = $("#msj_cuerpo").val();
		vMTiID = $("#MTiID").val();
		if ($.browser.msie) {
			vmsj_opcion = $("#msj_opcion").val();
			if (vPara==null){
				mostrarAlerta("Antes de enviar debe escribir un destinatario","Atención");
				return false;
			}
		}else{
			vmsj_opcion = "";
		}
		//alert(vPara);

		if (vPara.length==0){
			mostrarAlerta("Antes de enviar debe escribir un destinatario","Atención");
			return false;
		}
		if (vAsunto.length==0){
			mostrarAlerta("Antes de enviar debe escribir el asunto del mensaje","Atención");
			return false;
		}
		if (vCuerpo.length==0){
			mostrarAlerta("Antes de enviar debe escribir el cuerpo del mensaje","Atención");
			return false;
		}
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'enviarMensajeUsuario', UsuID: vUsuID, Para: vPara, Asunto: vAsunto, Cuerpo: vCuerpo, msj_opcion: vmsj_opcion, MTiID: vMTiID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	if ($.browser.msie) {
	  $.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  data: {opcion: 'cargarMensajeParaIE'},
		  url: 'cargarOpciones.php',
		  success: function(data){ 
			  $("#mostrarPara").html(data);
		  }
	  });//fin ajax//*/
	}//fin browser
	$("#msj_para_ie").autocomplete("buscarMsjUsuarios.php", {
		//multiple: true,
		mustMatch: true,
		minChars: 1,
		max: 50,
		formatItem:function(item, index, total, query){           
		   return item.Usu_Persona + ' (' + item.Usu_Nombre + ')';
		   //return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatMatch:function(item){
           //$("#msj_usuario").val(item.Usu_Nombre);
		   //alert($("#msj_usuario").val());
		   return item.Usu_Persona + ' (' + item.Usu_Nombre + ')';
		   
		   //return item.Per_Apellido;
       },
       formatResult:function(item){
		   return item.Usu_Persona + ' (' + item.Usu_Nombre + ')';
           //return item.Per_Apellido;
       },

		dataType: "json",
		parse:function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.Usu_Persona,
					result: row.Usu_Persona + ' (' + row.Usu_Nombre + ')'
				}
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	$("#msj_para_ie").result(colocarValor);	
	function colocarValor(event, data, formatted) {
		if (data)
			$("#msj_usuario").val(data.Usu_Nombre);
		
	}
	$(".botones").button();
	$("#agregar").click(function(evento){
		evento.preventDefault();
		msj_usuario=$("#msj_usuario").val();
		msj_para_ie=$("#msj_para_ie").val();
		encontrado=false;
		opcion = msj_para_ie;
		id = msj_usuario;
		getNuevoCombo= "<option value='" + id + "'>" + opcion + "</option>";
		$("#msj_para option").each(function(i){			 
		   valor = $(this).val();
		   //alert(valor + "-" + msj_usuario);
		   if (valor==msj_usuario)
			  encontrado=true;
		});
		if (encontrado==false) 
		  $("#msj_para").append(getNuevoCombo);


	});
	$("#eliminarPara").click(function (evento){
		evento.preventDefault();
		$("#msj_para option:selected").remove();
	});
	$(".botones").button();
	
	<?php
	if (isset($_POST['UsuID']) && isset($_POST['UsuNombre'])){
	?>	
		//alert("");
		$("#msj_para").val("<?php echo $_POST['UsuPersona']." (".$_POST['UsuNombre'].")";?>");	
		$("#msj_asunto").focus();
	<?php
	}
	?>
	
});//fin de la funcion ready


</script>

<div id="mostrar">
  <form id="signupform" autocomplete="off" method="get" action="">
	<p>&nbsp;</p>
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia"><img src="imagenes/comment_add_48.png" width="48" height="48" align="absmiddle" />Redactar Mensaje</div></td>
      </tr>
	  <tr>
	    <td width="25" class="texto">&nbsp;</td>
	  <td class="texto"><div align="right"><strong>Para   :</strong></div></td>
          <td>
          <div id="mostrarPara">
          <textarea name="msj_para" cols="45" rows="2" id="msj_para" title="Escriba un nombre de usuario"></textarea>
          </div>
          </td>
      </tr>
	  <tr>
	    <td class="texto">&nbsp;</td>
	  <td class="texto"><div align="right"><strong>Asunto   :</strong></div></td>
          <td><input name="msj_asunto" type="text" id="msj_asunto" size="60"/>          </td>
      </tr>
	  <?php
      if ($_SESSION['sesion_rol']==1){ //solo los superamdinistradores pueden enviar disitntos tipos de mensajes
	  ?>
      <tr>
	    <td class="texto">&nbsp;</td>
	    <td align="right" class="texto"><strong>Tipo de mensaje :</strong></td>
	    <td><?php cargarListaTipoMensaje("MTiID");?></td>
      </tr>
      <?php
	  }
	  ?>
		<tr>
		  <td colspan="3" align="center" class="texto"><textarea name="msj_cuerpo" id="msj_cuerpo" cols="60" rows="10">Escriba su mensaje aqui</textarea></td>
	  </tr>      

      <tr>
        <td colspan="3" align="center" class="texto"><button class="botones" id="barraEnviar"><img src="imagenes/comment_add.png" alt="Enviar Mensaje" width="32" height="32" border="0" align="absmiddle" /> Enviar Mensaje</button></td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="texto">&nbsp;</td>
      </tr>
    </table>
</form>
	</div>
	
	<p>&nbsp;</p>
	