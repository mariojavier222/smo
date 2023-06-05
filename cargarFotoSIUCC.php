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
<script language="javascript">
$(document).ready(function(){

	function cargarDNI(){
		vDNI = $("#DNI").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerApellidoNombreSIUCC", DNI: vDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#persona").val(data);
			}
		});//fin ajax//*/

	}
	function cargarTipoDoc(){
		vDNI = $("#DNI").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerTipoDoc", DNI: vDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#DocID").val(data);
			}
		});//fin ajax//*/

	}
	function cargarFoto(){
		vDNI = $("#DNI").val();
		vDocID = $("#DocID").val();
		$.ajax({
			type: "POST",
			cache: true,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerFotoSIUCC", DNI: vDNI, DocID: vDocID, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				//$("#foto").html(data);
				$("#fotoAlu").attr("src", data);
			}
		});//fin ajax//*/

	}

	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		if (evento.keyCode == '13'){
			$("#mostrar").empty();
			$("#mostrar2").html('<iframe width="800" height="800" scrolling="auto" frameborder="0" id="webFoto" name="webFoto"></iframe>');
			$("#cargando").show();
			cargarDNI();
			cargarTipoDoc();
			cargarFoto();
			$("#cargando").hide();
		}
	});

	$("#buscaFoto").click(function(evento){
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		$("#mostrar").empty();
		$("#mostrar2").html('<iframe width="800" height="800" scrolling="auto" frameborder="0" id="webFoto" name="webFoto"></iframe>');
		$("#cargando").show();
		cargarDNI();
		cargarTipoDoc();
		cargarFoto();
		$("#cargando").hide();
	});

	$("#barraFoto").click(function(evento){

		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vDocID = $("#DocID").val();
		vFoto = "";
		if ($.browser.msie) {
			if (vDNI==null){
				mostrarAlerta("Antes de ingresar debe escribir un DNI","Atención");
				return false;
			}
			if (vPersona=="NO EXISTE"){
				mostrarAlerta("Antes de ingresar debe seleccionar una persona","Atención");
				return false;
			}
			if (vDocID==null){
				mostrarAlerta("El tipo de documento es incorrecto","Atención");
				return false;
			}
		}else{
			if (vDNI.length==0){
				mostrarAlerta("Antes de ingresar debe escribir un DNI","Atención");
				return false;
			}
			if (vPersona=="NO EXISTE"){
				mostrarAlerta("Antes de ingresar debe seleccionar una persona","Atención");
				return false;
			}
			if (vDocID.length==0){
				mostrarAlerta("El tipo de documento es incorrecto","Atención");
				return false;
			}

		}

		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {DNI: vDNI, DocID: vDocID},
			url: 'foto_subir.php',
			success: function(data){ 
				$("#mostrar").html(data);
				$("#mostrar2").empty();
				$("#mostrar2").html('<iframe width="800" height="800" scrolling="auto" frameborder="0" id="webFoto" name="webFoto"></iframe>');
				
			}
		});//fin ajax//*/

	});
	$(".botones").button();
});//fin de la funcion ready


</script>
<p>&nbsp;</p>
<table width="50%" border="0" align="left" class="borde_recuadro">
  <tr>
    <td>		
        <table width="50%" border="0" align="left" class="borde_recuadro">
          <tr>
            <td colspan="3">		
            <div align="center" class="titulo_noticia"><img src="imagenes/camera_add.png" align="absmiddle" /> Cargar Foto SIUCC</div></td>
          </tr>
          <tr>
            <td width="50%" class="texto"><div align="right"><strong>DNI: </strong></div></td>
            <td>
              <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" />
            *       
              <button class="botones" id="buscaFoto">
            Buscar</button><input name="DocID" type="hidden" id="DocID" />
            </td>
            <td rowspan="3" align="center" valign="top" ><div id="foto" name="foto"><img src='http://www.uccuyo.edu.ar/alumno/imagenes/foto.jpg' border='1' id="fotoAlu"/></div></td>
          </tr>
          <tr>        
            <td class="texto"><div align="right"><strong>Persona: </strong></div></td>
            <td><input name="persona" type="text" id="persona" size="35" disabled="disabled" /></td>
          </tr>      
          <tr>
            <td colspan="2" align="center" class="texto"><button class="botones" id="barraFoto">
            Ingresar</button>
			</td>
         </tr>
        </table>      
	</td>
	</tr>        
  	<tr>
    <td>		
	<table width="50%" border="0" align="left" class="borde_recuadro">
      <tr>
        <td colspan="2" align="left" class="texto"><div id="mostrar"></div>
        <div id="mostrar2"><iframe width="800" height="800" scrolling="auto" frameborder="0" id="webFoto" name="webFoto"></iframe>
		</div>
        </td>
      </tr>
  </table>
 </td>
</tr>
</table>
<p>&nbsp;</p>
	