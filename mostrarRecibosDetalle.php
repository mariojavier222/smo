<script language="javascript">

$(document).ready(function(){

	$("a[id^='btnEliminar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(11,100);
		Rec_ID = $("#Rec_ID" + i).val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "eliminarEgreso_Recibo", Rec_ID: Rec_ID},
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
  $("a[id^='vImprimir']").click(function(evento){                       
    evento.preventDefault();
    var i = this.id.substr(9,100);
    Rec_ID = $("#Rec_ID" + i).val();
    window.open("imprimirReciboEgreso.php?Rec_ID="+Rec_ID, '_blank');    
  
   });//fin evento click//*/
	
});//fin domready 
</script>
<?php
require_once("conexion.php");
//require_once("listas.php");
require_once("funciones_generales.php");
        list($mes, $Cue_ID, $Anio) = explode("-", $_POST['i']);
		
		$sql = "SELECT * FROM Egreso_Cuenta WHERE Cue_ID = $Cue_ID";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$Cuenta = $row['Cue_Nombre'];
		}
		global $gMes;
		?>
        <div align="center" class="titulo_noticia">Detalle de Ordenes de Pago del Mes <?php echo $gMes[$mes];?>.<br />
Proveedor: <?php echo $Cuenta;?></div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        
        $sql = "SELECT * FROM Egreso_Recibo INNER JOIN Usuario ON (Usu_ID = Rec_Usu_ID) WHERE YEAR(Rec_FechaCompra)=$Anio AND MONTH(Rec_FechaCompra)=$mes AND Rec_Cue_ID = $Cue_ID ORDER BY Rec_FechaCompra";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;exit;

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display"  align="center">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Nº Orden</th>
              <th align="center" class="fila_titulo">Fecha Pago</th>
              <th align="center" class="fila_titulo">Tipo Recibo</th>
              <th align="center" class="fila_titulo">Número</th>
              <th align="center" class="fila_titulo">Importe</th>
              <th align="center" class="fila_titulo">Info Carga</th>
              <th align="center" class="fila_titulo">Acciones</th>              
            </tr>
          </thead>
          <tbody>         <?php		
			        $total = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$total += floatval($row[Rec_Importe]);						
						?>
            <tr style="background-color: gainsboro;" id="fila<?php echo $i;?>">                      
              <td align="center"><?php echo substr("00000000".$row[Rec_ID],-8); ?></td>
              <td align="center"><input type="hidden" id="Rec_ID<?php echo $i;?>" value="<?php echo $row[Rec_ID]; ?>" />  <?php echo cfecha($row[Rec_FechaCompra]); ?></td>
              <td align="center"><?php echo $row[Rec_TipoRecibo]; ?></td>
              <td align="center"><?php echo $row[Rec_Numero]; ?></td>
              <td align="center">$<?php echo number_format(floatval($row['Rec_Importe']),2,",","."); ?></td>
              <td align="center"><?php echo "$row[Usu_Nombre]<br />".cfecha($row['Rec_Fecha'])." $row[Rec_Hora]"; ?></td>
              <td align="center"><a href="#" id="btnEliminar<?php echo $i;?>" title="Eliminar $<?php echo number_format(floatval($row[Rec_Importe]),2,",","."); ?>" ><img src="imagenes/bullet_delete.png" width="32" height="32" /></a><a href="#" id="vImprimir<?php echo $i;?>" title="Imprimir Orden de Pago" ><img src="imagenes/printer.png" width="32" height="32" /></a></td>
              
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
  