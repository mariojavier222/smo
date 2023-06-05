<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>


<link href="css/general.css" rel="stylesheet" type="text/css" />

<!--<?php 
/*if ($_SESSION['sesion_rol']>10){
	?>
	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible en este momento para Usted.</span> La misma est&aacute; siendo revisada para brindarle un mejor servicio. Si este mensaje lo vuelve a ver muy seguido llame al Departamento de Sistemas (int. 315).</div>
</p><p></p>/
<?php
	//exit;
}//*/
?>-->


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
		bloquear("");		
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
											$("#noTieneLegajo").text("FALTA DOCUMENTACION");
											mostrarAlerta("La persona debe los siguientes requisitos que son obligatorios para continuar la inscripción<br />" + data, "FALTA DOCUMENTACION");
											bloquear("disabled");
											vDetener = true;
										}
									}//fin success

							});
							if (vDetener) {
								//alert("FALTA DOCUMENTACION");
								return;
								
							}
							$.post("cargarOpciones.php",{opcion: 'buscarLegajoTerciario', DNI: vDNI}, function(data){
								$("#LegID").val(data);
								//alert(data);
								vLegID = data;
								vLecID = $("#LecID").val();
							});
							bloquear("");
							if (vLegID==0){
								$("#noTieneLegajo").text("No tiene legajo");
								bloquear("disabled");
							}
							//cargarBeneficios(vDNI, vLecID);
							//Buscamos datos de la carrera
							$.ajax({
									type: "POST",
									cache: false,
									async: false,
									error: function (XMLHttpRequest, textStatus){
										alert(textStatus);},
									data: {opcion: "buscarDatosTituloTerciario", LegID: vLegID},
									url: 'cargarOpciones.php',
									success: function(data){ 
										if (data!="{}"){
											var obj = $.parseJSON(data);
											if (obj.Cantidad>1){
												//El alumno se encuentra inscripto en más de un titulo
												alert("El alumno se encuentra inscripto en más de un título");
											}
											$("#datosCarrera").text("El alumno tiene una carrera activa");
											//alert(obj.TAl_Tit_ID);
											$("#TitID").val(obj.TAl_Tit_ID);
											$("#Cohorte").val(obj.TAl_Cohorte);
											
											$("#fechaTituloAlta").val(obj.TAl_Fecha + " " + obj.TAl_Hora);
											buscarDatosTitulo();
											
										}else {
											$("#datosCarrera").text("No tiene una carrera activa");
											$("#datosInscripcion").text("No tiene inscripciones activas");
											//bloquear("");
											vDetener = true;
											//limpiarDatos();
											}
									}//fin success

							});
							
							//Buscamos datos de la inscripción al ciclo lectivo y datos del asegurado
							if (vDetener) return;
							vCarID = $("#CarID").val();
							vPlaID = $("#PlaID").val();
							//alert(vCarID + vPlaID);
							//alert("Abortando busqueda");							
							$.ajax({
									type: "POST",
									cache: false,
									async: false,
									error: function (XMLHttpRequest, textStatus){
										alert(textStatus);},
									data: {opcion: "buscarDatosInscripcionLectivoTerciario", LegID: vLegID, LecID: vLecID, CarID: vCarID, PlaID: vPlaID},
									url: 'cargarOpciones.php',
									success: function(data){ 
										if (data!="{}"){
											var obj = $.parseJSON(data);
											//alert(obj.total_legajos);
											if (obj.TieneAsegurado>0){
												 //alert(obj.Ase_Tutor_Per_ID);
												 if (obj.SinCursar==1)
												 	$("#SinCursar").attr("checked", "checked");
												 else
												 	$("#SinCursar").attr("checked", "");
												 $("#VinID").val(obj.Ase_Tutor_Per_ID);
												 buscarDatosFamiliar(obj.Ase_Tutor_Per_ID);
												 $("#noTieneLegajo").text("Contrato N°" + obj.Ase_Contrato);
												 $("#Contrato").val(obj.Ase_Contrato);
												 bloquear("");
											}
											/*$("#LecID").val(obj.Ins_Lec_ID);
											$("#CurID").val(obj.Ins_Cur_ID);
											$("#DivID").val(obj.Ins_Div_ID);
											$("#NivID").val(obj.Ins_Niv_ID);//*/
											
										}else {
											$("#datosInscripcion").text("No se encuentra inscripto al Ciclo Lectivo elegido");
											//limpiarDatos();
											}
									}//fin success

							});
							
						}else{
							limpiarDatos();
						}//fin if//*/

					});

				});				
			
		}//fin if
		
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
							//alert("");
							$("#noTieneLegajo").text("El alumno tiene una deuda de $"+ data);
							return;
						}
						$("#cargando").hide();
					}
				});//fin ajax//*/
		
		//bloquear("");
		//alert("FINAl");
	}//fin funcion
	function limpiarDatos(){
		//$("#DNI").val("");
		$("#noTieneLegajo").empty();
		$("#PerID").val("");
		$("#Persona").empty();		
		$("#documento").val("");
		$("#fechaNac").val("");
		$("#fechaTituloAlta").val("");
		$("#domicilio").val("");
		$("#telefono").val("");
		$("#CurID").val(-1);
		$("#DivID").val(1);
		$("#VinID").empty();
		$("#Contrato").val("");
		$("#datosCarrera").text("");
		$("#datosInscripcion").text("");


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
	 	
		$("#CurID").attr("disabled",valor);
		$("#DivID").attr("disabled",valor);
		$("#TitID").attr("disabled",valor);
		$("#Cohorte").attr("disabled",valor);
		$("#LecID").attr("disabled",valor);
		$("#VinID").attr("disabled",valor);
		$("#SinCursar").attr("disabled",valor);
		$("#ModificarDatos").attr("disabled",valor);
		
		if (valor==""){
			$("#spanGuardar").text("Guardar");
			$("#barraGuardar").removeAttr("disabled");						
		}else{
			$("#spanGuardar").text("Desactivado");
			$("#barraGuardar").attr("disabled",valor);
			
		}
		$("#datosInscripcionMateria").val("");
		
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
		//alert("");return;
		vLegID = $("#LegID").val();
		vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vTitID = $("#TitID").val();		
		vCarID = $("#CarID").val();
		vPlaID = $("#PlaID").val();
		vCohorte = $("#Cohorte").val();
		
		
		//alert(vArTID);
		vDNI = $("#DNI").val();
		vVinID = $("#VinID option:selected").val();		
		vContrato = $("#Contrato").val();
		if (vLegID==0){
			mostrarAlerta("Debe seleccionar un alumno que tenga cargado el número de legajo", "ERROR");
			return;
		}
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return;
		}
		if (vTitID==-1){
			mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
			$("#TitID").focus();
			return;
		}
		if (vVinID==null){
			mostrarAlerta("Debe seleccionar un familiar", "ERROR");
			return;
		}
		if (vVinID==-1){
			mostrarAlerta("Debe vincular un familiar antes de guardar la inscripción", "ERROR");
			return;
		}

		if (vContrato==""){
			mostrarAlerta("Debe escribir un contrato", "ERROR");
			$("#Contrato").focus();
			return;
		}
		if (!validarNumero(vContrato)){
			mostrarAlerta("Debe escribir un número válido para el contrato", "ERROR");
			$("#Contrato").focus();
			return;
		}
			
		
		//Validamos si el contrato ya fue cargado
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpciones.php',
			data: {opcion: "validarContratoGuardadoTerciario", LegID: vLegID, LecID: vLecID, Contrato: vContrato},
			success: function (data){
					if (data!=""){
						mostrarAlerta(data);
						$("#cargando").hide();
						return;
					}
					$("#cargando").hide();
					}
		});//fin ajax//*/
		
		vDocumento = $("#documento").val();
		//alert(vDocumento);
		
		if ($.browser.msie) {
			mostrarAlerta("La opción de Guardar solo esta disponible para otros navegadores excepto el Internet Explorer", "ATENCION");
			return;
		}
		if (vDocumento=="" || vDocumento=="FALTA CARGAR"){
			mostrarAlerta("El familiar seleccionado no tiene los datos personales cargados", "ERROR");
			return;
		}
		fechaNac = $("#fechaNac").val();
		if (fechaNac=="" || fechaNac=="FALTA CARGAR"){
			mostrarAlerta("El familiar seleccionado no tiene la fecha de nacimiento cargada", "ERROR");
			return;
		}
		domicilio = $("#domicilio").val();
		if (domicilio=="" || domicilio=="FALTA CARGAR"){
			mostrarAlerta("El familiar seleccionado no tiene el domicilio cargado", "ERROR");
			return;
		}
		telefono = $("#telefono").val();
		if (telefono=="" || telefono=="FALTA CARGAR"){
			mostrarAlerta("El familiar seleccionado no tiene el teléfono cargado", "ERROR");
			return;
		}
		//return;
		//*/
		//Revisamos si la opción de Sin cursar está elegida
		if ($('#SinCursar:checked').val() !== undefined) {
			vCurID = -1;
		}else{
			vCurID = buscarCurso();
		}
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpciones.php',
			data: {opcion: "guardarInscripcionLectivoTerciario", LegID: vLegID, LecID: vLecID, TitID: vTitID, CarID: vCarID, PlaID: vPlaID, VinID: vVinID, Contrato: vContrato, Cohorte: vCohorte, DNI: vDNI, CurID: vCurID},
			success: function (data){
					//Guardamos las materias de cursado
					vDivID = $("#DivID").val();
					vData = "";
					$("input:checked[id^='MatID']").each(function(index){
						vMatID = $(this).val();
						var i = this.id.substr(5,10);
						vPrdID = $("#PrdID" + i).val();
						vConID = $("#ConID" + i).val();
						$.ajax({
							type: "POST",
							cache: false,
							async: false,			
							url: 'cargarOpciones.php',
							data: {opcion: "guardarInscripcionMateriaTerciario", LegID: vLegID, LecID: vLecID, CarID: vCarID, PlaID: vPlaID, MatID: vMatID, ConID: vConID, PrdID: vPrdID, DivID: vDivID},
							success: function (data2){									
									//mostrarAlerta(data2);
									vData = data2;
									$("#cargando").hide();
									}
						});//fin ajax
					});
					mostrarAlerta(data, "Resultado de la inscripción");
					buscarDNI(vDNI);					
					mostrarConstanciaInscripcion();
					$("#cargando").hide();
					}
		});//fin ajax//*/
	});
	function buscarCurso(){
		vCurso = 0;
		$("input:checked[id^='MatID']").each(function(index){
			vMatID = $(this).val();
			var i = this.id.substr(5,10);
			if (vCurso < $("#CurID" + i).val())
				vCurso = $("#CurID" + i).val();
		});
		return vCurso;

	}//fin function
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
				data: {PerID: vPerID, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivoTerciario"},
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
		$("#datosInscripcionImprimir").hide();
		vPerID = $("#PerID").val();
		vLegID = $("#LegID").val();		
		vLecID = $("#LecID").val();	
		vCarID = $("#CarID").val();
		vPlaID = $("#PlaID").val();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpciones.php',
			data: {opcion: "mostrarConstanciaInscripcionTerciario", PerID: vPerID, LegID: vLegID, LecID: vLecID, CarID: vCarID, PlaID: vPlaID},
			success: function (data){
					//alert(data);
					$("#datosInscripcionImprimir").html(data);
					$("#datosInscripcionImprimir").show();
					$("#datosInscripcionImprimir").printElement({leaveOpen:true, printMode:'popup'});
					/*
					options = {mode: "popup", popTitle: "Imprimir Constancia de Inscripción", popClose: false};
					$("div#datosInscripcionImprimir").printArea(options);//*/
					$("#datosInscripcionImprimir").hide();
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
	function buscarDatosTitulo(){
		vTitID=$("#TitID").val();//alert("Tit: " + vTitID);
		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  error: function (XMLHttpRequest, textStatus){
			  alert(textStatus);},
		  data: {opcion: "buscarDatosCarreraPlan", TitID: vTitID},
		  url: 'cargarOpciones.php',
		  success: function(data){ 
			  if (data!="{}"){
				  var obj = $.parseJSON(data);
				  //alert(obj.total_legajos);				  
				  //alert(obj.Tit_Pla_ID + obj.Tit_Car_ID);
				  $("#PlaID").val(obj.Tit_Pla_ID);
				  $("#CarID").val(obj.Tit_Car_ID);
				  $.ajax({
					type: "POST",
					cache: false,
					async: false,			
					url: 'cargarOpciones.php',
					data: {opcion: "listarMateriaporTitulo", TitID: vTitID, CarID: obj.Tit_Car_ID, PlaID: obj.Tit_Pla_ID, LegID: $("#LegID").val(), LecID: $("#LecID").val()},
					success: function (data){
							//alert(data);
							if (data){								
								$("#datosInscripcionMateria").html(data);
								$("#cargando").hide();
							}
						}
				});//fin ajax
				  $("tr[id^='ocultar']").hide();
				  //****************************************************me quede aqui
				  $("#Carrera").val(obj.Car_Nombre);
				  $("#Plan").val(obj.Pla_Nombre);
				  
			  }else {
				  //limpiarDatos();
				  }
		  }//fin success

		  });//fin ajax
			
	}
	$("#TitID").change(function () {
   		$("#TitID option:selected").each(function () {
			//alert($(this).val());
			buscarDatosTitulo();
			buscarDNI(vDNI);
			
        });//fin each
   })
	$("#barraDeuda").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarCuotasImpagas.php',
				data: {DNI: vDNI, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivoTerciario"},
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
				data: {DNI: vDNI, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivoTerciario"},
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
				data: {DNI: vDNI, DNI_Volver: vDNI, NivID: 4, PerID: vPerID, pag_Volver: "cargarInscripcionLectivoTerciario"},
				success: function (data){
						$("#principal").html(data);
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});	
	$("#mostrarTodo").click(function(evento){
		evento.preventDefault();
		$("tr[id^='ocultar']").show();
	});	
	$("#ocultarTodo").click(function(evento){
		evento.preventDefault();
		$("tr[id^='ocultar']").hide();
	});	
	
	//$(".botones").button();
	$(".botonesMat button:first").button({
            icons: {
                primary: 'ui-icon-circle-plus'
            }
        }).next().button({
            icons: {
                primary: 'ui-icon-circle-minus'
            }
        });
	$("#SinCursar").click(function(evento){
		//evento.preventDefault();
		if ($('#SinCursar:checked').val() !== undefined) {
			$("#datosInscripcionMateria").hide();
		}else{
			$("#datosInscripcionMateria").show();
		}
	});	
});//fin de la funcion ready
</script>

<table border="0" align="center" cellspacing="4">
  <tr>
    <td width="48" valign="top"><button id="barraNuevo" class="barra_boton"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
      Nuevo </button></td>
    <td width="48" valign="top"><button id="barraGuardar" class="barra_boton"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      <span id="spanGuardar">Guardar</span></button></td>
    <td width="48" valign="top"><button id="barraDeuda" class="barra_boton"><img src="imagenes/table_cuotas.png" alt="Ver Deuda" width="48" height="48" border="0" /><br />
      Cuotas</button></td>
    <td width="48" valign="top"><button id="barraFamilias" class="barra_boton"><div align="center"><img src="imagenes/Users.png" alt="Ver Deuda" width="48" height="48" border="0" /><br />
      Familias</div></button></td>
          <td width="48" valign="top"><button id="barraRequisitos" class="barra_boton">
    <div align="center"><img src="imagenes/table_edit_req.png" alt="Ver Deuda" width="48" height="48" border="0" /><br />
      Requisitos</div></button></td>

<?php
      //if ($_SESSION['sesion_UsuID']==2){
		?>
        <td width="48" valign="top"><button id="barraImprimir" class="barra_boton">
        <div align="center"><img src="imagenes/printer.png" alt="Ver Deuda" width="32" height="32" border="0" align="middle" /><br />
      Constancia</div></button></td>
        <?php  
		//}
	  ?>
	<td></td>
  </tr>
</table>

	<div id="mostrarNuevo">

	  <table width="95%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="3"><div align="center"><span class="titulo_noticia"><img src="imagenes/report_edit.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia"> Inscripci&oacute;n al Ciclo Lectivo del Terciario</span></div></td>
        </tr>
       <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" <?php if (!empty($_POST['DNI'])) echo " value='".$_POST['DNI']."'";?>/>
        *       
          <input name="PerID" type="hidden" id="PerID" value="0" />
          <input name="LegID" type="hidden" id="LegID" value="0" />
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
         <td colspan="3" align="center" bgcolor="#FFCC00" class="titulo_noticia">Datos de la Carrera</td>
       </tr>
        <tr>
          <td colspan="3" align="center" class="borde_alerta texto" id="datosCarrera">       </td>
        </tr>
        <tr>
         <td class="texto"><div align="right">T&iacute;tulo de la Carrera:</div></td>
         <td colspan="2"><?php cargarListaTituloCarrera("TitID");?> 
           
             </tr>
        <tr>
          <td align="right" class="texto">Carrera:</td>
          <td colspan="2"><input name="Carrera" type="text" id="Carrera" size="60" disabled="disabled" />
            <input type="hidden" name="CarID" id="CarID" />
          </tr>
        <tr>
          <td align="right" class="texto">Plan: </td>
          <td colspan="2"><input name="Plan" type="text" id="Plan" size="6" disabled="disabled" />
            <input type="hidden" name="PlaID" id="PlaID" />
          </tr>
        <tr>
          <td align="right" class="texto">Cohorte:</td>
          <td colspan="2"><input name="Cohorte" type="text" id="Cohorte" size="6" maxlength="4" />        
        </tr>
        <tr>
          <td align="right" class="texto">Fecha:</td>
          <td colspan="2"><input name="fechaTituloAlta" type="text" id="fechaTituloAlta" size="20" disabled="disabled" />        
        </tr>             

       <tr>
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos de la Inscripci&oacute;n</div></td>
        </tr>
        <tr>
          <td colspan="3" align="center" class="borde_alerta texto" id="datosInscripcion">       </td>
        </tr>        
       <tr>
         <td class="texto"><div align="right">Ciclo Lectivo:</div></td>
         <td colspan="2"><?php 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
			cargarListaLectivoInscripcion("LecID", $UniID);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">División:</div></td>
         <td colspan="2"><?php cargarListaDivision("DivID");?> 
           
             </tr>
       <tr>
         <td align="right" class="texto">Sin cursar o rendir ex&aacute;manes</td>
         <td colspan="2"><label>
           <input type="checkbox" name="SinCursar" id="SinCursar" />
         </label>       
        </tr>
       <tr>
         <td class="texto">&nbsp;</td>
         <td colspan="2"><div class="botonesMat" align="center"><button id="mostrarTodo">Mostrar todas las materias</button><button id="ocultarTodo">Ocultar todas las materias</button></div>
         </a></tr>
       <tr>
         <td colspan="3" class="texto"><div id="datosInscripcionMateria"></div>         </td>
        </tr>
       <tr>       
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos del Padre, Madre o Tutor Asegurado</div></td>
        </tr>
       <tr>
         <td class="texto"><div align="right">Seleccionar familiar<br />
a asegurar:</div></td>
         <td colspan="2" valign="middle"><select name="VinID" size="5" id="VinID">
         </select>
           <br />  
           <button id="ModificarDatos" title="Modificar datos del asegurado" alt="Modificar datos del asegurado" class="botones">
         <img src="imagenes/group_go.png" width="32" height="32" align="absmiddle" />Modificar datos
</button>       
        </tr>		
       <tr>
         <td class="texto"><div align="right">Documento:</div></td>
         <td colspan="2"><input name="documento" type="text" disabled="disabled" id="documento" size="20" class="input_editar" />         </tr>
       <tr>
         <td align="right" class="texto">Fecha de nacimiento:</td>
         <td colspan="2"><input name="fechaNac" type="text" disabled="disabled" id="fechaNac" size="20" class="input_editar"/>
        </tr>
       <tr>
         <td align="right" class="texto">Domicilio real:</td>
         <td colspan="2"><input name="domicilio" type="text" disabled="disabled" id="domicilio" size="50" class="input_editar"/>
        </tr>
       <tr>
         <td align="right" class="texto">Tel&eacute;fono:</td>
         <td colspan="2"><input type="text" name="telefono" id="telefono" disabled="disabled" class="input_editar"/>
        </tr>
       <tr>
         <td align="right" class="texto">N&deg; Contrato: </td>
        <td colspan="2"><input name="Contrato" type="text" id="Contrato" size="10" />         </tr>
       <!--<tr>
         <td align="right" class="texto">Tipo de Beneficio:</td>
         <td colspan="2"><select name="ArTID" id="ArTID">
         </select> <div class="ui-state-error ui-corner-all" style="font-size:14px" id="avisoBeneficio"></div>        
         </tr>-->
      </table>
	  <p>&nbsp;</p>
		
</div>

<div id="mostrar"></div>
<div id="datosInscripcionImprimir"></div>

