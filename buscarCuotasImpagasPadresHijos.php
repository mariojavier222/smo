<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
$DNI = $_POST['DNI'];
$esUniversitario = $_POST['esUniversitario'];
$esUniversitario = 1;

$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$usuario_nombre = $row[Per_Nombre];
	$usuario_apellido = $row[Per_Apellido];
}
//echo "DNI: $DNI";
if (!empty($DNI)){
	$fechaHoy = date("d-m-Y");
	$Deuda = Obtener_Deuda_siucc($DNI);
	if ($Deuda>0){		
	?>
		<br /><div class="borde_alerta" align="center">
		  <p class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Al d�a de la fecha <?php echo $fechaHoy;?>la persona <strong><?php echo "$usuario_nombre $usuario_apellido";?></strong> tiene una deuda vencida de $<?php echo $Deuda;?>.</p></div><br />
	<?php
	}else{
	
	}//*/

	$sql = "SELECT Persona.Per_DNI, FTi_ID, Persona.Per_Apellido, Persona.Per_Nombre FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID)
    INNER JOIN Persona AS P
        ON (Familia.Fam_Vin_Per_ID = P.Per_ID)
WHERE Familia.Fam_FTi_ID =1 AND P.Per_DNI = $DNI ";
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
		vDNI = $("#DNI" + i).val();
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
	 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		if ($("#totalCuotas").val()==0){
			mostrarAlerta("Antes de imprimir debe seleccionar al menos una cuota","Atenci�n");
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
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<br /><div class="borde_alerta" align="center">
  <p class="texto">Se ha seleccionado a la persona  <strong><?php echo "$usuario_nombre $usuario_apellido";?></strong> (DNI: <?php echo $DNI;?>) para ver el detalle de las cuotas de sus familiares relacionados.</p></div><br />
 <form action="imprimir_cuota_siucc_varias_PadresHijos.php" id="formTodas" target="_blank" method="post"> 
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b�squeda</legend>
  <input name="esUniversitario" type="hidden" id="esUniversitario" value="<?php echo $esUniversitario;?>" />
<div id="listado">	
  
  
  <table width="100%" border="0">
    <tr>
      <td class="fila_titulo">&nbsp;</td>
      <td class="fila_titulo"><div align="left">Tipo de cuota </div></td>
      <td align="center" class="fila_titulo">Mes </td>
      <td align="center" class="fila_titulo">A&ntilde;o</td>
      <td align="center" class="fila_titulo">Vencimiento</td>
      <td align="center" class="fila_titulo">Importe</td>
      <td class="fila_titulo"><div align="left">Acciones</div></td>
    </tr>
	<?php $i=0;
	
	while ($row_prim = mysqli_fetch_array($result_prim)){
		$FTiID = gbuscarFTiRelaciona($row_prim[FTi_ID], $parentesco);
		gbuscarFTiRelaciona($FTiID, $parentesco);
		$juicio = tieneJuicio($row_prim[Per_DNI]);
		?>
      	<tr bgcolor="#FF9900" height="40px">
	  	<td colspan="7" bgcolor="#ABFF93"><?php echo "$parentesco: <strong>$row_prim[Per_Apellido], $row_prim[Per_Nombre]</strong> (DNI: $row_prim[Per_DNI])";?></td>
	  	</tr>

        <?php
		$sql = "SELECT * FROM Chequera_Serie     INNER JOIN Chequera_Cuota          ON (Chequera_Serie.ChS_Fac_ID = Chequera_Cuota.ChC_Fac_ID) AND (Chequera_Serie.ChS_TCh_ID = Chequera_Cuota.ChC_TCh_ID) AND (Chequera_Serie.ChS_ID = Chequera_Cuota.ChC_ChS_ID)  
	INNER JOIN siucc.Chequera_Alternativa 
        ON (Chequera_Cuota.ChC_Fac_ID = Chequera_Alternativa.ChA_Fac_ID) AND (Chequera_Cuota.ChC_TCh_ID = Chequera_Alternativa.ChA_TCh_ID) AND (Chequera_Cuota.ChC_ChS_ID = Chequera_Alternativa.ChA_ChS_ID) AND (Chequera_Cuota.ChC_Pro_ID = Chequera_Alternativa.ChA_Pro_ID) AND (Chequera_Cuota.ChC_Alt_ID = Chequera_Alternativa.ChA_Alt_ID)
	INNER JOIN Producto          ON (Chequera_Cuota.ChC_Pro_ID = Producto.Pro_ID) 		WHERE (Chequera_Serie.ChS_Per_ID = $row_prim[Per_DNI] AND Chequera_Cuota.ChC_Pagado = 0     AND Chequera_Cuota.ChC_Baja = 0     AND Chequera_Cuota.ChC_Cancelado = 0) 	ORDER BY Chequera_Cuota.ChC_1er_Vencimiento ASC;";
	  $result = consulta_mysql($sql);
	  if (mysqli_num_rows($result)==0){
		  ?>
          <tr bgcolor="#FF9900" height="40px">
	  	<td colspan="7" bgcolor="#FFFF99">No existen cuotas generadas</td>
	  	</tr>
          <?php
	  }else{//*/
		  while ($row = mysqli_fetch_array($result)){		
			$i++;
			//Calculamos el importe que deber�a pagar al d�a de hoy
			//Por defecto hacemos que pague el tercer importe
			$importe = $row[ChC_3er_Importe];
			$fechaCuota = cfecha($row[ChC_3er_Vencimiento]);
			$clase = "vencida_roja";
			$vencida1 = true;
			$vencida2 = true;
			$fechaHoy = date("d-m-Y");
			$ya_vencida=1;
			//Controlamos el segundo vencimiento - 
			$fecha2Vencimiento = cfecha($row[ChC_2do_Vencimiento]);
			$fecha = restarFecha($fechaHoy,$fecha2Vencimiento);
			if ( $fecha >= 0 ){
				$importe = $row[ChC_2do_Importe];
				$vencida2 = false;
				$fechaCuota = $fecha2Vencimiento;
				$clase = "vencida_azul";
				$ya_vencida=1;
			}
			//Controlamos el primer vencimiento - 
			$fecha1Vencimiento = cfecha($row[ChC_1er_Vencimiento]);
			$fecha = restarFecha($fechaHoy,$fecha1Vencimiento);
			if ( $fecha >= 0 ){
				$importe = $row[ChC_1er_Importe];
				$vencida1 = false;
				$vencida2 = false;
				$fechaCuota = $fecha1Vencimiento;
				if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
				//$clase = "vencida_no";			
				$ya_vencida=0;
			}
			$importe = intval($importe);
			
			//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
			$datosCuota = $row[ChC_Fac_ID].";".$row[ChC_TCh_ID].";".$row[ChC_ChS_ID].";".$row[ChC_Alt_ID].";".$row[ChC_Pro_ID].";".$row[ChC_Cuo_ID].";$row_prim[Per_DNI];*".$importe;
			$debito = false;
			$tarjeta=  "";
			$debito = tieneDebito($datosCuota, $tarjeta);
			if ($ya_vencida==0){
				if ($debito)
					$clase = "tiene_debito";
				}
			$detalle_alternativa="";
			$alternativas = tieneAlternativas($row[ChC_Fac_ID],$row[ChC_TCh_ID],$row[ChC_ChS_ID],$row[ChC_Alt_ID],$row[ChC_Pro_ID],$row[ChC_Cuo_ID]);
			if ($row[ChC_Pro_ID]==2)
				if ($alternativas>1)
					if ($row[ChC_Alt_ID]==1)
						$detalle_alternativa = "<br />".$row[ChA_Titulo]."";
						else $detalle_alternativa = "<br />".$row[ChA_Titulo]."";//"(Financiado)";
			//$totales = buscarEntidadEducativaTotal($row[Niv_ID]);
			
		?>
		<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
		  <td><input type="checkbox" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $datosCuota;?>"><input type="hidden" id="cuotas<?php echo $i;?>" value="<?php echo $importe;?>"></td>
		  <td><strong><?php echo $row[Pro_Nombre];?></strong><?php echo " ".$detalle_alternativa;?></td>
		  
		  <td><?php echo buscarMes($row[ChC_Mes]);?></td>
		  <td><?php echo $row[ChC_Anio];?></td>
		  <td><?php echo $fechaCuota;//cfecha($row[ChC_1er_Vencimiento]);?></td>
		  <td>$ <?php echo $importe;//$row[ChC_1er_Importe];?></td>
		  <td><?php //echo $totales;
		  if (!$juicio){
		  ?> <a href="#" id="imprimir<?php echo $i;?>" target="_blank"><img src="imagenes/printer_add.png" width="32" height="32" border="0" /></a><a href="#" id="detalles<?php echo $i;?>"> <img src="imagenes/magnifier_zoom_in.png" width="32" height="32" alt="Detalles de la cuota" title="Detalles de la cuota" border="0"/></a>
			
		  <?php
		  }
		  ?>	  <input type="hidden" id="DNI<?php echo $i;?>" name="DNI<?php echo $i;?>" value="<?php echo $row_prim[Per_DNI];?>" /></td>
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
<table width="60%" border="0" align="center" cellpadding="2" cellspacing="2">
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
