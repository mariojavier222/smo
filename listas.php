<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("funciones_generales.php");

function cargarListaTipoDoc($nombre){

	$sql = "SELECT * FROM PersonaDocumento";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Doc_ID]'>$row[Doc_Nombre]</option>";

	}//fin del while
	echo "</select>";
}

function ListarDeuda($Per_ID) {
               $sql = "SELECT * FROM Cuotapersona WHERE Cuo_Per_ID = $Per_ID";
               $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);    
                ?>
                <table width="195%" border="1" cellspacing="1" cellpadding="1" class="tabla">
                <thead>
                    <tr class="ui-widget-header">
                        <th align="center">Tipo de Cuota</th>
                        <th align="center">Mes</th>
                        <th align="center">Año</th>
                        <th align="center">Vnecimiento</th>
                        <th align="center">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                    
                    while ($row = mysqli_fetch_array($result)) {
                    $i++;
                    ?>
                    <tr><?php
                        $sql1 = "SELECT CTi_Nombre FROM cuotatipo where CTi_ID = $row[Cuo_CTi_ID]";
                        $res = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
                        $res2 = mysqli_fetch_array($res)
                        ?>
                        <td align="center"><?php echo $res2[CTi_Nombre]; ?></td>
                        <td align="center">
                            <?php
                            if ($row[Cuo_Mes] == '1')
                            echo "Enero";
                            else if ($row[Cuo_Mes] == '2')
                            echo "Febrero";
                            else if ($row[Cuo_Mes] == '3')
                            echo "Marzo";
                            else if ($row[Cuo_Mes] == '4')
                            echo " Abril";
                            else if ($row[Cuo_Mes] == '5')
                            echo "Mayo";
                            else if ($row[Cuo_Mes] == '6')
                            echo"Junio";
                            else if ($row[Cuo_Mes] == '7')
                            echo "Julio";
                            else if ($row[Cuo_Mes] == '8')
                            echo "Agosto";
                            else if ($row[Cuo_Mes] == '9')
                            echo "Septiembre";
                            else if ($row[Cuo_Mes] == '10')
                            echo "Octubre";
                            else if ($row[Cuo_Mes] == '11')
                            echo "Noviembre";
                            else if ($row[Cuo_Mes] == '12')
                            echo "Diciembre";
                            ?>
                        </td>
                        <td align="center"><?php echo $row[Cuo_Anio]; ?></td>
                        <td align="center"><?php echo $row[Cuo_1er_Vencimiento]; ?></td>
                        <td align="center"><?php echo $row[Cuo_Importe]; ?></td>
                    </tr>

                    <?php
                    }//fin while
                    ?>
                </tbody>
            </table>   
                <?php
            }

function cargarListaPais($nombre){
$sql = "SET NAMES UTF8";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Pais ORDER BY Pai_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	?><select name="<?php echo $nombre ?>" id="<?php echo $nombre ?>">
    <?php
	while ($row = mysqli_fetch_array($result)){
	?>
    <option value="<?php echo $row['Pai_ID'] ?>"><?php echo $row['Pai_Nombre'] ?></option>
    <?php
	}
	?>
   </select>
   <?php
}


 
 function cargarListaProvincia($nombre, $pais){
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Provincia WHERE Pro_Pai_ID = $pais";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
	?><select name="<?php echo $nombre ?>" id="<?php echo $nombre ?>">
	<option value="0">SIN PROVINCIA</option>
    <?php
	while ($row = mysqli_fetch_array($result)){
	?>
    <option value="<?php echo $row['Pro_ID'] ?>"><?php echo $row['Pro_Nombre'] ?></option>
    <?php
	}
	?>
   </select>
   <?php
	}
	else
	{
		echo "NO HAY PROVINCIAS";
	}
	
}

function cargarListaLocalidad($nombre, $pais, $prov){

	$sql = "SET NAMES UTF8";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Localidad WHERE Loc_Pai_ID = $pais AND Loc_Pro_ID = $prov";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
	?><select name="<?php echo $nombre ?>" id="<?php echo $nombre ?>">
	<option value="0">SIN LOCALIDAD</option>
    <?php
	while ($row = mysqli_fetch_array($result)){
	?>
    <option value="<?php echo $row['Loc_ID'] ?>"><?php echo $row['Loc_Nombre'] ?></option>
    <?php
	}
	?>
   </select>
   <?php
	}
	else
	{
		echo "NO HAY LOCALIDADES";
	}
	
}

 function cargarListaProvincia2($nombre, $pais){
$sql = "SET NAMES UTF8";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Provincia WHERE Pro_Pai_ID = $pais ORDER BY Pro_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		//echo "<select name='$nombre' id='$nombre'>";
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Pro_ID]'>$row[Pro_Nombre]</option>";
	
		}//fin del while
		//echo "</select>";
	}else{
		echo "<option value='-1'>NO HAY PROVINCIAS</option>";
	}
}

function cargarListaLocalidad2($nombre, $pais, $prov){
$sql = "SET NAMES UTF8";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Localidad WHERE Loc_Pai_ID = '$pais' AND Loc_Pro_ID = '$prov' ORDER BY Loc_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		//echo "<select name='$nombre' id='$nombre'>";
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Loc_ID]'>$row[Loc_Nombre]</option>";
	
		}//fin del while
		//echo "</select>";
	}else{
		echo "<option value='-1'>NO HAY LOCALIDADES</option>";
	}
}
function cargarListaEntidadEducativa($nombre){

	$sql = "SELECT * FROM EstudioEnte";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Ent_ID]'>$row[Ent_Nombre]</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

function cargarListaNivelEstudio($nombre){

	$sql = "SELECT * FROM EstudioNivel";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Niv_ID]'>$row[Niv_Tit_Mas]</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

function cargarListaNivelEstudioRelacionados($EntID){

	$sql = "SELECT Niv_Tit_Mas, Niv_ID FROM Estudio
    INNER JOIN EstudioNivel 
        ON (Est_Niv_ID = Niv_ID) WHERE Est_Ent_ID = $EntID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Niv_ID]'>$row[Niv_Tit_Mas]</option>";
	
		}//fin del while
	}else{
		echo "<option value='-1'>NO HAY NIVELES CARGADOS</option>";
	}
}//fin funcion

function cargarListaMenu($nombre){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Men_ID]'>$row[Men_Nombre]</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

//function cargaMes($dato){
//echo "<select name="mes">";
//        for ($i=1; $i<=12; $i++) {
//            if ($i == date('m'))
//                echo "<option value="'.$i.'" selected>'.$i.'</option>";
//            else
//                echo "<option value="'.$i.'">'.$i.'</option>";
//        }
//echo "</select>";
//}
function cargarListaMenu2(){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Men_ID]'>$row[Men_Nombre]</option>";

	}//fin del while
}//fin funcion

function cargarListaOpcion($nombre, $menu){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $menu ORDER BY Opc_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Opc_ID]'>$row[Opc_Nombre]</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

function cargarListaOpcion2($menu){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $menu ORDER BY Opc_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Opc_ID]'>$row[Opc_Nombre]</option>";

	}//fin del while
}//fin funcion

function cargarListaUsuarios($nombre, $soloSede = false){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Usuario WHERE Usu_Leg_ID IS NULL";
	
	//if ($soloSede) $sql .= " AND Usu_Sed_ID = ".$_SESSION['sesion_SedID'];
	$sql .= " ORDER BY Usu_Persona, Usu_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	echo "<option value='-1'>Seleccione un usuario...</option>";
	while ($row = mysqli_fetch_array($result)){
		//if (!is_numeric($row[Usu_Nombre]))
			echo "<option value='$row[Usu_ID]'>$row[Usu_Nombre] ($row[Usu_Persona])</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

function cargarListaUsuariosCaja($nombre, $soloSede = false){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Usuario WHERE Usu_Caja = 1 ORDER BY Usu_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	echo "<option value='-1'>Seleccione un usuario...</option>";
	while ($row = mysqli_fetch_array($result)){
		if (!is_numeric($row[Usu_Nombre]))
			echo "<option value='$row[Usu_ID]'>$row[Usu_Nombre] ($row[Usu_Persona])</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

function cargarListaRoles($nombre){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	echo "<option value='-1'>Seleccione una opción</option>";

	//if (isset($_SESSION['sesion_rol']) ){
		$sql = "SELECT * FROM Roles2 ORDER BY Rol_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Rol_ID]'>$row[Rol_Nombre]</option>";

		}//fin del while
	//}//fin if
	echo "</select>";
}//fin funcion

function cargarListaVinculosFamilia($nombre){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM FamiliaTipo WHERE FTi_Mostrar=1 ORDER BY FTi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[FTi_ID]'>$row[FTi_Nombre]</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

function cargarListaFamilia($DNI){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$PerID = gbuscarPerID($DNI);
	$sql = "SELECT
    FamiliaTipo.FTi_Nombre
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Persona.Per_DNI
    , Persona.Per_ID
    , FamiliaTipo.FTi_ID
FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID) WHERE Fam_Per_ID = $PerID ORDER BY FTi_ID, Per_Apellido, Per_Nombre;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Per_DNI],$row[FTi_ID]'>($row[FTi_Nombre]) $row[Per_Apellido], $row[Per_Nombre]</option>";

	}//fin del while
}//fin funcion


function cargarListaSede($nombre, $SedID=""){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (!empty($SedID)) $where = " WHERE Sed_ID = $SedID ";
	if ($_SESSION['sesion_rol']==1) $where = "";
	$sql = "SELECT * FROM Sede $where ORDER BY Sed_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Sed_ID]'>$row[Sed_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaCarrera($nombre){

	$sql = "SELECT * FROM Carrera ORDER BY Car_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Car_ID]'>$row[Car_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function


function cargarListaCursos($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	switch ($_SESSION['sesion_UniID']){
		case 1: 
			$where = "WHERE Cur_Colegio = 1";break;
		case 2: 
			$where = "WHERE Cur_Colegio = 1";break;		
		case 4: 
			$where = "WHERE Cur_Colegio = 1";break;
		case 3: 
			$where = "WHERE Cur_Grado = 1";break;
	}////fin switch
	$sql = "SELECT * FROM Curso $where ORDER BY Cur_Niv_ID, Cur_Curso";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los cursos...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaCursosColegio($nombre, $Cur_Colegio){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	 
	$where = "WHERE Cur_Colegio = $Cur_Colegio";
	
	$sql = "SELECT * FROM Curso INNER JOIN Colegio_Nivel ON (Cur_Niv_ID = Niv_ID) $where ORDER BY Cur_Niv_ID, Cur_Curso";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opci&oacute;n</option>";	
	$Nivel = "";
	while ($row = mysqli_fetch_array($result)){
		if ($Nivel!= $row['Niv_Nombre']){
			if ($Nivel!="") echo "</optgroup>";
			$Nivel = $row['Niv_Nombre'];			 
			echo "<optgroup label='$row[Niv_Nombre]'>";
		}
		echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
	}//fin del while

	echo "</optgroup></select>";
}//fin function

function cargarListaCursosCompleto($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT * FROM Curso ORDER BY Cur_Niv_ID, Cur_Curso";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los cursos...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaCursos2($nombre, $agregarTodos=false, $NivID){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Curso WHERE Cur_Niv_ID = '$NivID' ORDER BY Cur_Curso";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if ($agregarTodos) echo "<option value='999999'>Todos los cursos...</option>";
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
		}//fin del while
	}else{
		echo "<option value='-1'>NO HAY CURSOS DISPONIBLES</option>";
	}
}
function cargarListaCursosInstituto($Leg_Colegio){

	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	 
	$where = "WHERE Cur_Colegio = $Leg_Colegio";
	
	$sql = "SELECT * FROM Curso INNER JOIN Colegio_Nivel ON (Cur_Niv_ID = Niv_ID) $where ORDER BY Cur_Niv_ID, Cur_Curso";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<option value='-1'>Seleccione una opci&oacute;n</option>";	
	$Nivel = "";
	while ($row = mysqli_fetch_array($result)){
		if ($Nivel!= $row['Niv_Nombre']){
			if ($Nivel!="") echo "</optgroup>";
			$Nivel = $row['Niv_Nombre'];			 
			echo "<optgroup label='$row[Niv_Nombre]'>";
		}
		echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
	}//fin del while

	echo "</optgroup>";
	
	
}

function llenarCursoTurno($nombre, $NivID, $TurID, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if ($NivID!=999999) $where.=" AND Cur_Niv_ID = '$NivID' ";
	if ($TurID!=999999) $where.=" AND Cur_Turno = '$TurID' ";	
	$sql = "SELECT * FROM Curso WHERE 1 $where ORDER BY Cur_Curso";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if ($agregarTodos) echo "<option value='999999'>Todos los cursos...</option>";
	if (mysqli_num_rows($result)>0){
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
		}//fin del while
	}else{
		echo "<option value='-1'>NO HAY CURSOS DISPONIBLES</option>";
	}
}


function cargarListaDivision($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Division ORDER BY Div_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las divisiones...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Div_ID]'>$row[Div_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaNivel($nombre, $agregarTodos=false, $filtrarNivel=true){

	$sql = "SELECT * FROM Colegio_Nivel ";
	
	$sql .= " ORDER BY Niv_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";

	if (mysqli_num_rows($result)>1) echo "<option value='-1'>Seleccione una opción</option>";
	
	if ($agregarTodos) echo "<option value='999999'>Todos los niveles...</option>";	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Niv_ID]'>$row[Niv_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaNivelClase($nombre){

	$sql = "SELECT * FROM Colegio_Nivel WHERE Niv_ID = 1 OR Niv_ID = 2 ";
	
	$sql .= " ORDER BY Niv_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";

	if (mysqli_num_rows($result)>1) echo "<option value='-1'>Seleccione una opción</option>";	
	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Niv_ID]'>$row[Niv_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaPersonas($nombre, $soloSede = false){

    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM Persona ORDER BY Per_Apellido, Per_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	echo "<option value='-1'>Seleccione una persona...</option>";
	while ($row = mysqli_fetch_array($result)){
		if (!is_numeric($row[Usu_Nombre]))
			echo "<option value='$row[Per_ID]'>$row[Per_Apellido], $row[Per_Nombre]</option>";

	}//fin del while
	echo "</select>";
}//fin funcion


function cargarUnidad($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM unidad ";
	if ($filtrarNivel && $_SESSION['sesion_rol']>1){
	  if ($_SESSION['sesion_UniID']==3)
		  $sql .= " WHERE Uni_ID = 4";
	  else
		  $sql .= " WHERE Unis_ID < 4";
	}
	$sql .= " ORDER BY uni_id";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";

	if (mysqli_num_rows($result)>1) echo "<option value='-1'>Seleccione unidad</option>";
	
	if ($agregarTodos) echo "<option value='999999'>Todos los niveles...</option>";	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Uni_ID]'>$row[Uni_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function


function cargarListaLectivo($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Lectivo ORDER BY Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos los lectivos...</option>";	
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		if ($row[Lec_Actual]==1) $seleccionada = "selected='selected'";
		echo "<option value='$row[Lec_ID]' $seleccionada>$row[Lec_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaTipoCuota($nombre, $agregarTodos=false){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM CuotaTipo ORDER BY CTi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione tipo de cuota...</option>";
        if ($agregarTodos) echo "<option value='999999'>Todas las cuotas...</option>";	
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[CTi_ID]' $seleccionada>$row[CTi_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function


function cargarListaAlternativaCuota($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM CuotaAlternativa ORDER BY Alt_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='-1'>Seleccione alternativa de pago...</option>";
        if ($agregarTodos) echo "<option value='999999'>Todas las alternativas...</option>";	
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[Alt_ID]' $seleccionada>$row[Alt_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaLectivoSIUCC($nombre){

	$sql = "SELECT * FROM Lectivo ORDER BY Lec_ID";
	$result = consulta_mysql($sql);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		if ($row[Lec_Corriente]==1) $seleccionada = "selected='selected'";
		echo "<option value='$row[Lec_ID]' $seleccionada>$row[Lec_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaFacultadSIUCC($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Facultad WHERE Fac_ID <> 15 ORDER BY Fac_Nombre";
	$result = consulta_mysql($sql);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todas las UNIDADES...</option>";	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Fac_ID]'>$row[Fac_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaColegiosSIUCC($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Facultad WHERE Fac_ID <> 15 AND Fac_Colegio = 1 ORDER BY Fac_Nombre";
	$result = consulta_mysql($sql);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todas las UNIDADES...</option>";	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Fac_ID]'>$row[Fac_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaTipoChequeraSIUCC($nombre, $FacID, $LecID){

	$sql = "SELECT * FROM Tipo_Chequera ORDER BY TCh_Nombre";
	if (!empty($FacID)) $sql = "SELECT DISTINCTROW Tipo_Chequera.* FROM
    siucc.Lectivo_Curso
    INNER JOIN siucc.Tipo_Chequera 
        ON (Lectivo_Curso.LCu_TCh_ID = Tipo_Chequera.TCh_ID)
WHERE (Lectivo_Curso.LCu_Fac_ID = $FacID
    AND Lectivo_Curso.LCu_Lec_ID = $LecID);";
	$result = consulta_mysql($sql);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[TCh_ID]'>$row[TCh_Nombre] ($row[TCh_ID])</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaTipoChequeraColegioSIUCC($nombre, $FacID, $LecID){

	$sql = "SELECT DISTINCTROW Tipo_Chequera.* FROM
    siucc.Lectivo_Curso
    INNER JOIN siucc.Tipo_Chequera 
        ON (Lectivo_Curso.LCu_TCh_ID = Tipo_Chequera.TCh_ID)
	INNER JOIN siucc.Facultad 
        ON (Lectivo_Curso.LCu_Fac_ID = Fac_ID)
	WHERE Fac_Colegio = 1 ";
    if (!empty($FacID)) $sql .= " AND Lectivo_Curso.LCu_Fac_ID = $FacID  AND Lectivo_Curso.LCu_Lec_ID = $LecID";
	$result = consulta_mysql($sql);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='999999'>Todas las CHEQUERAS...</option>";	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[TCh_ID]'>$row[TCh_Nombre] ($row[TCh_ID])</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaTipoChequeraColegioSIUCC2($LecID, $FacID){


	$sql = "SELECT DISTINCTROW Tipo_Chequera.* FROM
    siucc.Lectivo_Curso
    INNER JOIN siucc.Tipo_Chequera 
        ON (Lectivo_Curso.LCu_TCh_ID = Tipo_Chequera.TCh_ID)
	INNER JOIN siucc.Facultad 
        ON (Lectivo_Curso.LCu_Fac_ID = Fac_ID)
	WHERE Fac_Colegio = 1 AND Lectivo_Curso.LCu_Lec_ID = $LecID";
	if ($FacID!=999999) $sql .= " AND Lectivo_Curso.LCu_Fac_ID = $FacID  ";
	$sql .= " ORDER BY TCh_Nombre;";
	echo "<option value='999999'>Todas las CHEQUERAS...</option>";	
	$result = consulta_mysql($sql);
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[TCh_ID]'>$row[TCh_Nombre] ($row[TCh_ID])</option>";
	}//fin del while

}//fin function

function cargarListaTipoChequeraSIUCC2($LecID, $FacID){

	$sql = "SELECT DISTINCTROW Tipo_Chequera.* FROM
    siucc.Lectivo_Curso
    INNER JOIN siucc.Tipo_Chequera 
        ON (Lectivo_Curso.LCu_TCh_ID = Tipo_Chequera.TCh_ID)
WHERE (Lectivo_Curso.LCu_Fac_ID = $FacID
    AND Lectivo_Curso.LCu_Lec_ID = $LecID) ORDER BY TCh_Nombre;";
	$result = consulta_mysql($sql);
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[TCh_ID]'>$row[TCh_Nombre] ($row[TCh_ID])</option>";
	}//fin del while

}//fin function


function cargarListaLectivoInscripcion($nombre, $UniID=1){

	$sql = "SELECT * FROM Configuracion, Lectivo WHERE Con_Lec_ID = Lec_ID AND Con_Uni_ID = 1 ORDER BY Con_Lec_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		if ($row[Lec_Actual]==1) $seleccionada = "selected='selected'";
		echo "<option value='$row[Lec_ID]' $seleccionada>$row[Lec_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaInscriptos($LecID, $CurID, $NivID, $DivID){

	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT
	Leg_Numero
	, Ins_Leg_ID
    , Persona.Per_DNI
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Persona.Per_Sexo
    , Colegio_Nivel.Niv_Nombre
    , Curso.Cur_Siglas
    , Division.Div_Siglas
	, Niv_Siglas
    , Colegio_Inscripcion.Ins_Provisoria
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
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID";
    if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
    if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
    if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Per_Apellido, Per_Nombre;";
		
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		echo "Seleccione los alumnos que desea realizar el cambio.<br /><select name='Inscriptos[]' id='Inscriptos' class='multiselect' multiple='multiple'";
		echo "<option value='0'>Alumno TESTER</option>";

		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Ins_Leg_ID]'>$row[Per_Apellido], $row[Per_Nombre]</option>";
		}//fin del while
		echo "</select>";
		echo "Total de Alumnos: ".mysqli_num_rows($result);
	}else{
		echo "No se encontraron alumnos inscriptos.";
	}
}//fin funcion

function cargarListaTurnos($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Turno ORDER BY Tur_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los turnos...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Tur_ID]'>$row[Tur_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaTipoMensaje($nombre){

	$sql = "SELECT * FROM Colegio_MensajeTipo ORDER BY MTi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[MTi_ID]'>$row[MTi_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function


function cargarListaTituloCarrera($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Titulo
    INNER JOIN Carrera 
        ON (Titulo.Tit_Car_ID = Carrera.Car_ID)
    INNER JOIN Plan 
        ON (Titulo.Tit_Pla_ID = Plan.Pla_ID) WHERE Car_Uni_ID = ".$_SESSION['sesion_UniID']." ORDER BY Tit_ID";
	$sql = "SELECT * FROM Titulo
    INNER JOIN Carrera 
        ON (Titulo.Tit_Car_ID = Carrera.Car_ID)
    INNER JOIN Plan 
        ON (Titulo.Tit_Pla_ID = Plan.Pla_ID) WHERE Car_Uni_ID = 3 ORDER BY Tit_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los títulos...</option>";
	echo "$sql<br />";
	while ($row = mysqli_fetch_array($result)){
		$principal = "";
		if ($row[Tit_Principal]==1) $principal = "(*)";
		echo "<option value='$row[Tit_ID]'>($row[Pla_Nombre]) $row[Car_Nombre] $principal</option>";
	}//fin del while

	echo "</select>";
}//fin function


function cargarListaUnidad($nombre, $clase=""){

	$sql = "SELECT * FROM Unidad ORDER BY Uni_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (!empty($clase)) {
		echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1' class='$clase'>";
	}else{
		echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
		}
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Uni_ID]'>$row[Uni_Siglas]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaConceptos($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Producto ORDER BY Pro_ID";
	$result = consulta_mysql($sql);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los conceptos...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Pro_ID]'>$row[Pro_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaConceptosCheckbox($nombre){

	$sql = "SELECT * FROM Producto ORDER BY Pro_Nombre";
	$result = consulta_mysql($sql);
	while ($row = mysqli_fetch_array($result)){
		echo "<input name='$nombre$row[Pro_ID]' type='checkbox' id='$nombre$row[Pro_ID]' value='$row[Pro_ID]' />
           <label for='$nombre$row[Pro_ID]'>$row[Pro_Nombre]</label><br />";
	}//fin del while
	
	echo "</select>";
}//fin function


function cargarListaConceptosMultiple($nombre){

	$sql = "SELECT * FROM Producto ORDER BY Pro_Nombre";
	$result = consulta_mysql($sql);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1' class='multiselect' multiple='multiple'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Pro_ID]'>$row[Pro_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaMateriasColegio($nombre, $agregarTodos=false, $agregarCurriculares=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Materia ORDER BY Mat_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarCurriculares) echo "<option value='-2'>MATERIAS CURRICULARES</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las materias...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Mat_ID]'>$row[Mat_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaCondiciones($nombre, $itemSeleccionado=""){

	$sql = "SELECT * FROM Condicion ORDER BY Con_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Con_ID]' ";
		if ($itemSeleccionado==$row[Con_ID]) echo "selected='selected'";
		echo " >$row[Con_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaPeriodo($nombre, $itemSeleccionado=""){

	$sql = "SELECT * FROM Periodo ORDER BY Prd_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Prd_ID]' ";
		if ($itemSeleccionado==$row[Prd_ID]) echo "selected='selected'";
		echo " >$row[Prd_Siglas]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaMateriasTitulo($nombre, $agregarTodos=false, $TitID){

	$sql = "SELECT * FROM
    TituloMateria
    INNER JOIN Materia 
        ON (TituloMateria.TMa_Mat_ID = Materia.Mat_ID) AND (TituloMateria.TMa_Car_ID = Materia.Mat_Car_ID) WHERE TMa_Tit_ID = $TitID ORDER BY Mat_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las materias...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Mat_ID]'>$row[Mat_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaMateriasTitulo2($TitID, $agregarTodos=false){

	if ($TitID==999999){
		echo "<option value='999999'>Todas las materias...</option>";
		exit;
	}
	$sql = "SELECT * FROM
    TituloMateria
    INNER JOIN Materia 
        ON (TituloMateria.TMa_Mat_ID = Materia.Mat_ID) AND (TituloMateria.TMa_Car_ID = Materia.Mat_Car_ID) WHERE TMa_Tit_ID = $TitID ORDER BY Mat_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las materias...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Mat_ID]' title='$row[Mat_Nombre]'>$row[Mat_ID] - ".substr($row[Mat_Nombre],0,45)."</option>";
	}//fin del while
}//fin function

function cargarListaTrimestre($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM
    Colegio_Trimestre
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Trimestre.Tri_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Lectivo 
        ON (Colegio_Trimestre.Tri_Lec_ID = Lectivo.Lec_ID) ORDER BY Lec_ID, Niv_ID, Tri_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas los periodos o trimestres...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Tri_ID]'>($row[Lec_Nombre] - $row[Niv_Nombre]) $row[Tri_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaNotasTipo($nombre){

	$sql = "SELECT * FROM Colegio_NotasTipo";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[NTi_ID]'>$row[NTi_Nombre]</option>";
	}//fin del while
	echo "</select>";
}

function cargarListaInstanciaTrimestre($nombre, $LecID, $NivID, $agregarTodos=false){

	$sql = "SELECT * FROM
    Colegio_Instancia
    INNER JOIN Colegio_Trimestre 
        ON (Colegio_Instancia.Cia_Tri_ID = Colegio_Trimestre.Tri_ID) AND (Colegio_Instancia.Cia_Niv_ID = Colegio_Trimestre.Tri_Niv_ID) AND (Colegio_Instancia.Cia_Lec_ID = Colegio_Trimestre.Tri_Lec_ID) WHERE Cia_Niv_ID = $NivID AND Cia_Lec_ID = $LecID ORDER BY Cia_Lec_ID, Cia_Niv_ID, Cia_Tri_ID, Cia_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas los periodos o trimestres...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Cia_ID]'>$row[Tri_Nombre] - $row[Cia_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaTipoInasistencia($nombre){
	//echo "Hola";exit;
	$sql = "SELECT * FROM Colegio_TipoInasistencia";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		$i++;
		echo "<input class='limpiar$nombre' type='radio' id='Ina$nombre_$i' name='Ina$nombre' value='$row[Ina_ID]' alt='$row[Ina_Nombre]' title='$row[Ina_Nombre]'><label for='name=Ina$nombre_$i' title='$row[Ina_Nombre]'>$row[Ina_Siglas]</label>";
	}//fin del while

}

function cargarListaHijosPadre($nombre, $PerID){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID)
WHERE (Familia.Fam_Per_ID = $PerID AND Fam_FTi_ID = 2)";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas los periodos o trimestres...</option>";
	while ($row = mysqli_fetch_array($result)){
		gObtenerApellidoNombrePersona($row[Fam_Vin_Per_ID], $Apellido, $Nombre, true);
		echo "<option value='$row[Fam_Vin_Per_ID]'>$Nombre</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaSicopedagogas($nombre){
	$sql = "SELECT * FROM Sicopedagoga";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Sic_ID]'>$row[Sic_Nombre] ($row[Sic_DNI])</option>";

	}//fin del while
	echo "</select>";

}

function cargaCantCuotas($nombre) {
	echo "<select name='$nombre' id='$nombre'>";
		foreach (range('1', '12') as $m):
			echo "<option value='$m'>$m</option>";
		endforeach;
	echo "</select>";
}

function cargarListaFormaPago($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM FormaPago ORDER BY For_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	?>
	    <select name='<?php echo $nombre; ?>' id='<?php echo $nombre; ?>'>
	<?php

	if ($agregarTodos){
		?>
	    <option value='999999'>Todas las formas...</option>
	    <?php
	}
	while ($row = mysqli_fetch_array($result)){
		?>
			<option value="<?php echo $row['For_ID'];?>"><?php echo $row['For_Nombre']; ?></option>
	        <?php
		}//fin del while
	?>
	    </select>
    <?php
}//fin function

function cargarListaTipoFactura($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM FacturaTipo ORDER BY FTi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los tipos de factura...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[FTi_ID]'>$row[FTi_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaCondVenta($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM CondicionVenta ORDER BY CVe_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las condiciones de venta...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[CVe_ID]'>$row[CVe_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaIVACliente($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM IvaCliente ORDER BY Iva_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las condiciones de venta...</option>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		if ($row[Iva_ID]==5) $seleccionada = "selected='selected'";
		echo "<option value='$row[Iva_ID]' $seleccionada>$row[Iva_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaConceptoFormaPago($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM ConceptoFormaPago ORDER BY Con_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las formas...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Con_ID]'>$row[Con_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function




//NAHUEL
function cargarListarMeses($nombre)
{
	

	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";	
		echo "<option value='1' $seleccionada selected='selected'>Enero</option>";
		echo "<option value='2'>Febrero</option>";
		echo "<option value='3'>Marzo</option>";
		echo "<option value='4' >Abril</option>";
		echo "<option value='5'>Mayo</option>";
		echo "<option value='6'>Junio</option>";
		echo "<option value='7'>Julio</option>";
		echo "<option value='8'>Agosto</option>";
		echo "<option value='9' >Septiembre</option>";
		echo "<option value='10'>Octubre</option>";
		echo "<option value='11' >Noviembre</option>";
		echo "<option value='12'>Diciembre</option>";
	
	echo "</select>";
                            
}
//NAHUEL
//NAHUEL 22-04-2013
function mostrarUsuariosCajasAbiertas($nombre,$Usu_ID)
{
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Caja
    INNER JOIN Usuario 
        ON (Caja_Usu_ID = Usu_ID)WHERE Caja_Apertura IS NOT NULL AND Caja_Cierre IS NULL AND Caja_Usu_ID!='$Usu_ID';";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){
		echo "ERROR: No existen Usuarios con Cajas Abiertas.";
	}else{
		echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
		echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Usu_ID]'>$row[Usu_Persona]</option>";
		}//fin del while
		
		echo "</select>";
	}//fin if
}
//FIN NAHUEL 22-04-2013

//NAHUEL 24-04-2013
function ListarBeneficios($nombre)
{
$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "
SELECT 	Ben_ID, 
	Ben_Nombre, 
	Ben_Siglas
	 
	FROM 
	CuotaBeneficio";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Ben_ID]'>$row[Ben_Nombre]</option>";
	}//fin del while
	
	echo "</select>";	
}
//FIN NAHUEL 24-04-2013


function cargarListaBloqueoTipo($nombre){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM BloqueoTipo ORDER BY BTi_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[BTi_ID]'>$row[BTi_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaControlarNiveles($CurID){

	$sql = "SELECT * FROM Curso
    INNER JOIN Colegio_Nivel 
        ON (Cur_Niv_ID = Niv_ID) WHERE Cur_ID = $CurID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		while ($row = mysqli_fetch_array($result)){
			echo "<option value='$row[Niv_ID]'>$row[Niv_Nombre]</option>";
	
		}//fin del while
	}else{
		echo "<option value='-1'>NO HAY NIVEL CARGADO PARA EL CURSO ELEGIDO</option>";
	}
}//fin funcion

function cargarListaRequisitos($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT * FROM Requisito INNER JOIN Colegio_Nivel ON (Req_Niv_ID = Niv_ID) ORDER BY Niv_ID, Req_Nombre";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los requisitos...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Req_ID]'>$row[Req_Nombre] ($row[Niv_Siglas])</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaDimension($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql = "SELECT * FROM Colegio_Dimension WHERE Dim_Lec_ID = ".gLectivoActual($LecNombre)." ORDER BY Dim_ID";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
	if ($agregarTodos) echo "<option value='999999'>Todas las dimensiones...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Dim_ID]'>$row[Dim_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaAmbito($nombre, $DimID=0, $Valor=''){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Dimension INNER JOIN Colegio_Ambito ON (Amb_Dim_ID = Dim_ID) WHERE Amb_Dim_ID = $DimID AND Amb_Lec_ID = 14 ORDER BY Amb_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	
	while ($row = mysqli_fetch_array($result)){
		if ($Valor==$row[Amb_ID]) $seleccionado = " selected ";
		echo "<option $seleccionado value='$row[Amb_ID]'>$row[Amb_Nombre]</option>";
	}//fin del while
	echo "</select>";
	}else{
		echo "No existe un ámbito";
	}//fin if
	
}//fin function

function cargarListaAlumnosClase($nombre, $ClaID=0, $LecID=0){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_InscripcionClase
    INNER JOIN Legajo 
        ON (IMa_Leg_ID = Leg_ID)
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID) WHERE IMa_Lec_ID = $LecID AND IMa_Cla_ID 0 $ClaID ORDER BY Per_Apellido, Per_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	
	while ($row = mysqli_fetch_array($result)){
		//if ($Valor==$row[Ser_ID]) $seleccionado = " selected ";
		echo "<option value='$row[IMa_Leg_ID]'>$row[Per_Apellido], $row[Per_Nombre]</option>";
	}//fin del while
	echo "</select>";
	}else{
		echo "No existe un ámbito";
	}//fin if
	
}//fin function

function cargarListaAlumnosClase2($nombre, $Cur_ID, $Div_ID, $Inf_Lec_ID){

	 $Inf_Lec_ID = 14;
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID) WHERE Ins_Lec_ID = $Inf_Lec_ID AND Ins_Cur_ID = $Cur_ID AND Ins_Div_ID = $Div_ID ORDER BY Per_Apellido, Per_Nombre";
		//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if (mysqli_num_rows($result)>0){
		echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	$i=1;
	while ($row = mysqli_fetch_array($result)){
		//if ($Valor==$row[Ser_ID]) $seleccionado = " selected ";
		echo "<option value='$row[Ins_Leg_ID]'>$i) $row[Per_Apellido], $row[Per_Nombre]</option>";
		$i++;
	}//fin del while
	echo "</select>";
	}else{
		echo "No existe un ámbito";
	}//fin if
	
}//fin function


function cargarCursoDivisionNivelInicial($Nombre){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (!isset($_SESSION['Doc_ID'])){
		echo "Usted no es docente";
	}else{
		$Doc_ID = $_SESSION['Doc_ID'];
		$sql = "SELECT * FROM Colegio_DocenteCurso   
    INNER JOIN Curso 
        ON (DCu_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (DCu_Div_ID = Div_ID)
   WHERE DCu_Lec_ID = ".gLectivoActual($LecNombre)." AND DCu_Doc_ID = $Doc_ID ORDER BY Cur_Turno, Cur_ID, Div_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$result_cur = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//$result_div = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$_SESSION['Curso'] = mysqli_num_rows($result);
	if (mysqli_num_rows($result)>0){
		//Cargamos los cursos y divisiones habilitados
		echo "<select name='$Nombre' id='$Nombre' style='position:relative;z-index:1'>";
		if (mysqli_num_rows($result)>1){
			echo "<option value='-1'>Seleccione una opción</option>";	
		}
		while ($row = mysqli_fetch_array($result_cur)){
			//if ($Valor==$row[Ser_ID]) $seleccionado = " selected ";
			echo "<option value='$row[Cur_ID],$row[Div_ID]'>$row[Cur_Nombre] $row[Div_Nombre]</option>";
		}//fin del while
		echo "</select>";		
		//$_SESSION['Cur_ID'] = $row[Cur_ID];
		//$_SESSION['Div_ID'] = $row[Div_ID];
	}else {
		echo "Usted no tiene curso y división asignado";
		}//fin if
	}
	
}//fin function

function cargarListaEgresoTipo($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Egreso_Tipo ORDER BY ETi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos...</option>";	
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[ETi_ID]' $seleccionada>$row[ETi_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaReciboTipo($nombre, $agregarTodos=false){

	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos...</option>";	
		$seleccionada = "";
		echo "<option value='Factura A'>Factura A</option>";
		echo "<option value='Factura B'>Factura B</option>";
		echo "<option value='Factura C' selected='selected'>Factura C</option>";
		echo "<option value='Ticket'>Ticket</option>";
		echo "<option value='Sin Factura'>Sin Factura</option>";
	echo "</select>";
}//fin function

function cargarListaAnios($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Lectivo ORDER BY Lec_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos los lectivos...</option>";	
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		if ($row[Lec_Actual]==1) $seleccionada = "selected='selected'";
		echo "<option value='$row[Lec_Nombre]' $seleccionada>$row[Lec_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaEgresoCuenta($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Egreso_Cuenta ORDER BY Cue_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos...</option>";	
	echo "<option value='-1'>Seleccione una opción</option>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[Cue_ID]' $seleccionada>$row[Cue_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaOrientacion($nombre, $agregarTodos=false, $filtrarNivel=true){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Orientacion INNER JOIN Colegio_Nivel ON (Ori_Niv_ID = Niv_ID) ORDER BY Niv_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";

	if (mysqli_num_rows($result)>1) echo "<option value='-1'>Seleccione una opción</option>";
	
	if ($agregarTodos) echo "<option value='999999'>Todas las orientaciones...</option>";	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Ori_ID]'>$row[Niv_Nombre]->$row[Ori_Nombre]</option>";
	}//fin del while
	
	echo "</select>";
}//fin function

function cargarListaMateriasOrientacion($nombre, $NivID, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Materia INNER JOIN Colegio_Orientacion 
        ON (Mat_Ori_ID = Ori_ID) WHERE Ori_Niv_ID = $NivID ORDER BY Ori_ID, Mat_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($NivID==1){
		echo "<option value='-2'>TODAS LAS MATERIAS CURRICULARES</option>";
		echo "<option value='-3'>TODAS LAS MATERIAS DE CONVIVENCIA</option>";
	}
	
	if ($agregarTodos) echo "<option value='999999'>Todas las materias...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Mat_ID]'>$row[Ori_Nombre]->$row[Mat_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaMateriasOrientacion2($nombre, $NivID, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Materia INNER JOIN Colegio_Orientacion 
        ON (Mat_Ori_ID = Ori_ID) WHERE Ori_Niv_ID = $NivID ORDER BY Ori_ID, Mat_Nombre, Mat_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<option value='-1'>Seleccione una opción</option>";
	if ($NivID==1){
		echo "<option value='-22'>TODAS LAS MATERIAS CURRICULARES Y DE CONVIVENCIA</option>";
		echo "<option value='-2'>TODAS LAS MATERIAS CURRICULARES</option>";
		echo "<option value='-3'>TODAS LAS MATERIAS DE CONVIVENCIA</option>";
		echo "<option value='-6'>TODAS LAS MATERIAS DE UNIDAD PEDAGOGICA (1º Grado)</option>";
		echo "<option value='-7'>TODAS LAS MATERIAS DE UNIDAD PEDAGOGICA (2º Grado)</option>";
	}	
	if ($agregarTodos) echo "<option value='999999'>Todas las materias...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Mat_ID]'>$row[Ori_Nombre]->$row[Mat_Nombre]</option>";
	}//fin del while
}//fin function

function cargarListaConfigCuota($LecID, $Nombre){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM CuotaModelo
    INNER JOIN Lectivo 
        ON (CMo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (CMo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (CMo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (CMo_Alt_ID = Alt_ID)
	INNER JOIN Usuario 
        ON (CMo_Usu_ID = Usu_ID) WHERE CMo_Lec_ID = $LecID ORDER BY CMo_Lec_ID, CMo_Niv_ID, CMo_CTi_ID, CMo_Alt_ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$Nombre' id='$Nombre'>";
	echo "<option value='-1'>Seleccione una Configuración...</option>";
	while ($row = mysqli_fetch_array($result)){
		$fechaVencimiento = cfecha($row['CMo_1er_Vencimiento']);
        $listado = "$row[CMo_Lec_ID];$row[CMo_Niv_ID];$row[CMo_CTi_ID];$row[CMo_Alt_ID];$row[CMo_Numero]";
		echo "<option value='$listado'>";
		$texto = "$row[Niv_Nombre]->$row[CTi_Nombre] $row[CMo_CantCuotas] ctas $row[CMo_Importe]. Vto: $fechaVencimiento";    
        echo "$texto</option>";

	}//fin del while
	echo "</select>";
}//fin funcion

function cargarListaConfigCuota2($LecID, $Nombre){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM CuotaModelo
    INNER JOIN Lectivo 
        ON (CMo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (CMo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (CMo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (CMo_Alt_ID = Alt_ID)
	INNER JOIN Usuario 
        ON (CMo_Usu_ID = Usu_ID) WHERE CMo_Lec_ID = $LecID ORDER BY CMo_Lec_ID, CMo_Niv_ID, CMo_CTi_ID, CMo_Alt_ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo "<select name='$Nombre' id='$Nombre'>";
	echo "<option value='-1'>Seleccione una Configuración...</option>";
	while ($row = mysqli_fetch_array($result)){
            $listado = "$row[CMo_Lec_ID];$row[CMo_Niv_ID];$row[CMo_CTi_ID];$row[CMo_Alt_ID];$row[CMo_Numero]";
			echo "<option value='$listado'>";
			$texto = "$row[Niv_Nombre]->$row[CTi_Nombre] $row[CMo_CantCuotas] cuotas de $row[CMo_Importe]";    
            echo "$texto</option>";

	}//fin del while
	//echo "</select>";
}//fin funcion

function cargarListaCuentaTipo($nombre, $orden='id',$agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if ($orden=="id") $orden = "CuT_ID";
	if ($orden=="orden") $orden = "CuT_Orden";
	if ($orden=="nombre") $orden = "CuT_Nombre";
	$sql = "SELECT * FROM CuentaTipo ORDER BY $orden";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='0'>Sin Categoría Padre</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los tipos de cuenta...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[CuT_ID]'>$row[CuT_Nombre]</option>";
	}//fin del while

	echo "</select>";
}//fin function

function cargarListaCuentaTipoInterior($orden='id',$agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	if ($orden=="id") $orden = "CuT_ID";
	if ($orden=="orden") $orden = "CuT_Orden";
	if ($orden=="nombre") $orden = "CuT_Nombre";
	$sql = "SELECT * FROM CuentaTipo ORDER BY $orden";//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	//echo "<option value='0'>Sin Categoría Padre</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los tipos de cuenta...</option>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[CuT_ID]'>$row[CuT_Nombre]</option>";
	}//fin del while

	//echo "</select>";
}//fin function


function cargarListaCuentaContable($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Cuenta INNER JOIN CuentaTipo ON (Cue_CuT_ID = CuT_ID) ORDER BY Cue_Codigo, Cue_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos...</option>";	
	echo "<option value='-1'>Seleccione una opción</option>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[Cue_ID]' $seleccionada>$row[Cue_Codigo] - $row[Cue_Nombre] ($row[CuT_Nombre])</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaDebitoTarjeta($nombre, $agregarTodos=false){

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM DebitoTarjeta ORDER BY DTa_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos...</option>";	
	echo "<option value='-1'>Seleccione una opción</option>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[DTa_ID]' $seleccionada>$row[DTa_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaDeudor($nombre, $agregarTodos=false){

	$sql = "SELECT * FROM Deudor ORDER BY Deu_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if ($agregarTodos) echo "<option value='999999'>Todos...</option>";	
	echo "<option value='-1'>Seleccione una opción</option>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[Deu_ID]' $seleccionada>$row[Deu_Nombre]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListaAdministradores($nombre, $agregarTodos=false){

	$sql = "SELECT UPPER(Usu_Persona) AS Usu_Persona, Usu_ID FROM Usuario WHERE Usu_Administrador = 1 ORDER BY Usu_Persona";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' >";//style='position:relative;z-index:1'
	if ($agregarTodos) echo "<option value='999999'>Todos...</option>";	
	echo "<option value='-1'>Seleccione una opción</option>";
	while ($row = mysqli_fetch_array($result)){
		$seleccionada = "";
		echo "<option value='$row[Usu_ID]' $seleccionada>$row[Usu_Persona]</option>";
	}//fin del while
	echo "</select>";
}//fin function

function cargarListadoDocentesActivo($i, $Doc_ID){

    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM  Colegio_Docente
    INNER JOIN Persona 
        ON (Doc_Per_ID = Per_ID) WHERE Doc_Activo=1 ORDER BY Per_Apellido, Per_Nombre";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    echo "<select name='Doc_ID$i' id='Doc_ID$i'>";
    echo "<option value='-1'>Seleccione un docente...</option>";    
    while ($row = mysqli_fetch_array($result)){
        if ($row['Doc_ID']==$Doc_ID) $elegida = "selected";else $elegida = "";
        echo "<option $elegida value='$row[Doc_ID]'>$row[Per_Apellido], $row[Per_Nombre]</option>";

    }//fin del while

    echo "</select>";
}//fin funcion


function cargarListaCursos3($nombre, $agregarTodos=false, $OriID){

    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $whereCursos = "";
    if ($OriID==4){
        $whereCursos = " AND Cur_Curso >=1 AND Cur_Curso <=3";  
    }
    if ($OriID==2 || $OriID==3){
        $whereCursos = " AND Cur_Curso >=4 AND Cur_Curso <=6";  
    }
    $sql = "SELECT * FROM Curso INNER JOIN Colegio_Orientacion 
        ON (Cur_Niv_ID = Ori_Niv_ID) WHERE Ori_ID = '$OriID' $whereCursos ORDER BY Cur_Curso";//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    //if ($agregarTodos) echo "<option value='999999'>Todos los cursos...</option>";
    if (mysqli_num_rows($result)>0){
        while ($row = mysqli_fetch_array($result)){
            echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
        }//fin del while
    }else{
        echo "<option value='-1'>NO HAY CURSOS DISPONIBLES</option>";
    }
}//fin function
function cargarListaDivisionOrientacion3($nombre, $OriID){

    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $whereCursos = "";
    if ($OriID==1 || $OriID==4){
        $whereCursos = " Div_ID >=1 AND Div_ID <=2";    
    }
    if ($OriID==2){
        $whereCursos = " Div_ID =3";    
    }
    if ($OriID==3){
        $whereCursos = " Div_ID =4";    
    }
    if ($OriID==5){
        $whereCursos = " Div_ID >2";    
    }
    $sql = "SELECT * FROM Division WHERE $whereCursos ORDER BY Div_ID";//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    if (mysqli_num_rows($result)>0){
        while ($row = mysqli_fetch_array($result)){
            echo "<option value='$row[Div_ID]'>$row[Div_Nombre]</option>";
        }//fin del while
    }else{
        echo "<option value='-1'>NO HAY DIVISIONES DISPONIBLES</option>";
    }
}//fin function
function cargarListaMateriasOrientacion3($nombre, $OriID, $agregarTodos=false){

    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM Colegio_Materia INNER JOIN Colegio_Orientacion 
        ON (Mat_Ori_ID = Ori_ID) WHERE Ori_ID = $OriID ORDER BY Ori_ID, Mat_Curricular, Mat_Convivencia, Mat_Pedagogica, Mat_Nombre";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    echo "<option value='-1'>Seleccione una opción</option>";
    if ($NivID==1){
        echo "<option value='-2'>TODAS LAS MATERIAS CURRICULARES</option>";
        echo "<option value='-3'>TODAS LAS MATERIAS DE CONVIVENCIA</option>";
        echo "<option value='-6'>TODAS LAS MATERIAS DE UNIDAD PEDAGOGICA (1º Grado)</option>";
        echo "<option value='-7'>TODAS LAS MATERIAS DE UNIDAD PEDAGOGICA (2º Grado)</option>";
    }   
    if ($agregarTodos) echo "<option value='999999'>Todas las materias...</option>";
    while ($row = mysqli_fetch_array($result)){
        //echo "<option value='$row[Mat_ID]'>$row[Ori_Nombre]->$row[Mat_Nombre]</option>";
        echo "<option value='$row[Mat_ID]'>($row[Mat_ID]) $row[Mat_Nombre]</option>";
    }//fin del while
}//fin function

//Mario. para mostrar el ciclo actual y en anterior
function cargarListaLectivoInscripcionNueva($nombre, $UniID=1){

	$sql = "SELECT * FROM Configuracion, Lectivo WHERE Con_Lec_ID = Lec_ID AND Con_Uni_ID = $UniID ORDER BY Con_Lec_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		$LecIDAnt= $row[Lec_ID]-1;
		$LecNomAnt= $row[Lec_Nombre]-1;
		$seleccionada = "";
		if ($row[Lec_Actual]==1) $seleccionada = "selected='selected'";
		echo "<option value='$row[Lec_ID]' $seleccionada>$row[Lec_Nombre]</option>";
	}//fin del while

	//Mario 17112021. para que puedan ver dos ciclos. El actual y el anterior 
	echo "<option value='$LecIDAnt'>$LecNomAnt</option>";
	echo "</select>";
}//fin function


function cargarListaNivelFiltrado($nombre, $agregarTodos=false, $filtrarNivel=true){
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Nivel where (Niv_ID >= 1 and Niv_ID <=3) OR Niv_ID>=6 ORDER BY Niv_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if (mysqli_num_rows($result)>1) echo "<option value='-1'>Seleccione una opción</option>";

	if ($agregarTodos) echo "<option value='999999'>Todos los niveles...</option>";	

	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Niv_ID]'>$row[Niv_Nombre]</option>";
	}//fin del while
	echo "<option value='8'>Bonos Primer Trimestre</option>";	
	echo "<option value='9'>Bonos Segundo Trimestre</option>";	
	echo "<option value='10'>Inscripción 2023</option>";
	echo "</select>";
}//fin function


function cargarListaNivelUsuarioAcotado($nombre, $agregarTodos=false, $filtrarNivel=true, $Usu_ID){
	if (empty($Usu_ID)) exit;
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM usuario_acotado
    INNER JOIN Colegio_Nivel 
        ON (usuario_acotado.Usu_Nivel = Colegio_Nivel.Niv_ID) where Niv_ID<>0 and Niv_ID<>4 and Niv_ID<>5 and usuario_acotado.Usu_ID=$Usu_ID ";
	$sql .= " ORDER BY Niv_Nombre";
	//echo $sql;exit;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
	if (mysqli_num_rows($result)>1) echo "<option value='-1'>Seleccione una opción</option>";
	if ($agregarTodos) echo "<option value='999999'>Todos los niveles...</option>";	
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Niv_ID]'>$row[Niv_Nombre]</option>";
	}//fin del while
	echo "</select>";

}//fin function
?>