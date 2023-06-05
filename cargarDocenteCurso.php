<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Colegio_DocenteCurso";

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
		DCu_Cur_ID = $("#DCu_Cur_ID").val();
		DCu_Div_ID = $("#DCu_Div_ID").val();
		if (DCu_Cur_ID==-1){
			jAlert("Error: Debe seleccionar un curso", "ERROR");
			return;
		}
		if (DCu_Div_ID==-1){
			jAlert("Error: Debe seleccionar una división", "ERROR");
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
	function llenarProyectos(vID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "llenarProyectos",  ID: vID, Nombre: "Ord_Proy_ID"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#Proyecto").html(data);
			}
		});//fin ajax//*/
		
	}//fin funcion
	
	function cargarDNI(){
		vDNI = $("#DNI").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerApellidoNombreDocente", DNI: vDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#persona").val(data);
				buscarDatos(vDNI);
			}
		});//fin ajax//*/
		
		

	}
	
	function buscarDatos(vDNI){
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarDatosDocente", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);return;
				if (data!="{}"){
					var obj = $.parseJSON(data);					
					$("#Legajo").val(obj.Doc_Legajo);
					$("#DCu_Doc_ID").val(obj.Doc_ID);
															
				}else {
					limpiarDatos();

					}
			}
		});//fin ajax//*/
	}
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		if (evento.keyCode == '13'){
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosDocente.php", {
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
			$("#DCu_Doc_ID").val(data.Doc_ID);			
			$("#mostrar").empty();
			buscarDatos(data.Per_DNI);
			
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/group.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Salitas del Docente</div></td>
      </tr>
          
          <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" value="<?php echo $_SESSION['sesion_ultimoDNI'];?>"/>
          
        *       
          <input name="DCu_Doc_ID" type="hidden" id="DCu_Doc_ID" /> 
          </td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="35" />          </td>
      </tr>
          <tr>
            <td class="texto" align="right">Ciclo Lectivo:</td>
            <td align="left"><?php 
			$UniID = $_SESSION['sesion_UniID'];
			cargarListaLectivo("DCu_Lec_ID");?><input name="opcion" type="hidden" id="opcion" value="guardar<?php echo $Tabla;?>" /></td>
        </tr>
         <tr>
         <td class="texto"><div align="right">Curso:</div></td>
         <td colspan="2"><?php cargarListaCursos("DCu_Cur_ID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td colspan="2"><?php cargarListaDivision("DCu_Div_ID", true);?> 
           
             </tr>
          <tr>
	  <td class="texto" align="right">¿Es docente especial?:</td>
          <td align="left"><select name="DCu_DocenteEspecial" id="DCu_DocenteEspecial">
            <option value="1">Si</option>
            <option value="0" selected="selected">No</option>
          </select></td>
        </tr>
          <tr>
	  <td class="texto" align="right">¿Es directora?:</td>
          <td align="left"><select name="DCu_Directora" id="DCu_Directora">
          <option value="1">Si</option>
            <option value="0" selected="selected">No</option>
          </select></td>
        </tr>
    </table>
    </form>

</div>
	<div id="divBuscador">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="iconos/mod_datos_personales.png" width="32" height="32" align="absmiddle" /> Listado de Docentes por Salita</div></td>
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
	