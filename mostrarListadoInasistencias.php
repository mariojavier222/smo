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
	$("input[id^='Justificada']").click(function(evento){
		//evento.preventDefault();
		var vID = this.id.substr(11,10);
		var vMarcada = $("#Justificada" + vID + ":checked").length;
		if (vMarcada == 1){
			$("#Certificado" + vID).show();
			$("#Deportiva" + vID).show();
			$("#Detalle" + vID).show();
			$("#labelC" + vID).show();
			$("#labelD" + vID).show();
		}else{
			$("#Certificado" + vID).hide();
			$("#Deportiva" + vID).hide();
			$("#Detalle" + vID).hide();
			$("#labelC" + vID).hide();
			$("#labelD" + vID).hide();
		}
		
	});
	$("button[id^='quitarSeleccion']").click(function(evento){
		evento.preventDefault();
		var vID = this.id.substr(15,10);
		//alert(".limpiar" + vID + ":checked");
		$(".limpiar" + vID + ":checked").prop('checked', false);
		
		$(".ocultar.limpiar" + vID).hide();
		$("#Detalle"+ vID).val("");			
		
	});

	
	
	<?
	//Obtener_LectivoActual($LecID, $LecNombre);
	obtenerLimitesLectivo($LecID, $LecDesde, $LecHasta);
	$LimiteFecha = "{minDate: '".cfecha($LecDesde)."', maxDate: '".cfecha($LecHasta)."'}";//*/
	?>
	$("#Fecha").datepicker(<? echo $LimiteFecha;?>);
	
	$("#botGuardar, #botGuardar2").click(function(evento){
		evento.preventDefault();
		//alert("");return;
		vCant = 0;
		vLegID = 0;
		vFecha = $("#Fecha").val();
		if (vFecha==""){
			jAlert("ATENCIÓN: La Fecha no puede estar vacía","Error en la Fecha");
			return;
		}
		$("#cargando").show();
		$("input[name^='Ina']:checked").each(function(index){
			vInaID = $(this).val();
			
			vID = this.name.substr(3,10);
			//alert(vNotSigla);
			vLegID = $("#LegID" + vID).val();
			//alert(vLegID);
			vJustificada = $("#Justificada" + vID + ":checked").length;
			vCertificado = $("#Certificado" + vID + ":checked").length;
			vDeportiva = $("#Deportiva" + vID + ":checked").length;
			vDetalle = $("#Detalle" + vID).val();
			//alert(vNotID);
			//vCant++;
			//alert(vJustificada);
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: 'guardarInasistencia', LegID: vLegID, LecID: <? echo $LecID;?>, InaID: vInaID, Justificada: vJustificada, Certificado: vCertificado, Deportiva: vDeportiva, Detalle: vDetalle, FechaFalta: vFecha},
				url: 'cargarOpciones.php',
				success: function(data){ 
					if (data){									
						//alert(data);
						vCant++;
					}//fin if
				}//fin success
			});//fin ajax//*/
			
		});
		jAlert("Total de Inasistencias cargadas: " + vCant, "Inasistencias guardadas");
		//cargarNotas();
		$("input[name^='Ina']").removeAttr("checked");
		$("input[name^='Justificada']").removeAttr("checked");
		$("input[name^='Certificado']").removeAttr("checked");
		$("input[name^='Deportiva']").removeAttr("checked");
		$("input[name^='Detalle']").val("");
		$(".ocultar").hide();
		$("#cargando").hide();
		
	});//*/


});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?
if ($total>0){	
	?>

<div align="center">
  <p><a name="arriba" id="arriba"></a></p>
  <div style="width:100px" class="barra_boton" id="botGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
  Guardar cambios</div>
  <p>&nbsp;</p>
  <p><span class="texto">Fecha de la Inasistencia:</span>
    <input name="Fecha" type="text" id="Fecha" size="10" maxlength="10" /> 
  </p>
</div>
<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda <? //echo $SedID;?></legend>
<div id="listado" class="page-break"><br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="center" class="fila_titulo">Alumno</th>
          <th align="center" class="fila_titulo">Sexo </th>
          <th align="center" class="fila_titulo">Nivel</th>
          <th align="center" class="fila_titulo">Curso</th>
          <th width="170" align="center" class="fila_titulo">Inasistencias</th>
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
      <td align="center"><? cargarListaTipoInasistencia($i);?> 
        <input class="limpiar<? echo $i;?>" name="Justificada" type="checkbox" id="Justificada<? echo $i;?>" title="Inasistencia Justificada" value="1" alt="Inasistencia Justificada" />
        <label for="Justificada" title="Inasistencia Justificada">J<br />
        </label>
          <input name="Certificado<? echo $i;?>" type="checkbox" id="Certificado<? echo $i;?>" title="Justificada con Certificado" value="1" alt="Justificada con Certificado" class="ocultar limpiar<? echo $i;?>"/>
          <label id="labelC<? echo $i;?>" for="Certificado<? echo $i;?>" title="Justificada con Certificado" class="ocultar limpiar<? echo $i;?>">C</label>
         <input name="Deportiva<? echo $i;?>" type="checkbox" id="Deportiva<? echo $i;?>" title="Justificada por Licencia Deportiva" value="1" alt="Justificada por Licencia Deportiva" class="ocultar limpiar<? echo $i;?>"/>
          <label id="labelD<? echo $i;?>" for="Deportiva<? echo $i;?>" title="Justificada por Licencia Deportiva" class="ocultar limpiar<? echo $i;?>">D</label>  
          
          <input type="text" name="Detalle<? echo $i;?>" id="Detalle<? echo $i;?>" alt="Motivo u observaciones de la inasistencia" title="Motivo u observaciones de la inasistencia" class="ocultar limpiar<? echo $i;?>"/>
          <button id="quitarSeleccion<? echo $i;?>">remover marcas</button>

          </td>
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
<table border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="48"><div class="barra_boton" id="botGuardar2"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      Guardar cambios</div></td>
    <td width="48"></td>
    <td width="100" align="center"><a href="#arriba"><img src="imagenes/go-up.png" alt="Ir arriba" title="Ir arriba" width="22" height="22" border="0" /></a></td>
  </tr>
</table>

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
