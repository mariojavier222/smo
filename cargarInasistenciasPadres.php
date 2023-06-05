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
<link href="css/general.css" rel="stylesheet" type="text/css" />

	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible para Usted.</span></div></p><p></p>
<?php
	exit;
}
//buscarTipoDoc($DNI, $DocuID);

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
$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID)	
    INNER JOIN Legajo 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
WHERE (Familia.Fam_Per_ID = $PerID AND Fam_FTi_ID = 2)";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	//Obtener_LectivoActual($LecID, $LecNombre);
	
	?>


<script language="javascript">

	   
	$(document).ready(function() {

	});
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<br />
<div align="center">  <span class="titulo_noticia"><img src="imagenes/tag_blue_delete.png" alt="" width="32" height="32" align="absmiddle" /> Inasistencias de mis hijos/as</span><br />
<br />
</div><br />



	<?php
    while ($row = mysqli_fetch_array($result)){
	
	
	?>
	                   
    <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" class="cuadroDatosPersonales">
          <tr>
    <td colspan="5" class="textoInformativo"><?php 
		$foto = buscarFoto($row[Per_DNI], $row[Per_Doc_ID], 50);
		//echo "$row[Per_DNI], $row[Per_Doc_ID]";
		echo "$foto<strong>$row[Per_Apellido], $row[Per_Nombre]</strong> (DNI: $row[Per_DNI])";?></td>
  </tr>
  <tr class="fila_titulo">
    <th rowspan="2" align="center">Tipo de Inasistencias</th>
    <th rowspan="2" align="center">Injustificadas</th>
    <th colspan="3" align="center">Justificadas</th>
    <th rowspan="2" align="center">Totales</th>
  </tr>
  <tr class="fila_titulo">
    <th align="center">Personal</th>
    <th align="center">Cert. M&eacute;dico</th>
    <th align="center">Deportiva</th>
  </tr>
  <?php
  $sql = "SELECT * FROM Colegio_TipoInasistencia;";
  $result_Ina = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  if (mysqli_num_rows($result_Ina)>0){

    while ($row_Ina = mysqli_fetch_array($result_Ina)){
	
	

  ?>
  <tr class="fila2">
    <td align="left" ><?php echo $row_Ina[Ina_Nombre];?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumno($LecID, $row[Leg_ID], $row_Ina[Ina_ID]);?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumno($LecID, $row[Leg_ID], $row_Ina[Ina_ID],1,0,0);?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumno($LecID, $row[Leg_ID], $row_Ina[Ina_ID],1,1,0);?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumno($LecID, $row[Leg_ID], $row_Ina[Ina_ID],1,0,1);?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumnoTipoTotal($LecID, $row[Leg_ID], $row_Ina[Ina_ID]);?></td>
  </tr>
  <?php
	}//fin while
  }//fin if
  ?>
  <tr class="fila_titulo">
    <td align="left">Total de inasistencias:</td>
    <td align="center"><?php echo obtenerInasistenciaAlumnoJustificacionTotal($LecID, $row[Leg_ID], 0, "");?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumnoJustificacionTotal($LecID, $row[Leg_ID], 1, "");?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumnoJustificacionTotal($LecID, $row[Leg_ID], 1, "Certificado");?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumnoJustificacionTotal($LecID, $row[Leg_ID], 1, "Deportiva");?></td>
    <td align="center"><?php echo obtenerInasistenciaAlumnoLectivoTotal($LecID, $row[Leg_ID]);?></td>
  </tr>

<br />

    
    </table>
    <br /><br />
<?php
	}//fin while
	?>
    
    <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron horarios asignados.</span>
<?php
}

?>
