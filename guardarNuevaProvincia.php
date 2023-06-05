<?php
require_once("conexion.php");
require_once("listas.php");

	$pais = $_POST['PaisID'];
	$provincia = $_POST['provincia'];
	$provincia = strtoupper(trim(utf8_decode($provincia)));
	$provincia = arreglarCadenaMayuscula($provincia);

	$sql = "SELECT * FROM Provincia WHERE Pro_Nombre = '$provincia' AND Pro_Pai_ID = $pais";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	if (mysqli_num_rows($result)>0){
		?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El nombre de provincia <strong><?php echo $provincia;?></strong> que intenta ingresar ya existe. Por favor verifique la ortografía o elija otro nombre</span>
		<?php
	}else{
		$sql = "INSERT INTO Provincia (Pro_Nombre, Pro_Pai_ID) VALUES ('$provincia', '$pais')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 

		?>
	<div class="borde_aviso"><span class="texto">Los datos de la provincia <strong><?php echo $provincia;?></strong> han sido insertados correctamente.</span></div>		
		<?php		
		}
	?>
	
	