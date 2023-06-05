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
	
	<?php 
	if (isset($_SESSION['sesion_UsuID']))
		echo "vUsuID = ".$_SESSION['sesion_UsuID'];
	else
		echo "document.location = 'index.php'";//*/
	?>;
	

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
			}
		});//fin ajax//*/

	}
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		if (evento.keyCode == '13' && vDNI!=""){
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	$("#botonLimpiar").click(function(evento){	
		evento.preventDefault();
		$("#DNI").val("");
		$("#persona").val("");
		$("#PerID").val("");
	});
	$("#barraMostrar").click(function(evento){
		vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vNivID = $("#NivID").val();
		vDivID = $("#DivID").val();
		vPerID = $("#PerID").val();
		$("#mostrarErrores").empty();
		//alert(vPerID);
		if (vPerID == 0){
			if (vLecID==-1){
				mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
				$("#LecID").focus();
				return;
			}
			if (vCurID==-1){
				mostrarAlerta("Debe seleccionar un Curso", "ERROR");
				$("#CurID").focus();
				return;
			}
			if (vNivID==-1){
				mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
				$("#NivID").focus();
				return;
			}
			if (vDivID==-1){
				mostrarAlerta("Debe seleccionar una División", "ERROR");
				$("#DivID").focus();
				return;
			}
		}//fin if PerID
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, PerID: vPerID},
			url: 'mostrarListadoInscriptosVerificar.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});

/*
	$("#barraVerificar").click(function(evento){
		evento.preventDefault();
		$("#mostrarErrores").empty();
		$("input[id^='DNI']").each(function(index){
			i = this.id.substr(3,10);
			valorIns = $("#Inscrip" + i).val();
			//alert(valorIns);
			if (valorIns==1){
				$("#ImgIns" + i).attr("src", "imagenes/ins_cargando.gif");
				vDNI = $("#DNI" + i).val();
				vLegID = $("#Leg" + i).val();
				vLecID = $("#Lec" + i).val();
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "verificarInscripcionDefinitiva", LegID: vLegID, LecID: vLecID, DNI: vDNI},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//$("#mostrar").html(data);
						if (data==1){
							
							$.ajax({
								type: "POST",
								cache: false,
								async: false,
								error: function (XMLHttpRequest, textStatus){
									alert(textStatus);},
								data: {opcion: "grabarInscripcionDefinitiva", LegID: vLegID, LecID: vLecID},
								url: 'cargarOpciones.php',
								success: function(data){ 
									$("#ImgIns" + i).attr("src", "imagenes/ins_definitiva.png");
									$("#ImgIns" + i).attr("alt", "Inscripción Definitiva");
									$("#ImgIns" + i).attr("title", "Inscripción Definitiva");
								}
							});//fin ajax
						}else{
							$("#ImgIns" + i).attr("src", "imagenes/ins_provisoria.png");
							$("#ImgIns" + i).attr("alt", "Inscripción Provisoria");
							$("#ImgIns" + i).attr("title", "Inscripción Provisoria");
						}
						
					}
				});//fin ajax
			}else{
				//por ahora no hace nada
			}
		});//fin each menu								  
		
	});
*/

	$("#barraAsignar").click(function(evento){
		evento.preventDefault();
		$("#mostrarErrores").empty();
		iTotal = 1;
		valorIns =0;
		$("input[id^='DNI']").each(function(index){
			i = this.id.substr(3,10);
			valorIns = $("#Inscrip"+i).val();
			valorIns = $("#DNI"+i).val();
			//alert(i);
			//alert(valorIns);
			if (valorIns>0 && i>0){
				//$("#ImgIns" + i).attr("src", "imagenes/ins_cargando.gif");
				vDNI = $("#DNI" + i).val();
				vLecID = $("#Lec" + i).val();
				vPerID = $("#PerID" + i).val();
				//alert(VCurID);
				vNivID = $("#Niv" + i).val();
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "actualizarImporteCuotasAlumno", NivID: vNivID, LecID: vLecID, DNI: vDNI, PerID: vPerID},
					url: 'cargarOpciones.php',
					success: function(data){ 						
						if (data){
							$("#mostrarErrores").append(data);							
						}						
						
					}
				});//fin ajax//*/
			}else{
				//alert ("nada");//no hace nada
			}
			//habilitar cuando se quiera probar solo con algunos alumnos
			/*iTotal = iTotal + 1;
			return (iTotal == 2);//*/
		});//fin each menu								  
		
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
			$("#mostrar").empty();
		}
	}
	$("#msj_para_ie").result(colocarValor);	
	$(".botones").button();
	$("#barraVolver").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI_Volver").val();
		//alert(vDNI.length);
		if (vDNI.length>0){
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
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">

	<p>&nbsp;</p>
	<table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="imagenes/table.png" width="32" height="32" align="absmiddle" /> Procesar las cuotas de los alumnos</div></td>
      </tr>
	   <tr>
         <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td><?php 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
			cargarListaLectivoInscripcionNueva("LecID", $UniID);
			//cargarListaLectivo("LecID");
			?> 
           
             </tr>      
  <tr>
         <td class="texto"><div align="right">Curso:</div></td>
         <td><?php cargarListaCursos("CurID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td><?php cargarListaDivision("DivID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td><?php cargarListaNivel("NivID", true);?>            
             </tr>
      	  <tr>
      	    <td colspan="2" align="center" class="titulo_noticia">Seleccionar s&oacute;lo a un alumno</td>
      </tr>
      	  <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" readonly ="readonly" /> *       
          <input name="PerID" type="hidden" id="PerID" value="0" /></td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="35" />
          <input type="submit" name="botonLimpiar" id="botonLimpiar" value="Limpiar" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraMostrar">
        Mostrar Alumnos</button> 
          <!--<button class="botones" id="barraVerificar">
        Comenzar Verificaci&oacute;n</button>-->
          <button class="botones" id="barraAsignar">
        Actualizar Importe Cuotas</button>
        
        
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div>
        <div id="mostrarErrores"></div>
        </td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	