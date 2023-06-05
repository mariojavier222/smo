<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$(document).ready(function(){
	
	<?php 
	if (isset($_SESSION['sesion_UsuID']))
		echo "vUsuID = ".$_SESSION['sesion_UsuID'];
	else
		echo "document.location = 'index.php'";//*/
	?>;
	<?php if (isset($_POST['DNI'])){
		$DNI = $_POST['DNI'];
		echo "$('#DNI').val($DNI);";
		echo "cargarDNI();";

	}
	?>
	//vUsuID = 0;
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
	$("#barraCuotas").click(function(evento){
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				mostrarAlerta("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					mostrarAlerta("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				mostrarAlerta("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					mostrarAlerta("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {UsuID: vUsuID, PerID: vPerID},
			url: 'MostrarAsignarCuotas.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosPersona.php", {
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
			$("#mostrar").empty();
		}
	}
	$("#msj_para_ie").result(colocarValor);	
	$(".botones").button();
	$("#barraVolver").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI_Volver").val();
		vPerID= $("#PerID").val();
		vPagVolver = $("#pag_Volver").val() + ".php";
		//alert(vDNI.length);
		if (vDNI.length>0){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: vPagVolver,
				data: {DNI: vDNI,PerID:vPerID},
				success: function (data){
						$("#principal").html(data);
						$("#cargando").hide();
						}
			});//fin ajax
		}//fin if
	});
	$(".ocultar").hide();
	$("#esUniversitario").click(function(evento){
		//evento.preventDefault();
		//alert("mmm");
		vContador = $("input:checked").length;
		if (vContador==0){
			$(".ocultar").hide();			
		}else{			
			$(".ocultar").show();
			$("#personaSIUCC").focusin();
		}//fin if//*/
	});
	$("#personaSIUCC").result(colocarValorSIUCC);
	$("#personaSIUCC").autocomplete("buscarDatosPersonaSIUCC.php", {
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
	
	function colocarValorSIUCC(event, data, formatted) {
		if (data){
			$("#DNISIUCC").val(data.Per_ID);
			$("#DNI").val(data.Per_ID);
			$("#mostrar").empty();
		}
	}
});//fin de la funcion ready


</script>

<div id="mostrarCuotas"> 

	<p>&nbsp;</p>
	<table width="90%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2" >		
		<div align="center" class="titulo_noticia"><img src="imagenes/table_cuotas.png" width="48" height="48" align="absmiddle" /> Asignar Cuotas Persona</div></td>
      </tr>
	  <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" />
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" /> <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/></td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="35" />          </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraCuotas">Mostrar Cuotas</button><?php if (isset($_POST['DNI'])){?>
<button style="width:48px" class="barra_boton" id="barraVolver"><img src="imagenes/go-previous.png" alt="Volver atr�s" title="Volver atr�s" width="22" height="22" border="0" /><br />
      Volver</button>
<?php }//fin if?></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	<div id="mostrar" class="texto"></div>