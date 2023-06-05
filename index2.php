<?php
require_once("globales.php");
require_once("conexion.php");
global $NombreSesion, $gIndex;

if (!isset($_SESSION['NombreSesion'])) {
    session_name($NombreSesion);
    // inicia sessiones
    session_start();
    $_SESSION['NombreSesion'] = $NombreSesion;
}

require_once("funciones_generales.php");
require_once("listas.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="shortcut icon" href="favicon.ico" >
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
            <title>GITECO - Gesti&oacute;n Integral de Terciarios y Colegios: Universidad Cat&oacute;lica de Cuyo</title>

            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" >  
                <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>

                <?php
                include_once("plantilla_cabecera.php");
                ?>
                <script language="javascript">

                    $(document).ready(function()
                    {

                        //Revisamos si el usuario es multi-unidad
<?php
if (isset($_SESSION['sesion_usuario'])) {
    $sql = "SELECT DISTINCTROW Unidad.* FROM Permiso, Unidad WHERE (Permiso.Prm_Usu_ID = '" . $_SESSION['sesion_UsuID'] . "' AND Prm_Uni_ID = Uni_ID)";
    $result_unidad = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result_unidad) > 1) {
        
    } else {
        $row_unidad = mysqli_fetch_array($result_unidad);
        echo "cargarLogo('" . $row_unidad[Uni_Foto] . "');";
        //echo "alert('$row_unidad[Uni_Foto]');";
    }
}

if ($_SESSION['sesion_rol'] == 1) {
    echo "$('#CambiarSedID').val('" . $_SESSION['sesion_SedID'] . "');";
    echo "$('#CambiarUniID').val('" . $_SESSION['sesion_UniID'] . "');";
}//*/
?>
	
	
<?php
if (isset($_SESSION['sesion_usuario'])) {
    $UsuID = $_SESSION['sesion_UsuID'];
    $sql = "SELECT Opc_ID, COUNT(*) AS Total FROM
                    AccesoOpcion
                    INNER JOIN Login 
                        ON (AccesoOpcion.Acc_Log_ID = Login.Log_ID)
                                INNER JOIN Opcion 
                        ON (AccesoOpcion.Acc_Men_ID = Opcion.Opc_Men_ID) AND (AccesoOpcion.Acc_Opc_ID = Opcion.Opc_ID)
                    INNER JOIN Usuario 
                        ON (Login.Log_Usu_ID = Usuario.Usu_ID) WHERE Usu_ID = $UsuID AND CHAR_LENGTH(Opc_Ruta)>3 GROUP BY Opc_ID ORDER BY Total DESC LIMIT 0,5";
    //echo $sql;//exit;
    //$sql = "SELECT * FROM Persona WHERE Per_ID = -1";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    if (mysqli_num_rows($result) > 0) {
        $mostrar = '<table width="70%" border="0" cellspacing="1" cellpadding="1"><tr>';
        while ($row = mysqli_fetch_array($result)) {
            traerDatosOpcion($row[Opc_ID], $nombre, $ruta, $comando);
            //$mostrar .= "$row[Opc_Nombre]";
            //$mostrar.="$nombre, $ruta, $comando";
            $mostrar .= '<td align="center"><a href="#" id="' . $comando . '" title="' . $nombre . '" class="acceso_directo"><img src="iconos/' . $ruta . '" border="0" /><br />' . $nombre . '</a></td>';
        }//fin while
        $mostrar .= '</tr></table>';
        ?>			
                        var $tabs = $('#tabs').tabs();
                        $(this).oneTime(5000, function() {	
                            $tabs.tabs('select', 2);	
                        });//*/
                			
                			
        <?php
    }//fin if//*/
}//fin if general
?>
	
	
    });//fin ready
                </script>

                <style type="text/css">
                    <!--
                    .Estilo1 {font-weight: bold}
                    -->
                </style>
                </head>

                <body>
                    <table width="950" border="0" align="center">

                        <tr><td class="ui-state-default ui-corner-all"><div align="center"><a href="<?php echo $gIndex; ?>"><img src="imagenes/logo_uccuyo.jpg" name="logo" width="90" height="100" border="0" id="logo" /></a></div><br />
                                <div id="mostrarCambiarUnidad">
                                    <?php
                                    if ($_SESSION['sesion_rol'] == 1) {
                                        ?>Cambiar Sede<br /><?php
                                    cargarListaSede("CambiarSedID");
                                        ?>
                                        <br />Cambiar Unidad<br />
                                        <?php
                                        cargarListaUnidad("CambiarUniID");
                                        ?>
                                        <br /><button id="cambiarUnidad" class="botones">Cambiar</button>
                                        <?php
                                    }//*/
                                    ?>

                                </div></td>
                            <td><div class="ui-state-default ui-corner-all">
                                    <div align="center" class="Estilo1">
                                        <table width="70%" border="0" align="center" cellpadding="1" cellspacing="1">
                                            <tr>
                                                <td width="20%" class="texto"><a href="<?php echo $gIndex; ?>"><img src="logos/logo_Giteco.png" alt="GITeCo" name="logoGITECO" width="161" height="34" border="0" id="logoGITECO" /></a></td>
                                                <td width="49%" class="texto"><?php
                                    if (isset($_SESSION['sesion_usuario'])) {
                                        $saludo = buscarSaludo();
                                        echo "$saludo <strong>" . $_SESSION['sesion_persona'] . "</strong><br />";
                                        if (isset($_SESSION['sesion_nombrerol']))
                                            echo " (" . $_SESSION['sesion_nombrerol'] . ") <i>Sede " . $_SESSION['sesion_Sede'] . "</i>";
                                    }
                                    ?></td>
                                                <td width="31%" align="right">

                                                    <?php if (isset($_SESSION['sesion_usuario'])) {
                                                        ?>
                                                        <a href="#" id="barraCambiarClave"><img src="imagenes/key_go.png" alt="Cambiar clave" width="32" height="32" border="0" align="absmiddle" />Cambiar clave</a><a href="#" id="barraSesion"><img src="botones/cerrar_sesion.gif" alt="Cerrar Sesi&oacute;n" width="32" height="32" border="0" align="absmiddle" title="Cerrar Sesi&oacute;n" />Salir</a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div><div id="tabs">
                                    <ul>				
                                        <li><a href="#Comienzasesion">Registrarse</a></li>
                                        <li><a href="#tabs-1">Avisos</a></li>
                                        <li><a href="#tabs-2">Accesos directos </a></li>
                                        <li><a href="#tabs-3">Contactos</a></li>

                                    </ul>
                                    <div id="Comienzasesion">
                                        <?php
                                        if (isset($_SESSION['sesion_usuario'])) {
                                            ?>
                                            <table width="70%" border="0" align="left" cellpadding="1" cellspacing="1">
                                                <tr>
                                                    <td><?php
                                        $saludo = buscarSaludo();
                                        echo "$saludo <strong>" . $_SESSION['sesion_persona'] . "</strong>";
                                        if (isset($_SESSION['sesion_nombrerol']))
                                            echo " (" . $_SESSION['sesion_nombrerol'] . ")";
                                            ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <?php
                                        }else {
                                            ?>
                                            <div id="sesion" align="left">
                                                <span class="advertencia_sesion"><strong>Por favor debe loguearse primero</strong></span> <br />
                                                <table width="300" border="0" align="left">
                                                    <tr>
                                                        <td rowspan="2" valign="middle" class="texto_sesion"><img src="botones/system-users.gif" width="32" height="32" /></td>
                                                        <td class="texto_sesion">Usuario</td>
                                                        <td class="texto_sesion">Clave</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input name="sesion_usuario" type="text" class="input_sesion" id="sesion_usuario" /></td>
                                                        <td><input name="sesion_clave" type="password" class="input_sesion" id="sesion_clave" /></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <table border="0" class="barra_boton texto">
                                                <tr>
                                                    <td ><a href="#" id="barraSesion" title="Iniciar Sesi�n" class="sinLink"><img src="botones/iniciar_sesion.gif"  width="32" height="32" border="0" align="absmiddle"/> Iniciar sesi&oacute;n</a></td>
                                                </tr>
                                            </table> 
                                            <br />
                                            <span class="textoInformativo">Si Ud. es <strong>PADRE DE UN ALUMNO</strong> y quiere iniciar sesi&oacute;n por <strong>PRIMERA VEZ</strong>, haga<a href="#" class="ui-state-default" id="sesionPadre"> <strong>click aqui</strong></a>
                                            </span>
                                            <?php
                                        }//fin else
                                        ?>

                                    </div>
                                    <div id="tabs-1">Usted no tiene avisos por parte del sistema .</div>
                                    <div id="tabs-2"><?php
                                        if (!empty($mostrar)) {
                                            echo $mostrar;
                                            ?>

                                            <?php
                                        } else {
                                            ?>Aqui podr&aacute; acceder a las opciones m&aacute;s frecuentes de uso. 
                                        <?php }
                                        ?>
                                    </div>
                                    <div id="tabs-3">Desde aqui podr&aacute; ponerse en contacto con otros usuarios del sistema.</div>

                                </div> </td>

                        </tr>
                    </table>
                    <table width="950" border="0" align="center">
                        <tr>
                            <td width="200" valign="top">
                                <div align="center"></div>
                                <?php
                                if (isset($_SESSION['sesion_usuario'])) {
                                    $usuario = $_SESSION['sesion_UsuID'];
                                    if ($usuario == "2") {
                                        echo "<div class='accordion'>";
                                        $sql = "SELECT * FROM Menu ORDER BY Men_Orden";
                                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <h1><a href="#"><?php echo $row['Men_Nombre']; ?></a></h1>				
                                            <div><?php echo cargarOpcionesMenu($row['Men_ID']); ?></div>

                                            <?php
                                        }//fin del while
                                        echo "</div>";
                                    } else {
                                        $sql = "SELECT DISTINCTROW Unidad.* FROM Permiso, Unidad WHERE (Permiso.Prm_Usu_ID = '$usuario' AND Prm_Uni_ID = Uni_ID)";
                                        $result_unidad = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                        while ($row_unidad = mysqli_fetch_array($result_unidad)) {
                                            ?>
                        <!--<h4><?php echo $row_unidad[Uni_Siglas]; ?></h4>-->
                                            <script language="javascript">cargarLogo("<?php echo $row_unidad[Uni_Foto]; ?>");</script>
                                            <div class="accordion">
                                                <?php
                                                $sql = "SELECT DISTINCTROW Menu.Men_ID, Menu.Men_Nombre, Permiso.Prm_Usu_ID FROM Permiso
			  INNER JOIN Menu 
			  ON (Permiso.Prm_Men_ID = Menu.Men_ID)
			  WHERE (Permiso.Prm_Usu_ID = '$usuario' AND Prm_Uni_ID = $row_unidad[Uni_ID]) ORDER BY Men_Orden";
                                                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                    <h1><a href="#"><?php echo $row['Men_Nombre']; ?></a></h1>				
                                                    <div><?php echo cargarOpcionesMenuUsuario($row['Men_ID'], $usuario); ?></div>

                                                <?php }//fin del while menu
                                                ?>
                                            </div>
                                            <?php
                                        }//fin del while unidad
                                    }//fin if 
                                }//fin if sesion
                                ?>

                                <br /><br />
                                <div id="datepicker"></div>
                            </td>
                            <td valign="top"><div class="ui-state-default ui-corner-all"><div id="principal" style="background-color:#FFFFFF">
                                        <br />
                                        <?php
                                        if ($usuario)
                                            cargarAlertaMensajeUsuario($usuario);
                                        cargarNoticias();
                                        ?>
                                        <br />
                                    </div></div></td>
                        </tr>
                    </table>


                    <div id="mostrar"></div>
                    <div id="mostrar_otros"></div>

                    <table width="950" border="0" align="center" class="ui-state-default ui-corner-all">
                        <tr>
                            <td><div align="center">&copy; <?php echo date("Y"); ?> - Desarrollado por Napta Soluciones Web </div></td>
                        </tr>
                    </table>
                    <div id="cargando" style="display:none; color:green;"><img src="imagenes/cargando.gif" alt="Cargando...por favor espere" />Cargando...</div>
                    <div id="dialog" title="Dialog Title">I'm in a dialog</div>
                </body>
                </html>
