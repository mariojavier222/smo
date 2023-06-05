<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$(document).ready(function(){
	
	vDNI = 0;

	$("#DNI").focus();
	$("#Persona").hide();
	
		
	$("#personaNombre").result(colocarValor);	
	$("#personaNombre").autocomplete("buscarDatosPersona.php", {
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
			buscarDNI(data.Per_DNI);
		}
	}
	

	function cargarDNI(){
		iDNI = $("#DNI").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerApellidoNombre", DNI: iDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#personaNombre").val(data);
				buscarDNI(iDNI);
				//$("#cargando").hide();
			}
		});//fin ajax//*/
	
	}
	$("#DNI").keyup(function(evento){
		evento.preventDefault();
		iDNI = $("#DNI").val();
		if (evento.keyCode == 13 && iDNI.length>2){
			//$("#cargando").show();
			cargarDNI();
		}//fin if
	});//fin de prsionar enter			

	function buscarDNI(DNI){
		$("#PerID").attr("value",0);
		if (validarNumero(DNI)){
				vDNI = DNI;
				$.post("cargarOpciones.php",{opcion: 'buscarDNI', DNI: vDNI}, function(data){
					$("#Persona").show();
					$("#Persona").html(data);
					$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
						$("#PerID").attr("value",data);
						vPerID = $("#PerID").val();						
					});					
				});

		}//fin if
	}//fin funcion

	$("#barraEliminar").click(function(evento){
		evento.preventDefault();
		
		vPerID = $("#PerID").val();	
		//alert(vPerID);return;
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: 'eliminarPersonaNueva', PerID: vPerID},
			url: 'cargarOpciones.php',		
			success: function (data){
				jAlert(data, "Resultado");
				}
			});//fin ajax///
	});//*/
   
});//fin de la funcion ready
</script>
<style type="text/css">
<!--
.Estilo2 {font-size: 12pt; font-weight: bold;}
-->
</style>
<div id="mostrarNuevo">

  <table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="2"><div align="center"><span class="titulo_noticia"><img src="imagenes/borrar_activo.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia"> Eliminar Persona </span></div></td>
        </tr>
       <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" />
        *       
          PerID: <input name="PerID" type="input" id="PerID" />
          <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" />
          <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/>
        </tr>
       <tr>
         <td align="right" class="texto"><strong>Buscar Persona   :</strong></td>
         <td><input name="personaNombre" type="text" id="personaNombre" size="35" />       
        </tr>
       <tr>
         <td colspan="2" class="texto"><div id="Persona"></div>       </td>
        </tr>
       <tr>
         <td colspan="2" align="center" class="texto"><button id="barraEliminar">Eliminar</button></td>
    </tr>
       <tr>
         <td colspan="2" class="texto"><div id="mostrar"></div>       </td>
        </tr>
    </table>

		
</div>



