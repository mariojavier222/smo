<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "DeudorRecibo";

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
		DRe_Deu_ID = $("#DRe_Deu_ID option:selected").val();
		DRe_FechaRecibo = $("#DRe_FechaRecibo").val();
		DRe_Importe = $("#DRe_Importe").val();
		txtDetalleRecibo = $("#txtDetalleRecibo").val();
		formaPago = $("#formaPago option:selected").val();
		CuentaOrigen = $("#CuentaOrigen option:selected").val();
		CuentaDestino = $("#CuentaDestino option:selected").val();
		Recibo_Sucursal = $("#Recibo_Sucursal").val();
		Recibo_Numero = $("#Recibo_Numero").val();

		if (DRe_Deu_ID == -1){			
			$("#DRe_Deu_ID").focus();
			mostrarCartel("Seleccione un Deudor", "ERROR");
			return;
		}
		if (DRe_FechaRecibo.length==0){
			mostrarCartel("Debe escribir una Fecha para el Ingreso", "ERROR");
			$("#DRe_FechaRecibo").focus();
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
		$("#DRe_ReciboNumero").val(Recibo_Completo);

		//Primero validamos que el número de factura esté disponible
		bNoSeguir = false;
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: 'validarNumeroFactura', FacturaNumero: Recibo_Completo},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data == "Mal"){
					jAlert("El Número de Factura ya fue usado, revise o coloque otro número", "Error en el Factura");
					bNoSeguir = true;
				}
			}
		});//fin ajax//*/
		if (bNoSeguir) return;
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
			mostrarCartel("Debe escribir un Detalle por el Servicio brindado al Deudor", "ERROR");
			$("#txtDetalleRecibo").focus();
			return;
		}
		if (DRe_Importe.length==0){
			mostrarCartel("Debe escribir un Importe para el Ingreso", "ERROR");
			$("#DRe_Importe").focus();
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
				if (data!="mal"){
					window.open('imprimirFacturaCuota.php?FacturaNumero=' + Recibo_Completo, '_blank');
				}else{
					jAlert(data, "Resultado de guardar los datos");
				}
							
			}
		});//fin ajax
	}
});
$(document).ready(function(){
	
	$("#DRe_FechaRecibo").mask("99/99/9999");
	$("#DRe_FechaRecibo").val("<?php echo date('d/m/Y');?>");
	$("#ChequeFecha").mask("99/99/9999");
	$("#DRe_FechaRecibo2").mask("99/99/9999");
	//$("#Rec_Importe").mask("99/99/");
	$("#formDatos").validate();//fin validation
	//$("#formDatosAdelanto").validate();//fin validation		
	
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
		recargarPagina();/*
		$("#barraGuardar").show();				
		$("#mostrarNuevo").fadeIn();
		//$("#mostrarAdelanto").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		$("#divFecha").hide();
		//$("#Cuenta").focus();
		limpiarDatos();*/
	});
	/*$("#btnImprimir").click(function(evento){
		evento.preventDefault();
		//datos = $("#formDatosAdelanto").serialize();
		a = $("#textoDetalle").val();
		b = $("#Detalle").val();
		c = $("#DRe_FechaRecibo2").val();
		d = $("#DRe_Importe2").val();
		//alert(d);
		pdfStream = "imprimirAdelantoSueldo.php?a="+a+"&b="+b+"&c="+c+"&d="+d;
		window.open(pdfStream);
		
	});//fin click*/
	$("#btnImprimirRecibo").click(function(evento){
		evento.preventDefault();
		//datos = $("#formDatosAdelanto").serialize();
		a = $("#txtDetalleRecibo").val();
		b = $("#DRe_Deu_ID option:selected").text();
		c = $("#DRe_FechaRecibo").val();
		d = $("#DRe_Importe").val();
		//e = $("#Rec_TipoRecibo option:selected").val();
		f = $("#formaPago option:selected").val();
		g = $("#DRe_ReciboNumero").val();
		h = $("#txtRazonSocial").val();
		i = $("#txtTitulo").val();
		
		//alert(d);
		pdfStream = "imprimirReciboIngreso.php?a="+a+"&b="+b+"&c="+c+"&d="+d+"&e="+e+"&f="+f+"&g="+g+"&h="+h+"&i="+i;
		window.open(pdfStream);
		
	});//fin click
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		tipo = 1;
		$("#formDatos").submit();
	});
	/*$("#barraGuardar2").click(function(evento){
		evento.preventDefault();
		tipo = 2;
		$("#formDatosAdelanto").submit();
	});*/
	
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
	$("#DRe_Deu_ID").change(function () {
   		$("#DRe_Deu_ID option:selected").each(function () {
			DRe_Deu_ID = $(this).val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
					url: 'cargarOpciones.php',
				data: {opcion: 'traerDatosDeudor', Deu_ID: DRe_Deu_ID},
				success: function (data){
					//alert(data);
					if (!data){
						jAlert("No existen datos relacionados con ese Deudor", "Atención");	
					}else{
						arreDatos = data.split(";"); 
						$("#txtRazonSocial").val(arreDatos[0]);
						$("#txtCUIT").val(arreDatos[1]);
						//$("#CuentaDestino").val(arreDatos[2]);
						//$("#CuentaDestino").change();
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
   /*$("#TipoEgreso").change(function () {
   		$("#TipoEgreso option:selected").each(function () {
			//alert($(this).val());
				//TipoEgreso=$(this).val();
				
				$("#barraBuscar").click();
        });
   });*/
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#barraGuardar").hide();
		$("#mostrarNuevo").hide();
		//$("#mostrarAdelanto").hide();
		$("#divBuscador").fadeIn();
		$("#divFecha").hide();
		Anio = $("#Anio").val();
		//TipoEgreso = $("#TipoEgreso").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Anio: Anio},
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
		//$("#mostrarAdelanto").hide();
		$("#divBuscador").hide();
		$("#divFecha").show();	
		$("#barraMostrarFecha").click();	
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
				url: 'mostrarIngresosDetalleFecha.php',
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
	
	
	/*$("#DRe_Importe").focusout(function(event){
		event.preventDefault();		//alert();return;
		vImporte = $("#DRe_Importe").val();
		if (vImporte>=1000){
			$("#formaPago").val("Cheque");
			$(".Cheque").show();
		}else{
			$(".Cheque").hide();
		}
	});*/
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
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Agregar un nuevo Registro" width="48" height="48" border="0" title="Agregar un nuevo Registro" /><br />Nuevo Ingreso</button> </td>
        <?php
		//if ($_SESSION['sesion_UsuID']==12 || $_SESSION['sesion_UsuID']==2){
        ?>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Registros" width="48" height="48" border="0" title="Buscar Registros" /><br />
      Listar Ingresos</button></td>
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"> Cargar Nueva Orden de Ingreso</div></td>
      </tr>
          <tr>
            <td class="texto" align="right">Nº de Orden (no tocar):</td>
            <td align="left">
            <input name="DRe_ID_2" type="text" id="DRe_ID_2" size="10" disabled value="<?php echo $DRe_IDUltimo = substr("00000000".buscarUltimaOrdenIngreso(),-8);?>">
            <input name="DRe_ID" type="hidden" id="DRe_ID" size="10" readonly="readonly" value="<?php echo $DRe_IDUltimo;?>">
            <input name="opcion" type="hidden" id="opcion" value="guardarOrdenIngreso" /></td>
        </tr>   
        <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;">Datos del Deudor </div></td>
      </tr>       
          <tr>
	  <td class="texto" align="right">Deudor:</td>
          <td align="left">
          <?php cargarListaDeudor("DRe_Deu_ID");?>
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
	  <td class="texto" align="right">Número de Factura Emitida:</td>
          <td align="left"><input name="DRe_ReciboNumero" id="DRe_ReciboNumero" type="hidden" >
          	<input name="Recibo_Sucursal" id="Recibo_Sucursal" type="text" value="" required size="4" maxlength="4">
          	<input name="Recibo_Numero" id="Recibo_Numero" type="text" size="8" maxlength="8" value="" required>
          </td>
        </tr>  
        <tr>
        <td colspan="2" align="center"><div class="ui-state-highlight ui-corner-all texto" style="margin-top: 10px; padding: 0 .7em;">Datos del Ingreso </div></td>
      </tr>
        <tr>
	  <td class="texto" align="right">Fecha de Ingreso:</td>
          <td align="left"><input name="DRe_FechaRecibo" id="DRe_FechaRecibo" type="text" required></td>
        </tr>          
	    <td class="texto" align="right">Importe ($):</td>
          <td align="left"><input name="DRe_Importe" id="DRe_Importe" type="text" title="Usar puntos para separar decimales" required></td>
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
          <td align="left"><input name="txtTitulo" type="text" id="txtTitulo" value="ORDEN DE INGRESO" /></td>
        </tr>
        
        <tr>
          <td class="texto" align="right">Detalle del Servicio:</td>
          <td align="left"><textarea required name="txtDetalleRecibo" id="txtDetalleRecibo" cols="45" rows="5"></textarea></td>
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
          <td align="left"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar datos" width="48" height="48" border="0" title="Guardar datos" /><br />Guardar Ingreso</button>
          <!-- <a href="#" target="_blank" id="btnImprimirRecibo"><img src="imagenes/printer.png" alt="Imprimir" width="48" height="48" border="0" title="Imprimir" /></a> --></td>
        </tr>        
    </table>
    </form>

</div>
<p></p>

	<div id="divBuscador">
      
       <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Ordenes de Ingresos</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">Año para comparar: <?php cargarListaAnios("Anio");?></td>
          </tr>          
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
            <td colspan="2"><div align="center" class="titulo_noticia"> Buscar Órdenes de Ingreso</div></td>
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
            	<option value="proveedor">Deudor</option>
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
	