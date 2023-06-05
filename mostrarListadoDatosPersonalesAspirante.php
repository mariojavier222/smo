<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	
	$Nombre = $_POST['Nombre'];
	$DNI = $_POST['DNI'];
	$Sexo = $_POST['Sexo'];
	$FechaNac = $_POST['FechaNac'];
	$DatNac = $_POST['DatNac'];
	$Dom = $_POST['Dom'];
	$Correo = $_POST['Correo'];
	$Tel = $_POST['Tel'];
	

	$sql = "SELECT * FROM personadatos INNER JOIN persona ON (personadatos.Dat_Per_ID = persona.Per_ID);";


$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>

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
			"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true } );//*/
                    
       $("a[id^='botonEditar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(11,12);
            
		
            $("#Sic_ID").val($("#SicID" + i).val());
            // $("#NivID").val($("#NivID" + i).val());
            // $("#LecID").val($("#LecID" + i).val());
            $("#Sic_Nombre").val($("#Sic_Nombre" + i).val());
            $("#Sic_DNI").val($("#Sic_DNI" + i).val());
            $("#Sic_Tel").val($("#Sic_Tel" + i).val());
            //$("#FechaHasta").val($("#FechaHasta" + i).val());
            $("#mostrarNuevo").fadeIn();
            $("#mostrar").empty();		
            $("#divBuscador").fadeOut();
		
        });//fin evento click//*/             
	});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
    <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" class="page-break">	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>		  
          <?php if ($Nombre){?><th align="center" class="fila_titulo">Alumno</th><?php }?>
          <?php if ($DNI){?><th align="center" class="fila_titulo">DNI</th><?php }?>
          <?php if ($Sexo){?><th align="center" class="fila_titulo">Sexo </th><?php }?>
          <?php if ($FechaNac){?><th align="center" class="fila_titulo">Fecha Nac.</th><?php }?>
          <?php if ($Dom){?><th align="center" class="fila_titulo">Domicilio</th><?php }?>
          <?php if ($Correo){?><th align="center" class="fila_titulo">Email</th><?php }?>
          <?php if ($Tel){?><th align="center" class="fila_titulo">Tel.</th><?php }?>
          <th align="center" class="fila_titulo">Acción</th>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	while ($row = mysqli_fetch_array($result)){
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
                $sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = $row[Per_ID]";
		$resultDatos = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($resultDatos)>0){
			
	?>
	<tr class="gradeA" id="fila<?php echo $i;?>" height="40px">
	  
      <?php if ($Nombre){?><td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?>
      
      </td><?php }?>
      <?php if ($DNI){?><td align="center"><?php echo $row[Per_DNI];?></td><?php }?>
      <?php if ($Sexo){?><td><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td><?php }?>      
      <?php if ($FechaNac){?><td align="center"><?php echo cfecha($row[Dat_Nacimiento]); ?></td><?php }?>
      <?php if ($Dom){?><td align="center"><?php if (!empty($row[Dat_Domicilio])) echo $row[Dat_Domicilio]; else echo "No está cargado";?></td><?php }?>
      <?php if ($Tel){?><td align="center"><?php if (!empty($row[Dat_Telefono])) echo $row[Dat_Telefono]; else echo "No está cargado";?></td><?php }?>
      <td align="center"><a href="#" id="botEditar<?php echo $i; ?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $row[Sic_ID]; ?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
    </tr>    
		  <?php
		}else{//entra por aca cuando no existen datos cargados en Otros datos.
		?>
	<tr class="gradeA" id="fila<?php echo $i;?>" height="40px">
      
	 
      <?php if ($Nombre){?><td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>      <?php }?>
      <?php if ($DNI){?><td align="center"><?php echo $row[Per_DNI];?></td><?php }?>
      <?php if ($Sexo){?><td><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td><?php }?>
      <?php if ($FechaNac){?><td align="center">---</td><?php }?>
      <?php if ($Dom){?><td align="center">---</td><?php }?>     
      <?php if ($Correo){?><td align="center">---</td><?php }?>
      <?php if ($Tel){?><td align="center">---</td><?php }?>
      <td align="center"><a href="#" id="botonEditar<?php echo $i; ?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $row[Sic_ID]; ?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
     
      
    </tr>    
		  <?php	
		}//fin de que encontr� otros datos en la b�squeda
		
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="12" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total alumnos inscriptos";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron alumnos inscriptos.</span>
<?php
}

?>
