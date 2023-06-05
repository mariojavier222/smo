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
	
	$("#fechaDesde").datepicker($.datepicker.regional['es']);
	$("#fechaHasta").datepicker($.datepicker.regional['es']);

	function validarDatos(){
		vLecID = $("#Lec_ID").val();
		vNivID = $("#NivID").val();
		vProID = $("#ProID").val();
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#Lec_ID").focus();
			return false;
		}
		if (vNivID==-1){
			mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
			$("#NivID").focus();
			return false;
		}
		if (vProID==-1){
			mostrarAlerta("Debe seleccionar un Concepto", "ERROR");
			$("#ProID").focus();
			return false;
		}
		
		return true;
	}
	
	$("#barraMostrar").click(function(evento){
	var	vNivID = $("#CMo_Niv_ID").val();
		//alert(vNivID);
	var	vCTi_ID = $("#CTi_ID").val();	
		//alert(vCTi_ID);
	
		//alert(vFechaHasta);
		vError = false;
	vTexto_Error = '';
	
    	
	if(vError) {
	mostrarAlerta1(vTexto_Error,"Existen datos incorrectos");
	return;
	}
	if (validarDatos()){
		//alert(vNivID);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {Lec_ID: vLecID, NivID: vNivID, CTi_ID: vCTi_ID},
			url: 'mostrarDevengadoVencidoVencer.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	}//fin if

	});
	$(".botones").button();	
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

  <p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia">
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Devengado con Deuda vencida y a vencer
	      </p>
        </div></td>
      </tr>
       <tr>
         <td width="50%" class="texto"><div align="right">Lectivo de Inscripción:</div></td>
         <td>
         	<?php cargarListaLectivo("Lec_ID", true); ?>            
       </tr>
       <tr>
         <td width="50%" class="texto"><div align="right">Niveles:</div></td>
         <td>
         	<?php cargarListaNivel("CMo_Niv_ID", true); ?>            
       </tr>
         <tr>
         <td class="texto"><div align="right">Tipo de Cuota:</div></td>
         <td><?php cargarListaTipoCuota("CTi_ID", true); ?> 
           
             </tr>
         
      <tr><td colspan="2" align="center" class="texto">Armará los devengados basado en la vencimiento de la cuota al día de hoy</td></tr>
       
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Generar y Listar</button>        
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	