<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Colegio_Dimension";

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
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/group.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Dimensiones</div></td>
      </tr>
          <tr>
            <td class="texto" align="right">Ciclo Lectivo:</td>
            <td align="left"><?php 
			$UniID = $_SESSION['sesion_UniID'];
			cargarListaLectivoInscripcion("Dim_Lec_ID", $UniID);?><input name="opcion" type="hidden" id="opcion" value="guardarDimension" /></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Dim_ID:</td>
          <td align="left"><input name="Dim_ID" type="text" id="Dim_ID" size="5" maxlength="3" readonly="readonly"></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Nombre de la Dimensión:</td>
          <td align="left"><input name="Dim_Nombre" type="text" id="Dim_Nombre" size="60" maxlength="100" required="required"></td>
      </tr>
          <tr>
	  <td class="texto" align="right">Detalle:</td>
          <td align="left">
            <textarea name="Dim_Detalle" id="Dim_Detalle" cols="45" rows="5"></textarea></td>
        </tr>
          <tr>
	  <td class="texto" align="right">¿Es Transversal?:</td>
          <td align="left">
            <select name="Dim_Transversal" id="Dim_Transversal">
              <option value="1">Si</option>
              <option value="0" selected="selected">No</option>
            </select>
          <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>
    </form>

</div>
	<div id="divBuscador">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="iconos/mod_datos_personales.png" width="32" height="32" align="absmiddle" /> Listado de Dimensiones</div></td>
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
	