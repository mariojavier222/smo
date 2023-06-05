<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

//sleep(3);
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
$DNI = $_SESSION['sesion_usuario'];
//$DNI = "18618965";
if (!is_numeric($DNI)){
	?>
	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible para Usted.</span></div></p><p></p>
<?php
	exit;
}
buscarTipoDoc($DNI, $DocID);

$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$rowDatos = mysqli_fetch_array($result);
	$usuario_nombre = $rowDatos[Per_Nombre];
	$usuario_apellido = $rowDatos[Per_Apellido];
	$PerID = $rowDatos[Per_ID];
}


          $sql = "SELECT * FROM PersonaDatos
    INNER JOIN Persona 
        ON (PersonaDatos.Dat_Per_ID = Persona.Per_ID) WHERE Per_ID = $PerID;";
		//echo $sql;
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>-1){
			$row = mysqli_fetch_array($result);
			
			?>
<table width="95%" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
<tr>
				    <td align="center"><p><span class="titulo_noticia"><img src="imagenes/hombre-mujer.png" alt="" width="32" height="32" align="absmiddle" /> Mis datos personales</span>
				    <div class="cuadroDatosPersonales">
				      <p></p>
				      <table width="580" border="0" align="center" cellpadding="1" cellspacing="1" class="texto">
				        <tr>
				          <td height="40" colspan="2" valign="top" class="textoInformativo">Nombre y Apellido: <strong><?php echo "$rowDatos[Per_Nombre] $rowDatos[Per_Apellido]";?></strong></td>
			            </tr>
				        <tr>
				          <td width="258" height="40" valign="top">Fecha de Nacimiento: <strong><?php echo cfecha($row[Dat_Nacimiento]);?></strong></td>
				          <td width="315" height="40" valign="top">Lugar: <strong><?php echo obtenerLugar($row[Dat_Nac_Pai_ID], $row[Dat_Nac_Pro_ID], $row[Dat_Nac_Loc_ID]);?></strong></td>
			            </tr>
				        <tr>
				          <td height="40" colspan="2" valign="top">Domicilio actual: <strong><?php echo $row[Dat_Domicilio];?></strong></td>
			            </tr>
				        <tr>
				          <td height="40" colspan="2" valign="top">Localidad: <strong><?php echo obtenerLugar($row[Dat_Dom_Pai_ID], $row[Dat_Dom_Pro_ID], $row[Dat_Dom_Loc_ID]);?></strong></td>
			            </tr>
				        <tr>
				          <td height="40" valign="top">Tel&eacute;fono fijo: <strong><?php echo validarVacio($row[Dat_Telefono]);?></strong></td>
				          <td height="40" valign="top">Tel&eacute;fono celular: <strong><?php echo validarVacio($row[Dat_Celular]);?></strong></td>
			            </tr>
				        <tr>
				          <td height="40" colspan="2" valign="top">Observaciones: <?php echo validarVacio($row[Dat_Observaciones]);?></td>
			            </tr>
				        <tr>
				          <td height="40" valign="top">&nbsp;</td>
				          <td height="40" valign="top">&nbsp;</td>
			            </tr>
			          </table>
				      <p>&nbsp;</p>
				      <p>&nbsp;</p>
				      <p></p>
			        </div></p></td>
			      </tr>
    </table>
	<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron datos cargados.</span></div>
<?php
}

?>
