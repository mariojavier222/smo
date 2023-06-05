<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
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
      "pageLength": 100,
      "aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
      "bLengthChange": true,
      "bFilter": true,
      "bSort": true,
      "bInfo": true,
      "bAutoWidth": true } );//*/
});//fin de la funcion ready


</script>
        <div align="center" class="titulo_noticia"> Movimientos de la Cuenta Corriente del Alumno</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        $Per_ID = $_POST['Per_ID'];
        
        $sql_2 = "SELECT * FROM CuentaAlumno WHERE  CAl_Per_ID = $Per_ID ORDER BY CAl_ID DESC";
        $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">#</th>
              <th align="center" class="fila_titulo">Fecha</th>
              <th align="center" class="fila_titulo">hora</th>
              <th align="center" class="fila_titulo">Concepto</th>
              <th align="center" class="fila_titulo">Debe</th>
              <th align="center" class="fila_titulo">Haber</th>
              <th align="center" class="fila_titulo">Saldo</th>
              <th align="center" class="fila_titulo">Detalle</th>
              <th align="center" class="fila_titulo">Forma Pago</th>
              <th align="center" class="fila_titulo">Pago Detalle</th>
            </tr>
          </thead>
          <tbody>
            <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        ?>
            <tr style="background-color: gainsboro;">
              <td align="center"><?php echo $i;?></td>
                <input type="hidden" name="CAl_ID" id="CAl_ID<?php echo $i; ?>" value="<?php echo $row[CAl_ID]; ?>" />
                <input type="hidden" name="CAl_Per_ID" id="CAl_Per_ID<?php echo $i; ?>" value="<?php echo $row[CAl_Per_ID]; ?>" />
                <input type="hidden" name="CAl_Concepto" id="CAl_Concepto<?php echo $i; ?>" value="<?php echo $row[CAl_Concepto]; ?>" />
                <input type="hidden" name="CAl_Debito" id="CAl_Debito<?php echo $i; ?>" value="<?php echo $row[CAl_Debito]; ?>" />
                <input type="hidden" name="CAl_Credito" id="CAl_Credito<?php echo $i; ?>" value="<?php echo $row[CAl_Credito]; ?>" />
                <input type="hidden" name="CAl_Saldo" id="CAl_Saldo<?php echo $i; ?>" value="<?php echo $row[CAl_Saldo]; ?>" />
                <input type="hidden" name="CAl_Fecha" id="CAl_Fecha<?php echo $i; ?>" value="<?php echo $row[CAl_Fecha]; ?>" />
                <input type="hidden" name="CAl_Hora" id="CAl_Hora<?php echo $i; ?>" value="<?php echo $row[CAl_Hora]; ?>" />
                <input type="hidden" name="CAl_Detalle" id="CAl_Detalle<?php echo $i; ?>" value="<?php echo $row[CAl_Detalle]; ?>" />
                <input type="hidden" name="CAl_Referencia" id="CAl_Referencia<?php echo $i; ?>" value="<?php echo $row[CAl_Referencia]; ?>" />
              <td align="center"><?php echo cfecha($row[CAl_Fecha]); ?></td>
              <td align="center"><?php echo $row[CAl_Hora]; ?></td>
              <td align="center"><?php echo $row[CAl_Concepto]; ?></td>
              <td align="center"><?php echo $row[CAl_Debito]; ?></td>
              <td align="center"><?php echo $row[CAl_Credito]; ?></td>
              <td align="center"><?php echo $row[CAl_Saldo]; ?></td>
              <td align="center" title="<?php echo $row[CAl_Referencia]; ?>"><?php echo $row[CAl_Detalle];?></td>
              <td align="center"><?php echo $row[CAl_FormaPago]; ?></td>
              <td align="center"><?php echo $row[CAl_PagoDetalle]; ?></td>
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
      <th colspan="13" class="fila_titulo"></th>
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
      