<?
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
	$Mes = $_POST['Mes'];
	
	
	
	$tituloPagina = $_POST['tituloPagina'];
	$tituloCursoDivision = $_POST['tituloCursoDivision'];
	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$where = "Ins_Lec_ID = $LecID  AND Leg_Sed_ID = $SedID AND Per_Baja = 0 AND Leg_Baja = 0 ";
	if ($CurID!=999999) $where.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $where.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $where.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	if (isset($_POST['PerID'])) $PerID = $_POST['PerID'];
	$sqlPersona = "";
	if (!empty($PerID)){
		$sqlPersona = " Per_ID = $PerID ";
		$where = $sqlPersona;
	} 
	//echo $where;exit;

	$sql = "SELECT
	Leg_Numero
    , Persona.Per_ID
	, Persona.Per_DNI
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Persona.Per_Sexo
    , Colegio_Nivel.Niv_Nombre
    , Curso.Cur_Curso
    , Division.Div_Nombre
	, Niv_Siglas
    , Colegio_Inscripcion.Ins_Provisoria
    , Colegio_Inscripcion.Ins_Lec_ID
    , Colegio_Inscripcion.Ins_Niv_ID
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
WHERE ($where) ORDER BY Niv_ID, Cur_ID, Div_ID, Persona.Per_Sexo DESC, Per_Apellido, Per_Nombre;";
//echo $sql;


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
		$("#listado").printElement({leaveOpen:true, printMode:'popup', pageTitle:'<? echo $tituloPagina;?> ' + vLectivo
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
<?
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" class="page-break">	
<br />
<? //echo $tituloCursoDivision;?>

 
 
	<? $i=0;
	
	$gMes[1] = "Enero";
	$gMes[2] = "Febrero";
	$gMes[3] = "Marzo";
	$gMes[4] = "Abril";
	$gMes[5] = "Mayo";
	$gMes[6] = "Junio";
	$gMes[7] = "Julio";
	$gMes[8] = "Agosto";
	$gMes[9] = "Septiembre";
	$gMes[10] = "Octubre";
	$gMes[11] = "Noviembre";
	$gMes[12] = "Diciembre";
	$iMes = intval($Mes);
	$textoMes = $gMes[$iMes];
	$textoMes = substr(strtoupper($textoMes),0,3)."/".substr(date("Y"),-2);
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	echo "Comienza el proceso....<br /><br /><br />";
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		$codAlu = substr("00000".$row['Per_ID'],-5);

		$whereCuotas = " Cuo_Lec_ID = $row[Ins_Lec_ID] AND Cuo_Per_ID = $row[Per_ID] AND Cuo_Niv_ID = $row[Ins_Niv_ID] AND Cuo_CTi_ID=2 AND Cuo_Mes='$Mes' AND Cuo_Anio='".date("Y")."' ";
		if (!empty($PerID)){
			$whereCuotas = " Cuo_Per_ID = $PerID ";

		} 
		$sql = "SELECT * FROM CuotaPersona WHERE $whereCuotas";
		//echo "$sql<br>";
	  $result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  while ($row2 = mysqli_fetch_array($result2)){


		$imp1 = str_replace(".", "", $row2['Cuo_Importe']);
		$imp1 = substr("00000000".$imp1, -8);
		$imp2 = intval($row2['Cuo_Importe'])+intval($row2['Cuo_1er_Recargo']);
		$imp2 = str_replace(".", "", $imp2)."00";
		$imp2 = substr("00000000".$imp2, -8);
		$dia = substr($row2['Cuo_1er_Vencimiento'],8,2);
		$mes = substr($row2['Cuo_1er_Vencimiento'],5,2);
		$anio = substr($row2['Cuo_1er_Vencimiento'],2,2);
		$venc1 = $dia.$mes.$anio;
		$dia = substr($row2['Cuo_2do_Vencimiento'],8,2);
		$mes = substr($row2['Cuo_2do_Vencimiento'],5,2);
		$anio = substr($row2['Cuo_2do_Vencimiento'],2,2);
		$venc2 = $dia.$mes.$anio;

		$iMes = intval($row2['Cuo_Mes']);
		$textoMes = $gMes[$iMes];
		$textoMes = substr(strtoupper($textoMes),0,3)."/".substr(date("Y"),-2);

		//buscarDatosCuota($row['Ins_Lec_ID'], $row['Per_ID'], $row['Ins_Niv_ID'], $Mes, $importe1, $importe2, $vencimiento1, $vencimiento2);
		/*$importe1 = "00075000";
		$vencimiento1="290319";
		$importe2 = "00080000";
		$vencimiento2="050419";*/
		$alumno = substr($row['Per_Apellido'],0,10).", ".substr($row['Per_Nombre'],0,15);
		$alumno = substr($alumno,0,20);
		$cont = strlen($alumno);
		$dif = 20 - $cont;
		if ($dif>0){
			for ($i=0; $i <= $dif; $i++) { 
				$alumno."*";
			}			
		}
		//&nbsp;
		$alumno = str_replace("*", "&nbsp;", $alumno);
		if ($row['Ins_Niv_ID']==1) $Nivel = "PRIM";
		if ($row['Ins_Niv_ID']==2) $Nivel = "SECU";
		$Division = "-".$row['Div_Nombre'];
		if ($row['Ins_Niv_ID']==3){
			$Nivel = "JARD";
			$Division = "";
		} 

		$cuota = "****"."$Nivel-".$row['Cur_Curso'].$Division."[$textoMes]";
		$cuota = str_replace("*", "&nbsp;", $cuota);


		$linea = $codAlu.$imp1.$venc1.$imp2.$venc2.$alumno.$cuota."<br>";
		//$linea = "$importe1/$vencimiento1<br>";
		echo $linea;
		}//fin while cuotas
	
		
	}//fin del while

	echo "<br />....FIN....<br />";
	echo "Total de registros: ".mysqli_num_rows($result);
	?>  

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

function buscarDatosCuota($Lec_ID, $Per_ID, $Niv_ID, $Mes, &$imp1, &$imp2, &$venc1, &$venc2){
	$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID = $Per_ID AND Cuo_Niv_ID = $Niv_ID AND Cuo_CTi_ID=2 AND Cuo_Mes='$Mes' AND Cuo_Anio='".date("Y")."'";
	//echo "$sql<br>";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$imp1 = str_replace(".", "", $row['Cuo_Importe']);
	$imp1 = substr("00000000".$imp1, -8);
	$imp2 = intval($row['Cuo_Importe'])+intval($row['Cuo_1er_Recargo']);
	$imp2 = str_replace(".", "", $imp2)."00";
	$imp2 = substr("00000000".$imp2, -8);
	$dia = substr($row['Cuo_1er_Vencimiento'],8,2);
	$mes = substr($row['Cuo_1er_Vencimiento'],5,2);
	$anio = substr($row['Cuo_1er_Vencimiento'],2,2);
	$venc1 = $dia.$mes.$anio;
	$dia = substr($row['Cuo_2do_Vencimiento'],8,2);
	$mes = substr($row['Cuo_2do_Vencimiento'],5,2);
	$anio = substr($row['Cuo_2do_Vencimiento'],2,2);
	$venc2 = $dia.$mes.$anio;
}

?>
