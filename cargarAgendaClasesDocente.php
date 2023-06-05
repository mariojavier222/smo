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
$sql = "SELECT * FROM
    Colegio_Horario
    INNER JOIN Colegio_Clase 
        ON (Colegio_Horario.Hor_Cla_ID = Colegio_Clase.Cla_ID)
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID)
    INNER JOIN Curso 
        ON (Colegio_Clase.Cla_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Clase.Cla_Div_ID = Division.Div_ID)
	WHERE Cla_Doc_ID = '$DocID' 
	AND Cla_Lec_ID = $LecID ORDER BY Hor_Dia_ID";
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
<div align="center">  <span class="titulo_noticia"><img src="imagenes/book_open.png" alt="" width="32" height="32" align="absmiddle" /> Mi Agenda de Clases</span><br />
<br />
</div><?php //echo $DocID;?>

	<iframe src="iframeAgendaClasesDocente.php?DocID=<?php echo $DocID;?>&LecID=<?php echo $LecID;?>" width="100%" height="700" frameborder="0" scrolling="no"></iframe>
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron horarios asignados.</span>
<?php
}

?>
