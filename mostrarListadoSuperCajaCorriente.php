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
		
	$("#imprimirTodasDesactivado").click(function(evento){
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
	vNumeroCaja = <?php echo $_POST['numeroCaja'];?>;
		 $("#Imprimirpopup").click(function(evento){
			 
			 			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'cargarImprimir2.php',
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
<div id="listado" class="texto">	
<br />
<br />

  <div align="center" class="titulo_noticia">Listado de la Super Caja Corriente</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        $NumCaja = $_POST['numeroCaja'];
		$UsuID = $_POST['UsuID'];
		$fechaDesde = $_POST['fechaDesde'];
		$fechaHasta = $_POST['fechaHasta'];
	 $sql_2 = "SELECT * FROM SuperCaja INNER JOIN SuperCajaCorriente ON (SCa_ID = SCC_SCa_ID) INNER JOIN Usuario ON SCC_Usu_ID = Usu_ID INNER JOIN CajaCorriente 
        ON (SCC_CCC_ID = CCC_ID)
    INNER JOIN FormaPago 
        ON (CCC_For_ID = For_ID) WHERE ";
 	$colocarAND = false;
	if (!empty($NumCaja)){
 		$sql_2 .=" SCC_SCa_ID = $NumCaja";
		$colocarAND = true;
	}
	
	if ($UsuID > 0){
 		if ($colocarAND) {$sql_2 .= " AND ";$colocarAND = false;}
		$sql_2 .=" SCa_Usu_ID = $UsuID";
		$colocarAND = true;
	}
	
	if (!empty($fechaDesde)){
 		if ($colocarAND) {$sql_2 .= " AND ";$colocarAND = false;}
		$sql_2 .=" SCa_Apertura >= '".cambiaf_a_mysql($fechaDesde)."' AND SCa_Apertura <='".cambiaf_a_mysql($fechaHasta)."'";
		$colocarAND = true;
	}
	$sql_2 .="  ORDER BY SCC_ID DESC";
        $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);


//echo "sql ".$sql_2;

        if (mysqli_num_rows($result) > 0) {
            
			$Saldo = obtenerSaldoSuperCajaCorriente($NumCaja);
			?>
            
            <p align="center" class="titulo_noticia">Saldo actual: $<?php echo number_format($Saldo,2,",",".");?></p>
      <table width="100%" border="1" id="listadoTabla" class="texto" style="border-collapse:collapse;">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">#</th>
              <th align="center" class="fila_titulo">Fecha</th>
              <th align="center" class="fila_titulo">Hora</th>
              <th align="center" class="fila_titulo">Concepto</th>
              <th align="center" class="fila_titulo">Ingreso</th>
              <th align="center" class="fila_titulo">Egreso</th>
              <th align="center" class="fila_titulo">Saldo de Caja</th>
              <th align="center" class="fila_titulo">Detalle del concepto</th>
              <th align="center" class="fila_titulo">Usuario</th>
              <th align="center" class="fila_titulo">Forma de Pago</th>             
            </tr>
        </thead>
          <tbody>
            <?php
			
			        $acum[] = array();
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$UsuID = $row[SCC_Usu_ID];
						$Saldo = ($row[SCC_Credito]-$row[SCC_Debito]);
                        $acum[$UsuID] = $acum[$UsuID] + $Saldo;
						$Saldo_Acutal = $acum[$UsuID];
						
						/*$valor = $row[CCC_Ret_Dinero];
						//echo $valor;	
						$clase = "";					
						if ($valor == 1){
							$clase = "retiro_dinero";										
						}else{
							$clase = ""; //"caja_abierta";
							}
							*/					
						?>
            <tr class="<?php echo $clase; ?>" title="ID: <?php echo $row['SCC_ID'];?>">
              <td align="center"><?php echo $i;?></td>
            <td align="center">
			<?php
			if($row[CCC_Fecha] != ''){
               echo cfecha($row[CCC_Fecha]);
				  }
				  else{
					  echo '--';
					  } 
			  ?>
              </td>
              <td align="center">
              <?php
			if($row[CCC_Hora] != ''){
              echo $row[CCC_Hora]; 
				  }
				  else{
					  echo '--';
					  }
					  
			  ?>
              </td>
              <td align="center"><?php echo $row[SCC_Concepto]; ?></td>              
              <td align="center">$<?php echo number_format(floatval($row[SCC_Credito]),2,",","."); ?></td>
              <td align="center">$<?php echo number_format(floatval($row[SCC_Debito]),2,",","."); ?></td>
              <td align="center">$<?php echo number_format(floatval($row[SCC_Saldo]),2,",","."); ?></td>
              <td align="center"><?php echo $row[SCC_Detalle]; ?></td>
              <td align="center"><?php echo $row[Usu_Nombre]; ?></td>
              <td align="center"><?php echo $row[For_Nombre]; ?></td>
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
      <th colspan="10" class="fila_titulo"></th>      
    </tr>
  </tfoot>      
  </table>
     
  <fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="imprimirSuperCaja.php?id=<?php echo $NumCaja;?>" id="imprimirTodas" target="_blank"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> <!--- <a href="#" id="Imprimirpopup"><img src="imagenes/printer.png" alt="Imprimir 2" title="Imprimir 2" width="32" border="0" align="absmiddle" /></a>--></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
</div> 