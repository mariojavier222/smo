<?php
require_once("conexion.php");
//sleep(3);

	$pais = $_POST['pais'];
	$pais = strtoupper(trim(utf8_decode($pais)));
	$sql = "SELECT * FROM Pais WHERE Pai_Nombre = '$pais'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	if (mysqli_num_rows($result)>0){
		?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El nombre de país <strong><?php echo $pais;?></strong> que intenta ingresar ya existe. Por favor verifique la ortografía o elija otro nombre</span>
		<?php
	}else{
		$sql = "INSERT INTO Pais (Pai_Nombre) VALUES ('$pais')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 

		?>
	<div class="borde_aviso"><span class="texto">Los datos del país <strong><?php echo $pais;?></strong> han sido insertados correctamente.</span></div>		
		<?php		
		}
	?>
	
