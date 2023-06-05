<?php
require_once("conexion.php");
//sleep(3);

	$nombreMasc = $_POST['nombre_nuevoMasc'];
	$nombreMasc = strtoupper(trim(utf8_decode($nombreMasc)));
	$nombreMasc = arreglarCadenaMayuscula($nombreMasc);
	$nombreFem = $_POST['nombre_nuevoFem'];
	$nombreFem = strtoupper(trim(utf8_decode($nombreFem)));
	$nombreFem = arreglarCadenaMayuscula($nombreFem);

	$sql = "SELECT * FROM EstudioNivel WHERE Niv_Tit_Mas = '$nombreMasc' AND Niv_Tit_Fem = '$nombreFem'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	if (mysqli_num_rows($result)>0){
		?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El titulo masculino <strong><?php echo $nombreMasc;?></strong> y el titulo femenino <strong><?php echo $nombreFem;?></strong> que intenta ingresar ya existe. Por favor verifique la ortografía o elija otro nombre</span>
		<?php
	}else{
		$sql = "INSERT INTO EstudioNivel (Niv_Tit_Mas, Niv_Tit_Fem) VALUES ('$nombreMasc', '$nombreFem')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 

		?>
	<div class="borde_aviso"><span class="texto">Los datos del Nivel de Estudio para el título masculino <strong><?php echo $nombreMasc;?></strong> y el título femenino <strong><?php echo $nombreFem;?></strong> han sido insertados correctamente.</span></div>
		<?php
		}
	?>