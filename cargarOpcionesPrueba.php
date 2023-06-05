<?php
header("Cache-Control: no-cache, must-revalidate"); 
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch($opcion){

	case "cambiarDivisionAlumnos":
		cambiarDivisionAlumnos();
		break;	

	default: 
		echo "La opci�n elegida no es v�lida";
}//fin switch

function cambiarDivisionAlumnos(){

	$LegID = $_POST['LegID'];
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$Inscriptos = $_POST['Inscriptos'];
	$CurNuevoID = $_POST['CurNuevoID'];
	$DivNuevoID = $_POST['DivNuevoID'];
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$i=0;
	$j=0;
	$bEntrar=false;
	foreach ($Inscriptos as $LegID){
		$bEntrar=true;
		//Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
		$sql = "SELECT * FROM Colegio_Inscripcion WHERE (Ins_Lec_ID = $LecID AND Ins_Leg_ID = $LegID";
		if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
		if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
		if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
		$sql.=");";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo "$sql - Entre";
		if (mysqli_num_rows($result)>0){//ya existe, actualizamos la inscripcion
			$row = mysqli_fetch_array($result);
			$sql = "UPDATE Colegio_Inscripcion SET Ins_Cur_ID = $CurNuevoID, Ins_Div_ID = $DivNuevoID, Ins_Usu_ID = $UsuID, Ins_Fecha = '$Fecha', Ins_Hora = '$Hora' WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID AND Ins_Cur_ID =$row[Ins_Cur_ID] AND Ins_Niv_ID = $row[Ins_Niv_ID] AND Ins_Div_ID = $row[Ins_Div_ID]";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			//echo "$sql<br />";
			$i++;			
		}else{
			$j++;
		}//*/
		
	}//fin foreach//*/
	if ($i>0){
		echo "Se cambiaron de divis�n a $i alumnos.<br />";
	}
	if ($j>0){
		echo "No se pudo cambiar de divis�n a $j alumnos porque el curso y la divis�n de origen no coinciden.";
	}
	if (!$bEntrar)	echo "Seleccione los alumnos que desea cambiar de divisi�n.";

}//fin funcion

function guardarPais(){
	//echo "Hola";exit;
	$Nombre = $_POST['Nombre'];
	$Nombre = strtoupper(trim(utf8_decode($Nombre)));
	$Nombre = arreglarCadenaMayuscula($Nombre);
	//$Pais = $_POST['Pais'];
	$ID = $_POST['ID'];
	
	if (!empty($Nombre)){
		$sql = "SELECT * FROM Pais WHERE Pai_Nombre = '$Nombre'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			echo "Ya existe";
		}else{
			$sql = "UPDATE Pais SET Pai_Nombre = '$Nombre' WHERE Pai_ID = $ID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Nombre;
		}
	}
}//fin funcion

function eliminarPais(){
	//echo "Hola";exit;
	$ID = $_POST['ID'];
	
	$sql = "SELECT * FROM Pais WHERE Pai_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		echo "El pa�s elegido no existe o ya fue eliminado.";
	}else{
		$sql = "SELECT COUNT(*) AS TOTAL FROM Provincia WHERE Pro_Pai_ID = $ID";
		$result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result_prov);
		if ($row[TOTAL]>0){//Tiene provincias vinculadas			
			echo "No se puede eliminar porque tiene ". $row[TOTAL] ." provincias relacionadas.";
		}else{
			$sql = "DELETE FROM Pais WHERE Pai_ID = $ID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se elimin� el pa�s seleccionado.";
		}


	}

}//fin funcion

function guardarProvincia(){

	$Nombre = $_POST['Nombre'];
	//echo "$Nombre"; exit;
	$Nombre = strtoupper(trim(utf8_decode($Nombre)));
	$Nombre = arreglarCadenaMayuscula($Nombre);
	$Pais = $_POST['Pais'];
	$ID = $_POST['ID'];
	//echo "$Pais-$Nombre-$ID"; exit;
	if (!empty($Nombre)){
		$sql = "SELECT * FROM Provincia WHERE Pro_Nombre = '$Nombre' AND Pro_Pai_ID = $Pais";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			echo "Ya existe";
		}else{
			$sql = "UPDATE Provincia SET Pro_Nombre = '$Nombre' WHERE Pro_ID = $ID AND Pro_Pai_ID = $Pais";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Nombre;
		}
	}
}//fin funcion

function eliminarProvincia(){
	//echo "Hola";exit;
	$ID = $_POST['ID'];
	$Pais = $_POST['Pais'];
	
	$sql = "SELECT * FROM Provincia WHERE Pro_ID = $ID AND Pro_Pai_ID = $Pais";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		echo "La provincia elegida no existe o ya fue eliminada.";
	}else{
		$sql = "SELECT COUNT(*) AS TOTAL FROM Localidad WHERE Loc_Pro_ID = $ID AND Loc_Pai_ID = $Pais";
		$result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result_prov);
		if ($row[TOTAL]>0){//Tiene localidades vinculadas			
			echo "No se puede eliminar porque tiene ". $row[TOTAL] ." localidades relacionadas.";
		}else{
			$sql = "DELETE FROM Provincia WHERE Pro_ID = $ID AND Pro_Pai_ID = $Pais";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se elimin� la provincia seleccionada.";
		}


	}

}//fin funcion

function guardarLocalidad(){

	$Nombre = $_POST['Nombre'];
	$Nombre = strtoupper(trim(utf8_decode($Nombre)));
	$Nombre = arreglarCadenaMayuscula($Nombre);
	$Pais = $_POST['Pais'];
	$Prov = $_POST['Prov'];
	$ID = $_POST['ID'];
	//echo "$Pais-$Nombre-$ID"; exit;
	if (!empty($Nombre)){
		$sql = "SELECT * FROM Localidad WHERE Loc_Nombre = '$Nombre' AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			echo "Ya existe";
		}else{
			$sql = "UPDATE Localidad SET Loc_Nombre = '$Nombre' WHERE Loc_ID = $ID AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Nombre;
		}
	}
}//fin funcion

function eliminarLocalidad(){
	//echo "Hola";exit;
	$ID = $_POST['ID'];
	$Pais = $_POST['Pais'];
	$Prov = $_POST['Prov'];
	
	$sql = "SELECT * FROM Localidad WHERE Loc_ID = $ID AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		echo "La localidad elegida no existe o ya fue eliminada.";
	}else{
		$sql = "SELECT COUNT(*) AS TOTAL FROM PersonaDatos WHERE Dat_Nac_Loc_ID = $ID AND Dat_Nac_Pai_ID = $Pais AND Dat_Nac_Pro_ID = $Prov";
		$result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result_prov);
		if ($row[TOTAL]>0){//Tiene personas vinculadas			
			echo "No se puede eliminar porque tiene ". $row[TOTAL] ." personas relacionadas.";
		}else{
			$sql = "DELETE FROM Localidad WHERE Loc_ID = $ID AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se elimin� la localidad seleccionada.";
		}


	}

}//fin funcion

function mostrarNivelEstudios(){

$ID = $_POST['ID'];

if (!empty($ID)){
	$sql = "SELECT
    Est_Ent_ID
    , Niv_Tit_Fem
    , Niv_Tit_Mas
FROM
    Estudio
    INNER JOIN EstudioNivel 
        ON (Est_Niv_ID = Niv_ID)
WHERE (Est_Ent_ID =$ID) ORDER BY Niv_Tit_Mas";
//	echo $sql;exit;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<hr /><table width='100%' border='0'><tr><td><strong>T�tulo Masc</strong></td><td><strong>T�tulo Fem</strong></td></tr>";
	while ($row = mysqli_fetch_array($result)){
		echo "    <tr><td>$row[Niv_Tit_Mas]</td>
    <td>$row[Niv_Tit_Fem]</td></tr>";
	}//fin while
	echo "  </table>";
}

}//fin funcion


function guardarEntidadEducativa(){

	$Nombre = $_POST['Nombre'];
	$Nombre = strtoupper(trim(utf8_decode($Nombre)));
	$Nombre = arreglarCadenaMayuscula($Nombre);

	$ID = $_POST['ID'];
	
	if (!empty($Nombre)){
		$sql = "SELECT * FROM EstudioEnte WHERE Ent_Nombre = '$Nombre'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			echo "Ya existe";
		}else{
			$sql = "UPDATE EstudioEnte SET Ent_Nombre = '$Nombre' WHERE Ent_ID = $ID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Nombre;
		}
	}
}//fin funcion
function eliminarEntidadEducativa(){
	//echo "Hola";exit;
	$ID = $_POST['ID'];
	
	$sql = "SELECT * FROM EstudioEnte WHERE Ent_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		echo "La entidad educativa elegida no existe o ya fue eliminado.";
	}else{
		$sql = "SELECT COUNT(*) AS TOTAL FROM Estudio INNER JOIN EstudioNivel ON (Est_Niv_ID = Niv_ID) WHERE (Est_Ent_ID = $ID) ORDER BY Niv_Tit_Mas";
		$result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result_prov);
		if ($row[TOTAL]>0){//Tiene provincias vinculadas			
			echo "No se puede eliminar porque tiene ". $row[TOTAL] ." niveles de estudios relacionadas.";
		}else{
			$sql = "DELETE FROM EstudioEnte WHERE Ent_ID = $ID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se elimin� la entidad educativa seleccionada.";
		}


	}

}//fin funcion

function mostrarEntidadEducativa(){

$ID = $_POST['ID'];

if (!empty($ID)){
	$sql = "SELECT Ent_Nombre FROM Estudio
     INNER JOIN EstudioEnte 
        ON (Est_Ent_ID = Ent_ID)
WHERE (Est_Niv_ID = $ID) ORDER BY Ent_Nombre";
	//echo $sql;exit;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<hr />";
	while ($row = mysqli_fetch_array($result)){
		echo "$row[Ent_Nombre]<br />";
	}//fin while
}

}//fin funcion

function guardarNivelEstudio(){

	$Nombre = $_POST['Nombre'];
	$Nombre = strtoupper(trim(utf8_decode($Nombre)));
	$Nombre = arreglarCadenaMayuscula($Nombre);
	$Campo = $_POST['Campo'];
	$ID = $_POST['ID'];
	
	if (!empty($Nombre)){
		$sql = "SELECT * FROM EstudioNivel WHERE $Campo = '$Nombre'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			echo "Ya existe";
		}else{
			$sql = "UPDATE EstudioNivel SET $Campo = '$Nombre' WHERE Niv_ID = $ID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Nombre;
		}
	}
}//fin funcion
function eliminarNivelEstudio(){
	//echo "Hola";exit;
	$ID = $_POST['ID'];
	
	$sql = "SELECT * FROM EstudioNivel WHERE Niv_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		echo "El nivel de estudios elegido no existe o ya fue eliminado.";
	}else{
		$sql = "SELECT COUNT(*) AS TOTAL FROM Estudio INNER JOIN EstudioEnte ON (Est_Ent_ID = Ent_ID) WHERE (Est_Niv_ID = $ID)";
		$result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result_prov);
		if ($row[TOTAL]>0){//Tiene vinculadciones
			echo "No se puede eliminar porque tiene ". $row[TOTAL] ." entidades educativas relacionadas.";
		}else{
			$sql = "DELETE FROM EstudioNivel WHERE Niv_ID = $ID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se elimin� el nivel de estudios seleccionada.";
		}


	}

}//fin funcion

function guardarEstudio(){

	$EntID = $_POST['EntID'];
	$NivID = $_POST['NivID'];
	$PaiID = $_POST['PaiID'];
	$ProID = $_POST['ProID'];
	$LocID = $_POST['LocID'];
	
	$sql = "SELECT * FROM Estudio WHERE Est_Ent_ID = $EntID AND Est_Niv_ID = $NivID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
		echo "Ya existe";
	}else{
		$sql = "INSERT INTO Estudio (Est_Ent_ID, Est_Niv_ID, Est_Pai_ID, Est_Pro_ID, Est_Loc_ID) VALUES ($EntID, $NivID, $PaiID, $ProID, $LocID)";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "<div class='borde_aviso'><span class='texto'>Los datos fueron relacionados correctamente.</span></div>	";
	}

}//fin funcion


function buscarEntPaiProLoc(){
	$EntID = $_POST['EntID'];
	$NivID = $_POST['NivID'];	
	$Buscar = $_POST['Buscar'];
	$sql = "SELECT * FROM Estudio WHERE Est_Ent_ID = $EntID AND Est_Niv_ID = $NivID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		if ($Buscar == "Pai") echo $row[Est_Pai_ID];
		if ($Buscar == "Pro") echo $row[Est_Pro_ID];
		if ($Buscar == "Loc") echo $row[Est_Loc_ID];			
	}else{
		echo 0;
	}
}//fin funcion

function buscarDNI(){
	$DNI = $_POST['DNI'];
	$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$foto = buscarFoto($DNI, $row[Per_Doc_ID], 60);//echo "TODO MAL $sql";exit;
		$edad = obtenerEdad($row[Per_ID]);
		?>

<table width='100%' border='0'>
  <tr>
    <td bgcolor="#C6E8FF" class="texto"><div align="center">Apellido y Nombre: <strong><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></strong><br />
	<?php
    if ($edad>0){
		echo "Edad: <strong>$edad a�os</strong>";
	}
	?></div></td>
    <td width="60" bgcolor="#C6E8FF">
    <div align="center"><?php echo $foto;?></div></td>
  </tr>
</table>
	<?php }else{?>
		<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El DNI ingresado no corresponde a una persona cargada, por favor verifique los datos ingresados antes de continuar.</span><br />
	<?php }

}//fin funcion

function buscarPerID(){
	$DNI = $_POST['DNI'];
	$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		echo $row[Per_ID];
	}else{
		echo 0;
	}

}//fin funcion

function buscarLegajoColegio(){
	$DNI = $_POST['DNI'];
	$sql = "SELECT
    Leg_ID
    , Leg_Per_ID
    , Per_DNI FROM Legajo
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID) WHERE Per_DNI = $DNI AND Leg_Colegio = 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		echo $row[Leg_ID];
	}else{
		echo 0;
	}

}//fin funcion

function obtenerApellidoNombre(){
	$PerID = $_POST['PerID'];
	$conDNI = $_POST['conDNI'];
	if (!empty($conDNI)){
		$DNI = $_POST['DNI'];
		$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
	}else{
		$sql = "SELECT * FROM Persona WHERE Per_ID = $PerID";
	}
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		echo utf8_decode("$row[Per_Apellido], $row[Per_Nombre]");
	}else{
		echo "";
	}

}//fin funcion

function ordenarMenuArriba(){

	$MenID = $_POST['MenID'];//2
	$Orden = $_POST['Orden'];//2
	
	$Orden_reemplazar = $Orden;
	$Orden = $Orden-1;	
	$sql = "SELECT * FROM Menu WHERE Men_Orden = $Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$MenID_reemplazar = $row[Men_ID];
	}
	mysqli_close ();
	
	$i = 0;
	if ($Orden < 1){
		$Orden = 1;
		$Orden_reemplazar = 2;
	}
	$mismo = false;
	if ($MenID_reemplazar == $MenID) $mismo = true;
	
	$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ( $row = mysqli_fetch_array($result) ){
		$i++;
		$OrdenNuevo = $i;
		$Menu = $row[Men_ID];
		if ($Menu == $MenID){
			$OrdenNuevo = $Orden;//1
		}
		if ( ($Menu == $MenID_reemplazar) && (!$mismo) ){
			$OrdenNuevo = $Orden_reemplazar;//2
		}
		
		$sql = "UPDATE Menu SET Men_Orden = $OrdenNuevo WHERE Men_ID = $Menu";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	}//fin while

}//fin funcion

function ordenarMenuAbajo(){

	$MenID = $_POST['MenID'];//2
	$Orden = $_POST['Orden'];//2
	
	$Orden_reemplazar = $Orden;
	$Orden = $Orden+1;	
	$sql = "SELECT * FROM Menu WHERE Men_Orden = $Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$MenID_reemplazar = $row[Men_ID];
	}
	mysqli_close ();
	$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$i = 0;
	if ($Orden > mysqli_num_rows($result)){
		$Orden = mysqli_num_rows($result);
		$Orden_reemplazar = $Orden - 1;
	}
	$mismo = false;
	if ($MenID_reemplazar == $MenID) $mismo = true;
	
	while ( $row = mysqli_fetch_array($result) ){
		$i++;
		$OrdenNuevo = $i;
		$Menu = $row[Men_ID];
		if ($Menu == $MenID){
			$OrdenNuevo = $Orden;//1
		}
		if ( ($Menu == $MenID_reemplazar) && (!$mismo) ){
			$OrdenNuevo = $Orden_reemplazar;//2
		}
		
		$sql = "UPDATE Menu SET Men_Orden = $OrdenNuevo WHERE Men_ID = $Menu";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	}//fin while

}//fin funcion

function mostrarMenuOpciones(){

$ID = $_POST['ID'];

if (!empty($ID)){
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $ID ORDER BY Opc_Orden";

	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<ul>";
	while ($row = mysqli_fetch_array($result)){
		echo "<li>$row[Opc_Nombre]</li>";
	}//fin while
	echo "</ul>";
}

}//fin funcion

function guardarMenu(){
	//echo "Hola";exit;
	$Nombre = $_POST['Nombre'];
	$Nombre = trim(utf8_decode($Nombre));
	//$Nombre = arreglarCadenaMayuscula($Nombre);

	$ID = $_POST['ID'];
	
	if (!empty($Nombre)){
		$sql = "SELECT * FROM Menu WHERE Men_Nombre = '$Nombre'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			echo "Ya existe";
		}else{
			$sql = "UPDATE Menu SET Men_Nombre = '$Nombre' WHERE Men_ID = $ID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Nombre;
		}
	}
}//fin funcion

function guardarOpcion(){
	//echo "Hola";exit;
	$MenID = $_POST['MenID'];
	$Nombre = $_POST['Nombre'];
	$Nombre = trim(utf8_decode($Nombre));
	//$Nombre = arreglarCadenaMayuscula($Nombre);

	$ID = $_POST['ID'];
	
	if (!empty($Nombre)){
		$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID AND Opc_Nombre = '$Nombre'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			echo "Ya existe";
		}else{
			$sql = "UPDATE Opcion SET Opc_Nombre = '$Nombre' WHERE Opc_ID = $ID AND Opc_Men_ID = $MenID";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Nombre;
		}
	}
}//fin funcion

function llenarOpcionesTabla(){

$Menu = $_POST['Menu'];

if (!empty($Menu)){
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $Menu AND Opc_Orden <> 99";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){
		$i=0;
		$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $Menu ORDER BY Opc_Orden";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ( $row = mysqli_fetch_array($result) ){		
			$i++;
			$sql = "UPDATE Opcion SET Opc_Orden = $i WHERE Opc_ID = $row[Opc_ID] AND Opc_Men_ID = $Menu";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}//fin while
	}
	$sql = "SELECT * FROM Opcion, Menu WHERE Men_ID = Opc_Men_ID AND Opc_Men_ID = $Menu ORDER BY Opc_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$total = mysqli_num_rows($result);
	if ($total>0){
	?>
	 <script language="javascript">
	 $("img[id^='arriba']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(6,10);
		//alert(i);
		vMenID = $("#MenuID").val();
		vOpcID = $("#Nuevo" + i).val();
		vOrden = $("#Orden" + i).val();
		$.post("cargarOpciones.php", {opcion: "ordenarOpcionArriba", MenID: vMenID, OpcID: vOpcID, Orden: vOrden}, function(){
			//cargarOpcionOrden();
			llenarOpcionesMenu(vMenID);
		});
	 });//fin evento click//*/	
	 function llenarOpcionesMenu(vMenu){
		$.post("cargarOpciones.php", { opcion: 'llenarOpcionesTabla', Menu: vMenu },	function(data){
     			$("#listado").html(data);
				$(".input_editar").hide();
   		});
	}//*/
	 $("img[id^='abajo']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(5,10);
		//alert(i);
		vMenID = $("#MenuID").val();
		vOpcID = $("#Nuevo" + i).val();
		vOrden = $("#Orden" + i).val();
		
		$.post("cargarOpciones.php", {opcion: "ordenarOpcionAbajo", MenID: vMenID, OpcID: vOpcID, Orden: vOrden}, function(){
			//cargarOpcionOrden();
			llenarOpcionesMenu(vMenID);
		});
	 });//fin evento click//*/		 
	 function cargarOpcionOrden(){
		//alert("");
		$("#mostrar").load("buscarOpcionOrdenar.php");
		
	}
		 $("input[id^='editar']").keyup(function(evento){
	 	if (evento.keyCode == 13){
			var i = this.id.substr(6,10);
			guardarNombre(i);
		}
	 });
	 $("input[id^='editar']").focusout(function(evento){
		var i = this.id.substr(6,10);
		guardarNombre(i);
	 });
	 function guardarNombre(i){
		var vNombre = $("#editar" + i).val();
		var vID = $("#Nuevo" + i).val();
		vNombre1 = $("#nombrePais" + i).text();
		vNombre2 = $("#editar" + i).val();
		vMenID = $("#MenuID").val();
		if (vNombre1 != vNombre2){
			$.post("cargarOpciones.php", { opcion: "guardarOpcion", Nombre: vNombre, MenID: vMenID, ID: vID },function(data){
			if (data=='Ya existe')
				mostrarAlerta("El nombre que intenta ingresar ya existe.", "");
			else
				$("#nombrePais" + i).text(data);
			});
		}//fin if
		$("#nombrePais" + i).show();
		$("#editar" + i).hide();
	 
	 }//fin funcion
	 $("span[id^='nombrePais']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(10,10);		
		$("#nombrePais" + i).hide();
		$("#editar" + i).val($("#nombrePais" + i).text());
		$("#editar" + i).show();
		$("#editar" + i).focus();
	 });//fin evento click//*/	 
	 </script>
	 <fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b�squeda</legend>

	 <table width="100%" border="0">
    <tr>
      <td class="fila_titulo" width="90">&nbsp;</td>
      <td class="fila_titulo" width="90"><div align="center">Orden</div></td>
      <td class="fila_titulo"><div align="left">Nombre de la Opci&oacute;n </div></td>
      <td class="fila_titulo"><div align="left">Comando </div></td>      
      <td class="fila_titulo"><div align="left">Pertenece al Men&uacute; </div></td>
    </tr>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>">
      <td><input name="Nuevo<?php echo $i;?>" type="checkbox" id="Nuevo<?php echo $i;?>" value="<?php echo $row[Opc_ID];?>"><input type="hidden" id="Orden<?php echo $i;?>" value="<?php echo $row[Opc_Orden];?>">
      <?php
	  if ($i==1) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	  if ($i>1){
	  ?><img id="arriba<?php echo $i;?>" src="botones/orden_arriba.gif" alt="Mover arriba" title="Mover arriba" width="20" height="20" style="cursor:pointer" />
	  <?php
	  }//fin if
	  if ($i<$total){
	  ?>
	  <img id="abajo<?php echo $i;?>" src="botones/orden_abajo.gif" alt="Mover abajo" title="Mover abajo" style="cursor:pointer" width="20" height="20" />
	  <?php
	  }//fin if
	  ?>	  </td>
      <td><div align="center"><?php echo $row[Opc_Orden];?></div></td>
      <td><span id="nombrePais<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre"><?php echo $row[Opc_Nombre];?></span>
      <input name="editar<?php echo $i;?>" type="text" id="editar<?php echo $i;?>" value="<?php echo $row[Opc_Nombre];?>" class="input_editar" /></td>
      
      <td><?php echo $row[Opc_Comando];?></td>
      <td><?php echo $row[Men_Nombre];?></td>
    </tr>
		  <?php		  
	}//fin del while
	?>  
	</table>
	</fieldset>
	<fieldset class="recuadro_inferior">
	<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se econtraron $total opciones cargados";?>
	</fieldset>	
	<?php
	}else{
	?>
		<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron opciones asociadas al men� seleccionado.</span>
	<?php
	}
	?>	

	<?php
}

}//fin funcion//*/


function ordenarOpcionArriba(){

	$MenID = $_POST['MenID'];//2
	$OpcID = $_POST['OpcID'];//2
	$Orden = $_POST['Orden'];//2
	
	$Orden_reemplazar = $Orden;
	$Orden = $Orden-1;	
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID AND Opc_Orden = $Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$OpcID_reemplazar = $row[Opc_ID];
	}
	mysqli_close ();
	
	$i = 0;
	if ($Orden < 1){
		$Orden = 1;
		$Orden_reemplazar = 2;
	}
	$mismo = false;
	if ($OpcID_reemplazar == $OpcID) $mismo = true;
	
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID ORDER BY Opc_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ( $row = mysqli_fetch_array($result) ){
		$i++;
		$OrdenNuevo = $i;
		$Opcion = $row[Opc_ID];
		if ($Opcion == $OpcID){
			$OrdenNuevo = $Orden;//1
		}
		if ( ($Opcion == $OpcID_reemplazar) && (!$mismo) ){
			$OrdenNuevo = $Orden_reemplazar;//2
		}
		
		$sql = "UPDATE Opcion SET Opc_Orden = $OrdenNuevo WHERE Opc_Men_ID = $MenID AND Opc_ID = $Opcion";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	}//fin while

}//fin funcion

function ordenarOpcionAbajo(){

	$MenID = $_POST['MenID'];//2
	$OpcID = $_POST['OpcID'];//2
	$Orden = $_POST['Orden'];//2
	
	$Orden_reemplazar = $Orden;
	$Orden = $Orden+1;	
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID AND Opc_Orden = $Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$OpcID_reemplazar = $row[Opc_ID];
	}
	mysqli_close ();
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID ORDER BY Opc_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$i = 0;
	if ($Orden > mysqli_num_rows($result)){
		$Orden = mysqli_num_rows($result);
		$Orden_reemplazar = $Orden - 1;
	}
	$mismo = false;
	if ($OpcID_reemplazar == $OpcID) $mismo = true;
	
	while ( $row = mysqli_fetch_array($result) ){
		$i++;
		$OrdenNuevo = $i;
		$Opcion = $row[Opc_ID];
		if ($Opcion == $OpcID){
			$OrdenNuevo = $Orden;//1
		}
		if ( ($Opcion == $OpcID_reemplazar) && (!$mismo) ){
			$OrdenNuevo = $Orden_reemplazar;//2
		}
		
		$sql = "UPDATE Opcion SET Opc_Orden = $OrdenNuevo WHERE Opc_Men_ID = $MenID AND Opc_ID = $Opcion";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	}//fin while

}//fin funcion

function guardarUsuario(){
	//echo "Hola";exit;
	$Usuario = $_POST['Usuario'];
	$Persona = ucwords(strtolower(utf8_decode($_POST['Persona'])));
	$Clave = md5($_POST['Clave']);

	$sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$Usuario'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
		$sql = "INSERT INTO Usuario (Usu_Nombre, Usu_Persona, Usu_Clave) VALUES ('$Usuario', '$Persona', '$Clave')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);			
		echo "Se agreg� correctamente el usuario <strong>$Usuario</strong> a la persona <strong>$Persona</strong>";
	}else{
		$sql = "UPDATE Usuario SET Usu_Persona = '$Persona', Usu_Clave = '$Clave' WHERE Usu_Nombre = '$Usuario'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se actualizaron los datos del usuario <strong>$Usuario</strong> para la persona <strong>$Persona</strong>";
	}

}//fin funcion

function guardarRol($Usuario, $Rol){
	//echo "Hola";exit;
	if (empty($Usuario) && empty($Rol)){
		$Usuario = $_POST['UsuID'];
		$Rol = $_POST['RolID'];
	}

	$sql = "SELECT * FROM RolUsuario WHERE RUs_Usu_ID = '$Usuario'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
		$sql = "INSERT INTO RolUsuario (RUs_Usu_ID, RUs_Rol_ID) VALUES ('$Usuario', '$Rol')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se agreg� correctamente el rol al usuario.";
	}else{
		$sql = "UPDATE RolUsuario SET RUs_Rol_ID = '$Rol' WHERE RUs_Usu_ID = '$Usuario'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se actualiz� el rol al usuario.";
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

function guadarPermisoUsuario(){
	//echo "Hola";exit;
	$UsuID = $_POST['UsuID'];
	$MenID = $_POST['MenID'];
	$OpcID = $_POST['OpcID'];
	$Boton = $_POST['Boton'];
	$UniID = $_POST['UniID'];			

		$sql = "SELECT * FROM Permiso WHERE Prm_Usu_ID = '$UsuID' AND Prm_Men_ID = '$MenID' AND Prm_Opc_ID = '$OpcID' AND Prm_Uni_ID = '$UniID'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){//no existe
			$sql = "INSERT INTO Permiso (Prm_Usu_ID, Prm_Men_ID, Prm_Opc_ID, Prm_Uni_ID, Prm_$Boton) VALUES ('$UsuID', '$MenID', '$OpcID', '$UniID', '1')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se agreg� correctamente el permiso al usuario.";
		}else{
			$sql = "UPDATE Permiso SET Prm_$Boton = '1' WHERE Prm_Usu_ID = '$UsuID' AND Prm_Men_ID = '$MenID' AND Prm_Opc_ID = '$OpcID' AND Prm_Uni_ID = '$UniID'";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se actualiz� el permiso al usuario.";
		}
		//echo $sql;

}//fin funcion
function guadarPermisoRol(){
	//echo "Hola";exit;
	$RolID = $_POST['RolID'];
	$MenID = $_POST['MenID'];
	$OpcID = $_POST['OpcID'];
	$Boton = $_POST['Boton'];
	$UniID = $_POST['UniID'];

		$sql = "SELECT * FROM RolUnidad WHERE RUn_Rol_ID = '$RolID' AND RUn_Men_ID = '$MenID' AND RUn_Opc_ID = '$OpcID' AND RUn_Uni_ID = '$UniID'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){//no existe
			$sql = "INSERT INTO RolUnidad (RUn_Rol_ID, RUn_Men_ID, RUn_Opc_ID, RUn_Uni_ID, RUn_$Boton) VALUES ('$RolID', '$MenID', '$OpcID', '$UniID', '1')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se agreg� correctamente el permiso al rol $Boton.";
		}else{
			$sql = "UPDATE RolUnidad SET RUn_$Boton = '1' WHERE RUn_Rol_ID = '$RolID' AND RUn_Men_ID = '$MenID' AND RUn_Opc_ID = '$OpcID' AND RUn_Uni_ID = '$UniID'";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se actualiz� el permiso al rol $Boton.";
		}

}//fin funcion
function eliminarPermisoUsuario(){
	$UsuID = $_POST['UsuID'];

	$sql = "DELETE FROM Permiso WHERE Prm_Usu_ID = '$UsuID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
}//fin funcion

function eliminarPermisoRol(){
	$RolID = $_POST['RolID'];

	$sql = "DELETE FROM RolUnidad WHERE RUn_Rol_ID = '$RolID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
}//fin funcion

function llenarRolesUsuario(){
	//echo "Hola";exit;
	$Usuario = $_POST['Usuario'];

		$sql = "SELECT * FROM RolUsuario, Roles, Usuario WHERE RUs_Usu_ID = Usu_ID AND RUs_Rol_ID = Rol_ID AND RUs_Usu_ID = '$Usuario'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){//ya existe otro con ese nombre
			$row = mysqli_fetch_array($result);
			?>
	<div class="borde_aviso"><span class="texto">El usuario <strong><?php echo $row[Usu_Nombre];?></strong> tiene el rol <strong><?php echo $row[Rol_Nombre];?></strong>.</span></div>		
		<?php	
		}else{
			?>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Este usuario no tiene cargado un rol todav�a.</span>
		<?php
		}

}//fin funcion

function cargarPermisosSimples(){
?>
<script language="javascript">
$(document).ready(function(){
	$("#cargandoPermisoUsuario").hide();
	
	$("#arbolUsuario").checkboxTree({
		collapsedarrow: "imagenes/img-arrow-collapsed.gif",
		expandedarrow: "imagenes/img-arrow-expanded.gif",
		blankarrow: "imagenes/img-arrow-blank.gif"
		,checkchildren: true//*/
		,checkparents: true

  	});
	 $("#ListPermisoUsuID").change(function () {
   		$("#ListPermisoUsuID option:selected").each(function () {
			//alert($(this).val());
				vOpcion=$(this).val();
				$("#cargandoPermisoUsuario").show();
				llenarArbolPermisoUsuario(vOpcion);
				
        });
   });
   function llenarArbolPermisoUsuario(vUsuario){
		
		$.post("cargarOpciones.php", { opcion: 'llenarArbolPermisoUsuario', Usuario: vUsuario },	function(data){
     			//$("#ArbolPermiso").empty();
				$("#ArbolPermiso").html(data);
				$("#arbolUsuario").checkboxTree({
					collapsedarrow: "imagenes/img-arrow-collapsed.gif",
					expandedarrow: "imagenes/img-arrow-expanded.gif",
					blankarrow: "imagenes/img-arrow-blank.gif"
					,checkchildren: true//*/
					,checkparents: true
			
				});
				$("#cargandoPermisoUsuario").hide();
   		});
		
	}
});//fin de la funcion ready
</script>	
 <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="botones/users_permisos.png" width="48" height="48" align="absmiddle" /> Asignar Permisos por Usuario</div></td>
          </tr>
          <tr>
            <td class="texto"><div align="right">Seleccione un usuario  :</div></td>
            <td><?php echo cargarListaUsuarios("ListPermisoUsuID");?><img src="imagenes/loading2.gif" alt="Cargando permisos" title="Cargando permisos" width="24" height="24" id="cargandoPermisoUsuario"/></td>
          </tr>
          <tr>
            <td class="texto"> <div align="right">Arbol de Permisos </div></td>
            <td class="texto"><div id="ArbolPermiso"><?php cargarArbolPermisos();?></div></td>
          </tr>
        </table>
<?php
}//fin function
function cargarPermisosRoles(){
?>
<script language="javascript">
$(document).ready(function(){
	$("#cargandoPermisoRol").hide();
	
	$("#perm_suave").click(function(evento){
		//$( ":checkbox").attr('checked', 'checked');
		$("#cargandoPermisoRol").show();
		$.post("cargarOpciones.php", { opcion: 'llenarArbolPermisoRoles' },	function(data){
     			//$("#ArbolPermiso").empty();
				$("#ArbolPermisoRoles").html(data);
				$( ":checkbox").attr('checked', '');
				$("input[id^='Botones2_']").each(function(){
					valor = $(this).val();
					if ( valor  == "Visible" ){				
						//alert($(this).attr('checked'));
						$(this).attr('checked', 'checked');
					}
				});//*/
				$("#arbolRol").checkboxTree({
					collapsedarrow: "imagenes/img-arrow-collapsed.gif",
					expandedarrow: "imagenes/img-arrow-expanded.gif",
					blankarrow: "imagenes/img-arrow-blank.gif"
					,checkchildren: true//*/
					,checkparents: true
			
				});
				$("#cargandoPermisoRol").hide();
   		});
	});//fin click
	$("#perm_simple").click(function(evento){
		//$( ":checkbox").attr('checked', 'checked');
		$("#cargandoPermisoRol").show();
		$.post("cargarOpciones.php", { opcion: 'llenarArbolPermisoRoles' },	function(data){
     			//$("#ArbolPermiso").empty();
				$("#ArbolPermisoRoles").html(data);
				$( ":checkbox").attr('checked', '');
				$("input[id^='Botones2_']").each(function(){
					valor = $(this).val();
					if ( valor  == "Visible" || valor  == "Imprimir" || valor  == "Guardar" ){				
						//alert($(this).attr('checked'));
						$(this).attr('checked', 'checked');
					}
				});//*/

				$("#arbolRol").checkboxTree({
					collapsedarrow: "imagenes/img-arrow-collapsed.gif",
					expandedarrow: "imagenes/img-arrow-expanded.gif",
					blankarrow: "imagenes/img-arrow-blank.gif"
					,checkchildren: true//*/
					,checkparents: true
			
				});
				$("#cargandoPermisoRol").hide();
   		});
	});//fin click
	$("#perm_medio").click(function(evento){
		//$( ":checkbox").attr('checked', 'checked');
		$("#cargandoPermisoRol").show();
		$.post("cargarOpciones.php", { opcion: 'llenarArbolPermisoRoles' },	function(data){
     			//$("#ArbolPermiso").empty();
				$("#ArbolPermisoRoles").html(data);
				$( ":checkbox").attr('checked', '');
				$("input[id^='Botones2_']").each(function(){
					valor = $(this).val();
					if ( valor  == "Visible" || valor  == "Imprimir" || valor  == "Guardar" || valor  == "Modificar" ){
						//alert($(this).attr('checked'));
						$(this).attr('checked', 'checked');
					}
				});//*/

				$("#arbolRol").checkboxTree({
					collapsedarrow: "imagenes/img-arrow-collapsed.gif",
					expandedarrow: "imagenes/img-arrow-expanded.gif",
					blankarrow: "imagenes/img-arrow-blank.gif"
					,checkchildren: true//*/
					,checkparents: true
			
				});
				$("#cargandoPermisoRol").hide();
   		});
	});//fin click
	$("#perm_completo").click(function(evento){
		//$( ":checkbox").attr('checked', 'checked');
		$("#cargandoPermisoRol").show();
		$.post("cargarOpciones.php", { opcion: 'llenarArbolPermisoRoles' },	function(data){
     			//$("#ArbolPermiso").empty();
				$("#ArbolPermisoRoles").html(data);
				$( ":checkbox").attr('checked', '');
				$("input[id^='Botones2_']").each(function(){
					valor = $(this).val();
					if ( valor  == "Visible" || valor  == "Imprimir" || valor  == "Guardar" || valor  == "Modificar" || valor  == "Eliminar" ){
						//alert($(this).attr('checked'));
						$(this).attr('checked', 'checked');
					}
				});//*/

				$("#arbolRol").checkboxTree({
					collapsedarrow: "imagenes/img-arrow-collapsed.gif",
					expandedarrow: "imagenes/img-arrow-expanded.gif",
					blankarrow: "imagenes/img-arrow-blank.gif"
					,checkchildren: true//*/
					,checkparents: true
			
				});
				$("#cargandoPermisoRol").hide();
   		});
	});//fin click
	$("#arbolRol").checkboxTree({
		collapsedarrow: "imagenes/img-arrow-collapsed.gif",
		expandedarrow: "imagenes/img-arrow-expanded.gif",
		blankarrow: "imagenes/img-arrow-blank.gif"
		,checkchildren: true//*/
		,checkparents: true

  	});
	 $("#ListPermisoRolID").change(function () {
   		$("#ListPermisoRolID option:selected").each(function () {
			//alert($(this).val());
				vOpcion=$(this).val();
				$("#cargandoPermisoRol").show();
				llenarArbolPermisoRoles(vOpcion);
				
        });
   });
   function llenarArbolPermisoRoles(vRol){
		
		$.post("cargarOpciones.php", { opcion: 'llenarArbolPermisoRoles', Rol: vRol },	function(data){
     			//$("#ArbolPermiso").empty();
				$("#ArbolPermisoRoles").html(data);
				$("#arbolRol").checkboxTree({
					collapsedarrow: "imagenes/img-arrow-collapsed.gif",
					expandedarrow: "imagenes/img-arrow-expanded.gif",
					blankarrow: "imagenes/img-arrow-blank.gif"
					,checkchildren: true//*/
					,checkparents: true
			
				});
				$("#cargandoPermisoRol").hide();
   		});
		
	}
});//fin de la funcion ready
$(function() {
		$(".botones button:first").button({
            icons: {
                primary: 'ui-icon-locked'
            }
        }).next().button({
            icons: {
                primary: 'ui-icon-locked'
            }
        }).next().button({
            icons: {
                primary: 'ui-icon-locked'
            }
        }).next().button({
            icons: {
                primary: 'ui-icon-locked'
            }
        });
	});

</script>	
 <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="botones/users_permisos_roles.png" width="48" height="48" align="absmiddle" /> Asignar Permisos por Roles</div></td>
          </tr>

          <tr>
            <td colspan="2"><div class="botones" align="center">

	<button id="perm_suave">Permiso suave</button>
	<button id="perm_simple">Permiso simple</button>
	<button id="perm_medio">Permiso medio</button>
	<button id="perm_completo">Permiso completo</button>
	<br /><br />
            </div>
</td>
          </tr>

          <tr>
            <td class="texto"><div align="right">Seleccione un rol  :</div></td>
            <td><?php echo cargarListaRoles("ListPermisoRolID");?><img src="imagenes/loading2.gif" alt="Cargando permisos" title="Cargando permisos" width="24" height="24" id="cargandoPermisoRol"/></td>
          </tr>
          <tr>
            <td class="texto"> <div align="right">Arbol de Permisos </div></td>
            <td class="texto"><div id="ArbolPermisoRoles"><?php cargarArbolPermisosRoles();?></div></td>
          </tr>
        </table>
<?php
}//fin function

function buscarOtrosDatos(){
	//echo "Hola";exit;
	$PerID = $_POST['PerID'];

		$sql = "SELECT * FROM PersonaDatos, Persona, PersonaDocumento WHERE Per_Doc_ID = Doc_ID AND Dat_Per_ID = Per_ID AND Dat_Per_ID = '$PerID'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
			echo "{}";
		}else{
			$row = mysqli_fetch_array($result);
			$datos .= "{\"Dat_Niv_ID\": \"".$row[Dat_Niv_ID]."\",\"";
			$datos .= "Dat_Ent_ID\": \"".$row[Dat_Ent_ID]."\",\"";
			$datos .= "Dat_Dom_Pro_ID\": \"".$row[Dat_Dom_Pro_ID]."\",\"";
			$datos .= "Dat_Dom_Pai_ID\": \"".$row[Dat_Dom_Pai_ID]."\",\"";
			$datos .= "Dat_Dom_Loc_ID\": \"".$row[Dat_Dom_Loc_ID]."\",\"";
			$datos .= "Dat_Nac_Pro_ID\": \"".$row[Dat_Nac_Pro_ID]."\",\"";
			$datos .= "Dat_Nac_Pai_ID\": \"".$row[Dat_Nac_Pai_ID]."\",\"";
			$datos .= "Dat_Nac_Loc_ID\": \"".$row[Dat_Nac_Loc_ID]."\",\"";
			
			$datos .= "Doc_Nombre\": \"".$row[Doc_Nombre]."\",\"";
			$datos .= "Per_DNI\": \"".$row[Per_DNI]."\",\"";
			$datos .= "Per_Apellido\": \"".$row[Per_Apellido]."\",\"";
			$datos .= "Per_Nombre\": \"".$row[Per_Nombre]."\",\"";
			$datos .= "Per_Sexo\": \"".$row[Per_Sexo]."\",\"";
			$datos .= "Per_Alternativo\": \"".$row[Per_Alternativo]."\",\"";
			
			$datos .= "Dat_Nacimiento\": \"".cfecha($row[Dat_Nacimiento])."\",\"";
			$datos .= "Dat_Domicilio\": \"".$row[Dat_Domicilio]."\",\"";
			$datos .= "Dat_CP\": \"".$row[Dat_CP]."\",\"";
			$datos .= "Dat_Email\": \"".$row[Dat_Email]."\",\"";
			$datos .= "Dat_Telefono\": \"".$row[Dat_Telefono]."\",\"";
			$datos .= "Dat_Celular\": \"".$row[Dat_Celular]."\",\"";
			$datos .= "Dat_Observaciones\": \"".$row[Dat_Observaciones]."\",\"";
			$datos .= "Dat_Fecha\": \"".cfecha($row[Dat_Fecha])."\",\"";
			$datos .= "Dat_Hora\": \"".$row[Dat_Hora]."\"}";
			echo $datos;
		}

}//fin funcion

function buscarDatosLegajo(){
	//echo "Hola";exit;
	$PerID = $_POST['PerID'];
	$TipoLegajo = $_POST['TipoLegajo'];

		$sql = "SELECT * FROM Legajo WHERE Leg_Per_ID = '$PerID'";
		if ($TipoLegajo=="Colegio") $sql .= " AND Leg_Colegio = 1"; else $sql .= " AND Leg_StaMaria = 1";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
			echo "{}";
		}else{
			$row = mysqli_fetch_array($result);
			$datos .= "{\"Leg_ID\": \"".$row[Leg_ID]."\",\"";
			$datos .= "Leg_Sed_ID\": \"".$row[Leg_Sed_ID]."\",\"";
			$datos .= "Leg_Numero\": \"".$row[Leg_Numero]."\",\"";
			$datos .= "Leg_Alta_Fecha\": \"".cfecha($row[Leg_Alta_Fecha])."\",\"";
			$datos .= "Leg_Baja\": \"".$row[Leg_Baja]."\",\"";
			$datos .= "Leg_Baja_Fecha\": \"".cfecha($row[Leg_Baja_Fecha])."\",\"";
			$datos .= "Leg_Baja_Hora\": \"".$row[Leg_Baja_Hora]."\",\"";
			$datos .= "Leg_Baja_Motivo\": \"".$row[Leg_Baja_Motivo]."\",\"";
			$datos .= "Leg_Colegio\": \"".$row[Leg_Colegio]."\",\"";
			$datos .= "Leg_StaMaria\": \"".$row[Leg_StaMaria]."\",\"";
			$datos .= "Leg_Usu_ID\": \"".$row[Leg_Usu_ID]."\",\"";
			$datos .= "Leg_Fecha\": \"".cfecha($row[Leg_Fecha])."\",\"";
			$datos .= "total_legajos\": \"".mysqli_num_rows($result)."\",\"";
			$datos .= "Leg_Hora\": \"".$row[Leg_Hora]."\"}";
			echo $datos;
		}

}//fin funcion

function buscarDatosInscripcionLectivo(){
	//echo "Hola";exit;
	$LegID = $_POST['LegID'];
	$LecID = $_POST['LecID'];

		$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){//ya existe otro con ese nombre
			echo "{}";
		}else{
			$row = mysqli_fetch_array($result);
			$datos .= "{\"Ins_Leg_ID\": \"".$row[Ins_Leg_ID]."\",\"";
			$datos .= "Ins_Lec_ID\": \"".$row[Ins_Lec_ID]."\",\"";
			$datos .= "Ins_Cur_ID\": \"".$row[Ins_Cur_ID]."\",\"";
			$datos .= "Ins_Niv_ID\": \"".$row[Ins_Niv_ID]."\",\"";
			$datos .= "Ins_Div_ID\": \"".$row[Ins_Div_ID]."\",\"";
			$datos .= "Ins_Usu_ID\": \"".$row[Ins_Usu_ID]."\",\"";
			$datos .= "Ins_Fecha\": \"".cfecha($row[Ins_Fecha])."\",\"";
			//Busco si tiene datos del asegurado
			$sql = "SELECT * FROM Colegio_Asegurado WHERE Ase_Leg_ID = '$LegID' AND Ase_Lec_ID = $LecID";
			$result_ase = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			$datos .= "TieneAsegurado\": \"".mysqli_num_rows($result_ase)."\",\"";
			if (mysqli_num_rows($result)>0){
				$row_ase = mysqli_fetch_array($result_ase);
				$datos .= "Ase_Tutor_Per_ID\": \"".$row_ase[Ase_Tutor_Per_ID]."\",\"";
				$datos .= "Ase_Contrato\": \"".$row_ase[Ase_Contrato]."\",\"";
				$datos .= "Ase_Usu_ID\": \"".$row_ase[Ase_Usu_ID]."\",\"";
				$datos .= "Ase_Fecha\": \"".cfecha($row_ase[Ase_Fecha])."\",\"";
				$datos .= "Ase_Hora\": \"".$row_ase[Ase_Hora]."\",\"";
			}
			$datos .= "Ins_Hora\": \"".$row[Ins_Hora]."\"}";
			echo $datos;
		}

}//fin funcion

function guardarFamilia($PerID, $PerID_Vinc, $FTiID, $UsuID){
	//echo "Hola";exit;
	if (empty($PerID)){
		$DNI = $_POST['DNI'];
		$DNI_Vinc = $_POST['DNI_Vinc'];
		$FTiID = $_POST['FTiID'];
		$PerID = gbuscarPerID($DNI);
		$PerID_Vinc = gbuscarPerID($DNI_Vinc);
		$UsuID = $_POST['UsuID'];
	}
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");
	
	$FTi_Relaciona = gbuscarFTiRelaciona($FTiID);
	
	$sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		$sql = "INSERT INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerID', '$PerID_Vinc', '$FTiID', '$UsuID', '$Fecha', '$Hora')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if ($FTi_Relaciona>0){
			$sql = "INSERT IGNORE INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerID_Vinc', '$PerID', '$FTi_Relaciona', '$UsuID', '$Fecha', '$Hora')";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}

		echo "Se agreg� correctamente la nueva relaci�n familiar";
	}else{
		$sql = "UPDATE Familia SET Fam_FTi_ID = '$FTiID', Fam_Usu_ID = '$UsuID', Fam_Fecha = '$Fecha', Fam_Hora = '$Hora' WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se actualiz� la relaci�n familiar.";
	}

}//fin funcion

function armarFamilia(){
	//echo "Hola";exit;
	$DNI = $_POST['DNI'];
	$UsuID = $_POST['UsuID'];
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");
	$PerID = gbuscarPerID($DNI);
	//echo "$DNI-$PerID";
	
	$sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' ORDER BY Fam_FTi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//existe
		$ip = 1;
		$ih = 1;
		$ihm = 1;
		while ($row = mysqli_fetch_array($result)){
			$FTiID = $row[Fam_FTi_ID];
			$VinPerID = $row[Fam_Vin_Per_ID];
			if ($FTiID==1)//es padre/madre
			{
				$padre[$ip] = $VinPerID;
				$ip++;
			}
			if ($FTiID==5)//es hermano
			{
				$hermano[$ih] = $VinPerID;
				$ih++;
			}
			if ($FTiID==8)//es hermano mayor
			{
				$hermanomayor[$ihm] = $VinPerID;
				$ihm++;
			}
		}//fin while		
		if (count($padre)>0 && count($hermano)>0){
			//Relacionamos los padres con los hermanos
			foreach($padre as $p){
				foreach($hermano as $h){
					guardarFamilia($h, $p, 1, $UsuID);
					//echo "$h-$p";
				}//fin foreach hermano				
			}//fin foreach padre
		}//fin if cont
		if (count($padre)>0 && count($hermanomayor)>0){
			//Relacionamos los padres con los hermanos mayores
			foreach($padre as $p){
				foreach($hermanomayor as $h){
					guardarFamilia($h, $p, 1, $UsuID);
					//echo "$h-$p";
				}//fin foreach hermano				
			}//fin foreach padre
		}//fin if cont
		if (count($hermano)>0){
			//Relacionamos los hermanos entre si
			foreach($hermano as $h1){
				foreach($hermano as $h2){
					if ($h1!=$h2){
						guardarFamilia($h1, $h2, 5, $UsuID);
						//echo "$h1-$h2";
					}
						
				}//fin foreach hermano2
			}//fin foreach hermano1
		}//fin if cont
		if (count($hermanomayor)>0){
			//Relacionamos los hermanos entre si
			foreach($hermanomayor as $h1){
				foreach($hermano as $h2){
					if ($h1!=$h2){
						guardarFamilia($h2, $h1, 8, $UsuID);
						//echo "$h1-$h2";
					}
						
				}//fin foreach hermano2
			}//fin foreach hermano1
		}//fin if cont
		
	}

}//fin funcion

function eliminarFamilia(){
	$DNI = $_POST['DNI'];
	$PerID = gbuscarPerID($DNI);
	$sql = "DELETE FROM Familia WHERE Fam_Per_ID = '$PerID'";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Se elimin� la relaci�n familiar de la persona con DNI $PerID.";

}//fin function

function enviarMensajeUsuario(){
	$UsuID = $_POST['UsuID'];
	$Para = $_POST['Para'];
	$msj_opcion = $_POST['msj_opcion'];
	$Asunto = $_POST['Asunto'];
	$Cuerpo = $_POST['Cuerpo'];
	$arreglo = explode(",",$Para);
	$MTiID = $_POST['MTiID'];
	$Fecha = date("Y-m-d");
	$Hora = date("H:i:s");

	$sql = "INSERT INTO Colegio_Mensaje (Men_Titulo, Men_Cuerpo, Men_Fecha, Men_Hora, Men_MTi_ID) VALUES ('$Asunto', '$Cuerpo', '$Fecha', '$Hora', $MTiID)";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT Men_ID FROM Colegio_Mensaje ORDER BY Men_ID DESC LIMIT 0,1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$MenID = $row[Men_ID];
	//$arreglo = $Para;
	if (!empty($msj_opcion)){
		foreach ($Para as $valor){
			$destino = $valor;
			if (!empty($destino)){			  
			  $Para = gbuscarUsuID($destino);
			  $destinatarios .= $destino.", ";
			  $sql = "INSERT INTO Colegio_MensajeDestino (Des_De_Usu_ID, Des_Para_Usu_ID, Des_Men_ID, Des_Fecha, Des_Hora) VALUES ('$UsuID', '$Para', $MenID, '$Fecha', '$Hora')";
			  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			 // echo $sql;
  
		  }//fin if
			//echo $valor;
		}//fin foreach
		
	}else{
	  foreach ($arreglo as $valor){
		  $inicio = strpos($valor, "(");
		  $fin = strpos($valor, ")");
		  $largo = intval(strlen($valor));
		  $destino = substr(trim($valor), $inicio);
		  $destino = str_replace("(","",$destino);
		  $destino = str_replace(")","",$destino);
		  $destino = trim($destino);
		  if (!empty($destino)){
			  $Para = gbuscarUsuID($destino);
			  //$Para = $destino;
			  $destinatarios .= $destino.", ";
			  $sql = "INSERT INTO Colegio_MensajeDestino (Des_De_Usu_ID, Des_Para_Usu_ID, Des_Men_ID, Des_Fecha, Des_Hora) VALUES ('$UsuID', '$Para', $MenID, '$Fecha', '$Hora')";
			  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			  //echo $sql;
  
		  }
	  }//fin foreach//*/
	}//fin if
	//echo $Para;
	?>
	<div class="borde_aviso"><span class="texto">Se envi&oacute; correctamente el mensaje a los siguientes destinatarios:  <strong><?php echo $$destinatarios;?></strong>.</span></div>
		<?php

}//fin function

function mostrarMensajeUsuario(){
	$MenID = $_POST['MenID'];
	$UsuID = $_POST['UsuID'];
	$Leido = $_POST['Leido'];
	$sql = "SELECT * FROM
    Colegio_MensajeDestino
    INNER JOIN Colegio_Mensaje 
        ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID)
    INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID) WHERE Men_ID = $MenID AND Des_Para_Usu_ID = '$UsuID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);	
	if (!empty($Leido)){
		$sql = "UPDATE Colegio_MensajeDestino SET Des_Leido=1 WHERE Des_ID = $row[Des_ID]";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;
	}//fin if

	?>    
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="19"><img src="imagenes/recuadro_mensajes/bubble-1.png" width="19" height="15"></td>
    <td class="borde_mensaje_top"></td>
    <td width="19"><img src="imagenes/recuadro_mensajes/bubble-3.png" width="19" height="15"></td>
  </tr>
  <tr>
    <td class="borde_mensaje_left"></td>
    <td bgcolor="#FFFFFF">De: <strong><?php echo utf8_decode($row[Usu_Nombre]);?></strong><br />
		Asunto: <strong><?php echo utf8_decode($row[Men_Titulo]);?></strong><br />
        Enviado: <strong><?php echo cfecha($row[Des_Fecha])." a las ".$row[Des_Hora];?></strong><hr />
		<?php echo utf8_decode($row[Men_Cuerpo]);?></td>
    <td class="borde_mensaje_rigth"></td>
  </tr>
  <tr>
    <td><img src="imagenes/recuadro_mensajes/bubble-6.png" width="19" height="29"></td>
    <td class="borde_mensaje_botton"></td>
    <td><img src="imagenes/recuadro_mensajes/bubble-8.png" width="19" height="29"></td>
  </tr>
</table>
    <?php
}//fin function

function cargarMensajeParaIE(){
	?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td><select name="msj_para" size="5" multiple="multiple" class="bordeLista" id="msj_para">
    </select><a href="#" id="eliminarPara"><img src="imagenes/folder_delete.png" alt="Eliminar Usuario" width="32" height="32" border="0" /></a></td>

    <td><input name="msj_para_ie" type="text" id="msj_para_ie" size="35" />
      <input type="hidden" name="msj_usuario" id="msj_usuario" />
      <input type="hidden" name="msj_opcion" id="msj_opcion" value="1" />
      <br>
<button id="agregar" class="botones"><img src="imagenes/go-previous.png" width="22" height="22" />Agregar</button></td>
  </tr>
</table>
    <?php
}//fin function

function mostrarDetalleCuota(){
	$DNI = $_POST['DNI'];
	$datosCuota = $_POST['Cuota'];
	list( $fac, $tch, $chs, $alt, $pro, $cuo ) = explode( ';', $datosCuota );
	$sql = "SELECT * FROM Chequera_Cuota, Producto, Chequera_Alternativa WHERE ChC_Fac_ID = ChA_Fac_ID AND ChC_TCh_ID = ChA_TCh_ID AND ChC_ChS_ID = ChA_ChS_ID AND ChC_Pro_ID = ChA_Pro_ID AND ChC_Alt_ID = ChA_Alt_ID AND ChC_Pro_ID = Pro_ID AND ChC_Fac_ID = $fac AND ChC_TCh_ID = $tch AND ChC_ChS_ID = $chs AND ChC_Alt_ID = $alt AND ChC_Pro_ID = $pro AND ChC_Cuo_ID = $cuo;";
	$result = consulta_mysql($sql);
	if (mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
	?>
    <strong>Informaci&oacute;n adicional de la cuota</strong>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td>Tipo de Cuota:</td>
    <td colspan="2"><strong><?php echo $row[Pro_Nombre];?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Vigencia:</td>
    <td colspan="2"><strong><?php echo buscarMes($row[ChC_Mes])." - ".$row[ChC_Anio];?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Alternativa:</td>
    <td colspan="2"><strong><?php if ($row[ChA_Alt_ID]>0) echo $row[ChA_Titulo];?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">1� Vto: <strong><?php echo cfecha($row[ChC_1er_Vencimiento]);?></strong></td>
    <td align="center">&nbsp;</td>
    <td align="center">2� Vto: <strong><?php echo cfecha($row[ChC_2do_Vencimiento]);?></strong></td>
    <td align="center">&nbsp;</td>
    <td align="center">3� Vto: <strong><?php echo cfecha($row[ChC_3er_Vencimiento]);?></strong></td>
  </tr>
  <tr>
    <td align="center"><strong>$<?php echo intval($row[ChC_1er_Importe]);?></strong></td>
    <td align="center">&nbsp;</td>
    <td align="center"><strong>$<?php echo intval($row[ChC_2do_Importe]);?></strong></td>
    <td align="center">&nbsp;</td>
    <td align="center"><strong>$<?php echo intval($row[ChC_3er_Importe]);?></strong></td>
  </tr>
</table>

    <?php
	}//fin del if
}//fin function

function generarLegajoColegio(){
	$sql = "SELECT MAX(CONVERT(Leg_Numero, SIGNED)) AS Maximo FROM Legajo WHERE Leg_Colegio = 1;";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$row = mysqli_fetch_array($result);
	$Legajo = $row[Maximo] + 1;
	echo $Legajo;
	
}//fin function

function cargarCuentaUsuarioAlumno(){
	$PerID = $_POST['PerID'];	
	$sql = "SELECT * FROM Legajo WHERE Leg_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo "Hol$LegID la";
	$row = mysqli_fetch_array($result);
	$sql = "SELECT * FROM Usuario WHERE Usu_Leg_ID = '$row[Leg_ID]'";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		//echo $sql;
		$row = mysqli_fetch_array($result);
		if ($row[Usu_Clave]==md5($row[Usu_Nombre]))
			$clave = $row[Usu_Nombre];
		else
			$clave = "***********";
		//echo $clave;
		echo $row[Usu_Nombre].";".$clave;
	}else{
		echo "Sin Usuario;Sin Clave";
	}//fin if
}//fin function

function cargarFamiliaresSeguroAlumno(){
	$PerID = $_POST['PerID'];
	$sql = "SELECT * FROM     Familia
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID)
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
WHERE (Fam_Per_ID = $PerID AND FamiliaTipo.FTi_ID <> 2 AND FamiliaTipo.FTi_ID <> 5 AND FamiliaTipo.FTi_ID <> 8) ORDER BY FTi_ID, Per_Apellido, Per_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		//echo "<select name='$nombre' id='$nombre'>";
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Per_ID]'>($row[FTi_Nombre]) $row[Per_Apellido], $row[Per_Nombre]</option>";
	
		}//fin del while
		//echo "</select>";
	}else{
		echo "<option value='-1'>NO HAY FAMILIARES VINCULADOS</option>";
	}

}//fin function

function cargarBeneficiosAlumno(){
global $gLectivoSIUCC;
	$DNI = $_POST['DNI'];
	$LecID = $_POST['LecID'];
	$LecID = $gLectivoSIUCC[$LecID] - 1;
	$sql = "SELECT DISTINCTROW ArT_ID, ArT_Descripcion FROM
    siucc.Beneficios
    INNER JOIN siucc.Arancel_Tipo 
        ON (Beneficios.Ben_ArT_ID = Arancel_Tipo.ArT_ID)
WHERE Ben_Per_ID = $DNI AND Ben_Lec_ID = $LecID AND Ben_Pro_ID = 3";
	$result = consulta_mysql($sql);
	if (mysqli_num_rows($result)>0){
		//echo "<select name='$nombre' id='$nombre'>";
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[ArT_ID]'>$row[ArT_Descripcion]</option>";
	
		}//fin del while
		//echo "</select>";
	}else{
		echo "<option value='-1'>NO HAY BENEFICIOS ASIGNADOS</option>";
	}

}//fin function

function verificarBeneficiosAlumno(){
global $gLectivoSIUCC;
	$DNI = $_POST['DNI'];
	$LecID = $_POST['LecID'];
	$LecID = $gLectivoSIUCC[$LecID] - 1;
	$sql = "SELECT DISTINCTROW ArT_ID, ArT_Descripcion FROM
    siucc.Beneficios
    INNER JOIN siucc.Arancel_Tipo 
        ON (Beneficios.Ben_ArT_ID = Arancel_Tipo.ArT_ID)
WHERE Ben_Per_ID = $DNI AND Ben_Lec_ID = $LecID AND ArT_Beneficio_Familiar = 1";
	$result = consulta_mysql($sql);
	if (mysqli_num_rows($result)>0){
		$sql = "SELECT Persona.Per_DNI, Familia.Fam_FTi_ID FROM Familia
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) WHERE Per_DNI = $DNI;";
		$result = consulta_mysql($sql);
		if (mysqli_num_rows($result)==0){
			echo "No tiene hermanos vinculados. Vincule sus hermanos antes de guardar.";
		}else{
			//echo "Tiene hermanos vinculados";
			echo "";
		}
	}else{
		//echo "No tiene beneficio familiar";
		echo "";
	}

}//fin function

function validarContratoGuardado(){

	$Contrato = $_POST['Contrato'];
	$LegID = $_POST['LegID'];
	$LecID = $_POST['LecID'];
	$sql = "SELECT
    Colegio_Asegurado.Ase_Contrato
    , Colegio_Asegurado.Ase_Fecha
    , Colegio_Asegurado.Ase_Hora
    , Colegio_Asegurado.Ase_Usu_ID
    , Persona.Per_DNI
    , Persona.Per_Apellido
    , Persona.Per_Nombre
	, Usu_Persona
FROM
    Colegio_Asegurado
    INNER JOIN Colegio_Inscripcion 
        ON (Colegio_Asegurado.Ase_Lec_ID = Colegio_Inscripcion.Ins_Lec_ID) AND (Colegio_Asegurado.Ase_Leg_ID = Colegio_Inscripcion.Ins_Leg_ID)
	INNER JOIN Usuario 
        ON (Colegio_Asegurado.Ase_Usu_ID = Usuario.Usu_ID)
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
WHERE (Ase_Leg_ID <> $LegID AND Ase_Lec_ID = $LecID AND Ase_Contrato = '$Contrato');";
	
	$result = consulta_mysql($sql);
	if (mysqli_num_rows($result)>0){	
		$row = mysqli_fetch_array($result);
		//El contrato le pertenece a otra persona
		echo "El contrato ingresado le pertenece al alumno <strong>$row[Per_Apellido], $row[Per_Nombre]</strong> con <strong>DNI $row[Per_DNI]</strong> y fue cargado el d�a <strong>".cfecha($row[Ase_Fecha])."</strong> a las $row[Ase_Hora] por el usuario <strong>$row[Usu_Persona]</strong>. <br />Por favor ingrese otro n�mero de contrato.";
		
	}else{		
		echo "";//El contrato ingresado no existe
	}

}//fin function

function guardarInscripcionLectivo(){

	$LegID = $_POST['LegID'];
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$VinID = $_POST['VinID'];
	$ArTID = $_POST['ArTID'];
	$DNI = $_POST['DNI'];
	$Contrato = $_POST['Contrato'];
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	//Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
	$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//ya existe, actualizamos la inscripcion
		$sql = "UPDATE Colegio_Inscripcion SET Ins_Cur_ID = $CurID, Ins_Niv_ID = $NivID, Ins_Div_ID = $DivID, Ins_Usu_ID = $UsuID, Ins_Fecha = '$Fecha', Ins_Hora = '$Hora' WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
		$mensaje = "Se actualiz�  la inscripci�n del alumno.";
	}else{
		$sql = "INSERT INTO Colegio_Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora) VALUES ($LegID, $LecID, $CurID, $NivID, $DivID, $UsuID, '$Fecha', '$Hora')";
		$mensaje = "Se agreg� correctamente la inscripci�n del alumno.";
	}
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//Buscamos si el alumno ya tiene cargado los datos del asegurado para el ciclo lectivo
	$sql = "SELECT * FROM Colegio_Asegurado WHERE Ase_Leg_ID = '$LegID' AND Ase_Lec_ID = $LecID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//ya existe, actualizamos la inscripcion
		$sql = "UPDATE Colegio_Asegurado SET Ase_Tutor_Per_ID = $VinID, Ase_Contrato = $Contrato, Ase_Usu_ID = $UsuID, Ase_Fecha = '$Fecha', Ase_Hora = '$Hora' WHERE Ase_Leg_ID = '$LegID' AND Ase_Lec_ID = $LecID";
		$mensaje .= "<br />Se actualiz�  los datos del asegurado del alumno.";
	}else{
		$sql = "INSERT INTO Colegio_Asegurado (Ase_Leg_ID, Ase_Lec_ID, Ase_Tutor_Per_ID, Ase_Contrato, Ase_Usu_ID, Ase_Fecha, Ase_Hora) VALUES ($LegID, $LecID, $VinID, $Contrato, $UsuID, '$Fecha', '$Hora')";
		$mensaje .= "<br />Se agreg� correctamente los datos del asegurado del alumno.";
	}
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo $mensaje."<br />";
	echo asignarCuotasAlumno($DNI, $LecID, $CurID, $NivID, $ArTID);

}//fin funcion

function mostrarConstanciaInscripcion(){

	$LegID = $_POST['LegID'];
	$LecID = $_POST['LecID'];
	
	//Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
	$sql = "SELECT
    Legajo.Leg_Numero
    , Colegio_Inscripcion.Ins_Fecha
    , Colegio_Asegurado.Ase_Contrato
    , Persona.Per_DNI
    , PersonaDocumento.Doc_Nombre
    , Persona2.Per_DNI AS DNI
    , Persona2.Per_Apellido AS Apellido
    , Persona2.Per_Nombre AS Nombre
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Colegio_Inscripcion.Ins_Lec_ID
    , Colegio_Inscripcion.Ins_Cur_ID
    , Colegio_Inscripcion.Ins_Niv_ID
    , Colegio_Inscripcion.Ins_Div_ID
	, Niv_Nombre
	, Cur_Nombre
	, Div_Nombre
	, Lec_Nombre
FROM
    Colegio_Asegurado
    INNER JOIN Colegio_Inscripcion 
        ON (Colegio_Asegurado.Ase_Leg_ID = Colegio_Inscripcion.Ins_Leg_ID) AND (Colegio_Asegurado.Ase_Lec_ID = Colegio_Inscripcion.Ins_Lec_ID)
	INNER JOIN Lectivo 
        ON (Colegio_Inscripcion.Ins_Lec_ID = Lectivo.Lec_ID)
	INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Colegio_Asegurado.Ase_Tutor_Per_ID = Persona.Per_ID)
    INNER JOIN PersonaDocumento 
        ON (Persona.Per_Doc_ID = PersonaDocumento.Doc_ID)
    INNER JOIN Persona AS Persona2
        ON (Legajo.Leg_Per_ID = Persona2.Per_ID)
WHERE Colegio_Inscripcion.Ins_Lec_ID = $LecID AND Ins_Leg_ID = $LegID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//ya existe, mostramos la inscripcion
		$row = mysqli_fetch_array($result);
		?>
		<link href="css/general.css" rel="stylesheet" type="text/css">
<table width="550px" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
  <tr>
    <td colspan="2" align="center"><table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td width="50%"><div align="center"><img src="logos/logo_college.png" width="90" height="106" /></div></td>
        <td><div align="center"><img src="logos/logo_Giteco.png" width="161" height="113" /></div></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><hr size="1" color="#999999">
          <p><span class="noticia_titulo">Constancia de Inscripci�n Provisoria</span><br />
          </p>
          <p>&nbsp; </p></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2" align="left"><span class="titulo_noticia">Legajo N� <?php echo $row[Leg_Numero];?></span><br>      
    <span class="titulo_noticia">Contrato N� <?php echo $row[Ase_Contrato];?></span><br>
    <span class="texto">Fecha Inscripci�n: <?php echo cfecha($row[Ins_Fecha]);?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" color="#999999"  /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><span class="titulo_noticia">Datos del Alumno</span></td>
  </tr>
  <tr>
    <td width="72%" colspan="2"><blockquote>
      <p><span class="texto">Nombre y Apellido: <strong><?php echo utf8_decode($row[Nombre]." ".$row[Apellido]);?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><blockquote>
      <p><span class="texto">D.N.I.: <strong><?php echo $row[DNI];?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><blockquote>
      <p><span class="texto">Nivel: <strong><?php echo $row[Niv_Nombre];?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><blockquote>
      <p><span class="texto">Ciclo Lectivo: <strong><?php echo $row[Lec_Nombre];?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><blockquote>
      <p><span class="texto">Curso: <strong><?php echo $row[Cur_Nombre];?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><blockquote>
      <p><span class="texto">Divis�n: <strong><?php echo $row[Div_Nombre];?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" color="#999999"></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><span class="titulo_noticia">Datos del Asegurado</span></td>
  </tr>
  <tr>
    <td colspan="2"><blockquote>
      <p><span class="texto">Nombre y Apellido: <strong><?php echo utf8_decode($row[Per_Nombre]." ".$row[Per_Apellido]);?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2"><blockquote>
      <p><span class="texto"><?php echo $row[Doc_Nombre];?>: <strong><?php echo $row[Per_DNI];?></strong></span></p>
    </blockquote></td>
  </tr>
  <tr>
    <td colspan="2" style="font:'Times New Roman', Times, serif; font-size:10px">&nbsp;</td>
  </tr>
  <tr>
    <td style="font:'Times New Roman', Times, serif; font-size:10px">Nota: La inscripci&oacute;n ser&aacute; definitiva cuando se acredite el pago de la matr&iacute;cula. La divisi&oacute;n est&aacute; sujeta a modificaci&oacute;n en funci&oacute;n del cupo.</td>
    <td style="font:'Times New Roman', Times, serif; font-size:10px"><?php echo date("d/m/Y");?></td>
  </tr>
</table>

        <?php
	}else{
		echo "El alumno todavia no se encuentra inscripto en el Ciclo Lectivo seleccionado.";
	}

}//fin funcion

?>
