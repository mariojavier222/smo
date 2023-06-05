<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script src="js/jquery.printarea.js" language="javascript"></script>
<script language="javascript">
$(document).ready(function(){

	
	// definimos las opciones del plugin AJAX FORM
	opciones= {
					   beforeSubmit: validarForm, //funcion que se ejecuta antes de enviar el form
					   success: datosEncontrados //funcion que se ejecuta una vez enviado el formulario
	};
	 //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
	
	$("#fechaAlta").datepicker($.datepicker.regional['es']);
	$("#fechaBaja").datepicker($.datepicker.regional['es']);
	
	$(".botones").button();
	//vDNI = 0;
	limpiarDatos();
	bloquear("disabled");
	
	$("#Persona").hide();
	$("#DNI").focus();
	$("#avisoBeneficio").empty();
	<?php if (isset($_POST['DNI'])){
		$DNI = $_POST['DNI'];
		echo "$('#DNI').val($DNI);";
		echo "cargarDNI();";

	}
	?>
	
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
				$("#cargando").hide();
			}
		});//fin ajax//*/
	
	}
	$("#DNI").keyup(function(evento){
		evento.preventDefault();
		iDNI = $("#DNI").val();
		if (evento.keyCode == 13 && iDNI!=""){
			limpiarDatos();
			$("#cargando").show();
			cargarDNI();
		
			
		}//fin if
	});//fin de prsionar enter			
	function buscarDNI(DNI){
		
		$("#PerID").attr("value",0);
		vDetener = false;
		//alert(DNI);
		//return;
		$.ajaxSetup({
  			cache: false,
			async: false
		});
		limpiarDatos();		
		$("#noTieneLegajo").text("No se encuentra inscripto");
		if (validarNumero(DNI)){
				vDNI = DNI;				
				$.post("cargarOpciones.php",{opcion: 'buscarDNI', DNI: vDNI}, function(data){
					$("#Persona").html(data);
					$("#Persona").fadeIn();
					$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
						$("#PerID").val(data);
						vPerID = data;
						cargarFamiliaresSeguroAlumno(vPerID);
						//alert(vPerID);
						
						if (vPerID>0){
							vTipoLegajo = $("#TipoLegajo").val();
							//alert("");
							$.ajax({
									type: "POST",
									cache: false,
									async: false,
									error: function (XMLHttpRequest, textStatus){
										alert(textStatus);},
									data: {opcion: "revisarRequisitosPersona", PerID: vPerID, NivID: 4},
									url: 'cargarOpciones.php',
									success: function(data){ 
										if (data){
											$("#noTieneLegajo").text("FALTA DOCUMENTACIÓN");
											mostrarAlerta("La persona debe los siguientes requisitos que son obligatorios para continuar la inscripción<br />" + data, "FALTA DOCUMENTACIÓN");
											bloquear("disabled");
											vDetener = true;
										}
									}//fin success

							});
							if (vDetener) return;
							vLecID = $("#LecID").val();
							bloquear("");
							//alert("");
							//cargarBeneficios(vDNI, vLecID);
							
							$.ajax({
								type: "POST",
								cache: false,
								async: false,
								error: function (XMLHttpRequest, textStatus){
									alert(textStatus);},
								data: {opcion: "buscarDatosInscripcionLectivoCursillo", PerID: vPerID, LecID: vLecID},
								url: 'cargarOpciones.php',
								success: function(data){ 
								//alert(data);
								//return;
								  if (data!="{}"){
									  var obj = $.parseJSON(data);
									  //alert(obj.total_legajos);
									  if (obj.CantTitulos>0){
										   //alert(obj.Ase_Tutor_Per_ID);
										   $("#noTieneLegajo").text("Ya se encuentra inscripto");								
										   $("#LecID").val(obj.Ins_Lec_ID);
										   $("#TurID").val(obj.Ins_Tur_ID);
										   $("#TitID").val(obj.Ins_Tit_ID);
										   if (obj.CantTitulos>1){
											   mostrarAlerta("Esta persona se encuentra inscripto en más de una carrera", "ATENCION");
											   $("#noTieneLegajo").text("ESTÁ INSCRIPTO EN MÁS DE UNA CARRERA");
										   }
  
									  }
									  
								  }else {
									  //limpiarDatos();
									  }
								}//fin success
							});//fin ajax
						}else{
							limpiarDatos();
						}//fin if//*/

					});

				});				
			
		}//fin if
		//alert(vDetener);
		if (vDetener) return;
		return;
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "obtenerDeuda", DNI: vDNI},
					url: 'cargarOpciones.php',
					success: function(data){ 
						
						if (data>0){
							bloquear("disabled");
							$("#noTieneLegajo").text("El alumno tiene una deuda de $"+ data);
							return;
						}
						$("#cargando").hide();
					}
				});//fin ajax//*/
	}//fin funcion
	function limpiarDatos(){
		//$("#DNI").val("");
		$("#noTieneLegajo").empty();
		$("#PerID").val("");
		$("#Persona").empty();		
		$("#documento").val("");
		$("#fechaNac").val("");
		$("#domicilio").val("");
		$("#telefono").val("");
		$("#TurID").val(-1);
		$("#TitID").val(-1);
		$("#NivID").val(-1);
		$("#VinID").empty();
		$("#Contrato").val("");


	}//fin funcion
	
	function cargarFamiliaresSeguroAlumno(vPerID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarFamiliaresSeguroAlumno", PerID: vPerID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#VinID").html(data);
				$("#cargando").hide();
			}
		});//fin ajax//*/
	}
	function cargarBeneficios(vDNI, vLecID){
		//Cargamos los beneficios que pueda tener el alumno
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarBeneficiosAlumno", DNI: vDNI, LecID: vLecID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#ArTID").html(data);
				$("#cargando").hide();
			}
		});//fin ajax//*/
		//Controlamos si tiene un beneficio de hermano y los mismos no estén cargados en el sistema
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "verificarBeneficiosAlumno", DNI: vDNI, LecID: vLecID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				if (data=="")
					$("#avisoBeneficio").empty();
				else
					$("#avisoBeneficio").html(data);
				$("#cargando").hide();
			}
		});//fin ajax//*/

	}
	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0]; 
    	  var error="";
			//if (!form.DNI.value) { 
				//alert('Por favor ingrese un DNI para buscar otros datos');         		
				$("#cargando").fadeOut();
				//return false; 
    		//} 
    		//alert('Todo bien.'); 		  
	 };
	 
	
	 function bloquear(valor){
	 	
		$("#TurID").attr("disabled",valor);
		$("#TitID").attr("disabled",valor);
		$("#NivID").attr("disabled",valor);
		$("#LecID").attr("disabled",valor);
		$("#VinID").attr("disabled",valor);
		$("#ModificarDatos").attr("disabled",valor);
		$("#barraGuardar").attr("disabled",valor);
		
	 }
	 
	 function datosEncontrados(data){		 
   	 	mostrarAlerta(data, "Resultado de guardar los cambios");
		cargarCuentaUsuario();
		$("#cargando").fadeOut();
	 };
	
	function buscarDatosFamiliar(vPerID){
		$.post("cargarOpciones.php",{opcion: 'buscarOtrosDatos', PerID: vPerID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#documento").val(obj.Doc_Nombre + ": " + obj.Per_DNI);
				$("#fechaNac").val(obj.Dat_Nacimiento);
				$("#domicilio").val(obj.Dat_Domicilio);
				$("#telefono").val(obj.Dat_Telefono);
			}else {
				$("#documento").val("FALTA CARGAR");
				$("#fechaNac").val("FALTA CARGAR");
				$("#domicilio").val("FALTA CARGAR");
				$("#telefono").val("FALTA CARGAR");
				}
		});		
	}//fin function
	$("#VinID").click(function(evento){
		evento.preventDefault();
		vPerID = $("#VinID option:selected").val();
		buscarDatosFamiliar(vPerID);

	});//*/
	 $("#barraGuardar").click(function(evento){
		evento.preventDefault();
		
		vPerID = $("#PerID").val();
		vLecID = $("#LecID").val();
		vTurID = $("#TurID").val();
		vTitID = $("#TitID").val();
		//alert(vArTID);
		vDNI = $("#DNI").val();
		if (vPerID==0){
			mostrarAlerta("Debe seleccionar una persona antes de guardar los cambios.", "ERROR");
			return;
		}
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return;
		}
		if (vTurID==-1){
			mostrarAlerta("Debe seleccionar un Turno", "ERROR");
			$("#TurID").focus();
			return;
		}
		if (vTitID==-1){
			mostrarAlerta("Debe seleccionar un Título de Carrera", "ERROR");
			$("#TitID").focus();
			return;
		}

		
		vDocumento = $("#documento").val();
		//alert(vDocumento);
		
		if ($.browser.msie) {
			mostrarAlerta("La opción de Guardar solo esta disponible para otros navegadores excepto el Internet Explorer", "ATENCION");
			return;
		}
		//*/
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpciones.php',
			data: {opcion: "guardarInscripcionLectivoCursillo", PerID: vPerID, LecID: vLecID, TurID: vTurID, TitID: vTitID,  DNI: vDNI},
			success: function (data){
					mostrarAlerta(data, "Resultado de la inscripción");
					buscarDNI(vDNI);
					//revisarContadorInscripcion();
					mostrarConstanciaInscripcion();
					$("#cargando").hide();
					}  
		});//fin ajax//*/
	});
	$("#ModificarDatos").click(function(evento){
		evento.preventDefault();
		vPerID = $("#VinID option:selected").val();
		vDNI = $("#DNI").val();
		//alert(vDNI.length);
		if (vPerID==null){
			mostrarAlerta("Antes de Modificar los datos debe seleccionar un familiar","Atención");
		}else{
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOtrosDatos.php',
				data: {PerID: vPerID, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivoCursillo"},
				success: function (data){
						$("#principal").html(data);
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
		}//fin if
	}); 
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		limpiarDatos();
		bloquear("");
	});	
	$("#barraImprimir").click(function(evento){
		evento.preventDefault();
		mostrarConstanciaInscripcion();
	});	
	function mostrarConstanciaInscripcion(){
		$("#datosInscripcion").hide();
		vPerID = $("#PerID").val();
		vLecID = $("#LecID").val();
		vTitID = $("#TitID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "mostrarConstanciaInscripcionCursillo", PerID: vPerID, LecID: vLecID, TitID: vTitID},
				success: function (data){
						$("#datosInscripcion").html(data);
						options = {mode: "popup", popTitle: "Imprimir Constancia de Inscripción", popClose: false};
						$("div#datosInscripcion").printArea(options);
						$("#datosInscripcion").hide();
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
	
	}
	function revisarContadorInscripcion(){
		vLecID = $("#LecID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "revisarContadorInscripcion", LecID: vLecID},
				success: function (data){
						//alert(data);
						if (data){
							mostrarAlerta(data, "¡ ¡ ¡ F E L I C I T A C I O N E S ! ! !");
							
							$("#cargando").hide();
						}
					}
			});//fin ajax
	
	}
	$("#barraDeuda").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarCuotasImpagas.php',
				data: {DNI: vDNI, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivoCursillo"},
				success: function (data){
						$("#principal").html(data);
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});	
	$("#barraFamilias").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarFamilia.php',
				data: {DNI: vDNI, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivoCursillo"},
				success: function (data){
						$("#principal").html(data);
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});	
	$("#barraRequisitos").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		vPerID = $("#PerID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarRequisitosPersona.php',
				data: {DNI: vDNI, DNI_Volver: vDNI, NivID: 4, PerID: vPerID, pag_Volver: "cargarInscripcionLectivoCursillo"},
				success: function (data){
						$("#principal").html(data);
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
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
    <td width="48"><button id="barraNuevo" class="barra_boton"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
      Nuevo </button></td>
    <td width="48"><button id="barraGuardar" class="barra_boton"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      Guardar</button></td>
    <td width="48"><button id="barraDeuda" class="barra_boton"><img src="imagenes/table_cuotas.png" alt="Ver Deuda" width="48" height="48" border="0" /><br />
      Cuotas</button></td>
    <td width="48"><button id="barraFamilias" class="barra_boton"><div align="center"><img src="imagenes/Users.png" alt="Ver Deuda" width="48" height="48" border="0" /><br />
      Familias</div></button></td>
    <td width="48"><button id="barraRequisitos" class="barra_boton">
    <div align="center"><img src="imagenes/table_edit_req.png" alt="Ver Deuda" width="48" height="48" border="0" /><br />
      Requisitos</div></button></td>
      
      <?php
      //if ($_SESSION['sesion_UsuID']==2){
		?>
        <td width="48"><button id="barraImprimir" class="barra_boton">
        <div align="center"><img src="imagenes/printer.png" alt="Ver Deuda" width="32" height="32" border="0" align="middle" /><br />
      Constancia</div></button></td>
        <?php  
		//}
	  ?>

  </tr>
</table>

	<div id="mostrarNuevo">

	  <table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="3"><div align="center"><span class="titulo_noticia"><img src="imagenes/report_edit.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia"> Inscripci&oacute;n al  Cursillo de Ingreso</span></div></td>
        </tr>
       <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" <?php if (!empty($_POST['DNI'])) echo " value='".$_POST['DNI']."'";?>/>
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input name="LegID" type="hidden" id="LegID" />
          <input name="TipoLegajo" type="hidden" id="TipoLegajo" value="<?php echo $_SESSION['sesion_Unidad'];?>" />
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
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos de la Inscripci&oacute;n</div></td>
        </tr>
       <tr>
         <td class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td colspan="2"><?php 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
			cargarListaLectivoInscripcion("LecID", $UniID);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Turno:</div></td>
         <td colspan="2"><?php cargarListaTurnos("TurID");?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">T&iacute;tulo de la Carrera:</div></td>
         <td colspan="2"><?php cargarListaTituloCarrera("TitID");?> 
           
             </tr>
       <tr>
         <td align="right" class="texto">&nbsp;</td>
         <td colspan="2">        
         </tr>
      </table>
	  <p>&nbsp;</p>
		
</div>

<div id="mostrar"></div>
<div id="datosInscripcion"></div>