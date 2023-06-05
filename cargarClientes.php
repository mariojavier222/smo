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
$.validator.setDefaults({
	submitHandler: function() { 
		Cli_Iva_ID = $("#Cli_Iva_ID").val();
		if (Cli_Iva_ID==-1){
			mostrarCartel("Debe seleccionar una condicion de IVA para el Cliente", "ERROR");
			return;
		}
		datos = $("#formDatos").serialize();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
				url: 'cargarOpciones.php',
			data: datos,
			success: function (data){
				mostrarCartel(data, "Resultado de guardar los datos");
			}
		});//fin ajax
	}
});
$(document).ready(function(){
	
	//Datos iniciales
	$("#loading").hide();
	$("#Cli_FechaAlta").datepicker({
				dateFormat:'dd/mm/yy',maxDate:new Date('<?php echo date("Y-m-d");?>')
				});
	$("#Cli_CUIT").mask("99-99999999-9");
	// validate the comment form when it is submitted
	//$("#formDatos").validate();
	$("#formDatos").validate({
		rules: {
			Cli_Email: {
				//required: true,
				email: true
			},
			Cli_Web: {
				url: true
			}/*,
			Cli_FechaAlta: {
				date: true
			}*/
		}
	});//fin validation
		
	
	
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
		$("#barraGuardar").removeAttr("disabled");
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		$("#formDatos").submit();
		$("#barraGuardar").attr("disabled", "disabled");
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
		//recargarPagina()
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
				data: {opcion: "buscarClientes", Texto: vTexto},
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/Profile.png" alt="Paises" width="48" height="48" align="absmiddle" /> Cargar Nuevo Cliente </div></td>
      </tr>
      <tr>
	  <td class="texto"><div align="right">Nombre comercial o de fantas&iacute;a:</div></td>
          <td><input name="Cli_NombreFantasia" type="text" required id="Cli_NombreFantasia" size="40" maxlength="100"/>
          <input name="opcion" type="hidden" id="opcion" value="guardarCliente" />
          <input type="hidden" name="Cli_ID" id="Cli_ID" /></td>
        </tr>
        <tr>
          <td align="right" class="texto">Condici&oacute;n del IVA:</td>
          <td><?php cargarListaIVA("Cli_Iva_ID");?></td>
      </tr>
          <tr>
	  <td class="texto" align="right">Raz&oacute;n Social:</td>
          <td align="left"><input name="Cli_RazonSocial" type="text" required id="Cli_RazonSocial" size="40" maxlength="100"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Persona de Contacto:</td>
          <td align="left"><input name="Cli_Contacto" type="text" required id="Cli_Contacto"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">C.U.I.T.:</td>
          <td align="left"><input name="Cli_CUIT" type="text" required id="Cli_CUIT"/></td>
        </tr>
          <tr>
            <td class="texto" align="right" title="Si el cliente realiza alguna retención sobre las facturas presendtadas, marcar esta casilla en SI, ej: Ingresos brutos, iva, ganancias, lote hogar, etc.">¿Hace retención?</td>
            <td align="left"><select name="Cli_Retencion" id="Cli_Retencion">
              <option value="0" selected="selected">No</option>
              <option value="1">Si</option>
            </select></td>
          </tr>
          <tr>
	  <td class="texto" align="right">Fecha de Alta:</td>
          <td align="left"><input name="Cli_FechaAlta" type="text" id="Cli_FechaAlta" title="Si se omite la fecha de alta se tomar&aacute; como fecha el d&iacute;a de hoy"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Tel&eacute;fonos:</td>
          <td align="left"><input name="Cli_Telefono" type="text" required id="Cli_Telefono" maxlength="50"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Celular:</td>
          <td align="left"><input name="Cli_Celular" type="text" id="Cli_Celular"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Domicilio:</td>
          <td align="left"><input name="Cli_Domicilio" type="text" id="Cli_Domicilio" size="40" maxlength="150"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Domicilio de Facturaci&oacute;n:</td>
          <td align="left"><input name="Cli_DomFacturacion" type="text" id="Cli_DomFacturacion" size="40" maxlength="150" required/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Fax:</td>
          <td align="left"><input name="Cli_Fax" type="text" id="Cli_Fax" maxlength="50"/></td>
        </tr>
        <tr>
	  <td class="texto" align="right">Localidad:</td>
          <td align="left"><input name="Cli_Localidad" type="text" id="Cli_Localidad" maxlength="100"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Provincia:</td>
          <td align="left"><input name="Cli_Provincia" type="text" id="Cli_Provincia" value="San Juan" maxlength="100"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Pa&iacute;s:</td>
          <td align="left"><input name="Cli_Pais" type="text" id="Cli_Pais" value="Argentina" maxlength="100"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">E-mail:</td>
          <td align="left"><input name="Cli_Email" type="text" id="Cli_Email" size="40" maxlength="100"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">P&aacute;gina Web:</td>
          <td align="left"><input name="Cli_Web" type="text" id="Cli_Web" size="40" maxlength="100"/></td>
        </tr>
          <!--<tr>
	  <td class="texto" align="right">Cli_Color:</td>
          <td align="left"><input name="Cli_Color" type="text" id="Cli_Color"/></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Cli_Foto:</td>
          <td align="left"><input name="Cli_Foto" type="text" id="Cli_Foto"/></td>
        </tr>-->
          <tr>
	  <td class="texto" align="right">Comentario adicional:</td>
          <td align="left">
            <textarea name="Cli_Comentario" id="Cli_Comentario" cols="45" rows="5"></textarea></td>
        </tr>
          

      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>
    </form>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/group.png" width="32" height="32" align="absmiddle" /> Buscar Clientes</div></td>
          </tr>
          <tr>
            <td align="right"><strong>Nombre comercial/Raz&oacute;n Social/C.U.I.T.</strong></td>
            <td><input name="textoBuscar" type="text" id="textoBuscar" size="60" title="Para buscar todos los registros escriba la palabra todos"/><img id="loading" src="imagenes/loading.gif" width="31" height="31" style="alignment-baseline:middle; vertical-align:middle" /><button id="mostrarTodo">Cargar todos</button></td>
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
	