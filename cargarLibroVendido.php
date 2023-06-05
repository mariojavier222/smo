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
                    data: {LecID: vLecID, CurID: vCurID},
                    url: 'mostrarLibroVendido.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
		$("#barraTotalLibro").click(function(evento){
		
            if (validarDatos()){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID},
                    url: 'mostrarLibroTotal.php',
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
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Turno: vTurno},
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
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, textoAlDia: vTextoAlDia, textoDebe: vTextoDebe, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision, Turno: vTurno},
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
                    data: {LecID: vLecID, CurID: vCurID},
                    url: 'mostrarLibroCursoGrilla.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
		$("#barraFaltaEntregar").click(function(evento){
		
            if (validarDatos()){
		
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID},
                    url: 'mostrarLibroFaltaEntregar.php',
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
		function llenarCursoTurno(){
			NivID = $("#NivID").val();
			TurID = $("#Turno").val();
			$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "llenarCursoTurno", NivID: NivID, TurID: TurID},
                    success: function (data){
                        $("#CurID").html(data);
                        $("#cargando").hide();
                    }
                });//fin ajax
		}//fin function
		
		$("#Turno").change(function () {
			$("#Turno option:selected").each(function () {				
				llenarCursoTurno();
				
			});
	    });
		$("#NivID").change(function () {
			$("#NivID option:selected").each(function () {
				validarCursoDivision();
				llenarCursoTurno();
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
                <div align="center" class="titulo_noticia"><img src="imagenes/book_open.png" width="32" height="32" align="absmiddle" /> Estado de Libros</div></td>
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
            <td class="texto"><div align="right">Curso:</div></td>
            <td><?php cargarListaCursos("CurID", true); ?> 

        </tr>
        <tr>
            <td colspan="2" align="center" class="texto">
            <button class="botones" id="barraMostrar"> Libros Vendidos</button>
            <button class="botones" id="barraTotalLibro">Total por Curso</button>
            <button class="botones" id="barraGrilla">Grilla de Alumnos</button>
            <button class="botones" id="barraFaltaEntregar">Falta entregar</button>            
            </td>
        </tr>       
        <tr>
            <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
        </tr>
    </table>

</div>

<p>&nbsp;</p>
