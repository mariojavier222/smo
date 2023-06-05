<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
require_once("funciones_generales.php");
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="plugin/css/screen.css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />

<script src="plugin/jquery.validate.js"></script>
<script src="plugin/localization/messages_es.js"></script>
<script src="plugin/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$.validator.setDefaults({
submitHandler: function() { 
	alert("");
	/*
	CCC_Detalle = $("#CCC_Detalle").val();
	CCC_Importe = $("#CCC_Importe").val();
	
	if (CCC_Detalle.length==0){
		mostrarCartel("Debe escribir un Detalle del Ajuste", "ERROR");
		$("#CCC_Detalle").focus();
		return;
	}	
	if (CCC_Importe.length==0){
		mostrarCartel("Debe escribir un Importe", "ERROR");
		$("#CCC_Importe").focus();
		return;
	}
	//alert("");return;
	datos = $("#formDatos").serialize();	
		
	//return;
	$.ajax({
		type: "POST",
		cache: false,
		async: false,			
			url: 'cargarOpciones.php',
		data: datos,
		success: function (data){
			jAlert(data, "Resultado de guardar los datos");				
		}
	});//fin ajax*/
}
});
$(document).ready(function(){

	$("#cargando").hide();
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		alert("");//return;
		$("#formDatos").submit();
	});	

});//fin de la funcion ready

</script>
<div id="listado" class="texto">	
<br />
<br />

  <div align="center" class="titulo_noticia">Realizar Ajuste de Caja</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        $NumCaja = $_POST['numeroCaja'];		
	
            ?>
  <form id="formDatos" method="post" action="" autocomplete="off">
	<table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;">Datos del Concepto </div></td>
      </tr>       
          <tr>
	  <td class="texto" align="right">Concepto:</td>
          <td align="left">
          <?php 
            echo "<select name='CCC_Concepto' id='CCC_Concepto' style='position:relative;z-index:1'>";	
			echo "<option value='Ajuste por concepto'>Ajuste por concepto</option>";
			echo "<option value='Ajuste por importe'>Ajuste por importe</option>";
			echo "<option value='Ajuste por alumno'>Ajuste por alumno</option>";			
			echo "</select>";
			?>
          <input name="opcion" type="hidden" id="opcion" value="guardarAjusteCaja" />
          <input name="opcion" type="hidden" id="CCC_Caja_ID" value="<?php echo $NumCaja;?>" />
          </td>
        </tr>
         <tr>
          <td class="texto" align="right">Detalle del Ajuste (Máx. 90 caract.): </td>
          <td align="left"><textarea name="CCC_Detalle" id="CCC_Detalle"/></textarea></td>
        </tr>
        <tr>
          <td class="texto" align="right">Tipo de transacción: </td>
          <td align="left"><?php 
            echo "<select name='Tipo' id='Tipo' style='position:relative;z-index:1'>";
			echo "<option value='CCC_Debito'>Debito (Egreso)</option>";
			echo "<option value='CCC_Credito'>Crédito (Ingreso)</option>";			
			echo "</select>";
			?></td>
        </tr> 
          <tr>
            <td class="texto" align="right">Forma de Pago:</td>
            <td align="left"><?php cargarListaFormaPago("CCC_For_ID");?></td>
          </tr>
        <tr>	  
	    <td class="texto" align="right">Importe ($):</td>
          <td align="left"><input name="CCC_Importe" id="CCC_Importe" type="text" title="Usar puntos para separar decimales" required></td>
        </tr>
        <tr>
          <td class="texto" align="right">&nbsp;</td>
          <td align="left"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar datos" width="48" height="48" border="0" title="Guardar datos" /><br />Guardar Ajuste</button>
          <!-- <a href="#" target="_blank" id="btnImprimirRecibo"><img src="imagenes/printer.png" alt="Imprimir" width="48" height="48" border="0" title="Imprimir" /></a> --></td>
        </tr>        
    </table>
    </form>     
 


</div> 