<?php
require_once("conexion.php");
//sleep(3);
echo "holahola!!!!!!!!!!!!!!!!!" ;
$ID = $_POST['ID'];
$DNI = $_POST['DNI'];
if (!empty($DNI))
	$sql = "SELECT * FROM Persona,PersonaDatos, Localidad, Provincia, Pais where Per_DNI=$DNI and Per_ID=$ID and Dat_Per_ID= Per_Id and  Dat_Dom_Pro_ID=Pro_ID and Dat_Dom_Loc_ID=Loc_ID and Dat_Dom_Pai_ID=Pai_ID";
else 
	echo "La persona no existe, repita las busqueda.";

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
while ($row = mysqli_fetch_array($result)){
	?>
	
	<p><span class="texto">
		<?php echo $row[Loc_Nombre];?>, <?php echo $row[Loc_ID];?>
		</span></p>
		  <?php
	}//fin del while

	echo "$DNI" ;
	echo "$ID" ;
?>
