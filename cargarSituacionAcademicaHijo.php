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
$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$usuario_nombre = $row[Per_Nombre];
	$usuario_apellido = $row[Per_Apellido];
	$PerID = $row[Per_ID];
}
//echo "DNI: $DNI";
	?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php


Obtener_LectivoActual($LecID, $LecNombre);
$sql = "SELECT Persona.Per_ID, Persona.Per_DNI, Persona.Per_Doc_ID, FTi_ID, Persona.Per_Apellido, Persona.Per_Nombre FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID)
    INNER JOIN Persona AS P
        ON (Familia.Fam_Vin_Per_ID = P.Per_ID)
WHERE Familia.Fam_FTi_ID =1 AND P.Per_DNI = '$DNI' ";
$result_prim = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result_prim);


if ($total>0){	
	?>
	
<br />
<div align="center">  <span class="titulo_noticia"><img src="imagenes/icono_asignatura.png" alt="" width="32" height="32" align="absmiddle" /> Clases de mis hijos</span><br />
<br />
</div>

<form action="imprimir_cuota_siucc_varias_PadresHijos.php" id="formTodas" target="_blank" method="post" class="texto"> 
<fieldset class="recuadro_simple" id="resultado_buscador">
  <div id="listado">	
  
  
  <?php
  while ($row_prim = mysqli_fetch_array($result_prim)){
	  $iPrim++;
		$FTiID = gbuscarFTiRelaciona($row_prim[FTi_ID], $parentesco);
		gbuscarFTiRelaciona($FTiID, $parentesco);
  ?>
  <table width="100%" border="0">
    <tr bgcolor="#FF9900" height="40px">
	  	<td colspan="7" bgcolor="#ABFF93" class="textoInformativo"><?php 
		$foto = buscarFoto($row_prim[Per_DNI], $row_prim[Per_Doc_ID], 30);
		echo "$foto $parentesco: <strong>$row_prim[Per_Apellido], $row_prim[Per_Nombre]</strong> (DNI: $row_prim[Per_DNI])";?>&nbsp;<a href="#" id="verCalificacionesCompleta<?php echo $iPrim;?>" target="_blank"><img src="imagenes/font_add.png" alt="Calificaciones completa de <?php echo $row_prim[Per_Nombre];?>" width="32" height="32" border="0" align="absmiddle" title="Calificaciones completa de <?php echo $row_prim[Per_Nombre];?>" />
	  	  <input type="hidden" id="PerID<?php echo $iPrim;?>" value="<?php echo $row_prim[Per_ID];?>" />
	  	  <input type="hidden" id="Persona<?php echo $iPrim;?>" value="<?php echo $row_prim[Per_Nombre];?>" />
	  	</a></td>
	  	</tr>
        <?php

		$sql = "SELECT DISTINCTROW * FROM 
	 Colegio_InscripcionClase
    INNER JOIN Colegio_Inscripcion 
        ON (IMa_Leg_ID = Ins_Leg_ID) AND (IMa_Lec_ID = Ins_Lec_ID)
    INNER JOIN Colegio_Clase 
        ON (IMa_Cla_ID = Colegio_Clase.Cla_ID)
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN persona 
        ON (Leg_Per_ID = persona.Per_ID)
    INNER JOIN Lectivo 
        ON (Cla_Lec_ID = lectivo.Lec_ID)
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Mat_ID)
    INNER JOIN Curso 
        ON (Colegio_Clase.Cla_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Cla_Div_ID = Div_ID)
	INNER JOIN Colegio_Nivel
		ON (Ins_Niv_ID = Niv_ID)
	WHERE Per_DNI = $row_prim[Per_DNI] 
	AND Cla_Lec_ID = $LecID ORDER BY Lec_Nombre, Niv_Nombre, Cur_Nombre, Div_Nombre, Mat_Nombre;";
	  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  if (mysqli_num_rows($result)==0){
		  ?>
          <tr bgcolor="#FF9900" height="40px">
	  	<td colspan="7" bgcolor="#FFFF99" class="textoInformativo">No existen Clases cargadas por el momento o todav&iacute;a no ha sido inscripto</td>
	  	</tr>
          <?php
	  }else{//*/
		  ?>
    <tr>
      <th class="fila_titulo">#</th>
      <th class="fila_titulo">Lectivo</th>
      <th class="fila_titulo">Prof.</th>
      <th align="center" class="fila_titulo">Materia </th>
      <th align="center" class="fila_titulo">Curso</th>
      <th align="center" class="fila_titulo">Div</th>
      <th class="fila_titulo">D&iacute;a y Horario</th>
      </tr>
	<?php 
		  while ($row = mysqli_fetch_array($result)){
			  
			$i++;
			if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
			
		?>
		<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
		  <td align="center"><input type="hidden" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $row[Cla_ID];?>">
          <?php echo $i;?>
          <input type="hidden" id="NivID<?php echo $iPrim;?>" value="<?php echo $row[Cla_Niv_ID];?>" /></td>
		  <td align="center"><?php echo $row[Lec_Nombre]?></td>
		  <td align="center"><?php 
		  $DNIDocente = obtenerDNIDocente($row[Cla_Doc_ID]);
		  buscarTipoDoc($DNIDocente, $DocuDocente);
		  $foto = buscarFoto($DNIDocente, $DocuDocente, 30);
		  echo "<a href='#' id='verFoto".$i."'>$foto</a>";
		  ?>&nbsp;
	      <input type="hidden" id="DNIDocente<?php echo $i;?>" value="<?php echo $DNIDocente;?>" />
          <input type="hidden" id="DocuDocente<?php echo $i;?>" value="<?php echo $DocuDocente;?>" />
          </td>
		  <td align="left"><?php echo $row[Mat_Nombre];?></td>
		  <td><?php echo $row[Cur_Siglas];?></td>
		  <td align="center"><?php echo $row[Div_Nombre];?></td>
		  <td align="center"><?php echo traerHorarioClase($row[Cla_ID]);?></td>
	    </tr>
			  <?php		  
		 
	}//fin del while
	}//fin del else
	?>  
</table>
<hr size="1" />
<?php
  }//fin while row_prim
?>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px"><div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir las cuotas seleccionadas" title="Imprimir las cuotas seleccionadas" width="32" border="0" align="absmiddle" /></a> - <?php echo "Se econtraron $total hijos/as suyos";?></div>
<br /><br /></fieldset>	<br /><br />
</form>
<script language="javascript">
$(document).ready(function(){

	

	
	$("a[id^='verCalificacionesCompleta']").click(function(evento){
		evento.preventDefault();
		var i = this.id.substr(25,10);
		//alert(i);
		var vPerID = $("#PerID" + i).val();
		var vPersona = $("#Persona" + i).val();
		var vNivID = $("#NivID" + i).val();
		//alert("#Nuevo" + i);
		$("#cargando").show();
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {PerID: vPerID, Persona: vPersona, NivID: vNivID},
					url: 'mostrarAlumnosClaseCalificacionCompletaHijo.php',
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
		
		$("#listado").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Clases de mis hijos',overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css',media:'print'}]										
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
 	$("a[id^='verFoto']").click(function(evento){
		evento.preventDefault();	
		var i = this.id.substr(7,10);
		var vDNI = $("#DNIDocente" + i).val();
		var vDocID = $("#DocuDocente" + i).val();
		//alert(vDNI);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarFoto", DNI: vDNI, DocID: vDocID, ancho: "500"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//mostrarAlerta(data, "Foto del Docente");
				$("#dialog").html(data);
				$("#dialog").dialog({ draggable: true, hide: 'slide', title: 'Foto del Docente', zIndex: 3900, resizable: true, modal: true, height: 'auto', width: 520, 
					buttons: {
							'Aceptar': function() {
							$(this).dialog('close');
						}
					}//fin buttons
				});//fin dialog
			}
		});//fin ajax//*/
	});

	
});//fin de la funcion ready
</script>
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron clases asignadas.</span>
<?php
}

?>
