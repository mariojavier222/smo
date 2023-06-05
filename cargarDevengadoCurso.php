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
		vLecID = $("#LecID").val();
		vNivID = $("#CMo_Niv_ID").val();
		vProID = $("#ProID").val();
		vCurID = $("#CurID").val();
        vDivID = $("#DivID").val();
		if (vLecID==-1){
			jAlert("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return false;
		}
		if (vNivID==-1){
			jAlert("Debe seleccionar un Nivel", "ERROR");
			$("#NivID").focus();
			return false;
		}
		if (vCurID==-1){
                jAlert("Debe seleccionar un Curso", "ERROR");
                $("#CurID").focus();
                return false;
            }
		if (vDivID==-1){
                jAlert("Debe seleccionar una Division", "ERROR");
                $("#DivID").focus();
                return false;
            }
		if (vProID==-1){
			jAlert("Debe seleccionar un Concepto", "ERROR");
			$("#ProID").focus();
			return false;
		}
		
		return true;
	}
	
	$("#barraMostrar").click(function(evento){
	var	vNivID = $("#CMo_Niv_ID").val();
		//alert(vNivID);
	var	vCTi_ID = $("#CTi_ID").val();
	
	var	Estado = $("#Estado").val();
		//alert(vCTi_ID);
	var	vFechaDesde = $("#fechaDesde").val();
		//alert(vFechaDesde);
	var	vFechaHasta = $("#fechaHasta").val();
		//alert(vFechaHasta);
		vError = false;
	vTexto_Error = '';
	
                if (vFechaDesde==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "Fecha Desde invalida </br>" ;
					$("#fechaDesde").attr("class","input_error");
                    					
					
                }
				else {
					$("#fechaDesde").attr("class","input_sesion");
					}
				if (vFechaHasta==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "Fecha Hasta invalida </br>" ;
					$("#fechaHasta").attr("class","input_error");
                    					
					
                }
				else {
					$("#fechaHasta").attr("class","input_sesion");
					}	
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
				data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, NivID: vNivID, CurID: vCurID, DivID: vDivID, CTi_ID: vCTi_ID, Estado: Estado},
				url: 'mostrarDevengadoCursoDetallado.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}//fin if

	});


	$(".botones").button();	
	$("#CMo_Niv_ID").change(function () {
		$("#CMo_Niv_ID option:selected").each(function () {				
			llenarCursoTurno();
		});
	});
	function llenarCursoTurno(){
		NivID = $("#CMo_Niv_ID").val();		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "cargarListaCursos2", NivID: NivID},
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
		<div align="center" class="titulo_noticia">
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Listar Devengados por Curso
	      </p>
        </div></td>
      </tr>
       <tr>
         <td width="50%" class="texto"><div align="right">Niveles:</div></td>
         <td><?php cargarListaNivel("CMo_Niv_ID", true); ?> 
           
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
         <td class="texto"><div align="right">Tipo de Cuota:</div></td>
         <td><?php cargarListaTipoCuota("CTi_ID", true); ?> 
           
             </tr>
         <tr>
           <td align="right" class="texto">Estado:</td>
           <td><select name="Estado" id="Estado">
             <option value="-1">Todos los estados</option>
             <option value="1">Sin Pagar</option>
             <option value="2">Pagado</option>
             <option value="3">Anulado</option>
             <option value="4">Cancelado por Plan de Pago</option>
           </select>           
      </tr>

       <tr>
           <td colspan="2" align="center" class="texto"><strong>La fecha de búsqueda se basa en la fecha de vencimiento de la cuota</strong></td>
      </tr>
       <tr>
	     <td align="right" class="texto">
           <input name="fechaDesde" type="text" id="fechaDesde" class="required fechaCompleta" />
           <br />
           <strong>Fecha Desde</strong></td>
         <td class="texto"><input name="fechaHasta" type="text" id="fechaHasta" class="required fechaCompleta" />
           <br /> 
         <strong>Fecha Hasta </strong>         </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Listar detallado</button>      
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	