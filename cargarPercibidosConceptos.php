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
		vFacID = $("#FacID").val();
		vProductos = "";
		//Listado de conceptos elegidos
		$("input[id^='Producto']:checked").each(function () {
			//alert($(this).val());
				vProductos= vProductos + $(this).val() + ";" ;
        });
		//alert(vProductos);
		vFacu = $("#Facu:checked").length;
		vAltID = $("#AltID:checked").length;
		vProID = $("#ProID:checked").length;
		vImporte = $("#Importe:checked").length;
		$("#cargando").show();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, Facu: vFacu, FacID: vFacID, AltID: vAltID, ProID: vProID, Importe: vImporte, Productos: vProductos},
				url: 'mostrarPercibidosConcepto.php',
				success: function(data){ 
					//alert(data);
					$("#mostrar").html(data);
					$("#cargando").hide();
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
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Listar Pericibidos por Concepto</p>
        </div></td>
      </tr>      	   
  <tr>
         <td class="texto"><div align="right">Unidad Acad&eacute;mica:</div></td>
         <td><?php cargarListaFacultadSIUCC("FacID", true);?> 
           
             </tr>
             <tr>
         <td width="50%" class="texto"><div align="right">Filtrar por Conceptos:</div></td>
         <td class="texto"><?php 
			cargarListaConceptosCheckbox("Producto");
			?> 
           
             </tr>      
       <tr>
         <td align="right" valign="middle" bgcolor="#75B3EA" class="texto">Campos a mostrar</td>
         <td valign="top" bgcolor="#75B3EA" class="texto">
           <input name="Facu" type="checkbox" id="Facu" checked="checked" />
           <label for="Facu">Unidad Acad&eacute;mica</label>
           <br />
           <input name="AltID" type="checkbox" id="AltID" checked="checked" />
           <label for="AltID">Alternativa de pago</label>
                      <br />           
           <input name="ProID" type="checkbox" id="ProID" checked="checked" />
         <label for="ProID">Concepto</label>
           <br />
           <input name="Importe" type="checkbox" id="Importe" checked="checked" />
           <label for="Importe">Importe</label>
           
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
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Registro de pagos</button></td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	