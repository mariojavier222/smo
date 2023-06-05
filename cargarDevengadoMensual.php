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
		vNivID = $("#NivID").val();
		vProID = $("#ProID").val();
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
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
	var	vMes = $("#Mes").val();
		//alert(vFechaDesde);
	var	vAnio = $("#Anio").val();
		//alert(vFechaHasta);
		vError = false;
	vTexto_Error = '';
	
    if (vAnio==-1){
        vError = true;
		vTexto_Error = vTexto_Error +  "Seleccione un Año por favor </br>" ;
		$("#Anio").attr("class","input_error");
    }
	else {
		$("#Anio").attr("class","input_sesion");
		}
	if (vMes==""){
        vError = true;
		vTexto_Error = vTexto_Error +  "Seleccione un Mes por favor </br>" ;
		$("#Mes").attr("class","input_error");
    }
	else {
		$("#Mes").attr("class","input_sesion");
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
			data: {Mes: vMes, Anio: vAnio, NivID: vNivID, CTi_ID: vCTi_ID},
			url: 'mostrarDevengadoMensual.php',
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
		  <p><img src="imagenes/table_key.png" width="32" height="32" align="absmiddle" /> Devengado Mensual
	      </p>
        </div></td>
      </tr>
       <tr>
         <td width="50%" class="texto"><div align="right">Niveles:</div></td>
         <td><?php cargarListaNivel("CMo_Niv_ID", true); ?> 
           
             </tr>
         <tr>
         <td class="texto"><div align="right">Tipo de Cuota:</div></td>
         <td><?php cargarListaTipoCuota("CTi_ID", true); ?> 
           
             </tr>
         <tr>
           <td align="right" class="texto">Año:</td>
           <td><select name="Anio" id="Anio">
           	<?php
           	$sql = "SELECT DISTINCTROW Cuo_Anio FROM CuotaPersona ORDER BY Cuo_Anio";
           	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
           	echo "<option value='-1'>Seleccione una opción</option>";
			while ($row = mysqli_fetch_array($result)){           
             	echo "<option value='$row[Cuo_Anio]'>$row[Cuo_Anio]</option>";
             }//fin while?>
           </select>           
      </tr>
      <tr>
           <td align="right" class="texto">Mes:</td>
           <td><select name="Mes" id="Mes">
           	<?php
           	$sql = "SELECT DISTINCTROW Cuo_Mes FROM CuotaPersona ORDER BY Cuo_Mes";
           	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
           	echo "<option value='-1'>Seleccione una opción</option>";
			while ($row = mysqli_fetch_array($result)){           
             	echo "<option value='$row[Cuo_Mes]'>$row[Cuo_Mes]</option>";
             }//fin while?>
           </select>           
      </tr>
      <tr><td colspan="2" align="center" class="texto">Armará los devengados basado en la vigencia de la cuota, no en la fecha de vencimiento</td></tr>
       
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
	