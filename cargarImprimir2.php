<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");

?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	
	$("#barraMostrar").click(function(evento){
	

	Fecha = $("#Fecha:checked").length;
	//alert(Fecha)
	Concepto = $("#Concepto:checked").length;
	Ingreso = $("#Ingreso:checked").length;
	Egreso = $("#Egreso:checked").length;
	Caja = $("#Caja:checked").length;
	Usuario = $("#Usuario:checked").length;
	SaldoUsuario = $("#SaldoUsuario:checked").length;
	FormaPago = $("#FormaPago:checked").length;
	letra= $("#letra").val();
	tamanio= $("#tamanio").val();
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	error: function (XMLHttpRequest, textStatus){
	alert(textStatus);},
	data: {opcion:"mostrarListadoImprimir",Fecha: Fecha, Concepto: Concepto, Ingreso: Ingreso, Egreso: Egreso, Caja: Caja,  Usuario: Usuario, SaldoUsuario: SaldoUsuario, FormaPago: FormaPago,letra:letra,tamanio:tamanio},
	url: 'cargarOpcionesImprimir.php',
	success: function(data){ 
	$("#mostrarImprimir2").html(data);
	}
	});//fin ajax//*/
	
	});	
	
	$("#barraMostrar").click();
	//$("#barraMostrar").click();
	$('button').button();
})
</script>

<table width="95%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td valign="top"  class="texto">
           <input name="Fecha" type="checkbox"  id="Fecha" checked="checked" />
           <label for="Fecha">Fecha</label>
          </td>
          <td>
           <input name="Concepto" type="checkbox"  id="Concepto" checked="checked" />
           <label for="Concepto">Concepto</label>
</td>
<td>
           <input type="checkbox" name="Ingreso" id="Ingreso" checked="checked" />
           <label for="Ingreso">Ingreso</label>
</td>
<td>
           <input name="Egreso" type="checkbox" id="Egreso" checked="checked" />
           <label for="Egreso">Egreso</label>
</td>
<td>
           <input name="Caja" type="checkbox" id="Caja" checked="checked" />
           <label for="Caja">Saldo de Caja</label>
</td>
<td>
           <input type="checkbox" name="Usuario" id="Usuario" checked="checked" />
           <label for="Usuario">Usuario</label>
</td>
<td>
           <input name="SaldoUsuario" type="checkbox" id="SaldoUsuario" checked="checked" />
           <label for="SaldoUsuario">Saldo de Usuario</label>
</td>
<td>
           <input name="FormaPago" type="checkbox" id="FormaPago" checked="checked" />
           <label for="FormaPago">Forma de Pago</label>
</td>
</tr>
<tr>
<td colspan="2"><strong>Estilo de Letra:</strong><select name="letra" id="letra">
<option value="Arial">Arial</option>
<option value="Times New Roman">Times New Roman</option>
<option value="Verdana">Verdana</option>
</select></td>
<td colspan="2"><strong>Tamaño de Letra:</strong><select name="tamanio" id="tamanio">
<option value="6">6px</option>
<option value="8">8px</option>
<option value="10" selected="selected">10px</option>
<option value="12">12px</option>
<option value="14">14px</option>
<option value="16">16px</option>
</select></td>
</tr>
<tr>
        <td colspan="8" align="center" class="texto"><button class="botones" id="barraMostrar">
        Mostrar Datos Imprimir</button>      
        </td>
      </tr>
  </table>
  <br>
<div id="mostrarImprimir2"></div>