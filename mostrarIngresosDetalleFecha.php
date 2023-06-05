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

  $("a[id^='vImprimir']").click(function(evento){                       
    evento.preventDefault();
    var i = this.id.substr(9,100);
    FacturaNumero = $("#DRe_ReciboNumero" + i).val();
      window.open("imprimirFacturaCuota.php?FacturaNumero="+FacturaNumero, '_blank');    
  
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
	
});//fin domready 
</script>
<?php
require_once("conexion.php");
//require_once("listas.php");
require_once("funciones_generales.php");
       		
		?><p></p>
        <div align="center" class="titulo_noticia">Detalle de recibos<br /></div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        $fechaDesde = $_POST['fechaDesde'];
		    $fechaHasta = $_POST['fechaHasta'];
        $filtroOrdenar = $_POST['filtroOrdenar'];
        $sqlOrder="";
        if ($filtroOrdenar=="orden") $sqlOrder = "ORDER BY DRe_ID";
        if ($filtroOrdenar=="fecha") $sqlOrder = "ORDER BY DRe_FechaRecibo";
        if ($filtroOrdenar=="proveedor") $sqlOrder = "ORDER BY Deu_Nombre";
        $sql = "SELECT * FROM DeudorRecibo  INNER JOIN Deudor 
        ON (DRe_Deu_ID = Deu_ID) INNER JOIN Usuario ON (Usu_ID = DRe_Usu_ID) WHERE DRe_FechaRecibo >= '".cambiaf_a_mysql($fechaDesde)."' AND DRe_FechaRecibo<='".cambiaf_a_mysql($fechaHasta)."' $sqlOrder";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;//exit;

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display"  align="center">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Nº Orden</th>
              <th align="center" class="fila_titulo">Fecha Ingreso</th>
              <th align="center" class="fila_titulo">Deudor</th>
              <th align="center" class="fila_titulo">Detalle del ingreso</th>
              <!-- <th align="center" class="fila_titulo">Tipo Recibo</th> -->
              <th align="center" class="fila_titulo">Nº</th>
              <th align="center" class="fila_titulo">Importe</th>
              <th align="center" class="fila_titulo">Forma Pago</th>
              <th align="center" class="fila_titulo">Info Carga</th>
              <th align="center" class="fila_titulo">Acciones</th>              
            </tr>
          </thead>
          <tbody>         <?php		
		        $total = 0;
            while ($row = mysqli_fetch_array($result)) {
              $i++;
						  $total += floatval($row['DRe_Importe']);						
						?>
            <tr style="background-color: gainsboro;" id="fila<?php echo $i;?>">                      
              <td align="center"><?php echo substr("00000000".$row[DRe_ID],-8); ?></td>
              <td align="center"><input type="hidden" id="DRe_ID<?php echo $i;?>" value="<?php echo $row[DRe_ID]; ?>" />  
                <input type="hidden" id="DRe_ReciboNumero<?php echo $i;?>" value="<?php echo $row[DRe_ReciboNumero]; ?>" />
                <?php echo cfecha($row[DRe_FechaRecibo]); ?></td>
              <td align="left"><?php echo $row['Deu_Nombre']; ?></td>
              <td align="left"><?php echo $row['DRe_Detalle']; ?></td>             
              <td align="center"><?php echo $row['DRe_ReciboNumero']; ?></td>
              <td align="center">$<?php echo number_format(floatval($row['DRe_Importe']),2,",","."); ?></td>
              <td align="center"><?php echo $row['DRe_FormaPago'];
              if ($row['DRe_FormaPago']=="cheque") echo "$row[DRe_ChequeNumero], $row[DRe_ChequeBanco], ".cfecha($row[DRe_ChequeFecha]);
               ?></td>
              <td align="center"><?php echo "$row[Usu_Nombre] ".cfecha($row['DRe_Fecha'])." $row[DRe_Hora]"; ?></td>
              <td align="center"><a href="#" id="btnEliminar<?php echo $i;?>" title="Eliminar $<?php echo number_format(floatval($row[DRe_Importe]),2,",","."); ?>" ><img src="imagenes/bullet_delete.png" width="32" height="32" /></a><a href="#" id="vImprimir<?php echo $i;?>" title="Imprimir Orden de Ingreso" ><img src="imagenes/printer.png" width="32" height="32" /></a></td>
              
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
      <th colspan="10" class="fila_titulo" align="right">
      Total: $<?php echo number_format($total,2,",",".");?> </th>      
    </tr>
  </tfoot>      
      </table>  
      <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>   
  <form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>