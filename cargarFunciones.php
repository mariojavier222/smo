<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("funciones_beneficios.php");
include_once("comprobar_sesion.php");

date_default_timezone_set('America/Argentina/San_Juan');

function guardarPais() {
//echo "Hola";exit;
    $Nombre = $_POST['Nombre'];
    $Nombre = strtoupper(trim(utf8_decode($Nombre)));
    $Nombre = arreglarCadenaMayuscula($Nombre);
//$Pais = $_POST['Pais'];
    $ID = $_POST['ID'];

    if (!empty($Nombre)) {
        $sql = "SELECT * FROM Pais WHERE Pai_Nombre = '$Nombre'";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
            echo "Ya existe";
        } else {
            $sql = "UPDATE Pais SET Pai_Nombre = '$Nombre' WHERE Pai_ID = $ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo $Nombre;
        }
    }
}

//fin funcion

function buscarUltimoDNI($valor){
	
	$sql = "SELECT * FROM Persona WHERE Per_DNI LIKE '99990%' ORDER BY Per_DNI DESC LIMIT 0, 1";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result);
		
		if (mysqli_num_rows($result) > 0) {
			
			$row[Per_DNI] = intval($row[Per_DNI])+1;
			echo"$row[Per_DNI]";
			
			} else {
				echo"No esta cargado el DNI por defecto.";
        //$sql = "INSERT INTO Persona (Per_ID, Per_Doc_ID, Per_DNI, Per_Apellido, Per_Nombre, Per_Sexo, Per_Foto, Per_Fecha, Per_Hora, Per_Alternativo, Per_Extranjero) VALUES (220, 220, 9999001, 'APELLIDOPRUEBA', 'Nombreprueba', 'M', '', '2013-02-19', '', '',0)";
//        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				
				}
		
	

	}

function eliminarPais() {
//echo "Hola";exit;
    $ID = $_POST['ID'];

    $sql = "SELECT * FROM Pais WHERE Pai_ID = $ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El país elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Provincia WHERE Pro_Pai_ID = $ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " provincias relacionadas.";
        } else {
            $sql = "DELETE FROM Pais WHERE Pai_ID = $ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el país seleccionado.";
        }
    }
}

//fin funcion

function guardarProvincia() {

    $Nombre = $_POST['Nombre'];
//echo "$Nombre"; exit;
    $Nombre = strtoupper(trim(utf8_decode($Nombre)));
    $Nombre = arreglarCadenaMayuscula($Nombre);
    $Pais = $_POST['Pais'];
    $ID = $_POST['ID'];
//echo "$Pais-$Nombre-$ID"; exit;
    if (!empty($Nombre)) {
        $sql = "SELECT * FROM Provincia WHERE Pro_Nombre = '$Nombre' AND Pro_Pai_ID = $Pais";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
            echo "Ya existe";
        } else {
            $sql = "UPDATE Provincia SET Pro_Nombre = '$Nombre' WHERE Pro_ID = $ID AND Pro_Pai_ID = $Pais";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo $Nombre;
        }
    }
}

//fin funcion

function eliminarProvincia() {
//echo "Hola";exit;
    $ID = $_POST['ID'];
    $Pais = $_POST['Pais'];

    $sql = "SELECT * FROM Provincia WHERE Pro_ID = $ID AND Pro_Pai_ID = $Pais";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "La provincia elegida no existe o ya fue eliminada.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Localidad WHERE Loc_Pro_ID = $ID AND Loc_Pai_ID = $Pais";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene localidades vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " localidades relacionadas.";
        } else {
            $sql = "DELETE FROM Provincia WHERE Pro_ID = $ID AND Pro_Pai_ID = $Pais";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se elimin� la provincia seleccionada.";
        }
    }
}

//fin funcion

function guardarLocalidad() {

    $Nombre = $_POST['Nombre'];
    $Nombre = strtoupper(trim(utf8_decode($Nombre)));
    $Nombre = arreglarCadenaMayuscula($Nombre);
    $Pais = $_POST['Pais'];
    $Prov = $_POST['Prov'];
    $ID = $_POST['ID'];
//echo "$Pais-$Nombre-$ID"; exit;
    if (!empty($Nombre)) {
        $sql = "SELECT * FROM Localidad WHERE Loc_Nombre = '$Nombre' AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
            echo "Ya existe";
        } else {
            $sql = "UPDATE Localidad SET Loc_Nombre = '$Nombre' WHERE Loc_ID = $ID AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo $Nombre;
        }
    }
}

//fin funcion

function eliminarLocalidad() {
//echo "Hola";exit;
    $ID = $_POST['ID'];
    $Pais = $_POST['Pais'];
    $Prov = $_POST['Prov'];

    $sql = "SELECT * FROM Localidad WHERE Loc_ID = $ID AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "La localidad elegida no existe o ya fue eliminada.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM PersonaDatos WHERE Dat_Nac_Loc_ID = $ID AND Dat_Nac_Pai_ID = $Pais AND Dat_Nac_Pro_ID = $Prov";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene personas vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " personas relacionadas.";
        } else {
            $sql = "DELETE FROM Localidad WHERE Loc_ID = $ID AND Loc_Pai_ID = $Pais AND Loc_Pro_ID = $Prov";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se elimin� la localidad seleccionada.";
        }
    }
}

//fin funcion

function mostrarNivelEstudios() {

    $ID = $_POST['ID'];

    if (!empty($ID)) {
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
        while ($row = mysqli_fetch_array($result)) {
            echo "    <tr><td>$row[Niv_Tit_Mas]</td>
    <td>$row[Niv_Tit_Fem]</td></tr>";
        }//fin while
        echo "  </table>";
    }
}

//fin funcion

function guardarEntidadEducativa() {

    $Nombre = $_POST['Nombre'];
    $Nombre = strtoupper(trim(utf8_decode($Nombre)));
    $Nombre = arreglarCadenaMayuscula($Nombre);

    $ID = $_POST['ID'];

    if (!empty($Nombre)) {
        $sql = "SELECT * FROM EstudioEnte WHERE Ent_Nombre = '$Nombre'";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
            echo "Ya existe";
        } else {
            $sql = "UPDATE EstudioEnte SET Ent_Nombre = '$Nombre' WHERE Ent_ID = $ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo $Nombre;
        }
    }
}

//fin funcion

function eliminarEntidadEducativa() {
//echo "Hola";exit;
    $ID = $_POST['ID'];

    $sql = "SELECT * FROM EstudioEnte WHERE Ent_ID = $ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "La entidad educativa elegida no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Estudio INNER JOIN EstudioNivel ON (Est_Niv_ID = Niv_ID) WHERE (Est_Ent_ID = $ID) ORDER BY Niv_Tit_Mas";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " niveles de estudios relacionadas.";
        } else {
            $sql = "DELETE FROM EstudioEnte WHERE Ent_ID = $ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se elimin� la entidad educativa seleccionada.";
        }
    }
}

//fin funcion

function mostrarEntidadEducativa() {

    $ID = $_POST['ID'];

    if (!empty($ID)) {
        $sql = "SELECT Ent_Nombre FROM Estudio
     INNER JOIN EstudioEnte 
        ON (Est_Ent_ID = Ent_ID)
WHERE (Est_Niv_ID = $ID) ORDER BY Ent_Nombre";
//echo $sql;exit;
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "<hr />";
        while ($row = mysqli_fetch_array($result)) {
            echo "$row[Ent_Nombre]<br />";
        }//fin while
    }
}

//fin funcion

function guardarNivelEstudio() {

    $Nombre = $_POST['Nombre'];
    $Nombre = strtoupper(trim(utf8_decode($Nombre)));
    $Nombre = arreglarCadenaMayuscula($Nombre);
    $Campo = $_POST['Campo'];
    $ID = $_POST['ID'];

    if (!empty($Nombre)) {
        $sql = "SELECT * FROM EstudioNivel WHERE $Campo = '$Nombre'";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
            echo "Ya existe";
        } else {
            $sql = "UPDATE EstudioNivel SET $Campo = '$Nombre' WHERE Niv_ID = $ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo $Nombre;
        }
    }
}

//fin funcion

function eliminarNivelEstudio() {
//echo "Hola";exit;
    $ID = $_POST['ID'];

    $sql = "SELECT * FROM EstudioNivel WHERE Niv_ID = $ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El nivel de estudios elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Estudio INNER JOIN EstudioEnte ON (Est_Ent_ID = Ent_ID) WHERE (Est_Niv_ID = $ID)";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene vinculadciones
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " entidades educativas relacionadas.";
        } else {
            $sql = "DELETE FROM EstudioNivel WHERE Niv_ID = $ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se elimin� el nivel de estudios seleccionada.";
        }
    }
}

//fin funcion

function guardarEstudio() {

    $EntID = $_POST['EntID'];
    $NivID = $_POST['NivID'];
    $PaiID = $_POST['PaiID'];
    $ProID = $_POST['ProID'];
    $LocID = $_POST['LocID'];

    $sql = "SELECT * FROM Estudio WHERE Est_Ent_ID = $EntID AND Est_Niv_ID = $NivID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
        echo "Ya existe";
    } else {
        $sql = "INSERT INTO Estudio (Est_Ent_ID, Est_Niv_ID, Est_Pai_ID, Est_Pro_ID, Est_Loc_ID) VALUES ($EntID, $NivID, $PaiID, $ProID, $LocID)";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "<div class='borde_aviso'><span class='texto'>Los datos fueron relacionados correctamente.</span></div>	";
    }
}

//fin funcion

function buscarEntPaiProLoc() {
    $EntID = $_POST['EntID'];
    $NivID = $_POST['NivID'];
    $Buscar = $_POST['Buscar'];
    $sql = "SELECT * FROM Estudio WHERE Est_Ent_ID = $EntID AND Est_Niv_ID = $NivID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if ($Buscar == "Pai")
            echo $row[Est_Pai_ID];
        if ($Buscar == "Pro")
            echo $row[Est_Pro_ID];
        if ($Buscar == "Loc")
            echo $row[Est_Loc_ID];
    }else {
        echo 0;
    }
}

//fin funcion

function buscarDNI() {
    $DNI = trim($_POST['DNI']);
    $_SESSION['sesion_ultimoDNI'] = $DNI;
	
	$bContinuar = false;
	if (empty($DNI)) exit;
	  $sql = "SET NAMES UTF8";
	  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  $sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
	  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  if (mysqli_num_rows($result) == 0) {
	  		$sql = "SELECT Persona.* FROM Legajo INNER JOIN Persona ON (Leg_Per_ID = Per_ID) WHERE Leg_Numero='$DNI';";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				$bContinuar = true;
			}//fin if
	  }else{
	  	if (mysqli_num_rows($result) > 0) {
		  $row = mysqli_fetch_array($result);
		  $bContinuar = true;
	  	}//fin if
	  }//fin else
	  if ($bContinuar){
		  $foto = buscarFoto($DNI, $row[Per_Doc_ID], 60); //echo "TODO MAL $sql";exit;
		  $edad = obtenerEdad($row[Per_ID]);
		  $CursoDiv = buscarCursoDivisionPersona($row[Per_ID], true);
		  $Legajo = buscarLegajoPersona($row[Per_ID]);
		  if (empty($Legajo)) $Legajo = "FALTA CARGAR";
		  //$otrosDatos = buscarOtrosDatosPersona($row[Per_ID]);
		  $otrosDatos = buscarTelefonosPersona($row[Per_ID]);
		  
		  obtenerTutores($row['Per_ID'], $arrarTutores, $cant);
		  obtenerHermanos($row['Per_ID'], $arrarHermanos, $cantH);
          obtenerHijos_2($row['Per_ID'], $arrayHijos, $cantHijos);
		  ?>
		  <link href="css/general.css" rel="stylesheet" type="text/css" />
          <script language="javascript">
$(document).ready(function(){
    $("a[id^='cargarHermano']").click(function(evento){
        evento.preventDefault();        
        var i = this.id.substr(13,10);
        //alert(i);
        $("#DNI").val(i);
        vDNI = $("#DNI").val();
        //alert("Enter");       
        if (vDNI.length > '5'){
            
            $("#mostrar").empty();
            $("#cargando").show();
            cargarDNI();
            $("#cargando").hide();
        }
        //cargarDNI();
    });//fin evento click//*/
//borrar

function cargarDNI(){
        vDNI = $("#DNI").val();
        //alert("");
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "obtenerApellidoNombre", DNI: vDNI, conDNI: "true"},
            url: 'cargarOpciones.php',
            success: function(data){ 
                $("#persona").val(data);
                //alert("no entre");
            }
        });//fin ajax//*/
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "buscarDNI", DNI: vDNI},
            url: 'cargarOpciones.php',
            success: function(data){ 
                $("#PersonaDatos").show();
                $("#PersonaDatos").html(data);
                //alert("no entre");
            }
        });//fin ajax//*/
        
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "buscarPerID", DNI: vDNI},
            url: 'cargarOpciones.php',
            success: function(data){ 
                $("#PerID").val(data);
                buscarBloqueo(data);
                validarPadre(data)
                //alert("no entre");
            }
        });//fin ajax//*/

    }

//borrar

  $("a[id^='verFotoAlumno']").click(function(evento){
        evento.preventDefault();
    	fDNI = this.id.substr(13,15);
    	//alert(fDNI);
    	data = "<img src='fotos/grande/"+fDNI+".jpg' title='Foto' width='500' border='1' align='absmiddle' class='foto'/>";	  
    	$("#dialog").html(data);
    	$("#dialog").dialog({ draggable: true, hide: 'slide', title: 'Foto del Alumno', zIndex: 3900, resizable: true, modal: true, height: 'auto', width: 520, 
    		buttons: {
    			'Aceptar': function() {
    				$(this).dialog('close');
    			}
    		}//fin buttons
    	});//fin dialog          
        //alert(vDNI);
        
    });
});//fin de la funcion ready


</script>
  
  <table width='100%' border='0'>
			  <tr>
			    <td width="60" rowspan="2" align="center" valign="middle" class="fila_titulo3"><?php echo $foto; ?></td>
			    <th align="center" class="fila_titulo3">Datos de la Persona</th>
			    <th class="fila_titulo3">Datos Familiares</th>
  </tr>
			  <tr class="fila2">
			    <td valign="top" class="texto">
                    Legajo: <strong><?php echo $Legajo;?></strong><br />
                   D.N.I.: <strong><?php echo $row['Per_DNI'];?></strong><br />
  Apellido y Nombre: <strong><?php echo "$row[Per_Apellido], $row[Per_Nombre]"; ?></strong><br />
					    <?php
                        $Lec_ID = gLectivoActual($nombre_lectivo);
                        $arrayBeneficios = obtenerBeneficioAlumnoLectivoNuevo($Lec_ID, $row['Per_ID']);
                        $total_beneficios = count($arrayBeneficios);
                        $mostrar_beneficios = '';
                        for ($iBen=0; $iBen < $total_beneficios; $iBen++) { 
                            $mostrar_beneficios .= $arrayBeneficios[$iBen]['Ben_Nombre'].' ('.cfecha($arrayBeneficios[$iBen]['CBe_Desde']).'-'.cfecha($arrayBeneficios[$iBen]['CBe_Hasta']).')<br>';
                        }
                        if (!empty($mostrar_beneficios)) $mostrar_beneficios = "Ciclo ".$nombre_lectivo.': '.$mostrar_beneficios;

                        $deuda_alumno=Obtener_Deuda_Sistema($row['Per_ID'], $Recargos);
                        if ($deuda_alumno>0){
                            if ($_SESSION['sesion_UsuCaja']==1){
                                $mostrar_deuda = $deuda_alumno + $Recargos;
                                echo "<span class='vencida_roja'><strong>Deuda vencida: $".$mostrar_deuda."</strong></span><br>";                                
                            }else{
                                echo "<span class='vencida_roja'><strong> Tiene Deuda vencida</strong></span><br>";
                            }
                        }else{
                            echo "<span class='cuota_pagada'><strong>No tiene Deuda Vencida</strong></span><br>";
                        }
                        if ($_SESSION['sesion_UsuCaja']==1 && !empty($mostrar_beneficios)){
                            echo "<span class='cuota_beneficio'><strong>".$mostrar_beneficios."</strong></span><br>";
                            //echo $mostrar_beneficios;
                        }
                        if ($edad > 0) {
                          echo "Edad: <strong>$edad años</strong>";
                        }else{
                          echo "Edad: <strong>FALTA CARGAR FECHA NACIMIENTO</strong>";
                        }						
                        ?><br /><?php echo $CursoDiv."<br />";
                        echo $otrosDatos;
                        if ($_SESSION['sesion_UsuCaja']==1){
                        ?>
                        <a href='imprimirFichaDatosPersonales.php?PerID=<?php echo $row[Per_ID];?>' target='_blank'>Imprimir Ficha Personal</a><br>
                        <?php  
                        }
                        echo buscarDatosPersonaBajaFicha($row[Per_ID]);
                        ?>
						  
					    </td>
				  <td valign="top" class="texto">
                  <div id="noTienePadreAsociado"></div>
                  <?php if ($cant>0){
					 	for ($i=1;$i<=$cant;$i++){
							echo $arrarTutores[$i]['FTi_Tipo'].": <strong>".$arrarTutores[$i]['Per_Apellido'].", ".$arrarTutores[$i]['Per_Nombre']."</strong><br />";
                            $dniTutor = $arrarTutores[$i]['Per_DNI'];                            
							echo "DNI: <strong><a id='cargarHermano$dniTutor' href='#'>".$arrarTutores[$i]['Per_DNI']."</a></strong> ";
							echo "".$arrarTutores[$i]['Telefonos']."<br />";
							
							if ($cant>1){
								echo "<hr />";
							}//fin if cant
						}//fin for
						//echo "<a href='imprimirGrupoFamiliarAlumno.php?id=$row[Per_ID]' target='_blank'>Imprimir</a>";
					 
					 }//fin if?>
                     <hr>
                     <div id="noTieneHermanoAsociado"></div>
                  <?php if ($cantH>0){
					 	for ($i=1;$i<=$cantH;$i++){
							echo $arrarHermanos[$i]['FTi_Tipo'].": <strong>".$arrarHermanos[$i]['Per_Apellido'].", ".$arrarHermanos[$i]['Per_Nombre']."</strong><br />";
                            $dniHerm = $arrarHermanos[$i]['Per_DNI'];
							echo "DNI: <strong><a id='cargarHermano$dniHerm' href='#'>".$arrarHermanos[$i]['Per_DNI']."</a></strong> ";
                            
                            $arrayBeneficios2 = obtenerBeneficioAlumnoLectivo($Lec_ID, $arrarHermanos[$i]['Per_ID']);
                            $total_beneficios2 = count($arrayBeneficios2);
                            $mostrar_beneficios2 = '';
                            for ($iBen=0; $iBen < $total_beneficios2; $iBen++) { 
                                $mostrar_beneficios2 .= $arrayBeneficios2[$iBen]['Ben_Nombre'].' ('.cfecha($arrayBeneficios2[$iBen]['CBe_Desde']).'-'.cfecha($arrayBeneficios2[$iBen]['CBe_Hasta']).')<br>';
                            }
                            if (!empty($mostrar_beneficios2)) $mostrar_beneficios2 = "Ciclo ".$nombre_lectivo.': '.$mostrar_beneficios2;
                            
                            $deuda_hermano=Obtener_Deuda_Sistema($arrarHermanos[$i]['Per_ID'], $Recargo);
                            if ($deuda_hermano>0){
                                if ($_SESSION['sesion_UsuCaja']==1){
                                    $mostrar_deuda = $deuda_hermano + $Recargo;
                                    echo "<br><span class='vencida_roja'><strong>Deuda vencida: $".$mostrar_deuda."</strong></span>";
                                    //echo $mostrar_beneficios;
                                }else{
                                    echo "<br><span class='vencida_roja'><strong>Deuda vencida</strong></span>";
                                
                                }
                            }else{
                                echo "<br><span class='cuota_pagada'><strong>No tiene Deuda Vencida</strong></span>";
                            }
                            if ($_SESSION['sesion_UsuCaja']==1 && !empty($mostrar_beneficios2)){
                                echo "<br><span class='cuota_beneficio'><strong>".$mostrar_beneficios2."</strong></span><br>";
                                //echo $mostrar_beneficios;
                            }                                
                            
							echo "<br />".$arrarHermanos[$i]['Telefonos']."<br />";
                            
							
							if ($cantH>1){
								echo "<hr />";
							}//fin if cant
						}//fin for
					 
					 }//fin if?>

                     <div id="noTieneHijosAsociado"></div>
                  <?php if ($cantHijos>0){
                        for ($i=1;$i<=$cantHijos;$i++){
                            echo $arrayHijos[$i]['FTi_Tipo'].": <strong>".$arrayHijos[$i]['Per_Apellido'].", ".$arrayHijos[$i]['Per_Nombre']."</strong><br />";
                            $dniHerm = $arrayHijos[$i]['Per_DNI'];
                            echo "DNI: <strong><a id='cargarHermano$dniHerm' href='#'>".$arrayHijos[$i]['Per_DNI']."</a></strong> ";
                            //if ($_SESSION['sesion_UsuCaja']==1){
                                $deuda_hijo=Obtener_Deuda_Sistema($arrayHijos[$i]['Per_ID'], $Recargos);
                                if ($deuda_hijo>0){
                                    if ($_SESSION['sesion_UsuCaja']==1){
                                        $mostrar_deuda = $deuda_hijo + $Recargos;
                                        echo "<br><span class='vencida_roja'><strong>Deuda vencida: $".$mostrar_deuda."</strong></span>";
                                    }else{
                                        echo "<br><span class='vencida_roja'><strong>Deuda vencida</strong></span>";
                                    
                                    }
                                }else{
                                    echo "<br><span class='cuota_pagada'><strong>No tiene Deuda Vencida</strong></span>";
                                }
                                
                            //}
                            echo "<br />".$arrayHijos[$i]['Telefonos']."<br />";
                            
                            
                            if ($cantHijos>1){
                                echo "<hr />";
                            }//fin if cant
                        }//fin for
                     
                     }//fin if?>
                  
				  </td>
			  </tr>
		  </table>
	  <?php } else { ?>
		  <div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El DNI ingresado no corresponde a una persona cargada, por favor verifique los datos ingresados antes de continuar.</span><br />
			  <?php
		  }
		
	
}//fin funcion

function buscarNivReq(){
	            $PerID = $_POST['PerID'];
				$sql = "SELECT DISTINCT (Pre_Niv_ID) FROM RequisitoPresentado WHERE Pre_Per_ID = $PerID ORDER BY Pre_Niv_ID DESC";
				$row = mysqli_fetch_array($sql);
				echo $row[Pre_Niv_ID];				
				}
//fin funcion

    function buscarPerID() {
        $DNI = trim($_POST['DNI']);
        $_SESSION['sesion_ultimoDNI'] = $DNI;
		if (empty($DNI)) exit;
        $sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            echo $row[Per_ID];
        } else {
			//echo $sql;
			$sql = "SELECT * FROM Legajo INNER JOIN Persona ON (Leg_Per_ID = Per_ID) WHERE Leg_Numero='$DNI';";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				echo $row[Per_ID];
			}else{
            	echo 0;
			}
        }
		
}//fin funcion

function buscarLegajoColegio() {
	$DNI = $_POST['DNI'];
	$_SESSION['sesion_ultimoDNI'] = $DNI;
	$sql = "SELECT
Leg_ID
, Leg_Per_ID
, Per_DNI FROM Legajo
INNER JOIN Persona 
	ON (Leg_Per_ID = Per_ID) WHERE Per_DNI = $DNI";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		echo $row[Leg_ID];
	} else {
		echo 0;
	}
}//fin function

function buscarLegajoColegioInstituto() {
	$DNI = $_POST['DNI'];
	$_SESSION['sesion_ultimoDNI'] = $DNI;
	$sql = "SELECT Leg_Colegio FROM Legajo INNER JOIN Persona 
	ON (Leg_Per_ID = Per_ID) WHERE Per_DNI = $DNI";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		echo $row[Leg_Colegio];
	} else {
		echo 0;
	}
}//fin function



    function buscarLegajoTerciario() {
        $DNI = $_POST['DNI'];
        $_SESSION['sesion_ultimoDNI'] = $DNI;
        $sql = "SELECT
    Leg_ID
    , Leg_Per_ID
    , Per_DNI FROM Legajo
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID) WHERE Per_DNI = $DNI AND Leg_StaMaria = 1";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            echo $row[Leg_ID];
        } else {
            echo 0;
        }
    }

//fin funcion

    function obtenerApellidoNombre() {
        $PerID = trim($_POST['PerID']);
        $conDNI = $_POST['conDNI'];
		
		
		$sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
        if (!empty($conDNI)) {
            $DNI = $_POST['DNI'];
            $_SESSION['sesion_ultimoDNI'] = $DNI;
            $sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
			if (empty($DNI)) exit;
        } else {
            $sql = "SELECT * FROM Persona WHERE Per_ID = $PerID";
			if (empty($PerID)) exit;
        }
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            echo "$row[Per_Apellido], $row[Per_Nombre]";
        } else {
            $sql = "SELECT * FROM Legajo INNER JOIN Persona ON (Leg_Per_ID = Per_ID) WHERE Leg_Numero='$DNI';";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				echo "$row[Per_Apellido], $row[Per_Nombre]";
			}else{
				echo "No existe";
			}
			
        }
    }

//fin funcion

    function obtenerApellidoNombreSIUCC() {

        $DNI = $_POST['DNI'];
        $sql = "SELECT * FROM Persona WHERE Per_ID = $DNI";
        $result = consulta_mysql($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            echo "$row[Per_Apellido], $row[Per_Nombre]";
        } else {
            echo "NO EXISTE";
        }
    }

//fin funcion

    function obtenerTipoDoc() {

        $DNI = $_POST['DNI'];
        $sql = "SELECT Per_Doc_ID FROM Persona WHERE Per_ID = $DNI";
        $result = consulta_mysql($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            echo "$row[Per_Doc_ID]";
        } else {
            echo '';
        }
    }

//fin funcion

    function obtenerFotoSIUCC() {
        $foto = "";
        $rutaAlu = "http://www.uccuyo.edu.ar/alumno/fotos 60x60/";
        $rutaGiteco = "http://www.uccuyo.edu.ar/uccdigital/images/60/";

        $fechaSegundos = time();
        $strNoCache = "?nocache=$fechaSegundos";

        $DNI = $_POST['DNI'];
        $DocID = $_POST['DocID'];

        $sql = "SELECT Per_CFoto FROM Persona WHERE Per_ID = $DNI AND Per_Doc_ID = $DocID;";
        $result = consulta_mysql($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            if (strlen($row[Per_CFoto]) > 6)
                $foto = $row[Per_CFoto];
            else
                echo "http://www.uccuyo.edu.ar/alumno/imagenes/falta_foto.jpg";
        }else {
            //echo '';
        }

//Si est� cargada en el SIUCC
        if (strlen($foto) > 6) {
            //Me fijo si est� subida en Giteco
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/uccdigital/images/60/" . $foto))
                echo $rutaGiteco . $foto . $strNoCache;
            else if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/alumno/fotos 60x60/" . $foto))
                echo $rutaAlu . $foto . $strNoCache;
        }
//echo $rutaGiteco.$foto.$strNoCache;
    }

//fin funcion

    function obtenerApellidoNombreDocente() {
        $sql = "SET NAMES UTF8";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $DocID = $_POST['DocID'];
        $conDNI = $_POST['conDNI'];
        if (!empty($conDNI)) {
            $DNI = $_POST['DNI'];
            $sql = "SELECT * FROM Persona, Colegio_Docente WHERE Per_ID = Doc_Per_ID AND Per_DNI = $DNI";
        } else {
            $sql = "SELECT * FROM Persona, Colegio_Docente WHERE Per_ID = Doc_Per_ID AND Doc_ID = $DocID";
        }
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            echo "$row[Per_Apellido], $row[Per_Nombre]";
        } else {
            echo "NO EXISTE COMO PROFESOR";
        }
    }

//fin funcion

    function ordenarMenuArriba() {

        $MenID = $_POST['MenID']; //2
        $Orden = $_POST['Orden']; //2

        $Orden_reemplazar = $Orden;
        $Orden = $Orden - 1;
        $sql = "SELECT * FROM Menu WHERE Men_Orden = $Orden";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $MenID_reemplazar = $row[Men_ID];
        }
        mysqli_close ();

        $i = 0;
        if ($Orden < 1) {
            $Orden = 1;
            $Orden_reemplazar = 2;
        }
        $mismo = false;
        if ($MenID_reemplazar == $MenID)
            $mismo = true;

        $sql = "SELECT * FROM Menu ORDER BY Men_Orden";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        while ($row = mysqli_fetch_array($result)) {
            $i++;
            $OrdenNuevo = $i;
            $Menu = $row[Men_ID];
            if ($Menu == $MenID) {
                $OrdenNuevo = $Orden; //1
            }
            if (($Menu == $MenID_reemplazar) && (!$mismo)) {
                $OrdenNuevo = $Orden_reemplazar; //2
            }

            $sql = "UPDATE Menu SET Men_Orden = $OrdenNuevo WHERE Men_ID = $Menu";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        }//fin while
    }

//fin funcion

    function ordenarMenuAbajo() {

        $MenID = $_POST['MenID']; //2
        $Orden = $_POST['Orden']; //2

        $Orden_reemplazar = $Orden;
        $Orden = $Orden + 1;
        $sql = "SELECT * FROM Menu WHERE Men_Orden = $Orden";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $MenID_reemplazar = $row[Men_ID];
        }
        mysqli_close ();
        $sql = "SELECT * FROM Menu ORDER BY Men_Orden";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        $i = 0;
        if ($Orden > mysqli_num_rows($result)) {
            $Orden = mysqli_num_rows($result);
            $Orden_reemplazar = $Orden - 1;
        }
        $mismo = false;
        if ($MenID_reemplazar == $MenID)
            $mismo = true;

        while ($row = mysqli_fetch_array($result)) {
            $i++;
            $OrdenNuevo = $i;
            $Menu = $row[Men_ID];
            if ($Menu == $MenID) {
                $OrdenNuevo = $Orden; //1
            }
            if (($Menu == $MenID_reemplazar) && (!$mismo)) {
                $OrdenNuevo = $Orden_reemplazar; //2
            }

            $sql = "UPDATE Menu SET Men_Orden = $OrdenNuevo WHERE Men_ID = $Menu";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        }//fin while
    }

//fin funcion

    function mostrarMenuOpciones() {

        $ID = $_POST['ID'];

        if (!empty($ID)) {
            $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $ID ORDER BY Opc_Orden";

            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "<ul>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<li>$row[Opc_Nombre]</li>";
            }//fin while
            echo "</ul>";
        }
    }

//fin funcion

    function guardarMenu() {
        //echo "Hola";exit;
        $Nombre = $_POST['Nombre'];
        $Nombre = trim($Nombre);
        //$Nombre = arreglarCadenaMayuscula($Nombre);

        $ID = $_POST['ID'];
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (!empty($Nombre)) {
            $sql = "SELECT * FROM Menu WHERE Men_Nombre = '$Nombre'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
                echo "Ya existe";
            } else {
                $sql = "UPDATE Menu SET Men_Nombre = '$Nombre' WHERE Men_ID = $ID";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo $Nombre;
            }
        }
    }

//fin funcion

    function guardarOpcion() {
        //echo "Hola";exit;
        $MenID = $_POST['MenID'];
        $Nombre = $_POST['Nombre'];
        $Nombre = trim($Nombre);
        //$Nombre = arreglarCadenaMayuscula($Nombre);

        $ID = $_POST['ID'];

        if (!empty($Nombre)) {
            $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID AND Opc_Nombre = '$Nombre'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
                echo "Ya existe";
            } else {
                $sql = "UPDATE Opcion SET Opc_Nombre = '$Nombre' WHERE Opc_ID = $ID AND Opc_Men_ID = $MenID";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo $Nombre;
            }
        }
    }

//fin funcion

    function llenarOpcionesTabla() {

        $Menu = $_POST['Menu'];
		$sql = "SET NAMES UTF8";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (!empty($Menu)) {
            $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $Menu AND Opc_Orden <> 99";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) == 0) {
                $i = 0;
                $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $Menu ORDER BY Opc_Orden";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                while ($row = mysqli_fetch_array($result)) {
                    $i++;
                    $sql = "UPDATE Opcion SET Opc_Orden = $i WHERE Opc_ID = $row[Opc_ID] AND Opc_Men_ID = $Menu";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                }//fin while
            }
			$sql = "SET NAMES UTF8";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            $sql = "SELECT * FROM Opcion, Menu WHERE Men_ID = Opc_Men_ID AND Opc_Men_ID = $Menu ORDER BY Opc_Orden";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            $total = mysqli_num_rows($result);
            if ($total > 0) {
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
                    <legend>Resultado de la búsqueda</legend>

                    <table width="100%" border="0">
                        <tr>
                            <td class="fila_titulo" width="90">&nbsp;</td>
                            <td class="fila_titulo" width="90"><div align="center">Orden</div></td>
                            <td class="fila_titulo"><div align="left">Nombre de la Opci&oacute;n </div></td>
                            <td class="fila_titulo"><div align="left">Comando </div></td>      
                            <td class="fila_titulo"><div align="left">Pertenece al Men&uacute; </div></td>
                        </tr>
                        <?php
                        $i = 0;
                        while ($row = mysqli_fetch_array($result)) {
                            $i++;
                            if (($i % 2) == 0)
                                $clase = "fila";
                            else
                                $clase = "fila2";
                            ?>
                            <tr class="<?php echo $clase ?>" id="fila<?php echo $i; ?>">
                                <td><input name="Nuevo<?php echo $i; ?>" type="checkbox" id="Nuevo<?php echo $i; ?>" value="<?php echo $row[Opc_ID]; ?>"><input type="hidden" id="Orden<?php echo $i; ?>" value="<?php echo $row[Opc_Orden]; ?>">
                                    <?php
                                    if ($i == 1)
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                    if ($i > 1) {
                                        ?><img id="arriba<?php echo $i; ?>" src="botones/orden_arriba.gif" alt="Mover arriba" title="Mover arriba" width="20" height="20" style="cursor:pointer" />
                                        <?php
                                    }//fin if
                                    if ($i < $total) {
                                        ?>
                                        <img id="abajo<?php echo $i; ?>" src="botones/orden_abajo.gif" alt="Mover abajo" title="Mover abajo" style="cursor:pointer" width="20" height="20" />
                                        <?php
                                    }//fin if
                                    ?>	  </td>
                                <td><div align="center"><?php echo $row[Opc_Orden]; ?></div></td>
                                <td><span id="nombrePais<?php echo $i; ?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre"><?php echo $row[Opc_Nombre]; ?></span>
                                    <input name="editar<?php echo $i; ?>" type="text" id="editar<?php echo $i; ?>" value="<?php echo $row[Opc_Nombre]; ?>" class="input_editar" /></td>

                                <td><?php echo $row[Opc_Comando]; ?></td>
                                <td><?php echo $row[Men_Nombre]; ?></td>
                            </tr>
                            <?php
                        }//fin del while
                        ?>  
                    </table>
                </fieldset>
                <fieldset class="recuadro_inferior">
                    <img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se econtraron $total opciones cargados"; ?>
                </fieldset>	
                <?php
            } else {
                ?>
                <div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron opciones asociadas al men� seleccionado.</span>
                    <?php
                }
                ?>	

                <?php
            }
        }

//fin funcion//*/

        function ordenarOpcionArriba() {

            $MenID = $_POST['MenID']; //2
            $OpcID = $_POST['OpcID']; //2
            $Orden = $_POST['Orden']; //2

            $Orden_reemplazar = $Orden;
            $Orden = $Orden - 1;
            $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID AND Opc_Orden = $Orden";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $OpcID_reemplazar = $row[Opc_ID];
            }
            mysqli_close ();

            $i = 0;
            if ($Orden < 1) {
                $Orden = 1;
                $Orden_reemplazar = 2;
            }
            $mismo = false;
            if ($OpcID_reemplazar == $OpcID)
                $mismo = true;

            $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID ORDER BY Opc_Orden";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            while ($row = mysqli_fetch_array($result)) {
                $i++;
                $OrdenNuevo = $i;
                $Opcion = $row[Opc_ID];
                if ($Opcion == $OpcID) {
                    $OrdenNuevo = $Orden; //1
                }
                if (($Opcion == $OpcID_reemplazar) && (!$mismo)) {
                    $OrdenNuevo = $Orden_reemplazar; //2
                }

                $sql = "UPDATE Opcion SET Opc_Orden = $OrdenNuevo WHERE Opc_Men_ID = $MenID AND Opc_ID = $Opcion";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            }//fin while
        }

//fin funcion

        function ordenarOpcionAbajo() {

            $MenID = $_POST['MenID']; //2
            $OpcID = $_POST['OpcID']; //2
            $Orden = $_POST['Orden']; //2

            $Orden_reemplazar = $Orden;
            $Orden = $Orden + 1;
            $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID AND Opc_Orden = $Orden";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $OpcID_reemplazar = $row[Opc_ID];
            }
            mysqli_close ();
            $sql = "SELECT * FROM Opcion WHERE Opc_Men_ID = $MenID ORDER BY Opc_Orden";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

            $i = 0;
            if ($Orden > mysqli_num_rows($result)) {
                $Orden = mysqli_num_rows($result);
                $Orden_reemplazar = $Orden - 1;
            }
            $mismo = false;
            if ($OpcID_reemplazar == $OpcID)
                $mismo = true;

            while ($row = mysqli_fetch_array($result)) {
                $i++;
                $OrdenNuevo = $i;
                $Opcion = $row[Opc_ID];
                if ($Opcion == $OpcID) {
                    $OrdenNuevo = $Orden; //1
                }
                if (($Opcion == $OpcID_reemplazar) && (!$mismo)) {
                    $OrdenNuevo = $Orden_reemplazar; //2
                }

                $sql = "UPDATE Opcion SET Opc_Orden = $OrdenNuevo WHERE Opc_Men_ID = $MenID AND Opc_ID = $Opcion";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            }//fin while
        }

//fin funcion
function Mayus($variable) {
	$variable = strtr(strtoupper($variable),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
	return $variable;
}

        function guardarUsuario() {
            //echo "Hola";exit;
			header("Content-Type: text/html;charset=utf-8");
            $Usuario = $_POST['Usuario'];
			//echo "Antes: ".$_POST['Persona']."<br />";
            //$Persona = ucwords(strtolower(utf8_decode($_POST['Persona'])));
			$Persona = Mayus($_POST['Persona']);
			//echo "Despues: $Persona<br />";
            $Clave = $_POST['Clave'];
			$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			// Create salted password 
			$Clave = hash('sha512', $Clave . $random_salt);
            $SedID = $_POST['SedID'];
            if (empty($SedID))
                $SedID = 1; //Si no trae sede como par�metro, le asignamos la sede San Juan por defecto			
			
            $sql = "SET NAMES UTF8";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		    $sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$Usuario'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                $sql = "INSERT INTO Usuario (Usu_Nombre, Usu_Persona, Usu_Clave, Usu_Salt, Usu_Sed_ID) VALUES ('$Usuario', '$Persona', '$Clave', '$random_salt', '$SedID')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se agregó correctamente el usuario <strong>$Usuario</strong> a la persona <strong>$Persona</strong>";
            } else {
                $sql = "UPDATE Usuario SET Usu_Persona = '$Persona', Usu_Clave = '$Clave', Usu_Salt = '$random_salt', Usu_Sed_ID = '$SedID' WHERE Usu_Nombre = '$Usuario'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se actualizaron los datos del usuario <strong>$Usuario</strong> para la persona <strong>$Persona</strong>";
            }
        }

//fin funcion

        function actualizarDatosUsuario() {
            //echo "Hola";exit;
            $UsuID = $_POST['UsuID'];
            $Persona = ucwords(strtolower(utf8_decode($_POST['Persona'])));
            $Email = $_POST['Email'];

            $sql = "SELECT * FROM Usuario WHERE Usu_ID = '$UsuID'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
                $sql = "UPDATE Usuario SET Usu_Persona = '$Persona', Usu_Email = '$Email' WHERE Usu_ID = '$UsuID'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se actualizaron los datos correctamente";
                $_SESSION['sesion_email'] = $Email;
            }
        }

//fin funcion

        function comprobarClave() {
            //echo "Hola";exit;
            $UsuID = $_SESSION['sesion_UsuID'];
            $Clave = $_POST['Clave'];

            $sql = "SELECT * FROM Usuario WHERE Usu_ID = '$UsuID'";// AND Usu_Clave = '$Clave'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre				
                $row = mysqli_fetch_array($result);
				$salt = $row['Usu_Salt'];
				$password = hash('sha512', $Clave . $salt);
				$db_password = $row['Usu_Clave'];
				//echo "$password - $db_password";
				if ($db_password == $password) {
					echo "Correcto";
				} else {
					echo "Incorrecto";
				}	
            } else {
                echo "Incorrecto";
            }
        }

//fin funcion

        function guardarRol($Usuario = "", $Rol = "", $BuscarIDUsuario = false) {
            //echo "Hola";exit;
            if (empty($Usuario) && empty($Rol)) {
                $Usuario = $_POST['UsuID'];
                $Rol = $_POST['RolID'];
            }
            if ($BuscarIDUsuario) {
                $sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$Usuario'";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    $Usuario = $row[Usu_ID];
                }
            }

            $sql = "SELECT * FROM RolUsuario WHERE RUs_Usu_ID = '$Usuario'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                $sql = "INSERT INTO RolUsuario (RUs_Usu_ID, RUs_Rol_ID) VALUES ('$Usuario', '$Rol')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se agrego correctamente el rol al usuario.";
            } else {
                $sql = "UPDATE RolUsuario SET RUs_Rol_ID = '$Rol' WHERE RUs_Usu_ID = '$Usuario'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se actualizo el rol al usuario.";
            }

            $sql = "SELECT * FROM RolUnidad WHERE RUn_Rol_ID = '$Rol'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            while ($row = mysqli_fetch_array($result)) {
                $sql = "SELECT * FROM Permiso WHERE Prm_Usu_ID = '$Usuario' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
                $result_permiso = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result_permiso) > 0) {
                    //ya existe, actualizamos
                    $sql = "UPDATE Permiso SET Prm_Visible = '$row[RUn_Visible]', Prm_Guardar = '$row[RUn_Guardar]', Prm_Modificar = '$row[RUn_Modificar]', Prm_Eliminar = '$row[RUn_Eliminar]', Prm_Imprimir = '$row[RUn_Imprimir]' WHERE Prm_Usu_ID = '$Usuario' AND Prm_Uni_ID = '$row[RUn_Uni_ID]' AND Prm_Men_ID = '$row[RUn_Men_ID]' AND Prm_Opc_ID = '$row[RUn_Opc_ID]'";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                } else {
                    //Insertamos el nuevo permiso
                    $sql = "INSERT INTO Permiso (Prm_Usu_ID, Prm_Men_ID, Prm_Opc_ID, Prm_Uni_ID, Prm_Visible, Prm_Guardar, Prm_Modificar, Prm_Eliminar, Prm_Imprimir) VALUES ('$Usuario', '$row[RUn_Men_ID]', '$row[RUn_Opc_ID]', '$row[RUn_Uni_ID]', '$row[RUn_Visible]', '$row[RUn_Guardar]', '$row[RUn_Modificar]', '$row[RUn_Eliminar]', '$row[RUn_Imprimir]')";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                }
            }//fin del while
        }

//fin funcion

        function actualizarRoles($Rol = "") {
            //echo "Hola";exit;
            if (empty($Rol)) {
                $Rol = $_POST['RolID'];
            }

            $sql = "SELECT * FROM RolUsuario WHERE RUs_Rol_ID = '$Rol'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            while ($row = mysqli_fetch_array($result)) {
                //Insertamos los nuevos permisos
                $sql = "INSERT IGNORE INTO Permiso (Prm_Usu_ID, Prm_Men_ID, Prm_Opc_ID, Prm_Uni_ID, Prm_Visible, Prm_Guardar, Prm_Modificar, Prm_Eliminar, Prm_Imprimir) (SELECT '$row[RUs_Usu_ID]', RUn_Men_ID, RUn_Opc_ID, RUn_Uni_ID, RUn_Visible, RUn_Guardar, RUn_Modificar, RUn_Eliminar, RUn_Imprimir FROM RolUnidad WHERE RUn_Rol_ID = $Rol)";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                //$mostrar = $sql;
            }//fin while
            echo "Se actualizó correctamente los Roles de los Usuarios";
        }

//fin funcion

        function guadarPermisoUsuario() {
            //echo "Hola";exit;
            $UsuID = $_POST['UsuID'];
            $MenID = $_POST['MenID'];
            $OpcID = $_POST['OpcID'];
            $Boton = $_POST['Boton'];
            $UniID = $_POST['UniID'];

            $sql = "SELECT * FROM Permiso WHERE Prm_Usu_ID = '$UsuID' AND Prm_Men_ID = '$MenID' AND Prm_Opc_ID = '$OpcID' AND Prm_Uni_ID = '$UniID'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) == 0) {//no existe
                $sql = "INSERT INTO Permiso (Prm_Usu_ID, Prm_Men_ID, Prm_Opc_ID, Prm_Uni_ID, Prm_$Boton) VALUES ('$UsuID', '$MenID', '$OpcID', '$UniID', '1')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				echo "$sql<br />";
                echo "Se agregó correctamente el permiso al usuario.";
            } else {
                $sql = "UPDATE Permiso SET Prm_$Boton = '1' WHERE Prm_Usu_ID = '$UsuID' AND Prm_Men_ID = '$MenID' AND Prm_Opc_ID = '$OpcID' AND Prm_Uni_ID = '$UniID'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				echo "$sql<br />";
                echo "Se actualizó el permiso al usuario.";
            }
            //echo $sql;
        }

//fin funcion

        function guadarPermisoRol() {
            //echo "Hola";exit;
            $RolID = $_POST['RolID'];
            $MenID = $_POST['MenID'];
            $OpcID = $_POST['OpcID'];
            $Boton = $_POST['Boton'];
            $UniID = $_POST['UniID'];

            $sql = "SELECT * FROM RolUnidad WHERE RUn_Rol_ID = '$RolID' AND RUn_Men_ID = '$MenID' AND RUn_Opc_ID = '$OpcID' AND RUn_Uni_ID = '$UniID'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) == 0) {//no existe
                $sql = "INSERT INTO RolUnidad (RUn_Rol_ID, RUn_Men_ID, RUn_Opc_ID, RUn_Uni_ID, RUn_$Boton) VALUES ('$RolID', '$MenID', '$OpcID', '$UniID', '1')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se agregó correctamente el permiso al rol $Boton.";
            } else {
                $sql = "UPDATE RolUnidad SET RUn_$Boton = '1' WHERE RUn_Rol_ID = '$RolID' AND RUn_Men_ID = '$MenID' AND RUn_Opc_ID = '$OpcID' AND RUn_Uni_ID = '$UniID'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se actualizó el permiso al rol $Boton.";
            }
        }

//fin funcion

        function eliminarPermisoUsuario() {
            $UsuID = $_POST['UsuID'];

            $sql = "DELETE FROM Permiso WHERE Prm_Usu_ID = '$UsuID'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			//echo "$sql<br />";
        }

//fin funcion

        function eliminarPermisoRol() {
            $RolID = $_POST['RolID'];

            $sql = "DELETE FROM RolUnidad WHERE RUn_Rol_ID = '$RolID'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        }

//fin funcion

        function llenarRolesUsuario() {
            //echo "Hola";exit;
            $Usuario = $_POST['Usuario'];

            $sql = "SELECT * FROM RolUsuario, Roles2, Usuario WHERE RUs_Usu_ID = Usu_ID AND RUs_Rol_ID = Rol_ID AND RUs_Usu_ID = '$Usuario'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
                $row = mysqli_fetch_array($result);
                ?>
                <div class="borde_aviso"><span class="texto">El usuario <strong><?php echo $row[Usu_Nombre]; ?></strong> tiene el rol <strong><?php echo $row[Rol_Nombre]; ?></strong>.</span></div>		
                <?php
            } else {
                ?>
                <div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Este usuario no tiene cargado un rol todav�a.</span>
                    <?php
                }
            }

//fin funcion

            function cargarPermisosSimples() {
                ?>
                <script language="javascript">
                    $(document).ready(function(){
                        $("#cargandoPermisoUsuario").hide();
                                            	
                        $("#arbolUsuario").checkboxTree({
                            collapsedarrow: "imagenes/img-arrow-collapsed.gif",
                            expandedarrow: "imagenes/img-arrow-expanded.gif",
                            blankarrow: "imagenes/img-arrow-blank.gif"
                            ,checkchildren: true///
                            ,checkparents: true
                            //,hoverClass: ""                            

                        });
                        //$("#arbolUsuario").checkboxTree();
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
                                    ,checkchildren: true///
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
                        <td><?php echo cargarListaUsuarios("ListPermisoUsuID"); ?><img src="imagenes/loading2.gif" alt="Cargando permisos" title="Cargando permisos" width="24" height="24" id="cargandoPermisoUsuario"/></td>
                    </tr>
                    <tr>
                        <td class="texto"> <div align="right">Arbol de Permisos </div></td>
                        <td class="texto"><div id="ArbolPermiso"><?php cargarArbolPermisos(); ?></div></td>
                    </tr>
                </table>
                <?php
            }

//fin function

            function cargarPermisosRoles() {
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
                                $( ":checkbox").attr('checked', false);
                                $("input[id^='Botones2_']").each(function(){
                                    valor = $(this).val();
                                    if ( valor  == "Visible" ){				
                                        //alert($(this).attr('checked'));
                                        $(this).attr('checked', true);
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
                                $( ":checkbox").attr('checked', false);
                                $("input[id^='Botones2_']").each(function(){
                                    valor = $(this).val();
                                    if ( valor  == "Visible" || valor  == "Imprimir" || valor  == "Guardar" ){				
                                        //alert($(this).attr('checked'));
                                        $(this).attr('checked', true);
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
                                $( ":checkbox").attr('checked', false);
                                $("input[id^='Botones2_']").each(function(){
                                    valor = $(this).val();
                                    if ( valor  == "Visible" || valor  == "Imprimir" || valor  == "Guardar" || valor  == "Modificar" ){
                                        //alert($(this).attr('checked'));
                                        $(this).attr('checked', true);
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
                                $( ":checkbox").attr('checked', false);
                                $("input[id^='Botones2_']").each(function(){
                                    valor = $(this).val();
                                    if ( valor  == "Visible" || valor  == "Imprimir" || valor  == "Guardar" || valor  == "Modificar" || valor  == "Eliminar" ){
                                        //alert($(this).attr('checked'));
                                        $(this).attr('checked', true);
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
                        <td><?php echo cargarListaRoles("ListPermisoRolID"); ?><img src="imagenes/loading2.gif" alt="Cargando permisos" title="Cargando permisos" width="24" height="24" id="cargandoPermisoRol"/></td>
                    </tr>
                    <tr>
                        <td class="texto"> <div align="right">Arbol de Permisos </div></td>
                        <td class="texto"><div id="ArbolPermisoRoles"><?php cargarArbolPermisosRoles(); ?></div></td>
                    </tr>
                </table>
                <?php
            }

//fin function

            function buscarOtrosDatos() {
                //echo "Hola";exit;
                $PerID = $_POST['PerID'];

                $sql = "SELECT * FROM PersonaDatos, Persona, PersonaDocumento WHERE Per_Doc_ID = Doc_ID AND Dat_Per_ID = Per_ID AND Dat_Per_ID = '$PerID'";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                    echo "{}";
                } else {
                    $row = mysqli_fetch_array($result);
                    $datos .= "{\"Dat_Niv_ID\": \"" . $row[Dat_Niv_ID] . "\",\"";
                    //$datos .= "Dat_Ent_ID\": \"" . $row[Dat_Ent_ID] . "\",\"";
                    $datos .= "Dat_Dom_Pro_ID\": \"" . $row[Dat_Dom_Pro_ID] . "\",\"";
                    $datos .= "Dat_Dom_Pai_ID\": \"" . $row[Dat_Dom_Pai_ID] . "\",\"";
                    $datos .= "Dat_Dom_Loc_ID\": \"" . $row[Dat_Dom_Loc_ID] . "\",\"";
                    $datos .= "Dat_Nac_Pro_ID\": \"" . $row[Dat_Nac_Pro_ID] . "\",\"";
                    $datos .= "Dat_Nac_Pai_ID\": \"" . $row[Dat_Nac_Pai_ID] . "\",\"";
                    $datos .= "Dat_Nac_Loc_ID\": \"" . $row[Dat_Nac_Loc_ID] . "\",\"";

                    $datos .= "Doc_Nombre\": \"" . $row[Doc_Nombre] . "\",\"";
                    $datos .= "Per_DNI\": \"" . $row[Per_DNI] . "\",\"";
                    $datos .= "Per_Apellido\": \"" . $row[Per_Apellido] . "\",\"";
                    $datos .= "Per_Nombre\": \"" . $row[Per_Nombre] . "\",\"";
                    $datos .= "Per_Sexo\": \"" . $row[Per_Sexo] . "\",\"";
                    $datos .= "Per_Alternativo\": \"" . $row[Per_Alternativo] . "\",\"";

                    $datos .= "Dat_Nacimiento\": \"" . cfecha($row[Dat_Nacimiento]) . "\",\"";
                    $datos .= "Dat_Domicilio\": \"" . $row[Dat_Domicilio] . "\",\"";
                    $datos .= "Dat_CP\": \"" . $row[Dat_CP] . "\",\"";
                    $datos .= "Dat_Email\": \"" . $row[Dat_Email] . "\",\"";
                    $datos .= "Dat_Telefono\": \"" . $row[Dat_Telefono] . "\",\"";
                    $datos .= "Dat_Celular\": \"" . $row[Dat_Celular] . "\",\"";
                    $datos .= "Dat_Ocupacion\": \"" . $row[Dat_Ocupacion] . "\",\"";
                    $datos .= "Dat_Observaciones\": \"" . $row[Dat_Observaciones] . "\",\"";
                    $datos .= "Dat_Fecha\": \"" . cfecha($row[Dat_Fecha]) . "\",\"";
                    $datos .= "Dat_Hora\": \"" . $row[Dat_Hora] . "\"}";
                    echo $datos;
                }
            }

//fin funcion

            function buscarDatosLegajo() {
                //echo "Hola";exit;
                $PerID = $_POST['PerID'];
                $TipoLegajo = $_POST['TipoLegajo'];

                $sql = "SELECT * FROM Legajo WHERE Leg_Per_ID = '$PerID'";
                
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                    echo "{}";
                } else {
                    $row = mysqli_fetch_array($result);
                    $datos .= "{\"Leg_ID\": \"" . $row[Leg_ID] . "\",\"";
                    $datos .= "Leg_Sed_ID\": \"" . $row[Leg_Sed_ID] . "\",\"";
                    $datos .= "Leg_Numero\": \"" . $row[Leg_Numero] . "\",\"";
                    $datos .= "Leg_Alta_Fecha\": \"" . cfecha($row[Leg_Alta_Fecha]) . "\",\"";
                    $datos .= "Leg_Baja\": \"" . $row[Leg_Baja] . "\",\"";
                    $datos .= "Leg_Baja_Fecha\": \"" . cfecha($row[Leg_Baja_Fecha]) . "\",\"";
                    $datos .= "Leg_Baja_Hora\": \"" . $row[Leg_Baja_Hora] . "\",\"";
                    $datos .= "Leg_Baja_Motivo\": \"" . $row[Leg_Baja_Motivo] . "\",\"";
                    $datos .= "Leg_Usu_ID\": \"" . $row[Leg_Usu_ID] . "\",\"";
                    $datos .= "Leg_Fecha\": \"" . cfecha($row[Leg_Fecha]) . "\",\"";
                    $datos .= "total_legajos\": \"" . mysqli_num_rows($result) . "\",\"";
                    $datos .= "Leg_Hora\": \"" . $row[Leg_Hora] . "\"}";

                    echo $datos;
                }
            }

//fin funcion

            function buscarDatosInscripcionLectivo() {
                //echo "Hola";exit;
                $LegID = $_POST['LegID'];
                $LecID = $_POST['LecID'];
				$NivColegio = $_POST['NivColegio'];

                $sql = "SELECT * FROM Colegio_Inscripcion INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID) WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";// AND Niv_Colegio = $NivColegio";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                    echo "{}";
                } else {
                    $row = mysqli_fetch_array($result);
                    $datos .= "{\"Ins_Leg_ID\": \"" . $row[Ins_Leg_ID] . "\",\"";
                    $datos .= "Ins_Lec_ID\": \"" . $row[Ins_Lec_ID] . "\",\"";
                    $datos .= "Ins_Cur_ID\": \"" . $row[Ins_Cur_ID] . "\",\"";
                    $datos .= "Ins_Niv_ID\": \"" . $row[Ins_Niv_ID] . "\",\"";
                    $datos .= "Ins_Div_ID\": \"" . $row[Ins_Div_ID] . "\",\"";
                    $datos .= "Ins_Usu_ID\": \"" . $row[Ins_Usu_ID] . "\",\"";
                    $datos .= "Ins_Usu_Nom\": \"" . obtenerNombreUsuario($row[Ins_Usu_ID]) . "\",\"";
                    $datos .= "Ins_Fecha\": \"" . cfecha(substr($row[Ins_Fecha],0,10)) . "\",\"";                    
                    $datos .= "Ins_Hora\": \"" . $row[Ins_Hora] . "\"}";
                    echo $datos;
                }
            }

//fin funcion

            function buscarDatosInscripcionLectivoTerciario() {
                //echo "Hola";exit;
                $LegID = $_POST['LegID'];
                $LecID = $_POST['LecID'];
                $CarID = $_POST['CarID'];
                $PlaID = $_POST['PlaID'];

                $sql = "SELECT * FROM Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID AND Ins_Car_ID = $CarID AND Ins_Pla_ID = $PlaID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                    echo "{}";
                } else {
                    $row = mysqli_fetch_array($result);
                    $datos .= "{\"Ins_Leg_ID\": \"" . $row[Ins_Leg_ID] . "\",\"";
                    $datos .= "Ins_Lec_ID\": \"" . $row[Ins_Lec_ID] . "\",\"";
                    $datos .= "Ins_Car_ID\": \"" . $row[Ins_Car_ID] . "\",\"";
                    $datos .= "Ins_Pla_ID\": \"" . $row[Ins_Pla_ID] . "\",\"";
                    $datos .= "Ins_SinCursar\": \"" . $row[Ins_SinCursar] . "\",\"";
                    $datos .= "Ins_Usu_ID\": \"" . $row[Ins_Usu_ID] . "\",\"";
                    $datos .= "Ins_Fecha\": \"" . cfecha($row[Ins_Fecha]) . "\",\"";
                    //Busco si tiene datos del asegurado
                    $sql = "SELECT * FROM InscripcionAsegurado WHERE Ase_Leg_ID = '$LegID' AND Ase_Lec_ID = $LecID AND Ase_Car_ID = $CarID AND Ase_Pla_ID = $PlaID";
                    $result_ase = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    $datos .= "TieneAsegurado\": \"" . mysqli_num_rows($result_ase) . "\",\"";
                    if (mysqli_num_rows($result) > 0) {
                        $row_ase = mysqli_fetch_array($result_ase);
                        $datos .= "Ase_Tutor_Per_ID\": \"" . $row_ase[Ase_Tutor_Per_ID] . "\",\"";
                        $datos .= "Ase_Contrato\": \"" . $row_ase[Ase_Contrato] . "\",\"";
                        $datos .= "Ase_Usu_ID\": \"" . $row_ase[Ase_Usu_ID] . "\",\"";
                        $datos .= "Ase_Fecha\": \"" . cfecha($row_ase[Ase_Fecha]) . "\",\"";
                        $datos .= "Ase_Hora\": \"" . $row_ase[Ase_Hora] . "\",\"";
                    }
                    $datos .= "Ins_Hora\": \"" . $row[Ins_Hora] . "\"}";
                    echo $datos;
                }
            }

//fin funcion

            function buscarDatosTituloTerciario() {
                //echo "Hola";exit;
                $LegID = $_POST['LegID'];
                //$TitID = $_POST['TitID'];
                //$CarID = $_POST['CarID'];
                //$PlaID = $_POST['PlaID'];
                //$sql = "SELECT * FROM TituloAlta WHERE TAl_Leg_ID = '$LegID' AND TAl_Tit_ID = $TitID AND TAl_Car_ID = $CarID AND TAl_Pla_ID = $PlaID";
                $sql = "SELECT * FROM TituloAlta WHERE TAl_Leg_ID = '$LegID' ";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                    echo "{}";
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        $datos .= "{\"TAl_Leg_ID\": \"" . $row[TAl_Leg_ID] . "\",\"";
                        $datos .= "Cantidad\": \"" . mysqli_num_rows($result) . "\",\"";
                        $datos .= "TAl_Tit_ID\": \"" . $row[TAl_Tit_ID] . "\",\"";
                        $datos .= "TAl_Car_ID\": \"" . $row[TAl_Car_ID] . "\",\"";
                        $datos .= "TAl_Pla_ID\": \"" . $row[TAl_Pla_ID] . "\",\"";
                        $datos .= "TAl_Cohorte\": \"" . $row[TAl_Cohorte] . "\",\"";
                        $datos .= "TAl_Fecha_Egreso\": \"" . cfecha($row[TAl_Fecha_Egreso]) . "\",\"";
                        $datos .= "TAl_Fecha\": \"" . cfecha($row[TAl_Fecha]) . "\",\"";
                        $datos .= "TAl_Hora\": \"" . $row[TAl_Hora] . "\"}";
                    }//fin while
                    echo $datos;
                }
            }

//fin funcion

            function buscarDatosCarreraPlan() {
                //echo "Hola";exit;
                $TitID = $_POST['TitID'];

                $sql = "SELECT * FROM
    Titulo
    INNER JOIN Carrera 
        ON (Titulo.Tit_Car_ID = Carrera.Car_ID)
    INNER JOIN Plan 
        ON (Titulo.Tit_Pla_ID = Plan.Pla_ID) WHERE Tit_ID = '$TitID' ";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                    echo "{}";
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        $datos .= "{\"Tit_ID\": \"" . $row[Tit_ID] . "\",\"";
                        $datos .= "Tit_Car_ID\": \"" . $row[Tit_Car_ID] . "\",\"";
                        $datos .= "Tit_Pla_ID\": \"" . $row[Tit_Pla_ID] . "\",\"";
                        $datos .= "Car_Nombre\": \"" . $row[Car_Nombre] . "\",\"";
                        $datos .= "Pla_Nombre\": \"" . $row[Pla_Nombre] . "\",\"";
                        $datos .= "Tit_Hora\": \"" . $row[Tit_Hora] . "\"}";
                    }//fin while
                    echo $datos;
                }
            }

//fin funcion

            function buscarDatosInscripcionLectivoCursillo() {
                //echo "Hola";exit;
                $PerID = $_POST['PerID'];
                $LecID = $_POST['LecID'];

                $sql = "SELECT * FROM CursilloInscripcion WHERE Ins_Per_ID = '$PerID' AND Ins_Lec_ID = $LecID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//No tiene inscripciones hechas
                    echo "{}";
                } else {
                    $row = mysqli_fetch_array($result);
                    $datos .= "{\"Ins_Per_ID\": \"" . $row[Ins_Per_ID] . "\",\"";
                    $datos .= "CantTitulos\": \"" . mysqli_num_rows($result) . "\",\"";
                    $datos .= "Ins_Lec_ID\": \"" . $row[Ins_Lec_ID] . "\",\"";
                    $datos .= "Ins_Tit_ID\": \"" . $row[Ins_Tit_ID] . "\",\"";
                    $datos .= "Ins_Tur_ID\": \"" . $row[Ins_Tur_ID] . "\",\"";
                    $datos .= "Ins_Car_ID\": \"" . $row[Ins_Car_ID] . "\",\"";
                    $datos .= "Ins_Pla_ID\": \"" . $row[Ins_Pla_ID] . "\",\"";
                    $datos .= "Ins_Usu_ID\": \"" . $row[Ins_Usu_ID] . "\",\"";
                    $datos .= "Ins_Fecha\": \"" . cfecha($row[Ins_Fecha]) . "\",\"";
                    //Busco si tiene datos del asegurado
                    $datos .= "Ins_Provisoria\": \"" . $row[Ins_Provisoria] . "\",\"";
                    $datos .= "Ins_Hora\": \"" . $row[Ins_Hora] . "\"}";
                    echo $datos;
                }
            }

//fin funcion

function guardarFamilia($PerID, $PerID_Vinc, $FTiID, $UsuID) {
    //echo "Hola";exit;
    if (empty($PerID)) {
        $DNI = $_POST['DNI'];
        $_SESSION['sesion_ultimoDNI'] = $DNI;
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
    if (mysqli_num_rows($result) == 0) {//no existe
        $sql = "INSERT INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerID', '$PerID_Vinc', '$FTiID', '$UsuID', '$Fecha', '$Hora')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($FTiID==1){  
            $DNI_Padre = $_POST['DNI_Vinc'];          
            gObtenerApellidoNombrePersona($DNI_Padre, $Apellido, $Nombre);
            guardarCuentaUsuario($DNI_Padre, $DNI_Padre, "$Nombre $Apellido");
        }
        if ($FTi_Relaciona > 0) {
            $sql = "INSERT IGNORE INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerID_Vinc', '$PerID', '$FTi_Relaciona', '$UsuID', '$Fecha', '$Hora')";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        }

        echo "Se agregó correctamente la nueva relación familiar";
    } else {
        $sql = "UPDATE Familia SET Fam_FTi_ID = '$FTiID', Fam_Usu_ID = '$UsuID', Fam_Fecha = '$Fecha', Fam_Hora = '$Hora' WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se actualizó la relación familiar.";
    }
}//fin funcion

/*Original
function armarFamilia() {
    //echo "Hola";exit;
    $DNI = $_POST['DNI'];
    $_SESSION['sesion_ultimoDNI'] = $DNI;
    $UsuID = $_POST['UsuID'];
    $Fecha = date("Y-m-d");
    $Hora = date("H:i:s");
    $PerID = gbuscarPerID($DNI);
    echo "$DNI-$PerID";

    $sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' ORDER BY Fam_FTi_ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//existe
        $ip = 1;
        $ih = 1;
        $ihm = 1;
        while ($row = mysqli_fetch_array($result)) {
            $FTiID = $row[Fam_FTi_ID];
            $VinPerID = $row[Fam_Vin_Per_ID];
            if ($FTiID == 1) {//es padre/madre
                $padre[$ip] = $VinPerID;
                $ip++;
            }
            if ($FTiID == 5) {//es hermano
                $hermano[$ih] = $VinPerID;
                $ih++;
            }
            if ($FTiID == 8) {//es hermano mayor
                $hermanomayor[$ihm] = $VinPerID;
                $ihm++;
            }
        }//fin while		
        if (count($padre) > 0 && count($hermano) > 0) {
            //Relacionamos los padres con los hermanos
            foreach ($padre as $p) {
                foreach ($hermano as $h) {
                    guardarFamilia($h, $p, 1, $UsuID);
                    //echo "$h-$p";
                }//fin foreach hermano				
            }//fin foreach padre
        }//fin if cont
        if (count($padre) > 0 && count($hermanomayor) > 0) {
            //Relacionamos los padres con los hermanos mayores
            foreach ($padre as $p) {
                foreach ($hermanomayor as $h) {
                    guardarFamilia($h, $p, 1, $UsuID);
                    //echo "$h-$p";
                }//fin foreach hermano				
            }//fin foreach padre
        }//fin if cont
        if (count($hermano) > 0) {
            //Relacionamos los hermanos entre si
            foreach ($hermano as $h1) {
                foreach ($hermano as $h2) {
                    if ($h1 != $h2) {
                        guardarFamilia($h1, $h2, 5, $UsuID);
                        //echo "$h1-$h2";
                    }
                }//fin foreach hermano2
            }//fin foreach hermano1
        }//fin if cont
        if (count($hermanomayor) > 0) {
            //Relacionamos los hermanos entre si
            foreach ($hermanomayor as $h1) {
                foreach ($hermano as $h2) {
                    if ($h1 != $h2) {
                        guardarFamilia($h2, $h1, 8, $UsuID);
                        //echo "$h1-$h2";
                    }
                }//fin foreach hermano2
            }//fin foreach hermano1
        }//fin if cont
        if (count($padre) > 0) {
            //Relacionamos los padres entre si
            foreach ($padre as $p1) {
                foreach ($padre as $p2) {
                    if ($p1 != $p2) {
                        guardarFamilia($p1, $p2, 9, $UsuID);
                        //echo "$h1-$h2";
                    }
                }//fin foreach hermano2
            }//fin foreach hermano1
        }//fin if cont
    }
}//fin funcion armarFamilia
*/
function armarFamilia() {
    //echo "Hola";exit;
    //Modificado para que arme la familia pero de hermanos solamente
    $DNI = $_POST['DNI'];
    $_SESSION['sesion_ultimoDNI'] = $DNI;
    $UsuID = $_POST['UsuID'];
    $Fecha = date("Y-m-d");
    $Hora = date("H:i:s");
    $PerID = gbuscarPerID($DNI);
    $hermano = array();
    echo "$DNI-$PerID";

    $sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' AND Fam_FTi_ID=3 ORDER BY Fam_FTi_ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//existe
        $ip = 1;
        $ih = 1;
        $ihm = 1;
        while ($row = mysqli_fetch_array($result)) {
            $FTiID = $row['Fam_FTi_ID'];
            $VinPerID = $row['Fam_Vin_Per_ID'];            
            if ($FTiID == 3) {//es hermano
                $hermano[$ih] = $VinPerID;
                $ih++;
            }
        }//fin while        
        
        if (count($hermano) > 0) {
            //Relacionamos los hermanos entre si
            foreach ($hermano as $h1) {
                foreach ($hermano as $h2) {
                    if ($h1 != $h2) {
                        guardarFamilia($h1, $h2, 3, $UsuID);
                        //echo "$h1-$h2";
                    }
                }//fin foreach hermano2
            }//fin foreach hermano1
        }//fin if cont
        
    }
}//fin funcion armarFamilia

            function eliminarFamilia() {
                $DNI = $_POST['DNI'];
                $PerID = gbuscarPerID($DNI);
                $sql = "DELETE FROM Familia WHERE Fam_Per_ID = '$PerID'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				$sql = "DELETE FROM Familia WHERE Fam_Vin_Per_ID = '$PerID'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                echo "Se elimin� la relaci�n familiar de la persona con DNI $PerID.";
            }

//fin function

            function enviarMensajeUsuario() {
                $UsuID = $_POST['UsuID'];
                $Para = $_POST['Para'];
                $msj_opcion = $_POST['msj_opcion'];
                $Asunto = utf8_decode($_POST['Asunto']);
                $Cuerpo = $_POST['Cuerpo'];
                $arreglo = explode("/,/", $Para);
                $MTiID = $_POST['MTiID'];
                if (empty($MTiID))
                    $MTiID = 1;
                $Fecha = date("Y-m-d");
                $Hora = date("H:i:s");
				$sql = "SET NAMES UTF8";
  				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "INSERT INTO Colegio_Mensaje (Men_Titulo, Men_Cuerpo, Men_Fecha, Men_Hora, Men_MTi_ID) VALUES ('$Asunto', '$Cuerpo', '$Fecha', '$Hora', $MTiID)";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "SELECT Men_ID FROM Colegio_Mensaje ORDER BY Men_ID DESC LIMIT 0,1";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $row = mysqli_fetch_array($result);
                $MenID = $row[Men_ID];
                //$arreglo = $Para;
                if (!empty($msj_opcion)) {
                    foreach ($Para as $valor) {
                        $destino = $valor;
                        if (!empty($destino)) {
                            $Para = gbuscarUsuID($destino);
                            $destinatarios .= $destino . ", ";
                            $sql = "INSERT INTO Colegio_MensajeDestino (Des_De_Usu_ID, Des_Para_Usu_ID, Des_Men_ID, Des_Fecha, Des_Hora) VALUES ('$UsuID', '$Para', $MenID, '$Fecha', '$Hora')";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            //enviarCorreoMensajeUsuario($UsuID, $Para, $Asunto, $Cuerpo);

                            // echo $sql;
                        }//fin if
                        //echo $valor;
                    }//fin foreach
                } else {
                    foreach ($arreglo as $valor) {
                        $inicio = strpos($valor, "(");
                        $fin = strpos($valor, ")");
                        $largo = intval(strlen($valor));
                        $destino = substr(trim($valor), $inicio);
                        $destino = str_replace("(", "", $destino);
                        $destino = str_replace(")", "", $destino);
                        $destino = trim($destino);
                        if (!empty($destino)) {
                            $Para = gbuscarUsuID($destino);
                            //$Para = $destino;
                            $destinatarios .= $destino . ", ";
                            $sql = "INSERT INTO Colegio_MensajeDestino (Des_De_Usu_ID, Des_Para_Usu_ID, Des_Men_ID, Des_Fecha, Des_Hora) VALUES ('$UsuID', '$Para', $MenID, '$Fecha', '$Hora')";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            //enviarCorreoMensajeUsuario($UsuID, $Para, $Asunto, $Cuerpo);
                            //echo $sql;
                        }
                    }//fin foreach//*/
                }//fin if
                //echo $Para;
                ?>
                <div class="borde_aviso"><span class="texto">Se envi&oacute; correctamente el mensaje a los siguientes destinatarios:  <strong><?php echo $destinatarios; ?></strong>.</span></div>
                <?php
            }

//fin function

            function mostrarMensajeUsuario() {
                $MenID = $_POST['MenID'];
                $UsuID = $_POST['UsuID'];
                $Leido = $_POST['Leido'];
				$sql = "SET NAMES UTF8";
  				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "SELECT * FROM
    Colegio_MensajeDestino
    INNER JOIN Colegio_Mensaje 
        ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID)
    INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID) WHERE Men_ID = $MenID AND Des_Para_Usu_ID = '$UsuID'";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $row = mysqli_fetch_array($result);
                if (!empty($Leido)) {
                    $sql = "UPDATE Colegio_MensajeDestino SET Des_Leido=1 WHERE Des_ID = $row[Des_ID]";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    //echo $sql;
                }//fin if
                ?>    
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
                    <tr>
                        <td width="19"><img src="imagenes/recuadro_mensajes/bubble-1.png" width="19" height="15"></td>
                        <td class="borde_mensaje_top"></td>
                        <td width="19"><img src="imagenes/recuadro_mensajes/bubble-3.png" width="19" height="15"></td>
                    </tr>
                    <tr>
                        <td class="borde_mensaje_left"></td>
                        <td bgcolor="#FFFFFF">De: <strong><?php echo utf8_decode($row[Usu_Nombre]); ?></strong><br />
                            Asunto: <strong><?php echo utf8_decode($row[Men_Titulo]); ?></strong><br />
                            Enviado: <strong><?php echo cfecha($row[Des_Fecha]) . " a las " . $row[Des_Hora]; ?></strong><hr />
                            <?php echo utf8_decode($row[Men_Cuerpo]); ?></td>
                        <td class="borde_mensaje_rigth"></td>
                    </tr>
                    <tr>
                        <td><img src="imagenes/recuadro_mensajes/bubble-6.png" width="19" height="29"></td>
                        <td class="borde_mensaje_botton"></td>
                        <td><img src="imagenes/recuadro_mensajes/bubble-8.png" width="19" height="29"></td>
                    </tr>
                </table>
                <?php
            }

//fin function

            function cargarMensajeParaIE() {
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
            }

//fin function

function mostrarDetalleCuota() {
    $DNI = $_POST['DNI'];
    $_SESSION['sesion_ultimoDNI'] = $DNI;
    $datosCuota = $_POST['Cuota'];
    list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	
    $sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM
    CuotaPersona
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
		$importe = $row[Cuo_Importe];
		$fechaCuota = cfecha($row[Cuo_1er_Vencimiento]);
		$fechaCuota2 = $row[Cuo_1er_Vencimiento];
		$dia15Cuota = substr($fechaCuota2,-2);
		$fechaHoy = date("d-m-Y");
		$fechaHoy2 = date("Y-m-d");
		$dias_vencidos = restarFecha($fechaCuota, $fechaHoy);					
		$mesesAtrazo = 0;
		$recargo = 0;
		if ( $dias_vencidos > 0 ){
			$mesesAtrazo = restarMeses($fechaCuota2, $fechaHoy2);
			$dia15 = substr($fechaHoy2,-2);
			if ($mesesAtrazo==0 && $dia15>$dia15Cuota) $mesesAtrazo=1;elseif ($dia15>$dia15Cuota) $mesesAtrazo++;
			
			if ($mesesAtrazo==1){
				$recargo=$row[Cuo_1er_Recargo];
			}else{
				$mesesAtrazo--;
				$recargo=$row[Cuo_1er_Recargo] + ($row[Cuo_Recargo_Mensual] * $mesesAtrazo);	
			}
			//$recargo=$row[Cuo_Recargo_Mensual] * $mesesAtrazo;
			
			$importe += $recargo;
		}else{
			$dias_vencidos = 0;
		}
		$importe = intval($importe);
		recalcularImporteCuota($datosCuota, $debe);
        $recargo = obtenerRecargoCuota($Cuo_Per_ID, $datosCuota);
		$importeAbonado = intval($row[Cuo_Importe]) - $debe;
        ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td>Tipo de Cuota:</td>
                <td colspan="5"><strong><?php echo $row[CTi_Nombre]; ?></strong></td>
            </tr>
            <tr>
                <td>Vigencia:</td>
                <td colspan="5"><strong><?php echo buscarMes($row[Cuo_Mes]) . "/" . $row[Cuo_Anio]; ?></strong></td>
            </tr>
            <tr>
                <td>1º Vencimiento:</td>
                <td colspan="5"><strong><?php echo cfecha($row[Cuo_1er_Vencimiento]); ?></strong></td>
            </tr>
            <tr>
                <td>2º Vencimiento:</td>
                <td colspan="5"><strong><?php echo cfecha($row[Cuo_2do_Vencimiento]); ?></strong></td>
            </tr>
            <tr>
                <td>1º Recargo:</td>
                <td colspan="5"><strong><?php echo "$".intval($row[Cuo_1er_Recargo]); ?></strong></td>
            </tr>
            <tr>
                <td>2º Recargo:</td>
                <td colspan="5"><strong><?php echo "$".intval($row[Cuo_2do_Recargo]); ?></strong></td>
            </tr>
            <tr>
                <td>Recargo Mensual:</td>
                <td colspan="5"><strong><?php echo "$".intval($row[Cuo_Recargo_Mensual]); ?></strong></td>
            </tr>            
            <tr>
                <td align="center"> Importe:</td>
                <td align="center">&nbsp;</td>
                <td align="center">Recargo:</td>
                <td align="center">&nbsp;</td>
                <td align="center">Días vencidos:</td>
                <td align="center">Abonado:</td>
            </tr>
            <tr>
                <td align="center"><strong>$<?php echo intval($row[Cuo_Importe]); ?></strong></td>
                <td align="center">&nbsp;</td>
                <td align="center"><strong>$<?php echo intval($recargo); ?></strong></td>
                <td align="center">&nbsp;</td>
                <td align="center"><strong><?php echo intval($dias_vencidos); ?></strong></td>
                <td align="center"><strong>$<?php echo intval($importeAbonado); ?></strong></td>
            </tr>
        </table>

        <?php
    }//fin del if
}//fin function

            function generarLegajoColegio() {
                $sql = "SELECT MAX(CONVERT(Leg_Numero, SIGNED)) AS Maximo FROM Legajo WHERE Leg_Colegio = 1;";
                //echo $sql;
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

                $row = mysqli_fetch_array($result);
                $Legajo = $row[Maximo] + 1;
                echo $Legajo;
            }

//fin function

            function generarLegajoTerciario() {
                $sql = "SELECT MAX(CONVERT(Leg_Numero, SIGNED)) AS Maximo FROM Legajo WHERE Leg_StaMaria = 1;";
                //echo $sql;
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

                $row = mysqli_fetch_array($result);
                $Legajo = $row[Maximo] + 1;
                echo $Legajo;
            }

//fin function

            function cargarCuentaUsuarioAlumno() {
                $PerID = $_POST['PerID'];
                $sql = "SELECT * FROM Persona WHERE Per_ID = $PerID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                //echo "Hol$LegID la";
                $row = mysqli_fetch_array($result);
                $sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$row[Per_DNI]'";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    //echo $sql;
                    $row = mysqli_fetch_array($result);
                    if ($row[Usu_Clave] == md5($row[Usu_Nombre]))
                        $clave = $row[Usu_Nombre];
                    else
                        $clave = "***********";
                    //echo $clave;
                    echo $row[Usu_Nombre].";".$clave;
                }else {
                    echo "Sin Usuario;Sin Clave";
                }//fin if
            }

//fin function

            function cargarCuentaUsuarioDocente() {
                $PerID = $_POST['PerID'];
                $sql = "SELECT * FROM Persona WHERE Per_ID = $PerID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                //echo "Hol$LegID la";
                $row = mysqli_fetch_array($result);
                $sql = "SELECT * FROM Usuario WHERE Usu_Nombre = '$row[Per_DNI]'";
                //echo $sql;
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    //echo $sql;
                    $row = mysqli_fetch_array($result);
                    if ($row[Usu_Clave] == md5($row[Usu_Nombre]))
                        $clave = $row[Usu_Nombre];
                    else
                        $clave = "***********";
                    //echo $clave;
                    echo $row[Usu_Nombre] . ";" . $clave;
                }else {
                    echo "Sin Usuario;Sin Clave";
                }//fin if
            }

//fin function

            function cargarFamiliaresSeguroAlumno() {
                $PerID = $_POST['PerID'];
                $sql = "SELECT * FROM     Familia
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID)
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
WHERE (Fam_Per_ID = $PerID AND FamiliaTipo.FTi_ID <> 2 AND FamiliaTipo.FTi_ID <> 5 AND FamiliaTipo.FTi_ID <> 8) ORDER BY FTi_ID, Per_Apellido, Per_Nombre";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    //echo "<select name='$nombre' id='$nombre'>";
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='$row[Per_ID]'>($row[FTi_Nombre]) $row[Per_Apellido], $row[Per_Nombre]</option>";
                    }//fin del while
                    //echo "</select>";
                } else {
                    echo "<option value='-1'>NO HAY FAMILIARES VINCULADOS</option>";
                }
            }

//fin function

            function cargarBeneficiosAlumno() {
                //global $gLectivoSIUCC;
                $PerID = $_POST['PerID'];
                $LecID = $_POST['LecID'];
                //$LecID = $gLectivoSIUCC[$LecID] - 1;
                $sql = "SELECT DISTINCTROW Ben_ID, Ben_Nombre FROM
    CuotaPersona
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID) WHERE Cuo_Per_ID = $PerID AND Cuo_Lec_ID = $LecID";
//echo $sql;
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    //echo "<select name='$nombre' id='$nombre'>";
                    //echo "<option value='-1'>NO APLICAR BENEFICIOS</option>";
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='$row[Ben_ID]'>$row[Ben_Nombre]</option>";
                    }//fin del while
                    //echo "</select>";
                } else {
                    echo "<option value='-1'>NO HAY BENEFICIOS ASIGNADOS</option>";
                }
            }

//fin function

            function verificarBeneficiosAlumno() {
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
                if (mysqli_num_rows($result) > 0) {
                    $sql = "SELECT Persona.Per_DNI, Familia.Fam_FTi_ID FROM Familia
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) WHERE Per_DNI = $DNI;";
                    $result = consulta_mysql($sql);
                    if (mysqli_num_rows($result) == 0) {
                        echo "No tiene hermanos vinculados. Vincule sus hermanos antes de guardar.";
                    } else {
                        //echo "Tiene hermanos vinculados";
                        echo '';
                    }
                } else {
                    //echo "No tiene beneficio familiar";
                    echo '';
                }
            }            

//fin function
            function validarContratoGuardado() {

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
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    //El contrato le pertenece a otra persona
                    echo "El contrato ingresado le pertenece al alumno <strong>$row[Per_Apellido], $row[Per_Nombre]</strong> con <strong>DNI $row[Per_DNI]</strong> y fue cargado el d�a <strong>" . cfecha($row[Ase_Fecha]) . "</strong> a las $row[Ase_Hora] por el usuario <strong>$row[Usu_Persona]</strong>. <br />Por favor ingrese otro n�mero de contrato.";
                } else {
                    echo ''; //El contrato ingresado no existe
                }
            }

//fin function

            function validarContratoGuardadoTerciario() {

                $Contrato = $_POST['Contrato'];
                $LegID = $_POST['LegID'];
                $LecID = $_POST['LecID'];
                $sql = "SELECT
    Ase_Contrato
    , Ase_Fecha
    , Ase_Hora
    , Ase_Usu_ID
    , Persona.Per_DNI
    , Persona.Per_Apellido
    , Persona.Per_Nombre
	, Usu_Persona
    FROM InscripcionAsegurado
    INNER JOIN Inscripcion 
        ON (InscripcionAsegurado.Ase_Leg_ID = Inscripcion.Ins_Leg_ID) AND (InscripcionAsegurado.Ase_Car_ID = Inscripcion.Ins_Car_ID) AND (InscripcionAsegurado.Ase_Pla_ID = Inscripcion.Ins_Pla_ID) AND (InscripcionAsegurado.Ase_Lec_ID = Inscripcion.Ins_Lec_ID)
    , Legajo
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID) AND (Legajo.Leg_ID = Inscripcion.Ins_Leg_ID)
    INNER JOIN Usuario 
        ON (InscripcionAsegurado.Ase_Usu_ID = Usuario.Usu_ID)
WHERE (Ase_Leg_ID <> $LegID AND Ase_Lec_ID = $LecID AND Ase_Contrato = '$Contrato');";

                $result = consulta_mysql($sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    //El contrato le pertenece a otra persona
                    echo "El contrato ingresado le pertenece al alumno <strong>$row[Per_Apellido], $row[Per_Nombre]</strong> con <strong>DNI $row[Per_DNI]</strong> y fue cargado el d�a <strong>" . cfecha($row[Ase_Fecha]) . "</strong> a las $row[Ase_Hora] por el usuario <strong>$row[Usu_Persona]</strong>. <br />Por favor ingrese otro n�mero de contrato.";
                } else {
                    echo ''; //El contrato ingresado no existe
                }
            }

//fin function

            function validarContratoGuardadoCursillo() {

                $Contrato = $_POST['Contrato'];
                $PerID = $_POST['PerID'];
                $LecID = $_POST['LecID'];
                $sql = "SELECT * CursilloInscripcion
    INNER JOIN Persona 
        ON (CursilloInscripcion.Ins_Per_ID = Persona.Per_ID)
	 INNER JOIN Usuario 
        ON (CursilloInscripcion.Ins_Usu_ID = Usuario.Usu_ID)
WHERE (Ins_Per_ID <> $PerID AND Ins_Lec_ID = $LecID AND Ins_Contrato = '$Contrato');";

                $result = consulta_mysql($sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    //El contrato le pertenece a otra persona
                    echo "El contrato ingresado le pertenece al alumno <strong>$row[Per_Apellido], $row[Per_Nombre]</strong> con <strong>DNI $row[Per_DNI]</strong> y fue cargado el d�a <strong>" . cfecha($row[Ins_Fecha]) . "</strong> a las $row[Ins_Hora] por el usuario <strong>$row[Usu_Persona]</strong>. <br />Por favor ingrese otro n�mero de contrato.";
                } else {
                    echo ''; //El contrato ingresado no existe
                }
            }

//fin function

function guardarInscripcionLectivo() {

    $LegID = $_POST['LegID'];
    $LecID = $_POST['LecID'];
    $CurID = $_POST['CurID'];
    $NivID = $_POST['NivID'];
    $DivID = $_POST['DivID'];
    $PerID = $_POST['PerID'];
    //$ArTID = $_POST['ArTID'];
    $DNI = $_POST['DNI'];
    $_SESSION['sesion_ultimoDNI'] = $DNI;
    //$Contrato = $_POST['Contrato'];
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

    //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
    $sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe, actualizamos la inscripcion
        $sql = "UPDATE Colegio_Inscripcion SET Ins_Cur_ID = $CurID, Ins_Niv_ID = $NivID, Ins_Div_ID = $DivID, Ins_Usu_ID = $UsuID, Ins_Fecha = '$Fecha', Ins_Hora = '$Hora' WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
        $mensaje = "Se actualizó  la inscripción del alumno.";
    } else {
        $sql = "INSERT INTO Colegio_Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora) VALUES ($LegID, $LecID, $CurID, $NivID, $DivID, $UsuID, '$Fecha', '$Hora')";
        $mensaje = "Se agregó correctamente la inscripción del alumno.";
    }
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    guardarClaseAlumnoLegajo($LegID, $LecID);

	guardarAsignacionCuota($PerID, $LecID, $NivID);
}

//fin funcion

            function guardarInscripcionLectivoTerciario() {

                $LegID = $_POST['LegID'];
                $LecID = $_POST['LecID'];
                $CarID = $_POST['CarID'];
                $TitID = $_POST['TitID'];
                $PlaID = $_POST['PlaID'];
                $VinID = $_POST['VinID'];
                $CurID = $_POST['CurID'];
                $Cohorte = $_POST['Cohorte'];
                if (empty($Cohorte))
                    $Cohorte = date("Y");
                $ArTID = 1;
                $DNI = $_POST['DNI'];
                $_SESSION['sesion_ultimoDNI'] = $DNI;
                $Contrato = $_POST['Contrato'];
                obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

                //Buscamos si el alumno tiene una carrera activa
                $sql = "SELECT * FROM TituloAlta WHERE TAl_Leg_ID = '$LegID' AND TAl_Tit_ID = $TitID AND TAl_Car_ID = $CarID AND TAl_Pla_ID = $PlaID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//guardamos si no existe
                    $sql = "INSERT INTO TituloAlta (TAl_Leg_ID, TAl_Tit_ID, TAl_Car_ID, TAl_Pla_ID, TAl_Cohorte, TAl_Fecha, TAl_Hora) VALUES ($LegID, $TitID, $CarID, $PlaID, '$Cohorte', '$Fecha', '$Hora')";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    $mensaje = "Se agreg� correctamente el ALTA del t�tulo.";
                }


                //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
                $sql = "SELECT * FROM Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID AND Ins_Car_ID = $CarID AND Ins_Pla_ID = $PlaID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//guardamos si no existe
                    if ($CurID == -1) {
                        $valorSinCursar = 1;
                    } else {
                        $valorSinCursar = 0;
                    }
                    $sql = "INSERT INTO Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Car_ID, Ins_Pla_ID, Ins_SinCursar, Ins_Fecha, Ins_Hora) VALUES ($LegID, $LecID, $CarID, $PlaID, $valorSinCursar, '$Fecha', '$Hora')";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    $mensaje = "Se agreg� correctamente la inscripci�n del alumno al Ciclo Lectivo.";
                }


                //Buscamos si el alumno ya tiene cargado los datos del asegurado para el ciclo lectivo
                $sql = "SELECT * FROM InscripcionAsegurado WHERE Ase_Leg_ID = '$LegID' AND Ase_Lec_ID = $LecID AND Ase_Car_ID = $CarID AND Ase_Pla_ID = $PlaID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {//ya existe, actualizamos la inscripcion
                    $sql = "UPDATE InscripcionAsegurado SET Ase_Tutor_Per_ID = $VinID, Ase_Contrato = $Contrato, Ase_Usu_ID = $UsuID, Ase_Fecha = '$Fecha', Ase_Hora = '$Hora' WHERE Ase_Leg_ID = '$LegID' AND Ase_Lec_ID = $LecID AND Ase_Car_ID = $CarID AND Ase_Pla_ID = $PlaID";
                    $mensaje .= "<br />Se actualiz�  los datos del asegurado del alumno.";
                } else {
                    $sql = "INSERT INTO InscripcionAsegurado (Ase_Leg_ID, Ase_Lec_ID, Ase_Car_ID, Ase_Pla_ID, Ase_Tutor_Per_ID, Ase_Contrato, Ase_Usu_ID, Ase_Fecha, Ase_Hora) VALUES ($LegID, $LecID, $CarID, $PlaID, $VinID, $Contrato, $UsuID, '$Fecha', '$Hora')";
                    $mensaje .= "<br />Se agreg� correctamente los datos del asegurado del alumno.";
                }
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                guardarRol($DNI, 9, true); //9 es el rol de Alumno Santa Maria
                echo $mensaje . "<br />";
                echo asignarCuotasAlumnoSantaMaria($DNI, $LecID, $CurID, $CarID, $ArTID);
            }

//fin funcion

            function guardarInscripcionMateriaTerciario() {

                $LegID = $_POST['LegID'];
                $LecID = $_POST['LecID'];
                $CarID = $_POST['CarID'];
                $PlaID = $_POST['PlaID'];
                $MatID = $_POST['MatID'];
                $PrdID = $_POST['PrdID'];
                $ConID = $_POST['ConID'];
                $DivID = $_POST['DivID'];
                $MMoID = 1;
                obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

                //Buscamos si el alumno tiene una carrera activa
                $sql = "SELECT * FROM MateriaLectivo WHERE MaL_Lec_ID = '$LecID' AND MaL_Mat_ID = $MatID AND MaL_Car_ID = $CarID AND MaL_Pla_ID = $PlaID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//guardamos si no existe
                    $sql = "INSERT INTO MateriaLectivo (MaL_Lec_ID, MaL_Mat_ID, MaL_Car_ID, MaL_Pla_ID, MaL_MMo_ID, MaL_Fecha, MaL_Hora) VALUES ($LecID, $MatID, $CarID, $PlaID, '$MMoID', '$Fecha', '$Hora')";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    //echo "$sql<br />";
                    //$mensaje = "Se agreg� correctamente la materia al Ciclo Lectivo.";
                }


                //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
                $sql = "SELECT * FROM InscripcionMateria WHERE IMa_Leg_ID = '$LegID' AND IMa_Lec_ID = $LecID AND IMa_Car_ID = $CarID AND IMa_Pla_ID = $PlaID AND IMa_Mat_ID = $MatID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//guardamos si no existe
                    $sql = "INSERT INTO InscripcionMateria (IMa_Leg_ID, IMa_Lec_ID, IMa_Car_ID, IMa_Pla_ID, IMa_Mat_ID, IMa_Div_ID, IMa_Prd_ID, IMa_ConI_ID, IMa_ConS_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora) VALUES ($LegID, $LecID, $CarID, $PlaID, $MatID, $DivID, $PrdID, $ConID, 0, $UsuID, '$Fecha', '$Hora')";
                    //echo "$sql<br />";
                } else {
                    $sql = "UPDATE InscripcionMateria SET IMa_Div_ID = $DivID, IMa_Prd_ID = $PrdID, IMa_ConI_ID = $ConID, IMa_Usu_ID = $UsuID, IMa_Fecha = '$Fecha', IMa_Hora = '$Hora' WHERE IMa_Leg_ID = '$LegID' AND IMa_Lec_ID = $LecID AND IMa_Car_ID = $CarID AND IMa_Pla_ID = $PlaID AND IMa_Mat_ID = $MatID";
                }
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);


                echo $mensaje . "<br />";
            }

//fin funcion

            function guardarInscripcionLectivoCursillo() {

                $PerID = $_POST['PerID'];
                $LecID = $_POST['LecID'];
                $TurID = $_POST['TurID'];
                $TitID = $_POST['TitID'];
                $DNI = $_POST['DNI'];
                $_SESSION['sesion_ultimoDNI'] = $DNI;
                obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
                obtenerDatosTitulo($TitID, $CarID, $PlaID);

                //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
                $sql = "SELECT * FROM CursilloInscripcion WHERE Ins_Per_ID = '$PerID' AND Ins_Lec_ID = $LecID AND Ins_Tit_ID = $TitID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {//ya existe, actualizamos la inscripcion
                    $sql = "UPDATE CursilloInscripcion SET Ins_Tur_ID = $TurID, Ins_Usu_ID = $UsuID, Ins_Fecha = '$Fecha', Ins_Hora = '$Hora' WHERE Ins_Per_ID = '$PerID' AND Ins_Lec_ID = $LecID AND Ins_Tit_ID = $TitID";
                    $mensaje = "Se actualiz�  la inscripci�n del alumno.";
                } else {
                    $sql = "INSERT INTO CursilloInscripcion (Ins_Per_ID, Ins_Lec_ID, Ins_Tur_ID, Ins_Tit_ID, Ins_Car_ID, Ins_Pla_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora, Ins_Provisoria) VALUES ($PerID, $LecID, $TurID, $TitID, $CarID, $PlaID, $UsuID, '$Fecha', '$Hora', 1)";
                    $mensaje = "Se agreg� correctamente la inscripci�n del alumno.";
                }
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

                echo $mensaje . "<br />";
                $CurID = 10; //primer a�o en UCCDigital
                $ArTID = -1;
                echo asignarCuotasAlumnoTerciario($DNI, $LecID, $CurID, $ArTID, $CarID);
            }

//fin funcion

            function mostrarConstanciaInscripcion() {

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
                if (mysqli_num_rows($result) > 0) {//ya existe, mostramos la inscripcion
                    $row = mysqli_fetch_array($result);
                    ?>
                    <link href="css/general.css" rel="stylesheet" type="text/css">
                    <table width="550px" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
                        <tr>
                            <td colspan="2" align="center"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td width="50%"><div align="center"><img src="logos/logo_college.png" width="90" height="106" /></div></td>
                                        <td><div align="center"><img src="logos/logo_Giteco.png" width="161" height="34" /></div></td>
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
                            <td colspan="2" align="left"><span class="titulo_noticia">Legajo N� <?php echo $row[Leg_Numero]; ?></span><br>      
                                <span class="titulo_noticia">Contrato N� <?php echo $row[Ase_Contrato]; ?></span><br>
                                <span class="texto">Fecha Inscripci�n: <?php echo cfecha($row[Ins_Fecha]); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr size="1" color="#999999"  /></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><span class="titulo_noticia">Datos del Alumno</span></td>
                        </tr>
                        <tr>
                            <td width="72%" colspan="2">
                                <p><span class="texto">Nombre y Apellido: <strong><?php echo $row[Nombre] . " " . $row[Apellido]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span class="texto">D.N.I.: <strong><?php echo $row[DNI]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span class="texto">Nivel: <strong><?php echo $row[Niv_Nombre]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span class="texto">Ciclo Lectivo: <strong><?php echo $row[Lec_Nombre]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span class="texto">Curso: <strong><?php echo $row[Cur_Nombre]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span class="texto">Divis�n: <strong><?php echo $row[Div_Nombre]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr size="1" color="#999999"></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><span class="titulo_noticia">Datos del Asegurado</span></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span class="texto">Nombre y Apellido: <strong><?php echo $row[Per_Nombre] . " " . $row[Per_Apellido]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span class="texto"><?php echo $row[Doc_Nombre]; ?>: <strong><?php echo $row[Per_DNI]; ?></strong></span></p>
                                </blockquote></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font:'Times New Roman', Times, serif; font-size:10px">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="font:'Times New Roman', Times, serif; font-size:10px">Nota: La inscripci&oacute;n ser&aacute; definitiva cuando se acredite el pago de la matr&iacute;cula. La divisi&oacute;n est&aacute; sujeta a modificaci&oacute;n en funci&oacute;n del cupo.</td>
                            <td style="font:'Times New Roman', Times, serif; font-size:10px"><?php echo date("d/m/Y"); ?></td>
                        </tr>
                    </table>

                    <?php
                } else {
                    echo "El alumno todavia no se encuentra inscripto en el Ciclo Lectivo seleccionado.";
                }
            }

//fin funcion

            function mostrarConstanciaInscripcionCursillo() {

                $PerID = $_POST['PerID'];
                $LecID = $_POST['LecID'];
                $TitID = $_POST['TitID'];

                //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
                $sql = "SELECT
    CursilloInscripcion.Ins_Provisoria
    , Turno.Tur_Nombre
    , Persona.Per_DNI
    , Persona.Per_Apellido
    , Persona.Per_Nombre
    , Persona.Per_Sexo
	, Lec_Nombre
	, Car_Nombre
	, Pla_Nombre
	, Ins_Fecha
FROM
    CursilloInscripcion
    INNER JOIN Turno 
        ON (CursilloInscripcion.Ins_Tur_ID = Turno.Tur_ID)
    INNER JOIN Persona 
        ON (CursilloInscripcion.Ins_Per_ID = Persona.Per_ID)
    INNER JOIN Carrera 
        ON (CursilloInscripcion.Ins_Car_ID = Carrera.Car_ID)
    INNER JOIN Plan 
        ON (CursilloInscripcion.Ins_Pla_ID = Plan.Pla_ID)
    INNER JOIN Lectivo 
        ON (CursilloInscripcion.Ins_Lec_ID = Lectivo.Lec_ID)
WHERE Ins_Lec_ID = $LecID AND Ins_Per_ID = $PerID"; // AND Ins_Tit_ID = $TitID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {//ya existe, mostramos la inscripcion
                    ?>
                    <link href="css/general.css" rel="stylesheet" type="text/css">
                    <table width="550px" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
                        <tr>
                            <td colspan="2" align="center"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td width="50%"><div align="center"><img src="logos/logo_stamaria.jpg" width="90" height="106" /></div></td>
                                        <td><div align="center"><img src="logos/logo_Giteco.png" width="161" height="34" /></div></td>
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
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td colspan="2" align="left"><span class="texto">Fecha Inscripci�n: <?php echo cfecha($row[Ins_Fecha]); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr size="1" color="#999999"  /></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"><span class="titulo_noticia">Datos del Alumno</span></td>
                            </tr>
                            <tr>
                                <td width="72%" colspan="2">
                                    <p><span class="texto">Nombre y Apellido: <strong><?php echo $row[Per_Nombre] . " " . $row[Per_Apellido]; ?></strong></span></p>
                                    </blockquote></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p><span class="texto">D.N.I.: <strong><?php echo $row[Per_DNI]; ?></strong></span></p>
                                    </blockquote></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p><span class="texto">Carrera: <strong><?php echo $row[Car_Nombre]; ?></strong></span></p>
                                    </blockquote></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p><span class="texto">Ciclo Lectivo: <strong><?php echo $row[Lec_Nombre]; ?></strong></span></p>
                                    </blockquote></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p><span class="texto">Turno: <strong><?php echo $row[Tur_Nombre]; ?></strong></span></p>
                                    </blockquote></td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr size="1" color="#999999"></td>
                            </tr>
                            <?php
                        }//fin while
                        ?>
                        <tr>
                            <td colspan="2" align="center"><span class="titulo_noticia"><br />
                                    Requisitos presentados</span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><table width="95%" border="0" align="center" cellpadding="1" cellspacing="1">
                                    <tr class="fila_titulo">
                                        <th scope="col">#</th>
                                        <th scope="col">Requisito</th>
                                        <th scope="col">Constancia</th>
                                        <th scope="col">Definitivo</th>
                                        <th scope="col">Fecha</th>
                                    </tr>
                                    <?php
                                    $sql = "SELECT * FROM
    RequisitoPresentado
    INNER JOIN Requisito 
        ON (RequisitoPresentado.Pre_Req_ID = Requisito.Req_ID) AND (RequisitoPresentado.Pre_Niv_ID = Requisito.Req_Niv_ID)
WHERE (Requisito.Req_Niv_ID = 4
    AND RequisitoPresentado.Pre_Per_ID = $PerID);";
                                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                    while ($row = mysqli_fetch_array($result)) {
                                        $i++;
                                        if (($i % 2) == 0)
                                            $clase = "fila";
                                        else
                                            $clase = "fila2";
                                        ?>
                                        <tr class="<?php echo $clase; ?>">
                                            <td><?php echo $row[Req_ID]; ?></td>
                                            <td><?php
                            echo $row[Req_Nombre];
                            if ($row[Req_Obligatorio] == 1 && $row[Req_Inscripcion] == 1)
                                echo " *";
                                        ?></td>
                                            <td align="center"><?php if ($row[Pre_Constancia] == 1) echo "X"; ?></td>
                                            <td align="center"><?php if ($row[Pre_Presento] == 1) echo "X"; ?></td>
                                            <td align="center"><?php echo cfecha($row[Pre_Fecha]); ?></td>
                                        </tr>
                                        <?php
                                    }//fin while
                                    ?>
                                </table></td>
                        </tr>
                        <tr>
                            <td style="font:'Times New Roman', Times, serif; font-size:10px"><br />
                                Nota: La inscripci&oacute;n ser&aacute; definitiva cuando se acredite el pago de la matr&iacute;cula. El turno est&aacute; sujeto a modificaci&oacute;n en funci&oacute;n del cupo. * Son requisitos obligatorios para la inscripci&oacute;n.</td>
                            <td style="font:'Times New Roman', Times, serif; font-size:10px"><?php echo date("d/m/Y"); ?></td>
                        </tr>
                    </table>

                    <?php
                }else {
                    echo "El alumno todavia no se encuentra inscripto en el Ciclo Lectivo seleccionado.";
                }
            }

//fin funcion

function cambiarDivisionAlumnos() {

    $LegID = $_POST['LegID'];
    $LecID = $_POST['LecID'];
    $CurID = $_POST['CurID'];
    $NivID = $_POST['NivID'];
    $DivID = $_POST['DivID'];
    $Inscriptos = $_POST['Inscriptos'];
    $NivNuevoID = $_POST['NivNuevoID'];
	$CurNuevoID = $_POST['CurNuevoID'];
    $DivNuevoID = $_POST['DivNuevoID'];
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
    $i = 0;
    $j = 0;
    $bEntrar = false;
    foreach ($Inscriptos as $LegID) {
        $bEntrar = true;

        //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
        $sql = "SELECT * FROM Colegio_Inscripcion WHERE (Ins_Lec_ID = $LecID AND Ins_Leg_ID = $LegID";
        if ($CurID != 999999)
            $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
        if ($NivID != 999999)
            $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
        if ($DivID != 999999)
            $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
        $sql.=");";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        //echo "$sql - Entre";
        if (mysqli_num_rows($result) > 0) {//ya existe, actualizamos la inscripcion
            $row = mysqli_fetch_array($result);
            $sql = "UPDATE Colegio_Inscripcion SET Ins_Niv_ID = $NivNuevoID, Ins_Cur_ID = $CurNuevoID, Ins_Div_ID = $DivNuevoID, Ins_Usu_ID = $UsuID, Ins_Fecha = '$Fecha', Ins_Hora = '$Hora' WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID AND Ins_Cur_ID =$row[Ins_Cur_ID] AND Ins_Niv_ID = $row[Ins_Niv_ID] AND Ins_Div_ID = $row[Ins_Div_ID]";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            borrarInscripcionClase($LecID, $LegID, $row[Ins_Niv_ID], $row[Ins_Cur_ID], $row[Ins_Div_ID]);
            agregarInscripcionClase($LecID, $LegID, $NivNuevoID, $CurNuevoID, $DivNuevoID);
            //echo "$sql<br />";
            $i++;
        } else {
            $j++;
        }//*/
    }//fin foreach//*/
    if ($i > 0) {
        echo "Se cambiaron de divisón a $i alumnos.<br />";
    }
    if ($j > 0) {
        echo "No se pudo cambiar de divisón a $j alumnos porque el curso y la divisón de origen no coinciden.";
    }
    if (!$bEntrar)
        echo "Seleccione los alumnos que desea cambiar de división.";
}//fin funcion

function agregarInscripcionClase($LecID, $LegID, $NivID, $CurID, $DivID){
$fecha = date("Y-m-d");
$hora = date("H:i:s");
    
    //echo "Hola";exit;
    $sql = "SELECT * FROM Colegio_Clase WHERE Cla_Lec_ID = $LecID AND Cla_Niv_ID = $NivID AND Cla_Cur_ID = $CurID AND Cla_Div_ID = $DivID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    if (mysqli_num_rows($result)>0){
        //echo "$sql<br />";      
        while ($row = mysqli_fetch_array($result)){
        
                $sql = "INSERT INTO Colegio_InscripcionClase (IMa_Lec_ID, IMa_Leg_ID, IMa_Cla_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora) VALUES ($LecID, $LegID, $row[Cla_ID], 1, '$fecha', '$hora')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                //echo "$sql<br />";
                //echo "Se agregaron las inscripciones de la clase.<br />";

            ///
        }//fin while///
    }//fin if

}//fin funcion
function borrarInscripcionClase($LecID, $LegID, $NivID, $CurID, $DivID){
$fecha = date("Y-m-d");
$hora = date("H:i:s");
    
    //echo "Hola";exit;
    $sql = "SELECT * FROM Colegio_Clase WHERE Cla_Lec_ID = $LecID AND Cla_Niv_ID = $NivID AND Cla_Cur_ID = $CurID AND Cla_Div_ID = $DivID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    if (mysqli_num_rows($result)>0){
        //echo "$sql<br />";      
        while ($row = mysqli_fetch_array($result)){
        
                $sql = "DELETE FROM Colegio_InscripcionClase WHERE IMa_Cla_ID = $row[Cla_ID] AND IMa_Lec_ID = $LecID AND IMa_Leg_ID = $LegID";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);                
                //echo "$sql<br />";
                //echo "Se eliminaron las inscripciones de la clase.<br />";

            ///
        }//fin while///
    }//fin if

}//fin funcion

            function VerUsuarioConectado() {
                $UsuID = $_SESSION['sesion_UsuID'];
                $Fecha = date("Y-m-d");
                $Hora = date("H:i:s");
                if (!empty($UsuID)) {
                    $sql = "SELECT DISTINCTROW Usu_Persona FROM
    AccesoOpcion
    INNER JOIN Login 
        ON (AccesoOpcion.Acc_Log_ID = Login.Log_ID)
    INNER JOIN Usuario 
        ON (Login.Log_Usu_ID = Usuario.Usu_ID) WHERE Log_Usu_ID = Usu_ID AND Log_Usu_ID <> '$UsuID' AND Log_Fecha = '$Fecha' AND DATE_SUB(NOW(),INTERVAL 5 MINUTE) <= Acc_Hora";
                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    if (mysqli_num_rows($result) > 0) {
//			echo "<ul>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<li>$row[Usu_Persona]</li>";
                        }
//			echo "</ul>";
                    }
                }//fin if
            }

//fin funcion

            function revisarContadorInscripcion() {
                $LecID = $_POST['LecID'];
                //$mostrar = "No entr�";
                $sql = "SELECT COUNT(*) AS Total FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $LecID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $row = mysqli_fetch_array($result);
                if ($row[Total] == 100) {
                    $mostrar = "<strong>¡ ¡ ¡ FELICITACIONES ! ! !</strong><br />Ha llegado a las <strong>1000 inscripciones</strong> para el ciclo lectivo";
                }
                echo $mostrar;
            }

//fin funcion

            function obtenerDetalleAccesoOpcion() {
                $LogID = $_POST['LogID'];
                $sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				$sql = "SELECT * FROM
    AccesoOpcion
    INNER JOIN Menu 
        ON (AccesoOpcion.Acc_Men_ID = Menu.Men_ID)
    INNER JOIN Opcion 
        ON (AccesoOpcion.Acc_Men_ID = Opcion.Opc_Men_ID) AND (AccesoOpcion.Acc_Opc_ID = Opcion.Opc_ID) WHERE Acc_Log_ID = $LogID ORDER BY Acc_Hora;";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<li>$row[Acc_Hora]: <strong>$row[Men_Nombre]</strong>->$row[Opc_Nombre]</li>";
                    }
                } else {
                    echo "No existen datos cargados.";
                }
            }

//fin funcion

            function arreglarDNI() {
                $DNI_Bueno = $_POST['DNI_Bueno'];
                $DNI_Malo = $_POST['DNI_Malo'];
                $sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI_Bueno;";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    $PerID_Bueno = $row[Per_ID];
                    $sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI_Malo;";
                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        $PerID_Malo = $row[Per_ID];
                        $sql = "UPDATE Per_DNI = $DNI_Bueno WHERE";
                    } else {
                        echo "No se encontraron registros con el n�mero de documento mal cargado.";
                        exit;
                    }
                } else {
                    echo "No se encontraron registros con el n�mero de documento bien cargado.";
                    exit;
                }
            }

//fin funcion

            function guardarRequisitos() {
                //echo "Hola";//exit;
                $Requisito = $_POST['Requisito'];
                //echo $Requisito;
                //$Requisito = strtoupper(trim(utf8_decode($Requisito)));
                $Requisito = Mayus($Requisito);
                $NivID = $_POST['NivID'];
                $Obligatorio = $_POST['Obligatorio'];
                $Constancia = $_POST['Constancia'];
                $Inscripcion = $_POST['Inscripcion'];
                obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
                $ReqID = $_POST['ReqID'];
                if (!empty($ReqID)) {
                    $sql = "UPDATE Requisito SET Req_Nombre = '$Requisito', Req_Obligatorio =  $Obligatorio, Req_Constancia =  $Constancia, Req_Inscripcion = $Inscripcion, Req_Usu_ID = '$UsuID', Req_Fecha = '$Fecha', Req_Hora = '$Hora' WHERE Req_Niv_ID = $NivID AND Req_ID = $ReqID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    exit;
                }
                if (!empty($Requisito)) {
                    $sql = "SELECT * FROM Requisito WHERE Req_Nombre = '$Requisito' AND Req_Niv_ID = $NivID";
                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    if (mysqli_num_rows($result) > 0) {//ya existe con ese nombre lo actualizamos
                        $row = mysqli_fetch_array($result);
                        $sql = "UPDATE Requisito SET Req_Nombre = '$Requisito', Req_Obligatorio =  $Obligatorio, Req_Constancia =  $Constancia, Req_Inscripcion = $Inscripcion, Req_Usu_ID = '$UsuID', Req_Fecha = '$Fecha', Req_Hora = '$Hora' WHERE Req_Niv_ID = $NivID AND Req_ID = $row[Req_ID]";
                        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        //echo $sql;
                    } else {
                        $sql = "INSERT INTO Requisito (Req_Niv_ID, Req_Nombre, Req_Obligatorio, Req_Constancia, Req_Inscripcion, Req_Usu_ID, Req_Fecha, Req_Hora) VALUES ($NivID, '$Requisito', $Obligatorio, $Constancia, $Inscripcion, '$UsuID', '$Fecha', '$Hora')";
                        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        echo $Requisito;
                    }
                }
            }

//fin funcion

            function eliminarRequisito() {
                //echo "Hola";exit;
                $ID = $_POST['ID'];

                $sql = "SELECT * FROM Requisito WHERE Req_ID = $ID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//no existe
                    echo "El requisito elegido no existe o ya fue eliminado.";
                } else {
                    $sql = "DELETE FROM Requisito WHERE Req_ID = $ID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    echo "Se elimin� el requisito seleccionado.";
                }
            }

//fin funcion

            function guardarCarrera() {
                //echo "Hola";//exit;
                $Carrera = $_POST['Carrera'];

                $Carrera = arreglarCadenaMayuscula(trim(utf8_decode($Carrera)));
                $UniID = $_POST['UniID'];
                $CarID = $_POST['CarID'];
                if (!empty($CarID)) {
                    $sql = "UPDATE Carrera SET Car_Nombre = '$Carrera', Car_Uni_ID = $UniID WHERE Car_ID = $CarID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    //echo $sql;
                    exit;
                }
                if (!empty($Carrera)) {
                    $sql = "SELECT * FROM Carrera WHERE Car_Nombre = '$Carrera' AND Car_Uni_ID = $UniID";
                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    if (mysqli_num_rows($result) > 0) {//ya existe con ese nombre lo actualizamos
                        echo "Ya existe";
                        //echo $sql;
                    } else {
                        $sql = "INSERT INTO Carrera (Car_Uni_ID, Car_Nombre) VALUES ($UniID, '$Carrera')";
                        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        echo $Carrera;
                    }
                }
            }

//fin funcion

            function eliminarCarrera() {
                //echo "Hola";exit;
                $ID = $_POST['ID'];

                $sql = "SELECT * FROM Carrera WHERE Car_ID = $ID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//no existe
                    echo "La carrera no existe o ya fue eliminada.";
                } else {
                    $sql = "DELETE FROM Carrera WHERE Car_ID = $ID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    echo "Se elimin� la carrera seleccionada.";
                }
            }

//fin funcion

            function guardarPlan() {
                //echo "Hola";//exit;
                $Plan = $_POST['Plan'];

                $Plan = arreglarCadenaMayuscula(trim(utf8_decode($Plan)));
                $PlaID = $_POST['PlaID'];
                if (!empty($PlaID)) {
                    $sql = "UPDATE Plan SET Pla_Nombre = '$Plan' WHERE Pla_ID = $PlaID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    //echo $sql;
                    exit;
                }
                if (!empty($Plan)) {
                    $sql = "SELECT * FROM Plan WHERE Pla_Nombre = '$Plan'";
                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    if (mysqli_num_rows($result) > 0) {//ya existe con ese nombre 
                        echo "Ya existe";
                        //echo $sql;
                    } else {
                        $sql = "INSERT INTO Plan (Pla_Nombre) VALUES ('$Plan')";
                        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        echo $Plan;
                    }
                }
            }

//fin funcion

            function eliminarPlan() {
                //echo "Hola";exit;
                $ID = $_POST['ID'];

                $sql = "SELECT * FROM Plan WHERE Pla_ID = $ID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//no existe
                    echo "El Plan no existe o ya fue eliminado.";
                } else {
                    $sql = "DELETE FROM Plan WHERE Pla_ID = $ID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    echo "Se elimin� el plan seleccionado.";
                }
            }

//fin funcion

            function guardarMateria() {
                //echo "Hola";//exit;
                $Materia = $_POST['Materia'];
                $MatID = $_POST['MatID'];
                $CarID = $_POST['CarID'];
                $CurID = $_POST['CurID'];
                $Optativa = $_POST['Optativa'];

                $Materia = arreglarCadenaMayuscula(trim(utf8_decode(strtoupper($Materia))));
                $sql = "SELECT * FROM Materia WHERE Mat_ID = $MatID AND Mat_Car_ID = $CarID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {//ya existe con ese nombre lo actualizamos
                    $sql = "UPDATE Materia SET Mat_Nombre = '$Materia', Mat_Cur_ID = $CurID, Mat_Optativa = $Optativa WHERE Mat_ID = $MatID AND Mat_Car_ID = $CarID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    echo "Se modificaron los valores correctamente de la materia <strong>($MatID) $Materia</strong>";
                    //echo $sql;
                } else {
                    $sql = "INSERT INTO Materia (Mat_ID, Mat_Nombre, Mat_Car_ID, Mat_Cur_ID, Mat_Optativa) VALUES ($MatID, '$Materia', $CarID, $CurID, $Optativa)";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    echo "Se agreg� correctamente la nueva materia <strong>($MatID) $Materia</strong>";
                }
            }

//fin funcion

            function guardarMateriaTitulo() {
                //echo "Hola";exit;
                $MatID = $_POST['MatID'];
                $CarID = $_POST['CarID'];
                $PlaID = $_POST['PlaID'];
                $TitID = $_POST['TitID'];

                $sql = "INSERT INTO TituloMateria (TMa_Mat_ID, TMa_Car_ID, TMa_Pla_ID, TMa_Tit_ID) VALUES ($MatID, $CarID, $PlaID, $TitID)";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                //echo $sql;
            }

//fin funcion

            function eliminarMateriasTitulo() {
                //echo "Hola";exit;
                $CarID = $_POST['CarID'];
                $PlaID = $_POST['PlaID'];
                $TitID = $_POST['TitID'];

                $sql = "DELETE FROM TituloMateria WHERE TMa_Car_ID = $CarID AND TMa_Pla_ID = $PlaID AND TMa_Tit_ID = $TitID";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                //echo $sql;
            }

//fin funcion

            function guardarRequisitoPersona() {
                //echo "Hola";//exit;
                $LecID = $_POST['LecID'];
				$ReqID = $_POST['ReqID'];
                $NivID = $_POST['NivID'];
                $PerID = $_POST['PerID'];
                $Campo = $_POST['Campo'];
                $Valor = $_POST['Valor'];
				$FechaPresentada = $_POST['FechaPresentada'];
				
                obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
                if (!empty($ReqID)) {
                    $sql = "SELECT * FROM RequisitoPresentado WHERE Pre_Niv_ID = $NivID AND Pre_Per_ID = $PerID AND Pre_Req_ID = $ReqID AND Pre_Lec_ID = $LecID";
                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    if (mysqli_num_rows($result) > 0) {//ya existe cosa que no deber�a
                        $sql = "UPDATE RequisitoPresentado SET $Campo = '$Valor', Pre_Usu_ID = '$UsuID', Pre_Fecha = '$Fecha', Pre_Hora = '$Hora' WHERE Pre_Niv_ID = $NivID AND Pre_Per_ID = $PerID AND Pre_Req_ID = $ReqID AND Pre_Lec_ID = $LecID";
                        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    } else {
                        if ($FechaPresentada!="---" && !empty($FechaPresentada) ){
							$Fecha = cambiaf_a_mysql($FechaPresentada);
							$UsuID = $_POST['UsuID'];
							
						}
						$sql = "INSERT INTO RequisitoPresentado (Pre_Niv_ID, Pre_Per_ID, Pre_Req_ID, $Campo, Pre_Usu_ID, Pre_Fecha, Pre_Hora, Pre_Lec_ID) VALUES ($NivID, '$PerID', $ReqID, $Valor, '$UsuID', '$Fecha', '$Hora', $LecID)";
                        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        //echo "$FechaPresentada:::::".$sql;			
                    }
                }
            }

//fin funcion
            
function guardarCuotaPorcentaje() {
   
    $Ben_ID = $_POST['Ben_ID'];
    //echo "Ben_ID ".$Ben_ID."<br />";
    $CTi_ID = $_POST['CTi_ID'];
    //echo "CTi_ID ".$CTi_ID."<br />";
    $PorValor = $_POST['PorValor'];
    $PorValorPorcentaje = $_POST['PorValorPorcentaje'];
    //echo "PorValor ".$PorValor."<br />";
    //return false;
    
    $sql2="SELECT * FROM CuotaPorcentaje WHERE Por_CTi_ID=$CTi_ID AND Por_Ben_ID=$Ben_ID;";
    $result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result2) != 0) {
        $sql = "UPDATE CuotaPorcentaje SET Por_Valor = '$PorValor', Por_Porcentaje = '$PorValorPorcentaje' WHERE
        Por_CTi_ID = '$CTi_ID' AND Por_Ben_ID = '$Ben_ID' ;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);  
    }else{
        $sql = "INSERT INTO CuotaPorcentaje (Por_CTi_ID, Por_Ben_ID, Por_Valor, Por_Porcentaje) VALUES ($CTi_ID, $Ben_ID, '$PorValor', '$PorValorPorcentaje')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);   
    }
    //echo $sql;    
}

//fin funcion

            function eliminarRequisitoPersona() {
                //echo "Hola";exit;
                $NivID = $_POST['NivID'];
                $PerID = $_POST['PerID'];
				$LecID = $_POST['LecID'];


                $sql = "SELECT * FROM RequisitoPresentado WHERE Pre_Niv_ID = $NivID AND Pre_Per_ID = $PerID AND Pre_Lec_ID = $LecID";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) == 0) {//no existe
                    //echo "El requisito elegido no existe o ya fue eliminado.";
                } else {
                    $sql = "DELETE FROM RequisitoPresentado WHERE Pre_Niv_ID = $NivID AND Pre_Per_ID = $PerID AND Pre_Lec_ID = $LecID";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    //echo "$sql<br />";
                    //echo "Se elimin� el requisito seleccionado.";
                }
            }

//fin funcion

            function mostrarDeudaCuotasSIUCC() {
                //echo "Hola";exit;
                $PerID = $_POST['PerID'];
                $sql = "SELECT * FROM Chequera_Serie     INNER JOIN Chequera_Cuota          ON (Chequera_Serie.ChS_Fac_ID = Chequera_Cuota.ChC_Fac_ID) AND (Chequera_Serie.ChS_TCh_ID = Chequera_Cuota.ChC_TCh_ID) AND (Chequera_Serie.ChS_ID = Chequera_Cuota.ChC_ChS_ID)  
	INNER JOIN siucc.Chequera_Alternativa 
        ON (Chequera_Cuota.ChC_Fac_ID = Chequera_Alternativa.ChA_Fac_ID) AND (Chequera_Cuota.ChC_TCh_ID = Chequera_Alternativa.ChA_TCh_ID) AND (Chequera_Cuota.ChC_ChS_ID = Chequera_Alternativa.ChA_ChS_ID) AND (Chequera_Cuota.ChC_Pro_ID = Chequera_Alternativa.ChA_Pro_ID) AND (Chequera_Cuota.ChC_Alt_ID = Chequera_Alternativa.ChA_Alt_ID)
	INNER JOIN Producto          ON (Chequera_Cuota.ChC_Pro_ID = Producto.Pro_ID) 		WHERE (Chequera_Serie.ChS_Per_ID = $PerID AND Chequera_Cuota.ChC_Pagado = 0     AND Chequera_Cuota.ChC_Baja = 0     AND Chequera_Cuota.ChC_Cancelado = 0) 	ORDER BY Chequera_Cuota.ChC_1er_Vencimiento ASC;";
                $result = consulta_mysql($sql);
                if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
                    ?>
                    <table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
                        <tr>
                            <th scope="col">Tipo de cuota</th>
                            <th scope="col">Mes</th>
                            <th scope="col">A&ntilde;o</th>
                            <th scope="col">Vencimiento</th>
                            <th scope="col">Importe</th>
                        </tr>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            //Calculamos el importe que deber�a pagar al d�a de hoy
                            //Por defecto hacemos que pague el tercer importe
                            $importe = $row[ChC_3er_Importe];
                            $fechaCuota = cfecha($row[ChC_3er_Vencimiento]);
                            $clase = "vencida_roja";
                            $vencida1 = true;
                            $vencida2 = true;
                            $fechaHoy = date("d-m-Y");
                            $ya_vencida = 1;
                            //Controlamos el segundo vencimiento - 
                            $fecha2Vencimiento = cfecha($row[ChC_2do_Vencimiento]);
                            $fecha = restarFecha($fechaHoy, $fecha2Vencimiento);
                            if ($fecha >= 0) {
                                $importe = $row[ChC_2do_Importe];
                                $vencida2 = false;
                                $fechaCuota = $fecha2Vencimiento;
                                $clase = "vencida_azul";
                                $ya_vencida = 1;
                            }
                            //Controlamos el primer vencimiento - 
                            $fecha1Vencimiento = cfecha($row[ChC_1er_Vencimiento]);
                            $fecha = restarFecha($fechaHoy, $fecha1Vencimiento);
                            if ($fecha >= 0) {
                                $importe = $row[ChC_1er_Importe];
                                $vencida1 = false;
                                $vencida2 = false;
                                $fechaCuota = $fecha1Vencimiento;
                                if (($i % 2) == 0)
                                    $clase = "fila";
                                else
                                    $clase = "fila2";
                                //$clase = "vencida_no";			
                                $ya_vencida = 0;
                            }
                            $importe = intval($importe);

                            //Creamos una variable que guarde todos los datos de identificaci�n de la Cuota		
                            $datosCuota = $row[ChC_Fac_ID] . ";" . $row[ChC_TCh_ID] . ";" . $row[ChC_ChS_ID] . ";" . $row[ChC_Alt_ID] . ";" . $row[ChC_Pro_ID] . ";" . $row[ChC_Cuo_ID] . ";*" . $importe;
                            $debito = false;
                            $tarjeta = "";
                            $debito = tieneDebito($datosCuota, $tarjeta);
                            if ($ya_vencida == 0) {
                                if ($debito)
                                    $clase = "tiene_debito";
                            }
                            $detalle_alternativa = "";
                            $alternativas = tieneAlternativas($row[ChC_Fac_ID], $row[ChC_TCh_ID], $row[ChC_ChS_ID], $row[ChC_Alt_ID], $row[ChC_Pro_ID], $row[ChC_Cuo_ID]);
                            if ($row[ChC_Pro_ID] == 2)
                                if ($alternativas > 1)
                                    if ($row[ChC_Alt_ID] == 1)
                                        $detalle_alternativa = "<br />" . $row[ChA_Titulo] . "";
                                    else
                                        $detalle_alternativa = "<br />" . $row[ChA_Titulo] . ""; //"(Financiado)";
//$totales = buscarEntidadEducativaTotal($row[Niv_ID]);





                                
                            ?>

                            <tr class="<?php echo $clase ?>">
                                <td><strong><?php echo $row[Pro_Nombre]; ?></strong><?php echo " " . $detalle_alternativa; ?></td>
                                <td><?php echo buscarMes($row[ChC_Mes]); ?></td>
                                <td><?php echo $row[ChC_Anio]; ?></td>
                                <td><?php echo $fechaCuota; //cfecha($row[ChC_1er_Vencimiento]);          ?></td>
                                <td>$ <?php echo $importe; //$row[ChC_1er_Importe];       ?></td>
                            </tr>
                            <?php
                        }//fin while
                        ?>
                    </table>
                <?php }else {
                    ?>
                    <div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron cuotas sin pagar.</span>

                        <?php
                    }
                }

//fin funcion

                

                    function revisarRequisitosPersona() {
                        //echo "Hola";exit;
                        $NivID = $_POST['NivID'];
                        $PerID = $_POST['PerID'];


                        $sql = "SELECT * FROM Requisito WHERE Req_Niv_ID = $NivID AND Req_Obligatorio = 1 AND Req_Inscripcion = 1 AND Req_ID NOT IN (SELECT Pre_Req_ID FROM RequisitoPresentado WHERE Pre_Niv_ID = $NivID AND Pre_Per_ID = $PerID)";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {//no existe
                            echo "<strong><ul>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<li>$row[Req_Nombre]</li>";
                            }
                            echo "</ul></strong>";
                        } else {
                            echo '';
                            //echo "Se elimin� el requisito seleccionado.";
                        }
                    }

//fin funcion

                    function verificarInscripcionDefinitiva() {
                        
                        $LegID = $_POST['LegID'];
                        $LecID = $_POST['LecID'];
                        $DNI = $_POST['DNI'];
                        $PerID = $_POST['PerID'];
//	echo "Hola";exit;
                        //obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
                        //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
                        $sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
                        //echo $sql;exit;
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_array($result);
                            $NivID = $row['Ins_Niv_ID'];

                            $sql = "SELECT * FROM CuotaPersona INNER JOIN CuotaTipo ON (Cuo_CTi_ID = CTi_ID) WHERE CTi_Inscripcion=1 AND Cuo_Lec_ID=$LecID AND Cuo_Per_ID = $PerID AND Cuo_Niv_ID = $NivID AND Cuo_Pagado=1;";
                            //echo $sql;
                            $result_siucc = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            if (mysqli_num_rows($result_siucc) > 0) {
                                $mensaje = 1;
                            } else {
                                $mensaje = 0;
                            }
                        }

                        echo $mensaje;
                        //echo $sql;
//*/
                    }

//fin funcion

                    function grabarInscripcionDefinitiva() {
                        $LegID = $_POST['LegID'];
                        $LecID = $_POST['LecID'];
                        //obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
                        //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
                        $sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
                        //echo $sql;exit;
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {
                            $sql = "UPDATE Colegio_Inscripcion SET Ins_Provisoria = 0 WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        }
//*/
                    }

//fin funcion

function guardarAltaDocente() {
	//echo "Hola";//exit;
	$PerID = $_POST['PerID'];
	$Legajo = $_POST['Legajo'];
	$Activo = $_POST['Activo'];
	$Alta = cambiaf_a_mysql($_POST['Alta']);
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Colegio_Docente WHERE Doc_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$sql = "UPDATE Colegio_Docente SET Doc_Legajo = '$Legajo', Doc_Alta = '$Alta', Doc_Activo = '$Activo', Doc_Fecha = '$Fecha', Doc_Hora = '$Hora' WHERE Doc_Per_ID = $PerID";
		$mensaje = "Se ha actualizado los cambios correctamente del Docente.";
	} else {
		$sql = "INSERT INTO Colegio_Docente (Doc_Per_ID, Doc_Legajo, Doc_Alta, Doc_Activo, Doc_Fecha, Doc_Hora) VALUES ('$PerID', '$Legajo', '$Alta', '$Activo', '$Fecha', '$Hora')";
		$mensaje = "Se ha agregado correctamente al nuevo Docente.";
	}
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$DNI = gbuscarDNI($PerID);
	gObtenerApellidoNombrePersona($DNI, $Apellido, $Nombre);
	guardarCuentaUsuario($DNI, $DNI, "$Nombre $Apellido");
	guardarRol($DNI, 6, true);
	echo $mensaje;
	//echo $sql;			
}

//fin funcion

                    function buscarDatosPersona() {
                        //echo "Hola";exit;
                        $DNI = $_POST['DNI'];
                        $_SESSION['sesion_ultimoDNI'] = $DNI;
						$sql = "SET NAMES UTF8;";
						consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

                        $sql = "SELECT * FROM Persona WHERE Per_DNI = '$DNI'";
                        //echo $sql;exit;
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                            echo "{}";
                        } else {
                            $row = mysqli_fetch_array($result);
                            $datos .= "{\"Per_Apellido\": \"" . $row[Per_Apellido] . "\",\"";
                            $datos .= "Per_Nombre\": \"" . $row[Per_Nombre] . "\",\"";
                            $datos .= "Per_Sexo\": \"" . $row[Per_Sexo] . "\",\"";
                            $datos .= "Per_Doc_ID\": \"" . $row[Per_Doc_ID] . "\",\"";
                            $datos .= "Per_Alternativo\": \"" . $row[Per_Alternativo] . "\",\"";
                            $datos .= "Per_Extranjero\": \"" . $row[Per_Extranjero] . "\",\"";
                            $datos .= "Per_DNI\": \"" . $row[Per_DNI] . "\"}";
                            echo $datos;
                        }
                    }

//fin funcion

                   function buscarDatosDocente() {
                        //echo "Hola";exit;
                        $DNI = $_POST['DNI'];
                        $_SESSION['sesion_ultimoDNI'] = $DNI;
						$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

                        $sql = "SELECT * FROM Colegio_Docente INNER JOIN Persona 
	ON (Colegio_Docente.Doc_Per_ID = Persona.Per_ID) WHERE Per_DNI = '$DNI'";
                        //echo $sql;exit;
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
                            echo "{}";
                        } else {
                            $row = mysqli_fetch_array($result);
                            //$foto = buscarFoto($DNI, $row[Per_Doc_ID], 60);
                            $datos .= "{\"Doc_Legajo\": \"" . $row['Doc_Legajo'] . "\",\"";
                            $datos .= "Doc_Alta\": \"" . cfecha($row['Doc_Alta']) . "\",\"";
                            $datos .= "Doc_ID\": \"" . $row['Doc_ID'] . "\",\"";
                            $datos .= "Doc_Activo\": \"" . $row['Doc_Activo'] . "\",\"";
                            $datos .= "Doc_Fecha\": \"" . cfecha($row['Doc_Fecha']) . "\",\"";
                            $datos .= "Doc_Hora\": \"" . $row['Doc_Hora'] . "\",\"";
                            $datos .= "Per_Apellido\": \"" . $row['Per_Apellido'] . "\",\"";
                            $datos .= "Per_Nombre\": \"" . $row['Per_Nombre'] . "\",\"";
                            $datos .= "Per_Sexo\": \"" . $row['Per_Sexo'] . "\",\"";
                            $datos .= "Per_Foto\": \"" . $foto . "\",\"";
                            $datos .= "Per_Doc_ID\": \"" . $row['Per_Doc_ID'] . "\",\"";
                            //Busco si tiene datos de cursos
                            /*
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
                             */
                            $datos .= "Per_DNI\": \"" . $row[Per_DNI] . "\"}";
                            echo $datos;
                        }
                    }

//fin funcion

function guardarMateriaColegio() {
	//echo "Hola";//exit;
	$Materia = $_POST['Materia'];
	$sql = "SET NAMES UTF8;";
	//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$Materia = strtoupper(arreglarCadenaMayuscula(trim($Materia)));
	$Siglas = strtoupper(arreglarCadenaMayuscula(trim($_POST['Siglas'])));
	$MatID = $_POST['MatID'];
	$Mat_Ori_ID = $_POST['OriID'];
	$Mat_Convivencia = $_POST['Mat_Convivencia'];
	$Mat_Curricular = $_POST['Mat_Curricular'];
	if (!empty($MatID)) {
		$sql = "UPDATE Colegio_Materia SET Mat_Nombre = '$Materia', Mat_Siglas = '$Siglas', Mat_Ori_ID = '$Mat_Ori_ID', Mat_Convivencia = '$Mat_Convivencia', Mat_Curricular = '$Mat_Curricular' WHERE Mat_ID = $MatID";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;
		exit;
	}
	if (!empty($Materia)) {
		$sql = "SELECT * FROM Colegio_Materia WHERE Mat_Nombre = '$Materia' AND Mat_Ori_ID = '$Mat_Ori_ID'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {//ya existe con ese nombre lo actualizamos
			echo "Ya existe";
			//echo $sql;
		} else {
			$sql = "INSERT INTO Colegio_Materia (Mat_Ori_ID, Mat_Nombre, Mat_Siglas, Mat_Curricular, Mat_Convivencia) VALUES ('$Mat_Ori_ID',  '$Materia', '$Siglas', $Mat_Curricular, $Mat_Convivencia)";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo $Materia;
		}
	}
}//fin funcion

function eliminarMateriaColegio() {
	//echo "Hola";exit;
	$ID = $_POST['ID'];

	$sql = "SELECT * FROM Colegio_Materia WHERE Mat_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//no existe
		echo "La materia no existe o ya fue eliminada.";
	} else {
		$sql = "DELETE FROM Colegio_Materia WHERE Mat_ID = $ID";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se eliminó la materia seleccionada.";
	}
}//fin funcion

function guardarClaseDocente() {
//echo "Hola";//exit;
$DocID = $_POST['DocID'];
$LecID = $_POST['LecID'];
$CurID = $_POST['CurID'];
$NivID = $_POST['NivID'];
$DivID = $_POST['DivID'];
$MatID = $_POST['MatID'];
$SedID = $_POST['SedID'];
$Hs = $_POST['Hs'];
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

if ($MatID == -2 || $MatID == -22 || $MatID == -3 || $MatID == -4 || $MatID == -6 || $MatID == -7) {
	if ($MatID == -2) $sql = "SELECT * FROM Colegio_Materia WHERE Mat_Curricular = 1";
    if ($MatID == -22) $sql = "SELECT * FROM Colegio_Materia WHERE (Mat_Curricular = 1 OR Mat_Convivencia = 1)";
	if ($MatID == -3) $sql = "SELECT * FROM Colegio_Materia WHERE Mat_Convivencia = 1";
    //if ($MatID == -4) $sql = "SELECT * FROM Colegio_Materia WHERE Mat_Pedagogica = 1";
    if ($MatID == -6) $sql = "SELECT * FROM Colegio_Materia WHERE Mat_Pedagogica = 1 AND Mat_Curso = 1";
    if ($MatID == -7) $sql = "SELECT * FROM Colegio_Materia WHERE Mat_Pedagogica = 1 AND Mat_Curso = 2";
	$resultMat = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($rowMat = mysqli_fetch_array($resultMat)) {
		$MatID = $rowMat['Mat_ID'];
		guardarClaseSola($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
	}//fin while
} else {

	if ($CurID == 999999) {//Verificamos las inscripciones hechas en el ciclo lectivo y solo asignamos los cursos en los cuales fueron inscriptos los alumnos
		$sql = "SELECT DISTINCTROW Ins_Cur_ID FROM Colegio_Inscripcion
INNER JOIN Curso 
ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
INNER JOIN Legajo 
ON (Colegio_Inscripcion.Ins_Leg_ID = Leg_ID)			
WHERE Ins_Niv_ID = $NivID AND Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID;";
		$resultCur = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($rowCur = mysqli_fetch_array($resultCur)) {
			$CurID = $rowCur[Ins_Cur_ID];

			if ($DivID == 999999) {//Verificamos las inscripciones hechas en el ciclo lectivo y solo asignamos las divisiones en los cuales fueron inscriptos los alumnos
				$sql = "SELECT DISTINCTROW Ins_Div_ID FROM Colegio_Inscripcion
INNER JOIN Division 
ON (Colegio_Inscripcion.Ins_Div_ID = Div_ID) 
INNER JOIN Legajo 
ON (Colegio_Inscripcion.Ins_Leg_ID = Leg_ID)
WHERE Ins_Niv_ID = $NivID AND Ins_Cur_ID = $CurID AND Ins_Lec_ID = $LecID  AND Leg_Sed_ID = $SedID;";
				$resultDiv = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				while ($rowDiv = mysqli_fetch_array($resultDiv)) {
					$DivID = $rowDiv[Ins_Div_ID];
					guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
				}//fin while
			} else {
				guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
			}//fin if division
		}//fin while
	} else {
		if ($DivID == 999999) {//Verificamos las inscripciones hechas en el ciclo lectivo y solo asignamos los cursos en los cuales fueron inscriptos los alumnos
			$sql = "SELECT DISTINCTROW Ins_Div_ID FROM Colegio_Inscripcion
INNER JOIN Division 
ON (Colegio_Inscripcion.Ins_Div_ID = Div_ID) 
INNER JOIN Legajo 
ON (Colegio_Inscripcion.Ins_Leg_ID = Leg_ID)
WHERE Ins_Niv_ID = $NivID AND Ins_Cur_ID = $CurID AND  Ins_Lec_ID = $LecID  AND Leg_Sed_ID = $SedID;";
			$resultDiv = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			while ($rowDiv = mysqli_fetch_array($resultDiv)) {
				$DivID = $rowDiv[Ins_Div_ID];
				guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
			}//fin while
		} else {
			guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
		}//fin if division		
	}//fin if curso
}//fin if materia
}//fin funcion

function guardarClaseSola($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID) {
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);


if ($CurID == 999999) {//Verificamos las inscripciones hechas en el ciclo lectivo y solo asignamos los cursos en los cuales fueron inscriptos los alumnos
		$sql = "SELECT DISTINCTROW Ins_Cur_ID FROM Colegio_Inscripcion
INNER JOIN Curso 
ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
INNER JOIN Legajo 
ON (Colegio_Inscripcion.Ins_Leg_ID = Leg_ID)			
WHERE Ins_Niv_ID = $NivID AND Ins_Lec_ID = $LecID AND Leg_Sed_ID = $SedID;";
		$resultCur = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($rowCur = mysqli_fetch_array($resultCur)) {
			$CurID = $rowCur['Ins_Cur_ID'];

			if ($DivID == 999999) {//Verificamos las inscripciones hechas en el ciclo lectivo y solo asignamos las divisiones en los cuales fueron inscriptos los alumnos
				$sql = "SELECT DISTINCTROW Ins_Div_ID FROM Colegio_Inscripcion
INNER JOIN Division 
ON (Colegio_Inscripcion.Ins_Div_ID = Div_ID) 
INNER JOIN Legajo 
ON (Colegio_Inscripcion.Ins_Leg_ID = Leg_ID)
WHERE Ins_Niv_ID = $NivID AND Ins_Cur_ID = $CurID AND Ins_Lec_ID = $LecID  AND Leg_Sed_ID = $SedID;";
				$resultDiv = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				while ($rowDiv = mysqli_fetch_array($resultDiv)) {
					$DivID = $rowDiv['Ins_Div_ID'];
					guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
				}//fin while
			} else {
				guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
			}//fin if division
		}//fin while
	} else {
		if ($DivID == 999999) {//Verificamos las inscripciones hechas en el ciclo lectivo y solo asignamos los cursos en los cuales fueron inscriptos los alumnos
			$sql = "SELECT DISTINCTROW Ins_Div_ID FROM Colegio_Inscripcion
INNER JOIN Division 
ON (Colegio_Inscripcion.Ins_Div_ID = Div_ID) 
INNER JOIN Legajo 
ON (Colegio_Inscripcion.Ins_Leg_ID = Leg_ID)
WHERE Ins_Niv_ID = $NivID AND Ins_Cur_ID = $CurID AND  Ins_Lec_ID = $LecID  AND Leg_Sed_ID = $SedID;";
			$resultDiv = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			while ($rowDiv = mysqli_fetch_array($resultDiv)) {
				$DivID = $rowDiv[Ins_Div_ID];
				guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
			}//fin while
		} else {
			guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID);
		}//fin if division		
	}//fin if curso


}//fin function
//*

function guardarClaseUnica($LecID, $MatID, $NivID, $CurID, $DivID, $DocID, $Hs, $SedID){
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
$sql = "SELECT * FROM Colegio_Clase WHERE Cla_Lec_ID = '$LecID' AND Cla_Mat_ID = '$MatID' AND Cla_Niv_ID = '$NivID' AND Cla_Cur_ID = '$CurID' AND Cla_Div_ID = '$DivID' AND Cla_Doc_ID = '$DocID' AND Cla_Sed_ID = $SedID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//echo $sql;
if (mysqli_num_rows($result) == 0) {//no existe 
	$sql = "INSERT INTO Colegio_Clase (Cla_Lec_ID, Cla_Sed_ID, Cla_Mat_ID, Cla_Niv_ID, Cla_Cur_ID, Cla_Div_ID, Cla_Doc_ID, Cla_Hs, Cla_Usu_ID, Cla_Fecha, Cla_Hora) VALUES ('$LecID', $SedID, '$MatID', '$NivID', '$CurID', '$DivID', '$DocID', '$Hs', '$UsuID', '$Fecha', '$Hora')";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $ClaID = $res['id'];
    }else{
        echo "Mal";
    }

	guardarClaseAlumno($ClaID);
	//echo $sql;
	echo "Cambios guardados correctamente.";
}//fin if clase sola
}

function guardarClaseAlumno($ClaID) {
$sql = "INSERT INTO Colegio_InscripcionClase (IMa_Leg_ID, IMa_Lec_ID, IMa_Cla_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora)
SELECT Ins_Leg_ID, Ins_Lec_ID, Cla_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora
FROM Colegio_Clase
INNER JOIN Colegio_Inscripcion
ON (Colegio_Clase.Cla_Lec_ID = Colegio_Inscripcion.Ins_Lec_ID)
AND (Colegio_Clase.Cla_Niv_ID = Colegio_Inscripcion.Ins_Niv_ID)
AND (Colegio_Clase.Cla_Cur_ID = Colegio_Inscripcion.Ins_Cur_ID)
AND (Colegio_Clase.Cla_Div_ID = Colegio_Inscripcion.Ins_Div_ID)
WHERE (Colegio_Clase.Cla_ID = $ClaID);";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
}//fin function

function guardarClaseAlumnoLegajo($LegID, $LecID) {
    $sql = "INSERT IGNORE INTO Colegio_InscripcionClase (IMa_Leg_ID, IMa_Lec_ID, IMa_Cla_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora)
        SELECT Ins_Leg_ID, Ins_Lec_ID, Cla_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora
        FROM Colegio_Clase
        INNER JOIN Colegio_Inscripcion
        ON (Colegio_Clase.Cla_Lec_ID = Colegio_Inscripcion.Ins_Lec_ID)
        AND (Colegio_Clase.Cla_Niv_ID = Colegio_Inscripcion.Ins_Niv_ID)
        AND (Colegio_Clase.Cla_Cur_ID = Colegio_Inscripcion.Ins_Cur_ID)
        AND (Colegio_Clase.Cla_Div_ID = Colegio_Inscripcion.Ins_Div_ID)
        WHERE (Ins_Lec_ID=$LecID AND Ins_Leg_ID=$LegID);";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
}//fin function

function mostrarClaseDocente() {
    //echo "Hola";//exit;
    $DocID = $_POST['DocID'];
    $LecID = $_POST['LecID'];
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM
Colegio_Clase
INNER JOIN Colegio_Nivel 
ON (Colegio_Clase.Cla_Niv_ID = Colegio_Nivel.Niv_ID)
INNER JOIN Sede 
ON (Colegio_Clase.Cla_Sed_ID = Sed_ID)    
INNER JOIN Curso 
ON (Colegio_Clase.Cla_Cur_ID = Curso.Cur_ID)
INNER JOIN Division 
ON (Colegio_Clase.Cla_Div_ID = Division.Div_ID)
INNER JOIN Colegio_Materia 
ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID) 
    INNER JOIN Colegio_Orientacion 
        ON (Colegio_Materia.Mat_Ori_ID = Colegio_Orientacion.Ori_ID)
WHERE Cla_Lec_ID = '$LecID' AND Cla_Doc_ID = '$DocID' AND Cla_Baja = 0 
ORDER BY Niv_ID, Ori_ID, Cla_Baja, Mat_Convivencia, Cur_ID, Div_ID, Mat_Nombre";//Mat_ID
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    //echo $sql;
    if (mysqli_num_rows($result) > 0) {
        ?>
        <p class="titulo_noticia">Clases activas del Docente</p>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center" class="borde_recuadro">
            <tr class="fila_titulo">
                <td>#</td>
                <td>Nivel/Orientación</td>
                <td>Curso</td>
                <td>Div.</td>
                <td>Materia</td>
                <td>Alumnos</td>
                <td>D&iacute;a y Horario</td>
                <td align="center">#</td>
            </tr>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                $i++;
                if (($i % 2) == 0)
                    $clase = "fila";
                else
                    $clase = "fila2";
                ?>
                <tr class="<?php echo $clase; ?>">
                    <td><input type="checkbox" name="Cla_ID<?php echo $i;?>"  name="Cla_ID<?php echo $i;?>" value="<?php echo $row['Cla_ID'];?>"></td>
                    <td><?php echo $row['Niv_Nombre']."->".$row['Ori_Nombre'];
                    if ($row['Cla_Baja']==1) echo "<br>[DADA DE BAJA]";
                     ?></td>
                    <td><?php echo $row['Cur_Siglas']; ?></td>
                    <td><?php echo $row['Div_Siglas']; ?></td>
                    <td><?php echo $row['Mat_Nombre']; ?></td>
                    <td><?php 
                    $Inscriptos = obtenerAlumnosCurso($LecID, $row['Cla_Niv_ID'], $row['Cla_Cur_ID'], $row['Cla_Div_ID'], $InscriptosBaja);
                    echo $Inscriptos; ?></td>
                    <td><?php echo traerHorarioClase($row['Cla_ID']); ?></td>
                    <td align="center">
                    <?php
                    if ($row['Mat_Convivencia']==0){
                    ?>
                        <button id="agregar<?php echo $i; ?>" title="Agregar Día y Horario">Horarios</button> 
                        <button id="eliminarHorarios<?php echo $i; ?>" title="Eliminar Horarios del docente">Eliminar horarios</button> 
                    <?php
                    }//fin if
                    ?>                
                        
                        <input type="hidden" id="id<?php echo $i; ?>" value="<?php echo $row['Cla_ID']; ?>" />
                        <input type="hidden" id="materia<?php echo $i; ?>" value="<?php echo $row['Mat_Nombre']; ?>" />
                        <?php if ($_SESSION['sesion_usuario']==38077890 || $_SESSION['sesion_usuario']=='superadmin') {?>
                         <button id="eliminar<?php echo $i; ?>" title="Dar de Baja la Clase del docente">Dar de Baja</button> 
                        <?php }?>
                        </td>
                </tr>
                <?php
            }//fin while
            ?>
        </table>
        <div>Seleccione las clases que desea cambiar por otro docente
        <button id="btnCambiarDocente">Cambiar Docente</button>  
        <?php cargarListadoDocentesActivo('Nuevo', $DocID);?>      
        </div>
        <?php
    }else {
        ?>
        <p class="titulo_noticia">No tiene clases asignadas.</p>
        <?php
    }//*/
    $sql = "SELECT * FROM
Colegio_Clase
INNER JOIN Colegio_Nivel 
ON (Colegio_Clase.Cla_Niv_ID = Colegio_Nivel.Niv_ID)
INNER JOIN Sede 
ON (Colegio_Clase.Cla_Sed_ID = Sed_ID)    
INNER JOIN Curso 
ON (Colegio_Clase.Cla_Cur_ID = Curso.Cur_ID)
INNER JOIN Division 
ON (Colegio_Clase.Cla_Div_ID = Division.Div_ID)
INNER JOIN Colegio_Materia 
ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID) 
    INNER JOIN Colegio_Orientacion 
        ON (Colegio_Materia.Mat_Ori_ID = Colegio_Orientacion.Ori_ID)
WHERE Cla_Lec_ID = '$LecID' AND Cla_Doc_ID = '$DocID' AND Cla_Baja = 1 
ORDER BY Niv_ID, Ori_ID, Cla_Baja, Mat_Convivencia, Mat_ID, Cur_ID, Div_ID, Mat_Nombre";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    //echo $sql;
    if (mysqli_num_rows($result) > 0) {
        ?>
        <p class="titulo_noticia">Clases dadas de baja</p>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center" class="borde_recuadro">
            <tr class="fila_titulo">
                <!--<td>Sede</td>-->
                <td>Nivel/Orientación</td>
                <td>Curso</td>
                <td>Div.</td>
                <td>Materia</td>                
            </tr>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                $i++;
                if (($i % 2) == 0)
                    $clase = "fila";
                else
                    $clase = "fila2";
                ?>
                <tr class="<?php echo $clase; ?>">
                    <!--<td><?php echo $row['Sed_Nombre']; ?></td>-->
                    <td><?php echo $row['Niv_Nombre']."->".$row['Ori_Nombre'];
                    if ($row['Cla_Baja']==1) echo "<br>[DADA DE BAJA]";
                     ?></td>
                    <td><?php echo $row['Cur_Siglas']; ?></td>
                    <td><?php echo $row['Div_Siglas']; ?></td>
                    <td><?php echo $row['Mat_Nombre']; ?></td>
                </tr>
                <?php
            }//fin while
            ?>
        </table>        
        <?php
    }/*else {
        ?>
        <p class="titulo_noticia">No tiene clases asignadas.</p>
        <?php
    }//*/
}//fin funcion

function eliminarClaseDocente() {
	//echo "Hola";exit;
	$ID = $_POST['ID'];
	//echo $ID;exit;
	$sql = "SELECT * FROM Colegio_Clase WHERE Cla_ID = $ID";

	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//no existe
		echo "La clase elegida no existe o ya fue eliminada.";
	} else {
		$seguir = true;
		/* $sql = "SELECT COUNT(*) AS TOTAL FROM Colegio_Horario WHERE Hor_Cla_ID = $ID";
		  $result_1 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		  $row = mysqli_fetch_array($result_1);
		  if ($row[TOTAL]>0){//Tiene provincias vinculadas
		  echo "No se puede eliminar porque tiene ". $row[TOTAL] ." horarios relacionados.";
		  $seguir = false;
		  }
		  $sql = "SELECT COUNT(*) AS TOTAL FROM Colegio_InscripcionClase WHERE IMa_Cla_ID = $ID";
		  $result_2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		  $row = mysqli_fetch_array($result_2);
		  if ($row[TOTAL]>0){//Tiene alumnos vinculados
		  echo "No se puede eliminar porque tiene ". $row[TOTAL] ." inscripciones relacionadas.";
		  $seguir = false;
		  }
		  // */
		$sql = "DELETE FROM Colegio_InscripcionClase WHERE IMa_Cla_ID = $ID";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "DELETE FROM Colegio_Horario WHERE Hor_Cla_ID = $ID";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

		if ($seguir) {
			//$sql = "DELETE FROM Colegio_Clase WHERE Cla_ID = $ID";
            $sql = "UPDATE Colegio_Clase SET Cla_Baja = 1 WHERE Cla_ID = $ID"; 
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "Se dió de Baja la clase seleccionada.";
		}
	}
}//fin funcion
function eliminarClaseHorarioDocente() {
	$ID = $_POST['ID'];
	$sql = "SELECT * FROM Colegio_Clase WHERE Cla_ID = $ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//no existe
		echo "La clase elegida no existe.";
	} else {
	  $sql = "DELETE FROM Colegio_Horario WHERE Hor_Cla_ID = $ID";
	  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  echo "Se eliminó los horarios de la clase seleccionada.";
	}
}//fin funcion

                    function cambiarUnidadUsuario() {
                        $_SESSION['sesion_SedID'] = $_POST['SedID'];
                        $_SESSION['sesion_UniID'] = $_POST['UniID'];
                        $_SESSION['sesion_Sede'] = $_POST['Sede'];
                        echo "Cambios realizados correctamente.";
                    }

//fin function

                    function verificarLongitudTexto() {
                        $Texto = $_POST['Texto'];
                        $Long = $_POST['Long'];
                        $Tipo = $_POST['Tipo'];
                        if (empty($Texto))
                            echo "Vac�o";
                        if ($Long > 0) {
                            if (strlen($Texto) < $Long)
                                echo "No cumple con la m�nima cantidad ($Long) de caracteres";
                        }
                        if (!empty($Tipo)) {
                            if (!is_numeric($Texto) && $Tipo == "Num")
                                echo "El N�mero ingresado no es n�merico";
                        }
                    }

                    function listarMateriaporTitulo() {
                        //echo "Hola";//exit;
                        $TitID = $_POST['TitID'];
                        $CarID = $_POST['CarID'];
                        $PlaID = $_POST['PlaID'];
                        $LegID = $_POST['LegID'];
                        $LecID = $_POST['LecID'];
                        $sql = "SELECT * FROM
    TituloMateria
    INNER JOIN Materia 
        ON (TituloMateria.TMa_Mat_ID = Materia.Mat_ID) AND (TituloMateria.TMa_Car_ID = Materia.Mat_Car_ID)
    INNER JOIN Curso 
        ON (Materia.Mat_Cur_ID = Curso.Cur_ID) WHERE TMa_Tit_ID = '$TitID' AND TMa_Car_ID = '$CarID' AND TMa_Pla_ID = '$PlaID' ORDER BY Cur_ID, Mat_ID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        //echo $sql;
                        if (mysqli_num_rows($result) > 0) {
                            ?>
                            <p class="titulo_noticia">Materias disponibles</p>
                            <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center" class="borde_recuadro">
                                <tr class="fila_titulo">
                                    <th>#</th>
                                    <th>C&oacute;d.</th>
                                    <th>Materia</th>
                                    <th>Curso</th>
                                    <th>Cond. Ingreso</th>
                                    <th>Periodo</th>
                                </tr>
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    $i++;
                                    if (($i % 2) == 0)
                                        $clase = "fila";
                                    else
                                        $clase = "fila2";
                                    $sql = "SELECT * FROM  InscripcionMateria 
			WHERE IMa_Leg_ID = $LegID
		    AND IMa_Lec_ID = $LecID
		    AND IMa_Pla_ID = $PlaID
			AND IMa_Mat_ID = $row[Mat_ID]
			AND IMa_Car_ID = $CarID;";
                                    $resultMat = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//echo $sql;
                                    if (mysqli_num_rows($resultMat) > 0) {
                                        $rowMat = mysqli_fetch_array($resultMat);
                                        $chq = "checked='checked'";
                                        $cond = $rowMat[IMa_ConI_ID];
                                        $prd = $rowMat[IMa_Prd_ID];
                                        $id = "mostrar$i";
                                    } else {
                                        $chq = "";
                                        $cond = "";
                                        $prd = "";
                                        $id = "ocultar$i";
                                    }
                                    ?>
                                    <tr class="<?php echo $clase; ?>" id="<?php echo $id; ?>">
                                        <td height="40"><input type="checkbox" id="MatID<?php echo $i; ?>" value="<?php echo $row[Mat_ID]; ?>" <?php echo $chq; ?> /><input type="hidden" id="CurID<?php echo $i; ?>" value="<?php echo $row[Mat_Cur_ID]; ?>" /></td>
                                        <td><?php echo $row[Mat_ID]; ?></td>
                                        <td><?php echo $row[Mat_Nombre]; ?></td>
                                        <td><?php echo $row[Cur_Siglas]; ?></td>
                                        <td><?php echo cargarListaCondiciones("ConID$i", $cond); ?></td>
                                        <td><?php echo cargarListaPeriodo("PrdID$i", $prd); ?></td>            
                                    </tr>
                                    <?php
                                }//fin while
                                ?>
                            </table>        
                            <?php
                        } else {
                            ?>
                            <p class="titulo_noticia">No existen materias asociadas al t&iacute;tulo elegido.</p>
                            <?php
                        }//*/
                    }

//fin funcion

                    function mostrarConstanciaInscripcionTerciario() {

                        $PerID = $_POST['PerID'];
                        $LegID = $_POST['LegID'];
                        $LecID = $_POST['LecID'];
                        $CarID = $_POST['CarID'];
                        $PlaID = $_POST['PlaID'];
                        //echo "Hola";exit;
                        //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
                        $sql = "SELECT 
	PersonaTutor.Per_DNI AS DNI
    , PersonaTutor.Per_Apellido AS Apellido
    , PersonaTutor.Per_Nombre AS Nombre 
	, Persona.*
	, Legajo.*
	, InscripcionAsegurado.*
	, Car_Nombre
	, Pla_Nombre
	, Lec_Nombre
	, Ins_Fecha, Ins_Hora
	, Doc_Nombre
	, Ins_SinCursar
	FROM     InscripcionAsegurado
    INNER JOIN Inscripcion 
        ON (InscripcionAsegurado.Ase_Leg_ID = Inscripcion.Ins_Leg_ID) AND (InscripcionAsegurado.Ase_Car_ID = Inscripcion.Ins_Car_ID) AND (InscripcionAsegurado.Ase_Pla_ID = Inscripcion.Ins_Pla_ID) AND (InscripcionAsegurado.Ase_Lec_ID = Inscripcion.Ins_Lec_ID)
    INNER JOIN Legajo 
        ON (Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
  INNER JOIN Carrera 
        ON (Inscripcion.Ins_Car_ID = Carrera.Car_ID)
    INNER JOIN Plan 
	    INNER JOIN Lectivo 
        ON (Inscripcion.Ins_Lec_ID = Lectivo.Lec_ID)
        ON (Inscripcion.Ins_Pla_ID = Plan.Pla_ID)		
    INNER JOIN Persona AS PersonaTutor
        ON (InscripcionAsegurado.Ase_Tutor_Per_ID = PersonaTutor.Per_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
	 INNER JOIN PersonaDocumento 
        ON (PersonaTutor.Per_Doc_ID = PersonaDocumento.Doc_ID)
WHERE Ins_Lec_ID = $LecID AND Ins_Leg_ID = $LegID AND Ins_Car_ID = $CarID AND Ins_Pla_ID = $PlaID";
                        //echo "$sql<br />";exit;
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {//ya existe, mostramos la inscripcion
                            ?>
                            <link href="css/general.css" rel="stylesheet" type="text/css">
                            <table width="550px" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
                                <tr>
                                    <td colspan="2" align="center"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                            <tr>
                                                <td width="50%"><div align="center"><img src="logos/logo_stamaria.jpg" width="90" height="106" /></div></td>
                                                <td><div align="center"><img src="logos/logo_Giteco.png" width="161" height="34" /></div></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center"><hr size="1" color="#999999">
                                                    <p><span class="noticia_titulo">Constancia de Inscripci�n Provisoria</span><br />
                                                    </p></td>
                                            </tr>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td colspan="2" align="center"><span class="texto">Fecha Inscripci�n: <?php echo cfecha($row[Ins_Fecha]); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><hr size="1" color="#999999"  /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center"><span class="titulo_noticia">Datos del Alumno</span></td>
                                    </tr>
                                    <tr>
                                        <td width="155" align="right">
                                            <span class="texto">Nombre y Apellido:</span></td>
                                        <td width="388"><span class="texto"> <strong><?php echo $row[Per_Nombre] . " " . $row[Per_Apellido]; ?></strong></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <span class="texto">D.N.I.:</span></td>
                                        <td><span class="texto"> <strong><?php echo $row[Per_DNI]; ?></strong></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <span class="texto">Carrera:</span></td>
                                        <td><span class="texto"> <strong><?php echo "($row[Pla_Nombre]) $row[Car_Nombre]"; ?></strong></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <span class="texto">Ciclo Lectivo:</span></td>
                                        <td><span class="texto"> <strong><?php echo $row[Lec_Nombre]; ?></strong></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><hr size="1" color="#999999"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center"><span class="titulo_noticia">Datos del Asegurado</span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <span class="texto">Nombre y Apellido:</span></td>
                                        <td><span class="texto"> <strong><?php echo $row[Nombre] . " " . $row[Apellido]; ?></strong></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <span class="texto"><?php echo $row[Doc_Nombre]; ?>:</span></td>
                                        <td><span class="texto"> <strong><?php echo $row[DNI]; ?></strong></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><hr size="1" color="#999999"></td>
                                    </tr>  
                                    <tr>
                                        <td colspan="2" align="center"><span class="titulo_noticia">Datos de Acceso a <strong>GITECO</strong></span></td>
                                    </tr>
                                    <tr class="texto">
                                        <td align="right">Portal de acceso:</td>
                                        <td align="left"><strong>www.uccuyo.edu.ar/uccdigital</strong></td>
                                    </tr>
                                    <tr class="texto">
                                        <td align="right">Usuario:</td>
                                        <td align="left"><strong><?php echo $row[Per_DNI]; ?></strong></td>
                                    </tr>
                                    <tr class="texto">
                                        <td align="right">Clave:</td>
                                        <td align="left"><strong><?php echo $row[Per_DNI]; ?></strong></td>
                                    </tr>
                                    <?php
                                }//fin while
                                ?>
                                <tr>
                                    <td colspan="2"><hr size="1" color="#999999"></td>
                                </tr>

                                <tr>
                                    <td colspan="2" align="center"><span class="titulo_noticia">    Materias inscriptas</span></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <?php
                                        if ($row[Ins_SinCursar] == 1) {
                                            echo "El alumno se encuentra inscripto s&oacute;lo para rendir ex&aacute;menes finales.";
                                        } else {
                                            $sql = "SELECT * FROM
		InscripcionMateria
		INNER JOIN Division 
			ON (InscripcionMateria.IMa_Div_ID = Division.Div_ID)
		INNER JOIN Periodo 
			ON (InscripcionMateria.IMa_Prd_ID = Periodo.Prd_ID)
		INNER JOIN Condicion 
			ON (InscripcionMateria.IMa_ConI_ID = Condicion.Con_ID)
		 INNER JOIN Materia 
			ON (InscripcionMateria.IMa_Mat_ID = Materia.Mat_ID) AND (InscripcionMateria.IMa_Car_ID = Materia.Mat_Car_ID)
		INNER JOIN Curso 
			ON (Materia.Mat_Cur_ID = Curso.Cur_ID)
	WHERE IMa_Lec_ID = $LecID AND IMa_Leg_ID = $LegID AND IMa_Car_ID = $CarID AND IMa_Pla_ID = $PlaID ORDER BY Cur_ID, Mat_ID;";
                                            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                            if (mysqli_num_rows($result) > 0) {
                                                ?>

                                                <table width="95%" border="0" align="center" cellpadding="1" cellspacing="1">
                                                    <tr class="fila_titulo">
                                                        <th scope="col">C&oacute;d.</th>
                                                        <th scope="col">Materia</th>
                                                        <th scope="col">Curso</th>
                                                        <th scope="col">Cond. Ingreso</th>
                                                        <th scope="col">Periodo</th>
                                                    </tr>
                                                    <?php
                                                    $sql = "SELECT * FROM
		InscripcionMateria
		INNER JOIN Division 
			ON (InscripcionMateria.IMa_Div_ID = Division.Div_ID)
		INNER JOIN Periodo 
			ON (InscripcionMateria.IMa_Prd_ID = Periodo.Prd_ID)
		INNER JOIN Condicion 
			ON (InscripcionMateria.IMa_ConI_ID = Condicion.Con_ID)
		 INNER JOIN Materia 
			ON (InscripcionMateria.IMa_Mat_ID = Materia.Mat_ID) AND (InscripcionMateria.IMa_Car_ID = Materia.Mat_Car_ID)
		INNER JOIN Curso 
			ON (Materia.Mat_Cur_ID = Curso.Cur_ID)
	WHERE IMa_Lec_ID = $LecID AND IMa_Leg_ID = $LegID AND IMa_Car_ID = $CarID AND IMa_Pla_ID = $PlaID;";
                                                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        $i++;
                                                        if (($i % 2) == 0)
                                                            $clase = "fila";
                                                        else
                                                            $clase = "fila2";
                                                        ?>
                                                        <tr class="<?php echo $clase; ?>">
                                                            <td><?php echo $row[Mat_ID]; ?></td>
                                                            <td><?php echo $row[Mat_Nombre];
                                                        ?></td>
                                                            <td align="center"><?php echo $row[Cur_Siglas]; ?></td>
                                                            <td align="center"><?php echo $row[Con_Siglas]; ?></td>
                                                            <td align="center"><?php echo $row[Prd_Siglas]; ?></td>
                                                        </tr>
                                                        <?php
                                                    }//fin while
                                                    ?>
                                                </table>
                                                <?php
                                            }else {//no tiene materias inscriptas
                                                ?>
                                                El alumno no tiene materias inscriptas.
                                                <?php
                                            }
                                        }//fin del else de que el alumno se encuentra inscripto s�lo para rendir examenes
                                        ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><span class="titulo_noticia">    Requisitos presentados</span></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><table width="95%" border="0" align="center" cellpadding="1" cellspacing="1">
                                            <tr class="fila_titulo">
                                                <th scope="col">#</th>
                                                <th scope="col">Requisito</th>
                                                <th scope="col">Constancia</th>
                                                <th scope="col">Definitivo</th>
                                                <th scope="col">Fecha</th>
                                            </tr>
                                            <?php
                                            $sql = "SELECT * FROM
    RequisitoPresentado
    INNER JOIN Requisito 
        ON (RequisitoPresentado.Pre_Req_ID = Requisito.Req_ID) AND (RequisitoPresentado.Pre_Niv_ID = Requisito.Req_Niv_ID)
WHERE (Requisito.Req_Niv_ID = 4
    AND RequisitoPresentado.Pre_Per_ID = $PerID);";
                                            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                            while ($row = mysqli_fetch_array($result)) {
                                                $i++;
                                                if (($i % 2) == 0)
                                                    $clase = "fila";
                                                else
                                                    $clase = "fila2";
                                                ?>
                                                <tr class="<?php echo $clase; ?>">
                                                    <td><?php echo $row[Req_ID]; ?></td>
                                                    <td><?php
                                                echo $row[Req_Nombre];
                                                if ($row[Req_Obligatorio] == 1 && $row[Req_Inscripcion] == 1)
                                                    echo " *";
                                                ?></td>
                                                    <td align="center"><?php if ($row[Pre_Constancia] == 1) echo "X"; ?></td>
                                                    <td align="center"><?php if ($row[Pre_Presento] == 1) echo "X"; ?></td>
                                                    <td align="center"><?php echo cfecha($row[Pre_Fecha]); ?></td>
                                                </tr>
                                                <?php
                                            }//fin while
                                            ?>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td style="font:'Times New Roman', Times, serif; font-size:10px"><br />
                                        <!--Nota: La inscripci&oacute;n ser&aacute; definitiva cuando se acredite el pago de la matr&iacute;cula. El turno est&aacute; sujeto a modificaci&oacute;n en funci&oacute;n del cupo. * Son requisitos obligatorios para la inscripci&oacute;n.--></td>
                                    <td align="right" style="font:'Times New Roman', Times, serif; font-size:10px"><?php echo date("d/m/Y"); ?></td>
                                </tr>
                            </table>

                            <?php
                        }else {
                            echo "El alumno todavia no se encuentra inscripto en el Ciclo Lectivo seleccionado.";
                        }
//*/
                    }

//fin funcion

                    function guardarAutoridad() {
//echo "Hola";//exit;
                        $Nombre = $_POST['Nombre'];
                        $AutID = $_POST['AutID'];
                        $UniID = $_POST['UniID'];
                        $Cargo = utf8_decode($_POST['Cargo']);
                        $Email = $_POST['Email'];
                        $Orden = $_POST['Orden'];

                        $Nombre = trim(utf8_decode($Nombre));
                        $sql = "SELECT * FROM Autoridad WHERE Aut_ID = '$AutID'";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {//ya existe con ese nombre lo actualizamos
                            $sql = "UPDATE Autoridad SET Aut_Nombre = '$Nombre', Aut_Uni_ID = $UniID, Aut_Cargo = '$Cargo', Aut_Email = '$Email', Aut_Orden = '$Orden' WHERE Aut_ID = $AutID";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se modificaron los valores correctamente de la autoridad <strong>$Nombre ($Cargo)</strong>";
//echo $sql;
                        } else {
                            $sql = "INSERT INTO Autoridad (Aut_Nombre, Aut_Uni_ID, Aut_Cargo, Aut_Email, Aut_Orden) VALUES ('$Nombre', $UniID, '$Cargo', '$Email', '$Orden')";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se agreg� correctamente la nueva autoridad <strong>$Nombre ($Cargo)</strong>";
                        }
                    }

//fin funcion

                    function EnviarCorreoAutoridad() {

                        $AutID = $_POST['AutID'];
                        if (empty($AutID)) {
                            echo "Por favor seleccione una autoridad para enviar su consulta";
                            exit;
                        }
                        $Asunto = $_POST['Asunto'];
                        if (empty($Asunto)) {
                            echo "Por favor escriba un asunto para poder enviar su consulta";
                            exit;
                        }
                        $Consulta = $_POST['Consulta'];
                        if (empty($Consulta)) {
                            echo "Por favor escriba su consulta antes de continuar";
                            exit;
                        }

                        $Asunto = "Nueva consulta enviada desde GITECO: " . $_POST['Asunto'];
                        $Consulta = "<i>Esta consulta fue enviada por el usuario " . $_SESSION['sesion_usuario'] . " (" . $_SESSION['sesion_nombrerol'] . ") a las " . date("H:i:s") . " del d&iacute;a " . date("d-m-Y") . "</i><br />----------------------------------------------------------------------------------------------------------------------------------------<br />";
                        $Consulta .= $_POST['Consulta'];
                        $sql = "SELECT * FROM Autoridad WHERE Aut_ID = $AutID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_array($result);
                            $Email = $row[Aut_Email];
                            $Nombre = $row[Aut_Nombre];
                            $Cargo = $row[Aut_Cargo];
                            if (empty($Email))
                                $Email = "infocolegio@uccuyo.edu.ar";
                            $Email = "fabricioeche@gmail.com";
                            $nombreFrom = $_SESSION['sesion_persona'];
                            if (empty($_SESSION['sesion_email']))
                                $from = $Email;
                            else
                                $from = $_SESSION['sesion_email'];
                            if (!enviarEmail($Asunto, $Consulta, $Email, $Nombre, $from, $nombreFrom)) {
                                echo "Su consulta no pudo ser enviada. Ocurri� un error inesperado. Intente nuevamente en unos minutos.";
                            } else {
                                echo "Su consulta se realiz� correctamente.";
                            }
                        }//*/
                    }

//fin function
//Esta funcion muestra los horarios que tiene cargada la clase
function agregarHorarioClaseDocente() {
	?>
	<style type="text/css">@import "js/time/jquery.timeentry.css";</style> 
	<script type="text/javascript" src="js/time/jquery.timeentry.js"></script>
	<script language="javascript">
		$(document).ready(function(){

			$.timeEntry.setDefaults($.timeEntry.regional['es']);
			$("#horaInicio").timeEntry({show24Hours: true, showSeconds: false, spinnerImage: 'js/time/spinnerOrange.png'}, $.timeEntry.regional['es']);
			$("#horaFin").timeEntry({show24Hours: true, showSeconds: false, spinnerImage: 'js/time/spinnerOrange.png'});
			$("#botGuardar").button();
							
			$("#botGuardar").click(function(evento){
				//alert("");
				evento.preventDefault();
				vDiaID = $("#DiaID option:selected").val();
				vhoraInicio = $("#horaInicio").val();
				vhoraFin = $("#horaFin").val();
				//alert(vDiaID);return;
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "guardarHorarioClase", ClaID: vClaID, DiaID: vDiaID, horaInicio: vhoraInicio, horaFin: vhoraFin},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//$("#Resultado").html(data);
						$("#barraRefrescar").click();
						jAlert(data, "Resultado de guardar");
						//alert(vDocID);
						//alert(vLecID);
						
										
					}
				});//fin ajax//*/
			});
							
		});
	</script>

	<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
		<tr>
			<td colspan="2"><div id="Resultado"></div></td>
		</tr>
		<tr>
			<td>Día</td>
			<td><select name="DiaID" id="DiaID">
					<?php
					$sql = "SET NAMES UTF8;";
					consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
					$sql = "SELECT * FROM Colegio_Dia WHERE Dia_ID >0 AND Dia_ID <6";
					$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_array($result)) {
							?>
							<option value="<?php echo $row['Dia_ID']; ?>"><?php echo $row['Dia_Nombre']; ?></option>
							<?php
						}//fin while
					}//fin if
					?>

				</select></td>
		</tr>
		<tr>
			<td>Hora de inicio</td>
			<td><input name="horaInicio" type="text" id="horaInicio" size="6"></td>
		</tr>
		<tr>
			<td>Hora de finalización</td>
			<td><input name="horaFin" type="text" id="horaFin" size="6"></td>
		</tr>
		<tr>
			<td><button id="botGuardar">Guardar</button></td>
			<td>&nbsp;</td>
		</tr>
	</table>


	<?php

}//fin function

function guardarHorarioClase() {

	$ClaID = $_POST['ClaID'];
	$DiaID = $_POST['DiaID'];
	$horaInicio = $_POST['horaInicio'];
	$horaFin = $_POST['horaFin'];

	//echo "Valor hora Inicio: $DiaID - $horaInicio a $horaFin";
	if (empty($horaInicio)) {
		echo "Por favor escriba una hora de Inicio";
		exit;
	}
	if (empty($horaFin)) {
		echo "Por favor escriba una hora de Finalización";
		exit;
	}

	$sql = "SELECT * FROM Colegio_Horario WHERE Hor_Cla_ID = $ClaID AND Hor_Dia_ID = $DiaID AND Hor_Inicio = '$horaInicio'";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {//ya existe 
		echo "El horario que intenta guardar ya existe para el día seleccionado. <br />Intente con otro día o elimine el día antes de continuar.";
	} else {
		$sql = "INSERT INTO Colegio_Horario (Hor_Cla_ID, Hor_Dia_ID, Hor_Inicio, Hor_Fin) VALUES ($ClaID, $DiaID,  '$horaInicio', '$horaFin')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//echo $sql;
		echo "Se guardó correctamente el horario de la clase";
	}//*/
}

                    function guardarTrimestre() {
                        //echo "Hola";//exit;
                        $Nombre = $_POST['Nombre'];
                        $TriID = $_POST['TriID'];
                        $NivID = $_POST['NivID'];
                        $LecID = $_POST['LecID'];
                        $FechaDesde = cambiaf_a_mysql($_POST['FechaDesde']);
                        $FechaHasta = cambiaf_a_mysql($_POST['FechaHasta']);

                        $Nombre = arreglarCadenaMayuscula(trim(utf8_decode(strtoupper($Nombre))));
                        $sql = "SELECT * FROM Colegio_Trimestre WHERE Tri_ID = '$TriID'";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {//ya existe lo actualizamos
                            $sql = "UPDATE Colegio_Trimestre SET Tri_Nombre = '$Nombre', Tri_Desde = '$FechaDesde', Tri_Hasta = '$FechaHasta' WHERE Tri_ID = $TriID";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se modificaron los valores correctamente del trimestre <strong>($TriID) $Nombre</strong>";
                            //echo $sql;
                        } else {
                            $sql = "INSERT INTO Colegio_Trimestre (Tri_Nombre, Tri_Niv_ID, Tri_Lec_ID, Tri_Desde, Tri_Hasta) VALUES ('$Nombre', $NivID, $LecID, '$FechaDesde', '$FechaHasta')";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se agregó correctamente el nuevo trimestre <strong>$Nombre</strong>";
                        }
                    }

//fin funcion
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function guardarConfiguracionCuota() {
	//echo "Hola";exit;

	//$CMo_Uni_ID = $_POST['CMo_Uni_ID'];
	$CMo_Niv_ID = $_POST['CMo_Niv_ID'];
	$CMo_Lectivo = $_POST['CMo_Lectivo'];
	$CTi_ID = $_POST['CMo_CTi_ID'];
	$CMo_Usu_ID = $_POST['CMo_Usu_ID'];
	$Alt_ID = $_POST['CMo_Alt_ID'];
	$CMo_Fecha = date("Y-m-d");//cambiaf_a_mysql($_POST['CMo_Fecha']);
	$CMo_Hora = date("H:i:s");//cambiaf_a_mysql($_POST['CMo_Hora']);
	$CMo_CantCuotas = $_POST['CMo_CantCuotas'];
    $CMo_Numero = $_POST['CMo_Numero'];
	$CMo_Importe = $_POST['CMo_Importe'];
	$CMo_1er_Recargo = $_POST['CMo_1er_Recargo'];
	$CMo_2do_Recargo = $_POST['CMo_2do_Recargo'];
	$CMo_1er_Vencimiento = cambiaf_a_mysql($_POST['CMo_1er_Vencimiento']);
	$CMo_2do_Vencimiento = cambiaf_a_mysql($_POST['CMo_2do_Vencimiento']);
	$CMo_3er_Vencimiento = cambiaf_a_mysql($_POST['CMo_3er_Vencimiento']);

    if (empty($_POST['CMo_2do_Vencimiento'])) $CMo_2do_Vencimiento = $CMo_1er_Vencimiento;
    if (empty($_POST['CMo_3er_Vencimiento'])) $CMo_3er_Vencimiento = $CMo_1er_Vencimiento;


	$CMo_Mes = $_POST['CMo_Mes'];
	$CMo_Anio = $_POST['CMo_Anio'];
	$CMo_Recargo_Mensual = $_POST['CMo_Recargo_Mensual'];

   //Mario 04/04/2022. para cuota agrupada y especial
    $CMo_Agrupa = 0;
    $CMo_Especial = 0;
    //echo "CMo_Agrupa ".$_POST['CMo_Agrupa']."<br />";
    //echo "CMo_Especial ".$_POST['CMo_Especial']."<br />";

    if($_POST['CMo_Agrupa']==1){
        $CMo_Agrupa = 1;
    }
    if($_POST['CMo_Especial']==1){
        $CMo_Especial = 1;
    }
    //***********************************************

	$sql = "SELECT * FROM CuotaModelo WHERE CMo_Lec_ID = $CMo_Lectivo AND CMo_Niv_ID = $CMo_Niv_ID AND CMo_CTi_ID = $CTi_ID AND CMo_Alt_ID = $Alt_ID AND CMo_Numero=$CMo_Numero";
	//echo $sql;exit;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		echo "ERROR: Ya existe una configuración de cuotas para el Ciclo Lectivo, Nivel, Tipo de Cuota y Alternativa";
		exit;
	}
	
    $sql = "INSERT INTO CuotaModelo (CMo_Lec_ID, CMo_Niv_ID, CMo_CTi_ID, CMo_Alt_ID, CMo_Usu_ID, CMo_Fecha, CMo_Hora, CMo_CantCuotas,
    CMo_Importe, CMo_1er_Vencimiento,   CMo_2do_Vencimiento, CMo_3er_Vencimiento, CMo_Mes, 
    CMo_Anio, CMo_1er_Recargo, CMo_2do_Recargo, CMo_Recargo_Mensual, CMo_Numero, CMo_Agrupa, CMo_Especial) VALUES ($CMo_Lectivo,'$CMo_Niv_ID', '$CTi_ID', '$Alt_ID', '$CMo_Usu_ID', '$CMo_Fecha', '$CMo_Hora', 
    $CMo_CantCuotas, '$CMo_Importe', '$CMo_1er_Vencimiento', '$CMo_2do_Vencimiento', '$CMo_3er_Vencimiento', 
    $CMo_Mes,$CMo_Anio, '$CMo_1er_Recargo', '$CMo_2do_Recargo', '$CMo_Recargo_Mensual', '$CMo_Numero', '$CMo_Agrupa', '$CMo_Especial')";
	//echo $sql;
	$result=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if ($result) echo "Se agregó correctamente la nueva configuración";
    else echo "Hubo un error al guardar!";
}

                    /*function guardarAsignacionCuota() {

                        $Cuo_Per_ID = $_POST['Cuo_Per_ID'];
                        $Cuo_Lec_ID = $_POST['Cuo_Lec_ID'];
                        $Cuo_Niv_ID = $_POST['Cuo_Niv_ID'];
                        $Cuo_Alt_ID = $_POST['Cuo_Alt_ID'];
						$Cuo_CTi_ID = $_POST['Cuo_CTi_ID'];

                        $sql = "SELECT * FROM CuotaModelo WHERE CMo_Lec_ID = $Cuo_Lec_ID AND CMo_Niv_ID = $Cuo_Niv_ID AND CMo_CTi_ID = $Cuo_CTi_ID AND CMo_Alt_ID = $Cuo_Alt_ID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        

                        while ($row2 = mysqli_fetch_array($result)) {
                            $cant_cuo = $row2[CMo_CantCuotas];

                            for ($i = 1; $i <= $cant_cuo; $i++) {
                                
                                if($i >= 1){
                                    $mes = 3 + $i - 1;
                                    
                                $sql = "INSERT IGNORE INTO CuotaPersona (Cuo_Per_ID, Cuo_Uni_ID,Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lectivo, Cuo_Alt_ID, Cuo_Numero, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado)
                                values($Cuo_Per_ID, $row2[CMo_Uni_ID], $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $Cuo_Lectivo, $row2[CMo_Alt_ID], $i, $row2[CMo_Usu_ID], '', '', $row2[CMo_Importe], $row2[CMo_1er_Recargo], $row2[CMo_2do_Recargo], $row2[CMo_1er_Vencimiento], $row2[CMo_2do_Vencimiento], $row2[CMo_3er_Vencimiento], $mes, $row2[CMo_Anio], '0','0','0')";
                                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                }
                                else {
                                $sql = "INSERT IGNORE INTO cuotapersona (Cuo_Per_ID, Cuo_Uni_ID,Cuo_Niv_ID, Cuo_CTi_ID, Cuo_Lectivo, Cuo_Alt_ID, Cuo_Numero, Cuo_Usu_ID, Cuo_Fecha, Cuo_Hora, Cuo_Importe, Cuo_1er_Recargo, Cuo_2do_Recargo, Cuo_1er_Vencimiento, Cuo_2do_Vencimiento, Cuo_3er_Vencimiento, Cuo_Mes, Cuo_Anio, Cuo_Pagado, Cuo_Cancelado, Cuo_Anulado)
                                values($Cuo_Per_ID, $Cuo_Uni_ID, $row2[CMo_Niv_ID], $Cuo_Niv_ID, $Cuo_Lectivo, $row2[CMo_Alt_ID], $row2[CMo_CantCuotas], $row2[CMo_Usu_ID], '', '', $row2[CMo_Importe], $row2[CMo_1er_Recargo], $row2[CMo_2do_Recargo], $row2[CMo_1er_Vencimiento], $row2[CMo_2do_Vencimiento], $row2[CMo_3er_Vencimiento], $row2[CMo_Mes], $row2[CMo_Anio], '0','0','0')";
                                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                               }
                            }
                        }


                        echo "Se agregó correctamente la nueva configuración";
                    }*/

                    function cargaMes($m) {
                        ?><select name="CMo_Mes" id="CMo_Mes">
                            <?php foreach (range('12', '1') as $m) : ?>
                                <option value="<?php echo $m; ?>" <?php
                        if (date('n') == $m) {
                            echo 'selected="selected"';
                        }
                                ?>>                                    
                                            <?php
                                            if ($m == '1')
                                                echo "Enero";
                                            else if ($m == '2')
                                                echo "Febrero";
                                            else if ($m == '3')
                                                echo "Marzo";
                                            else if ($m == '4')
                                                echo " Abril";
                                            else if ($m == '5')
                                                echo "Mayo";
                                            else if ($m == '6')
                                                echo"Junio";
                                            else if ($m == '7')
                                                echo "Julio";
                                            else if ($m == '8')
                                                echo "Agosto";
                                            else if ($m == '9')
                                                echo "Septiembre";
                                            else if ($m == '10')
                                                echo "Octubre";
                                            else if ($m == '11')
                                                echo "Noviembre";
                                            else if ($m == '12')
                                                echo "Diciembre";
                                            ?>
                                </option>
                        <?php endforeach; ?>
                        </select><?php
                    }

                    function cargaListado() {
                        $CMo_Uni_ID = $_POST['CMo_Uni_ID'];
                        $CMo_Niv_ID = $_POST['CMo_Niv_ID'];
                        $CMo_Lectivo = $_POST['CMo_Lectivo'];
                        $CTi_ID = $_POST['CTi_ID'];
//                        $CMo_Usu_ID = $_SESSION['sesion_UsuID'];
                        $Alt_ID = $_POST['Alt_ID'];
//                        $CMo_Hora = cambiaf_a_mysql($_POST['CMo_Hora']);
                        $CMo_CantCuotas = $_POST['CMo_CantCuotas'];
                        $CMo_Importe = $_POST['CMo_Importe'];
                        $CMo_1er_Recargo = $_POST['CMo_1er_Recargo'];
                        $CMo_2do_Recargo = $_POST['CMo_2do_Recargo'];
                        $CMo_Mes = $_POST['CMo_Mes'];
                        echo $CMo_Anio = $_POST['CMo_Anio'];
                        ?>
                        <div>   
                            <table>
                                <thead>
                                    <tr class="ui-widget-header">
                                        <th align="center">Unidad</th>
                                        <th align="center">Nivel</th>
                                        <th align="center">Lectivo</th>
                                        <th align="center">Tipo de Cuota</th>
                                        <th align="center">Alternativa de Cuota</th>
                                        <th align="center">Usuario</th>
                                        <th align="center">Fecha Actual</th>
                                        <th align="center">Hora Actual</th>
                                        <th align="center">Cant. Cuotas</th>
                                        <th align="center">Importe</th>
                                        <th align="center">1º Recargo</th>
                                        <th align="center">2º Recargo</th>
                                        <th align="center">Mes</th>
                                        <th align="center">Año</th>
                                    </tr>
                                <tbody>
                                    <tr>
                                        <?php
                                        $sql = "SELECT uni_nombre FROM Unidad where Uni_ID = $CMo_Uni_ID";
                                        $resu = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                        $res = mysqli_fetch_array($resu)
                                        ?>
                                        <td align="center"><?php echo $res[uni_nombre]; ?></td>
                                        <?php
                                        $sql1 = "SELECT niv_nombre FROM Colegio_Nivel where Niv_ID =  $CMo_Niv_ID";
                                        $resul = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
                                        $result = mysqli_fetch_array($resul)
                                        ?>
                                        <td align="center"><?php echo $result[niv_nombre]; ?></td>
                                        <?php
                                        $sql2 = "SELECT Lec_Nombre FROM Lectivo where Lec_ID =  $CMo_Lectivo";
                                        $resulta = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
                                        $resultado = mysqli_fetch_array($resulta)
                                        ?>
                                        <td align="center"><?php echo $resultado[Lec_Nombre]; ?></td>
                                        <?php
                                        $sql3 = "SELECT CTi_Nombre FROM CuotaTipo where CTi_ID =  $CTi_ID";
                                        $resultad = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
                                        $resultado1 = mysqli_fetch_array($resultad)
                                        ?>

                                        <td align="center"><?php echo $resultado1[CTi_Nombre]; ?></td>
                                        <td align="center"><?php echo $Alt_ID; ?></td>
                                        <td><?php echo $_SESSION['sesion_usuario']; ?></td>
                                        <td align="center"><?php echo date("d/m/Y"); ?></td>
                                        <td align="center"><?php echo date("H:i:s"); ?></td>
                                        <td align="center"><?php echo $CMo_CantCuotas; ?></td>
                                        <td align="center"><?php echo $CMo_Importe; ?></td>
                                        <td align="center"><?php echo $CMo_1er_Recargo; ?></td>
                                        <td align="center"><?php echo $CMo_2do_Recargo; ?></td>                           
                                        <td align="center">
                                            <?php
                                            if ($CMo_Mes == '1')
                                                echo "Enero";
                                            else if ($CMo_Mes == '2')
                                                echo "Febrero";
                                            else if ($CMo_Mes == '3')
                                                echo "Marzo";
                                            else if ($CMo_Mes == '4')
                                                echo " Abril";
                                            else if ($CMo_Mes == '5')
                                                echo "Mayo";
                                            else if ($CMo_Mes == '6')
                                                echo"Junio";
                                            else if ($CMo_Mes == '7')
                                                echo "Julio";
                                            else if ($CMo_Mes == '8')
                                                echo "Agosto";
                                            else if ($CMo_Mes == '9')
                                                echo "Septiembre";
                                            else if ($CMo_Mes == '10')
                                                echo "Octubre";
                                            else if ($CMo_Mes == '11')
                                                echo "Noviembre";
                                            else if ($CMo_Mes == '12')
                                                echo "Diciembre";
                                            ?>
                                        </td>
                                        <td align="center"><?php echo $CMo_Anio; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }

                     

                    function eliminarTrimestre() {
                        //echo "Hola";exit;
                        $ID = $_POST['ID'];

                        $sql = "SELECT * FROM Colegio_Trimestre WHERE Tri_ID = $ID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) == 0) {//no existe
                            echo "El periodo o trimestre elegido no existe o ya fue eliminado.";
                        } else {
                            $sql = "SELECT COUNT(*) AS TOTAL FROM Colegio_Instancia WHERE Cia_Tri_ID = $ID";
                            $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            $row = mysqli_fetch_array($result_prov);
                            if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
                                echo "No se puede eliminar porque tiene " . $row[TOTAL] . " instancias de evaluaci�n relacionadas.";
                            } else {
                                $sql = "DELETE FROM Colegio_Trimestre WHERE Tri_ID = $ID";
                                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                echo "Se elimin� el periodo o trimestre seleccionado.";
                            }
                        }
                    }

//fin funcion
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    function eliminarConfiguracionCuota() {
                        //echo "Hola";exit;
                        $CMo_Uni_ID = $_POST['CMo_Uni_ID'];
                        $CMo_Niv_ID = $_POST['CMo_Niv_ID'];
                        $CMo_CTi_ID = $_POST['CMo_CTi_ID'];
                        $CMo_Alt_ID = $_POST['CMo_Alt_ID'];

                        $sql = "SELECT * FROM cuotamodelo WHERE CMo_Uni_ID = $CMo_Uni_ID and CMo_Niv_ID = $CMo_Niv_ID and CMo_CTi_ID = $CMo_CTi_ID and CMo_Alt_ID = $CMo_Alt_ID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) == 0) {//no existe
                            echo "La configuracion elegida no existe o ya fue eliminado.";
                        } else {
                            $sql = "SELECT * FROM cuotamodelo WHERE CMo_Uni_ID = $CMo_Uni_ID and CMo_Niv_ID = $CMo_Niv_ID and CMo_CTi_ID = $CMo_CTi_ID and CMo_Alt_ID = $CMo_Alt_ID";
                            $Resultado = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            $row = mysqli_fetch_array($Resultado);
                            if ($row[cuotamodelo] > 0) {//Tiene provincias vinculadas			
                                echo "No se puede eliminar porque tiene " . $row[cuotamodelo] . " instancias de evaluación relacionadas.";
                            } else {
                                $sql = "DELETE FROM cuotamodelo WHERE CMo_Uni_ID = $CMo_Uni_ID and CMo_Niv_ID = $CMo_Niv_ID and CMo_CTi_ID = $CMo_CTi_ID and CMo_Alt_ID = $CMo_Alt_ID";
                                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                echo "Se eliminó la configuración seleccionado.";
                            }
                        }
                    }

//fin funcion

                    function guardarInstanciaTrimestre() {
                        //echo "Hola";//exit;
                        $Nombre = $_POST['Nombre'];
                        $TriID = $_POST['TriID'];
                        $CiaID = $_POST['CiaID'];
                        $Orden = $_POST['Orden'];
                        $Opcional = $_POST['Opcional'];
                        obtenerTrimestreNivelLectivo($TriID, $LecID, $NivID);

                        $Nombre = arreglarCadenaMayuscula(trim(utf8_decode(strtoupper($Nombre))));
                        $sql = "SELECT * FROM Colegio_Instancia WHERE Cia_ID = '$CiaID'";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {//ya existe lo actualizamos
                            $sql = "UPDATE Colegio_Instancia SET Cia_Nombre = '$Nombre', Cia_Orden = $Orden, Cia_Opcional = $Opcional WHERE Cia_ID = $CiaID";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se modificaron los valores correctamente de la instancia del trimestre <strong>($CiaID) $Nombre</strong>";
                            //echo $sql;
                        } else {
                            $sql = "INSERT INTO Colegio_Instancia (Cia_Nombre, Cia_Tri_ID, Cia_Niv_ID, Cia_Lec_ID, Cia_Orden, Cia_Opcional) VALUES ('$Nombre', $TriID, $NivID, $LecID, $Orden, $Opcional)";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se agreg� correctamente la nueva instancia <strong>$Nombre</strong>";
                        }
                    }

//fin funcion

                    function eliminarInstanciaTrimestre() {
                        //echo "Hola";exit;
                        $ID = $_POST['ID'];

                        $sql = "SELECT * FROM Colegio_Instancia WHERE Cia_ID = $ID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) == 0) {//no existe
                            echo "La instancia elegida no existe o ya fue eliminada.";
                        } else {
                            $sql = "SELECT COUNT(*) AS TOTAL FROM Colegio_Evaluacion WHERE Eva_Cia_ID = $ID";
                            $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            $row = mysqli_fetch_array($result_prov);
                            if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
                                echo "No se puede eliminar porque tiene " . $row[TOTAL] . " evaluaciones de alumnos relacionadas.";
                            } else {
                                $sql = "DELETE FROM Colegio_Instancia WHERE Cia_ID = $ID";
                                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                echo "Se elimin� la instancia seleccionada.";
                            }
                        }
                    }

//fin funcion

                    function guardarNotaColegio() {
                        //echo "Hola";//exit;
                        $Nombre = $_POST['Nombre'];
                        $NotID = $_POST['NotID'];
                        $NTiID = $_POST['NTiID'];
                        $Siglas = $_POST['Siglas'];
                        $Numero = $_POST['Numero'];

                        $Nombre = arreglarCadenaMayuscula(trim(utf8_decode(strtoupper($Nombre))));
                        $Siglas = arreglarCadenaMayuscula(trim(utf8_decode(strtoupper($Siglas))));
                        $sql = "SELECT * FROM Colegio_Notas WHERE Not_ID = '$NotID'";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {//ya existe lo actualizamos
                            $sql = "UPDATE Colegio_Notas SET Not_Nombre = '$Nombre', Not_Siglas = '$Siglas', Not_Numero = $Numero, Not_NTi_ID = $NTiID WHERE Not_ID = $NotID";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se modificaron los valores correctamente de la nota <strong>($Siglas) $Nombre</strong>";
                            //echo $sql;
                        } else {
                            $sql = "INSERT INTO Colegio_Notas (Not_Nombre, Not_NTi_ID, Not_Siglas, Not_Numero) VALUES ('$Nombre', $NTiID, '$Siglas', $Numero)";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se agreg� correctamente la nueva nota <strong>$Nombre</strong>";
                        }
                    }

//fin funcion

                    function eliminarNotaColegio() {
                        //echo "Hola";exit;
                        $ID = $_POST['ID'];

                        $sql = "SELECT * FROM Colegio_Notas WHERE Not_ID = $ID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) == 0) {//no existe
                            echo "La Nota elegida no existe o ya fue eliminada.";
                        } else {
                            $sql = "SELECT COUNT(*) AS TOTAL FROM Colegio_Evaluacion WHERE Eva_Not_ID = $ID";
                            $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            $row = mysqli_fetch_array($result_prov);
                            if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
                                echo "No se puede eliminar porque tiene " . $row[TOTAL] . " instancias de evaluaci�n relacionadas.";
                            } else {
                                $sql = "DELETE FROM Colegio_Notas WHERE Not_ID = $ID";
                                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                echo "Se elimin� la Nota seleccionada.";
                            }
                        }
                    }

//fin funcion

                    function obtenerDatosClaseDocente() {

                        $ClaID = $_POST['ID'];
                      $sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
					    $sql = "SELECT * FROM
                    Colegio_Clase
                    INNER JOIN Colegio_Nivel 
                    ON (Colegio_Clase.Cla_Niv_ID = Colegio_Nivel.Niv_ID)
                    INNER JOIN Curso 
                    ON (Colegio_Clase.Cla_Cur_ID = Curso.Cur_ID)
                    INNER JOIN Division 
                    ON (Colegio_Clase.Cla_Div_ID = Division.Div_ID) 
                    INNER JOIN Colegio_Materia 
                    ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID)
                    WHERE Cla_ID = $ClaID";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_array($result);
                            $horario = traerHorarioClase($ClaID);
                            $mostrar = "$row[Mat_Nombre]<br /><span style='font-size:12px'>$row[Niv_Nombre]: $row[Cur_Nombre] $row[Div_Nombre]<br />$horario</span>";
                        }//fin if
                        echo $mostrar;
                    }

//fin funcion//*/

                    function obtenerNotaTipoArray() {

                        $NotSigla = $_POST['NotSigla'];
                        //echo "Nota : $NotSigla";exit;
                        $sql = "SELECT * FROM
                    Colegio_Notas
                    INNER JOIN Colegio_NotasTipo 
                    ON (Colegio_Notas.Not_NTi_ID = Colegio_NotasTipo.NTi_ID)
                    WHERE Not_Siglas = '$NotSigla'";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_array($result);
                            $mostrar = "$row[Not_NTi_ID];$row[NTi_Nombre];$row[Not_ID]";
                            echo $mostrar;
                        } else {
                            echo '';
                        }//fin if//*/
                    }

//fin funcion//*/

                    function guardarNotaClase() {
                        //echo "Hola";//exit;
                        $LegID = $_POST['LegID'];
                        $LecID = $_POST['LecID'];
                        $NotID = $_POST['NotID'];
                        $ClaID = $_POST['ClaID'];
                        $CiaID = $_POST['CiaID'];
                        $EvaID = $_POST['EvaID'];
                        $Aud = $_POST['Aud'];

                        $FechaEval = cambiaf_a_mysql($_POST['FechaEval']);
                        obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
                        if (empty($_POST['FechaEval']))
                            $FechaEval = $Fecha;

                        if (empty($EvaID)) {
                            //Primero buscamos el n�mero de evaluaci�n tomadfas hasta el momento
                            $sql = "SELECT MAX(Eva_ID) AS MAXIMO FROM Colegio_Evaluacion WHERE Eva_Leg_ID = $LegID AND Eva_Lec_ID = $LecID AND Eva_Cla_ID = $ClaID";
                            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            if (mysqli_num_rows($result) == 0) {
                                $EvaID = 1; //No existen notas cargadas para esa clase
                            } else {
                                $row = mysqli_fetch_array($result);
                                $EvaID = $row[MAXIMO] + 1; //ya existen notas cargadas, por eso aumentamos en uno el n�mero encontrado
                            }
                            $sql = "INSERT INTO Colegio_Evaluacion (Eva_Leg_ID, Eva_Lec_ID, Eva_Cla_ID, Eva_ID, Eva_Cia_ID, Eva_Not_ID, Eva_FechaEval, Eva_Usu_ID, Eva_Fecha, Eva_Hora) VALUES ($LegID, $LecID, $ClaID, $EvaID, $CiaID, $NotID, '$FechaEval', $UsuID, '$Fecha', '$Hora')";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se agreg� correctamente la evaluaci�n</strong>";
                        } else {
                            if (empty($Aud))
                                $Aud = 0;
                            $sql = "UPDATE Colegio_Evaluacion SET Eva_Not_ID = $NotID, Eva_FechaEval = '$FechaEval', Eva_Aud = $Aud, Eva_Usu_ID = $UsuID, Eva_Fecha = '$Fecha', Eva_Hora = '$Hora' WHERE Eva_Leg_ID = $LegID AND Eva_Lec_ID = $LecID AND Eva_Cla_ID = $ClaID AND Eva_ID = $EvaID";
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se modificaron los valores correctamente de la evaluaci�n</strong>";
                        }
                    }

//fin funcion//*/
                    function obtenerIdUsuario($Id_usuario) {
                        $Usu_nom = $_SESSION['sesion_usuario'];
                        $sql5 = "SELECT Usu_ID FROM Usuario WHERE Usu_Nombre like '$Usu_nom'";
                        $re = consulta_mysql_2022($sql5,basename(__FILE__),__LINE__);
                        $row = mysqli_fetch_array($re);
                        echo $Id_usuario = $row[Usu_ID];
                    }

                    function guardarInasistencia() {
                        //echo "Hola";//exit;
                        $LegID = $_POST['LegID'];
                        $LecID = $_POST['LecID'];
                        $InaID = $_POST['InaID'];
                        $Justificada = $_POST['Justificada'];
                        $Certificado = $_POST['Certificado'];
                        $Deportiva = $_POST['Deportiva'];
                        $Detalle = utf8_decode($_POST['Detalle']);

                        $FechaFalta = cambiaf_a_mysql($_POST['FechaFalta']);
                        obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
                        if (empty($_POST['FechaFalta']))
                            $FechaFalta = $Fecha;

                        //Primero buscamos la inasistencia de la fecha indicada
                        $sql = "SELECT * FROM Colegio_Ausentismo WHERE Aus_Leg_ID = $LegID AND Aus_Lec_ID = $LecID AND Aus_FechaFalta = '$FechaFalta'";
                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        //if (mysqli_num_rows($result) == 0) {
                            $sql = "INSERT INTO Colegio_Ausentismo (Aus_Leg_ID, Aus_Lec_ID, Aus_FechaFalta, Aus_Ina_ID, Aus_Justificada, Aus_Certificado, Aus_Deportiva, Aus_Detalle, Aus_Usu_ID, Aus_Fecha, Aus_Hora) VALUES ($LegID, $LecID, '$FechaFalta', $InaID, $Justificada, $Certificado, $Deportiva, '$Detalle', $UsuID, '$Fecha', '$Hora')";
                            //echo $sql;
                            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            echo "Se agregó correctamente la inasistencia</strong>";
                        //} else {
                        //    $sql = "UPDATE Colegio_Ausentismo SET Aus_Ina_ID = $InaID, Aus_Detalle = '$Detalle', Aus_Justificada = $Justificada, Aus_Certificado = $Certificado, Aus_Deportiva = $Deportiva, Aus_Usu_ID = $UsuID, Aus_Fecha = '$Fecha', Aus_Hora = '$Hora' WHERE Aus_Leg_ID = $LegID AND Aus_Lec_ID = $LecID AND Aus_FechaFalta = '$FechaFalta'";
                        //    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                            //echo "Se modificaron los valores correctamente de la inasistencia</strong>";
                        //}
                    }

//fin funcion//*/
                    //*/
                    

function eliminarConfigurarCuota() {
	//echo "Hola";exit;
	$Lec_ID = $_POST['Lec_ID'];
    $Niv_ID = $_POST['Niv_ID'];
    $CTi_ID = $_POST['CTi_ID'];
    $Alt_ID = $_POST['Alt_ID'];
    $Numero = $_POST['Numero'];
    $sql = "DELETE FROM CuotaModelo WHERE CMo_Lec_ID = $Lec_ID AND CMo_Niv_ID = $Niv_ID AND CMo_CTi_ID = $CTi_ID AND CMo_Alt_ID = $Alt_ID AND CMo_Numero = $Numero";
    //echo $sql;
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    echo "Se eliminó la configuración de cuota seleccionada.";
	
	
}	

function agregarCuotaColaPago() {
	//echo "Hola";exit;
	
	$datosCuota = $_POST['Cuota'];
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

	$sql = "INSERT IGNORE INTO CuotaPersonaCola (CPT_Usu_ID, CPT_Lec_ID, CPT_Per_ID, CPT_Niv_ID, CPT_CTi_ID, CPT_Alt_ID, CPT_Numero, CPT_Fecha, CPT_Hora) VALUES($UsuID, $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, '$Fecha', '$Hora')";
	//echo $sql;
	if ($result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__))
		echo "Bien";
	//echo "Se eliminó la configuración de cuota seleccionada.";
}

function quitarCuotaColaPago() {
	//echo "Hola";exit;
	$datosCuota = $_POST['Cuota'];
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);

	$sql = "DELETE FROM CuotaPersonaCola WHERE CPT_Lec_ID = $Cuo_Lec_ID AND CPT_Per_ID = $Cuo_Per_ID AND CPT_Niv_ID = $Cuo_Niv_ID AND CPT_CTi_ID=$Cuo_CTi_ID AND CPT_Alt_ID = $Cuo_Alt_ID AND CPT_Numero = $Cuo_Numero";
	//echo $sql;
	if ($result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__))
		echo "Bien";
	
	
}	

function cargarDetalleFormaPago(){
	$ForID = $_POST['ForID'];
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM FormaPagoDetalle WHERE FDe_For_ID = $ForID AND FDe_ID > 1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		?>        
        <table width="85%" border="0" align="center" cellpadding="2" cellspacing="2" class="texto">
  <tr>
    <td colspan="2"><strong>Detalle del pago</strong></td>
  </tr>
        <?php
		while ($row = mysqli_fetch_array($result)){
		?>
        <tr>
       
            
            <?php
            if(($row[FDe_For_ID] == 2) && ($row[FDe_DatoFecha] == '1')){
				$clase = "required fechaCompleta";
			}else{
				$clase = "input_editar";
			}
				
				?>
                       
            
<script language="javascript">
$(document).ready(function(){
	//valor = $("#valor").val();
	$(".required").datepicker($.datepicker.regional['es']);

});//fin de la funcion ready	
</script>

            
           
					
                    <td><?php echo $row[FDe_Nombre];?></td>
            <td><input name="campo<?php echo $row[FDe_ID];?>" type="text" id="campo<?php echo $row[FDe_ID];?>" size="20" maxlength="100"           
            class="<?php echo $clase;?>" /></td>
                   
            
            
          </tr>
        <?php
		}//fin while
		?>
        </table>
        <?php
	}
}//fin function	

function cargarDetalleFormaPago2(){
    $ForID = $_POST['ForID'];
    $i = $_POST['i'];
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM FormaPagoDetalle WHERE FDe_For_ID = $ForID AND FDe_ID > 1";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        ?>        
        <table width="85%" border="0" align="center" cellpadding="2" cellspacing="2" class="texto">
  <tr>
    <td colspan="2"><strong>Detalle del pago</strong></td>
  </tr>
        <?php
        while ($row = mysqli_fetch_array($result)){
        ?>
        <tr>
       
            
            <?php
            if(($row[FDe_For_ID] == 2) && ($row[FDe_DatoFecha] == '1')){
                $clase = "required fechaCompleta";
            }else{
                $clase = "input_editar";
            }
                
                ?>
                       
            
<script language="javascript">
$(document).ready(function(){
    //valor = $("#valor").val();
    $(".required").datepicker($.datepicker.regional['es']);

});//fin de la funcion ready    
</script>

            
           
                    
                    <td><?php echo $row[FDe_Nombre];?></td>
            <td><input name="campo<?php echo $i;?>-<?php echo $row[FDe_ID];?>" type="text" id="campo<?php echo $i;?>-<?php echo $row[FDe_ID];?>" size="50" maxlength="100" class="<?php echo $clase;?>" /></td>
                   
            
            
          </tr>
        <?php
        }//fin while
        ?>
        </table>
        <?php
    }
}//fin function 		

function buscarFacturaUltima() {
	//echo "Hola";exit;
	//$Sucursal = $_POST['Sucursal'];
	//$Sucursal = "0002"; 
    $Sucursal = "0003";
	$sql = "SELECT Fac_Sucursal, Fac_Numero FROM Factura WHERE Fac_Sucursal = '$Sucursal' Order by Fac_Numero DESC LIMIT 0,1";
	//echo $sql;
    /*if ($Sucursal=="0001"){
        $sql = "SELECT Fac_Sucursal, Fac_Numero FROM Factura WHERE Fac_Sucursal = '$Sucursal' AND Fac_Numero<'34002' Order by Fac_Numero DESC LIMIT 0,1";
        //echo $sql;
    }*/
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Numero = $row['Fac_Numero']+1;
		$Numero = substr("00000000".$Numero,-8);
		echo $row['Fac_Sucursal']."-".$Numero;
	}else{
		echo $Sucursal."-00000001";
	}				
}//fin if
function buscarReciboUltima($escribir_echo=true) {
	//echo "Hola";exit;
	//$Sucursal = $_POST['Sucursal'];
//	$Sucursal = "0002";
    $Sucursal = "0003";
	$sql = "SELECT Fac_Sucursal, Fac_Numero FROM Factura WHERE Fac_Sucursal = '$Sucursal' Order by Fac_Numero DESC LIMIT 0,1";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Numero = $row['Fac_Numero']+1;
		$Numero = substr("00000000".$Numero,-8);
        if ($escribir_echo){
            echo $row['Fac_Sucursal']."-".$Numero;    
        }else{
            return $row['Fac_Sucursal']."-".$Numero;    
        }
		
	}else{
        if ($escribir_echo){
		  echo $Sucursal."-00000001";
        }else{
            return $Sucursal."-00000001";
        }
	}				
}//fin if

function validarNumeroFactura() {
	//echo "Hola";exit;
	$FacturaNumero = $_POST['FacturaNumero'];
	list($Sucursal, $Numero) = explode("-", $FacturaNumero);

	$sql = "SELECT Fac_Numero FROM Factura WHERE Fac_Sucursal = '$Sucursal' AND Fac_Numero = '$Numero'";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		echo "Mal";
	}
}//fin if

function generarFactura() {
    //echo "Hola";exit;
    consulta_iniciar();
    $todo_bien = false;
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
    $CajaID = cajaAbiertaUsuario($UsuID);
    if (!$CajaID){
        echo "Error: La Caja se encuentra cerrada";
        exit;
    }
    
    $FacturaNumero = $_POST['FacturaNumero'];
    list($Sucursal, $Numero) = explode("-",$FacturaNumero);
    $Domicilio = $_POST['Domicilio'];
    $Razon = addslashes($_POST['Razon']);
    $CUIT = $_POST['CUIT'];
    $Fac_Detalle = $_POST['Fac_Detalle'];
    $FacturaImporteTotal = $_POST['FacturaImporteTotal'];
    $FTiID = $_POST['FTiID'];
    $CVeID = 1;//$_POST['CVeID'];
    $IvaID = 5;//$_POST['IvaID'];
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	//valido que el número de la factura no haya sido usado
    $sql = "SELECT Fac_Sucursal,Fac_Numero FROM Factura WHERE Fac_Sucursal = '$Sucursal' AND Fac_Numero = '$Numero'";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0) {
        echo "Error: El número de recibo o factura ya fue usado! Vuelva a realizar el cobro.";
        exit;
    }
    //*****************************************************
	
    $sql = "INSERT INTO Factura (Fac_FTi_ID, Fac_Iva_ID, Fac_CVe_ID, Fac_Fecha, Fac_Hora, Fac_Usu_ID, Fac_CUIT, Fac_Sucursal, Fac_Numero, Fac_PersonaNombre, Fac_PersonaDomicilio, Fac_ImporteTotal, Fac_Pagada, Fac_Anulada, Fac_Detalle) VALUES('$FTiID', '$IvaID', '$CVeID', '$Fecha', '$Hora', '$UsuID', '$CUIT', '$Sucursal', '$Numero', '$Razon', '$Domicilio', '$FacturaImporteTotal', 1, 0, '$Fac_Detalle')";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $FacID = $res['id'];
    }else{
        echo "Mal";
    }
    
    $ForID = $_POST['ForID'];
    $Item = 0;
    foreach($_POST as $nombre_campo => $valor){ 
       $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
      // echo "$asignacion<br />"; 
      // echo $i++;
       
       
       if (substr($nombre_campo,0,10)=="datosCuota"){
            $datosCuota = $valor;
            $Item++;
            $j = substr($nombre_campo,10,2);
            $cuotas = "cuotas".$j;
            $cuotas = "importeAbonar".$j;
            //echo $cuotas;exit;
            
            $ImporteCuota = $_POST[$cuotas];
 
            if (!is_numeric($ImporteCuota)){
                continue;
            }
            if (intval($ImporteCuota)==0 && ($Cuo_CTi_ID==16 || $Cuo_CTi_ID==17)){
                continue;
            }
            //echo $ImporteCuota;exit;
            
            list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(';', $datosCuota);

            if ($Cuo_CTi_ID==16 || $Cuo_CTi_ID==17){
                $CTi_ID = 17;//Recargo por mora
                $CTi_ID = 16;//Gastos administrativos
                $Cuo_Numero = buscarNumeroCuotaPersonaFactura($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID);
                //echo $Cuo_Numero;                
                guardarCuotaPersonaFactura($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, $ImporteCuota);

                //vuelvo a armar el $datosCuota con el nuevo $Cuo_Numero
                $datosCuota=$Cuo_Lec_ID.";".$Cuo_Per_ID.";".$Cuo_Niv_ID.";".$Cuo_CTi_ID.";".$Cuo_Alt_ID.";".$Cuo_Numero;
            }
            
            $Orden = buscarOrdenPagoCuota($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero);
            
            //echo $Orden; exit;
            if ($Orden==1){
                //Guardo en la Cuenta Corriente del Alumno el importe total de la cuota, generamos por primera vez el concepto de cuota
                guardarConceptoCtaCte($Cuo_Per_ID, $datosCuota, "debe",1);
            }
            
            //Inserto el pago corespondiente
            $CCoID = guardarPagoCtaCte($Cuo_Per_ID, $datosCuota, $ImporteCuota);
            //echo $CCoID; exit;
            
            if ($CCoID==0) {
                consulta_retroceder();
                exit;
            }

            $sql = "INSERT INTO CuotaPago (CuP_Lec_ID, CuP_Per_ID, CuP_Niv_ID, CuP_CTi_ID, CuP_Alt_ID, CuP_Numero, CuP_Orden, CuP_Fac_ID, CuP_FDe_Item, CuP_Caja_ID, CuP_Fecha, CuP_Hora, CuP_Usu_ID, CuP_Importe, CuP_CCo_ID) VALUES('$Cuo_Lec_ID', '$Cuo_Per_ID', '$Cuo_Niv_ID', '$Cuo_CTi_ID', '$Cuo_Alt_ID', '$Cuo_Numero', '$Orden', '$FacID', '$Item', '$CajaID', '$Fecha', '$Hora', '$UsuID', '$ImporteCuota', '$CCoID')";
            
            //echo "$sql<br />";exit;

            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            
            //Actualizar Alternativa de pago de la cuota
            actualizarAlernativaPago($datosCuota);
           
            //Insertamos el pago en la Caja Corriente
            guardarPagoCajaCorriente($CajaID, $datosCuota, $ImporteCuota, $ForID, $FacturaNumero);
            //echo "va bien"; exit;

            //Insertamos el detalle de la factura
            $Saldo = "";$sinPlan=false;
            if (calcularPagoTotalCuota($datosCuota, $debe, $sinPlan)){
                $Detalle = "Cancela ";                
                //Actualizo la cuota como pagada si los pagos parciales cubren el total del importe
                $sql = "UPDATE CuotaPersona SET Cuo_Pagado = 1 WHERE Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID='$Cuo_CTi_ID' AND Cuo_Alt_ID='$Cuo_Alt_ID' AND Cuo_Numero='$Cuo_Numero'";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);//echo $sql;
            }else{
                $Detalle = "A cuenta ";
                $DetalleRecargo = "";
                //if ($recargo>0) $DetalleRecargo .= " (int. p/mora: $".$recargo.")";
                $Saldo = " (Saldo a la fecha: $".$debe.")$DetalleRecargo";
            }
            if ($Cuo_CTi_ID==17 || $Cuo_CTi_ID==16){
                $Detalle = "";
                $Saldo = "";
            } 
            //echo "va bien"; exit;

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

                //Mario. 27-04-2023
                //solo para mayo 2023 hasta que pongan el valor nuevo de cuota
                /*
                if ($Cuo_Lec_ID==23 && $Cuo_CTi_ID==2 && $row[Cuo_Mes]==5 && $row[Cuo_Anio]==2023){
                    $Detalle = "A cuenta de ";
                }
                */
                //******************
                
                $Detalle .= "$row[CTi_Nombre] ".buscarMes($row[Cuo_Mes])."/$row[Cuo_Anio] $Saldo";
                if ($Cuo_CTi_ID==17 || $Cuo_CTi_ID==16){
                    $Detalle = "   $row[CTi_Nombre]";                    
                } 
            }
            $sql = "INSERT INTO FacturaDetalle (FDe_Fac_ID, FDe_Item, FDe_Cantidad, FDe_Detalle, FDe_ImporteUnitario, FDe_Importe) VALUES('$FacID', '$Item', 1, '$Detalle', '$ImporteCuota', '$ImporteCuota')";
            //echo "$sql<br />";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            
            //Inserto el detalle del pago
            for ($i=2;$i<=10;$i++){
                $nombreCampoFDe = "campo".$i;
                
                if (isset($_POST[$nombreCampoFDe])){
                    $sql = "INSERT INTO CuotaPagoDetalle (CPD_Lec_ID, CPD_Per_ID, CPD_Niv_ID, CPD_CTi_ID, CPD_Alt_ID, CPD_Numero, CPD_Orden, CPD_FDe_ID, CPD_For_ID, CPD_Fecha, CPD_Hora, CPD_Valor) VALUES('$Cuo_Lec_ID', '$Cuo_Per_ID', '$Cuo_Niv_ID', '$Cuo_CTi_ID', '$Cuo_Alt_ID', '$Cuo_Numero', '$Orden', '$i', '$ForID', '$Fecha', '$Hora', '".$_POST[$nombreCampoFDe]."')";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                }
            }
            //Hacemos una inserción solamente para cuando el FDeID = 1, es decir el importe
            $sql = "INSERT INTO CuotaPagoDetalle (CPD_Lec_ID, CPD_Per_ID, CPD_Niv_ID, CPD_CTi_ID, CPD_Alt_ID, CPD_Numero, CPD_Orden, CPD_FDe_ID, CPD_For_ID, CPD_Fecha, CPD_Hora, CPD_Valor) VALUES('$Cuo_Lec_ID', '$Cuo_Per_ID', '$Cuo_Niv_ID', '$Cuo_CTi_ID', '$Cuo_Alt_ID', '$Cuo_Numero', '$Orden', 1, '$ForID', '$Fecha', '$Hora', '$ImporteCuota')";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            $todo_bien = true;
            
        }//fin if
        //*/
    } //fin foreach
    if (!$todo_bien){ 
        consulta_retroceder();
    }else{
        consulta_terminar();
        vaciarColaPagoUsuario();
    }
    //session_unregister('sesion_ultimoDNI');
    
}//fin function



function buscarOrdenPagoCuota($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero){
	$sql = "SELECT * FROM CuotaPago WHERE CuP_Lec_ID = $Cuo_Lec_ID AND CuP_Per_ID = $Cuo_Per_ID AND CuP_Niv_ID = $Cuo_Niv_ID AND CuP_CTi_ID=$Cuo_CTi_ID AND CuP_Alt_ID=$Cuo_Alt_ID AND CuP_Numero=$Cuo_Numero ORDER BY Cup_Orden DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$Orden = $row[CuP_Orden] + 1;
	}else{
		$Orden = 1;
	}
	return $Orden;
}//fin function

function guardarDetallePago() {
	$ForID = $_POST['For_ID'];
	/* $FDe_Nombre1 = $_POST['FDe_Nombre1'];
	  $FDe_Nombre2 = $_POST['FDe_Nombre2'];
	  $FDe_Nombre3 = $_POST['FDe_Nombre3'];
	  $FDe_Nombre4 = $_POST['FDe_Nombre4'];
	  $FDe_Nombre5 = $_POST['FDe_Nombre5'];
	  $FDe_Nombre6 = $_POST['FDe_Nombre6'];
	  $FDe_Nombre7 = $_POST['FDe_Nombre7'];
	  $FDe_Nombre8 = $_POST['FDe_Nombre8'];
	  $FDe_Nombre9 = $_POST['FDe_Nombre9'];
	  $FDe_Nombre10 = $_POST['FDe_Nombre10']; */

	for ($i = 1; $i <= 10; $i++) {
		$campo = "FDe_Nombre" . $i;
		$$campo = $_POST[$campo];
		$cam = trim($$campo);
		if (!empty($cam)) {
			echo $cam;
			$sql_ins = "INSERT INTO FormaPagoDetalle (FDe_For_ID, FDe_ID, FDe_Nombre) VALUES ($ForID, $i, '" . $$campo . "')";
			consulta_mysql_2022($sql_ins,basename(__FILE__),__LINE__);                                 
		}else{
			$sql_con = "UPDATE FormaPagoDetalle SET FDe_Nombre = '" . $$campo . "' WHERE FDe_For_ID = $ForID AND FDe_ID = $i";
			consulta_mysql_2022($sql_con,basename(__FILE__),__LINE__);
		}
			
	}//fin del for
	echo "Se agregaron correctamente";
}//fin function

function guardarCajaApertura(){
	$importe = $_POST['Importe'];
	$hora = date('H:i:s');
	$fecha = date("Y-m-d");
	$UsuID = $_SESSION['sesion_UsuID'];
	//inserta en tabla caja para asentar fecha de la apertura
	$sql = "INSERT INTO Caja(Caja_Apertura, Caja_Usu_ID) VALUES ('$fecha $hora', $UsuID)";

    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $id = $res['id'];
    }else{
        echo "Mal";
    }
	 
	//echo $sql; guardar ultimo id insertado.
	// inserta en la tabla cajacorriente para asentar datos de apertura
	$sql_1 = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Credito, CCC_Saldo, CCC_Fecha, CCC_Hora,CCC_Usu_ID, CCC_For_ID) VALUES ( $id,'Apertura de Caja', $importe, $importe, '$fecha', '$hora', $UsuID, 1)";
	consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
	//echo $sql_1;
	
	
	}
function guardarSuperCajaApertura(){
	$importe = $_POST['Importe'];
	$hora = date('H:i:s');
	$fecha = date("Y-m-d");
	$UsuID = $_SESSION['sesion_UsuID'];
	//inserta en tabla caja para asentar fecha de la apertura
	$sql = "INSERT INTO SuperCaja(SCa_Apertura, SCa_Usu_ID) VALUES ('$fecha $hora', $UsuID)";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $id = $res['id'];
    }else{
        echo "Mal";
    }
	
}//fin function
function guardarSuperCajaCierre(){
	$nrocaja = $_POST['NumeroCaja'];
	$hora = date('H:i:s');
	$fecha = date("Y-m-d");
	$UsuID = $_SESSION['sesion_UsuID'];
	////////CIERRE DEFINITIVO DE CAJA 
	$sql = "SELECT SUM(SCC_Debito)AS Debito, SUM(SCC_Credito)AS Credito FROM SuperCajaCorriente WHERE SCC_SCa_ID = $nrocaja";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		$Debito = $row['Debito'];
		$Credito = $row['Credito'];
		$importe = $Credito - $Debito;
	}else{
		$importe = 0;
	}
	$sql_cierre = "UPDATE SuperCaja SET SCa_Cierre = '$fecha $hora', SCa_ImporteTotal = '$importe' WHERE SCa_ID = $nrocaja";
	consulta_mysql_2022($sql_cierre,basename(__FILE__),__LINE__);

}//fin function
function guardarCajaRendicion(){
	$nrocaja = $_POST['numerocaja'];
	$Hora = date('H:i:s');
	$Fecha = date("Y-m-d");	
	$UsuID = $_SESSION['sesion_UsuID'];
	$forma = 1;
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$SCC_SCa_ID = obtenerSuperCaja();
	if (!$SCC_SCa_ID){
		echo "No se encuentra abierta la SuperCaja.";
		exit;
	}

	$sql_importe = "SELECT SUM(CCC_Credito - CCC_Debito) AS importe FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja";
	$result_importe = consulta_mysql_2022($sql_importe,basename(__FILE__),__LINE__);
    $row_importe = mysqli_fetch_array($result_importe);
	   //echo $row_importe[importe];return;
	$importe = $row_importe[importe];
	if (empty($importe)) $importe=0;
	
	////////CIERRE DEFINITIVO DE CAJA 
	$sql_cierre = "UPDATE Caja SET Caja_Cierre = '$Fecha $Hora', Caja_Importe_Total = '$importe' WHERE Caja_ID = $nrocaja";
	consulta_mysql_2022($sql_cierre,basename(__FILE__),__LINE__);
	   
	////////TRASPASO DE LA TABLA CAJACORRIENTE A LA TABLA CAJARENDIDA
	   
	$sql_insert = "INSERT INTO CajaRendida (CRe_Caja_ID,CRe_Usu_ID,CRe_For_ID,CRe_Importe,CRe_Fecha_Rendida)
SELECT
  CCC_Caja_ID,
  CCC_Usu_ID,
  CCC_For_ID,
  SUM(CCC_Credito) - SUM(CCC_Debito), '$Fecha $Hora'
FROM CajaCorriente
WHERE CCC_Caja_ID = $nrocaja
GROUP BY CCC_Caja_ID,
     CCC_Usu_ID,
     CCC_For_ID";
	 consulta_mysql_2022($sql_insert,basename(__FILE__),__LINE__);
	
	//PREPARAMOS TODO PARA PASAR LOS IMPORTES A LA SUPER CAJA AGRUPADOS POR FORMA DE PAGO
    $sql_forma = "SELECT DISTINCTROW CCC_For_ID FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja";
    $result_forma = consulta_mysql_2022($sql_forma,basename(__FILE__),__LINE__);
    while ($row_forma = mysqli_fetch_array($result_forma)){
     
        $For_ID = $row_forma['CCC_For_ID'];
        
        $sql = "SELECT SUM(CCC_Debito) AS RETIRO FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja AND CCC_Concepto = 'RETIRO DE DINERO' AND CCC_For_ID = $For_ID";
    	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    	 $row = mysqli_fetch_array($result);
    	 $retiro = $row['RETIRO'];

         $sql = "SELECT SUM(CCC_Debito) AS RESTA FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja AND CCC_Concepto <> 'RETIRO DE DINERO' AND CCC_Credito = 0 AND CCC_For_ID = $For_ID";
         $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
         $row = mysqli_fetch_array($result);
         $resta = $row['RESTA'];
         //$resta = 0;
    	 
         $sql = "SELECT SUM(CCC_Credito) AS SUMA FROM CajaCorriente
    WHERE CCC_Caja_ID = $nrocaja AND CCC_Concepto <> 'RETIRO DE DINERO' AND CCC_Debito = 0 AND CCC_For_ID = $For_ID";
    	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    	 $row = mysqli_fetch_array($result);
    	 $suma = $row['SUMA'];
    	 $importe = $suma - $retiro - $resta;
    	//echo $sql;
    	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Detalle, CCC_Debito, CCC_Fecha, CCC_Hora, CCC_Usu_ID, CCC_For_ID) VALUES ($nrocaja, 'CAJA RENDIDA NUMERO $nrocaja', 'SE TRASLADARON LOS INGRESOS A LA SUPERCAJA', $importe, '$Fecha', '$Hora', $UsuID, $For_ID)";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $SCC_CCC_ID = $res['id'];
        }else{
            echo "Mal";
        }

        actualizarSaldoCajaCorriente($nrocaja, $SCC_CCC_ID);

        //Aqui pasamos cada Forma de Pago de la Caja Diaria a la Cuenta Contable Asociada
        if (buscarCuentaContableFormaPago($For_ID)){
            //Guardamos la entrada de dinero a la cuenta asociada
            $Cue_ID = buscarCuentaContableFormaPago($For_ID);
            guardarMovimientoCuentaContable($nrocaja, $Cue_ID, "CAJA RENDIDA NUMERO $nrocaja", 0, $importe, $For_ID, 'SE TRASLADARON LOS INGRESOS DE LA CAJA DIARIA');
        }

    	
    	$SCC_FechaHora = "$Fecha $Hora";
    	$sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($nrocaja, $SCC_CCC_ID, $SCC_SCa_ID, 'CAJA RENDIDA NUMERO $nrocaja', 0, $importe, '$SCC_FechaHora', '', $UsuID)";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $SCC_ID = $res['id'];
        }else{
            echo "Mal";
        }
    	
    	actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);


}//fin while FORMA PAGO

echo "Caja Cerrada y Rendida";

}//fin function

function obtenerSuperCaja(){
	$sql = "SELECT * FROM SuperCaja WHERE SCa_Apertura IS NOT NULL AND SCa_Cierre IS NULL ORDER BY SCa_ID DESC";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){		
		$row = mysqli_fetch_array($result);
		return $row[SCa_ID];
	}else{
		return false;
	}
	
}//fin function
		
function guardarRetiroDinero(){
	
	$forma = $_POST['forma'];
	$nrocaja = $_POST['caja'];
	$total = $_POST['total'];
	$observaciones = $_POST['observaciones'];
	$importe = $_POST['valor'];
	$tot = $total - $importe;
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$SCC_SCa_ID = obtenerSuperCaja();
	if (!$SCC_SCa_ID){
		echo "No se encuentra abierta la SuperCaja.";
	}else{	
		$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Detalle, CCC_Debito, CCC_Saldo, CCC_Fecha, CCC_Hora, CCC_Usu_ID, CCC_For_ID) VALUES ($nrocaja, 'RETIRO DE DINERO', '$observaciones', $importe, $tot, '$Fecha', '$Hora', $UsuID, $forma)";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $SCC_CCC_ID = $res['id'];
        }else{
            echo "Mal";
        }
		
		$SCC_FechaHora = "$Fecha $Hora";
		$sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($nrocaja, $SCC_CCC_ID, $SCC_SCa_ID, 'RETIRO DE RECEPCIÓN', 0, $importe, '$SCC_FechaHora', 'Retiró $observaciones', $UsuID)";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $SCC_ID = $res['id'];
        }else{
            echo "Mal";
        }

		actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);
		//echo $sql;
	}//fin if 
	
}//fin function
function guardarIngresoDineroSuperCaja(){
	
	$forma = 1;//$_POST['forma'];
	$nrocaja = $_POST['caja'];
	$total = $_POST['total'];
	$observaciones = $_POST['observaciones'];
	$importe = $_POST['valor'];
	$tot = $total - $importe;
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$CCC_Caja_ID = cajaAbiertaUsuario($UsuID);
	if (!$CCC_Caja_ID){
		echo "No se encuentra abierta la Caja del Usuario.";
		exit;
	}
	$SCC_SCa_ID = obtenerSuperCaja();
	if (!$SCC_SCa_ID){
		echo "No se encuentra abierta la SuperCaja.";
	}else{	
		$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Detalle, CCC_Credito, CCC_Debito, CCC_Fecha, CCC_Hora, CCC_Usu_ID, CCC_For_ID) VALUES ($CCC_Caja_ID, 'INGRESO DE DINERO A SUPERCAJA', '$observaciones', $importe, $importe, '$Fecha', '$Hora', $UsuID, $forma)";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $SCC_CCC_ID = $res['id'];
        }else{
            echo "Mal";
        }

		actualizarSaldoCajaCorriente($CCC_Caja_ID, $SCC_CCC_ID);
		
		$SCC_FechaHora = "$Fecha $Hora";
		$sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CCC_Caja_ID, $SCC_CCC_ID, $SCC_SCa_ID, 'INGRESO DE DINERO', 0, $importe, '$SCC_FechaHora', 'Tipo de ingreso:  $observaciones', $UsuID)";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $SCC_ID = $res['id'];
        }else{
            echo "Mal";
        }

		actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);
		//echo $sql;
	}//fin if 
	
}//fin function	
function guardarCajaAuditada(){
	
	$forma = $_POST['For_ID'];
	//echo "forma ".$forma."<br />";
	$nrocaja = $_POST['Caja_ID'];
	//echo "nrocaja ".$nrocaja."<br />";
	//$fecha = date("Y-m-d H:i:s");
	//$UsuID = $_SESSION['sesion_UsuID'];
	
	//echo "ASDAS".$UsuID."<br />";
	//return false;
	obtenerRegistroUsuario($UsuID,$Fecha,$Hora);
	//echo "UsuID ".$UsuID."<br />";
	//echo "Fecha ".$Fecha."<br />";
	//echo "Hora ".$Hora."<br />";
	$sql = "UPDATE CajaRendida SET 	CRe_Auditado = 1,   CRe_Fecha_Auditada = '$Fecha', CRe_Usu_Auditado = '$UsuID'  WHERE CRe_Caja_ID = $nrocaja AND CRe_For_ID = $forma ";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
}//fin function
	
	//NAHUEL 08-04-2013	
		
function VerDetallesFactura(){
    
    
    
    $Fac_ID = $_POST['Fac_ID'];
    
    $Fac_Numero = $_POST['FacturaNumero'];
    list($Sucursal, $Numero) = explode("-", $Fac_Numero);
     //echo "HOLA: ".$NCr_ID;
      $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
             $sql2="SELECT *
FROM
    FacturaDetalle
    INNER JOIN Factura 
        ON (FDe_Fac_ID = Fac_ID)
    INNER JOIN FacturaTipo 
        ON (Fac_FTi_ID = FTi_ID)
    INNER JOIN IvaCliente 
        ON (Fac_Iva_ID = Iva_ID)
    INNER JOIN CondicionVenta 
        ON (Fac_CVe_ID = CVe_ID)
    INNER JOIN Usuario 
        ON (Fac_Usu_ID = Usu_ID) WHERE ";
        if (!empty($Fac_ID))
            $sql2.="Fac_ID='$Fac_ID';";
        if (!empty($Fac_Numero))
            $sql2.="Fac_Numero ='$Numero' AND Fac_Sucursal = '$Sucursal';";
     //echo $sql2;
     $result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
     $row2 = mysqli_fetch_array($result2);
    $Fac_ID = $row2[Fac_ID];
    $FTi_ID = $row2[FTi_ID];
    $FormaPago = buscarFormaPagoFactura($Fac_ID);
    $FormaPagoDetalle =buscarFormaPagoFacturaDetalle($Fac_ID);  
        
     $sql="SELECT *
FROM
    FacturaDetalle
    INNER JOIN Factura 
        ON (FDe_Fac_ID = Fac_ID)
    INNER JOIN FacturaTipo 
        ON (Fac_FTi_ID = FTi_ID)
    INNER JOIN IvaCliente 
        ON (Fac_Iva_ID = Iva_ID)
    INNER JOIN CondicionVenta 
        ON (Fac_CVe_ID = CVe_ID) WHERE Fac_ID='$Fac_ID';";
     $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);    
     ?>
         <style type="text/css">
    .tablilla1{ font-size:12px; font-family:Arial, Helvetica, sans-serif;}
      .tablilla1 td{ padding: 4px}
    </style>
    <div id="conenedortodo">
    <table class="tablilla1" style="border: solid 1px; border-collapse:collapse; font-size:15px" width="100%">
    
    
        <tr style="border: solid 1px;">
    <td><strong>Tipo de Factura:</strong></td> <td ><strong><?php echo $row2['FTi_Nombre'] ?></strong> Número: <strong><?php echo $row2['Fac_Sucursal']."-".$row2['Fac_Numero'];?></strong></td>
    </tr>
    
    <tr style="border: solid 1px;">
    <td><strong>Persona:</strong></td> <td><?php echo $row2['Fac_PersonaNombre'] ?></td>
    </tr>
    
     <tr style="border: solid 1px;">
    <td><strong>Condición IVA:</strong></td> <td><?php echo $row2['Iva_Nombre'] ?><strong>  Condición Venta: </strong><?php echo $row2['CVe_Nombre'] ?></td>
    </tr>
         <tr style="border: solid 1px;">
    <td><strong>Forma Pago:</strong></td> <td><?php 
        if (!empty($FormaPagoDetalle)){
                $FormaPago .= " ($FormaPagoDetalle)";
            }
        echo $FormaPago; ?>
            
        </td>
    </tr>
    <tr style="border: solid 1px;">
    <td><strong>Estado:</strong></td> <td><strong>
    <?php 
    if ($FTi_ID == 3) echo '<font color="#0000FF">NOTA DE CREDITO</font>';
    else {
        if ($row2['Fac_Pagada']==1) echo '<font color="#000000">PAGADA</font>';
        if ($row2['Fac_Anulada']==1) echo '<font color="#FF0000">ANULADA</font>';
        } ?>
           
       </strong></td>
    </tr>        
         <tr style="border: solid 1px;">
    <td><strong>Fecha/Hora:</strong></td> <td><?php echo cfecha($row2['Fac_Fecha'])." ".$row2[Fac_Hora] ?></td>
    </tr>
    
            </tr>
         <tr style="border: solid 1px;">
    <td><strong>Atendió:</strong></td> <td><?php echo $row2['Usu_Persona']; ?></td>
    </tr>
    <tr style="border: solid 1px;">
    <td><strong>Detalle/Comentario:</strong></td> <td><?php echo $row2['Fac_Detalle']; ?></td>
    </tr>
    
    </table>
    <br />

    <table class="tablilla1" style="border: solid 1px; border-collapse:collapse" width="100%">
    <tr class="texto" style="border: solid 1px;">
       <td ><strong>Cantidad:</strong> </td> 
       <td><strong>Detalle:</strong> </td>
       <td align="center"><strong>Importe:</strong> </td>
    </tr>
     <?php
     $suma=0;
while($row = mysqli_fetch_array($result)){
    $FDe_Cantidad = $row['FDe_Cantidad'];
    $FDe_Detalle = $row['FDe_Detalle'];
    $FDe_Importe = $row['FDe_Importe'];
    $suma+=$FDe_Importe;
    ?>
     <tr class="texto" style="border: solid 1px;">
        <td><?php echo $FDe_Cantidad?></td>
        <td><?php echo $FDe_Detalle?></td>
        <td align="center">$ <?php echo $FDe_Importe?></td>
      </tr>

    
    <?php
}
$suma = number_format($suma,2,'.','');
if ($FTi_ID == 1) {  
     $imprimirRecibo = "imprimirFacturaCuota";
 }
 if ($FTi_ID == 2) {    
     $imprimirRecibo = "imprimirReciboCuota";
 }
 if ($FTi_ID == 3) {    
     $imprimirRecibo = "imprimirFacturaCuota";
 }
?>
 <tr class="texto" style="border: solid 1px;">
 <td  colspan="2" style="background-color:#CCC"><a href="<?php echo $imprimirRecibo;?>.php?Fac=<?php echo $row2[Fac_ID];?>" target="_blank" id="imprimirCuotaSimple2"><img src="imagenes/printer.png" alt="Imprimir el recibo nuevamente" title="Imprimir el recibo nuevamente" width="32" border="0" align="absmiddle" /></a></td>
        <td align="center" style="background-color:#CCC">$ <?php echo $suma?></td>
      </tr>
    </table></div>

<!-- Eze 15/09 -->
<?php if (isset($_POST['NdeC'])) {
    obtenerRegistroUsuario($UsuID_Ndec, $Fecha_Ndec, $Hora_Ndec);
?>
    <input type="hidden" id="Fac_ID_Ndec" name="Fac_ID_Ndec" value="<?php echo $Fac_ID;?>">
    <input type="hidden" id="UsuID_Ndec" name="UsuID_Ndec" value="<?php echo $UsuID_Ndec;?>">
    <input type="hidden" id="Fecha_Ndec" name="Fecha_Ndec" value="<?php echo $Fecha_Ndec;?>">
    <input type="hidden" id="Hora_Ndec" name="Hora_Ndec" value="<?php echo $Hora_Ndec;?>">
    <div>
        <table width="85%" border="0" align="center" cellpadding="2" cellspacing="2" class="texto">
        <tr>
        <td align="right">Nota de Crédito Nº:</td>
        <td align="left"><input name="NdecNumero" type="text" class="input_editar" style="font-size:18px" id="NdecNumero" size="20" maxlength="13" />
        </td>
        <td></td>
        </tr>
        <tr>
        <td align="right">Pedir autorización</td>
        <td align="left"><?php cargarListaAdministradores("UsuAut");?></td>
        <td><button name="NdecBot" id="NdecBot"> Generar Nota de Crédito </button></td>
        </tr>
        <tr>
        <td align="right"></td>
        <td align="left"></td>
        <td></td>
        </tr>
        </table>
    </div>
<?php } ?>
<!-- Eze 15/09 -->
<script type='text/javascript' src='js/jquery.maskedinput-1.3.min.js'></script>
<script language="javascript">
$(document).ready(function(){
//Eze 15/09
    $("#NdecNumero").mask("9999-99999999");

    $.ajax({
        type: "POST",
        cache: false,
        async: false,
        data: {opcion: 'buscarNdecUltima'},
        url: 'cargarOpciones.php',
        success: function(data){ 
                if (data)
                    $("#NdecNumero").val(data);
        }
        
    });//fin ajax///

    $("#NdecBot").click(function(evento){

        Fac_ID_Ndec=$("#Fac_ID_Ndec").val();
        NdecNumero=$("#NdecNumero").val();
        UsuID_Ndec=$("#UsuID_Ndec").val();
        Fecha_Ndec=$("#Fecha_Ndec").val();
        Hora_Ndec=$("#Hora_Ndec").val();
        UsuAut = $("#UsuAut option:selected").val();
        if (UsuAut==-1){
            alert("ERROR: Debe seleccionar una persona para autorizar");
            return;
        }
        
        /*jConfirm('¿Está seguro que desea Anular Factura?', 'Confirmar la Anulacion', function(resp){
            if (resp){*/

        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
              alert(textStatus);},
            data: {opcion: "buscarCodigoAutorizacion"},
            url: 'cargarOpciones.php',
            success: function(data){ 
               jPrompt('Ingrese el código de autorización: <br>' + data.trim() , '', 'Busque la posición', function(r){
                if (r){
                    //alert("Bien");
                    cod = data.trim();
                    $.ajax({
                        type: "POST",
                        cache: false,
                        async: false,
                        error: function (XMLHttpRequest, textStatus){
                          alert(textStatus);},
                        data: {opcion: "buscarAutorizacion", UsuAut: UsuAut, Coordenadas: cod, valor: r},
                        url: 'cargarOpciones.php',
                        success: function(data){ 
                            
                            if (data.trim()!="Bien"){
                                jAlert(data, "Resultado");
                            }else{
                            //alert("Generamos");
                            $.ajax({
                                url: 'cargarOpciones.php',
                                type: "POST",
                                cache: false,
                                data: { opcion: "Cargar_Ndec", Fac_ID_Ndec: Fac_ID_Ndec, NdecNumero: NdecNumero, UsuID_Ndec: UsuID_Ndec, Fecha_Ndec: Fecha_Ndec, Hora_Ndec: Hora_Ndec, UsuAut: UsuAut },
                                async: false,
                                success: function(data4){
                                    
                                    if ((data4 != "Ya existe nota de credito con ese numero") && (data4 != "El usuario no tiene una caja abierta!")) {
                                        Fac_NotaCredito = data4;
                                        $.ajax({
                                            url: 'cargarOpciones.php',
                                            type: "POST",
                                            cache: false,
                                            data: { opcion: "Anular_Factura", Fac_ID: Fac_ID, Fac_NotaCredito: Fac_NotaCredito },
                                            async: false,
                                            success: function(data2){
                                                //alert (data2);
                                                    
                                                $("#probar").html(data2);
                                                Fac_Numero= $("#Fac_Numero").val();
                                                fecha1= $("#fecha1").val();
                                                fecha2= $("#fecha2").val();
                                                Ciclo_lectivo=  $("#Ciclo_lectivo").val();
                                                Mes= $("#Mes").val();
                                                opcionnum= $("#opcionnum").val();
                                                per_ID=$("#per_ID1").val();
                                                
                                                $.ajax({
                                                    url: 'listadoFactura.php',
                                                    type: "POST",
                                                    cache: false,
                                                    data: { Fac_Numero:Fac_Numero, opcionnum:opcionnum, fecha1:fecha1, fecha2:fecha2, Ciclo_lectivo:Ciclo_lectivo, Mes:Mes, per_ID:per_ID },
                                                    async: false,
                                                    success: function(data3){
                                                        $("#ContenidoTODO").html(data3);
                                                        jAlert("Nota de crédito realizada","Nota de C");
                                                    }
                                                });
                                                $imprimirRecibo = "imprimirFacturaCuota";
                                                window.open('<?php echo $imprimirRecibo;?>.php?Fac=' + Fac_NotaCredito, '_blank');
                                                $("#dialog").dialog('close');
                                                
                                            }
                                        });//fin ajax
                                    }//fin if 
                                }//fi success
                            });//fin ajax
                            }//fin else
                           
                        }//fin success
                      });//fin ajax//*/                                 
                }else{
                    alert("ERROR: Ingrese un valor");return;
                }//fin if
            });//fin del confirm//*/
               
            }
        });//fin ajax//*/ 
                
            /*}//fin if
        });//fin del confirm//*/
    });

//Eze 15/09
    $("#imprimirCuotaSimple").click(function(evento){
        evento.preventDefault();
        //alert("");
        FacturaNumero = "<?php echo $row2[Fac_Sucursal]."-".$row2[Fac_Numero];?>";
        $.ajax({
                type: "POST",
                cache: false,
                async: false,           
                url: "mostrarFacturaImpresa.php",
                data: {FacturaNumero:FacturaNumero},
                success: function (data){
                        $("#conenedortodo").html(data);
                }
        });//fin ajax
    });



});//fin ready
</script>
<?php



    }//fin Fac_ID_Ndec	

//corregida el 22/11/2021- Mario
function Anular_Factura(){
$Fac_ID = $_POST['Fac_ID'];
$Fac_NotaCredito = $_POST['Fac_NotaCredito'];//ID de la Factura deNota de Credito
$infoFacturaNotaCredito = buscarInfoFactura($Fac_NotaCredito);
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

//Mario. 17/02/2022
//Para controlar que el usuario tenga una caja abierta donde irá a parar la nota de crédito    
$CajaID = cajaAbiertaUsuario($UsuID);
if (!$CajaID){
    $disabled = "disabled=disabled";
    echo "El usuario no tiene una caja abierta!";
}else { 
    
    if ((!empty($Fac_ID)) && (!empty($Fac_NotaCredito))){
        
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        //Mario. 28/06/2022 aplico transacciones porque da errores!
        consulta_iniciar();
        $todo_bien = false;

        $sql2="SELECT * FROM FacturaDetalle where FDe_Fac_ID='$Fac_ID' ORDER BY FDe_Item ASC;";
        $result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
        
        while($row2 = mysqli_fetch_array($result2)){
            $FDe_Item=$row2['FDe_Item'];
            $sqlCup = "UPDATE CuotaPago SET CuP_Anulada = '1'
            WHERE CuP_Fac_ID='$Fac_ID' AND CuP_FDe_Item='$FDe_Item';";
            consulta_mysql_2022($sqlCup,basename(__FILE__),__LINE__);
            
            $sql="SELECT * FROM CuotaPago WHERE  CuP_FDe_Item='$FDe_Item' AND CuP_Fac_ID='$Fac_ID'";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            $row = mysqli_fetch_array($result);
            $CuP_CTi_ID=$row[CuP_CTi_ID];
            $CuP_CCo_ID=$row[CuP_CCo_ID];
            $CCo_Debito=$row[CuP_Importe];
            $CuP_Per_ID=$row[CuP_Per_ID];
            $CuP_Lec_ID=$row[CuP_Lec_ID];
            $CuP_Per_ID=$row[CuP_Per_ID];
            $CuP_Niv_ID=$row[CuP_Niv_ID];
            $CuP_CTi_ID=$row[CuP_CTi_ID];
            $CuP_Alt_ID=$row[CuP_Alt_ID];
            $CuP_Numero=$row[CuP_Numero];
            
            $CCC_Referencia = $row[CuP_Lec_ID].";".$row[CuP_Per_ID].";".$row[CuP_Niv_ID].";".$row[CuP_CTi_ID].";".$row[CuP_Alt_ID].";".$row[CuP_Numero];

            if ($CuP_CTi_ID==16 || $CuP_CTi_ID==17){
                $sqlCup = "UPDATE CuotaPersona SET Cuo_Pagado = '0', Cuo_Anulado='1', Cuo_Motivo = 'Dado de baja por nota de credito' WHERE
            Cuo_Lec_ID = '$CuP_Lec_ID' AND Cuo_Per_ID = '$CuP_Per_ID' AND Cuo_Niv_ID = '$CuP_Niv_ID' AND Cuo_CTi_ID = '$CuP_CTi_ID' AND Cuo_Alt_ID = '$CuP_Alt_ID' AND Cuo_Numero = '$CuP_Numero' ;";
                consulta_mysql_2022($sqlCup,basename(__FILE__),__LINE__);
            }else{
                $sqlCup = "UPDATE CuotaPersona SET Cuo_Pagado = '0' WHERE
            Cuo_Lec_ID = '$CuP_Lec_ID' AND Cuo_Per_ID = '$CuP_Per_ID' AND Cuo_Niv_ID = '$CuP_Niv_ID' AND Cuo_CTi_ID = '$CuP_CTi_ID' AND Cuo_Alt_ID = '$CuP_Alt_ID' AND Cuo_Numero = '$CuP_Numero' ;";
                consulta_mysql_2022($sqlCup,basename(__FILE__),__LINE__);
            }
            //FIN CUOTA PERSONA
            
            $sql34="SELECT * FROM CuotaTipo WHERE CTi_ID='$CuP_CTi_ID'";
            $result34 = consulta_mysql_2022($sql34,basename(__FILE__),__LINE__);
            $row34 = mysqli_fetch_array($result34);
            $CTi_Nombre=$row34[CTi_Nombre];
                
            //CUENTA CORRIENTE
            $sqCCO="SELECT * FROM CuentaCorriente WHERE CCo_ID='$CuP_CCo_ID'";
            $resultCCO = consulta_mysql_2022($sqCCO,basename(__FILE__),__LINE__);
            $rowCCO = mysqli_fetch_array($resultCCO);
            $CCo_Concepto=$rowCCO[CCo_Concepto];
            //FIN CUENTA CORRIENTE
            
            $CCo_Usu_ID= $_SESSION['sesion_usuario'];
            
            $sq55="SELECT * FROM Usuario WHERE Usu_Nombre='$CCo_Usu_ID';";
            $result55 = consulta_mysql_2022($sq55,basename(__FILE__),__LINE__);
            $row55 = mysqli_fetch_array($result55);
            $CCo_Usu_ID=$row55[Usu_ID];
            
            $CCo_Concepto="$infoFacturaNotaCredito. Anula Pago ".$CTi_Nombre." - ".$CCo_Concepto;   
                    
            $sqlCup = "INSERT INTO CuentaCorriente (
            CCo_Per_ID, 
            CCo_Concepto, 
            CCo_Debito,  
            CCo_Saldo, 
            CCo_Fecha, 
            CCo_Hora, 
            CCo_Usu_ID)
            VALUES (
            '$CuP_Per_ID', 
            '$CCo_Concepto', 
            '$CCo_Debito', 
            '0', 
            CURDATE(), 
            CURTIME(), 
            '$CCo_Usu_ID'
            )";
            $res=ejecutar_2022($sqlCup,basename(__FILE__),__LINE__);
            if ($res["success"] == true){
                $CCo_ID = $res['id'];
            }else{
                echo "Mal";
            }
            
            actualizarSaldoCtaCte($CuP_Per_ID,$CCo_ID);
            
            $CajaID = cajaAbiertaUsuario($UsuID);
            if (!$CajaID){
                $disabled = "disabled=disabled";
            }else{
                //NUEVO CAJA CORRIENTE
                $sqlCaja = "SELECT * FROM CajaCorriente WHERE CCC_Referencia='$CCC_Referencia'";
                $resultCaja = consulta_mysql_2022($sqlCaja,basename(__FILE__),__LINE__);
                $rowCaja = mysqli_fetch_array($resultCaja);
                $CajaID2=$rowCaja[CCC_Caja_ID];
                $CCC_For_ID=$rowCaja[CCC_For_ID];
                $CCC_Detalle=$rowCaja[CCC_Detalle];
                
                if($CajaID2==$CajaID){
                    $bandera=0;
                    $sqlfac2="SELECT * FROM Factura INNER JOIN FacturaTipo ON (Fac_FTi_ID = FTi_ID) WHERE Fac_ID=$Fac_ID";
                    $resultfac2 = consulta_mysql_2022($sqlfac2,basename(__FILE__),__LINE__);
                    $rowfac2 = mysqli_fetch_array($resultfac2);
                    
                    $CCC_Concepto=$CCo_Concepto.". Recibo: $rowfac2[FTi_Nombre] $rowfac2[Fac_Sucursal]-$rowfac2[Fac_Numero]";
                        
                    $sqlCup = "INSERT INTO CajaCorriente ( 
                    CCC_Caja_ID, 
                    CCC_Concepto, 
                    CCC_Debito, 
                    CCC_Fecha, 
                    CCC_Hora, 
                    CCC_Usu_ID, 
                    CCC_For_ID,
                    CCC_Detalle,
                    CCC_Referencia)
                    VALUES (
                    '$CajaID', 
                    '$CCC_Concepto', 
                    '$CCo_Debito',  
                     CURDATE(), 
                     CURTIME(), 
                    '$CCo_Usu_ID', 
                    '$CCC_For_ID',
                    '$CCC_Detalle',
                    '$CCC_Referencia'
                    );";
                    $res=ejecutar_2022($sqlCup,basename(__FILE__),__LINE__);
                    if ($res["success"] == true){
                        $CCC_ID = $res['id'];
                    }else{
                        echo "Mal";
                    }

                    actualizarSaldoCajaCorriente($CajaID,$CCC_ID);            
                
                }else{
                
                    $sqlfac2="SELECT * FROM Factura INNER JOIN FacturaTipo ON (Fac_FTi_ID = FTi_ID) WHERE Fac_ID=$Fac_ID";
                    $resultfac2 = consulta_mysql_2022($sqlfac2,basename(__FILE__),__LINE__);
                    $rowfac2 = mysqli_fetch_array($resultfac2);
                    
                    $CCC_Concepto=$CCo_Concepto.". Recibo: $rowfac2[FTi_Nombre] $rowfac2[Fac_Sucursal]-$rowfac2[Fac_Numero]";
                        
                    $sqlCup = "INSERT INTO CajaCorriente ( 
                    CCC_Caja_ID, 
                    CCC_Concepto, 
                    CCC_Debito, 
                    CCC_Fecha, 
                    CCC_Hora, 
                    CCC_Usu_ID, 
                    CCC_For_ID,
                    CCC_Detalle,
                    CCC_Referencia)
                    VALUES (
                    '$CajaID', 
                    '$CCC_Concepto', 
                    '$CCo_Debito',  
                     CURDATE(), 
                     CURTIME(), 
                    '$CCo_Usu_ID', 
                    '$CCC_For_ID',
                    '$CCC_Detalle',
                    '$CCC_Referencia'
                    );";
                    $res=ejecutar_2022($sqlCup,basename(__FILE__),__LINE__);
                    if ($res["success"] == true){
                        $CCC_ID = $res['id'];
                    }else{
                        echo "Mal";
                    }
                    actualizarSaldoCajaCorriente($CajaID,$CCC_ID);                 
                }
                //FIN DE NUEVO CAJA CORRIENTE
            }// else de la caja si esta abierta
        } // FIN WHILE

        $sqlCup = "UPDATE Factura SET Fac_Pagada = '0', Fac_Anulada = '1' WHERE Fac_ID = '$Fac_ID';";
        consulta_mysql_2022($sqlCup,basename(__FILE__),__LINE__);
        $todo_bien = true;
        if (!$todo_bien){ 
            consulta_retroceder();
        }else{
            consulta_terminar();
        }

    }else echo "ERROR: faltan datos!";

} 
//***************

}//fin de function



	/**
 * Description of guardarRetiroDinero
 *
 * @author Balmaceda Diego
 */
function guardarPagosVarios(){
	
	$forma = $_POST['forma'];
	$nrocaja = $_POST['caja'];
	$total = $_POST['total'];
	$observaciones = $_POST['observaciones'];
	$importe = $_POST['valor'];
	$tot = $total - $importe;
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
		
	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Debito, CCC_Saldo, CCC_Fecha, CCC_Hora, CCC_Usu_ID, CCC_For_ID, CCC_Ret_Dinero) VALUES ($nrocaja, '$observaciones', $importe, $tot, '$Fecha', '$Hora', $UsuID, $forma, 1)";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	
}//fin function

function editarConfiguracionCuotas()
{
	//echo "NAHUEL";
	$CMo_Lec_ID = $_POST['Lec_ID'];
	//echo "Lec_ID ".$CMo_Lec_ID."<br />";
	$CMo_Niv_ID = $_POST['Niv_ID'];
	//echo "Niv_ID ".$CMo_Niv_ID."<br />";
	$CMo_Alt_ID = $_POST['Alt_ID'];
	//echo "Alt_ID ".$CMo_Alt_ID."<br />";
	$CMo_CTi_ID = $_POST['CTi_ID'];
	//echo "CTi_ID ".$CMo_CTi_ID."<br />";
	$CMo_Importe = $_POST['CMo_Importe'];
	//echo "CMo_Importe ".$CMo_Importe."<br />";
	$CMo_Recargo_Mensual = $_POST['CMo_Recargo_Mensual'];
	$CMo_1er_Recargo = $_POST['CMo_1er_Recargo'];
    $CMo_2do_Recargo = $_POST['CMo_2do_Recargo'];

	//echo "CMo_Recargo_Mensual ".$CMo_Recargo_Mensual."<br />";
	$CMo_Mes = $_POST['CMo_Mes'];
	//echo "CMo_Mes ".$CMo_Mes."<br />";
	$CMo_CantCuotas = $_POST['CMo_CantCuotas'];
    $CMo_Numero = $_POST['CMo_Numero'];
    $Numero = $_POST['Numero'];
	//echo "CMo_CantCuotas ".$CMo_CantCuotas."<br />";
	$CMo_1er_Vencimiento = cambiaf_a_mysql($_POST['CMo_1er_Vencimiento']);
    $CMo_2do_Vencimiento = cambiaf_a_mysql($_POST['CMo_2do_Vencimiento']);
    $CMo_3er_Vencimiento = cambiaf_a_mysql($_POST['CMo_3er_Vencimiento']);

    //Mario. 23112021
    if (empty($_POST['CMo_2do_Vencimiento'])) $CMo_2do_Vencimiento=$CMo_1er_Vencimiento;
	if (empty($_POST['CMo_3er_Vencimiento'])) $CMo_3er_Vencimiento=$CMo_1er_Vencimiento;

    //Mario 04/04/2022. para cuota agrupada y especial
    $CMo_Agrupa = 0;
    $CMo_Especial = 0;
    //echo "CMo_Agrupa ".$_POST['CMo_Agrupa']."<br />";
    //echo "CMo_Especial ".$_POST['CMo_Especial']."<br />";

    if($_POST['CMo_Agrupa']==1){
        $CMo_Agrupa = 1;
    }
    if($_POST['CMo_Especial']==1){
        $CMo_Especial = 1;
    }
    //***********************************************
	
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

	//return false;
	$sql = "UPDATE CuotaModelo SET	
	CMo_Importe = '$CMo_Importe' , 
	CMo_Mes = '$CMo_Mes' , 
	CMo_1er_Recargo = '$CMo_1er_Recargo',
    CMo_2do_Recargo = '$CMo_2do_Recargo',
	CMo_Recargo_Mensual = '$CMo_Recargo_Mensual',
	CMo_CantCuotas = '$CMo_CantCuotas',
	CMo_1er_Vencimiento = '$CMo_1er_Vencimiento',
    CMo_2do_Vencimiento = '$CMo_2do_Vencimiento',
    CMo_3er_Vencimiento = '$CMo_3er_Vencimiento',
    CMo_Numero = '$CMo_Numero',
    CMo_Agrupa = '$CMo_Agrupa',
    CMo_Especial = '$CMo_Especial',
    CMo_Fecha = '$Fecha', 
    CMo_Hora = '$Hora', 
    CMo_Usu_ID = '$UsuID'
	WHERE
	CMo_Lec_ID = '$CMo_Lec_ID' AND CMo_Niv_ID = '$CMo_Niv_ID' AND CMo_CTi_ID = '$CMo_CTi_ID' AND CMo_Alt_ID = '$CMo_Alt_ID' AND CMo_Numero = '$Numero';";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "Se edito correctamente";

}
//Esta función actualizará los importes de las cuotas ya asignadas a los alumnos
/*function actualizarConfiguracionCuotas()
{
	$Lec_ID = $_POST['Lec_ID'];
	//echo "Lec_ID ".$Lec_ID."<br />";
	$Niv_ID = $_POST['Niv_ID'];
	//echo "Niv_ID ".$Niv_ID."<br />";
	$Alt_ID = $_POST['Alt_ID'];
	//echo "Alt_ID ".$Alt_ID."<br />";
	$CTi_ID = $_POST['CTi_ID'];

    $Numero = $_POST['Numero'];
	//echo "CTi_ID ".$CTi_ID."<br />";
	
	$sql="SELECT * FROM CuotaModelo WHERE CMo_Lec_ID='$Lec_ID' AND CMo_Niv_ID='$Niv_ID' AND CMo_CTi_ID='$CTi_ID' AND CMo_Alt_ID='$Alt_ID' AND CMo_Numero='$Numero'";
	//echo "sq55: ".$sq55."<br />";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	
	$CMo_1er_Vencimiento=$row[CMo_1er_Vencimiento];
    //$CMo_2do_Vencimiento=$row[CMo_2do_Vencimiento];
    //$CMo_3er_Vencimiento=$row[CMo_3er_Vencimiento];
	//echo "CMo_1er_Vencimiento ".$CMo_1er_Vencimiento."<br />";
	$CMo_Mes=$row[CMo_Mes];
	//echo "CMo_Mes ".$CMo_Mes."<br />";
	$CMo_Recargo_Mensual=$row[CMo_Recargo_Mensual];
	//echo "CMo_Recargo_Mensual ".$CMo_Recargo_Mensual."<br />";
	$CMo_CantCuotas=$row[CMo_CantCuotas];
	//echo "CMo_CantCuotas ".$CMo_CantCuotas."<br />";
	$CMo_Importe=$row[CMo_Importe];
	//echo "CMo_Importe ".$CMo_Importe."<br />";
	$CMo_Anio=$row[CMo_Anio];
	//echo "CMo_Anio ".$CMo_Anio."<br />";
	
	$CMo_FechaUltima = $CMo_Anio."-12-31";
	
	$sql="UPDATE CuotaPersona SET Cuo_Importe = '$CMo_Importe', Cuo_ImporteOriginal = '$CMo_Importe', Cuo_Recargo_Mensual = '$CMo_Recargo_Mensual' WHERE Cuo_Lec_ID='$Lec_ID' AND Cuo_Niv_ID='$Niv_ID' AND Cuo_CTi_ID='$CTi_ID' AND Cuo_Alt_ID='$Alt_ID' AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento >= '$CMo_1er_Vencimiento' AND Cuo_1er_Vencimiento <= '$CMo_FechaUltima'";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	echo "Se actualizaron ".mysql_affected_rows()." registros<br />";
	//return false;
	
	
	
}//fin function actualizar*/

function actualizarConfiguracionCuotas()
{
    $Lec_ID = $_POST['Lec_ID'];
    //echo "Lec_ID ".$Lec_ID."<br />";
    $Niv_ID = $_POST['Niv_ID'];
    //echo "Niv_ID ".$Niv_ID."<br />";
    $Alt_ID = $_POST['Alt_ID'];
    //echo "Alt_ID ".$Alt_ID."<br />";
    $CTi_ID = $_POST['CTi_ID'];
    $Numero = $_POST['Numero'];
    //echo "CTi_ID ".$CTi_ID."<br />";
    
    $sql="SELECT * FROM CuotaModelo WHERE CMo_Lec_ID='$Lec_ID' AND CMo_Niv_ID='$Niv_ID' AND CMo_CTi_ID='$CTi_ID' AND CMo_Alt_ID='$Alt_ID' AND CMo_Numero='$Numero'";
    //echo "sq55: ".$sq55."<br />";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $row = mysqli_fetch_array($result);
    
    $CMo_1er_Vencimiento=$row[CMo_1er_Vencimiento];
    //echo "CMo_1er_Vencimiento ".$CMo_1er_Vencimiento."<br />";
    $CMo_Mes=$row[CMo_Mes];
    //echo "CMo_Mes ".$CMo_Mes."<br />";
    $CMo_Recargo_Mensual=$row[CMo_Recargo_Mensual];
    //echo "CMo_Recargo_Mensual ".$CMo_Recargo_Mensual."<br />";
    $CMo_CantCuotas=$row[CMo_CantCuotas]+ $Numero;
    //echo "CMo_CantCuotas ".$CMo_CantCuotas."<br />";
    $CMo_Importe=$row[CMo_Importe];
    //echo "CMo_Importe ".$CMo_Importe."<br />";
    $CMo_Anio=$row[CMo_Anio];
    //echo "CMo_Anio ".$CMo_Anio."<br />";
    
    $CMo_FechaUltima = $CMo_Anio."-12-31";
    
    $sql="UPDATE CuotaPersona SET Cuo_Importe = '$CMo_Importe', Cuo_ImporteOriginal = '$CMo_Importe', Cuo_Recargo_Mensual = '$CMo_Recargo_Mensual' WHERE Cuo_Lec_ID='$Lec_ID' AND Cuo_Niv_ID='$Niv_ID' AND Cuo_CTi_ID='$CTi_ID' AND Cuo_Alt_ID='$Alt_ID' AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento >= '$CMo_1er_Vencimiento' AND Cuo_1er_Vencimiento <= '$CMo_FechaUltima'";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    $sql = "SELECT DISTINCTROW Cuo_Per_ID, Cuo_Lec_ID, Cuo_Niv_ID FROM CuotaPersona WHERE Cuo_Lec_ID='$Lec_ID' AND Cuo_Niv_ID='$Niv_ID' AND Cuo_CTi_ID='$CTi_ID' AND Cuo_Alt_ID='$Alt_ID' AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0 AND Cuo_Numero >= $Numero AND Cuo_Numero <= $CMo_CantCuotas";
    //echo $sql;exit;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $mostrarContActualizado = 0;
    $contActualizado = 0;
    while ($row = mysqli_fetch_array($result)) {
        $Cuo_Per_ID = $row['Cuo_Per_ID'];
        $Cuo_Lec_ID = $row['Cuo_Lec_ID'];
        $Cuo_Niv_ID = $row['Cuo_Niv_ID'];
        actualizarCuotaCTIConfiguracion($Cuo_Per_ID, $Cuo_Lec_ID, $Cuo_Niv_ID, $CTi_ID, true, $contActualizado);
        $mostrarContActualizado += $contActualizado;
        //aplicarBeneficioCuota($Cuo_Lec_ID, $Cuo_Per_ID, $row2[CMo_Niv_ID], $row2[CMo_CTi_ID], $row2[CMo_Alt_ID], $i, $row_cuo[Cuo_Ben_ID]);
    }//fin while
    //echo $sql;
    echo "Se actualizaron ".$mostrarContActualizado." registros<br />";
    //return false;
    
    
    
}//fin function actualizar

function mostrarVistaPrevia()
{
	$PerID = $_POST['PerID'];
	//echo "PerID ".$PerID."<br />";
	$CTi_ID = $_POST['CTi_ID'];
	//echo "CTi_ID ".$CTi_ID."<br />";
	$Alt_ID = $_POST['Alt_ID'];
	//echo "Alt_ID ".$Alt_ID."<br />";
	$CMo_CantCuotas = $_POST['CMo_CantCuotas'];
	//echo "CMo_CantCuotas ".$CMo_CantCuotas."<br />";
	$CMo_Importe = $_POST['CMo_Importe'];
	//echo "CMo_Importe ".$CMo_Importe."<br />";
	$CMo_1er_Vencimiento = $_POST['CMo_1er_Vencimiento'];
    $CMo_2do_Vencimiento = $_POST['CMo_2do_Vencimiento'];
    $CMo_3er_Vencimiento = $_POST['CMo_3er_Vencimiento'];
	//echo "CMo_1er_Vencimiento ".$CMo_1er_Vencimiento."<br />";
	$CMo_Mes = $_POST['CMo_Mes'];
	//echo "CMo_Mes ".$CMo_Mes."<br />";
	$CMo_Anio = $_POST['CMo_Anio'];
	//echo "CMo_Anio ".$CMo_Anio."<br />";
	$CMo_1er_Recargo = $_POST['CMo_1er_Recargo'];
    $CMo_2do_Recargo = $_POST['CMo_2do_Recargo'];
	$CMo_Recargo_Mensual = $_POST['CMo_Recargo_Mensual'];
	//echo "CMo_Recargo_Mensual ".$CMo_Recargo_Mensual."<br />";
	
	
	
	$sql2="SELECT 	* 	FROM Persona WHERE Per_ID=$PerID;";
	$result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
	$row2 = mysqli_fetch_array($result2);
	$Per_Nombre=$row2[Per_Nombre];
	$Per_Apellido=$row2[Per_Apellido];
	$_SESSION['sesion_ultimoDNI'] = $row2[Per_DNI];
	
	
	
	?>
    
    <script type="text/javascript">
	$(document).ready(function(){
		/*total=0;
		importe=$("#CMo_Importe"+i).val();
		importe=parseFloat(importe);*/
	
		$(".tiempoIMporte").keydown(function(){
			setTimeout(function() {
   // alert("asdempoIMpasd");
	 var suma=0;
	 CMo_CantCuotas=parseFloat($('#CMo_CantCuotas').val());
	//asd=$('#CMo_Importe'+1).val();
	//alert(asd);
	 for(j=1;j<=CMo_CantCuotas;j++)
	 {
		 importe=parseFloat($('#CMo_Importe'+j).val());
		 // alert(importe)
		 if(!isNaN(importe))
		 {
		 suma+=importe
		 }
		
	 }
	  $("#total").empty();
	$("#total").html(suma);
	//alert(suma);
	 
	 
	 
}, 500);
			})
		
$(".botones").button();


$("#GuardarVistaPrevia").click(function() {

	   
	   CMo_CantCuotas=$('#CMo_CantCuotas').val();
	   PerID=$('#PerID').val();
	   CTi_ID=$('#CTi_ID').val();
	   Alt_ID=$('#Alt_ID').val();
	   CMo_1er_Vencimiento=$('#CMo_1er_Vencimiento').val();
       CMo_2do_Vencimiento=$('#CMo_2do_Vencimiento').val();
       CMo_3er_Vencimiento=$('#CMo_3er_Vencimiento').val();
	   CMo_Mes=$('#CMo_Mes').val();
	   CMo_Anio=$('#CMo_Anio').val();
	   CMo_Recargo_Mensual=$('#CMo_Recargo_Mensual').val();
	   CMo_1er_Recargo=$('#CMo_1er_Recargo').val();
       CMo_2do_Recargo=$('#CMo_2do_Recargo').val();
	//return false;
	var miArray = new Array() 
	var i=0;
		 for(j=1;j<=CMo_CantCuotas;j++)
	 {
		 
		importe2=parseFloat($('#CMo_Importe'+j).val());
		if(!isNaN(importe2))
		{
			 miArray[i]=importe2;
			 //alert(miArray[i])
			 i++;
		}
		 
	 }
	
	$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
					data: {opcion:"GuardarVistaPrevia",CMo_CantCuotas:CMo_CantCuotas,PerID:PerID,CTi_ID:CTi_ID,Alt_ID:Alt_ID,CMo_1er_Vencimiento:CMo_1er_Vencimiento,CMo_2do_Vencimiento:CMo_2do_Vencimiento,CMo_3er_Vencimiento:CMo_3er_Vencimiento,CMo_Mes:CMo_Mes,CMo_Anio:CMo_Anio, CMo_1er_Recargo: CMo_1er_Recargo, CMo_2do_Recargo: CMo_2do_Recargo, CMo_Recargo_Mensual:CMo_Recargo_Mensual,miArray:miArray},
                    success: function (data){
                        jAlert(data, "Resultado de la operación");
                        //$("#cargando").hide();
						$("#barraCuotas2").click();
						//recargarPagina();
		
		}
		})//fin ajax
	
	
	
	});
	
	 function recargarPagina(){
            $("#mostrar").empty();

            $.ajax({
                cache: false,
                async: false,			
                url: "cargarCuotasImpagas.php",
                success: function (data){
                    $("#principal").html(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        }//fin function
	
	})
	</script>
   
    <input type="hidden" name="CMo_CantCuotas" id="CMo_CantCuotas" value="<?php echo $CMo_CantCuotas ?>" />
     <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
    <input type="hidden" name="CTi_ID" id="CTi_ID" value="<?php echo $CTi_ID ?>" />
    <input type="hidden" name="Alt_ID" id="Alt_ID" value="<?php echo $Alt_ID ?>" />
    <input type="hidden" name="CMo_1er_Vencimiento" id="CMo_1er_Vencimiento" value="<?php echo $CMo_1er_Vencimiento ?>" />
    <input type="hidden" name="CMo_2do_Vencimiento" id="CMo_2do_Vencimiento" value="<?php echo $CMo_2do_Vencimiento ?>" />
    <input type="hidden" name="CMo_3er_Vencimiento" id="CMo_3er_Vencimiento" value="<?php echo $CMo_3er_Vencimiento ?>" />
    
     <input type="hidden" name="CMo_Mes" id="CMo_Mes" value="<?php echo $CMo_Mes ?>" />
     
      <input type="hidden" name="CMo_Anio" id="CMo_Anio" value="<?php echo $CMo_Anio ?>" />
      
       <input type="hidden" name="CMo_1er_Recargo" id="CMo_1er_Recargo" value="<?php echo $CMo_1er_Recargo ?>" />
       <input type="hidden" name="CMo_2do_Recargo" id="CMo_2do_Recargo" value="<?php echo $CMo_2do_Recargo ?>" />
    <input type="hidden" name="CMo_Recargo_Mensual" id="CMo_Recargo_Mensual" value="<?php echo $CMo_Recargo_Mensual ?>" />
       <style type="text/css">
   tr{
	  border:#000 solid 1px; border-collapse:collapse 
   }
   </style>
    
    
    <table  id="tablaPreviaCuotas" width="100%" align="center" style="border:#000 solid 1px; border-collapse:collapse">
   <tr height="60px" >
        <td colspan="7" align="center">		
		<div align="center" class="titulo_noticia"> Vista Previa de las Cuotas</div></td>
      </tr>
   
   <tr align="center" class="titulo_noticia">
   <td>Nº de Cuotas</td>
   <td>Mes</td><td>Año</td>
   <td>1º Vencimiento</td>
   <td>2º Vencimiento</td>
   <td>3º Vencimiento</td>
   <td>1º Recargo</td>
   <td>2º Recargo</td>
   <td>Recargo mensual</td>
   <td>Importe</td></tr>
   
   
   <?php
   $total=0;
   for($i=1;$i<=$CMo_CantCuotas;$i++)
   {
	   $total+=$CMo_Importe;
   ?>
   
    <tr align="center">
    <td><?php echo $i ?></td>
    <td><?php echo buscarMes($CMo_Mes) ?></td>
    <td><?php echo $CMo_Anio ?></td>
    <td><?php echo $CMo_1er_Vencimiento ?></td>
    <td><?php echo $CMo_2do_Vencimiento ?></td>
    <td><?php echo $CMo_3er_Vencimiento ?></td>
    <td><?php echo "$".$CMo_1er_Recargo ?></td>
    <td><?php echo "$".$CMo_2do_Recargo ?></td>
    <td><?php echo "$".$CMo_Recargo_Mensual ?></td>
    <td>$<input type="text" name="CMo_Importe<?php echo $i ?>" id="CMo_Importe<?php echo $i ?>" value="<?php echo $CMo_Importe ?>" size="10" maxlength="10" style=" text-align:center" class="tiempoIMporte" /></td>
    
    </tr>
   <?php
   $CMo_1er_Vencimiento=cambiaf_a_mysql($CMo_1er_Vencimiento);
   $CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
   $CMo_1er_Vencimiento=cfecha($CMo_1er_Vencimiento);

   $CMo_2do_Vencimiento=cambiaf_a_mysql($CMo_2do_Vencimiento);
   $CMo_2do_Vencimiento=date("Y-m-d", strtotime("$CMo_2do_Vencimiento +1 month"));
   $CMo_2do_Vencimiento=cfecha($CMo_2do_Vencimiento);

   $CMo_3er_Vencimiento=cambiaf_a_mysql($CMo_3er_Vencimiento);
   $CMo_3er_Vencimiento=date("Y-m-d", strtotime("$CMo_3er_Vencimiento +1 month"));
   $CMo_3er_Vencimiento=cfecha($CMo_3er_Vencimiento);
   
   //$CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
   if($CMo_Mes=='12')
   {
	   $CMo_Mes=1;
	   $CMo_Anio++;
	   
   }
   else
   {
	   $CMo_Mes++;
   }
   }
   ?>

   <tr>
   <td  colspan="6" align="right" class="titulo_noticia">TOTAL:</td> <td align="center"><div class="titulo_noticia" id="total">$<?php echo $total ?></div></td>
   </tr>
     <tr><td colspan="7" align="center" style="padding-top:25px;"><button class="botones" id="GuardarVistaPrevia">Guardar</button></td></tr>
    </table>
    <br />
<br />

		<?php	
}//fin function mostrarVistaPrevia


function mostrarVistaPreviaPlanPagos()
{
	$PerID = $_POST['PerID'];
	$CTi_ID = $_POST['CTi_ID'];
	$Alt_ID = $_POST['Alt_ID'];
	$CMo_CantCuotas = $_POST['CMo_CantCuotas'];
	$CMo_Importe = $_POST['CMo_Importe'];
	$CMo_Adelanto = $_POST['CMo_Adelanto'];
	$CMo_1er_Vencimiento = $_POST['CMo_1er_Vencimiento'];
    $CMo_2do_Vencimiento = $_POST['CMo_2do_Vencimiento'];
    $CMo_3er_Vencimiento = $_POST['CMo_3er_Vencimiento'];
	$CMo_Mes = $_POST['CMo_Mes'];
	$CMo_Anio = $_POST['CMo_Anio'];
	$CMo_Recargo_Mensual = $_POST['CMo_Recargo_Mensual'];
	$CMo_1er_Recargo = $_POST['CMo_1er_Recargo'];
	
	$sql2="SELECT *	FROM Persona WHERE Per_ID=$PerID;";
	$result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
	$row2 = mysqli_fetch_array($result2);
	$Per_Nombre=$row2[Per_Nombre];
	$Per_Apellido=$row2[Per_Apellido];
	$Per_DNI=$row2[Per_DNI];
	$_SESSION['sesion_ultimoDNI'] = $row2[Per_DNI];
	
	?>
    
    <script type="text/javascript">
	$(document).ready(function(){
	
		$(".tiempoIMporte").keydown(function(){
			setTimeout(function() {
				 var suma=0;
				 CMo_CantCuotas=parseInt($('#CMo_CantCuotas').val());
				//asd=$('#CMo_Importe'+1).val();
				//alert(asd);
				 for(j=1;j<=CMo_CantCuotas+1;j++)
				 {
					 importe=parseFloat($('#CMo_Importe'+j).val());
					 // alert(importe)
					 if(!isNaN(importe))
					 {
					 suma+=importe
					 }
					
				 }//fin for
		  		$("#total").empty();
				$("#total").html(suma);
			}, 500);//fin setTimeout
		});//fin keydown
		
$(".botones").button();


$("#GuardarVistaPrevia").click(function() {	
	CMo_CantCuotas=$('#CMo_CantCuotas').val() + 1;
	PerID=$('#PerID').val();
	CTi_ID=$('#CTi_ID').val();
	Alt_ID=$('#Alt_ID').val();
	CMo_1er_Vencimiento=$('#CMo_1er_Vencimiento').val();
    CMo_2do_Vencimiento=$('#CMo_2do_Vencimiento').val();
    CMo_3er_Vencimiento=$('#CMo_3er_Vencimiento').val();
	CMo_Mes=$('#CMo_Mes').val();
	CMo_Anio=$('#CMo_Anio').val();
	CMo_Recargo_Mensual=$('#CMo_Recargo_Mensual').val();
	CMo_1er_Recargo=$('#CMo_1er_Recargo').val();
    CMo_2do_Recargo=$('#CMo_2do_Recargo').val();
	var miArray = new Array();
	var i=0;
	for(j=1;j<=CMo_CantCuotas;j++){
		importe2=parseFloat($('#CMo_Importe'+j).val());
		if(!isNaN(importe2)){
			 miArray[i]=importe2;
			 i++;
		}//fin if
	}//fin for
	
	var arreCuotas = new Array();
	var j=0;
	$("input[id^='Nuevo']:checked").each(function(evento){
		valor=$(this).val();//alert(valor);
		arreCuotas[j]=valor;//alert(arreCuotas[j]);		 
		j++;
	});//fin each	
	//return;
	//Guardamos los datos del plan de pagos
	PadrePer_ID=$('#DNITutor').val();	
	PPa_DeudaTotal=$('#CMo_Importe').val();	
	$.ajax({
	  type: "POST",
	  cache: false,
	  async: false,			
	  url: 'cargarOpciones.php',
	  data: {opcion:"GuardarPlanPago",AlumnoPer_ID: PerID, PadrePer_ID: PadrePer_ID, PPa_DeudaTotal: PPa_DeudaTotal, arreCuotas: arreCuotas},
	  success: function (data){
		  if(data.substr(0,2)=="No"){
		  	jAlert(data, "ERROR");
			return;
		  }else{
		  	Cuo_Cancela_PPa_ID = data;
			$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,			
			  url: 'cargarOpciones.php',
			  data: {opcion:"GuardarVistaPrevia",CMo_CantCuotas:CMo_CantCuotas,PerID:PerID,CTi_ID:CTi_ID,Alt_ID:Alt_ID,CMo_1er_Vencimiento:CMo_1er_Vencimiento,CMo_Mes:CMo_Mes,CMo_Anio:CMo_Anio,CMo_1er_Recargo: CMo_1er_Recargo, CMo_Recargo_Mensual:CMo_Recargo_Mensual,miArray:miArray, Cuo_Cancela_PPa_ID: Cuo_Cancela_PPa_ID},
			  success: function (data){
				  $("#barraPlanesPago").click();
				  jAlert(data, "Resultado de la operación");
				  
				  //$("#cargando").hide();
				  //recargarPagina();
				}//fin if success
			})//fin ajax
		  }
		}//fin if success
	})//fin ajax
	
});//fin click
	
	 function recargarPagina(){
            $("#mostrar").empty();

            $.ajax({
                cache: false,
                async: false,			
                url: "cargarCuotasImpagas.php",
                success: function (data){
                    $("#principal").html(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        }//fin function
	
	})
	</script>
   
    <input type="hidden" name="CMo_CantCuotas" id="CMo_CantCuotas" value="<?php echo $CMo_CantCuotas ?>" />
     <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
    <input type="hidden" name="CTi_ID" id="CTi_ID" value="<?php echo $CTi_ID ?>" />
    <input type="hidden" name="Alt_ID" id="Alt_ID" value="<?php echo $Alt_ID ?>" />
    <input type="hidden" name="CMo_1er_Vencimiento" id="CMo_1er_Vencimiento" value="<?php echo $CMo_1er_Vencimiento ?>" />
    
     <input type="hidden" name="CMo_Mes" id="CMo_Mes" value="<?php echo $CMo_Mes ?>" />
     
      <input type="hidden" name="CMo_Anio" id="CMo_Anio" value="<?php echo $CMo_Anio ?>" />
      
       <input type="hidden" name="CMo_Recargo_Mensual" id="CMo_Recargo_Mensual" value="<?php echo $CMo_Recargo_Mensual ?>" />
     <input type="hidden" name="CMo_1er_Recargo" id="CMo_1er_Recargo" value="<?php echo $CMo_Recargo_Mensual ?>" />
       <style type="text/css">
   tr{
	  border:#000 solid 1px; border-collapse:collapse 
   }
   </style>
    
    
    <table  id="tablaPreviaCuotas" width="100%" align="center" style="border:#000 solid 1px; border-collapse:collapse">
   <tr height="60px" >
        <td colspan="7" align="center">		
		<div align="center" class="titulo_noticia"> Vista Previa de las Cuotas</div></td>
      </tr>
   
   <tr align="center" class="titulo_noticia"><td>Nº de Cuotas</td><td>Mes</td><td>Año</td><td>Fecha Vencimiento</td>
   <td>1º Recargo</td>
   <td>Recargo mensual</td>
   <td>Importe</td></tr>
   
   
   <?php
   $total=0;
   $totalAdeudado = $CMo_Importe;
   $totalSinAdelanto = intval($CMo_Importe - $CMo_Adelanto);
   //$valorCuota = $totalSinAdelanto / $CMo_CantCuotas;
   for($i=1;$i<=$CMo_CantCuotas + 1;$i++){
	   
	   if ($i == 1) $valorCuota = $CMo_Adelanto; else  $valorCuota = round($totalSinAdelanto / $CMo_CantCuotas);
	   $total+=$valorCuota;
   ?>
   
    <tr align="center"><td><?php echo $i ?></td><td><?php echo buscarMes($CMo_Mes) ?></td><td><?php echo $CMo_Anio ?></td><td><?php echo $CMo_1er_Vencimiento ?></td>
    <td><?php echo "$".$CMo_1er_Recargo ?></td>
    <td><?php echo "$".$CMo_Recargo_Mensual ?></td>
    <td>$<input type="text" name="CMo_Importe<?php echo $i ?>" id="CMo_Importe<?php echo $i ?>" value="<?php echo $valorCuota ?>" size="10" maxlength="10" style=" text-align:center" class="tiempoIMporte" /></td>
    
    </tr>
   <?php
   $CMo_1er_Vencimiento=cambiaf_a_mysql($CMo_1er_Vencimiento);
   $CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
   $CMo_1er_Vencimiento=cfecha($CMo_1er_Vencimiento);
   
   //$CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
   if($CMo_Mes=='12')
   {
	   $CMo_Mes=1;
	   $CMo_Anio++;
	   
   }
   else
   {
	   $CMo_Mes++;
   }
   }
   ?>

   <tr>
   <td  colspan="6" align="right" class="titulo_noticia">TOTAL:</td> <td align="center"><div class="titulo_noticia" id="total">$<?php echo $total ?></div></td>
   </tr>
     <tr><td colspan="7" align="center" style="padding-top:25px;"><button class="botones" id="GuardarVistaPrevia">Guardar Plan de Pago</button></td></tr>
    </table>
    <br />
<br />

		<?php	
}//fin function mostrarVistaPreviaPlanPagos

    
function GuardarVistaPrevia(){
	
	$miArray = $_POST['miArray'];
	$PerID = $_POST['PerID'];
	$CTi_ID = $_POST['CTi_ID'];
	$Alt_ID = $_POST['Alt_ID'];
	$CMo_CantCuotas = $_POST['CMo_CantCuotas'];
	$CMo_1er_Vencimiento = $_POST['CMo_1er_Vencimiento'];
	$CMo_1er_Vencimiento=cambiaf_a_mysql($CMo_1er_Vencimiento);
    
    $CMo_2do_Vencimiento = $_POST['CMo_2do_Vencimiento'];
    $CMo_2do_Vencimiento=cambiaf_a_mysql($CMo_2do_Vencimiento);
    
    $CMo_3er_Vencimiento = $_POST['CMo_3er_Vencimiento'];
    $CMo_3er_Vencimiento=cambiaf_a_mysql($CMo_3er_Vencimiento);
	
    $CMo_Mes = $_POST['CMo_Mes'];
	$CMo_Anio = $_POST['CMo_Anio'];
	$CMo_1er_Recargo = $_POST['CMo_1er_Recargo'];
    $CMo_2do_Recargo = $_POST['CMo_2do_Recargo'];
	$CMo_Recargo_Mensual = $_POST['CMo_Recargo_Mensual'];
	$Cuo_PPa_ID = $_POST['Cuo_Cancela_PPa_ID'];
	if (empty($Cuo_PPa_ID)) $Cuo_PPa_ID = 0;
	
	$sql2="SELECT *, MAX(Cuo_Numero) AS Cuo_Numero FROM 
	CuotaPersona INNER JOIN Lectivo ON Lec_ID = Cuo_Lec_ID WHERE Cuo_Per_ID=$PerID AND Lec_Nombre='$CMo_Anio' AND Cuo_CTi_ID = $CTi_ID GROUP BY Cuo_Lec_ID;";
	
	//echo $sql2;exit;
	$result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
	//***
	 if (mysqli_num_rows($result2) == 0){
		$Cuo_Numero=1;
		Obtener_LectivoActual($Cuo_Lec_ID, $Lec_Nombre);	
		$sql2="SELECT *, MAX(Cuo_Numero) AS Cuo_Numero FROM 
	CuotaPersona WHERE Cuo_Per_ID=$PerID AND Cuo_Lec_ID=$Cuo_Lec_ID GROUP BY Cuo_Lec_ID;";
		$result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
		 if (mysqli_num_rows($result2) > 0){	
			$row2 = mysqli_fetch_array($result2);
			$Cuo_Numero=$row2[Cuo_Numero]+1;
			$Cuo_Lec_ID=$row2[Cuo_Lec_ID];
			$Cuo_Niv_ID=$row2[Cuo_Niv_ID];
			$Cuo_Ben_ID=1;//$row2[Cuo_Ben_ID];
		 }else{
			$sql2="SELECT MIN(Cuo_Niv_ID) AS Cuo_Niv_ID	FROM CuotaPersona WHERE Cuo_Per_ID=$PerID;";
			$result_nivel = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
			$row_nivel = mysqli_fetch_array($result_nivel);
			$Cuo_Niv_ID=$row_nivel[Cuo_Niv_ID];
			$Cuo_Ben_ID=1;
		 }
	 }else{
		$row2 = mysqli_fetch_array($result2);
		$Cuo_Numero=$row2[Cuo_Numero]+1;
		$Cuo_Lec_ID=$row2[Cuo_Lec_ID];
		$Cuo_Niv_ID=$row2[Cuo_Niv_ID];
		$Cuo_Ben_ID=1;//$row2[Cuo_Ben_ID];
	 }
	 
	if ($Cuo_Niv_ID==0){
        $sql2="SELECT Ins_Niv_ID FROM Colegio_Inscripcion INNER JOIN Legajo ON (Ins_Leg_ID = Leg_ID) WHERE Leg_Per_ID=$PerID AND Ins_Lec_ID = $Cuo_Lec_ID;";
        $result_nivel = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
        $row_nivel = mysqli_fetch_array($result_nivel);
        $Cuo_Niv_ID=$row_nivel[Ins_Niv_ID];
    }
			 
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	//return false;
	 for($i=0;$i<count($miArray);$i++)
   {
	   //echo $miArray[$i];
	   
	  $sql="INSERT INTO CuotaPersona 
	(Cuo_Lec_ID, 
	Cuo_Per_ID, 
	Cuo_Niv_ID,
	Cuo_CTi_ID, 
	Cuo_Alt_ID, 
	Cuo_Numero, 
	Cuo_Ben_ID, 
	Cuo_Usu_ID, 
	Cuo_Fecha, 
	Cuo_Hora, 
	Cuo_Importe, 
	Cuo_1er_Recargo, 
	Cuo_2do_Recargo, 
	Cuo_1er_Vencimiento, 
	Cuo_2do_Vencimiento, 
	Cuo_3er_Vencimiento, 
	Cuo_Mes, 
	Cuo_Anio, 
	Cuo_Pagado, 
	Cuo_Cancelado, 
	Cuo_Anulado, 
	Cuo_Recargo_Mensual,
	Cuo_ImporteOriginal,
	Cuo_PPa_ID
	)
	VALUES
	('$Cuo_Lec_ID', 
	'$PerID', 
	'$Cuo_Niv_ID', 
	'$CTi_ID', 
	'$Alt_ID', 
	'$Cuo_Numero', 
	'$Cuo_Ben_ID', 
	'$UsuID', 
	'$Fecha', 
	'$Hora', 
	'$miArray[$i]', 
	'$CMo_1er_Recargo', 
	'$CMo_2do_Recargo', 
	'$CMo_1er_Vencimiento', 
	'$CMo_2do_Vencimiento', 
	'$CMo_3er_Vencimiento', 
	'$CMo_Mes', 
	'$CMo_Anio', 
	'0', 
	'0', 
	'0', 
	'$CMo_Recargo_Mensual',
	'$miArray[$i]',
	'$Cuo_PPa_ID'
	);"; 
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql."<br />";
	 
	// $CMo_Mes++; 
	
	 

   $CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
   $CMo_2do_Vencimiento=date("Y-m-d", strtotime("$CMo_2do_Vencimiento +1 month"));
   $CMo_3er_Vencimiento=date("Y-m-d", strtotime("$CMo_3er_Vencimiento +1 month"));
   //$CMo_1er_Vencimiento=cfecha($CMo_1er_Vencimiento); 
	   
	   $Cuo_Numero++;
	   
		 if($CMo_Mes=='12')
{
   $CMo_Mes=1;
   $CMo_Anio++;
   
}
else
{
   $CMo_Mes++;
} 
	   
	   
	   
   }//fin for
   
   echo "Guardado Correctamente";
   
}//fin function

function GuardarPlanPago(){
		  /*data: {opcion:"GuardarPlanPago",AlumnoPer_ID: PerID, PadrePer_ID: PadrePer_ID, PPa_DeudaTotal: PPa_DeudaTotal},
	  success: function (data){
		  Cuo_Cancela_PPa_ID = data;*/
	$PPa_Per_ID = $_POST['AlumnoPer_ID'];
	$PadrePer_ID = $_POST['PadrePer_ID'];
	$PPa_DeudaTotal = $_POST['PPa_DeudaTotal'];
	$arreCuotas = $_POST['arreCuotas'];
	if (empty($PadrePer_ID)){
		echo "No se pudo generar el Plan de Pago. El padre/madre no se encuentra cargado.";
		exit; 
	}
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql="SELECT * FROM Legajo
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID)
    INNER JOIN Colegio_Inscripcion 
        ON (Leg_ID = Ins_Leg_ID)
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cur_Niv_ID = Niv_ID) WHERE Per_ID=$PPa_Per_ID ORDER BY Ins_Lec_ID DESC;";
	//echo $sql2;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
	 if (mysqli_num_rows($result) > 0) {			
		$row = mysqli_fetch_array($result);
		$PPa_CursoAlumo=addslashes("$row[Cur_Nombre] '$row[Div_Nombre]'");
		$PPa_NivelAlumno=$row['Niv_Nombre'];
		$PPa_Alumno=addslashes("$row[Per_Apellido], $row[Per_Nombre]");
		$PPa_DNIAlumno=$row['Per_DNI'];
	 }else{
		echo "No se pudo generar el Plan de Pago. El alumno no se encuentra inscripto al Ciclo Lectivo.";
		exit;	
	 }
	obtenerRegistroUsuario($PPa_Usu_ID, $PPa_Fecha, $PPa_Hora);
	
	$sql="SELECT * FROM Familia
    INNER JOIN Persona 
        ON (Fam_Per_ID = Per_ID)
    INNER JOIN FamiliaTipo 
        ON (Fam_FTi_ID = FTi_ID)
    INNER JOIN PersonaDatos 
        ON (Dat_Per_ID = Per_ID) 
	 INNER JOIN Localidad 
        ON (Dat_Dom_Loc_ID = Loc_ID) AND (Dat_Dom_Pai_ID = Loc_Pai_ID) AND (Dat_Dom_Pro_ID = Loc_Pro_ID)
	WHERE Fam_Vin_Per_ID = $PPa_Per_ID AND Fam_Per_ID = $PadrePer_ID;";
	//echo $sql2;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	 if (mysqli_num_rows($result) > 0) {			
		$row = mysqli_fetch_array($result);
		if ($row['Per_Sexo']=="M") $PPa_RolTutor="Padre"; else $PPa_RolTutor="Madre";
		$PPa_DomicilioTutor=addslashes("$row[Dat_Domicilio], $row[Loc_Nombre]");
		$PPa_Tutor=addslashes("$row[Per_Apellido], $row[Per_Nombre]");
		$PPa_DNITutor=$row['Per_DNI'];
	 }else{
		echo "No se pudo generar el Plan de Pago. El padre/madre no se encuentra cargado o le faltan datos personales.";
		exit;	
	 }
	 
	 $sql = "INSERT INTO PlanPago (PPa_Per_ID, PPa_Fecha, PPa_Hora, PPa_Usu_ID, PPa_CursoAlumo, PPa_NivelAlumno, PPa_Alumno, PPa_DNIAlumno, PPa_RolTutor, PPa_Tutor, PPa_DNITutor, PPa_DomicilioTutor, PPa_DeudaTotal, PPa_DetalleCompromiso) VALUES($PPa_Per_ID, '$PPa_Fecha', '$PPa_Hora', $PPa_Usu_ID, '$PPa_CursoAlumo', '$PPa_NivelAlumno', '$PPa_Alumno', '$PPa_DNIAlumno', '$PPa_RolTutor', '$PPa_Tutor', '$PPa_DNITutor', '$PPa_DomicilioTutor', '$PPa_DeudaTotal', '$PPa_DetalleCompromiso')";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $Cuo_Cancela_PPa_ID = $res['id'];
    }else{
        echo "No se pudo generar el Plan de Pago. Error al guardar en la Base de Datos.";
        exit;
    }

	 //Marcamos todas las cuotas que serán cancaledas por el nuevo plan de pago	 
	 for($i=0;$i<count($arreCuotas);$i++){
	 	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $arreCuotas[$i]);
		$sql = "SELECT * FROM CuotaPersona 
		INNER JOIN Lectivo 
			ON (Cuo_Lec_ID = Lec_ID)
		INNER JOIN Colegio_Nivel 
			ON (Cuo_Niv_ID = Niv_ID)
		INNER JOIN CuotaTipo 
			ON (Cuo_CTi_ID = CTi_ID)
		WHERE Cuo_Lec_ID='$Cuo_Lec_ID' AND Cuo_Per_ID='$Cuo_Per_ID' AND Cuo_Niv_ID='$Cuo_Niv_ID' AND Cuo_CTi_ID='$Cuo_CTi_ID' AND Cuo_Alt_ID='$Cuo_Alt_ID' AND Cuo_Numero='$Cuo_Numero'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	
	 	if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$datosCuota = $arreCuotas[$i];
			$importe = $row[Cuo_Importe];
			$importeOriginal = $importe;
			recalcularImporteCuota($datosCuota, $importe);
			$importeAbonado = buscarPagosTotales($datosCuota);
			$importeActual = $importeOriginal - $importeAbonado;
			$recargoCuota = $importe - $importeActual;
			$fechaCuota = cfecha($row[Cuo_1er_Vencimiento]);
			//concepto,lectivo, vencimiento, importe, recargo, total
			$conceto = "$row[CTi_Nombre] (".buscarMes($row[Cuo_Mes])."/".$row[Cuo_Anio].")";
			$lectivo = $row['Lec_Nombre'];
			$detalleCompromiso .= "$conceto|$lectivo|$fechaCuota|$importeActual|$recargoCuota|$importe*";			
			$sql = "UPDATE CuotaPersona SET Cuo_Cancela_PPa_ID='$Cuo_Cancela_PPa_ID', Cuo_Cancelado = 1 WHERE Cuo_Lec_ID='$Cuo_Lec_ID' AND Cuo_Per_ID='$Cuo_Per_ID' AND Cuo_Niv_ID='$Cuo_Niv_ID' AND Cuo_CTi_ID='$Cuo_CTi_ID' AND Cuo_Alt_ID='$Cuo_Alt_ID' AND Cuo_Numero='$Cuo_Numero'";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		}//fin if
		
	 }//fin for
	 //exit;
	 $sql = "UPDATE PlanPago SET PPa_DetalleCompromiso = '$detalleCompromiso' WHERE PPa_ID = $Cuo_Cancela_PPa_ID";
	 consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	 echo $Cuo_Cancela_PPa_ID;
   
}//fin function

function guardarTraspasoDinero(){
		
	
	$forma = $_POST['forma'];
	$nrocaja = $_POST['caja'];
	$total = $_POST['total'];
	$observaciones = $_POST['observaciones'];
	$importe = $_POST['valor'];
	$tot = $total - $importe;
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$Usu_ID_traspaso = $_POST['Usu_ID_traspaso'];
	//echo "tot ".$tot."<br />";
	
	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Debito, CCC_Saldo, CCC_Fecha, CCC_Hora, CCC_Usu_ID, CCC_For_ID) VALUES ($nrocaja, 'Traspaso de Dinero: $observaciones', $importe, $tot, '$Fecha', '$Hora', $UsuID, $forma)";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql."<br />";
	
	//NUEVO TODO NUEVO
	
	$CajaID = cajaAbiertaUsuario($Usu_ID_traspaso);
	//echo "CajaID ".$CajaID."<br />";
	
	
	
	$sql = "SELECT For_ID,For_Nombre, SUM(CCC_Credito) - SUM(CCC_Debito)  AS total
FROM CajaCorriente INNER JOIN FormaPago ON (CajaCorriente.CCC_For_ID = FormaPago.For_ID) WHERE CCC_Caja_ID =$CajaID AND For_ID=$forma GROUP BY For_Nombre";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
		 if (mysqli_num_rows($result) > 0) {
			 
			 $row = mysqli_fetch_array($result);
			 $tot=$row['total']+$importe;
	  		// echo "tot ".$tot."<br />";
			 
		 }
		 else
		 {
			  $tot=0+$importe;
			  //echo "tot ".$tot."<br />";
		 }   
	
	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Credito, CCC_Saldo, CCC_Fecha, CCC_Hora, CCC_Usu_ID, CCC_For_ID) VALUES ($CajaID, 'Traspaso de Dinero: $observaciones', $importe, $tot, '$Fecha', '$Hora', $Usu_ID_traspaso, $forma)";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	
		
	}
function IngresoDineroEfectivo(){
		
	
	$forma = $_POST['forma'];
	//echo "forma ".$forma."<br />";
	$nrocaja = $_POST['caja'];
	//echo "nrocaja ".$nrocaja."<br />";
	$total = $_POST['total'];
	//echo "total ".$total."<br />";
	$observaciones = $_POST['observaciones'];
	//echo "observaciones ".$observaciones."<br />";
	$importe = $_POST['valor'];
	//echo "importe ".$importe."<br />";
	$tot = $total + $importe;
	//echo "tot ".$tot."<br />";
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
		
	$sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Credito, CCC_Saldo, CCC_Fecha, CCC_Hora, CCC_Usu_ID, CCC_For_ID, CCC_Ret_Dinero) VALUES ($nrocaja, 'Ingreso Dinero: $observaciones', $importe, $tot, '$Fecha', '$Hora', $UsuID, $forma, 1)";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	

	}
	
function mostrarDetalleAuditada()
{
?>
<script language="javascript">
    $(document).ready(function(){
		$(".botones").button();	
		
		
				 $("button[id^='btnguardar']").click(function(evento){
			// alert("asdasd");
			 //return false;
            evento.preventDefault();
            var j = this.id.substr(10,15);
			var Caja_ID = $("#Caja_ID"+ j).val();
			var i = $("#i"+j).val();
			//alert(vCaja_ID);
			 var vFor_ID = $("#For_ID"+ j).val();  
				//alert(vFor_ID);        
			
            $("input[id^='no_rendido']:checked").each(function () {
                
					
					//alert(vFor_ID);
					
				//alert(vCaja_ID);
			$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "guardarCajaAuditada", Caja_ID: Caja_ID, For_ID: vFor_ID},
                    success: function (data){
                        $("#cargando").hide();
						//$("#div_campos").html(data);
						mostrarAlerta("Se guardaron correctamente los cambios.", "Confirmación")
						
						$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "mostrarDetalleAuditada", Caja_ID: Caja_ID, i:i},
                    success: function (data){
                       // $("#cargando").hide();
						$("#div_campos"+i).html(data);
						//mostrarAlerta("Se guardaron correctamente los cambios.", "Confirmación")
                    }
                });//fin ajax///    
                    }
                });//fin ajax///
            });//           

           ;
            
           
            
        });
		
		
		$("button[id^='btncerrar']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(9,15);
            $("#tr_campos"+ i).hide(); 
		});
		
	})
</script>
<?php
$nocaja = $_POST['Caja_ID'];
$i = $_POST['i'];
//echo "nono".$nocaja."<br />";
//echo "i".$i."<br />";

$j=0;
	?><table>
                <?php	
				$sql1 = "SELECT Caja_ID, CRe_Importe, Usu_Persona, For_Nombre, CRe_Fecha_Rendida, CRe_Usu_Auditado, Usu_Persona, Usu_ID, For_ID
FROM
    CajaRendida
    INNER JOIN Usuario 
        ON (CajaRendida.CRe_Usu_ID = Usuario.Usu_ID)
    INNER JOIN FormaPago 
        ON (CajaRendida.CRe_For_ID = FormaPago.For_ID)
    INNER JOIN Caja 
        ON (CajaRendida.CRe_Caja_ID = Caja.Caja_ID)
        WHERE CRe_Caja_ID = '$nocaja'";
				$result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
				
				//echo $sql1."<br />";
				?>
                  <thead>
                    <tr class="gradeA" id="fila">
                      <th align="center" class="fila_titulo">Usuario</th>
                      <th align="center" class="fila_titulo">Forma de Pago</th>
                      <th align="center" class="fila_titulo">Importe</th>
                      <th align="center" class="fila_titulo">Fecha Rendida</th>
                      <th align="center" class="fila_titulo">Verificado</th>
                      <th align="center" class="fila_titulo">Verificado</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  while ($row1 = mysqli_fetch_array($result1)) {
                        $j++;
						
						 if($row1[CRe_Usu_Auditado] != ''){
							 $asdasd='style="background-color:#6F6"';
						 }
						 else
						 {
							 $asdasd='style="background-color:#F88"';
						 }
				  ?>
                    <tr <?php echo $asdasd ?>  >
                      <td align="center"><?php echo $row1[Usu_Persona] ?>
                      </td>
                      <td align="center"><?php echo $row1[For_Nombre] ?></td>
                      <td align="center"><?php echo $row1[CRe_Importe] ?></td>
                      <td align="center"><?php echo $row1[CRe_Fecha_Rendida] ?>
                      </td>
                      <td align="center">
                      <?php
                      if($row1[CRe_Usu_Auditado] != ''){
					  ?>
                      <input type="checkbox" checked="checked" disabled="disabled" id="rendido<?php echo $i.$j?>"/>                      
                      <?php
					  } else {
					  ?>                      
                      <input type="checkbox" id="no_rendido<?php echo $i.$j?>"/>
                      <?php
					  echo $j;
					  }
					  ?>
                      <input type="hidden" name="Caja_ID" id="Caja_ID<?php echo $i.$j?>" value="<?php echo $row1[Caja_ID];?>"/>
                      <input type="hidden" name="CRe_Usu_ID" id="CRe_Usu_ID<?php echo $i.$j?>" value="<?php echo $row1[Usu_ID];?>"/>
                      <input type="hidden"  name="For_ID" id="For_ID<?php echo $i.$j?>" value="<?php echo $row1[For_ID];?>"/>
                      <input type="hidden" name="i<?php echo $i.$j?>" id="i<?php echo $i.$j?>" value="<?php echo $i ?>" /></td>
                      <td><button class="botones" id="btnguardar<?php echo $i.$j?>">Guardar</button></td>
                    </tr>
                    <?php
                    }//fin while interno
                    ?>
                    <tr><td colspan="5">                    
                        <button class="botones" id="btncerrar<?php echo $i ?>">Cerrar</button></td>
                    </tr>
                  </tbody>
                </table>
                <?php
}

//25-04-2013 NAHUEL
function agregarBeneficioCuota()
{
$datosCuota = $_POST['Cuota'];
$Ben_ID = $_POST['Ben_ID'];
list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);

//echo "Ben_ID ".$Ben_ID."<br />";
//echo "Cuo_Lec_ID ".$Cuo_Lec_ID."<br />";
    
    
    $sql1 = "SELECT * FROM 
    CuotaPorcentaje WHERE Por_CTi_ID='$Cuo_CTi_ID' AND Por_Ben_ID='$Ben_ID';";
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
        //echo $sql;
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    }
}

//25-04-2013 FIN NAHUEL

function mostrarDatosPersonales1()
{
	
$PerID = $_POST['PerID'];
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);

?>
<input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
<script language="javascript">
$(document).ready(function(){
$(".botones").button();

$("#editarDatos").click(function(){
	
	PerID=$("#PerID").val();
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'editarDatosPersonales', PerID: PerID},
					url: 'cargarOpciones.php',
					success: function(data){ 
					$("#editarDatosPersonales").html(data);
					
					}
		});//fin ajax///
	
	})

})
</script>
<style type="text/css">

.tablilla1
{	
	border: solid 1px;
}

.tablilla1 tr td
{
	border-collapse:collapse;
	font-size:15px;
	border: solid 1px;
}
</style>


<?php
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql="SELECT * FROM Persona WHERE Per_ID='$PerID'";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$foto = buscarFoto($row[Per_DNI], $row[Per_Doc_ID], 60);
?>
 <table class="tablilla1" style="" width="80%">
    
    <tr>
    <th colspan="3" align="center" height="35px;" style="font-size:18px;">FICHA PERSONAL DEL ALUMNO</th>
    </tr>
        <tr><td rowspan="4" width="60px"><?php echo $foto ?></td></tr>
    <tr>
    <td bgcolor="#E4E4E4"><strong>Apellido Y Nombre:</strong></td><td><?php echo $row[Per_Apellido]." ".$row[Per_Nombre] ?></td>
    </tr>
     <tr>
    <td bgcolor="#E4E4E4"><strong>Nº Documento:</strong></td><td><?php echo $row[Per_DNI] ?></td>
    </tr>
     <tr>
    <td bgcolor="#E4E4E4"><strong>Sexo:</strong></td><td><?php if($row[Per_Sexo]=='M')
	{
		echo "Masculino";
	}
	if($row[Per_Sexo]=='F')
	{
		echo "Femenino";
	}?></td>
    </tr>
</table>
<br />
<table width="80%">
<tr align="right"><td><!--<button class="botones" id="editarDatos">Editar Datos Personales</button>--></td></tr>
</table>
	
    <?php
}
function mostrarDatosAdiccionales()
{
$PerID = $_POST['PerID'];
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);
obtenerTutores($PerID, $arrarTutores, $cant);
obtenerHermanos($PerID, $arrarHermanos, $cantH);

?>

<script language="javascript">
$(document).ready(function(){

$(".botones").button();


//NAHUEL33
$("#editarDatosAdicionales").click(function(){
	
	PerID=$("#PerID").val();
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'editarDatosAdiccionales', PerID: PerID},
					url: 'cargarOpciones.php',
					success: function(data){ 
					$("#editarDatosPersonalesAdicionales").html(data);
					
					}
		});//fin ajax///
	
	})

})
</script>

<style type="text/css">

.tablilla1
{	
	border: solid 1px;
}

.tablilla1 tr td
{
	border-collapse:collapse;
	font-size:15px;
	border: solid 1px;
	height:25px;
}
</style>
<?php	
$sql="SELECT * FROM PersonaDatos INNER JOIN Pais 
        ON (Dat_Dom_Pai_ID = Pai_ID)
    INNER JOIN Provincia 
        ON (Dat_Dom_Pro_ID = Pro_ID)
    INNER JOIN Localidad 
        ON (Dat_Dom_Loc_ID = Loc_ID) WHERE Dat_Per_ID=$PerID;";
		//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if(mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_array($result);
		if(($row['Dat_Nac_Pai_ID']!='')&&($row['Dat_Nac_Pro_ID']!='')&&($row['Dat_Nac_Loc_ID']!=''))
		{
			$sql3="SELECT Pai_ID, Pai_Nombre FROM Pais WHERE Pai_ID=$row[Dat_Nac_Pai_ID];";
			$result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
			$row3 = mysqli_fetch_array($result3);
			$Pai_Nombre=$row3[Pai_Nombre];
			
			$sql3="SELECT Pro_ID, Pro_Pai_ID, Pro_Nombre FROM Provincia WHERE Pro_ID=$row[Dat_Nac_Pro_ID]";
			$result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
			$row3 = mysqli_fetch_array($result3);
			$Pro_Nombre=$row3['Pro_Nombre'];
			
			$sql3="SELECT Loc_ID, Loc_Pai_ID, Loc_Pro_ID, Loc_Nombre 
			FROM Localidad  WHERE Loc_ID=$row[Dat_Nac_Loc_ID] ";
			$result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
			$row3 = mysqli_fetch_array($result3);
			$Loc_Nombre=$row3['Loc_Nombre'];
			
		}
		?>
        
         <table class="tablilla1" style="" width="80%">
    
    <tr>
    <th colspan="3" align="center" height="35px;" style="font-size:18px;">DATOS ADICIONALES</th>
    </tr>
    <tr>
    <td bgcolor="#E4E4E4" rowspan="5"><strong>Domicilio:</strong></td>
    </tr>
    <tr><td><strong>País:</strong></td><td><?php echo $row['Pai_Nombre'] ?></td></tr>
    <tr><td><strong>Provincia:</strong></td><td><?php echo $row['Pro_Nombre'] ?></td></tr>
    <tr><td><strong>Localidad:</strong></td><td><?php echo $row['Loc_Nombre'] ?></td></tr>
     <tr><td><strong>Dirección:</strong></td><td><?php echo $row['Dat_Domicilio'] ?></td></tr>

<tr>
    <td bgcolor="#E4E4E4" rowspan="5"><strong>Nacimiento:</strong></td>
    </tr>
    <tr><td><strong>País:</strong></td><td><?php echo $Pai_Nombre ?></td></tr>
    <tr><td><strong>Provincia:</strong></td><td><?php echo $Pro_Nombre ?></td></tr>
    <tr><td><strong>Localidad:</strong></td><td><?php echo $Loc_Nombre ?></td></tr>
     <tr><td><strong>Fecha Nac:</strong></td><td><?php echo cfecha($row['Dat_Nacimiento']) ?></td></tr>

     <tr>
    <td bgcolor="#E4E4E4"><strong>Código Postal:</strong></td><td colspan="2"><?php echo $row['Dat_CP'] ?></td>
    </tr>
     <tr>
    <td bgcolor="#E4E4E4"><strong>Email:</strong></td><td colspan="2"><?php echo $row['Dat_Email'] ?></td>
    </tr>
         <tr>
    <td bgcolor="#E4E4E4"><strong>Teléfono:</strong></td><td colspan="2"><?php echo $row['Dat_Telefono'] ?></td>
    </tr>
         <tr>
    <td bgcolor="#E4E4E4"><strong>Celular:</strong></td><td colspan="2"><?php echo $row['Dat_Celular'] ?></td>
    </tr>
             <!-- <tr>
    <td bgcolor="#E4E4E4"><strong>Ocupación:</strong></td><td colspan="2"><?php echo $row['Dat_Ocupacion'] ?></td>
    </tr> -->
             <tr>
    <td bgcolor="#E4E4E4"><strong>Observación Gral.:</strong></td><td colspan="2"><?php echo $row['Dat_Observaciones'] ?></td>
    </tr>
    <tr>
    <td bgcolor="#E4E4E4"><strong>Observación Económica:</strong></td><td colspan="2"><?php echo $row['Dat_ObservEconomicas'] ?></td>
    </tr>
</table>

<br />
<table width="80%">
<tr align="right"><td><!--<button class="botones" id="editarDatosAdicionales">Editar Datos Adicionales</button>--></td></tr>
</table>
       
        <?php
	}
	else
	{
		echo "NO SE ENCONTRARON DATOS ADICIONALES";
		?>
		<br />
<table width="80%">
<tr align="right"><td><button class="botones" id="editarDatosAdicionales">Cargar Datos Adicionales</button></td></tr>
</table>

<?php
	}

//Buscamos info de los padres
if ($cant>0){
    for ($i=1;$i<=$cant;$i++){
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $Per_IDTutor = $arrarTutores[$i]['Per_ID'];
        $sql="SELECT * FROM Persona WHERE Per_ID='$Per_IDTutor'";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result);
        $foto = buscarFoto($row['Per_DNI'], $row['Per_Doc_ID'], 60);
        ?>
         <table class="tablilla1" style="" width="80%"><tr>
            <th colspan="3" align="center" height="35px;" style="font-size:18px;">FICHA PERSONAL <?php echo $arrarTutores[$i]['FTi_Tipo'];?></th>
            </tr><tr><td rowspan="4" width="60px"><?php echo $foto ?></td></tr>
            <tr><td bgcolor="#E4E4E4"><strong>Apellido Y Nombre:</strong></td><td><?php echo $row['Per_Apellido']." ".$row['Per_Nombre'] ?></td>
            </tr><tr><td bgcolor="#E4E4E4"><strong>Nº Documento:</strong></td><td><?php echo $row['Per_DNI'] ?></td></tr>
             <tr><td bgcolor="#E4E4E4"><strong>Sexo:</strong></td><td><?php if($row['Per_Sexo']=='M') echo "Masculino"; if($row[Per_Sexo]=='F') echo "Femenino";?></td></tr>
         </table>
        <p></p>
        <?php 
        $sql="SELECT * FROM PersonaDatos INNER JOIN Pais 
        ON (Dat_Dom_Pai_ID = Pai_ID)
    INNER JOIN Provincia 
        ON (Dat_Dom_Pro_ID = Pro_ID)
    INNER JOIN Localidad 
        ON (Dat_Dom_Loc_ID = Loc_ID) WHERE Dat_Per_ID=$Per_IDTutor;";
        //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        if(($row['Dat_Nac_Pai_ID']!='')&&($row['Dat_Nac_Pro_ID']!='')&&($row['Dat_Nac_Loc_ID']!=''))
        {
            $sql3="SELECT * FROM Pais WHERE Pai_ID=$row[Dat_Nac_Pai_ID];";
            $result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
            $row3 = mysqli_fetch_array($result3);
            $Pai_Nombre=$row3['Pai_Nombre'];
            
            $sql3="SELECT * FROM Provincia WHERE Pro_ID=$row[Dat_Nac_Pro_ID]";
            $result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
            $row3 = mysqli_fetch_array($result3);
            $Pro_Nombre=$row3[Pro_Nombre];
            
            $sql3="SELECT * FROM Localidad  WHERE Loc_ID=$row[Dat_Nac_Loc_ID] ";
            $result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
            $row3 = mysqli_fetch_array($result3);
            $Loc_Nombre=$row3['Loc_Nombre'];
            
        }
        ?><table class="tablilla1" style="" width="80%"><tr>
    <th colspan="3" align="center" height="35px;" style="font-size:18px;">DATOS ADICIONALES <?php echo $arrarTutores[$i]['FTi_Tipo'];?></th>
    </tr><tr><td bgcolor="#E4E4E4" rowspan="5"><strong>Domicilio:</strong></td>
    </tr>    <tr><td><strong>País:</strong></td><td><?php echo $row['Pai_Nombre'] ?></td></tr>    <tr><td><strong>Provincia:</strong></td><td><?php echo $row['Pro_Nombre'] ?></td></tr>    <tr><td><strong>Localidad:</strong></td><td><?php echo $row['Loc_Nombre'] ?></td></tr>     <tr><td><strong>Dirección:</strong></td><td><?php echo $row['Dat_Domicilio'] ?></td></tr>
<tr>    <td bgcolor="#E4E4E4" rowspan="5"><strong>Nacimiento:</strong></td>
    </tr>
    <tr><td><strong>País:</strong></td><td><?php echo $Pai_Nombre ?></td></tr>
    <tr><td><strong>Provincia:</strong></td><td><?php echo $Pro_Nombre ?></td></tr>
    <tr><td><strong>Localidad:</strong></td><td><?php echo $Loc_Nombre ?></td></tr>
     <tr><td><strong>Fecha Nac:</strong></td><td><?php echo cfecha($row['Dat_Nacimiento']) ?></td></tr>
     <tr>
    <td bgcolor="#E4E4E4"><strong>Código Postal:</strong></td><td colspan="2"><?php echo $row['Dat_CP'] ?></td>
    </tr>     <tr>
    <td bgcolor="#E4E4E4"><strong>Email:</strong></td><td colspan="2"><?php echo $row['Dat_Email'] ?></td>
    </tr>         <tr>
    <td bgcolor="#E4E4E4"><strong>Teléfono:</strong></td><td colspan="2"><?php echo $row['Dat_Telefono'] ?></td>
    </tr>         <tr>
    <td bgcolor="#E4E4E4"><strong>Celular:</strong></td><td colspan="2"><?php echo $row['Dat_Celular'] ?></td>
    </tr>              <tr>
    <td bgcolor="#E4E4E4"><strong>Ocupación:</strong></td><td colspan="2"><?php echo $row['Dat_Ocupacion'] ?></td>
    </tr>              <tr>
    <td bgcolor="#E4E4E4"><strong>Observación Gral.:</strong></td><td colspan="2"><?php echo $row['Dat_Observaciones'] ?></td>
    </tr>    <tr>
    <td bgcolor="#E4E4E4"><strong>Observación Económica:</strong></td><td colspan="2"><?php echo $row['Dat_ObservEconomicas'] ?></td>
    </tr>
</table>

<p></p>      
        <?php
    }else  {
        echo "NO SE ENCONTRARON DATOS ADICIONALES";
        
    }//fin if Datos adicionales

    }//fin for
}//fin cant


}//fin function modificado por fabricio 18/07/2018
function editarDatosPersonales()
{	
$PerID = $_POST['PerID'];
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);

?>
<input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
<script language="javascript">
$(document).ready(function(){
$(".botones").button();

$("#guardarDatosPersona").click(function(){
	//alert("asdasd");
	var PerID=$("#PerID").val();
	Per_Apellido=$("#Per_Apellido").val();
	//alert(Per_Apellido)
	Per_Nombre=$("#Per_Nombre").val();
	Per_DNI=$("#Per_DNI").val();
	Per_Sexo=$("input[name='Per_Sexo']:checked").val();
	//alert(Per_Sexo);
	if(Per_Apellido=='')
	{
		jAlert("Ingrese Apellido","Datos Personales");
		return false;
	}
	if(Per_Nombre=='')
	{
		jAlert("Ingrese Nombre","Datos Personales");
		return false;
	}
	if(Per_DNI=='')
	{
		jAlert("Ingrese DNI","Datos Personales");
		return false;
	}
	//return false;
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'guardarDatosPersonales', PerID: PerID,Per_Apellido:Per_Apellido,Per_Nombre:Per_Nombre,Per_DNI:Per_DNI,Per_Sexo:Per_Sexo},
					url: 'cargarOpciones.php',
					success: function(data){ 
					$("#asdasd").html(data);
					jAlert("Guardado Correctamente","Datos Personales");
					
					$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'mostrarDatosPersonales1', PerID: PerID},
					url: 'cargarOpciones.php',
					success: function(data){ 
					$("#editarDatosPersonales").html(data);
					
					}
		});//fin ajax///
					}
		});//fin ajax///
		
		//Script para subir la foto
    var button = $('#upload_button'), interval;
    var upload = new AjaxUpload('#upload_button', {
        action: 'subirArchivo.php',	
        onSubmit : function(file , ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
            // extensiones permitidas
            alert('Error: Solo se permiten imagenes');
            // cancela upload
            return false;
        } else {
            var valor = $("#form_nueva2 #DNI").get(0).value;
			if (valor.length==0){
				alert('Error: Escriba un n�mero de documento antes de subir un archivo.');
				return false;
			}else{
				//Cambio el texto del boton y lo deshabilito
				var vDNI = $('#form_nueva2 #DNI').get(0).value;
				var vDocID = $('#DocID').get(0).value;
				upload.setData({'DNI': vDNI, 'DocID': vDocID});
				button.text('Subiendo...');
				this.disable();
			}
        }
        },
        onComplete: function(file, response){
            button.text('Archivo subido');
			$('#lista').html(response);
        }  
    });
//fin de subir foto
	
	})

})
</script>
<style type="text/css">

.tablilla1
{	
	border: solid 1px;
}

.tablilla1 tr td
{
	border-collapse:collapse;
	font-size:15px;
	border: solid 1px;
}
input
{
	height:20px;
	font-size:15px;
}
</style>


<?php
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql="SELECT * FROM persona WHERE Per_ID='$PerID'";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$foto = buscarFoto($row[Per_DNI], $row[Per_Doc_ID], 60);
?>
 <table class="tablilla1" style="" width="80%">
  
    <tr>
    <th colspan="3" align="center" height="35px;" style="font-size:18px;">DATOS PERSONALES</th>
    </tr>
      <tr><td rowspan="4"><?php echo $foto ?>
</td></tr>
    <tr class="texto">
    <td bgcolor="#E4E4E4"><strong>Apellido Y Nombre:</strong></td><td><input type="text" name="Per_Apellido" id="Per_Apellido" value="<?php echo $row[Per_Apellido]?>" /> <input type="text" name="Per_Nombre" id="Per_Nombre" value="<?php echo $row[Per_Nombre] ?>" /></td>
    </tr>
     <tr>
    <td bgcolor="#E4E4E4"><strong>Nº Documento:</strong></td><td><input type="text" name="Per_DNI" id="Per_DNI" size="8" value="<?php echo $row[Per_DNI]?>" /></td>
    </tr>
     <tr style="vertical-align:middle" valign="middle">
    <td bgcolor="#E4E4E4"><strong>Sexo:</strong></td><td >
    <?php
	//echo $row[Per_Sexo];
	if($row[Per_Sexo]=='')
	{
		?>
        <input type="radio" name="Per_Sexo" id="Per_Sexo" value="F" /><label for="Per_Sexo" style="cursor:pointer">Femenino</label> <input type="radio" name="Per_Sexo" id="Per_Sexo1" value="M" /><label for="Per_Sexo1" style="cursor:pointer">Masculino</label>
        <?php
	}
		if($row[Per_Sexo]=='F')
	{
		?>
        <input type="radio" name="Per_Sexo" id="Per_Sexo" value="F" checked="checked" /><label for="Per_Sexo" style="cursor:pointer">Femenino</label> <input type="radio" name="Per_Sexo" id="Per_Sexo1" value="M" /><label for="Per_Sexo1" style="cursor:pointer">Masculino</label>
        <?php
	}
		if($row[Per_Sexo]=='M')
	{
		?>
        <input type="radio" name="Per_Sexo" id="Per_Sexo" value="F" /><label for="Per_Sexo" style="cursor:pointer">Femenino</label> <input type="radio" name="Per_Sexo" id="Per_Sexo1" value="M" checked="checked" /><label for="Per_Sexo1" style="cursor:pointer">Masculino</label>
        <?php
	}
	?>
    </td>
    <!--</tr>
    <tr class="texto">
    <td bgcolor="#E4E4E4"><strong>Foto:</strong></td><td><div align="center" id="upload_button" class="barra_boton" >
            <img src="imagenes/camera_add.png" alt="Subir foto" /><br />
              Subir foto</div><div id="lista"></div></td>
    </tr>-->
</table>
<br />
<table width="80%">
<tr align="right"><td><button class="botones" id="guardarDatosPersona">Guardar</button></td></tr>
</table>
<div id="asdasd"></div>
    <?php
	
}
function guardarDatosPersonales()
{
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$PerID = $_POST['PerID'];
//echo "PerID".$PerID."<br />";
$Per_Apellido = $_POST['Per_Apellido'];
$Per_Apellido = str_replace("'","´",$Per_Apellido);

//echo "Per_Apellido".$Per_Apellido."<br />";
$Per_Nombre = $_POST['Per_Nombre'];
$Per_Nombre = str_replace("'","´",$Per_Nombre);

//echo "Per_Nombre".$Per_Nombre."<br />";
$Per_DNI = $_POST['Per_DNI'];
//echo "Per_DNI".$Per_DNI."<br />";
$Per_Sexo = $_POST['Per_Sexo'];
//echo "Per_Sexo".$Per_Sexo."<br />";

	$sql = "UPDATE persona 
	SET
	Per_DNI = '$Per_DNI' , 
	Per_Apellido = '$Per_Apellido' , 
	Per_Nombre = '$Per_Nombre' , 
	Per_Sexo = '$Per_Sexo'
	
	WHERE
	Per_ID = '$PerID' ;";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
}
function editarDatosAdiccionales()
{
	
$PerID = $_POST['PerID'];
//echo "PerID ".$PerID."<br />";
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);

//return false;

?>


<script language="javascript">
$(document).ready(function(){
		

   $("#NacPaisID").change(function () {
   		$("#NacPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Nac", vPais);
        });
   })
   	// Parametros para el combo2
	$("#NacProID").change(function () {
   		$("#NacProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#NacPaisID").val();
			llenarLocalidad("Nac", vProv, vPais);
        });
   })
   $("#DomPaisID").change(function () {
   		$("#DomPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Dom", vPais);
        });
   })
   	// Parametros para el combo2
	$("#DomProID").change(function () {
   		$("#DomProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#DomPaisID").val();
			llenarLocalidad("Dom", vProv, vPais);
        });
   })

$(".botones").button();

$("#Dat_Nacimiento").datepicker({
	changeYear: true,
	yearRange: '1800:2050'
	});

$("#GuardarDatosAdicionales").click(function(){
	//alert("asdasd");
	var PerID=$("#PerID").val();
	//alert("PerID"+PerID);
	var DomPaisID=$("#DomPaisID").val();
	//alert("DomPaisID"+DomPaisID);
	var DomProID=$("#DomProID").val();
	//alert("DomProID"+DomProID);
	var DomLocID=$("#DomLocID").val();
	//alert("DomLocID"+DomLocID);
	var Dat_Domicilio=$("#Dat_Domicilio").val();
	//alert("Dat_Domicilio"+Dat_Domicilio);
	var NacPaisID=$("#NacPaisID").val();
	//alert("NacPaisID"+NacPaisID);
	var NacProID=$("#NacProID").val();
	//alert("NacProID"+NacProID);
	var NacLocID=$("#NacLocID").val();
	//alert("NacLocID"+NacLocID);
	var Dat_Nacimiento=$("#Dat_Nacimiento").val();
	//alert("Dat_Nacimiento"+Dat_Nacimiento);
	var Dat_CP=$("#Dat_CP").val();
	//alert("Dat_CP"+Dat_CP);
	var Dat_Email=$("#Dat_Email").val();
	//alert("Dat_Email"+Dat_Email);
	var Dat_Telefono=$("#Dat_Telefono").val();
	//alert("Dat_Telefono"+Dat_Telefono);
	var Dat_Celular=$("#Dat_Celular").val();
	//alert("Dat_Celular"+Dat_Celular);
	var Dat_Ocupacion=$("#Dat_Ocupacion").val();
	//alert("Dat_Ocupacion"+Dat_Ocupacion);
	var Dat_Observaciones=$("#Dat_Observaciones").val();
	//alert("Dat_Observaciones"+Dat_Observaciones);
	
	//return false;
	if(Dat_Domicilio=='')
	{
		jAlert("Ingrese Direccion","Datos Personales");
		return false;
	}
	if((Dat_Telefono=='')&&(Dat_Celular==''))
	{
		jAlert("Ingrese Telofono o Celular","Datos Personales");
		return false;
	}
	//return false;
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'GuardarDatosAdicionales', PerID: PerID,			DomPaisID:DomPaisID,
					DomProID:DomProID,
					DomLocID:DomLocID,
					Dat_Domicilio:Dat_Domicilio,
					NacProID:NacProID,
					NacPaisID:NacPaisID,
					NacLocID:NacLocID,
					Dat_Nacimiento:Dat_Nacimiento,
					Dat_CP:Dat_CP,
					Dat_Email:Dat_Email,
					Dat_Telefono:Dat_Telefono,
					Dat_Celular:Dat_Celular,
					Dat_Ocupacion:Dat_Ocupacion,
					Dat_Observaciones:Dat_Observaciones,
					},
					url: 'cargarOpciones.php',
					success: function(data){ 
					$("#asdasd").html(data);
				jAlert("Guardado Correctamente","Datos Personales");
		
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'mostrarDatosAdiccionales', PerID: PerID},
					url: 'cargarOpciones.php',
					success: function(data){ 
					$("#editarDatosPersonalesAdicionales").html(data);
					
					}
		});//fin ajax///
					}
		});//fin ajax///
	
	})

function mostrarAlerta3(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {
					'Aceptar': function() {
						
						var PerID=$("#PerID").val();
						$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'editarDatosAdiccionales', PerID: PerID},
					url: 'cargarOpciones.php',
					success: function(data){ 
					$("#editarDatosPersonalesAdicionales").html(data);
					
					}
		});//fin ajax///
						
						
					$(this).dialog('close');
				}
				
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion

$("#CargarNuevoPais, #CargarNuevoPais2").click(function(){
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	url: 'cargarNuevoPais.php',
	success: function(data){ 
	mostrarAlerta3(data,"Cargar Nuevo Pais",700,500);
	//return false;
	}
	});//fin ajax///
	
	})//fin function click cargar pais
	
$("#CargarNuevoProvincia, #CargarNuevoProvincia2").click(function(){
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	url: 'cargarNuevaProvincia.php',
	success: function(data){ 
	mostrarAlerta3(data,"Cargar Nueva Provincia",700,500);
	//return false;
	}
	});//fin ajax///
	
	})//fin function click cargar Procincia
	
	$("#CargarNuevaLocalidad, #CargarNuevaLocalidad2").click(function(){
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	url: 'cargarNuevaLocalidad.php',
	success: function(data){ 
	mostrarAlerta3(data,"Cargar Nueva Localidad",700,500);
	//return false;
	}
	});//fin ajax///
	
	})//fin function click cargar Procincia
	
	

})//document
	function llenarLocalidad(vObj, vProv, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },		function(data){
     			$("#" + vObj + "LocID").html(data);
				if (vOpcion) $("#" + vObj + "LocID").attr("value",vOpcion);
   		});
	}
	function llenarProvincia(vObj, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarProvincia', Pais: vPais },function(data){
     			$("#" + vObj + "ProID").html(data);
					vProv = $("#" + vObj + "ProID").val();
					if (vOpcion)
						$("#" + vObj + "ProID").attr("value", vOpcion);
					else
						llenarLocalidad(vObj, vProv, vPais);

   			});
	}


</script>

<style type="text/css">

.tablilla1
{	
	border: solid 1px;
}

.tablilla1 tr td
{
	border-collapse:collapse;
	font-size:15px;
	border: solid 1px;
}
input
{
	height:20px;
	font-size:15px;
}
</style>
<?php	


$sql="SELECT *
FROM
    personadatos
    INNER JOIN pais 
        ON (Dat_Dom_Pai_ID = Pai_ID)
    INNER JOIN provincia 
        ON (Dat_Dom_Pro_ID = Pro_ID)
    INNER JOIN localidad 
        ON (Dat_Dom_Loc_ID = Loc_ID) WHERE Dat_Per_ID=$PerID;";
		//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if(mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_array($result);
		?>
<script language="javascript">

$("#Dat_Domicilio").val('<?php echo $row[Dat_Domicilio]  ?>');

$("#Dat_Nacimiento").val('<?php echo cfecha($row[Dat_Nacimiento])  ?>');

$("#Dat_Email").val('<?php echo $row[Dat_Email]  ?>');

$("#Dat_Telefono").val('<?php echo $row[Dat_Telefono]  ?>');

$("#Dat_Celular").val('<?php echo $row[Dat_Celular]  ?>');

$("#Dat_Ocupacion").val('<?php echo $row[Dat_Ocupacion]  ?>');

$("#Dat_Observaciones").val('<?php echo $row[Dat_Observaciones]  ?>');

$("#Dat_CP").val('<?php echo $row[Dat_CP]  ?>');

$("#NacPaisID").val(<?php echo $row[Dat_Nac_Pai_ID] ?>);
llenarProvincia("Nac", <?php echo $row[Dat_Nac_Pai_ID] ?>, <?php echo $row[Dat_Nac_Pro_ID] ?>);
llenarLocalidad("Nac", <?php echo $row[Dat_Nac_Pro_ID] ?>, <?php echo $row[Dat_Nac_Pai_ID] ?>, <?php echo $row[Dat_Nac_Loc_ID] ?>);
$("#DomPaisID").val(<?php echo $row[Dat_Dom_Pai_ID] ?>);
llenarProvincia("Dom", <?php echo $row[Dat_Dom_Pai_ID] ?>, <?php echo $row[Dat_Dom_Pro_ID] ?>);								
llenarLocalidad("Dom", <?php echo $row[Dat_Dom_Pro_ID] ?>, <?php echo $row[Dat_Dom_Pai_ID] ?>, <?php echo $row[Dat_Dom_Loc_ID] ?>);
</script>
<?php		
	}		
		?>
        
         
         <table class="tablilla1" style="" width="80%">
    
    <tr>
    <th colspan="3" align="center" height="35px;" style="font-size:18px;">DATOS ADICIONALES</th>
    </tr>
    <tr>
    <td bgcolor="#E4E4E4" rowspan="5"><strong>Domicilio:</strong></td>
    </tr>
    <tr valign="middle"><td><strong>Pais:</strong></td><td><?php cargarListaPais('DomPaisID') ?><a style="cursor:pointer" id="CargarNuevoPais" title="Cargar Nuevo Pais"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td><strong>Provincia:</strong></td><td><?php cargarListaProvincia('DomProID',0) ?><a style="cursor:pointer" id="CargarNuevoProvincia" title="Cargar Nueva Provincia"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td><strong>Localidad:</strong></td><td><?php cargarListaLocalidad('DomLocID',0,0) ?><a style="cursor:pointer" id="CargarNuevaLocalidad" title="Cargar Nueva Localidad"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
     <tr><td><strong>Direccion:</strong></td><td><input type="text" name="Dat_Domicilio" id="Dat_Domicilio" size="40" />*</td></tr>

<tr>
    <td bgcolor="#E4E4E4" rowspan="5"><strong>Nacimiento:</strong></td>
    </tr>
    <tr><td><strong>Pais:</strong></td><td><?php cargarListaPais('NacPaisID') ?><a style="cursor:pointer" id="CargarNuevoPais2" title="Cargar Nuevo Pais"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td><strong>Provincia:</strong></td><td><?php cargarListaProvincia('NacProID',0) ?><a style="cursor:pointer" id="CargarNuevoProvincia2" title="Cargar Nueva Provincia"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td><strong>Localidad:</strong></td><td><?php cargarListaLocalidad('NacLocID',0,0) ?><a style="cursor:pointer" id="CargarNuevaLocalidad2" title="Cargar Nueva Localidad"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
     <tr><td><strong>Fecha:</strong></td><td><input type="text" name="Dat_Nacimiento" id="Dat_Nacimiento" /></td></tr>

     <tr>
    <td bgcolor="#E4E4E4"><strong>Codigo Postal:</strong></td><td colspan="2"><input type="text" size="5" name="Dat_CP" id="Dat_CP" /></td>
    </tr>
     <tr>
    <td bgcolor="#E4E4E4"><strong>Mail:</strong></td><td colspan="2"><input type="text" name="Dat_Email" size="40" id="Dat_Email" /></td>
    </tr>
         <tr>
    <td bgcolor="#E4E4E4"><strong>Telefono:</strong></td><td colspan="2"><input type="text" name="Dat_Telefono" id="Dat_Telefono" />*</td>
    </tr>
         <tr>
    <td bgcolor="#E4E4E4"><strong>Celular:</strong></td><td colspan="2"><input type="text" name="Dat_Celular" id="Dat_Celular" size="40" />*</td>
    </tr>
             <tr>
    <td bgcolor="#E4E4E4"><strong>Ocupacion:</strong></td><td colspan="2"><input type="text" size="40" name="Dat_Ocupacion" id="Dat_Ocupacion" /></td>
    </tr>
             <tr>
    <td bgcolor="#E4E4E4"><strong>Observacion:</strong></td><td colspan="2"><input type="text" size="40" name="Dat_Observaciones" id="Dat_Observaciones" /></td>
    </tr>
</table>

<br />
<table width="80%">
<tr align="right"><td><button class="botones" id="GuardarDatosAdicionales">Guardar</button></td></tr>
</table>
<div id="asdasd"></div>
<?php

}
//FIN NAHUEL 27-02-2013

function GuardarDatosAdicionales()
{
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$PerID = $_POST['PerID'];
//echo "PerID".$PerID."<br />";
$DomPaisID = $_POST['DomPaisID'];
//echo "DomPaisID".$DomPaisID."<br />";
$DomProID = $_POST['DomProID'];
//echo "DomProID".$DomProID."<br />";
$DomLocID = $_POST['DomLocID'];
//echo "DomLocID".$DomLocID."<br />";
$Dat_Domicilio = $_POST['Dat_Domicilio'];
//echo "Dat_Domicilio".$Dat_Domicilio."<br />";
$NacPaisID = $_POST['NacPaisID'];
//echo "NacPaisID".$NacPaisID."<br />";
	
$NacProID = $_POST['NacProID'];
//echo "NacProID".$NacProID."<br />";
$NacLocID = $_POST['NacLocID'];
//echo "NacLocID".$NacLocID."<br />";

$Dat_Nacimiento = cambiaf_a_mysql($_POST['Dat_Nacimiento']);
//echo "Dat_Nacimiento".$Dat_Nacimiento."<br />";
$Dat_CP = $_POST['Dat_CP'];
//echo "Dat_CP".$Dat_CP."<br />";
$Dat_Email = $_POST['Dat_Email'];
//echo "Dat_Email".$Dat_Email."<br />";

$Dat_Telefono = $_POST['Dat_Telefono'];
//echo "Dat_Telefono".$Dat_Telefono."<br />";
$Dat_Celular = $_POST['Dat_Celular'];
//echo "Dat_Celular".$Dat_Celular."<br />";
$Dat_Ocupacion = $_POST['Dat_Ocupacion'];
//echo "Dat_Ocupacion".$Dat_Ocupacion."<br />";

$Dat_Observaciones = $_POST['Dat_Observaciones'];
//echo "Dat_Observaciones".$Dat_Observaciones."<br />";

$sql="SELECT *
FROM
    personadatos
    INNER JOIN pais 
        ON (Dat_Dom_Pai_ID = Pai_ID)
    INNER JOIN provincia 
        ON (Dat_Dom_Pro_ID = Pro_ID)
    INNER JOIN localidad 
        ON (Dat_Dom_Loc_ID = Loc_ID) WHERE Dat_Per_ID=$PerID;";
		//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if(mysqli_num_rows($result)>0)
	{

$sql = "UPDATE personadatos 
	SET
	Dat_Dom_Pro_ID = '$DomProID' , 
	Dat_Dom_Pai_ID = '$DomPaisID' , 
	Dat_Dom_Loc_ID = '$DomLocID' , 
	Dat_Nac_Pro_ID = '$NacProID' , 
	Dat_Nac_Pai_ID = '$NacPaisID' , 
	Dat_Nac_Loc_ID = '$NacLocID' , 
	Dat_Nacimiento = '$Dat_Nacimiento' , 
	Dat_Domicilio = '$Dat_Domicilio' , 
	Dat_CP = '$Dat_CP' , 
	Dat_Email = '$Dat_Email' , 
	Dat_Telefono = '$Dat_Telefono' , 
	Dat_Celular = '$Dat_Celular' , 
	Dat_Ocupacion = '$Dat_Ocupacion' , 
	Dat_Observaciones = '$Dat_Observaciones'
	
	WHERE
	Dat_Per_ID = '$PerID';";
//echo $sql;
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
	else
	{
		
		$sql = "INSERT INTO personadatos 
	(Dat_Per_ID, 
	Dat_Dom_Pro_ID, 
	Dat_Dom_Pai_ID, 
	Dat_Dom_Loc_ID, 
	Dat_Nac_Pro_ID, 
	Dat_Nac_Pai_ID, 
	Dat_Nac_Loc_ID, 
	Dat_Nacimiento, 
	Dat_Domicilio, 
	Dat_CP, 
	Dat_Email, 
	Dat_Telefono, 
	Dat_Celular, 
	Dat_Ocupacion, 
	Dat_Observaciones, 
	Dat_Fecha, 
	Dat_Hora
	)
	VALUES
	('$PerID', 
	'$DomProID', 
	'$DomPaisID', 
	'$DomLocID', 
	'$NacProID', 
	'$NacPaisID', 
	'$NacLocID', 
	'$Dat_Nacimiento', 
	'$Dat_Domicilio', 
	'$Dat_CP', 
	'$Dat_Email', 
	'$Dat_Telefono', 
	'$Dat_Celular', 
	'$Dat_Ocupacion', 
	'$Dat_Observaciones', 
	CURDATE(), 
	CURTIME()
	);";
//echo $sql;
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
	}
}

function guardarEntrevista()
{
	
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$PerID = $_POST['PerID'];
//echo "PerID".$PerID."<br />";
$Ent_Turno = $_POST['Ent_Turno'];
//echo "Ent_Turno".$Ent_Turno."<br />";	
$Ent_Fecha = cambiaf_a_mysql($_POST['Ent_Fecha']);
//echo "Ent_Fecha".$Ent_Fecha."<br />";	

$Ent_Hora = $_POST['Ent_Hora'];
//echo "Ent_Hora".$Ent_Hora."<br />";	
$Ent_Sic_ID = $_POST['Ent_Sic_ID'];
//echo "Ent_Sic_ID".$Ent_Sic_ID."<br />";	

$Ent_Asistio = $_POST['Ent_Asistio'];
//echo "Ent_Asistio".$Ent_Asistio."<br />";	
$Ent_Estado = $_POST['Ent_Estado'];
//echo "Ent_Estado".$Ent_Estado."<br />";	

$sql="SELECT *
FROM
    entrevista
    INNER JOIN persona 
        ON (Ent_per_ID = Per_ID)
    INNER JOIN sicopedagoga 
        ON (Ent_Sic_ID = Sic_ID) WHERE Ent_per_ID='$PerID';";
		//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if(mysqli_num_rows($result)>0)
	{
		$sql = "UPDATE entrevista 
	SET
	Ent_Sic_ID = '$Ent_Sic_ID' , 
	Ent_Turno = '$Ent_Turno' , 
	Ent_Fecha = '$Ent_Fecha' , 
	Ent_Hora = '$Ent_Hora' , 
	Ent_Asistio = '$Ent_Asistio' , 
	Ent_Estado = '$Ent_Estado'
	
	WHERE
	Ent_per_ID = '$PerID' ;
";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
	else
	{
	$sql = "
INSERT INTO entrevista 
	(Ent_per_ID, 
	Ent_Sic_ID, 
	Ent_Turno, 
	Ent_Fecha, 
	Ent_Hora, 
	Ent_Asistio, 
	Ent_Estado
	)
	VALUES
	('$PerID', 
	'$Ent_Sic_ID', 
	'$Ent_Turno', 
	'$Ent_Fecha', 
	'$Ent_Hora', 
	'$Ent_Asistio', 
	'$Ent_Estado'
	);
";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	}
}
//NAHUEL 02-05-2013
function anularCuotaPersona()
{
	
$datosCuota = $_POST['Cuota'];
$Motivo = $_POST['Motivo'];
//$Ben_ID = $_POST['Ben_ID'];
list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);

//echo "Ben_ID ".$Ben_ID."<br />";
//echo "Cuo_Lec_ID ".$Cuo_Lec_ID."<br />";
	$sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$sql = " UPDATE CuotaPersona SET
	Cuo_Anulado = '1', Cuo_Fecha = '$Fecha', Cuo_Hora = '$Hora', Cuo_Usu_ID = '$UsuID', Cuo_Motivo = '$Motivo'
		WHERE
	Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero' ;
";
	//echo $sql;
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

}
//FIN NAHUEL 02-05-2013

//NAHUEL 04-05-2013
function EditarCuotas()
{
$datos = $_POST['datos2'];
//echo "ASda".$datos;
?>
<script language="javascript">
$(document).ready(function(){
	
$(".botones").button();
$("#FechaVencimiento").datepicker({
	changeYear: true,
	yearRange: '2013:2050'
	});
/*$("#GuardarEditarCambio").click(function(evento){
	evento.preventDefault();
	
	datos=$("#datos").val();
	//alert(datos)
	Cuo_Importe=$("#Cuo_Importe").val();
	FechaVencimiento=$("#FechaVencimiento").val();
	Motivo=$("#Motivo").val();
	$.ajax({
		type: "POST",
		cache: false,
		async: false,
		data: {opcion: 'GuardarEditarCambio', datos: datos, Cuo_Importe: Cuo_Importe, FechaVencimiento: FechaVencimiento, Motivo: Motivo},
		url: 'cargarOpciones.php',
		success: function(data){ 
			jAlert(data, "EDITAR CUOTAS");
				 $("#barraCuotas").click();
				}
    });//fin ajax////
});*/
		
 });
</script>
<table width="300" align="center">
<tr>
<td>
Nuevo Importe:
</td>
<td>
<input type="hidden" name="datos" id="datos" value="<?php echo $datos ?>" />
<input type="text" name="Cuo_Importe" id="Cuo_Importe"  size="7"/>
</td>
</tr>
<tr>
  <td>Nueva Fecha Vencimiento</td>
  <td><input type="text" name="FechaVencimiento" id="FechaVencimiento" /></td>
</tr>
<tr>
  <td>Motivo del cambio</td>
  <td><textarea name="MotivoEditar" id="MotivoEditar"></textarea></td>
</tr>
</table>
<?php

}
function GuardarEditarCambio()
{
	$datosCuota = $_POST['datos'];
	list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
	$Cuo_Importe = $_POST['Cuo_Importe'];
	$FechaVencimiento = $_POST['FechaVencimiento'];
    $Motivo = $_POST['Motivo'];
	//echo "$datos - $FechaVencimiento - $Cuo_Importe<br />";
	if (!empty($Cuo_Importe)) {
		
		$sql = "UPDATE CuotaPersona SET Cuo_Importe = '$Cuo_Importe'
		WHERE Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero' ;";
		//echo $sql;
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
    //echo $Motivo;
    if (!empty($Motivo)) {
        
        $sql = "UPDATE CuotaPersona SET Cuo_Motivo = CONCAT(Cuo_Motivo,'-$Motivo')
        WHERE Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero';";
        //echo $sql;
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    }
	if (!empty($FechaVencimiento)) {
		
		$sql = "UPDATE CuotaPersona SET
		Cuo_1er_Vencimiento = '".cambiaf_a_mysql($FechaVencimiento)."'
			WHERE
		Cuo_Lec_ID = '$Cuo_Lec_ID' AND Cuo_Per_ID = '$Cuo_Per_ID' AND Cuo_Niv_ID = '$Cuo_Niv_ID' AND Cuo_CTi_ID = '$Cuo_CTi_ID' AND Cuo_Alt_ID = '$Cuo_Alt_ID' AND Cuo_Numero = '$Cuo_Numero' ;";
		//echo $sql;
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}	
	
	echo "Guardado Correctamente";
}
//FIN NAHUEL 04-05-2013


function bloquearPersona(){
	$PerID = $_POST['PerID'];
	$Motivo = $_POST['Motivo'];
	$BTiID = $_POST['BTiID'];
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	$sql = "INSERT INTO Bloqueo (Blo_Per_ID, Blo_BTi_ID, Blo_Motivo, Blo_Fecha, Blo_Hora, Blo_Usu_ID) 
	VALUES($PerID, $BTiID, '$Motivo', '$Fecha', '$Hora', $UsuID)";
	if (consulta_mysql_2022($sql,basename(__FILE__),__LINE__)){
		echo "Se bloqueó correctamente a la persona";
	}
	
}//fin function

function obtenerBloqueo(){
	//return "Hola";
	$PerID = trim($_POST['PerID']);
	if (empty($PerID)) exit;
	$sql = "SELECT * FROM Bloqueo WHERE Blo_Per_ID = $PerID";
	//return $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		echo $row[Blo_Motivo];
	}else{
		echo '';
	}
}//fin function

function levantarBloqueo(){
	//return "Hola";
	$PerID = $_POST['PerID'];
	$sql = "DELETE FROM Bloqueo WHERE Blo_Per_ID = $PerID";
	//return $sql;
	if ($result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__)){
		echo "Se restauró la persona correctamente";
	}else{
		echo "Hubo un error al tratar de restaurar la persona. Vuelva a intentarlo por favor.";
	}
}//fin function

function guardarCursoDivisionLectivo(){
	//return "Hola";
	$LegID = $_POST['LegID'];
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	
	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

	//Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
	$sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {//ya existe, actualizamos la inscripcion
		$sql = "UPDATE Colegio_Inscripcion SET Ins_Cur_ID = $CurID, Ins_Niv_ID = $NivID, Ins_Div_ID = $DivID, Ins_Usu_ID = $UsuID, Ins_Fecha = '$Fecha', Ins_Hora = '$Hora' WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
		$mensaje = "Se actualizó el curso y la división en la inscripción del alumno.";
	} else {
		
		$mensaje = "El alumno no se encuentra inscripto al ciclo lectivo o existe un error en la inscripción.";
	}
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo $mensaje;
}//fin function

//********************** COUNTING ***********************************
function guardarCounting(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//obtenerRegistroUsuario($CSe_Usu_ID, $CSe_Fecha, $CSe_Hora);	
	$Cou_Cur_ID = $_POST['Cou_Cur_ID'];
	$Cou_Lec_ID = $_POST['Cou_Lec_ID'];
	$Cou_Total = $_POST['Cou_Total'];
	
	$Tabla = "Counting";
	
    $sql = "SELECT * FROM $Tabla WHERE Cou_Cur_ID='$Cou_Cur_ID' AND Cou_Lec_ID='$Cou_Lec_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Cou_Total='$Cou_Total' WHERE Cou_Cur_ID='$Cou_Cur_ID' AND Cou_Lec_ID='$Cou_Lec_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  $sql = "INSERT INTO $Tabla (Cou_Cur_ID, Cou_Lec_ID, Cou_Total) VALUES('$Cou_Cur_ID', '$Cou_Lec_ID', '$Cou_Total')";
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarCounting(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Counting";
	
	$texto = $_POST['Texto'];
	$Cou_Lec_ID = $_POST['Cou_Lec_ID'];
	if ($texto!="todos") $where = "";
	$sql = "SELECT * FROM
    $Tabla $where INNER JOIN Lectivo ON (Cou_Lec_ID = Lec_ID) INNER JOIN Curso ON (Cou_Cur_ID = Cur_ID) AND Cou_Lec_ID = $Cou_Lec_ID ORDER BY Cou_Lec_ID, Cur_ID, Cur_Nombre";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Cou_Lec_ID = $("#Cou_Lec_ID" + i).val();
		Cou_Cur_ID = $("#Cou_Cur_ID" + i).val();
		//alert(vCliID);
		buscarDatos(Cou_Lec_ID, Cou_Cur_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 /*$("a[id^='botEntrevista']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(13,10);
		CCT_ID = $("#CCT_ID" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", CCT_ID: CCT_ID }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm///
	
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Cou_Lec_ID, Cou_Cur_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Cou_Lec_ID: Cou_Lec_ID, Cou_Cur_ID: Cou_Cur_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Cou_Cur_ID").val(obj.Cou_Cur_ID);
				$("#Cou_Lec_ID").val(obj.Cou_Lec_ID);
				$("#Cou_Total").val(obj.Cou_Total);
				
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Ciclo Lectivo</th>
                  <th align="center">Curso/Turno</th>
                  <th align="center">Limite de Vacantes</th>
                  <th align="center">Vacantes disponibles</th>
                  <th align="center">Inscriptos</th>
                  
                 
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[Lec_Nombre];?><input type="hidden" id="Cou_Lec_ID<?php echo $i;?>" value="<?php echo $row[Cou_Lec_ID];?>" />
                  <input type="hidden" id="Cou_Cur_ID<?php echo $i;?>" value="<?php echo $row[Cou_Cur_ID];?>" />
                 </td>
                 <td>
                  <span id="Nombre<?php echo $i;?>"><?php echo $row[Cur_Nombre];?></span></td>
                 <td align="center"><?php echo $row[Cou_Total];?></td>
                 <td align="center"><?php echo revisarVacantes($row[Cou_Lec_ID], $row[Cou_Cur_ID], $Inscriptos);?></td>
                 <td align="center"><?php echo $Inscriptos;?></td>
                                    
                  <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <!--<a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a>--></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosCounting() {
	//echo "Hola";exit;
	$Cou_Cur_ID = $_POST['Cou_Cur_ID'];
	$Cou_Lec_ID = $_POST['Cou_Lec_ID'];
	
	$Tabla = "Counting";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE Cou_Cur_ID='$Cou_Cur_ID' AND Cou_Lec_ID='$Cou_Lec_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						
			

		$datos .= "{\"Cou_Cur_ID\": \"" . $row[Cou_Cur_ID] . "\",\"";
		$datos .= "Cou_Lec_ID\": \"" . $row[Cou_Lec_ID] . "\",\"";
		$datos .= "Cou_Total\": \"" . $row[Cou_Total] . "\"}";
		
		
		echo $datos;
	}
 }//fin funcion
 
function eliminarCounting() {
//echo "Hola";exit;
    $CCT_ID = $_POST['CCT_ID'];
	$CCT_Nombre = $_POST['CCT_Nombre'];
	
	$Tabla = "CuentaTipo";

    $sql = "SELECT * FROM $Tabla WHERE CCT_ID='$CCT_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Tipo de Cuenta contable elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Cuenta WHERE Cue_CCT_ID = $CCT_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Cuentas contables relacionadas.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE CCT_ID = $CCT_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Tipo de Cuenta seleccionado.";
        }
    }
}//fin function

//********************** ASPIRANTE ***********************************
function guardarAspirante(){
	
	setlocale(LC_CTYPE,"es_ES");
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora);	
	//Aspirante
	$Asp_Per_ID = $_POST['Asp_Per_ID'];
	$Asp_Lec_ID = $_POST['Asp_Lec_ID'];
	$Asp_Usu_ID = $Usu_ID;
	$Asp_Fecha = $Fecha;
	$Asp_Hora = $Hora;
	//Persona
	$Per_ID = $_POST['Per_ID'];
	$Per_Doc_ID = $_POST['Per_Doc_ID'];
	$Per_DNI = $_POST['Per_DNI'];
	//$Per_Apellido = strtoupper($_POST['Per_Apellido']);
	$Per_Apellido = mb_convert_case($_POST['Per_Apellido'], MB_CASE_UPPER, "UTF-8");
    $Per_Apellido = str_replace("'","´",$Per_Apellido);

	//$Per_Nombre = ucwords(strtolower($_POST['Per_Nombre']));
	$Per_Nombre = mb_convert_case($_POST['Per_Nombre'], MB_CASE_TITLE, "UTF-8");
    $Per_Nombre = str_replace("'","´",$Per_Nombre);

	$Per_Sexo = $_POST['Per_Sexo'];
	$Per_Fecha = $Fecha;
	$Per_Hora = $Hora;
	//PersonaDatos
	$Dat_Per_ID = $_POST['Dat_Per_ID'];
	$Dat_Dom_Pro_ID = $_POST['DomProID'];
	$Dat_Dom_Pai_ID = $_POST['DomPaisID'];
	$Dat_Dom_Loc_ID = $_POST['DomLocID'];
	$Dat_Nac_Pro_ID = $_POST['NacProID'];
	$Dat_Nac_Pai_ID = $_POST['NacPaisID'];
	$Dat_Nac_Loc_ID = $_POST['NacLocID'];
	$Dat_Nacimiento = cambiaf_a_mysql($_POST['Dat_Nacimiento']);
	$Dat_Domicilio = $_POST['Dat_Domicilio'];
	$Dat_CP = $_POST['Dat_CP'];
	$Dat_Email = $_POST['Dat_Email'];
	$Dat_Telefono = $_POST['Dat_Telefono'];
	$Dat_Celular = $_POST['Dat_Celular'];
	$Dat_Observaciones = $_POST['Dat_Observaciones'];
	$Dat_Fecha = $Fecha;
	$Dat_Hora = $Hora;
	
	$Tabla = "Aspirante";
	
    //Busco si existe como persona
	$sql = "SELECT * FROM Persona WHERE Per_ID='$Per_ID'";
	
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
	
	//Actualizo
	
	 $sql = "UPDATE Persona SET Per_Doc_ID='$Per_Doc_ID', Per_DNI='$Per_DNI', Per_Apellido='$Per_Apellido', Per_Nombre='$Per_Nombre', Per_Sexo='$Per_Sexo', Per_Fecha='$Per_Fecha', Per_Hora='$Per_Hora' WHERE Per_ID='$Per_ID'";
	 consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	 $sql = "UPDATE PersonaDatos SET Dat_Per_ID='$Dat_Per_ID', Dat_Dom_Pro_ID='$Dat_Dom_Pro_ID', Dat_Dom_Pai_ID='$Dat_Dom_Pai_ID', Dat_Dom_Loc_ID='$Dat_Dom_Loc_ID', Dat_Nac_Pro_ID='$Dat_Nac_Pro_ID', Dat_Nac_Pai_ID='$Dat_Nac_Pai_ID', Dat_Nac_Loc_ID='$Dat_Nac_Loc_ID', Dat_Nacimiento='$Dat_Nacimiento', Dat_Domicilio='$Dat_Domicilio', Dat_CP='$Dat_CP', Dat_Email='$Dat_Email', Dat_Telefono='$Dat_Telefono', Dat_Celular='$Dat_Celular', Dat_Observaciones='$Dat_Observaciones', Dat_Fecha='$Dat_Fecha', Dat_Hora='$Dat_Hora' WHERE Dat_Per_ID='$Dat_Per_ID'";
	 consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	 
	 $mensaje = "Se actualizó correctamente los datos";
	 
  } else {
	  //Inserto
	  $sql = "INSERT INTO Persona (Per_Doc_ID, Per_DNI, Per_Apellido, Per_Nombre, Per_Sexo, Per_Fecha, Per_Hora, Per_Token) VALUES('$Per_Doc_ID', '$Per_DNI', '$Per_Apellido', '$Per_Nombre', '$Per_Sexo', '$Per_Fecha', '$Per_Hora', UUID())";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $Per_ID = $res['id'];
        }else{
            echo "Mal";
        }

	  $Asp_Per_ID = $Per_ID;
	  $Dat_Per_ID = $Per_ID;
	  $sql = "INSERT INTO Aspirante (Asp_Per_ID, Asp_Lec_ID, Asp_Usu_ID, Asp_Fecha, Asp_Hora)VALUES('$Asp_Per_ID', '$Asp_Lec_ID', '$Asp_Usu_ID', '$Asp_Fecha', '$Asp_Hora')";
	  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  $sql = "INSERT INTO PersonaDatos (Dat_Per_ID, Dat_Dom_Pro_ID, Dat_Dom_Pai_ID, Dat_Dom_Loc_ID, Dat_Nac_Pro_ID, Dat_Nac_Pai_ID, Dat_Nac_Loc_ID, Dat_Nacimiento, Dat_Domicilio, Dat_CP, Dat_Email, Dat_Telefono, Dat_Celular, Dat_Observaciones, Dat_Fecha, Dat_Hora) VALUES('$Dat_Per_ID', '$Dat_Dom_Pro_ID', '$Dat_Dom_Pai_ID', '$Dat_Dom_Loc_ID', '$Dat_Nac_Pro_ID', '$Dat_Nac_Pai_ID', '$Dat_Nac_Loc_ID', '$Dat_Nacimiento', '$Dat_Domicilio', '$Dat_CP', '$Dat_Email', '$Dat_Telefono', '$Dat_Celular', '$Dat_Observaciones', '$Dat_Fecha', '$Dat_Hora')";
	  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  $mensaje = "Se agregó un nuevo registro correctamente";
	  
  }
  //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $Per_ID."/".$mensaje;
        
}//fin function

function buscarAspirante(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Aspirante";
	
	$texto = $_POST['Texto'];
	$Lec_ID = $_POST['Lec_ID'];
	
	
	if ($texto!="todos") {
		$where = "WHERE (Per_DNI = '$texto' OR Per_Apellido LIKE '%$texto%')";
		if ($Lec_ID!=999999) $where .= " AND Asp_Lec_ID = $Lec_ID";
	}else{
		if ($Lec_ID!=999999) $where .= " WHERE Asp_Lec_ID = $Lec_ID";
		}
	$sql = "SELECT * FROM
    $Tabla INNER JOIN Persona 
        ON (Asp_Per_ID = Per_ID)
		INNER JOIN Lectivo
		ON (Asp_Lec_ID = Lec_ID) $where ORDER BY Per_Apellido, Per_Nombre";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Asp_Lec_ID = $("#Asp_Lec_ID" + i).val();
		Asp_Per_ID = $("#Asp_Per_ID" + i).val();
		//alert(vCliID);
		buscarDatos(Asp_Lec_ID, Asp_Per_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 $("a[id^='botLegajo']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Asp_Per_ID = $("#Asp_Per_ID" + i).val();
		//alert(vCliID);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "generarLegajoAspirante", Per_ID: Asp_Per_ID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				jAlert("El Legajo fue generado correctamente con el número <strong>"+data+"</strong>", "Legajo generado");
				//$("#mostrarResultado").html(data);
				$("#Legajo" + i).text(data);
				$("#botLegajo" + i).hide();
				$("#loading").hide();
			}
		});//fin ajax//*/
	 });//fin evento click//*/	
	  $("a[id^='botEntrevista']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(13,10);
		Asp_Per_ID = $("#Asp_Per_ID" + i).val();
		textoBuscar = $("#textoBuscar").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {Per_ID: Asp_Per_ID, textoBuscar: textoBuscar},
			url: 'cargarNuevaEntrevista.php',
			success: function(data){ 
				//alert(data);
				//$("#mostrarResultado").html(data);
				$("#principal").html(data);
				$("#loading").hide();
			}
		});//fin ajax//*/
	
	 });//fin evento click//*/	
	 /*$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		CCT_ID = $("#CCT_ID" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", CCT_ID: CCT_ID }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm///
	
	 });//fin evento click//*/	
});//fin domready
function buscarDatos(Asp_Lec_ID, Asp_Per_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Asp_Lec_ID: Asp_Lec_ID, Asp_Per_ID: Asp_Per_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Asp_Per_ID").val(obj.Asp_Per_ID);
				$("#Asp_Lec_ID").val(obj.Asp_Lec_ID);
				//$("#Asp_Usu_ID").val(obj.Asp_Usu_ID);
				//$("#Asp_Fecha").val(obj.Asp_Fecha);
				//$("#Asp_Hora").val(obj.Asp_Hora);
				//--------------
				$("#Per_ID").val(obj.Per_ID);
				$("#Per_Doc_ID").val(obj.Per_Doc_ID);
				$("#Per_DNI").val(obj.Per_DNI);
				$("#Per_Apellido").val(obj.Per_Apellido);
				$("#Per_Nombre").val(obj.Per_Nombre);
				$("#Per_Sexo").val(obj.Per_Sexo);
				//$("#Per_Fecha").val(obj.Per_Fecha);
				//$("#Per_Hora").val(obj.Per_Hora);
				//-------------
				$("#Dat_Per_ID").val(obj.Dat_Per_ID);
				$("#DomProID").val(obj.Dat_Dom_Pro_ID);
				$("#DomPaisID").val(obj.Dat_Dom_Pai_ID);
				$("#DomLocID").val(obj.Dat_Dom_Loc_ID);
				$("#NacProID").val(obj.Dat_Nac_Pro_ID);
				$("#NacPaisID").val(obj.Dat_Nac_Pai_ID);
				$("#NacLocID").val(obj.Dat_Nac_Loc_ID);
				$("#Dat_Nacimiento").val(obj.Dat_Nacimiento);
				$("#Dat_Domicilio").val(obj.Dat_Domicilio);
				$("#Dat_CP").val(obj.Dat_CP);
				$("#Dat_Email").val(obj.Dat_Email);
				$("#Dat_Telefono").val(obj.Dat_Telefono);
				$("#Dat_Celular").val(obj.Dat_Celular);
				$("#Dat_Observaciones").val(obj.Dat_Observaciones);
				if (obj.Dat_Telefono.length>8)$("#Dat_Observaciones").val($("#Dat_Observaciones").val() + obj.Dat_Telefono);
				if (obj.Dat_Celular.length>10)$("#Dat_Observaciones").val($("#Dat_Observaciones").val() + obj.Dat_Celular);
				//$("#Dat_Fecha").val(obj.Dat_Fecha);
				//$("#Dat_Hora").val(obj.Dat_Hora);
				
				
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Ciclo Lectivo</th>
                  <th align="center">Apellido y Nombre</th>
                  <th align="center">DNI</th>
                  <th align="center">Sexo</th>
                  <th align="center">Legajo</th>
                  <th align="center">Entrevista</th>              
                  <th width="150" align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>" title="Fecha carga aspirante <?php echo cfecha($row[Asp_Fecha])." ".$row[Per_Hora];?>">                 
                  <td align="center"><a name="<?php echo $row[Asp_Per_ID];?>" id="<?php echo $row[Asp_Per_ID];?>"></a><?php echo $row[Lec_Nombre];?>
                    <input type="hidden" id="Asp_Lec_ID<?php echo $i;?>" value="<?php echo $row[Asp_Lec_ID];?>" />
                  <input type="hidden" id="Asp_Per_ID<?php echo $i;?>" value="<?php echo $row[Asp_Per_ID];?>" /></td>
                 <td>
                  <span id="Nombre<?php echo $i;?>"><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></span></td>
                 <td align="center"><?php echo $row[Per_DNI];?></td>
                 <td align="center"><?php echo $row[Per_Sexo];?></td>
                 <td align="center"><span id="Legajo<?php echo $i;?>"><?php $legajo = buscarLegajoAspirante($row[Asp_Per_ID]);echo $legajo;?></span></td>
                 <td align="center"><?php buscarDatosEntrevista($row[Asp_Per_ID]);?></td>
                                    
                  <td align="right">
                  <?php
                  if (!is_numeric($legajo)){
				  ?><a href="#" id="botLegajo<?php echo $i;?>"><img src="iconos/mod_legajo_generar.png" alt="Generar Legajo" title="Generar Legajo" width="32" height="32" border="0" /></a> 
                  <?php
				  }//fin if
				  ?>
                  <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> 
                  <a href="#" id="botEntrevista<?php echo $i;?>"><img src="iconos/mod_pendiente.png" alt="Editar Entrevista" title="Editar Entrevista" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
            Total de aspirantes: <?php echo mysqli_num_rows($result);?>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosAspirante() {
	//echo "Hola";exit;
	$Asp_Per_ID = $_POST['Asp_Per_ID'];
	$Asp_Lec_ID = $_POST['Asp_Lec_ID'];
	
	$Tabla = "Aspirante";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla INNER JOIN Persona 
        ON (Asp_Per_ID = Per_ID)INNER JOIN PersonaDatos 
        ON (Per_ID = Dat_Per_ID) WHERE Asp_Per_ID='$Asp_Per_ID' AND Asp_Lec_ID='$Asp_Lec_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);									

		$datos .= "{\"Asp_Per_ID\": \"" . $row[Asp_Per_ID] . "\",\"";
		$datos .= "Asp_Lec_ID\": \"" . $row[Asp_Lec_ID] . "\",\"";
		$datos .= "Asp_Usu_ID\": \"" . $row[Asp_Usu_ID] . "\",\"";
		$datos .= "Asp_Fecha\": \"" . $row[Asp_Fecha] . "\",\"";
		$datos .= "Asp_Hora\": \"" . $row[Asp_Hora] . "\",\"";
		//-----------------
		$datos .= "Per_ID\": \"" . $row[Per_ID] . "\",\"";
		$datos .= "Per_Doc_ID\": \"" . $row[Per_Doc_ID] . "\",\"";
		$datos .= "Per_DNI\": \"" . $row[Per_DNI] . "\",\"";
		$datos .= "Per_Apellido\": \"" . $row[Per_Apellido] . "\",\"";
		$datos .= "Per_Nombre\": \"" . $row[Per_Nombre] . "\",\"";
		$datos .= "Per_Sexo\": \"" . $row[Per_Sexo] . "\",\"";
		$datos .= "Per_Foto\": \"" . $row[Per_Foto] . "\",\"";
		$datos .= "Per_Fecha\": \"" . $row[Per_Fecha] . "\",\"";
		$datos .= "Per_Hora\": \"" . $row[Per_Hora] . "\",\"";
		$datos .= "Per_Alternativo\": \"" . $row[Per_Alternativo] . "\",\"";
		$datos .= "Per_Extranjero\": \"" . $row[Per_Extranjero] . "\",\"";
		//-----------------
		$datos .= "Dat_Per_ID\": \"" . $row[Dat_Per_ID] . "\",\"";
		$datos .= "Dat_Dom_Pro_ID\": \"" . $row[Dat_Dom_Pro_ID] . "\",\"";
		$datos .= "Dat_Dom_Pai_ID\": \"" . $row[Dat_Dom_Pai_ID] . "\",\"";
		$datos .= "Dat_Dom_Loc_ID\": \"" . $row[Dat_Dom_Loc_ID] . "\",\"";
		$datos .= "Dat_Nac_Pro_ID\": \"" . $row[Dat_Nac_Pro_ID] . "\",\"";
		$datos .= "Dat_Nac_Pai_ID\": \"" . $row[Dat_Nac_Pai_ID] . "\",\"";
		$datos .= "Dat_Nac_Loc_ID\": \"" . $row[Dat_Nac_Loc_ID] . "\",\"";
		$datos .= "Dat_Nacimiento\": \"" . cfecha($row[Dat_Nacimiento]) . "\",\"";
		$datos .= "Dat_Domicilio\": \"" . $row[Dat_Domicilio] . "\",\"";
		$datos .= "Dat_CP\": \"" . $row[Dat_CP] . "\",\"";
		$datos .= "Dat_Email\": \"" . $row[Dat_Email] . "\",\"";
		$datos .= "Dat_Telefono\": \"" . $row[Dat_Telefono] . "\",\"";
		$datos .= "Dat_Celular\": \"" . $row[Dat_Celular] . "\",\"";
		$datos .= "Dat_Ocupacion\": \"" . $row[Dat_Ocupacion] . "\",\"";
		$datos .= "Dat_Observaciones\": \"" . $row[Dat_Observaciones] . "\",\"";
		$datos .= "Dat_Fecha\": \"" . $row[Dat_Fecha] . "\",\"";
		$datos .= "Dat_Hora\": \"" . $row[Dat_Hora] . "\"}";
		
		
		echo $datos;
	}
 }//fin funcion
 
function eliminarAspirante() {
//echo "Hola";exit;
    $CCT_ID = $_POST['CCT_ID'];
	$CCT_Nombre = $_POST['CCT_Nombre'];
	
	$Tabla = "CuentaTipo";

    $sql = "SELECT * FROM $Tabla WHERE CCT_ID='$CCT_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Tipo de Cuenta contable elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Cuenta WHERE Cue_CCT_ID = $CCT_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Cuentas contables relacionadas.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE CCT_ID = $CCT_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Tipo de Cuenta seleccionado.";
        }
    }
}//fin function


function guardarNuevaEntrevista(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//obtenerRegistroUsuario($CSe_Usu_ID, $CSe_Fecha, $CSe_Hora);	
	$Ent_per_ID = $_POST['Ent_per_ID'];
	$Ent_Sic_ID = $_POST['Ent_Sic_ID'];
	$Ent_Turno = $_POST['Ent_Turno'];
	$Ent_Fecha = cambiaf_a_mysql($_POST['Ent_Fecha']);
	$Ent_Hora = $_POST['Ent_Hora'];
	$Ent_Asistio = $_POST['Ent_Asistio'];
	$Ent_Estado = $_POST['Ent_Estado'];
	
	$Tabla = "Entrevista";
	
    $sql = "SELECT * FROM $Tabla WHERE Ent_per_ID='$Ent_per_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Ent_Sic_ID='$Ent_Sic_ID', Ent_Turno='$Ent_Turno', Ent_Fecha='$Ent_Fecha', Ent_Hora='$Ent_Hora', Ent_Asistio='$Ent_Asistio', Ent_Estado='$Ent_Estado' WHERE Ent_per_ID='$Ent_per_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  $sql = "INSERT INTO $Tabla (Ent_Per_ID, Ent_Sic_ID, Ent_Turno, Ent_Fecha, Ent_Hora, Ent_Asistio, Ent_Estado) VALUES('$Ent_per_ID', '$Ent_Sic_ID', '$Ent_Turno', '$Ent_Fecha', '$Ent_Hora', '$Ent_Asistio', '$Ent_Estado')";
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function generarLegajoAspirante(){
	
	$PerID = $_POST[Per_ID];
	obtenerRegistroUsuario($UsuID, $FechaAlta, $Hora);	
	$FechaAlta = cambiaf_a_mysql($_POST['fechaAlta']);
	$Fecha = $FechaAlta;
	$SedID = 1;//por defecto vale 1
	$Legajo = buscarLegajoPersona($PerID);
	if ($Legajo==-1) {
		$sql = "SELECT MAX(CONVERT(Leg_Numero, SIGNED)) AS Maximo FROM Legajo WHERE Leg_Colegio = 1;";
		//echo $sql;
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result);
		$Legajo = $row[Maximo] + 1;		
		$sql = "INSERT INTO Legajo(Leg_Sed_ID, Leg_Per_ID, Leg_Numero, Leg_Alta_Fecha, Leg_Usu_ID, Leg_Fecha, Leg_Hora) VALUES ($SedID, $PerID, '$Legajo', '$FechaAlta', '$UsuID', '$Fecha', '$Hora')";
		//echo $sql;
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
	}else if ($Legajo==0){
		$sql = "SELECT MAX(CONVERT(Leg_Numero, SIGNED)) AS Maximo FROM Legajo WHERE Leg_Colegio = 1;";
		//echo $sql;
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result);
		$Legajo = $row[Maximo] + 1;
		$sql = "UPDATE Legajo SET Leg_Numero = '$Legajo' WHERE Leg_Per_ID = $PerID";
		//echo $sql;
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
	
	echo $Legajo;
}//fin function

//********************** PERSONA NUEVA ***********************************
function guardarPersonaNueva(){
	
	setlocale(LC_CTYPE,"es_ES");
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora);	
	
	//Persona
    //Mario. si es cero no sigue
    $Per_ID = $_POST['Per_ID'];

    if (strlen($Per_ID)>10){ 
        $mensaje="ERROR faltan datos o son incorrectos!";
        echo $Per_ID."/".$mensaje;   
    }else{
    	
    	$Per_Doc_ID = $_POST['Per_Doc_ID'];
    	$Per_DNI = $_POST['Per_DNI'];
    	//$Per_Apellido = strtoupper($_POST['Per_Apellido']);
    	$Per_Apellido = mb_convert_case($_POST['Per_Apellido'], MB_CASE_UPPER, "UTF-8");
        $Per_Apellido = str_replace("'","´",$Per_Apellido);

    	//$Per_Nombre = ucwords(strtolower($_POST['Per_Nombre']));
    	$Per_Nombre = mb_convert_case($_POST['Per_Nombre'], MB_CASE_TITLE, "UTF-8");
        $Per_Nombre = str_replace("'","´",$Per_Nombre);

    	$Per_Sexo = $_POST['Per_Sexo'];
    	$Per_Fecha = $Fecha;
    	$Per_Hora = $Hora;
    	//PersonaDatos
    	$Dat_Per_ID = $_POST['Per_ID'];

        $Dat_Nac_Pai_ID = $_POST['NacPaisID'];
        $Dat_Nac_Pro_ID = $_POST['NacProID'];
        $Dat_Nac_Loc_ID = $_POST['NacLocID'];
        if (empty($Dat_Nac_Pai_ID)||empty($Dat_Nac_Pro_ID)||empty($Dat_Nac_Loc_ID)) {
            $Dat_Nac_Pai_ID=1;
            $Dat_Nac_Pro_ID=19;
            $Dat_Nac_Loc_ID=1735;
        }    
        if ($Dat_Nac_Pai_ID==0 || $Dat_Nac_Pro_ID==0 || $Dat_Nac_Loc_ID==0) {
            $Dat_Nac_Pai_ID=1;
            $Dat_Nac_Pro_ID=19;
            $Dat_Nac_Loc_ID=1735;
        }    
    	
        $Dat_Dom_Pai_ID = $_POST['DomPaisID'];
        $Dat_Dom_Pro_ID = $_POST['DomProID'];
        $Dat_Dom_Loc_ID = $_POST['DomLocID'];
        if (empty($Dat_Dom_Pai_ID)||empty($Dat_Dom_Pro_ID)||empty($Dat_Dom_Loc_ID)){ 
        	$Dat_Dom_Pai_ID=1;
            $Dat_Dom_Pro_ID=19;
            $Dat_Dom_Loc_ID=1735;
        }
        if ($Dat_Dom_Pai_ID==0 || $Dat_Dom_Pro_ID==0 || $Dat_Dom_Loc_ID==0) {
            $Dat_Dom_Pai_ID=1;
            $Dat_Dom_Pro_ID=19;
            $Dat_Dom_Loc_ID=1735;
        }

        $Dat_Nacimiento = cambiaf_a_mysql($_POST['Dat_Nacimiento']);
    	$Dat_Domicilio = $_POST['Dat_Domicilio'];
    	$Dat_CP = $_POST['Dat_CP'];
    	$Dat_Email = $_POST['Dat_Email'];
    	$Dat_Telefono = $_POST['Dat_Telefono'];
    	$Dat_Celular = $_POST['Dat_Celular'];
    	$Dat_Observaciones = $_POST['Dat_Observaciones'];
        $Dat_Ocupacion = $_POST['Dat_Ocupacion'];
        $Dat_Retira = $_POST['Dat_Retira'];
    	$Dat_Fecha = $Fecha;
    	$Dat_Hora = $Hora;
    	
    	$Tabla = "Persona";
    	
        //Busco si existe como persona
    	$sql = "SELECT * FROM Persona WHERE Per_ID='$Per_ID'";
    	
    	//echo '<br>sql: '.$sql;
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
    	
    	//Actualizo
    	
    	 $sql = "UPDATE Persona SET Per_Doc_ID='$Per_Doc_ID', Per_DNI='$Per_DNI', Per_Apellido='$Per_Apellido', Per_Nombre='$Per_Nombre', Per_Sexo='$Per_Sexo', Per_Fecha='$Per_Fecha', Per_Hora='$Per_Hora' WHERE Per_ID='$Per_ID'";
    	 $resultadoSQL=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    	 
         if (!$resultadoSQL) {
            echo '/Error en la consulta! Hay datos relacionados';
            exit();
         }   

    	 $sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID = '$Dat_Per_ID'";
    	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
         if (mysqli_num_rows($result) > 0) {
    	 
    		 $sql = "UPDATE PersonaDatos SET Dat_Dom_Pro_ID='$Dat_Dom_Pro_ID', Dat_Dom_Pai_ID='$Dat_Dom_Pai_ID', Dat_Dom_Loc_ID='$Dat_Dom_Loc_ID', Dat_Nac_Pro_ID='$Dat_Nac_Pro_ID', Dat_Nac_Pai_ID='$Dat_Nac_Pai_ID', Dat_Nac_Loc_ID='$Dat_Nac_Loc_ID', Dat_Nacimiento='$Dat_Nacimiento', Dat_Domicilio='$Dat_Domicilio', Dat_CP='$Dat_CP', Dat_Email='$Dat_Email', Dat_Telefono='$Dat_Telefono', Dat_Celular='$Dat_Celular', Dat_Ocupacion = '$Dat_Ocupacion', Dat_Retira='$Dat_Retira', Dat_Observaciones='$Dat_Observaciones', Dat_Fecha='$Dat_Fecha', Dat_Hora='$Dat_Hora' WHERE Dat_Per_ID='$Dat_Per_ID'";
    		 consulta_mysql_2022($sql,basename(__FILE__),__LINE__);	 
    	 }else{
    		  $sql = "INSERT INTO PersonaDatos (Dat_Per_ID, Dat_Dom_Pro_ID, Dat_Dom_Pai_ID, Dat_Dom_Loc_ID, Dat_Nac_Pro_ID, Dat_Nac_Pai_ID, Dat_Nac_Loc_ID, Dat_Nacimiento, Dat_Domicilio, Dat_CP, Dat_Email, Dat_Telefono, Dat_Celular, Dat_Ocupacion, Dat_Retira, Dat_Observaciones, Dat_Fecha, Dat_Hora) VALUES('$Per_ID', '$Dat_Dom_Pro_ID', '$Dat_Dom_Pai_ID', '$Dat_Dom_Loc_ID', '$Dat_Nac_Pro_ID', '$Dat_Nac_Pai_ID', '$Dat_Nac_Loc_ID', '$Dat_Nacimiento', '$Dat_Domicilio', '$Dat_CP', '$Dat_Email', '$Dat_Telefono', '$Dat_Celular', '$Dat_Ocupacion', '$Dat_Retira', '$Dat_Observaciones', '$Dat_Fecha', '$Dat_Hora')";
    		  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    	 
    	 }//fin
         guardarCuentaUsuario($Per_DNI, $Per_DNI, "$Per_Apellido, $Per_Nombre");
    	 //$mensaje = $sql;
    	 $mensaje = "Se actualizó correctamente los datos";
    	 
      } else {

          if (strlen($Per_ID)<=10){ 

        	  //Inserto
        	  $sql = "INSERT INTO Persona (Per_Doc_ID, Per_DNI, Per_Apellido, Per_Nombre, Per_Sexo, Per_Fecha, Per_Hora, Per_Token) VALUES('$Per_Doc_ID', '$Per_DNI', '$Per_Apellido', '$Per_Nombre', '$Per_Sexo', '$Per_Fecha', '$Per_Hora', UUID())";

            $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
            if ($res["success"] == true){
                $Per_ID = $res['id'];
            }else{
                echo '/Error en la consulta! Hay datos relacionados';
                exit();
            }
        	  
        	  $Dat_Per_ID = $Per_ID;
        	  
        	  $sql = "INSERT INTO PersonaDatos (Dat_Per_ID, Dat_Dom_Pro_ID, Dat_Dom_Pai_ID, Dat_Dom_Loc_ID, Dat_Nac_Pro_ID, Dat_Nac_Pai_ID, Dat_Nac_Loc_ID, Dat_Nacimiento, Dat_Domicilio, Dat_CP, Dat_Email, Dat_Telefono, Dat_Celular, Dat_Observaciones, Dat_Ocupacion, Dat_Retira, Dat_Fecha, Dat_Hora) VALUES('$Dat_Per_ID', '$Dat_Dom_Pro_ID', '$Dat_Dom_Pai_ID', '$Dat_Dom_Loc_ID', '$Dat_Nac_Pro_ID', '$Dat_Nac_Pai_ID', '$Dat_Nac_Loc_ID', '$Dat_Nacimiento', '$Dat_Domicilio', '$Dat_CP', '$Dat_Email', '$Dat_Telefono', '$Dat_Celular', '$Dat_Observaciones', '$Dat_Ocupacion', '$Dat_Retira', '$Dat_Fecha', '$Dat_Hora')";
        	  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
              $mensaje = "Se agregó un nuevo registro correctamente";
          
          }else $mensaje = "Hubo un error en la operación";    
    	      	  
      }
      //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
      
      //Mario. corregir duplicados
      //si el Per_ID es correcto sigue sino no
      if (strlen($Per_ID)<=10){ 
        echo $Per_ID."/".$mensaje;
      }else {
        echo "999999999/".$mensaje;
      }
   }//del else Per_ID==0


}//fin function

function buscarPersonaNueva(){
	
    $texto = $_POST['Texto'];
    $where="";
    $vieneApeNom=0;
    $ape="";
    $nom="";
    $findme = ',';
    $pos = strpos($texto, $findme);
    
    //si viene coma, entonces viene apellido y nombre
    if ($pos !== false) {
        $vieneApeNom=1;
        //asigno el apellido y el nombre
        list($ape, $nom) = explode(",", $texto);
        $ape=trim($ape);
        $nom=trim($nom);
    }else {
        $vieneApeNom=0;
        $ape="";
        $nom="";
    }

    //echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Persona INNER JOIN PersonaEstado ON (Per_Baja = PEs_ID)";
	
	if ($texto!="todos") {
        if ($vieneApeNom==0){
		  $where = "WHERE (Per_DNI = '$texto' OR Per_Apellido LIKE '%$texto%')";
        }else {
          $where = "WHERE (Per_Apellido LIKE '%$ape%' AND Per_Nombre LIKE '%$nom%')";  
        }
	}else $where="";

	$sql = "SELECT * FROM $Tabla $where ORDER BY Per_Apellido, Per_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    //echo $sql;
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		
		Per_ID = $("#Per_ID" + i).val();
		//alert(vCliID);
		
        buscarDatos(Per_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 
	 $("a[id^='botBaja']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(7,10);		
		
		Per_ID = $("#Per_ID" + i).val();
		//alert(vCliID);
		buscarDatosBaja(Per_ID);		
		
	 });//fin evento click//*/	
	 
	 $("a[id^='botLegajo']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Per_ID = $("#Per_ID" + i).val();
		//alert(vCliID);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "generarLegajoAspirante", Per_ID: Asp_Per_ID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				jAlert("El Legajo fue generado correctamente con el número <strong>"+data+"</strong>", "Legajo generado");
				//$("#mostrarResultado").html(data);
				$("#Legajo" + i).text(data);
				$("#botLegajo" + i).hide();
				$("#loading").hide();
			}
		});//fin ajax//*/
	 });//fin evento click//*/	
	  $("a[id^='botEntrevista']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(13,10);
		Asp_Per_ID = $("#Asp_Per_ID" + i).val();
		textoBuscar = $("#textoBuscar").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {Per_ID: Asp_Per_ID, textoBuscar: textoBuscar},
			url: 'cargarNuevaEntrevista.php',
			success: function(data){ 
				//alert(data);
				//$("#mostrarResultado").html(data);
				$("#principal").html(data);
				$("#loading").hide();
			}
		});//fin ajax//*/
	
	 });//fin evento click//*/	
	
});//fin domready


function buscarDatos(Per_ID){
	$.post("cargarOpciones.php",{opcion: 'buscarDatosPersonaNueva', Per_ID: Per_ID}, function(data){
		//alert(data);
		//return;
		if (data!="{}"){
			
			var obj = $.parseJSON(data);
			//alert(obj.Per_Apellido);
			
			$("#Per_ID").val(obj.Per_ID);
			$("#Per_Doc_ID").val(obj.Per_Doc_ID);
			$("#Per_DNI").val(obj.Per_DNI);
			$("#Per_Apellido").val(obj.Per_Apellido);
			$("#Per_Nombre").val(obj.Per_Nombre);
			$("#Per_Sexo").val(obj.Per_Sexo);
			//$("#Per_Fecha").val(obj.Per_Fecha);
			//$("#Per_Hora").val(obj.Per_Hora);
			//-------------
			if (obj.No_Tiene=="SI"){
			  $("#Dat_Per_ID").val(obj.Dat_Per_ID);
			  $("#DomProID").val(obj.Dat_Dom_Pro_ID);
			  $("#DomPaisID").val(obj.Dat_Dom_Pai_ID);
			  $("#DomLocID").val(obj.Dat_Dom_Loc_ID);
			  $("#NacProID").val(obj.Dat_Nac_Pro_ID);
			  $("#NacPaisID").val(obj.Dat_Nac_Pai_ID);
			  $("#NacLocID").val(obj.Dat_Nac_Loc_ID);
			  $("#Dat_Nacimiento").val(obj.Dat_Nacimiento);
			  $("#Dat_Domicilio").val(obj.Dat_Domicilio);
			  $("#Dat_CP").val(obj.Dat_CP);
			  $("#Dat_Email").val(obj.Dat_Email);
			  $("#Dat_Telefono").val(obj.Dat_Telefono);
			  $("#Dat_Celular").val(obj.Dat_Celular);
              $("#Dat_Ocupacion").val(obj.Dat_Ocupacion);
              $("#Dat_Retira").val(obj.Dat_Retira);
			  $("#Dat_Observaciones").val(obj.Dat_Observaciones);
			  //if (obj.Dat_Telefono.length>8)$("#Dat_Observaciones").val($("#Dat_Observaciones").val() + obj.Dat_Telefono);
			  //if (obj.Dat_Celular.length>10)$("#Dat_Observaciones").val($("#Dat_Observaciones").val() + obj.Dat_Celular);
			}else{
			    $("#NacPaisID").val(1);
				$("#NacProID").val(19);
				$("#NacLocID").val(1735);
				$("#DomPaisID").val(1);
				$("#DomProID").val(19);
				$("#DomLocID").val(1735);				  
			}
			//$("#Dat_Fecha").val(obj.Dat_Fecha);
			//$("#Dat_Hora").val(obj.Dat_Hora);
			
			
		}//fin if
	});		
}//fin function

function buscarDatosBaja(Per_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatosPersonaBaja', Per_ID: Per_ID}, function(data){
			//alert(data);
			//return;
			jAlert(data, "Estado de Persona");
			valorBaja = $("#PEs_ID_Aux").val();
			$("#PEs_ID").val(valorBaja);
			
			$("#botGuardarBaja").click(function(evento){											  
				evento.preventDefault();
				Per_Baja = $("#PEs_ID").val();
				//alert(Per_Baja);
				Per_BajaMotivo = $("#Per_BajaMotivo").val();
				//alert(Per_BajaMotivo);				
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "guardarPersonaBaja", Per_ID: Per_ID, Per_Baja: Per_Baja, Per_BajaMotivo: Per_BajaMotivo},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//$("#textoGuardarBaja").text(data);
						$("#textoGuardarBaja").text("Cambios realizados");						
					}
				});//fin ajax//*/
			 });//fin evento click//*/	
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">#</th>
                  <th align="center">Apellido y Nombre</th>
                  <th align="center">DNI</th>
                  <th align="center">Sexo</th>
                  <th align="center">Legajo</th>
                  <th align="center" title="Indica el Estado de Baja">Estado</th>
                  <th align="center">Otros datos</th>              
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>" title="Fecha carga persona <?php echo cfecha($row[Per_Fecha])." ".$row[Per_Hora];?>">                 
                  <td align="center"><a name="<?php echo $row[Per_ID];?>" id="<?php echo $row[Per_ID];?>"></a><?php echo $row[Per_ID];?>
                    <input type="hidden" id="Per_ID<?php echo $i;?>" value="<?php echo $row[Per_ID];?>" /></td>
                 <td>
                  <span id="Nombre<?php echo $i;?>"><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></span></td>
                 <td align="center"><?php echo $row[Per_DNI];?></td>
                 <td align="center"><?php echo $row[Per_Sexo];?></td>
                 <td align="center"><span id="Legajo<?php echo $i;?>"><?php $legajo = buscarLegajoAspirante($row[Per_ID]);echo $legajo;?></span></td>
                 <td align="center">
				 <?php 
				 if ($row[PEs_Nombre]!="Normal") 
				 	echo "<span class='nota_Reprobado'>$row[PEs_Nombre]</span>";
				else
					echo $row[PEs_Nombre];
				 
				 ?></td>
                 <td align="center"><?php $datos = buscarOtrosDatosPersona($row[Per_ID]);
				 if ($datos) echo $datos; else echo "No cargado";
				 ?></td>
                                    
                  <td align="right">
                  
                  <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a>
                 <?php
                 obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	   if ($UsuID==2){
	   ?>
                  <a href="#" id="botBaja<?php echo $i;?>"><img src="imagenes/group_delete.png" alt="Actualizar Estado de Baja a la Persona" title="Actualizar Estado de Baja a la Persona" width="32" height="32" border="0" /></a> 
                   <?php
	   }//fin if
	   ?>
                 </td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
            Total de personas: <?php echo mysqli_num_rows($result);?>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if

}//fin function

function buscarDatosPersonaBaja() {
	//echo "Hola";exit;
	$Per_ID = $_POST['Per_ID'];
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM Persona INNER JOIN PersonaEstado ON (Per_Baja = PEs_ID) WHERE Per_ID='$Per_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		?>
        <span id="textoGuardarBaja">Estado</span>
        <table width="100%" align="center" style="height:100px">
  <tr>
    <td>Estado de Baja</td>
    <td><input type="hidden" id="PEs_ID_Aux" value="<?php echo $row[Per_Baja];?>" />
    <select name="PEs_ID" id="PEs_ID">
      <?php
	  $sql = "SELECT * FROM PersonaEstado";
	  //echo $sql;
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result2) > 0) {
		while ($row2 = mysqli_fetch_array($result2)){
	  ?>
      <option value="<?php echo $row2[PEs_ID];?>"><?php echo $row2[PEs_Nombre];?></option>
      <?php
		}//fin while
	}//fin if
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Motivo</td>
    <td><textarea id="Per_BajaMotivo"><?php echo $row[Per_BajaMotivo];?></textarea></td>
  </tr>
  <tr>
    <td>Última modificación</td>
    <td><?php echo cfecha($row[Per_BajaFecha])." ".$row[Per_BajaHora];?></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="botGuardarBaja" id="botGuardarBaja" value="Guardar Estado de Baja"></td>
  </tr>
</table>
        <?php			
	}//fin if	
}//fin Function
function guardarPersonaBaja(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Per_BajaUsu_ID, $Per_BajaFecha, $Per_BajaHora);	
	$Per_ID = $_POST['Per_ID'];
	$Per_Baja = $_POST['Per_Baja'];
	$Per_BajaMotivo = $_POST['Per_BajaMotivo'];
	
	$Tabla = "Persona";
	
    $sql = "SELECT * FROM $Tabla WHERE Per_ID='$Per_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Per_Baja='$Per_Baja', Per_BajaMotivo='$Per_BajaMotivo', Per_BajaUsu_ID='$Per_BajaUsu_ID', Per_BajaFecha='$Per_BajaFecha', Per_BajaHora='$Per_BajaHora' WHERE Per_ID='$Per_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } 
  //echo $sql;
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  //echo $mensaje;
        
}//fin function

function buscarDatosPersonaNueva() {
	//echo "Hola";exit;
	$Per_ID = $_POST['Per_ID'];

	
	$Tabla = "Persona";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE Per_ID='$Per_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);									

        if (strlen($row[Per_Foto])<5) $foto='';
        else $foto = $row[Per_Foto]; 

		if (strlen($row[Per_Alternativo])<5) $alternativo='';		
		else $alternativo=$row[Per_Alternativo];

        //-----------------
		$datos .= "{\"Per_ID\": \"" . $row[Per_ID] . "\",\"";
		$datos .= "Per_Doc_ID\": \"" . $row[Per_Doc_ID] . "\",\"";
		$datos .= "Per_DNI\": \"" . $row[Per_DNI] . "\",\"";
		$datos .= "Per_Apellido\": \"" . $row[Per_Apellido] . "\",\"";
		$datos .= "Per_Nombre\": \"" . $row[Per_Nombre] . "\",\"";
		$datos .= "Per_Sexo\": \"" . $row[Per_Sexo] . "\",\"";
		$datos .= "Per_Foto\": \"" . $foto . "\",\"";
		$datos .= "Per_Fecha\": \"" . $row[Per_Fecha] . "\",\"";
		$datos .= "Per_Hora\": \"" . $row[Per_Hora] . "\",\"";
		$datos .= "Per_Alternativo\": \"" . $alternativo . "\",\"";
		$datos .= "Per_Extranjero\": \"" . $row[Per_Extranjero]; 
		//-----------------
		$sql = "SELECT * FROM $Tabla INNER JOIN PersonaDatos ON (Per_ID = Dat_Per_ID) WHERE Dat_Per_ID='$Per_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		
		$row = mysqli_fetch_array($result);		
		
		$datos .= "\",\"";
		$datos .= "No_Tiene\": \"" . "SI" . "\",\"";
		$datos .= "Dat_Per_ID\": \"" . $row[Dat_Per_ID] . "\",\"";
		$datos .= "Dat_Dom_Pro_ID\": \"" . $row[Dat_Dom_Pro_ID] . "\",\"";
		$datos .= "Dat_Dom_Pai_ID\": \"" . $row[Dat_Dom_Pai_ID] . "\",\"";
		$datos .= "Dat_Dom_Loc_ID\": \"" . $row[Dat_Dom_Loc_ID] . "\",\"";
		$datos .= "Dat_Nac_Pro_ID\": \"" . $row[Dat_Nac_Pro_ID] . "\",\"";
		$datos .= "Dat_Nac_Pai_ID\": \"" . $row[Dat_Nac_Pai_ID] . "\",\"";
		$datos .= "Dat_Nac_Loc_ID\": \"" . $row[Dat_Nac_Loc_ID] . "\",\"";
		$datos .= "Dat_Nacimiento\": \"" . cfecha($row[Dat_Nacimiento]) . "\",\"";
		$datos .= "Dat_Domicilio\": \"" . limpiarString($row['Dat_Domicilio']) . "\",\"";
		$datos .= "Dat_CP\": \"" . $row[Dat_CP] . "\",\"";
		$datos .= "Dat_Email\": \"" . $row[Dat_Email] . "\",\"";
		$datos .= "Dat_Telefono\": \"" . $row[Dat_Telefono] . "\",\"";
		$datos .= "Dat_Celular\": \"" . $row[Dat_Celular] . "\",\"";		
        $datos .= "Dat_Ocupacion\": \"" . limpiarString($row[Dat_Ocupacion]) . "\",\"";
        $datos .= "Dat_Retira\": \"" . $row[Dat_Retira] . "\",\"";
		$datos .= "Dat_Observaciones\": \"" . limpiarString($row[Dat_Observaciones]) . "\",\"";
		$datos .= "Dat_Fecha\": \"" . $row[Dat_Fecha] . "\",\"";
		$datos .= "Dat_Hora\": \"" . $row[Dat_Hora];
		}//fin otros datos
		
        $datos .= "\"}";
		echo $datos;
	}
 }//fin funcion
 function limpiarString($texto)
{
      $textoLimpio = preg_replace('([^A-Za-z0-9àèìòùáéíóúçñäëïöüÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ \)\(:?¿¡!.,;-])', '', $texto);	     					
      return $textoLimpio;
}
 
function eliminarPersonaNueva() {
//echo "Hola";exit;
    $Per_ID = $_POST['PerID'];
	$sql = "SET FOREIGN_KEY_CHECKS = 0;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM Legajo WHERE Leg_Per_ID='$Per_ID'";//echo $sql;exit;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $Leg_ID = $row['Leg_ID'];//echo $Leg_ID;exit;        
        $sql = "DELETE FROM Colegio_InscripcionClase WHERE IMa_Leg_ID = $Leg_ID";        
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $sql = "DELETE FROM Colegio_Inscripcion WHERE Ins_Leg_ID = $Leg_ID";        
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);//exit;
    }
    $sql = "SELECT * FROM Persona WHERE Per_ID='$Per_ID'";//echo $sql;exit;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "La Persona que intenta eliminar no se encuentra o ya fue eliminado.";
    } else {
          
		$sql = "DELETE FROM PersonaDatos WHERE Dat_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "DELETE FROM RequisitoPresentado WHERE Pre_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "DELETE FROM Familia WHERE Fam_Per_ID = $Per_ID OR Fam_Vin_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);		
		
		$sql = "DELETE FROM Legajo WHERE Leg_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "DELETE FROM CuotaPersona WHERE Cuo_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "DELETE FROM CuotaPago WHERE CuP_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "DELETE FROM CuotaPagoDetalle WHERE CPD_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "DELETE FROM CuentaCorriente WHERE CCo_Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);		
		$sql = "DELETE FROM Persona WHERE Per_ID = $Per_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
		echo "Se ha eliminado la persona seleccionada";     
    }
    $sql = "SET FOREIGN_KEY_CHECKS = 1;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
}//fin function

function buscarDNIRepetido(){
	$Per_DNI = $_POST['Per_DNI'];
	$sql = "SELECT * FROM Persona WHERE Per_DNI='$Per_DNI'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//no existe
		$row = mysqli_fetch_array($result);
		echo "El <strong>DNI ingresado</strong> ya existe para la persona <strong>$row[Per_Apellido], $row[Per_Nombre]</strong>. No se puede continuar cargando una persona con el DNI de otro.";
	}
}//fin function

//---------------------------BACKUPS--------------------------------------------
function mostrarBackups(){
	
	$directorio = opendir("DumpSistema"); //ruta actual
	?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr class="fila_titulo">
    <th scope="col">Nombre del Backup</th>
    <th scope="col">Fecha</th>
    <th scope="col">Tamaño</th>
    <th scope="col">Acción</th>
  </tr>
    <?php
	while ($files[] = readdir($directorio));
	rsort($files);
	closedir($directorio);
	foreach ($files as $archivo)
	
	{
		
		if ($archivo <> "" && $archivo <> "." && $archivo <> ".." && !preg_match("/^hide/i",$archivo) && !is_dir($archivo)){
			
			?>
            <tr class="fila">
    <td><?php echo $archivo;?></td>
    <td align="center"><?php 
	echo date("F d Y H:i:s.", filectime("DumpSistema/".$archivo)); 
	?></td>
    <td align="center"><?php 
	$size = filesize("DumpSistema/".$archivo);
	echo formatBytes($size, 2);
	?></td>
    <td><a href="descargarBackup.php?archivo=<?php echo $archivo;?>" title="Descargar fichero"><img src="iconos/mod_descargar.png" width="32" height="32" /></a></td>
  </tr>
          <?php  
		}//fin if
		}//fin foreach
	
	?>
    </table>
    <?php
}//fin function

function mostrarRestore(){
	
	$directorio = opendir("RestoreSistema"); //ruta actual
	?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr class="fila_titulo">
    <th scope="col">Nombre del Restore</th>
    <th scope="col">Fecha</th>
    <th scope="col">Tamaño</th>
    <th scope="col">Acción</th>
  </tr>
    <?php
	while ($files[] = readdir($directorio));
	rsort($files);
	closedir($directorio);
	foreach ($files as $archivo)
	
	{
		
		if ($archivo <> "" && $archivo <> "." && $archivo <> ".." && !preg_match("/^hide/i",$archivo) && !is_dir($archivo)){
			
			?>
            <tr class="fila2">
    <td><?php echo $archivo;?></td>
    <td align="center"><?php 
	echo date("F d Y H:i:s.", filectime("RestoreSistema/".$archivo)); 
	?></td>
    <td align="center"><?php 
	$size = filesize("RestoreSistema/".$archivo);
	echo formatBytes($size, 2);
	?></td>
    <td><a href="importarBackup.php?archivo=<?php echo $archivo;?>" title="Descargar fichero" target="_blank"><img src="iconos/mod_upload.png" width="32" height="32" /></a></td>
  </tr>
          <?php  
		}//fin if
		}//fin foreach
	
	?>
    </table>
    <?php
}//fin function
function mostrarRestoreWeb(){
	
	$dir = "RestoreWeb";
	$directorio = opendir($dir); //ruta actual
	?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr class="fila_titulo">
    <th scope="col">Nombre del Archivo Web</th>
    <th scope="col">Fecha</th>
    <th scope="col">Tamaño</th>
    <th scope="col">Acción</th>
  </tr>
    <?php
	while ($files[] = readdir($directorio));
	rsort($files);
	closedir($directorio);
	foreach ($files as $archivo)
	
	{
		
		if ($archivo <> "" && $archivo <> "." && $archivo <> ".." && !preg_match("/^hide/i",$archivo) && !is_dir($archivo)){
			
			?>
            <tr class="fila2">
    <td><?php echo $archivo;?></td>
    <td align="center"><?php 
	echo date("F d Y H:i:s.", filectime("$dir/$archivo")); 
	?></td>
    <td align="center"><?php 
	$size = filesize("$dir/$archivo");
	echo formatBytes($size, 2);
	?></td>
    <td><a href="importarActualizarWeb.php?archivo=<?php echo $archivo;?>" title="Actualizar la web con este archivo" target="_blank"><img src="iconos/mod_upload.png" width="32" height="32" /></a></td>
  </tr>
          <?php  
		}//fin if
		}//fin foreach
	
	?>
    </table>
    <?php
}//fin function

//--------VALIDACIONES---------------------

function validarPadreDatos(){
	$Per_ID = $_POST[Per_ID];
	$respuesta = obtenerTutor($Per_ID, $DNITutor, $PerIDTutor);
	if ($respuesta=="No cargado")
		echo "El Alumno/a no tiene asociado un padre/madre";
	
	
}//fin function

//********************** DIMENSION ***********************************
function guardarDimension(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//obtenerRegistroUsuario($CSe_Usu_ID, $CSe_Fecha, $CSe_Hora);	
	$Dim_Lec_ID = $_POST['Dim_Lec_ID'];
	$Dim_ID = $_POST['Dim_ID'];
	$Dim_Nombre = $_POST['Dim_Nombre'];
	$Dim_Detalle = $_POST['Dim_Detalle'];
	$Dim_Transversal = $_POST['Dim_Transversal'];
	
	$Tabla = "Colegio_Dimension";
	
    $sql = "SELECT * FROM $Tabla WHERE Dim_Lec_ID='$Dim_Lec_ID' AND Dim_ID='$Dim_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Dim_Nombre='$Dim_Nombre', Dim_Detalle='$Dim_Detalle', Dim_Transversal='$Dim_Transversal' WHERE Dim_Lec_ID='$Dim_Lec_ID' AND Dim_ID='$Dim_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  $sql = "SELECT * FROM $Tabla WHERE Dim_Lec_ID='$Dim_Lec_ID' ORDER BY Dim_ID DESC";	
	  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  if (mysqli_num_rows($result) > 0){
		  $row=mysqli_fetch_array($result);
		  $Dim_ID = $row[Dim_ID] + 1;
	  }else{
	  	$Dim_ID = 1;
	  }
	  $sql = "INSERT INTO $Tabla (Dim_Lec_ID, Dim_ID, Dim_Nombre, Dim_Detalle, Dim_Transversal) VALUES('$Dim_Lec_ID', '$Dim_ID', '$Dim_Nombre', '$Dim_Detalle', '$Dim_Transversal')";
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarColegio_Dimension(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Colegio_Dimension";
	
	$texto = $_POST['Texto'];
	if ($texto!="todos") $where = "";
	$sql = "SELECT * FROM
    $Tabla $where INNER JOIN Lectivo ON (Dim_Lec_ID = Lec_ID) ORDER BY Dim_Lec_ID, Dim_ID, Dim_Nombre";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Dim_Lec_ID = $("#Dim_Lec_ID" + i).val();
		Dim_ID = $("#Dim_ID" + i).val();
		//alert(vCliID);
		buscarDatos(Dim_Lec_ID, Dim_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 
	 
});//fin domready
function buscarDatos(Dim_Lec_ID, Dim_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Dim_Lec_ID: Dim_Lec_ID, Dim_ID: Dim_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Dim_Lec_ID").val(obj.Dim_Lec_ID);
				$("#Dim_ID").val(obj.Dim_ID);
				$("#Dim_Nombre").val(obj.Dim_Nombre);
				$("#Dim_Detalle").val(obj.Dim_Detalle);
				$("#Dim_Transversal").val(obj.Dim_Transversal);
				
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Ciclo Lectivo</th>
                  
                  <th align="center">Nombre</th>
                  <th align="center">Detalle</th>
                  <th align="center">¿Es Transversal?</th>
                  
                 
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[Lec_Nombre];?><input type="hidden" id="Dim_Lec_ID<?php echo $i;?>" value="<?php echo $row[Dim_Lec_ID];?>" />
                  <input type="hidden" id="Dim_ID<?php echo $i;?>" value="<?php echo $row[Dim_ID];?>" />
                 </td>
                 <td>
                  <span id="Nombre<?php echo $i;?>"><?php echo $row[Dim_Nombre];?></span></td>
                 <td align="center"><?php echo $row[Dim_Detalle];?></td>                 
                 <td align="center"><?php if ($row[Dim_Transversal]==1) echo "Si"; else echo "No";?></td>
                                    
                  <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <!--<a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a>--></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosColegio_Dimension() {
	//echo "Hola";exit;
	$Dim_ID = $_POST['Dim_ID'];
	$Dim_Lec_ID = $_POST['Dim_Lec_ID'];
	
	$Tabla = "Colegio_Dimension";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE Dim_ID='$Dim_ID' AND Dim_Lec_ID='$Dim_Lec_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						
			

		$datos .= "{\"Dim_Lec_ID\": \"" . $row[Dim_Lec_ID] . "\",\"";
		$datos .= "Dim_ID\": \"" . $row[Dim_ID] . "\",\"";
		$datos .= "Dim_Nombre\": \"" . $row[Dim_Nombre] . "\",\"";
		$datos .= "Dim_Detalle\": \"" . $row[Dim_Detalle] . "\",\"";
		$datos .= "Dim_Transversal\": \"" . $row[Dim_Transversal] . "\"}";
		
		
		echo $datos;
	}
 }//fin funcion
 
function eliminarColegio_Dimension() {
//echo "Hola";exit;
    $CCT_ID = $_POST['CCT_ID'];
	$CCT_Nombre = $_POST['CCT_Nombre'];
	
	$Tabla = "Colegio_Dimension";

    $sql = "SELECT * FROM $Tabla WHERE CCT_ID='$CCT_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Tipo de Cuenta contable elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Cuenta WHERE Cue_CCT_ID = $CCT_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Cuentas contables relacionadas.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE CCT_ID = $CCT_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Tipo de Cuenta seleccionado.";
        }
    }
}//fin function

//********************** AMBITO ***********************************
function guardarColegio_Ambito(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//obtenerRegistroUsuario($CSe_Usu_ID, $CSe_Fecha, $CSe_Hora);	
	$Amb_Lec_ID = $_POST['Amb_Lec_ID'];
	$Amb_Dim_ID = $_POST['Amb_Dim_ID'];
	$Amb_ID = $_POST['Amb_ID'];
	$Amb_Nombre = $_POST['Amb_Nombre'];
	$Amb_Detalle = $_POST['Amb_Detalle'];
	$Amb_Indicador = $_POST['Amb_Indicador'];
	
	$Tabla = "Colegio_Ambito";
	
    $sql = "SELECT * FROM $Tabla WHERE Amb_Lec_ID='$Amb_Lec_ID' AND Amb_Dim_ID='$Amb_Dim_ID' AND Amb_ID='$Amb_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Amb_Nombre='$Amb_Nombre', Amb_Detalle='$Amb_Detalle', Amb_Indicador='$Amb_Indicador' WHERE Amb_Lec_ID='$Amb_Lec_ID' AND Amb_Dim_ID='$Amb_Dim_ID' AND Amb_ID='$Amb_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  $sql = "SELECT * FROM $Tabla WHERE Amb_Lec_ID='$Amb_Lec_ID' AND Amb_Dim_ID='$Amb_Dim_ID' ORDER BY Amb_Lec_ID DESC, Amb_Dim_ID, Amb_ID DESC";	
	  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	 // echo $sql;
	  if (mysqli_num_rows($result) > 0){
		  $row=mysqli_fetch_array($result);
		  $Amb_ID = $row[Amb_ID] + 1;
	  }else{
	  	$Amb_ID = 1;
	  }
	  $sql = "INSERT INTO $Tabla (Amb_Lec_ID, Amb_Dim_ID, Amb_ID, Amb_Nombre, Amb_Detalle, Amb_Indicador) VALUES('$Amb_Lec_ID', '$Amb_Dim_ID', '$Amb_ID', '$Amb_Nombre', '$Amb_Detalle', '$Amb_Indicador')";
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarColegio_Ambito(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Colegio_Ambito";
	
	$texto = $_POST['Texto'];
	if ($texto!="todos") $where = "";
	$sql = "SELECT * FROM
    $Tabla $where INNER JOIN Colegio_Dimension 
        ON (Amb_Lec_ID = Dim_Lec_ID) AND (Amb_Dim_ID = Dim_ID)
    INNER JOIN Lectivo 
        ON (Dim_Lec_ID = Lec_ID) ORDER BY Amb_Lec_ID, Amb_Dim_ID, Amb_ID, Amb_Nombre";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Amb_Lec_ID = $("#Amb_Lec_ID" + i).val();
		Amb_Dim_ID = $("#Amb_Dim_ID" + i).val();
		Amb_ID = $("#Amb_ID" + i).val();
		//alert(vCliID);
		buscarDatos(Amb_Lec_ID, Amb_Dim_ID, Amb_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 
	 
});//fin domready
function buscarDatos(Amb_Lec_ID, Amb_Dim_ID, Amb_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Amb_Lec_ID: Amb_Lec_ID, Amb_Dim_ID: Amb_Dim_ID, Amb_ID: Amb_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Amb_Lec_ID").val(obj.Amb_Lec_ID);
				$("#Amb_Dim_ID").val(obj.Amb_Dim_ID);
				$("#Amb_ID").val(obj.Amb_ID);
				$("#Amb_Nombre").val(obj.Amb_Nombre);
				$("#Amb_Detalle").val(obj.Amb_Detalle);
				$("#Amb_Indicador").val(obj.Amb_Indicador);
				
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Ciclo Lectivo</th>
                  <th align="center">Dimensión</th>
                  <th align="center">Nombre</th>
                  <th align="center">Detalle</th>
                  <th align="center">Indicador
                 
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[Lec_Nombre];?><input type="hidden" id="Amb_Lec_ID<?php echo $i;?>" value="<?php echo $row[Amb_Lec_ID];?>" />
                  <input type="hidden" id="Amb_Dim_ID<?php echo $i;?>" value="<?php echo $row[Amb_Dim_ID];?>" />
                  <input type="hidden" id="Amb_ID<?php echo $i;?>" value="<?php echo $row[Amb_ID];?>" />
                 </td>
                 <td align="center"><?php echo $row[Dim_Nombre];?></td>
                 <td>
                  <span id="Nombre<?php echo $i;?>"><?php echo $row[Amb_Nombre];?></span></td>
                 <td align="center"><?php echo $row[Amb_Detalle];?></td>                 
                 <td align="center"><?php echo $row[Amb_Indicador];?></td>
                                    
                  <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <!--<a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a>--></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosColegio_Ambito() {
	//echo "Hola";exit;
	$Amb_ID = $_POST['Amb_ID'];
	$Amb_Dim_ID = $_POST['Amb_Dim_ID'];
	$Amb_Lec_ID = $_POST['Amb_Lec_ID'];
	
	$Tabla = "Colegio_Ambito";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE Amb_ID='$Amb_ID' AND Amb_Lec_ID='$Amb_Lec_ID' AND Amb_Dim_ID='$Amb_Dim_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						
			
		$datos .= "{\"Amb_Lec_ID\": \"" . $row[Amb_Lec_ID] . "\",\"";
		$datos .= "Amb_Dim_ID\": \"" . $row[Amb_Dim_ID] . "\",\"";
		$datos .= "Amb_ID\": \"" . $row[Amb_ID] . "\",\"";
		$datos .= "Amb_Nombre\": \"" . $row[Amb_Nombre] . "\",\"";
		$datos .= "Amb_Detalle\": \"" . $row[Amb_Detalle] . "\",\"";
		$datos .= "Amb_Indicador\": \"" . $row[Amb_Indicador] . "\"}";
				
		echo $datos;
	}
 }//fin funcion
 
function eliminarColegio_Ambito() {
//echo "Hola";exit;
    $CCT_ID = $_POST['CCT_ID'];
	$CCT_Nombre = $_POST['CCT_Nombre'];
	
	$Tabla = "Colegio_Ambito";

    $sql = "SELECT * FROM $Tabla WHERE CCT_ID='$CCT_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Tipo de Cuenta contable elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Cuenta WHERE Cue_CCT_ID = $CCT_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Cuentas contables relacionadas.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE CCT_ID = $CCT_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Tipo de Cuenta seleccionado.";
        }
    }
}//fin function

//********************** AMBITO INFORME***********************************
function guardarColegio_AmbitoInforme(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Inf_Usu_ID, $Inf_Fecha, $Inf_Hora);	
	$Inf_Lec_ID = $_POST['Inf_Lec_ID'];
	$Inf_Dim_ID = $_POST['Inf_Dim_ID'];
	$Inf_Amb_ID = $_POST['Inf_Amb_ID'];
	$Inf_Cla_ID = $_POST['Inf_Cla_ID'];
	$Inf_Leg_ID = $_POST['Inf_Leg_ID'];
	$Inf_Detalle = limpiaCadena($_POST['Inf_Detalle']);
	$Inf_Doc_ID = $_POST['Inf_Doc_ID'];
	$Inf_Aceptado = $_POST['Inf_Aceptado'];
	$Inf_Corregido = $_POST['Inf_Corregido'];
	if (empty($Inf_Aceptado)) $Inf_Aceptado=0;
	if (empty($Inf_Corregido)) $Inf_Corregido=0;
	$Inf_Semestre = 2;//Lo coloco manualmente or seguridad
	obtenerRegistroUsuario($Inf_UsuModif, $Inf_FechaModif, $Inf_HoraModif);
	
	
	$Tabla = "Colegio_AmbitoInforme";
	
    $sql = "SELECT * FROM $Tabla WHERE Inf_Semestre='$Inf_Semestre' AND Inf_Lec_ID='$Inf_Lec_ID' AND Inf_Dim_ID='$Inf_Dim_ID' AND Inf_Amb_ID='$Inf_Amb_ID' AND Inf_Leg_ID='$Inf_Leg_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
		 $row=mysqli_fetch_array($result);
		 if ($row[Inf_Aceptado]==1){
			 $mensaje = "ATENCION!!!! No se puede actualizar este informe porque ya fue aprobado";
		 }else{
		 	obtenerRegistroUsuario($Inf_UsuModif, $Inf_FechaModif, $Inf_HoraModif);
		 	$sql = "UPDATE $Tabla SET Inf_Detalle='$Inf_Detalle', Inf_Aceptado='$Inf_Aceptado', Inf_Corregido='$Inf_Corregido', Inf_FechaModif='$Inf_FechaModif', Inf_HoraModif='$Inf_HoraModif', Inf_UsuModif='$Inf_UsuModif' WHERE Inf_Lec_ID='$Inf_Lec_ID' AND Inf_Dim_ID='$Inf_Dim_ID' AND Inf_Amb_ID='$Inf_Amb_ID' AND Inf_Leg_ID='$Inf_Leg_ID' AND Inf_Semestre='$Inf_Semestre'";
		 	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		 	$mensaje = "Se actualizó correctamente los datos";
		 }
		 
		 
  } else {
	  
	  $sql = "INSERT INTO $Tabla (Inf_Lec_ID, Inf_Dim_ID, Inf_Amb_ID, Inf_Leg_ID, Inf_Semestre, Inf_Detalle, Inf_Usu_ID, Inf_Fecha, Inf_Hora, Inf_Aceptado, Inf_Corregido, Inf_Doc_ID) VALUES('$Inf_Lec_ID', '$Inf_Dim_ID', '$Inf_Amb_ID', '$Inf_Leg_ID', '$Inf_Semestre', '$Inf_Detalle', '$Inf_Usu_ID', '$Inf_Fecha', '$Inf_Hora', '$Inf_Aceptado', '$Inf_Corregido', '$Inf_Doc_ID')";
	  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  
  echo $mensaje;
        
}//fin function

function cambiarEstadoColegio_AmbitoInforme(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	//obtenerRegistroUsuario($Inf_Usu_ID, $Inf_Fecha, $Inf_Hora);	
	$Inf_Lec_ID = $_POST['Inf_Lec_ID'];
	$Inf_Dim_ID = $_POST['Inf_Dim_ID'];
	$Inf_Amb_ID = $_POST['Inf_Amb_ID'];
	$Inf_Leg_ID = $_POST['Inf_Leg_ID'];
	$Inf_Semestre = 2;//Lo coloco manualmente or seguridad
	
	$Campo = $_POST['Campo'];
	
	//obtenerRegistroUsuario($Inf_UsuModif, $Inf_FechaModif, $Inf_HoraModif);
	
	
	$Tabla = "Colegio_AmbitoInforme";
	
    $sql = "SELECT * FROM $Tabla WHERE Inf_Lec_ID='$Inf_Lec_ID' AND Inf_Dim_ID='$Inf_Dim_ID' AND Inf_Amb_ID='$Inf_Amb_ID' AND Inf_Leg_ID='$Inf_Leg_ID' AND Inf_Semestre='$Inf_Semestre'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
		 	$sql = "UPDATE $Tabla SET $Campo='1' WHERE Inf_Lec_ID='$Inf_Lec_ID' AND Inf_Dim_ID='$Inf_Dim_ID' AND Inf_Amb_ID='$Inf_Amb_ID' AND Inf_Leg_ID='$Inf_Leg_ID' AND Inf_Semestre='$Inf_Semestre'";
		 	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		 	$mensaje = "Se actualizó el estado del registro";	 		 
  }
  
  echo $mensaje;
        
}//fin function

function buscarColegio_AmbitoInforme(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Colegio_AmbitoInforme";
	$Doc_ID = $_SESSION['Doc_ID'];
	
	$texto = $_POST['Texto'];
	$Directora = $_SESSION['Directora'];
	$Inf_Semestre = 2;//Lo coloco manualmente por seguridad
	//echo $_POST['Curso2']."*******";
	if (isset($_POST['Curso2'])){
		list($Cur_ID, $Div_ID) = explode(",", $_POST['Curso2']);
		$whereCurso = "WHERE Cur_ID = $Cur_ID AND Div_ID = $Div_ID";
		if ($_POST['Curso2'] == -1) $whereCurso = "";
		//echo $whereCurso;
	}
	$where = "$whereCurso AND Inf_Doc_ID = $Doc_ID ";
	if (isset($_POST['Corregidos'])) $whereCorregidos = " AND Inf_Corregido = 1 AND Inf_Aceptado = 0 ";
	if (isset($_POST['Aceptados'])) $whereAceptados = " AND Inf_Aceptado = 1 ";
	if ($Directora==0) $subWhere = "AND Inf_Doc_ID = $Doc_ID AND ";
	if ($texto!="todos") $where = "$whereCurso $whereCorregidos $whereAceptados $subWhere (Per_Apellido LIKE '%$texto%' OR Cur_Nombre LIKE '%$texto%') ";
	if ($Directora==1){
		if (isset($_POST['Corregidos'])) $whereCorregidos = " AND Inf_Corregido = 1 AND Inf_Aceptado = 0  ";
		if (isset($_POST['Aceptados'])) $whereAceptados = " AND Inf_Aceptado = 1  ";
		if ($_SESSION['sesion_UsuID']==319) $whereIngles = "AND Inf_Dim_ID = 5 AND Inf_Amb_ID = 1";

		//if (empty($whereCorregidos)) $whereCorregidos = " AND Inf_Corregido = 0 AND Inf_Aceptado = 0  ";
		//if (empty($whereAceptados)) $whereAceptados = " AND Inf_Corregido = 0 AND Inf_Aceptado = 0  ";
		if ($texto!="todos") {
			$where = "$whereCurso $whereCorregidos $whereAceptados $whereIngles AND (Per_Apellido LIKE '%$texto%' OR Cur_Nombre LIKE '%$texto%')";
		}else{
			if ($_SESSION['sesion_UsuID']==319) $whereIngles = "Inf_Dim_ID = 5 AND Inf_Amb_ID = 1";

			$where = " $whereCurso $whereCorregidos $whereAceptados";
			$where = trim($where);
			if (empty($where) && $_SESSION['sesion_UsuID']==319){
				$where = " WHERE $whereIngles";
			}else{
				$where = " $whereCurso $whereCorregidos $whereAceptados $whereIngles";
			}
		}
			//$where = " $whereCurso $whereCorregidos $whereAceptados";
		//if (substr(trim($where),-4)!="AND " && strlen($where)>1) $where = substr(trim($where),0,strlen(trim($where))-4);
	}
	$sql = "SELECT * FROM
    $Tabla  
    INNER JOIN Legajo 
        ON (Inf_Leg_ID = Leg_ID)
    INNER JOIN Colegio_Inscripcion 
        ON (Inf_Leg_ID = Ins_Leg_ID) AND (Inf_Lec_ID = Ins_Lec_ID)
    INNER JOIN Lectivo 
        ON (Inf_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Dimension 
        ON (Inf_Dim_ID = Dim_ID) AND (Inf_Lec_ID = Dim_Lec_ID)
    INNER JOIN Colegio_Ambito 
        ON (Inf_Amb_ID = Amb_ID) AND (Inf_Dim_ID = Amb_Dim_ID) AND (Inf_Lec_ID = Amb_Lec_ID)
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID)
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID)
	$where AND Inf_Semestre='$Inf_Semestre' AND Ins_Lec_ID = ".gLectivoActual($LecNombre)." ORDER BY Inf_Corregido, Inf_Aceptado, Cur_ID, Div_ID, Per_Apellido, Per_Nombre, Inf_Lec_ID, Inf_Dim_ID, Inf_Amb_ID";
	//echo $sql;
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Inf_Lec_ID = $("#Inf_Lec_ID" + i).val();
		Inf_Dim_ID = $("#Inf_Dim_ID" + i).val();
		Inf_Amb_ID = $("#Inf_Amb_ID" + i).val();
		Inf_Leg_ID = $("#Inf_Leg_ID" + i).val();
		
		//alert(vCliID);
		buscarDatos(Inf_Lec_ID, Inf_Dim_ID, Inf_Amb_ID, Inf_Leg_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 $("a[id^='botFalta']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(8,10);		
		Inf_Lec_ID = $("#Inf_Lec_ID" + i).val();
		Inf_Dim_ID = $("#Inf_Dim_ID" + i).val();
		Inf_Amb_ID = $("#Inf_Amb_ID" + i).val();
		Inf_Leg_ID = $("#Inf_Leg_ID" + i).val();
		
		//alert(vCliID);
		cambiarEstado(Inf_Lec_ID, Inf_Dim_ID, Inf_Amb_ID, Inf_Leg_ID, "Inf_Corregido");		
		$("#fila" + i).fadeOut("slow");
		
	 });//fin evento click//*/	
	 $("a[id^='botAprobar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(10,10);		
		Inf_Lec_ID = $("#Inf_Lec_ID" + i).val();
		Inf_Dim_ID = $("#Inf_Dim_ID" + i).val();
		Inf_Amb_ID = $("#Inf_Amb_ID" + i).val();
		Inf_Leg_ID = $("#Inf_Leg_ID" + i).val();
		
		//alert(vCliID);
		cambiarEstado(Inf_Lec_ID, Inf_Dim_ID, Inf_Amb_ID, Inf_Leg_ID, "Inf_Aceptado");	
		$("#fila" + i).fadeOut("slow");	
		
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Inf_Lec_ID, Inf_Dim_ID, Inf_Amb_ID, Inf_Leg_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Inf_Lec_ID: Inf_Lec_ID, Inf_Dim_ID: Inf_Dim_ID, Inf_Amb_ID: Inf_Amb_ID, Inf_Leg_ID: Inf_Leg_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Inf_Lec_ID").val(obj.Inf_Lec_ID);
				$("#Inf_Dim_ID").val(obj.Inf_Dim_ID);
				llenarAmbito2(obj.Inf_Dim_ID);
				$("#Inf_Amb_ID").val(obj.Inf_Amb_ID);
				
				$("#Inf_Detalle").val(obj.Inf_Detalle);
				$("#Inf_Usu_ID").val(obj.Inf_Usu_ID);
				$("#Inf_Fecha").val(obj.Inf_Fecha);
				$("#Inf_Hora").val(obj.Inf_Hora);
				$("#Inf_Aceptado").val(obj.Inf_Aceptado);
				$("#Inf_Corregido").val(obj.Inf_Corregido);
				$("#Inf_FechaModif").val(obj.Inf_FechaModif);
				$("#Inf_HoraModif").val(obj.Inf_HoraModif);
				$("#Inf_UsuModif").val(obj.Inf_UsuModif);
				$("#Inf_Doc_ID").val(obj.Inf_Doc_ID);
				$("#Curso").val(obj.Ins_Cur_ID + ',' + obj.Ins_Div_ID);
				Curso=$("#Curso").val();
				llenarAlumnos2(Curso);
				$("#Inf_Leg_ID").val(obj.Inf_Leg_ID);
				//$("#Ins_Cur_ID").val(obj.Ins_Cur_ID);
				//$("#Ins_Div_ID").val(obj.Ins_Div_ID);
				
			}//fin if
		});		
	}//fin function
function llenarAmbito2(vID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaAmbito",  ID: vID, Nombre: "Inf_Amb_ID"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#Ambito").html(data);
			}
		});//fin ajax//*/
		
		
	}//fin funcion	
function llenarAlumnos2(vID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaAlumnosClase2",  ID: vID, Nombre: "Inf_Leg_ID"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#Alumnos").html(data);
			}
		});//fin ajax//*/
		
	}//fin funcion
function cambiarEstado(Inf_Lec_ID, Inf_Dim_ID, Inf_Amb_ID, Inf_Leg_ID, Campo){
		$.post("cargarOpciones.php",{opcion: 'cambiarEstado<?php echo $Tabla;?>', Inf_Lec_ID: Inf_Lec_ID, Inf_Dim_ID: Inf_Dim_ID, Inf_Amb_ID: Inf_Amb_ID, Inf_Leg_ID: Inf_Leg_ID, Campo: Campo}, function(data){
			jAlert(data, "Resultado de cambiar estado");
			
			//return;
			
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Ciclo Lectivo</th>
                  <th align="center">Alumno</th>
                  <th align="center">Dimensión/Ámbito</th>
                  <th align="center">Informe</th>
                  <th align="center">Curso/División</th> 
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "filaDoc";else $clase = "fila2Doc";
					if ($row[Inf_Corregido]==1) $clase = "corregirLista";
					if ($row[Inf_Aceptado]==1) $clase = "cuota_pagada";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[Lec_Nombre];?>
                  <input type="hidden" id="Inf_Lec_ID<?php echo $i;?>" value="<?php echo $row[Inf_Lec_ID];?>" />
                  <input type="hidden" id="Inf_Dim_ID<?php echo $i;?>" value="<?php echo $row[Inf_Dim_ID];?>" />
                  <input type="hidden" id="Inf_Amb_ID<?php echo $i;?>" value="<?php echo $row[Inf_Amb_ID];?>" />
                  <input type="hidden" id="Inf_Leg_ID<?php echo $i;?>" value="<?php echo $row[Inf_Leg_ID];?>" />
                  <input type="hidden" id="Inf_Semestre<?php echo $i;?>" value="<?php echo $row[Inf_Semestre];?>" />
                 </td>
                 <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
                 <td><?php echo "$row[Dim_Nombre]<br /><strong>$row[Amb_Nombre]</strong>";?></td>
                 <td><?php echo $row[Inf_Detalle];?></td><td align="center"><?php echo "$row[Cur_Nombre] $row[Div_Nombre]";?></td>                 
                 
                                    
                  <td align="center">
                  
                  <?php if ($row[Inf_Aceptado]==1){?>
                  <img src="imagenes/ins_definitiva.png" alt="Informe aceptado" title="Informe aceptado" width="32" height="32" border="0" /> 
                  <?php
				  }else{
				   if ($Directora==1){?>
					  <a href="#" id="botAprobar<?php echo $i;?>" title="Aprobar informe"><img src="imagenes/accept.png" width="32" height="32" style="vertical-align:middle" border="0" /></a>
					 <?php }//fin directora
				   if ($row[Inf_Corregido]==1){?>
                  <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_inactivo.png" alt="Se debe corregir urgente" title="Se debe corregir urgente" width="32" height="32" border="0" /></a> 
                  <?php
				   }else{
					   if ($row[Inf_Corregido]==0){?>
                  <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> 
                  <?php
				   	  if ($Directora==1){?>
					  <a href="#" id="botFalta<?php echo $i;?>"><img src="imagenes/application32.png" width="32" height="32" style="vertical-align:middle" border="0" title="Para Revisar"/></a>
					 <?php }//fin directora
				  }//fin corregido
				   }//fin corregido
				  }//fin else
				  ?>
                  </td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
            Total de registros: <?php if (mysqli_num_rows($result)>0) echo mysqli_num_rows($result);?>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosColegio_AmbitoInforme() {
	//echo "Hola";exit;
	$Inf_Lec_ID = $_POST['Inf_Lec_ID'];
	$Inf_Dim_ID = $_POST['Inf_Dim_ID'];
	$Inf_Amb_ID = $_POST['Inf_Amb_ID'];
	$Inf_Leg_ID = $_POST['Inf_Leg_ID'];
	$Inf_Semestre = 2;//Lo coloco manualmente por seguridad
	
	$Tabla = "Colegio_AmbitoInforme";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla INNER JOIN Colegio_Inscripcion 
        ON (Inf_Lec_ID = Ins_Lec_ID) AND (Inf_Leg_ID = Ins_Leg_ID) WHERE Inf_Lec_ID='$Inf_Lec_ID' AND Inf_Dim_ID='$Inf_Dim_ID' AND Inf_Amb_ID='$Inf_Amb_ID' AND Inf_Leg_ID='$Inf_Leg_ID' AND Inf_Semestre = '$Inf_Semestre'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						

		$datos .= "{\"Inf_Lec_ID\": \"" . $row[Inf_Lec_ID] . "\",\"";
		$datos .= "Inf_Semestre\": \"" . $row[Inf_Semestre] . "\",\"";
		$datos .= "Inf_Dim_ID\": \"" . $row[Inf_Dim_ID] . "\",\"";
		$datos .= "Inf_Amb_ID\": \"" . $row[Inf_Amb_ID] . "\",\"";
		$datos .= "Inf_Leg_ID\": \"" . $row[Inf_Leg_ID] . "\",\"";
		$datos .= "Inf_Detalle\": \"" . limpiarHTML2($row[Inf_Detalle]) . "\",\"";
		$datos .= "Inf_Usu_ID\": \"" . $row[Inf_Usu_ID] . "\",\"";
		$datos .= "Inf_Fecha\": \"" . $row[Inf_Fecha] . "\",\"";
		$datos .= "Inf_Hora\": \"" . $row[Inf_Hora] . "\",\"";
		$datos .= "Inf_Aceptado\": \"" . $row[Inf_Aceptado] . "\",\"";
		$datos .= "Inf_Corregido\": \"" . $row[Inf_Corregido] . "\",\"";
		$datos .= "Inf_FechaModif\": \"" . $row[Inf_FechaModif] . "\",\"";
		$datos .= "Inf_HoraModif\": \"" . $row[Inf_HoraModif] . "\",\"";
		$datos .= "Inf_Doc_ID\": \"" . $row[Inf_Doc_ID] . "\",\"";
		$datos .= "Ins_Cur_ID\": \"" . $row[Ins_Cur_ID] . "\",\"";
		$datos .= "Ins_Div_ID\": \"" . $row[Ins_Div_ID] . "\",\"";
		$datos .= "Inf_UsuModif\": \"" . $row[Inf_UsuModif] . "\"}";
				
		echo $datos;
	}
 }//fin funcion
function limpiaCadena($cadena) {     
	
	 return limpiarHTML(trim($cadena));
}
//Eliminar HTML de Word
function limpiarHTML($html) {
	
	$html = strip_tags($html);
	
	return $html;
} 
function limpiarHTML2($html) {

	$html = preg_match("[\n\r\"\']*", "", $html);

	$html = strip_tags($html);

	$html = preg_match("•	", "", $html);

	return $html;
} 
function eliminarColegio_AmbitoInforme() {
//echo "Hola";exit;
    $CCT_ID = $_POST['CCT_ID'];
	$CCT_Nombre = $_POST['CCT_Nombre'];
	
	$Tabla = "Colegio_AmbitoInforme";

    $sql = "SELECT * FROM $Tabla WHERE CCT_ID='$CCT_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Tipo de Cuenta contable elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Cuenta WHERE Cue_CCT_ID = $CCT_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Cuentas contables relacionadas.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE CCT_ID = $CCT_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Tipo de Cuenta seleccionado.";
        }
    }
}//fin function


//********************** Docente Curso***********************************
function guardarColegio_DocenteCurso(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($DCu_Usu_ID, $DCu_Fecha, $DCu_Hora);	
	$DCu_Lec_ID = $_POST['DCu_Lec_ID'];
	$DCu_Doc_ID = $_POST['DCu_Doc_ID'];
	$DCu_Cur_ID = $_POST['DCu_Cur_ID'];
	$DCu_Div_ID = $_POST['DCu_Div_ID'];
	$DCu_DocenteEspecial = $_POST['DCu_DocenteEspecial'];
	$DCu_Directora = $_POST['DCu_Directora'];
	
	$Tabla = "Colegio_DocenteCurso";
	
    $sql = "SELECT * FROM $Tabla WHERE DCu_Lec_ID='$DCu_Lec_ID' AND DCu_Doc_ID='$DCu_Doc_ID' AND DCu_Cur_ID='$DCu_Cur_ID' AND DCu_Div_ID='$DCu_Div_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET DCu_Usu_ID='$DCu_Usu_ID', DCu_Fecha='$DCu_Fecha', DCu_Hora='$DCu_Hora', DCu_DocenteEspecial='$DCu_DocenteEspecial', DCu_Directora='$DCu_Directora' WHERE DCu_Lec_ID='$DCu_Lec_ID' AND DCu_Doc_ID='$DCu_Doc_ID' AND DCu_Cur_ID='$DCu_Cur_ID' AND DCu_Div_ID='$DCu_Div_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  
	  $sql = "INSERT INTO $Tabla (DCu_Lec_ID, DCu_Doc_ID, DCu_Cur_ID, DCu_Div_ID, DCu_Usu_ID, DCu_Fecha, DCu_Hora, DCu_DocenteEspecial, DCu_Directora) VALUES('$DCu_Lec_ID', '$DCu_Doc_ID', '$DCu_Cur_ID', '$DCu_Div_ID', '$DCu_Usu_ID', '$DCu_Fecha', '$DCu_Hora', '$DCu_DocenteEspecial', '$DCu_Directora')";//echo $sql;
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarColegio_DocenteCurso(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Colegio_DocenteCurso";
	
	$texto = $_POST['Texto'];
	if ($texto!="todos") $where = "";
	$sql = "SELECT * FROM
    Colegio_Docente
    INNER JOIN Persona 
        ON (Doc_Per_ID = Per_ID)
    INNER JOIN Colegio_DocenteCurso 
        ON (DCu_Doc_ID = Doc_ID)
    INNER JOIN Lectivo 
        ON (DCu_Lec_ID = Lec_ID)
    INNER JOIN Curso 
        ON (DCu_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (DCu_Div_ID = Div_ID)  WHERE DCu_Lec_ID > 13 $where ORDER BY Lec_ID, Cur_ID, Div_ID, Per_Apellido, Per_Nombre";
	//echo $sql;
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
				
		//vNombre = $("#Nombre" + i).text();
		DCu_Lec_ID = $("#DCu_Lec_ID" + i).val();
		DCu_Doc_ID = $("#DCu_Doc_ID" + i).val();
		DCu_Cur_ID = $("#DCu_Cur_ID" + i).val();
		DCu_Div_ID = $("#DCu_Div_ID" + i).val();
		
		jConfirm('Est&aacute; seguro que desea eliminar ?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", DCu_Lec_ID: DCu_Lec_ID, DCu_Doc_ID: DCu_Doc_ID,DCu_Cur_ID: DCu_Cur_ID,  DCu_Div_ID: DCu_Div_ID }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		DCu_Lec_ID = $("#DCu_Lec_ID" + i).val();
		DCu_Doc_ID = $("#DCu_Doc_ID" + i).val();
		DCu_Cur_ID = $("#DCu_Cur_ID" + i).val();
		DCu_Div_ID = $("#DCu_Div_ID" + i).val();
		
		//alert(vCliID);
		buscarDatos(DCu_Lec_ID, DCu_Doc_ID, DCu_Cur_ID, DCu_Div_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 
	 
});//fin domready
function buscarDatos(DCu_Lec_ID, DCu_Doc_ID, DCu_Cur_ID, DCu_Div_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', DCu_Lec_ID: DCu_Lec_ID, DCu_Doc_ID: DCu_Doc_ID, DCu_Cur_ID: DCu_Cur_ID, DCu_Div_ID: DCu_Div_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#DCu_Lec_ID").val(obj.DCu_Lec_ID);
				$("#DCu_Doc_ID").val(obj.DCu_Doc_ID);
				$("#DCu_Cur_ID").val(obj.DCu_Cur_ID);
				$("#DCu_Div_ID").val(obj.DCu_Div_ID);
				$("#DCu_Usu_ID").val(obj.DCu_Usu_ID);
				$("#DCu_Fecha").val(obj.DCu_Fecha);
				$("#DCu_Hora").val(obj.DCu_Hora);
				$("#DCu_DocenteEspecial").val(obj.DCu_DocenteEspecial);
				$("#DCu_Directora").val(obj.DCu_Directora);
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Ciclo Lectivo</th>
                  <th align="center">DNI</th>
                  <th align="center">Docente</th>
                  <th align="center">Curso</th>
                  <th align="center">División</th>
                  <th align="center">¿Es Especial? ¿Es directora?</th>
                                   
                  <th align="center">Acci&oacute;n</th>
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[Lec_Nombre];?>
                  <input type="hidden" id="DCu_Lec_ID<?php echo $i;?>" value="<?php echo $row[DCu_Lec_ID];?>" />
                  <input type="hidden" id="DCu_Doc_ID<?php echo $i;?>" value="<?php echo $row[DCu_Doc_ID];?>" />
                  <input type="hidden" id="DCu_Cur_ID<?php echo $i;?>" value="<?php echo $row[DCu_Cur_ID];?>" />
                  <input type="hidden" id="DCu_Div_ID<?php echo $i;?>" value="<?php echo $row[DCu_Div_ID];?>" />
                 </td>
                 <td align="center"><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
                  <td align="center"><?php echo "$row[Per_DNI]";?></td>
                 <td align="center"><?php echo $row[Cur_Nombre];?></td>
                 <td>
                  <span id="Nombre<?php echo $i;?>"><?php echo $row[Div_Nombre];?></span></td>
                 <td align="center"><?php if ($row[DCu_DocenteEspecial]==1) echo "Es especial";//else echo "NO";
				 if ($row[DCu_Directora]==1) echo "Es directora";//else echo "NO";
				 ?></td>                 
                 
                                    
                  <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
  </table>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosColegio_DocenteCurso() {
	//echo "Hola";exit;
	$DCu_Lec_ID = $_POST['DCu_Lec_ID'];
	$DCu_Doc_ID = $_POST['DCu_Doc_ID'];
	$DCu_Cur_ID = $_POST['DCu_Cur_ID'];
	$DCu_Div_ID = $_POST['DCu_Div_ID'];
	
	$Tabla = "Colegio_DocenteCurso";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE DCu_Lec_ID='$DCu_Lec_ID' AND DCu_Doc_ID='$DCu_Doc_ID' AND DCu_Cur_ID='$DCu_Cur_ID' AND DCu_Div_ID='$DCu_Div_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						

		$datos .= "{\"DCu_Lec_ID\": \"" . $row[DCu_Lec_ID] . "\",\"";
		$datos .= "DCu_Doc_ID\": \"" . $row[DCu_Doc_ID] . "\",\"";
		$datos .= "DCu_Cur_ID\": \"" . $row[DCu_Cur_ID] . "\",\"";
		$datos .= "DCu_Div_ID\": \"" . $row[DCu_Div_ID] . "\",\"";
		$datos .= "DCu_Usu_ID\": \"" . $row[DCu_Usu_ID] . "\",\"";
		$datos .= "DCu_Fecha\": \"" . $row[DCu_Fecha] . "\",\"";
		$datos .= "DCu_Hora\": \"" . $row[DCu_Hora] . "\",\"";
		$datos .= "DCu_DocenteEspecial\": \"" . $row[DCu_DocenteEspecial] . "\",\"";
		$datos .= "DCu_Directora\": \"" . $row[DCu_Directora] . "\"}";
				
		echo $datos;
	}
 }//fin funcion
 
function eliminarColegio_DocenteCurso() {
//echo "Hola";exit;
	$DCu_Lec_ID = $_POST['DCu_Lec_ID'];
	$DCu_Doc_ID = $_POST['DCu_Doc_ID'];
	$DCu_Cur_ID = $_POST['DCu_Cur_ID'];
	$DCu_Div_ID = $_POST['DCu_Div_ID'];
	
	$CCT_Nombre = $_POST['CCT_Nombre'];
	
	$Tabla = "Colegio_DocenteCurso";

 
            $sql = "DELETE FROM $Tabla WHERE DCu_Lec_ID='$DCu_Lec_ID' AND DCu_Doc_ID='$DCu_Doc_ID' AND DCu_Cur_ID='$DCu_Cur_ID' AND DCu_Div_ID='$DCu_Div_ID'";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Docente seleccionado.";
      
}//fin function


//********************** Egreso_Cuenta ***********************************
function guardarEgreso_Cuenta(){
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Cue_Usu_ID, $Cue_Fecha, $Cue_Hora);	
	$Cue_ID = $_POST['Cue_ID'];
	$Cue_Nombre = strtoupper(trim($_POST['Cue_Nombre']));
	$Cue_Nombre = arreglarCadenaMayuscula($Cue_Nombre);
    $Cue_CUIT = $_POST['Cue_CUIT'];
    $Cue_RazonSocial = $_POST['Cue_RazonSocial'];
    $Cue_Telefono = $_POST['Cue_Telefono'];
    $Cue_Cue1 = $_POST['CuentaAsociada1'];
    $Cue_Cue2 = $_POST['CuentaAsociada2'];
    $Cue_Cue3 = $_POST['CuentaAsociada3'];
    $Cue_Cue4 = $_POST['CuentaAsociada4'];
    $Cue_Cue5 = $_POST['CuentaAsociada5'];
	
	
	$Tabla = "Egreso_Cuenta";
	
    //$sql = "SELECT * FROM $Tabla WHERE Cue_Nombre LIKE '$Cue_Nombre'";
	$sql = "SELECT * FROM $Tabla WHERE Cue_ID = '$Cue_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        //me fijo si hay otro con el mismo nombre o razón social
        $sql = "SELECT * FROM $Tabla WHERE (Cue_Nombre = '$Cue_Nombre' OR Cue_RazonSocial = '$Cue_RazonSocial') AND Cue_ID<>$Cue_ID;";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) == 0) {
            $sql = "UPDATE $Tabla SET Cue_Usu_ID='$Cue_Usu_ID', Cue_Fecha='$Cue_Fecha', Cue_Hora='$Cue_Hora', Cue_Nombre = '$Cue_Nombre', Cue_CUIT='$Cue_CUIT', Cue_RazonSocial='$Cue_RazonSocial', Cue_Telefono='$Cue_Telefono', Cue_Cue1='$Cue_Cue1', Cue_Cue2='$Cue_Cue2', Cue_Cue3='$Cue_Cue3', Cue_Cue4='$Cue_Cue4', Cue_Cue5='$Cue_Cue5' WHERE Cue_ID = $Cue_ID";
	        $mensaje = "Se actualizó correctamente los datos";
        }else $mensaje = "No se puede actualizar porque hay datos repetidos!";
    } else {
        //me fijo si hay otro con el mismo nombre o razón social
        $sql = "SELECT * FROM $Tabla WHERE (Cue_Nombre = '$Cue_Nombre' OR Cue_RazonSocial = '$Cue_RazonSocial');";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) == 0) {        
	       $sql = "INSERT INTO $Tabla (Cue_ID, Cue_Nombre, Cue_Usu_ID, Cue_Fecha, Cue_Hora, Cue_CUIT, Cue_RazonSocial, Cue_Telefono, Cue_Cue1, Cue_Cue2, Cue_Cue3, Cue_Cue4, Cue_Cue5) VALUES('$Cue_ID', '$Cue_Nombre', '$Cue_Usu_ID', '$Cue_Fecha', '$Cue_Hora', '$Cue_CUIT', '$Cue_RazonSocial', '$Cue_Telefono', '$Cue_Cue1', '$Cue_Cue2', '$Cue_Cue3', '$Cue_Cue4', '$Cue_Cue5')";
	       $mensaje = "Se agregó un nuevo registro correctamente";
       }else $mensaje = "No se puede guardar porque hay datos repetidos!"; 
    }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarEgreso_Cuenta(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Egreso_Cuenta";
	$IDTabla = "Cue_ID";
	
	$texto = $_POST['Texto'];
    $where = " WHERE Cue_Nombre LIKE '%$texto%' OR Cue_CUIT LIKE '%$texto%' OR Cue_RazonSocial LIKE '%$texto%'";
	if ($texto=="todos") $where = "";
	$sql = "SELECT * FROM Egreso_Cuenta $where ORDER BY Cue_Nombre";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Cue_ID = $("#Cue_ID" + i).val();
		Cue_Nombre = $("#Cue_Nombre" + i).val();				
		//alert(vCliID);
		buscarDatos(Cue_ID, Cue_Nombre);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 $("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Cue_ID, Cue_Nombre){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Cue_ID: Cue_ID, Cue_Nombre: Cue_Nombre}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Cue_ID").val(obj.Cue_ID);
				$("#Cue_Nombre").val(obj.Cue_Nombre);
                $("#Cue_CUIT").val(obj.Cue_CUIT);
                $("#Cue_RazonSocial").val(obj.Cue_RazonSocial);
                $("#Cue_Telefono").val(obj.Cue_Telefono);
                $("#CuentaAsociada1").val(obj.Cue_Cue1);
                $("#CuentaAsociada2").val(obj.Cue_Cue2);
                $("#CuentaAsociada3").val(obj.Cue_Cue3);
                $("#CuentaAsociada4").val(obj.Cue_Cue4);
                $("#CuentaAsociada5").val(obj.Cue_Cue5);
				//$("#Cue_Usu_ID").val(obj.Cue_Usu_ID);
				//$("#Cue_Fecha").val(obj.Cue_Fecha);
				//$("#Cue_Hora").val(obj.Cue_Hora);
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Código</th>
                  <th align="center">Proveedor (Razón Social)</th>
                  <th align="center">CUIT</th>
                  <th align="center">Teléfonos</th>
                  <th align="center">Cuentas asociadas</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[Cue_ID];?>
                  <input type="hidden" id="Cue_ID<?php echo $i;?>" value="<?php echo $row[Cue_ID];?>" />
                  <input type="hidden" id="Cue_Nombre<?php echo $i;?>" value="<?php echo $row[Cue_Nombre];?>" />
                  
                 </td>
                 <td align="left"><?php 
                 if (!empty($row[Cue_RazonSocial])) echo "$row[Cue_Nombre] ($row[Cue_RazonSocial])";else echo $row[Cue_Nombre];?></td>
                 <td align="center"><?php echo $row[Cue_CUIT];?></td>
                 <td align="left"><?php echo $row[Cue_Telefono];?></td>
                 <td align="left"><?php 
                 if (!is_null($row[Cue_Cue1]) && $row[Cue_Cue1]>0) echo buscarNombreCuentaContable($row[Cue_Cue1])." (P)<br />";
                 if (!is_null($row[Cue_Cue2]) && $row[Cue_Cue2]!=-1) echo buscarNombreCuentaContable($row[Cue_Cue2])." (S)<br />";
                 if (!is_null($row[Cue_Cue3]) && $row[Cue_Cue3]!=-1) echo buscarNombreCuentaContable($row[Cue_Cue3])." (S)<br />";
                 if (!is_null($row[Cue_Cue4]) && $row[Cue_Cue4]!=-1) echo buscarNombreCuentaContable($row[Cue_Cue4])." (S)<br />";
                 if (!is_null($row[Cue_Cue5]) && $row[Cue_Cue5]!=-1) echo buscarNombreCuentaContable($row[Cue_Cue5])." (S)<br />";
                 ?></td>
                 <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
  </table>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosEgreso_Cuenta() {
	//echo "Hola";exit;
	$Cue_ID = $_POST['Cue_ID'];
	$Cue_Nombre = $_POST['Cue_Nombre'];
	
	$Tabla = "Egreso_Cuenta";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE Cue_Nombre='$Cue_Nombre'";
    $sql = "SELECT * FROM $Tabla WHERE Cue_ID='$Cue_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						

		$datos .= "{\"Cue_ID\": \"" . $row[Cue_ID] . "\",\"";
		$datos .= "Cue_Nombre\": \"" . $row[Cue_Nombre] . "\",\"";
		$datos .= "Cue_Usu_ID\": \"" . $row[Cue_Usu_ID] . "\",\"";
		$datos .= "Cue_Fecha\": \"" . $row[Cue_Fecha] . "\",\"";
        $datos .= "Cue_CUIT\": \"" . $row[Cue_CUIT] . "\",\"";
        $datos .= "Cue_RazonSocial\": \"" . $row[Cue_RazonSocial] . "\",\"";
        $datos .= "Cue_Telefono\": \"" . $row[Cue_Telefono] . "\",\"";
        $datos .= "Cue_Cue1\": \"" . $row[Cue_Cue1] . "\",\"";
        $datos .= "Cue_Cue2\": \"" . $row[Cue_Cue2] . "\",\"";
        $datos .= "Cue_Cue3\": \"" . $row[Cue_Cue3] . "\",\"";
        $datos .= "Cue_Cue4\": \"" . $row[Cue_Cue4] . "\",\"";
        $datos .= "Cue_Cue5\": \"" . $row[Cue_Cue5] . "\",\"";
		$datos .= "Cue_Hora\": \"" . $row[Cue_Hora] . "\"}";
				
		echo $datos;
	}
 }//fin funcion
 
function eliminarEgreso_Cuenta() {
//echo "Hola";exit;
    $Cue_ID = $_POST['Cue_ID'];
	$Cue_Nombre = $_POST['Cue_Nombre'];
	
	$Tabla = "Egreso_Cuenta";

    $sql = "SELECT * FROM $Tabla WHERE Cue_ID='$Cue_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Proveedor elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Egreso_Recibo WHERE Rec_Cue_ID = $Cue_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas			
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Ordenes de pago relacionados.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE Cue_ID = $Cue_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Proveedor seleccionado.";
        }
    }
}//fin function

//********************** Egreso_Recibos ***********************************
function guardarEgreso_Recibo(){	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Rec_Usu_ID, $Rec_Fecha, $Rec_Hora);		
	$CajaID = cajaAbiertaUsuario($Rec_Usu_ID);
	if (!$CajaID){
		echo "Error: La Caja se encuentra cerrada";
		exit;
	}
	$SCC_SCa_ID = obtenerSuperCaja();
	if (!$SCC_SCa_ID){
		echo "No se encuentra abierta la SuperCaja.";
		exit;
	}
	$Rec_ID = $_POST['Rec_ID'];
	$Rec_Cue_ID = $_POST['Rec_Cue_ID'];
	$Rec_FechaCompra = cambiaf_a_mysql($_POST['Rec_FechaCompra']);
	$Rec_Importe = str_replace(",",".",$_POST['Rec_Importe']);
	$Rec_ETi_ID = $_POST['Rec_ETi_ID'];
	$Rec_TipoRecibo = $_POST['Rec_TipoRecibo'];
	$Rec_Numero = $_POST['Rec_Numero'];
	$Rec_FormaPago = $_POST['formaPago'];
	$Rec_RazonSocial = $_POST['txtRazonSocial'];
	$Rec_Detalle = $_POST['txtDetalleRecibo'];
	
	
	$Tabla = "Egreso_Recibo";
	
    $sql = "SELECT * FROM $Tabla WHERE Rec_ID = '$Rec_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Rec_Cue_ID='$Rec_Cue_ID', Rec_FechaCompra='$Rec_FechaCompra', Rec_Usu_ID='$Rec_Usu_ID', Rec_Fecha='$Rec_Fecha', Rec_Hora='$Rec_Hora', Rec_Importe='$Rec_Importe', Rec_ETi_ID='$Rec_ETi_ID', Rec_TipoRecibo='$Rec_TipoRecibo', Rec_Numero='$Rec_Numero', Rec_FormaPago='$Rec_FormaPago', Rec_RazonSocial='$Rec_RazonSocial', Rec_Detalle='$Rec_Detalle'  WHERE Rec_ID = '$Rec_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  
	  $sql = "INSERT INTO $Tabla (Rec_ID, Rec_Cue_ID, Rec_FechaCompra, Rec_Usu_ID, Rec_Fecha, Rec_Hora, Rec_Importe, Rec_ETi_ID, Rec_TipoRecibo, Rec_Numero, Rec_RazonSocial, Rec_Detalle, Rec_FormaPago) VALUES('$Rec_ID', '$Rec_Cue_ID', '$Rec_FechaCompra', '$Rec_Usu_ID', '$Rec_Fecha', '$Rec_Hora', '$Rec_Importe', '$Rec_ETi_ID', '$Rec_TipoRecibo', '$Rec_Numero', '$Rec_RazonSocial', '$Rec_Detalle', '$Rec_FormaPago')";
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  
  $sql = "SELECT * FROM Egreso_Cuenta WHERE Cue_ID = '$Rec_Cue_ID'";
  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  if (mysqli_num_rows($result) > 0){
  	$row = mysqli_fetch_array($result);
	$Concepto = "Cuenta: ".$row['Cue_Nombre'];
	$Detalle = "Recibo: $Rec_Numero. $Rec_RazonSocial/$Rec_Detalle";
	$CCCID = guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $Rec_Importe, 1, $Detalle);
	$SCC_FechaHora = "$Rec_Fecha $Rec_Hora";
	$sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CajaID, $CCCID, $SCC_SCa_ID, 'PAGOS $Concepto', $Rec_Importe, 0, '$SCC_FechaHora', '$Detalle', $Rec_Usu_ID)";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $SCC_ID = $res['id'];
    }else{
        echo "Mal.";
        exit;
    }
	actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);
  }
  echo $mensaje;
        
}//fin function
function guardarAdelantoEgreso_Recibo(){
	
	//Adelanto de Sueldo
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Rec_Usu_ID, $Rec_Fecha, $Rec_Hora);		
	$CajaID = cajaAbiertaUsuario($Rec_Usu_ID);
	if (!$CajaID){
		echo "Error: La Caja se encuentra cerrada";
		exit;
	}
	$SCC_SCa_ID = obtenerSuperCaja();
	if (!$SCC_SCa_ID){
		echo "No se encuentra abierta la SuperCaja.";
		exit;
	}
	$Rec_ID = $_POST['Rec_ID'];
	$Rec_Cue_ID = $_POST['Rec_Cue_ID'];
	$Rec_FechaCompra = cambiaf_a_mysql($_POST['Rec_FechaCompra2']);
	$Rec_Importe = str_replace(",",".",$_POST['Rec_Importe2']);
	$Rec_ETi_ID = $_POST['Rec_ETi_ID'];
	$Rec_TipoRecibo = $_POST['Rec_TipoRecibo'];
	$Rec_Numero = $_POST['Rec_Numero'];
	$Detalle = $_POST['Detalle'];
	$textoDetalle = $_POST['textoDetalle'];
	
	$Tabla = "Egreso_Recibo";
	
    $sql = "SELECT * FROM $Tabla WHERE Rec_ID = '$Rec_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Rec_Cue_ID='$Rec_Cue_ID', Rec_FechaCompra='$Rec_FechaCompra', Rec_Usu_ID='$Rec_Usu_ID', Rec_Fecha='$Rec_Fecha', Rec_Hora='$Rec_Hora', Rec_Importe='$Rec_Importe', Rec_ETi_ID='$Rec_ETi_ID', Rec_TipoRecibo='$Rec_TipoRecibo', Rec_Numero='$Rec_Numero' WHERE Rec_ID = '$Rec_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  
	  $sql = "INSERT INTO $Tabla (Rec_ID, Rec_Cue_ID, Rec_FechaCompra, Rec_Usu_ID, Rec_Fecha, Rec_Hora, Rec_Importe, Rec_ETi_ID, Rec_TipoRecibo, Rec_Numero) VALUES('$Rec_ID', '$Rec_Cue_ID', '$Rec_FechaCompra', '$Rec_Usu_ID', '$Rec_Fecha', '$Rec_Hora', '$Rec_Importe', '$Rec_ETi_ID', '$Rec_TipoRecibo', '$Rec_Numero')";
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  
  $sql = "SELECT * FROM Egreso_Cuenta WHERE Cue_ID = '$Rec_Cue_ID'";
  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  if (mysqli_num_rows($result) > 0){
  	$row = mysqli_fetch_array($result);
	$Concepto = "Cuenta: ".$row['Cue_Nombre'];
	$Detalle = "Adelanto: $textoDetalle para $Detalle";
	$CCCID = guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $Rec_Importe, 1, $Detalle);
	$SCC_FechaHora = "$Rec_Fecha $Rec_Hora";
	$sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CajaID, $CCCID, $SCC_SCa_ID, 'ADELANTO SUELDO $Concepto', '$Rec_Importe', 0, '$SCC_FechaHora', '$Detalle', $Rec_Usu_ID)";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $SCC_ID = $res['id'];
    }else{
        echo "Mal";
    }

	actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);
  }
  echo $mensaje;
        
}//fin function
function buscarEgreso_Recibo(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Egreso_Recibo";
	$IDTabla = "Rec_ID";
	
	$texto = $_POST['Texto'];
	$Anio = $_POST['Anio'];
	$TipoEgreso = $_POST['TipoEgreso'];
	$where = " WHERE YEAR(Rec_FechaCompra)=$Anio";
	if ($TipoEgreso!=999999) $where .= " AND Rec_ETi_ID = $TipoEgreso";
	
	$sql = "SELECT Cue_ID, Cue_Nombre, SUM(Rec_Importe)AS Importe FROM
    Egreso_Recibo INNER JOIN Egreso_Cuenta ON (Rec_Cue_ID = Cue_ID) INNER JOIN Usuario ON (Rec_Usu_ID = Usu_ID) $where GROUP BY Cue_ID ORDER BY Cue_Nombre, Rec_FechaCompra ";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
     //echo $sql;
    if (mysqli_num_rows($result) > 0){
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){
	
	/*$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		DCu_Lec_ID = $("#DCu_Lec_ID" + i).val();
		DCu_Doc_ID = $("#DCu_Doc_ID" + i).val();
		DCu_Cur_ID = $("#DCu_Cur_ID" + i).val();
		DCu_Div_ID = $("#DCu_Div_ID" + i).val();
		
		//alert(vCliID);
		buscarDatos(Cue_ID, Cue_Nombre);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		//tituloRecibo = $("#tituloRecibo").html();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle: 'Listado de Ordenes de Pago',overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
		});
		
		$("#cargando").hide();
	 });//fin evento click//*/
	
	 $("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'Ordenes de Pago', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
	 
	 $("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	
	 
	 $("a[id^='link']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(4,100);
		
		/*<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();*/
		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {i: i},
				url: 'mostrarRecibosDetalle.php',
				success: function(data){ 
					 mostrarAlertaGrande(data, 'Detalle de los recibos');
					 //jAlert(data, 'Detalle de los recibos');
					
				}
			});//fin ajax//*/ 
	
	 });//fin evento click//*/	
	 $("a[id^='verCuenta']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,100);
		
		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {i: i},
				url: 'mostrarRecibosDetalleCompleto.php',
				success: function(data){ 
					 $("#mostrarResultado").html(data);
					 //mostrarAlertaGrande(data, 'Detalle de los recibos');
					 //jAlert(data, 'Detalle de los recibos');
					
				}
			});//fin ajax//*/ 
	
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Rec_ID, Anio){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Rec_ID: Rec_ID, Anio: Anio}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Rec_ID").val(obj.Rec_ID);
				$("#Rec_Cue_ID").val(obj.Rec_Cue_ID);
				$("#Rec_FechaCompra").val(obj.Rec_FechaCompra);
				$("#Rec_Usu_ID").val(obj.Rec_Usu_ID);
				$("#Rec_Fecha").val(obj.Rec_Fecha);
				$("#Rec_Hora").val(obj.Rec_Hora);
				$("#Rec_Importe").val(obj.Rec_Importe);
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">
                  
                  
                  <th align="center">Proveedor</th>
                  
                  <th align="center">Importe Anual</th>
                  <?php
				  global $gMes;
                  $sql = "SELECT MONTH(Rec_FechaCompra)AS Mes FROM Egreso_Recibo $where GROUP BY MONTH(Rec_FechaCompra) ORDER BY Mes";
				  $result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                  //echo $sql;
    			  if (mysqli_num_rows($result2) > 0){
					  $iMes = 0;
				  	while ($row2 = mysqli_fetch_array($result2)){
						$iMes++;
						$Meses[$iMes] = $row2['Mes'];
						$textoSuma = "cont".$Meses[$iMes];
						$$textoSuma = 0;
						echo '<th align="center">'.$gMes[$row2['Mes']].'</th>';
					}//fin while
				  }//FIN IF
				  ?>
                  
                  
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">
                <td><?php echo '<a href="#" id="verCuenta'.$row['Cue_ID'].'">'.$row['Cue_Nombre'].'</a>';?></td>                 
                 <!-- <td align="center"><?php echo $row['Rec_ID'];?>
                  <input type="hidden" id="Rec_ID<?php echo $i;?>" value="<?php echo $row['Rec_ID'];?>" />
                  <input type="hidden" id="Rec_FechaCompra<?php echo $i;?>" value="<?php echo $row['Rec_FechaCompra'];?>" />
                  <input type="hidden" id="Rec_Importe<?php echo $i;?>" value="<?php echo $row['Rec_Importe'];?>" />-->
                 </td>
                 
                 <td align="center"><?php echo number_format($row['Importe'],2,',','.');?></td>
                 
                 <?php
                 	for ($k = 1; $k <= $iMes; $k++){
						/*$sql = "SELECT SUM(Rec_Importe)AS Importe FROM
    Egreso_Recibo INNER JOIN Egreso_Cuenta ON (Rec_Cue_ID = Cue_ID) $where AND Cue_Nombre = '".addslashes($row['Cue_Nombre'])."' AND MONTH(Rec_FechaCompra) = $Meses[$k] ";*/
                        $sql = "SELECT SUM(Rec_Importe)AS Importe FROM
    Egreso_Recibo INNER JOIN Egreso_Cuenta ON (Rec_Cue_ID = Cue_ID) $where AND Cue_ID = $row[Cue_ID] AND MONTH(Rec_FechaCompra) = $Meses[$k] ";
				 		$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
						if (mysqli_num_rows($result2) > 0){
							$row2 = mysqli_fetch_array($result2);
							if ($row2['Importe']>0){
								$link = $Meses[$k]."-".$row['Cue_ID']."-$Anio";
								if ($row['Cue_Nombre']!="BANCO CTA CTEXXXX"){
									$textoSuma = "cont".$Meses[$k];
									$$textoSuma += $row2['Importe'];
								}
								echo '<td align="right"><a href="#" id="link'.$link.'">'.number_format($row2['Importe'],2,',','.').'</a></td>';
							}else{
								echo '<td align="right">----</td>';
							}
						}else{
							echo '<td align="right">----</td>';
						}
					}//fin for
				 ?>
                 
                 <!--<td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>-->
                </tr>
              
              <?php
			  
			  
				}//fin while
				echo "<tr><td colspan='2' align='right'><strong>TOTALES MENSUALES</strong></td>";
			  for ($k = 1; $k <= $iMes; $k++){
						
					$textoSuma = "cont".$Meses[$k];					
					echo '<td align="right"><strong>'.number_format($$textoSuma,2,',','.').'</strong></td>';
							
					}//fin for
				echo "</tr>";
			  ?>
            </tbody>
  </table>
 
<?php  $sql = "SELECT SUM(Rec_Importe)AS Importe FROM
    Egreso_Recibo $where ";
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result2) > 0){
		$row2 = mysqli_fetch_array($result2); 
		echo "Total Anual: $".number_format($row2[Importe],2,',','.');
	}//fin if
?>
 <a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
  <form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosEgreso_Recibo() {
	//echo "Hola";exit;
	$Rec_ID = $_POST['Rec_ID'];
	$Anio = $_POST['Anio'];
	
	$Tabla = "Egreso_Recibo";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE YEAR(Rec_FechaCompra)='$Anio'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						

		$datos .= "{\"Rec_ID\": \"" . $row[Rec_ID] . "\",\"";
		$datos .= "Rec_Cue_ID\": \"" . $row[Rec_Cue_ID] . "\",\"";
		$datos .= "Rec_FechaCompra\": \"" . $row[Rec_FechaCompra] . "\",\"";
		$datos .= "Rec_Usu_ID\": \"" . $row[Rec_Usu_ID] . "\",\"";
		$datos .= "Rec_Fecha\": \"" . $row[Rec_Fecha] . "\",\"";
		$datos .= "Rec_Hora\": \"" . $row[Rec_Hora] . "\",\"";
		$datos .= "Rec_Importe\": \"" . $row[Rec_Importe] . "\"}";
				
		echo $datos;
	}
 }//fin funcion
 
function eliminarEgreso_Recibo() {
//echo "Hola";exit;
    $Rec_ID = $_POST['Rec_ID'];
	$Rec_FechaCompra = $_POST['Rec_FechaCompra'];
	
	obtenerRegistroUsuario($Rec_Usu_ID, $Rec_Fecha, $Rec_Hora);		
	$CajaID = cajaAbiertaUsuario($Rec_Usu_ID);
	if (!$CajaID){
		echo "Error: La Caja se encuentra cerrada";
		exit;
	}
	$SCC_SCa_ID = obtenerSuperCaja();
	if (!$SCC_SCa_ID){
		echo "Error: No se encuentra abierta la SuperCaja.";
		exit;
	}
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Egreso_Recibo";

    $sql = "SELECT * FROM $Tabla WHERE Rec_ID = '$Rec_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "Error: El Recibo elegido no existe o ya fue eliminado.";
    } else {
        	$row = mysqli_fetch_array($result);
			$Rec_Cue_ID = $row['Rec_Cue_ID'];
			$Rec_Importe = $row['Rec_Importe'];
			$Rec_Numero = $row['Rec_Numero'];
			$sql = "SELECT * FROM Egreso_Cuenta WHERE Cue_ID = '$Rec_Cue_ID'";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			if (mysqli_num_rows($result) > 0){
			  $row = mysqli_fetch_array($result);
			  $Concepto = "Anulación Cuenta: ".$row['Cue_Nombre'];
			  $Detalle = "Recibo: $Rec_Numero";
			  $CCCID = guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $Rec_Importe, 1, $Detalle);
			  $SCC_FechaHora = "$Rec_Fecha $Rec_Hora";
			  $sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CajaID, $CCCID, $SCC_SCa_ID, 'ANULACIÓN PAGOS $Concepto', 0, $Rec_Importe, '$SCC_FechaHora', '$Detalle', $Rec_Usu_ID)";
                $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
                if ($res["success"] == true){
                    $SCC_ID = $res['id'];
                }else{
                    echo "Mal";
                }
			  
			  actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);
			}
            $sql = "DELETE FROM $Tabla WHERE Rec_ID = $Rec_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Recibo seleccionado. Actualice para ver los cambios";
        
    }
	
}//fin function

//********************** Libro ***********************************
function guardarLibro(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Rec_Usu_ID, $Rec_Fecha, $Rec_Hora);	
	$Lib_ID = $_POST['Lib_ID'];
	$Lib_Cur_ID = $_POST['Lib_Cur_ID'];
	$Lib_Nombre = strtoupper($_POST['Lib_Nombre']);
	$Lib_Editorial = strtoupper($_POST['Lib_Editorial']);
	$Lib_Detalle = $_POST['Lib_Detalle'];
	$Lib_Costo = $_POST['Lib_Costo'];
	$Lib_Venta = $_POST['Lib_Venta'];
	$Lib_Stock = $_POST['Lib_Stock'];
	
	$Tabla = "Libro";
	
    $sql = "SELECT * FROM $Tabla WHERE Lib_ID = '$Lib_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET Lib_Cur_ID='$Lib_Cur_ID', Lib_Nombre='$Lib_Nombre', Lib_Editorial='$Lib_Editorial', Lib_Detalle='$Lib_Detalle', Lib_Costo='$Lib_Costo', Lib_Venta='$Lib_Venta', Lib_Stock='$Lib_Stock' WHERE Lib_ID = '$Lib_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {
	  
	  $sql = "INSERT INTO $Tabla (Lib_ID, Lib_Cur_ID, Lib_Nombre, Lib_Editorial, Lib_Detalle, Lib_Costo, Lib_Venta, Lib_Stock) VALUES('$Lib_ID', '$Lib_Cur_ID', '$Lib_Nombre', '$Lib_Editorial', '$Lib_Detalle', '$Lib_Costo', '$Lib_Venta', '$Lib_Stock')";
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarLibro(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "Libro";
	$IDTabla = "Lib_ID";
	
	$texto = $_POST['Texto'];
	
	if ($texto=="todos") $where = " ";
	
	$sql = "SELECT * FROM Libro INNER JOIN Curso ON (Lib_Cur_ID = Cur_ID)
	$where ORDER BY Lib_Nombre, Lib_Editorial ";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Lib_ID = $("#Lib_ID" + i).val();
		Lib_Nombre = $("#Lib_Nombre" + i).val();
		//alert(vCliID);
		buscarDatos(Lib_ID, Lib_Nombre);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 $("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
	 
	 $("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	
	 
	 $("a[id^='link']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(4,100);
		
		<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {i: i},
				url: 'mostrarRecibosDetalle.php',
				success: function(data){ 
					 jAlert(data, 'Resultado de la eliminación');
					
				}
			});//fin ajax//*/ 
	
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Lib_ID, Lib_Nombre){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Lib_ID: Lib_ID, Lib_Nombre: Lib_Nombre}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Lib_ID").val(obj.Lib_ID);
				$("#Lib_Cur_ID").val(obj.Lib_Cur_ID);
				$("#Lib_Nombre").val(obj.Lib_Nombre);
				$("#Lib_Editorial").val(obj.Lib_Editorial);
				$("#Lib_Detalle").val(obj.Lib_Detalle);
				$("#Lib_Costo").val(obj.Lib_Costo);
				$("#Lib_Venta").val(obj.Lib_Venta);
				$("#Lib_Stock").val(obj.Lib_Stock);
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">

                  <th align="center">Código</th>                                   
                  <th align="center">Título</th>
                  <th align="center">Curso</th>
                  <th align="center">Costo</th>
                  <th align="center">Venta</th>
                  <th align="center">Stock</th>                                  
                  <th align="center">Acciones</th>
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">
                 <td align="center"><?php echo $row[Lib_ID];?>
                 <input type="hidden" id="Lib_ID<?php echo $i;?>" value="<?php echo $row[Lib_ID];?>" />
                 <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Lib_Nombre];?>" />
                 </td>
                 <td><?php echo $row[Lib_Nombre];?></td>  
                 <td><?php echo $row[Cur_Nombre];?></td>                                                  
                 <td align="center"><?php echo number_format($row[Lib_Costo],2,',','.');?></td>
                 <td align="center"><?php echo number_format($row[Lib_Venta],2,',','.');?></td>
                 <td align="center"><?php echo $row[Lib_Stock];?></td>
                 <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
  </table>
 
<?php  echo "Total de ítems: ".mysqli_num_rows($result);
?>
 <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
  <form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function

function buscarDatosLibro() {
	//echo "Hola";exit;
	$Lib_ID = $_POST['Lib_ID'];
	$Lib_Nombre = $_POST['Lib_Nombre'];
	
	$Tabla = "Libro";
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT * FROM $Tabla WHERE Lib_ID='$Lib_ID'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
		echo "{}";
	} else {
		$row = mysqli_fetch_array($result);						

		$datos .= "{\"Lib_ID\": \"" . $row[Lib_ID] . "\",\"";
		$datos .= "Lib_Cur_ID\": \"" . $row[Lib_Cur_ID] . "\",\"";
		$datos .= "Lib_Nombre\": \"" . $row[Lib_Nombre] . "\",\"";
		$datos .= "Lib_Editorial\": \"" . $row[Lib_Editorial] . "\",\"";
		$datos .= "Lib_Detalle\": \"" . $row[Lib_Detalle] . "\",\"";
		$datos .= "Lib_Costo\": \"" . $row[Lib_Costo] . "\",\"";
		$datos .= "Lib_Venta\": \"" . $row[Lib_Venta] . "\",\"";
		$datos .= "Lib_Stock\": \"" . $row[Lib_Stock] . "\"}";
				
		echo $datos;
	}
 }//fin funcion
 
function eliminarLibro() {
//echo "Hola";exit;
    $Lib_ID = $_POST['Lib_ID'];
	
	$Tabla = "Libro";

    $sql = "SELECT * FROM $Tabla WHERE Lib_ID = '$Lib_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Libro elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT * FROM LibroVenta WHERE LVe_Lib_ID = '$Lib_ID'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {//no existe
			echo "El Libro elegido no puede eliminarse porque tiene Ventas asociadas.";
		}else{
            $sql = "DELETE FROM $Tabla WHERE Lib_ID = $Lib_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Libro seleccionado.";
		}
    }
}//fin function

//********************** Libro Venta ***********************************
function guardarLibroVenta(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($LVe_Usu_ID, $LVe_Fecha, $LVe_Hora);	
	$LVe_Per_ID = $_POST['LVe_Per_ID'];
	$LVe_Lib_ID = $_POST['LVe_Lib_ID'];
	$LVe_LNu_ID = $_POST['LVe_LNu_ID'];	
	$LVe_Venta = $_POST['LVe_Venta'];
	$LVe_Costo = $_POST['LVe_Costo'];
	
	$Tabla = "LibroVenta";
	
    $sql = "SELECT * FROM $Tabla WHERE LVe_Per_ID='$LVe_Per_ID' AND LVe_Lib_ID='$LVe_Lib_ID'";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
	 $sql = "UPDATE $Tabla SET LVe_LNu_ID='$LVe_LNu_ID', LVe_Fecha='$LVe_Fecha', LVe_Hora='$LVe_Hora', LVe_Usu_ID='$LVe_Usu_ID', LVe_Venta='$LVe_Venta', LVe_Costo='$LVe_Costo' WHERE LVe_Per_ID='$LVe_Per_ID' AND LVe_Lib_ID='$LVe_Lib_ID'";
	 $mensaje = "Se actualizó correctamente los datos";
  } else {	  
	  $sql = "INSERT INTO $Tabla (LVe_Per_ID, LVe_Lib_ID, LVe_LNu_ID, LVe_Fecha, LVe_Hora, LVe_Usu_ID, LVe_Venta, LVe_Costo) VALUES('$LVe_Per_ID', '$LVe_Lib_ID', '$LVe_LNu_ID', '$LVe_Fecha', '$LVe_Hora', '$LVe_Usu_ID', '$LVe_Venta', '$LVe_Costo')";
	  
	  $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarLibroVenta(){
	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Tabla = "LibroVenta";
	$IDTabla = "Lib_ID";
	
	$texto = $_POST['Texto'];
	
	if ($texto=="todos") $where = " ";
	
	$sql = "SELECT * FROM Libro INNER JOIN Curso ON (Lib_Cur_ID = Cur_ID)
	$where ORDER BY Lib_Nombre, Lib_Editorial ";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Lib_ID = $("#Lib_ID" + i).val();
		Lib_Nombre = $("#Lib_Nombre" + i).val();
		//alert(vCliID);
		buscarDatos(Lib_ID, Lib_Nombre);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 $("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
	 
	 $("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	
	 
	 $("a[id^='link']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(4,100);
		
		<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {i: i},
				url: 'mostrarRecibosDetalle.php',
				success: function(data){ 
					 jAlert(data, 'Resultado de la eliminación');
					
				}
			});//fin ajax//*/ 
	
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Lib_ID, Lib_Nombre){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Lib_ID: Lib_ID, Lib_Nombre: Lib_Nombre}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Lib_ID").val(obj.Lib_ID);
				$("#Lib_Cur_ID").val(obj.Lib_Cur_ID);
				$("#Lib_Nombre").val(obj.Lib_Nombre);
				$("#Lib_Editorial").val(obj.Lib_Editorial);
				$("#Lib_Detalle").val(obj.Lib_Detalle);
				$("#Lib_Costo").val(obj.Lib_Costo);
				$("#Lib_Venta").val(obj.Lib_Venta);
				$("#Lib_Stock").val(obj.Lib_Stock);
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">

                  <th align="center">Código</th>                                   
                  <th align="center">Título</th>
                  <th align="center">Curso</th>
                  <th align="center">Costo</th>
                  <th align="center">Venta</th>
                  <th align="center">Stock</th>                                  
                  <th align="center">Acciones</th>
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">
                 <td align="center"><?php echo $row[Lib_ID];?>
                 <input type="hidden" id="Lib_ID<?php echo $i;?>" value="<?php echo $row[Lib_ID];?>" />
                 <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Lib_Nombre];?>" />
                 </td>
                 <td><?php echo $row[Lib_Nombre];?></td>  
                 <td><?php echo $row[Cur_Nombre];?></td>                                                  
                 <td align="center"><?php echo number_format($row[Lib_Costo],2,',','.');?></td>
                 <td align="center"><?php echo number_format($row[Lib_Venta],2,',','.');?></td>
                 <td align="center"><?php echo $row[Lib_Stock];?></td>
                 <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
  </table>
 
<?php  echo "Total de ítems: ".mysqli_num_rows($result);
?>
 <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
  <form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
}//fin function


 
function eliminarLibroVenta() {
//echo "Hola";exit;
    $Lib_ID = $_POST['Lib_ID'];
	
	$Tabla = "LibroVenta";

    $sql = "SELECT * FROM $Tabla WHERE Lib_ID = '$Lib_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Libro elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT * FROM LibroVenta WHERE LVe_Lib_ID = '$Lib_ID'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {//no existe
			echo "El Libro elegido no puede eliminarse porque tiene Ventas asociadas.";
		}else{
            $sql = "DELETE FROM $Tabla WHERE Lib_ID = $Lib_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Libro seleccionado.";
		}
    }
}//fin function

function buscarLibrosTabla() {
//echo "Hola";exit;
    $Lib_Cur_ID = $_POST['Cur_ID'];
	$Per_ID = $_POST[Per_ID];
    $sql = "SELECT * FROM Libro INNER JOIN Curso ON (Lib_Cur_ID = Cur_ID) WHERE Lib_Cur_ID = '$Lib_Cur_ID' ORDER BY Lib_Nombre, Lib_Editorial";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "No existen Libros cargados para ese Curso.";
    } else {
        ?>
        <script>
		$(document).ready(function(){
			$(".botones").button();
			$("a[id^='botReservar']").click(function(evento){											  
				evento.preventDefault();
				var i = this.id.substr(11,10);		
				Lib_ID = $("#Lib_ID" + i).val();
				Lib_Nombre = $("#Lib_Nombre" + i).val();
				//alert(vCliID);
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "reservarLibro", Lib_ID: Lib_ID, Per_ID: <?php echo $Per_ID;?>},
					url: 'cargarOpciones.php',
					success: function(data){ 
						jAlert(data);						
						//$("#mostrarResultado").html(data);
						//$("#loading").hide();
					}
				});//fin ajax//*/
			 });//fin evento click//*/	
			$("a[id^='botEntregar']").click(function(evento){											  
				evento.preventDefault();
				var i = this.id.substr(11,10);		
				Lib_ID = $("#Lib_ID" + i).val();
				Lib_Nombre = $("#Lib_Nombre" + i).val();
				//alert(vCliID);
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "entregarLibro", Lib_ID: Lib_ID, Per_ID: <?php echo $Per_ID;?>},
					url: 'cargarOpciones.php',
					success: function(data){ 
						jAlert(data);						
						//$("#mostrarResultado").html(data);
						//$("#loading").hide();
					}
				});//fin ajax//*/
			 });//fin evento click//*/	
			$("input[id^='Lib_ID']").click(function(evento){
				//evento.preventDefault();
				i = this.id.substr(6,10);
				
				//vCuota = $("#Nuevo" + i).val();
				vImporte = parseFloat($("#Lib_Venta" + i).val());
				//alert(vImporte);
				vTotal = parseFloat($("#totalPagar").val());
				vCantLibros = parseFloat($("#CantLibros").val());
				if (this.checked){
					vTotal += parseFloat(vImporte);
					vCantLibros += 1;
				}else{
					vTotal -= parseFloat(vImporte);
					vCantLibros -= 1;
				}
				$("#totalPagar").val(vTotal);
				$("#total").text("$" + vTotal);
				$("#CantLibros").val(vCantLibros);
			 });//fin evento click//*/
			$("#botArmar").click(function(evento){
				evento.preventDefault();
				totalPagar = $("#totalPagar").val();
				if (totalPagar.length==0 || totalPagar=="" || totalPagar==0){
					jAlert("Debe seleccionar los libros primero","Atención");
					return;
				}
				//alert(totalPagar);
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {totalPagar: totalPagar, Per_ID: <?php echo $Per_ID;?>},
					url: 'MostrarAsignarCuotasLibro.php',
					success: function(data){ 
						$("#mostrarVenta").html(data);
					}
				});//fin ajax//*/
			 });//fin evento click//*/
		
		});//fin dom ready
		</script>
        <form id="formDatosLibro" method="post" action="">
        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">

                  <th align="center">Código</th>                                   
                  <th align="left">Título</th>
                  <th align="left">Detalle</th>
                  <th align="center">Venta</th>
                  <th align="center">Stock</th>                                  
                  <th align="center">Acciones</th>
                </tr>
  </thead>
                <tbody>
                <?php
				
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
					$saltar = false;					
					
					$sql = "SELECT * FROM LibroVenta WHERE LVe_Per_ID = $Per_ID AND LVe_Lib_ID = $row[Lib_ID]";
					$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    				if (mysqli_num_rows($result2) > 0) {
						$clase = "vendido";
						$saltar = true;
					}//fin if
					
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">
                 <td align="center">                 
                 <?php
                 if (!$saltar){
				 ?>
                 <input type="checkbox" id="Lib_ID<?php echo $i;?>" value="<?php echo $row[Lib_ID];?>" />
                 <?php
				 }else{
					?>
                 <input type="hidden" id="Lib_ID<?php echo $i;?>" value="<?php echo $row[Lib_ID];?>" />   
                    <?php 
				 }//fin if
				 ?>
                 <input type="hidden" id="Lib_Venta<?php echo $i;?>" value="<?php echo $row[Lib_Venta];?>" />
                 <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Lib_Nombre];?>" />
                 </td>
                 <td><?php echo "$row[Lib_Nombre] ($row[Lib_Editorial])";?></td>  
                 <td><?php echo $row[Lib_Detalle];?></td>  
                 <td align="center"><?php echo number_format($row[Lib_Venta],2,',','.');?></td>
                 <td align="center"><?php echo $row[Lib_Stock];?></td>
                 <td align="center">
                 	<?php 
					$sql = "SELECT * FROM LibroEncargado WHERE LEn_Per_ID = $Per_ID AND LEn_Lib_ID = $row[Lib_ID]";
					$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    				if (mysqli_num_rows($result2) > 0) {
						$row2 = mysqli_fetch_array($result2);
						if (empty($row2[LEn_FechaEntregado])){
							echo "Libro pedido / No entregado todavía";
							echo "<a href='#' id='botEntregar".$i."'><img src='imagenes/report_user.png' alt='Entregar Libro' title='Entregar Libro' width='32' height='32' border='0' style='vertical-align:middle'/></a>";
						}else{
							echo "Libro pedido / Entregado: ".cfecha($row2[LEn_FechaEntregado])." $row2[LEn_HoraEntregado]";
						}
					}else{//fin if
					  ?>
                    <a href="#" id="botReservar<?php echo $i;?>"><img src="imagenes/report_go.png" alt="Pedir Libro" title="Pedir Libro" width="32" height="32" border="0" style="vertical-align:middle" /></a> 
                    
                    <?php
					}//fin if
					?>
                 </td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
  </table>
  <div align="center" style="font-family: Arial, Helvetica, sans-serif;font-size: 14pt;font-weight: bold;color: #000;background-color: #FF9;border: 1px solid #000;">
   <input type="hidden" id="totalPagar" value="0" />
    <input type="hidden" id="CantLibros" value="0" />
   Total: <span id="total"></span> &nbsp;&nbsp;&nbsp;<button id="botArmar" class="botones">Financiado</button><!--<button id="botContado" class="botones">Contado</button>-->
  </div>
        <div id="mostrarVenta"></div>
        </form>
        <?php
    }
}//fin function

function reservarLibro() {
//echo "Hola";exit;
    $Lib_ID = $_POST['Lib_ID'];
	$Per_ID = $_POST['Per_ID'];
	obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora);	
	
    $sql = "SELECT * FROM LibroEncargado WHERE LEn_Lib_ID = '$Lib_ID' AND LEn_Per_ID = $Per_ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//no existe
        echo "Este libro ya se encuentra reservado.";
    } else {
        $sql = "INSERT INTO LibroEncargado (LEn_Per_ID, LEn_Lib_ID, LEn_Fecha, LEn_Hora, LEn_Usu_ID) VALUES($Per_ID, $Lib_ID, '$Fecha', '$Hora', '$Usu_ID')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se reservó correctamente el Libro";
    }
}//fin function
function entregarLibro() {
//echo "Hola";exit;
    $Lib_ID = $_POST['Lib_ID'];
	$Per_ID = $_POST['Per_ID'];
	obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora);	
	
    $sql = "SELECT * FROM LibroEncargado WHERE LEn_Lib_ID = '$Lib_ID' AND LEn_Per_ID = $Per_ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "Este libro no se encuentra reservado. Primero se debe reservar antes de entregarlo";
    } else {
        $sql = "UPDATE LibroEncargado SET LEn_FechaEntregado = '$Fecha', LEn_HoraEntregado = '$Hora', LEn_UsuEntregado = '$Usu_ID' WHERE LEn_Per_ID = $Per_ID AND LEn_Lib_ID = $Lib_ID";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se entregó correctamente el Libro";
    }
}//fin function
function mostrarVistaPreviaLibro(){
	$PerID = $_POST['PerID'];
	//echo "PerID ".$PerID."<br />";
	$CMo_CantCuotas = $_POST['CMo_CantCuotas'];
	//echo "CMo_CantCuotas ".$CMo_CantCuotas."<br />";
	$CMo_Importe = $_POST['CMo_Importe'];
	$CMo_Importe = $CMo_Importe / $CMo_CantCuotas;
	
	//echo "CMo_Importe ".$CMo_Importe."<br />";
	$CMo_1er_Vencimiento = $_POST['CMo_1er_Vencimiento'];
	//echo "CMo_1er_Vencimiento ".$CMo_1er_Vencimiento."<br />";
	$CMo_Mes = $_POST['CMo_Mes'];
	//echo "CMo_Mes ".$CMo_Mes."<br />";
	$CMo_Anio = $_POST['CMo_Anio'];
	//echo "CMo_Anio ".$CMo_Anio."<br />";
	$CMo_Recargo_Mensual = $_POST['CMo_Recargo_Mensual'];
	//echo "CMo_Recargo_Mensual ".$CMo_Recargo_Mensual."<br />";
	
	
	
	$sql2="SELECT 	* 	FROM Persona WHERE Per_ID=$PerID;";
	$result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
	$row2 = mysqli_fetch_array($result2);
	$Per_Nombre=$row2[Per_Nombre];
	$Per_Apellido=$row2[Per_Apellido];
	$_SESSION['sesion_ultimoDNI'] = $row2[Per_DNI];
	
	
	
	?>
    
    <script type="text/javascript">
	$.validator.setDefaults({
		submitHandler: function() { 
			
			datos = $("#formDatosLibro").serialize();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
					url: 'cargarOpciones.php',
				data: datos,
				success: function (data){
					jAlert(data, "Resultado de guardar los datos");
				}
			});//fin ajax
		}
	});
	$(document).ready(function(){
		/*total=0;
		importe=$("#CMo_Importe"+i).val();
		importe=parseFloat(importe);*/
	
		$(".tiempoIMporte").keydown(function(){
			setTimeout(function() {
   // alert("asdempoIMpasd");
	 var suma=0;
	 CMo_CantCuotas=parseFloat($('#CMo_CantCuotas').val());
	//asd=$('#CMo_Importe'+1).val();
	//alert(asd);
	 for(j=1;j<=CMo_CantCuotas;j++)
	 {
		 importe=parseFloat($('#CMo_Importe'+j).val());
		 // alert(importe)
		 if(!isNaN(importe))
		 {
		 suma+=importe
		 }
		
	 }
	$("#total").empty();
	$("#total").html(suma);
	//alert(suma);
	 
	 
	 
}, 500);
			})
		
$(".botones").button();

$("#GuardarVistaPrevia").click(function(evento) {

	   evento.preventDefault();
		/*a = $("#totalPagar").val();
		alert(a);
		return;*/
		
		//1) Registrar el número de venta
		//Bucamos el último número de venta y le sumamos uno
		ventaNumero = 0;
		$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,			
			  url: 'cargarOpciones.php',
			  data: {opcion:"BuscarLibroVentaNumero", Per_ID: <?php echo $PerID;?>},
			  success: function (data){
				  //alert(data);return;
				  ventaNumero = data
  
			}
		})//fin ajax
		
		//2) Registra la venta libro
		
		$("input[id^='Lib_ID']:checked").each(function( index ){
				
			i = this.id.substr(6,10);
			
			LVe_Lib_ID = $(this).val();			
			LVe_Venta = parseFloat($("#Lib_Venta" + i).val());
			LVe_Costo = parseFloat($("#Lib_Costo" + i).val());
			//alert("Venta Numero: " + ventaNumero);
			$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,			
			  url: 'cargarOpciones.php',
			  data: {opcion:"guardarLibroVenta", LVe_Lib_ID: LVe_Lib_ID, LVe_Venta: LVe_Venta, LVe_Costo: LVe_Costo, LVe_Per_ID: <?php echo $PerID;?>, LVe_LNu_ID: ventaNumero},
			  success: function (data){
				  //alert("Guardar: "+ data);
				}
			})//fin ajax
				
		});//fin evento click//*/
		
		//3) Registrar las cuotas
		
	   CMo_CantCuotas=$('#CMo_CantCuotas').val();
	   PerID=$('#PerID').val();	   
	   CMo_1er_Vencimiento=$('#CMo_1er_Vencimiento').val();
	   CMo_Mes=$('#CMo_Mes').val();
	   CMo_Anio=$('#CMo_Anio').val();
	   CMo_Recargo_Mensual=$('#CMo_Recargo_Mensual').val();
	//return false;
	var miArray = new Array() 
	var i=0;
		 for(j=1;j<=CMo_CantCuotas;j++)
	 {
		 
		importe2=parseFloat($('#CMo_Importe'+j).val());
		if(!isNaN(importe2))
		{
			 miArray[i]=importe2;
			 //alert(miArray[i])
			 i++;
		}
		 
	 }
	//$("#formDatos").submit();
	//alert(ventaNumero);
	$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
					data: {opcion:"GuardarVistaPreviaLibro",CMo_CantCuotas:CMo_CantCuotas,PerID:PerID,CMo_1er_Vencimiento:CMo_1er_Vencimiento,CMo_Mes:CMo_Mes,CMo_Anio:CMo_Anio,CMo_Recargo_Mensual:CMo_Recargo_Mensual,miArray:miArray, LCu_LNu_ID: ventaNumero},
                    success: function (data){
                        jAlert(data, "Resultado de la operación");
                        $("#cargando").hide();
						//recargarPagina();
		
		}
		})//fin ajax
	
	
	
	});
	
	 function recargarPagina(){
            $("#mostrar").empty();

            $.ajax({
                cache: false,
                async: false,			
                url: "cargarCuotasImpagas.php",
                success: function (data){
                    $("#principal").html(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        }//fin function
	
	})
	</script>
   
    <input type="hidden" name="CMo_CantCuotas" id="CMo_CantCuotas" value="<?php echo $CMo_CantCuotas ?>" />
     <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
    <input type="hidden" name="CMo_1er_Vencimiento" id="CMo_1er_Vencimiento" value="<?php echo $CMo_1er_Vencimiento ?>" />
    
     <input type="hidden" name="CMo_Mes" id="CMo_Mes" value="<?php echo $CMo_Mes ?>" />
     
      <input type="hidden" name="CMo_Anio" id="CMo_Anio" value="<?php echo $CMo_Anio ?>" />
      
       <input type="hidden" name="CMo_Recargo_Mensual" id="CMo_Recargo_Mensual" value="<?php echo $CMo_Recargo_Mensual ?>" />
    
       <style type="text/css">
   tr{
	  border:#000 solid 1px; border-collapse:collapse 
   }
   </style>
    
    
    <table  id="tablaPreviaCuotas" width="100%" align="center" style="border:#000 solid 1px; border-collapse:collapse">
   <tr height="60px" >
        <td colspan="6" align="center">		
		<div align="center" class="titulo_noticia"> Vista Previa de la financiación</div></td>
      </tr>
   
   <tr align="center" class="titulo_noticia"><td>Nº de Cuotas</td><td>Mes</td><td>Año</td><td>Fecha Vencimiento</td><td>Recargo</td><td>Importe</td></tr>
   
   
   <?php
   $total=0;
   for($i=1;$i<=$CMo_CantCuotas;$i++)
   {
	   $total+=$CMo_Importe;
   ?>
   
    <tr align="center"><td><?php echo $i ?></td><td><?php echo buscarMes($CMo_Mes) ?></td><td><?php echo $CMo_Anio ?></td><td><?php echo $CMo_1er_Vencimiento ?></td><td><?php echo "$".$CMo_Recargo_Mensual ?></td><td>$<input type="text" name="CMo_Importe<?php echo $i ?>" id="CMo_Importe<?php echo $i ?>" value="<?php echo $CMo_Importe ?>" size="10" maxlength="10" style=" text-align:center" class="tiempoIMporte" /></td>
    
    </tr>
   <?php
   $CMo_1er_Vencimiento=cambiaf_a_mysql($CMo_1er_Vencimiento);
   $CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
   $CMo_1er_Vencimiento=cfecha($CMo_1er_Vencimiento);
   
   //$CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
   if($CMo_Mes=='12')
   {
	   $CMo_Mes=1;
	   $CMo_Anio++;
	   
   }
   else
   {
	   $CMo_Mes++;
   }
   }
   ?>

   <tr>
   <td  colspan="5" align="right" class="titulo_noticia">TOTAL:</td> <td align="center"><div class="titulo_noticia" id="total">$<?php echo $total ?></div></td>
   </tr>
     <tr><td colspan="6" align="center" style="padding-top:25px;"><button class="botones" id="GuardarVistaPrevia">Guardar</button></td></tr>
    </table>
    <br />
<br />

		<?php	
    }//fin function
    
    function GuardarVistaPreviaLibro()
    {
        
        $miArray = $_POST['miArray'];
        $PerID = $_POST['PerID'];
		$LCu_LNu_ID = $_POST['LCu_LNu_ID'];
		
        //echo "PerID ".$PerID."<br />";
        $CMo_CantCuotas = $_POST['CMo_CantCuotas'];
        //echo "CMo_CantCuotas ".$CMo_CantCuotas."<br />";
        //$CMo_Importe = $_POST['CMo_Importe'];
        //echo "CMo_Importe ".$CMo_Importe."<br />";
        $CMo_1er_Vencimiento = $_POST['CMo_1er_Vencimiento'];
		$CMo_1er_Vencimiento=cambiaf_a_mysql($CMo_1er_Vencimiento);
        //echo "CMo_1er_Vencimiento ".$CMo_1er_Vencimiento."<br />";
        $CMo_Mes = $_POST['CMo_Mes'];
        //echo "CMo_Mes ".$CMo_Mes."<br />";
        $CMo_Anio = $_POST['CMo_Anio'];
        //echo "CMo_Anio ".$CMo_Anio."<br />";
        $CMo_Recargo_Mensual = $_POST['CMo_Recargo_Mensual'];
        //echo "CMo_Recargo_Mensual ".$CMo_Recargo_Mensual."<br />";
        
		Obtener_LectivoActual($LCu_Lec_ID, $Lec_Nombre);
        $sql2="SELECT MAX(LCu_Numero) AS LCu_Numero FROM 
        LibroCuotaPersona  WHERE LCu_Per_ID='$PerID' AND LCu_Lec_ID='$LCu_Lec_ID' AND LCu_LNu_ID='$LCu_LNu_ID';";
		//echo $sql2;
        $result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
        //***
         if (mysqli_num_rows($result2) == 0){
		 	$LCu_Numero=1;					
			
		 }else{
			$row2 = mysqli_fetch_array($result2);
		 	$LCu_Numero=$row2[LCu_Numero]+1;			
		 }
		 
        
                 
        obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
        //return false;
         for($i=0;$i<count($miArray);$i++)
       {
          		   
          $sql = "INSERT INTO LibroCuotaPersona (LCu_Per_ID, LCu_Lec_ID, LCu_LNu_ID, LCu_Numero, LCu_Fecha, LCu_Hora, LCu_Usu_ID, LCu_Importe, LCu_Vencimiento, LCu_Recargo, LCu_Mes, LCu_Anio, LCu_Pagado, LCu_Anulado) VALUES('$PerID', '$LCu_Lec_ID', '$LCu_LNu_ID', '$LCu_Numero', '$Fecha', '$Hora', '$Usu_ID', '$miArray[$i]', '$CMo_1er_Vencimiento', '$CMo_Recargo_Mensual', '$CMo_Mes', '$CMo_Anio', 0, 0)";
		  
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$LCu_Numero++;
		//echo $sql."<br />";
         
        // $CMo_Mes++; 
		
         
 
       $CMo_1er_Vencimiento=date("Y-m-d", strtotime("$CMo_1er_Vencimiento +1 month"));
      // $CMo_1er_Vencimiento=cfecha($CMo_1er_Vencimiento); 
           
		   $Cuo_Numero++;
		   
		     if($CMo_Mes=='12')
   {
	   $CMo_Mes=1;
	   $CMo_Anio++;
	   
   }
   else
   {
	   $CMo_Mes++;
   } 
		   
		   
		   
       }//fin for
       
	   echo "Guardado Correctamente";
	   
         
         
        
}//fin function

function BuscarLibroVentaNumero() {
	//echo "Hola";exit;
	
	$Per_ID = $_POST[Per_ID];
	$sql = "INSERT INTO LibroVentaNumero (LNu_Per_ID) VALUES($Per_ID)";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        echo $res['id'];
    }else{
        echo "Mal";
    }
	
	
	
}//fin funcion


function buscarLibroCuotaPersona() {
//echo "Hola";exit;
    $Lec_ID = $_POST['Lec_ID'];
	$Per_ID = $_POST[Per_ID];
	$Deuda = Obtener_Deuda_Libros($Per_ID);
    $sql = "SELECT * FROM LibroCuotaPersona INNER JOIN Lectivo ON (LCu_Lec_ID = Lec_ID) WHERE LCu_Per_ID = '$Per_ID' AND LCu_Lec_ID = $Lec_ID ORDER BY LCu_Pagado, LCu_Vencimiento";
	//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "No existen Cuotas generadas para la persona.";
    } else {
        ?>
  <script>
		$(document).ready(function(){
			$(".botones").button();
			$("a[id^='botAnular']").click(function(evento){											  
				evento.preventDefault();
				var i = this.id.substr(9,10);		
				Anular = $("#Anular" + i).val();
				
				//alert(vCliID);
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "eliminarLibroCuotaPago", Anular: Anular},
					url: 'cargarOpciones.php',
					success: function(data){ 
						jAlert(data);	
						$.ajax({
							type: "POST",
							cache: false,
							async: false,
							error: function (XMLHttpRequest, textStatus){
								alert(textStatus);},
							data: {opcion: "buscarLibroCuotaPersona", Lec_ID: <?php echo $Lec_ID;?>, Per_ID: <?php echo $Per_ID;?>},
							url: 'cargarOpciones.php',
							success: function(data){ 
								//alert(data);
								$("#mostrarResultado").html(data);
								//$("#loading").hide();
							}
						});//fin ajax//*/						
						//$("#mostrarResultado").html(data);
						//$("#loading").hide();
					}
				});//fin ajax//*/
			 });//fin evento click//*/	
			$("input[id^='Numero']").click(function(evento){
				//evento.preventDefault();
				i = this.id.substr(6,10);
				
				//vCuota = $("#Nuevo" + i).val();
				vImporte = parseFloat($("#Importe" + i).val());
				//alert(vImporte);
				vTotal = parseFloat($("#totalPagar").val());
				vCantLibros = parseFloat($("#CantLibros").val());
				if (this.checked){
					vTotal += parseFloat(vImporte);
					vCantLibros += 1;
				}else{
					vTotal -= parseFloat(vImporte);
					vCantLibros -= 1;
				}
				$("#totalPagar").val(vTotal);
				$("#paraPagar").val(vTotal);
				$("#total").text("$" + vTotal);
				$("#CantLibros").val(vCantLibros);
			 });//fin evento click//*/
			$("#botPagar").click(function(evento){
				evento.preventDefault();
				totalPagar = parseFloat($("#totalPagar").val());
				if (totalPagar.length==0 || totalPagar=="" || totalPagar==0){
					jAlert("Debe seleccionar por lo menos una Cuota antes de realizar el Pago","Atención");
					return;
				}
				paraPagar = parseFloat($("#paraPagar").val());
				//alert(paraPagar);
				if (paraPagar.length==0 || paraPagar=="" || paraPagar==0 || paraPagar>totalPagar){
					jAlert("El importe a Pagar no puede estar vacío, ser cero, negativo o mayor al total a pagar","Atención");
					return;
				}
				saldo = paraPagar;
				Rec_Persona = $("#persona").val();
				$.ajax({
					  type: "POST",
					  cache: false,
					  async: false,			
					  url: 'cargarOpciones.php',
					  data: {opcion:"GenerarLibroReciboPago", Rec_Persona: Rec_Persona, Rec_ImporteTotal: saldo},
					  success: function (data){
						  //alert("Recibo 1: "+ data);
						  LCP_Rec_ID = data;
						}
					})//fin ajax
				$("input[id^='Numero']:checked").each(function( index ){
				
					i = this.id.substr(6,10);
					
					//i = $(this).val();			
					Importe = parseFloat($("#Importe" + i).val());
					LCu_LNu_ID = $("#LCu_LNu_ID" + i).val();
					LCu_Numero = $("#LCu_Numero" + i).val();
					//alert("Venta Numero: " + ventaNumero);
					//alert("Recibo 2: "+ LCP_Rec_ID);
					$.ajax({
					  type: "POST",
					  cache: false,
					  async: false,			
					  url: 'cargarOpciones.php',
					  data: {opcion:"guardarLibroCuotaPago", LCP_LNu_ID: LCu_LNu_ID, LCP_Numero: LCu_Numero, LCP_Lec_ID: <?php echo $Lec_ID;?>, LCP_Per_ID: <?php echo $Per_ID;?>, saldo: saldo, LCP_Rec_ID: LCP_Rec_ID},
					  success: function (data){
						  //alert("Guardar: "+ data);
						  //alert(data);
						  saldo = data;
						}
					})//fin ajax
						
				});//fin evento click//*/
				
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "buscarLibroCuotaPersona", Lec_ID: <?php echo $Lec_ID;?>, Per_ID: <?php echo $Per_ID;?>},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//alert(data);
						$("#mostrarResultado").html(data);
						//$("#loading").hide();
					}
				});//fin ajax//*/	
				
			 });//fin evento click//*/
		
		});//fin dom ready
		</script>
        <?php
        if ($Deuda>0){
		gObtenerApellidoNombrePersona($Per_ID, $usuario_nombre, $usuario_apellido, true);
	?>
<link href="css/general.css" rel="stylesheet" type="text/css" />

		<br /><div class="borde_alerta" align="center">
		  <p class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Al día de la fecha <?php echo $fechaHoy;?> el alumno <strong><?php echo "$usuario_nombre $usuario_apellido";?></strong> tiene una Deuda de Libros vencida de $<?php echo $Deuda;?>.</p></div><br />
          <?php
		}//fin if
		  ?>
        <form id="formDatosLibro" method="post" action="" autocomplete="OFF">
        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">

                  <th align="center">#</th>                                   
                  <th align="left">Cuota</th>
                  <th align="left">Lectivo</th>
                  <th align="center">Vencimiento</th>
                  <th align="center">Importe</th>
                  <th align="center">Saldo</th>                                  
                  <th align="center">Detalles</th>
                </tr>
  </thead>
                <tbody>
                <?php
				global $gMes;
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
					//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
					$datosCuota = $row[LCu_Lec_ID].";".$row[LCu_Per_ID].";".$row[LCu_LNu_ID].";".$row[LCu_Numero];
					//Calculamos el importe que deber�a pagar al d�a de hoy
					$importe = $row[LCu_Importe];
					//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
					$importeOriginal = $importe;
					recalcularImporteCuotaLibro($datosCuota, $importe);
					//if ($row[LCu_Pagado]==1) $importe = buscarPagosTotalesLibro($datosCuota);
					//if ($row[LCu_Pagado]==1) $importe = 0;
					$fechaCuota = cfecha($row[LCu_Vencimiento]);
					$clase = "vencida_roja";
					$fechaHoy = date("d-m-Y");
					$ya_vencida=1;
					$fecha = restarFecha($fechaCuota, $fechaHoy);
					$fechaCuota2 = $row[LCu_Vencimiento];
					$fechaHoy2 = date("Y-m-d");
					$mesesAtrazo = 0;
					if ( $fecha > 0 ){
												
						$ya_vencida=1;
					}else{
						if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
					}	
					if ($row[LCu_Pagado]==1) $clase = "cuota_pagada";
					if ($row[LCu_Anulado]==1) $clase = "cuota_anulada";
				
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">
                 <td align="center">                 
                 <?php
                 if ($row[LCu_Pagado]==0){
				 ?>
                 <input type="checkbox" id="Numero<?php echo $i;?>" value="<?php echo $i;?>" />
                 <?php
				 }else{
					?>
                 <input type="hidden" id="Numero<?php echo $i;?>" value="<?php echo $i;?>" />   
                    <?php 
				 }//fin if
				 ?>
                 <input type="hidden" id="LCu_LNu_ID<?php echo $i;?>" value="<?php echo $row[LCu_LNu_ID];?>" />
                 <input type="hidden" id="LCu_Numero<?php echo $i;?>" value="<?php echo $row[LCu_Numero];?>" />
                 <input type="hidden" id="Importe<?php echo $i;?>" value="<?php echo $importe;?>" class="botonesCampos" />
                 </td>
                 <td><?php echo $gMes[$row[LCu_Mes]]."/$row[LCu_Anio]";?></td>  
                 <td><?php echo $row[Lec_Nombre];?></td>  
                 <td align="center"><?php echo cfecha($row[LCu_Vencimiento]);?></td>
                 <td align="center"><?php echo number_format($row[LCu_Importe],2,',','.');?></td>
                 <td align="center"><?php echo number_format($importe,2,',','.');?></td>
                 <td align="left">
                 <?php
                 //Buscamos los pagos parciales
				 $sql2 = "SELECT * FROM LibroCuotaPago INNER JOIN Usuario ON LCP_Usu_ID = Usu_ID WHERE LCP_Per_ID='$Per_ID' AND LCP_Lec_ID='$Lec_ID' AND LCP_LNu_ID='$row[LCu_LNu_ID]' AND LCP_Numero='$row[LCu_Numero]'";
				 $result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
   			 	 if (mysqli_num_rows($result2) > 0) {
				 	$j=0;
					while ($row2=mysqli_fetch_array($result2)){
				 		$j++;
						if ($row2[LCP_Anulado]==1){
						?><img src="imagenes/bullet_delete.png" title="Anulado" width="32" height="32" border="0" style="vertical-align:middle; alignment-baseline:middle" /><?php
						}else{
						?><img src="imagenes/bullet_blue.png" title="Pagado" width="32" height="32" border="0" style="vertical-align:middle; alignment-baseline:middle"/><?php
						}
						echo cfecha($row2[LCP_Fecha])." $row2[LCP_Date] $row2[Usu_Nombre] $row2[LCP_Importe]";
				 if ($row2[LCP_Anulado]==0){
				 ?>                    
                    <a href="#" id="botAnular<?php echo $i.$j;?>"><img src="imagenes/table_delete.png" alt="Anular pago" title="Anular pago" width="32" height="32" border="0" style="vertical-align:middle; alignment-baseline:middle"/></a> 
                    <input type="hidden" id="Anular<?php echo $i.$j;?>" value="<?php echo $datosCuota.";$row2[LCP_Orden]";?>" />
                  <?php
				 }//fin if de mostrar Anular
				 echo "<br />";
					}//fin while
				 }//fin if
				  ?>  
                 </td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
  </table>
  <div align="center" style="font-family: Arial, Helvetica, sans-serif;font-size: 14pt;font-weight: bold;color: #000;background-color: #FF9;border: 1px solid #000;">
   <input type="hidden" id="totalPagar" value="0" />
    <input type="hidden" id="CantLibros" value="0" />
   Total: <span id="total"></span> &nbsp;&nbsp;&nbsp;<button id="botPagar" class="botones">Pagar</button><input type="text" id="paraPagar" value="0" size="10" maxlength="4" />
  </div>
        <div id="mostrarVenta"></div>
        
  </form>
  <h2>Recibos generados de la persona
</h2>
<table width="90%" class="tabla" align="center">
  <tr class="fila_titulo">
    <th scope="col">#</th>
    <th scope="col">Fecha</th>
    <th scope="col">Usuario</th>
    <th scope="col">Importe</th>
    <th scope="col">Acciones</th>
  </tr>
  <?php
  $sql = "SELECT DISTINCTROW LibroReciboPago.* FROM LibroReciboPago
    INNER JOIN LibroCuotaPago 
        ON (Rec_ID = LCP_Rec_ID) WHERE LCP_Per_ID = $Per_ID ORDER BY Rec_ID DESC";
  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  $j=0;
  while ($row = mysqli_fetch_array($result)){
	  if ($j%2==0) $clase = "fila";else $clase = "fila2";
	  $j++;
  ?>
  <tr class="<?php echo $clase;?>">
    <td align="center"><?php echo $row[Rec_ID];?></td>
    <td align="center"><?php echo cfecha($row[Rec_Fecha])." ".$row[Rec_Hora];?></td>
    <td align="center"><?php echo gbuscarPersonaUsuID($row[Rec_Usu_ID]);?></td>
    <td align="right"><?php echo $row[Rec_ImporteTotal];?></td>
    <td align="center"><a href="imprimirReciboLibro.php?id=<?php echo $row[Rec_ID];?>" target="_blank"><img src="imagenes/printer.png" alt="Imprimir recibo" title="Imprimir recibo" width="32" height="32" border="0" style="vertical-align:middle; alignment-baseline:middle"/></a></td>
  </tr>

        <?php
  	}//fin while
		?></table>
        <?php
    }
}//fin function

function GenerarLibroReciboPago() {
	//echo "Hola";exit;
	
	$Rec_Persona = $_POST['Rec_Persona'];
	$Rec_ImporteTotal = $_POST['Rec_ImporteTotal'];
	obtenerRegistroUsuario($Rec_Usu_ID, $Rec_Fecha, $Rec_Hora);
	$sql = "INSERT INTO LibroReciboPago (Rec_Fecha, Rec_Hora, Rec_Usu_ID, Rec_Persona, Rec_ImporteTotal) VALUES('$Rec_Fecha', '$Rec_Hora', '$Rec_Usu_ID', '$Rec_Persona', '$Rec_ImporteTotal')";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        echo $res['id'];
    }else{
        echo "Mal";
    }
	
}//fin funcion

function guardarLibroCuotaPago(){
	
	
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora);	
	$LCP_Per_ID = $_POST['LCP_Per_ID'];
	$LCP_Lec_ID = $_POST['LCP_Lec_ID'];
	$LCP_LNu_ID = $_POST['LCP_LNu_ID'];
	$LCP_Numero = $_POST['LCP_Numero'];
	$LCP_Rec_ID = $_POST['LCP_Rec_ID'];
	$saldo = $_POST['saldo'];
	
	//echo "Recibo 3: $LCP_Rec_ID";
	
	$Tabla = "LibroCuotaPago";
	
    //Priemro buscamos el valor total de la cuota sin pagar
	$sql = "SELECT * FROM LibroCuotaPersona WHERE LCu_Per_ID='$LCP_Per_ID' AND LCu_Lec_ID='$LCP_Lec_ID' AND LCu_LNu_ID='$LCP_LNu_ID' AND LCu_Numero='$LCP_Numero' AND LCu_Pagado = 0 AND LCu_Anulado = 0";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Importe = $row[LCu_Importe];
		$sql = "SELECT SUM(LCP_Importe) AS Total, MAX(LCP_Orden) AS Orden FROM $Tabla WHERE LCP_Per_ID='$LCP_Per_ID' AND LCP_Lec_ID='$LCP_Lec_ID' AND LCP_LNu_ID='$LCP_LNu_ID' AND LCP_Numero='$LCP_Numero' AND LCP_Anulado = 0";
		//echo $sql;
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {
			 $row = mysqli_fetch_array($result);
			 $totalPagado = $row[Total];
			 $Orden = $row[Orden];
			 $Orden++;
			 if ($Importe>$totalPagado){
			 	if ($saldo>0){
					$faltaPagar = $Importe - $totalPagado;
					if ($saldo<=$faltaPagar){						
						$importePagar = $saldo;
						$saldo = 0;
					}else{
						$importePagar = $faltaPagar;
						$saldo = $saldo - $faltaPagar;
						
					}					
					$sql = "INSERT INTO LibroCuotaPago (LCP_Per_ID, LCP_Lec_ID, LCP_LNu_ID, LCP_Numero, LCP_Orden, LCP_Fecha, LCP_Date, LCP_Usu_ID, LCP_Importe, LCP_Anulado, LCP_Rec_ID) VALUES('$LCP_Per_ID', '$LCP_Lec_ID', '$LCP_LNu_ID', '$LCP_Numero', '$Orden', '$Fecha', '$Hora', '$Usu_ID', '$importePagar', '0', '$LCP_Rec_ID')";
				}
			 }
		 
	  } else {	  
		  $faltaPagar = $Importe;
		  if ($saldo<=$faltaPagar){			  
			  $importePagar = $saldo;
			  $saldo = 0;
		  }else{
			  $importePagar = $faltaPagar;
			  $saldo = $saldo - $faltaPagar;			  
		  }	
		  //Se va a registra el primer pago
		  $sql = "INSERT INTO LibroCuotaPago (LCP_Per_ID, LCP_Lec_ID, LCP_LNu_ID, LCP_Numero, LCP_Orden, LCP_Fecha, LCP_Date, LCP_Usu_ID, LCP_Importe, LCP_Anulado, LCP_Rec_ID) VALUES('$LCP_Per_ID', '$LCP_Lec_ID', '$LCP_LNu_ID', '$LCP_Numero', '1', '$Fecha', '$Hora', '$Usu_ID', '$importePagar', '0', '$LCP_Rec_ID')";
	  }
	  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}else{
		//hubo un error. La cuota ya fue abonada o anulada o no existe
	}	
	$datosCuota = $LCP_Lec_ID.";".$LCP_Per_ID.";".$LCP_LNu_ID.";".$LCP_Numero;
	if (calcularPagoTotalCuotaLibro($datosCuota, $debe)){
		//Actualizo la cuota como pagada si los pagos parciales cubren el total del importe		
		$sql = "UPDATE LibroCuotaPersona SET LCu_Pagado = 1 WHERE LCu_Per_ID='$LCP_Per_ID' AND LCu_Lec_ID='$LCP_Lec_ID' AND LCu_LNu_ID='$LCP_LNu_ID' AND LCu_Numero='$LCP_Numero'";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
  	echo $saldo;
        
}//fin function

function eliminarLibroCuotaPago() {
//echo "Hola";exit;
    $datosCuota = $_POST['Anular'];
	list( $LCP_Lec_ID, $LCP_Per_ID, $LCP_LNu_ID, $LCP_Numero, $LCP_Orden ) = explode(";", $datosCuota);
	
	$Tabla = "LibroCuotaPago";

    $sql = "SELECT * FROM $Tabla WHERE LCP_Per_ID='$LCP_Per_ID' AND LCP_Lec_ID='$LCP_Lec_ID' AND LCP_LNu_ID='$LCP_LNu_ID' AND LCP_Numero='$LCP_Numero' AND LCP_Orden='$LCP_Orden' ";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El pago elegido no existe o ya fue eliminado.";
    } else {
            $sql = "UPDATE $Tabla SET LCP_Anulado = 1 WHERE LCP_Per_ID='$LCP_Per_ID' AND LCP_Lec_ID='$LCP_Lec_ID' AND LCP_LNu_ID='$LCP_LNu_ID' AND LCP_Numero='$LCP_Numero' AND LCP_Orden='$LCP_Orden'";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			$datosCuota = $LCP_Lec_ID.";".$LCP_Per_ID.";".$LCP_LNu_ID.";".$LCP_Numero;
			//if (!calcularPagoTotalCuotaLibro($datosCuota, $debe)){
			calcularPagoTotalCuotaLibro($datosCuota, $debe);
			//echo "Debe: $debe<br />";
			if ($debe>0){	
				//Actualizo la cuota como pagada si los pagos parciales cubren el total del importe		
				$sql = "UPDATE LibroCuotaPersona SET LCu_Pagado = 0 WHERE LCu_Per_ID='$LCP_Per_ID' AND LCu_Lec_ID='$LCP_Lec_ID' AND LCu_LNu_ID='$LCP_LNu_ID' AND LCu_Numero='$LCP_Numero'";
				//echo $sql;				
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			}
            echo "Se eliminó el Pago seleccionado.";
		
    }
}//fin function

function obtenerCuadroAutoriza(){
    $EMoID = $_POST['EMoID'];
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM EmpleadoMovimiento
     INNER JOIN Usuario 
        ON (EMo_Usu_Autoriza = Usu_ID)
 WHERE EMo_ID = $EMoID;";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
            echo "<li>$row[Acc_Hora]: <strong>$row[Men_Nombre]</strong>->$row[Opc_Nombre]</li>";
        
    } else {
        echo "No existen datos cargados.";
    }
}

function importarEmpleadoDocente(){
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT Per_ID,
        UPPER(Per_Apellido) AS Per_Apellido
        , UPPER(Per_Nombre) AS Per_Nombre 
    FROM
        Colegio_Docente
        INNER JOIN Persona 
            ON (Colegio_Docente.Doc_Per_ID = Persona.Per_ID) WHERE Doc_Activo=1;";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora); 
    while ($row = mysqli_fetch_array($result)){
        
        $Persona = $row['Per_Apellido'].",".$row['Per_Nombre'];

        /*$sql = "INSERT IGNORE INTO Empleado (Emp_Per_ID, Emp_ETi_ID, Emp_Persona, Emp_Fecha, Emp_Hora, Emp_Usu_ID) VALUES($row[Per_ID], 1, '$Persona', '$Fecha', '$Hora', $Usu_ID)";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);*/


        $sql = "SELECT * FROM Empleado WHERE Emp_Per_ID = $row[Per_ID]";
        $result_buscar = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result_buscar) == 0) {
            $sql = "INSERT IGNORE INTO Empleado (Emp_Per_ID, Emp_ETi_ID, Emp_Persona, Emp_Fecha, Emp_Hora, Emp_Usu_ID) VALUES($row[Per_ID], 1, '$Persona', '$Fecha', '$Hora', $Usu_ID)";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);   
        }
        //echo $Persona." - cargado<br>";
        

    }//fin del while
    echo "Se actualizaron los Docentes en el Control de Empleados.";
}

function eliminarDocentesDuplicados(){
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT Emp_Per_ID FROM Empleado GROUP BY Emp_Per_ID HAVING COUNT(*)>1;";

    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    //obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora); 
    while ($row = mysqli_fetch_array($result)){        
        
        $sql = "SELECT * FROM Empleado WHERE Emp_Per_ID = $row[Emp_Per_ID] ORDER BY Emp_ID";//echo $sql; exit;
        $result_buscar = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        //$total = mysqli_num_rows($result_buscar);
        $i = 1;
        if (mysqli_num_rows($result_buscar) > 0) {
            while ($row_buscar = mysqli_fetch_array($result_buscar)){
                if ($i>1){
                    $sql = "DELETE FROM Empleado WHERE Emp_ID = $row_buscar[Emp_ID]";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                }
                $i++;
            }    
           
        
        }
        

    }//fin del while
    echo "Se eliminaron las Personas duplicadas en el Control de Empleados.";
}//fin function


//Eze
function guardarLectivo() {
    $LecID = $_POST['LecID'];
    $Ciclo = $_POST['Ciclo'];
    $Desde = $_POST['Desde'];
    $Hasta = $_POST['Hasta'];
    $Actual = $_POST['Actual'];

    $sql = "SELECT * FROM Lectivo WHERE Lec_ID = '$LecID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($Actual) {
        $sql = "UPDATE Lectivo SET Lec_Actual = 0";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    }
    if (mysqli_num_rows($result) > 0) {//ya existe
        $sql = "UPDATE Lectivo SET Lec_Nombre = '$Ciclo', Lec_Desde = '$Desde', Lec_Hasta = '$Hasta', Lec_Actual = '$Actual' WHERE Lec_ID = $LecID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se modificaron los valores del ciclo <strong>$Ciclo</strong>";
//echo $sql;
    } else {
        $sql = "INSERT INTO Lectivo (Lec_Nombre, Lec_Desde, Lec_Hasta, Lec_Actual) VALUES ('$Ciclo', '$Desde', '$Hasta', '$Actual')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se agrego correctamente el ciclo <strong>$Ciclo</strong>";
    }

} //fin funcion

function eliminarLectivo() {
    $LecID = $_POST['LecID'];
    $Ciclo = $_POST['Ciclo'];

    $sql = "SELECT * FROM Lectivo WHERE Lec_ID = '$LecID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "No se puedo eliminar";                           
    }
    else {
        $sql = "DELETE FROM Lectivo WHERE Lec_ID = '$LecID' AND Lec_Nombre = '$Ciclo'";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se eliminó correctamente el ciclo <strong>$Ciclo</strong>";
    }
}

function guardarCurso() {

    $CurID = $_POST['CurID'];
    $CurNivID = $_POST['CurNivID'];
    $CurNombre = $_POST['CurNombre'];
    $CurSiglas = $_POST['CurSiglas'];
    $CurTurno = $_POST['CurTurno'];
    $CurCurso = $_POST['CurCurso'];

    $CurNombre =trim(utf8_decode($CurNombre));
    $CurTurno = trim(utf8_decode($CurTurno));
    $CurSiglas =trim(utf8_decode($CurSiglas));

    $sql = "SELECT * FROM Curso WHERE Cur_ID = '$CurID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe
        $sql = "UPDATE Curso SET Cur_Niv_ID = '$CurNivID', Cur_Nombre = '$CurNombre', Cur_Siglas = '$CurSiglas', Cur_Turno = '$CurTurno', Cur_Curso = '$CurCurso', Cur_Colegio = '$CurColegio'  WHERE Cur_ID = $CurID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se modificaron los valores del curso <strong>$CurNombre</strong>";
    } else {
        $sql = "INSERT INTO Curso (Cur_Niv_ID, Cur_Nombre, Cur_Siglas, Cur_Turno, Cur_Curso, Cur_Colegio) VALUES ('$CurNivID', '$CurNombre', '$CurSiglas', '$CurTurno', '$CurCurso', '$CurColegio')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "<br/> Se agrego correctamente el curso <strong>$CurNombre</strong>";
    }
} //fin funcion

function eliminarCurso() {
    $CurID = $_POST['CurID'];
    $CurNombre = $_POST['CurNombre'];

    $sql = "SELECT * FROM Curso WHERE Cur_ID = '$CurID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "No se puedo eliminar";                           
    }
    else {
        $sql = "DELETE FROM Curso WHERE Cur_ID = '$CurID' AND Cur_Nombre = '$CurNombre'";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se eliminó correctamente el curso <strong>$CurNombre</strong>";
    }
} //fin funcion

function guardarDivision() {

    $DivID = $_POST['DivID'];
    if ($DivID == '') {
        $DivID = '-1';
    }
    $DivNombre = $_POST['DivNombre'];
    $DivSiglas = $_POST['DivSiglas'];
    $DivNombre =trim(utf8_decode($DivNombre));
    $DivSiglas = trim(utf8_decode($DivSiglas));

    $sql = "SELECT * FROM Division WHERE Div_ID = '$DivID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe
        $sql = "UPDATE Division SET Div_Nombre = '$DivNombre', Div_Siglas = '$DivSiglas' WHERE Div_ID = $DivID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se modificaron los valores de la division <strong>$DivNombre</strong>";
    } else {
        $sql = "INSERT INTO Division (Div_Nombre, Div_Siglas) VALUES ('$DivNombre', '$DivSiglas')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "<br/> Se agrego correctamente la division <strong>$DivNombre</strong>";
    }
} //fin funcion

function eliminarDivision() {
    $DivID = $_POST['DivID'];
    $DivNombre = $_POST['DivNombre'];
    $DivNombre =trim(utf8_decode($DivNombre));

    $sql = "SELECT * FROM Division WHERE Div_ID = '$DivID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "No se puedo eliminar";                           
    }
    else {
        $sql = "DELETE FROM Division WHERE Div_ID = '$DivID' AND Div_Nombre = '$DivNombre'";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se eliminó correctamente la division <strong>$DivNombre</strong>";
    }
} //fin funcion

function guardarTipoEmpleado() {

    $ETiID = $_POST['ETiID'];
    $ETiNombre = $_POST['ETiNombre'];
    $ETiSiglas = $_POST['ETiSiglas'];
    $ETiNombre =trim(utf8_decode($ETiNombre));
    $ETiSiglas = trim(utf8_decode($ETiSiglas));

    $sql = "SELECT * FROM EmpleadoTipo WHERE ETi_ID = '$ETiID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe
        $sql = "UPDATE EmpleadoTipo SET ETi_Nombre = '$ETiNombre', ETi_Siglas = '$ETiSiglas' WHERE ETi_ID = $ETiID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se modificaron los valores de <strong>$ETiNombre</strong>";
    } else {
        $sql = "INSERT INTO EmpleadoTipo (ETi_Nombre, ETi_Siglas) VALUES ('$ETiNombre', '$ETiSiglas')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "<br/> Se agrego correctamente <strong>$ETiNombre</strong>";
    }
} //fin funcion

function eliminarTipoEmpleado() {
    $ETiID = $_POST['ETiID'];
    $ETiNombre = $_POST['ETiNombre'];
    $ETiNombre =trim(utf8_decode($ETiNombre));

    $sql = "SELECT * FROM EmpleadoTipo WHERE ETi_ID = '$ETiID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "No se puedo eliminar";                           
    }
    else {
        $sql = "DELETE FROM EmpleadoTipo WHERE ETi_ID = '$ETiID' AND ETi_Nombre = '$ETiNombre'";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se eliminó correctamente <strong>$ETiNombre</strong>";
    }
} //fin funcion

function guardarCuota() {
    $CTiID = $_POST['CTiID'];
    if ($CTiID == '') {
        $CTiID = '-1';
    }
    $CTiNombre = $_POST['CTiNombre'];
    $CTiNombre =trim(utf8_decode($CTiNombre));
    $CTiModo = $_POST['CTiModo'];
    $CTiModo2 = '';
    if ($CTiModo == 'CTi_Factura') {
        $CTiModo2 = 'CTi_Recibo';
    }
    else {
        $CTiModo2 = 'CTi_Factura';
    }

    $sql = "SELECT * FROM CuotaTipo WHERE CTi_ID = '$CTiID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe
        $sql = "UPDATE CuotaTipo SET CTi_Nombre = '$CTiNombre', $CTiModo = 1, $CTiModo2 = 0 WHERE CTi_ID = $CTiID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se modificaron los valores de la cuota <strong>$CTiNombre</strong>";
    } else {
        $sql = "INSERT INTO CuotaTipo (CTi_Nombre, $CTiModo, $CTiModo2) VALUES ('$CTiNombre', 1, 0)";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se agrego correctamente la cuota <strong>$CTiNombre</strong>";
    }

} //fin funcion

function eliminarCuota() {
    $CTiID = $_POST['CTiID'];
    $CTiNombre = $_POST['CTiNombre'];
    $CTiNombre =trim(utf8_decode($CTiNombre));

    $sql = "SELECT * FROM CuotaTipo WHERE CTi_ID = '$CTiID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "No se puedo eliminar";                           
    }
    else {
        $sql = "DELETE FROM CuotaTipo WHERE CTi_ID = '$CTiID' AND CTi_Nombre = '$CTiNombre'";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se eliminó correctamente la cuota <strong>$CTiNombre</strong>";
    }
}

function Editar_Factura() {

    ?>
    <script type="text/javascript">

    function recargarPagina(){
        $("#mostrar").empty();

        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcionnum: "persona", per_ID: vPerID, opcionvol: 1},
            url: 'listadoFactura.php',
            success: function(data){ 
                $("#mostrar").html(data);
            }
        });//fin ajax//*/
    }//fin function

        $("#button_editarfac").click(function(evento){

            vFac_ID_n = $("#Fac_ID_n").val();
            vFac_Sucursal_n = $("#Fac_Sucursal_n").val();
            vFac_Numero_n = $("#Fac_Numero_n").val();
            vFac_Tipo_n = $("#Fac_Tipo_n").val();

            $.ajax({
                url: 'cargarOpciones.php',
                type: "POST",
                async: false,
                data: {opcion: "Guardar_Factura", Fac_ID_n: vFac_ID_n, Fac_Sucursal_n: vFac_Sucursal_n, Fac_Numero_n: vFac_Numero_n, Fac_Tipo_n: vFac_Tipo_n},
                success: function (data){
                        jAlert(data, "Resultado de la operacion");
                        $("#editfac").hide();
                        recargarPagina();
                        $("#cargando").hide();
                    }
            });//fin ajax//*/
        });

    </script>
    <?php

    $Fac_ID = $_POST['Fac_ID'];

    $sql = "SELECT * FROM Factura WHERE Fac_ID = $Fac_ID";

    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    while ($row = mysqli_fetch_array($result)) {
    ?>

    <fieldset class="recuadro_simple" id="factura_edit" style=" width: 95%;">
        <legend>Editar Factura</legend>
        <br />
        <br />
        <table width="80%" border="0" align="center" class="borde_recuadro">
          
            <tr>
              <td align="right" class="texto" width="30%">Tipo</td>
              <td><label>
                <?php cargarListaTipoFactura('Fac_Tipo_n'); ?>
              </label></td>
              <td rowspan="3" width="30%"><label> 
                <button name="button_editarfac" id="button_editarfac">Guardar</button>
              </label></td>
            </tr>
            <tr>
              <td align="right" class="texto" width="30%">Sucursal </td>
              <td><label>
                <input name="Fac_ID_n" type="hidden" id="Fac_ID_n" value="<?php echo $row[Fac_ID]; ?>" />
                <input name="Fac_Sucursal_n" type="text" id="Fac_Sucursal_n" size="40" value="<?php echo $row[Fac_Sucursal]; ?>"/>
              </label></td>
            </tr>
            <tr>
              <td align="right" class="texto" width="30%">Factura </td>
              <td><label>
                <input name="Fac_Numero_n" type="text" id="Fac_Numero_n" size="40" value="<?php echo $row[Fac_Numero]; ?>"/>
              </label></td>
            </tr>
        </table>
        
    </fieldset>

    <?php
    }
}//fin funcion

function Guardar_Factura() {

    $Fac_ID_n = $_POST['Fac_ID_n'];
    $Fac_Sucursal_n = $_POST['Fac_Sucursal_n'];
    $Fac_Numero_n = $_POST['Fac_Numero_n'];
    $Fac_Tipo_n = $_POST['Fac_Tipo_n'];

    $sql1 = "SELECT * FROM Factura WHERE Fac_ID = $Fac_ID_n";
    $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
    $sql2 = "SELECT * FROM Factura WHERE Fac_Numero = '$Fac_Numero_n' AND Fac_Sucursal = '$Fac_Sucursal_n' AND Fac_FTi_ID = '$Fac_Tipo_n'";
    $result2 = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);

    if ((mysqli_num_rows($result1) > 0) && (mysqli_num_rows($result2) == 0)) {//existe id pero no factura
        $sql = "UPDATE Factura SET Fac_Sucursal = '$Fac_Sucursal_n', Fac_Numero = '$Fac_Numero_n', Fac_FTi_ID = '$Fac_Tipo_n' WHERE Fac_ID = $Fac_ID_n";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se modificaron los valores de la factura a <strong>$Fac_Sucursal_n-$Fac_Numero_n</strong>";
    } else {
        echo "No se puede editar";
    }

}//fin funcion

function guardarDebito() {

    $consulta = $_POST['consulta'];
    $consulta = stripslashes($consulta);
	consulta_mysql_2022($consulta,basename(__FILE__),__LINE__);

}

function buscarClasesAlumno () {
    $DNI = trim($_POST['DNI']);
    $Lec_ID = $_POST['LecID'];
    $_SESSION['sesion_ultimoDNI'] = $DNI;

    ?>
    <script language="javascript">
        $("a[id^='botBorrar']").click(function(evento){                                         
            evento.preventDefault();
            var i = this.id.substr(9,10);
            vIMaLegID = $("#IMaLegID" + i).val();
            vIMaLecID = $("#IMaLecID" + i).val();
            vIMaClaID = $("#IMaClaID" + i).val();
            
            jConfirm('¿Est&aacute; seguro que desea eliminar ?', 'Confirmar la eliminaci&oacute;n', function(r){
                if (r){//eligi� eliminar
                    $.post("cargarOpciones.php", { opcion: "eliminarClasesAlumno", IMaLegID: vIMaLegID, IMaLecID: vIMaLecID, IMaClaID: vIMaClaID }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
                        $("#filaClAlum" + i).hide();
                    });//fin post                   
                }//fin if
            });//fin del confirm//*/
        
        });//fin evento click//*/
    </script>
    <table width="95%" border="0" align="center" cellpadding="1" cellspacing="1" id="tablaClAlum">
        <tr class="fila_titulo">
            <th>Materia</th>
            <th>Profesor</th>
            <th>Acción</th>
        </tr>
    <?php

    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $sql = "SELECT *
            FROM
                Colegio_Inscripcion
                INNER JOIN Colegio_InscripcionClase 
                    ON (Ins_Leg_ID = IMa_Leg_ID) AND (Ins_Lec_ID = IMa_Lec_ID)
                INNER JOIN Colegio_Clase 
                    ON (Cla_ID = IMa_Cla_ID)
                INNER JOIN Colegio_Docente 
                    ON (Doc_ID = Cla_Doc_ID)
                INNER JOIN Colegio_Nivel 
                    ON (Niv_ID = Cla_Niv_ID)
                INNER JOIN Curso 
                    ON (Cla_Cur_ID = Cur_ID)
                INNER JOIN Colegio_Materia 
                    ON (Cla_Mat_ID = Mat_ID)
                INNER JOIN Division 
                    ON (Cla_Div_ID = Div_ID)
                INNER JOIN Legajo 
                    ON (Leg_ID = Ins_Leg_ID)
                INNER JOIN Persona 
                    ON (Per_ID = Leg_Per_ID) WHERE Per_DNI = $DNI AND IMa_Lec_ID = $Lec_ID ORDER BY Mat_Orden;";//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $apeProf = '';
        $nomProf = '';
        gObtenerApellidoNombrePersona($row[Doc_Per_ID], $apeProf, $nomProf, true);
        ?>

        <tr id="filaClAlum<?php echo $i;?>">
        <td>
        <input name="IMaLegID" type="hidden" id="IMaLegID<?php echo $i;?>" value="<?php echo $row[IMa_Leg_ID]; ?>" />
        <input name="IMaLecID" type="hidden" id="IMaLecID<?php echo $i;?>" value="<?php echo $row[IMa_Lec_ID]; ?>" />
        <input name="IMaClaID" type="hidden" id="IMaClaID<?php echo $i;?>" value="<?php echo $row[IMa_Cla_ID]; ?>" />
        <?php echo $row[Mat_Nombre]; ?> </td>
        <td><?php echo $apeProf.", ".$nomProf; ?></td>
        <td><a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar materia" title="Borrar materia" width="32" height="32" border="0" /></a></td>
        </tr>

        <?php
    $i++;
    }
    ?>
    </table>
    <?php


}

function eliminarClasesAlumno() {
    $IMaLegID = $_POST['IMaLegID'];
    $IMaLecID = $_POST['IMaLecID'];
    $IMaClaID = $_POST['IMaClaID'];

    $sql = "SELECT * FROM Colegio_InscripcionClase WHERE IMa_Leg_ID = $IMaLegID AND IMa_Lec_ID = $IMaLecID AND IMa_Cla_ID = $IMaClaID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "No se puedo eliminar";                           
    }
    else {
        $sql = "DELETE FROM Colegio_InscripcionClase WHERE IMa_Leg_ID = $IMaLegID AND IMa_Lec_ID = $IMaLecID AND IMa_Cla_ID = $IMaClaID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se eliminó correctamente";
    }
}

function buscarNdecUltima() {
    //echo "Hola";exit;
    //$Sucursal = $_POST['Sucursal'];
    $Sucursal = "0003";
    $sql = "SELECT Fac_Sucursal, Fac_Numero FROM Factura WHERE Fac_Sucursal = '$Sucursal' AND Fac_FTi_ID = 3 Order by Fac_Numero DESC LIMIT 0,1";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        $Numero = $row[Fac_Numero]+1;
        $Numero = substr("00000000".$Numero,-8);
        echo $row[Fac_Sucursal]."-".$Numero;
    }else{
        echo $Sucursal."-00000001";
    }               
}//fin if

function Cargar_Ndec() {

    $Fac_ID_Ndec = $_POST['Fac_ID_Ndec'];
    $NdecNumero = $_POST['NdecNumero'];
    $NdecSuc = substr($NdecNumero, 0, 4);
    $NdecNum = substr($NdecNumero, 5, 8);
    $UsuID_Ndec = $_POST['UsuID_Ndec'];
    $Fecha_Ndec = $_POST['Fecha_Ndec'];
    $Hora_Ndec = $_POST['Hora_Ndec'];
    $Fac_Usu_ID = $_POST['Fac_Usu_ID'];
    $Fac_UsuAut = $_POST['UsuAut'];
    $Fac_FechaAut = date("Y-m-d");
    $Fac_HoraAut = date("H:i:s");
    $sql1 = "SELECT Fac_Sucursal, Fac_Numero FROM Factura WHERE Fac_Sucursal = $NdecSuc AND Fac_Numero = $NdecNum AND Fac_FTi_ID = 3";
    $result = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0)
        echo "Ya existe nota de credito con ese numero";
    else {
        $sql2 = "INSERT INTO Factura (Fac_FTi_ID, Fac_Iva_ID, Fac_CVe_ID, Fac_Fecha, Fac_Hora, Fac_Usu_ID, Fac_CUIT, Fac_Sucursal, Fac_Numero, Fac_PersonaNombre, Fac_PersonaDomicilio, Fac_ImporteTotal, Fac_Pagada, Fac_Anulada, Fac_ID_Ndec, Fac_UsuAut, Fac_FechaAut, Fac_HoraAut) 
            SELECT 3, Fac_Iva_ID, Fac_CVe_ID, '$Fecha_Ndec', '$Hora_Ndec', $UsuID_Ndec, Fac_CUIT, $NdecSuc, $NdecNum, Fac_PersonaNombre, Fac_PersonaDomicilio, Fac_ImporteTotal, Fac_Pagada, Fac_Anulada, $Fac_ID_Ndec, $Fac_UsuAut, '$Fac_FechaAut', '$Fac_HoraAut' FROM Factura WHERE Fac_ID = $Fac_ID_Ndec;";
        consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
        //echo $sql2;

        $sqlid = "SELECT Fac_ID FROM Factura WHERE Fac_Sucursal = $NdecSuc AND Fac_Numero = $NdecNum AND Fac_FTi_ID = 3";
        $result = consulta_mysql_2022($sqlid,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result);
        $N_Fac_ID = $row[Fac_ID];//Busco el id del que se inserta

        $sql3 = "INSERT INTO FacturaDetalle (FDe_Fac_ID, FDe_Item, FDe_Cantidad, FDe_Detalle, FDe_ImporteUnitario, FDe_Importe) 
            SELECT $N_Fac_ID, FDe_Item, FDe_Cantidad, FDe_Detalle, FDe_ImporteUnitario, FDe_Importe FROM FacturaDetalle WHERE FDe_Fac_ID = $Fac_ID_Ndec;";
        consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
        echo $N_Fac_ID;
        //echo $sql3;
    }
    //agregarNotaCreditoCaja($Fac_ID_Ndec, $N_Fac_ID);//Factura que Anula, Nota de Credito
}

function mostrarNotasdeCredito(&$i, $row) {
    $Fac_ID_Ndec = $row[Fac_ID];
    $sqlndc = "SELECT * FROM Factura INNER JOIN FacturaTipo
        ON (Fac_FTi_ID = FTi_ID) WHERE Fac_ID_Ndec = $Fac_ID_Ndec ORDER BY Fac_ID;";
    $result1 = consulta_mysql_2022($sqlndc,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result1) > 0) {

            while ($row1 = mysqli_fetch_array($result1)) {
                $i++; 
                ?>

                <tr style="background-color: #b6e4f7;" class="texto">
                <input type="hidden" name="Fac_ID" id="Fac_ID<?php echo $i; ?>" value="<?php echo $row1[Fac_ID]; ?>" />
                <input type="hidden" name="Fac_FTi_ID" id="Fac_FTi_ID<?php echo $i; ?>" value="<?php echo $row1[Fac_FTi_ID]; ?>" />
                <input type="hidden" name="Fac_Iva_ID" id="Fac_Iva_ID<?php echo $i; ?>" value="<?php echo $row1[Fac_Iva_ID]; ?>" />
                <input type="hidden" name="Fac_CVe_ID" id="Fac_CVe_ID<?php echo $i; ?>" value="<?php echo $row1[Fac_CVe_ID]; ?>" />
                <input type="hidden" name="Fac_Fecha" id="Fac_Fecha<?php echo $i; ?>" value="<?php echo $row1[Fac_Fecha]; ?>" />
                <input type="hidden" name="Fac_Hora" id="Fac_Hora<?php echo $i; ?>" value="<?php echo $row1[Fac_Hora]; ?>" />
                <input type="hidden" name="Fac_Usu_ID" id="Fac_Usu_ID<?php echo $i; ?>" value="<?php echo $row1[Fac_Usu_ID]; ?>" />
                <input type="hidden" name="Fac_CUIT" id="Fac_CUIT<?php echo $i; ?>" value="<?php echo $row1[Fac_CUIT]; ?>" />
                <input type="hidden" name="Fac_Sucursal" id="Fac_Sucursal<?php echo $i; ?>" value="<?php echo $row1[Fac_Sucursal]; ?>" />
                <input type="hidden" name="Fac_FTi_ID" id="Fac_Numero<?php echo $i; ?>" value="<?php echo $row1[Fac_Numero]; ?>" />
                <input type="hidden" name="Fac_PersonaNombre" id="Fac_PersonaNombre<?php echo $i; ?>" value="<?php echo $row1[Fac_PersonaNombre]; ?>" />
                <input type="hidden" name="Fac_PersonaDomicilio" id="Fac_PersonaDomicilio<?php echo $i; ?>" value="<?php echo $row1[Fac_PersonaDomicilio]; ?>" />
                <input type="hidden" name="Fac_ImporteTotal" id="Fac_ImporteTotal<?php echo $i; ?>" value="<?php echo $row1[Fac_ImporteTotal]; ?>" />
                <input type="hidden" name="Fac_Pagada" id="Fac_Pagada<?php echo $i; ?>" value="<?php echo $row1[Fac_Pagada]; ?>" />
                <input type="hidden" name="Fac_Anulada" id="Fac_Anulada<?php echo $i; ?>" value="<?php echo $row1[Fac_Anulada]; ?>" />
              <td align="center"><?php echo $row1[Fac_ID]; ?></td>
              <td align="center"><?php echo $row1[FTi_Nombre]; ?></td>
              <td align="center"><?php echo $row1[Fac_Sucursal]."-".$row1[Fac_Numero]; ?></td>
              <td align="center"><?php echo cfecha($row1[Fac_Fecha])." ".$row1[Fac_Hora]; ?></td>             
              <td align="center"><?php echo $row1[Fac_PersonaNombre]; ?></td>
           
              <td align="center"><?php echo $row1[Fac_ImporteTotal]; ?></td>
              <?php
              $pagada = $row1[Fac_Pagada];
              $anulada = $row1[Fac_Anulada];
              if(( $pagada == 0)&&( $anulada == 0)){
                ?><td align="center">Generada</td><?php  
              }else if(( $pagada == 1)&&( $anulada == 0)){
                ?><td align="center">Pagada</td><?php  
              }if(( $pagada == 0)&&( $anulada == 1)){
                ?><td align="center">Anulada</td> <?php
              }
              
              $importeTotal += $row1[Fac_ImporteTotal];
              if ($row1[Fac_Anulada]==1)
                $importeTotalAnulados += $row1[Fac_ImporteTotal];
              
              ?>
              <td align="center"><?php echo buscarFormaPagoFactura($row1[Fac_ID], true); ?></td>
                <td align="center">
                
                <a href="#" onclick="fac_Detalle('<?php echo $i ?>')" id="botEditar<?php echo $i;?>"><img src="imagenes/magnifier.png" alt="Detalle Factura" title="Detalle Factura" width="32" height="32" border="0" /></a>
                <?php /*if ($pagada==1){ ?>
                <a href="#" onclick="Anular_Factura('<?php echo $i ?>')" id="botEditar<?php echo $i;?>"><img src="imagenes/table_delete.png" alt="Eliminar Factura" title="Eliminar Factura" width="32" height="32" border="0" /></a>

                <a href="#" onclick="Editar_Factura('<?php echo $i ?>')" id="botEditar<?php echo $i;?>"><img src="imagenes/tag_pink.png" alt="Editar Factura" title="Editar Factura" width="32" height="32" border="0" /></a>
                <?php } */?>
                
                </td>
              </tr> 
              <?php
          }
      }
}
//Eze

//********************** CuentaTipo ***********************************
function guardarCuentaTipo(){
    
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora); 
    $ID = $_POST['CuT_ID'];
    $Nombre = strtoupper(trim($_POST['CuT_Nombre']));
    $Nombre = arreglarCadenaMayuscula($Nombre);
    $CuT_Orden = $_POST['CuT_Orden'];
    $CuT_Padre_ID = $_POST['CuT_Padre_ID'];
    
    
    $Tabla = "CuentaTipo";
    
    if (!empty($ID)){//ya existe, actualizamos
     $sql = "UPDATE $Tabla SET CuT_Nombre='$Nombre', CuT_Orden='$CuT_Orden', CuT_Padre_ID='$CuT_Padre_ID' WHERE CuT_ID = $ID";
     $mensaje = "Se actualizó correctamente los datos";
  } else {
      $sql = "SELECT * FROM $Tabla WHERE CuT_Nombre = '$Nombre'";
      $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
      if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
        $mensaje = "Ya existe otro Tipo de Cuenta Contable con el Nombre $Nombre, elija otro.";
        echo $mensaje;
        return;
      }else{
        $sql = "INSERT INTO $Tabla (CuT_Nombre, CuT_Orden, CuT_Padre_ID) VALUES('$Nombre', '$CuT_Orden', $CuT_Padre_ID)";
        $mensaje = "Se agregó un nuevo registro correctamente";
      }
      
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarCuentaTipo(){
    //echo "Hola";exit;
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    $Tabla = "CuentaTipo";
    $IDTabla = "CuT_ID";
    
    $texto = $_POST['Texto'];
    if ($texto!="todos") $where = "";
    $sql = "SELECT * FROM
    CuentaTipo $where ORDER BY CuT_Orden, CuT_Nombre";
     $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
    
    $("a[id^='botEditar']").click(function(evento){                                           
        evento.preventDefault();
        var i = this.id.substr(9,10);       
        CuT_ID = $("#CuT_ID" + i).val();
        CuT_Nombre = $("#CuT_Nombre" + i).val();                
        //alert(vCliID);
        buscarDatos(CuT_ID, CuT_Nombre);
        
        $("#divBuscador").hide();
        $("#mostrarNuevo").fadeIn();
        $("#barraGuardar").show();
     });//fin evento click//*/  

     $("a[id^='botBorrar']").click(function(evento){                                              
        evento.preventDefault();
        var i = this.id.substr(9,10);
        <?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
        
        vNombre = $("#Nombre" + i).text();
        
        jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
                if (r){//eligió eliminar
                    $.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
                        $("#fila" + i).hide();
                        //recargarPagina();
                    });//fin post                   
                }//fin if
            });//fin del confirm//*/
    
     });//fin evento click//*/  
     
});//fin domready
function buscarDatos(CuT_ID, CuT_Nombre){
        $.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', CuT_ID: CuT_ID, CuT_Nombre: CuT_Nombre}, function(data){
            //alert(data);
            //return;
            if (data!="{}"){
                var obj = $.parseJSON(data);
                //alert(obj.total_legajos);
                $("#CuT_ID").val(obj.CuT_ID);
                $("#CuT_Nombre").val(obj.CuT_Nombre);
                $("#CuT_Orden").val(obj.CuT_Orden);
                $("#CuT_Padre_ID").val(obj.CuT_Padre_ID);
            }//fin if
        });     
    }//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Código</th>
                  <th align="center">Orden</th>
                  <th align="center">Tipo de Cuenta Contable</th>
                  <th align="center">Categoría Padre</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
                    $i++;
                    if ($i%2==0) $clase = "fila";else $clase = "fila2";
                ?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[CuT_ID];?>
                  <input type="hidden" id="CuT_ID<?php echo $i;?>" value="<?php echo $row[CuT_ID];?>" />
                  <input type="hidden" id="CuT_Nombre<?php echo $i;?>" value="<?php echo $row[CuT_Nombre];?>" />
                  <input type="hidden" id="CuT_Orden<?php echo $i;?>" value="<?php echo $row[CuT_Orden];?>" />
                  <input type="hidden" id="CuT_Padre_ID<?php echo $i;?>" value="<?php echo $row[CuT_Padre_ID];?>" />                  
                 </td>
                 <td align="center"><?php echo $row[CuT_Orden];?></td>
                 <td align="left"><?php echo $row[CuT_Nombre];?></td>
                 <td align="center"><?php echo buscarNombreCuentaTipo($row[CuT_Padre_ID]);?></td>
                 <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
                }//fin while
              ?>
            </tbody>
  </table>
<?php
    }else{
        echo "No existen datos relacionados con la consulta hecha";
    }//fin if
}//fin function

function buscarDatosCuentaTipo() {
    //echo "Hola";exit;
    $CuT_ID = $_POST['CuT_ID'];
    $CuT_Nombre = $_POST['CuT_Nombre'];
    
    $Tabla = "CuentaTipo";
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM $Tabla WHERE CuT_Nombre='$CuT_Nombre'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "{}";
    } else {
        $row = mysqli_fetch_array($result);   

        $datos .= "{\"CuT_ID\": \"" . $row[CuT_ID] . "\",\"";
        $datos .= "CuT_Nombre\": \"" . $row[CuT_Nombre] . "\",\"";
        $datos .= "CuT_Orden\": \"" . $row[CuT_Orden] . "\",\"";
        $datos .= "CuT_Padre_ID\": \"" . $row[CuT_Padre_ID] . "\"}";
                
        echo $datos;
    }
 }//fin funcion
 
function eliminarCuentaTipo() {
//echo "Hola";exit;
    $CuT_ID = $_POST['CuT_ID'];
    $CuT_Nombre = $_POST['CuT_Nombre'];
    
    $Tabla = "CuentaTipo";

    $sql = "SELECT * FROM $Tabla WHERE CuT_ID='$CuT_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El tipo de cuenta contable elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM Cuenta WHERE Cue_CuT_ID = $CuT_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas         
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Cuentas Contables relacionadas.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE CuT_ID = $CuT_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Tipo de Cuenta Contable seleccionado.";
        }
    }
}//fin function

//********************** Cuenta ***********************************
function guardarCuenta(){
    
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora);    
    $Cue_Nombre = strtoupper(trim($_POST['Cue_Nombre']));
    $Cue_Nombre = arreglarCadenaMayuscula($Cue_Nombre);
    $Cue_ID = $_POST['Cue_ID'];
    $Cue_CuT_ID = $_POST['Cue_CuT_ID'];    
    $Cue_Detalle = $_POST['Cue_Detalle'];
    $Cue_Codigo = $_POST['Cue_Codigo'];
    
    
    $Tabla = "Cuenta";
    
    if (!empty($Cue_ID)){//ya existe, actualizamos
     $sql = "UPDATE $Tabla SET Cue_CuT_ID='$Cue_CuT_ID', Cue_Nombre='$Cue_Nombre', Cue_Detalle='$Cue_Detalle', Cue_Codigo='$Cue_Codigo' WHERE Cue_ID = $Cue_ID";
     $mensaje = "Se actualizó correctamente los datos";
  } else {
      $sql = "SELECT * FROM $Tabla WHERE Cue_Nombre = '$Cue_Nombre' OR Cue_Codigo = '$Cue_Codigo'";
      $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
      if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
        $mensaje = "Ya existe otra Cuenta Contable con el Nombre $Cue_Nombre ó código $Cue_Codigo, elija otro.";
        echo $mensaje;
        return;
      }else{
        $sql = "INSERT INTO $Tabla (Cue_CuT_ID, Cue_Nombre, Cue_Detalle, Cue_Codigo) VALUES( '$Cue_CuT_ID', '$Cue_Nombre', '$Cue_Detalle', '$Cue_Codigo')";
        $mensaje = "Se agregó un nuevo registro correctamente";
      }
      
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarCuenta(){
    //echo "Hola";exit;
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    $Tabla = "Cuenta";
    $IDTabla = "Cue_ID";
    
    $texto = $_POST['Texto'];
    $where = "WHERE Cue_Nombre like '%$texto%' OR Cue_Codigo like '%$texto%'";
    if ($texto=="todos") $where = "";
    
    $sql = "SELECT * FROM Cuenta $where ORDER BY Cue_Codigo, Cue_Nombre";
     $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
    
    $("a[id^='botEditar']").click(function(evento){                                           
        evento.preventDefault();
        var i = this.id.substr(9,10);       
        Cue_ID = $("#Cue_ID" + i).val();
        Cue_Nombre = $("#Cue_Nombre" + i).val();                
        //alert(vCliID);
        buscarDatos(Cue_ID, Cue_Nombre);
        
        $("#divBuscador").hide();
        $("#divListar").hide();
        $("#mostrarNuevo").fadeIn();
        $("#barraGuardar").show();
     });//fin evento click//*/ 

     $("a[id^='botListar']").click(function(evento){                                           
        evento.preventDefault();
        var i = this.id.substr(9,10);       
        Cue_ID = $("#Cue_ID" + i).val();
        Cue_Nombre = $("#Cue_Nombre" + i).val();                
        
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "listarMovimientosCuenta", Cue_ID: Cue_ID},
            url: 'cargarOpciones.php',
            success: function(data){ 
                //alert(data);
                $("#divListar").html(data);
                //$("#loading").hide();
            }
        });//fin ajax//*/
        
        
        $("#divBuscador").hide();
        $("#mostrarNuevo").hide();
        $("#barraGuardar").hide();
        $("#divListar").show();
     });//fin evento click//*/  

     $("a[id^='botBorrar']").click(function(evento){                                              
        evento.preventDefault();
        var i = this.id.substr(9,10);
        <?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
        
        vNombre = $("#Nombre" + i).text();
        
        jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
                if (r){//eligió eliminar
                    $.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
                        $("#fila" + i).hide();
                        //recargarPagina();
                    });//fin post                   
                }//fin if
            });//fin del confirm//*/
    
     });//fin evento click//*/  
     
});//fin domready
function buscarDatos(Cue_ID, Cue_Nombre){
    $.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Cue_ID: Cue_ID, Cue_Nombre: Cue_Nombre}, function(data){
        //alert(data);
        //return;
        if (data!="{}"){
            var obj = $.parseJSON(data);
            //alert(obj.total_legajos);
            $("#Cue_ID").val(obj.Cue_ID);
            $("#Cue_CuT_ID").val(obj.Cue_CuT_ID);
            $("#Cue_Nombre").val(obj.Cue_Nombre);
            $("#Cue_Detalle").val(obj.Cue_Detalle);
            $("#Cue_Codigo").val(obj.Cue_Codigo);
        }//fin if
    });     
}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  <!-- <th align="center">ID</th> -->
                  <th align="center">Código</th>
                  <th align="left">Nombre</th>
                  <th align="center">Detalle</th>
                  <th align="center">Saldo</th>
                  <th align="center">Tipo de Cuenta</th>
                  <th align="center" width="120px">Acci&oacute;n</th>
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
                    $i++;
                    if ($i%2==0) $clase = "fila";else $clase = "fila2";
                ?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row[Cue_Codigo];?>
                  <input type="hidden" id="Cue_ID<?php echo $i;?>" value="<?php echo $row[Cue_ID];?>" />
                  <input type="hidden" id="Cue_Nombre<?php echo $i;?>" value="<?php echo $row[Cue_Nombre];?>" />
                                   
                 </td>
                 <!-- <td align="center"><?php echo $row[Cue_Codigo];?></td> -->
                 <td align="left"><?php echo $row[Cue_Nombre];?></td>
                 <td align="center"><?php echo $row[Cue_Detalle];?></td>
                 <td align="center"><?php echo traerSaldoCuentaContable($row[Cue_ID]);?></td>
                 <td align="center"><?php echo buscarNombreCuentaTipo($row[Cue_CuT_ID]);?></td>
                 <td align="center"><a href="#" id="botListar<?php echo $i;?>"><img src="imagenes/report.png" alt="Listar movimientos" title="Listar movimientos" width="32" height="32" border="0" /></a> <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
                }//fin while
              ?>
            </tbody>
  </table>
<?php
echo "Resultado total: ".mysqli_num_rows($result)." cuentas";
    }else{
        echo "No existen datos relacionados con la consulta hecha";
    }//fin if
}//fin function

function buscarDatosCuenta() {
    //echo "Hola";exit;
    $Cue_ID = $_POST['Cue_ID'];
    $Cue_Nombre = $_POST['Cue_Nombre'];
    
    $Tabla = "Cuenta";
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM $Tabla WHERE Cue_Nombre='$Cue_Nombre'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "{}";
    } else {
        $row = mysqli_fetch_array($result);   

        $datos .= "{\"Cue_ID\": \"" . $row[Cue_ID] . "\",\"";
        $datos .= "Cue_CuT_ID\": \"" . $row[Cue_CuT_ID] . "\",\"";
        $datos .= "Cue_Nombre\": \"" . $row[Cue_Nombre] . "\",\"";
        $datos .= "Cue_Detalle\": \"" . $row[Cue_Detalle] . "\",\"";
        $datos .= "Cue_Codigo\": \"" . $row[Cue_Codigo] . "\"}";
                
        echo $datos;
    }
 }//fin funcion
 
function eliminarCuenta() {
//echo "Hola";exit;
    $Cue_ID = $_POST['Cue_ID'];
    $Cue_Nombre = $_POST['Cue_Nombre'];
    
    $Tabla = "Cuenta";

    $sql = "SELECT * FROM $Tabla WHERE Cue_ID='$Cue_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "La Cuenta Contable elegida no existe o ya fue eliminada.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM CuentaMovimiento WHERE CMo_Cue_ID = $Cue_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas         
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Movimientos Contables relacionados.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE Cue_ID = $Cue_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó la Cuenta Contable seleccionada.";
        }
    }
}//fin function
//*************FIN*******************

function obtenerDetalleInasistencia() {
    $LegID = $_POST['LegID'];    
    list($Lec_ID, $Leg_ID) = explode(";", $LegID);
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);    
    $sql = "SELECT * FROM
    Colegio_Ausentismo
    INNER JOIN Colegio_TipoInasistencia 
        ON (Aus_Ina_ID = Ina_ID)
         WHERE Aus_Leg_ID = $Leg_ID AND Aus_Lec_ID = $Lec_ID ORDER BY Aus_FechaFalta";
         //echo $sql; exit;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $texto = "";
        echo "<ul>";
        while ($row = mysqli_fetch_array($result)) {
            
            if ($row['Aus_Justificada']==1){
                $texto .= "Justificada";
            }else{
                $texto .= "Sin Justificar";
            }
            if ($row['Aus_Certificado']==1){
                $texto .= " con Certificado";
            }
            if ($row['Aus_Deportiva']==1){
                $texto .= " Deportiva";
            }
            if (!empty($row['Aus_Detalle'])){
                $texto .= "; $row[Aus_Detalle]";
            }
            $id = $LegID.";$row[Aus_FechaFalta]";
            echo "<li id='q$id'>".cfecha($row[Aus_FechaFalta]).": <strong>$row[Ina_Nombre] ($row[Ina_Cant])</strong>->$texto (<a href='#' id='eliminarAsistencia$id'>eliminar</a>)</li>";
        
            $texto = "";
        }//fin while
        echo "</ul>";
    } else {
        echo "No existen datos cargados.";
    }
}//fin funcion

function eliminarAsistencia() {
//echo "Hola";exit;
    $id = $_POST['id'];
    list($Lec_ID, $Leg_ID, $FechaFalta) = explode(";", $id);

    $sql = "DELETE FROM Colegio_Ausentismo WHERE Aus_Leg_ID = $Leg_ID AND Aus_Lec_ID = $Lec_ID AND Aus_FechaFalta = '$FechaFalta'";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    echo "Se eliminó correctamente. Cierre este cuadro de diálogo para ver los cambios";        
}//fin funcion

function traerDatosProveedor(){
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $Cue_ID = $_POST['Cue_ID'];
    $sql = "SELECT * FROM Egreso_Cuenta WHERE Cue_ID=$Cue_ID";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        /*$datos[0] = $row[Rec_RazonSocial];
        $datos[1] = $row[Rec_CUIT];
        echo $datos;*/
        echo "$row[Cue_RazonSocial];$row[Cue_CUIT];$row[Cue_Cue1]";
    }else{
        echo false;
    }
}

function traerSaldoCuentaContable($Cuenta = 0){
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($Cuenta==0) $Cue_ID = $_POST['Cue_ID']; else  $Cue_ID = $Cuenta;  
    $sql = "SELECT SUM(CMo_Debito) AS Debe, SUM(CMo_Credito) AS Haber FROM CuentaMovimiento WHERE CMo_Cue_ID = $Cue_ID";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        $Saldo = intval($row[Haber]) - intval($row[Debe]);       
        if ($Cuenta==0) echo $Saldo; else return $Saldo;
    }else{
        if ($Cuenta==0) echo false; else return false;
    }
}

function guardarOrdenPago(){    
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    obtenerRegistroUsuario($Rec_Usu_ID, $Rec_Fecha, $Rec_Hora);     
    $CajaID = cajaAbiertaUsuario($Rec_Usu_ID);
    if (!$CajaID){
        echo "Error: La Caja se encuentra cerrada";
        exit;
    }
    $SCC_SCa_ID = obtenerSuperCaja();
    if (!$SCC_SCa_ID){
        echo "No se encuentra abierta la SuperCaja.";
        exit;
    }

    $Rec_ID = $_POST['Rec_ID'];
    $Rec_Cue_ID = $_POST['Rec_Cue_ID'];
    /*
    if ($Rec_Cue_ID==44){
        echo "Error: No se puede realizar este pago por el momento. Intente de nuevo más tarde";
        exit;
    }*/
    $Rec_FechaCompra = cambiaf_a_mysql($_POST['Rec_FechaCompra']);
    $Rec_FechaFactura = cambiaf_a_mysql($_POST['Rec_FechaFactura']);
    $Rec_Importe = str_replace(",",".",$_POST['Rec_Importe']);
    $Rec_ETi_ID = $_POST['Rec_ETi_ID'];
    $Rec_TipoRecibo = $_POST['Rec_TipoRecibo'];
    $Rec_Numero = $_POST['Rec_Numero'];
    $Rec_FormaPago = $_POST['formaPago'];
    $Rec_RazonSocial = $_POST['txtRazonSocial'];
    $Rec_Detalle = $_POST['txtDetalleRecibo'];
    $Rec_Autoriza = $_POST['Rec_Autoriza'];
    $Rec_ChequeNumero = $_POST['ChequeNumero'];
    $Rec_ChequeBanco = $_POST['ChequeBanco'];
    $Rec_ChequeFecha = cambiaf_a_mysql($_POST['ChequeFecha']);
    //Datos contables
    $CuentaOrigen = $_POST['CuentaOrigen'];
    $CuentaDestino = $_POST['CuentaDestino'];
    
    $Tabla = "Egreso_Recibo";

    //Primero buscamos si la factura del proveedor ya se encuentra cargada
    $sql = "SELECT * FROM $Tabla WHERE Rec_Cue_ID = '$Rec_Cue_ID' AND Rec_Numero = '$Rec_Numero'";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
        echo "Error: El N° de Factura del Proveedor ya existe";
        exit;
    }

    //Registramos el pago tradicionalmente    
    $sql = "SELECT * FROM $Tabla WHERE Rec_ID = '$Rec_ID'";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
     
     echo "Error: El N° de Orden de Pago ya existe";
     exit;
  } else {
     
      $sql = "INSERT INTO $Tabla (Rec_ID, Rec_Cue_ID, Rec_FechaCompra, Rec_Usu_ID, Rec_Fecha, Rec_Hora, Rec_Importe, Rec_ETi_ID, Rec_TipoRecibo, Rec_Numero, Rec_RazonSocial, Rec_Detalle, Rec_FormaPago, Rec_Autoriza, Rec_ChequeNumero, Rec_ChequeBanco, Rec_ChequeFecha, Rec_FechaFactura) VALUES('$Rec_ID', '$Rec_Cue_ID', '$Rec_FechaCompra', '$Rec_Usu_ID', '$Rec_Fecha', '$Rec_Hora', '$Rec_Importe', '$Rec_ETi_ID', '$Rec_TipoRecibo', '$Rec_Numero', '$Rec_RazonSocial', '$Rec_Detalle', '$Rec_FormaPago', '$Rec_Autoriza', '$Rec_ChequeNumero', '$Rec_ChequeBanco', '$Rec_ChequeFecha', '$Rec_FechaFactura')";
      consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
      $mensaje = "Se agregó un nuevo registro correctamente";
      $detalleItems = "";
      if (isset($_POST['itemOrden1']) && !empty($_POST['itemImporte1'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden1']."', '$Rec_ID', '".$_POST['itemDetalle1']."', '".$_POST['itemImporte1']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= "Item 1: ".$_POST['itemDetalle1']." Importe: ".$_POST['itemImporte1'];
      }
      if (isset($_POST['itemOrden2']) && !empty($_POST['itemImporte2'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden2']."', '$Rec_ID', '".$_POST['itemDetalle2']."', '".$_POST['itemImporte2']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= ". Item 2: ".$_POST['itemDetalle2']." Importe: ".$_POST['itemImporte2'];
      }
      if (isset($_POST['itemOrden3']) && !empty($_POST['itemImporte3'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden3']."', '$Rec_ID', '".$_POST['itemDetalle3']."', '".$_POST['itemImporte3']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= ". Item 3: ".$_POST['itemDetalle3']." Importe: ".$_POST['itemImporte3'];
      }
      if (isset($_POST['itemOrden4']) && !empty($_POST['itemImporte4'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden4']."', '$Rec_ID', '".$_POST['itemDetalle4']."', '".$_POST['itemImporte4']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= ". Item 4: ".$_POST['itemDetalle4']." Importe: ".$_POST['itemImporte4'];
      }
      if (isset($_POST['itemOrden5']) && !empty($_POST['itemImporte5'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden5']."', '$Rec_ID', '".$_POST['itemDetalle5']."', '".$_POST['itemImporte5']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= ". Item 5: ".$_POST['itemDetalle5']." Importe: ".$_POST['itemImporte5'];
      }
      if (isset($_POST['itemOrden6']) && !empty($_POST['itemImporte6'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden6']."', '$Rec_ID', '".$_POST['itemDetalle6']."', '".$_POST['itemImporte6']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= ". Item 6: ".$_POST['itemDetalle6']." Importe: ".$_POST['itemImporte6'];
      }
      if (isset($_POST['itemOrden7']) && !empty($_POST['itemImporte7'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden7']."', '$Rec_ID', '".$_POST['itemDetalle7']."', '".$_POST['itemImporte7']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= ". Item 7: ".$_POST['itemDetalle7']." Importe: ".$_POST['itemImporte7'];
      }
      if (isset($_POST['itemOrden8']) && !empty($_POST['itemImporte8'])){
        $sql = "INSERT INTO Egreso_ReciboItem (RIt_Item, RIt_Rec_ID, RIt_Detalle, RIt_Importe) VALUES('".$_POST['itemOrden8']."', '$Rec_ID', '".$_POST['itemDetalle8']."', '".$_POST['itemImporte8']."')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $detalleItems .= ". Item 8: ".$_POST['itemDetalle8']." Importe: ".$_POST['itemImporte8'];
      }
  }
  


  
  //Realizamos el movimiento contable

  $sql = "SELECT * FROM Egreso_Cuenta WHERE Cue_ID = '$Rec_Cue_ID'";
  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  if (mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_array($result);
    $Concepto = "Orden de Pago N° $Rec_ID a Proveedor: $row[Cue_Nombre] (CUIT $row[Cue_CUIT])";
    $Detalle = "$Rec_TipoRecibo: $Rec_Numero. $Rec_Detalle. $detalleItems";
    if ($Rec_FormaPago=="Efectivo") $ForID=1;
    if ($Rec_FormaPago=="Cheque"){
      $ForID=2; 
      $Detalle .=" - Cheque: $Rec_ChequeNumero. Banco: $Rec_ChequeBanco. Fecha: ".$_POST['ChequeFecha']; 
    } 
    if ($Rec_FormaPago=="Transferencia") $ForID=12;

    //Guardamos la salida de dinero del origen
    guardarMovimientoCuentaContable($CajaID, $CuentaOrigen, $Concepto, $Rec_Importe, 0, $ForID, $Detalle);

    //Guardamos la entrada de dinero del destino
    guardarMovimientoCuentaContable($CajaID, $CuentaDestino, $Concepto, 0, $Rec_Importe, $ForID, $Detalle);

    /*$CCCID = guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $Rec_Importe, 1, $Detalle);
    $SCC_FechaHora = "$Rec_Fecha $Rec_Hora";
    $sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CajaID, $CCCID, $SCC_SCa_ID, 'PAGOS $Concepto', $Rec_Importe, 0, '$SCC_FechaHora', '$Detalle', $Rec_Usu_ID)";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $SCC_ID = mysql_insert_id();
    actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);*/
  }
  echo $mensaje;
        
}//fin function

function buscarDNIPadre() {
    $DNI = trim($_POST['DNI']);
    $_SESSION['sesion_ultimoDNI'] = $DNI;
    
    $bContinuar = false;
    if (empty($DNI)) exit;
      $sql = "SET NAMES UTF8";
      consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
      $sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
      $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
      if (mysqli_num_rows($result) > 0) {            
        $row = mysqli_fetch_array($result);
        $bContinuar = true;
      }//fin if
      
      if ($bContinuar){
          $foto = buscarFoto($DNI, $row[Per_Doc_ID], 60); //echo "TODO MAL $sql";exit;
          $edad = obtenerEdad($row[Per_ID]);          
          //$otrosDatos = buscarOtrosDatosPersona($row[Per_ID]);
          $otrosDatos = buscarTelefonosPersona($row[Per_ID]);
          
          obtenerHijos($row['Per_ID'], $arrarTutores, $cant);          
          ?>
          <link href="css/general.css" rel="stylesheet" type="text/css" />
          <script language="javascript">
$(document).ready(function(){
    $("a[id^='cargarHermano']").click(function(evento){
        evento.preventDefault();        
        var i = this.id.substr(13,10);
        //alert(i);
        $("#DNI").val(i);
        //cargarDNI();
    });//fin evento click//*/
  $("a[id^='verFotoAlumno']").click(function(evento){
        evento.preventDefault();
        fDNI = this.id.substr(13,15);
        //alert(fDNI);
        data = "<img src='fotos/grande/"+fDNI+".jpg' title='Foto' width='500' border='1' align='absmiddle' class='foto'/>";   
        $("#dialog").html(data);
        $("#dialog").dialog({ draggable: true, hide: 'slide', title: 'Foto del Alumno', zIndex: 3900, resizable: true, modal: true, height: 'auto', width: 520, 
            buttons: {
                'Aceptar': function() {
                    $(this).dialog('close');
                }
            }//fin buttons
        });//fin dialog          
        //alert(vDNI);
        
    });
});//fin de la funcion ready


</script>
  
  <table width='100%' border='0'>
              <tr>
                <td width="60" rowspan="2" align="center" valign="middle" class="fila_titulo3"><?php echo $foto; ?></td>
                <th align="center" class="fila_titulo3">Datos del Padre/Madre</th>
                <th class="fila_titulo3">Datos de su/s hijo/s</th>
  </tr>
              <tr class="fila2">
                <td valign="top" class="texto">
  Apellido y Nombre: <strong><?php echo "$row[Per_Apellido], $row[Per_Nombre]"; ?></strong><br />
                        <?php
                          if ($edad > 0) {
                              echo "Edad: <strong>$edad años</strong>";
                          }else{
                              echo "Edad: <strong>FALTA CARGAR FECHA NACIMIENTO</strong>";
                          }                     
                          ?><br /><?php echo $otrosDatos;
                          ?>
                          
                        </td>
                  <td valign="top" class="texto"><p>
                  <div id="noTienePadreAsociado"></div>
                  <?php if ($cant>0){
                        for ($i=1;$i<=$cant;$i++){
                            $edad = obtenerEdad($arrarTutores[$i]['Per_ID']);
                            $CursoDiv = buscarCursoDivisionPersona($arrarTutores[$i]['Per_ID'], true);
                            echo $arrarTutores[$i]['FTi_Tipo'].": <strong>".$arrarTutores[$i]['Per_Apellido'].", ".$arrarTutores[$i]['Per_Nombre']."</strong><br />";
                            echo "DNI: <strong>".$arrarTutores[$i]['Per_DNI']."</strong> ";
                             echo "Edad: <strong>".$edad." años</strong> <br />";
                             echo "Curso: <strong>".$CursoDiv."</strong> <br />";
                            echo "".$arrarTutores[$i]['Telefonos']."<br />";
                            
                            if ($cant>1){
                                echo "<hr />";
                            }//fin if cant
                        }//fin for
                        //echo "<a href='imprimirGrupoFamiliarAlumno.php?id=$row[Per_ID]' target='_blank'>Imprimir</a>";
                     
                     }//fin if?>
                     
                  
                  </p></td>
              </tr>
          </table>
      <?php } else { ?>
          <div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />El DNI ingresado no corresponde a una persona cargada, por favor verifique los datos ingresados antes de continuar.</span><br />
              <?php
          }
        
    
}//fin funcion

function guardarAjusteCaja(){
    //echo "PPP"; exit;
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $CCC_Caja_ID = $_POST['CCC_Caja_ID'];
    $CCC_Concepto = $_POST['CCC_Concepto'];
    $CCC_Detalle = $_POST['CCC_Detalle'];
    $Tipo = $_POST['Tipo'];
    $CCC_For_ID = $_POST['CCC_For_ID'];
    $CCC_Importe = $_POST['CCC_Importe'];
    obtenerRegistroUsuario($Usu_ID, $Fecha, $Hora);

    $sql = "SELECT * FROM Caja INNER JOIN CajaCorriente ON (Caja_ID = CCC_Caja_ID) INNER JOIN Usuario ON (CCC_Usu_ID = Usu_ID) INNER JOIN FormaPago ON (CCC_For_ID = For_ID) 
 WHERE CCC_Caja_ID=$CCC_Caja_ID";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $sql = "INSERT INTO CajaCorriente (CCC_For_ID, CCC_Caja_ID, CCC_Concepto, $Tipo, CCC_Fecha, CCC_Hora, CCC_Detalle, CCC_Usu_ID) VALUES($CCC_For_ID, $CCC_Caja_ID, '$CCC_Concepto', '$CCC_Importe', '$Fecha', '$Hora', '$CCC_Detalle', $Usu_ID)";
        $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
        if ($res["success"] == true){
            $CCC_ID= $res['id'];
        }else{
            echo "Mal";
        }
        
        actualizarSaldoCajaCorriente($CCC_Caja_ID, $CCC_ID);
        echo "Se agregó correctamente el ajuste.";
    }else{
        echo "ERROR: La Caja no existe.";
    }
}//fin function

function migrarInscripcionesAlumnos() {

    //$LegID = $_POST['LegID'];

    $LecID = $_POST['LecID'];
    $CurID = $_POST['CurID'];
    $NivID = $_POST['NivID'];
    $DivID = $_POST['DivID'];
    $Inscriptos = $_POST['Inscriptos'];
    $LecNuevoID = $_POST['LecNuevoID'];
    $NivNuevoID = $_POST['NivNuevoID'];
    $CurNuevoID = $_POST['CurNuevoID'];
    $DivNuevoID = $_POST['DivNuevoID'];
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
    $i = 0;
    $j = 0;
    $bEntrar = false;
    foreach ($Inscriptos as $LegID) {
        $bEntrar = true;

        //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
        $sql = "SELECT * FROM Colegio_Inscripcion WHERE (Ins_Lec_ID = $LecNuevoID AND Ins_Leg_ID = $LegID";
        if ($CurNuevoID != 999999)
            $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurNuevoID ";
        if ($NivNuevoID != 999999)
            $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivNuevoID ";
        if ($DivNuevoID != 999999)
            $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivNuevoID";
        $sql.=");";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        //echo "$sql - Entre";
        if (mysqli_num_rows($result) == 0) {//no se encuentra inscripto            
            $sql = "INSERT INTO Colegio_Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora) VALUES ($LegID, $LecNuevoID, $CurNuevoID, $NivNuevoID, $DivNuevoID, $UsuID, '$Fecha', '$Hora')";            
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            borrarInscripcionClase($LecNuevoID, $LegID, $NivNuevoID, $CurNuevoID, $DivNuevoID);
            agregarInscripcionClase($LecID, $LegID, $NivNuevoID, $CurNuevoID, $DivNuevoID);
            //guardarClaseAlumnoLegajo($LegID, $LecID);
            $PerID = buscarPersonaLegajo($LegID);
            guardarAsignacionCuota($PerID, $LecNuevoID, $NivNuevoID);
            //echo "$sql<br />";
            $i++;
        } else {
            $j++;
        }//*/
    }//fin foreach//*/
    if ($i > 0) {
        echo "Se migraron $i alumnos.<br />";
    }
    if ($j > 0) {
        echo "$j alumnos ya se encontraban inscriptos.";
    }
    if (!$bEntrar)
        echo "Seleccione los alumnos que desea cambiar de división.";
}//fin funcion

function buscarPersonaLegajo($LegID){
    
    /*$sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);*/
    
    $sql = "SELECT * FROM Legajo 
        WHERE Leg_ID = $LegID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){     
        $row = mysqli_fetch_array($result);
        return $row['Leg_Per_ID']; 
    }
    return -1;
}//fin function


//********************** Deudor ***********************************
function guardarDeudor(){
    
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    obtenerRegistroUsuario($Deu_Usu_ID, $Deu_Fecha, $Deu_Hora);
    $Deu_Nombre = strtoupper(trim($_POST['Deu_Nombre']));
    $Deu_Nombre = arreglarCadenaMayuscula($Deu_Nombre);
    $Deu_CUIT = $_POST['Deu_CUIT'];
    $Deu_RazonSocial = $_POST['Deu_RazonSocial']; 
    $Deu_ID = $_POST['Deu_ID'];
    
    $Tabla = "Deudor";
    
    //$sql = "SELECT * FROM $Tabla WHERE Cue_Nombre LIKE '$Cue_Nombre'";
    $sql = "SELECT * FROM $Tabla WHERE Deu_ID='$Deu_ID'";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
     $sql = "UPDATE $Tabla SET Deu_Nombre='$Deu_Nombre', Deu_CUIT='$Deu_CUIT', Deu_RazonSocial='$Deu_RazonSocial', Deu_Usu_ID='$Deu_Usu_ID', Deu_Fecha='$Deu_Fecha', Deu_Hora='$Deu_Hora' WHERE Deu_ID='$Deu_ID'";
     $mensaje = "Se actualizó correctamente los datos";
  } else {
      $sql = "INSERT INTO $Tabla (Deu_Nombre, Deu_CUIT, Deu_RazonSocial, Deu_Usu_ID, Deu_Fecha, Deu_Hora) VALUES('$Deu_Nombre', '$Deu_CUIT', '$Deu_RazonSocial', '$Deu_Usu_ID', '$Deu_Fecha', '$Deu_Hora')";
      $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  echo $mensaje;
        
}//fin function

function buscarDeudor(){
    //echo "Hola";exit;
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    $Tabla = "Deudor";
    $IDTabla = "Deu_ID";
    
    $texto = $_POST['Texto'];
    $where = " WHERE Deu_Nombre LIKE '%$texto%' OR Deu_CUIT LIKE '%$texto%' OR Deu_RazonSocial LIKE '%$texto%'";
    if ($texto=="todos") $where = "";
    $sql = "SELECT * FROM $Tabla $where ORDER BY Deu_Nombre";
     $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
    
    $("a[id^='botEditar']").click(function(evento){                                           
        evento.preventDefault();
        var i = this.id.substr(9,10);       
        Deu_ID = $("#Deu_ID" + i).val();
        Deu_Nombre = $("#Deu_Nombre" + i).val();                
        //alert(vCliID);
        buscarDatos(Deu_ID, Deu_Nombre);
        
        $("#divBuscador").hide();
        $("#mostrarNuevo").fadeIn();
        $("#barraGuardar").show();
     });//fin evento click///  
     $("a[id^='botBorrar']").click(function(evento){                                              
        evento.preventDefault();
        var i = this.id.substr(9,10);
        <?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
        
        vNombre = $("#Nombre" + i).text();
        
        jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
                if (r){//eligió eliminar
                    $.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
                        $("#fila" + i).hide();
                        //recargarPagina();
                    });//fin post                   
                }//fin if
            });//fin del confirm///
    
     });//fin evento click///  
     
});//fin domready
function buscarDatos(Deu_ID, Deu_Nombre){
        $.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Deu_ID: Deu_ID, Deu_Nombre: Deu_Nombre}, function(data){
            //alert(data);
            //return;
            if (data!="{}"){
                var obj = $.parseJSON(data);
                //alert(obj.total_legajos);
                $("#Deu_ID").val(obj.Deu_ID);
                $("#Deu_Nombre").val(obj.Deu_Nombre);
                $("#Deu_CUIT").val(obj.Deu_CUIT);
                $("#Deu_RazonSocial").val(obj.Deu_RazonSocial);
            }//fin if
        });     
    }//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Código</th>
                  <th align="center">Deudor (Razón Social)</th>
                  <th align="center">CUIT</th>
                  <!-- <th align="center">Teléfonos</th>
                  <th align="center">Cuentas asociadas</th> -->
                  <th align="center">Acci&oacute;n</th>
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
                    $i++;
                    if ($i%2==0) $clase = "fila";else $clase = "fila2";
                ?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td align="center"><?php echo $row['Deu_ID'];?>
                  <input type="hidden" id="Deu_ID<?php echo $i;?>" value="<?php echo $row['Deu_ID'];?>" />
                  <input type="hidden" id="Deu_Nombre<?php echo $i;?>" value="<?php echo $row[Deu_Nombre];?>" />
                  
                 </td>
                 <td align="left"><?php 
                 if (!empty($row[Deu_RazonSocial])) echo "$row[Deu_Nombre] ($row[Deu_RazonSocial])";else echo $row[Deu_Nombre];?></td>
                 <td align="center"><?php echo $row[Deu_CUIT];?></td>
                 
                 <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
                }//fin while
              ?>
            </tbody>
  </table>
<?php
    }else{
        echo "No existen datos relacionados con la consulta hecha";
    }//fin if
}//fin function

function buscarDatosDeudor() {
    //echo "Hola";exit;
    $Deu_ID = $_POST['Deu_ID'];
    $Deu_Nombre = $_POST['Deu_Nombre'];
    
    $Tabla = "Deudor";
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM $Tabla WHERE Deu_Nombre='$Deu_Nombre'";
    $sql = "SELECT * FROM $Tabla WHERE Deu_ID='$Deu_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
        echo "{}";
    } else {
        $row = mysqli_fetch_array($result);                      

        $datos .= "{\"Deu_ID\": \"" . $row[Deu_ID] . "\",\"";
        $datos .= "Deu_Nombre\": \"" . $row[Deu_Nombre] . "\",\"";
        $datos .= "Deu_CUIT\": \"" . $row[Deu_CUIT] . "\",\"";
        $datos .= "Deu_RazonSocial\": \"" . $row[Deu_RazonSocial] . "\",\"";
        $datos .= "Deu_Usu_ID\": \"" . $row[Deu_Usu_ID] . "\",\"";
        $datos .= "Deu_Fecha\": \"" . $row[Deu_Fecha] . "\",\"";
        $datos .= "Deu_Hora\": \"" . $row[Deu_Hora] . "\"}";
                
        echo $datos;
    }
 }//fin funcion
 
function eliminarDeudor() {
//echo "Hola";exit;
    $Deu_ID = $_POST['Deu_ID'];
    $Deu_Nombre = $_POST['Deu_Nombre'];
    
    $Tabla = "Deudor";

    $sql = "SELECT * FROM $Tabla WHERE Deu_ID='$Deu_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "El Deudor elegido no existe o ya fue eliminado.";
    } else {
        $sql = "SELECT COUNT(*) AS TOTAL FROM DeudorRecibo WHERE DRe_Deu_ID = $Deu_ID";
        $result_prov = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result_prov);
        if ($row[TOTAL] > 0) {//Tiene provincias vinculadas         
            echo "No se puede eliminar porque tiene " . $row[TOTAL] . " Deudas pendientes relacionadas.";
        } else {
            $sql = "DELETE FROM $Tabla WHERE Deu_ID = $Deu_ID";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            echo "Se eliminó el Deudor seleccionado.";
        }
    }
}//fin function

//********************** DeudorRecibos ***********************************
function guardarDeudorRecibo(){    
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    obtenerRegistroUsuario($DRe_Usu_ID, $DRe_Fecha, $DRe_Hora);     
    $CajaID = cajaAbiertaUsuario($DRe_Usu_ID);
    if (!$CajaID){
        echo "Error: La Caja se encuentra cerrada";
        exit;
    }
    $SCC_SCa_ID = obtenerSuperCaja();
    if (!$SCC_SCa_ID){
        echo "No se encuentra abierta la SuperCaja.";
        exit;
    }
    /*$Rec_ID = $_POST['Rec_ID'];
    $Rec_Cue_ID = $_POST['Rec_Cue_ID'];
    $Rec_FechaCompra = cambiaf_a_mysql($_POST['Rec_FechaCompra']);
    $Rec_Importe = str_replace(",",".",$_POST['Rec_Importe']);
    $Rec_ETi_ID = $_POST['Rec_ETi_ID'];
    $Rec_TipoRecibo = $_POST['Rec_TipoRecibo'];
    $Rec_Numero = $_POST['Rec_Numero'];
    $Rec_FormaPago = $_POST['formaPago'];
    $Rec_RazonSocial = $_POST['txtRazonSocial'];
    $Rec_Detalle = $_POST['txtDetalleRecibo'];*/

    $DRe_ID = $_POST['DRe_ID'];
    $DRe_Deu_ID = $_POST['DRe_Deu_ID'];
    $DRe_FechaRecibo = cambiaf_a_mysql($_POST['DRe_FechaRecibo']);
    $DRe_Importe = str_replace(",",".",$_POST['DRe_Importe']);
    $DRe_Detalle = $_POST['txtDetalleRecibo'];
    $DRe_FormaPago = $_POST['DRe_FormaPago'];
    $DRe_ChequeNumero = $_POST['DRe_ChequeNumero'];
    $DRe_ChequeBanco = $_POST['DRe_ChequeBanco'];
    $DRe_ChequeFecha = $_POST['DRe_ChequeFecha'];
    $DRe_Fecha = $_POST['DRe_Fecha'];
    $DRe_Hora = $_POST['DRe_Hora'];
    $DRe_Usu_ID = $_POST['DRe_Usu_ID'];
    $DRe_ReciboNumero = $_POST['DRe_ReciboNumero'];
    $DRe_DetallePago = $_POST['DRe_DetallePago'];
    
    
    $Tabla = "DeudorRecibo";
    
    $sql = "SELECT * FROM $Tabla WHERE DRe_ID = '$DRe_ID'";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre
     $sql = "UPDATE $Tabla SET DRe_Deu_ID='$DRe_Deu_ID', DRe_FechaRecibo='$DRe_FechaRecibo', DRe_Importe='$DRe_Importe', DRe_Detalle='$DRe_Detalle', DRe_FormaPago='$DRe_FormaPago', DRe_ChequeNumero='$DRe_ChequeNumero', DRe_ChequeBanco='$DRe_ChequeBanco', DRe_ChequeFecha='$DRe_ChequeFecha', DRe_Fecha='$DRe_Fecha', DRe_Hora='$DRe_Hora', DRe_Usu_ID='$DRe_Usu_ID', DRe_ReciboNumero='$DRe_ReciboNumero', DRe_DetallePago='$DRe_DetallePago'  WHERE DRe_ID='$DRe_ID'";
     $mensaje = "Se actualizó correctamente los datos";
  } else {
      
      $sql = "INSERT INTO $Tabla (DRe_ID, DRe_Deu_ID, DRe_FechaRecibo, DRe_Importe, DRe_Detalle, DRe_FormaPago, DRe_ChequeNumero, DRe_ChequeBanco, DRe_ChequeFecha, DRe_Fecha, DRe_Hora, DRe_Usu_ID, DRe_ReciboNumero, DRe_DetallePago) VALUES('$DRe_Deu_ID', '$DRe_FechaRecibo', '$DRe_Importe', '$DRe_Detalle', '$DRe_FormaPago', '$DRe_ChequeNumero', '$DRe_ChequeBanco', '$DRe_ChequeFecha', '$DRe_Fecha', '$DRe_Hora', '$DRe_Usu_ID', '$DRe_ReciboNumero', '$DRe_DetallePago')";
      $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  
  $sql = "SELECT * FROM Deudor WHERE Deu_ID = '$DRe_Deu_ID'";
  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  if (mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_array($result);
    $Concepto = "Deudor: ".$row['Deu_Nombre'];
    $Detalle = "Recibo: $DRe_ReciboNumero. $row[Deu_RazonSocial]/$DRe_Detalle";
    $CCCID = guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $DRe_Importe, 1, $Detalle);
    $SCC_FechaHora = "$DRe_Fecha $DRe_Hora";
    $sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CajaID, $CCCID, $SCC_SCa_ID, 'INGRESO $Concepto', 0, $DRe_Importe, '$SCC_FechaHora', '$Detalle', $DRe_Usu_ID)";
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $SCC_ID= $res['id'];
    }else{
        echo "Mal";
    }

    actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);
  }
  echo $mensaje;
        
}//fin function

function buscarDeudorRecibo(){
    //echo "Hola";exit;
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    $Tabla = "DeudorRecibo";
    $IDTabla = "DRe_ID";
    
    $texto = $_POST['Texto'];
    $Anio = $_POST['Anio'];
    //$TipoEgreso = $_POST['TipoEgreso'];
    $where = " WHERE YEAR(DRe_FechaRecibo)=$Anio";
    //if ($TipoEgreso!=999999) $where .= " AND Rec_ETi_ID = $TipoEgreso";
    
    $sql = "SELECT Deu_ID, Deu_Nombre, SUM(DRe_Importe)AS Importe FROM
    DeudorRecibo INNER JOIN Deudor ON (DRe_Deu_ID = Deu_ID) INNER JOIN Usuario ON (DRe_Usu_ID = Usu_ID) $where GROUP BY Deu_ID ORDER BY Deu_Nombre, DRe_FechaRecibo ";
     $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
     //echo $sql;
    if (mysqli_num_rows($result) > 0){
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){

$("#imprimirTodas").click(function(evento){
        evento.preventDefault();
        //tituloRecibo = $("#tituloRecibo").html();
        $("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle: 'Listado de Ordenes de Ingreso',overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
                                        
        });
        
        $("#cargando").hide();
     });//fin evento click//*/
    
     $("#barraExportar").click(function(evento){
        evento.preventDefault();
        jPrompt('Escriba el nombre del archivo a exportar:', 'Ordenes de Ingreso', 'Exportar listado a Excel', function(r) {
            if( r ){
                $("#archivo").val(r);
                $("#formExportarExcel").submit();
            } 
        });
        
    });
     
     $("a[id^='botBorrar']").click(function(evento){                                              
        evento.preventDefault();
        var i = this.id.substr(9,10);
        <?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
        
        vNombre = $("#Nombre" + i).text();
        
        jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
                if (r){//eligió eliminar
                    $.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
                        $("#fila" + i).hide();
                        //recargarPagina();
                    });//fin post                   
                }//fin if
            });//fin del confirm//*/
    
     });//fin evento click//*/  
     
     $("a[id^='link']").click(function(evento){                                           
        evento.preventDefault();
        var i = this.id.substr(4,100);
        
        /*<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
        
        vNombre = $("#Nombre" + i).text();*/
        
        $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {i: i},
                url: 'mostrarRecibosIngresoDetalle.php',
                success: function(data){ 
                     mostrarAlertaGrande(data, 'Detalle de los recibos');
                     //jAlert(data, 'Detalle de los recibos');
                    
                }
            });//fin ajax//*/ 
    
     });//fin evento click//*/  
     $("a[id^='verCuenta']").click(function(evento){                                              
        evento.preventDefault();
        var i = this.id.substr(9,100);
        
        
        $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {i: i},
                url: 'mostrarRecibosIngresoDetalleCompleto.php',
                success: function(data){ 
                     $("#mostrarResultado").html(data);
                     //mostrarAlertaGrande(data, 'Detalle de los recibos');
                     //jAlert(data, 'Detalle de los recibos');
                    
                }
            });//fin ajax//*/ 
    
     });//fin evento click//*/  
     
});//fin domready
function buscarDatos(Rec_ID, Anio){
        $.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Rec_ID: Rec_ID, Anio: Anio}, function(data){
            //alert(data);
            //return;
            if (data!="{}"){
                var obj = $.parseJSON(data);
                //alert(obj.total_legajos);
                $("#Rec_ID").val(obj.Rec_ID);
                $("#Rec_Cue_ID").val(obj.Rec_Cue_ID);
                $("#Rec_FechaCompra").val(obj.Rec_FechaCompra);
                $("#Rec_Usu_ID").val(obj.Rec_Usu_ID);
                $("#Rec_Fecha").val(obj.Rec_Fecha);
                $("#Rec_Hora").val(obj.Rec_Hora);
                $("#Rec_Importe").val(obj.Rec_Importe);
            }//fin if
        });     
    }//fin function    
    
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">
                  
                  
                  <th align="center">Deudor</th>
                  
                  <th align="center">Importe Anual</th>
                  <?php
                  global $gMes;
                  $sql = "SELECT MONTH(DRe_FechaRecibo)AS Mes FROM DeudorRecibo $where GROUP BY MONTH(DRe_FechaRecibo) ORDER BY Mes";
                  $result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                  //echo $sql;
                  if (mysqli_num_rows($result2) > 0){
                      $iMes = 0;
                    while ($row2 = mysqli_fetch_array($result2)){
                        $iMes++;
                        $Meses[$iMes] = $row2['Mes'];
                        $textoSuma = "cont".$Meses[$iMes];
                        $$textoSuma = 0;
                        echo '<th align="center">'.$gMes[$row2['Mes']].'</th>';
                    }//fin while
                  }//FIN IF
                  ?>
                  
                  
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
                    $i++;
                    if ($i%2==0) $clase = "fila";else $clase = "fila2";
                ?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">
                <td><?php echo '<a href="#" id="verCuenta'.$row['Deu_ID'].'">'.$row['Deu_Nombre'].'</a>';?></td>
                 </td>
                 
                 <td align="center"><?php echo number_format($row['Importe'],2,',','.');?></td>
                 
                 <?php
                    for ($k = 1; $k <= $iMes; $k++){
                        
                        $sql = "SELECT SUM(DRe_Importe)AS Importe FROM
    DeudorRecibo INNER JOIN Deudor ON (DRe_Deu_ID = Deu_ID) $where AND Deu_ID = $row[Deu_ID] AND MONTH(DRe_FechaRecibo) = $Meses[$k] ";
                        $result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                        if (mysqli_num_rows($result2) > 0){
                            $row2 = mysqli_fetch_array($result2);
                            if ($row2['Importe']>0){
                                $link = $Meses[$k]."-".$row['Deu_ID'];
                                if ($row['Deu_Nombre']!="BANCO CTA CTEXXXX"){
                                    $textoSuma = "cont".$Meses[$k];
                                    $$textoSuma += $row2['Importe'];
                                }
                                echo '<td align="right"><a href="#" id="link'.$link.'">'.number_format($row2['Importe'],2,',','.').'</a></td>';
                            }else{
                                echo '<td align="right">----</td>';
                            }
                        }else{
                            echo '<td align="right">----</td>';
                        }
                    }//fin for
                 ?>
                 
                 <!--<td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>-->
                </tr>
              
              <?php
              
              
                }//fin while
                echo "<tr><td colspan='2' align='right'><strong>TOTALES MENSUALES</strong></td>";
              for ($k = 1; $k <= $iMes; $k++){
                        
                    $textoSuma = "cont".$Meses[$k];                 
                    echo '<td align="right"><strong>'.number_format($$textoSuma,2,',','.').'</strong></td>';
                            
                    }//fin for
                echo "</tr>";
              ?>
            </tbody>
  </table>
 
<?php  $sql = "SELECT SUM(DRe_Importe)AS Importe FROM
    DeudorRecibo $where ";
    $result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result2) > 0){
        $row2 = mysqli_fetch_array($result2); 
        echo "Total Anual: $".number_format($row2[Importe],2,',','.');
    }//fin if
?>
 <a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
  <form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<?php
    }else{
        echo "No existen datos relacionados con la consulta hecha";
    }//fin if
}//fin function

function buscarDatosDeudorRecibo() {
    //echo "Hola";exit;
    $DRe_ID = $_POST['DRe_ID'];
    $Anio = $_POST['Anio'];
    
    $Tabla = "DeudorRecibo";
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM $Tabla WHERE YEAR(DRe_FechaRecibo)='$Anio'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//ya existe otro con ese nombre
        echo "{}";
    } else {
        $row = mysqli_fetch_array($result);

        $datos .= "{\"DRe_ID\": \"" . $row[DRe_ID] . "\",\"";
        $datos .= "DRe_Deu_ID\": \"" . $row[DRe_Deu_ID] . "\",\"";
        $datos .= "DRe_FechaRecibo\": \"" . $row[DRe_FechaRecibo] . "\",\"";
        $datos .= "DRe_Importe\": \"" . $row[DRe_Importe] . "\",\"";
        $datos .= "DRe_Detalle\": \"" . $row[DRe_Detalle] . "\",\"";
        $datos .= "DRe_FormaPago\": \"" . $row[DRe_FormaPago] . "\",\"";
        $datos .= "DRe_ChequeNumero\": \"" . $row[DRe_ChequeNumero] . "\",\"";
        $datos .= "DRe_ChequeBanco\": \"" . $row[DRe_ChequeBanco] . "\",\"";
        $datos .= "DRe_ChequeFecha\": \"" . $row[DRe_ChequeFecha] . "\",\"";
        $datos .= "DRe_Fecha\": \"" . $row[DRe_Fecha] . "\",\"";
        $datos .= "DRe_Hora\": \"" . $row[DRe_Hora] . "\",\"";
        $datos .= "DRe_Usu_ID\": \"" . $row[DRe_Usu_ID] . "\",\"";
        $datos .= "DRe_ReciboNumero\": \"" . $row[DRe_ReciboNumero] . "\",\"";
        $datos .= "DRe_DetallePago\": \"" . $row[DRe_DetallePago] . "\"}";
                
        echo $datos;
    }
 }//fin funcion
 
function eliminarDeudorRecibo() {
//echo "Hola";exit;
    $DRe_ID = $_POST['DRe_ID'];
    $UsuAut = $_POST['UsuAut'];
    $NdecNumero = $_POST['NdecNumero'];
    $ForID = 1;
    
    obtenerRegistroUsuario($DRe_Usu_ID, $DRe_Fecha, $DRe_Hora);     
    $CajaID = cajaAbiertaUsuario($DRe_Usu_ID);
    if (!$CajaID){
        echo "Error: La Caja se encuentra cerrada";
        exit;
    }
    $SCC_SCa_ID = obtenerSuperCaja();
    if (!$SCC_SCa_ID){
        echo "Error: No se encuentra abierta la SuperCaja.";
        exit;
    }
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $numeroNotaCredito = $NdecNumero;
    $Tabla = "DeudorRecibo";

    $sql = "SELECT * FROM $Tabla WHERE DRe_ID = '$DRe_ID'";//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "Error: El Ingreso elegido no existe o ya fue eliminado.";
    } else {
        $row = mysqli_fetch_array($result);
        $DRe_Deu_ID =  $row['DRe_Deu_ID'];
        $DRe_Importe = $row['DRe_Importe'];
        $DRe_ReciboNumero =  $row['DRe_ReciboNumero'];
        
        $sql = "SELECT * FROM Deudor WHERE Deu_ID = '$DRe_Deu_ID'";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0){
          
          $row = mysqli_fetch_array($result);

          $DRe_RazonSocial = $row['Deu_Nombre'];
          $UsuarioAutorizo = gbuscarPersonaUsuID($UsuAut);

          $Concepto = "Anulacion Deudor por Nota de Credito: ".$row['Deu_Nombre'];
          $Detalle = "Recibo anulado: $DRe_ReciboNumero - Recibo Nota Credito: $numeroNotaCredito. Autoriza $UsuarioAutorizo";

          generarFacturaDeudor($DRe_RazonSocial, $row['Deu_CUIT'], $NdecNumero, $DRe_Importe, $ForID, $Detalle, 3, $UsuAut);

          $CCCID = guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $DRe_Importe, 1, "Recibo anulado: $DRe_ReciboNumero");
          $SCC_FechaHora = "$DRe_Fecha $DRe_Hora";
          $sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CajaID, $CCCID, $SCC_SCa_ID, 'NOTA DE CRÉDITO $Concepto', $DRe_Importe, 0, '$SCC_FechaHora', '$Detalle', $DRe_Usu_ID)";
            $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
            if ($res["success"] == true){
                $SCC_ID= $res['id'];
            }else{
                echo "Mal";
            }

          actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);
        }
        //$sql = "DELETE FROM $Tabla WHERE DRe_ID = $DRe_ID";
        $sql = "UPDATE $Tabla SET DRe_Baja = 1, DRe_NotaCredito = '$numeroNotaCredito' WHERE DRe_ID = $DRe_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se realizó la Nota de Crédito del Ingreso seleccionado. Actualice para ver los cambios";
        
    }
    
}//fin function

function guardarOrdenIngreso(){    
    
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    obtenerRegistroUsuario($DRe_Usu_ID, $DRe_Fecha, $DRe_Hora);     
    $CajaID = cajaAbiertaUsuario($DRe_Usu_ID);
    if (!$CajaID){
        echo "Error: La Caja se encuentra cerrada";
        exit;
    }
    $SCC_SCa_ID = obtenerSuperCaja();
    if (!$SCC_SCa_ID){
        echo "No se encuentra abierta la SuperCaja.";
        exit;
    }
    $DRe_ID = $_POST['DRe_ID'];
    $DRe_Deu_ID = $_POST['DRe_Deu_ID'];
    $DRe_FechaRecibo = cambiaf_a_mysql($_POST['DRe_FechaRecibo']);
    $DRe_Importe = str_replace(",",".",$_POST['DRe_Importe']);
    //$DRe_ETi_ID = $_POST['Rec_ETi_ID'];
    //$DRe_TipoRecibo = $_POST['Rec_TipoRecibo'];
    $DRe_ReciboNumero = $_POST['DRe_ReciboNumero'];
    $DRe_FormaPago = $_POST['formaPago'];
    $DRe_RazonSocial = $_POST['txtRazonSocial'];
    $DRe_Detalle = $_POST['txtDetalleRecibo'];
    //$DRe_Autoriza = $_POST['Rec_Autoriza'];
    $DRe_ChequeNumero = $_POST['ChequeNumero'];
    $DRe_ChequeBanco = $_POST['ChequeBanco'];
    $DRe_ChequeFecha = cambiaf_a_mysql($_POST['ChequeFecha']);
    //Datos contables
    $CuentaOrigen = $_POST['CuentaOrigen'];
    $CuentaDestino = $_POST['CuentaDestino'];
    
    $Tabla = "DeudorRecibo";

    //Registramos el pago tradicionalmente
    
    $sql = "SELECT * FROM $Tabla WHERE DRe_ID = '$DRe_ID'";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe otro con ese nombre     
     //$mensaje = "Se actualizó correctamente los datos";
     $mensaje = "El N° de Recibo de Ingreso ya existe";
     exit;
  } else {     
      $sql = "INSERT INTO $Tabla (DRe_ID, DRe_Deu_ID, DRe_FechaRecibo, DRe_Importe, DRe_Detalle, DRe_FormaPago, DRe_ChequeNumero, DRe_ChequeBanco, DRe_ChequeFecha, DRe_Fecha, DRe_Hora, DRe_Usu_ID, DRe_ReciboNumero, DRe_DetallePago) VALUES('$DRe_ID', '$DRe_Deu_ID', '$DRe_FechaRecibo', '$DRe_Importe', '$DRe_Detalle', '$DRe_FormaPago', '$DRe_ChequeNumero', '$DRe_ChequeBanco', '$DRe_ChequeFecha', '$DRe_Fecha', '$DRe_Hora', '$DRe_Usu_ID', '$DRe_ReciboNumero', '$DRe_DetallePago')";
      $mensaje = "Se agregó un nuevo registro correctamente";
  }
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  
  //Realizamos el movimiento contable

  $sql = "SELECT * FROM Deudor WHERE Deu_ID = '$DRe_Deu_ID'";
  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
  if (mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_array($result);
    $Concepto = "Recibo de Ingreso N° $DRe_ID a Deudor: $row[Deu_Nombre] (CUIT $row[Deu_CUIT])";
    $Detalle = "$DRe_ReciboNumero - $DRe_Detalle";
    if ($DRe_FormaPago=="Efectivo") $ForID=1;
    if ($DRe_FormaPago=="Cheque"){
      $ForID=2; 
      $Detalle .=" - Cheque: $DRe_ChequeNumero. Banco: $DRe_ChequeBanco. Fecha: ".$_POST['ChequeFecha']; 
    } 
    if ($DRe_FormaPago=="Transferencia") $ForID=12;

    //Guardamos la salida de dinero del origen
    guardarMovimientoCuentaContable($CajaID, $CuentaOrigen, $Concepto, $DRe_Importe, 0, $ForID, $Detalle);

    //Guardamos la entrada de dinero del destino
    guardarMovimientoCuentaContable($CajaID, $CuentaDestino, $Concepto, 0, $DRe_Importe, $ForID, $Detalle);

    generarFacturaDeudor($DRe_RazonSocial, $row['Deu_CUIT'], $DRe_ReciboNumero, $DRe_Importe, $ForID, $DRe_Detalle, 1);

    /*$CCCID = guardarIngresoEgresoCajaCorriente($CajaID, $Concepto, $Rec_Importe, 1, $Detalle);
    $SCC_FechaHora = "$Rec_Fecha $Rec_Hora";
    $sql = "INSERT INTO SuperCajaCorriente (SCC_Caja_ID, SCC_CCC_ID, SCC_SCa_ID, SCC_Concepto, SCC_Debito, SCC_Credito, SCC_FechaHora, SCC_Detalle, SCC_Usu_ID) VALUES($CajaID, $CCCID, $SCC_SCa_ID, 'PAGOS $Concepto', $Rec_Importe, 0, '$SCC_FechaHora', '$Detalle', $Rec_Usu_ID)";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $SCC_ID = mysql_insert_id();
    actualizarSuperCajaCorriente($SCC_ID, $SCC_SCa_ID);*/
  }
  echo $mensaje;
        
}//fin function

function traerDatosDeudor(){
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $Deu_ID = $_POST['Deu_ID'];
    $sql = "SELECT * FROM Deudor WHERE Deu_ID=$Deu_ID";
    //echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        /*$datos[0] = $row[Rec_RazonSocial];
        $datos[1] = $row[Rec_CUIT];
        echo $datos;*/
        echo "$row[Deu_RazonSocial];$row[Deu_CUIT]";
    }else{
        echo false;
    }
}

function generarFacturaDeudor($Razon, $CUIT, $FacturaNumero, $FacturaImporteTotal, $ForID, $Detalle, $FTiID=1, $UsuAut=0) {
    //echo "Hola";exit;
    consulta_iniciar();
    $todo_bien = false;
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
    $CajaID = cajaAbiertaUsuario($UsuID);
    if (!$CajaID){
        echo "Error: La Caja se encuentra cerrada";
        exit;
    }
    
    //$FacturaNumero = $_POST['FacturaNumero'];
    list($Sucursal, $Numero) = explode("-",$FacturaNumero);
    //$Domicilio = $_POST['Domicilio'];
    //$Razon = $_POST['Razon'];
    //$CUIT = $_POST['CUIT'];
    //$FacturaImporteTotal = $_POST['FacturaImporteTotal'];
    //$FTiID = 1;//$_POST['FTiID'];
    $CVeID = 1;//$_POST['CVeID'];
    $IvaID = 5;//$_POST['IvaID'];
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $Fac_FechaAut = date("Y-m-d");
    $Fac_HoraAut = date("H:i:s");
    $sql = "INSERT INTO Factura (Fac_FTi_ID, Fac_Iva_ID, Fac_CVe_ID, Fac_Fecha, Fac_Hora, Fac_Usu_ID, Fac_CUIT, Fac_Sucursal, Fac_Numero, Fac_PersonaNombre, Fac_PersonaDomicilio, Fac_ImporteTotal, Fac_Pagada, Fac_Anulada, Fac_UsuAut, Fac_FechaAut, Fac_HoraAut) VALUES($FTiID, $IvaID, $CVeID, '$Fecha', '$Hora', $UsuID, '$CUIT', '$Sucursal', '$Numero', '$Razon', '$Domicilio', '$FacturaImporteTotal', 1, 0, $UsuAut, '$Fac_FechaAut', '$Fac_HoraAut')";
    //echo $sql;
    $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
    if ($res["success"] == true){
        $FacID = $res['id'];
    }else{
        echo "Mal";
    }
    
    //$ForID = $_POST['ForID'];
    //Insertamos el pago en la Caja Corriente
    
    guardarIngresoPagoCajaCorriente($CajaID, $Detalle, $FacturaImporteTotal, $ForID, $FacturaNumero);

    $sql = "INSERT INTO FacturaDetalle (FDe_Fac_ID, FDe_Item, FDe_Cantidad, FDe_Detalle, FDe_ImporteUnitario, FDe_Importe) VALUES($FacID, 1, 1, '$Detalle', '$FacturaImporteTotal', '$FacturaImporteTotal')";
            //echo "$sql<br />";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    if (!$todo_bien){ 
        consulta_retroceder();
    }else{
        consulta_terminar();
        //vaciarColaPagoUsuario();
    }
    //session_unregister('sesion_ultimoDNI');
    
}//fin function

//********************** Fin DeudorRecibo ***********************************



function buscarAutorizacion(){

    if ($_SESSION['sesion_UsuID']==2) {
        echo "Bien";
    }else{    
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $UsuAut = $_POST['UsuAut'];
        $Coordenadas = $_POST['Coordenadas'];
        $valor = $_POST['valor'];
        list($valor1,$valor2) = explode(" ", $Coordenadas);
        $iLetra = substr($valor1, 0,1);
        if (strlen($valor1)==3) $iNum = substr($valor1, -2); else $iNum = substr($valor1, -1);
        $TCo_Valor = substr($valor, 0,2);
        $sql = "SELECT * FROM TarjetaCoordenadas WHERE TCo_Usu_ID=$UsuAut AND TCo_Letra = '$iLetra' AND TCo_Numero = '$iNum' AND TCo_Valor = '$TCo_Valor'";//echo $sql;    
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        //echo $sql;
        if (mysqli_num_rows($result)>0){       
            //validamos la segunda parte
            $iLetra = substr($valor2, 0,1);
            if (strlen($valor2)==3) $iNum = substr($valor2, -2); else $iNum = substr($valor2, -1);
            $TCo_Valor = substr($valor, -2);
            $sql = "SELECT * FROM TarjetaCoordenadas WHERE TCo_Usu_ID=$UsuAut AND TCo_Letra = '$iLetra' AND TCo_Numero = '$iNum' AND TCo_Valor = '$TCo_Valor'";//echo $sql;        
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            //echo $sql;
            if (mysqli_num_rows($result)>0) {
                echo "Bien";
            }else{
                echo "El Código ingresado no es correcto";
                return;
            }       
        }else{
            echo "El Código ingresado no es correcto";
            return;
        }

    }
}

function buscarCodigoAutorizacion(){
    $Letras[1]="A";
    $Letras[2]="B";
    $Letras[3]="C";
    $Letras[4]="D";
    $Letras[5]="E";
    $Letras[6]="F";
    $Letras[7]="G";
    $Letras[8]="H";
    $Letras[9]="I";
    $Letras[10]="J";    
    
    $nLetra = rand(1, 10);
    $nNum = rand(1, 10);
    $mostrar = $Letras[$nLetra].$nNum;
    $nLetra = rand(1, 10);
    $nNum = rand(1, 10);
    $mostrar .= " ".$Letras[$nLetra].$nNum;
    echo $mostrar;

}//fin function

function listarMovimientosCuenta(){
    //echo "Hola";exit;
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $Cue_ID = $_POST['Cue_ID'];
    $Cue_Nombre = buscarNombreCuentaContable($Cue_ID);
    
    $sql = "SELECT * FROM CuentaMovimiento
    INNER JOIN Cuenta 
        ON (CMo_Cue_ID = Cue_ID)
    INNER JOIN CuentaTipo 
        ON (Cue_CuT_ID = CuT_ID)
    INNER JOIN FormaPago 
        ON (CMo_For_ID = For_ID)
    INNER JOIN Usuario 
        ON (CMo_Usu_ID = Usu_ID)
         WHERE Cue_ID = $Cue_ID";
         //echo $sql;
     $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
    
    $("#imprimirTodas").click(function(evento){
        evento.preventDefault();
        vCuenta = <?php echo $Cue_Nombre;?>;        
        $("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Movimientos de la Cuenta ' + vCuenta,overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css', media:'print'}]                                    
        });
        $("#cargando").hide();
     });//fin evento click//*/


    $("#barraExportar").click(function(evento){
        evento.preventDefault();
        jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
            if( r ){
                $("#archivo").val(r);
                $("#formExportarExcel").submit();
            } 
        });
        
    }); 
    $("#cargando").hide();   

    $("#verTodas").click(function(evento){
        evento.preventDefault();
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "buscarCuenta", Texto: "todos"},
            url: 'cargarOpciones.php',
            success: function(data){ 
                //alert(data);
                $("#principal").html(data);
                //$("#loading").hide();
            }
        });//fin ajax//*/         
    });
     
});//fin domready

</script>
<!-- <button class="botones" id="verTodas"><< Volver a las Cuentas Contables</button> -->
<h1>Movimientos de la Cuenta <?php echo $Cue_Nombre;?></h1>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">
                  <th align="center">ID</th>
                  <th align="left">Concepto</th>
                  <th align="center">Débito</th>
                  <th align="center">Crédito</th>
                  <th align="center">Forma Pago</th>
                  <th align="center">Fecha</th>
                  <th align="center">Hora</th>
                  <th align="center">Usuario</th>
                  <th align="center">Detalle</th>
                  <th align="center">Caja</th>
                </tr>
  </thead>
                <tbody>
                <?php
                $totalDebito = 0;
                $totalCredito = 0;
                while ($row=mysqli_fetch_array($result)){
                    $i++;
                    if ($i%2==0) $clase = "fila";else $clase = "fila2";
                    $totalDebito += $row[CMo_Debito];
                    $totalCredito += $row[CMo_Credito];
                ?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">  
                    <td align="center"><?php echo $row['CMo_ID'];?> </td>
                  <td align="left"><?php echo $row['CMo_Concepto'];?>
                  <input type="hidden" id="CMo_ID<?php echo $i;?>" value="<?php echo $row[CMo_ID];?>" />  
                 </td>
                 <td align="right"><?php echo number_format($row[CMo_Debito],2,",",".");?></td>
                 <td align="right"><?php echo number_format($row[CMo_Credito],2,",",".");?></td>
                 <td align="center"><?php echo $row[For_Nombre];?></td>
                 <td align="center"><?php echo cfecha($row[CMo_Fecha]);?></td>
                 <td align="center"><?php echo $row[CMo_Hora];?></td>
                 <td align="center"><?php echo $row[Usu_Nombre];?></td>
                 <td align="left"><?php echo $row[CMo_Detalle];?></td>
                 <td align="center"><?php echo $row[CMo_Caja_ID];?></td>
                </tr>
              
              <?php
                }//fin while
              ?>
              <tr class="fila_titulo">  
                 <td align="right" colspan="2">TOTALES</td>
                 <td align="right"><?php echo number_format($totalDebito,2,",",".");?></td>
                 <td align="right"><?php echo number_format($totalCredito,2,",",".");?></td>
                 <td align="center"></td>
                 <td align="center"></td>
                 <td align="center"></td>
                 <td align="center"></td>
                 <td align="center"></td>
                 <td align="center"></td>
                </tr>
            </tbody>
  </table>
<?php
//echo "<p class='texto'>Resultado total: ".mysqli_num_rows($result)." movimientos</p>";
?>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "<span class='texto'>Resultado total: ".mysqli_num_rows($result)." movimientos</span>";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<?php
    }else{
        echo "No existen datos relacionados con la consulta hecha";
    }//fin if
}//fin function

function cambiarDocenteClase() {
    //echo "Hola";exit;
    $Cla_ID = $_POST['Cla_ID'];
    $Doc_ID = $_POST['Doc_ID'];
    
    $sql = "SELECT * FROM Colegio_Clase WHERE Cla_ID = $Cla_ID";

    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        echo "La clase elegida no existe";
        exit;
    } else {
        
        $sql = "UPDATE Colegio_Clase SET Cla_Doc_ID = $Doc_ID WHERE Cla_ID = $Cla_ID"; 
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        echo "Se cambió el docente correctamente.";
        
    }
}//fin funcion
function buscarNivID() {
    //echo "Hola";exit;
    $Ori_ID = $_POST['OriID'];
    
    
    $sql = "SELECT * FROM Colegio_Orientacion WHERE Ori_ID = $Ori_ID";

    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//no existe
        $row=mysqli_fetch_array($result);
        echo $row['Ori_Niv_ID'];
        
    }
}//fin funcion

function borrarInscripcionLectivo() {
//echo "Hola";exit;
    $Lec_ID = $_POST['LecID'];
    $Leg_ID = $_POST['LegID'];

    $sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $Lec_ID AND Ins_Leg_ID = $Leg_ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "El alumno no tiene inscripción o ya fue eliminada.";
    } else {
        $sql = "INSERT INTO Colegio_InscripcionEliminado (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Provisoria, Ins_Fecha, Ins_Hora) SELECT Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Provisoria, Ins_Fecha, 
Ins_Hora FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $Lec_ID AND Ins_Leg_ID = $Leg_ID";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $sql = "DELETE FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $Lec_ID AND Ins_Leg_ID = $Leg_ID";
        //echo $sql;
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        echo "Se eliminó la inscripción correctamente.";

    }
}//fin funcion


function buscarPersonaDeuda(){
    
    $texto = $_POST['Texto'];
    $where="";
    $vieneApeNom=0;
    $ape="";
    $nom="";
    $findme = ',';
    $pos = strpos($texto, $findme);
    
    //si viene coma, entonces viene apellido y nombre
    if ($pos !== false) {
        $vieneApeNom=1;
        //asigno el apellido y el nombre
        list($ape, $nom) = explode(",", $texto);
        $ape=trim($ape);
        $nom=trim($nom);
    }else {
        $vieneApeNom=0;
        $ape="";
        $nom="";
    }

    //echo "Hola";exit;
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    $Tabla = "Persona INNER JOIN PersonaEstado ON (Per_Baja = PEs_ID)";
    
    if ($texto!="todos") {
        if ($vieneApeNom==0){
          $where = "WHERE (Per_DNI = '$texto' OR Per_Apellido LIKE '%$texto%')";
        }else {
          $where = "WHERE (Per_Apellido LIKE '%$ape%' AND Per_Nombre LIKE '%$nom%')";  
        }
    }else $where="";

    $sql = "SELECT * FROM $Tabla $where ORDER BY Per_Apellido, Per_Nombre";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    //echo $sql;
    if (mysqli_num_rows($result) > 0){

?>

<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
   <thead>
    <tr class="fila_titulo" height="30">
      <th align="center">#</th>
      <th align="center">Apellido y Nombre</th>
      <th align="center">DNI</th>
      <th align="center">Sexo</th>
      <th align="center">Legajo</th>
      <th align="center" title="Indica el Estado de Baja">Estado</th>
      <th align="center">Deuda</th>
    </tr>
    </thead>
    <tbody>
    <?php
    while ($row=mysqli_fetch_array($result)){
        $i++;
        if ($i%2==0) $clase = "fila";else $clase = "fila2";
    ?>
    <tr class="<?php echo $clase;?>"  height="30" id="fila<?php echo $i;?>" title="Fecha carga persona <?php echo cfecha($row[Per_Fecha])." ".$row[Per_Hora];?>">                 
      <td align="center"><a name="<?php echo $row[Per_ID];?>" id="<?php echo $row[Per_ID];?>"></a><?php echo $row[Per_ID];?>
        <input type="hidden" id="Per_ID<?php echo $i;?>" value="<?php echo $row[Per_ID];?>" /></td>
     <td>
      <span id="Nombre<?php echo $i;?>"><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></span></td>
     <td align="center"><?php echo $row[Per_DNI];?></td>
     <td align="center"><?php echo $row[Per_Sexo];?></td>
     <td align="center"><span id="Legajo<?php echo $i;?>"><?php $legajo = buscarLegajoAspirante($row[Per_ID]);echo $legajo;?></span></td>
     <td align="center">
     <?php 
     if ($row[PEs_Nombre]!="Normal") 
        echo "<span class='nota_Reprobado'>$row[PEs_Nombre]</span>";
    else
        echo $row[PEs_Nombre];
     
     ?></td>
                       
      <td align="right">
    <?php          
        $deuda_alumno=0;
        $deuda_alumno=Obtener_Deuda_Sistema($row['Per_ID'], $Recargos);
        echo $deuda_alumno;
    ?>
     </td>
    </tr>
  
  <?php
    }//fin while
  ?>
</tbody>
</table>
Total de personas: <?php echo mysqli_num_rows($result);?>
<?php
    }else{
        echo "No existen datos relacionados con la consulta hecha";
    }//fin if

}//fin function

//Mario. 21 02 2022
function buscarDatosPersonaBajaFicha($Per_ID) {
    //echo "Hola";exit;
    //$Per_ID = $_POST['Per_ID'];
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM Persona INNER JOIN PersonaEstado ON (Per_Baja = PEs_ID) WHERE Per_ID='$Per_ID' AND PEs_ID=1";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        ?>
        <div class='ui-widget'>
            <div class='ui-state-error ui-corner-all' style='padding: 0 .7em;'>
                <p><span style='float: left; margin-right: .3em;' class='ui-icon ui-icon-alert'></span>
                    BAJA <br />
                    Motivo: <strong><?php echo $row[Per_BajaMotivo];?></strong> <br />
                    Fecha Hora: <strong><?php echo cfecha($row[Per_BajaFecha])." ".$row[Per_BajaHora];?></strong>
                </p>
            </div>
        </div>
        <?php           
    }//fin if   
   
    //baja de legajo
    $sql = "SET NAMES UTF8";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM Legajo WHERE Leg_Per_ID = '$Per_ID' AND Leg_Baja =1;";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        ?>
        <div class='ui-widget'>
            <div class='ui-state-error ui-corner-all' style='padding: 0 .7em;'>
                <p><span style='float: left; margin-right: .3em;' class='ui-icon ui-icon-alert'></span>
                    BAJA DE LEGAJO<br />
                    Motivo: <strong><?php echo $row[Leg_Baja_Motivo];?></strong> <br />
                    Fecha: <strong><?php echo cfecha($row[Leg_Baja_Fecha]);?></strong>
                </p>
            </div>
        </div>
        <?php           
    }//fin if       
}

function obtenerNombreUsuario($Id_usuario) {
    $sql5 = "SELECT Usu_Nombre, Usu_Persona FROM Usuario WHERE Usu_ID = '$Id_usuario'";
    $re = consulta_mysql_2022($sql5,basename(__FILE__),__LINE__);
    $row = mysqli_fetch_array($re);
    return $row[Usu_Persona];
}
?>