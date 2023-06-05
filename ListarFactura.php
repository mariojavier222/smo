<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
    $(document).ready(function(){
		
		$("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		vLectivo = $("#LecID option:selected").text();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Recibos ' + vLectivo,overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css', media:'print'}]									
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
	
		 $("#Imprimirpopup").click(function(evento){
			 
			 			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "mostrarListadoImprimir2"},
				url: 'cargarOpcionesImprimir.php',
				success: function(data){ 
					 mostrarAlerta3(data,"LISTAR CAJA CORRIENTE (IMPRIMIR)",800,700);
				}
			});//fin ajax//*/ 
			 
			 });
	
	
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
	
    
    $('#listadoTabla2').dataTable( {
        "bPaginate": true,
        //"aaSorting": [[ 1, "asc" ]],
        "aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true } );//*/
});//fin de la funcion ready


</script>
<div id="listado">	
<br />
<br />

        <div align="center" class="titulo_noticia">Listado de Recibos emitidos</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        $NumCaja = $_POST['numerocaja'];
		//echo $NumCaja;
		$importeTotal = 0;
	 $sql_2 = "SELECT * FROM
    FacturaDetalle
    INNER JOIN Factura 
        ON (FDe_Fac_ID = Fac_ID)
    INNER JOIN FacturaTipo 
        ON (Fac_FTi_ID = FTi_ID)  
    INNER JOIN CuotaPago 
        ON (FDe_Fac_ID = CuP_Fac_ID) AND (FDe_Item = CuP_FDe_Item)
    INNER JOIN Caja 
        ON (CuP_Caja_ID = Caja_ID)
    INNER JOIN Persona 
        ON (Per_ID = CuP_Per_ID) WHERE Caja_ID=$NumCaja GROUP BY Fac_ID ORDER BY Fac_ID;";
        $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
        //echo $sql_2;

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="1000px" border="1" id="listadoTabla" class="texto" style="border-collapse:collapse;">
          <thead>
            <tr class="fila_titulo" id="fila" style="height:30px;">
           <th>#</th>
           <th width="120px">Recibo</th>
           <th>Fecha y Hora</th>
           <th>Apellido y Nombre</th>
           <th>Monto</th>
           <th>Concepto</th>
           <th>Forma Pago</th>
            </tr>
          </thead>
          <tbody>
            <?php
			
			     $i=0;
                    while ($row = mysqli_fetch_array($result)) {

$i++;
if ($row[Fac_Anulada]==1) {
	$clase = "class = 'cuota_anulada'"; 
	$title = "title = 'Factura anulada'";
}else{
	$clase = "";
	$title = "";
}
?>
        <tr style="height:30px;" <?php echo $clase; echo " $title";?>>
         <td><?php echo $i ?></td>
         <td><?php echo $row[FTi_Siglas]." ".$row[Fac_Sucursal]."-".$row[Fac_Numero];if ($row[Fac_Anulada]==1) echo "-ANU"; ?></td>
         <td><?php echo cfecha($row[Fac_Fecha])." ".$row[Fac_Hora] ?></td>
         <td><?php echo $row[Per_DNI]." ".$row[Per_Apellido]." ".$row[Per_Nombre] ?></td>
         <td><?php echo "$".number_format($row[Fac_ImporteTotal],2,",","."); ?></td>
         <td align="left"> 
		   <?php 
		    if ($row[Fac_Anulada]==0) $importeTotal += $row[Fac_ImporteTotal];
		    $Fac_ID=$row[Fac_ID];
		    $sql_3 = "SELECT 	* FROM FacturaDetalle WHERE FDe_Fac_ID=$Fac_ID;";
        $result2 = consulta_mysql_2022($sql_3,basename(__FILE__),__LINE__);
		    while ($row2 = mysqli_fetch_array($result2)) { 
		      echo $row2[FDe_Detalle]."<br>";
		    }?></td>

        <td><?php
        echo buscarFormaPagoFactura($row[Fac_ID]);
        echo "<br />".buscarFormaPagoFacturaDetalle($row[Fac_ID]);
        ?></td>
              </tr>
            
            <?php
            // Eze Nota de Credito
            $sql_4 = "SELECT  * FROM Factura WHERE Fac_ID_Ndec = $Fac_ID;";
            $result4 = consulta_mysql_2022($sql_4,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result4) > 0) {
               while ($row4 = mysqli_fetch_array($result4)) { ?>
                <tr style="height:30px; background-color: #b6e4f7;" <?php echo " $title";?>>
                <td><?php echo $i ?></td>
                <td><?php echo $row[FTi_Siglas]." ".$row4[Fac_Sucursal]."-".$row4[Fac_Numero]."-NdeC"; ?></td>
                <td><?php echo cfecha($row4[Fac_Fecha])." ".$row4[Fac_Hora] ?></td>
                <td><?php echo $row[Per_DNI]." ".$row[Per_Apellido]." ".$row[Per_Nombre] ?></td>
                <td><?php echo "$".number_format($row4[Fac_ImporteTotal],2,",","."); ?></td>
                <td><?php echo "Nota de Credito sobre Factura Nº ".$row[Fac_Sucursal]."-".$row[Fac_Numero]; ?></td>
                <td><?php
                echo buscarFormaPagoFactura($row[Fac_ID]);
                if ($row[FTi_ID]>1) echo buscarFormaPagoFacturaDetalle($row[Fac_ID]);
                ?></td>
<?php
              }
            }

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
      <th colspan="12" class="fila_titulo"></th>      
    </tr>
  </tfoot>      
      </table>
     
  <fieldset class="recuadro_inferior" style="height:32px">
<div align="left" style="font-size:16px">
<a href="imprimirCajaCorrienteRecibos.php?id=<?php echo $NumCaja;?>" target="_blank"><img src="imagenes/printer.png" alt="Imprimir el listado en PDF" title="Imprimir el listado en PDF" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
<!--<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a>   - <a href="#" id="Imprimirpopup"><img src="imagenes/printer.png" alt="Imprimir 2" title="Imprimir 2" width="32" border="0" align="absmiddle" /></a>--> Total importe: $<?php echo number_format($importeTotal,0,",",".");?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
  </div> 
<p><br />
    <br />
</p>
<div id="mostrar"></div>
<p>&nbsp;</p>    