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
<link href="js/ui.multiselect.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/ui.multiselect.js"></script>
        
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	
	$("#fechaVencimiento").datepicker($.datepicker.regional['es']);

	function validarDatos(){
		vDesde = $("#Desde").val();
		vMesDesde = $("#mesDesde").val();
		vHasta = $("#Hasta").val();
		vMesHasta = $("#mesHasta").val();
		vFechaVencimiento = $("#fechaVencimiento").val();
		
		if (!validarNumero(vDesde)){
			mostrarAlerta("Debe escribir un Año Desde válido", "ERROR");
			return false;
		}
		if (!validarNumero(vHasta)){
			mostrarAlerta("Debe escribir un Año Hasta válido", "ERROR");
			return false;
		}
		
		return true;
	}
	
	$("#barraMostrar").click(function(evento){
		
		vProID = $("#ProID").val();
		if (validarDatos()){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {FechaVencimiento: vFechaVencimiento, Desde: vDesde, Hasta: vHasta, ProID: vProID, MesDesde: vMesDesde, MesHasta: vMesHasta},
				url: 'mostrarDeudasAlCierre.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}//fin if

	});
	$(".botones").button();	
	$(".multiselect").multiselect();
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

  <p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia">
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Deudas al Cierres (Deudas sobre valores devengados)</p>
        </div></td>
      </tr>
      	   <tr>
         <td width="50%" class="texto"><div align="right">
           <p><strong>Desde el Mes-A&ntilde;o:</strong><br />
             <input name="mesDesde" type="text" id="mesDesde" size="3" maxlength="2" />
             -
             <input name="Desde" type="text" class="ui-button-text-only" id="Desde" size="6" maxlength="4" />
             |
           </p>
</div></td>
      <td class="texto"><strong>Hasta el Mes-A&ntilde;o:</strong><br />
        |
        <input name="mesHasta" type="text" id="mesHasta" size="3" maxlength="2" />
        -
        <input name="Hasta" type="text" id="Hasta" size="6" maxlength="4" />      </tr>      
  <tr>
         <td class="texto"><div align="right"><strong>Fecha Vencimiento:</strong><br />
      </div></td>
         <td><input name="fechaVencimiento" type="text" class="required fechaCompleta" id="fechaVencimiento" size="15" maxlength="10" />         </tr>
       <tr>
         <td colspan="2" align="center" class="texto"><p><strong><br />
         Seleccione los conceptos:</strong></p></td>
         </tr>
	   <tr>
	     <td colspan="2" class="texto"><?php cargarListaConceptosMultiple("ProID");?></td>
         </tr>
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
	