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
	
	
	//FILTRAR POR FECHA Y USUARIO
	$("#barraMostrar").click(function(evento){
		$("#tablaCaja").hide();
		$("#tablaUsuario").hide();
		vUsuID = $("#ListUsuID").val();
		vFechaDesde = $("#fechaDesde").val();
		vFechaHasta = $("#fechaHasta").val();
		
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
					mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
					return;
					}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: 'ListarPorFecha',fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, Usuario: vUsuID},
				url: 'mostrarListadoCaja.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/  
	});
	
	//FILTRAR POR FECHA Y USUARIO
	$("#barraMostrarCajaCorriente").click(function(evento){
		$("#tablaCaja").hide();
		$("#tablaUsuario").hide();
		vUsuID = $("#ListUsuID").val();
		vFechaDesde = $("#fechaDesde").val();
		vFechaHasta = $("#fechaHasta").val();
		
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
					mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
					return;
					}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, Usuario: vUsuID},
				url: 'mostrarListadoCajaCorriente.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/  
	});
	
	
	//FILTRAR POR CAJA Y USUARIO
	$("#barraMostrar1").click(function(evento){
		$("#tablaFecha").hide();
		$("#tablaUsuario").hide();
	    vnumeroCaja = $("#numeroCaja").val();
		vUsuario = $("#ListUsuID1").val();
		vError = false;
	vTexto_Error = '';
	
                if (vnumeroCaja==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "Numero de Caja invalida </br>" ;
					$("#numeroCaja").attr("class","input_error");
                    					
					
                }
				else {
					$("#numeroCaja").attr("class","input_sesion");
					}
				if(vError) {
					mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
					return;
					}
		
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: 'ListarPorCaja',numeroCaja: vnumeroCaja, Usuario: vUsuario},
				url: 'mostrarListadoCaja.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/  
	});
	
	//FILTRAR POR CAJA Y USUARIO PARA CAJA CORRIENTE
	$("#barraMostrar3").click(function(evento){
		$("#tablaFecha").hide();
		$("#tablaUsuario").hide();
	    vnumeroCaja = $("#numeroCaja").val();
		vUsuario = $("#ListUsuID1").val();
		vError = false;
		vTexto_Error = '';
	
        if (vnumeroCaja==""){
            vError = true;
			vTexto_Error = vTexto_Error +  "Numero de Caja invalida </br>" ;
			$("#numeroCaja").attr("class","input_error");			
        }
		else {
			$("#numeroCaja").attr("class","input_sesion");
			}
		if(vError) {
			mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
			return;
			}
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {numeroCaja: vnumeroCaja, UsuID: vUsuario},
			url: 'mostrarListadoCajaCorriente.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/  
	});
	$("#barraArreglarCaja").click(function(evento){
		$("#tablaFecha").hide();
		$("#tablaUsuario").hide();
		vnumeroCaja = $("#numeroCaja").val();
		vUsuario = $("#ListUsuID1").val();
		vError = false;
		vTexto_Error = '';
	
        if (vnumeroCaja==""){
            vError = true;
			vTexto_Error = vTexto_Error +  "Numero de Caja invalida </br>" ;
			$("#numeroCaja").attr("class","input_error");			
        }
		else {
			$("#numeroCaja").attr("class","input_sesion");
			}
		if(vError) {
			mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
			return;
			}
		//vUsuario = 1;
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {numeroCaja: vnumeroCaja, Usuario: vUsuario, Arreglar: true},
			url: 'mostrarAjustarCajaCorriente.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax///
	});
	$("#ListarRecibo").click(function(evento){
		$("#tablaFecha").hide();
		$("#tablaUsuario").hide();
		vNumeroCaja = $("#numeroCaja").val();
		vError = false;
		vTexto_Error = '';
	
        if (vNumeroCaja==""){
            vError = true;
			vTexto_Error = vTexto_Error +  "Numero de Caja invalida </br>" ;
			$("#numeroCaja").attr("class","input_error");
            					
			
        }
		else {
			$("#numeroCaja").attr("class","input_sesion");
			}
		if(vError) {
			mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
			return;
			} 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'ListarFactura.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});
	
	
	//FILTRAR POR USUARIO
	$("#barraMostrar2").click(function(evento){
		$("#tablaFecha").hide();
		$("#tablaCaja").hide();
		vUsuID = $("#ListUsuID2").val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: 'ListarPorUsuario',Usuario: vUsuID},
				url: 'mostrarListadoCaja.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});
	
$("#botonLimpiar").click(function(evento){
      
    limpiar();
         					
});

$("#cargarTodo").click(function(evento){
	
	$.ajax({
		type: "POST",
		cache: false,
		async: false,
		error: function (XMLHttpRequest, textStatus){
			alert(textStatus);},
		//data: {},
		url: 'mostrarListadoCajaTodo.php',
		success: function(data){ 
			$("#principal").html(data);
		}
	});//fin ajax//*/  
});

function limpiar(){	
    $("#listado").hide();
	$("#tablaFecha").show();
	$("#tablaCaja").show();
	$("#tablaUsuario").show();
}
	
	$(".botones").button();	
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

<table width="20%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td class="fila_titulo3" colspan="2"><strong>Referencias</strong></td>
  </tr>
  <tr>
    <td class="caja_abierta">&nbsp;</td>
    <td class="fila_titulo2">Caja Abierta.</td>
  </tr>
  <tr>
    <td class="caja_cerrada">&nbsp;</td>
    <td class="fila_titulo2">Caja Cerrada.</td>
  </tr>
</table>

  <p>&nbsp;</p>
  <input name="botonLimpiar" type="button" class="botones" id="botonLimpiar"  value="Limpiar y Volver">

  <button class="botones" id="cargarTodo">Listar Todas las Cajas</button>

  <fieldset>
  <table id="tablaFecha" width="100%" border="0" align="center" class="borde_recuadro" >
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia">
		  <p> Listar Por Fecha y Usuario
	      </p>
        </div></td>
      </tr>
       <tr>
       <td align="right"><?php echo cargarListaUsuariosCaja("ListUsuID", true);?></td>
       </tr>
       <tr>
	     <td align="right" class="texto"><strong>Fecha Desde:<br />
          <input name="fechaDesde" type="text" id="fechaDesde" class="required fechaCompleta" />
           </strong></td>
         <td class="texto"><strong>Fecha Hasta:<br />
         </strong>
           <input name="fechaHasta" type="text" id="fechaHasta" class="required fechaCompleta" /></td>
                  </tr>
      <tr>
        <td colspan="3" align="center" class="texto"><button class="botones" id="barraMostrar">
        Listar</button>  
        <button class="botones" id="barraMostrarCajaCorriente">
        Listar Caja Corriente</button>  
            
        </td>
      </tr>
     </table>
     <table id="tablaCaja" width="100%" border="0" align="center" class="borde_recuadro"> 
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia">
		  <p> Listar por Caja y Usuario
	      </p>
        </div></td>
      </tr>
      <tr>
        <td align="center"><?php echo cargarListaUsuariosCaja("ListUsuID1", true);?></td></tr>
        <tr>
        <td colspan="3" align="center" class="texto">
        <strong>Número de caja: </strong>        <input style="margin-top: 0px;" name="numeroCaja" type="text" id="numeroCaja" title="Si se deja vacío este campo la búsqueda se realizará en todas las cajas"/>        
        </td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="texto"><button class="botones" id="barraMostrar1">
        Listar</button>
        <button class="botones" id="barraMostrar3">
        Listar Caja Corriente</button> 
        <?php if ($UsuID==2 || $UsuID==11 || $UsuID==12 || $UsuID==10){
		?>
        <button class="botones" id="barraArreglarCaja">
        Ajustar Caja</button>
		<?php }?>
        <button class="botones" id="ListarRecibo">
        Listar Recibo</button>          
        </td>
      </tr>
      </table>
      <table id="tablaUsuario" width="100%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia">
		  <p> Listar por Usuario
	      </p>
        </div></td>
      </tr>
      <tr>
	  <td align="center"><?php echo cargarListaUsuariosCaja("ListUsuID2", true);?></td>
	  </tr>
      <tr>
        <td colspan="3" align="center" class="texto"><button class="botones" id="barraMostrar2">
        Listar</button>        
        </td>
      </tr>
      </table>
      <tr>
        <td colspan="3" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>
</fieldset>
	</div>
	
	<p>&nbsp;</p>
