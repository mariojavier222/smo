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
		echo "vUsuID = ".$_SESSION['sesion_UsuID'];
	else
		echo "document.location = 'index.php'";//*/
	?>;
	
	function validarDatos(){
		vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vNivID = $("#NivID").val();
		vDivID = $("#DivID").val();
		vSedID = $("#SedID").val();
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
			vOrden = $("#Orden:checked").length;
			vCurso = $("#Curso:checked").length;			
			vNombre = $("#Nombre:checked").length;
			vDNI = $("#DNI:checked").length;
			vSexo = $("#Sexo:checked").length;
			vFechaNac = $("#FechaNac:checked").length;
			vDatNac = $("#DatNac:checked").length;
			vDom = $("#Dom:checked").length;
			vDatDom = $("#DatDom:checked").length;			
			vPadre = $("#Padre:checked").length;
			vPadreInfo = $("#PadreInfo:checked").length;
			vMadre = $("#Madre:checked").length;
			vMadreInfo = $("#MadreInfo:checked").length;
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID, Curso: vCurso, Nombre: vNombre, DNI: vDNI, Sexo: vSexo, FechaNac: vFechaNac, DatNac: vDatNac, Dom: vDom, DatDom: vDatDom, Orden: vOrden, Padre: vPadre, PadreInfo: vPadreInfo, Madre: vMadre, MadreInfo: vMadreInfo},
				url: 'mostrarListadoFichasPersonales.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}//fin if
	});
	$("#barraImprimir").click(function(evento){
		
		if (validarDatos()){			
			pdfStream = "imprimirDatosPersonales.php?a="+vLecID+"&b="+vCurID+"&c="+vNivID+"&d="+vDivID+"&e="+vSedID;
			window.open(pdfStream);
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
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

	<p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="imagenes/group_go.png" width="32" height="32" align="absmiddle" /> Listar Fichas Personales Completas de los Alumnos</div></td>
      </tr>
	   <tr>
         <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td><?php 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
			//cargarListaLectivoInscripcion("LecID", $UniID);
			cargarListaLectivo("LecID");
			?> 
           <input name="SedID" type="hidden" id="SedID" value="1" />
           <input name="Turno" id="Turno" value="999999" type="hidden" />
             </tr> 
    <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td><?php cargarListaNivel("NivID", true);?> 
           
     </tr>		            
                    

  <tr>
         <td class="texto"><div align="right">Curso:</div></td>
         <td><?php cargarListaCursos("CurID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">División:</div></td>
         <td><?php cargarListaDivision("DivID", true);?> 
           
             </tr>
       
       <tr>
         <td align="right" valign="middle" bgcolor="#75B3EA" class="texto">Campos a mostrar</td>
         <td valign="top" bgcolor="#75B3EA" class="texto">
           <input name="Orden" type="checkbox" id="Orden" checked="checked" />
           <label for="Orden">Orden</label>
           <br />
           <input name="Curso" type="checkbox" id="Curso" checked="checked" />
           <label for="Curso">Curso/División</label>
           <!--<br />
            <input name="Div" type="checkbox" id="Div" checked="checked" />
           <label for="Div">División</label> -->
           <br />
           <input name="Nombre" type="checkbox" id="Nombre" checked="checked" />
           <label for="Nombre">Apellido y Nombre</label>
           <br />
           <input name="DNI" type="checkbox" id="DNI" checked="checked" />
           <label for="DNI">DNI</label>
<br />
           <input type="checkbox" name="Sexo" id="Sexo" />
           <label for="Sexo">Sexo</label>
           <br />
           <input name="FechaNac" type="checkbox" id="FechaNac" checked="checked" />
           <label for="FechaNac">Fecha de Nacimiento</label>
           <br />
           <input type="checkbox" name="DatNac" id="DatNac" />
           <label for="DatNac">Lugar de Nacimiento</label>
           <br />
           <input name="Dom" type="checkbox" id="Dom" checked="checked" />
           <label for="Dom">Domicilio</label>
           <br />
           <input type="checkbox" name="DatDom" id="DatDom" />
           <label for="DatDom">Datos de domicilio</label>
           <br />
           <input type="checkbox" name="Padre" id="Padre" />
           <label for="Padre">Datos del Padre</label>
           <br />
           <input type="checkbox" name="PadreInfo" id="PadreInfo" />
           <label for="PadreInfo">Info del Padre</label>
           <br />
           <input type="checkbox" name="Madre" id="Madre" />
           <label for="Madre">Datos de la Madre</label>
           <br />
           <input type="checkbox" name="MadreInfo" id="MadreInfo" />
           <label for="MadreInfo">Info de la Madre</label>
           </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Listar Fichas Personales</button>
        <!-- <button class="botones" id="barraImprimir">
        <img src="iconos/mod_listar.png" width="32" height="32" style="vertical-align:middle" />Imprimir</button>  -->     
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	