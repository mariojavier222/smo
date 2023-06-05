<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Egreso_Recibo";
$fechaHoy = date("d/m/Y");
$UsuID = $_SESSION['sesion_UsuID'];
/*if ($fechaHoy=="24/08/2018" && $UsuID==11){
	header("Location: http://www.afip.gob.ar/sitio/externos/default.asp");
}*/

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
	$("#fechaDesde").datepicker($.datepicker.regional['es']);
	$("#fechaHasta").datepicker($.datepicker.regional['es']);
$.validator.setDefaults({
	submitHandler: function() { 
		Rec_Cue_ID = $("#Rec_Cue_ID option:selected").val();
		Rec_FechaCompra = $("#Rec_FechaCompra").val();
		Rec_Importe = $("#Rec_Importe").val();
		txtDetalleRecibo = $("#txtDetalleRecibo").val();
		formaPago = $("#formaPago option:selected").val();
		CuentaOrigen = $("#CuentaOrigen option:selected").val();
		CuentaDestino = $("#CuentaDestino option:selected").val();
		Recibo_Sucursal = $("#Recibo_Sucursal").val();
		Recibo_Numero = $("#Recibo_Numero").val();

		if (Rec_Cue_ID == -1){			
			$("#Rec_Cue_ID").focus();
			mostrarCartel("Seleccione un Proveedor", "ERROR");
			return;
		}
		if (Rec_FechaCompra.length==0){
			mostrarCartel("Debe escribir una Fecha para el Pago", "ERROR");
			$("#Rec_FechaCompra").focus();
			return;
		}
		if (Recibo_Sucursal.length==0){
			mostrarCartel("Debe escribir un Punto de Venta", "ERROR");
			$("#Recibo_Sucursal").focus();
			return;
		}
		if (Recibo_Numero.length==0){
			mostrarCartel("Debe escribir el número de la factura", "ERROR");
			$("#Recibo_Numero").focus();
			return;
		}	
		Recibo_Sucursal = "0000" + Recibo_Sucursal;
		Recibo_Sucursal = Recibo_Sucursal.substr(-4);
		Recibo_Numero = "00000000" + Recibo_Numero;
		Recibo_Numero = Recibo_Numero.substr(-8);
		Recibo_Completo = Recibo_Sucursal + "-" + Recibo_Numero;
		$("#Rec_Numero").val(Recibo_Completo);
		Rec_ID = $("#Rec_ID").val();
		//alert(Recibo_Completo);
		//return;

		if (formaPago == "Cheque"){//es cheque, controlamos que se llenen los otros campos del cheque			
			ChequeFecha = $("#ChequeFecha").val();
			ChequeBanco = $("#ChequeBanco").val();
			ChequeNumero = $("#ChequeNumero").val();
			if (ChequeNumero.length==0 || ChequeBanco.length==0 || ChequeFecha.length==0){
				mostrarCartel("Debe escribir un valor para la Fecha, Banco y Número del Cheque a entregar", "ERROR");
				return;
			}		
		}
		if (txtDetalleRecibo.length==0){
			mostrarCartel("Debe escribir un Detalle del Servicio del Proveedor", "ERROR");
			$("#txtDetalleRecibo").focus();
			return;
		}
		if (Rec_Importe.length==0){
			mostrarCartel("Debe escribir un Importe para el Pago", "ERROR");
			$("#Rec_Importe").focus();
			return;
		}

		if (CuentaOrigen == -1){			
			$("#CuentaOrigen").focus();
			mostrarCartel("Seleccione una Cuenta Origen para el Movimiento Contable", "ERROR");
			return;
		}
		if (CuentaDestino == -1){			
			$("#CuentaDestino").focus();
			mostrarCartel("Seleccione una Cuenta Destino para el Movimiento Contable", "ERROR");
			return;
		}
		datos = $("#formDatos").serialize();	
			
		//return;
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
				url: 'cargarOpciones.php',
			data: datos,
			success: function (data){
				//alert(data.substr(0,5));
				if (data.substr(0,5)!="Error"){
					window.open('imprimirReciboEgreso.php?Rec_ID=' + Rec_ID, '_blank');
				}else{
					jAlert(data, "Resultado de guardar los datos");
				}								
			}
		});//fin ajax
	}
});
$(document).ready(function(){
	
	$("#Rec_FechaCompra").mask("99/99/9999");
	$("#Rec_FechaCompra").val("<?php echo date('d/m/Y');?>");
	$("#Rec_FechaFactura").mask("99/99/9999");
	$("#Rec_FechaFactura").val("<?php echo date('d/m/Y');?>");
	$("#ChequeFecha").mask("99/99/9999");
	$("#Rec_FechaCompra2").mask("99/99/9999");
	//$("#Rec_Importe").mask("99/99/");
	$("#formDatos").validate();//fin validation
	$("#formDatosAdelanto").validate();//fin validation		
	
	//$("#mostrarNuevo").hide();
	$("#divBuscador").hide();
	$("#divFecha").hide();
	//$(".ocultar").hide();
	$("input[class^='ocultar']").hide();
	$("select[class^='ocultar']").hide();
	$("#mostrar").empty();
	$("#nombre").focus();
	$("#form_Buscador").unbind('submit');
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		recargarPagina();
		/*$("#barraGuardar").show();				
		$("#mostrarNuevo").fadeIn();
		$("#mostrarAdelanto").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		$("#divFecha").hide();
		//$("#Cuenta").focus();
		limpiarDatos();*/
	});
	$("#btnImprimir").click(function(evento){
		evento.preventDefault();
		//datos = $("#formDatosAdelanto").serialize();
		a = $("#textoDetalle").val();
		b = $("#Detalle").val();
		c = $("#Rec_FechaCompra2").val();
		d = $("#Rec_Importe2").val();
		//alert(d);
		pdfStream = "imprimirAdelantoSueldo.php?a="+a+"&b="+b+"&c="+c+"&d="+d;
		window.open(pdfStream);
		
	});//fin click
	$("#btnImprimirRecibo").click(function(evento){
		evento.preventDefault();
		//datos = $("#formDatosAdelanto").serialize();
		a = $("#txtDetalleRecibo").val();
		b = $("#Rec_Cue_ID option:selected").text();
		c = $("#Rec_FechaCompra").val();
		d = $("#Rec_Importe").val();
		e = $("#Rec_TipoRecibo option:selected").val();
		f = $("#formaPago option:selected").val();
		g = $("#Rec_Numero").val();
		h = $("#txtRazonSocial").val();
		i = $("#txtTitulo").val();
		
		//alert(d);
		pdfStream = "imprimirReciboEgreso.php?a="+a+"&b="+b+"&c="+c+"&d="+d+"&e="+e+"&f="+f+"&g="+g+"&h="+h+"&i="+i;
		window.open(pdfStream);
		
	});//fin click
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		tipo = 1;
		$("#formDatos").submit();
	});
	$("#barraGuardar2").click(function(evento){
		evento.preventDefault();
		tipo = 2;
		$("#formDatosAdelanto").submit();
	});
	
	function recargarPagina(){
		$("#mostrar").empty();

		$.ajax({
			cache: false,
			async: false,			
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
					$("#principal").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	}//fin function
	$("#Rec_Cue_ID").change(function () {
   		$("#Rec_Cue_ID option:selected").each(function () {
			Rec_Cue_ID = $(this).val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
					url: 'cargarOpciones.php',
				data: {opcion: 'traerDatosProveedor', Cue_ID: Rec_Cue_ID},
				success: function (data){
					//alert(data);
					if (!data){
						jAlert("No existen datos relacionados con ese Proveedor", "Atención");	
					}else{
						arreDatos = data.split(";"); 
						$("#txtRazonSocial").val(arreDatos[0]);
						$("#txtCUIT").val(arreDatos[1]);
						$("#CuentaDestino").val(arreDatos[2]);
						$("#CuentaDestino").change();
					}
								
				}
			});//fin ajax
        });
   });
	$("#CuentaOrigen").change(function () {
   		$("#CuentaOrigen option:selected").each(function () {
			CuentaOrigen = $(this).val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
					url: 'cargarOpciones.php',
				data: {opcion: 'traerSaldoCuentaContable', Cue_ID: CuentaOrigen},
				success: function (data){
					//alert(data);
					$("#SaldoOrigen").val(data);
				}
			});//fin ajax
        });
   });
	$("#CuentaDestino").change(function () {
   		$("#CuentaDestino option:selected").each(function () {
			CuentaDestino = $(this).val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
					url: 'cargarOpciones.php',
				data: {opcion: 'traerSaldoCuentaContable', Cue_ID: CuentaDestino},
				success: function (data){
					//alert(data);
					$("#SaldoDestino").val(data);
				}
			});//fin ajax
        });
   });
	$("#Anio").change(function () {
   		$("#Anio option:selected").each(function () {
			//alert($(this).val());
				//Anio=$(this).val();
				
				$("#barraBuscar").click();
        });
   });
   $("#TipoEgreso").change(function () {
   		$("#TipoEgreso option:selected").each(function () {
			//alert($(this).val());
				//TipoEgreso=$(this).val();
				
				$("#barraBuscar").click();
        });
   });
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#barraGuardar").hide();
		$("#mostrarNuevo").hide();
		$("#mostrarAdelanto").hide();
		$("#divBuscador").fadeIn();
		$("#divFecha").hide();
		Anio = $("#Anio").val();
		TipoEgreso = 1;//$("#TipoEgreso").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Anio: Anio, TipoEgreso: TipoEgreso},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					//$("#loading").hide();
				}
			});//fin ajax//*/
		//recargarPagina();
	});
	$("#barraBuscarFecha").click(function(evento){
		evento.preventDefault();
		$("#barraGuardar").hide();
		$("#mostrarNuevo").hide();
		$("#mostrarAdelanto").hide();
		$("#divBuscador").hide();
		$("#divFecha").show();	
		$("#barraMostrarFecha").click();	
	});
	$(".items").hide();
	$("#agregarItems").click(function(evento){
		evento.preventDefault();
		$(".items").show();			
	});
	$("#barraMostrarFecha").click(function(evento){
		evento.preventDefault();
		vFechaDesde = $("#fechaDesde").val();
		//alert(vFechaDesde);
		vFechaHasta = $("#fechaHasta").val();
		vFiltroOrdenar = $("#filtroOrdenar option:selected").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, filtroOrdenar: vFiltroOrdenar},
				url: 'mostrarRecibosDetalleFecha.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultadoFecha").html(data);
					//$("#loading").hide();
				}
			});//fin ajax//*/
		
	});
	$("#mostrarTodo").click(function(evento){
		evento.preventDefault();
		$("#textoBuscar").val("todos");
		$("#textoBuscar").keyup();
	});
	$("#textoBuscar").keyup(function(event){
		event.preventDefault();
		$("#loading").show();
		vTexto = $("#textoBuscar").val();
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13 || vTexto.length>2) {  
			//alert("Hola " + event.keyCode);   	
			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Texto: vTexto},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					$("#loading").hide();
				}
			});//fin ajax//*/
   		}

	});
	
	/*$("#Rec_Importe").keyupNO(function(event){
		event.preventDefault();
		
		if (event.keyCode == 13) {  
			//alert("Hola " + event.keyCode);   	
			$("#formDatos").submit();			
   		}
	});*/
	$("#Rec_Importe").focusout(function(event){
		event.preventDefault();		//alert();return;
		vImporte = $("#Rec_Importe").val();
		if (vImporte>=1000){
			$("#formaPago").val("Cheque");
			$(".Cheque").show();
		}else{
			$(".Cheque").hide();
		}
	});
	$("#formaPago").change(function () {
   		$("#formaPago option:selected").each(function () {
			var formaPago = $(this).val();
			//alert(formaPago);/*
			if (formaPago=="Cheque"){
				$(".Cheque").show();
			}else{
				$(".Cheque").hide();
			}//*/
        });
   });
	$(".Cheque").hide();
	//$("#ChequeBanco").hide();

	function limpiarDatos(){
		$("input:text").val("");
		$("select").val(-1);
		$("textarea").val("");
	}
	
	
	
	//Datos iniciales
	$("#loading").hide();
	//$("#Cuenta").focus();

	 
});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Agregar un nuevo Registro" width="48" height="48" border="0" title="Agregar un nuevo Registro" /><br />Nuevo Pago</button> </td>
        <?php
		//if ($_SESSION['sesion_UsuID']==12 || $_SESSION['sesion_UsuID']==2){
        ?>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Registros" width="48" height="48" border="0" title="Buscar Registros" /><br />
      Listar</button></td>
      <?php
      //}//fin if
	  ?>
          <td width="48"><button class="barra_boton"  id="barraBuscarFecha">  <img src="botones/Search.png" alt="Buscar por fecha" width="48" height="48" border="0" title="Buscar por fecha" /><br />Búsqueda avanzada</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
    <form id="formDatos" method="post" action="" autocomplete="off">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"> Cargar Nueva Orden de Pago</div></td>
      </tr>
          <tr>
            <td class="texto" align="right">Nº de Orden (no tocar):</td>
            <td align="left">
            <input name="Rec_ID_2" type="text" id="Rec_ID_2" size="10" disabled value="<?php echo $Rec_IDUltimo = substr("00000000".buscarUltimaOrdenPago(),-8);?>">
            <input name="Rec_ID" type="hidden" id="Rec_ID" size="10" readonly="readonly" value="<?php echo $Rec_IDUltimo;?>">
            <input name="opcion" type="hidden" id="opcion" value="guardarOrdenPago" /></td>
        </tr>   
        <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;">Datos del Proveedor </div></td>
      </tr>       
          <tr>
	  <td class="texto" align="right">Proveedor:</td>
          <td align="left"><input name="Rec_ETi_ID" type="hidden" id="Rec_ETi_ID" value="1" />
          <?php cargarListaEgresoCuenta("Rec_Cue_ID");?>
          </td>
        </tr>
         <tr>
          <td class="texto" align="right">Razón Social: </td>
          <td align="left"><input type="text" name="txtRazonSocial" id="txtRazonSocial" readonly/></td>
        </tr>
        <tr>
          <td class="texto" align="right">CUIT: </td>
          <td align="left"><input type="text" name="txtCUIT" id="txtCUIT" readonly/></td>
        </tr> 
          <tr>
            <td class="texto" align="right">Tipo de Recibo:</td>
            <td align="left"><?php cargarListaReciboTipo("Rec_TipoRecibo");?></td>
          </tr>
          <tr>
	  <td class="texto" align="right">Fecha de la Factura:</td>
          <td align="left"><input name="Rec_FechaFactura" id="Rec_FechaFactura" type="text" required></td>
        </tr>
          <tr>
	  <td class="texto" align="right">Número de recibo del proveedor:</td>
          <td align="left"><input name="Rec_Numero" id="Rec_Numero" type="hidden" >
          	<input name="Recibo_Sucursal" id="Recibo_Sucursal" type="text" value="" required size="4" maxlength="4">
          	<input name="Recibo_Numero" id="Recibo_Numero" type="text" size="8" maxlength="8" value="" required>
          </td>
        </tr>  
        <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;">Datos del Servicio </div></td>
      </tr>
        <tr>
	  <td class="texto" align="right">Fecha de Pago:</td>
          <td align="left"><input name="Rec_FechaCompra" id="Rec_FechaCompra" type="text" required></td>
        </tr> 
                  
	    <td class="texto" align="right">Importe ($):</td>
          <td align="left"><input name="Rec_Importe" id="Rec_Importe" type="text" title="Usar puntos para separar decimales" required></td>
        </tr><tr>
          <td class="texto" align="right">Forma de Pago:</td>
          <td align="left"><select name="formaPago" id="formaPago">
            <option value="Efectivo">Efectivo</option>
            <option value="Cheque">Cheque</option>
            <option value="Transferencia">Transferencia Bancaria</option>
          </select></td>
        </tr>
        <tr class="Cheque">
          <td class="texto" align="right">Número de Cheque: </td>
          <td align="left"><input name="ChequeNumero" type="text" id="ChequeNumero" value="" /></td>
        </tr>
        <tr class="Cheque">
          <td class="texto" align="right">Banco Emisor: </td>
          <td align="left"><input name="ChequeBanco" type="text" id="ChequeBanco" value="" /></td>
        </tr>
        <tr class="Cheque">
          <td class="texto" align="right">Fecha del Cheque: </td>
          <td align="left"><input name="ChequeFecha" type="text" id="ChequeFecha" value="" /></td>
        </tr>
        <tr>
          <td class="texto" align="right">Título del Recibo: </td>
          <td align="left"><input name="txtTitulo" type="text" id="txtTitulo" value="ORDEN DE PAGO" /></td>
        </tr>
        
        <tr>
          <td class="texto" align="right">Detalle del Servicio:</td>
          <td align="left"><textarea required name="txtDetalleRecibo" id="txtDetalleRecibo" cols="45" rows="5"></textarea></td>
        </tr>
        <tr>
        <td colspan="2" align="center"><button id="agregarItems">Agregar Items</button></td>
      </tr>
        <tr class="items">
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;">Items del Servicio </div></td>
      </tr>
      <tr class="items">
        <td colspan="2" align="center">
        	<table>
				<tr><th class="fila_titulo" align="center">Detalle</th><th align="center" class="fila_titulo">Importe</th></tr>
        		<tr><td>
        			<input name="itemOrden1" type="hidden" id="itemOrden1" value="1" />
        			<input name="itemDetalle1" type="text" id="itemDetalle1" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte1" type="text" id="itemImporte1" value="" size="30" maxlength="10" />
        		</td></tr>
        		<tr><td>
        			<input name="itemOrden2" type="hidden" id="itemOrden2" value="2" />
        			<input name="itemDetalle2" type="text" id="itemDetalle2" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte2" type="text" id="itemImporte2" value="" size="30" maxlength="10" />
        		</td></tr>
        		<tr><td>
        			<input name="itemOrden3" type="hidden" id="itemOrden3" value="3" />
        			<input name="itemDetalle3" type="text" id="itemDetalle3" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte3" type="text" id="itemImporte3" value="" size="30" maxlength="10" />
        		</td></tr>
        		<tr><td>
        			<input name="itemOrden4" type="hidden" id="itemOrden4" value="4" />
        			<input name="itemDetalle4" type="text" id="itemDetalle4" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte4" type="text" id="itemImporte4" value="" size="30" maxlength="10" />
        		</td></tr>
        		<tr><td>
        			<input name="itemOrden5" type="hidden" id="itemOrden5" value="5" />
        			<input name="itemDetalle5" type="text" id="itemDetalle5" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte5" type="text" id="itemImporte5" value="" size="30" maxlength="10" />
        		</td></tr>
        		<tr><td>
        			<input name="itemOrden6" type="hidden" id="itemOrden6" value="6" />
        			<input name="itemDetalle6" type="text" id="itemDetalle6" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte6" type="text" id="itemImporte6" value="" size="30" maxlength="10" />
        		</td></tr>
        		<tr><td>
        			<input name="itemOrden7" type="hidden" id="itemOrden7" value="7" />
        			<input name="itemDetalle7" type="text" id="itemDetalle7" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte7" type="text" id="itemImporte7" value="" size="30" maxlength="10" />
        		</td></tr>
        		<tr><td>
        			<input name="itemOrden8" type="hidden" id="itemOrden8" value="8" />
        			<input name="itemDetalle8" type="text" id="itemDetalle8" value="" size="90" maxlength="150" />
        		</td><td>
        			<input name="itemImporte8" type="text" id="itemImporte8" value="" size="30" maxlength="10" />
        		</td></tr>
        		

        	</table>

    	</td>
      </tr>
        <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;">Datos de Cuenta Origen</div></td>
      </tr>
      <tr>
	  <td class="texto" align="right">Cuenta Origen:</td>
          <td align="left"><?php cargarListaCuentaContable("CuentaOrigen");?></td>
        </tr>
        <tr>
          <td class="texto" align="right">Saldo Cuenta Origen:</td>
          <td align="left"><input type="text" readonly name="SaldoOrigen" id="SaldoOrigen" size="10" /></td>
        </tr>
        <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;"><strong>Datos de Cuenta Destino</strong></div></td>
      </tr>
        <tr>
	  <td class="texto" align="right">Cuenta Destino:</td>
          <td align="left"><?php cargarListaCuentaContable("CuentaDestino");?></td>
        </tr>
        <tr>
          <td class="texto" align="right">Saldo Cuenta Destino:</td>
          <td align="left"><input type="text" readonly name="SaldoDestino" id="SaldoDestino" size="10" /></td>
        </tr>
        <tr>
          <td class="texto" align="right">&nbsp;</td>
          <td align="left"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar datos" width="48" height="48" border="0" title="Guardar datos" /><br />Guardar Pago</button>
          <!-- <a href="#" target="_blank" id="btnImprimirRecibo"><img src="imagenes/printer.png" alt="Imprimir" width="48" height="48" border="0" title="Imprimir" /></a> --></td>
        </tr>        
    </table>
    </form>

</div>
<p></p>

	<div id="divBuscador">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Ordenes de Pago a Proveedores</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">Año para comparar: <?php cargarListaAnios("Anio");?></td>
          </tr>
          <!-- <tr>
            <td align="center" class="texto">Tipo de Egreso: <?php //cargarListaEgresoTipo("TipoEgreso", true);?></td>
          </tr> -->
          <tr>
            <td align="center" class="texto">
            
            <div id="mostrarResultado">
            
            </div>
            
            
                      
            </td>
          </tr>
        </table>
      
</div>
<div id="divFecha">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"> Buscar Órdenes de Pago</div></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp</td>
          </tr>
          <tr>
          	<tr>
            <td colspan="2">&nbsp</td>
          </tr>
          <tr>
          	<tr>
            <td colspan="2" align="center" class="texto">Filtrar por Fecha</td>
          </tr>
          <tr>
	     <td align="right" class="texto">
           <input name="fechaDesde" type="text" id="fechaDesde" class="required fechaCompleta" value="<?php echo date("d/m/Y");?>" />
           <br />
           <strong>Fecha Desde</strong></td>
         <td class="texto"><input name="fechaHasta" type="text" id="fechaHasta" class="required fechaCompleta" value="<?php echo date("d/m/Y");?>" />
           <br /> 
         <strong>Fecha Hasta </strong></tr>
         <tr>
          	<tr>
            <td colspan="2">&nbsp</td>
          </tr>
          <tr>
          	<tr>
            <td align="right" class="texto">Ordenar por:</td>
            <td><select name="filtroOrdenar" id="filtroOrdenar">
            	<option value="orden">Nº Orden</option>
            	<option value="fecha">Fecha de Pago</option>
            	<option value="proveedor">Proveedor</option>
            </select></td>
          </tr>
         <tr>
          	<tr>
            <td colspan="2">&nbsp</td>
          </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrarFecha">
        Generar y Listar</button>        
        </td>
      </tr>
          <tr>
            <td colspan="2" align="center" class="texto">
            
            <div id="mostrarResultadoFecha">
            
            </div>
            
            
                      
            </td>
          </tr>
        </table>
      
</div>
	<p><br />
	  <br />
    </p>
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	