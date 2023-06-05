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
$.validator.setDefaults({
submitHandler: function() { 
	/*Dat_Telefono = $("#Dat_Telefono").val();
	Dat_Celular = $("#Dat_Celular").val();
	Per_Sexo = $("#Per_Sexo").val();
	if (Per_Sexo == -1){
		mostrarCartel("Seleccione el Sexo de la persona", "ERROR");
		$("#Per_Sexo").focus();
		return;
	}
	if (!Dat_Telefono && !Dat_Celular){
		mostrarCartel("Debe escribir un número de teléfono o celular de referencia para la persona", "ERROR");
		$("#Dat_Telefono").focus();
		return;
	}*/
	//alert("voy bien");
	datos = $("#formDatos" + i).serialize();
	$.ajax({
		type: "POST",
		cache: false,
		async: false,			
		url: 'guardarItemCaja.php',
		data: datos,
		success: function (data){
			
			mostrarCartel(data, "Resultado de guardar los datos");
			//$("#barraGuardar").removeAttr("disabled");
		}
	});//fin ajax
}
});
    $(document).ready(function(){
		
    	$("select[id^='For_ID']").hide();
    	$("button[id^='Guardar']").hide();

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
	
	$("a[id^='EditarItemCaja']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(14,10);
		$("#For_ID" + i).show();
		$("#For_ID" + i).val($("#CCC_For_ID" + i).val());
		$("#For_ID" + i).change();
		$(this).hide();
		$("#Guardar" + i).show();
		CCC_ID = $("#CCC_ID" + i).val();
		
	 });//fin evento click//*/
	 $("button[id^='Guardar']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(7,10);
		
		CCC_ID = $("#CCC_ID" + i).val();
		For_ID = $("#For_ID" + i).val();
		//alert(vCuota);
		$("#cargando").show();
		$("#formDatos" + i).validate();
		$("#formDatos" + i).submit();
		$("#cargando").hide();
		$(this).hide();
		$("#For_ID" + i).hide();
		$("#mostrarDetallePago" + i).hide();
		//$("#For_ID" + i).hide();
	 });//fin evento click//*/	
	 $("select[id^='For_ID']").change(function () {
	 	i = this.id.substr(6,10);
   		$("#For_ID" + i + " option:selected").each(function () {
			//alert($(this).val());
				
				vForID=$(this).val();
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'cargarDetalleFormaPago', ForID: vForID, i: i},
					url: 'cargarOpciones.php',
					success: function(data){ 
						if (data){
							$("#mostrarDetallePago" + i).html(data);
						}else{
							$("#mostrarDetallePago" + i).empty();
						}
					}
					
			});//fin ajax///
        });
   })
	
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

$("#verTodas").click(function(evento){
    evento.preventDefault();
    $.ajax({
        type: "POST",
        cache: false,
        async: false,
        error: function (XMLHttpRequest, textStatus){
            alert(textStatus);},
        //data: {numeroCaja: vNumCaja},
        url: 'mostrarListadoCajaTodo.php',
        success: function(data){ 
            $("#principal").html(data);
        }
    });//fin ajax//*/ 
              
});

});//fin de la funcion ready



</script>
<div id="listado" class="texto">	
<br />
<br />

  
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        $NumCaja = $_POST['numeroCaja'];
        ?>
        <button class="botones" id="verTodas"><< Listar Todas las Cajas Diarias</button>
<div align="center" class="titulo_noticia">Listado de Caja Corriente de la Caja Nº <? echo $NumCaja;?></div>
        <?
		$UsuID = $_POST['UsuID'];
		$fechaDesde = $_POST['fechaDesde'];
		$fechaHasta = $_POST['fechaHasta'];
		$Arreglar = false;
		if (isset($_POST['Arreglar'])){
			$Arreglar = true;
		}
	 $sql_2 = "SELECT * FROM Caja INNER JOIN CajaCorriente ON (Caja_ID = CCC_Caja_ID) INNER JOIN Usuario ON (CCC_Usu_ID = Usu_ID) INNER JOIN FormaPago ON (CCC_For_ID = For_ID) 
 WHERE ";
 	$colocarAND = false;
	if (!empty($NumCaja)){
 		$sql_2 .=" CCC_Caja_ID = $NumCaja";
		$colocarAND = true;
	}
	
	if ($UsuID > 0){
 		if ($colocarAND) {$sql_2 .= " AND ";$colocarAND = false;}
		$sql_2 .=" Caja_Usu_ID = $UsuID";
		$colocarAND = true;
	}
	
	if (!empty($fechaDesde)){
 		if ($colocarAND) {$sql_2 .= " AND ";$colocarAND = false;}
		$sql_2 .=" Caja.Caja_Apertura >= '".cambiaf_a_mysql($fechaDesde)."' AND Caja.Caja_Apertura <='".cambiaf_a_mysql($fechaHasta)."'";
		$colocarAND = true;
	}
	$sql_2 .="  ORDER BY CCC_ID";
	//echo $sql_2;
    $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);


//echo "sql ".$sql_2;

        if (mysqli_num_rows($result) > 0) {
            ?>
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
              <!-- <th align="center" class="fila_titulo">Usuario</th>
              <th align="center" class="fila_titulo">Saldo de Usuario</th> -->
              <th align="center" class="fila_titulo">Forma de Pago</th>
              <?php if ($Arreglar){ ?>
              <th align="center" class="fila_titulo">Acción</th>
              <?php } ?>             
            </tr>
        </thead>
          <tbody>
            <?php
			
			        $acum[] = array();
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$UsuID = $row['CCC_Usu_ID'];
						$Saldo = $row['CCC_Credito']-$row['CCC_Debito'];
                        $acum[$UsuID] = $acum[$UsuID] + $Saldo;
						$Saldo_Acutal = $acum[$UsuID];
						
						$CCC_Debito = $row['CCC_Debito'];
						$CCC_Credito = $row['CCC_Credito'];
						$debito += $CCC_Debito;
						$credito += $CCC_Credito;
						
						$valor = $row[CCC_Ret_Dinero];
						//echo $valor;	
						$clase = "";					
						if ($valor == 1){
							$clase = "retiro_dinero";										
						}else{
							$clase = ""; //"caja_abierta";
							}
												
						?>
            <tr class="<?php echo $clase; ?>" title="<?php echo $row[CCC_Detalle]; ?>-<?php echo $row[CCC_Referencia]; ?>">
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
              <td align="center"><?php echo "$row[CCC_Concepto] - $row[CCC_Detalle]"; ?></td>              
              <td align="center">$<?php echo number_format(floatval($row[CCC_Credito]),2,",","."); ?></td>
              <td align="center">$<?php echo number_format(floatval($row[CCC_Debito]),2,",","."); ?></td>
              <td align="center">$<?php echo number_format(floatval($Saldo_Acutal),2,",","."); ?></td>
              <!-- <td align="center"><?php echo $row[Usu_Nombre]; ?></td>
              <td align="center">$<?php echo number_format(floatval($Saldo_Acutal),2,",","."); ?></td> -->
              <td align="center"><?php echo $row[For_Nombre]; ?></td>
              <?php if ($Arreglar){ 
              	if ($i>1 && $row['CCC_Debito']==0){
              	?>
              <td align="center">
              	<form id="formDatos<?php echo $i;?>" name="formDatos<?php echo $i;?>">
              	<a style="cursor:pointer" id="EditarItemCaja<?php echo $i;?>"><img src="imagenes/application_form_edit.png" title="Editar Item de Caja" width="30" height="30" border="0"/></a>
              	<input type="hidden" name="CCC_ID<?php echo $i;?>" id="CCC_ID<?php echo $i;?>" value="<?php echo $row['CCC_ID'];?>">
              	<input type="hidden" name="CCC_For_ID<?php echo $i;?>" id="CCC_For_ID<?php echo $i;?>" value="<?php echo $row['CCC_For_ID'];?>">
              	<input type="hidden" name="NumCaja<?php echo $i;?>" id="NumCaja<?php echo $i;?>" value="<?php echo $NumCaja;?>">
              	<?php cargarListaFormaPago("For_ID$i");?> 
              	<div id="mostrarDetallePago<?php echo $i;?>"></div>  
              	<button id="Guardar<?php echo $i;?>">Guardar</button>
              </form>           	
              </td>
              <?php 
         	 }//fin if i>1
          	}//fin Arreglar ?>
            </tr>
            
            <?php
                    }//fin while
					$importeTotal = $credito - $debito;
				$importeTotal = number_format($importeTotal,2,",",".");
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
      <th colspan="10" class="fila_titulo">Saldo Total: $<?php echo $importeTotal;?></th>      
    </tr>
  </tfoot>      
  </table>
     
  <fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="imprimirCajaCorriente.php?id=<?php echo $NumCaja;?>" target="_blank"><img src="imagenes/printer.png" alt="Imprimir el listado en PDF" title="Imprimir el listado en PDF" width="32" border="0" align="absmiddle" />Caja Corriente</a> <a href="imprimirCajaCorrienteRecibos.php?id=<?php echo $NumCaja;?>" target="_blank"><img src="imagenes/printer.png" alt="Imprimir el listado en PDF" title="Imprimir el listado en PDF" width="32" border="0" align="absmiddle" />Recibos</a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <a href="#" id="Imprimirpopup"><img src="imagenes/printer.png" alt="Imprimir 2" title="Imprimir 2" width="32" border="0" align="absmiddle" /></a></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
</div> 