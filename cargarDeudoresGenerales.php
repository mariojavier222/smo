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


<script language="javascript">
$(document).ready(function(){	
	
	$("#barraLectivoTotales").click(function(evento){				
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				//data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, LecID: vLecID, FacID: vFacID, TChID: vTChID},
				url: 'mostrarEstadisticaDeudoresLectivoTotales.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
	});
	$("#barraLectivoImportes").click(function(evento){				
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				//data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, LecID: vLecID, FacID: vFacID, TChID: vTChID},
				url: 'mostrarEstadisticaDeudoresLectivoImportes.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
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
		  <p>  Deudores Generales</p>
        </div></td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto">Por Ciclo Lectivo (mirada económica)</td>
      </tr>
      <tr>
        <td width="50%" align="right" class="texto"><button class="botones" id="barraLectivoTotales">
        Totales alumnos</button></td>
        <td align="left" class="texto"><button class="botones" id="barraLectivoImportes">
        Totales importes</button></td>
      </tr>
      <tr>
        <td colspan="2" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	