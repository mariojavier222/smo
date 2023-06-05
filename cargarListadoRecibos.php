<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
        
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	
	$("#fechaDesde").datepicker($.datepicker.regional['es']);
	$("#fechaHasta").datepicker($.datepicker.regional['es']);

	function validarDatos(){
		/*vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vNivID = $("#NivID").val();
		vDivID = $("#DivID").val();
		vSedID = $("#SedID").val();
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return false;
		}
		if (vCurID==-1){
			mostrarAlerta("Debe seleccionar un Curso", "ERROR");
			$("#CurID").focus();
			return false;
		}
		if (vNivID==-1){
			mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
			$("#NivID").focus();
			return false;
		}
		if (vDivID==-1){
			mostrarAlerta("Debe seleccionar una División", "ERROR");
			$("#DivID").focus();
			return false;
		}*/
		return true;
	}
	
	$("#barraMostrar").click(function(evento){
		
		vFechaDesde = $("#fechaDesde").val();
		vFechaHasta = $("#fechaHasta").val();
		if (validarDatos()){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta},
				url: 'mostrarListadoRecibos.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}//fin if

	});
	$(".botones").button();	
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

  <p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia">
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Listado de Recibos por fecha de emisi&oacute;n
	      </p>
        </div></td>
      </tr>
    
	   <tr>
	     <td align="right" class="texto">
           <input name="fechaDesde" type="text" id="fechaDesde" class="required fechaCompleta" />
           <br />
           <strong>Fecha Desde</strong></td>
         <td class="texto"><input name="fechaHasta" type="text" id="fechaHasta" class="required fechaCompleta" />
           <br /> 
         <strong>Fecha Hasta </strong>         </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Listar</button>        
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	