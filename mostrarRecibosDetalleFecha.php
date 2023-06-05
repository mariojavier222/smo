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
        if ($filtroOrdenar=="orden") $sqlOrder = "ORDER BY Rec_ID";
        if ($filtroOrdenar=="fecha") $sqlOrder = "ORDER BY Rec_FechaCompra";
        if ($filtroOrdenar=="proveedor") $sqlOrder = "ORDER BY Cue_Nombre";
        $sql = "SELECT * FROM Egreso_Recibo  INNER JOIN Egreso_Cuenta 
        ON (Rec_Cue_ID = Cue_ID) INNER JOIN Usuario ON (Usu_ID = Rec_Usu_ID) WHERE Rec_FechaCompra >= '".cambiaf_a_mysql($fechaDesde)."' AND Rec_FechaCompra<='".cambiaf_a_mysql($fechaHasta)."' $sqlOrder";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;//exit;

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display"  align="center">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Nº Orden</th>
              <th align="center" class="fila_titulo">Fecha Pago</th>
              <th align="center" class="fila_titulo">Proveedor</th>
              <th align="center" class="fila_titulo">Detalle del pago</th>
              <th align="center" class="fila_titulo">Tipo Recibo</th>
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
						  $total += floatval($row['Rec_Importe']);						
						?>
            <tr style="background-color: gainsboro;" id="fila<?php echo $i;?>">                      
              <td align="center"><?php echo substr("00000000".$row[Rec_ID],-8); ?></td>
              <td align="center"><input type="hidden" id="Rec_ID<?php echo $i;?>" value="<?php echo $row[Rec_ID]; ?>" />  <?php echo cfecha($row[Rec_FechaCompra]); ?></td>
              <td align="left"><?php echo $row['Cue_Nombre']; ?></td>
              <td align="left"><?php echo $row['Rec_Detalle']; ?></td>
              <td align="center"><?php echo $row['Rec_TipoRecibo']; ?></td>
              <td align="center"><?php echo $row['Rec_Numero']; ?></td>
              <td align="center">$<?php echo number_format(floatval($row['Rec_Importe']),2,",","."); ?></td>
              <td align="center"><?php echo $row['Rec_FormaPago'];
              if ($row['Rec_FormaPago']=="cheque") echo "$row[Rec_ChequeNumero], $row[Rec_ChequeBanco], ".cfecha($row[Rec_ChequeFecha]);
               ?></td>
              <td align="center"><?php echo "$row[Usu_Nombre] ".cfecha($row['Rec_Fecha'])." $row[Rec_Hora]"; ?></td>
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
      <th colspan="10" class="fila_titulo" align="right">
      Total: $<?php echo number_format($total,2,",",".");?> </th>      
    </tr>
  </tfoot>      
      </table>  
      <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>   
  <form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>