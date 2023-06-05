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
		if (Per_Sexo == -1){
			mostrarCartel("Seleccione el Sexo del aspirante", "ERROR");
			$("#Per_Sexo").focus();
			return;
		}
		if (!Dat_Telefono && !Dat_Celular){
			mostrarCartel("Debe escribir un número de teléfono o celular de referencia para el aspirante", "ERROR");
			$("#Dat_Telefono").focus();
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
				$("#Asp_Per_ID").val(cartel[0]);
				mostrarCartel(cartel[1], "Resultado de guardar los datos");
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
	$("#Dat_Telefono").mask("999-9999");
	$("#Dat_Celular").mask("999-999999");
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
		$("#mostrarNuevo").hide();
		$("#divBuscador").fadeIn();
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
		$("#loading").show();
		vTexto = $("#textoBuscar").val();
		Lec_ID = $("#Lec_ID").val();
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13 || vTexto.length>2) {  
			//alert("Hola " + event.keyCode);   	
			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarAspirante", Texto: vTexto, Lec_ID: Lec_ID},
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
		$("select").val(-1);
	}
	 //---------------------------
	$("#NacPaisID").val(1);
	$("#NacProID").val(2);
	$("#NacLocID").val(5);
	$("#DomPaisID").val(1);
	$("#DomProID").val(2);
	$("#DomLocID").val(5);
	
   $("#NacPaisID").change(function () {
   		$("#NacPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Nac", vPais);
        });
   })
   	// Parametros para el combo2
	$("#NacProID").change(function () {
   		$("#NacProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#NacPaisID").val();
			llenarLocalidad("Nac", vProv, vPais);
        });
   })
   $("#DomPaisID").change(function () {
   		$("#DomPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Dom", vPais);
        });
   })
   	// Parametros para el combo2
	$("#DomProID").change(function () {
   		$("#DomProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#DomPaisID").val();
			llenarLocalidad("Dom", vProv, vPais);
        });
   })

	function llenarLocalidad(vObj, vProv, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },		function(data){
     			$("#" + vObj + "LocID").html(data);
				if (vOpcion) $("#" + vObj + "LocID").attr("value",vOpcion);
   		});
	}
	function llenarProvincia(vObj, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarProvincia', Pais: vPais },function(data){
     			$("#" + vObj + "ProID").html(data);
					vProv = $("#" + vObj + "ProID").val();
					if (vOpcion)
						$("#" + vObj + "ProID").attr("value", vOpcion);
					else
						llenarLocalidad(vObj, vProv, vPais);

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
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar datos" width="48" height="48" border="0" title="Guardar datos" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
    <form id="formDatos" method="post" action="" class="cmxformNOOO">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="iconos/mod_apirante-agregar.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nuevo Aspirante </div></td>
      </tr>
     
          <tr>
            <td class="texto" align="right">Ciclo Lectivo:</td>
            <td align="left"><?php 
			//cargarListaLectivo("Asp_Lec_ID");
			$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
			cargarListaLectivoInscripcion("Asp_Lec_ID", $UniID);?></td>
          </tr>
          <td class="texto" align="right">Tipo Documento:</td>
          <td align="left"> <?php cargarListaTipoDoc("Per_Doc_ID"); ?></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Número:</td>
          <td align="left"><input name="Per_DNI" type="text" required id="Per_DNI"></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Apellidos:</td>
          <td align="left"><input name="Per_Apellido" type="text" required id="Per_Apellido" size="40">
            <input name="opcion" type="hidden" id="opcion" value="guardarAspirante" />
            <input type="hidden" name="Per_ID" id="Per_ID" />
            <input type="hidden" name="Asp_Per_ID" id="Asp_Per_ID" /></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Nombres:</td>
          <td align="left"><input name="Per_Nombre" type="text" required id="Per_Nombre" size="40"></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Sexo:</td>
          <td align="left">
          <select name="Per_Sexo" id="Per_Sexo" class="required" >
                        <option value="-1">Elegir una opción</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
          </td>
        </tr>
      
      <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 20px; padding: 0 .7em;">Datos de Nacimiento </div></td>
      </tr>
      <tr>
	  <td class="texto" align="right">Fecha de Nacimiento:</td>
          <td align="left"><input name="Dat_Nacimiento" type="text" required id="Dat_Nacimiento">
        <input name="Dat_Per_ID" id="Dat_Per_ID" type="hidden" /></td>
        </tr>
      <tr>
	  <td class="texto" align="right">País:</td>
          <td align="left"><?php cargarListaPais("NacPaisID");    ?></td>
        </tr>
        <tr>
	  <td class="texto" align="right">Provincia:</td>
          <td align="left"><?php cargarListaProvincia("NacProID",1);    ?></td>
        </tr>
         
          <tr>
	  <td class="texto" align="right">Localidad:</td>
          <td align="left"><?php cargarListaLocalidad("NacLocID",1,2);    ?></td>
        </tr>
         <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 20px; padding: 0 .7em;">Datos de Domicilio actual</div></td>
      </tr>
          <tr>
	  <td class="texto" align="right">Domicilio:</td>
          <td align="left"><input name="Dat_Domicilio" type="text" required id="Dat_Domicilio" size="60"></td>
        </tr>
        <tr>
	  <td class="texto" align="right">País:</td>
          <td align="left"><?php cargarListaPais("DomPaisID");    ?></td>
        </tr>
          <tr>
            <td class="texto" align="right">Provincia:</td>
            <td align="left"><?php cargarListaProvincia("DomProID",1);    ?></td>
        </tr>
          
          <tr>
	  <td class="texto" align="right">Localidad:</td>
          <td align="left"><?php cargarListaLocalidad("DomLocID",1,2);    ?></td>
        </tr>
           
          
          <tr>
	  <td class="texto" align="right">Código Postal:</td>
          <td align="left"><input name="Dat_CP" type="text" id="Dat_CP" size="6"></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Email (opcional):</td>
          <td align="left"><input name="Dat_Email" type="text" id="Dat_Email" size="40"></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Teléfono:</td>
          <td align="left"><input name="Dat_Telefono" type="text" id="Dat_Telefono"></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Celular:</td>
          <td align="left"><input name="Dat_Celular" id="Dat_Celular" type="text"></td>
        </tr>
          <tr>
            <td class="texto" align="right">Observaciones:</td>
            <td align="left"><textarea name="Dat_Observaciones" cols="40" rows="5" id="Dat_Observaciones"></textarea></td>
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
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="iconos/mod_apirante.png" width="32" height="32" align="absmiddle" /> Buscar Aspirantes</div></td>
          </tr>
          <tr>
            <td align="right" class="texto">Ciclo Lectivo</td>
            <td><?php cargarListaLectivo("Lec_ID", true);?></td>
          </tr>
          <tr>
            <td align="right" class="texto"><strong>Apellido/DNI</strong></td>
            <td><input name="textoBuscar" type="text" id="textoBuscar" size="60" title="Para buscar todos los registros escriba la palabra todos"/><img id="loading" src="iconos/loading.gif" width="31" height="31" style="alignment-baseline:middle; vertical-align:middle" /><button id="mostrarTodo">Cargar todos</button></td>
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
	