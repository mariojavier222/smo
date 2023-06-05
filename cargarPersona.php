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
		Dat_Telefono = $("#Dat_Telefono").val();
		Dat_Celular = $("#Dat_Celular").val();
		Per_Sexo = $("#Per_Sexo").val();
		Dat_Nacimiento = $("#Dat_Nacimiento").val();
		if (Per_Sexo == -1){
			mostrarCartel("Seleccione el Sexo de la persona", "ERROR");
			$("#Per_Sexo").focus();
			return;
		}
		if (!Dat_Telefono && !Dat_Celular){
			mostrarCartel("Debe escribir un número de teléfono o celular de referencia para la persona", "ERROR");
			$("#Dat_Telefono").focus();
			return;
		} 

		if (Dat_Nacimiento=='00/00/0000' || Dat_Nacimiento=='0000/00/00'){
			mostrarCartel("Debe escribir una Fecha válida", "ERROR");
			$("#Dat_Nacimiento").focus();
			return;
		} 

		//alert("voy bien");
		datos = $("#formDatos").serialize();
		$.ajax({ 
			type: "POST",
			cache: false,
			async: false,			
				url: 'cargarOpciones.php',
			data: datos,
			success: function (data){
				cartel = data.split("/");
				$("#Per_ID").val(cartel[0]);

				$("#Dat_Per_ID").val(cartel[0]);
				mostrarCartel(cartel[1], "Resultado de guardar los datos");
				//$("#barraGuardar").removeAttr("disabled");
			}
		});//fin ajax
	}
});
$(document).ready(function(){
	
	//Datos iniciales
	$("#loading").hide();
	$("#Per_DNI").focus();
	/*$("#Dat_Nacimiento").datepicker({
				dateFormat:'dd/mm/yy',maxDate:new Date('<?php echo date("Y-m-d");?>')
				});*/
	$("#Dat_Nacimiento").mask("99/99/9999");
	//$("#Dat_Telefono").mask("999-9999");
	//$("#Dat_Celular").mask("999-9999999");
	// validate the comment form when it is submitted
	//$("#formDatos").validate();
	$("#formDatos").validate({
		rules: {
			Dat_Email: {
				//required: true,
				email: true
			},
			Per_DNI: {
				digits: true
			}
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
	
	$("#Per_DNI").focusout(function(event){
		event.preventDefault();
		//alert("");
		$("#loading").show();
		Per_DNI = $("#Per_DNI").val();
		Per_ID = $("#Per_ID").val();
		if (Per_ID == ""){	
		
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarDNIRepetido", Per_DNI: Per_DNI},
				url: 'cargarOpciones.php',
				success: function(data){ 
					data=data.trim();
					//alert(data);
					if (data.length >0){
						jAlert(data, "DNI incorrecto");
						$("#barraGuardar").hide();
					}else{
						$("#barraGuardar").show();
					}				
					$("#loading").hide();
				}
			});//fin ajax//*/  	
		}//fin if

	});
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			url: 'cargarPersona.php',
			success: function(data){ 
				$("#principal").html(data);
			}
		});//fin ajax//*/
		/*$("#barraGuardar").show();				
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		limpiarDatos();
		$("#barraGuardar").removeAttr("disabled");*/
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		//$("#barraGuardar").attr("disabled","disabled");
		$("#formDatos").submit();
		//$("#barraGuardar").attr("disabled", "disabled");
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
		$("#mostrarResultado").empty();
		$("#mostrarNuevo").hide();
		$("#divBuscador").fadeIn();
		$("#textoBuscar").keyup();
		$("#loading").hide();
		//recargarPagina()
	});
	<?php
	if (isset($_POST[textoBuscar])){
		echo "$('#barraGuardar').hide();";
		echo "$('#mostrarNuevo').hide();";
		echo "$('#divBuscador').fadeIn();";
		
		echo "$('#textoBuscar').val('".$_POST[textoBuscar]."');";
		echo "$('#textoBuscar').keyup();";
		//echo "$('#mostrarTodo').click();";
	}
	?>
	$("#mostrarTodo").click(function(evento){
		evento.preventDefault();
		$("#textoBuscar").val("todos");
		$("#textoBuscar").keyup();
	});
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
					data: {opcion: "buscarPersonaNueva", Texto: vTexto},
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

	function limpiarDatos(){
		$("input").val("");
		$("textarea").val("");
		$("select").val(-1);
		$("#NacPaisID").val(1);
		$("#NacProID").val(19);
		$("#NacLocID").val(1735);
		$("#DomPaisID").val(1);
		$("#DomProID").val(19);
		$("#DomLocID").val(1735);
	}
	 //---------------------------

	$("#NacPaisID").val(1);
	$("#NacProID").val(19);
	$("#NacLocID").val(1735);
	$("#DomPaisID").val(1);
	$("#DomProID").val(19);
	$("#DomLocID").val(1735);
	
   $("#NacPaisID").change(function () {
   		$("#NacPaisID option:selected").each(function () {
				//alert($(this).val());
				vPais=$(this).val();
				llenarProvinciaPer("Nac", vPais);
      });
   })
   	// Parametros para el combo2
	$("#NacProID").change(function () {
   		$("#NacProID option:selected").each(function () {
				//alert($(this).val());
				vProv=$(this).val();
				vPais = $("#NacPaisID").val();
				llenarLocalidadPer("Nac", vProv, vPais);
      });
   })

   $("#DomPaisID").change(function () {
   		$("#DomPaisID option:selected").each(function () {
				//alert($(this).val());
				vPais=$(this).val();
				llenarProvinciaPer("Dom", vPais);
      });
   })
   	// Parametros para el combo2
	$("#DomProID").change(function () {
   		$("#DomProID option:selected").each(function () {
				//alert($(this).val());
				vProv=$(this).val();
				vPais = $("#DomPaisID").val();
				llenarLocalidadPer("Dom", vProv, vPais);
      });
   })


   $("#NacPaisID").click(function () {
   		$("#NacPaisID option:selected").each(function () {
				//alert($(this).val());
				vPais=$(this).val();
				llenarProvinciaPer("Nac", vPais);
      });
   })
   	// Parametros para el combo2
	$("#NacProID").click(function () {
   		$("#NacProID option:selected").each(function () {
				//alert($(this).val());
				vProv=$(this).val();
				vPais = $("#NacPaisID").val();
				llenarLocalidadPer("Nac", vProv, vPais);
      });
   })

   $("#DomPaisID").click(function () {
   		$("#DomPaisID option:selected").each(function () {
				//alert($(this).val());
				vPais=$(this).val();
				llenarProvinciaPer("Dom", vPais);
      });
   })
   	// Parametros para el combo2
	$("#DomProID").click(function () {
   		$("#DomProID option:selected").each(function () {
				//alert($(this).val());
				vProv=$(this).val();
				vPais = $("#DomPaisID").val();
				llenarLocalidadPer("Dom", vProv, vPais);
      });
   })

	function llenarLocalidadPer(vObj, vProv, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },		function(data){
     			$("#" + vObj + "LocID").html(data);
				if (vOpcion) $("#" + vObj + "LocID").attr("value",vOpcion);
   		});
	}

	function llenarProvinciaPer(vObj, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarProvincia', Pais: vPais },function(data){
     			$("#" + vObj + "ProID").html(data);
					vProv = $("#" + vObj + "ProID").val();
					if (vOpcion)
						$("#" + vObj + "ProID").attr("value", vOpcion);
					else
						llenarLocalidadPer(vObj, vProv, vPais);

   			});
	}	
   //---------------------------
	 
});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Agregar un nuevo Registro" width="48" height="48" border="0" title="Agregar un nuevo Registro" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Registros" width="48" height="48" border="0" title="Buscar Registros" /><br />
      Listar</button></td>
          <td width="48"><button  class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar datos" width="48" height="48" border="0" title="Guardar datos" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
    <form id="formDatos" method="post" action="" class="cmxformNOOO">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="iconos/mod_apirante-agregar.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nueva Persona </div></td>
      </tr>
          <td class="texto" align="right">Tipo Documento:</td>
          <td align="left"> <?php cargarListaTipoDoc("Per_Doc_ID"); ?> *</td>
        </tr>
          <tr>
	  <td class="texto" align="right">Número:</td>
          <td align="left"><input name="Per_DNI" type="number" required id="Per_DNI" min="5000000" max="99999999"> *</td>
        </tr>
          <tr>
	  <td class="texto" align="right">Apellidos:</td>
          <td align="left"><input name="Per_Apellido" type="text" required id="Per_Apellido" size="40"> *
            <input name="opcion" type="hidden" id="opcion" value="guardarPersonaNueva" />
            <input type="hidden" name="Per_ID" id="Per_ID" />
           </td>
        </tr>
          <tr>
	  <td class="texto" align="right">Nombres:</td>
          <td align="left"><input name="Per_Nombre" type="text" required id="Per_Nombre" size="40"> *</td>
        </tr>
          <tr>
	  <td class="texto" align="right">Sexo:</td>
          <td align="left">
          <select name="Per_Sexo" id="Per_Sexo" class="required" >
                        <option value="-1">Elegir una opción</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
						<option value="X">No Binario (X)</option>
                    </select> *
          </td>
        </tr>
      
      <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 20px; padding: 0 .7em;">Datos de Nacimiento </div></td>
      </tr>
      <tr>
	  <td class="texto" align="right">Fecha de Nacimiento:</td>
          <td align="left"><input name="Dat_Nacimiento" type="text" required id="Dat_Nacimiento"> *
        <input name="Dat_Per_ID" id="Dat_Per_ID" type="hidden" /></td>
        </tr>
      <tr>
	  <td class="texto" align="right">País:</td>
          <td align="left"><?php cargarListaPais("NacPaisID");    ?> *</td>
        </tr>
        <tr>
	  <td class="texto" align="right">Provincia:</td>
          <td align="left"><?php cargarListaProvincia("NacProID",1);    ?> *</td>
        </tr>
         
          <tr>
	  <td class="texto" align="right">Localidad:</td>
          <td align="left"><?php cargarListaLocalidad("NacLocID",1,19);    ?> *</td>
        </tr>
         <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 20px; padding: 0 .7em;">Datos de Domicilio actual</div></td>
      </tr>
          <tr>
	  <td class="texto" align="right">Domicilio:</td>
          <td align="left"><input name="Dat_Domicilio" type="text" required id="Dat_Domicilio" size="60"> *</td>
        </tr>
        <tr>
	  <td class="texto" align="right">País:</td>
          <td align="left"><?php cargarListaPais("DomPaisID");    ?> *</td>
        </tr>
          <tr>
            <td class="texto" align="right">Provincia:</td>
            <td align="left"><?php cargarListaProvincia("DomProID",1);    ?> *</td>
        </tr>
          
          <tr>
	  <td class="texto" align="right">Localidad:</td>
          <td align="left"><?php cargarListaLocalidad("DomLocID",1,19);    ?> *</td>
        </tr>
       <tr>
	  <td class="texto" align="right">Código Postal:</td>
          <td align="left"><input name="Dat_CP" type="text" id="Dat_CP" size="6" required> *</td>
        </tr>
          <tr>
	  <td class="texto" align="right">Email:</td>
          <td align="left"><input name="Dat_Email" type="text" id="Dat_Email" size="40" required> *</td>
        </tr>
          <tr>
	  <td class="texto" align="right">Teléfono:</td>
          <td align="left"><input name="Dat_Telefono" type="text" id="Dat_Telefono"></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Celular:</td>
          <td align="left"><input name="Dat_Celular" id="Dat_Celular" type="text" required> *</td>
        </tr>
        <td class="texto" align="right">Ocupación:</td>
        <td align="left"><input name="Dat_Ocupacion" id="Dat_Ocupacion" type="text" size="60"></td>
      </tr>
      <tr>
	  	<td class="texto" align="right">Persona que retira al alumno:</td>
        <td align="left"><input name="Dat_Retira" id="Dat_Retira" type="text" size="60"></td>
      </tr>
          <tr>
            <td class="texto" align="right">Observaciones:</td>
            <td align="left"><textarea name="Dat_Observaciones" cols="40" rows="5" id="Dat_Observaciones"></textarea></td>
        </tr>
      <tr>
        <td colspan="2" class="texto" align="center">* Datos obligatorios</td>
      </tr>
    </table>
    </form>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="iconos/mod_apirante.png" width="32" height="32" align="absmiddle" /> Buscar Personas</div></td>
          </tr>
          <tr>
            <td align="right" class="texto"></td>
            <td align="left" class="texto">Ingrese al menos cuatro carácteres o dígitos para buscar. <br>Puede buscar por Apellido y Nombre de esta forma: <i>Apellido, Nombre</i>. Ej. <i>Perez, Ju</i></td>
          </tr>
          <tr>
            <td align="right" class="texto"><strong>Apellido/DNI</strong></td>
            <td><input name="textoBuscar" type="text" id="textoBuscar" size="60" title="Para buscar todos los registros escriba la palabra todos"/><img id="loading" src="iconos/loading.gif" width="31" height="31" style="alignment-baseline:middle; vertical-align:middle" />&nbsp;<button id="mostrarTodo">Cargar todos</button></td>
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
	