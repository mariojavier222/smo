<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="js/ui.multiselect.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/ui.multiselect.js"></script>

<script language="javascript">
$(document).ready(function(){
	
	<?php 
	if (isset($_SESSION['sesion_UsuID']))
		echo "vUsuID = ".$_SESSION['sesion_UsuID'];
	else
		echo "document.location = 'index.php'";//*/
	?>;
	

	function cargarDNI(){
		vDNI = $("#DNI").val();
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
			}
		});//fin ajax//*/

	}
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		if (evento.keyCode == '13' && vDNI!=""){
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	$("#barraMostrar").click(function(evento){
		vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vNivID = $("#NivID").val();
		vDivID = $("#DivID").val();
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return;
		}
		if (vCurID==-1){
			mostrarAlerta("Debe seleccionar un Curso", "ERROR");
			$("#CurID").focus();
			return;
		}
		if (vNivID==-1){
			mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
			$("#NivID").focus();
			return;
		}
		if (vDivID==-1){
			mostrarAlerta("Debe seleccionar una División", "ERROR");
			$("#DivID").focus();
			return;
		}
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaInscriptos", LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#mostrar").html(data);
				$(".multiselect").multiselect();
			}
		});//fin ajax//*/
	});
	$("#eliminarVinculo").click(function (evento){
		evento.preventDefault();
		//alert($("#Vinculos option:selected").val());
		$("#Vinculos option:selected").remove();
	});

	$("#barraMostrar").button({
            icons: {
                primary: 'ui-icon-zoomin'
            }
    });
	$("#barraCambiar").button({
            icons: {
                primary: 'ui-icon-arrowreturnthick-1-e'
            }
    });//*/

	$("#barraCambiar").click(function(evento){
		evento.preventDefault();
		vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vNivID = $("#NivID").val();
		vDivID = $("#DivID").val();
		vNivNuevoID = $("#NivNuevoID").val();
		vCurNuevoID = $("#CurNuevoID").val();
		vDivNuevoID = $("#DivNuevoID").val();
		vInscriptos = $("#Inscriptos").val();
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").addClass("corregirLista");
			$("#LecID").focus();
			return;
		}else{
			$("#LecID").removeClass("corregirLista");
		}
		if (vCurID==-1){
			mostrarAlerta("Debe seleccionar un Curso", "ERROR");
			$("#CurID").addClass("corregirLista");
			$("#CurID").focus();
			return;
		}else{
			$("#CurID").removeClass("corregirLista");
		}
		if (vDivID==-1){
			mostrarAlerta("Debe seleccionar una División", "ERROR");
			$("#DivID").addClass("corregirLista");
			$("#DivID").focus();
			return;
		}else{
			$("#DivID").removeClass("corregirLista");
		}
		if (vNivID==-1){
			mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
			$("#NivID").addClass("corregirLista");
			$("#NivID").focus();
			return;
		}else{
			$("#NivID").removeClass("corregirLista");
		}
		if (vNivNuevoID==-1 || vNivNuevoID==999999){
			mostrarAlerta("Debe seleccionar el Nivel que desea cambiar", "ERROR");
			$("#NivNuevoID").addClass("corregirLista");
			$("#NivNuevoID").focus();
			return;
		}else{
			$("#NivNuevoID").removeClass("corregirLista");
		}
		if (vCurNuevoID==-1 || vCurNuevoID==999999){
			mostrarAlerta("Debe seleccionar el Curso que desea cambiar", "ERROR");
			$("#CurNuevoID").addClass("corregirLista");
			$("#CurNuevoID").focus();
			return;
		}else{
			$("#CurNuevoID").removeClass("corregirLista");
		}
		if (vDivNuevoID==-1 || vDivNuevoID==999999){
			mostrarAlerta("Debe seleccionar la División que desea cambiar", "ERROR");
			$("#DivNuevoID").addClass("corregirLista");
			$("#DivNuevoID").focus();
			return;
		}else{
			$("#DivNuevoID").removeClass("corregirLista");
		}

		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cambiarDivisionAlumnos", LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, NivNuevoID: vNivNuevoID, CurNuevoID: vCurNuevoID, DivNuevoID: vDivNuevoID, Inscriptos: vInscriptos},
			url: 'cargarOpciones.php',
			success: function(data){ 
				mostrarAlerta(data, "Resultado");
			}
		});//fin ajax//*/
		

	});
	
	
	// choose either the full version
  	//$.localise('ui-multiselect', {/*language: 'en',*/ path: 'js/locale/'});
	//$(".multiselect").multiselect();
  // or disable some features

  //$(".multiselect").multiselect({sortable: false, searchable: false});
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

	<p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia">
		  <p><img src="imagenes/application32.png" width="32" height="32" align="absmiddle" /> Cambiar Divisi&oacute;n de los Alumnos<br />
		    
		  </p>
		</div></td>
      </tr>
	   
      <tr>
        <td class="texto"><table width="100%" border="0" cellspacing="1" cellpadding="1">
          <tr>
         <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td><?php 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
//			cargarListaLectivoInscripcion("LecID", $UniID);
			cargarListaLectivo("LecID");
			?> 
           
          </td></tr>  
          <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td><?php cargarListaNivel("NivID", true);?> 
           
          </td></tr>    
  <tr>
         <td class="texto"><div align="right">Curso:</div></td>
         <td><?php cargarListaCursos("CurID", true);?> 
           
          </td></tr>
       <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td><?php cargarListaDivision("DivID", true);?> 
           
          </td></tr>
       
      <tr>
        <td colspan="2" align="center" class="texto"><button id="barraMostrar" class="botones">
        Mostrar Alumnos</button></td>
      </tr>
        </table></td>
        <td align="center" class="texto">&nbsp;</td>
        <td valign="bottom" class="texto"><table width="100%" border="0" cellspacing="1" cellpadding="1">      
  <tr>
    <td colspan="2" align="center" class="texto"><strong>Datos Nuevos</strong></td>
    </tr>
  <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td><?php cargarListaNivel("NivNuevoID", true);?> 
           
          </td></tr> 

          
  <tr>
    <td class="texto"><div align="right">Curso:</div></td>
    <td><?php cargarListaCursos("CurNuevoID", true);?> 
      
    </td></tr>
       <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td><?php cargarListaDivision("DivNuevoID", true);?> 
           
             </td></tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button id="barraCambiar" class="botones">
          Cambiar Divisi&oacute;n</button></td>
      </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="texto"><div id="resultado"></div><div id="mostrar"></div></td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="texto"></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
