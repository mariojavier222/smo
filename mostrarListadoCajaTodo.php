<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
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
		
		$(".botondiego").button();
		
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
				
	//Filtro la caja por Fecha para ver individualmente
	
	$("a[id^='campos']").click(function(evento){
            evento.preventDefault();			
            var i = this.id.substr(6,15);
            vNumCaja = $("#numCaja" + i).val();
            //alert(i);return;
            $.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numeroCaja: vNumCaja},
				url: 'mostrarListadoCajaCorriente.php',
				success: function(data){ 
					$("#principal").html(data);
				}
			});//fin ajax//*/          
        });
			
		
	$("button[id^='btn_vista_previa_cerrar']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(23,10);
            $("#tr_campos"+ i).hide();
        });
		
		$("table[id^='tablaDetalle']").dataTable( {
            "bPaginate": true,
            //"aaSorting": [[ 1, "asc" ]],
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true } );//*/	
	
    				
});//fin de la funcion ready


</script>
<?php
$fechaDesde = $_POST['fechaDesde'];
$fechaHasta = $_POST['fechaHasta'];


	
?>
<div id="listado" >	
	<h1>Listado de todas las Cajas</h1>
<br />
<br />
		<?php
		$usuario = $_SESSION['sesion_UsuID'];
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);  

        $where = "";      
        
		$sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) $where ORDER BY Caja_ID DESC";
		$result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Número</th>
              <th align="center" class="fila_titulo">Fecha Apertura</th>
              <th align="center" class="fila_titulo">Fecha Cierre</th>
              <th align="center" class="fila_titulo">Usuario</th>
              <!--<th align="center" class="fila_titulo">Usuario Cierre</th>-->
              <th align="center" class="fila_titulo">Total</th>
              <th align="center" class="fila_titulo">Detalle</th>
            </tr>
          </thead>
          <tbody>
            <?php
			$acum = 0;
            while ($row = mysqli_fetch_array($result)) {
                $i++;
				$id = $row[Caja_ID];
				$valor = $row[Caja_Cierre];						
				/*if ($valor == ''){
					$clase = "caja_abierta";	
				}else{
					$clase = "caja_cerrada";
					}*/
				if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
            ?>
            <tr class ="<?php echo $clase?>">
              <td align="center"><?php echo $row[Caja_ID]; ?></td>
              <td align="center"><?php echo cfecha(substr($row[Caja_Apertura],0,10))." (".substr($row[Caja_Apertura],11,5)." hs)"; ?></td>
              <?php
              if($row[Caja_Cierre]!= ''){
				  ?>
                  <td align="center"><?php echo cfecha(substr($row[Caja_Cierre],0,10))." (".substr($row[Caja_Cierre],11,5)." hs)";; ?></td>
                  <?php
				  }else{
				  ?>
                  <td align="center">Caja Abierta</td>
                  <?php
				  }
			  ?>
              
              <td align="left"><?php echo $row[Usu_Persona]; ?></td>
              <!--<td align="left"><?php echo $row[Usu_Persona]; ?></td>-->
              <td align="right">$<?php 
			  $totalCaja = number_format($row['Caja_Importe_Total'],2,",",".");// buscarRecaudacion($id);
			  $acum += $row['Caja_Importe_Total'];
			  //echo "$totalCaja/$acum"; 
        if ($totalCaja==0){
          echo '-----';
        }else{
          echo $totalCaja;  
        }
			  
			  ?></td>
              <td align="center"><input type="hidden" id="numCaja<? echo $i;?>" value="<? echo $id;?>">
             <!--  <a id="campos<?php //echo $i; ?>" href="#"><img  src="imagenes/magnifier_zoom_in.png" alt="Ver Detalle de Cobros" title="Ver Detalle de Cobros" width="22" height="22" border="0" /></a>  -->
              <a href="imprimirCajaCorriente.php?id=<?php echo $id;?>" target="_blank"><img src="imagenes/printer.png" alt="Imprimir el listado en PDF" title="Imprimir el listado en PDF" width="32" border="0" align="absmiddle" /></a></td>
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
      <th colspan="12" class="fila_titulo"></th>
    </tr>
  </tfoot>
      </table>                   
    </fieldset>
    <div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> Total: <?php echo "$".number_format($acum,2,",",".");?></div>
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
