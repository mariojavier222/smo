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
WHERE (Familia.Fam_Per_ID = $PerID AND Fam_FTi_ID = 2) ORDER BY Per_Sexo ASC";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	
	
	?>


<script language="javascript">

	   
	$(document).ready(function() {

	});
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<br />
<div align="center">  <span class="titulo_noticia"><img src="imagenes/book_open.png" alt="" width="32" height="32" align="absmiddle" /> Agenda de Clases de mis hijos/as</span><br />
<br />
</div>
	<?php
    while ($row = mysqli_fetch_array($result)){
	
	?>
    <iframe src="iframeAgendaClasesPadres.php?PerID=<?php echo $row[Fam_Vin_Per_ID];?>&LecID=<?php echo $LecID;?>" width="100%" height="600" frameborder="0" scrolling="no"></iframe><br />    
<?php
	}//fin while
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron horarios asignados.</span>
<?php
}

?>
