<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Entrevista";
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
		
		//alert("voy bien");
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
	<?php
	if (isset($_POST[Per_ID])){
	?>
		buscarDatos(<?php echo $_POST[Per_ID];?>);	
	<?php
	}//fin if
	?>
	
	$("#Ent_Fecha").mask("99/99/9999");
	$("#Ent_Hora").mask("99:99");
	
	// validate the comment form when it is submitted
	//$("#formDatos").validate();
	$("#formDatos").validate({
		rules: {
			Dat_Email: {
				//required: true,
				email: true
			},
			Dat_Nacimiento: {
				date: true
			},
			Per_DNI: {
				digital: true
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
	$("#barraVolver").click(function(evento){
		evento.preventDefault();
		textoBuscar = $("#textoBuscar").val();
		Ent_Per_ID = $("#Ent_Per_ID").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {Per_ID: Ent_Per_ID, textoBuscar: textoBuscar},
			url: 'cargarAspirante.php',
			success: function(data){ 
				//alert(data);
				//$("#mostrarResultado").html(data);
				$("#principal").html(data);
				$("#loading").hide();
			}
		});//fin ajax//*/
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
		 
});//fin de la funcion ready

function buscarDatos(Ent_per_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatosArreglo<?php echo $Tabla;?>', Ent_per_ID: Ent_per_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Ent_per_ID").val(obj.Ent_per_ID);
				$("#Ent_Sic_ID").val(obj.Ent_Sic_ID);
				$("#Ent_Turno").val(obj.Ent_Turno);
				$("#Ent_Fecha").val(obj.Ent_Fecha);
				$("#Ent_Hora").val(obj.Ent_Hora);
				$("#Ent_Asistio").val(obj.Ent_Asistio);
				$("#Ent_Estado").val(obj.Ent_Estado);
				
				
			}//fin if
		});		
	}//fin function
</script>

<table border="0" align="center" cellspacing="4">
      <tr>
               
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar datos" width="48" height="48" border="0" title="Guardar datos" /><br />Guardar</button></td>
      
      <td width="48"><button class="barra_boton"  id="barraVolver"> <img src="imagenes/go-previous.png" alt="Buscar Registros" width="48" height="48" border="0" title="Buscar Registros" /><br />
      Volver</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
    <form id="formDatos" method="post" action="" class="cmxformNOOO">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="iconos/mod_pendiente.png" alt="Paises" width="32" height="32" align="absmiddle" /> Entrevista de <?php 
		
		$Per_ID = $_POST[Per_ID];
		$textoBuscar = $_POST[textoBuscar];
		$DNI = gbuscarDNI($Per_ID);
		$foto = buscarFoto($DNI, 1, 60);
		gObtenerApellidoNombrePersona($Per_ID, $Apellido, $Nombre, true);
		echo "$foto $Apellido, $Nombre";?></div></td>
      </tr>
          <tr>
            <td class="texto" align="right">Psicopedagoga:</td>
            <td align="left"><?php cargarListaSicopedagogas("Ent_Sic_ID");?>
            <input name="Ent_per_ID" id="Ent_per_ID" type="hidden" value="<?php echo $Per_ID;?>" />
            <input name="opcion" id="opciones" type="hidden" value="guardarNuevaEntrevista" />
            <input name="textoBuscar" id="textoBuscar" type="hidden" value="<?php echo $textoBuscar;?>" /></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Turno:</td>
          <td align="left"><select id="Ent_Turno" name="Ent_Turno">
        <option value="Mañana">Mañana</option> 
          <option value="Tarde">Tarde</option> 
        
      </select></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Fecha:</td>
          <td align="left"><input name="Ent_Fecha" id="Ent_Fecha" type="text" required></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Hora:</td>
          <td align="left"><input name="Ent_Hora" id="Ent_Hora" type="text" required></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Asistió:</td>
          <td align="left"><select id="Ent_Asistio" name="Ent_Asistio">
            <option value='0'>NO</option> 
            <option value='1'>SI</option> 
            </select></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Estado Final de la Entrevista:</td>
          <td align="left"><select id="Ent_Estado" name="Ent_Estado">
                       <option value='0'>NO</option> 
                       <option value='1'>SI</option> 
       </select></td>
        </tr>
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>
    </form>
</div>
	<p><br />
	  <br />
    </p>
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	