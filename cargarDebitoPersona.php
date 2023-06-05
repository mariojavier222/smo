<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "DebitoPersona";

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="plugin/css/screen.css" />

<script src="plugin/jquery.validate.js"></script>
<script src="plugin/localization/messages_es.js"></script>
<script src="plugin/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

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
				/*ID = $("#Cue_CuT_ID option:selected").val();
				$.post("cargarOpciones.php", { opcion: 'cargarListaCuentaTipoInterior', orden: "nombre" }, function(data){
					//alert(data);
	     			$("#Cue_CuT_ID").html(data);
	     			$("#Cue_CuT_ID").val(ID);
		   		});*/
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
		if (event.keyCode == 13 || vTexto.length>=2) {  
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

	function cargarDNI(){
		vDNI = $("#DNI").val();
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerApellidoNombre", DNI: vDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#persona").val(data);
				//alert("no entre");
			}
		});//fin ajax//*/
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarDNIPadre", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#PersonaDatos").show();
				$("#PersonaDatos").html(data);
				//alert("no entre");
			}
		});//fin ajax//*/
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarPerID", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#PerID").val(data);				
				//alert("no entre");
			}
		});//fin ajax//*/

	}
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		//alert("Enter");
		if (evento.keyCode == '13'){
			
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosPadre.php", {
		//multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,		
		formatItem:function(item, index, total, query){
		   return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatMatch:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatResult:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },

		dataType: "json",
		//parse: prep_data,
		parse:function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.Per_Apellido,
					result: row.Per_Apellido + ", " + row.Per_Nombre
				}
				$("#cargando").hide();
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	
	function colocarValor(event, data, formatted) {
		if (data){
			$("#DNI").val(data.Per_DNI);
			$("#PerID").val(data.Per_ID);
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarDNIPadre", DNI: data.Per_DNI},
				url: 'cargarOpciones.php',
				success: function(data){ 
					$("#PersonaDatos").show();
					$("#PersonaDatos").html(data);
					//alert("no entre");
				}
			});//fin ajax//*/			
			$("#mostrar").empty();
		}
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
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"> Adherir Padres al Débito Automático</div></td>
      </tr>          
          <tr>
      <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" />
        *       
          <input name="PerID" type="hidden" id="PerID" />  </td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Padre/Madre   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="50" />
          </td>
      </tr>
      <tr>
        <td colspan="2" class="texto">        
        <div id="PersonaDatos"></div></td>
      </tr>    	
	  <td class="texto" align="right">Titular de la tarjeta/cuenta:</td>
          <td align="left">
          	<input name="Deb_TarjetaTitular" type="text" id="Deb_TarjetaTitular" size="40">
          	<input name="Deb_ID" type="hidden" id="Deb_ID" size="10">
            <input name="opcion" type="hidden" id="opcion" value="guardar<?php echo $Tabla;?>" />
          </td>
        </tr>
        <tr>
	  <td class="texto" align="right">Número de Tarjeta:</td>
          <td align="left"><textarea name="Deb_TarjetaNumero" id="Deb_TarjetaNumero"></textarea></td>
        </tr>
        <!-- <tr>
	  <td class="texto" align="right">Código:</td>
          <td align="left"><input name="Deb_Codigo" type="text" id="Deb_Codigo" size="10"></td>
        </tr> -->
        <tr>
	  <td class="texto" align="right">Tipo de Tarjeta/Cuenta:</td>
          <td align="left"><?php cargarListaDebitoTarjeta("Deb_DTa_ID");?></td>
        </tr> 

    </table>
    </form>

</div>
	<div id="divBuscador">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Cuentas Contables</div></td>
          </tr>
          <tr>
            <td align="right" class="texto"><strong>Cuenta/Código a buscar</strong><input name="textoBuscar" type="text" id="textoBuscar" size="60" title="Para buscar todos los registros escriba la palabra todos"/><img id="loading" src="iconos/loading.gif" width="31" height="31" style="alignment-baseline:middle; vertical-align:middle" /><button id="mostrarTodo">Cargar todos</button></td>
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
	