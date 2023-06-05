<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="plugin/css/screen.css" />

<script src="plugin/jquery.validate.js"></script>
<script src="plugin/localization/messages_es.js"></script>
<script src="plugin/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>

<script language="javascript">

$(document).ready(function(){
	
	$("#loading").hide();
	$("#mostrar").empty();
	
	<?php
	if (isset($_POST[textoBuscar])){
		echo "$('#textoBuscar').val('".$_POST[textoBuscar]."');";
		echo "$('#textoBuscar').keyup();";
		//echo "$('#mostrarTodo').click();";
	}
	?>

	$("#textoBuscar").keyup(function(event){
		event.preventDefault();
				
		vTexto = $("#textoBuscar").val();
		
		//agregó mario para que no busque vacío o menos de tres caracteres
		if (vTexto.length>3){
			//alert("Hola " + event.keyCode);
			if (event.keyCode == 13 || vTexto!="") {  
				//alert("Hola " + event.keyCode);   	
				$("#loading").show();
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "buscarPersonaDeuda", Texto: vTexto},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//alert(data);
						$("#mostrarResultado").html(data);
						$("#loading").hide();
					}
				});//fin ajax//*/
	   	}
		}else {
			$("#mostrarResultado").html("");
		}
	});

	 
});//fin de la funcion ready


</script>

	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="iconos/mod_apirante.png" width="32" height="32" align="absmiddle" /> Buscar Deuda - Personas</div></td>
          </tr>
          <tr>
            <td align="right" class="texto"></td>
            <td align="left" class="texto">Ingrese al menos cuatro carácteres o dígitos para buscar. <br>Puede buscar por Apellido y Nombre de esta forma: <i>Apellido, Nombre</i>. Ej. <i>Perez, Ju</i></td>
          </tr>
          <tr>
            <td align="right" class="texto"><strong>Apellido/DNI</strong></td>
            <td><input name="textoBuscar" type="text" id="textoBuscar" size="60"/><img id="loading" src="iconos/loading.gif" width="31" height="31" style="alignment-baseline:middle; vertical-align:middle" />&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="texto">
            <div id="mostrarResultado">
            
            </div>
            
            
                      
            </td>
          </tr>
        </table>
      
	</div>
	
	<p><br />
	  <br />
    </p>
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	