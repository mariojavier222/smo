<?
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("listas.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$SedID = $_POST['SedID'];

$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT
	Ins_Leg_ID
	, Leg_Numero
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
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID AND Leg_Baja = 0 AND Per_Baja = 0 ";
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Per_Sexo, Per_Apellido, Per_Nombre;";


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
	claseFila = "";
	
	$("tr[id^='fila']").mouseenter(function(evento){
		evento.preventDefault();
		var vID = this.id.substr(4,10);
		claseFila = $("#fila" + vID).attr("class");
		$("#fila" + vID).attr("class", "filaRecuadro");
		
	});
	$("tr[id^='fila']").mouseleave(function(evento){
		evento.preventDefault();
		var vID = this.id.substr(4,10);
		$("#fila" + vID).attr("class", claseFila);
		
	});
	$(".ocultar").hide();

	$("a[id^='lupa']").click(function(evento){
		evento.preventDefault();
		//vID = this.id;
		var vID = this.id.substr(4,10);

		vLegID = $("#acceso" + vID).val();	
		//alert(vLogID);
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "obtenerDetalleInasistencia", LegID: vLegID},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(vLogID);
					vCuadro = "<h2>Detalle de las Inasistencias</h2>" + data;
					jAlert(vCuadro, "Inasistencias");
					$("a[id^='eliminarAsistencia']").click(function(evento){
						evento.preventDefault();
						//alert("bien");return;
						var vID = this.id.substr(18,20);
						$("#q"+ vID).remove();
						$("#q"+ vID).hide();
						//alert("."+ vID);
						$.ajax({
							type: "POST",
							cache: false,
							async: false,
							error: function (XMLHttpRequest, textStatus){
								alert(textStatus);},
							data: {opcion: 'eliminarAsistencia', id: vID},
							url: 'cargarOpciones.php',
							success: function(data){ 
								if (data){								
									$("#barraAcumuladas").click();
									alert(data);					
								}//fin if
							}//fin success
						});//fin ajax//*/
									
						
					});
					
					
					$("#cargando").hide();
				}
		});//fin ajax//*/
	 });//fin evento click//*/




});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda <? //echo $SedID;?></legend>
<div id="listado" class="page-break"><br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th rowspan="2" align="center" class="fila_titulo">DNI</th>
          <th rowspan="2" align="center" class="fila_titulo">Alumno</th>
          <th rowspan="2" align="center" class="fila_titulo">Sexo </th>
          <th rowspan="2" align="center" class="fila_titulo">Nivel</th>
          <th rowspan="2" align="center" class="fila_titulo">Curso</th>
          <th rowspan="2" width="170" align="center" class="fila_titulo">Inasistencias</th>
        </tr>        
       </thead>
       <tbody>
	<? $i=0;
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		//if ($i==10) echo "<p style='page-break-before:always'></p>";
		
	?>
	<tr class="<? echo $clase;?>" id="fila<? echo $i;?>" height="40px">
      <td align="center"><? echo $row[Per_DNI];?>
        <input name="LegID" type="hidden" id="LegID<? echo $i;?>" value="<? echo $row[Ins_Leg_ID];?>" /></td>
      <td><? echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
      
      <td><? if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td>
      <td><? echo $row[Niv_Siglas];?></td>
      <td><? echo $row[Cur_Siglas];?> <? echo $row[Div_Siglas];?></td>      
    <td align="center"><? $acum = obtenerInasistenciaAlumnoLectivoTotal($LecID, $row[Ins_Leg_ID]);
    if ($acum>0){
    	echo "$acum <a href='#'' id='lupa$i'>
      <img src='imagenes/magnifier_zoom_in.png' width='32' height='32' border='0' /></a>
      <input name='acceso$i' type='hidden' id='acceso$i' value='$LecID;$row[Ins_Leg_ID]' />";
    }else{
    	echo "0";
    }
    ?></td>    
    </tr>
    
		  <?		  
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="6" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <? echo "Se encontraron $total alumnos inscriptos";?></div>
<br /><br /></fieldset>


<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
 <?
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron alumnos inscriptos.</span>
<?
}

?>
