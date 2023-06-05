<?
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
echo "Esta opción ha sido deshabilitada. El registro de asistencias fue migrado a la nueva versión en Napta Colegios" ;

exit;
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
        
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	
	<? 
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
			mostrarAlerta("Debe seleccionar una División", "ERROR");
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
				url: 'mostrarListadoInasistencias.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}//fin if
	});
	$("#barraAcumuladas").click(function(evento){
		
		if (validarDatos()){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, SedID: vSedID},
				url: 'mostrarListadoInasistenciasAcumuladas.php',
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
	$("#Turno").change(function () {
			$("#Turno option:selected").each(function () {				
				llenarCursoTurno();
				
			});
	    });
		$("#NivID").change(function () {
			$("#NivID option:selected").each(function () {
				//validarCursoDivision();
				llenarCursoTurno();				
			});
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
		
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

	<p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="imagenes/tag_blue_delete.png" width="32" height="32" align="absmiddle" /> Cargar Inasistencias</div></td>
      </tr>
	   <tr>
         <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td><? 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
			//cargarListaLectivoInscripcion("LecID", $UniID);
			cargarListaLectivo("LecID");
			?> 
           
             </tr>      
                    <tr>
         <td class="texto"><div align="right">Sede:</div></td>
         <td colspan="2"><? cargarListaSede("SedID");?>   
         <input name="Turno" id="Turno" value="999999" type="hidden" />         
             </tr>

  <!-- <tr>
         <td class="texto"><div align="right">Curso:</div></td>
         <td><? //cargarListaCursos("CurID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td><? //cargarListaDivision("DivID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td><? //cargarListaNivel("NivID", true);?> 
           
             </tr> -->
      

	<tr>
            <td class="texto"><div align="right">Nivel del Colegio:</div></td>
            <td><? cargarListaNivel("NivID", true); ?> 

        </tr>
        <tr>
            <td class="texto"><div align="right">Curso:</div></td>
            <td><? cargarListaCursos("CurID", true); ?> 

        </tr>
        <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td><? cargarListaDivision("DivID", true);?> 
           
             </tr>

      <tr>
        <td colspan="2" align="center" class="texto">
        <button class="botones" id="barraMostrar">
        Agregar inasistencias</button> 
        <button class="botones" id="barraAcumuladas">
        Mostrar acumuladas</button>       
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	