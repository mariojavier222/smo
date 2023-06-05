<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	$LecNombre = Obtener_LectivoNombre($LecID+1);
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$SedID = $_POST['SedID'];
	$textoAlDia = $_POST['textoAlDia'];
	$textoDebe = $_POST['textoDebe'];
	$cantGrilla = $_POST['cantGrilla'];
	$tituloPagina = $_POST['tituloPagina'];
	$tituloCursoDivision = $_POST['tituloCursoDivision'];
	$Turno = $_POST['Turno'];
	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT
	Leg_Numero
	, Leg_ID
    , Persona.Per_ID
	, Leg_ID
	, Niv_ID
	, Persona.Per_DNI
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Persona.Per_Sexo
    , Colegio_Nivel.Niv_Nombre
    , Curso.Cur_Siglas
    , Division.Div_Siglas
	, Niv_Siglas
    , Colegio_Inscripcion.Ins_Provisoria
    , Colegio_Inscripcion.Ins_Lec_ID
FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID  AND Leg_Sed_ID = $SedID AND Per_Baja = 0 AND Leg_Baja = 0  ";
	if ($Turno!=999999) $sql.=" AND Cur_Turno = '$Turno' ";
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Persona.Per_Sexo DESC, Per_Apellido, Per_Nombre;";


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
		//$("div#mostrar").printArea();
		vLectivo = $("#LecID option:selected").text();
		$("#listado").printElement({leaveOpen:true, printMode:'popup', pageTitle:'<?php echo $tituloPagina;?> ' + vLectivo
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

	$('#listadoTabla2').dataTable( {
			"bPaginate": true,
			//"aaSorting": [[ 2, "desc" ], [ 1, "asc" ]],
			//"sPaginationType": "full_numbers",
			//"bJQueryUI": true,
			//"sDom": '<"toolbar">frtip',
			//"sDom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
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
function buscarCuotasImpagas2(PerID)
{
	$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {PerID: PerID},
			url: 'buscarCuotasImpagas.php',
			success: function(data){ 
				 mostrarAlerta2(data,"DETALLE DE LA CUOTA",900,900);
				 $("#mostrarDatosOpcionesBorrar").hide();
				 $(".borrarTD").hide();
				 
			}
		});//fin ajax//*/
}

function mostrarAlerta2(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {
					'Cerrar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.pepe {
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-right-style: solid;
	border-bottom-style: solid;
	border-right-color: #000;
	border-bottom-color: #000;
}
</style>
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" class="page-break">	
<br />
<?php echo $tituloCursoDivision;?>

 
 <table width="100%" border="0" cellspacing="0" class="display" id="listadoTabla">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">&nbsp;</th>
          <th align="center" class="fila_titulo">#</th>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="left" class="fila_titulo">Alumno/a</th>        
          <th align="center" class="fila_titulo">¿Se incribió al Ciclo <?php echo $LecNombre;?>?</th>
         
         
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
		
		//if ($i==10) echo "<p style='page-break-before:always'></p>";
		$PerID = $row[Per_ID];
		/*$Deuda = Obtener_Deuda_Sistema($PerID);
		if ($Deuda>0){
			$TotalDeuda += $Deuda;
			$textoDeuda = $textoDebe;//"DEBE";
		}else{
			$textoDeuda = $textoAlDia;//"AL DIA";
		}*/
		
	$claseFila = "border-bottom-width: 1px;	border-bottom-style: solid;	border-bottom-color: #000 ;";
	$claseGrilla = "border-right-width: 1px;	border-bottom-width: 1px;	border-right-style: solid;	border-bottom-style: solid;	border-right-color: #000;	border-bottom-color: #000;";
	?>
	<tr id="fila<?php echo $i;?>" >
	  <td align="center">
      <?php
      $esNuevo = false;
	  $esNuevo = buscarAlumnoNuevo($row[Per_ID], $row[Niv_ID]);
	  if ($esNuevo) echo "N";
	  ?>
	  </td>
	  <td style="<?php echo $claseFila;?>" align="center"><?php echo $i;?></td>
      <td style="<?php echo $claseFila;?>" align="center"><?php echo $row[Per_DNI];?></td>
      <td style="<?php echo $claseFila;?>"><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>    
      <td>
      <?php
        $estaInscripto = false;
	  	$estaInscripto = buscarAlumnoEstaInscripto($row[Leg_ID], $LecID+1);
	 	if ($estaInscripto) echo "INSCRIPTO";else echo "<span class='nota_Aplazado'>FALTA INSCRIBIR</span>";
		  ?>
      </td>
      </tr>
    
		  <?php
		
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
