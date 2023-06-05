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
    
//    $("a[id^='botEditar']").click(function(evento){											  
//            evento.preventDefault();
//            var i = this.id.substr(9,10);
//            
//            $("#Fac_ID").val($("#Fac_ID" + i).val());
//            $("#Fac_FTi_ID").val($("#Fac_FTi_ID" + i).val());
//            $("#Fac_Iva_ID").val($("#Fac_Iva_ID" + i).val());            
//            $("#Fac_CVe_ID").val($("#Fac_CVe_ID" + i).val());
//            $("#Fac_Fecha").val($("#Fac_Fecha" + i).val());
//            $("#Fac_Hora").val($("#Fac_Hora" + i).val());
//            $("#Fac_Usu_ID").val($("#Fac_Usu_ID" + i).val());
//            $("#Fac_CUIT").val($("#Fac_CUIT" + i).val());
//            $("#Fac_Sucursal").val($("#Fac_Sucursal" + i).val());
//            $("#Fac_Numero").val($("#Fac_Numero" + i).val());
//            $("#Fac_PersonaNombre").val($("#Fac_PersonaNombre" + i).val());
//            $("#Fac_PersonaDomicilio").val($("#Fac_PersonaDomicilio" + i).val());
//            $("#Fac_ImporteTotal").val($("#Fac_ImporteTotal" + i).val());
//            $("#Fac_Pagada").val($("#Fac_Pagada" + i).val());
//            $("#Fac_Anulada").val($("#Fac_Anulada" + i).val());
//            $("#mostrarNuevo").fadeIn();
//            $("#mostrar").empty();		
//            $("#listado").fadeOut();
//		
//        });//fin evento click//*/	 
//        $("a[id^='botBorrar']").click(function(evento){											  
//            evento.preventDefault();
//            var i = this.id.substr(9,10);
//            vFac_Numero = $("#Fac_Numero" + i).val();
//            vID = $("#Fac_ID" + i).val();
//            
//		
//            jConfirm('Est&aacute; seguro que desea eliminar a <strong>' + vFor_Nombre + '</strong>?', 'Confirmar la eliminación', function(r){
//                if (r){//eligio eliminar
//                    $.post("cargarOpcionesFormaPago.php", { opcion: "eliminarFormaPago", ID: vID }, function(data){
//                        jAlert(data, 'Resultado de la eliminación');
//                        recargarPagina();
//                    });//fin post					
//                }//fin if
//            });//fin del confirm//*/
//	
//        });//fin evento click//*/
    
    $('#listadoTabla').dataTable( {
                    "bPaginate": true,
                    //"aaSorting": [[ 1, "asc" ]],
                    "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
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

        <div align="center" class="titulo_noticia">Listado de Caja Corriente</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        $NumCaja = $_POST['numeroCaja'];
		$NumUsuario = $_POST['Usuario'];
        
		if($NumUsuario != -1){
        $sql_2 = "SELECT * FROM Caja INNER JOIN CajaCorriente ON (Caja.Caja_ID = CajaCorriente.CCC_Caja_ID) INNER JOIN Usuario ON (CajaCorriente.CCC_Usu_ID = Usuario.Usu_ID) INNER JOIN FormaPago ON (CajaCorriente.CCC_For_ID = FormaPago.For_ID) 
 WHERE Usuario.Usu_ID = $NumUsuario AND CajaCorriente.CCC_Caja_ID = $NumCaja ORDER BY CCC_ID";
 }
 else {
	 $sql_2 = "SELECT * FROM Caja INNER JOIN CajaCorriente ON (Caja.Caja_ID = CajaCorriente.CCC_Caja_ID) INNER JOIN Usuario ON (CajaCorriente.CCC_Usu_ID = Usuario.Usu_ID) INNER JOIN FormaPago ON (CajaCorriente.CCC_For_ID = FormaPago.For_ID) 
 WHERE CajaCorriente.CCC_Caja_ID = $NumCaja  ORDER BY CCC_ID";
	 }
        $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Fecha</th>
              <th align="center" class="fila_titulo">hora</th>
              <th align="center" class="fila_titulo">Concepto</th>
              <th align="center" class="fila_titulo">Debe</th>
              <th align="center" class="fila_titulo">Haber</th>
              <th align="center" class="fila_titulo">Saldo de Caja</th>
              <th align="center" class="fila_titulo">Usuario</th>
              <th align="center" class="fila_titulo">Saldo de Usuario</th>
              <th align="center" class="fila_titulo">Forma de Pago</th>             
              <th align="center" class="fila_titulo">Detalle</th>
            </tr>
          </thead>
          <tbody>
            <?php
			
			        $acum[] = array();
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$UsuID = $row[CCC_Usu_ID];
						$Saldo = ($row[CCC_Credito]-$row[CCC_Debito]);
                        $acum[$UsuID] = $acum[$UsuID] + $Saldo;
						$Saldo_Acutal = $acum[$UsuID];
												
						?>
            <tr style="background-color: gainsboro;">
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
              <td align="center"><?php echo $row[CCC_Concepto]; ?></td>
              <td align="center">$<?php echo intval($row[CCC_Debito]); ?></td>
              <td align="center">$<?php echo intval($row[CCC_Credito]); ?></td>
              <td align="center">$<?php echo intval($row[CCC_Saldo]); ?></td>
              <td align="center"><?php echo $row[Usu_Nombre]; ?></td>
              <td align="center">$<?php echo intval($Saldo_Acutal); ?></td>
              <td align="center"><?php echo $row[For_Nombre]; ?></td>
              <td align="center"><img src="imagenes/comment.png" title="<?php echo $row[CCC_Detalle]; ?>-<?php echo $row[CCC_Referencia]; ?>" /></td>
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