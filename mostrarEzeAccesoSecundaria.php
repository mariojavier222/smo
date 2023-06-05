<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);.
	if (isset($_POST['fechaDesde'])){
		$fechaDesde = $_POST['fechaDesde'];
		$fechaHasta = $_POST['fechaHasta'];
		$where = "(EMo_Fecha >= '".cambiaf_a_mysql($fechaDesde)."' AND EMo_Fecha<='".cambiaf_a_mysql($fechaHasta)."')";
	}
	if (isset($_POST['EmpID'])){
		$EmpID = $_POST['EmpID'];
		$where = "(Emp_ID = $EmpID)";
	}
	

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT DISTINCTROW Emp_ID, Empleado.*, Persona.*, EmpleadoMovimiento.*, EmpleadoTipo.* FROM
    Empleado
    INNER JOIN Persona 
        ON (Emp_Per_ID = Per_ID)
    INNER JOIN EmpleadoMovimiento 
        ON (Emp_ID = EMo_Emp_ID)
    INNER JOIN EmpleadoTipo 
        ON (Emp_ETi_ID = ETi_ID)
    INNER JOIN Colegio_Docente 
        ON (Per_ID = Doc_Per_ID)
	INNER JOIN Colegio_Clase
        ON (Cla_Doc_ID = Doc_ID)
    WHERE $where AND Emp_ETi_ID =1
    AND Cla_Niv_ID = 2
    ORDER BY EMo_ID;";
	
//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
<head>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<script language="javascript">
$(document).ready(function(){

	$("a[id^='verFotoAlumno']").click(function(evento){
            evento.preventDefault();
			fDNI = this.id.substr(13,15);
			//alert(fDNI);
			data = "<img src='fotos/grande/"+fDNI+".jpg' title='Foto' width='500' border='1' align='absmiddle' class='foto'/>";	  
			$("#dialog").html(data);
			$("#dialog").dialog({ draggable: true, hide: 'slide', title: 'Foto del Alumno', zIndex: 3900, resizable: true, modal: true, height: 'auto', width: 520, 
				buttons: {
					'Aceptar': function() {
						$(this).dialog('close');
					}
				}//fin buttons
			});//fin dialog          
            //alert(vDNI);
            
        });
 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Control de Asitencia',overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
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
			"aaSorting": [[ 0, "asc" ]],
			"aoColumnDefs": [
						{ "bVisible": false, "aTargets": [ 0 ] }
					],//*/
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true } );//*/
	
	$('#listadoTabla').dataTable( {
			"bPaginate": true,			
			/*"aoColumnDefs": [ 

						{ "bVisible": false, "aTargets": [ 0 ] }
					],//*/
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
			/*"bLengthChange": true,*/
			"bFilter": true
			/*"bSort": true,*/
			/*"bInfo": true */
			} );//*/


	$("a[id^='lupa']").click(function(evento){
		evento.preventDefault();
		//vID = this.id;
		var vID = this.id.substr(4,10);

		vEMoID = $("#acceso" + vID).val();	
		//alert(vLogID);
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "obtenerCuadroAutoriza", EMoID: vEMoID},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(vLogID);
					vCuadro = "<div class = 'cuadroPopup'><h2>AUTORIZAR SALIDA</h2>" + data + "</div>";
					//$("#detalle").html(vCuadro);
					//$("#detalle").bPopup();
					jAlert(vCuadro,"Autorizar Salida")
					//$("#detalle" + vLogID).hide();
					$("#cargando").hide();
				}
		});//fin ajax//*/
	 });//fin evento click//*/


});//fin de la funcion ready
</script>
</head>
<body>
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" >	
<br />
<br />

 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">ID</th>
          <th align="center" class="fila_titulo">FOTO</th>
          <th align="center" class="fila_titulo">Fecha</th>
          <th align="center" class="fila_titulo">Hora</th>
          <th align="center" class="fila_titulo">D.N.I. </th>
          <th align="center" class="fila_titulo">Persona</th>
          <th align="center" class="fila_titulo">Estado</th>
          <th align="center" class="fila_titulo">Cargo</th> 
          <th align="center" class="fila_titulo">Acción</th>          
        </tr>
       </thead>
            <tfoot>
        <tr>
          <th colspan="8" class="fila_titulo"></th>
        </tr>
        </tfoot>

       <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;

		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";

		$foto = buscarFoto($row['Per_DNI'], $row['Per_Doc_ID'], 60);
	  
		
		
	?>
	<tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>" height="40px" title="<?php echo $row[EMo_IP];?>">
      <td><?php echo $row[EMo_ID];?></td>
      <td><?php echo $foto;?></td>
      <td align="center"><?php echo cfecha($row[EMo_Fecha]);?></td>
      <td><?php echo $row[EMo_Hora];?></td>      
      <td><?php echo $row[Per_DNI];?></td>
      <td><?php echo $row[Per_Apellido].", ".$row[Per_Nombre];?></td>      
      <td align="center"><?php if ($row[EMo_Estado]=="E") echo "<span class='cuota_pagada'>ENTRADA</span>";else echo "<span class='vencida_roja'>SALIDA</span>";  		
			?></td>
      <td><?php echo $row[ETi_Nombre];?></td>
      <td align="center">
      <a href="#" id="lupa<?php echo $i;?>">
      <img src="imagenes/ins_definitiva.png" width="32" height="32" border="0" /></a>
      <input name="acceso<?php echo $i;?>" type="hidden" id="acceso<?php echo $i;?>" value="<?php echo $row[EMo_ID];?>" />
      
      </td>
      </tr>
    
		  <?php		  
	}//fin del while
	?>  
</tbody>
</table>

</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total registros";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<div class="borde_alerta" id="detalle"></div>
 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron datos relacionados con la b&uacute;squeda.</span></div>
<?php
}

?>
</body>