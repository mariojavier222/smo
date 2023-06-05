<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");

if (isset($_SESSION['sesion_usuario'])){
	$usuario = $_SESSION['sesion_UsuID'];
	if ($usuario == "superadmin"){
		$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($row = mysqli_fetch_array($result)){
		?>
			<h1><a href="#"><?php echo $row['Men_Nombre'];?></a></h1>				
			<div id="Menu"><?php echo cargarOpcionesMenu($row['Men_ID']);?></div>
	
	<?php 	}//fin del while
	}else{
		$sql = "SELECT DISTINCTROW Menu.Men_ID, Menu.Men_Nombre, Permiso.Prm_Usu_ID FROM Permiso
		INNER JOIN Menu 
		ON (Permiso.Prm_Men_ID = Menu.Men_ID)
		WHERE (Permiso.Prm_Usu_ID = '$usuario') ORDER BY Men_Orden";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($row = mysqli_fetch_array($result)){
		?>
			<h1><a href="#"><?php echo $row['Men_Nombre'];?></a></h1>				
			<div id="Menu"><?php echo cargarOpcionesMenuUsuario($row['Men_ID'], $usuario);?></div>
	
	<?php 	}//fin del while
	}//fin if 
}//fin if sesion
	
?>
