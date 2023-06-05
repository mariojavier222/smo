<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Colegio_AmbitoInforme";

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
		Inf_Leg_ID = $("#Inf_Leg_ID").val();
		if (Inf_Leg_ID==-1){
			jAlert("Error: Debe seleccionar un alumno", "ERROR");
			return;
		}
		Inf_Amb_ID = $("#Inf_Amb_ID").val();
		if (Inf_Amb_ID==-1){
			jAlert("Error: Debe seleccionar un ámbito", "ERROR");
			return;
		}
		Inf_Detalle = $("#Inf_Detalle").val();
		if (Inf_Detalle==""){
			jAlert("Error: Debe escribir un informe", "ERROR");
			return;
		}
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
		/*$.ajax({
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
	$("#mostrarAceptados").click(function(evento){
		evento.preventDefault();
		$("#loading").show();
		Curso2 = $("#Curso2").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Aceptados: true, Curso2: Curso2},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					$("#loading").hide();
				}
			});//fin ajax//*/
	});
	$("#mostrarCorregidos").click(function(evento){
		evento.preventDefault();
		$("#loading").show();
		Curso2 = $("#Curso2").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Corregidos: true, Curso2: Curso2},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					$("#loading").hide();
				}
			});//fin ajax//*/
	});
	$("#textoBuscar").keyup(function(event){
		event.preventDefault();
		$("#loading").show();
		vTexto = $("#textoBuscar").val();
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13 || vTexto.length>2) {  
			//alert("Hola " + event.keyCode);   	
			Curso2 = $("#Curso2").val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Texto: vTexto, Curso2: Curso2},
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
	
	$("#Inf_Dim_ID").change(function () {
   		$("#Inf_Dim_ID option:selected").each(function () {
			//alert($(this).val());
				Inf_Dim_ID=$(this).val();
				llenarAmbito(Inf_Dim_ID);
				//llenarProyectos(Ord_Cli_ID);
        });
   	})
	function llenarAmbito(vID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaAmbito",  ID: vID, Nombre: "Inf_Amb_ID"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#Ambito").html(data);
			}
		});//fin ajax//*/
		
		
	}//fin funcion
	$("#Curso").change(function () {
   		$("#Curso option:selected").each(function () {
			//alert($(this).val());
				Curso=$(this).val();
				llenarAlumnos(Curso);
        });
   	});
	Curso=$("#Curso option:selected").val();
	if (Curso!=-1) llenarAlumnos(Curso);
	function llenarAlumnos(vID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaAlumnosClase2",  ID: vID, Nombre: "Inf_Leg_ID"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#Alumnos").html(data);
			}
		});//fin ajax//*/
		
	}//fin funcion
	 
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/group.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Informe</div></td>
      </tr>
          <tr>
            <td class="texto" align="right">Curso/División</td>
            <td align="left" class="titulo_noticia">
			
			<?php 
			cargarCursoDivisionNivelInicial("Curso");			
			?></td>
          </tr>
          <tr>
            <td class="texto" align="right">Alumnos:</td>
            <td align="left"><div id="Alumnos">
            </div></td>
          </tr>
          <tr>
            <td class="texto" align="right">Ciclo Lectivo:</td>
            <td align="left"><?php 
			$UniID = $_SESSION['sesion_UniID'];
			cargarListaLectivoInscripcion("Inf_Lec_ID", $UniID);?><input name="opcion" type="hidden" id="opcion" value="guardar<?php echo $Tabla;?>" /></td>
        </tr>
        <tr>
            <td class="texto" align="right">Dimensión:</td>
            <td align="left"><?php 		
			cargarListaDimension("Inf_Dim_ID");?></td>
        </tr>
        <tr>
            <td class="texto" align="right">Ámbito:</td>
            <td align="left"><div id="Ambito"> <?php cargarListaAmbito("Inf_Amb_ID", 6);?>
            </div></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Informe:</td>
          <td align="left">
            <textarea name="Inf_Detalle" id="Inf_Detalle" cols="60" rows="8"></textarea></td>
        </tr>
          <tr>
	  <td class="texto" align="right">:</td>
          <td align="left">
            <input name="Inf_Aceptado" id="Inf_Aceptado" type="hidden" />
            <input name="Inf_Corregido" id="Inf_Corregido" type="hidden" />
           <input name="Inf_Doc_ID" type="hidden" id="Inf_Doc_ID" value="<?php echo $_SESSION['Doc_ID'];?>" /></td>
        </tr>
    </table>
    </form>

</div>
	<div id="divBuscador">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="iconos/mod_datos_personales.png" width="32" height="32" align="absmiddle" /> Listado de Informes</div></td>
          </tr>
          <tr>
            <td colspan="2" align="right" class="texto">&nbsp;</td>
          </tr>
          <tr>
            <td align="right" class="texto"><strong>Buscar por Alumno/Curso:</strong></td>
            <td><input name="textoBuscar" type="text" id="textoBuscar" size="60" title="Para buscar todos los registros escriba la palabra todos"/><img id="loading" src="imagenes/loading.gif" width="31" height="31" style="alignment-baseline:middle; vertical-align:middle" /></td>
          </tr>
          <tr>
            <td align="right" class="texto">Curso/División:</td>
            <td><?php 
			cargarCursoDivisionNivelInicial("Curso2");			
			?></td>
          </tr>
          <tr>
            <td align="right" class="texto">&nbsp;</td>
            <td><button id="mostrarTodo">Ver todos</button>
            <button id="mostrarAceptados">Ver Aceptados</button>
            <button id="mostrarCorregidos">Ver Falta Corregir</button>
            </td>
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
