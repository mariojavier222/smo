<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$SedID = $_POST['SedID'];



	$sql = "SELECT
    Legajo.Leg_Numero
    , Colegio_Inscripcion.Ins_Fecha
    , Persona.Per_DNI
	, PersonaDocumento.Doc_Nombre
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Per_ID
    , Colegio_Inscripcion.Ins_Lec_ID
    , Colegio_Inscripcion.Ins_Cur_ID
    , Colegio_Inscripcion.Ins_Niv_ID
    , Colegio_Inscripcion.Ins_Div_ID
	, Niv_Nombre
	, Cur_Nombre
	, Div_Nombre
FROM
   Colegio_Inscripcion 
       INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
   INNER JOIN PersonaDocumento 
        ON (Persona.Per_Doc_ID = PersonaDocumento.Doc_ID)
    INNER JOIN Persona
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID";
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Persona.Per_Sexo DESC, Apellido, Nombre;";

/*$sql = "SELECT
    Legajo.Leg_Numero
    , Colegio_Inscripcion.Ins_Fecha
    , Persona2.Per_DNI AS DNI
    , Persona2.Per_Apellido AS Apellido
    , Persona2.Per_Nombre AS Nombre
    , Colegio_Inscripcion.Ins_Lec_ID
    , Colegio_Inscripcion.Ins_Cur_ID
    , Colegio_Inscripcion.Ins_Niv_ID
    , Colegio_Inscripcion.Ins_Div_ID
	, Niv_Nombre
	, Cur_Nombre
	, Div_Nombre
FROM
 Colegio_Inscripcion 
	INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona AS Persona2
        ON (Legajo.Leg_Per_ID = Persona2.Per_ID)
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID";
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Persona2.Per_Sexo, Apellido, Nombre;";//*/

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
			"aaSorting": [[ 3, "asc" ]],
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
          <th colspan="8" class="fila_titulo"></th>
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
