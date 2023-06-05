<?php 
require_once("globales.php");
////////////////////////////////////////////////////
//Convierte fecha de mysql a normal
////////////////////////////////////////////////////

//Mario. 17 03 2022 para funciones nuevas de beneficios
include_once("funciones_beneficios.php");


function cfecha($fecha){
	$length = strrpos($fecha," ");
	$newDate = explode( "-" , substr($fecha,$length));
	$lafecha = $newDate[2]."/".$newDate[1]."/".$newDate[0];
    return $lafecha;
}

////////////////////////////////////////////////////
//Convierte fecha de normal a mysql
////////////////////////////////////////////////////

function cambiaf_a_mysql($fecha){
    $lafecha=substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);
    return $lafecha;
} 

// fecha en formato dd/mm/yyyy retorna la diferencia en dias
function restarFecha($dFecIni, $dFecFin){

	$dFecIni = str_replace("/","-",$dFecIni);
	$dFecFin = str_replace("/","-",$dFecFin);

	list($diaI,$mesI,$anioI) = explode("-",$dFecIni);
	list($diaF,$mesF,$anioF) = explode("-",$dFecFin);

	$date1 = mktime(0,0,0,$mesI, $diaI, $anioI);
	$date2 = mktime(0,0,0,$mesF, $diaF, $anioF);
	return round(($date2 - $date1) / (60 * 60 * 24));
}

//funcion para sumar dias a una fecha. Su fundamento es pasar todo a segundos (timestamp), realizar la suma y volver a convertir el resultado a formato de fecha
function sumarFecha($fecha,$ndias){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$anio)=explode("/", $fecha);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$anio)=explode("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$anio) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y",$nueva);
      return ($nuevafecha);  
}

function buscarFoto($DNI, $DocID, $ancho, $usuario=false){

	if (!$usuario){
		$DNI = substr("000000000".$DNI,-9);
		$DNI = $DocID.$DNI;
	}
	//echo $DNI;
	if ($ancho<=60)
		$carpeta = "chica";
	else
		$carpeta = "grande";
		
	//$raiz = $_SERVER['DOCUMENT_ROOT']."/naptacolegios/fotos/$carpeta";
	//$raiz = $_SERVER['DOCUMENT_ROOT']."/steresita/fotos/$carpeta";
	$raiz = $_SERVER['DOCUMENT_ROOT']."/local/cesap/fotos/$carpeta";
	$archivo = $raiz."/$DNI.jpg";
	//echo $archivo;
	$fecha = date("YmdHis");
	
	if (file_exists($archivo))
		return "<a href='#' id='verFotoAlumno$DNI'><img id=foto$DNI src='fotos/$carpeta/$DNI.jpg?$fecha' title='Foto' width='$ancho' border='1' align='absmiddle' class='foto'/></a>";
	else
		return "<img src='fotos/$carpeta/sin_foto.jpg' title='Foto' width='$ancho' border='1' align='absmiddle'/>";

	
}//fin funcion

function buscarProvinciaPaisTotal($ID){

	$sql = "SELECT * FROM Provincia WHERE Pro_Pai_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$total = mysqli_num_rows($result);
	return $total;
	
}//fin funcion


function buscarLocalidadProvinciaTotal($ID, $PaiID){

	$sql = "SELECT * FROM Localidad WHERE Loc_Pro_ID = $ID AND Loc_Pai_ID = $PaiID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$total = mysqli_num_rows($result);
	return $total;
	
}//fin funcion

function obtenerLugar($PaiID, $ProID, $LocID){

	$Pais = obtenerPais($PaiID);
	$Pro = obtenerProvincia($ProID);
	$Loc = obtenerLocalidad($LocID);
	//echo "$PaiID, $ProID, $LocID";
	return "$Loc - $Pro - $Pais";
	
}//fin funcion

function obtenerLocalidad($ID){

	$sql = "SELECT * FROM Localidad WHERE Loc_ID = '$ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Loc_Nombre'];
	}else{
		return "Sin Localidad";
	}
	
}//fin funcion

function obtenerProvincia($ID){

	$sql = "SELECT * FROM Provincia WHERE Pro_ID = '$ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Pro_Nombre'];
	}else{
		return "Sin Provincia";
	}
	
}//fin funcion

function obtenerPais($ID){

	$sql = "SELECT * FROM Pais WHERE Pai_ID = '$ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Pai_Nombre'];
	}else{
		return "Sin Pais";
	}
	
}//fin funcion

function buscarNivelEstudiosTotal($ID){

	$sql = "SELECT * FROM Estudio WHERE Est_Ent_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$total = mysqli_num_rows($result);
	return $total;
	
}//fin funcion

function buscarEntidadEducativaTotal($ID){

	$sql = "SELECT * FROM Estudio WHERE Est_Niv_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$total = mysqli_num_rows($result);
	return $total;
	
}//fin funcion

function buscarSaludo(){
	$hora = date("H");
	if ($hora<12){
		$saludo = "Buenos d&iacute;as ";
		$imagen = "manana";
	}elseif ($hora<20){
		$saludo = "Buenas tardes ";
		$imagen = "tarde";
		}else {
			$saludo = "Buenas noches ";
			$imagen = "noche";
		}
	$imagen = "<img src='imagenes/dia_".$imagen.".gif' align='absmiddle' /> ";
	return "$imagen $saludo";
}

function buscarMenuOpcionesTotal($ID){

	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$total = mysqli_num_rows($result);
	return $total;
	
}//fin funcion

function traerDatosOpcion($ID, &$nombre, &$ruta, &$comando){

	$sql = "SELECT * FROM Opcion WHERE Opc_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$total = mysqli_num_rows($result);
	if ($total>0) {
		$row = mysqli_fetch_array($result);
		$nombre = $row[Opc_Nombre];
		$ruta = $row[Opc_Ruta];
		$comando = $row[Opc_Comando];
	}
	
}//fin funcion


function cargarArbolPermisos($Usuario=""){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<ul id='arbolUsuario' class='unorderedlisttree checkboxtreeactive'>";
	$sql = "SELECT * FROM Unidad WHERE Uni_ID <> 2";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$chk_Visible="checked='checked'";
	$chk_Bloqueado="";
	$chk_Guardar="checked='checked'";
	$chk_Modificar="checked='checked'";
	$chk_Eliminar="checked='checked'";
	$chk_Imprimir="checked='checked'";
	while ($row = mysqli_fetch_array($result)){
		
		
		echo "<li>
			<input type='checkbox' id='Unidad_$row[Uni_ID]' value='$row[Uni_ID]' />
			<label>$row[Uni_Nombre]</label>";
		$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
		$result_menu = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($row_menu = mysqli_fetch_array($result_menu)){

		echo "<ul>
			<li>";
				echo "<input type='checkbox' id='Menu_".$row[Uni_ID]."_".$row_menu[Men_ID]."' value='$row_menu[Men_ID]' />
				<label>$row_menu[Men_Nombre]</label>";
				$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $row_menu[Men_ID] ORDER BY Opc_Orden";
				$result_opcion = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				echo "<ul>";
				while ($row_opcion = mysqli_fetch_array($result_opcion)){
					echo "<li>";
					if ($Usuario){
						$chk_Visible="";
						$chk_Bloqueado="";
						$chk_Guardar="";
						$chk_Modificar="";
						$chk_Eliminar="";
						$chk_Imprimir="";
						$permiso_Visible = buscarPermisoOpcionUsuario($Usuario, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Visible");
						if ($permiso_Visible) $chk_Visible = " checked='checked' ";
						//$permiso_Bloqueado = buscarPermisoOpcionUsuario($Usuario, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Bloqueado");
						//if ($permiso_Bloqueado) $chk_Bloqueado = " checked='checked' ";
						$permiso_Guardar = buscarPermisoOpcionUsuario($Usuario, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Guardar");
						if ($permiso_Guardar) $chk_Guardar = " checked='checked' ";
						$permiso_Modificar = buscarPermisoOpcionUsuario($Usuario, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Modificar");
						if ($permiso_Modificar) $chk_Modificar = " checked='checked' ";
						$permiso_Eliminar = buscarPermisoOpcionUsuario($Usuario, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Eliminar");
						if ($permiso_Eliminar) $chk_Eliminar = " checked='checked' ";
						$permiso_Imprimir = buscarPermisoOpcionUsuario($Usuario, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Imprimir");
						if ($permiso_Imprimir) $chk_Imprimir = " checked='checked' ";
					
					}
					
					echo "<input type='checkbox' id='Opcion_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."' value='$row_opcion[Opc_ID]' />
					<label>$row_opcion[Opc_Nombre]</label>";
					//Cargamos los permisos de los botones por cada opcion
					echo "<ul>";
					//Que la opci�n est� visible para el usuario
					echo "<li><input type='checkbox' $chk_Visible id='Botones_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_a' value='Visible' />
					<label>Visible</label></li>";
					//Que la opci�n est� bloqueada para el usuario, la podr� ver pero al hacer click no hace nada
					//echo "<li><input type='checkbox' id='Botones_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_b' value='Bloqueado' $chk_Bloqueado/>					<label>Bloqueado</label></li>";
					//El usuario podr� Guardar los cambios
					echo "<li><input type='checkbox' $chk_Guardar id='Botones_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_c' value='Guardar' />
					<label>Guardar</label></li>";
					//EL usuario podr� Editar contenido
					echo "<li><input type='checkbox' $chk_Modificar' id='Botones_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_d' value='Modificar' />
					<label>Modificar</label></li>";
					//El usuario podr� Eliminar contenido
					echo "<li><input type='checkbox' $chk_Eliminar id='Botones_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_e' value='Eliminar' />
					<label>Eliminar</label></li>";
					//El usuario podr� Imprimir
					echo "<li><input type='checkbox' $chk_Imprimir id='Botones_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_f' value='Imprimir' />					<label>Imprimir</label></li>";

					echo "</ul>";
					
					
					echo "	</li>";
				}//fin while opcion
				echo "</ul>";
		echo "	</li>
		</ul>";
		}//fin while Menu
		echo "</li>";
	  
		
	}//fin while
	echo "</ul>";
}//fin funcion


function buscarPermisoOpcionUsuario($UsuID, $MenID, $OpcID, $UniID, $campo){

	$sql = "SELECT * FROM Permiso WHERE Prm_Usu_ID = $UsuID AND Prm_Men_ID = $MenID AND Prm_Opc_ID = $OpcID AND Prm_Uni_ID = $UniID AND Prm_$campo = 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0) 
		return true;
	else 
		return false;
}//fin funcion

function cargarArbolPermisosRoles($Rol=""){
	
	echo "<ul id='arbolRol' class='unorderedlisttree checkboxtreeactive'>";
	$sql = "SELECT * FROM Unidad WHERE Uni_ID <> 2";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$chk_Visible="checked='checked'";
	$chk_Bloqueado="";
	$chk_Guardar="checked='checked'";
	$chk_Modificar="checked='checked'";
	$chk_Eliminar="checked='checked'";
	$chk_Imprimir="checked='checked'";
	while ($row = mysqli_fetch_array($result)){
		
		
		echo "<li>
			<input type='checkbox' id='Unidad2_".$row[Uni_ID]."' value='$row[Uni_ID]' />
			<label>$row[Uni_Nombre]</label>";
		$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
		$result_menu = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($row_menu = mysqli_fetch_array($result_menu)){

		echo "<ul>
			<li>";
				echo "<input type='checkbox' id='Menu2_".$row[Uni_ID]."_".$row_menu[Men_ID]."' value='$row_menu[Men_ID]' />
				<label>$row_menu[Men_Nombre]</label>";
				$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $row_menu[Men_ID] ORDER BY Opc_Orden";
				$result_opcion = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				echo "<ul>";
				while ($row_opcion = mysqli_fetch_array($result_opcion)){
					echo "<li>";
					if ($Rol){
						$chk_Visible="";
						$chk_Bloqueado="";
						$chk_Guardar="";
						$chk_Modificar="";
						$chk_Eliminar="";
						$chk_Imprimir="";
						$permiso_Visible = buscarPermisoOpcionRoles($Rol, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Visible");
						if ($permiso_Visible) $chk_Visible = " checked='checked' ";
						//$permiso_Bloqueado = buscarPermisoOpcionRoles($Rol, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Bloqueado");
						//if ($permiso_Bloqueado) $chk_Bloqueado = " checked='checked' ";
						$permiso_Guardar = buscarPermisoOpcionRoles($Rol, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Guardar");
						if ($permiso_Guardar) $chk_Guardar = " checked='checked' ";
						$permiso_Modificar = buscarPermisoOpcionRoles($Rol, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Modificar");
						if ($permiso_Modificar) $chk_Modificar = " checked='checked' ";
						$permiso_Eliminar = buscarPermisoOpcionRoles($Rol, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Eliminar");
						if ($permiso_Eliminar) $chk_Eliminar = " checked='checked' ";
						$permiso_Imprimir = buscarPermisoOpcionRoles($Rol, $row_menu[Men_ID], $row_opcion[Opc_ID], $row[Uni_ID], "Imprimir");
						if ($permiso_Imprimir) $chk_Imprimir = " checked='checked' ";
					
					}
					
					echo "<input type='checkbox' id='Opcion2_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."' value='$row_opcion[Opc_ID]' />
					<label>$row_opcion[Opc_Nombre]</label>";
					//Cargamos los permisos de los botones por cada opcion
					echo "<ul>";
					//Que la opci�n est� visible para el usuario
					echo "<li><input type='checkbox' $chk_Visible id='Botones2_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_a' value='Visible' />
					<label>Visible</label></li>";
					//Que la opci�n est� bloqueada para el usuario, la podr� ver pero al hacer click no hace nada
					//echo "<li><input type='checkbox' $chk_Bloqueado id='Botones2_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_b' value='Bloqueado' />					<label>Bloqueado</label></li>";
					//El usuario podr� Guardar los cambios
					echo "<li><input type='checkbox' $chk_Guardar id='Botones2_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_c' value='Guardar' />
					<label>Guardar</label></li>";
					//EL usuario podr� Editar contenido
					echo "<li><input type='checkbox' $chk_Modificar' id='Botones2_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_d' value='Modificar' />
					<label>Modificar</label></li>";
					//El usuario podr� Eliminar contenido
					echo "<li><input type='checkbox' $chk_Eliminar id='Botones2_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_e' value='Eliminar' />
					<label>Eliminar</label></li>";
					//El usuario podr� Imprimir
					echo "<li><input type='checkbox' $chk_Imprimir id='Botones2_".$row[Uni_ID]."_".$row_menu[Men_ID]."_".$row_opcion[Opc_ID]."_f' value='Imprimir' />					<label>Imprimir</label></li>";

					echo "</ul>";
					
					
					echo "	</li>";
				}//fin while opcion
				echo "</ul>";
		echo "	</li>
		</ul>";
		}//fin while Menu
		echo "</li>";
	  
		
	}//fin while
	echo "</ul>";
}//fin funcion

function buscarPermisoOpcionRoles($RolID, $MenID, $OpcID, $UniID, $campo){

	$sql = "SELECT * FROM RolUnidad WHERE RUn_Rol_ID = $RolID AND RUn_Men_ID = $MenID AND RUn_Opc_ID = $OpcID AND RUn_Uni_ID = $UniID AND RUn_$campo = 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0) 
		return true;
	else 
		return false;
}//fin funcion

function gbuscarPerID($DNI){
	$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Per_ID'];
	}else{
		return 0;
	}

}//fin funcion

function gbuscarDNI($PerID){
	$sql = "SELECT * FROM Persona WHERE Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Per_DNI];
	}else{
		return 0;
	}

}//fin funcion


function gbuscarFTiRelaciona($FTiID, &$Nombre=''){
	$sql = "SELECT * FROM FamiliaTipo WHERE FTi_ID = $FTiID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$Nombre = $row[FTi_Nombre];
	return $row[FTi_Relaciona];

}//fin funcion

/*function gbuscarFTiParentesco($DNI_sup, $DNI_inf){
	$sql = "SELECT * FROM FamiliaTipo WHERE FTi_ID = $FTiID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	return $row[FTi_Relaciona];

}//fin funcion//*/


function gbuscarUsuID($usuario){
	//return $row[Usu_ID];
	$sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$usuario'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Usu_ID];
	}else{
		return 0;
	}

}//fin funcion

function gbuscarPersonaUsuID($UsuID){
	//return $row[Usu_ID];
	$sql = "SELECT * FROM Usuario WHERE Usu_ID = '$UsuID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Usu_Persona];
	}else{
		return 0;
	}

}//fin funcion

function marcarMensajeLeido($DesID){
	$sql = "UPDATE Colegio_MensajeDestino SET Des_Leido = 1 WHERE Des_ID = $DesID";
	//echo $sql;
	$resultado = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (substr($resultado,0,5)!="ERROR") echo "Mensaje marcado como le�do";
}

function cargarMensajesUsuario($UsuID){
	$sql = "SELECT Colegio_MensajeDestino.Des_Para_Usu_ID
    , Colegio_Mensaje.Men_Titulo
    , Colegio_Mensaje.Men_Cuerpo
    , Colegio_MensajeDestino.Des_Leido
    , Colegio_MensajeDestino.Des_Fecha
	, Usu_Nombre
FROM
    Colegio_MensajeDestino
    INNER JOIN Colegio_Mensaje 
        ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID) 
		 INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID)
		WHERE Des_Leido = 0 AND Des_Para_Usu_ID='$UsuID' ORDER BY Des_Fecha DESC, Des_Hora DESC;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			?>
            <table width="80%" align="center" border="0" cellspacing="1" cellpadding="1" class="ui-state-default ui-corner-all">
  <tr>
    <td class="ui-widget-header"><span class="texto"><?php  echo $row[Men_Titulo];?> </span></td>
  </tr>
  <tr>
    <td class="texto"><?php  echo utf8_decode($row[Men_Cuerpo]);?></td>
  </tr>
  <tr>
    <td align="right" class="texto"><hr /><?php  echo "<i>Enviado por $row[Usu_Nombre] el ".cfecha($row[Des_Fecha])."</i>";?></td>
  </tr>
</table><br />


            <?php 
		}
	}else{
		$mostrar = "";
	}//*/
	echo $mostrar;

}//fin funcion

function cargarAlertaFaltaEmail($UsuID){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Usuario WHERE Usu_ID = $UsuID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
	if (mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			if (empty($row['Usu_Email'])){
			?>
            <p><div class="borde_alerta">
            <p><img src="imagenes/Info.png" width="48" height="48" style="alignment-baseline:middle; vertical-align:middle" /><strong>PRESTÁ ATENCIÓN...</strong></p>
            <p>Hola <strong><?php  echo $row[Usu_Persona];?></strong>, seg&uacute;n nuestros registros todav&iacute;a no has completado tu cuenta de correo. Para nosotros es importante que nos brindes una cuenta de correo electr&oacute;nica que uses frecuentemente para que podamos enviarte notificaciones, recordatorios y novedades que surjan en nuestra instituci&oacute;n. Para completarla o cambiarla puedes hacer   <a href="#" class="ui-state-default" id="misDatosPersonales2"> <strong>click aqui</strong></a>.</p>
            </div></p>

            <?php 
			}//fin del if vacio correo
	}//fin if
}//fin funcion

function cargarAlertaVersionBeta(){
	if ($_SESSION['sesion_rol']==11){ //Es Padre de un alumno
	?>
            <p><div class="borde_alerta">
            <p>Hola <strong><?php  echo $_SESSION['sesion_persona'];?></strong>, te comentamos que en estos momentos, <strong>GITECO</strong> se encuentra en fase de Desarrollo. Debido a esto, es posible que algunas opciones que tenga que ver con la parte acad&eacute;mica de tus hijos/as est&eacute;n incompletas o tengan datos cargados err&oacute;neamente. La impresi&oacute;n de las boletas de pago son correctas y los importes relacionados con las deudas son datos reales.</p>
            <p>Te pedimos disculpas y esperamos tener todas esta informaci&oacute;n cargada en forma completa para el a&ntilde;o pr&oacute;ximo. Cualquier sugerencia por parte tuya ser&aacute; muy bienvenida.</p>
            </div></p>

<?php 
	}//fin if
}//fin funcion

function cargarAlertaMensajeUsuario($UsuID){
	$sql = "SELECT COUNT(*) AS total FROM     Colegio_MensajeDestino
    INNER JOIN Colegio_Mensaje 
        ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID) 
		 INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID)
		WHERE Des_Leido = 0 AND Des_Para_Usu_ID='$UsuID' AND Des_Borrado=0 ORDER BY Des_Fecha DESC, Des_Hora DESC;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	if ($row['total']>0){
			?>
            <table width="200px" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="right"><img src="imagenes/recuadro_mensajes/bubble-1.png" width="19" height="15" align="right"></td>
    <td class="borde_mensaje_top"></td>
    <td width="19"><img src="imagenes/recuadro_mensajes/bubble-3.png" width="19" height="15"></td>
  </tr>
  <tr>
    <td class="borde_mensaje_left" align="right"><img src="imagenes/recuadro_mensajes/bubble-tail3.png" width="29" height="30" align="right"></td>
    <td bgcolor="#FFFFFF"><a href='#' id='VerMensajes' class="texto"><img src="imagenes/comments.png" alt="Mensajes sin leer" width="32" height="32" border="0" align="left" longdesc="Mensajes sin leer" /><?php  echo "Usted tiene $row[total] mensajes sin leer.";?></a></td>
    <td class="borde_mensaje_rigth"></td>
  </tr>
  <tr>
    <td><img src="imagenes/recuadro_mensajes/bubble-6.png" width="19" height="29" align="right"></td>
    <td class="borde_mensaje_botton"></td>
    <td><img src="imagenes/recuadro_mensajes/bubble-8.png" width="19" height="29"></td>
  </tr>
</table>


            <?php 
	}//fin if
}//fin funcion

function cargarNoticias(){
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT Colegio_MensajeDestino.Des_Para_Usu_ID
    , Colegio_Mensaje.Men_Titulo
    , Colegio_Mensaje.Men_Cuerpo
    , Colegio_MensajeDestino.Des_Leido
    , Colegio_MensajeDestino.Des_Fecha
	, Men_MTi_ID
	, Usu_Nombre
	, Men_ID
	, Des_ID
FROM
    Colegio_MensajeDestino
    INNER JOIN Colegio_Mensaje 
        ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID) 
		 INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID)
		WHERE Des_Leido = 0 AND Des_Borrado = 0 AND ( Des_Para_Usu_ID='9' OR Des_Para_Usu_ID='$UsuID') AND (Men_MTi_ID = 3 OR Men_MTi_ID = 1) ORDER BY Des_Fecha DESC, Des_Hora DESC LIMIT 0,10;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;exit;
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			mostrarNoticiaSola($row[Des_ID]);
		}//fin while
	}else{
		$mostrar = "";
	}//*/
	echo $mostrar;
	$sql = "SELECT Colegio_MensajeDestino.Des_Para_Usu_ID
    , Colegio_Mensaje.Men_Titulo
    , Colegio_Mensaje.Men_Cuerpo
    , Colegio_MensajeDestino.Des_Leido
    , Colegio_MensajeDestino.Des_Fecha
	, Men_MTi_ID
	, Men_ID
	, Des_ID
FROM
    Colegio_MensajeDestino
    INNER JOIN Colegio_Mensaje 
        ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID) 
		 INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID)
		WHERE Des_Leido = 0 AND Des_Borrado = 0 AND ( Des_Para_Usu_ID='9' OR Des_Para_Usu_ID='$UsuID') AND (Men_MTi_ID = 3 OR Men_MTi_ID = 1) ORDER BY Des_Fecha DESC, Des_Hora DESC LIMIT 10,10;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
	if (mysqli_num_rows($result)>0){
		?>
        <table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
              <tr>
                <td><span class="noticia_titulo">Noticias anteriores</span><br />
        <?php 
		while ($row = mysqli_fetch_array($result)){
		?>
        <a href="#" id="<?php  echo $row[Des_ID];?>" class="texto noticia"><?php  echo $row[Men_Titulo];?></a><br />
		<?php 
		}//fin while</td>
		?>
              </tr>
</table>
        <?php 
	}//fin if

}//fin funcion

function mostrarNoticiaSola($ID, $titulo="", $cuerpo="", $fecha="", $tipo=3){
  if (!empty($ID)){
  $sql = "SET NAMES UTF8";
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  $sql = "SELECT * FROM
	  Colegio_MensajeDestino
	  INNER JOIN Colegio_Mensaje 
		  ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID) 
		  INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID)
		  WHERE Des_ID='$ID';";
	  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  if (mysqli_num_rows($result)>0){
		  $row = mysqli_fetch_array($result);
		  $titulo = $row[Men_Titulo];
		  $cuerpo = $row[Men_Cuerpo];
		  $fecha = $row[Des_Fecha];
		  $tipo = $row[Men_MTi_ID];
		  $DesID = $row[Des_ID];
	  }//fin if
  }//fin if
?>
<table width="90%" align="center" border="0" cellspacing="1" cellpadding="1" class="noticia_borde" id="noticiaID<?php  echo $DesID;?>">
  <tr>
    <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="noticia_fecha"><?php  echo cfecha($fecha);?></span><br />
    <?php  if ($tipo==1){?>
    	<div id="cerraNoticia" align="right" style="float:right"><a href="#" class="cerrarNoticia" id="cerrarID<?php  echo $DesID;?>">[x] marcar como leída</a></div><img src="imagenes/comments.png" width="32" height="32" align="absmiddle" /> 
    <?php  }else{
		?>
        <img src="imagenes/newspaper.png" width="32" height="32" align="absmiddle" /> 
		<?php  }?>
    <span class="noticia_titulo"><?php  echo $titulo;?> </span>    
    </td>
  </tr>
  <tr>
    <td class="texto"><?php  echo $cuerpo;?></td>
  </tr>
  <tr>
    <td align="right" class="texto"><strong><i><?php  if ($tipo==1) echo "enviado por $row[Usu_Persona]";?></i></strong></td>
  </tr>
</table><br />	
<?php 
}//fin function

function tieneDebito($datosCuota,&$Tarjeta){
	// Los delimitadores pueden ser barras, puntos o guiones
	list( $fac, $tch, $chs, $alt, $pro, $cuo ) = explode(";", $datosCuota);
	$sql = "SELECT DebitoTipo.TDe_Nombre FROM siucc.Chequera_Debito INNER JOIN siucc.Persona_Tarjeta 
	ON (Chequera_Debito.ChD_PTa_ID = Persona_Tarjeta.PTa_ID) INNER JOIN siucc.DebitoTipo ON (Persona_Tarjeta.PTa_TDe_ID = DebitoTipo.TDe_ID) WHERE ChD_Fac_ID = $fac AND ChD_TCh_ID = $tch AND ChD_ChS_ID = $chs AND ChD_Alt_ID = $alt AND ChD_Pro_ID = $pro AND ChD_Cuo_ID = $cuo;";
	$result = consulta_mysql($sql);
	if (mysqli_num_rows($result) == 0){
		return false;
	} else {
		$row = mysqli_fetch_array($result);
		$Tarjeta = $row[TDe_Nombre];
		return true;
	}
}//fin funcion

function tieneAlternativas($datosCuota){
	 list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(';', $datosCuota);
	$sql = "SELECT COUNT(DISTINCT(Cuo_Alt_ID)) AS Total FROM CuotaPersona WHERE Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$total = $row[Total];
	return $total;
//	return $sql;
}//fin funcion

function tieneJuicio($DNI){
	$sql = "SELECT * FROM Bloqueado WHERE Blo_Per_ID = $DNI AND Blo_BlT_ID = 2;";
	$result = consulta_mysql($sql);
	if ( mysqli_num_rows($result) > 0 ){
		return true;
	}else {
		return false;
	}
//	return $sql;
}//fin funcion
function buscarIncobrable($DNI){
	$sql = "SELECT * FROM Bloqueado WHERE Blo_Per_ID = $DNI AND Blo_BlT_ID = 5;";
	$result = consulta_mysql($sql);
	//echo $sql;
	if ( mysqli_num_rows($result) > 0 ){
		return true;
	}else {
		return false;
	}
//	return $sql;
}//fin funcion

function buscarTipoDoc($DNI, &$Doc_ID){
	$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI;";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$row = mysqli_fetch_array($result);
	$Doc_ID = $row[Per_Doc_ID];
	//return $row['Doc_Nombre'];
	
}//fin function


function registrarSesion($usuario){
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	
	 if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    $agent = $_SERVER['HTTP_USER_AGENT'];

	$sql = "INSERT INTO Login (Log_Usu_ID, Log_Fecha, Log_Hora, Log_IP, Log_Agent, Log_Status) VALUES 
	('$usuario', '$fecha', '$hora', '$ip', '$agent', 1)";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Login WHERE Log_Fecha = '$fecha' AND Log_Hora = '$hora'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$_SESSION['sesion_LogID'] = $row['Log_ID'];
}//fin fucntion


function gObtenerApellidoNombrePersona($DNI, &$Apellido, &$Nombre, $buscarPerID = false){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if ($buscarPerID)
		$sql = "SELECT * FROM Persona WHERE Per_ID = $DNI";
	else
		$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Apellido = $row[Per_Apellido];
		$Nombre = $row[Per_Nombre];
	}else{
		$Apellido = "DNI Inexistente";
		$Nombre = "DNI Inexistente";
	}

}//fin funcion

function guardarCuentaUsuario($DNI, $clave, $persona, $legajo="", $SedID=1){
	$sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$DNI'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)==0){
		if (empty($legajo)) $legajo = 'NULL';
		//$persona = utf8_encode($persona);		
		  $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		  // Create salted password 
		  $clave = hash('sha512', $clave . $random_salt);
		  $sql = "INSERT INTO Usuario (Usu_Nombre, Usu_Persona, Usu_Clave, Usu_Salt, Usu_Sed_ID) VALUES ('$DNI', '$persona', '$clave', '$random_salt', '$SedID')";
          consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//$sql = "INSERT INTO Usuario(Usu_Nombre, Usu_Persona, Usu_Clave, Usu_Leg_ID, Usu_Sed_ID) VALUES ('$DNI', '$persona', '".md5($clave)."', $legajo, $SedID)";/
	}//fin if
}//fin function


function asignarCuotasAlumno($DNI, $LecID, $CurID, $NivID, $ArTID, $mostrarInfo = true){
global $gCursoSIUCC, $gLectivoSIUCC, $gTChNivel, $gUniFacSIUCC;
	buscarTipoDoc($DNI, $DocID);	
	$Debug = 2222222222222222;
	$CurID = $gCursoSIUCC[$CurID];
	$LecID = $gLectivoSIUCC[$LecID];
	$CH_Colegio = 1;//Indica la clase de chequera, 1 es Colegio
	$GeoID = 1;//Indica la sede en el SIUCC, 1 es San Juan
	$FacID = $gUniFacSIUCC[$_SESSION['sesion_UniID']];//Busco la faculta que corresponde a la Unidad
	//$FacID = 9;//Colegio Nuestra Se�ora del tulum en el SIUCC
	if ($_SESSION['sesion_SedID']==2)
		$TChID = $gTChNivel[4];//Buscamos el tipo de chequera de acuerdo al nivel elegido, barreal es 4
	if ($_SESSION['sesion_SedID']==1)
		$TChID = $gTChNivel[$NivID];//Buscamos el tipo de chequera de acuerdo al nivel elegido
	//*/
	$Fecha = date("Y-m-d");
	if ($ArTID<0) $ArTID=1;
	
	//Buscamos si el alumno existe como Persona en el SIUCC
	$sql = "SELECT * FROM Persona WHERE Per_ID = $DNI AND Per_Doc_ID = $DocID";
	$result = consulta_mysql($sql);
	if (mysqli_num_rows($result)==0){
		echo "<br /><strong>NO SE LE PUEDE GENERAR LAS CUOTAS porque el Alumno no existe como persona en el SIUCC</strong>";
		exit;	
	}
	
	//Primero: Buscamos los tipos de chequeras asignadas al curso del alumno	
	if (!empty($TChID)) $sqlTChID = " AND LCu_TCh_ID = $TChID";
	$sql = "SELECT LCu_TCh_ID FROM Lectivo_Curso
    INNER JOIN Tipo_Chequera 
        ON (Lectivo_Curso.LCu_TCh_ID = Tipo_Chequera.TCh_ID) WHERE LCu_Fac_ID = $FacID AND LCu_Lec_ID = $LecID AND LCu_Cur_ID = $CurID AND TCh_CCh_ID = $CH_Colegio AND TCh_Geo_ID = $GeoID $sqlTChID";
	//echo $sql."<br />";
	if ($_SESSION['sesion_UsuID']==$Debug){
				echo $sql."<br />";//
			}
	$result = consulta_mysql($sql);
	$totalTCh=0;//indica el total de tipos de chequeras a asignar
	while ($row = mysqli_fetch_array($result)){
		$totalTCh++;
		$arrTCh[$totalTCh] = $row[LCu_TCh_ID];
		//echo "Tipo Chequera: ".$arrTCh[$totalTCh]."<br />";
	}//fin while
	
	if ($totalTCh==0){
		if ($mostrarInfo) echo "No existe una configuraci�n de chequera en este momento.";
		exit;
	}
	//Segundo: Buscamos si el alumno ya tiene asignada la chequera. Esto es por si se reinscribe
	for ($i=1;$i<=$totalTCh;$i++){

		$sql = "SELECT * FROM Chequera_Serie WHERE ChS_Per_ID = $DNI AND ChS_Doc_ID = $DocID AND ChS_Fac_ID = $FacID AND ChS_TCh_ID = ".$arrTCh[$i]." AND ChS_Lec_ID = $LecID";
		$result = consulta_mysql($sql);
		if (mysqli_num_rows($result)==0){
			//No tiene asignada la chequera. Procedemos a crearsela
			//echo "No tiene asignada una cuota";
			$ChSID = 1;
			$sql = "SELECT ChS_ID FROM Chequera_Serie WHERE ChS_Fac_ID = $FacID AND ChS_TCh_ID = ".$arrTCh[$i]." ORDER BY ChS_ID DESC";
			$result = consulta_mysql($sql);
			$row = mysqli_fetch_array($result);
			if (mysqli_num_rows($result)>0) $ChSID = intval($row[ChS_ID]) + 1;
			
			//Insertamos la chequera
			$sql = "INSERT INTO Chequera_Serie (ChS_Fac_ID, ChS_ID, ChS_Per_ID, ChS_Doc_ID, ChS_Lec_ID, ChS_TCh_ID, ChS_Cur_ID, ChS_Geo_ID, ChS_Fecha) VALUES ($FacID, $ChSID, $DNI, $DocID, $LecID, ".$arrTCh[$i].", $CurID, $GeoID, '$Fecha');";
			
			if ($_SESSION['sesion_UsuID']==$Debug){
				echo $sql."<br />";//
			}else{
				consulta_mysql($sql);
			}
			
			//Insertamos el total de la chequera por producto
			$sql = "INSERT IGNORE INTO  Chequera_Total (ChT_Fac_ID, ChT_TCh_ID, ChT_ChS_ID, ChT_Pro_ID, ChT_Total)
			SELECT LeT_Fac_ID, ".$arrTCh[$i].", $ChSID, LeT_Pro_ID, ROUND(LeT_Total * ArP_Porcentaje / 100) FROM Lectivo_Total, Arancel_Porcentaje WHERE LeT_Fac_ID = $FacID AND LeT_Lec_ID = $LecID AND LeT_TCh_ID = ".$arrTCh[$i]." AND ArP_ArT_ID = $ArTID  AND LeT_Pro_ID = ArP_Pro_ID";
			if ($_SESSION['sesion_UsuID']==$Debug){
				echo $sql."<br />";//
			}else{
				consulta_mysql($sql);
			}

			//Insertamos las alternativas de la chequera por producto
			$sql = "INSERT IGNORE INTO  Chequera_Alternativa (ChA_Fac_ID, ChA_TCh_ID, ChA_ChS_ID, ChA_Pro_ID, ChA_Alt_ID, ChA_Titulo, ChA_Cuotas)
			SELECT LeA_Fac_ID, ".$arrTCh[$i].", $ChSID, LeA_Pro_ID, LeA_Alt_ID, LeA_Titulo, LeA_Cuotas FROM Lectivo_Alternativa WHERE LeA_Fac_ID = $FacID AND LeA_Lec_ID = $LecID AND LeA_TCh_ID = ".$arrTCh[$i];
			if ($_SESSION['sesion_UsuID']==$Debug){
				echo $sql."<br />";//
			}else{
				consulta_mysql($sql);
			}
			
			$sql = "INSERT IGNORE INTO  Chequera_Cuota (ChC_Fac_ID, ChC_TCh_ID, ChC_ChS_ID, ChC_Pro_ID, ChC_Alt_ID, ChC_Cuo_ID, ChC_Mes, ChC_Anio, ChC_1er_Vencimiento, ChC_1er_Importe_Original, ChC_1er_Importe, ChC_2do_Vencimiento, ChC_2do_Importe_Original, ChC_2do_Importe, ChC_3er_Vencimiento, ChC_3er_Importe_Original, ChC_3er_Importe)
			SELECT LeC_Fac_ID, ".$arrTCh[$i].", $ChSID, LeC_Pro_ID, LeC_Alt_ID, LeC_Cuo_ID, LeC_Mes, LeC_Anio, LeC_1er_Vencimiento, LeC_1er_Importe, ROUND(LeC_1er_Importe * ArP_Porcentaje / 100), LeC_2do_Vencimiento, LeC_2do_Importe, ROUND(LeC_2do_Importe * ArP_Porcentaje / 100), LeC_3er_Vencimiento, LeC_3er_Importe, LeC_3er_Importe FROM Lectivo_Cuota, Arancel_Porcentaje WHERE LeC_Fac_ID = $FacID AND LeC_Lec_ID = $LecID AND LeC_TCh_ID = ".$arrTCh[$i]." AND ArP_ArT_ID = $ArTID  AND Lec_Pro_ID = ArP_Pro_ID";
			if ($_SESSION['sesion_UsuID']==$Debug){
				echo $sql."<br />";//
			}else{
				consulta_mysql($sql);
			}
			
			$sql = "INSERT IGNORE INTO  Chequera_Beneficio (ChB_Fac_ID, ChB_TCh_ID, ChB_ChS_ID, ChB_Pro_ID, ChB_Alt_ID, ChB_Cuo_ID, ChB_Mes, ChB_Anio, ChB_ArT_ID)
			SELECT LeC_Fac_ID, ".$arrTCh[$i].", $ChSID, LeC_Pro_ID, LeC_Alt_ID, LeC_Cuo_ID, LeC_Mes, LeC_Anio, $ArTID FROM Lectivo_Cuota WHERE LeC_Fac_ID = $FacID AND LeC_Lec_ID = $LecID AND LeC_TCh_ID = ".$arrTCh[$i];
			if ($_SESSION['sesion_UsuID']==$Debug){
				echo $sql."<br />";//
			}else{
				consulta_mysql($sql);
			}

			if ($ArTID>1){
				//Guardamos los datos del beneficio si el alumno lo tuviera.
				$porc = traerPorcentajeSIUCC($ArTID, $ProID);
				$sql = "INSERT INTO Beneficios (Ben_Per_ID, Ben_Doc_ID, Ben_Fac_ID, Ben_TCh_ID, Ben_ChS_ID, Ben_Lec_ID, Ben_Pro_ID, Ben_ArT_ID, Ben_Desde, Ben_Hasta, Ben_Porc, Ben_Fecha, Ben_Operario) VALUES ($DNI, $DocID, $FacID, ".$arrTCh[$i].", $ChSID, $LecID, $ProID, $ArTID, '$Fecha', '$Fecha', '$porc', '$Fecha', '".$_SESSION['sesion_usuario']."')";
				if ($_SESSION['sesion_UsuID']==$Debug){
					echo $sql."<br />";//
				}else{
					consulta_mysql($sql);
				}

			}//fin if
			
			if ($mostrarInfo) echo "<br /><strong>Se ha generado una nueva chequera al alumno.</strong>";
			
		}//fin del if
		else{
			if ($mostrarInfo) echo "<br /><strong>El alumno ya tiene generada la chequera de inscripci�n.</strong>";
		}
	}//fin del for
	

}//fin function


function tieneBajaAcademica($DNI, $DocID, $LecID, $Nivel){//$Nivel: Colegio, StaMaria
	
	$sql = "SELECT * FROM ColegioBajas WHERE Col_Per_ID = $DNI AND Col_Doc_ID = $DocID AND Col_Lec_ID = $LecID;";
	$result = consulta_mysql($sql);
	if ( mysqli_num_rows($result) > 0 ){
		return true;
	}else {
		$sql = "SELECT * FROM
    Legajo
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
WHERE (Persona.Per_DNI = $DNI AND Leg_Baja = 1 AND Leg_$Nivel = 1)";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if ( mysqli_num_rows($result) > 0 ){
			return true;
		}else {		
			return false;
		}
	}
//	return $sql;
}//fin funcion

function actualizarCuotasAlumnoCursado($PerID, $LecID, $NivID, $masivo){
	
	//Buscamos si el alumno fue dado de baja
	/*
	if (tieneBajaAcademica($DNI, $DocID, $gLectivoSIUCC[$LecID], "Colegio")){
		echo "ERROR: El Alumno con DNI $DNI tiene BAJA ACADEMICA.<br />";
		exit;
	}//*/
	
	//Revisamos que todas las configuraciones de cuotas se encuentren generadas previamente, por si se agregaron nuevas configuraciones despu�s de la inscripci�n
	//Este item fue quitado por seguridad hasta que no se haga una estructura que soporte multiple asignacion de chequeras
	//asignarCuotasAlumno($DNI, $LecID, $CurID, $NivID, $ArTID, false);
	
	$Debug = 22222222222222222222222222222222222222222222222;

	$Fecha = date("Y-m-d");

	guardarAsignacionCuota($PerID, $LecID, $NivID, $masivo);	
	

}//fin function

function asignarCuotasEspecial($PerID, $CMoID){
	//echo $CMoID;exit;
	list($CMo_Lec_ID,$CMo_Niv_ID,$CMo_CTi_ID,$CMo_Alt_ID, $CMo_Numero) = explode(";", $CMoID);
	
	//echo "$CMo_Lec_ID,$CMo_Niv_ID,$CMo_CTi_ID,$CMo_Alt_ID";exit;
	
	$Debug = 22222222222222222222222222222222222222222222222;

	$Fecha = date("Y-m-d");

	guardarAsignacionCuotaEspecial($PerID, $CMo_Lec_ID, $CMo_Niv_ID, $CMo_CTi_ID, $CMo_Alt_ID, $CMo_Numero);	
	

}//fin function

function actualizarImporteCuotasAlumno($PerID, $LecID, $NivID){
	
		
	$Debug = 22222222222222222222222222222222222222222222222;

	$Fecha = date("Y-m-d");
	//echo $PerID."-".$LecID."-".$NivID;
	actualizarImporteCuota($PerID, $LecID, $NivID);	
	

}//fin function

function obtenerRegistroUsuario(&$UsuID, &$Fecha, &$Hora){
	date_default_timezone_set('America/Argentina/San_Juan');
	if (!isset($_SESSION['sesion_UsuID'])){
		$UsuID=1;
	}else{
		$UsuID = $_SESSION['sesion_UsuID'];	
	}	
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");

}//fin function

function obtenerEdad($PerID, &$fechaNac=""){

	$sql = "SELECT Dat_Nacimiento FROM PersonaDatos WHERE Dat_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$fechaHoy = date("d/m/Y");
		if (empty($row['Dat_Nacimiento']) || $row['Dat_Nacimiento']=="0000-00-00"){
			return 0;
			exit;
		}
		$fechaNac = cfecha($row['Dat_Nacimiento']);
		$dias = restarFecha($fechaNac, $fechaHoy);
		return intval($dias/365);
	}else return 0;
	
}//fin funcion

function obtenerTelefono($PerID, &$Celular){

	$sql = "SELECT Dat_Telefono, Dat_Celular FROM PersonaDatos WHERE Dat_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);				
		$Celular = $row[Dat_Celular];		
		return $row[Dat_Telefono];
	}else return "No cargado";
	
}//fin funcion

function obtenerDatosClase($ClaID, &$Nivel, &$Materia, &$Curso, &$Division, &$NivID='', &$LecID=''){

	$sql = "SELECT * FROM Colegio_Clase
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID)
    INNER JOIN Curso 
        ON (Colegio_Clase.Cla_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Clase.Cla_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Clase.Cla_Niv_ID = Colegio_Nivel.Niv_ID) WHERE Cla_ID = $ClaID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);						
		$NivID = $row[Cla_Niv_ID];
		$LecID = $row[Cla_Lec_ID];
		$Nivel = $row[Niv_Nombre];
		$Curso = $row[Cur_Nombre];
		$Division = $row[Div_Nombre];
		$Materia = $row[Mat_Nombre];
		
	}
	
}//fin funcion


function Obtener_Grupo_Familiar($PerID, $mostrarDNI = false){

	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID) WHERE Fam_Per_ID = $PerID ORDER BY FTi_ID, Per_Apellido, Per_Nombre;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$texto_dni = '';
	while ($row = mysqli_fetch_array($result)){
		if ($mostrarDNI) $texto_dni = $row['Per_DNI'];
		if ($row['Per_Sexo']=='M'){
			$texto_relacion = $row['FTi_M'];
		}else{
			$texto_relacion = $row['FTi_F'];
		}
		$mostrar .= "($texto_relacion) $texto_dni <strong>$row[Per_Apellido], $row[Per_Nombre]</strong><br />";
	
	}//fin while
	return $mostrar;
	
}//fin funcion

function Obtener_DatosInscripcionLectivo($DNI, $LecID){

	$sql = "SELECT
    Persona.Per_DNI
    , Curso.Cur_Nombre
    , Colegio_Nivel.Niv_Nombre
    , Division.Div_Nombre
    , Colegio_Inscripcion.Ins_Lec_ID
FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID) WHERE Per_DNI = $DNI AND Ins_Lec_ID = $LecID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$mostrar = "$row[Niv_Nombre];$row[Cur_Nombre];$row[Div_Nombre]";	
	}//fin if
	return $mostrar;
	
}//fin funcion

function Obtener_LectivoActual(&$LecID, &$Lec_Nombre){

	$sql = "SELECT
	Con_Lec_ID
    , Lectivo.Lec_Nombre
    , Configuracion.Con_Uni_ID FROM
    Configuracion
    INNER JOIN Lectivo 
        ON (Configuracion.Con_Lec_ID = Lectivo.Lec_ID) WHERE Con_Uni_ID = ".$_SESSION['sesion_UniID'].";";
		//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$LecID = $row[Con_Lec_ID];
		$Lec_Nombre = $row[Lec_Nombre];
	}//fin if
	
}//fin funcion

function Obtener_LectivoNombre($LecID){

	$sql = "SELECT Lectivo.Lec_Nombre
    FROM Lectivo WHERE Lec_ID = ".$LecID.";";
		//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Lec_Nombre];
	}//fin if
	
}//fin funcion

function gLectivoActual(&$Lec_Nombre){

	$sql = "SELECT Lec_ID, Lectivo.Lec_Nombre FROM Lectivo 
        WHERE Lec_Actual = 1;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$LecID = $row['Lec_ID'];
		$Lec_Nombre = $row['Lec_Nombre'];
		return $LecID;
	}//fin if
	
}//fin funcion


function obtenerDatosTitulo($TitID, &$CarID, &$PlaID){

	$sql = "SELECT * FROM Titulo WHERE Tit_ID = $TitID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$CarID = $row[Tit_Car_ID];
		$PlaID = $row[Tit_Pla_ID];
	}//fin if
	
}//fin funcion//*/

function buscarSexoPersona($DNI){

	$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Per_Sexo];
	}//fin if
	
}//fin funcion//*/

function guardarRolUsuario($UsuID, $Rol){

	$sql = "SELECT * FROM RolUsuario WHERE RUs_Usu_ID = '$UsuID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
		$sql = "INSERT INTO RolUsuario (RUs_Usu_ID, RUs_Rol_ID) VALUES ('$UsuID', '$Rol')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}else{
		$sql = "UPDATE RolUsuario SET RUs_Rol_ID = '$Rol' WHERE RUs_Usu_ID = '$UsuID'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
	
	$sql = "SELECT * FROM RolUnidad WHERE RUn_Rol_ID = '$Rol'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		$sql = "SELECT * FROM Permiso WHERE Prm_Usu_ID = '$UsuID' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
		$result_permiso = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result_permiso)>0){
			//ya existe, actualizamos
			$sql = "UPDATE Permiso SET Prm_Visible = '$row[RUn_Visible]', Prm_Bloqueado = '$row[RUn_Bloqueado]', Prm_Guardar = '$row[RUn_Guardar]', Prm_Modificar = '$row[RUn_Modificar]', Prm_Eliminar = '$row[RUn_Eliminar]', Prm_Imprimir = '$row[RUn_Imprimir]' WHERE Prm_Usu_ID = '$UsuID' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}else{
			//Insertamos el nuevo permiso
			$sql = "INSERT INTO Permiso (Prm_Usu_ID, Prm_Men_ID, Prm_Opc_ID, Prm_Uni_ID, Prm_Visible, Prm_Bloqueado, Prm_Guardar, Prm_Modificar, Prm_Eliminar, Prm_Imprimir) VALUES ('$UsuID', '$row[RUn_Men_ID]', '$row[RUn_Opc_ID]', '$row[RUn_Uni_ID]', '$row[RUn_Visible]', '$row[RUn_Bloqueado]', '$row[RUn_Guardar]', '$row[RUn_Modificar]', '$row[RUn_Eliminar]', '$row[RUn_Imprimir]')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}
		
	}//fin del while
	

}//fin funcion


function enviarEmail($asunto, $cuerpo, $para, $nombre, $from = "infocolegio@uccuyo.edu.ar", $nombreFrom = "Notificaciones Colegio Ntra. Sra. del Tulum"){	
		
	
	error_reporting(E_ALL);
	
	/*require("php4_Mail.php");
	require("php4_AttachmentMail.php");
	require("php4_Multipart.php");
	*/
	$addBCC = "fabricio.echegaray@sistemas.uccuyo.edu.ar";
	$msgOK = "Mensaje enviado correctamente";
	$msgFAILED = "El Mensaje ha fallado";
	
	/**
	 * Send simple mails in several formats
	 * Send 1: High priority and content in HTML (already have <html> tags)
	 * Send 2: Normal priority and content in complete HTML (without have <html> tags)
	 * Send 3: Low Priority and content in text format
	 */
	$mail = new Mail($para, $asunto, $nombreFrom, $from);
	
	$mail->setBodyHtml(utf8_encode($cuerpo));
	$mail->addBCC($addBCC);
	$mail->addBCC("seccionacademica@uccuyo.edu.ar");
	$mail->addBCC("administracioncolegios@uccuyo.edu.ar");
	$mail->setPriority(ABSTRACTMAIL_HIGH_PRIORITY);
	if ($mail->send())
		return true;//echo $msgOK;
	else
		return false;//echo $msgFAILED;	
	
	
}//fin function //*/

function actualizarCorreo($Usuario, $Correo){
	//echo "Hola";exit;

	$sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$Usuario'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
		$sql = "UPDATE Usuario SET Usu_Email = '$Correo' WHERE Usu_Nombre = '$Usuario'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}

}//fin funcion

function validarVacio($texto){
	if (empty($texto))
		return "------";
	else
		return $texto;
}//fin function

function Obtener_NombreChequera($TChID){

	$sql = "SELECT * FROM Tipo_Chequera WHERE TCh_ID = $TChID;";
	$result = consulta_mysql($sql);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[TCh_Nombre];
	}//fin if
	return "";
	
}//fin funcion

function obtenerCarreraPlan($TitID, &$CarID, &$PlaID){
	//echo "Hola";exit;

		$sql = "SELECT * FROM
    Titulo
    INNER JOIN Carrera 
        ON (Titulo.Tit_Car_ID = Carrera.Car_ID)
    INNER JOIN Plan 
        ON (Titulo.Tit_Pla_ID = Plan.Pla_ID) WHERE Tit_ID = '$TitID' ";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
			echo "{}";
		}else{
			$row = mysqli_fetch_array($result);
			$CarID = $row[Tit_Car_ID];
			$PlaID = $row[Tit_Pla_ID];
		}

}//fin funcion

function cargarListadoContactos($nombre = ""){
	$sql = "SELECT * FROM Autoridad WHERE Aut_Uni_ID = ".$_SESSION['sesion_UniID']." AND Aut_ID=10 ORDER BY Aut_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		if (!empty($nombre)){//El listado se hace en forma de combo
			?>
			<select id="<?php  echo $nombre;?>">
			<?php 
			while ($row = mysqli_fetch_array($result)){
				?>
				<option value="<?php  echo $row[Aut_ID];?>"><?php  echo $row[Aut_Cargo];?></option>
				<?php 
			}//fin while
			?>
			</select>
			<?php 
		}else{//El listado se construye en forma de tablas
			
		}//fin if else
	}//fin if
	
}//fin function

function MostrtarUsuariosConectados(){
	$UsuID = $_SESSION['sesion_UsuID'];
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");
	if (!empty($UsuID)){
		$sql = "SELECT DISTINCTROW Usu_Persona FROM
    AccesoOpcion
    INNER JOIN Login 
        ON (AccesoOpcion.Acc_Log_ID = Login.Log_ID)
    INNER JOIN Usuario 
        ON (Login.Log_Usu_ID = Usuario.Usu_ID) WHERE Log_Usu_ID = Usu_ID AND Log_Usu_ID <> '$UsuID' AND Log_Fecha = '$Fecha' AND DATE_SUB(NOW(),INTERVAL 5 MINUTE) <= Acc_Hora";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
//			echo "<ul>";
			while ($row = mysqli_fetch_array($result)){
				echo "<li>$row[Usu_Persona]</li>";
			}
//			echo "</ul>";
		}
	}//fin if

}//fin funcion


function enviarCorreoMensajeUsuario($UsuID, $Para, $Asunto, $Cuerpo){
	
	$Cuerpo = "Esta es una notificaci�n autom�tica para informarle que tiene un mensaje sin leer en el GITECO.<br />
<i>El mismo fue enviado a las ".date("H:i:s")." del d&iacute;a ".date("d-m-Y")."</i><br />----------------------------------------------------------------------------------------------------------------------------------------<br /><br />$Cuerpo";
	$Asunto = "Nuevo mensaje en el GITECO: $Asunto";
	if (traerCorreoUsuario($UsuID) && traerCorreoUsuario($Para)){
		enviarEmail($Asunto, $Cuerpo, traerCorreoUsuario($Para), gbuscarPersonaUsuID($Para));
	}
	

}//fin function


function traerCorreoUsuario($UsuID){
	
	$sql = "SELECT * FROM Usuario WHERE Usu_ID = $UsuID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		if (empty($row[Usu_Email]))
			return false;
		else
			return $row[Usu_Email];
	}//fin if
}

function traerRolUsuario($UsuID, &$RolID){
	
	$sql = "SELECT * FROM RolUsuario, Roles2 WHERE RUs_Rol_ID = Rol_ID AND RUs_Usu_ID = $UsuID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		
		$row_rol = mysqli_fetch_array($result);
		$RolID = $row_rol[RUs_Rol_ID];
		return $row_rol[Rol_Nombre];
	
	}else{
		$RolID = 0;
		return "No tiene un rol asignado";
	}//*/

}//fin function

function obtenerLimitesLectivo($LecID, &$LecDesde, &$LecHasta){
	$sql = "SELECT * FROM Lectivo WHERE Lec_ID = $LecID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$LecDesde = $row[Lec_Desde];
		$LecHasta = $row[Lec_Hasta];	
	}
}//fin function

function obtenerTutor($PerID, &$DNITutor, &$PerIDTutor){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) WHERE Fam_Vin_Per_ID = $PerID AND Fam_FTi_ID = 2 ORDER BY FTi_ID, Per_Sexo DESC, Per_Apellido, Per_Nombre;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$mostrar = "$row[Per_Apellido], $row[Per_Nombre]";
		$DNITutor = $row['Per_DNI'];
		$PerIDTutor = $row['Per_ID'];
		
		
	}else{
		$mostrar = "No cargado";
		$DNITutor = "";
		$PerIDTutor = "";
	}
	//echo $sql;
	return $mostrar;
	
	
}//fin funcion

function obtenerTutores($PerID, &$arrarTutores, &$i){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)		
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) 
	WHERE Fam_Vin_Per_ID = $PerID AND Fam_FTi_ID = 2 ORDER BY FTi_ID, Per_Sexo DESC, Per_Apellido, Per_Nombre;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$i = 0;
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			$i++;
			$arrarTutores[$i]['Per_Apellido'] = $row[Per_Apellido];
			$arrarTutores[$i]['Per_Nombre'] = $row[Per_Nombre];
			$arrarTutores[$i]['Per_DNI'] = $row[Per_DNI];
			$arrarTutores[$i]['Per_ID'] = $row[Per_ID];
			$arrarTutores[$i]['Telefonos'] = buscarTelefonosPersona($row[Per_ID]);
			$arrarTutores[$i]['CelularSMS'] = buscarCeluarPersonaSMS($row['Per_ID']);
			$arrarTutores[$i]['Per_Sexo'] = $row[Per_Sexo];
			$arrarTutores[$i]['Email'] = buscarEmailPersona($row['Per_ID']);
			//$FTR = gbuscarFTiRelacionaTipo(2, $Nombre);
			//gbuscarFTiRelacionaTipo(2, $Nombre, $FTi_M, $FTi_F);
			if ($row[Per_Sexo]=="M")
				$arrarTutores[$i]['FTi_Tipo'] = "Papá";
			else
				$arrarTutores[$i]['FTi_Tipo'] = "Mamá";
		}
	}
	//echo $sql;
	//return $mostrar;
	
	
}//fin funcion

function obtenerHermanos($PerID, &$arrarHermanos, &$i){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)		
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) 
	WHERE Fam_Vin_Per_ID = $PerID AND Fam_FTi_ID = 3 ORDER BY FTi_ID, Per_Sexo DESC, Per_Apellido, Per_Nombre;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$i = 0;
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			$i++;
			$arrarHermanos[$i]['Per_Apellido'] = $row['Per_Apellido'];
			$arrarHermanos[$i]['Per_Nombre'] = $row['Per_Nombre'];
			$arrarHermanos[$i]['Per_DNI'] = $row['Per_DNI'];
			$arrarHermanos[$i]['Per_ID'] = $row['Per_ID'];
			$arrarHermanos[$i]['Telefonos'] = buscarTelefonosPersona($row['Per_ID']);
			$arrarHermanos[$i]['Per_Sexo'] = $row['Per_Sexo'];
			//$FTR = gbuscarFTiRelacionaTipo(2, $Nombre);
			//gbuscarFTiRelacionaTipo(2, $Nombre, $FTi_M, $FTi_F);
			if ($row['Per_Sexo']=="M")
				$arrarHermanos[$i]['FTi_Tipo'] = "Hermano";
			else
				$arrarHermanos[$i]['FTi_Tipo'] = "Hermana";
		}
	}
	//echo $sql;
	//return $mostrar;
	
	
}//fin funcion

function obtenerHijos_2($PerID, &$arrayHijos, &$i){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)		
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) 
	WHERE Fam_Vin_Per_ID = $PerID AND Fam_FTi_ID = 1 ORDER BY FTi_ID, Per_Sexo DESC, Per_Apellido, Per_Nombre;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$i = 0;
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			$i++;
			$arrayHijos[$i]['Per_Apellido'] = $row['Per_Apellido'];
			$arrayHijos[$i]['Per_Nombre'] = $row['Per_Nombre'];
			$arrayHijos[$i]['Per_DNI'] = $row['Per_DNI'];
			$arrayHijos[$i]['Per_ID'] = $row['Per_ID'];
			$arrayHijos[$i]['Telefonos'] = buscarTelefonosPersona($row['Per_ID']);
			$arrayHijos[$i]['Per_Sexo'] = $row['Per_Sexo'];
			//$FTR = gbuscarFTiRelacionaTipo(2, $Nombre);
			//gbuscarFTiRelacionaTipo(2, $Nombre, $FTi_M, $FTi_F);
			if ($row['Per_Sexo']=="M")
				$arrayHijos[$i]['FTi_Tipo'] = "Hijo";
			else
				$arrayHijos[$i]['FTi_Tipo'] = "Hija";
		}
	}
	//echo $sql;
	//return $mostrar;
	
	
}//fin funcion

function gbuscarFTiRelacionaTipo($FTiID, &$Nombre, &$FTi_M, &$FTi_F){
	$sql = "SELECT * FROM FamiliaTipo WHERE FTi_ID = $FTiID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$Nombre = $row[FTi_Nombre];
	$FTi_M = $row[FTi_M];
	$FTi_F = $row[FTi_F];
	return $row[FTi_Relaciona];

}//fin funcion

function obtenerDatosNacimiento($PerID, &$fechaNac, &$PaiID, &$ProID, &$LocID){

	$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = '$PerID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);	
		//return $row['Dat_Nacimiento'];	
		$fechaNac = cfecha($row['Dat_Nacimiento']);
		$PaiID = $row['Dat_Nac_Pai_ID'];
		$ProID = $row['Dat_Nac_Pro_ID'];
		$LocID = $row['Dat_Nac_Loc_ID'];		
	}else return 0;
	
}//fin funcion

function obtenerTrimestreLectivo($TriID){

	$sql = "SELECT * FROM
    Colegio_Trimestre
    INNER JOIN Lectivo 
        ON (Colegio_Trimestre.Tri_Lec_ID = Lectivo.Lec_ID) WHERE Tri_ID = $TriID ORDER BY Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
			echo $row[Lec_Nombre];
	
	}else{
		echo "NO HAY LECTIVO";
	}
}

function obtenerTrimestreNivel($TriID){

	$sql = "SELECT * FROM
    Colegio_Trimestre
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Trimestre.Tri_Niv_ID = Colegio_Nivel.Niv_ID) WHERE Tri_ID = $TriID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
			echo $row[Niv_Nombre];	
	}else{
		echo "NO HAY NIVEL";
	}
}

function obtenerTrimestreNivelLectivo($TriID, &$LecID, &$NivID){

	$sql = "SELECT * FROM
    Colegio_Trimestre
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Trimestre.Tri_Niv_ID = Colegio_Nivel.Niv_ID)
	INNER JOIN Lectivo 
        ON (Colegio_Trimestre.Tri_Lec_ID = Lectivo.Lec_ID) WHERE Tri_ID = $TriID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$LecID = $row[Tri_Lec_ID];
		$NivID = $row[Tri_Niv_ID];
	}
}

function obtenerDocIDDocente($PerID){
	$sql = "SELECT * FROM
    Colegio_Docente WHERE Doc_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Doc_ID];
	}

}//fin funcion

function obtenerDNIDocente($DocID){
	$sql = "SELECT * FROM
    Colegio_Docente
	INNER JOIN Persona ON Doc_Per_ID = Per_ID
	WHERE Doc_ID = $DocID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Per_DNI];
	}

}//fin funcion


function traerHorarioClase($ClaID){
	$sql = "SELECT Colegio_Dia.Dia_Siglas, Colegio_Horario.Hor_Inicio, Colegio_Horario.Hor_Fin FROM
    Colegio_Horario
    INNER JOIN Colegio_Dia ON (Colegio_Horario.Hor_Dia_ID = Colegio_Dia.Dia_ID) WHERE Hor_Cla_ID = $ClaID;";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			if (!empty($mostrar)) $mostrar .= "<br />";
			$mostrar .= "$row[Dia_Siglas] ".formatoHora($row[Hor_Inicio])." a ".formatoHora($row[Hor_Fin]);
		}//fin while
	}else{
		$mostrar = "No asignado";	
	}
	return $mostrar;

}

function formatoHora($hora){
	return substr($hora,0,strlen($hora)-3);
}

function buscarNivelDocente($DocID, $LecID){
	$sql = "SELECT DISTINCTROW Cla_Niv_ID FROM
    Colegio_Clase WHERE Cla_Doc_ID = $DocID AND Cla_Lec_ID = $LecID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Cla_Niv_ID];
	}

}//fin funcion

function my_array_search($needle, $haystack) {
        if (empty($needle) || empty($haystack)) {
            return false;
        }
       
        foreach ($haystack as $key => $value) {
            $exists = 0;
            foreach ($needle as $nkey => $nvalue) {
                if (!empty($value[$nkey]) && $value[$nkey] == $nvalue) {
                    $exists = 1;
                } else {
                    $exists = 0;
                }
            }
            if ($exists) return $key;
        }
       
        return false;
}

function cargarNotaClaseAlumnoTabla($LegID=0, $LecID=0, $ClaID=0, $CiaID=0){
	//echo "Hola";//exit;
	if ($LegID == 0 && $LecID == 0){
		$LegID = $_POST['LegID'];
		$LecID = $_POST['LecID'];
		$ClaID = $_POST['ClaID'];
		$CiaID = $_POST['CiaID'];
	}
		
	$sql = "SELECT * FROM Colegio_Evaluacion 
	 INNER JOIN Colegio_Notas 
        ON (Colegio_Evaluacion.Eva_Not_ID = Colegio_Notas.Not_ID)
	 INNER JOIN Colegio_NotasTipo 
        ON (Colegio_Notas.Not_NTi_ID = Colegio_NotasTipo.NTi_ID)
	WHERE Eva_Leg_ID = $LegID AND Eva_Lec_ID = $LecID AND Eva_Cla_ID = $ClaID AND Eva_Cia_ID = $CiaID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		echo "<table border='0' align='left' cellpadding='1' cellspacing='1'><tr>";
		while ($row = mysqli_fetch_array($result)){			
			$clase = "nota_".$row[NTi_Nombre];
			echo "<td width='15' align='center' class='$clase'><strong>$row[Not_Siglas]</strong></td>";
		}//fin while
		echo "</tr></table>";
	}	
						

}//fin funcion//*/

function obtenerPromedioTrimestre($LegID=0, $LecID=0, $ClaID=0, $TriID=0){
	//echo "Hola";//exit;
	if ($LegID == 0 && $LecID == 0){
		$LegID = $_POST['LegID'];
		$LecID = $_POST['LecID'];
		$ClaID = $_POST['ClaID'];
		$TriID = $_POST['TriID'];
		$post = true;
	}
		
	$sql = "SELECT * FROM Colegio_Evaluacion 
	 INNER JOIN Colegio_Notas 
        ON (Colegio_Evaluacion.Eva_Not_ID = Colegio_Notas.Not_ID)
	  INNER JOIN Colegio_Instancia 
        ON (Colegio_Evaluacion.Eva_Cia_ID = Colegio_Instancia.Cia_ID)
	WHERE Eva_Leg_ID = $LegID AND Eva_Lec_ID = $LecID AND Eva_Cla_ID = $ClaID AND Cia_Tri_ID = $TriID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$suma = 0;
		while ($row = mysqli_fetch_array($result)){			
			if (is_numeric($row[Not_Siglas])){
				$i++;
				$suma += floatval($row[Not_Numero]);				
			}
				
		}//fin while
		if ($i>0){
			$prom = $suma / $i;
			$prom = round($prom,2);
		}else{
			$prom = "AUS";
		}
		if ($post) echo $prom; else return $prom;

	}	
						

}//fin funcion//*/

function obtenerNotaEstado($Nota, $conClase=true){

	$Nota = intval($Nota);
	//return $Nota;	exit;
	$sql = "SELECT * FROM
    Colegio_Notas
    INNER JOIN Colegio_NotasTipo 
        ON (Colegio_Notas.Not_NTi_ID = Colegio_NotasTipo.NTi_ID) WHERE Not_Siglas = '$Nota'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$clase = "nota_".$row[NTi_Nombre];
		if ($conClase) 
			$mostrar = "<span class='$clase'>$row[NTi_Siglas]</span>";
		else
			$mostrar = $row[NTi_Siglas];
		return $mostrar;
	}
}

function obtenerInasistenciaAlumno($LecID, $LegID, $InaID, $Justificada=0, $Certificado=0, $Deportiva=0){

	$sql = "SELECT COUNT(*) AS Total FROM
    Colegio_Ausentismo WHERE Aus_Leg_ID = $LegID AND Aus_Lec_ID = $LecID AND Aus_Ina_ID = $InaID
	AND Aus_Justificada = $Justificada AND Aus_Certificado = $Certificado AND Aus_Deportiva = $Deportiva";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$mostrar = $row[Total]." ";
	return $mostrar;
}

function obtenerInasistenciaAlumnoTipoTotal($LecID, $LegID, $InaID){

	$sql = "SELECT COUNT(*) AS Total FROM
    Colegio_Ausentismo WHERE Aus_Leg_ID = $LegID AND Aus_Lec_ID = $LecID AND Aus_Ina_ID = $InaID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$mostrar = $row[Total]." ";
	return $mostrar;
}

function obtenerInasistenciaAlumnoLectivoTotal($LecID, $LegID){

	$sql = "SELECT COUNT(*) AS Total FROM
    Colegio_Ausentismo WHERE Aus_Leg_ID = $LegID AND Aus_Lec_ID = $LecID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$mostrar = $row[Total]." ";
	return $mostrar;
}

function obtenerInasistenciaAlumnoJustificacionTotal($LecID, $LegID, $Justificada=0, $Tipo=""){

	$sql = "SELECT COUNT(*) AS Total FROM
    Colegio_Ausentismo WHERE Aus_Leg_ID = $LegID AND Aus_Lec_ID = $LecID AND Aus_Justificada = $Justificada";
	if (!empty($Tipo))
		$sql .= " AND Aus_$Tipo = 1";
	else
		$sql .= " AND Aus_Certificado = 0 AND Aus_Deportiva = 0";
		
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$mostrar = $row[Total]." ";
	return $mostrar;
}

function cargarFotoUsuario(){
	if (isset($_SESSION['sesion_usuario'])){
		$foto = buscarFoto($_SESSION['sesion_usuario'], "", 60, true);
		echo "<a href='#' id='verFotoUsuario' alt='Haga click para ver la Foto en grande' title='Haga click para ver la Foto en grande'>$foto</a>";		
	}
}

function guardarAsignacionCuota($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $masivo='') {

	if (empty($Cuo_Per_ID)){
		echo "No hay datos de la persona!";
		exit;
	}
	/*$Cuo_Per_ID = $_POST['Cuo_Per_ID'];
	$Cuo_Lec_ID = $_POST['Cuo_Lec_ID'];
	$Cuo_Niv_ID = $_POST['Cuo_Niv_ID'];
	$Cuo_Alt_ID = $_POST['Cuo_Alt_ID'];
	$Cuo_CTi_ID = $_POST['Cuo_CTi_ID'];*/

	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SELECT * FROM CuotaModelo WHERE CMo_Lec_ID = $Cuo_Lec_ID AND CMo_Niv_ID = $Cuo_Niv_ID AND CMo_Especial=0  ORDER BY CMo_Lec_ID, CMo_Niv_ID, CMo_CTi_ID, CMo_Alt_ID, CMo_Numero";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//echo $sql;exit;
	while ($row2 = mysqli_fetch_array($result)) {
		
		$Numero = $row2['CMo_Numero'];
		$cant_cuo = $row2['CMo_CantCuotas']+$Numero;
		$mes = $row2['CMo_Mes'];
		$anio = $row2['CMo_Anio'];
		//$fechaCuota = $row2[CMo_1er_Vencimiento];
		$fecha1Venc = $row2['CMo_1er_Vencimiento'];
		$fecha2Venc = $row2['CMo_2do_Vencimiento'];
		$fecha3Venc = $row2['CMo_3er_Vencimiento'];
		if ($fecha2Venc=="0000-00-00") $fecha2Venc = $fecha1Venc;
		if ($fecha3Venc=="0000-00-00") $fecha3Venc = $fecha1Venc;

		for ($i = $Numero; $i < $cant_cuo; $i++) {
			
			//Original
			/*if ($i>1){
				$mes++;
				if ($mes>12) $anio++;
				$mes = $mes%12;
				if ($mes==0) $mes=12;
				$fechaCuota = "$anio-$mes-".substr($row2[CMo_1er_Vencimiento],-2);
			}*/
			if ($i>$Numero){
				$mes++;
				if ($mes>12) $anio++;
				$mes = $mes%12;
				if ($mes==0) $mes=12;
				//$fechaCuota = "$anio-$mes-".substr($row2[CMo_1er_Vencimiento],-2);
				$fecha1Venc = "$anio-$mes-".substr($fecha1Venc,-2);
				$fecha2Venc = "$anio-$mes-".substr($fecha2Venc,-2);
				$fecha3Venc = "$anio-$mes-".substr($fecha3Venc,-2);
			}
			$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_Lec_ID = $Cuo_Lec_ID AND  Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Mes = $mes AND Cuo_Anio = $anio";
			//echo $sql;
			$result_verif = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			$continuar=false;
			if (mysqli_num_rows($result_verif)==0){
				$continuar = true;
				/*if ($row2['CMo_CTi_ID']==1){
					if (verificarInscripcionPrevia($Cuo_Per_ID, $Cuo_Lec_ID)){
						$continuar = false;
					}
				}*/
				$Cuo_Ben_ID = 1;
				
				if (COLEGIO_SIGLAS=='cesap'){
					//cuota mensual y fotocopias
					if ($row2['CMo_CTi_ID']==2 || $row2['CMo_CTi_ID']==5){
						obtenerBeneficioAlumnoNuevo($Cuo_Lec_ID, $Cuo_Per_ID, $row2['CMo_CTi_ID'], $Cuo_Ben_ID);
					}
				}else{		
					//if ($row2['CMo_CTi_ID']==2){
						obtenerBeneficioAlumnoNuevo($Cuo_Lec_ID, $Cuo_Per_ID, $row2['CMo_CTi_ID'], $Cuo_Ben_ID);
					//}
				}
				
				if ($continuar){
					$sql = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal, Cuo_Masivo, Cuo_Agrupa)
					values($Cuo_Per_ID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $Cuo_Lec_ID, $row2[CMo_Alt_ID], $i, $Cuo_Ben_ID, $UsuID, '$Fecha', '$Hora', $row2[CMo_Importe], '$row2[CMo_1er_Recargo]', '$row2[CMo_2do_Recargo]', '$fecha1Venc', '$fecha2Venc', '$fecha3Venc', $mes, $anio, '0','0','0', '$row2[CMo_Recargo_Mensual]', '$row2[CMo_Importe]', '$masivo', '$row2[CMo_Agrupa]')";
					//if ($row2[CMo_CTi_ID]==2) echo "$sql<br>";
					consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
					$datosCuota = $Cuo_Lec_ID.";".$Cuo_Per_ID.";".$row2['CMo_Niv_ID'].";".$row2['CMo_CTi_ID'].";".$row2['CMo_Alt_ID'].";".$i;
					aplicarImporteCuotaBeneficio($datosCuota, $Cuo_Ben_ID);					
					echo "$sql<br>";
				}
				
			}else{// aplica el benficio en caso de que la cuota exista
				obtenerBeneficioAlumnoNuevo($Cuo_Lec_ID, $Cuo_Per_ID, $row2['CMo_CTi_ID'], $Cuo_Ben_ID);
				$datosCuota = $Cuo_Lec_ID.";".$Cuo_Per_ID.";".$row2['CMo_Niv_ID'].";".$row2['CMo_CTi_ID'].";".$row2['CMo_Alt_ID'].";".$i;
				aplicarImporteCuotaBeneficio($datosCuota, $Cuo_Ben_ID);					
				echo 'Ya la tiene asignada'.$datosCuota."-".$Cuo_Ben_ID;
			}


			
			
		}
	}

	gObtenerApellidoNombrePersona($Cuo_Per_ID, $Apellido, $Nombre, true);
	echo "$Apellido, $Nombre: Se agregó correctamente la nueva configuración<br />";
}

function actualizarImporteCuota($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID) {

	/*$Cuo_Per_ID = $_POST['Cuo_Per_ID'];
	$Cuo_Lec_ID = $_POST['Cuo_Lec_ID'];
	$Cuo_Niv_ID = $_POST['Cuo_Niv_ID'];
	$Cuo_Alt_ID = $_POST['Cuo_Alt_ID'];
	$Cuo_CTi_ID = $_POST['Cuo_CTi_ID'];*/

	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SELECT * FROM CuotaModelo WHERE CMo_Lec_ID = $Cuo_Lec_ID AND CMo_Niv_ID = $Cuo_Niv_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//echo $sql;exit;
	while ($row2 = mysqli_fetch_array($result)) {
		$cant_cuo = $row2[CMo_CantCuotas];
		$mes = $row2[CMo_Mes];
		$anio = $row2[CMo_Anio];
		$fechaCuota = $row2[CMo_1er_Vencimiento];

		/*
		for ($i = 1; $i <= $cant_cuo; $i++) {
			
			if ($i>1){
				$mes++;
				if ($mes>12) $anio++;
				$mes = $mes%12;
				if ($mes==0) $mes=12;
				$fechaCuota = "$anio-$mes-".substr($row2[CMo_1er_Vencimiento],-2);
			}
			
			$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Alt_ID = $row2[CMo_Alt_ID] AND Cuo_Numero = $i AND Cuo_Mes = $mes AND Cuo_Anio = $anio AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0";
			$result_cuo = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			
			if (mysqli_num_rows($result_cuo)>0){
				$row_cuo = mysqli_fetch_array($result_cuo);
				$sql = "UPDATE CuotaPersona SET Cuo_Importe = $row2[CMo_Importe], Cuo_ImporteOriginal = $row2[CMo_Importe], Cuo_Fecha = '$Fecha', Cuo_Hora = '$Hora', Cuo_Usu_ID = '$UsuID' 
				WHERE 
				Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Alt_ID = $row2[CMo_Alt_ID] AND Cuo_Numero = $i AND Cuo_Mes = $mes AND Cuo_Anio = $anio AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $row2[CMo_Alt_ID], $i, $row_cuo[Cuo_Ben_ID]);
				
			}
		*/
		
		//Mario. 29/11/2021
		//corrección para que asigne las cuotas	
		$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Alt_ID = $row2[CMo_Alt_ID] AND Cuo_Mes = $mes AND Cuo_Anio = $anio AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0";
		$result_cuo = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($row_cuo = mysqli_fetch_array($result_cuo)) {		
			//echo '<br>'.$sql;	
			$sqlu = "UPDATE CuotaPersona SET 
				Cuo_Importe = $row2[CMo_Importe],
				Cuo_1er_Recargo = $row2[CMo_1er_Recargo],  
				Cuo_1er_Vencimiento = '$row2[CMo_1er_Vencimiento]',
				Cuo_Recargo_Mensual = $row2[CMo_Recargo_Mensual],
				Cuo_ImporteOriginal = $row2[CMo_Importe], 
				Cuo_Fecha = '$Fecha', 
				Cuo_Hora = '$Hora', 
				Cuo_Usu_ID = '$UsuID'
			WHERE 
			Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Alt_ID = $row2[CMo_Alt_ID] AND Cuo_Numero = $row_cuo[Cuo_Numero] AND Cuo_Mes = $mes AND Cuo_Anio = $anio AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0";
			consulta_mysql_2022($sqlu,basename(__FILE__),__LINE__);
			//echo '<br>'.$sqlu.'<br>';
			aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $row2[CMo_Alt_ID],  $row_cuo[Cuo_Numero], $row_cuo[Cuo_Ben_ID]);
				
			
		}//del while
		
	}//del while

	gObtenerApellidoNombrePersona($Cuo_Per_ID, $Apellido, $Nombre, true);
	echo "$Apellido, $Nombre: Se ACTUALIZÓ correctamente la configuración<br />";
}

function actualizarCuotaCTIConfiguracion($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Numero, $conFecha=false, &$cont = 0) {

	/*$Cuo_Per_ID = $_POST['Cuo_Per_ID'];
	$Cuo_Lec_ID = $_POST['Cuo_Lec_ID'];
	$Cuo_Niv_ID = $_POST['Cuo_Niv_ID'];
	$Cuo_Alt_ID = $_POST['Cuo_Alt_ID'];
	$Cuo_CTi_ID = $_POST['Cuo_CTi_ID'];*/

	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SELECT * FROM CuotaModelo WHERE CMo_Lec_ID = $Cuo_Lec_ID AND CMo_Niv_ID = $Cuo_Niv_ID AND CMo_CTi_ID  = $Cuo_CTi_ID AND CMo_Numero = $Numero";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//echo $sql;exit;
	while ($row2 = mysqli_fetch_array($result)) {
		$cant_cuo = $row2[CMo_CantCuotas]+$Numero;
		$mes = $row2[CMo_Mes];
		$anio = $row2[CMo_Anio];
		$fechaCuota = $row2[CMo_1er_Vencimiento];

		for ($i = $Numero; $i <= $cant_cuo; $i++) {
			
			if ($i>1){
				$mes++;
				if ($mes>12) $anio++;
				$mes = $mes%12;
				if ($mes==0) $mes=12;
				$fechaCuota = "$anio-$mes-".substr($row2[CMo_1er_Vencimiento],-2);
			}
			
			$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Alt_ID = $row2[CMo_Alt_ID] AND Cuo_Numero = $i AND Cuo_Mes = $mes AND Cuo_Anio = $anio AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0";
			
			$result_cuo = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			if (mysqli_num_rows($result_cuo)>0){
				$row_cuo = mysqli_fetch_array($result_cuo);
				$sqlFecha = "";
				if ($conFecha){
					$sqlFecha = ", Cuo_1er_Vencimiento='$fechaCuota', Cuo_2do_Vencimiento='$fechaCuota', Cuo_3er_Vencimiento='$fechaCuota'";
				}
				$sql = "UPDATE CuotaPersona SET Cuo_Importe = $row2[CMo_Importe], Cuo_ImporteOriginal = $row2[CMo_Importe] $sqlFecha WHERE Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Alt_ID = $row2[CMo_Alt_ID] AND Cuo_Numero = $i AND Cuo_Mes = $mes AND Cuo_Anio = $anio AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0";
				
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				$cont = mysql_affected_rows();
				aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $row2[CMo_Alt_ID], $i, $row_cuo[Cuo_Ben_ID]);
				//echo "$row_cuo[Cuo_Importe] - $row2[CMo_Importe] - $row_cuo[Cuo_ImporteOriginal]<br />";
			}
			
			
		}
	}
	
}//fin function

function aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, $Ben_ID)
{

	
	
    $sql1 = "SELECT * FROM CuotaPorcentaje WHERE Por_CTi_ID='$Cuo_CTi_ID' AND Por_Ben_ID='$Ben_ID';";
    $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
    //echo $sql1."<br />";
    if (mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_array($result1);
        $Por_Valor=$row1['Por_Valor'];
        $Por_Porcentaje=$row1['Por_Porcentaje'];
    }else{
        $Por_Valor=0;
        $Por_Porcentaje=0;
    }
	//echo $Por_Valor;
    //echo 'porc'.$Por_Porcentaje;
     
    $sql1 = "SELECT Cuo_ImporteOriginal FROM 
    CuotaPersona WHERE Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero'";
    //echo $sql1;
    $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
    $row1 = mysqli_fetch_array($result1); 
    $Cuo_Importe=$row1['Cuo_ImporteOriginal'];
    //$Cuo_ImporteOriginal = $Cuo_Importe;
    //echo $Cuo_Importe;
    if ($Por_Porcentaje>0){
        $Cuo_Importe = $Cuo_Importe - ($Cuo_Importe * $Por_Porcentaje / 100);
        $Cuo_Importe = intval($Cuo_Importe);
    }
    if ($Por_Valor>0){
        $Cuo_Importe = $Cuo_Importe - $Por_Valor;
    }
    
	
    $sql = " UPDATE CuotaPersona SET
    Cuo_Ben_ID = '$Ben_ID',
    Cuo_Importe= '$Cuo_Importe'
        WHERE
    Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero';";
    //echo $sql;
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($Ben_ID==1) {
        $sql = " UPDATE CuotaPersona SET
        Cuo_Ben_ID = 1,
        Cuo_Importe= Cuo_ImporteOriginal
        WHERE
        Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero';";
        //echo $sql;
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    }


}

function buscarMes($m){
global $gMes;	
	
	return $gMes[$m];
}

function restarMeses($fech_ini, $fech_fin){

  /*
   FELIPE DE JESUS SANTOS SALAZAR, LIFER35@HOTMAIL.COM
   SEP-2010

   ESTA FUNCION NOS REGRESA LA CANTIDAD DE MESES ENTRE 2 FECHAS

   EL FORMATO DE LAS VARIABLES DE ENTRADA $fech_ini Y $fech_fin ES YYYY-MM-DD

   $fech_ini TIENE QUE SER MENOR QUE $fech_fin

   ESTA FUNCION TAMBIEN SE PUEDE HACER CON LA FUNCION date

   SI ENCUENTRAS ALGUN ERROR FAVOR DE HACERMELO SABER

   ESPERO TE SEA DE UTILIDAD, POR FAVOR NO QUIERES ESTE COMENTARIO, GRACIAS

   */



   //SEPARO LOS VALORES DEL ANIO, MES Y DIA PARA LA FECHA INICIAL EN DIFERENTES
   //VARIABLES PARASU MEJOR MANEJO

   $fIni_yr=substr($fech_ini,0,4);
    $fIni_mon=substr($fech_ini,5,2);
    $fIni_day=substr($fech_ini,8,2);

   //SEPARO LOS VALORES DEL ANIO, MES Y DIA PARA LA FECHA FINAL EN DIFERENTES
   //VARIABLES PARASU MEJOR MANEJO
   $fFin_yr=substr($fech_fin,0,4);
    $fFin_mon=substr($fech_fin,5,2);
    $fFin_day=substr($fech_fin,8,2);

   $yr_dif=$fFin_yr - $fIni_yr;
   //echo "la diferencia de años es -> ".$yr_dif."<br>";
   //LA FUNCION strtotime NOS PERMITE COMPARAR CORRECTAMENTE LAS FECHAS
   //TAMBIEN ES UTIL CON LA FUNCION date
   if(strtotime($fech_ini) > strtotime($fech_fin)){
      echo 'ERROR -> la fecha inicial '.$fech_ini.' es mayor a la fecha final '.$fech_fin.'<br>';
      exit();
   }
   else{
       if($yr_dif == 1){
         $fIni_mon = 12 - $fIni_mon;
         $meses = $fFin_mon + $fIni_mon;
         return $meses;
         //LA FUNCION utf8_encode NOS SIRVE PARA PODER MOSTRAR ACENTOS Y
         //CARACTERES RAROS
         //echo utf8_encode("la diferencia de meses con un año de diferencia es -> ".$meses."<br>");
      }
      else{
          if($yr_dif == 0){
             $meses=$fFin_mon - $fIni_mon;
            return $meses;
            //echo utf8_encode("la diferencia de meses con cero años de diferencia es -> ".$meses.", donde el mes inicial es ".$fIni_mon.", el mes final es ".$fFin_mon."<br>");
         }
         else{
             if($yr_dif > 1){
               $fIni_mon = 12 - $fIni_mon;
               $meses = $fFin_mon + $fIni_mon + (($yr_dif - 1) * 12);
               return $meses;
               //echo utf8_encode("la diferencia de meses con mas de un año de diferencia es -> ".$meses."<br>");
            }
            else
               echo "ERROR -> la fecha inicial es mayor a la fecha final <br>";
               exit();
         }
      }
   }

}

function Obtener_Deuda_Sistema($PerID, &$Recargo_Deuda=0){

// Busco todas las chequeras de esta persona
$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Per_ID = $PerID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado =0";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//$mostrar.=mysqli_error ();	
//echo $sql;
$Deuda=0;
$Recargo = 0;
if (mysqli_num_rows($result) == 0){
	
	}else{
		
		while ($row = mysqli_fetch_array($result)){
			
			
			/*$datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
			//Calculamos el importe que deber�a pagar al d�a de hoy
			$importe = $row['Cuo_Importe'];
			//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
			recalcularImporteCuota($datosCuota, $importe);
			//$importe = $row[Cuo_Importe];
			$fechaCuota = cfecha($row['Cuo_1er_Vencimiento']);
			$fechaCuota2 = $row['Cuo_1er_Vencimiento'];
			$fechaHoy = date("d-m-Y");
			$fechaHoy2 = date("Y-m-d");
			$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
			$mesesAtrazo = 0;
			$recargo = 0;
			if ( $dias_vencidos > 0 ){
				
				$Deuda += intval($importe);
			}else{
				$dias_vencidos = 0;
			}

			//*/
			//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
			$datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
			//Calculamos el importe que deber�a pagar al d�a de hoy
			$importe = $row['Cuo_Importe'];
			//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
			$importeOriginal = $importe;
			recalcularImporteCuota($datosCuota, $importe);
			//if ($row[Cuo_Pagado]==1) $importe = buscarPagosTotales($datosCuota);
			$recargo2 = obtenerRecargoCuota($row['Cuo_Per_ID'], $datosCuota);

			/*$fechaCuota = cfecha($row['Cuo_1er_Vencimiento']);
			$fechaCuota2 = $row['Cuo_1er_Vencimiento'];
			$fechaHoy = date("d-m-Y");
			$fechaHoy2 = date("Y-m-d");
			$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
			$mesesAtrazo = 0;
			$recargo = 0;
			/*if ( $dias_vencidos > 0 ){
				
				$Deuda += intval($importe);
				$Recargo += intval($recargo2);
			}else{
				$dias_vencidos = 0;
			}*/

			$fechaCuota = cfecha($row['Cuo_1er_Vencimiento']);			
			$fechaHoy = date("d-m-Y");
			//$fechaHoy = "01-01-2018";
			$ya_vencida=1;
			$fecha = restarFecha($fechaCuota, $fechaHoy);
			$fechaCuota2 = $row['Cuo_1er_Vencimiento'];
			$fechaHoy2 = date("Y-m-d");
			$mesesAtrazo = 0;
			if ( $fecha > 0 ){
				/*if ($row['Cuo_Pagado']==0 && $row['Cuo_Cancelado']==0 && $row['Cuo_Anulado']==0 && $row['Cuo_Ben_ID']!=1 && $ya_vencida==1){*/
				if ($row['Cuo_Ben_ID']!=1){	
					//cambiamos el valor del importe					
					actualizarImporteCuotaBeneficio($datosCuota, $importe);
					$importe = intval($importe);			
					$recargo2 = obtenerRecargoCuota($row['Cuo_Per_ID'], $datosCuota);
				}			
				$Deuda += intval($importe);
				$Recargo += intval($recargo2);
			}
			//$Deuda += intval($importe);
			//$Recargo += intval($recargo2);

		}//del while
	}//del else	

$Recargo_Deuda = $Recargo;
return intval($Deuda);	

}//fin de la function

function Obtener_Deuda_Detallada_Sistema($PerID){

// Busco todas las chequeras de esta persona
$sql = "SELECT * FROM CuotaPersona INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CuotaTipo.CTi_ID) WHERE Cuo_Per_ID = $PerID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado =0 AND Cuo_CTi_ID <> 5";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//$mostrar.=mysqli_error ();	
//echo $sql;
if (mysqli_num_rows($result) == 0){
	$Deuda=0;
	$Detalle = "";
	}else{
		
		while ($row = mysqli_fetch_array($result)){
			
			$datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
			//Calculamos el importe que deber�a pagar al d�a de hoy
			$importe = $row['Cuo_Importe'];
			//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
			recalcularImporteCuota($datosCuota, $importe);
			//$importe = $row[Cuo_Importe];
			$fechaCuota = cfecha($row['Cuo_1er_Vencimiento']);
			$fechaCuota2 = $row['Cuo_1er_Vencimiento'];
			$fechaHoy = date("d-m-Y");
			$fechaHoy2 = date("Y-m-d");
			$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
			$mesesAtrazo = 0;
			$recargo = 0;
			if ( $dias_vencidos > 0 ){
				/*$mesesAtrazo = restarMeses($fechaCuota2, $fechaHoy2);
				$dia15 = substr($fechaHoy2,-2);
				if ($mesesAtrazo==0 && $dia15>15) $mesesAtrazo=1;						
				$recargo=$row[Cuo_Recargo_Mensual] * $mesesAtrazo;
				$importe += $recargo;*/
				$Deuda += intval($importe);
				$Detalle .= "$row[CTi_Nombre] ($row[Cuo_Mes]/$row[Cuo_Anio]): $".intval($importe)."<br />";
			}else{
				$dias_vencidos = 0;
			}//*/
			//$Deuda += intval($importe);
		}//del while
	}//del else	

return $Detalle;
//return intval($Deuda);	

}//fin de la function

function Obtener_Deuda_Libros($PerID){

// Busco todas las chequeras de esta persona
$sql = "SELECT * FROM LibroCuotaPersona WHERE LCu_Per_ID = $PerID AND LCu_Pagado =0 AND LCu_Anulado =0";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//$mostrar.=mysqli_error ();	
//echo $sql;
if (mysqli_num_rows($result) == 0){
	$Deuda=0;
	}else{
		
		while ($row = mysqli_fetch_array($result)){
			
			$datosCuota = $row[LCu_Lec_ID].";".$row[LCu_Per_ID].";".$row[LCu_LNu_ID].";".$row[LCu_Numero];
			//Calculamos el importe que deber�a pagar al d�a de hoy
			$importe = $row[LCu_Importe];
			//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
			recalcularImporteCuotaLibro($datosCuota, $importe);
			//$importe = $row[Cuo_Importe];
			$fechaCuota = cfecha($row[LCu_Vencimiento]);
			$fechaCuota2 = $row[LCu_Vencimiento];
			$fechaHoy = date("d-m-Y");
			$fechaHoy2 = date("Y-m-d");
			$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
			$mesesAtrazo = 0;
			$recargo = 0;
			if ( $dias_vencidos > 0 ){
				/*$mesesAtrazo = restarMeses($fechaCuota2, $fechaHoy2);
				$dia15 = substr($fechaHoy2,-2);
				if ($mesesAtrazo==0 && $dia15>15) $mesesAtrazo=1;						
				$recargo=$row[Cuo_Recargo_Mensual] * $mesesAtrazo;
				$importe += $recargo;*/
				$Deuda += intval($importe);
			}else{
				$dias_vencidos = 0;
			}//*/
			//$Deuda += intval($importe);
		}//del while
	}//del else	


return intval($Deuda);	

}//fin de la function

function cajaAbierta(){
	$sql = "SELECT * FROM Caja WHERE Caja_Apertura IS NOT NULL AND Caja_Cierre IS NULL";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Caja_ID];
	}else{
		return false;
	}
}//fin function

function guardarConceptoCtaCte($PerID, $datosCuota){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT * FROM CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero);";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Concepto = "Generacion de $row[CTi_Nombre] ".buscarMes($row[Cuo_Mes])."/$row[Cuo_Anio]";
		$Detalle = "Usuario que generó la cuota: $row[Usu_Nombre] Nivel: $row[Niv_Nombre] Beneficio: $row[Ben_Nombre]";
		$valor = $row[Cuo_Importe];
		
		$sql = "INSERT INTO CuentaCorriente (CCo_Per_ID, CCo_Concepto, CCo_Debito, CCo_Fecha, CCo_Hora, CCo_Detalle, CCo_Referencia) VALUES($PerID, '$Concepto', '$valor', '$Fecha', '$Hora', '$Detalle', '$datosCuota')";
		//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res['success'] == true){
        $CCoID = $res['id'];
    }else{
        echo "Mal";exit;
    }

		actualizarSaldoCtaCte($PerID, $CCoID);
		$importe = $row[Cuo_Importe];
		$fechaCuota = cfecha($row[Cuo_1er_Vencimiento]);
		$fechaCuota2 = $row[Cuo_1er_Vencimiento];
		$fechaHoy = date("d-m-Y");
		$fechaHoy2 = date("Y-m-d");
		$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
		$mesesAtrazo = 0;
		$recargo = 0;
		if ( $dias_vencidos > 0 ){
			$mesesAtrazo = restarMeses($fechaCuota2, $fechaHoy2);
			$dia15 = substr($fechaHoy2,-2);
			if ($mesesAtrazo==0 && $dia15>15) $mesesAtrazo=1;
			if ($mesesAtrazo==1){
				$recargo=$row[Cuo_1er_Recargo];
			}else{
				$mesesAtrazo--;
				$recargo=$row[Cuo_1er_Recargo] + ($row[Cuo_Recargo_Mensual] * $mesesAtrazo);	
			}					
			//$recargo=$row[Cuo_Recargo_Mensual] * $mesesAtrazo;			
		}

		//Habilitado por Mario. 28/06/2022, porque no generaba este arancel y luego cuando lo anulan da error!
		if ($recargo>0){
			$Concepto = "Generacion de recargo por pago atrasado $row[CTi_Nombre] ".buscarMes($row[Cuo_Mes])."/$row[Cuo_Anio]";
			$Detalle = utf8_encode("Usuario que generó la cuota: $row[Usu_Nombre] Nivel: $row[Niv_Nombre] Beneficio: $row[Ben_Nombre]");
			$valor = $recargo;			
			
			$sql = "INSERT INTO CuentaCorriente (CCo_Per_ID, CCo_Concepto, CCo_Debito, CCo_Fecha, CCo_Hora, CCo_Detalle, CCo_Referencia) VALUES($PerID, '$Concepto', $valor, '$Fecha', '$Hora', '$Detalle', '$datosCuota')";
			//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
	    if ($res['success'] == true){
	        $CCoID = $res['id'];
	    }else{
	        echo "Mal"; exit;
	    }
			actualizarSaldoCtaCte($PerID, $CCoID);
		}
		//********************************
		
	}
	return $CCoID;
	

}//fin function

function guardarPagoCtaCte($PerID, $datosCuota, $importe){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$CCoID=0;
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  //echo $datosCuota;exit;
	list($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero) = explode(';', $datosCuota);

	$sql = "SELECT * FROM CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero);";
//echo $sql;exit;

	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Concepto = "Pago de $row[CTi_Nombre] ".buscarMes($row[Cuo_Mes])."/$row[Cuo_Anio]";
		
		//$Detalle = utf8_decode("Usuario que genero la cuota: $row[Usu_Nombre] Nivel: $row[Niv_Nombre] Beneficio: $row[Ben_Nombre]");//echo "Hola";exit;
		
		$Detalle = "Usuario que genero la cuota: $row[Usu_Nombre] Nivel: $row[Niv_Nombre] Beneficio: $row[Ben_Nombre]";//echo "Hola";exit;

		$sql = "INSERT INTO CuentaCorriente (CCo_Per_ID, CCo_Concepto, CCo_Credito, CCo_Usu_ID, CCo_Fecha, CCo_Hora, CCo_Detalle, CCo_Referencia) VALUES('$PerID', '$Concepto', '$importe', '$UsuID', '$Fecha', '$Hora', '$Detalle', '$datosCuota')";
		$res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
		if ($res['success'] == true){
		  $CCoID = $res['id'];
		}else{
		  echo "Mal";exit;
		}

		actualizarSaldoCtaCte($PerID, $CCoID);
	}
	return $CCoID;
	

}//fin function

function guardarPagoCajaCorriente($CajaID, $datosCuota, $importe, $ForID, $Recibo){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT * FROM CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
	 INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID)
WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero);";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Concepto = "[$Recibo: $row[Per_Apellido], $row[Per_Nombre]] $row[CTi_Nombre] ".buscarMes($row['Cuo_Mes'])."/$row[Cuo_Anio]";
		$Concepto = addslashes($Concepto);
		
		//$Detalle = utf8_encode("$row[Niv_Nombre] - $row[Ben_Nombre]");
		$Detalle = "$row[Niv_Nombre] - $row[Ben_Nombre]";

		$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Credito, CCC_Usu_ID, CCC_Fecha, CCC_Hora, CCC_Detalle, CCC_Referencia, CCC_For_ID) VALUES('$CajaID', '$Concepto', '$importe', '$UsuID', '$Fecha', '$Hora', '$Detalle', '$datosCuota', '$ForID')";
		$res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
		if ($res['success'] == true){
		  $CCCID = $res['id'];
		}else{
		  echo "Mal";exit;
		}
		
		actualizarSaldoCajaCorriente($CajaID, $CCCID);
	}
	return $CCCID;
	

}//fin function
function guardarEgresoCajaCorriente($CajaID, $Concepto, $importe, $ForID, $Detalle){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Debito, CCC_Usu_ID, CCC_Fecha, CCC_Hora, CCC_Detalle, CCC_For_ID, CCC_Ret_Dinero) VALUES('$CajaID', '$Concepto', '$importe', '$UsuID', '$Fecha', '$Hora', '$Detalle', '$ForID', 1)";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
  if ($res['success'] == true){
      $CCCID = $res['id'];
  }else{
      echo "Error"; exit;
  }

	actualizarSaldoCajaCorriente($CajaID, $CCCID);
	
	return $CCCID;
	

}//fin function

function guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $importe, $ForID, $Detalle){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Debito, CCC_Credito, CCC_Usu_ID, CCC_Fecha, CCC_Hora, CCC_Detalle, CCC_For_ID, CCC_Ret_Dinero) VALUES('$CajaID', '$Concepto', '$importe', '$importe', '$UsuID', '$Fecha', '$Hora', '$Detalle', '$ForID', 1)";
//	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
  if ($res['success'] == true){
      $CCCID = $res['id'];
  }else{
      echo "Error"; exit;
  }

	actualizarSaldoCajaCorriente($CajaID, $CCCID);
	
	return $CCCID;
	

}//fin function

function actualizarSaldoCtaCte($PerID, $CCoID){
	$sql = "SELECT SUM(CCo_Debito) AS Debe, SUM(CCo_Credito) AS Haber FROM CuentaCorriente WHERE CCo_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Haber = intval($row[Haber]) - intval($row[Debe]);
		$sql = "UPDATE CuentaCorriente SET CCo_Saldo = '$Haber' WHERE CCo_ID = '$CCoID'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}

}//fin function

function actualizarSaldoCajaCorriente($CajaID, $CCCID){
	$sql = "SELECT SUM(CCC_Debito) AS Debe, SUM(CCC_Credito) AS Haber FROM CajaCorriente WHERE CCC_Caja_ID = $CajaID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Haber = intval($row[Haber]) - intval($row[Debe]);
		$sql = "UPDATE CajaCorriente SET CCC_Saldo = '$Haber' WHERE CCC_ID = '$CCCID'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}

}//fin function

function actualizarImporteCuotaBeneficio($datosCuota, &$importe){
		
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT * FROM CuotaPersona   
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)    
WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0);";//echo $sql;	
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Cuo_ImporteOriginal = $row['Cuo_ImporteOriginal'];
		$sql = "UPDATE CuotaPersona SET Cuo_Importe = Cuo_ImporteOriginal WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0)";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$importe = $Cuo_ImporteOriginal;
	}
}//fin function

function aplicarImporteCuotaBeneficio($datosCuota, $Ben_ID){
//Le aplica el beneficio a la cuota			
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
    
    $sql1 = "SELECT * FROM CuotaPorcentaje WHERE Por_CTi_ID='$Cuo_CTi_ID' AND Por_Ben_ID='$Ben_ID';";
    $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
    //echo $sql1."<br />";
    if (mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_array($result1);
        $Por_Valor=$row1['Por_Valor'];
        $Por_Porcentaje=$row1['Por_Porcentaje'];
    }else{
        $Por_Valor=0;
        $Por_Porcentaje=0;
    }
     
    $sql1 = "SELECT Cuo_ImporteOriginal FROM 
    CuotaPersona WHERE Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero'";
    //echo $sql1;
    $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
    $row1 = mysqli_fetch_array($result1); 
    $Cuo_Importe=$row1['Cuo_ImporteOriginal'];
    //$Cuo_ImporteOriginal = $Cuo_Importe;
    //echo $Cuo_Importe;
    if ($Por_Porcentaje>0){
        $Cuo_Importe = $Cuo_Importe - ($Cuo_Importe * $Por_Porcentaje / 100);
        $Cuo_Importe = intval($Cuo_Importe);
    }
    if ($Por_Valor>0){
        $Cuo_Importe = $Cuo_Importe - $Por_Valor;
    }
    
    $sql = " UPDATE CuotaPersona SET
    Cuo_Ben_ID = '$Ben_ID',
    Cuo_Importe= '$Cuo_Importe'
        WHERE
    Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero';";
    //echo $sql;
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($Ben_ID==1) {
        $sql = " UPDATE CuotaPersona SET
        Cuo_Ben_ID = 1,
        Cuo_Importe= Cuo_ImporteOriginal
        WHERE
        Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero';";
        echo $sql;
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    }

}//fin function

function calcularPagoTotalCuota($datosCuota, &$debe=0, &$sinPlan=false){
	//$seguir = true;
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	if (!$sinPlan) $sqlPlan = "AND Cuo_Cancelado = 0";

	$sql = "SELECT * FROM CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero AND Cuo_Pagado = 0 $sqlPlan);";//echo $sql;
	//if ($Cuo_CTi_ID==17) echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$importe = $row['Cuo_Importe'];
	
		$fechaHoy = date("d-m-Y");	
			
		$sql = "SELECT SUM(CuP_Importe) AS Total FROM CuotaPago WHERE CuP_Lec_ID = '$Cuo_Lec_ID' AND CuP_Per_ID = '$Cuo_Per_ID' AND CuP_Niv_ID = '$Cuo_Niv_ID' AND CuP_CTi_ID='$Cuo_CTi_ID' AND CuP_Alt_ID='$Cuo_Alt_ID' AND CuP_Numero='$Cuo_Numero' AND CuP_Anulada =0";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//if ($Cuo_CTi_ID==17) echo $sql;
		if (mysqli_num_rows($result)>0){		
			$row = mysqli_fetch_array($result);
			$importeAbonado = $row['Total'];
		}else{
			$importeAbonado = 0;
		}
		//echo "Abonado: $importeAbonado<br />";
		$debe = intval($importe) - intval($importeAbonado);
		//echo "Pagado:$debe";//exit;
		if ($importe > $importeAbonado) return false; else return true;

	}//exit;
	
	
}//fin function

function calcularPagoTotalCuotaLibro($datosCuota, &$debe=0){
	list( $LCu_Lec_ID, $LCu_Per_ID, $LCu_LNu_ID, $LCu_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT * FROM LibroCuotaPersona   
WHERE (LCu_Lec_ID = $LCu_Lec_ID AND LCu_Per_ID = $LCu_Per_ID AND LCu_LNu_ID = $LCu_LNu_ID AND LCu_Numero=$LCu_Numero AND LCu_Anulado = 0);";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$importe = $row[LCu_Importe];
		$fechaCuota = cfecha($row[LCu_Vencimiento]);
		$fechaCuota2 = $row[LCu_Vencimiento];
		$dia15Cuota = substr($fechaCuota2,-2);
		$fechaHoy = date("d-m-Y");
		$fechaHoy2 = date("Y-m-d");
		$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
		$mesesAtrazo = 0;
		$recargo = 0;
		if ( $dias_vencidos > 0 ){
			$mesesAtrazo = restarMeses($fechaCuota2, $fechaHoy2);
			$dia15 = substr($fechaHoy2,-2);
			//echo "////$mesesAtrazo $dia15              /////";
			if ($mesesAtrazo==0 && $dia15>$dia15Cuota) $mesesAtrazo=1;elseif ($dia15>$dia15Cuota) $mesesAtrazo++;
			
			$recargo=$row[LCu_Recargo] * $mesesAtrazo;			
		}
		$importe += $recargo;
		$sql = "SELECT SUM(LCP_Importe) AS Total FROM LibroCuotaPago WHERE LCP_Lec_ID = $LCu_Lec_ID AND LCP_Per_ID = $LCu_Per_ID AND LCP_LNu_ID = $LCu_LNu_ID AND LCP_Numero=$LCu_Numero AND LCP_Anulado =0";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){		
			$row = mysqli_fetch_array($result);
			$importeAbonado = $row[Total];
		}else{
			$importeAbonado = 0;
		}
		//echo "Abonado: $importeAbonado<br />";
		$debe = floatval($importe) - floatval($importeAbonado);
		//echo "Pagado:$debe";exit;
		//echo $importe; exit;
		if ($importe > $importeAbonado) return false; else return true;
	}//exit;
	return true;
	
}//fin function

function vaciarColaPagoUsuario(){
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$sql = "DELETE FROM CuotaPersonaCola WHERE CPT_Usu_ID = $UsuID";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
}//fin function


function actualizarAlernativaPago($datosCuota){
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "DELETE FROM CuotaPersona WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID<>$Cuo_Alt_ID AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0)";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	
}//fin function

function recalcularImporteCuota($datosCuota, &$importe){
		
	calcularPagoTotalCuota($datosCuota, $debe);
	
	$importe = $debe;
	
	//echo $sql;
	
}//fin function

function recalcularImporteCuotaLibro($datosCuota, &$importe){
		
	calcularPagoTotalCuotaLibro($datosCuota, $debe);
	
	$importe = $debe;
	
	//echo $sql;
	
}//fin function

function buscarCursoDivisionPersona($PerID, $ConDivAnterior = false){
	
	$LecID_actual = gLectivoActual($texto);
	$LecID_anterior = $LecID_actual - 1;
	$LecID_siguiente = $LecID_actual + 1;
	$datos='';
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//Buscamos Lectivo actual
	$sql = "SELECT * FROM Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
	INNER JOIN Colegio_Nivel 
        ON (Ins_Niv_ID = Niv_ID)
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID) WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID_actual ORDER BY Ins_Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$datos = "<strong>Ciclo $texto:</strong> $row[Cur_Nombre] '$row[Div_Nombre]' $row[Niv_Nombre]";
	}
	if ($ConDivAnterior){
		//Buscamos Lectivo siguiente
		$sql = "SELECT * FROM Colegio_Inscripcion
	    INNER JOIN Legajo 
	        ON (Ins_Leg_ID = Leg_ID)
		INNER JOIN Colegio_Nivel 
	        ON (Ins_Niv_ID = Niv_ID)	
	    INNER JOIN Curso 
	        ON (Ins_Cur_ID = Cur_ID)
	    INNER JOIN Division 
	        ON (Ins_Div_ID = Div_ID) 
	    INNER JOIN Lectivo 
	        ON (Ins_Lec_ID = Lec_ID)
	    WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID_siguiente ORDER BY Ins_Lec_ID DESC";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){		
			$row = mysqli_fetch_array($result);
			if (!empty($datos)) $datos .= '<br>';
			$datos .= "<strong>Ciclo $row[Lec_Nombre]:</strong> $row[Cur_Nombre] '$row[Div_Nombre]' $row[Niv_Nombre]";
		}
		$sql = "SELECT * FROM Colegio_Inscripcion
	    INNER JOIN Legajo 
	        ON (Ins_Leg_ID = Leg_ID)
		INNER JOIN Colegio_Nivel 
	        ON (Ins_Niv_ID = Niv_ID)	
	    INNER JOIN Curso 
	        ON (Ins_Cur_ID = Cur_ID)
	    INNER JOIN Division 
	        ON (Ins_Div_ID = Div_ID) 
	    INNER JOIN Lectivo 
	        ON (Ins_Lec_ID = Lec_ID)
	    WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID_anterior ORDER BY Ins_Lec_ID DESC";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){		
			$row = mysqli_fetch_array($result);	
			if (!empty($datos)) $datos .= '<br>';	
			$datos .= "<strong>Ciclo $row[Lec_Nombre]:</strong> $row[Cur_Nombre] '$row[Div_Nombre]' $row[Niv_Nombre]";
		}
	}//fin div anterior
	
	return $datos;
}//fin function
function buscarCursoDivisionPersonaActual($PerID){
	
	$LecID = gLectivoActual($texto);
	$LecID_Siguiente = $LecID+1;
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Inscripcion
    INNER JOIN Legajo ON (Ins_Leg_ID = Leg_ID)
	INNER JOIN Colegio_Nivel ON (Ins_Niv_ID = Niv_ID)
    INNER JOIN Curso ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division ON (Ins_Div_ID = Div_ID) 
    WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID ORDER BY Ins_Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$datos = "$row[Niv_Nombre]: $row[Cur_Siglas] $row[Div_Siglas]";
	}else{
		$sql = "SELECT * FROM Colegio_Inscripcion
	    INNER JOIN Legajo ON (Ins_Leg_ID = Leg_ID)
		INNER JOIN Colegio_Nivel ON (Ins_Niv_ID = Niv_ID)
	    INNER JOIN Curso ON (Ins_Cur_ID = Cur_ID)
	    INNER JOIN Division ON (Ins_Div_ID = Div_ID) 
	    INNER JOIN Lectivo ON (Ins_Lec_ID = Lec_ID) 
	    WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID_Siguiente ORDER BY Ins_Lec_ID DESC";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){		
			$row = mysqli_fetch_array($result);
			$datos = "$row[Lec_Nombre]-$row[Niv_Nombre]: $row[Cur_Siglas] $row[Div_Siglas]";
		}else{
			$datos = 'No se encuentra inscripto al Lectivo '.$texto;	
		}
		
	}
	
	
	return $datos;
}//fin function
/*function buscarCursoDivisionActual($PerID, $ConDivAnterior = false){
	
	$LecID = gLectivoActual($texto);
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
	INNER JOIN Colegio_Nivel 
        ON (Ins_Niv_ID = Niv_ID)
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID) WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID ORDER BY Ins_Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$datos = "$row[Cur_Nombre] '$row[Div_Nombre]' $row[Niv_Nombre]";
	}
	return $datos;	
	
}//fin function*/
/*function buscarCursoDivisionPersona($PerID, $ConDivAnterior = false){
	
	$LecID = gLectivoActual($texto);
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
	INNER JOIN Colegio_Nivel 
        ON (Ins_Niv_ID = Niv_ID)
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID) WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID ORDER BY Ins_Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$datos = "$row[Cur_Nombre] '$row[Div_Nombre]' $row[Niv_Nombre]";
	}
	$LecID = $LecID - 1;
	$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
	INNER JOIN Colegio_Nivel 
        ON (Ins_Niv_ID = Niv_ID)	
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID) WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID ORDER BY Ins_Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		if ($ConDivAnterior)
			$datos .= " [$row[Cur_Nombre] '$row[Div_Nombre]']";
	}
	if (empty($datos)){
		Obtener_LectivoActual($LecID, $texto);
		$sql = "SELECT * FROM
		Colegio_Inscripcion
		INNER JOIN Legajo 
			ON (Ins_Leg_ID = Leg_ID)
		INNER JOIN Curso 
			ON (Ins_Cur_ID = Cur_ID)
		INNER JOIN Division 
			ON (Ins_Div_ID = Div_ID)
		INNER JOIN Colegio_Nivel 
        ON (Ins_Niv_ID = Niv_ID)
		INNER JOIN Lectivo
			ON (Ins_Lec_ID = Lec_ID)	
			 WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID ORDER BY Ins_Lec_ID DESC";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){		
			$row = mysqli_fetch_array($result);
			$datos = "<strong>$row[Lec_Nombre]:</strong> [$row[Cur_Nombre] '$row[Div_Nombre]' $row[Niv_Nombre]]";
		}
	}
	return $datos;
}//fin function*/

function buscarOtrosDatosPersona($PerID){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT * FROM PersonaDatos 
        WHERE Dat_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$datos .= "<br />Teléfono: $row[Dat_Telefono]<br />";
		$datos .= "Celular: $row[Dat_Celular]<br />"; 
		$datos .= "Observaciones: $row[Dat_Observaciones]<br />";
		$datos .= "Domicilio: $row[Dat_Domicilio]<br />";
		return $datos; 
	}

}//fin function

function buscarEmailPersona($PerID){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT * FROM PersonaDatos 
        WHERE Dat_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$datos = "NO CARGADO";
	//echo $sql;
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$datos = $row['Dat_Email'];			
	}
	return $datos;

}//fin function

function buscarTelefonosPersona($PerID){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT * FROM PersonaDatos 
        WHERE Dat_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		if (!empty($row[Dat_Telefono]))
			$datos .= "Teléfono: $row[Dat_Telefono]<br />";
		if (!empty($row[Dat_Celular]))
			$datos .= "Celular: $row[Dat_Celular]<br />"; 
		if (!empty($row[Dat_Observaciones]))
			$datos .= "Observaciones: $row[Dat_Observaciones]<br />";
		//$datos .= "Domicilio: $row[Dat_Domicilio]<br />";
		return $datos; 
	}

}//fin function

function buscarLegajoPersona($PerID){
	
	/*$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);*/
	
	$sql = "SELECT * FROM Legajo 
        WHERE Leg_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		return $row[Leg_Numero]; 
	}
	return -1;
}//fin function
function buscarLegajoAspirante($PerID){
	
	$Legajo = buscarLegajoPersona($PerID);
	if ($Legajo==-1){
		return "<span class='nota_Aplazado'>No generado</span>";}
	else if ($Legajo==0) return "<span class='nota_Reprobado'>Legajo Vacío</span>"; else return $Legajo;
}//fin function

function buscarPagosParciales($datosCuota){
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT * FROM CuotaPago 
	 INNER JOIN Factura 
        ON (CuP_Fac_ID = Fac_ID)
	WHERE CuP_Lec_ID = $Cuo_Lec_ID AND CuP_Per_ID = $Cuo_Per_ID AND CuP_Niv_ID = $Cuo_Niv_ID AND CuP_CTi_ID=$Cuo_CTi_ID AND CuP_Alt_ID=$Cuo_Alt_ID AND CuP_Numero=$Cuo_Numero ORDER BY CuP_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){			
			echo cfecha($row[CuP_Fecha])." Recibo: <a href='#' onclick='fac_Detalle($row[Fac_ID])'>$row[Fac_Sucursal]-$row[Fac_Numero]</a> $".intval($row[CuP_Importe])."<br />";
		}//fin while
	}//fin if
}//fin function

function buscarPagosParcialesEze($datosCuota){
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT * FROM CuotaPago 
	 INNER JOIN Factura 
        ON (CuP_Fac_ID = Fac_ID)
	WHERE CuP_Lec_ID = $Cuo_Lec_ID AND CuP_Per_ID = $Cuo_Per_ID AND CuP_Niv_ID = $Cuo_Niv_ID AND CuP_CTi_ID=$Cuo_CTi_ID AND CuP_Alt_ID=$Cuo_Alt_ID AND CuP_Numero=$Cuo_Numero ORDER BY CuP_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){			
			echo cfecha($row[CuP_Fecha])." Recibo: <a href='#' onclick='fac_Detalle($row[Fac_ID])'>$row[Fac_Sucursal]-$row[Fac_Numero]</a> $".intval($row[Fac_ImporteTotal])."<br />";
		}//fin while
	}//fin if
}//fin function

function buscarPagosTotales($datosCuota){
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT SUM(CuP_Importe) AS Total FROM CuotaPago WHERE CuP_Lec_ID = $Cuo_Lec_ID AND CuP_Per_ID = $Cuo_Per_ID AND CuP_Niv_ID = $Cuo_Niv_ID AND CuP_CTi_ID=$Cuo_CTi_ID AND CuP_Alt_ID=$Cuo_Alt_ID AND CuP_Numero=$Cuo_Numero AND CuP_Anulada =0";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Total'];
	}//fin if
	//return 0;
}//fin function

function buscarPagosTotalesLibro($datosCuota){
	list( $LCu_Lec_ID, $LCu_Per_ID, $LCu_LNu_ID, $LCu_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT SUM(LCP_Importe) AS Total FROM LibroCuotaPago WHERE LCP_Lec_ID = $LCu_Lec_ID AND LCP_Per_ID = $LCu_Per_ID AND LCP_LNu_ID = $LCu_LNu_ID AND LCP_Numero=$LCu_Numero AND LCP_Anulado =0";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Total];
	}//fin if
	//return 0;
}//fin function

//NAHUEL 20/04/2013 FUNCIONES GENERALES
function cajaAbiertaUsuario($UsuID){
	$sql = "SELECT * FROM Caja WHERE Caja_Apertura IS NOT NULL AND Caja_Cierre IS NULL AND Caja_Usu_ID='$UsuID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Caja_ID];
	}else{
		return false;
	}
}//fin function
//FIN NAHUEL 20/04/2013 FUNCIONES GENERALES

function cajaSuperAbiertaUsuario(){
	$sql = "SELECT * FROM SuperCaja WHERE SCa_Apertura IS NOT NULL AND SCa_Cierre IS NULL";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[SCa_ID];
	}else{
		return false;
	}
}//fin function

function obtenerSaldoSuperCajaCorriente($SCa_ID){
	$sql = "SELECT SCC_Saldo FROM SuperCajaCorriente WHERE SCC_SCa_ID = $SCa_ID ORDER BY SCC_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[SCC_Saldo];
	}else{
		return 0;
	}
}//fin function

function actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID){
	$sql = "SELECT SUM(SCC_Debito)AS Debito, SUM(SCC_Credito)AS Credito FROM SuperCajaCorriente WHERE SCC_SCa_ID = $SCC_SCa_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$Debito = $row['Debito'];
		$Credito = $row['Credito'];
		$importe = $Credito - $Debito;
	}else{
		$importe = 0;
	}
	$sql = "UPDATE SuperCajaCorriente SET SCC_Saldo = '$importe' WHERE SCC_ID = $SCC_ID";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
}//fin function

/*NAHUEL 29-04-2013
buscarFoto
FIN NAHUEL 29-04-2013*/

function Obtener_Nivel($LecID, $PerID){
	
	$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
    INNER JOIN Persona
        ON (Leg_Per_ID = Per_ID) WHERE Per_ID = $PerID AND Ins_Lec_ID = $LecID";
		//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row[Ins_Niv_ID];
	}//fin if
	//return 0;
}//fin function

function obtenerPadreMadre($PerID){

	$sql = "SELECT * FROM Familia INNER JOIN Persona ON (Fam_Per_ID = Per_ID) WHERE Fam_Vin_Per_ID = $PerID AND Fam_FTi_ID = 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		if ($row[Per_Sexo]=="M") {
			return "(Papá)";
		}else {
			return "(Mamá)";
		}
	}else{
		return false;
	}
}//fin function

function obtenerAlumno($PerID, $LecID){
	//return "Hola";
	$sql = "SELECT * FROM Legajo INNER JOIN Persona ON (Leg_Per_ID = Per_ID) INNER JOIN Colegio_Inscripcion ON (Ins_Leg_ID = Leg_ID)     WHERE Leg_Per_ID = $PerID AND Ins_Lec_ID = $LecID";
	//return $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		if ($row[Per_Sexo]=="M") {
			return "(Alumno)";
		}else {
			return "(Alumna)";
		}		
	}else{
		return false;
	}
}//fin function

function revisarVacantes($Lec_ID, $Cur_ID, &$Inscriptos){
	$sql = "SELECT * FROM Counting WHERE Cou_Cur_ID='$Cur_ID' AND Cou_Lec_ID='$Lec_ID'"; 
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Vacantes = $row[Cou_Total];
	}else{
		$Vacantes = 0;
	}
	if ($Vacantes>0){
		$sql = "SELECT * FROM Colegio_Inscripcion  INNER JOIN Legajo 
        ON (Ins_Leg_ID = Legajo.Leg_ID) WHERE Ins_Lec_ID = $Lec_ID AND Ins_Cur_ID = $Cur_ID AND Leg_Baja = 0";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$TotalInscriptos = mysqli_num_rows($result);
		$Inscriptos = $TotalInscriptos;
		if ($Vacantes>$TotalInscriptos){
			return $Vacantes - $TotalInscriptos;
		}else{
			return 0;
		}
		
	}else{
		return 0;
	}
	
}//fin function

function buscarDatosEntrevista($Per_ID){
	$sql = "SELECT * FROM Entrevista INNER JOIN Sicopedagoga ON (Ent_Sic_ID = Sic_ID) WHERE Ent_Per_ID='$Per_ID'"; 
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			echo "[".cfecha($row[Ent_Fecha])." ".substr($row[Ent_Hora],0,5)." hs] $row[Sic_Nombre]<br />";
		}//fin while
	}else{
		echo "Sin Entrevista";
	}
	
}//fin function
function buscarDatosArregloEntrevista($Ent_per_ID) {
	//echo "Hola";exit;

	
	$Tabla = "Entrevista";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE Ent_per_ID='$Ent_per_ID' ORDER BY Ent_Fecha DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);									

		$datos .= "{\"Ent_per_ID\": \"" . $row[Ent_per_ID] . "\",\"";
		$datos .= "Ent_Sic_ID\": \"" . $row[Ent_Sic_ID] . "\",\"";
		$datos .= "Ent_Turno\": \"" . $row[Ent_Turno] . "\",\"";
		$datos .= "Ent_Fecha\": \"" . cfecha($row[Ent_Fecha]) . "\",\"";
		$datos .= "Ent_Hora\": \"" . $row[Ent_Hora] . "\",\"";
		$datos .= "Ent_Asistio\": \"" . $row[Ent_Asistio] . "\",\"";
		$datos .= "Ent_Estado\": \"" . $row[Ent_Estado] . "\"}";
		
		
		echo $datos;
	}
 }//fin funcion
 
function buscarCanceloMatricula($Lec_ID, $Per_ID, $Niv_ID){
	$sql = "SELECT * FROM CuotaPersona INNER JOIN CuotaTipo ON (Cuo_CTi_ID = CTi_ID) WHERE CTi_Inscripcion=1 AND Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID='$Per_ID' AND Cuo_Niv_ID = $Niv_ID AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0"; 
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		return false;
	}else{
		return true;
	}
	
}//fin function

function buscarAlumnoNuevo($Per_ID, $Niv_ID){
	$sql = "SELECT * FROM Colegio_Inscripcion INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID) WHERE Leg_Per_ID='$Per_ID'"; 
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo "$sql<br />";
	if (mysqli_num_rows($result)>1){
		return false;
	}else{
		return true;
	}
	
}//fin function

function buscarAlumnoEstaInscripto($Leg_ID, $Lec_ID){
	$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID='$Leg_ID' AND Ins_Lec_ID = '$Lec_ID'"; 
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo "$sql<br />";
	if (mysqli_num_rows($result)>0){
		return true;
	}else{
		return false;
	}
	
}//fin function

function buscarRequisitosFaltantePersona($LecID, $NivID, $PerID, $ReqID) {
    //echo "Hola";exit;
	//AND Req_Obligatorio = 1 AND Req_Inscripcion = 1
	if ($ReqID!=999999) $where = "AND Req_ID = $ReqID ";
	$sql = "SELECT * FROM Requisito WHERE Req_Niv_ID = $NivID $where AND Req_ID NOT IN (SELECT Pre_Req_ID FROM RequisitoPresentado WHERE Pre_Niv_ID = $NivID AND Pre_Per_ID = $PerID AND Pre_Lec_ID = $LecID)";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {//no existe
		$mostrar =  "Falta:";
		while ($row = mysqli_fetch_array($result)) {
			$mostrar .= "- $row[Req_Nombre]<br />";
		}
		return $mostrar;
	} else {
		return false;
		//echo "Se elimin� el requisito seleccionado.";
	}
}
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
     $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 


function generarUsuariosPadres(){

set_time_limit(120);

$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 1;
$sql = "SELECT DISTINCTROW Per_DNI, Per_Apellido, Per_Nombre FROM Familia INNER JOIN Persona 
        ON (Fam_Vin_Per_ID = Per_ID)
WHERE (Familia.Fam_FTi_ID =1)";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//echo "Comienza el proceso....<br />";
if (mysqli_num_rows($result)>0){
	while ($row = mysqli_fetch_array($result)){
		$DNI = $row[Per_DNI];
		$Apellido = $row[Per_Apellido];
		$Nombre = $row[Per_Nombre];
		//echo "Padre: $Nombre $Apellido ($DNI)<br />";
		guardarCuentaUsuario($DNI, $DNI, "$Nombre $Apellido");
		guardarRolUnico($DNI, 11, true);
		//echo "------------------------------------<br />";
	}//fin while
	echo "<br />....FIN....<br />";
	//echo "Total de registros: ".mysqli_num_rows($result);
}//fin if

}//fin function

function guardarRolUnico($Usuario="", $Rol="", $BuscarIDUsuario=false){
	//echo "Hola";exit;
	if (empty($Usuario) && empty($Rol)){
		$Usuario = $_POST['UsuID'];
		$Rol = $_POST['RolID'];
	}
	if ($BuscarIDUsuario){
		$sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$Usuario'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			$Usuario = $row[Usu_ID];
		}
	}

	$sql = "SELECT * FROM RolUsuario WHERE RUs_Usu_ID = '$Usuario'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
		$sql = "INSERT INTO RolUsuario (RUs_Usu_ID, RUs_Rol_ID) VALUES ('$Usuario', '$Rol')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se agregó correctamente el rol al usuario.";
	}else{
		$sql = "UPDATE RolUsuario SET RUs_Rol_ID = '$Rol' WHERE RUs_Usu_ID = '$Usuario'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se actualizó el rol al usuario.";
	}
	
	$sql = "SELECT * FROM RolUnidad WHERE RUn_Rol_ID = '$Rol'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		$sql = "SELECT * FROM Permiso WHERE Prm_Usu_ID = '$Usuario' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
		$result_permiso = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result_permiso)>0){
			//ya existe, actualizamos
			$sql = "UPDATE Permiso SET Prm_Visible = '$row[RUn_Visible]', Prm_Bloqueado = '$row[RUn_Bloqueado]', Prm_Guardar = '$row[RUn_Guardar]', Prm_Modificar = '$row[RUn_Modificar]', Prm_Eliminar = '$row[RUn_Eliminar]', Prm_Imprimir = '$row[RUn_Imprimir]' WHERE Prm_Usu_ID = '$Usuario' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}else{
			//Insertamos el nuevo permiso
			$sql = "INSERT INTO Permiso (Prm_Usu_ID, Prm_Men_ID, Prm_Opc_ID, Prm_Uni_ID, Prm_Visible, Prm_Bloqueado, Prm_Guardar, Prm_Modificar, Prm_Eliminar, Prm_Imprimir) VALUES ('$Usuario', '$row[RUn_Men_ID]', '$row[RUn_Opc_ID]', '$row[RUn_Uni_ID]', '$row[RUn_Visible]', '$row[RUn_Bloqueado]', '$row[RUn_Guardar]', '$row[RUn_Modificar]', '$row[RUn_Eliminar]', '$row[RUn_Imprimir]')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}
		
	}//fin del while
	

}//fin funcion

function buscarDocenteInicialAlumno($Lec_ID, $Cur_ID, $Div_ID){
	
	$sql = "SET NAMES UTF8";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT
    Persona.Per_Apellido
    , Persona.Per_Nombre
    , Persona.Per_DNI
FROM
    Colegio_DocenteCurso
    INNER JOIN Colegio_Docente 
        ON (DCu_Doc_ID = Doc_ID)
    INNER JOIN Persona 
        ON (Doc_Per_ID = Per_ID) 
        WHERE DCu_Lec_ID = $Lec_ID AND DCu_Cur_ID = $Cur_ID AND DCu_Div_ID = $Div_ID AND DCu_DocenteEspecial=0 AND DCu_Directora=0";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		while ($row = mysqli_fetch_array($result)){
			if (empty($texto)) 
				$texto = utf8_decode("$row[Per_Apellido], $row[Per_Nombre]");
			else 
				$texto = utf8_decode(" - $row[Per_Apellido], $row[Per_Nombre]");
		}//fin while
		return $texto; 
	}
	return -1;
}//fin function
function ObtenerInscripcionLectivo($Leg_ID, $Lec_ID){

	$sql = "SELECT * FROM
    Colegio_Inscripcion WHERE Ins_Leg_ID = $Leg_ID AND Ins_Lec_ID = $Lec_ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$mostrar = "$row[Ins_Cur_ID] $row[Ins_Div_ID]";	
	}//fin if
	return $mostrar;
	
}//fin funcion

function tieneLibroPersona($Per_ID, $Lib_ID){

	$sql = "SELECT * FROM
    LibroVenta WHERE LVe_Per_ID = $Per_ID AND LVe_Lib_ID = $Lib_ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$mostrar = "SI";
		/*$row = mysqli_fetch_array($result);
		$mostrar = "$row[Ins_Cur_ID] $row[Ins_Div_ID]";	*/
	}//fin if
	return $mostrar;
	
}//fin funcion

function obtenerAlumnosCurso($Lec_ID, $Niv_ID, $Cur_ID, $Div_ID, &$AlumnosConBaja){
	
	$sql = "SELECT * FROM Colegio_Inscripcion INNER JOIN Legajo ON (Ins_Leg_ID = Leg_ID) WHERE Ins_Lec_ID = $Lec_ID AND Ins_Niv_ID = $Niv_ID AND Ins_Cur_ID = $Cur_ID AND Ins_Div_ID = $Div_ID AND Leg_Baja = 0";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$TotalInscriptos = mysqli_num_rows($result);
	
	$sql = "SELECT * FROM Colegio_Inscripcion INNER JOIN Legajo ON (Ins_Leg_ID = Leg_ID) WHERE Ins_Lec_ID = $Lec_ID AND Ins_Niv_ID = $Niv_ID AND Ins_Cur_ID = $Cur_ID AND Ins_Div_ID = $Div_ID AND Leg_Baja = 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$AlumnosConBaja = mysqli_num_rows($result);
	
	return $TotalInscriptos;
	
	
}//fin function	

function guardarAsignacionCuotaEspecial($PerID, $CMo_Lec_ID, $CMo_Niv_ID, $CMo_CTi_ID, $CMo_Alt_ID, $CMo_Numero) {
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SELECT * FROM CuotaModelo WHERE CMo_Lec_ID = $CMo_Lec_ID AND CMo_Niv_ID = $CMo_Niv_ID AND CMo_CTi_ID = $CMo_CTi_ID AND CMo_Alt_ID = $CMo_Alt_ID AND CMo_Numero = $CMo_Numero";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//echo $sql;exit;
	while ($row2 = mysqli_fetch_array($result)) {
		$Numero = $row2['CMo_Numero'];
		$cant_cuo = $row2['CMo_CantCuotas']+$Numero;
		//$cant_cuo = $row2['CMo_CantCuotas'];
		$mes = $row2['CMo_Mes'];
		$anio = $row2['CMo_Anio'];
		$fechaCuota = $row2['CMo_1er_Vencimiento'];

		for ($i = $Numero; $i < $cant_cuo; $i++) {
			
			if ($i>$Numero){
				$mes++;
				if ($mes>12) $anio++;
				$mes = $mes%12;
				if ($mes==0) $mes=12;
				$fechaCuota = "$anio-$mes-".substr($row2['CMo_1er_Vencimiento'],-2);
			}

			$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Per_ID = $PerID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_Lec_ID = $CMo_Lec_ID AND  Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Mes = $mes AND Cuo_Anio = $anio";
			//echo $sql;
			$result_verif = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			$continuar=false;
			if (mysqli_num_rows($result_verif)==0){
				$continuar = true;
				
				$Cuo_Ben_ID = 1;
				
				if ($continuar){
					$sql = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal, Cuo_Agrupa)
			values($PerID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $CMo_Lec_ID, $row2[CMo_Alt_ID], $i, 1, $UsuID, '$Fecha', '$Hora', '$row2[CMo_Importe]', '$row2[CMo_1er_Recargo]', '$row2[CMo_2do_Recargo]', '$fechaCuota', '$fechaCuota', '$fechaCuota', $mes, $anio, '0','0','0', '$row2[CMo_Recargo_Mensual]', '$row2[CMo_Importe]', '$row2[CMo_Agrupa]')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
					/*$sql = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal, Cuo_Masivo)
					values($Cuo_Per_ID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $Cuo_Lec_ID, $row2[CMo_Alt_ID], $i, $Cuo_Ben_ID, $UsuID, '$Fecha', '$Hora', $row2[CMo_Importe], '$row2[CMo_1er_Recargo]', '$row2[CMo_2do_Recargo]', '$fecha1Venc', '$fecha2Venc', '$fecha3Venc', $mes, $anio, '0','0','0', '$row2[CMo_Recargo_Mensual]', '$row2[CMo_Importe]', '$masivo')";
					//if ($row2[CMo_CTi_ID]==2) echo "$sql<br>";
					consulta_mysql_2022($sql,basename(__FILE__),__LINE__);*/
					
				}
				
			}else{
				//echo 'Ya la tiene asignada';
			}
			
			
		}
	}
	
/*
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SELECT * FROM CuotaModelo WHERE CMo_Lec_ID = $CMo_Lec_ID AND CMo_Niv_ID = $CMo_Niv_ID AND CMo_CTi_ID = $CMo_CTi_ID AND CMo_Alt_ID = $CMo_Alt_ID AND CMo_Numero = $CMo_Numero";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//echo $sql;exit;
	while ($row2 = mysqli_fetch_array($result)) {
		$cant_cuo = $row2['CMo_CantCuotas'];
		$mes = $row2['CMo_Mes'];
		$anio = $row2['CMo_Anio'];
		$fechaCuota = $row2['CMo_1er_Vencimiento'];

		for ($i = 1; $i <= $cant_cuo; $i++) {
			
			if ($i>1){
				$mes++;
				if ($mes>12) $anio++;
				$mes = $mes%12;
				if ($mes==0) $mes=12;
				$fechaCuota = "$anio-$mes-".substr($row2['CMo_1er_Vencimiento'],-2);
			}
			
			$sql = "SELECT * FROM CuotaPersona WHERE Cuo_Lec_ID = $CMo_Lec_ID AND Cuo_Per_ID = $PerID AND Cuo_Niv_ID = $row2[CMo_Niv_ID] AND Cuo_CTi_ID = $row2[CMo_CTi_ID] AND Cuo_Alt_ID = $row2[CMo_Alt_ID] AND Cuo_Mes = $mes AND Cuo_Anio = $anio";
			//echo $sql;
			$resultCuota = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			if (mysqli_num_rows($resultCuota)==0){
			
				$sql = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal)
				values($PerID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $CMo_Lec_ID, $row2[CMo_Alt_ID], $i, 1, $UsuID, '$Fecha', '$Hora', '$row2[CMo_Importe]', '$row2[CMo_1er_Recargo]', '$row2[CMo_2do_Recargo]', '$fechaCuota', '$fechaCuota', '$fechaCuota', $mes, $anio, '0','0','0', '$row2[CMo_Recargo_Mensual]', '$row2[CMo_Importe]')";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			}
			
		}
	}//fin while*/
	//echo $sql;
	gObtenerApellidoNombrePersona($PerID, $Apellido, $Nombre, true);
	echo "$Apellido, $Nombre: Se agregó correctamente la nueva configuración<br />";
}

function obtenerBeneficioAlumno($Lec_ID, $Per_ID, $CTi_ID, &$Cuo_Ben_ID=1){

	//Deshabilitado 24/08/2021
	/*$sql = "SELECT DISTINCTROW Ben_Nombre, Cuo_Ben_ID FROM CuotaPersona
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID) WHERE Cuo_Lec_ID = '$Lec_ID' AND Cuo_Per_ID = $Per_ID AND Cuo_Ben_ID > 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Cuo_Ben_ID = $row['Cuo_Ben_ID'];
		return $row['Ben_Nombre'];
	}else {
		return false;
	}*/

	$sql = "SELECT DISTINCTROW Ben_Nombre, CBe_Ben_ID FROM PersonaBeneficio
	INNER JOIN CuotaBeneficio ON (CBe_Ben_ID = Ben_ID)    
    WHERE CBe_Lec_ID = '$Lec_ID' AND CBe_Per_ID = $Per_ID AND CBe_CTi_ID = $CTi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Cuo_Ben_ID = $row['CBe_Ben_ID'];
		return $row['Ben_Nombre'];
	}else {
		return false;
	}
	
}//fin funcion

function obtenerBeneficioAlumnoLectivo($Lec_ID, $Per_ID){

	$arrayBeneficios = array();
	$sql = "SELECT * FROM PersonaBeneficio
    INNER JOIN CuotaBeneficio 
        ON (CBe_Ben_ID = Ben_ID) WHERE CBe_Lec_ID = '$Lec_ID' AND CBe_Per_ID = $Per_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			
			$arrayBeneficios[] = array('CBe_ID' => $row['CBe_ID'], 'CBe_Ben_ID' => $row['CBe_Ben_ID'], 'CBe_Lec_ID' => $row['CBe_Lec_ID'], 'CBe_Desde' => $row['CBe_Desde'], 'CBe_Hasta' => $row['CBe_Hasta'], 'CBe_CTi_ID' => $row['CBe_CTi_ID'], 'Ben_Nombre' => $row['Ben_Nombre']);
		}
		
		
	}
	return $arrayBeneficios;
	
}//fin funcion



function obtenerPlanPagoAlumno($Lec_ID, $Per_ID){

	$sql = "SELECT * FROM PlanPago WHERE PPa_Per_ID = $Per_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['PPa_ID'];
	}else {
		return false;
	}
	
}//fin funcion

function buscarDatosPersonaCuota($Lec_ID, $Per_ID, $Niv_ID, $CTi_ID, $Alt_ID, $Numero){
	$sql = "SELECT * FROM CuotaPersona
    INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID) WHERE Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID='$Per_ID' AND Cuo_Niv_ID = $Niv_ID AND Cuo_CTi_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0"; 
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		return false;
	}else{
		return true;
	}
	
}//fin function

//Fecha: 18/05/2017
function validarEvaluacionesClase($ClaID){
	//echo "Hola";//exit;
			
	$sql = "SELECT * FROM Colegio_Evaluacion WHERE Eva_Cla_ID = $ClaID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	return mysqli_num_rows($result);				

}//fin funcion//*/

function obtenerFacturaPersona($CuP_Fac_ID){

	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPago
    INNER JOIN CuotaPersona 
        ON (CuP_Lec_ID = Cuo_Lec_ID) AND (CuP_Per_ID = Cuo_Per_ID) AND (CuP_Niv_ID = Cuo_Niv_ID) AND (CuP_CTi_ID = Cuo_CTi_ID) AND (CuP_Alt_ID = Cuo_Alt_ID) AND (CuP_Numero = Cuo_Numero) WHERE CuP_Fac_ID = $CuP_Fac_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Cuo_Per_ID'];
	}else {
		return false;
	}
	
}//fin funcion

//01-08-2017
function obtenerRecargoCuota($PerID, $datosCuota){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$sql = "SELECT * FROM CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero);";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		
		$importe = $row['Cuo_Importe'];
		
		$fechaHoy = date("d-m-Y");	
		$v1erVencimiento = cfecha($row['Cuo_1er_Vencimiento']);
		$v2doVencimiento = cfecha($row['Cuo_2do_Vencimiento']);
		$v3erVencimiento = cfecha($row['Cuo_3er_Vencimiento']);
		$dias_vencidos = restarFecha($v1erVencimiento, $fechaHoy);
		$mesesAtrazo = 0;
		$recargo = 0;
		//echo $dias_vencidos;
		if ( $dias_vencidos > 0 ){
			
			$fechaCuota = cfecha($row['Cuo_1er_Vencimiento']);
			$fechaCuota2 = $row['Cuo_1er_Vencimiento'];
			$dia15Cuota = substr($fechaCuota2,-2);
			$fechaHoy = date("d-m-Y");
			$fechaHoy2 = date("Y-m-d");
			$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
			$mesesAtrazo = 0;
			$recargo = 0;
			
			//echo $dias_vencidos;

			if ( $dias_vencidos > 0 ){
				$mesesAtrazo = restarMeses($fechaCuota2, $fechaHoy2);
				$dia15 = substr($fechaHoy2,-2);
				//echo "////$mesesAtrazo $dia15              /////";
				if ($mesesAtrazo==0 && $dia15>$dia15Cuota) $mesesAtrazo=1;elseif ($dia15>$dia15Cuota) $mesesAtrazo++;
				
				if ($mesesAtrazo==1){
					$recargo=$row['Cuo_1er_Recargo'];
				}else{
					$mesesAtrazo--;
					$recargo=$row['Cuo_1er_Recargo'] + ($row['Cuo_Recargo_Mensual'] * $mesesAtrazo);	
				}
			}

		}

			
	}
	return $recargo;

	
	

}//fin function

function guardarCuotaPersonaFactura($Per_ID, $Lec_ID, $Niv_ID, $CTi_ID, $Alt_ID, $Cuo_Numero, $Importe) {

	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);		
	
	$fechaCuota = date("Y-m-d");
	$mes = date("n");
	$anio = date("Y");		
	$sql = "INSERT INTO CuotaPersona (Cuo_Per_ID, Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lec_ID, Cuo_Alt_ID, Cuo_Numero, Cuo_Ben_ID, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado, Cuo_Recargo_Mensual, Cuo_ImporteOriginal)
	values($Per_ID, $Niv_ID, $CTi_ID, $Lec_ID, $Alt_ID, $Cuo_Numero, 1, $UsuID, '$Fecha', '$Hora', '$Importe', '0', '0', '$fechaCuota', '$fechaCuota', '$fechaCuota', $mes, $anio, '1','0','0', '0', '$Importe')";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

}//fin function

function buscarNumeroCuotaPersonaFactura($Per_ID, $Lec_ID, $Niv_ID, $CTi_ID, $Alt_ID){

	$Numero = 0;
	$sql = "SELECT MAX(Cuo_Numero) AS Numero FROM CuotaPersona WHERE Cuo_Per_ID = $Per_ID AND Cuo_Niv_ID = $Niv_ID AND Cuo_CTi_ID = $CTi_ID AND Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = $Alt_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	if (mysqli_num_rows($result)>0){	
		$row = mysqli_fetch_array($result);	
		$Numero = $row['Numero'] + 1;
		return $Numero;
	}else {
		return 1;
	}
	
}//fin funcion

function buscarFormaPagoFactura($Fac_ID, $Siglas = false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	if (empty($Fac_ID)) return false;

	$Fac_NC = esNotaDeCredito($Fac_ID);
	if ($Fac_NC){
		$Fac_ID = $Fac_NC;
	}

	$sql = "SELECT DISTINCTROW For_Nombre, For_Siglas FROM CuotaPagoDetalle
    INNER JOIN CuotaPago 
        ON (CPD_Lec_ID = CuP_Lec_ID) AND (CPD_Per_ID = CuP_Per_ID) AND (CPD_Niv_ID = CuP_Niv_ID) AND (CPD_CTi_ID = CuP_CTi_ID) AND (CPD_Alt_ID = CuP_Alt_ID) AND (CPD_Numero = CuP_Numero) AND (CPD_Orden = CuP_Orden)
    INNER JOIN FormaPago 
        ON (CPD_For_ID = For_ID) WHERE CuP_Fac_ID = $Fac_ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		if ($Siglas) return $row['For_Siglas'];else return $row['For_Nombre'];
		
	}else {
		$sql = "SELECT * FROM Factura WHERE Fac_ID = $Fac_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			$Fac_Recibo = $row['Fac_Sucursal']."-".$row['Fac_Numero'];
			$sql = "SELECT * FROM DeudorRecibo WHERE DRe_ReciboNumero = '$Fac_Recibo'";//echo $sql;
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			if (mysqli_num_rows($result)>0){
				return $row['DRe_FormaPago']." ".$row['DRe_DetallePago'];
			}
		}
		
		return false;
	}
	
}//fin funcion

function buscarFormaPagoFacturaDetalle($Fac_ID){

	if (empty($Fac_ID)) return false;
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT DISTINCTROW FDe_Nombre, CPD_Valor FROM CuotaPagoDetalle
    INNER JOIN CuotaPago 
        ON (CPD_Lec_ID = CuP_Lec_ID) AND (CPD_Per_ID = CuP_Per_ID) AND (CPD_Niv_ID = CuP_Niv_ID) AND (CPD_CTi_ID = CuP_CTi_ID) AND (CPD_Alt_ID = CuP_Alt_ID) AND (CPD_Numero = CuP_Numero) AND (CPD_Orden = CuP_Orden)
     INNER JOIN FormaPagoDetalle 
        ON (CPD_FDe_ID = FDe_ID) AND (CPD_For_ID = FDe_For_ID) WHERE CuP_Fac_ID = $Fac_ID AND FDe_ID<>1;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	if (mysqli_num_rows($result)>0){	
		$valor = "";	
		while ($row = mysqli_fetch_array($result)){
			$valor .= $row['FDe_Nombre'].":".$row['CPD_Valor']." ";
		}
		return $valor;
	}else {
		$Fac_NC = esNotaDeCredito($Fac_ID);
		//echo "Entre";
		if ($Fac_NC){
			$sql = "SELECT DISTINCTROW FDe_Nombre, CPD_Valor FROM CuotaPagoDetalle
		    INNER JOIN CuotaPago 
		        ON (CPD_Lec_ID = CuP_Lec_ID) AND (CPD_Per_ID = CuP_Per_ID) AND (CPD_Niv_ID = CuP_Niv_ID) AND (CPD_CTi_ID = CuP_CTi_ID) AND (CPD_Alt_ID = CuP_Alt_ID) AND (CPD_Numero = CuP_Numero) AND (CPD_Orden = CuP_Orden)
		     INNER JOIN FormaPagoDetalle 
		        ON (CPD_FDe_ID = FDe_ID) AND (CPD_For_ID = FDe_For_ID) WHERE CuP_Fac_ID = $Fac_NC AND FDe_ID<>1;";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			//echo $sql;
			if (mysqli_num_rows($result)>0){	
				$valor = "";	
				while ($row = mysqli_fetch_array($result)){
					$valor .= $row['FDe_Nombre'].":".$row['CPD_Valor']." ";
				}//fin while
				return $valor;
			}
		}else{
			return false;
		}
		
	}
	
}//fin funcion
 // Eze

function buscarNotasDeCredito($Fac_ID_c){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT Fac_ID, Fac_Sucursal, Fac_Numero FROM Factura WHERE Fac_ID_Ndec = $Fac_ID_c";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			return "Nota de credito <a href='#' onclick='fac_Detalle(".$row['Fac_ID'].")'>".$row['Fac_Sucursal']."-".$row['Fac_Numero']."</a> <br />";
		}
	}
}

function esNotaDeCredito($Fac_ID){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (empty($Fac_ID)) return false;
	$sql = "SELECT Fac_ID_Ndec FROM Factura WHERE Fac_ID = $Fac_ID AND Fac_ID_Ndec IS NOT NULL";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Fac_ID_Ndec'];
	}else{
		return false;
	}
}//fin function

function buscarInfoFactura($Fac_ID){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT FTi_Nombre, Fac_ID, Fac_Sucursal, Fac_Numero FROM Factura INNER JOIN FacturaTipo ON (Fac_FTi_ID = FTi_ID) WHERE Fac_ID = $Fac_ID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return "$row[FTi_Nombre] Nº $row[Fac_Sucursal]-$row[Fac_Numero]";
	}
}
// Eze

function buscarNombreCuentaTipo($CuT_ID){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM CuentaTipo WHERE CuT_ID = $CuT_ID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['CuT_Nombre'];
	}else{
		return false;
	}
}

function buscarNombreCuentaContable($Cue_ID){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Cuenta WHERE Cue_ID = $Cue_ID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Cue_Nombre'];
	}else{
		return false;
	}
}

function buscarUltimaOrdenPago(){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Egreso_Recibo ORDER BY Rec_ID DESC";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Rec_ID'] + 1;
	}else{
		return 1;
	}
}

function guardarMovimientoCuentaContable($CajaID, $CueID, $Concepto, $Debito, $Credito, $ForID, $Detalle){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "INSERT INTO CuentaMovimiento (CMo_For_ID, CMo_Cue_ID, CMo_Concepto, CMo_Debito, CMo_Credito, CMo_Fecha, CMo_Hora, CMo_Usu_ID, CMo_Detalle, CMo_Referencia, CMo_Caja_ID) VALUES($ForID, $CueID, '$Concepto', '$Debito', '$Credito', '$Fecha', '$Hora', $UsuID, '$Detalle', '0', $CajaID)";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
  if ($res['success'] == true){
      $CMo_ID = $res['id'];
  }else{
      echo "Error"; exit;
  }

	actualizarSaldoCuentaContable($CMo_ID, $CueID);
	
	return $CMo_ID;
	

}//fin function

function actualizarSaldoCuentaContable($Cue_ID, $CMo_ID){
	$sql = "SELECT SUM(CMo_Debito) AS Debe, SUM(CMo_Credito) AS Haber FROM CuentaMovimiento WHERE CMo_Cue_ID = $Cue_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Saldo = intval($row[Haber]) - intval($row[Debe]);
		$sql = "UPDATE CuentaMovimiento SET CMo_Saldo = '$Saldo' WHERE CMo_ID = $CMo_ID";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}

}//fin function

function buscarCeluarPersonaSMS($PerID){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT * FROM PersonaDatos 
        WHERE Dat_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	$celu = "";
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		if (!empty($row['Dat_Celular'])){
			$celu = $row['Dat_Celular'];
			$celu = str_replace("-", "", $celu);
			if (substr($celu, 0,2)=="15"){
				$celu = substr($celu, 2,20);
			}
			$celu = "264".$celu;
		}
			
		
		return $celu; 
		}
	

}//fin function

function obtenerHijos($PerID, &$arrarTutores, &$i){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)		
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) 
	WHERE Fam_Vin_Per_ID = $PerID AND Fam_FTi_ID = 1 ORDER BY FTi_ID, Per_Sexo DESC, Per_Apellido, Per_Nombre;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$i = 0;
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			$i++;
			$arrarTutores[$i]['Per_Apellido'] = $row[Per_Apellido];
			$arrarTutores[$i]['Per_Nombre'] = $row[Per_Nombre];
			$arrarTutores[$i]['Per_DNI'] = $row[Per_DNI];
			$arrarTutores[$i]['Per_ID'] = $row[Per_ID];
			$arrarTutores[$i]['Telefonos'] = buscarTelefonosPersona($row[Per_ID]);
			//$arrarTutores[$i]['CelularSMS'] = buscarCeluarPersonaSMS($row['Per_ID']);
			$arrarTutores[$i]['Per_Sexo'] = $row[Per_Sexo];
			//$arrarTutores[$i]['Email'] = buscarEmailPersona($row['Per_ID']);
			//$FTR = gbuscarFTiRelacionaTipo(2, $Nombre);
			//gbuscarFTiRelacionaTipo(2, $Nombre, $FTi_M, $FTi_F);
			if ($row[Per_Sexo]=="M")
				$arrarTutores[$i]['FTi_Tipo'] = "Hijo";
			else
				$arrarTutores[$i]['FTi_Tipo'] = "Hija";
		}
	}
	//echo $sql;
	//return $mostrar;
	
	
}//fin funcion

function buscarDatosPagoCheque($datosCuota,&$arreglo){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero) = explode(';', $datosCuota);
	$sql = "SELECT
    CuotaPagoDetalle.CPD_For_ID
    , FormaPago.For_Nombre
    , FormaPagoDetalle.FDe_Nombre
    , CuotaPagoDetalle.CPD_Valor
    , CuotaPagoDetalle.CPD_Fecha
    , CuotaPagoDetalle.CPD_Hora
    , CuotaPagoDetalle.CPD_Lec_ID
    , CuotaPagoDetalle.CPD_Per_ID
    , CuotaPagoDetalle.CPD_Niv_ID
    , CuotaPagoDetalle.CPD_CTi_ID
    , CuotaPagoDetalle.CPD_Alt_ID
    , CuotaPagoDetalle.CPD_Numero
    , CuotaPagoDetalle.CPD_Orden
FROM
    CuotaPagoDetalle
    INNER JOIN FormaPago 
        ON (CuotaPagoDetalle.CPD_For_ID = FormaPago.For_ID)
    INNER JOIN FormaPagoDetalle 
        ON (CuotaPagoDetalle.CPD_FDe_ID = FormaPagoDetalle.FDe_ID) AND (CuotaPagoDetalle.CPD_For_ID = FormaPagoDetalle.FDe_For_ID)
WHERE (CuotaPagoDetalle.CPD_For_ID = 2 AND CPD_Lec_ID = $Cuo_Lec_ID AND CPD_Per_ID=$Cuo_Per_ID AND CPD_Niv_ID=$Cuo_Niv_ID AND CPD_CTi_ID=$Cuo_CTi_ID AND CPD_Alt_ID=$Cuo_Alt_ID AND CPD_Numero=$Cuo_Numero)";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	//$i=0;
	if (mysqli_num_rows($result)>0){		
		while ($row = mysqli_fetch_array($result)){
			//$i++;
			$Nombre = $row[FDe_Nombre];
			$arreglo[$Nombre] = $row[CPD_Valor];
			//echo "$row[FDe_Nombre]: $row[CPD_Valor]<br />";
		}//fin while
		
			
	}	
		
	

}//fin function

function buscarDatosPagoLiqTarjetas($datosCuota,&$arreglo, &$Fac_ID){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	list($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero) = explode(';', $datosCuota);
	$sql = "SELECT
    CuotaPagoDetalle.CPD_For_ID
    , FormaPago.For_Nombre
    , CuP_Fac_ID
    , FormaPagoDetalle.FDe_Nombre
    , CuotaPagoDetalle.CPD_Valor
    , CuotaPagoDetalle.CPD_Fecha
    , CuotaPagoDetalle.CPD_Hora
    , CuotaPagoDetalle.CPD_Lec_ID
    , CuotaPagoDetalle.CPD_Per_ID
    , CuotaPagoDetalle.CPD_Niv_ID
    , CuotaPagoDetalle.CPD_CTi_ID
    , CuotaPagoDetalle.CPD_Alt_ID
    , CuotaPagoDetalle.CPD_Numero
    , CuotaPagoDetalle.CPD_Orden
FROM
    CuotaPagoDetalle
    INNER JOIN CuotaPago 
        ON (CuotaPagoDetalle.CPD_Lec_ID = CuP_Lec_ID) AND (CuotaPagoDetalle.CPD_Per_ID = CuP_Per_ID) AND (CuotaPagoDetalle.CPD_Niv_ID = CuP_Niv_ID) AND (CuotaPagoDetalle.CPD_CTi_ID = CuP_CTi_ID) AND (CuotaPagoDetalle.CPD_Alt_ID = CuP_Alt_ID) AND (CuotaPagoDetalle.CPD_Numero = CuP_Numero) AND (CuotaPagoDetalle.CPD_Orden = CuP_Orden)
    INNER JOIN FormaPago 
        ON (CuotaPagoDetalle.CPD_For_ID = FormaPago.For_ID)
    INNER JOIN FormaPagoDetalle 
        ON (CuotaPagoDetalle.CPD_FDe_ID = FormaPagoDetalle.FDe_ID) AND (CuotaPagoDetalle.CPD_For_ID = FormaPagoDetalle.FDe_For_ID)
WHERE (For_LiqTarjeta = 1 AND CPD_Lec_ID = $Cuo_Lec_ID AND CPD_Per_ID=$Cuo_Per_ID AND CPD_Niv_ID=$Cuo_Niv_ID AND CPD_CTi_ID=$Cuo_CTi_ID AND CPD_Alt_ID=$Cuo_Alt_ID AND CPD_Numero=$Cuo_Numero)";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	//$i=0;
	if (mysqli_num_rows($result)>0){		
		while ($row = mysqli_fetch_array($result)){
			//$i++;
			$Nombre = $row[FDe_Nombre];
			$arreglo[$Nombre] = $row[CPD_Valor];
			//echo "$row[FDe_Nombre]: $row[CPD_Valor]<br />";
			$Fac_ID = $row[CuP_Fac_ID];
		}//fin while	
	}else{
		$arreglo['Lote'] = "SIN LOTE";
	}	
		
	

}//fin function

function crearCuotaRecibo($datosCuota, $debito=false){
	//$seguir = true;
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	obtenerRegistroUsuario($Rec_Usu_ID, $Rec_Fecha, $Rec_Hora);
	
	if ($debito) $debito=1; else $debito=0;
	$sql = "SELECT * FROM CuotaRecibo    
WHERE (Rec_Lec_ID = $Cuo_Lec_ID AND Rec_Per_ID = $Cuo_Per_ID AND Rec_Niv_ID = $Cuo_Niv_ID AND Rec_CTi_ID=$Cuo_CTi_ID AND Rec_Alt_ID=$Cuo_Alt_ID AND Rec_Numero=$Cuo_Numero);";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {
		$sql = "INSERT INTO CuotaRecibo (Rec_Com_ID, Rec_Lec_ID, Rec_Per_ID, Rec_Niv_ID, Rec_CTi_ID, Rec_Alt_ID, Rec_Numero, Rec_Fecha, Rec_Hora, Rec_Usu_ID, Rec_Creada, Rec_Debito) VALUES(NULL, $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, '$Rec_Fecha', '$Rec_Hora', '$Rec_Usu_ID', 'Local', $debito)";
		//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res['success'] == true){
        return $res['id'];
    }else{
        echo "Error"; exit;
    }

	}else{
		$row = mysqli_fetch_array($result);
		return $row['Rec_ID'];
	}
	
	
}//fin function

function obtenerBeneficio($Ben_ID){

	$sql = "SELECT Ben_Nombre FROM CuotaBeneficio 
       WHERE Ben_ID = $Ben_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['Ben_Nombre'];
	}
	
}//fin funcion

function buscarFormaPagoCuota($datosCuota, &$Fac_ID, &$Fac_Numero){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	

	$sql = "SELECT DISTINCTROW CuP_Fac_ID, For_Nombre, For_Siglas FROM CuotaPagoDetalle
    INNER JOIN CuotaPago 
        ON (CPD_Lec_ID = CuP_Lec_ID) AND (CPD_Per_ID = CuP_Per_ID) AND (CPD_Niv_ID = CuP_Niv_ID) AND (CPD_CTi_ID = CuP_CTi_ID) AND (CPD_Alt_ID = CuP_Alt_ID) AND (CPD_Numero = CuP_Numero) AND (CPD_Orden = CuP_Orden)
    INNER JOIN FormaPago 
        ON (CPD_For_ID = For_ID) WHERE (CuP_Lec_ID = $Cuo_Lec_ID AND CuP_Per_ID = $Cuo_Per_ID AND CuP_Niv_ID = $Cuo_Niv_ID AND CuP_CTi_ID=$Cuo_CTi_ID AND CuP_Alt_ID=$Cuo_Alt_ID AND CuP_Numero=$Cuo_Numero);";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$Fac_ID = $row['CuP_Fac_ID'];
		$Fac_Numero = buscarInfoFactura($Fac_ID);
		return $row['For_Nombre'];		
	}else {
		return false;
	}
	
}//fin funcion

function buscarFormaPagoCuotaDetalle($datosCuota){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);

	$sql = "SELECT DISTINCTROW FDe_Nombre, CPD_Valor FROM CuotaPagoDetalle
    INNER JOIN CuotaPago 
        ON (CPD_Lec_ID = CuP_Lec_ID) AND (CPD_Per_ID = CuP_Per_ID) AND (CPD_Niv_ID = CuP_Niv_ID) AND (CPD_CTi_ID = CuP_CTi_ID) AND (CPD_Alt_ID = CuP_Alt_ID) AND (CPD_Numero = CuP_Numero) AND (CPD_Orden = CuP_Orden)
     INNER JOIN FormaPagoDetalle 
        ON (CPD_FDe_ID = FDe_ID) AND (CPD_For_ID = FDe_For_ID) WHERE CuP_Lec_ID = $Cuo_Lec_ID AND CuP_Per_ID = $Cuo_Per_ID AND CuP_Niv_ID = $Cuo_Niv_ID AND CuP_CTi_ID=$Cuo_CTi_ID AND CuP_Alt_ID=$Cuo_Alt_ID AND CuP_Numero=$Cuo_Numero AND FDe_ID<>1;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	if (mysqli_num_rows($result)>0){	
		$valor = "";	
		while ($row = mysqli_fetch_array($result)){
			$valor .= $row['FDe_Nombre'].":".$row['CPD_Valor']." ";
		}
		return $valor;
	}else {
		/*$Fac_NC = esNotaDeCredito($Fac_ID);
		//echo "Entre";
		if ($Fac_NC){
			$sql = "SELECT DISTINCTROW FDe_Nombre, CPD_Valor FROM CuotaPagoDetalle
		    INNER JOIN CuotaPago 
		        ON (CPD_Lec_ID = CuP_Lec_ID) AND (CPD_Per_ID = CuP_Per_ID) AND (CPD_Niv_ID = CuP_Niv_ID) AND (CPD_CTi_ID = CuP_CTi_ID) AND (CPD_Alt_ID = CuP_Alt_ID) AND (CPD_Numero = CuP_Numero) AND (CPD_Orden = CuP_Orden)
		     INNER JOIN FormaPagoDetalle 
		        ON (CPD_FDe_ID = FDe_ID) AND (CPD_For_ID = FDe_For_ID) WHERE CuP_Fac_ID = $Fac_NC AND FDe_ID<>1;";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			//echo $sql;
			if (mysqli_num_rows($result)>0){	
				$valor = "";	
				while ($row = mysqli_fetch_array($result)){
					$valor .= $row['FDe_Nombre'].":".$row['CPD_Valor']." ";
				}//fin while
				return $valor;
			}
		}else{
			return false;
		}*/
		
	}
	
}//fin funcion

function buscarUltimaOrdenIngreso(){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM DeudorRecibo ORDER BY DRe_ID DESC";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		return $row['DRe_ID'] + 1;
	}else{
		return 1;
	}
}//fin function

function guardarIngresoPagoCajaCorriente($CajaID, $Concepto, $importe, $ForID, $Recibo){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	
	$Concepto = "[$Recibo]: $Concepto";
	$Concepto = addslashes($Concepto);
	$Detalle = utf8_encode("ORDEN DE INGRESO");
	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Credito, CCC_Usu_ID, CCC_Fecha, CCC_Hora, CCC_Detalle, CCC_Referencia, CCC_For_ID) VALUES($CajaID, '$Concepto', $importe, $UsuID, '$Fecha', '$Hora', '$Detalle', '$datosCuota', $ForID)";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
	if ($res['success'] == true){
	  $CCoID = $res['id'];
	}else{
	  echo "Mal";exit;
	}
	actualizarSaldoCajaCorriente($CajaID, $CCCID);
	return $CCCID;

}//fin function


function guardarAsientoCajaCorriente($Caja_ID){
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $sql = "DELETE FROM AsientoCajaDetalle WHERE Asi_Caja_ID = $Caja_ID";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "DELETE FROM AsientoCaja WHERE AsC_Caja_ID = $Caja_ID";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT * FROM CajaCorriente WHERE CCC_Caja_ID = $Caja_ID ORDER BY CCC_ID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$Asi_Orden=0;
	while ($row = mysqli_fetch_array($result)){
		$Asi_Orden++;
		$Asi_For_ID = $row['CCC_For_ID'];
		$Asi_CCC_ID = $row['CCC_ID'];
		$datosCuota = $row['CCC_Referencia'];
		if (empty($datosCuota)){
			$Asi_CTi_ID = 0;
		}else{
			list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Asi_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
		}
		$Asi_Debe = $row['CCC_Debito'];
		$Asi_Haber = $row['CCC_Credito'];
		$Asi_Fecha = $row['CCC_Fecha'];
		$Asi_FechaHora = date("Y-m-d H:i:s");
		$Asi_Saldo = 0;
		$Asi_Usu_ID = 1;
		
		set_time_limit(0);

		if ($Asi_Debe!=$Asi_Haber){
			$sql = "INSERT INTO AsientoCajaDetalle (Asi_Caja_ID, Asi_Fecha, Asi_For_ID, Asi_CTi_ID, Asi_Orden, Asi_Debe, Asi_Haber, Asi_Saldo, Asi_FechaHora, Asi_Usu_ID, Asi_CCC_ID) VALUES('$Caja_ID', '$Asi_Fecha', '$Asi_For_ID', '$Asi_CTi_ID', '$Asi_Orden', '$Asi_Debe', '$Asi_Haber', '$Asi_Saldo', '$Asi_FechaHora', '$Asi_Usu_ID', '$Asi_CCC_ID')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}
	}//fin while

	$sql = "SELECT Asi_Caja_ID, Asi_For_ID, For_Nombre, SUM(Asi_Haber) AS Haber FROM AsientoCajaDetalle INNER JOIN FormaPago ON (For_ID = Asi_For_ID) WHERE Asi_Caja_ID = $Caja_ID GROUP BY Asi_Caja_ID, Asi_For_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		$AsC_Caja_ID = $row['Asi_Caja_ID'];
		$AsC_Concepto = $row['For_Nombre'];
		$AsC_For_ID = $row['Asi_For_ID'];
		$AsC_Debe = floatval($row['Haber']);// - $row['Debe']);
		$AsC_Haber = 0;
		$AsC_CTi_ID = 0;
		$AsC_FechaHora = date("Y-m-d H:i:s");;
		$AsC_Usu_ID = 1;
		$AsC_Tipo = "ingreso";
		set_time_limit(0);
		$sql = "INSERT INTO AsientoCaja (AsC_Caja_ID, AsC_Concepto, AsC_Debe, AsC_Haber, AsC_For_ID, AsC_CTi_ID, AsC_FechaHora, AsC_Usu_ID, AsC_Tipo) VALUES('$AsC_Caja_ID', '$AsC_Concepto', '$AsC_Debe', '$AsC_Haber', '$AsC_For_ID', '$AsC_CTi_ID', '$AsC_FechaHora', '$AsC_Usu_ID', '$AsC_Tipo')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

		/*$sql = "SELECT Asi_Caja_ID, Asi_For_ID, CTi_Nombre, Asi_CTi_ID, SUM(Asi_Haber) AS Haber FROM AsientoCajaDetalle INNER JOIN CuotaTipo ON (CTi_ID = Asi_CTi_ID) WHERE Asi_For_ID = $row[Asi_For_ID] AND Asi_Caja_ID = $AsC_Caja_ID GROUP BY Asi_Caja_ID, Asi_For_ID, Asi_CTi_ID";*/
		$sql = "SELECT CTi_Nombre, Asi_CTi_ID, SUM(Asi_Haber) AS Haber FROM AsientoCajaDetalle INNER JOIN CuotaTipo ON (CTi_ID = Asi_CTi_ID) WHERE Asi_For_ID = $row[Asi_For_ID] AND Asi_Caja_ID = $AsC_Caja_ID GROUP BY Asi_CTi_ID";
		//echo $sql;
		$resultDet = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($rowDet = mysqli_fetch_array($resultDet)){
			
			$AsC_Concepto = "    a ".$rowDet['CTi_Nombre'];
			$AsC_Haber = floatval($rowDet['Haber']);// - $row['Debe']);
			$AsC_Debe = 0;

			if ($AsC_Haber>0){
				$sql = "INSERT INTO AsientoCaja (AsC_Caja_ID, AsC_Concepto, AsC_Debe, AsC_Haber, AsC_For_ID, AsC_CTi_ID, AsC_FechaHora, AsC_Usu_ID, AsC_Tipo) VALUES('$AsC_Caja_ID', '$AsC_Concepto', '$AsC_Debe', '$AsC_Haber', '$AsC_For_ID', '$AsC_CTi_ID', '$AsC_FechaHora', '$AsC_Usu_ID', '$AsC_Tipo')";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			}

			
		}


	}//fin while
}//fin function

function buscarCuentaContableFormaPago($For_ID){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM FormaPago WHERE For_ID = $For_ID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		if (!empty($row['For_Cue_ID'])) return $row['For_Cue_ID'];
	}
	return false;
	
}//fin function

function verificarInscripcionPrevia($Per_ID, $Lec_ID){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM listado_matricula WHERE per_id = $Per_ID AND lec_id=$Lec_ID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		return true;
	}
	return false;
	
}//fin function


//Mario. 04/03/2022
function buscarUltimoCursoDivisionPersona($PerID){
	
	$datos='';
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//Buscamos la ultima inscripción del alumno
	$sql = "SELECT * FROM Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
	INNER JOIN Colegio_Nivel 
        ON (Ins_Niv_ID = Niv_ID)
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID) WHERE Leg_Per_ID = $PerID ORDER BY Ins_Lec_ID DESC LIMIT 1 ;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$datos = "<strong>Ciclo $texto:</strong> $row[Cur_Nombre] '$row[Div_Nombre]' $row[Niv_Nombre]";
	}
	
	return $datos;
}//fin function

?>