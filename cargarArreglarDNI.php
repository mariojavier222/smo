<?php
include_once("comprobar_sesion.php");
//require_once("registro_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");

?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$(document).ready(function(){


	vUsuID = <?php echo $_SESSION['sesion_UsuID'];?>;
	<?php 
	if (isset($_POST['DNI'])){
		$DNI = $_POST['DNI'];
		echo "$('#DNI').val($DNI);";
		echo "buscarDNI($DNI);";
		echo "cargarFamilia($DNI);";

	}
	?>
	$("#DNI_Vinc").keyup(function(evento){
		evento.preventDefault();
		if (evento.keyCode == 13){
			buscarDNI_Vinc($(this).val());
		}
	});//fin de prsionar enter			
	function buscarDNI_Vinc(DNI){
		$("#PerID_Vinc").attr("value",0);
		if (validarNumero(DNI)){
					vDNI = DNI;
					$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
						$("#PerID_Vinc").attr("value",data);
						vPerID = $("#PerID_Vinc").val();
						//alert(vPerID);
						if (vPerID>0){
							$.post("cargarOpciones.php",{opcion: 'obtenerApellidoNombre', PerID: vPerID}, function(data){
								$("#persona_vinc").val(data);							
							});
						}else{
							//limpiarDatos();
						}//fin if

					});


		}//fin if
	}//fin funcion
	$("#DNI").keyup(function(evento){
		evento.preventDefault();
		if (evento.keyCode == 13){
			buscarDNI($(this).val());
		}
	});//fin de prsionar enter			
	function cargarDNI(vDNI){
			$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
				$("#PerID").attr("value",data);
				vPerID = $("#PerID").val();
				//alert(vPerID);
				if (vPerID>0){
					$.post("cargarOpciones.php",{opcion: 'obtenerApellidoNombre', PerID: vPerID}, function(data){
						$("#persona").val(data);							
					});
				}else{
					//limpiarDatos();
				}//fin if
		
			});
	}//fin function
	function buscarDNI(DNI){
		$("#PerID").attr("value",0);
		if (validarNumero(DNI)){
			cargarDNI(DNI);

		}//fin if
	}//fin funcion

	
	 
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		return;
		vDNI_Bueno = $("#DNI").val();
		vDNI_Malo = $("#DNI_Vinc").val();
		if (vDNI && vDNI_Vinc){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'arreglarDNI', DNI_Bueno: vDNI_Bueno, DNI_Malo: vDNI_Malo},
				url: 'cargarOpciones.php'
				success: function(data){ 
					mostrarAlerta("Se ha arreglado el DNI del nuevo alumno correctamente", "Resultado de la operación");
				}
			});//fin ajax///
			
		}//fin if
		
	});//*/


	$("#persona").autocomplete("buscarDatosPersona.php", {
		//multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,		
		formatItem:function(item, index, total, query){
		   return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatMatch:function(item){
           return item.Per_Apellido;
       },
       formatResult:function(item){
           return item.Per_DNI;
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
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	
	function colocarValorVinc(event, data, formatted) {
		if (data)
			$("#DNI_Vinc").val(data.Per_DNI);
		
	}
	
	function colocarValor(event, data, formatted) {
		if (data){
			$("#DNI").val(data.Per_DNI);
		}
	}

	$("#persona_vinc").result(colocarValorVinc);
	
	$("#persona").result(colocarValor);	
	$("#persona_vinc").autocomplete("buscarDatosPersona.php", {
		//multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,
		formatItem:function(item, index, total, query){           
		   return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatMatch:function(item){
           $("#DNI_Vinc").val(item.Per_DNI);
		   return item.Per_Apellido;
       },
       formatResult:function(item){
		   
           return item.Per_Apellido;
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
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});

});//fin de la funcion ready
$(function() {
		$(".botones").button();
});
</script>
<style type="text/css">
<!--
.Estilo2 {font-size: 12pt; font-weight: bold;}
-->
</style>
<div id="mostrarNuevo">

	  <table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="4"><div align="center"><span class="titulo_noticia"><img src="imagenes/group_edit.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia"> Arreglar DNI </span>
             <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" />
         </div></td>
        </tr>
       <tr>
         <td colspan="2" class="texto"><strong>Documento bien cargado         </strong></td>
        <td colspan="2" class="texto"><strong>Documento mal cargado     </strong></tr>
       <tr> <td class="texto"> <div align="right">Documento: </div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" />
        *       
          <input name="PerID" type="hidden" id="PerID" />
        <td class="texto">Documento:                
        <td><input name="DNI_Vinc" type="text" class="texto_buscador required digits" id="DNI_Vinc" />
          *
          <input name="PerID_Vinc" type="hidden" id="PerID_Vinc" />        </tr>
       <tr>
         <td align="right" class="texto">Persona: </td>
         <td><input name="persona" type="text" id="persona" size="35" />       
         <td class="texto">       
         Persona:       
         <td>       
       <label>
         <input name="persona_vinc" type="text" id="persona_vinc" size="35" />
       </label>       
        </tr>
       <tr>
         <td colspan="4" align="center" class="texto"><button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      Arreglar</button>         </td>
        </tr>
        <tr>
         <td colspan="4" class="texto">       </td>
        </tr>
    </table>
	<br />
	<br />

</div>

<div id="mostrar"></div>

