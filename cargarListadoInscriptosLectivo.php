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
$UsuID = $_SESSION['sesion_UsuID'];
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
			vTurno = $("#Turno").val();
            vMes = $("#mes").val();
			vCriterioDeuda = $("#criterioDeuda").val();
			vTextoAlDia = $("#textoAlDia").val();
			vTextoDebe = $("#textoDebe").val();
			vCantGrilla = $("#cantGrilla").val();
			vtituloPagina = $("#tituloPagina").val();
			vtituloRequisito = $("#tituloRequisito").val();
			vtituloCursoDivision = $("#tituloCursoDivision").val();
			vtituloMostrarAlumnos = $("#tituloMostrarAlumnos").val();
			vReqID = $("#ReqID").val();
            vSedID = 1;
            if (vLecID==-1){
                jAlert("Debe seleccionar un Ciclo Lectivo", "ERROR");
                $("#LecID").focus();
                return false;
            }
            if (vCurID==-1){
                jAlert("Debe seleccionar un Curso", "ERROR");
                $("#CurID").focus();
                return false;
            }
            if (vNivID==-1){
                jAlert("Debe seleccionar un Nivel", "ERROR");
                $("#NivID").focus();
                return false;
            }
            if (vDivID==-1){
                jAlert("Debe seleccionar una Divisi�n", "ERROR");
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
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Turno: vTurno, tituloRequisito: vtituloRequisito, tituloMostrarAlumnos: vtituloMostrarAlumnos, ReqID: vReqID},
                    url: 'mostrarListadoInscriptosLectivo.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
		$("#barraListadoBecados").click(function(evento){
		
            if (validarDatos()){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Turno: vTurno},
                    url: 'mostrarListadoBecados.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
        $("#barraListadoBajas").click(function(evento){
        
            if (validarDatos()){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Turno: vTurno},
                    url: 'mostrarListadoBajas.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
		$("#barraListadoPlanes").click(function(evento){
		
            //if (validarDatos()){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Turno: vTurno},
                    url: 'mostrarListadoPlanesPagos.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
           // }//fin if
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
		 $("#barraDeudoresCriterio").click(function(evento){
		
            if (validarDatos()){
		
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Turno: vTurno, criterioDeuda: vCriterioDeuda},
                    url: 'mostrarListadoDeudoresCriterioLectivo.php',
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
        $("#barraEstadoSMSDeuda").click(function(evento){
        
            if (validarDatos()){
        
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, textoAlDia: vTextoAlDia, textoDebe: vTextoDebe, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision, Turno: vTurno, Deuda: "Si"},
                    url: 'mostrarListadoEstadoCuentasLectivoSMS.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
        $("#barraEstadoSMSDia").click(function(evento){
        
            if (validarDatos()){
        
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, textoAlDia: vTextoAlDia, textoDebe: vTextoDebe, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision, Turno: vTurno, Deuda: "No"},
                    url: 'mostrarListadoEstadoCuentasLectivoSMS.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
        $("#barraRapipago").click(function(evento){
        
            if (validarDatos()){
        
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, textoAlDia: vTextoAlDia, textoDebe: vTextoDebe, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision, Turno: vTurno, Mes: vMes},
                    url: 'mostrarListadoRapipago.php',
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
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, cantGrilla: vCantGrilla, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision, Turno: vTurno},
                    url: 'mostrarListadoGrillaLectivo.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });
		$("#barraDeudaPersonalizada").click(function(evento){
		
            if (validarDatos()){
                textoDeudaPersonalizada = $("#textoDeudaPersonalizada").val();
				window.open("imprimirDeudaPersonalizada.php?LecID="+vLecID+"&CurID="+vCurID+"&NivID="+vNivID+"&DivID="+vDivID+"&SedID="+vSedID+"&textoAdicional="+textoDeudaPersonalizada, '_blank');
				
            }//fin if
        });
		$("#barraNotifDeuda").click(function(evento){
		 
            if (validarDatos()){
                textoDeudaPersonalizada = $("#textoDeudaPersonalizada").val();
				window.open("imprimirNotificacionDeuda.php?LecID="+vLecID+"&CurID="+vCurID+"&NivID="+vNivID+"&DivID="+vDivID+"&SedID="+vSedID+"&criterioDeuda="+vCriterioDeuda+"&textoAdicional="+textoDeudaPersonalizada, '_blank');
				
            }//fin if
        });
		$("#barraNotifDeudaDetallada").click(function(evento){
		
            if (validarDatos()){
                textoDeudaPersonalizada = $("#textoDeudaPersonalizada").val();
				window.open("imprimirNotificacionDeudaDetallada.php?LecID="+vLecID+"&CurID="+vCurID+"&NivID="+vNivID+"&DivID="+vDivID+"&SedID="+vSedID+"&criterioDeuda="+vCriterioDeuda+"&textoAdicional="+textoDeudaPersonalizada, '_blank');
				
            }//fin if
        });
		$("#barraMatching").click(function(evento){
		
            if (validarDatos()){
		
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, cantGrilla: vCantGrilla, tituloPagina: vtituloPagina, tituloCursoDivision: vtituloCursoDivision, Turno: vTurno},
                    url: 'mostrarListadoMatchingLectivo.php',
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

        $("#barraDeudoresAndrea").click(function(evento){
            if (validarDatos()){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);},
                    data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Turno: vTurno},
                    url: 'mostrarListadoDeudoresLectivo-andrea.php',
                    success: function(data){ 
                        $("#mostrar").html(data);
                    }
                });//fin ajax//*/
            }//fin if
        });


    });//fin de la funcion ready


</script>


<div id="mostrarCuotas">

    <p>&nbsp;</p>
    <table width="95%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td colspan="2">		
                <div align="center" class="titulo_noticia"><img src="imagenes/table.png" width="32" height="32" align="absmiddle" /> Listar alumnos inscriptos al Ciclo Lectivo</div></td>
        </tr>
        <tr>
            <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
            <td><?php
               $UniID = $_SESSION['sesion_UniID'];
               //echo $UniID;
               //cargarListaLectivoInscripcion("LecID", $UniID);
               cargarListaLectivo("LecID");
               ?> 
              <input name="Turno" id="Turno" value="999999" type="hidden" />
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
           <?php
           if ($_SESSION['sesion_UsuCaja']==1){
		        if ($UsuID==2){
           ?> 
                     <button class="botones" id="barraDeudoresAndrea">Padrón Deudores Andrea</button> 
            <?php
                }
           ?> 
            <button class="botones" id="barraDeudores">Padrón Deudores</button> 
            <button class="botones" id="barraDeudaPersonalizada">Deuda personalizada</button>
            <button class="botones" id="barraListadoBecados">Listado Becados</button>
            <button class="botones" id="barraListadoBajas">Listado Bajas</button>
            <br>
            <button class="botones" id="barraListadoPlanes">Listado Planes de Pago</button>
            <!-- <button class="botones" id="barraEstadoSMSDeuda">SMS Deuda</button>
            <button class="botones" id="barraEstadoSMSDia">SMS Al Día</button>
            <button class="botones" id="barraRapipago">RAPIPAGO</button> -->
           <?php
		   }//fin if
		   ?>
            <button class="botones" id="barraEstado">Estado de Cuentas</button>
             <button class="botones" id="barraGrilla">Grilla de Alumnos</button>
            <!-- <button class="botones" id="barraMatching">Falta Inscribir</button>-->
            
            </td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="texto"><fieldset style="width:auto">
          <legend>
          <strong>Configuración de parámetros adicionales </strong></legend>
          <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td align="right"><span class="texto">[RAPIPAGO] Mes a mostrar:</span></td>
              <td><span class="texto">
                <select id="mes">
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
              </span></td>
            </tr>
            <tr>
              <td align="right"><span class="texto">Leyenda para las DEUDAS PERSONALIZADAS:</span></td>
              <td><span class="texto">
                <textarea name="textoDeudaPersonalizada" id="textoDeudaPersonalizada" cols="50"></textarea>
              </span></td>
            </tr>
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
            <tr class="texto">
              <td align="right">Incluir Requisitos de Ingreso:</td>
              <td><select name="tituloRequisito" size="1" id="tituloRequisito">
                <option value="0">No</option>
                <option value="1">Si</option>
              </select></td>
            </tr>
             <tr class="texto">
              <td align="right">Seleccionar Requisito:</td>
              <td><?php cargarListaRequisitos("ReqID", true); ?></td>
            </tr>
             <tr class="texto">
              <td align="right">Mostrar todos los Alumnos:</td>
              <td><select name="tituloMostrarAlumnos" size="1" id="tituloMostrarAlumnos">
                <option value="0">No</option>
                <option value="1" selected="selected">Si</option>
              </select></td>
            </tr>
             <?php
           if ($_SESSION['sesion_UsuCaja']==1){
		   ?>
            <tr>
              <td colspan="2" align="center"><h2>Datos Deudores Criterio</h2></td>
            </tr>
            <tr>
              <td align="right">Total deuda del alumno:</td>
              <td><input name="criterioDeuda" type="text" id="criterioDeuda" value="1000" size="10" maxlength="6" /> <button class="botones" id="barraDeudoresCriterio">Padrón Deudores Criterio</button><button class="botones" id="barraNotifDeuda">Notificación Deuda</button><button class="botones" id="barraNotifDeudaDetallada">Notificación Deuda Detalladas</button></td>
            </tr>
            <?php
		   }//fin if
		   ?>
            <!--
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
