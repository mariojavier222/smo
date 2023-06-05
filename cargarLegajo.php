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
	
	$("#fechaAlta").datepicker($.datepicker.regional['es']);
	$("#fechaBaja").datepicker($.datepicker.regional['es']);
	
	$(".botones").button();

	//vDNI = 0; 
	limpiarDatos();
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
				$("#cargando").hide();
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
	
	$("#DNI").keyup(function(evento){
		evento.preventDefault();
		iDNI = $("#DNI").val();
		if (evento.keyCode == 13 && iDNI!=""){
			limpiarDatos();
			$("#cargando").show();
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
					$("#cargando").hide();
				}
			});//fin ajax//*/
		
			
		}//fin if

	});//fin de prsionar enter			

	$("#GenerarLegajoColegio").click(function(evento){
		evento.preventDefault();
		$("#cargando").show();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "GenerarLegajoColegio"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#Legajo").val(data);
				$("#cargando").hide();
			}
		});//fin ajax//*/
	});//fin de prsionar enter			

	function buscarDNI(DNI){
		$("#PerID").attr("value",0);
		limpiarDatos();
		$("#noTieneLegajo").text("No tiene legajo");
		if (validarNumero(DNI)){
				vDNI = DNI;
				$.post("cargarOpciones.php",{opcion: 'buscarDNI', DNI: vDNI}, function(data){
					$("#Persona").show();
					$("#Persona").html(data);
					$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
						$("#PerID").attr("value",data.trim());
						vPerID = $("#PerID").val();
						//alert(vPerID);
						if (vPerID>0){
							vTipoLegajo = $("#TipoLegajo").val();
							$.post("cargarOpciones.php",{opcion: 'buscarDatosLegajo', PerID: vPerID, TipoLegajo: vTipoLegajo}, function(data){
								//alert(data);
								if (data.trim()!="{}"){
									var obj = $.parseJSON(data);
									//alert(obj.total_legajos);
									if (obj.total_legajos>1){
										 mostrarAlerta("El alumno seleccionado tiene más de un legajo. No se puede continuar", "ERROR -ALERTA");
									}else{
										$("#fechaAlta").val(obj.Leg_Alta_Fecha);
										$("#Legajo").val(obj.Leg_Numero);
										$("#SedID").val(obj.Leg_Sed_ID);
										$("#noTieneLegajo").text("Legajo " + obj.Leg_Numero);
										$("#Leg_Baja").removeAttr("checked");
										//alert(obj.Leg_Baja);
										if (obj.Leg_Baja==1){
											$("#Leg_Baja").attr("checked", "checked");
											$("#fechaBaja").val(obj.Leg_Baja_Fecha);
											$("#motivo").val(obj.Leg_Baja_Motivo);
										}
										/*if (obj.Leg_Baja_Fecha!="00/00/0000"){
											$("#fechaBaja").val(obj.Leg_Baja_Fecha);
											$("#motivo").val(obj.Leg_Baja_Motivo);
										}*/
										cargarCuentaUsuario();
									}//fin else
								}else {
									//limpiarDatos();
									}
							});
						}else{
							limpiarDatos();
						}//fin if//*/

					});

				});
			
		}//fin if
	}//fin funcion

	function limpiarDatos(){
		$("#fechaAlta").val("<?php echo date("d/m/Y");?>");
		$("#fechaBaja").val("");
		$("#noTieneLegajo").empty();
		$("#motivo").val("");
		$("#PerID").val("");
		$("#cuentaUsuario").empty();
		$("#cuentaClave").empty();
		$("#Persona").empty();		
		$("#Legajo").val("");
		$("#SedID").val(1);
		$("#Leg_Baja").removeAttr("checked");
	}//fin funcion
	
	
	function validarDatos(){
        vDNI = $("#DNI").val();
				persona=$("#personaNombre").val();
				vPerID=$("#PerID").val();
        vfechaAlta=$("#fechaAlta").val();

        if (vDNI.length==0 || vPerID.length==0){
            jAlert("Falta el DNI", "ERROR");
            $("#DNI").focus();
            return false;
        }
        if (persona.length==0){
            jAlert("Falta la persona", "ERROR");
            $("#personaNombre").focus();
            return false;
        }
				if (vfechaAlta.length==0){
            jAlert("Falta la fecha de Alta", "ERROR");
            $("#fechaAlta").focus();
            return false;
        }        
        
        return true;
  }	
	 
	 function datosEncontrados(data){		 
   	mostrarAlerta(data, "Resultado de guardar los cambios");
		cargarCuentaUsuario();
		vDNI = $("#DNI").val();
		buscarDNI(vDNI);
		$("#cargando").fadeOut();
	 };
	
	function cargarCuentaUsuario(){
		vPerID=$("#PerID").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarCuentaUsuarioAlumno", PerID: vPerID},
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


	$("#barraGuardar").click(function(evento){
//		alert("SI");
		evento.preventDefault();
		if (validarDatos()){
		  datos = $("#form_datos").serialize();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'guardarLegajo.php',
				data: datos,
				success: function (data){
						jAlert(data,"Resultado");
						buscarDNI(vDNI);
						$("#cargando").hide();
						}
			});//fin ajax				

		}//	else jAlert("Error!","Error");
		
	});
	
	$("#barraInscripcion").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		//alert(vDNI.length);
		if (vDNI.length==0){
			mostrarAlerta("Antes de Inscribir debe seleccionar un alumno","Atención");
		}else{
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarInscripcionLectivo.php',
				data: {DNI: vDNI},
				success: function (data){
						$("#principal").html(data);
						$("#cargando").hide();
						}
			});//fin ajax
		}//fin if
	});

	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		limpiarDatos();
		$("#DNI").val("");
		$("#personaNombre").val("");

		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();
		$("#divBuscador").fadeOut();
		$("#DNI").focus();
	});	
});//fin de la funcion ready
</script>
<style type="text/css">
<!--
.Estilo2 {font-size: 12pt; font-weight: bold;}
-->
</style>
<table border="0" align="center" cellspacing="4">
  <tr>
    <td width="48"><button class="barra_boton" id="barraNuevo" > <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
      Nuevo </button></td>
    <td width="48"><button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      Guardar</button></td>
    <td width="48"><button class="barra_boton" id="barraInscripcion"><img src="botones/app_48.png" alt="Inscribir al Ciclo Lectivo" width="48" height="48" border="0" /><br />
      Inscripci&oacute;n</button></td>

  </tr>
</table>

	<div id="mostrarNuevo">

	<form action="" method="post" name="form_datos" id="form_datos" >
	  <table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="3"><div align="center"><span class="titulo_noticia"><img src="iconos/mod_legajo.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia"> Asignar Legajo</span></div></td>
        </tr>
       <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="number" class="texto_buscador required digits" id="DNI" value=""/>
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input name="TipoLegajo" type="hidden" id="TipoLegajo" value="Colegio" />
          <input name="UsuID" type="hidden" id="UsuID" value="<?php echo $_SESSION['sesion_UsuID'];?>" />
               
        <td><div class="ui-state-error ui-corner-all" id="noTieneLegajo" style="font-size:14px"></div>
       </tr>
       <tr>
         <td align="right" class="texto"><strong>Buscar Persona   :</strong></td>
         <td colspan="2"><input name="personaNombre" type="text" id="personaNombre" size="35" />       
        </tr>
       <tr>
         <td colspan="3" class="texto"><div id="Persona"></div>       </td>
        </tr>
       <tr>
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos del Alta</div></td>
        </tr>
       <tr>
         <td class="texto"><div align="right">Fecha de alta: 
         </div></td>
        <td colspan="2"><input name="fechaAlta" type="text" id="fechaAlta" class="required fechaCompleta" />
        *       </tr>
       
       <tr>
       <tr>
         <td class="texto"><div align="right">Sede:</div></td>
         <td colspan="2"><?php cargarListaSede("SedID", $_SESSION['sesion_SedID']);?> 
           
             </tr>
        <tr>
         <td class="texto"><div align="right">Es alumno de:</div></td>
         <td colspan="2"><select name="Leg_Colegio" id="Leg_Colegio">
            <option value="1">Colegio</option>

           </select>
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
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos de la Baja</div></td>
        </tr>
        <tr>
         <td align="right" class="texto">&iquest;Est&aacute; dado de baja?</td>
         <td colspan="2"><input name="Leg_Baja" type="checkbox" id="Leg_Baja" value="1" />       
        </tr>
       <tr>
         <td class="texto"><div align="right">Fecha de la Baja:</div></td>
        <td colspan="2"><input name="fechaBaja" type="text" id="fechaBaja" />
        </tr>		
       <tr>
         <td class="texto"><div align="right">Motivo de la Baja:</div></td>
        <td colspan="2"><textarea name="motivo" cols="20" rows="5" id="motivo"></textarea>       </tr>
       <tr>
         <td class="texto">&nbsp;</td>
        <td colspan="2">       </tr>
    </table>
	  <p>&nbsp;</p>
    </form>
		
</div>

<div id="mostrar"></div>

