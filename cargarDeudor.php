<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Deudor";

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="plugin/css/screen.css" />

<script src="plugin/jquery.validate.js"></script>
<script src="plugin/localization/messages_es.js"></script>
<script src="plugin/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>

<script language="javascript">
$.validator.setDefaults({
	submitHandler: function() { 
		
		datos = $("#formDatos").serialize();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
				url: 'cargarOpciones.php',
			data: datos,
			success: function (data){
				jAlert(data, "Resultado de guardar los datos");
			}
		});//fin ajax
	}
});
$(document).ready(function(){
	
	//Datos iniciales
	$("#loading").hide();
	
	$("#formDatos").validate();//fin validation
		
	
	
	//$("#mostrarNuevo").hide();
	$("#divBuscador").hide();
	//$(".ocultar").hide();
	$("input[class^='ocultar']").hide();
	$("select[class^='ocultar']").hide();
	$("#mostrar").empty();
	$("#nombre").focus();
	$("#form_Buscador").unbind('submit');
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		$("#barraGuardar").show();				
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		limpiarDatos();
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		$("#formDatos").submit();
	});
	$("#Cue_Nombre").keyup(function(event){
		event.preventDefault();
		vTexto = $("#textoBuscar").val();
		if (event.keyCode == 13 && vTexto.length>2) {  
			//alert("Hola " + event.keyCode);   	
			$("#formDatos").submit();			
   		}
	});
	$("#Deu_Nombre").focusout(function(event){
		event.preventDefault();		
		vDeu_ID = $("#Deu_ID").val();
		vDeu_Nombre = $("#Deu_Nombre").val();
		if (vDeu_ID=="") $("#Deu_RazonSocial").val(vDeu_Nombre);
	});
	function recargarPagina(){
		$("#mostrar").empty();

		$.ajax({
			cache: false,
			async: false,			
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
					$("#principal").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	}//fin function
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#barraGuardar").hide();
		$("#mostrarNuevo").hide();
		$("#divBuscador").fadeIn();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Texto: "todos"},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					//$("#loading").hide();
				}
			});//fin ajax//*/
		//recargarPagina();
	});
	$("#mostrarTodo").click(function(evento){
		evento.preventDefault();
		$("#textoBuscar").val("todos");
		$("#textoBuscar").keyup();
	});
	$("#textoBuscar").keyup(function(event){
		event.preventDefault();
		$("#loading").show();
		vTexto = $("#textoBuscar").val();
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13 || vTexto.length>2) {  
			//alert("Hola " + event.keyCode);   	
			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Texto: vTexto},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					$("#loading").hide();
				}
			});//fin ajax//*/
   		}

	});
	
	function limpiarDatos(){
		$("input:text").val("");
		$("select").val(-1);
		$("textarea").val("");
	}
	
	
	 
});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Agregar un nuevo Registro" width="48" height="48" border="0" title="Agregar un nuevo Registro" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Registros" width="48" height="48" border="0" title="Buscar Registros" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar datos" width="48" height="48" border="0" title="Guardar datos" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
    <form id="formDatos" method="post" action="" class="cmxformNOOO">
	<table width="100%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle" align="center" class="texto"><div align="center" class="titulo_noticia"> Cargar Deudores</div><br /><i>Administrar los deudores, por ejemplo: Buffete, Fotocopiadora, ART, etc.</i></td>
      </tr>
          <tr>
            <td class="texto" align="right" width="30%">Código (no completar):</td>
            <td align="left" width="70%"><input name="Deu_ID" type="text" id="Deu_ID" size="10" readonly="readonly">
            <input name="opcion" type="hidden" id="opcion" value="guardarDeudor" /></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Nombre del Deudor:</td>
          <td align="left"><input name="Deu_Nombre" type="text" id="Deu_Nombre" size="60"></td>
        </tr> 
        <tr>
	  <td class="texto" align="right">Razon Social:</td>
          <td align="left"><input name="Deu_RazonSocial" id="Deu_RazonSocial" type="text" size="60"></td>
        </tr> 
        <tr>
	  <td class="texto" align="right">C.U.I.T.:</td>
          <td align="left"><input name="Deu_CUIT" id="Deu_CUIT" type="text" size="40"></td>
        </tr>
    </table>
    </form>

</div>
	<div id="divBuscador">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Deudores</div></td>
          </tr>
          <tr>
            <td align="center" class="texto"><strong>Deudor/CUIT</strong><input name="textoBuscar" type="text" id="textoBuscar" size="60" title="Para buscar todos los registros escriba la palabra todos"/><img id="loading" src="iconos/loading.gif" width="31" height="31" style="alignment-baseline:middle; vertical-align:middle" /><button id="mostrarTodo">Cargar todos</button></td>
          </tr>
          <tr>
            <td align="center" class="texto">
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
	