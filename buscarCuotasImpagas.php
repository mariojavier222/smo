<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("listas.php");

?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	.cuota_online{
		background-color:#666;
		color: #FFF;
	} 
</style>
<?php

//sleep(3);
$PerID = $_POST['PerID'];
$tipo = $_POST['Tipo'];
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);

if (!empty($PerID)){
	$fechaHoy = date("d-m-Y");
	$Deuda = Obtener_Deuda_Sistema($PerID, $Recargos);
	
	if ($Deuda>0){
		gObtenerApellidoNombrePersona($PerID, $usuario_nombre, $usuario_apellido, true);
	?>
<link href="css/general.css" rel="stylesheet" type="text/css" />

		<br /><div class="borde_alerta" align="center">
		  <p ><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Al día de la fecha <?php echo $fechaHoy;?> el alumno <strong><?php echo "$usuario_nombre $usuario_apellido";?></strong> tiene una deuda vencida de $<?php echo $Deuda + $Recargos;?>.</p></div><br />
		  <!--<table width="100%" border="0" cellspacing="1" cellpadding="1">
		    <tr>
		      <th scope="col"><button id="botVerTodo">Ver todas las cuotas</button></th>
	        </tr>
	    </table>-->
        <script language="javascript">
$(document).ready(function(){
	
	$("#botVerTodo").click(function(evento){
		evento.preventDefault();
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {UsuID: <?php echo $UsuID;?>, PerID: <?php echo $PerID;?>,verTodo: 'mostrar'},			
			url: 'buscarCuotasImpagas.php',
			success: function(data){ 
			 $("#mostrar").html(data);
					}
		});//fin ajax///
		
	 });//fin evento click//*/
	});
	</script>
<?php
	}else{
	
	}//*/
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//Obtener_LectivoActual($LecID, $LecNombre);
	$LecID = gLectivoActual($LecNombre);
	$LecIDUno = $LecID + 1;
	/*if (!isset($_POST[verTodo])){
		$sqlOpcional = " AND(Cuo_Lec_ID = $LecID OR Cuo_Lec_ID = $LecIDUno)";
	}*/
	
	if ($tipo == "cuotas"){
		$sqlOpcional = " AND CTi_Factura = 1 AND Cuo_Pagado = 0 AND Cuo_Anulado = 0 AND Cuo_Cancelado = 0";
	}
	if ($tipo == "recibos"){
		$sqlOpcional = " AND CTi_Recibo = 1 AND Cuo_Pagado = 0 AND Cuo_Anulado = 0 AND Cuo_Cancelado = 0";
	}
	if ($tipo == "pagos"){
		$sqlOpcional = " AND (Cuo_Pagado = 1 OR Cuo_Anulado = 1 OR Cuo_Cancelado = 1)";
	}
	
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
WHERE (Cuo_Per_ID = $PerID $sqlOpcional) ORDER BY Cuo_Pagado DESC, Cuo_Anulado DESC, Cuo_CTi_ID, Cuo_1er_Vencimiento, Cuo_Alt_ID  ASC, Cuo_Anio, Cuo_Mes;";
}else{
	exit;}
//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	?>
	
<script language="javascript">
$(document).ready(function(){

var vTotal=0;

$(".botones").button();

	$("input[id^='NuevoC']").click(function(evento){
		//evento.preventDefault();
		i = this.id.substr(6,10);
		
		vImporte = parseInt($("#cuotas" + i).val());
		vImporteTR= parseInt($("#totCuoRec" + i).val());
		//alert (vImporte);
		//alert(vImporte);
		//vTotal = parseInt($("#totalPagar").val());
		
		vTotalCuotas = parseInt($("#totalCuotas").val());
		if (this.checked){
			vTotal += parseInt(vImporteTR);
			vTotalCuotas += 1;
			//acumulo los importes a pagar seleccionados
		}else{
			vTotal -= parseInt(vImporteTR);
			vTotalCuotas -= 1;
			//acumulo los importes a pagar seleccionados
		}
		$("#totalPagar").val(vTotal);
		$("#totalCuotas").val(vTotalCuotas);
		
		vTotal=parseInt(vTotal);
		$("#tot-a-pagar").val(vTotal); 
	 
	 });//fin evento click//*/

	
	$("a[id^='detalles']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(8,10);
		//alert(i);
		vCuota = $("#NuevoC" + i).val();
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
					 mostrarAlerta2(data,"DETALLE DE LA CUOTA",400,400);
					//mostrarAlerta2(data,"Detalles de la Cuota");}
					}
		});//fin ajax///
		$("#cargando").hide();
	 });//fin evento click//*/
	 ///////////////////////////////////////////////////////////////////ACCIONES/////////////////////////////////////////////////////////////////////
	 
 
 		

	//marcar y desmarcar todo
	$("#marcar").click(function(evento){
		evento.preventDefault();
		vTotal = 0;
		j=0;
		$( ":checkbox:not(:disabled)").attr('checked', 'checked');
		$("#totalCuotas").val($("#totalesCuotas").val());
		$("input[id^='NuevoC']").each(function(){
			i = this.id.substr(6,10);					
			vImporte = parseInt($("#cuotas" + i).val());
			vImporteTR = parseInt($("#totCuoRec" + i).val());
			vTotal += parseInt(vImporteTR);		
			//alert(vImporteTR);
			j++;
		});
		//alert (j);
		$("#totalPagar").val(vTotal);

		vTotal=parseInt(vTotal);
		$("#tot-a-pagar").val(vTotal); 

	});

	$("#desmarcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").removeAttr('checked');
		//$( ":checkbox").attr('checked', '');
		$("#totalPagar").val(0);
		$("#totalCuotas").val(0);
		$("#tot-a-pagar").val(0); 
	  vTotal = 0;
	});
	
	
	
$("#anularCuotas").click(function(evento){
		evento.preventDefault();
		//alert("asdasd");
		
		if(!($(".activo").is(':checked'))) {  
			jAlert("Elija una cuota para Anular", "ANULAR CUOTAS");
			return false; 
		}
		var totalCola=0;

		jConfirm('¿Está seguro que desea Anular estas Cuotas?', 'Confirmar la Anulacion', function(r){
			if (r){

				jPrompt('Escriba el motivo:', '', 'Motivo de la anulación', function(m) {
				    if( m ){
				    	$( "input:checked").each(function(index, element) {
				   
							$.ajax({
								type: "POST",
								cache: false,
								async: false,
								data: {opcion: 'anularCuotaPersona', Cuota: element.value, Motivo: m},
								url: 'cargarOpciones.php',
								success: function(data){ 
								//$("#mostrar222").html(data);
										data=data.trim();
										//alert(data);
										if (data==1)	totalCola = totalCola + 1;
											//jAlert("Cuota Anulada Correctamente", "ANULAR CUOTAS");
											//$("#barraCuotas").click();
								}
									
							});//fin ajax///
							
						});//fin each
						
				    }else{
				    	jAlert("Debe escribir un motivo para Anular una Cuota","Error");
				    } //alert('You entered ' + r);
				});
			
			}// jconfirm
			
		});//fin del confirm//*/

			
	});
		
		
	$("#buttonbeneficio").click(function(evento){
		evento.preventDefault();
		//alert("asdasd");
		totalCola = 0;
		Ben_ID=$("#Ben_ID").val();
		//alert(Ben_ID);
		/*if(Ben_ID==1)
		{
			jAlert("Elija un Beneficio para aplicar a las cuotas", "Beneficio");
			return false;
		}*/
		
		if(!($(".activo").is(':checked'))) {  
            jAlert("Elija una cuota para aplicarle el Beneficio", "Beneficio");
			return false; 
        }
		//return false
		
		$( "input:checked").each(function(index, element) {
			//alert(element.value);
			//alert("asdasd");
            
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'agregarBeneficioCuota', Cuota: element.value,Ben_ID:Ben_ID},
				url: 'cargarOpciones.php',
				success: function(data){ 
				$("#mostrar222").html(data);
				if (data)
					totalCola = totalCola + 1;
					//jAlert("Beneficio Cargado Correctamente", "Beneficio Cuotas");
					$("#barraRecibos").click();
				}
						
			});//fin ajax///
       		
		});//fin each
		$("#barraCuotas").click();
		
	});//fin click
	
	
	
	$("#agregarPagar").click(function(evento){
		evento.preventDefault();
		totalCola = 0;
								$.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarOpciones.php',
                data: {opcion:"vaciarColaPagoUsuario22"},
            });//fin ajax
		$( "input:checked").each(function(index, element) {
            
			$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'agregarCuotaColaPago', Cuota: element.value},
					url: 'cargarOpciones.php',
					success: function(data){ 
							if (data)
								totalCola = totalCola + 1;
								
					}
					
			});//fin ajax///
       		
		});//fin each
		
		if (totalCola>0){
			$.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarCuotasColaPagoRecargo.php',
                data: {Tipo: "<?php echo $tipo;?>"},
                success: function (data){
                   mostrarAlerta4(data,"PAGOS DE CUOTAS",1000,750);   
                }
            });//fin ajax
			//jAlert("Se agregaron " + totalCola + " cuotas para pagar después", "Cola de Pago");

			}
		else
			jAlert("No se pudieron agregar cuotas", "Cola de Pago");
		
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
				'Guardar': function() {
					datos=$("#datos").val();
					//alert(datos)
					Cuo_Importe=$("#Cuo_Importe").val();
					FechaVencimiento=$("#FechaVencimiento").val();
					Motivo=$("#MotivoEditar").val();
					$.ajax({
						type: "POST",
						cache: false,
						async: false,
						data: {opcion: 'GuardarEditarCambio', datos: datos, Cuo_Importe: Cuo_Importe, FechaVencimiento: FechaVencimiento, Motivo: Motivo},
						url: 'cargarOpciones.php',
						success: function(data){ 
							jAlert(data, "EDITAR CUOTAS");
								 $("#barraCuotas").click();
								}				    		
					});//fin ajax///
					$(this).dialog('close');
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion

function mostrarAlerta4(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {				
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion

function mostrarAlerta2(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: true, modal: true, 
			buttons: {
					'Aceptar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion

function EditarCuotas(datos2){
	$.ajax({
            type: "POST",
            cache: false,
            async: false,			
            url: 'cargarOpciones.php',
            data: {datos2:datos2,opcion:"EditarCuotas"},
            success: function (data){
               mostrarAlerta3(data,"EDITAR CUOTAS",400,200);   
            }
        });//fin ajax
}
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<form action="" id="formTodas" target="_blank" method="post"> 
  <table width="100%" border="0" cellpadding="2" cellspacing="2">
<tr>
  <tr>
    <td width="10">&nbsp;</td>
    <td><strong>Referencias</strong></td>
  </tr>
    <td class="vencida_roja">&nbsp;</td>
    <td>Cuota  Vencida.</td>
 
    <td class="cuota_pagada">&nbsp;</td>
    <td>Cuota  Pagada.</td>
    
    <td class="cuota_cancelada">&nbsp;</td>
    <td>Cuota  Cancelada.</td>
 
    <td class="cuota_beneficio">&nbsp;</td>
    <td>Cuota  Beneficio.</td>
 
    <td class="cuota_anulada">&nbsp;</td>
    <td>Cuota  Anulada.</td>

    <td class="cuota_online">&nbsp;</td>
    <td>Cobrada online.</td>
  </tr>
</table>
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
  
<div id="listado">	
  
  
  <table width="100%" border="0">
    <tr>
      <td class="fila_titulo">&nbsp;</td>
      <td align="center" class="fila_titulo">Tipo de cuota </td>
      <td align="center" class="fila_titulo">Lectivo</td>
      <td align="center" class="fila_titulo">Vencimiento</td>
      <td align="center" class="fila_titulo">Importe</td>
      <td align="center" class="fila_titulo">Recargo</td>
      <td align="center" class="fila_titulo">Total</td>
      <td align="center" style="font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: bold;
    background-color: #FFCC00;" class="borrarTD"><div align="center">Acciones</div></td>
    </tr>
	<?php $i=0;
	//$juicio = tieneJuicio($DNI);
	$CTiActual = 0;
	$AltActual = 0;
	$TipoCuota = "";	
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		$motivo = "";
		$saga_migrado=false;
		if ($row['Id_Deuda']>0) $saga_migrado=true;
		if (!empty($row['Cuo_Motivo'])) $motivo = "Motivo: $row[Cuo_Motivo]";
		$info = "Registro modificado por $row[Usu_Nombre] ".cfecha($row[Cuo_Fecha])." $row[Cuo_Hora]. $motivo";
		
		//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
		$datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
		//Calculamos el importe que deber�a pagar al d�a de hoy
		$importe = $row['Cuo_Importe'];
		//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
		$importeOriginal = $importe;
		recalcularImporteCuota($datosCuota, $importe);
		if ($row['Cuo_Pagado']==1) $importe = buscarPagosTotales($datosCuota);
		$recargo2 = obtenerRecargoCuota($row['Cuo_Per_ID'], $datosCuota);
		//if ($recargo2>0) $importe -= $recargo2;
		//$recargo2 = $importe;
		$fechaCuota = cfecha($row['Cuo_1er_Vencimiento']);
		$clase = "vencida_roja";
		$fechaHoy = date("d-m-Y");
		$ya_vencida=0;
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
		
		$texto_saga='';
		if ($saga_migrado) {
			$importe = intval($importeOriginal);
			$texto_saga = '<br><u>Concepto SAGA ('.$row['Id_Deuda'].')</u>: '.$row['Cuo_Concepto'];
		}
		
		$debito = false;
		$tarjeta=  "";
		//$debito = tieneDebito($datosCuota, $tarjeta);
		if ($ya_vencida==0){
			if ($debito) $clase = "tiene_debito";
			//$clase = "vencida_roja";
		}
		//Actualizamos el importe si vemos que el alumno se le venció la cuota y no pagó a tiempo		
		/*if ($row['Cuo_Pagado']==0 && $row['Cuo_Cancelado']==0 && $row['Cuo_Anulado']==0 && $row['Cuo_Ben_ID']!=1 && $ya_vencida==1){
			//cambiamos el valor del importe
			
			actualizarImporteCuotaBeneficio($datosCuota, $importe);
			$importe = intval($importe);			
			$recargo2 = obtenerRecargoCuota($row['Cuo_Per_ID'], $datosCuota);
		}*/
		if ($row['Cuo_Pagado']==1) $clase = "cuota_pagada";
		if ($row['Cuo_Cancelado']==1) $clase = "cuota_cancelada";
		if ($row['Cuo_Anulado']==1) $clase = "cuota_anulada";
		if(($clase!='vencida_roja')&&($clase!='cuota_pagada'))
		{
			if ($row['Cuo_Ben_ID']!=1) $clase = "cuota_beneficio";
			if ($row['Cuo_Anulado']==1) $clase = "cuota_anulada";
		}
		if ($row['Cuo_MarcadaOnline']==1 && $row['Cuo_Procesada']==0) $clase = "cuota_online";
		
		if ($row['Cuo_Pagado']==1 || $row['Cuo_Cancelado']==1 |$row['Cuo_Anulado']==1) {
			$noMostrar = true;
			$desabilitada = "disabled=disabled";
		}else{
			$noMostrar = false;
			$desabilitada = "";
		}
		if ($row['Cuo_MarcadaOnline']==1 && $row['Cuo_Procesada']==0){
			$desabilitada = "disabled=disabled";
		}
			
		$detalle_alternativa="";
  		$alternativas = tieneAlternativas($datosCuota);
		if ($alternativas>1) $detalle_alternativa = " (".$row['Alt_Nombre'].")";
		$texto_beneficio='';
		if($row['Cuo_Ben_ID']!=1) $texto_beneficio=" ".$row['Ben_Nombre'];
		
	 if ($TipoCuota !=  $row['CTi_Nombre']){
	 	
		$TipoCuota =  $row['CTi_Nombre'];
	
	  ?>
	<tr  height="40px" title="<?php echo $info;?>">
	  <td colspan="6"><strong class="corregirLista"><i><?php echo $TipoCuota;?></i></strong></td>
    </tr>
    <?php
	}//fin if tipo de cuota
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px" title="<?php echo $info;?>">
      <td>
      	<input type="checkbox" id="NuevoC<?php echo $i;?>" name="NuevoC<?php echo $i;?>" value="<?php echo $datosCuota;?>" <?php echo $desabilitada;?> class="activo"><input type="hidden" id="cuotas<?php echo $i;?>" value="<?php echo $importe;?>">

      </td>      
      <td><?php echo $row['CTi_Nombre'];?><?php echo " ".$detalle_alternativa;?> (<?php echo buscarMes($row['Cuo_Mes']);?>/<?php echo $row['Cuo_Anio'];?>)<?php echo $texto_beneficio.$texto_saga; echo " - ".$row['Niv_Nombre']?> </td>
      <td align="center"><?php echo $row['Lec_Nombre'];?></td>
      <td align="center"><?php echo $fechaCuota;//cfecha($row[ChC_1er_Vencimiento]);?></td>
      <td align="center"><?php 
      if($row['Cuo_Ben_ID']!=1){
		  $Ben_Nombre=$row['Ben_Nombre'];		  
		  echo "$".$importe;
		  
	  }
	  else
	  {
	  echo "$".$importe;
	  }//$row[ChC_1er_Importe];?></td>
	  <td align="center">
	  <?php 
	  	if ($tipo == "pagos"){
	  		echo "---";
	  	}else{
	  		if ($recargo2==0) echo "---"; else echo "$".intval($recargo2);
	  	}
	  	

	  ?></td>
	  <td align="center">$ <?php if ($tipo == "pagos") echo $importe; else echo $importe + $recargo2;?>
	  <input type="hidden" name="totCuoRec<?php echo $i;?>" id="totCuoRec<?php echo $i;?>" value="<?php echo $importe + $recargo2; ?>" />	
	  </td>
      <td class="borrarTD" align="center"><?php //echo $totales;
	  if (!$juicio){
	  buscarPagosParcialesEze($datosCuota);
	  if (!$noMostrar){
	  ?> 
      <a style="cursor:pointer" id="detalles<?php echo $i;?>"> <img src="imagenes/magnifier_zoom_in.png" width="32" height="32" alt="Detalles de la cuota" title="Detalles de la cuota" border="0"/></a>
    <?php  
     if ($row['Cuo_MarcadaOnline']==0){ 
    ?>	
      <a style="cursor:pointer" onclick="EditarCuotas('<?php echo $datosCuota ?>');" id="EditarCuotas<?php echo $i;?>"><img src="imagenes/application_form_edit.png" title="EDITAR CUOTAS" width="30" height="30" border="0"/></a>
	  <?php
			}
	  }//fin NoMostrar
	  	
	  
	  }//fin juicio
	  ?>	  </td>
    </tr>
		  <?php		  
	}//fin del while
	?>  
</table>
</fieldset>

<input type="hidden" name="DNI" id="DNI" value="<?php echo $DNI ?>" />
<fieldset class="recuadro_inferior" style="height:32px"><div align="left" id="mostrarDatosOpcionesBorrar">
<p><img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "$total cuotas";?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<strong>Importe total a pagar: <input type="text" name="tot-a-pagar" id="tot-a-pagar" value="0" size="10" maxlength="12" readonly style="background-color: #ffeb3bb8; border: 2px solid #3f51b5; padding: 2px; font-weight: bold;
"></strong>

</p>


<input name="totalCuotas" type="hidden" id="totalCuotas" value="0" />
  <input name="totalesCuotas" type="hidden" id="totalesCuotas" value="<?php echo $total;?>" />
  <input type="hidden" value="<?php echo $PerID;?>" id="PerID" name="PerID"/>


</div></fieldset>
<?php
if ($tipo != "pagos"){
?>
<fieldset class="recuadro_inferior" style="height:45px">

<button id="agregarPagar"><img src="imagenes/pagar.png" alt="Agregar las cuotas seleccionadas para pagar" title="PAGAR CUOTAS" width="32" border="0" align="absmiddle" /> Pagar Cuotas</button>
<?php //if ($UsuID==2 || $UsuID==3 || $UsuID==11 || $UsuID==10){
?>
<strong style="font-size:14px; padding-left:20px;">Beneficios Cuotas: </strong>
  <?php
ListarBeneficios('Ben_ID');
?><button id="buttonbeneficio"><img src="botones/editar_guardar_peq.gif" width="16" height="16" style="vertical-align:middle" /> Aplicar Beneficios</button>&nbsp;&nbsp;&nbsp;
<button id="anularCuotas"><img src="imagenes/Delete.png" title="ANULAR CUOTAS" width="32" border="0" align="absmiddle" /> Anular Cuotas</button>
<?php
//}//fin if usuario comun
?>
</fieldset>
<?php
}//fin if pagos
?>
<div id="mostrar22"></div>

</div>

  <?php
}else{
?>
  
  <div class="borde_alerta">
  <span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron cuotas para la consulta realizada.</span>
<?php
}
?>
