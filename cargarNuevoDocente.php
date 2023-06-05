<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	$("#mostrarNuevo").show();
	$("#cargando").hide();
	$("#mostrar").empty();
	$("#DNI").focus();
	$("#Alta").val("<?php echo date("d/m/Y");?>");	
	$("#Estado").empty();

	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		vPerID = $("#PerID").val();
		vLegajo = $("#Legajo").val();
		vActivo = $("#Activo").val();
		vAlta = $("#Alta").val();
		
		if (vPerID.length==0){
			mostrarAlerta("Debe ingresar un valor válido en el DNI.", "Error");	
			return;
		}		
		if (vLegajo.length==0){
			mostrarAlerta("Debe ingresar un valor en el Legajo.", "Error");	
			return;
		}		
		
		if (vAlta.length==0){
			mostrarAlerta("Debe ingresar un valor en la Fecha de Alta.", "Error");	
			return;
		}		
		if (!validarNumero(vLegajo)){
			mostrarAlerta("Debe ingresar solo digitos en el Legajo.", "Error");
			return;
		}
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "guardarAltaDocente", PerID: vPerID, Legajo: vLegajo, Activo: vActivo, Alta: vAlta},
			url: 'cargarOpciones.php',
			success: function(data){ 
				mostrarAlerta(data, "Resultado de guardar los cambios");
				recargarPagina();
			}
		});//fin ajax//*/
	});
	function recargarPagina(){
		$("#mostrar").empty();

		$.ajax({
			cache: false,
			async: false,			
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
					$("#principal").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	}//fin function
	function cargarCuentaUsuario(){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarCuentaUsuarioDocente", PerID: $("#PerID").val()},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				arreglo = data.split(";");
				$("#cuentaUsuario").text(arreglo[0]);
				$("#cuentaClave").text(arreglo[1]);
				$("#cargando").hide();
			}
		});//fin ajax//*/
	}//fin function
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		$("#DNI").val("");
		$("#PerID").val("");
		$("#persona").val("");
		$("#Legajo").val("");
		$("#Activo").val(1);
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();
		$("#Alta").val("<?php echo date("d/m/Y");?>");	
	});//fin evento click
//*/
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
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "buscarPerID", DNI: vDNI},
					url: 'cargarOpciones.php',
					success: function(data){ 
						$("#PerID").val(data);
						buscarDatos(vDNI);						
					}
				});//fin ajax//*/
				
				
			}
		});//fin ajax//*/
		
		

	}
	
	function buscarDatos(vDNI){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarDatosDocente", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				if (data!="{}"){
					var obj = $.parseJSON(data);
					$("#Alta").val(obj.Doc_Alta);
					$("#Legajo").val(obj.Doc_Legajo);
					$("#Activo").val(obj.Doc_Activo);
					$("#Estado").html("ES PROFESOR");
					if (obj.Per_Sexo=="F"){
						$("#Estado").append("A");
					}
					if (obj.Doc_Activo==0){
						$("#Estado").append(" - ESTA DADO DE BAJA");
					}					
					$("#Foto").html(obj.Per_Foto);
					cargarCuentaUsuario();
					
				}else {
					limpiarDatos();
					$("#Estado").html("NO ES PROFESOR/A");					
					}
			}
		});//fin ajax//*/
	}
	
	function limpiarDatos(){
		$("#Alta").val("<?php echo date("d/m/Y");?>");
		//$("#Legajo").val("");
		$("#Activo").val(1);
		$("#Estado").empty();
	}
	
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		if (evento.keyCode == '13'){
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosPersona.php", {
		//multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,		
		formatItem:function(item, index, total, query){
		   return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatMatch:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatResult:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },

		dataType: "json",
		//parse: prep_data,
		parse:function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.Per_Apellido,
					result: row.Per_Apellido + ", " + row.Per_Nombre
				}
				$("#cargando").hide();
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	
	function colocarValor(event, data, formatted) {
		if (data){
			$("#DNI").val(data.Per_DNI);
			$("#PerID").val(data.Per_ID);
			buscarDatos(data.Per_DNI);
			$("#mostrar").empty();
			
		}
	}
	$("#Alta").datepicker($.datepicker.regional['es']);

});//fin de la funcion ready

$(function() {
		$(".botones").button();
	});

</script>
<link href="css/general.css" rel="stylesheet" type="text/css" />


	<div id="mostrarNuevo"><!--<form autocomplete="off" onsubmit="return false;">-->
	  <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
          <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/icono919.gif" width="32" height="33" align="absmiddle" /> Dar de alta al Docente</div></td>
        </tr>
        <tr>
          <td class="texto"><div align="right"></div></td>
          <td><div id="Foto"></div></td>
        </tr>
          <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" value="<?php echo $_SESSION['sesion_ultimoDNI'];?>"/>
        *       
          <input name="PerID" type="hidden" id="PerID" /> 
          <div class="ui-state-error ui-corner-all" id="Estado" style="font-size:14px"></div></td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="35" />          </td>
      </tr>
        <tr>
          <td class="texto"><div align="right">N&ordm; Legajo: </div></td>
          <td><input name="Legajo" type="text" id="Legajo" class="required digits" value="<?php
          $sql = "SELECT Doc_Legajo FROM Colegio_Docente ORDER BY Doc_Legajo DESC LIMIT 0,1";
		  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		  $row = mysqli_fetch_array($result);
		  echo $row[Doc_Legajo] + 1;
		  ?>" />
            </td> 
        </tr>
        <tr>
          <td class="texto"><div align="right">&iquest;Est&aacute; activo? </div></td>
          <td><select name="Activo" id="Activo">
            <option value="1" selected="selected">Si</option>
            <option value="0">No</option>
          </select>
		</td>
        </tr>
        <tr>
          <td align="right" class="texto">Fecha de alta:</td>
          <td><input name="Alta" type="text" id="Alta"  /></td>
        </tr>
        <tr>       
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos de la cuenta de usuario</div></td>
        </tr>
       <tr>
         <td class="texto"><div align="right">Usuario asignado:</div></td>
        <td colspan="2"><div id="cuentaUsuario" class="ui-state-highlight ui-corner-all" style="width:200px; font-size:14px" align="center">usuario</div>
        </tr>		
	<tr>
         <td class="texto"><div align="right">Clave por defecto:</div></td>
        <td colspan="2"><div id="cuentaClave" class="ui-state-highlight ui-corner-all" style="width:200px; font-size:14px" align="center">Clave</div>
        </tr>	
        <tr>
          <td colspan="2"><div align="center">
            <p>&nbsp;</p>
            <button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
       Nuevo </button> 
            <button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Guardar</button></div></td>
        </tr>
      </table>
   <!--   </form>-->
</div>
		<br /><br />
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	
	

	
