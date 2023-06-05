<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Colegio_AmbitoInforme";

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="plugin/css/screen.css" />
<script src="plugin/jquery.validate.js"></script>
<script src="plugin/localization/messages_es.js"></script>
<script src="plugin/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>
<script language="javascript">
$.validator.setDefaults({
	submitHandler: function() { 
		
		Curso = $("#Curso").val();
		if (Curso==-1){
			jAlert("Error: Debe seleccionar un curso", "ERROR");
			return;
		}
		Tipo_Impresion = $("#Tipo_Impresion").val();
		Inf_Semestre = $("#Inf_Semestre").val();
		if (Inf_Semestre==1)
			  ir = "1Sem";
		  else
			  ir = "2Sem";
		if (Tipo_Impresion=="Alumno"){			
			$("#formDatos").attr("action", "imprimir_informe_nivel_inicial_" + ir + ".php");
			Inf_Leg_ID = $("#Inf_Leg_ID").val();
			if (Inf_Leg_ID==-1){
				jAlert("Error: Debe seleccionar un alumno", "ERROR");
				return;
			}
		}else{
			$("#formDatos").attr("action", "imprimir_informe_nivel_inicial_curso_" + ir + ".php");
		}
		
		$("#formDatos").submit();
	}
});
$(document).ready(function(){
	
	//Datos iniciales
	$("#formDatos").validate();//fin validation
	$("#mostrar").empty();
	$("#botImprimir").button();
	
	
	$("#Curso").change(function () {
   		$("#Curso option:selected").each(function () {
			//alert($(this).val());
				Curso=$(this).val();
				//alert("");
				llenarAlumnos(Curso);
        });
   	});
	Curso=$("#Curso option:selected").val();
	if (Curso!=-1) llenarAlumnos(Curso);
	
	function llenarAlumnos(vID){
		Inf_Lec_ID = $("#Inf_Lec_ID").val();
		//alert(Inf_Lec_ID);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaAlumnosClase2",  ID: vID, Nombre: "Inf_Leg_ID", Inf_Lec_ID: Inf_Lec_ID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#Alumnos").html(data);
			}
		});//fin ajax//*/
		
	}//fin funcion
	 
});//fin de la funcion ready


</script>

<div id="mostrarNuevo">
  <form action="imprimir_informe_nivel_inicial.php" method="post" target="_blank" class="cmxformNOOO" id="formDatos">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/group.png" alt="Paises" width="32" height="32" align="absmiddle" /> Imprimir Informe</div></td>
      </tr>
          <tr>
            <td class="texto" align="right">Curso/División</td>
            <td align="left" class="titulo_noticia">
			
			<?php 
			cargarCursoDivisionNivelInicial("Curso");			
			?></td>
          </tr>
          <tr>
            <td class="texto" align="right">Alumnos:</td>
            <td align="left"><div id="Alumnos">
            </div></td>
          </tr>
          <tr>
            <td class="texto" align="right">Ciclo Lectivo:</td>
            <td align="left"><?php 
			$UniID = $_SESSION['sesion_UniID'];
			cargarListaLectivoInscripcion("Inf_Lec_ID", $UniID);?><input name="opcion" type="hidden" id="opcion" value="imprimir<?php echo $Tabla;?>" /></td>
        </tr>
         <tr>
            <td align="right" class="texto">Periodo:</td>
            <td class="texto"><label for="Tipo_Impresion"></label>
              <select name="Inf_Semestre" id="Inf_Semestre">
                <option value="1">Primer Semestre</option>
                <option value="2" selected="selected">Segundo Semestre</option>
            </select></td>
      </tr>
          <tr>
            <td align="right" class="texto">Tipo de impresión:</td>
            <td class="texto"><label for="Tipo_Impresion"></label>
              <select name="Tipo_Impresion" id="Tipo_Impresion">
                <option value="Alumno" selected="selected">Por Alumno</option>
                <option value="Curso">Por Curso</option>
            </select></td>
      </tr>
          <tr>
            <td align="right" class="texto">Qué imprimir?</td>
            <td class="texto"><select name="Que_Imprimir" id="Que_Imprimir">
              <option value="todo">Todo el informe</option>
              <option value="portada">Sólo la portada</option>
              <option value="interior">Sólo el interior</option>
            </select></td>
          </tr>
          <tr>
            <td align="center" class="texto"></td>
            <td class="texto"><button id="botImprimir">Imprimir Informe</button></td>
        </tr>
    </table>
</form></div>
	<p><br />
	  <br />
    </p>
	<div id="mostrar"></div>
	<p>&nbsp;</p>
