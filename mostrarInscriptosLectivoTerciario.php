<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	$TitID = $_POST['TitID'];
	$SedID = $_POST['SedID'];
	obtenerCarreraPlan($TitID, $CarID, $PlaID);



	$sql = "SELECT
    Legajo.Leg_Numero
    , Inscripcion.Ins_Fecha
    , InscripcionAsegurado.Ase_Contrato
    , Persona.Per_DNI
	, Persona2.Per_ID AS PerID
    , PersonaDocumento.Doc_Nombre
    , Persona2.Per_DNI AS DNI
    , Persona2.Per_Apellido AS Apellido
    , Persona2.Per_Nombre AS Nombre
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Inscripcion.Ins_Lec_ID
	, Car_Nombre
FROM
    InscripcionAsegurado
    INNER JOIN Inscripcion 
        ON (InscripcionAsegurado.Ase_Leg_ID = Ins_Leg_ID) AND (Ase_Lec_ID = Ins_Lec_ID)
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Ase_Tutor_Per_ID = Persona.Per_ID)
    INNER JOIN PersonaDocumento 
        ON (Persona.Per_Doc_ID = PersonaDocumento.Doc_ID)
    INNER JOIN Persona AS Persona2
        ON (Legajo.Leg_Per_ID = Persona2.Per_ID)
	INNER JOIN Carrera 
        ON (Inscripcion.Ins_Car_ID = Carrera.Car_ID)
WHERE (Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID";
    if ($TitID!=999999) $sql.=" AND Ins_Car_ID =$CarID AND Ins_Pla_ID =$PlaID";
	$sql.=") ;";



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
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Inscriptos y Asegurados para el Ciclo Lectivo ' + vLectivo
		,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
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
			"aaSorting": [[ 1, "asc" ], [ 2, "asc" ]],
			//"sPaginationType": "full_numbers",
			//"bJQueryUI": true,
			//"sDom": '<"toolbar">frtip',
			"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true } );//*/
	//$("div.toolbar").html('Custom tool bar! Text/images etc.');


	/*$('#listadoTabla').dataTable( {
					"aaSorting": [[ 4, "desc" ]]
				} );//*/


});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
<div id="listado" >	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Leg.</th>
          <th align="center" class="fila_titulo">Carrera</th>
          <th align="center" class="fila_titulo">Alumno</th>
          <th align="center" class="fila_titulo">DNI </th>
          <th align="center" class="fila_titulo">Fecha Ins.</th>
          <th align="center" class="fila_titulo">Fecha Nac.</th>
          <th align="center" class="fila_titulo">Asegurado</th>
          <th align="center" class="fila_titulo">Documento</th>
          <th align="center" class="fila_titulo"><div align="left">Contr.</div></th>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		$div = $row[Div_Nombre];
		$cur = $row[Cur_Nombre];
		$niv = $row[Niv_Nombre];
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		//if ($i==10) echo "<p style='page-break-before:always'></p>";
		
	?>
	<tr class="gradeA<?php //echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      <td align="center"><?php echo $row[Leg_Numero];?></td>
      <td><?php echo $row[Car_Nombre];?></td>
      <td><?php echo "$row[Apellido], $row[Nombre]";?></td>
      
      <td><?php echo $row[DNI];?></td>
      <td><?php echo cfecha($row[Ins_Fecha]);?></td>
      <td><?php
      obtenerEdad($row[PerID], $fechaNac);
	  echo $fechaNac;
	  ?></td>
      <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
      <td><?php echo "$row[Doc_Nombre]:$row[Per_DNI]";?></td>
      <td align="center"><?php echo $row[Ase_Contrato]; ?></td>
    </tr>
    
		  <?php		  
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="9" class="fila_titulo"></th>
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
