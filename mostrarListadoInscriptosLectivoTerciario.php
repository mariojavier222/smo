<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$TitID = $_POST['TitID'];
	$MatID = $_POST['MatID'];
	$DivID = $_POST['DivID'];
	$SedID = $_POST['SedID'];
	$mostrarDeuda = $_POST['mostrarDeuda'];

	obtenerCarreraPlan($TitID, $CarID, $PlaID);

	$sql = "SELECT * FROM
    Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
	 INNER JOIN Carrera 
        ON (Inscripcion.Ins_Car_ID = Carrera.Car_ID)
    INNER JOIN Plan 
        ON (Inscripcion.Ins_Pla_ID = Plan.Pla_ID)
WHERE (Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID";
	if ($TitID!=999999) $sql.=" AND Ins_Car_ID = $CarID AND Ins_Pla_ID = $PlaID";    
	$sql.=");";// ORDER BY Niv_ID, Cur_ID, Div_ID, Persona2.Per_Sexo, Apellido, Nombre;";


$result = consulta_mysql($sql);
$total = mysqli_num_rows($result);

?>
<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>

<script language="javascript">
$(document).ready(function(){

	
	 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();

		vLectivo = $("#LecID option:selected").text();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Inscriptos para el Ciclo Lectivo ' + vLectivo
		,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
										});
//		$("div#listado").printArea(options);
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
			"aaSorting": [[ 2, "asc" ], [ 1, "asc" ]],
			"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true } );//*/


});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda <?php echo $SedID;?></legend>
<div id="listado" class="page-break">	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Leg.</th>
          <th align="center" class="fila_titulo">Alumno</th>
          <th align="center" class="fila_titulo">Sexo </th>
          <th align="center" class="fila_titulo">T&iacute;tulo</th>
          <?php //if ($MatID==999999){    ?>
          	<th align="center" class="fila_titulo">Materia</th>
          <?php //}
          ?>
          <th align="center" class="fila_titulo">Curso</th>
          <th align="center" class="fila_titulo">Div</th>
           <?php if ($mostrarDeuda=="true"){?>
	              <th align="center" class="fila_titulo">Deuda</th>
              <?php }?>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		//if ($i==10) echo "<p style='page-break-before:always'></p>";
		$DNI = $row[Per_DNI];
		if ($mostrarDeuda=="true"){
			$Deuda = Obtener_Deuda_siucc($DNI);
			if ($Deuda>0){
				$continuar = true;
			}else{
				$continuar = false;
			}
		}else {
			$continuar = true;
		}

		if ($continuar){
			$sql = "SELECT DISTINCTROW Mat_Nombre, Cur_Siglas, Div_Siglas FROM
		InscripcionMateria
		INNER JOIN Materia 
			ON (InscripcionMateria.IMa_Mat_ID = Materia.Mat_ID) AND (InscripcionMateria.IMa_Car_ID = Materia.Mat_Car_ID)
		INNER JOIN Curso 
			ON (Materia.Mat_Cur_ID = Curso.Cur_ID)
		INNER JOIN Division 
			ON (InscripcionMateria.IMa_Div_ID = Division.Div_ID) WHERE IMa_Lec_ID = $LecID AND IMa_Leg_ID = $row[Leg_ID]";
			if ($TitID!=999999) $sql.=" AND IMa_Car_ID = $CarID AND IMa_Pla_ID = $PlaID";		
			if ($MatID!=999999) {
				$sql.=" AND Mat_ID = $MatID ";
			}else{
				if ($CurID!=999999) $sql.=" AND Cur_ID = $CurID ";
				if ($DivID!=999999) $sql.=" AND IMa_Div_ID = $DivID";
			}
			$sqlMostrar = $sql;
			$resultDetalle = consulta_mysql($sql);
			if (mysqli_num_rows($resultDetalle)>0){
				while ($rowDet = mysqli_fetch_array($resultDetalle)){
				?>
				<tr class="gradeA" id="fila<?php echo $i;?>" height="40px">
				  <td align="center"><?php echo $row[Leg_Numero];?></td>
				  <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
				  
				  <td><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td>
				  <td><?php echo "($row[Pla_Nombre]) $row[Car_Nombre]";?></td>
				  <?php //if ($MatID==999999){    ?>
					  <td><?php echo $rowDet[Mat_Nombre];?></td>
				  <?php //}?>
				  <td><?php echo $rowDet[Cur_Siglas];?></td>
				  <td><?php echo $rowDet[Div_Siglas];?></td>
				  <?php if ($mostrarDeuda=="true"){?>
					  <td align="center"><?php echo "$".$Deuda; ?></td>
				  <?php }?>
				  </tr>
		
			  <?php	
				}//fin if continuar
			}//fin while
		}/*else if ($CurID==999999 && $MatID!=999999){
		
	?>
        <tr class="gradeA" id="fila<?php echo $i;?>" height="40px">
          <td align="center"><?php echo $row[Leg_Numero];?></td>
          <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
          
          <td><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td>
          <td><?php echo "($row[Pla_Nombre]) $row[Car_Nombre]";?></td>
          <?php if ($MatID==999999){    ?>
	              <td>----</td>
              <?php }?>
          
          <td>----</td>
          <td>----</td>
          </tr>
    
		  <?php	

		}//*/
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="6" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>
<?php //echo "$sqlMostrar<br />";?>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total alumnos inscriptos al ciclo lectivo";?></div>
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
