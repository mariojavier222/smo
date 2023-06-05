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
		vTitID = $("#TitID").val();
		vDivID = $("#DivID").val();
		vSedID = $("#SedID").val();
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return false;
		}
		if (vTitID==-1){
			mostrarAlerta("Debe seleccionar un Título", "ERROR");
			$("#TitID").focus();
			return false;
		}
		if (vCurID==-1){
			mostrarAlerta("Debe seleccionar un Curso", "ERROR");
			$("#CurID").focus();
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
				data: {LecID: vLecID, CurID: vCurID, TitID: vTitID, DivID: vDivID, SedID: vSedID, mostrarDeuda: false},
				url: 'mostrarListadoInscriptosLectivoTerciarioCurso.php',
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
				data: {LecID: vLecID, CurID: vCurID, TitID: vTitID, MatID: vMatID, DivID: vDivID, SedID: vSedID, mostrarDeuda: true},
				url: 'mostrarListadoInscriptosLectivoTerciarioCurso.php',
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
	/*$("#TitID").change(function () {
   		$("#TitID option:selected").each(function () {
			//alert($(this).val());
			vTitID=$(this).val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "llenarMateriasTitulo", TitID: vTitID},
				success: function (data){
						$("#MatID").html(data);
						validarMatID(-1);
						$("#cargando").hide();
						}
			});//fin ajax
        });
   })//*/
	$("#MatID").change(function () {
   		$("#MatID option:selected").each(function () {
			//alert($(this).val());
			vMatID=$(this).val();
			validarMatID(vMatID);
        });
   })
	function validarMatID(vMatID){
		if (vMatID==-1 || vMatID==999999){
			  $("#CurID").attr("disabled", "");
			  $("#DivID").attr("disabled", "");
		  }else{
			  $("#CurID").attr("disabled", "disabled");
			  $("#DivID").attr("disabled", "disabled");
			  $("#CurID").val(999999);
			  $("#DivID").val(999999);
		  }
		 $("#cargando").hide();
	}
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

	<p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="imagenes/table.png" width="32" height="32" align="absmiddle" /> Listar alumnos inscriptos al Ciclo Lectivo por Curso</div></td>
      </tr>
	   <tr>
         <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td><?php 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;			
			cargarListaLectivo("LecID");
			?> 
           
             </tr>      
                    <tr>
         <td class="texto"><div align="right">Sede:</div></td>
         <td colspan="2"><?php cargarListaSede("SedID");?>            
             </tr>
       <tr>
         <td class="texto"><div align="right">Título:</div></td>
         <td><?php cargarListaTituloCarrera("TitID", true);?> 
           
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
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Mostrar Inscriptos</button>
        <button class="botones" id="barraDeudores">
        Padrón Deudores</button>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	