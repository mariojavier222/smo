<?php
require_once("conexion.php");
//sleep(3);

	$nombre = $_POST['nombre_nuevo'];
	$nombre = strtoupper(trim(utf8_decode($nombre)));
	$nombre = arreglarCadenaMayuscula($nombre);
	$sql = "SELECT * FROM EstudioEnte WHERE Ent_Nombre = '$nombre'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	if (mysqli_num_rows($result)>0){
		?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El nombre de la Entidad Educativa <strong><?php echo $nombre;?></strong> que intenta ingresar ya existe. Por favor verifique la ortografía o elija otro nombre</span>
		<?php
	}else{
		$sql = "INSERT INTO EstudioEnte (Ent_Nombre) VALUES ('$nombre')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 

		?>
	<div class="borde_aviso"><span class="texto">Los datos de la Entidad Educativa <strong><?php echo $nombre;?></strong> han sido insertados correctamente.</span></div>		
		<?php		
		}
	?>
	
