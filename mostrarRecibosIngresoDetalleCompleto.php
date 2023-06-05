<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script language="javascript">

$(document).ready(function(){

	$("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		tituloRecibo = $("#tituloRecibo").html();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle: tituloRecibo,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
		});
		
		$("#cargando").hide();
	 });//fin evento click//*/
 
 		
	$("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
	$("a[id^='btnEliminar55']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(11,100);
		DRe_ID = $("#DRe_ID" + i).val();
		alert(i);
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "eliminarDeudorRecibo", DRe_ID: DRe_ID},
				url: 'cargarOpciones.php',
				success: function(data){ 
					 if (data.substr(0,2)=="Er"){								
						jAlert(data, "ERROR")
					 }else{
					   $("#fila" + i).hide();
					   jAlert(data, 'Resultado de Eliminar');
					 }
				}
			});//fin ajax//*/ 
	
	 });//fin evento click//*/
	$("a[id^='btnEliminar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(11,100);
		DRe_ID = $("#DRe_ID" + i).val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {DRe_ID: DRe_ID},
			url: 'mostrarRecibosIngresoNotaCredito.php',
			success: function(data){ 
				 $("#principal").html(data);
			}
		});//fin ajax//*/ 
	
	 });//fin evento click//*/	
	 
	 $("a[id^='vImprimir']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,100);
		FacturaNumero = $("#DRe_ReciboNumero" + i).val();
    	window.open("imprimirFacturaCuota.php?FacturaNumero="+FacturaNumero, '_blank');		 
	
	 });//fin evento click//*/	
	
});//fin domready 
</script>
<?php
require_once("conexion.php");
//require_once("listas.php");
require_once("funciones_generales.php");
        $Deu_ID = $_POST['i'];
		
		$sql = "SELECT * FROM Deudor WHERE Deu_ID = $Deu_ID";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$Cuenta = $row['Deu_Nombre'];
			$Deu_RazonSocial = $row['Deu_RazonSocial'];
		}
		//global $gMes;
		?><p></p>
        <div id="tituloRecibo" align="center" class="titulo_noticia">Detalle de Ordenes de  Ingresos.<br />
Deudor: <?php echo $Cuenta;?></div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        
        $sql = "SELECT * FROM DeudorRecibo INNER JOIN Usuario ON (Usu_ID = DRe_Usu_ID) WHERE DRe_Deu_ID = $Deu_ID ORDER BY DRe_FechaRecibo";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;exit;

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display"  align="center">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Nº Orden</th>
              <th align="center" class="fila_titulo">Fecha Ingreso</th>
              <!-- <th align="center" class="fila_titulo">Tipo Recibo</th> -->
              <th align="center" class="fila_titulo">Nº Recibo</th>
              <th align="center" class="fila_titulo">Importe</th>
              <th align="center" class="fila_titulo">Detalle</th>
              <th align="center" class="fila_titulo">Info Carga</th>
              <th align="center" class="fila_titulo">Eliminar</th>              
            </tr>
          </thead>
          <tbody>         <?php	
		  $total = 0;	
            while ($row = mysqli_fetch_array($result)) {
	        
                $i++;
				$total += floatval($row[DRe_Importe]);	
				$color = "gainsboro";
				$baja = false;
				if ($row['DRe_Baja']==1) {
					$color = "red";	
					$baja = true;
					$notaCredito = $row['DRe_NotaCredito'];
				}				
				?>
            <tr style="background-color: <? echo $color;?>;" id="fila<?php echo $i;?>">                      
              <td align="center"><?php echo substr("00000000".$row[DRe_ID],-8); ?></td>
              <td align="center">
              <input type="hidden" id="DRe_ID<?php echo $i;?>" value="<?php echo $row[DRe_ID]; ?>" />  <?php echo cfecha($row[DRe_FechaRecibo]); ?>
              
              <input type="hidden" id="DRe_ReciboNumero<?php echo $i;?>" value="<?php if($baja) echo $notaCredito; else echo $row[DRe_ReciboNumero]; ?>" />  
              <?php echo cfecha($row[DRe_FechaRecibo]); ?>

          	  </td>              
              <td align="center"><?php echo $row[DRe_ReciboNumero]; ?></td>
              <td align="center">$<?php echo number_format(floatval($row['DRe_Importe']),2,",","."); ?></td>
              <td align="center"><?php 
			  
			  echo "$Deu_RazonSocial<br />$row[DRe_FormaPago]<br />$row[DRe_Detalle]";
			   
			  
			  ?></td>
              <td align="center"><?php echo "$row[Usu_Nombre]<br />".cfecha($row['DRe_Fecha'])." $row[DRe_Hora]"; ?></td>
              <td align="center">
              	<? if (!$baja){?>
              	<a href="#" id="btnEliminar<?php echo $i;?>" title="Generar Nota de Crédito por $<?php echo number_format(floatval($row[DRe_Importe]),2,",","."); ?>" ><img src="imagenes/bullet_delete.png" width="32" height="32" /></a> 
              <? }?>
              	<a href="#" id="vImprimir<?php echo $i;?>" title="<? if($baja) echo "Imprimir Nota de Crédito"; else echo "Imprimir Orden de Ingreso";?>" ><img src="imagenes/printer.png" width="32" height="32" /></a></td>
              
              </tr>
            
            <?php
                    }//fin while
                    ?>
          </tbody>
          <?php
            } else {
                ?>
          No existen datos cargados.
  <?php
            }
            ?>
  <tfoot>
    <tr>
      <th colspan="8" class="fila_titulo" align="right">
      Total: $<?php echo number_format($total,2,",",".");?></th>      
    </tr>
  </tfoot>      
      </table>     
  <fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Total: ($) $total";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>