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
                        vres = $("input['name=requisito']:checked").val() 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, res: vres},
				url: 'mostrarListadoDatosPersonales.php',
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
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

	<p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="imagenes/group_go.png" width="32" height="32" align="absmiddle" /> Listar Requisitos de Ingresos de los Alumnos</div></td>
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
         <td><?php cargarListaCursos("CurID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td><?php cargarListaNivel("NivID", true);?> 
           
             </tr>
       <tr>
         <td align="right" valign="middle" bgcolor="#75B3EA" class="texto">Campos a mostrar</td>
         <td valign="top" bgcolor="#75B3EA" class="texto">
           <input name="requisito" type="radio" value="FOTOCOPIA DE DNI" checked="on" />
           <label for="Dni">Fotocopia de DNI</label>
           <br />
           <input name="requisito" type="radio" value="PARTIDA DE NACIMIENTO"  />
           <label for="ParNac">Fotocopia de Partida de Nacimiento</label>
           <br />
           <input name="requisito" type="radio" value="CERTIFICADO DE VACUNAS" />
           <label for="CerVac">Certificado de Vacunas</label>
           <br />
           <input name="requisito" type="radio" value="GRUPO SANGUINEO" />
           <label for="Sanguineo">Grupo Sanguineo</label>
           <br />
           <input name="requisito" type="radio" value="CERTIFICADO MEDICO" />
           <label for="CerMed">Certificado Medico</label>
           <br />
           <input type="radio" name="requisito" value="FOTO CARNET" />
           <label for="FotCar">Foto Carnet</label>
           <br />
           <input type="radio" name="requisito" value="CONTROL OFTALMOLOGICO" />
           <label for="ConOft">Control Oftalmológico</label>
           <br />
           <input name="requisito" type="radio" value="CERTIFICADO DE TENENCIA" />
           <label for="CerTen">Certificado de Tenencia</label>
           <br />
           <input type="radio" name="requisito" value="CONTRATO DE ADHESION" />
           <label for="ConAd">Contrato de Adhesión</label>
           <br />
           <input name="requisito" type="radio" value="DECLARACION JURADA" />
           <label for="DecJur">Declaración Jurada de Salud</label>
           <br />
           <input type="radio" name="requisito" value="FICHA DE ACTUALIZACION DE DATOS" />
           <label for="FichaAc">Ficha de Actuaclización de Datos</label>
           <br />
           <input type="radio" name="requisito" value="CERTIFICADO AUDIOMETRICO" />
           <label for="CerAu">Certificado Audiométrico</label>
           <br />
           <input type="radio" name="requisito" value="CARTILLA SANITARIA" />
           <label for="CarSan">Cartilla Sanitaria</label>
           <br /></tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Mostrar Datos Personales</button>      
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	