<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	
	//$ProID = $_POST['ProID'];

/*    INNER JOIN siucc.Curso 
        ON (Chequera_Serie.ChS_Cur_ID = Curso.Cur_ID)//*/
	//if ($NivID==999999){
		
	//*/// AND Persona.Per_DNI = Chequera_Serie.ChS_Per_ID ";
		//if ($ProID!=999999) $sql.=" AND Pro_ID = $ProID";
//		$sql.= " AND ChS_Fac_ID = 9) GROUP BY TCh_Nombre, Cur_ID, Cur_Literal, Pro_Nombre;";		
	//}else{
//		$total = 1;	
//	}//fin if
		
	
//echo "SQL: $sql";exit;



?>

	<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="js/ui.jqgrid.css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-sp.js" type="text/javascript"></script>
	<script type="text/javascript">
	$.jgrid.no_legacy_api = true;
	$.jgrid.useJSON = true;
	</script>

<script language="javascript">
$(document).ready(function(){

	
 	$(".botones").button();
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Accesos a GITeCo',overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
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
                    $('#listadoTabla22').dataTable( {
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

<link href="css/general.css" rel="stylesheet" type="text/css" />


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" >	
<br />
<br />
<?php

$sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $fechaDesde = $_POST['fechaDesde'];
	$fechaHasta = $_POST['fechaHasta'];
	 
$sql = "SELECT MONTH(CuP_Fecha) AS Mes, Caja_Usu_ID, Usu_Persona,  SUM(CuP_Importe) AS Total FROM Caja
    INNER JOIN CuotaPago 
        ON (Caja.Caja_ID = CuotaPago.CuP_Caja_ID)
    INNER JOIN CuotaPersona 
        ON (CuotaPago.CuP_Numero = CuotaPersona.Cuo_Numero) AND(CuotaPago.CuP_Per_ID = CuotaPersona.Cuo_Per_ID) AND (CuotaPago.CuP_Niv_ID = CuotaPersona.Cuo_Niv_ID) AND (CuotaPago.CuP_Lec_ID = CuotaPersona.Cuo_Lec_ID) AND (CuotaPago.CuP_CTi_ID = CuotaPersona.Cuo_CTi_ID) AND (CuotaPago.CuP_Alt_ID = CuotaPersona.Cuo_Alt_ID)  
	INNER JOIN Usuario 
        ON (Caja_Usu_ID = Usu_ID)  
	WHERE CuP_Anulada = 0 AND (CuP_Fecha >= '".cambiaf_a_mysql($fechaDesde)."' AND CuP_Fecha<='".cambiaf_a_mysql($fechaHasta)."')
	GROUP BY  MONTH(CuP_Fecha), Caja_Usu_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
?>
 
 <table width="100px" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Usuario</th>
          <th align="center" class="fila_titulo">Mes</th>
          <th align="center" class="fila_titulo">Total ($)</th>
        </tr>
       </thead>
       <tbody>
	   <?php 
	   
	   //global $gMes;
		while ($row = mysqli_fetch_array($result)){		
			
				$i++;
				$acum = $acum + $row[Total];
			
		?>
				<tr class="gradeA" height="25px">
				  <td><?php echo $row[Usu_Persona];?></td>
				  <td><?php echo $gMes[$row[Mes]];?></td>
                  <td><?php echo $row[Total];?></td>
				  </tr>
		
			  <?php		  
              
		}//fin del while 
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="5" class="fila_titulo">IMPORTE TOTAL: $<?php echo $acum;?></th>
          
        </tr>
        </tfoot>
        <tr></tr>
</table>
</div><?php
//echo $sql;exit;
?>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a><a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a></div>  
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>

