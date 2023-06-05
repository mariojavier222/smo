<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");

$opcionnum = $_POST['opcionnum'];
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
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Facturas' + vLectivo,overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css', media:'print'}]									
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
                    "aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true } );//*/
});//fin de la funcion ready

$('#volver').click(function(){
	
	$.ajax({
	url: 'cargarFacturas.php',
	type: "POST",
	cache: false,
	async: true,
	success: function(data2){
		$("#principal").html(data2);
		}
	});
	
	})

function Anular_Factura_viejo(i)
{
	
	Fac_ID=$("#Fac_ID"+i).val();
	
				jConfirm('¿Está seguro que desea Anular Factura?', 'Confirmar la Anulacion', function(r){
    			if (r){
				$.ajax({
	url: 'cargarOpciones.php',
	type: "POST",
	cache: false,
	data: { opcion: "Anular_Factura",Fac_ID:Fac_ID},
	async: true,
	success: function(data2){
		//mostrarAlerta(data2);
		$("#probar").html(data2);
		
		
		//nahuel22	
		Fac_Numero=	$("#Fac_Numero").val();
		fecha1=	$("#fecha1").val();
		fecha2=	$("#fecha2").val();
		Ciclo_lectivo=	$("#Ciclo_lectivo").val();
		Mes= $("#Mes").val();
		opcionnum= $("#opcionnum").val();
		per_ID=$("#per_ID1").val();
		

		$.ajax({
	url: 'listadoFactura.php',
	type: "POST",
	cache: false,
	data: {Fac_Numero:Fac_Numero,opcionnum:opcionnum,fecha1:fecha1,fecha2:fecha2,Ciclo_lectivo:Ciclo_lectivo,Mes:Mes,per_ID:per_ID},
	async: true,
	success: function(data3){
		$("#ContenidoTODO").html(data3);
		}
	});
		
	
		}
	});
					}//fin if
			});//fin del confirm//*/

}




function fac_Detalle(i){
	Fac_ID=$("#Fac_ID"+i).val();
$.ajax({
	url: 'cargarOpciones.php',
	type: "POST",
	cache: false,
	data: { opcion: "VerDetallesFactura",Fac_ID:Fac_ID},
	async: true,
	success: function(data2){
		//$("#facturas_Listar_Contenido").append(data2);
		//mostrarAlerta2(data2,'Detalle: Nota De Crédito',500,200);
		mostrarAlerta2(data2,"DETALLE DE LA FACTURA",600,700);
		}
	});
	return false;
	}
	
	function mostrarAlerta2(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: true, modal: true, 
			buttons: {
					'Aceptar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog
}//fin funcion

//Eze

$.fn.scrollView = function () {
  return this.each(function () {
    $('html, body').animate({
      scrollTop: $(this).offset().top
    }, 0);
  });
}

function Editar_Factura(i) {
  $("#editfac").show();
  Fac_ID=$("#Fac_ID"+i).val();
  Fac_FTi_ID=$("#Fac_FTi_ID"+i).val();

  $.ajax({
    url: 'cargarOpciones.php',
    type: "POST",
    cache: false,
    data: { opcion: "Editar_Factura", Fac_ID:Fac_ID, Fac_FTi_ID: Fac_FTi_ID},
    async: true,
    success: function(data2){
      $("#editfac").html(data2);
      $('#editfac').scrollView();
      }
  });
}

function Anular_Factura(i) {
  
  Fac_ID=$("#Fac_ID"+i).val();
  $.ajax({
    url: 'cargarOpciones.php',
    type: "POST",
    cache: false,
    data: { opcion: "VerDetallesFactura", Fac_ID:Fac_ID, NdeC: 1},
    async: true,
    success: function(data2){
      mostrarAlerta3(data2,"Nota de credito",600,500);
      }
  });
  return false;

}

function mostrarAlerta3(cuerpo, titulo, ancho, alto) {
  cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
  $("#dialog").html(cuerpo);
  $("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: true, modal: true, 
    buttons: {
          'Cancelar': function() {
            $(this).dialog('close');
          }
    }//fin buttons
  });//fin dialog
}//fin funcion

function fnExcelReport()
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('listadoTabla'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"Guardar como archivo.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}

//Eze
</script>

<div id="listado" class="page-break">

    <fieldset class="recuadro_simple" id="resultado_buscador">
        <legend>Resultado de la b&uacute;squeda</legend>    
        <br />
        <br />
        <div align="center" class="titulo_noticia">Listado de Recibos</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$importeTotal = 0;
		$importeTotalAnulados = 0;
//echo "NAHUEL";
switch ($opcionnum) {
case "numero":

$Fac_Numero = $_POST['Fac_Numero']; 
//echo $Fac_Numero;
//return false;
$naa = explode("-",$Fac_Numero);
$Fac_Sucursal=$naa[0];
$Fac_Numero=$naa[1];
//echo $Fac_Numero;
//echo $Fac_Sucursal;
//return false;
$sql_1 = "SELECT * FROM Factura
INNER JOIN FacturaTipo
      ON (Factura.Fac_FTi_ID = FacturaTipo.FTi_ID)
       INNER JOIN Usuario ON (Factura.Fac_Usu_ID = Usuario.Usu_ID) WHERE Fac_Numero='$Fac_Numero' and Fac_Sucursal='$Fac_Sucursal' ORDER BY Factura.Fac_ID";
break;

case "Fecha":
//echo "NAHUEL";
 
$fecha1 = cambiaf_a_mysql($_POST['fecha1']); 
//echo $fecha1."<br />";
$fecha2 = cambiaf_a_mysql($_POST['fecha2']); 
//echo $fecha2."<br />";

//return false;
$sql_1 = "SELECT * FROM
    Factura
    INNER JOIN Usuario 
        ON (Factura.Fac_Usu_ID = Usuario.Usu_ID)
    INNER JOIN CuotaPago 
        ON (CuotaPago.CuP_Fac_ID = Factura.Fac_ID)
    INNER JOIN FacturaTipo
      ON (Factura.Fac_FTi_ID = FacturaTipo.FTi_ID) WHERE Fac_Fecha<='$fecha2' AND Fac_Fecha>='$fecha1' GROUP BY Fac_ID ORDER BY Fac_ID"; 

$fecha1=cfecha($fecha1);
$fecha2=cfecha($fecha2);

break;

case "cicloLectivo":
//echo "NAHUEL";
//return false;


//$opcionnum = $_POST['opcionnum']; 
$Ciclo_lectivo = $_POST['Ciclo_lectivo']; 
$Mes = $_POST['Mes']; 
$sql_1 = "SELECT *
FROM
    Factura
    INNER JOIN Usuario 
        ON (Factura.Fac_Usu_ID = Usuario.Usu_ID)
    INNER JOIN CuotaPago 
        ON (CuotaPago.CuP_Fac_ID = Factura.Fac_ID)
    INNER JOIN FacturaTipo
      ON (Factura.Fac_FTi_ID = FacturaTipo.FTi_ID)
       WHERE CuP_Lec_ID='$Ciclo_lectivo' AND MONTH(Fac_Fecha)='$Mes' GROUP BY Fac_ID ORDER BY Fac_ID"; 
	//echo $sql_1;
break;

case "persona":
$per_ID = $_POST['per_ID']; 
//echo $per_ID;
//echo "NAHUEL";
$sql_1 = "SELECT * FROM
    Factura
    INNER JOIN Usuario 
        ON (Factura.Fac_Usu_ID = Usuario.Usu_ID)
    INNER JOIN CuotaPago 
        ON (CuotaPago.CuP_Fac_ID = Factura.Fac_ID)
    INNER JOIN FacturaTipo
      ON (Factura.Fac_FTi_ID = FacturaTipo.FTi_ID) WHERE
	CuP_Per_ID='$per_ID' GROUP BY Fac_ID ORDER BY Fac_ID"; 


//return false;
break;


}

//echo $sql_1;
//echo "NAHUEL".$per_ID."<br />";
//echo "opcion".$opcionnum;
?>
<input type="hidden" name="per_ID1" id="per_ID1" value="<?php echo $per_ID ?>" />


<input type="hidden" name="Fac_Numero" id="Fac_Numero" value="<?php echo $Fac_Numero ?>" />
<input type="hidden" name="fecha1" id="fecha1" value="<?php echo $fecha1 ?>" />
<input type="hidden" name="opcionnum" id="opcionnum" value="<?php echo $opcionnum ?>" />
<input type="hidden" name="fecha2" id="fecha2" value="<?php echo $fecha2 ?>" />

<input type="hidden" name="Ciclo_lectivo" id="Ciclo_lectivo" value="<?php echo $Ciclo_lectivo ?>" />
<input type="hidden" name="Mes" id="Mes" value="<?php echo $Mes ?>" />

<?php

        $result = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">#</th>
              <th align="center" class="fila_titulo">Tipo de Factura</th>
              <th align="center" class="fila_titulo">Nro. Factura</th>
              <th align="center" class="fila_titulo">Fecha/Hora</th>
              <!--<th align="center" class="fila_titulo">hora</th>-->
            
              <th align="center" class="fila_titulo">Persona</th>
         
              <th align="center" class="fila_titulo">Importe</th>
              <th align="center" class="fila_titulo">Estado</th>
              <th align="center" class="fila_titulo">Forma Pago</th>
                <th align="center" class="fila_titulo">Acción</th>
<!--              <th align="center" class="fila_titulo">Acci&oacute;n</th>-->
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = mysqli_fetch_array($result)) {
              $i++;
              
            ?>
            <tr style="background-color: gainsboro;" class="texto">
                <input type="hidden" name="Fac_ID" id="Fac_ID<?php echo $i; ?>" value="<?php echo $row[Fac_ID]; ?>" />
                <input type="hidden" name="Fac_FTi_ID" id="Fac_FTi_ID<?php echo $i; ?>" value="<?php echo $row[Fac_FTi_ID]; ?>" />
                <input type="hidden" name="Fac_Iva_ID" id="Fac_Iva_ID<?php echo $i; ?>" value="<?php echo $row[Fac_Iva_ID]; ?>" />
                <input type="hidden" name="Fac_CVe_ID" id="Fac_CVe_ID<?php echo $i; ?>" value="<?php echo $row[Fac_CVe_ID]; ?>" />
                <input type="hidden" name="Fac_Fecha" id="Fac_Fecha<?php echo $i; ?>" value="<?php echo $row[Fac_Fecha]; ?>" />
                <input type="hidden" name="Fac_Hora" id="Fac_Hora<?php echo $i; ?>" value="<?php echo $row[Fac_Hora]; ?>" />
                <input type="hidden" name="Fac_Usu_ID" id="Fac_Usu_ID<?php echo $i; ?>" value="<?php echo $row[Fac_Usu_ID]; ?>" />
                <input type="hidden" name="Fac_CUIT" id="Fac_CUIT<?php echo $i; ?>" value="<?php echo $row[Fac_CUIT]; ?>" />
                <input type="hidden" name="Fac_Sucursal" id="Fac_Sucursal<?php echo $i; ?>" value="<?php echo $row[Fac_Sucursal]; ?>" />
                <input type="hidden" name="Fac_FTi_ID" id="Fac_Numero<?php echo $i; ?>" value="<?php echo $row[Fac_Numero]; ?>" />
                <input type="hidden" name="Fac_PersonaNombre" id="Fac_PersonaNombre<?php echo $i; ?>" value="<?php echo $row[Fac_PersonaNombre]; ?>" />
                <input type="hidden" name="Fac_PersonaDomicilio" id="Fac_PersonaDomicilio<?php echo $i; ?>" value="<?php echo $row[Fac_PersonaDomicilio]; ?>" />
                <input type="hidden" name="Fac_ImporteTotal" id="Fac_ImporteTotal<?php echo $i; ?>" value="<?php echo $row[Fac_ImporteTotal]; ?>" />
                <input type="hidden" name="Fac_Pagada" id="Fac_Pagada<?php echo $i; ?>" value="<?php echo $row[Fac_Pagada]; ?>" />
                <input type="hidden" name="Fac_Anulada" id="Fac_Anulada<?php echo $i; ?>" value="<?php echo $row[Fac_Anulada]; ?>" />
              <td align="center"><?php echo $row[Fac_ID]; ?></td>
              <td align="center"><?php echo $row[FTi_Nombre]; ?></td>
              <td align="center"><?php echo $row[Fac_Sucursal]."-".$row[Fac_Numero]; ?></td>
              <td align="center"><?php echo cfecha($row[Fac_Fecha])." ".$row[Fac_Hora]; ?></td>             
              <td align="center"><?php echo $row[Fac_PersonaNombre]; ?></td>
           
              <td align="center">$<?php echo number_format($row[Fac_ImporteTotal],2,",","."); ?></td>
              <?php
              $pagada = $row[Fac_Pagada];
              $anulada = $row[Fac_Anulada];
              if(( $pagada == 0)&&( $anulada == 0)){
                ?><td align="center">Generada</td><?php  
              }else if(( $pagada == 1)&&( $anulada == 0)){
                ?><td align="center">Pagada</td><?php  
              }if(( $pagada == 0)&&( $anulada == 1)){
                ?><td align="center">Anulada</td> <?php
              }
			  
			  $importeTotal += $row[Fac_ImporteTotal];
			  if ($row[Fac_Anulada]==1)
			  	$importeTotalAnulados += $row[Fac_ImporteTotal];
			  
              ?>
              <td align="center"><?php echo buscarFormaPagoFactura($row[Fac_ID], true); ?></td>
                <td align="center">

                
                <a href="#" onclick="fac_Detalle('<?php echo $i ?>')" id="botEditar<?php echo $i;?>"><img src="imagenes/magnifier.png" alt="Detalle Factura" title="Detalle Factura" width="32" height="32" border="0" /></a>
                <?php if ($pagada==1){ ?>
                <a href="#" onclick="Anular_Factura('<?php echo $i ?>')" id="botEditar<?php echo $i;?>"><img src="imagenes/table_delete.png" alt="Anular Factura y Hacer Nota de Crédito" title="Anular Factura y Hacer Nota de Crédito" width="32" height="32" border="0" /></a>

                <a href="#" onclick="Editar_Factura('<?php echo $i ?>')" id="botEditar<?php echo $i;?>"><img src="imagenes/tag_pink.png" alt="Editar Factura" title="Editar Factura" width="32" height="32" border="0" /></a>
                <?php } ?>
                
                
                </td>
              
              
              
<!--              <td align="center">
                  <a href="#" id="botEditar<?php echo $i; ?>"> <img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /> </a> 
                  <a href="#" id="botBorrar<?php echo $i; ?>"> <img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /> </a> 
              </td>
-->
              </tr>
            
            <?php
          
            mostrarNotasdeCredito($i, $row);
            
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
     <br />
<br />

  <fieldset class="recuadro_inferior" style="height:32px">
<div align="left" class="texto">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <a href="#" id="barraExportar2" onclick="fnExcelReport();"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> Total (incluye anulados): $<?php echo $importeTotal;?> - Total anulados: $<?php echo $importeTotalAnulados;?> - Facturación total (sin anulados): $<?php $resta = $importeTotal - $importeTotalAnulados; echo $resta;?></div>
<br /><br /></fieldset><br />
<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<br />
<div id="editfac">  </div>
<div id="probar"></div>
<button id="volver" <?php if(isset($_POST['opcionvol'])) { ?> style="visibility: hidden" <?php } ?> >Volver</button>
    </fieldset>
</div>