<?php
require_once("conexion.php");
include_once("comprobar_sesion.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
//sleep(3);

$DNI = $_SESSION['sesion_usuario'];
//$DNI = "18618965";
if (!is_numeric($DNI)){
	?>
	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible para Usted.</span></div></p><p></p>
<?php
	exit;
}
buscarTipoDoc($DNI, $DocuID);

$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$usuario_nombre = $row[Per_Nombre];
	$usuario_apellido = $row[Per_Apellido];
}
//echo "DNI: $DNI";
	?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php

$DocID = obtenerDocIDDocente($row[Per_ID]);
Obtener_LectivoActual($LecID, $LecNombre);
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Clase
    INNER JOIN Sede 
        ON (Colegio_Clase.Cla_Sed_ID = Sede.Sed_ID)
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID)
    INNER JOIN Lectivo 
        ON (Colegio_Clase.Cla_Lec_ID = Lectivo.Lec_ID)
	 INNER JOIN Curso 
        ON (Colegio_Clase.Cla_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Clase.Cla_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Clase.Cla_Niv_ID = Colegio_Nivel.Niv_ID)
	WHERE Cla_Doc_ID = '$DocID' 
	AND Cla_Lec_ID = $LecID ORDER BY Lec_Nombre, Niv_Nombre, Cur_Nombre, Div_Nombre, Mat_Nombre";
	//echo "$sql";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	?>
	
<script language="javascript">
$(document).ready(function(){

	$("input[id^='Nuevo']").click(function(evento){
		//evento.preventDefault();
		i = this.id.substr(5,10);
		
		//vCuota = $("#Nuevo" + i).val();
		vImporte = parseInt($("#cuotas" + i).val());
		//alert(vImporte);
		vTotal = parseInt($("#totalPagar").val());
		vTotalCuotas = parseInt($("#totalCuotas").val());
		if (this.checked){
			vTotal += parseInt(vImporte);
			vTotalCuotas += 1;
		}else{
			vTotal -= parseInt(vImporte);
			vTotalCuotas -= 1;
		}
		$("#totalPagar").val(vTotal);
		$("#totalCuotas").val(vTotalCuotas);
	 });//fin evento click//*/

	$("a[id^='verCalificaciones_']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(18,10);
		//alert(i);
		vClaID = $("#Nuevo" + i).val();
		//alert(vCuota);
		$("#cargando").show();
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {ClaID: vClaID},
					url: 'mostrarAlumnosClaseCalificacion.php',
					success: function(data){ 
						$("#principal").html(data);
					}
		});//fin ajax///
		$("#cargando").hide();
	 });//fin evento click//*/
	$("a[id^='verCalificacionesCompleta']").click(function(evento){
		evento.preventDefault();
		var i = this.id.substr(25,10);
		//alert(i);
		var vClaID = $("#Nuevo" + i).val();
		//alert("#Nuevo" + i);
		$("#cargando").show();
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {ClaID: vClaID},
					url: 'mostrarAlumnosClaseCalificacionCompleta.php',
					success: function(data){ 
						$("#principal").html(data);
					}
		});//fin ajax///
		$("#cargando").hide();
	 });//fin evento click//*/
	$("a[id^='verAlumnos']").click(function(evento){
		evento.preventDefault();
		i = this.id.substr(10,10);
		//alert(i);
		vClaID = $("#Nuevo" + i).val();
		//alert(vCuota);
		$("#cargando").show();
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {ClaID: vClaID},
					url: 'mostrarAlumnosClase.php',
					success: function(data){ 
						$("#principal").html(data);
					}
		});//fin ajax///
		$("#cargando").hide();
	 });//fin evento click//*/
	 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		
		$("#listado").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Mis Clases',overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css',media:'print'}]										
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
 
 		

	//marcar y desmarcar todo
	$("#marcar").click(function(evento){
		evento.preventDefault();
		vTotal = 0;
		$( ":checkbox").attr('checked', 'checked');
		$("#totalCuotas").val($("#totalesCuotas").val());
		$("input[id^='Nuevo']").each(function(){
			i = this.id.substr(5,10);					
			vImporte = parseInt($("#cuotas" + i).val());
			vTotal += parseInt(vImporte);		
		});
		$("#totalPagar").val(vTotal);
	});
	$("#desmarcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").attr('checked', '');
		$("#totalPagar").val(0);
		$("#totalCuotas").val(0);
	}); 
});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<br />
<div align="center">  <span class="titulo_noticia"><img src="imagenes/icono_asignatura.png" alt="" width="32" height="32" align="absmiddle" /> Mis clases</span><br />
<br />
</div>

<form action="imprimir_cuota_siucc_varias_PadresHijos.php" id="formTodas" target="_blank" method="post" class="texto"> 
<fieldset class="recuadro_simple" id="resultado_buscador">
  <div id="listado">	
  
  
  <table width="100%" border="0">
    <tr>
      <th class="fila_titulo">#</th>
      <th class="fila_titulo">Lectivo</th>
      <th class="fila_titulo">Sede</th>
      <th align="center" class="fila_titulo">Materia </th>
      <th align="center" class="fila_titulo">Nivel</th>
      <th align="center" class="fila_titulo">Curso</th>
      <th align="center" class="fila_titulo">Div</th>
      <th class="fila_titulo">D&iacute;a y Horario</th>
      <th class="fila_titulo"><div align="left">Acciones</div></th>
    </tr>
	<?php $i=0;
	
	while ($row = mysqli_fetch_array($result)){
				
			$i++;
			if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
			
		?>
		<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
		  <td align="center"><input type="hidden" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $row[Cla_ID];?>">
          <?php echo $i;?>
          </td>
		  <td align="center"><?php echo $row[Lec_Nombre]?></td>
		  <td align="center"><?php echo $row[Sed_Siglas];?></td>		  
		  <td align="left"><?php echo $row[Mat_Nombre];?></td>
		  <td><?php echo $row[Niv_Nombre];?></td>
		  <td><?php echo $row[Cur_Siglas];?></td>
		  <td align="center"><?php echo $row[Div_Nombre];?></td>
		  <td align="center"><?php echo traerHorarioClase($row[Cla_ID]);?></td>
		  <td><?php //echo $totales;
		  ?> <a href="#" id="verAlumnos<?php echo $i;?>"> <img src="imagenes/group.png" width="32" height="32" alt="Ver mis alumnos" title="Ver mis alumnos" border="0"/></a> <a href="#" id="verCalificaciones_<?php echo $i;?>" target="_blank"><img src="imagenes/font.png" alt="Calificaciones de los alumnos" title="Calificaciones de los alumnos" width="32" height="32" border="0" /></a><a href="#" id="verCalificacionesCompleta<?php echo $i;?>" target="_blank"><img src="imagenes/font_add.png" alt="Calificaciones completa de los alumnos" title="Calificaciones completa de los alumnos" width="32" height="32" border="0" /></a>
			
		  </td>
		</tr>
			  <?php		  
		 
	}//fin del while
	?>  
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px"><div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir las cuotas seleccionadas" title="Imprimir las cuotas seleccionadas" width="32" border="0" align="absmiddle" /></a> - <?php echo "Se econtraron $i clases asignadas";?></div>
<br /><br /></fieldset>	<br /><br />
</form>
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron clases asignadas.</span>
<?php
}

?>
