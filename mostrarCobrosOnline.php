<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
$NumCaja = $_POST['numerocaja'];
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

$(".botones").button();

$("#barraProcesar").click(function(evento){
    evento.preventDefault();
    vCajaID = <?php echo $NumCaja;?>;
    $.ajax({
        type: "POST",
        cache: false,
        async: false,
        error: function (XMLHttpRequest, textStatus){
            alert(textStatus);},
        data: {opcion: "procesarCobrosOnline", CajaID: vCajaID},
        url: 'cargarOpcionesPagosOnline.php',
        success: function(data){                        
            if (data){
                mostrarAlerta(data);                          
            }                       
            
        }
    });//fin ajax//*/
                                     
        
    });

</script>
<div id="listado">	
<br />
<br />

        <div align="center" class="titulo_noticia">Listado de cobros recibidos sin procesar</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        
		//echo $NumCaja;
		$importeTotal = 0;
	 $sql_2 = "SELECT * FROM CuotaPersona
    INNER JOIN CuotaTipo 
        ON (CuotaPersona.Cuo_CTi_ID = CuotaTipo.CTi_ID)
    INNER JOIN Persona 
        ON (CuotaPersona.Cuo_Per_ID = Persona.Per_ID)
    INNER JOIN Lectivo 
        ON (CuotaPersona.Cuo_Lec_ID = Lectivo.Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (CuotaPersona.Cuo_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN CuotaBeneficio 
        ON (CuotaPersona.Cuo_Ben_ID = CuotaBeneficio.Ben_ID) 
    INNER JOIN CuotaUnica 
        ON (CuotaPersona.Cuo_Numero = CuotaUnica.Rec_Numero) AND (CuotaPersona.Cuo_Per_ID = CuotaUnica.Rec_Per_ID) AND (CuotaPersona.Cuo_Niv_ID = CuotaUnica.Rec_Niv_ID) AND (CuotaPersona.Cuo_CTi_ID = CuotaUnica.Rec_CTi_ID) AND (CuotaPersona.Cuo_Lec_ID = CuotaUnica.Rec_Lec_ID) AND (CuotaPersona.Cuo_Alt_ID = CuotaUnica.Rec_Alt_ID)
    WHERE Cuo_MarcadaOnline=1 AND Cuo_Procesada = 0 GROUP BY Rec_ID,Rec_Lec_ID, Rec_Per_ID, Rec_Niv_ID, Rec_CTi_ID, Rec_Alt_ID, Rec_Numero ORDER BY Cuo_FechaMarcada DESC, Cuo_HoraMarcada DESC;";
        $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
        //echo $sql_2;

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="1000px" border="1" id="listadoTabla" class="texto" style="border-collapse:collapse;">
          <thead>
            <tr class="fila_titulo" id="fila" style="height:30px;">
               <th>#</th>
               <th>DNI</th>
               <th>Apellido y Nombre</th>
               <th>Fecha</th>
               <th>Hora</th>
               <th>Importe</th>
               <th>Medio pago</th>
               <th>Concepto</th>
               <th>Detalle</th>
            </tr>
          </thead>
          <tbody>
            <?php
			
            $i=0;
            while ($row = mysqli_fetch_array($result)) {
                $i++;   
                $importeTotal += $row['Cuo_ImporteMarcada'];           
?>
                <tr style="height:30px;">
                 <td align="center"><?php echo $i; ?><input type="hidden" id="Rec_ID<?php echo $i;?>" value="<?php echo $row['Rec_ID'];?>" /></td>
                 <td align="center"><?php echo $row['Per_DNI']; ?></td>
                 <td><?php echo $row['Per_Apellido'].', '.$row['Per_Nombre']; ?></td>
                 <td align="center"><?php echo cfecha($row['Cuo_FechaMarcada']);?></td>         
                 <td align="center"><?php echo substr($row['Cuo_HoraMarcada'],0,5).' hs.'; ?></td>         
                 <td align="right"><?php echo "$".number_format($row['Cuo_ImporteMarcada'],2,",","."); ?></td>
                 <td align="center"><?php echo $row['Cuo_MedioPago'];?></td>
                 <td align="center"><?php echo $row['CTi_Nombre'];?></td>
                 <td align="center"><?php echo $gMes[$row['Cuo_Mes']].'/'.$row['Cuo_Anio'];?></td>
        
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
<div align="left" style="font-size:16px">
    <button class="botones" id="barraProcesar">
        Procesar cobros online</button>
<a href="imprimirCajaCorrienteRecibos.php?id=<?php echo $NumCaja;?>" target="_blank"><img src="imagenes/printer.png" alt="Imprimir el listado en PDF" title="Imprimir el listado en PDF" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
<!--<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" b
    order="0" align="absmiddle" /></a>   - <a href="#" id="Imprimirpopup"><img src="imagenes/printer.png" alt="Imprimir 2" title="Imprimir 2" width="32" border="0" align="absmiddle" /></a>--> Total importe: $<?php echo number_format($importeTotal,0,",",".");?></div>
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