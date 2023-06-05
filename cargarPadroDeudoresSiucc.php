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

	
	
	$("#barraMostrar").click(function(evento){
		
		vFechaDesde = $("#fechaDesde").val();
		vFechaHasta = $("#fechaHasta").val();
		vLecID = $("#LecID").val();
		vFacID = $("#FacID").val();
		vTChID = $("#TChID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, LecID: vLecID, FacID: vFacID, TChID: vTChID},
				url: 'mostrarPadronDeudoresSIUCC.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/


	});
	$("#barraPagos").click(function(evento){
		
		vFechaDesde = $("#fechaDesde").val();
		vFechaHasta = $("#fechaHasta").val();
		vLecID = $("#LecID").val();
		vFacID = $("#FacID").val();
		vTChID = $("#TChID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, LecID: vLecID, FacID: vFacID, TChID: vTChID},
				url: 'mostrarRegistroPagosSIUCC.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/


	});
	$("#barraDeudas").click(function(evento){
		
		vFechaDesde = $("#fechaDesde").val();
		vFechaHasta = $("#fechaHasta").val();
		vLecID = $("#LecID").val();
		vFacID = $("#FacID").val();
		vTChID = $("#TChID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, LecID: vLecID, FacID: vFacID, TChID: vTChID},
				url: 'mostrarCuotasPendientesSIUCC.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/


	});
	$(".botones").button();	
	$("#LecID").change(function () {
   		$("#LecID option:selected").each(function () {
			//alert($(this).val());
				vLecID=$(this).val();
				vFacID=$("#FacID").val();
				//alert(vFacID);
				cargarTipoChequera(vLecID, vFacID);
        });
   })
	$("#FacID").change(function () {
   		$("#FacID option:selected").each(function () {
			//alert($(this).val());
				vFacID=$(this).val();
				vLecID=$("#LecID").val();
				//alert(vFacID);
				cargarTipoChequera(vLecID, vFacID);
        });
   })

	function cargarTipoChequera(vLecID, vFacID){
	  $.ajax({
		type: "POST",
		cache: false,
		async: false,
		data: {opcion: "cargarListaTipoChequeraSIUCC2", LecID: vLecID, FacID: vFacID},
		url: 'cargarOpciones.php',
		success: function(data){
				//alert(data);
				$("#TChID").html(data);
		  }//fin success
	  });//fin ajax	
		
	}
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

  <p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia">
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Listar Alumnos Deudores del SIUCC</p>
        </div></td>
      </tr>
      	   <tr>
         <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td><?php 
			cargarListaLectivoSIUCC("LecID");
			?> 
           
             </tr>      
  <tr>
         <td class="texto"><div align="right">Unidad Acad&eacute;mica:</div></td>
         <td><?php cargarListaFacultadSIUCC("FacID");?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Tipo de Chequera:</div></td>
         <td><?php cargarListaTipoChequeraSIUCC("TChID");?> 
           
             </tr>
      <tr>
        <td align="right" class="texto"><button class="botones" id="barraMostrar">
        Listar</button>        
        </td>
        <td align="center" class="texto">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" class="texto"><input name="fechaDesde" type="text" id="fechaDesde" class="required fechaCompleta" />
          <br />
        <strong>Fecha Desde</strong></td>
        <td align="left" class="texto"><input name="fechaHasta" type="text" id="fechaHasta" class="required fechaCompleta" />
          <br />
        <strong>Fecha Hasta </strong></td>
      </tr>
      <tr>
        <td align="right" class="texto"><button class="botones" id="barraPagos">
        Registro de pagos</button></td>
        <td align="left" class="texto"><button class="botones" id="barraDeudas">
        Cuotas pendientes</button></td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	