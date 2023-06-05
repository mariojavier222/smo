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
		//echo "buscarDNI($DNI);";
//		echo "cargarFamilia($DNI);";

	}
	?>
	
	$("#barraRequisitos").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarRequisitosPersona.php',
				data: {DNI: vDNI, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivo"},
				success: function (data){
						$("#principal").html(data);
						//jAlert(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});		

	$("#DNI_Vinc").keyup(function(evento){
		evento.preventDefault();
		//busco el DNI
		if ($(this).val().length >= 8){
			buscarDNI_Vinc($(this).val());
		}else {
			$("#PerID_Vinc").val("");
			$("#persona_vinc").val("");
		}		
		/*
		if (evento.keyCode == 13){
			buscarDNI_Vinc($(this).val());
		}
		*/
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
					$("#PerID_Vinc").val("");
					$("#persona_vinc").val("");
				}//fin if
			});
		}//fin if
	}//fin funcion

	$("#DNI").keyup(function(evento){
		evento.preventDefault();
		//busco el DNI
		if ($(this).val().length >= 8){
			buscarDNI($(this).val());
		}else {
			$("#PerID").val("");
			$("#persona").val("");
			$("#Vinculos").empty();
		}
		/*
		if (evento.keyCode == 13){
			//alert('entra');
			buscarDNI($(this).val());
		}
		*/
	});//fin de prsionar enter			
	
	function cargarDNI(vDNI){
			$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
				$("#PerID").attr("value",data);
				vPerID = $("#PerID").val();
				//alert(vPerID);
				if (vPerID>0){
					$.post("cargarOpciones.php",{opcion: 'obtenerApellidoNombre', PerID: vPerID}, function(data){
						$("#persona").val(data);
						cargarFamilia(vDNI);
					});
				}else{
					$("#PerID").val("");
					$("#persona").val("");
					$("#Vinculos").empty();
				}//fin if
		
			});
	}//fin function
	
	function buscarDNI(DNI){
		$("#PerID").attr("value",0);
		if (validarNumero(DNI)){
			cargarDNI(DNI);

		}//fin if
	}//fin funcion

	function limpiarDatos(){
		$("#DNI").val("");
		$("#DNI_Vinc").val("");
		$("#PerID").val("");
		$("#PerID_Vinc").val("");
		$("#persona_vinc").val("");
		$("#persona").val("");
		$("#Vinculos").empty();
	}//fin funcion
	
	$("#eliminarVinculo").click(function (evento){
		evento.preventDefault();
		//alert($("#Vinculos option:selected").val());
		var count = $("#Vinculos option:selected").length;

    if(count>0) {

			vValor  =$("#Vinculos option:selected").val();
			arreglo = vValor.split(",");
			vDNI_Vinc = arreglo[0];

			vDNI = $("#DNI").val();
			vPerID=$("#PerID").val();

			if (vDNI && vPerID){
				jConfirm('&iquest;Est&aacute; seguro que desea <b>ELIMINAR EL VINCULO</b>?', 'Confirmar!', function(r){
					if (r){			
						$.ajax({
							type: "POST",
							cache: false,
							async: false,
							data: {opcion: 'eliminarVinculo', PerID: vPerID, DNI_Vinc: vDNI_Vinc, UsuID: vUsuID},
							url: 'cargarOpcionesFamilia.php',
							success: function (data){
								mostrarAlerta(data, "Resultado:");
								$("#Vinculos option:selected").remove();	
							}
						});//fin ajax///
						cargarFamilia(vDNI);
					}// jconfirm
				});//fin del confirm
			}else mostrarAlerta("Falta la persona a desvincular!", "Error");
		}else mostrarAlerta("Seleccione el v&iacute;nculo a eliminar!", "Error");
	});
	 

	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		limpiarDatos();
	});


//Mario. 23/05/2022. Para borrar todos los vínculos de una persona
	$("#eliminarTodosVinculos").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		PerID=$("#PerID").val();

		if (vDNI && PerID){
			jConfirm('&iquest;Est&aacute; seguro que desea <b>ELIMINAR TODOS LOS VINCULOS FAMILIARES</b> DE ESTA PERSONA?', 'Confirmar!', function(r){
				if (r){			
					$.ajax({
						type: "POST",
						cache: false,
						async: false,
						data: {opcion: 'eliminarFamilia', DNI: vDNI, UsuID: vUsuID},
						url: 'cargarOpcionesFamilia.php'
						//success: function(msg){ alert(msg);}
					});//fin ajax///
					mostrarAlerta("Se eliminaron los v&iacute;nculos familiares de la persona con DNI "+vDNI, "Resultado de la operaci&oacute;n");
					cargarFamilia(vDNI);
				}// jconfirm
			});//fin del confirm//*/
		}else mostrarAlerta("Falta la persona a desvincular!", "Error");
	});	
//*******************************************************************

	$("#agregar").click(function(evento){
		evento.preventDefault();
		DNI = $("#DNI").val();
		DNI_Vinc=$("#DNI_Vinc").val();
		persona_vinc=$("#persona_vinc").val();
		PerID=$("#PerID").val();
		PerID_Vinc=$("#PerID_Vinc").val();

		if (DNI && DNI_Vinc && persona_vinc && PerID && PerID_Vinc){
			if (PerID!=PerID_Vinc){
				Vinculos=$("#FTiID option:selected").text();
				FTiID=$("#FTiID option:selected").val();
				encontrado=false;
				opcion = "(" + Vinculos + ") " + persona_vinc;
				id = DNI_Vinc + "," + FTiID;
				getNuevoCombo= "<option value='" + id + "'>" + opcion + "</option>";
				$("#Vinculos option").each(function(i){			 
				   valor = $(this).val();
				   arreglo = valor.split(",");
				   if (arreglo[0]==DNI_Vinc)  encontrado=true;
				});
				if (encontrado==false) $("#Vinculos").append(getNuevoCombo);
		
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'guardarFamilia', PerID: PerID, PerID_Vinc: PerID_Vinc, DNI: DNI, DNI_Vinc:DNI_Vinc, FTiID: FTiID, UsuID: vUsuID},
					url: 'cargarOpcionesFamilia.php',
					success: function (data){
							mostrarAlerta(data, "Resultado");
					}
				});//fin ajax///
			
			}else {
				mostrarAlerta("NO puede vincular a la misma persona!","Error");
			}
	 	}else {
		 	mostrarAlerta("Falta una o ambas personas a vincular!","Error");
		}				
	});



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
	
	
	function cargarFamilia(vDNI){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: 'cargarListaFamilia', DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ $("#Vinculos").html(data);}
		});//fin ajax///
	}
	
	function colocarValor(event, data, formatted) {
		if (data){
			$("#DNI").val(data.Per_DNI);
			$("#PerID").val(data.Per_ID);
			//alert('DNI:'+data.Per_DNI);
			cargarFamilia(data.Per_DNI);
		}
	}

	function colocarValorVinc(event, data, formatted) {
		if (data){
			$("#DNI_Vinc").val(data.Per_DNI);
			$("#PerID_Vinc").val(data.Per_ID);
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
	
	$("#barraVolver").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI_Volver").val();
		vPagVolver = $("#pag_Volver").val() + ".php";
		//alert(vDNI.length);
		if (vDNI.length>0){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: vPagVolver,
				data: {DNI: vDNI},
				success: function (data){
						$("#principal").html(data);
						$("#cargando").hide();
				}
			});//fin ajax
		}//fin if
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
<table border="0" align="center" cellspacing="4">
  <tr>

    <td width="48"><button class="botones" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Nuevo" /><br />
      Nuevo </button></td>
<!--    <td width="48">
    	<a href="" class="botones" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />Guardar</a>	
    	<button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      Guardar</button>-->

      <input type="hidden" name="DNI_Asp" id="DNI_Asp" value="<?php echo $_POST['DNI_Asp'];?>" />
      <input type="hidden" name="DNI_Padre" id="DNI_Padre" value="<?php echo $_POST['DNI_Padre'];?>" />
      <input type="hidden" name="Apellido_Asp" id="Apellido_Asp" value="<?php echo $_POST['Apellido_Asp'];?>" />
      <input type="hidden" name="Nombre_Asp" id="Nombre_Asp" value="<?php echo $_POST['Nombre_Asp'];?>" />          
      <input type="hidden" name="Apellido_Padre" id="Apellido_Padre" value="<?php echo $_POST['Apellido_Padre'];?>" />
      <input type="hidden" name="Nombre_Padre" id="Nombre_Padre" value="<?php echo $_POST['Nombre_Padre'];?>" />
   
   <td width="48"><button id="barraRequisitos" class="botones"><img src="imagenes/table_edit_req.png" alt="Requisitos de ingreso" width="48" height="48" border="0" /><br />
      Requisitos</button></td>

    
	 
  </tr>
</table>

	<div id="mostrarNuevo">

	<form action="" method="post" name="form_nuevo" id="form_nuevo" >
	  <table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="4"><div align="center"><span class="titulo_noticia"><img src="imagenes/Users.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia">Vincular Familias </span>
           <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" />
           <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/>
         </div></td>
        </tr>

       <tr> <td align="center" class="texto" colspan="2"><strong> ALUMNO</strong></td>
            <td align="center" class="texto" colspan="2"><strong>FAMILIAR</strong> </td>               
       </tr>

       <tr> <td class="texto"> <div align="right">Documento: </div></td>
        <td><input name="DNI" type="number" class="texto_buscador required digits" id="DNI" value="<?php echo $_POST['DNI_Asp'];?>" autocomplete="OFF"/>
        *       
          <input name="PerID" type="hidden" id="PerID" value="" />
        <td class="texto">Documento:                
        <td><input name="DNI_Vinc" type="number" class="texto_buscador required digits" id="DNI_Vinc" value="<?php echo $_POST['DNI_Padre'];?>" autocomplete="OFF"/>
          <input name="PerID_Vinc" type="hidden" id="PerID_Vinc" value=""/>        </tr>
       <tr>
         <td align="right" class="texto">Persona: </td>
         <td><input name="persona" type="text" id="persona" size="35" value=""/>       
         <td class="texto">       
         Persona:       
         <td>       
       <label>
         <input name="persona_vinc" type="text" id="persona_vinc" size="35" value=""/>
       </label>       
        </tr>
              
       <tr>
         <td rowspan="2" align="right" class="texto"><div align="right">Se vincula con... </div>
         <p><a href="#" id="eliminarVinculo" name="eliminarVinculo"><img src="imagenes/folder_delete.png" alt="Eliminar Vinculo" title="Eliminar Vinculo" width="32" height="32" border="0" /></a></p></td>
         <td rowspan="2">       
           <select name="Vinculos" size="5" multiple="multiple" class="bordeLista" id="Vinculos">
        </select> &nbsp;
         <a href="#" id="eliminarTodosVinculos" name="eliminarTodosVinculos"><img src="botones/Delete.png" alt="Eliminar Todos los Vinculos" title="Eliminar Todos los Vinculos" width="48" height="48" border="0" /></a>
        </td>
         <td class="texto">V&iacute;nculo </td>
         <td><?php cargarListaVinculosFamilia("FTiID"); ?> </td>
       </tr>
       <tr>
         <td>
         <a href="" id="agregar" class="botones"><img src="imagenes/go-previous.png" width="22" height="22" alt="Agregar Vinculo" title="Agregar Vinculo"/>Agregar</a>	

<!--         <button id="agregar" class="botones"><img src="imagenes/go-previous.png" width="22" height="22" alt="Agregar Vinculo" title="Agregar Vinculo"/>Agregar</button>-->
<td>
       </tr>
       <tr>
         <td colspan="4" class="texto">       </td>
       </tr>
    </table>
	</form>
	<br />
	<br />

</div>

<div id="mostrar"></div>

