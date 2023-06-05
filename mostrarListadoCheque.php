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
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Inscriptos para el Ciclo Lectivo ' + vLectivo,overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css', media:'print'}]									
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
    
    $('#listadoTabla').dataTable( {
                    "bPaginate": true,
                    //"aaSorting": [[ 1, "asc" ]],
                    //"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
                    "bLengthChange": false,
                    "bFilter": true,
                    //"bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true } );//*/
});//fin de la funcion ready


</script>
<div id="listado">	
<br />
<br />

        <div align="center" class="titulo_noticia">Listado de Cheques</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
		$sql = "SELECT
    CuotaPagoDetalle.CPD_For_ID
    , FormaPago.For_Nombre
    , FormaPagoDetalle.FDe_Nombre
    , CuotaPagoDetalle.CPD_Valor
    , CuotaPagoDetalle.CPD_Fecha
    , CuotaPagoDetalle.CPD_Hora
    , CuotaPagoDetalle.CPD_Lec_ID
    , CuotaPagoDetalle.CPD_Per_ID
    , CuotaPagoDetalle.CPD_Niv_ID
    , CuotaPagoDetalle.CPD_CTi_ID
    , CuotaPagoDetalle.CPD_Alt_ID
    , CuotaPagoDetalle.CPD_Numero
    , CuotaPagoDetalle.CPD_Orden
FROM
    CuotaPagoDetalle
    INNER JOIN FormaPago 
        ON (CuotaPagoDetalle.CPD_For_ID = FormaPago.For_ID)
    INNER JOIN FormaPagoDetalle 
        ON (CuotaPagoDetalle.CPD_FDe_ID = FormaPagoDetalle.FDe_ID) AND (CuotaPagoDetalle.CPD_For_ID = FormaPagoDetalle.FDe_For_ID)
WHERE (CuotaPagoDetalle.CPD_For_ID = 2)
ORDER BY CuotaPagoDetalle.CPD_Lec_ID ASC, CuotaPagoDetalle.CPD_Per_ID ASC, CuotaPagoDetalle.CPD_Niv_ID ASC, CuotaPagoDetalle.CPD_CTi_ID ASC, CuotaPagoDetalle.CPD_Alt_ID ASC, CuotaPagoDetalle.CPD_Numero ASC, CuotaPagoDetalle.CPD_Orden ASC";
        
		
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Fecha de Emisión</th>
              <th align="center" class="fila_titulo">Fecha de Cobro</th>
              <th align="center" class="fila_titulo">Banco</th>
              <th align="center" class="fila_titulo">Nro. Cheque</th>
              <th align="center" class="fila_titulo">Importe</th>
            </tr>
          </thead>
          <tbody>
            <?php		        
					$i = 1;
					$j = 0;
                    while ($row = mysqli_fetch_array($result)) {
						$j++;					
						
						if($j == 5){							
							$i++;
							$j = 1;							
							}
						$columna = $row[FDe_Nombre];	
						$arreglo[$i][$columna] = $row[CPD_Valor];								
                    }//fin while
					  $fila = $i;
				      $col = $j;                                
				for ($i = 1; $i<= $fila; $i++){
						 ?>
						 <tr style="background-color: gainsboro;">
               <td align="center"> <?php echo $arreglo[$i]['Fecha Emisión']; ?></td>
               <td align="center"> <?php echo $arreglo[$i]['Fecha Cobro']; ?></td>
               <td align="center"> <?php echo $arreglo[$i]['Banco']; ?></td>
               <td align="center"> <?php echo $arreglo[$i]['Num Cheque']; ?></td>
               <td align="center"> <?php echo $arreglo[$i]['Importe']; ?></td> 
					 </tr>
				     <?php
					}	// fin for de $i				
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
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a></div>
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