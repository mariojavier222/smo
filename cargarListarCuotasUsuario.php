<?php
require_once("conexion.php");
include_once("comprobar_sesion.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
//sleep(3);

$DNI = $_SESSION['sesion_usuario'];
//$DNI = "18618965";
if (!is_numeric($DNI)){
	?>
	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible para Usted.</span></div></p><p></p>
<?php
	exit;
}
//buscarTipoDoc($DNI, $DocID);

$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$usuario_nombre = $row[Per_Nombre];
	$usuario_apellido = $row[Per_Apellido];
	$PerID = $row[Per_ID];
	$DocID = $row[Per_Doc_ID];
}
//echo "DNI: $DNI";
if (!empty($DNI)){
	$fechaHoy = date("d-m-Y");
	$Deuda = Obtener_Deuda_Sistema($PerID);
	if ($Deuda>0){		
	?>
<link href="css/general.css" rel="stylesheet" type="text/css" />

		<br /><div class="borde_alerta" align="center">
		  <p class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Al d&iacute;a de la fecha <?php echo $fechaHoy;?> la persona <strong><?php echo "$usuario_nombre $usuario_apellido";?></strong> tiene una deuda vencida de $<?php echo $Deuda;?>.</p></div><br />
	<?php
	}else{
	
	}//*/

	$sql = "SELECT * FROM Persona WHERE Per_DNI = '$DNI' ";
}else{
	exit;
	}
//echo $sql;
$result_prim = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result_prim);


if ($total>0){	
	?>
	
<script language="javascript">
$(document).ready(function(){

	$("input[id^='Nuevo']").click(function(evento){
		//evento.preventDefault();
		i = this.id.substr(5,10);
		
		//vCuota = $("#Nuevo" + i).val();
		vImporte = parseInt($("#cuotas" + i).val());
		//alert(vImporte);
		vTotal = parseInt($("#totalPagar").val());
		vTotalCuotas = parseInt($("#totalCuotas").val());
		if (this.checked){
			vTotal += parseInt(vImporte);
			vTotalCuotas += 1;
		}else{
			vTotal -= parseInt(vImporte);
			vTotalCuotas -= 1;
		}
		$("#totalPagar").val(vTotal);
		$("#totalCuotas").val(vTotalCuotas);
	 });//fin evento click//*/

	$("a[id^='imprimir']").click(function(evento){
		//evento.preventDefault();
		i = this.id.substr(8,10);
		//alert(i);
		vCuota = $("#Nuevo" + i).val();
		vDNI = $("#DNI" + i).val();
		vesUniversitario = $("#esUniversitario").val();
		//alert(vCuota);
		$("#cargando").show();
		//$(this).attr('target','_blank'); 
		$(this).attr("href","imprimir_cuota_siucc.php?DNI=" + vDNI + "&cuota=" + vCuota); 
		/*if (vesUniversitario==1){
			$.ajax({
					type: "GET",
					cache: false,
					async: false,
					data: {DNI: vDNI, cuota: vCuota},
					url: 'imprimir_cuota_siucc.php'
			});//fin ajax///
		}//fin if//*/
		$("#cargando").hide();
	 });//fin evento click//*/
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
					success: function(data){ 
					 mostrarAlerta2(data,"DETALLE DE LA CUOTA",400,300);
					//mostrarAlerta2(data,"Detalles de la Cuota");}
					}
		});//fin ajax///
		$("#cargando").hide();
	 });//fin evento click//*/
	 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		if ($("#totalCuotas").val()==0){
			mostrarAlerta("Antes de imprimir debe seleccionar al menos una cuota","Atención");
			return false;	
		}
		//vDNI = $("#DNI").val();
		/*$("input[id^='Nuevo']").each(function(){
			alert(this.value);									
		});//*/
		//alert(vCuota);
		$("#cargando").show();
		$("#formTodas").submit();
		$("#cargando").hide();
	 });//fin evento click//*/
 
 		

	//marcar y desmarcar todo
	$("#marcar").click(function(evento){
		evento.preventDefault();
		vTotal = 0;
		$( ":checkbox").attr('checked', 'checked');
		$("#totalCuotas").val($("#totalesCuotas").val());
		$("input[id^='Nuevo']").each(function(){
			i = this.id.substr(5,10);					
			vImporte = parseInt($("#cuotas" + i).val());
			vTotal += parseInt(vImporte);		
		});
		$("#totalPagar").val(vTotal);
	});
	$("#desmarcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").attr('checked', '');
		$("#totalPagar").val(0);
		$("#totalCuotas").val(0);
	}); 
});//fin de la funcion ready
function fac_Detalle(Fac_ID){
  $.ajax({
	  url: 'cargarOpciones.php',
	  type: "POST",
	  cache: false,
	  data: { opcion: "VerDetallesFactura",Fac_ID:Fac_ID},
	  async: true,
	  success: function(data2){
		  //$("#facturas_Listar_Contenido").append(data2);
		  //mostrarAlerta2(data2,'Detalle: Nota De Crédito',500,200);
		  mostrarAlerta2(data2,"DETALLE DE LA FACTURA",600,500);
		  }
	  });
  return false;
}//fin function
function mostrarAlerta3(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {
					'Aceptar': function() {
					$(this).dialog('close');
				}
				
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion
function mostrarAlerta2(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {
					'Aceptar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<br /><div class="borde_alerta" align="center">
  <p class="texto">Se ha seleccionado a la persona  <strong><?php echo "$usuario_nombre $usuario_apellido";?></strong> (DNI: <?php echo $DNI;?>) para ver el detalle de las cuotas que tiene generadas..</p></div><br />
 <form action="imprimir_cuota_siucc_varias_PadresHijos.php" id="formTodas" target="_blank" method="post" class="texto"> 
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend class="texto">Resultado de la b&uacute;squeda</legend>
  <input name="esUniversitario" type="hidden" id="esUniversitario" value="<?php echo $esUniversitario;?>" />
<div id="listado">	
  
  
  <table width="100%" border="0">
    <tr>
      <th class="fila_titulo">&nbsp;</th>
      <th class="fila_titulo"><div align="left">Mes</div></th>
      <th align="center" class="fila_titulo">Tipo de cuota</th>
      <th align="center" class="fila_titulo">A&ntilde;o</th>
      <th align="center" class="fila_titulo">Vencimiento</th>
      <th align="center" class="fila_titulo">Importe</th>
      <th class="fila_titulo"><div align="left">Acciones</div></th>
    </tr>
	<?php $i=0;
	
	while ($row_prim = mysqli_fetch_array($result_prim)){
		
		//$juicio = tieneJuicio($row_prim[Per_DNI]);
		?>
      	<tr bgcolor="#FF9900" height="40px">
	  	<td colspan="7" bgcolor="#ABFF93" class="textoInformativo"><?php 
		$foto = buscarFoto($DNI, $DocID, 30);
		echo "$foto <strong>$row_prim[Per_Apellido], $row_prim[Per_Nombre]</strong> (DNI: $row_prim[Per_DNI])";?>&nbsp;</td>
	  	</tr>

        <?php
		$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	Obtener_LectivoActual($LecID, $LecNombre);
	$sql = "SELECT * FROM
    CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
WHERE (Cuo_Per_ID = $PerID AND Cuo_Lec_ID = $LecID )	ORDER BY  Cuo_Pagado DESC, Cuo_1er_Vencimiento, Cuo_CTi_ID, Cuo_Alt_ID  ASC;";
	  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  if (mysqli_num_rows($result)==0){
		  ?>
          <tr bgcolor="#FF9900" height="40px">
	  	<td colspan="7" bgcolor="#FFFF99" class="textoInformativo">No existen cuotas generadas</td>
	  	</tr>
          <?php
	  }else{//*/
		  while ($row = mysqli_fetch_array($result)){		
			$i++;
			//Creamos una variable que guarde todos los datos de identificacin de la Cuota
		$datosCuota = $row[Cuo_Lec_ID].";".$row[Cuo_Per_ID].";".$row[Cuo_Niv_ID].";".$row[Cuo_CTi_ID].";".$row[Cuo_Alt_ID].";".$row[Cuo_Numero];
		//Calculamos el importe que debera pagar al da de hoy
		$importe = $row[Cuo_Importe];
		//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
		$importeOriginal = $importe;
		recalcularImporteCuota($datosCuota, $importe);
		if ($row[Cuo_Pagado]==1) $importe = buscarPagosTotales($datosCuota);
		$fechaCuota = cfecha($row[Cuo_1er_Vencimiento]);
		$clase = "vencida_roja";
		$fechaHoy = date("d-m-Y");
		$ya_vencida=1;
		$fecha = restarFecha($fechaCuota, $fechaHoy);
		$fechaCuota2 = $row[Cuo_1er_Vencimiento];
		$fechaHoy2 = date("Y-m-d");
		$mesesAtrazo = 0;
		if ( $fecha > 0 ){
			/*$mesesAtrazo = restarMeses($fechaCuota2, $fechaHoy2);
			$dia15 = substr($fechaHoy2,-2);
			
			if ($mesesAtrazo==0 && $dia15>15) $mesesAtrazo=1;
			echo $importe;exit;
			$importe += ($row[Cuo_Recargo_Mensual] * $mesesAtrazo);
			//$recargo = $row[Cuo_Recargo_Mensual] * $mesesAtrazo;
			//echo $recargo;exit;*/
			
			$ya_vencida=1;
		}else{
			if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		}
		$importe = intval($importe);
		
		
		$debito = false;
		$tarjeta=  "";
		//$debito = tieneDebito($datosCuota, $tarjeta);
		if ($ya_vencida==0){
			if ($debito) $clase = "tiene_debito";
			//$clase = "vencida_roja";
		}
		
		if ($row[Cuo_Pagado]==1) $clase = "cuota_pagada";
		if ($row[Cuo_Cancelado]==1) $clase = "cuota_cancelada";
		if ($row[Cuo_Anulado]==1) $clase = "cuota_anulada";
		if(($clase!='vencida_roja')&&($clase!='cuota_pagada'))
		{
			if ($row[Cuo_Ben_ID]!=1) $clase = "cuota_beneficio";
		}
		
		if ($row[Cuo_Pagado]==1 || $row[Cuo_Cancelado]==1 |$row[Cuo_Anulado]==1) {
			$noMostrar = true;
			$desabilitada = "disabled=disabled";
		}else{
			$noMostrar = false;
			$desabilitada = "";
		}
			
		$detalle_alternativa="";
  		$alternativas = tieneAlternativas($datosCuota);
		if ($alternativas>1)
			$detalle_alternativa = " (".$row[Alt_Nombre].")";
		
	  ?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      <td><input type="checkbox" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $datosCuota;?>" <?php echo $desabilitada;?> class="activo"><input type="hidden" id="cuotas<?php echo $i;?>" value="<?php echo $importe;?>"></td>      
      <td><strong><?php echo buscarMes($row[Cuo_Mes]);?></strong></td>
      <td><?php echo $row[CTi_Nombre];?><?php echo " ".$detalle_alternativa;?></td>
      <td><?php echo $row[Cuo_Anio];?> <?php //echo $mesesAtrazo;?></td>
      <td><?php echo $fechaCuota;//cfecha($row[ChC_1er_Vencimiento]);?></td>
      <td><?php if($row[Cuo_Ben_ID]!=1)
	  {
		  $sql1 = "SELECT 	Ben_ID, 
	Ben_Nombre, 
	Ben_Siglas
	 
	FROM 
	CuotaBeneficio WHERE Ben_ID=$row[Cuo_Ben_ID]";
	//echo $sql1;
	$result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
	$row1 = mysqli_fetch_array($result1); 
	$Ben_Nombre=$row1[Ben_Nombre];
		  
		  ?>
         <a style="cursor:pointer;font-size:15px; color:#009" title="<?php echo "BENEFICIO: ".$Ben_Nombre ?>">$<?php echo $importe;
		 //echo " - $importeOriginal"; ?> </a> 
		  <?php
	  }
	  else
	  {
	  echo "$".$importe;
	  }//$row[ChC_1er_Importe];?></td>
      <td><?php //echo $totales;
	  if (!$juicio){
	  buscarPagosParciales($datosCuota);
	  if (!$noMostrar){
	  ?> 
      <a style="cursor:pointer" id="detalles<?php echo $i;?>"> <img src="imagenes/magnifier_zoom_in.png" width="32" height="32" alt="Detalles de la cuota" title="Detalles de la cuota" border="0"/></a>
       
	  <?php
	  }//fin NoMostrar
	  	
	  
	  }//fin juicio
	  ?>	  </td>
    </tr>
		  <?php		  
	}//fin del while
	  }//fin del else
	}//fin del while
	?>  
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px"><div align="left">
<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir las cuotas seleccionadas" title="Imprimir las cuotas seleccionadas" width="32" border="0" align="absmiddle" /></a> - <?php echo "Se econtraron $i cuotas sin pagar";?></div>
<br /><br /></fieldset>	<br /><br />

<fieldset class="recuadro_inferior">
  Total a pagar: 
  $
  <input name="totalPagar" type="text" id="totalPagar" size="15" value="0" disabled="disabled" />
  <input name="totalCuotas" type="hidden" id="totalCuotas" value="0" />
  <input name="totalesCuotas" type="hidden" id="totalesCuotas" value="<?php echo $i;?>" />
  <input type="hidden" value="<?php echo $DNI;?>" id="DNI2" name="DNI2"/>
</fieldset>
</form>
<table width="60%" border="0" align="center" cellpadding="2" cellspacing="2" class="texto">
  <tr>
    <td>&nbsp;</td>
    <td><strong>Referencias</strong></td>
  </tr>
  <tr>
    <td width="10" class="vencida_azul">&nbsp;</td>
    <td>Cuota con el primer vencimiento vencido.</td>
  </tr>
  <tr>
    <td class="vencida_roja">&nbsp;</td>
    <td>Cuota con el segundo vencimiento vencido.</td>
  </tr>
  <tr>
    <td class="tiene_debito">&nbsp;</td>
    <td>La cuota est&aacute; agregada al d&eacute;bito autom&aacute;tico.</td>
  </tr>
</table>

<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron parientes relacionados.</span>
<?php
}

?>
