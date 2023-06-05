<?php
//ob_start(); 
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

date_default_timezone_set('America/Argentina/San_Juan');
//phpinfo();exit;
/* if ( $_POST['clave'] != "sistemas2" ){
  echo "<form name='form1' method='post' action='index.php'>
  Escriba la clave correcta antes de continuar:
  <input id='clave' name='clave' type='password'>
  </form>";
  exit;
  }// */
//echo "Hola";die();
/* session_name("sesion_abierta");
  session_start();
  session_unset();
  session_destroy();// */

session_name("sesion_abierta");
// incia sessiones
session_start();

if (!isset($_SESSION['sesion_UsuID'])){
	header("Location: index-nuevo.php");
}
$Server=$_SERVER['SERVER_NAME'];

require_once("conexion.php");

require_once("funciones_generales.php");
//require_once("listas.php");
//echo "Bien ".date("H:i:s");exit;



//para poner el sitio en mantenimiento
global $gMantenimiento;
global $gFechaIniMant;
global $gFechaFinMant;
$fechaHoy=date("Y-m-d");
if ($fechaHoy>=$gFechaIniMant && $fechaHoy<=$gFechaFinMant){
    if ($gMantenimiento) {
        header("Location: index_mantenimiento.php");
    }
}
//************************************



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="shortcut icon" href="favicon.ico" >
            <!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>NAPTA COLEGIOS - <?php echo COLEGIO_NOMBRE;?></title>

            <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" >  
                <link href="css/general.css" rel="stylesheet" type="text/css"  media="screen"/>
                <link href="js/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
                <link href="js/tipsy.css" rel="stylesheet" type="text/css" media="screen" />
                <!--<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>-->
                <script type="text/javascript" src="js/jquery-1.6.2.js"></script>
                 <!--<script type="text/javascript" src="js/jquery-1.9.1.js"></script>-->
                <script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script>
                <script type="text/javascript" src="js/jquery.form.js"></script>
                <script type="text/javascript" src="JAjax.js"></script>
                <script language="javascript" src="js/AjaxUpload.2.0.min.js" type="text/javascript"></script>
                <script src="js/jquery.validate.js" type="text/javascript"></script>
                <script src="js/jquery.alerts.js" type="text/javascript"></script>
                <script src="js/jquery.tipsy.js" type="text/javascript"></script> 
                <script type="text/javascript" src="js/jquery.purr.js"></script>
                <script src="js/jquery.bpopup.js" language="javascript"></script>


                <!--<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
                <script src="js/jquery.Jcrop.js"></script>
                -->

                <script language="javascript">
                    $(function(){
                        //                         Tabs
                        $("#tabs").tabs();
                        //                         Datepicker
                        $("#datepicker").datepicker({
                            inline: true
                        });//*/
                        // Accordion
                        $(".accordion").accordion({ header: "h1" });
                        //Inicializamos y Ocultamos el cuadro de dÃ¯Â¿Â½alogo		
                        $("#dialog").dialog({ draggable: false, hide: 'slide' });
                        $("#dialog").dialog("destroy");
                        //		        $(acordeon).slideUp('slow'); 
                    });//fin de la funcion
                                
                        
                    function cargarLogo(logo){
                        //alert("logos/"+logo+".jpg");
                        $("#logo").attr("src","logos/logo_smo.png");
                        $("#logo").attr("width","120");
                    }
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
        echo "cargarLogo('" . $row_unidad['Uni_Foto'] . "');";
        //echo "alert('$row_unidad[Uni_Foto]');";
    }
}

if ($_SESSION['sesion_rol'] == 1) {
    echo "$('#CambiarSedID').val('" . $_SESSION['sesion_SedID'] . "');";
    echo "$('#CambiarUniID').val('" . $_SESSION['sesion_UniID'] . "');";
}//*/
?>
        $("#cerrarSesion").click(function(evento){
            evento.preventDefault();
            //mostrarAlerta("Se ha cerrado la sesiÃ¯Â¿Â½n correctamente", "Cerrar SesiÃ¯Â¿Â½n");
            //$.post("cerrar_sesion.php");
            //            document.location.href = "http://www.uccuyo.edu.ar//cerrar_sesion.php";
            //alert("Se cerro");
        });
	
        $("#sesion_clave").keyup(function(evento){
            evento.preventDefault();
            if (evento.keyCode == 13){
			
                $("#cargando").show();
                cargarSesion();		
                $("#cargando").hide();		
			
            }//fin if
        });//fin de prsionar enter		
        function cargarSesion(){
            if ($("#sesion_usuario").length > 0){
		
                usuario = $("#sesion_usuario").val();
                clave = $("#sesion_clave").val();
                if ( usuario.length==0 || clave.length==0 ){
                    mostrarAlerta("Por favor escriba un <strong>nombre de usuario</strong> y/o <strong>clave</strong> antes de continuar","Falta usuario y/o contraseÃ±a");						
                }else{
                    $.ajax({
                        type: "POST",
                        cache: false,
                        async: false,
                        data: {sesion_usuario: usuario, sesion_clave: clave},
                        url: 'registro_sesion.php',
                        success: function(data){
                            if (data.trim()=="Bien"){
                                //alert("Hola <?php  //echo $_SESSION['sesion_usuario'];                          ?>");
                                document.location = "index.php";
							
                            }else{							
                                mostrarAlerta("El <strong>usuario</strong> y/o la combinaciÃ³n de la <strong>contraseÃ±a</strong> ingresa no son correctas.","Error de inicio de sesiÃ³n");
								/*$("#sesion").html(data);
                                $("#barraSesion").attr("src", "botones/cerrar_sesion.gif");			
                                $("#barraSesion").attr("alt", "Cerrar SesiÃ³n");
                                $("#barraSesion").attr("title", "Cerrar SesiÃ³n");
                                $("#barraSesion").attr("style", "cursor:pointer");//*/
                            }
                        }//fin success
                    });//fin ajax			
                }	
            }else{//cerramos la sesion
                $("#cargando").hide();
                document.location.href = "cerrar_sesion.php";
            }	
        }//fin function
		
        $("#barraSesion, #barraSesion2").click(function(evento){
            evento.preventDefault();
            $("#cargando").show();
            cargarSesion();		
            $("#cargando").hide();
        });
	
        $("#barraCambiarClave").click(function(evento){
            evento.preventDefault();
            //alert("Cambiar clave");
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                //data: {sesion_usuario: usuario, sesion_clave: clave},
                url: 'cargarCambiarClave.php',
                success: function(data){
                    $("#principal").html(data);
                    $("#cargando").hide();
			 
                }//fin success
            });//fin ajax	
        });
        $("#sesionPadre").click(function(evento){
            evento.preventDefault();
            //alert("Cambiar clave");
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                //data: {sesion_usuario: usuario, sesion_clave: clave},
                url: 'cargarSesionPadre.php',
                success: function(data){
                    $("#principal").html(data);
                    $("#cargando").hide();
			 
                }//fin success
            });//fin ajax	
        });
        
        function mostrarNotificacion(titulo, mensaje){
            var notice = '<div class="notice">'
                + '<div class="notice-body">'
                + '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">'+
                '<tr><td><img src="imagenes/info_notificacion.png" /></td>' +
                '<td>'	 + '<h3>' + titulo + '</h3>'
                + '<p>' + mensaje + '</p></td></tr></table>'
                + '</div>'
                + '<div class="notice-bottom">'
                + '</div>'
                + '</div>';
            $( notice ).purr();	
	
        }
        /*$(this).oneTime(1000, function() {	
            //$(this).parent(".main-window").hide();
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'VerUsuarioConectado'},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    if (data)
                        mostrarNotificacion("Usuarios conectados", data);
                }
            });//fin ajax///
        });//*/
									 
        /*$(this).everyTime(500000, function() {								   
            //$(this).parent(".main-window").hide();
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'VerUsuarioConectado'},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    if (data)
                        mostrarNotificacion("Usuarios", data);
                }
            });//fin ajax///
        });//*/
	
<?php 
if (isset($_SESSION['sesion_usuario'])) {
    $UsuID = $_SESSION['sesion_UsuID'];
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT Opc_ID, COUNT(*) AS Total FROM
                    AccesoOpcion
                    INNER JOIN Login 
                        ON (AccesoOpcion.Acc_Log_ID = Login.Log_ID)
                                INNER JOIN Opcion 
                        ON (AccesoOpcion.Acc_Men_ID = Opcion.Opc_Men_ID) AND (AccesoOpcion.Acc_Opc_ID = Opcion.Opc_ID)
                    INNER JOIN Usuario 
                        ON (Login.Log_Usu_ID = Usuario.Usu_ID) WHERE Usu_ID = $UsuID AND CHAR_LENGTH(Opc_Ruta)>3 GROUP BY Opc_ID ORDER BY Total DESC LIMIT 0,8";
    //echo $sql;//exit;
    //$sql = "SELECT * FROM Persona WHERE Per_ID = -1";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    if (mysqli_num_rows($result) > 0) {
        $mostrar = '<table width="100%" border="0" cellspacing="1" cellpadding="1"><tr>';
        while ($row = mysqli_fetch_array($result)) {
            traerDatosOpcion($row[Opc_ID], $nombre, $ruta, $comando);
			$largo = strlen($nombre);
			if ($largo>12){
				$nombre = substr($nombre,0,14)."...";
			}
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
        $(".botones").button();
		//$(".boton").button();
        $("#cambiarUnidad").click(function(evento){
            vCambiarSedID = $("#CambiarSedID").val();
            vCambiarUniID = $("#CambiarUniID").val();
            vSede = $("#CambiarSedID option:selected").attr('id');
            vSede = $("#CambiarSedID option[value=" + vCambiarSedID + "]").text();
		
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'cambiarUnidadUsuario', SedID: vCambiarSedID, UniID: vCambiarUniID, Sede: vSede},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
                        document.location.href = "index.php";
                    }
                }
            });//fin ajax///
        });//*/
        //Funcion que marca el mensaje noticia como leido y lo quita de la vista
        $("a[id^='cerrarID']").click(function(evento){
            evento.preventDefault();		
            ID = this.id.substr(8,10);
            $("#cargando").show();		
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'marcarMensajeLeido', DesID: ID},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
                        $("#noticiaID" + ID).fadeOut();
                    }
                }
            });//fin ajax///
            $("#cargando").hide();
        });
	
        $("#guardarDatos").button();
	
        $("#misDatosPersonales2").click(function(evento){
            evento.preventDefault();	
           $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                //data: {opcion: 'marcarMensajeLeido', DesID: ID},
                url: 'cargarAlertaEmail.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
                        $("#principal").html(data);
                    }
                }
            });//fin ajax///
        });	
        $("#btnCargarTodos").click(function(evento){
            evento.preventDefault();    
            cargarPagosInformados("todos");   
        });
        <?php
        if ($_SESSION['sesion_UsuCaja']==1){
            //echo 'cargarPagosInformados("faltantes");';
        }
        ?>
        
        function cargarPagosInformados(tipo){            
           $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);}, 
                data: {tipo: tipo},
                url: 'cargarPagosInformados.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
                        $("#principal").html(data);
                    }
                }
            });//fin ajax///
        }//fin function
	
        $("#menuContactos").click(function(evento){
            evento.preventDefault();	
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                //data: {sesion_usuario: usuario, sesion_clave: clave},
                url: 'cargarContactos.php',
                success: function(data){
                    $("#principal").html(data);
                    $("#cargando").hide();
			 
                }//fin success
            });//fin ajax	
        });	
        $("#cambiarFotoUsuario").click(function(evento){
            evento.preventDefault();	
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                //data: {sesion_usuario: usuario, sesion_clave: clave},
                url: 'cargarSubirFotoUsuario.php',
                success: function(data){
                    $("#principal").html(data);
                    $("#cargando").hide();
			 
                }//fin success
            });//fin ajax	
        });	
        $("#menuMostrarUsuarios").click(function(evento){
            evento.preventDefault();	
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                //data: {sesion_usuario: usuario, sesion_clave: clave},
                url: 'cargarUsuariosConectados.php',
                success: function(data){
                    $("#principal").html(data);
                    $("#cargando").hide();
			 
                }//fin success
            });//fin ajax	
        });	
        
	
	
        $("#verFotoUsuario").click(function(evento){
            evento.preventDefault();	
            var vDNI = "<?php  echo $_SESSION['sesion_usuario']; ?>";
            //alert(vDNI);
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: "buscarFoto", DNI: vDNI, DocID: '', ancho: "500", usuario: true},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    //mostrarAlerta(data, "Foto del Docente");
                    $("#dialog").html(data);
                    $("#dialog").dialog({ draggable: true, hide: 'slide', title: 'Foto del Usuario', zIndex: 3900, resizable: true, modal: true, height: 'auto', width: 520, 
                        buttons: {
                            'Aceptar': function() {
                                $(this).dialog('close');
                            }
                        }//fin buttons
                    });//fin dialog
                }
            });//fin ajax//*/
        });
		$("a[id^='verFotoAlumno']").click(function(evento){
            evento.preventDefault();
			fDNI = this.id.substr(13,15);
			alert(fDNI);
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
        
        $("#mostrarCuadro").show();
	
    });//fin ready
                </script>


                <style type="text/css">
                    <!--
                    .Estilo1 {font-weight: bold}
                    -->
                </style>
                <style type="text/css">
                    .mostrarBuscador {
                        /*display:inline;*/
                        position:absolute;
                        width:300px;
                        margin-left:165px;
                        margin-top:5px;
                        z-index: 999;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 12px;
                        font-weight: normal;
                        color: #FFF;
                        text-decoration: none;
                        /*background-image: url(imagenes/fondo_gris.png);*/
                        background-repeat: repeat-x;
                    }
                    .mostrarfondohome{
                        background-image: url(imagenes/fondo_college.png);
                    }
                    .color_saludo{
                        color: white;
                    }
                </style>
</head>

                <body>
                    <table width="1100" border="0" align="center">
                        
                        <tr class="mostrarfondohome">
                            <td>                                
                                <table>
                                    <tr>
                                        <td>
                                            <div align="center" style="margin-left: 15px;"><a href="index.php"><img src="logos/logo_smo.png" name="logo" id="logo" style="margin-top: -4px; margin-left: -19px;" border="0" /></a></div><br /> 
                                        </td>
                                        <td>
                                            <div>
                                                <div align="center" class="Estilo1">
                                                    <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                                                        <tr>
                                                            <td class="texto"><?php 
if (isset($_SESSION['sesion_usuario'])) {
    
	?>
                                                                    <div style="margin-left: -103px; margin-top: -8px;">
                                                                        <?php 
                                                                        $saludo = buscarSaludo();
                                                                        echo "<span class='color_saludo' style='font-family: avant guard, arial;'>$saludo</span> <strong class='color_saludo' style='font-family: avant guard, arial;'>" . $_SESSION['sesion_persona'] . "</strong>";
                                                                        ?>
                                                                    </div>
                                                                    <?php 
                                                                    echo "<br />";
//                                                        if (isset($_SESSION['sesion_nombrerol']))
//                                                            echo " (" . $_SESSION['sesion_nombrerol'] . ") <i>Sede " . $_SESSION['sesion_Sede'] . "</i>";
                                                                }
                                                                ?>            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div style="width:600px; margin-top: 102px; margin-left: -17px;">
                                                <div id="Comienzasesion">
                                                    <?php 
                                                    if (isset($_SESSION['sesion_usuario'])) {
                                                        ?>
                                                        <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                                                            <tr>
                                                                <td><?php 
                                                    if (isset($_SESSION['sesion_nombrerol']))
                                                        ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <?php 
                                                    }else {
                                                        ?>
                                                        <!--<table><tr><td> 
                                                        <div id="sesion" align="left">
                                                            <table width="300" border="0" align="left" style="margin-top: 20px;">
                                                                <tr>
                                                                    <td rowspan="2" valign="middle" class="texto_sesion"><img src="botones/system-users.gif" width="32" height="32" /></td>
                                                                    <td class="texto_sesion">Usuario</td>
                                                                    <td class="texto_sesion">Clave</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input name="sesion_usuario" type="text" class="input_sesion" id="sesion_usuario" /></td>
                                                                    <td><input name="sesion_clave" type="password" class="input_sesion" id="sesion_clave" /></td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                        </div></td><td>
                                                        <table border="0" class="texto">
                                                            <tr>
                                                                <td ><button id="barraSesion" title="Iniciar SesiÃ¯Â¿Â½n" class="botones"> <img src="botones/iniciar_sesion.gif"  width="32" height="32" border="0" align="absmiddle"/> Iniciar sesi&oacute;n</button></td>
                                                            </tr>
                                                        </table> 
                                                            </td></tr></table>-->
                                                        <?php 
                                                    }//fin else
                                                    ?>

                                                </div>
                                                <?php 
                                                if (isset($_SESSION['sesion_usuario'])) {
                                                    ?>
                                                    <div id="tabMostrarUsuarios"></div>
                                                    <?php 
                                                    if (!empty($mostrar)) {
                                                        ?>
                                                        <div id="tabs-2" style="margin-left: 97px; margin-top:20px; width: 800px;"> 
                                                            <?php  echo $mostrar; ?>

                                                            <?php 
                                                        } else {
                                                            ?><span class="texto">Aqui podr&aacute; acceder a las opciones m&aacute;s frecuentes de uso.</span> 
                                                        <?php  }
                                                        ?>
                                                    </div>
                                                    <?php 
                                                }//fin de Mi Perfil y Accesos directos
                                                ?>

                                            </div>
                                        </td>
                                        <td>
                                            <div style="margin-top: -114px; margin-left: 35px;">
                                                 <?php 
                                                if (isset($_SESSION['sesion_usuario'])) {
                                                    ?>
                                                <table><tr><td><div>                                                    
                                                    <?php 
                                                    
													//if ($Server=="localhost"){
													?><a href="#" id="barraCambiarClave" class="link_acceso"><img src="imagenes/CambiarClave.png" alt="Cambiar clave" style="width:141px; height:27px; margin-top: 2px;" border="0" align="absmiddle" /></a>
                                                    <?php 
													//}
													?>
                                                </div></td>
                                                <td><div>
                                                    <a href="#" class="link_acceso" id="barraSesion"><img src="imagenes/salir.png" alt="Cerrar Sesi&oacute;n" style="width:92px; height:24px; margin-top: 4px;" border="0" align="absmiddle" title="Cerrar Sesi&oacute;n" /></a>                                   
                                                </div></td></tr></table><?php  }//fin if usuario?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>            

                            </td>

                        </tr>
                    </table>
                    <!--<fieldset style="margin-left: 169px; margin-top: 4px; border-radius: 4px; width: 60px;"><a href="#" id="abrir" class="texto2" style="border: 15px grey"><center><b style="font-size: 15px; font-family: arial;">MENU</b></center></a></fieldset>-->
                    <!--BOTON EN QUE SE DESPLIEGA EL MENU PRICIPAL-->
                    <table width="1100" align="center" id="Menu">
                        <tr>
                            <td width="180" valign="top">
                            <!--BotÃ³n Mostrar Todos los Pagos-->
                            <?php 
                            if ($_SESSION['sesion_UsuCaja']==1){
                                echo '<a href="#" id="btnCargarTodos">Todos los Pagos Informados</a>';
                            }
                            ?>
                                <?php 
							 if (isset($_SESSION['sesion_usuario']) && $_SESSION['sesion_CambiarClave']==false) {
                                if (isset($_SESSION['sesion_UsuFoto'])) {
									$fotoUsuario = $_SERVER['DOCUMENT_ROOT']."/naptacolegios/fotos/grande/".$_SESSION['sesion_UsuFoto'].".jpg";
									if (file_exists($fotoUsuario))
										$fotoUsuario=$_SESSION['sesion_UsuFoto'];
									else
										$fotoUsuario = "sin_foto";
											?>
                                        <div align="center" style="height:100px"><img src="fotos_usuarios/sin_foto.jpg" width="60" height="80" alt="Falta cargar foto" style="alignment-baseline:middle; vertical-align:middle; border:thin"></div>
                                        <?php 
										}else{
										?>
                                        <div align="center" style="height:100px"><img src="fotos_usuarios/sin_foto.jpg" width="60" height="80" alt="Falta cargar foto" style="alignment-baseline:middle; vertical-align:middle; border:thin"></div>
                                        <?php 
										}//fin else
							 }//fin if usuario sesion
								?>
                                    <table width="180px" border="0" align="center" cellpadding="1" cellspacing="1">
                                        <tbody>
                                            <?php 
											$sql = "SET NAMES UTF8;";
											consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                            $usuario = $_SESSION['sesion_UsuID'];
                                            if ($usuario == "2") {
                                                echo "<div id=acordeon class='accordion' style='margin-top: -15px;'>";
                                                $sql = "SELECT * FROM Menu ORDER BY Men_Orden";
                                                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                    <h1><a href="#"><?php  echo $row['Men_Nombre']; ?></a></h1>				
                                                    <div><?php  echo cargarOpcionesMenu($row['Men_ID']); ?></div>

                                                    <?php 
                                                }//fin del while
                                                echo "</div>";
                                            } else {
                                                if (!$_SESSION['sesion_CambiarClave']){
												$sql = "SELECT DISTINCTROW Unidad.* FROM Permiso, Unidad WHERE (Permiso.Prm_Usu_ID = '$usuario' AND Prm_Uni_ID = Uni_ID)";
                                                $result_unidad = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                                while ($row_unidad = mysqli_fetch_array($result_unidad)) {
                                                    ?>
                                                    <h4><?php  echo $row_unidad[Uni_Siglas]; ?></h4>
                                                    <script language="javascript">cargarLogo("<?php  echo $row_unidad[Uni_Foto]; ?>");</script>
                                                    <div id="menu" style="margin-top:-53px"; class="accordion">
                                                        <?php 
                                                        $sql = "SELECT DISTINCTROW Menu.Men_ID, Menu.Men_Nombre, Permiso.Prm_Usu_ID FROM Permiso
			  INNER JOIN Menu 
			  ON (Permiso.Prm_Men_ID = Menu.Men_ID)
			  WHERE (Permiso.Prm_Usu_ID = '$usuario' AND Prm_Uni_ID = $row_unidad[Uni_ID])";// ORDER BY Men_Orden";
                                                        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            ?>
                                                            <h1><a href="#"><?php  echo $row['Men_Nombre']; ?></a></h1>				
                                                            <div><?php  echo cargarOpcionesMenuUsuario($row['Men_ID'], $usuario); ?></div>

                                                        <?php  }//fin del while menu
                                                        ?>
                                                    </div>
                                                    <?php 
                                                }//fin del while unidad
                                            }//fin if 
											}//fin if 
                                            ?>                                               
                                        </tbody>
                                    </table>
                                
                            </td>
                            <td valign = "top">
                                <!--TABLA DE INFORMACION CENTRAL-->
                          <table  width="100%" border = "0" align = "left" cellpadding = "0" cellspacing = "0" style="margin-left: 3px; margin-top: 1px;">

                                    <tr>
                                        <td valign="top" class = "fondo_body_medio">
                                            <div id = "principal">
                                                <!--                                                <div id = "mostrarCambiarUnidad">
                                                <?php 
                                                if ($_SESSION['sesion_rol'] == 1) {
                                                    ?>Cambiar Sede<br /><?php 
                                                cargarListaSede("CambiarSedID");
                                                    ?>
                                                    ambiar Unidad<br />
                                                    <?php 
                                                    cargarListaUnidad("CambiarUniID");
                                                    ?>
                                                      /><button id="cambiarUnidad" class="botones">Cambiar</button>
                                                    <?php 
                                                }//*/
                                                ?>
                                                
                                                                                                </div>-->
                                                <?php                                                     
													if ($Server=="localhost"){
													?>
                                                    <div class="noticia_borde">
                                                    <!--<span class="textoInformativo">
                                                    NOTA: La versiÃ³n del Sistema ONLINE de GATEWAY no se encuentra actualizada con la base de datos real del Colegio. Usted solamente podrÃ¡ consultar los datos personales, econÃ³micos y acadÃ©micos. No podrÃ¡ cambiar la clave se le asignÃ³.
                                                    </span>-->
                                                    </div>
                                                    <?php 
													}//fin if
													?>
                                                <br />
                                                <?php 
                                                if ($usuario) {
                                                    cargarAlertaMensajeUsuario($usuario);
													//echo"entre";
                                                    //cargarAlertaVersionBeta($UsuID);
													if ($_SESSION['sesion_CambiarClave'])
														include_once("cargarCambiarClave.php");
													
                                                    cargarAlertaFaltaEmail($usuario);
                                                } else {
                                                    ?>
                                                    <table width="400" border="0" align="center" cellpadding="1" cellspacing="1" class="tabla_login" style="background-color:#F9A51C">
  <tr>
    <td colspan="2" align="center"><strong>Antes de comenzar a operar el sistema Ud. debe indentificarse</strong></td>
  </tr>
  <tr>
    <td align="right">Usuario:</td>
    <td><input name="sesion_usuario" type="text" class="input_sesion" id="sesion_usuario" size="25" /></td>
  </tr>
  <tr>
    <td align="right">Clave:</td>
    <td><input name="sesion_clave" type="password" class="input_sesion" id="sesion_clave" size="25" /></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td><button id="barraSesion2" title="Iniciar SesiÃ³n" class="botones">Iniciar sesi&oacute;n</button></td>
    </tr>
</table>
                                                    
                                                    
                                                    <br /><br /><br />

                                                    <?php 
                                                }
                                                cargarNoticias();
                                                ?>                                                
                                          </div><!--Fin Div Principal-->
                                            
                                            
                                            </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

<!--                    <table width="950" border="0" align="center">
                        <tr>
                            <td valign = "top">
                                TABLA DE INFORMACION CENTRAL
                                <table width = "100%" border = "0" align = "left" cellpadding = "0" cellspacing = "0" style="margin-left: 0px; margin-top: -42px; border: grey 2px solid; ">

                                    <tr>
                                        <td class = "fondo_body_medio">
                                            <div id = "principal">
                                                                                                <div id = "mostrarCambiarUnidad">
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
                                                
                                                                                                </div>
                                                <br />
                                                <?php 
                                                if ($usuario) {
                                                    cargarAlertaMensajeUsuario($usuario);
                                                    cargarAlertaVersionBeta($UsuID);
                                                    cargarAlertaFaltaEmail($usuario);
                                                } else {
                                                    ?>
                                                    <span class="textoInformativo">Si Ud. es <strong>PADRE DE UN ALUMNO</strong> y quiere iniciar sesi&oacute;n por <strong>PRIMERA VEZ</strong>, haga<a href="#" class="ui-state-default" id="sesionPadre"> <strong>click aqui</strong></a>
                                                    </span><br /><br /><br />

                                                    <?php 
                                                }
                                                cargarNoticias();
                                                ?>
                                                 <h1>DEBIDO A PROBLEMAS EN LA COMUNICACI&Oacute;N CON EL SERVIDOR, NO SE PUEDE ACCEDER A ESTE SISTEMA</h1><br />
                                            </div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>-->
              <table width="950" border="0" align="center" class="ui-state-default ui-corner-all">
                        <tr>
                            <td><div align="center">&copy; <?php  echo date("Y"); ?> - Desarrollado por DOCMEDIA </div></td>
                        </tr>
                    </table>
                    <div id="cargando" style="display:none; color:green; zIndex:9999;"><img src="imagenes/cargando.gif" alt="Cargando...por favor espere" /></div>
                    <div id="dialog" title="Dialog Title">I'm in a dialog</div>
                </body>
                </html>
