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
	
	$Curso = $_POST['Curso'];
	$Div = $_POST['Div'];
	$Nombre = $_POST['Nombre'];
	$DNI = $_POST['DNI'];
	$Sexo = $_POST['Sexo'];
	$FechaNac = $_POST['FechaNac'];
	$DatNac = $_POST['DatNac'];
	$Dom = $_POST['Dom'];
	$DatDom = $_POST['DatDom'];
	$Correo = $_POST['Correo'];
	$Tel = $_POST['Tel'];
	$Cel = $_POST['Cel'];
	$Orden = $_POST['Orden'];
	$Tutor = $_POST['Tutor'];
	$TutorNac = $_POST['TutorNac'];
	$PerRetira = $_POST['PerRetira'];
	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT
	Leg_Numero
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
	, Per_ID
	, Leg_Numero
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
WHERE (Per_Baja = 0 AND Leg_Baja = 0 AND Colegio_Inscripcion.Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID";
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Per_Apellido, Per_Nombre;";


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

	
	});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
<div id="listado" class="page-break">	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <?php if ($Orden){?>
          <th align="center" class="fila_titulo">Orden</th>
          <th align="center" class="fila_titulo">Estado</th>
          <?php }?>
		  <?php if ($DNI){?><th align="center" class="fila_titulo">DNI</th><?php }?>
          <?php if ($Nombre){?><th align="center" class="fila_titulo">Alumno</th><?php }?>
          <?php if ($Sexo){?><th align="center" class="fila_titulo">Sexo </th><?php }?>
          <?php if ($Curso){?><th align="center" class="fila_titulo">Curso</th><?php }?>
          <?php if ($Div){?><th align="center" class="fila_titulo">Div</th><?php }?>
          <?php if ($FechaNac){?><th align="center" class="fila_titulo">Fecha Nac.</th><?php }?>
          <?php if ($DatNac){?><th align="center" class="fila_titulo">Lugar de Nac.</th><?php }?>
          <?php if ($Dom){?><th align="center" class="fila_titulo">Domicilio</th><?php }?>
          <?php if ($DatDom){?><th align="center" class="fila_titulo">Lugar de Domicilio</th><?php }?>
          <?php if ($Correo){?><th align="center" class="fila_titulo">Email</th><?php }?>
          <?php if ($Tel){?><th align="center" class="fila_titulo">Tel.</th><?php }?>
          <?php if ($Cel){?><th align="center" class="fila_titulo">Cel.</th><?php }?>
          <?php if ($Tutor){?><th align="center" class="fila_titulo">Tutor</th><?php }?>
          <?php if ($TutorNac){?><th align="center" class="fila_titulo">Tutor Nac.</th><?php }?>
          <?php if ($PerRetira){?><th align="center" class="fila_titulo">Persona Retira.</th><?php }?>
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
		$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = $row[Per_ID]";
		$resultDatos = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($resultDatos)>0){
			$rowDatos = mysqli_fetch_array($resultDatos);
			$DatosDomicilio = obtenerLugar($rowDatos[Dat_Dom_Pai_ID], $rowDatos[Dat_Dom_Pro_ID], $rowDatos[Dat_Dom_Loc_ID]);
			$DatosNacimiento = obtenerLugar($rowDatos[Dat_Nac_Pai_ID], $rowDatos[Dat_Nac_Pro_ID], $rowDatos[Dat_Nac_Loc_ID]);
	?>
	<tr class="gradeA" id="fila<?php echo $i;?>" height="40px">
      <?php if ($Orden){?><td align="center"><?php echo $i;?></td>
      
      <?php }?>
      <td align="center"><?php if ($row[Leg_Baja]==1) echo "BAJA";?></td>
	  <?php if ($DNI){?><td align="center"><?php echo $row[Per_DNI];?></td><?php }?>
      <?php if ($Nombre){?><td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td><?php }?>
      <?php if ($Sexo){?><td><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td><?php }?>
      <?php if ($Curso){?><td><?php echo $row[Cur_Siglas];?></td><?php }?>
      <?php if ($Div){?><td><?php echo $row[Div_Siglas];?></td><?php }?>
      <?php if ($FechaNac){?><td align="center"><?php echo cfecha($rowDatos[Dat_Nacimiento]); ?></td><?php }?>
      <?php if ($DatNac){?><td align="center"><?php echo $DatosNacimiento;?></td><?php }?>
      <?php if ($Dom){?><td align="center"><?php echo $rowDatos[Dat_Domicilio];?></td><?php }?>
      <?php if ($DatDom){?><td align="center"><?php echo $DatosNacimiento;?></td><?php }?>
      <?php if ($Correo){?><td align="center"><?php if (!empty($rowDatos[Dat_Email])) echo $rowDatos[Dat_Email]; else echo "No est� cargado";?></td><?php }?>
      <?php if ($Tel){?><td align="center"><?php if (!empty($rowDatos[Dat_Telefono])) echo $rowDatos[Dat_Telefono]; else echo "No está cargado";?></td><?php }?>
      <?php if ($Cel){?><td align="center"><?php if (!empty($rowDatos[Dat_Celular])) echo $rowDatos[Dat_Celular]; else echo "No está cargado";?></td><?php }?>
      <?php if ($Tutor){?><td align="center"><?php 
	  
	  $NombreTutor = obtenerTutor($row[Per_ID], $DNITutor, $PerIDTutor);
	  obtenerDatosNacimiento($PerIDTutor, $fechaNac, $PaiID, $ProID, $LocID);
	  $DatosTutorNac = obtenerLugar($PaiID, $ProID, $LocID);
	  echo $NombreTutor;
	  ?></td><?php }?>
      <?php if ($TutorNac){?><td align="center"><?php echo $DatosTutorNac;?></td><?php }?>
      <?php if ($PerRetira){?><td align="center"><?php if (!empty($rowDatos[Dat_Retira])) echo $rowDatos[Dat_Retira]; else echo "No cargado";?></td><?php }?>

    </tr>    
		  <?php
		}else{//entra por aca cuando no existen datos cargados en Otros datos.
		?>
	<tr class="gradeA" id="fila<?php echo $i;?>" height="40px">
      <?php if ($Orden){?><td align="center"><?php echo $i;?></td>
      
      <?php }?>
      <td align="center"><?php if ($row[Leg_Baja]==1) echo "BAJA";?></td>
	  <?php if ($DNI){?><td align="center"><?php echo $row[Per_DNI];?></td><?php }?>
      <?php if ($Nombre){?><td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>      <?php }?>
      <?php if ($Sexo){?><td><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td><?php }?>
      <?php if ($Curso){?><td><?php echo $row[Cur_Siglas];?></td><?php }?>
      <?php if ($Div){?><td><?php echo $row[Div_Siglas];?></td><?php }?>
      <?php if ($FechaNac){?><td align="center">---</td><?php }?>
      <?php if ($DatNac){?><td align="center">---</td><?php }?>
      <?php if ($Dom){?><td align="center">---</td><?php }?>
      <?php if ($DatDom){?><td align="center">---</td><?php }?>
      <?php if ($Correo){?><td align="center">---</td><?php }?>
      <?php if ($Tel){?><td align="center">---</td><?php }?>
      <?php if ($Cel){?><td align="center">---</td><?php }?>
      <?php if ($Tutor){?><td align="center"><?php 	  
	  $NombreTutor = obtenerTutor($row[Per_ID], $DNITutor, $PerIDTutor);
	  obtenerDatosNacimiento($PerIDTutor, $fechaNac, $PaiID, $ProID, $LocID);
	  $DatosTutorNac = obtenerLugar($PaiID, $ProID, $LocID);
	  echo $NombreTutor;
	  ?></td><?php }?>
      <?php if ($TutorNac){?><td align="center"><?php echo $DatosTutorNac;?></td><?php }?>
	  <?php if ($PerRetira){?><td align="center"><?php if (!empty($rowDatos[Dat_Retira])) echo $rowDatos[Dat_Retira]; else echo "No cargado";?></td><?php }?>
    </tr>    
		  <?php	
		}//fin de que encontr� otros datos en la b�squeda
		
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="13" class="fila_titulo"></th>
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
