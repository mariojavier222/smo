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

<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>


<script src="js/jquery.printElement.js" language="javascript"></script>
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script type='text/javascript' src='js/jquery.maskedinput-1.3.min.js'></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />

<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
    $(document).ready(function(){
    
	 $("#Fac_Numero").mask("9999-99999999");
	})
</script>


	<script type="text/javascript"> 

$(document).ready(function(){
	$("#div_contenido").hide();
$("#contenido_POR_NUMERO").hide();
$("#contenidoPOR_FECHA").hide();
$("#contenidoPERSONA").hide();

	
$("#button_buscarCliente").click(function(){
	var Ciclo_lectivo=$("#Ciclo_lectivo").val();
	var Mes=$("#Mes").val();
	var opcionnum=$("#opcionnum4").val();
//alert(opcionnum);
	$.ajax({
	url: 'listadoFactura.php',
	type: "POST",
	cache: false,
	data: {opcionnum:opcionnum,Ciclo_lectivo:Ciclo_lectivo,Mes:Mes },
	async: true,
	success: function(data2){
		$("#ContenidoTODO").html(data2);
		}
	});
	return false;
});

$("#porCliente").click(function(){
$("#div_contenido").show();	
$("#contenido_POR_NUMERO").hide();
$("#contenidoPOR_FECHA").hide();
$("#contenidoPERSONA").hide();
})

$("#porNumero").click(function(){
$("#div_contenido").hide();	
$("#contenido_POR_NUMERO").show();	
$("#contenidoPOR_FECHA").hide();
$("#contenidoPERSONA").hide();
})

$("#porFecha").click(function(){
$("#div_contenido").hide();	
$("#contenido_POR_NUMERO").hide();
$("#contenidoPOR_FECHA").show();
$("#contenidoPERSONA").hide();	
})

$("#porPersona").click(function(){
$("#div_contenido").hide();	
$("#contenido_POR_NUMERO").hide();
$("#contenidoPOR_FECHA").hide();
$("#contenidoPERSONA").show();	
})

$("#EnviarNumFac").click(function(){
var Fac_Numero=$("#Fac_Numero").val();
var opcionnum=$("#opcionnum3").val();

if((Fac_Numero=='')){
	alert("Ingrese Números de Factura");
	return false;
}

$.ajax({
	url: 'listadoFactura.php',
	type: "POST",
	cache: false,
	data: {Fac_Numero:Fac_Numero,opcionnum:opcionnum},
	async: true,
	success: function(data2){
		$("#ContenidoTODO").html(data2);
		}
	});

return false;})

$("#fecha1,#fecha2").datepicker({
	changeYear: true,
	yearRange: '2011:2050'
	});
	
$("#EnviarFechas").click(function(){
var fecha1=$("#fecha1").val();
var fecha2=$("#fecha2").val();
var opcionnum=$("#opcionnum2").val();
if((fecha1=='')||(fecha2=='')){
alert("Ingrese fechas");
return false;
}
//alert(fecha1)
//alert(fecha2)
//alert(opcionnum);
$.ajax({
	url: 'listadoFactura.php',
	type: "POST",
	cache: false,
	data: {fecha1:fecha1,fecha2:fecha2,opcionnum:opcionnum},
	async: true,
	success: function(data2){
		$("#ContenidoTODO").html(data2);
		}
	});

return false;})

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

	$("#barraCuotas").click(function(evento){
		
		vPerID = $("#PerID").val();
		opcionnum="persona";
		//alert(vPerID);
		//alert(opcionnum);
		//return false;
		//alert("");
		$.ajax({
			url: 'listadoFactura.php',
	type: "POST",
	cache: false,
	data: {opcionnum:opcionnum, per_ID: vPerID},
	async: true,
	success: function(data2){
		$("#ContenidoTODO").html(data2);
		}
		
		});//fin ajax//*/
		
			
			
	
	
		
		
	});


})//fin doc ready
</script> 
<link href="css/cliente.css" rel="stylesheet" type="text/css">
<style type="text/css">
#tablaOcpionesListarFacturas td{ font-size:14px; padding:11px; font-family:Tahoma, Geneva, sans-serif;}
#tablaOcpionesListarFacturas label:hover{ cursor:pointer; text-decoration:underline}
#fieldset_OpcionesListar{ /*background:#FFC*/;  font-weight:bold; padding:10px; }
#contenido_POR_NUMERO{font-size:16px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold}
#contenidoPERSONA{font-size:16px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold}
#div_buscadorCliente{font-size:16px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold}
#contenido_POR_NUMERO input{font-size:16px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold}
#fieldset_POR_NUMERO{/*background:#FCC;*/}


#contenidoPOR_FECHA{font-size:16px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold}
#contenidoPOR_FECHA input{font-size:16px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold}
#contenidoPOR_FECHA{/*background:#FCC;*/}
#tabla1_porNumero td{ padding:10px;}

</style>
<div id="ContenidoTODO">
<div id="opcionesListarFacturas">
<fieldset id="fieldset_OpcionesListar">
<legend style="font-size:14px">Buscar Por</legend>
<table id="tablaOcpionesListarFacturas" align="center" >
<tr>
<td>
<input type="radio" name="opcionRadio" id="porCliente" value="porCliente">
<label for="porCliente"> Por Ciclo Lectivo</label></td>
<td>
<input type="radio" name="opcionRadio" id="porNumero" value="porNumero">
<label for="porNumero">Por Número de Factura</label>
</td>
<td>
<input type="radio" name="opcionRadio" id="porFecha" value="porFecha">
<label for="porFecha">Por Fecha de Emisión</label>
</td>
<td>
<input type="radio" name="porPersona" id="porPersona" value="porPersona">
<label for="porPersona">Por Persona</label>
</td>

</tr>
</table>
</fieldset>
</div>

<div id="contenido_POR_NUMERO" align="center">
<br>
<fieldset id="fieldset_POR_NUMERO">

Numero: <input type="text" name="Fac_Numero" id="Fac_Numero" size="14" ><br><br>
<input type="hidden" name="opcionnum3" id="opcionnum3" value="numero" />
<button id="EnviarNumFac">Buscar</button>
</fieldset>
</div>

<div id="div_contenido" class="texto">
<br>

<div id="div_buscadorCliente" align="center">
<fieldset id="cicloElectivo">
<div id="buscarCliente_contenido">
<table id="tabla1_porNumero">
<tr>
<td align="right">Ciclo Electivo:</td>  <td><?php cargarListaLectivo('Ciclo_lectivo'); ?></td>
</tr>
<tr>
<td align="right">Mes:</td>  <td><?php cargarListarMeses('Mes'); ?></td>
</tr>

</table><br />
<input type="hidden" name="opcionnum4" id="opcionnum4" value="cicloLectivo" />
<button name="button_buscarCliente" id="button_buscarCliente">Buscar</button>

</div>
</fieldset>
</div>
</div><!-- cierre div_contenido -->

<div id="contenidoPOR_FECHA" align="center">
<br>
<fieldset id="fieldset_POR_NUMERO">
<table align="center" id="tabla1_porNumero">
<tr>
<td>Desde <input type="text" name="fecha1" id="fecha1" size="11" style="padding-left:6px" ></td>
<td>Hasta <input type="text" name="fecha2" id="fecha2" size="11" style="padding-left:6px" ></td>
</tr>
</table>
<input type="hidden" name="opcionnum2" id="opcionnum2" value="Fecha" />
<br />

<button id="EnviarFechas">Buscar</button>
</fieldset>

</div>

<div id="contenidoPERSONA" align="center">
<br>
<fieldset id="fieldsetPERSONA">
<table align="center" id="tabla1_porNumero">
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="35" />          </td>
      </tr>
  <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" />
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" /> <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/></td>
      </tr>

</table>
<input type="hidden" name="opcionnum2" id="opcionnum2" value="Fecha" />
<!--<input type="hidden" name="PerID" id="PerID" />-->


<!-- <input name="DNI" type="text" class="texto_buscador" id="DNI" size="15" />-->
              
          <input name="PerID" type="hidden" id="PerID" />
<br />

<button class="botones" id="barraCuotas">Buscar</button><?php if (isset($_POST['DNI'])){?>
<?php }//fin if?>
</fieldset>

</div>


</div><!-- todo -->
<?php
