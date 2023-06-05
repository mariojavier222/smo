<?php
require_once("conexion.php");
require_once("listas.php");

	$pais = $_POST['PaisID'];
	$prov = $_POST['ProID'];	
	$localidad = $_POST['localidad'];
	$localidad = strtoupper(trim(utf8_decode($localidad)));
	$localidad = arreglarCadenaMayuscula($localidad);
//echo "$pais - $prov - $localidad";exit;
	$sql = "SELECT * FROM Localidad WHERE Loc_Nombre = '$localidad' AND Loc_Pai_ID = $pais AND Loc_Pro_ID = $prov";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	if (mysqli_num_rows($result)>0){
		?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El nombre de localidad <strong><?php echo $localidad;?></strong> que intenta ingresar ya existe. Por favor verifique la ortografía o elija otro nombre</span>
		<?php
	}else{
		$sql = "INSERT INTO Localidad (Loc_Nombre, Loc_Pai_ID, Loc_Pro_ID) VALUES ('$localidad', '$pais', '$prov')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 

		?>
	<div class="borde_aviso"><span class="texto">Los datos de la localidad <strong><?php echo $localidad;?></strong> han sido insertados correctamente.</span></div>		
		<?php		
		}
	?>