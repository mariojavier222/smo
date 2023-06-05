<?php
header("Cache-Control: no-cache, must-revalidate");
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
echo "Esta opción ya no se encuentra en Napta Cobranzas. Para acceder a ella deberá ir a Napta Colegios.";
exit;
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
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
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
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarCursoDivision", PerID: $("#PerID").val()},
				url: 'cargarOpciones.php',
				success: function(data){ 
					$("#textoCurso").val(data);
					//alert("no entre");
				}
			});//fin ajax//*/
			
			$("#mostrar").empty();
		}
	}    

        $("#barraMostrar").click(function(evento){
		
                
        });
       
        $(".botones").button();
        
		$("#NivID").change(function () {
			$("#NivID option:selected").each(function () {
				validarCursoDivision();
			});
	    });
		$("#CurID").change(function () {
			$("#CurID option:selected").each(function () {
				validarCursoDivision();
			});
	    });
		$("#DivID").change(function () {
			$("#DivID option:selected").each(function () {
				validarCursoDivision();
			});
	    });
		
		function validarCursoDivision(){
			var texto = '';
			if ($("#NivID").val()>0){
				texto = $("#NivID option:selected").text();
			}
			if ($("#CurID").val()>0){
				texto = texto + " - " + $("#CurID option:selected").text();
			}
			if ($("#DivID").val()>0){
				texto = texto + " " + $("#DivID option:selected").text();
			}
			$("#tituloCursoDivision").val(texto);
		}
	
    });//fin de la funcion ready


</script>


<div id="mostrarCuotas">
<form action="imprimir_solicitud_retiro.php" method="post" target="_blank" class="cmxformNOOO" id="formDatos">
    <p>&nbsp;</p>
    <table width="95%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td colspan="2">		
                <div align="center" class="titulo_noticia"><img src="imagenes/table.png" width="32" height="32" align="absmiddle" /> Imprimir Retiro antes de Hora</div></td>
        </tr>
        <tr>
            <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
            <td><?php
               $UniID = $_SESSION['sesion_UniID'];
               //echo $UniID;
               //cargarListaLectivoInscripcion("LecID", $UniID);
               cargarListaLectivo("LecID");
               ?> </td>
        </tr>      
        
        <!--<tr>
          <td align="right" class="texto">Turno:</td>
          <td><select name="Turno" id="Turno">
            <option value="999999">Todos los turnos</option>
            <option value="Mañana">Mañana</option>
            <option value="Tarde">Tarde</option>            
          </select>                    
      </tr>
        <tr>
            <td class="texto"><div align="right">Nivel del Colegio:</div></td>
            <td><?php //cargarListaNivel("NivID", true); ?> 

        </tr>
        <tr>
            <td class="texto"><div align="right">Curso:</div></td>
            <td><?php //cargarListaCursos("CurID", true); ?> 

        </tr>
        <tr>
         <td class="texto"><div align="right">División:</div></td>
         <td><?php //cargarListaDivision("DivID", true);?> 
           
             </tr>-->
           <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" readonly="readonly" <?php if (!empty($_POST['DNI'])) echo " value='".$_POST['DNI']."'";?>/>
        *       
          <input name="PerID" type="hidden" id="PerID" />      
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
            <td colspan="2" align="center" class="texto">
            
            <!--<button class="botones" id="barraDeudores">Padrón Deudores</button>
            <button class="botones" id="barraEstado">Estado de Cuentas</button>
             <button class="botones" id="barraGrilla">Grilla de Alumnos</button>-->
            </td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="texto"><fieldset style="width:auto">
          <legend>
          <strong>Configuración de parámetros adicionales </strong></legend>
          <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td align="right"><span class="texto">Fecha:</span></td>
              <td><span class="texto">
                <input name="textoDia" type="text" id="textoDia" value="<?php echo date("d");?>" size="5" required />
                <input name="textoMes" type="text" id="textoMes" value="<?php 			require_once("globales.php");
				global $gMes;
				$mes = date("n");
				echo $gMes[$mes];?>" size="12" required />
                <input name="textoAnio" type="text" id="textoAnio" value="<?php echo date("Y");?>" size="10" required />
              </span></td>
            </tr>
            <tr class="texto">
              <td align="right">Título de la Solicitud:</td>
              <td><input name="textoTitulo" type="text" required id="textoTitulo" value="RETIRO A MI HIJO/A ANTES DE HORA" size="50" /></td>
            </tr>
            <tr class="texto">
              <td align="right">Curso y División:</td>
              <td><input name="textoCurso" type="text" id="textoCurso" size="50" required /></td>
            </tr>
            <tr class="texto">
              <td align="right">Apellido Padre/Madre:</td>
              <td><input name="textoApellidoPadre" type="text" id="textoApellidoPadre" size="50" required /></td>
            </tr>
            <tr class="texto">
              <td align="right">Nombre Padre/Madre:</td>
              <td><input name="textoNombrePadre" type="text" id="textoNombrePadre" size="50" required /></td>
            </tr>
            <tr class="texto">
              <td align="right">DNI del Padre/Madre</td>
              <td><input name="textoDNIPadre" type="text" id="textoDNIPadre" size="50" required /></td>
            </tr>
            <tr class="texto">
              <td align="right">Motivo: </td>
              <td><textarea name="textoLineaAdicional" id="textoLineaAdicional" cols="45" rows="5"></textarea></td>
            </tr>
            <tr class="texto">
              <td colspan="2" align="center"><button class="botones" id="barraMostrar">Imprimir Solicitud</button></td>
            </tr>
            <!--<tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>-->
          </table>
          </fieldset>
          </td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
        </tr>
    </table>
    
</form>
</div>

<p>&nbsp;</p>
