<?php
require_once("conexion.php");
//sleep(3);
$opcion = $_POST['opcion'];
$nombre = $_POST['Nombre'];
$detalle = $_POST['Detalle'];
$menu = $_POST['Menu'];
switch ($opcion){

case "menu":
	guardarMenu($nombre, $detalle);
	break;

case "opcion":
	guardarOpcion($nombre, $menu, $detalle);
	break;

default: 
	echo "La opción elegida no es válida";


}//fin switch

function guardarMenu($nombre, $detalle){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Menu WHERE Men_Nombre = '$nombre'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	if (mysqli_num_rows($result)>0){
		?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El nombre del menú <strong><?php echo $nombre;?></strong> que intenta ingresar ya existe. Por favor verifique la ortografía o elija otro nombre</span>
		<?php
	}else{
		$sql = "SELECT MAX(Men_Orden) AS Maximo FROM Menu";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array(result);
		$maximo = $row[Maximo] + 1;

		$sql = "INSERT INTO Menu (Men_Nombre, Men_Detalle, Men_Orden) VALUES ('$nombre', '$detalle', $maximo)";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 

		?>
	<div class="borde_aviso"><span class="texto">Los datos del menú <strong><?php echo $nombre;?></strong> han sido insertados correctamente. Por defecto tendrá la última posición.</span></div>		
		<?php		
		}

}//fin funcion

function guardarOpcion($nombre, $menu, $detalle){
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$detalle=filter_var($detalle, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $menu AND Opc_Nombre = '$nombre'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	if (mysqli_num_rows($result)>0){
		?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El nombre de la opción <strong><?php echo $nombre;?></strong> que intenta ingresar para el menú elegido ya existe. Por favor verifique la ortografía o elija otro nombre</span>
		<?php
	}else{
		$sql = "SELECT MAX(Opc_Orden) AS Maximo FROM Opcion WHERE Opc_Men_ID = $menu";
		//echo $sql;
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result);
		$maximo = $row[Maximo] + 1;
		$sql = "INSERT INTO Opcion (Opc_Nombre, Opc_Men_ID, Opc_Comando, Opc_Orden, Opc_Fecha) VALUES ('$nombre', '$menu', '$detalle', $maximo, CURRENT_TIMESTAMP)";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 

		?>
	<div class="borde_aviso"><span class="texto">Los datos de la opción <strong><?php echo $nombre;?></strong> han sido insertados correctamente. Por defecto tendrá la última posición.</span></div>		
		<?php		
		}

}//fin funcion

	?>
	
