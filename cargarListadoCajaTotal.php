<?php
header("Cache-Control: no-cache, must-revalidate");
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />

<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
    $(document).ready(function(){
	
<?php
if (isset($_SESSION['sesion_UsuID']))
    echo "vUsuID = " . $_SESSION['sesion_UsuID'];
else
    echo "document.location = 'index.php'"; //*/
?>;
	
        function validarDatos(){
            vLecID = $("#LecID").val();
            vCurID = $("#CurID").val();
            vNivID = $("#NivID").val();
            vDivID = $("#DivID").val();
			vTextoAlDia = $("#textoAlDia").val();
			vTextoDebe = $("#textoDebe").val();
			vCantGrilla = $("#cantGrilla").val();
			vtituloPagina = $("#tituloPagina").val();
			vtituloCursoDivision = $("#tituloCursoDivision").val();
            vSedID = 1;
            if (vLecID==-1){
                mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
                $("#LecID").focus();
                return false;
            }
            if (vCurID==-1){
                mostrarAlerta("Debe seleccionar un Curso", "ERROR");
                $("#CurID").focus();
                return false;
            }
            if (vNivID==-1){
                mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
                $("#NivID").focus();
                return false;
            }
            if (vDivID==-1){
                mostrarAlerta("Debe seleccionar una Divisi�n", "ERROR");
                $("#DivID").focus();
                return false;
            }
            return true;
        }

        $("#barraMostrar").click(function(evento){
		
            if (validarDatos()){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID},
                    url: 'mostrarListadoInscriptosLectivo.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
        $("#barraDeudores").click(function(evento){
		
            if (validarDatos()){
		
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID},
                    url: 'mostrarListadoDeudoresLectivo.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
		$("#barraEstado").click(function(evento){
		
            if (validarDatos()){
		
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, textoAlDia: vTextoAlDia, textoDebe: vTextoDebe, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision},
                    url: 'mostrarListadoEstadoCuentasLectivo.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
		$("#barraGrilla").click(function(evento){
		
            if (validarDatos()){
		
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, cantGrilla: vCantGrilla, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision},
                    url: 'mostrarListadoGrillaLectivo.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
        $(".botones").button();
        $("#barraVolver").click(function(evento){
            evento.preventDefault();
            vDNI = $("#DNI_Volver").val();
            //alert(vDNI.length);
            if (vDNI.length>0){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarInscripcionLectivo.php',
                    data: {DNI: vDNI},
                    success: function (data){
                        $("#principal").html(data);
                        $("#cargando").hide();
                    }
                });//fin ajax
            }//fin if
        });
		$("#NivID").change(function () {
			$("#NivID option:selected").each(function () {
				validarCursoDivision();
			});
	    });
		$("#CurID").change(function () {
			$("#CurID option:selected").each(function () {
				validarCursoDivision();
			});
	    });
		$("#DivID").change(function () {
			$("#DivID option:selected").each(function () {
				validarCursoDivision();
			});
	    });
		
		function validarCursoDivision(){
			var texto = '';
			if ($("#NivID").val()>0){
				texto = $("#NivID option:selected").text();
			}
			if ($("#CurID").val()>0){
				texto = texto + " - " + $("#CurID option:selected").text();
			}
			if ($("#DivID").val()>0){
				texto = texto + " " + $("#DivID option:selected").text();
			}
			$("#tituloCursoDivision").val(texto);
		}
    });//fin de la funcion ready


</script>


<div id="mostrarCuotas">

    <p>&nbsp;</p>
    <table width="95%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td colspan="2">		
                <div align="center" class="titulo_noticia"><img src="iconos/mod_listar.png" width="32" height="32" align="absmiddle" /> Listado de Cajas</div></td>
        </tr>
        <tr>
            <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
            <td><?php
               $UniID = $_SESSION['sesion_UniID'];
               //echo $UniID;
               //cargarListaLectivoInscripcion("LecID", $UniID);
               cargarListaLectivo("LecID");
               ?> 
        </tr>      
        
        <tr>
            <td class="texto"><div align="right">Nivel del Colegio:</div></td>
            <td><?php cargarListaNivel("NivID", true); ?> 

        </tr>
        <tr>
            <td class="texto"><div align="right">Curso:</div></td>
            <td><?php cargarListaCursos("CurID", true); ?> 

        </tr>
        <tr>
         <td class="texto"><div align="right">División:</div></td>
         <td><?php cargarListaDivision("DivID", true);?> 
           
             </tr>
        <tr>
            <td colspan="2" align="center" class="texto">
            <button class="botones" id="barraMostrar">Mostrar Inscriptos</button>
            <button class="botones" id="barraDeudores">Padrón Deudores</button>
            <button class="botones" id="barraEstado">Estado de Cuentas</button>
             <button class="botones" id="barraGrilla">Grilla de Alumnos</button>
            </td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="texto"><fieldset style="width:500px">
          <legend>
          <strong>Configuración de parámetros adicionales </strong></legend>
          <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td align="right"><span class="texto">Leyenda si el alumno está al día:</span></td>
              <td><span class="texto">
                <input name="textoAlDia" type="text" id="textoAlDia" value="AL DIA" />
              </span></td>
            </tr>
            <tr>
              <td align="right"><span class="texto">Leyenda si el alumno debe:</span></td>
              <td><span class="texto">
                <input name="textoDebe" type="text" id="textoDebe" value="DEBE" />
              </span></td>
            </tr>
            <tr class="texto">
              <td align="right">Cantidad de Grillas:</td>
              <td><input name="cantGrilla" type="text" id="cantGrilla" value="8" size="6" maxlength="2" /></td>
            </tr>
            <tr class="texto">
              <td align="right">Título de la página</td>
              <td><input name="tituloPagina" type="text" id="tituloPagina" value="Listado de alumnos" size="40" /></td>
            </tr>
            <tr class="texto">
              <td align="right">Curso y División:</td>
              <td><input name="tituloCursoDivision" type="text" id="tituloCursoDivision" value="Todos" size="40" /></td>
            </tr>
            <!--<tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>-->
          </table>
          </fieldset>
          </td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
        </tr>
    </table>

</div>

<p>&nbsp;</p>
