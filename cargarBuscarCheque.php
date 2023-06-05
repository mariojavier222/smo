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
		
		
		return true;
	}
	
	$("#barraMostrar").click(function(evento){
	
	var	vFechaDesde = $("#fechaDesde").val();
		//alert(vFechaDesde);
	var	vFechaHasta = $("#fechaHasta").val();
		//alert(vFechaHasta);
		vError = false;
	vTexto_Error = '';
	
                if (vFechaDesde==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "Fecha Desde invalida </br>" ;
					$("#fechaDesde").attr("class","input_error");
                    					
					
                }
				else {
					$("#fechaDesde").attr("class","input_sesion");
					}
				if (vFechaHasta==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "Fecha Hasta invalida </br>" ;
					$("#fechaHasta").attr("class","input_error");
                    					
					
                }
				else {
					$("#fechaHasta").attr("class","input_sesion");
					}	
					if(vError) {
					mostrarAlerta1(vTexto_Error,"Existen datos incorrectos");
					return;
					}
		if (validarDatos()){
			//alert(vNivID);
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta},
				url: 'mostrarBuscarCheque.php',
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
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Buscar Cheques de Caja Diaria</p>
        </div></td>
      </tr>

       <tr>
	     <td width="50%" align="right" class="texto">
           <input name="fechaDesde" type="text" id="fechaDesde" class="required fechaCompleta" />
           <br />
           <strong>Fecha Desde</strong></td>
         <td class="texto"><input name="fechaHasta" type="text" id="fechaHasta" class="required fechaCompleta" />
           <br /> 
         <strong>Fecha Hasta </strong>         </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Buscar Cheques</button>        
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	