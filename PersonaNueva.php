<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

$DNI = $_POST['DNI'];
$DocID = $_POST['DocID'];
$_SESSION['sesion_ultimoDNI'] = $DNI;
$Apellido = strtoupper(addslashes(utf8_decode($_POST['Apellidos'])));
$Nombre = ucwords(strtolower(addslashes(utf8_decode($_POST['Nombre']))));
$Sexo = $_POST['Sexo'];
$Extranjero = $_POST['Extranjero'];
$Alternativo = $_POST['Alternativo'];
$Editar = $_POST['Editar'];
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

$sql = "SELECT * FROM Persona, PersonaDocumento WHERE Per_DNI = $DNI AND Per_Doc_ID = Doc_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	//Ya existe el DNI a cargar
	if ($Editar=="si"){
	//$DNI = $_POST['DNI'];
	$sql = "UPDATE Persona SET Per_DNI = $DNI, Per_Doc_ID = $DocID, Per_Apellido = '$Apellido', Per_Nombre = '$Nombre', Per_Sexo = '$Sexo', Per_Alternativo = '$Alternativo', Per_Extranjero = '$Extranjero' WHERE Per_ID = $PerID";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
?><div class="borde_aviso"><span class="texto">Los datos de <?php echo "$Apellido, $Nombre";?> han sido actualizados correctamente.</span></div><?php
	}else{
	?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />La persona que desea crear ya existe con ese n&uacute;mero de documento. Si desea hacer un cambio de apellido o nombre tiene que dirigirse a la secci&oacute;n de buscar.<br />
Los datos que se encontraron con ese documento son:</span><br />

	<?php while ($row = mysqli_fetch_array($result)){
		$nombre = "$row[Per_Nombre] $row[Per_Apellido]";
		$tipo_doc = $row[Doc_Nombre];
		$DNI = $row[Per_DNI];
		?>
<p class="texto">
		Nombre y Apellido: <strong><?php echo $nombre;?></strong><br />
		<?php echo $tipo_doc;?>: <strong><?php echo $DNI;?></strong></p>
<?php }//fin while
	?></div><?php
	}//fin del else de editar
}else{
	$sql = "INSERT INTO Persona (Per_DNI, Per_Doc_ID, Per_Apellido, Per_Nombre, Per_Sexo, Per_Alternativo, Per_Extranjero, Per_Fecha, Per_Hora) VALUES ($DNI, $DocID, '$Apellido', '$Nombre', '$Sexo', '$Alternativo', '$Extranjero', '$Fecha', '$Hora')";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
?><div class="borde_aviso"><span class="texto">Los datos han sido insertados correctamente.</span></div><?php
}

?>
