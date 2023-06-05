<script language="javascript">

$(document).ready(function(){

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
					 $("#mostrar").html(data);
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
        list($mes, $Deu_ID) = split("-", $_POST['i']);
		
		$sql = "SELECT * FROM Deudor WHERE Deu_ID = $Deu_ID";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$Cuenta = $row['Deu_Nombre'];
		}
		global $gMes;
		?>
        <div align="center" class="titulo_noticia">Detalle de Ordenes de Ingresos del Mes <?php echo $gMes[$mes];?>.<br />
Deudor: <?php echo $Cuenta;?></div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        
        $sql = "SELECT * FROM DeudorRecibo INNER JOIN Usuario ON (Usu_ID = DRe_Usu_ID) WHERE MONTH(DRe_FechaRecibo)=$mes AND DRe_Deu_ID = $Deu_ID ORDER BY DRe_FechaRecibo";
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
              <th align="center" class="fila_titulo">Info Carga</th>
              <th align="center" class="fila_titulo">Acciones</th>              
            </tr>
          </thead>
          <tbody>         <?php		
			        $total = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$total += floatval($row[DRe_Importe]);						
						?>
            <tr style="background-color: gainsboro;" id="fila<?php echo $i;?>">                      
              <td align="center"><?php echo substr("00000000".$row[DRe_ID],-8); ?></td>
              <td align="center"><input type="hidden" id="DRe_ID<?php echo $i;?>" value="<?php echo $row[DRe_ID]; ?>" />  
                <input type="hidden" id="DRe_ReciboNumero<?php echo $i;?>" value="<?php echo $row[DRe_ReciboNumero]; ?>" />
                <?php echo cfecha($row[DRe_FechaRecibo]); ?></td>              
              <td align="center"><?php echo $row[DRe_ReciboNumero]; ?></td>
              <td align="center">$<?php echo number_format(floatval($row['DRe_Importe']),2,",","."); ?></td>
              <td align="center"><?php echo "$row[Usu_Nombre]<br />".cfecha($row['DRe_Fecha'])." $row[DRe_Hora]"; ?></td>
              <td align="center"><!-- <a href="#" id="btnEliminar<?php echo $i;?>" title="Nota de crédito por $<?php echo number_format(floatval($row[DRe_Importe]),2,",","."); ?>" ><img src="imagenes/bullet_delete.png" width="32" height="32" /></a> --><a href="#" id="vImprimir<?php echo $i;?>" title="Imprimir Orden de Ingreso" ><img src="imagenes/printer.png" width="32" height="32" /></a></td>
              
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
      <th colspan="7" class="fila_titulo" align="right">
      Total: $<?php echo number_format($total,2,",",".");?></th>      
    </tr>
  </tfoot>      
      </table>     
  