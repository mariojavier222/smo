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

error_reporting(E_ERROR | E_PARSE); ini_set('display_errors', 1);

$UsuID = $_SESSION['sesion_UsuID'];
//$esUniversitario = $_POST['esUniversitario'];

if (!empty($UsuID)){
	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    CuotaPersonaCola
	INNER JOIN CuotaPersona 
        ON (CPT_Lec_ID = Cuo_Lec_ID) AND (CPT_Per_ID = Cuo_Per_ID) AND (CPT_Niv_ID = Cuo_Niv_ID) AND (CPT_CTi_ID = Cuo_CTi_ID) AND (CPT_Alt_ID = Cuo_Alt_ID) AND (CPT_Numero = Cuo_Numero)
    INNER JOIN Lectivo 
        ON (CPT_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (CPT_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (CPT_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (CPT_Alt_ID = Alt_ID)
    INNER JOIN Usuario 
        ON (CPT_Usu_ID = Usu_ID)
	INNER JOIN Persona 
        ON (CPT_Per_ID = Per_ID)	
WHERE (CPT_Usu_ID = $UsuID)	ORDER BY Per_Apellido, Per_Nombre, CPT_Per_ID, CPT_CTi_ID, CPT_Alt_ID, CPT_Numero ASC;";
//echo $sql;
}else{
	exit;}

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	?>
    <script type='text/javascript' src='js/jquery.maskedinput-1.3.min.js'></script>
<script language="javascript">
$(document).ready(function(){

	$("#pagarCuotas").button();
	$("#botonBuscarFactura").button();
	$("#CUIT").mask('99-99999999-9');
	
	$("input[id^='importeAbonar']").keyup(function(evento){
		evento.preventDefault();
		i = this.id.substr(13,10);
		vImporte = parseInt($("#importeCuota" + i).val());
		vValor = parseInt(this.value);
		if (vValor <= 0 || vValor > vImporte){
			jAlert("El importe a abonar para esta cuota debe ser menor al valor de la cuota", "Error");
			$("#importeAbonar" + i).val(vImporte);
			vValor = vImporte;
		}		
		vImporte = vValor;
		//alert(vImporte);
		calcularTotalPagar();
		calcularTotalReg();
	
	 });//fin evento click//*/
	$("input[id^='gastosAbonar']").keyup(function(evento){
		evento.preventDefault();
		i = this.id.substr(12,10);

		vImporte = parseInt($("#gastosAbonar" + i).val());
		$("#importeCuota" + i).val(vImporte);
		$("#importeAbonar" + i).val(vImporte);
		calcularTotalPagar();
		calcularTotalReg();
	
	 });//fin evento click//*/
	 
	 
	 
	 <?php
	   $Tipo = $_POST['Tipo'];
	   if ($Tipo == "cuotas") {
		   echo 'opcionBuscarUltima = "buscarFacturaUltima";';
		   echo '$("#FacturaNumero").removeAttr("readonly");';
		   echo '$("#FacturaNumero").mask("9999-99999999");';
		   $FTiID = 1;
		   $imprimirRecibo = "imprimirFacturaCuota";
	   }
	   if ($Tipo == "recibos") {
		   echo 'opcionBuscarUltima = "buscarReciboUltima";';
		   //echo '$("#FacturaNumero").attr("readonly", "readonly");';
		   $FTiID = 2;
		   $imprimirRecibo = "imprimirReciboCuota";
	   }
	   ?>

	$("#pagarCuotas").click(function(evento){
		evento.preventDefault();
		
		vTotReg=$("#totali").val();
        //alert("");/*
        //alert(vTotReg);/*
        if (vTotReg < 1){
                jAlert("Debe haber al menos 1 concepto", "Error en cantidad de conceptos");
                return false;        
        }
        if (vTotReg > 20){
                jAlert("Solo puede pagar hasta 20 conceptos", "Error en cantidad de conceptos");
                return;
        }

		vForID = $("#ForID").val();
		if (vForID == -1){
			jAlert("Por favor elija una forma de pago antes de pagar  la cuota", "Error en la Forma de pago");
			return;
		}
		vRazon = $("#Razon").val();
		if (vRazon.length == 0){
			jAlert("Por favor escriba una razón social antes de pagar  la cuota", "Error en la Razón Social");
			return;
		}
		vFacturaNumero = $("#FacturaNumero").val();
		if (vFacturaNumero.length == 0){
			jAlert("Por favor escriba un número de factura antes de pagar  la cuota", "Error en el Número de Factura");
			return;
		}
		$("#cargando").show();
		
		//Primero validamos que el número de factura esté disponible
		bNoSeguir = false;
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: 'validarNumeroFactura', FacturaNumero: vFacturaNumero},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data == "Mal"){
					jAlert("El Número de Factura ya fue usado, revise o coloque otro número", "Error en el Factura");
					bNoSeguir = true;
				}
			}
		});//fin ajax//*/
		if (bNoSeguir) return;
		vDomicilio = $("#Domicilio").val();
		vCUIT = $("#CUIT").val();
		vFacturaImporteTotal = $("#FacturaImporteTotal").val();
		vFTiID = $("#FTiID").val();
		vCVeID = $("#CVeID").val();
		vIvaID = $("#IvaID").val();
		//Segundo generamos los datos generales de la factura
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			//data: {opcion: 'generarFactura', FacturaNumero: vFacturaNumero, Razon: vRazon, Domicilio: vDomicilio, CUIT: vCUIT, FacturaImporteTotal: vFacturaImporteTotal, FTiID: vFTiID, CVeID: vCVeID, IvaID: vIvaID},
			data: $("#form1").serialize(),
			url: 'cargarOpciones.php',
			success: function(data){ 
				data=data.trim();
				if (data == "Mal"){
					jAlert("La Factura no pudo ser grabada, intentelo de nuevo más tarde", "Error en generar la Factura");
					return;
				}else{
					FacturaNumero = $('#FacturaNumero').val();
					//alert(FacturaNumero);
					
				//jAlert(data, "Resultado de la operación");
				

				//recargarPagina();
				window.open('<?php echo $imprimirRecibo;?>.php?FacturaNumero=' + FacturaNumero, '_blank');
				$("#dialog").dialog("close");
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					url: 'cargarCuotasImpagas.php',
					success: function(data){ 
						$("#principal").html(data);
					}
				});//fin ajax//*/
		
					
					
				}
			}
		});//fin ajax//*/

		
		$("#cargando").hide();
	 });//fin evento click de pagar cuotas//*/
	 
	 
	$("a[id^='detalles']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(8,10);
		//alert(i);
		vCuota = $("#Nuevo" + i).val();
		vDNI = $("#DNI").val();
		//alert(vCuota);
		$("#cargando").show();
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'mostrarDetalleCuota', DNI: vDNI, Cuota: vCuota},
					url: 'cargarOpciones.php',
					success: function(data){ mostrarAlerta(data,"Detalles de la Cuota");}
		});//fin ajax///
		$("#cargando").hide();
	 });//fin evento click//*/

	$("a[id^='eliminarFila']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(12,10);
		//alert(i);		
		$("#filaCola" + i).remove();
		calcularTotalPagar();
		calcularTotalReg();
	 });//fin evento click//*/

	$("a[id^='eliminarFilaCola']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(16,10);
		//alert(i);		
		vCuota = $("#datosCuota" + i).val();
		$("#filaCola" + i).remove();
		$.ajax({
			type: "POST",
			data: {opcion: 'quitarCuotaColaPago', Cuota: vCuota},
			url: 'cargarOpciones.php'
		});//fin ajax///
		calcularTotalPagar();
		calcularTotalReg();
	 });//fin evento click//*/

	 ///////////////////////////////////////////////////////////////////ACCIONES//////////
 	function calcularTotalPagar(){
		vTotal = 0;
		$("input[id^='importeAbonar']").each(function(){
			i = this.id.substr(13,10);		
			//alert(i);			
			vImporte = parseInt($("#importeAbonar" + i).val());
			//alert(vImporte);
			vTotal += parseInt(vImporte);		
		});
		$("#totalPagar").val(vTotal);
		$("#FacturaImporteTotal").val(vTotal);
	}	

	function calcularTotalReg(){
        vTotal = 0;
        i=0;
        $("input[id^='importeAbonar']").each(function(){
            i = i+1;                
        });
        $("#totali").val(i);
    }        
             
	//marcar y desmarcar todo
	$("#marcar").click(function(evento){
		evento.preventDefault();
		vTotal = 0;
		$( ":checkbox").attr('checked', 'checked');
		
		/*$("#totalCuotas").val($("#totalesCuotas").val());
		$("input[id^='Nuevo']").each(function(){
			i = this.id.substr(5,10);					
			vImporte = parseInt($("#cuotas" + i).val());
			vTotal += parseInt(vImporte);		
		});
		$("#totalPagar").val(vTotal);*/
	});
	$("#desmarcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").removeAttr('checked');
		/*
		//$( ":checkbox").attr('checked', '');
		$("#totalPagar").val(0);
		$("#totalCuotas").val(0);*/
	}); 
	$("#quitarPagar").click(function(evento){
		evento.preventDefault();
		totalCola = 0;
		$( "input:checked").each(function(index, element) {
            
			$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'quitarCuotaColaPago', Cuota: element.value},
					url: 'cargarOpciones.php',
					success: function(data){ 
							if (data)
								totalCola = totalCola + 1;
					}
					
			});//fin ajax///
       		
		});//fin each
		
		if (totalCola>0){
			jAlert("Se quitaron " + totalCola + " cuotas para pagar después", "Cola de Pago");
			recargarPagina();
		}else{
			jAlert("No se pudieron quitar cuotas", "Cola de Pago");
		}
		
	}); 
	
	$("#botonBuscarFactura").click(function(evento){
		evento.preventDefault();

		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'buscarFacturaUltima'},
				url: 'cargarOpciones.php',
				success: function(data){ 
						if (data)
							$("#FacturaNumero").val(data);
				}
				
		});//fin ajax///
       		

		
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
	
	$("#ForID").change(function () {
   		$("#ForID option:selected").each(function () {
			//alert($(this).val());
				vForID=$(this).val();
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'cargarDetalleFormaPago', ForID: vForID},
					url: 'cargarOpciones.php',
					success: function(data){ 
							if (data){
								$("#mostrarDetallePago").html(data);
							}else{
								//jAlert("No existe un detalle para esta forma de pago", "Advertencia");
								$("#mostrarDetallePago").empty();
							}
					}
					
			});//fin ajax///
        });
   })
   
  
   //Buscamos automáticamente el número de factura
   
   $.ajax({
		type: "POST",
		cache: false,
		async: false,
		data: {opcion: opcionBuscarUltima},
		url: 'cargarOpciones.php',
		success: function(data){ 
				if (data)
					$("#FacturaNumero").val(data);
		}
			
	});//fin ajax///
   calcularTotalPagar();
   calcularTotalReg();

});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<form method="post" id="form1" autocomplete="off" onsubmit="function() { return false; }">
<!--
<div align="center" class="titulo_noticia">
  <p><br />
  <img src="imagenes/table_cuotas.png" width="48" height="48" align="absmiddle" /> Cola de Cuotas para Pagos</p>
</div>
  
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend class="texto">Resultado de la búsqueda</legend>-->
  <div id="conenedortodo">
<div id="listado">	
  
  
  <table width="100%" border="0" class="texto">
    <tr>
      <td align="center" class="fila_titulo">#</td>
      <td class="fila_titulo">Persona</td>
      <td class="fila_titulo"><div align="left">Tipo de cuota </div></td>
      <td align="center" class="fila_titulo">Mes </td>
      <td align="center" class="fila_titulo">A&ntilde;o</td>
      <td align="center" class="fila_titulo">Vencimiento</td>
      <td align="center" class="fila_titulo">Importe total</td>
      <td class="fila_titulo">Importe a pagar</td>
    </tr>
	<?php $i=0;

	//22/11/2021
	//MArio. para contar los recargos e intereses o gastos administrativos que hay en el tabla
	//porque siempre les ponía el Cuo_Numero con el mismo valor
	$j=0;//para los recargos
	
	//$juicio = tieneJuicio($DNI);
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		$clase = "";
		//$datosCurso = buscarCursoDivisionPersona($row['Cuo_Per_ID'], false);
		$datosCurso = buscarUltimoCursoDivisionPersona($row['Cuo_Per_ID']);
		$datosCurso = strip_tags($datosCurso);
		$Razon = "$row[Per_Apellido], $row[Per_Nombre] (DNI: $row[Per_DNI]) $datosCurso";
		//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
		$datosCuota = $row[Cuo_Lec_ID].";".$row[Cuo_Per_ID].";".$row[Cuo_Niv_ID].";".$row[Cuo_CTi_ID].";".$row[Cuo_Alt_ID].";".$row[Cuo_Numero];

		$Lec_ID = $row['Cuo_Lec_ID'];
		$Per_ID = $row['Cuo_Per_ID'];
		$Niv_ID = $row['Cuo_Niv_ID'];
		$Alt_ID = $row['Cuo_Alt_ID'];
		//Calculamos el importe que deber�a pagar al d�a de hoy
		$importe = $row[Cuo_Importe];
		//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
		recalcularImporteCuota($datosCuota, $importe);
		$recargo2 = obtenerRecargoCuota($row['Cuo_Per_ID'], $datosCuota);
		//if ($recargo2>0) $importe -= $recargo2;

		$fechaCuota = cfecha($row['Cuo_1er_Vencimiento']);
		//$clase = "vencida_roja";
		$fechaHoy = date("d-m-Y");
		$ya_vencida=1;
		$fecha = restarFecha($fechaCuota, $fechaHoy);
		$fechaCuota2 = $row['Cuo_1er_Vencimiento'];
		$fechaHoy2 = date("Y-m-d");
		$mesesAtrazo = 0;
		if ( $fecha > 0 ){			
			$ya_vencida=1;
		}else{
			if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		}
		$importe = intval($importe);
		$importeTotal += $importe;
		
		
		$debito = false;
		$tarjeta=  "";
		//$debito = tieneDebito($datosCuota, $tarjeta);
		if ($ya_vencida==0){
			if ($debito)
				$clase = "tiene_debito";
			}
		$detalle_alternativa="";
  		
	?>
	<tr class="<?php echo $clase?>" id="filaCola<?php echo $i;?>" height="40px">
      <td align="center"><input type="hidden" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $datosCuota;?>">
      <input type="hidden" id="datosCuota<?php echo $i;?>" name="datosCuota<?php echo $i;?>" value="<?php echo $datosCuota;?>">
      <input type="hidden" id="importeCuota<?php echo $i;?>" value="<?php echo $importe;?>">
      <?php echo $i;?></td>
      <td><strong><?php echo substr("$row[Per_Apellido], $row[Per_Nombre]",0,30);?></strong></td>
      <td><strong><?php echo $row[CTi_Nombre];?></strong><?php echo " ".$detalle_alternativa;?></td>
      
      <td><?php echo buscarMes($row[Cuo_Mes]);?></td>
      <td><?php echo $row[Cuo_Anio];?> </td>
      <td><?php echo $fechaCuota;?></td>
      <td>$ <?php echo $importe;?></td>
      <?php 
      $texto_readonly = '';
      //if ($row['Cuo_Ben_ID']!=1) $texto_readonly = 'readonly title="Las becas no admiten pagos a cuenta"';
      ?>
      <td>$ <input type="text" id="importeAbonar<?php echo $i;?>" name="importeAbonar<?php echo $i;?>" value="<?php echo $importe;?>" <?php echo $texto_readonly;?> size="7" maxlength="7"  /> <a style="cursor:pointer" id="eliminarFilaCola<?php echo $i;?>"> <img src="imagenes/bullet_delete.png" width="32" height="32" alt="Eliminar recargo" title="Eliminar recargo" border="0" style="vertical-align: middle;"/></a> </td>
    </tr>
<?php	
	//Corroboramos si tiene recargo la cuota
	if ($recargo2>0){
		$i++;
		$clase = "vencida_roja";
		$CTi_ID = 17;//Recargo por mora
		$Cuo_Numero = 1;//Número de recargos en el mes
		$fechaCuota = date("d/m/Y");
		$importeTotal += $recargo2;
		$Cuo_Numero = buscarNumeroCuotaPersonaFactura($row[Cuo_Per_ID], $row[Cuo_Lec_ID], $row[Cuo_Niv_ID], $CTi_ID, $row[Cuo_Alt_ID]); 
		
		$Cuo_Numero=$Cuo_Numero+$j;
		$j++;
		
		$datosCuota = $row[Cuo_Lec_ID].";".$row[Cuo_Per_ID].";".$row[Cuo_Niv_ID].";".$CTi_ID.";".$row[Cuo_Alt_ID].";".$Cuo_Numero;
	?>
	<tr class="<?php echo $clase?>" id="filaCola<?php echo $i;?>" height="40px">
      <td align="center"><input type="hidden" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $datosCuota;?>">
      <input type="hidden" id="datosCuota<?php echo $i;?>" name="datosCuota<?php echo $i;?>" value="<?php echo $datosCuota;?>">
      <input type="hidden" id="importeCuota<?php echo $i;?>" value="<?php echo $recargo2;?>">
      <?php echo $i;?></td>
      <td><strong><?php echo substr("$row[Per_Apellido], $row[Per_Nombre]",0,30);?></strong></td>
      <td><strong><?php echo "Recargo por Mora";?></strong><?php echo " ".$detalle_alternativa;?></td>
      
      <td><?php echo buscarMes($row['Cuo_Mes']);?></td>
      <td><?php echo $row['Cuo_Anio'];?> </td>
      <td><?php echo $fechaCuota;?></td>
      <td>$ <?php echo $recargo2;?></td>
      <td>$ <input type="text" id="importeAbonar<?php echo $i;?>" name="importeAbonar<?php echo $i;?>" readonly value="<?php echo $recargo2;?>" size="7" maxlength="7" style="vertical-align: middle;" /><a style="cursor:pointer" id="eliminarFila<?php echo $i;?>"> <img src="imagenes/bullet_delete.png" width="32" height="32" alt="Eliminar recargo" title="Eliminar recargo" border="0" style="vertical-align: middle;"/></a></td>
    </tr>
		  <?php	 	
		 } 

	}//fin del while
	//Armamos el campo para los gastos administrativos
	$i++;
	$CTi_ID = 16;//gastos administrativos
	$Cuo_Numero = 1;//Número de recargos en el mes
	$fechaCuota = date("d/m/Y");
	$importeTotal += 0;
	$Cuo_Numero = buscarNumeroCuotaPersonaFactura($Per_ID, $Lec_ID, $Niv_ID, $CTi_ID, $Alt_ID); 

	$Cuo_Numero=$Cuo_Numero+$j;
	$j++;

	$datosCuota = $Lec_ID.";".$Per_ID.";".$Niv_ID.";".$CTi_ID.";".$Alt_ID.";".$Cuo_Numero;
	$clase = "tiene_debito";
	$mes = date("n");
	$anio = date("Y");
	?>
</table>
</div>
</fieldset>

<?php
$CajaID = cajaAbiertaUsuario($UsuID);
if (!$CajaID){
	$disabled = "disabled=disabled";
?>
<div class="borde_alerta" align="center">
		  <p class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se puede pagar estas cuotas porque no existe una Caja Abierta</p></div>
<?php
}

?>

<br />

<fieldset class="recuadro_inferior texto" style="alignment-adjust:middle">
 <div align="center"> Total a pagar: 
  $
  <input name="totalPagar" type="text" id="totalPagar" size="15" value="<?php echo $importeTotal;?>" disabled="disabled" /></div>


  <input name="totalCuotas" type="hidden" id="totalCuotas" value="0" />
  <input name="totali" type="hidden" id="totali" value="<?php echo $i;?>" />
  <input name="totalesCuotas" type="hidden" id="totalesCuotas" value="<?php echo $importeTotal;?>" />
  <input type="hidden" value="<?php echo $DNI;?>" id="DNI2" name="DNI2"/>
  <input type="hidden" value="generarFactura" id="opcion" name="opcion"/>
</fieldset>
  <br />
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend class="texto">Forma de Pago</legend>
<p class="titulo_noticia">Elija una forma de pago: <?php cargarListaFormaPago("ForID");?></p>
<div id="mostrarDetallePago"></div>
</fieldset>
<fieldset class="recuadro_inferior texto">
</fieldset>
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend class="texto">Datos de facturación</legend>
<table width="85%" border="0" align="center" cellpadding="2" cellspacing="2" class="texto">
  <!--<tr>
    <td align="right">Tipo de Factura:</td>
    <td align="left"><?php //cargarListaTipoFactura("FTiID");?></td>
  </tr>
  <tr>
    <td align="right">Condición de Venta:</td>
    <td align="left"><?php //cargarListaCondVenta("CVeID");?></td>
  </tr>
  <tr>
    <td align="right">IVA Cliente:</td>
    <td align="left"><?php //cargarListaIVACliente("IvaID");?></td>
  </tr>-->
  <tr>
        <td align="right">Razón social:</td>
        <td align="left"><input name="Razon" type="text" id="Razon" size="80" maxlength="100" class="input_editar" value="<?php echo $Razon;?>" />
        <input name="Domicilio" type="hidden" class="input_editar" id="Domicilio" size="50" maxlength="100" />
        <input name="CUIT" type="hidden" class="input_editar" id="CUIT" size="20" maxlength="20" /></td>
  </tr>
  <!-- <tr>
        <td align="right">Curso actual:</td>
        <td align="left"><input name="datosCurso" type="text" id="datosCurso" size="80" maxlength="100" class="input_editar" value="<?php //echo $datosCurso;?>" />
        <input name="Domicilio" type="hidden" class="input_editar" id="Domicilio" size="50" maxlength="100" />
        <input name="CUIT" type="hidden" class="input_editar" id="CUIT" size="20" maxlength="20" /></td>
  </tr> -->
  <tr>
    <td align="right">Importe total ($):</td>
    <td align="left"><input name="FacturaImporteTotal" type="text" class="input_editar" id="FacturaImporteTotal" value="<?php echo $importeTotal;?>" size="10" maxlength="8" readonly="readonly" /></td>
  </tr>
  <tr>
    <td align="right">Nº Factura:</td>
    <td align="left"><input name="FacturaNumero" type="text" class="input_editar" style="font-size:18px" id="FacturaNumero" size="20" maxlength="13" readonly="readonly" /><input name="FTiID" type="hidden" id="FTiID" value="<?php echo $FTiID;?>" />
    
    
    
     <!-- <button id="botonBuscarFactura">Buscar Número</button>--> </td>
  </tr>
  <tr>
    <td align="right">Detalle o comentario adicional del recibo (este mensaje no saldrá en el recibo impreso pero ustedes podrán verlo):</td>
    <td align="left"><textarea name="Fac_Detalle" rows="5" cols="50"></textarea></td>
  </tr>
       
        </table>
</fieldset>
<div align="right">
<button id="pagarCuotas" style="width:200px; height:60px; font-size:18px" title="Pagar todas las cuotas" <?php echo $disabled;?> >Pagar</button>
</div>



</form>
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron cuotas sin pagar.</span>
<?php
}

?>
</div>