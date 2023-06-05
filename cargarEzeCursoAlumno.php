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
<link rel="stylesheet" href="plugin/css/screen.css" />

<script src="plugin/jquery.validate.js"></script>
<script src="plugin/localization/messages_es.js"></script>
<script src="plugin/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>

<script language="javascript">
$.validator.setDefaults({
	submitHandler: function() { 
		
		datos = $("#formDatos").serialize();
		$("#formDatos").submit();
		/*$.ajax({
			type: "POST",
			cache: false,
			async: false,			
				url: 'imprimir_certificado_escolaridad.php',
			data: datos,
			success: function (data){
				
				mostrarCartel(data, "Resultado de guardar los datos");
			}
		});//fin ajax*/
	}
});
</script>
<script language="javascript">
    $(document).ready(function(){
	
	$("#formDatos").validate();//fin validation
	
    function cargarDNI(){
		vDNI = $("#DNI").val();
		//alert("");
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
				//alert("no entre");
			}
		});//fin ajax//*/
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarDNI", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#PersonaDatos").show();
				$("#PersonaDatos").html(data);
				
				//alert("no entre");
			}
		});//fin ajax//*/
		
		

	}
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		//alert("Enter");
		if (evento.keyCode == '13'){
			
			$("#mostrar").empty();
			//$("#cargando").show();
			cargarDNI();
			//$("#cargando").hide();
		}
	});
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosAlumno.php", {
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
				//$("#cargando").hide();
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	
	function colocarValor(event, data, formatted) {
		if (data){
			$("#DNI").val(data.Per_DNI);
			$("#PerID").val(data.Per_ID);
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarDNI", DNI: data.Per_DNI},
				url: 'cargarOpciones.php',
				success: function(data){ 
					$("#PersonaDatos").show();
					$("#PersonaDatos").html(data);
					//alert("no entre");
				}
			});//fin ajax//*/
			LecID = $("#LecID").val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarClasesAlumno", DNI: data.Per_DNI, LecID: LecID},
				url: 'cargarOpciones.php',
				success: function(data){ 
					$("#mostrarClases").html(data);
					//alert("no entre");
				}
			});//fin ajax//*/
			
			$("#mostrar").empty();
		}
	}    

        $("#barraMostrar").click(function(evento){
		
                
        });
	
    });//fin de la funcion ready


</script>


<div id="mostrarCuotas">
    <p>&nbsp;</p>
    <table width="95%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td colspan="2">		
                <div align="center" class="titulo_noticia"><img src="imagenes/table.png" width="32" height="32" align="absmiddle" /> Clases por alumno</div></td>
        </tr>    

           <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" readonly="readonly" <?php if (!empty($_POST['DNI'])) echo " value='".$_POST['DNI']."'";?>/>
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input name="LecID" type="hidden" id="LecID" value="<?php $aLectivo = ''; $aLecID = gLectivoActual($aLectivo); echo $aLecID; ?>" />
        <td>
       </tr>
        <tr>
         <td align="right" class="texto"><strong>Buscar Persona   :</strong></td>
         <td colspan="2"><input name="persona" type="text" id="persona" size="35" />       
        </tr>
        <tr>
          <td colspan="3" align="right" class="texto"> <div id="PersonaDatos"></div>&nbsp;          </td>
      </tr>

        <tr>
            <td colspan="2" align="center" class="texto"><div id="mostrarClases"></div></td>
        </tr>
    </table>
</div>

<p>&nbsp;</p>
