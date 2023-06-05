<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("cargarFuncionesDivision.php");	

$UsuID = $_SESSION['sesion_UsuID'];
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script src="js/jquery.printarea.js" language="javascript"></script>

<script language="javascript">
$(document).ready(function(){

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
	<?php if (isset($_SESSION['sesion_ultimoDNI'])){
		if (is_numeric($_SESSION['sesion_ultimoDNI'])){
			$DNI = $_SESSION['sesion_ultimoDNI'];
			//echo "alert('$DNI');";
			echo "$('#DNI').val($DNI);";
			echo "cargarDNI();";
		}

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

		$("#btnHabilitar").show();
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

		$("#btnHabilitar").show();
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
					$("#Persona").html(data.trim());
					$("#Persona").fadeIn();
					$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
						$("#PerID").val(data.trim());
						vPerID = $("#PerID").val();
						//cargarFamiliaresSeguroAlumno(vPerID);
						
						//alert(vPerID);
						
						if (vPerID>0){
							vTipoLegajo = $("#TipoLegajo").val();
							//alert("");
							$.post("cargarOpciones.php",{opcion: 'buscarLegajoColegio', DNI: vDNI}, function(data){
								$("#LegID").val(data.trim());
								//alert(data);
								vLegID = data.trim();
								vLecID = $("#LecID").val();
							});
							$.post("cargarOpcionesDivision.php", { opcion: 'llenarListaCursosColegioTraerNivel', }, function(data){
								$("#CurID").html(data.trim());									
							});

							/*
							$.post("cargarOpciones.php",{opcion: 'buscarLegajoColegioInstituto', DNI: vDNI}, function(data){								
								vLegajoColegioInstituto = data;
								//alert(data);
								/*
								$.post("cargarOpciones.php", { opcion: 'cargarListaCursosInstituto', Leg_Colegio: vLegajoColegioInstituto },		function(data){
									$("#CurID").html(data);									
								});
								
								
							});
							*/
							bloquear("");
							if (vLegID==0){
								$("#noTieneLegajo").text("No tiene legajo");
								bloquear("disabled");
							}
							//cargarBeneficios(vPerID, vLecID);
							vNivColegio = 1;//Sólo buscará inscripciones al Colegio
							$.post("cargarOpciones.php",{opcion: 'buscarDatosInscripcionLectivo', LegID: vLegID, LecID: vLecID, NivColegio: vNivColegio}, function(data){
								//alert(data);
								//return;
								if (data.trim()!="{}"){
									var obj = $.parseJSON(data);
									//alert(obj.total_legajos);
									if (obj.Ins_Leg_ID>0){
										 //alert(obj.Ase_Tutor_Per_ID);
										 //$("#VinID").val(obj.Ase_Tutor_Per_ID);
										 buscarOtrosDatos(vPerID);
										 $("#noTieneLegajo").text("Fecha Inscripcion: " + obj.Ins_Fecha);
										 $("#borrarInscripcion").show();
										 //$("#Contrato").val(obj.Ase_Contrato);
									}
									$("#LecID").val(obj.Ins_Lec_ID);
									$("#CurID").val(obj.Ins_Cur_ID);
									$("#DivID").val(obj.Ins_Div_ID);
									$("#NivID").val(obj.Ins_Niv_ID);
									vLecID = $("#LecID").val();
									vCurID = $("#CurID").val();
									cargarListaDivisionCurso(vCurID,vLecID);
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
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "obtenerDeuda", PerID: vPerID},
					url: 'cargarOpciones.php',
					success: function(data){ 
						
						if (data.trim()>0){
							jAlert("El alumno tiene una deuda. Comuniquese con Administraci&oacute;n", "Atenci&oacute;n");							
							/*jConfirm("El alumno tiene una deuda de $"+ data + ". &iquest;Desea habilitarlo para la inscripci&oacute;n?", 'Confirmar la habilitacion de la inscripcion', function(r){
								if (r){//eligió habilitar
									bloquear("");
									}
								else {
									bloquear("disabled");													
								}//fin if
							});//fin del confirm//*/
							
							//bloquear("disabled");23/01/2019 comentado para poder inscribir fuera de termino
							$("#noTieneLegajo").text("El alumno tiene una deuda. Comuniquese con Administracion");
							$("#cargando").hide();
							return;
						}
						$("#cargando").hide();
					}
				});//fin ajax//*/
	}//fin funcion
	
	function limpiarDatos(){
		$("#noTieneLegajo").empty();
		$("#borrarInscripcion").hide();
		$("#PerID").val("");
		$("#Persona").empty();		
		$("#documento").val("");
		$("#fechaNac").val("");
		$("#domicilio").val("");
		$("#telefono").val("");
		$("#CurID").val(-1);
		$("#DivID").val(-1);
		$("#NivID").val(-1);
		$("#VinID").empty();
		$("#Contrato").val("");
		$("#mostrarVacantes").html("");

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
	
	function cargarBeneficios(vPerID, vLecID){
		//Cargamos los beneficios que pueda tener el alumno
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarBeneficiosAlumno", PerID: vPerID, LecID: vLecID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);
				$("#BenID").html(data);
				$("#cargando").hide();
			}
		});//fin ajax//*/
	
	}
	 
	 $("#NivID").attr("disabled","disabled");
	 //alert("entre");
	
	 function bloquear(valor){
	 	
		if (valor==""){
			$("#CurID").removeAttr("disabled");
			$("#DivID").removeAttr("disabled");
			//$("#NivID").removeAttr("disabled");
			$("#LecID").removeAttr("disabled");
			$("#VinID").removeAttr("disabled");

			$("#barraGuardar").removeAttr("disabled");
		}else{
			$("#CurID").attr("disabled",valor);
			$("#DivID").attr("disabled",valor);
			$("#NivID").attr("disabled",valor);
			$("#LecID").attr("disabled",valor);
			$("#VinID").attr("disabled",valor);

			$("#barraGuardar").attr("disabled",valor);
		}
		
		
	 }
	 
	 function datosEncontrados(data){		 
   	 	jAlert(data, "Resultado de guardar los cambios");
		cargarCuentaUsuario();
		$("#cargando").fadeOut();
	 };
	
	function buscarOtrosDatos(vPerID){
		$.post("cargarOpciones.php",{opcion: 'buscarOtrosDatos', PerID: vPerID}, function(data){
			//alert(data);
			//return;
			if (data.trim()!="{}"){
				var obj = $.parseJSON(data.trim());
				//alert(obj.total_legajos);
				$("#documento").val(obj.Doc_Nombre + ": " + obj.Per_DNI);
				$("#fechaNac").val(obj.Dat_Nacimiento);
				$("#domicilio").val(obj.Dat_Domicilio);
				$("#telefono").val(obj.Dat_Celular);
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
		vPerID = $("#PerID").val();
		buscarDatosFamiliar(vPerID);

	});//*/
	
	$("#btnBorrarInscripcion").click(function(evento){
		evento.preventDefault();
		$("#cargando").show();
		vDNI = $("#DNI").val();
		vLegID = $("#LegID").val();
		vLecID = $("#LecID").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpcionesInscripcion.php',
			data: {opcion: "borrarInscripcionLectivo", LegID: vLegID, LecID: vLecID},
			success: function (data){
					jAlert(data, "Resultado:");
					buscarDNI(vDNI);
					//revisarContadorInscripcion();
					//mostrarConstanciaInscripcion();
					$("#cargando").hide();
			}
		});//fin ajax//*/
	});
	
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		
		vLegID = $("#LegID").val();
		vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vNivID = $("#NivID").val();
		vDivID = $("#DivID").val();
		vPerID = $("#PerID").val();
		//alert(vArTID);
		vDNI = $("#DNI").val();
		//vVinID = $("#VinID option:selected").val();		
		//vContrato = $("#Contrato").val();
		if (vLegID==0){
			jAlert("Debe seleccionar un alumno que tenga cargado el número de legajo", "ERROR");
			return;
		}
		if (vPerID==0){
			jAlert("NO se puede identificar a la Persona!", "ERROR");
			return;
		}
		if (vLecID==-1){
			jAlert("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return;
		}
		if (vCurID==-1){
			jAlert("Debe seleccionar un Curso", "ERROR");
			$("#CurID").focus();
			return;
		}
		if (vNivID==-1){
			jAlert("Debe seleccionar un Nivel", "ERROR");
			$("#NivID").focus();
			return;
		}
		if (vDivID==-1){
			jAlert("Debe seleccionar una División", "ERROR");
			$("#DivID").focus();
			return;
		}
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpcionesInscripcion.php',
			data: {opcion: "guardarInscripcionLectivo", LegID: vLegID, LecID: vLecID, CurID: vCurID, NivID: vNivID, DivID: vDivID, PerID: vPerID, DNI: vDNI},
			success: function (data){
					jAlert(data, "Resultado:");
					buscarDNI(vDNI);
					//revisarContadorInscripcion();
					//mostrarConstanciaInscripcion();
					$("#cargando").hide();
					}
		});//fin ajax//*/
	});
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		$("#DNI").val("");
		$("#personaNombre").val("");

		limpiarDatos();
		bloquear("");
	});	
	
	$("#barraImprimir").click(function(evento){
		evento.preventDefault();
		mostrarConstanciaInscripcion();
	});	
	
	function mostrarConstanciaInscripcion(){
		$("#datosInscripcion").hide();
		vLegID = $("#LegID").val();
		vLecID = $("#LecID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "mostrarConstanciaInscripcion", LegID: vLegID, LecID: vLecID},
				success: function (data){
						$("#datosInscripcion").html(data);
						$("#datosInscripcion").show();
						$("#datosInscripcion").printElement({leaveOpen:true, printMode:'popup'});
						/*options = {mode: "popup", popTitle: "Imprimir Constancia de Inscripción", popClose: false};
						$("div#datosInscripcion").printArea(options);//*/
						$("#datosInscripcion").hide();
						//jAlert(data);
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
						if (data.trim()){
							jAlert(data, "¡ ¡ ¡ F E L I C I T A C I O N E S ! ! !");
							
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
				data: {DNI: vDNI, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivo"},
				success: function (data){
						$("#principal").html(data);
						//jAlert(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});	
	
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
	
	$("#barraFamilias").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarFamilia.php',
				data: {DNI: vDNI, DNI_Volver: vDNI, pag_Volver: "cargarInscripcionLectivo"},
				success: function (data){
						$("#principal").html(data);
						//jAlert(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});	
	//alert("entre");

	$("#btnHabilitar").click(function(evento){
		evento.preventDefault();
		//alert("entré");
		LegID = $("#LegID").val();
				
		if (LegID==0){
			jAlert("Debe seleccionar un alumno que tenga cargado el n&uacute;mero de legajo", "ERROR");
			return;
		}
		$("#NivID").removeAttr("disabled");
		$("#CurID").removeAttr("disabled");
		$("#DivID").removeAttr("disabled");

		$("#btnHabilitar").hide();
	});	
	
 	
	function controlarNivel(vID){
		$.post("cargarOpciones.php", { opcion: 'controlarNivel', ID: vID }, function(data){
				//alert(data);
     			$("#NivID").html(data);
   		});
	}//fin funcion
	
	
	$("#CurID").change(function () {
   		$("#CurID option:selected").each(function () {
			//alert($(this).val());
			Cur_ID=$(this).val();
			controlarNivel(Cur_ID);
			Lec_ID=$("#LecID").val();
			if (Cur_ID>0){
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "revisarVacantes", Cur_ID: Cur_ID, Lec_ID: Lec_ID},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//alert(data);
						if (data.trim()==0){
							bloquear("disabled");
							mostrarDatos = "No hay Vacantes: " + data;
						}else{
							mostrarDatos = "Vacantes: " + data;
							
						}
						$("#mostrarVacantes").html(mostrarDatos);
						//$("#loading").hide();
					}
				});//fin ajax//*/
			}
				
        });
        cargarListaDivisionCurso(Cur_ID,Lec_ID);
   });//fin click 
   
   	$("#CurID").click(function () {
   		$("#CurID option:selected").each(function () {
			//alert($(this).val());
			Cur_ID=$(this).val();
			controlarNivel(Cur_ID);
			Lec_ID=$("#LecID").val();
			if (Cur_ID>0){
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "revisarVacantes", Cur_ID: Cur_ID, Lec_ID: Lec_ID},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//alert(data);
						if (data.trim()==0){
							bloquear("disabled");
							mostrarDatos = "No hay Vacantes: " + data;
						}else{
							mostrarDatos = "Vacantes: " + data;
							
						}
						$("#mostrarVacantes").html(mostrarDatos);
						//$("#loading").hide();
					}
				});//fin ajax//*/
			}	
        });
        cargarListaDivisionCurso(Cur_ID,Lec_ID);
   });//fin click 
   
   $("#LecID").change(function () {
   		$("#LecID option:selected").each(function () {
			//alert($(this).val());
				iDNI = $("#DNI").val();
				buscarDNI(iDNI);
        });
   });//fin click 
   
   	function cargarListaDivisionCurso(CurID,LecID){
		$.ajax({
            type: "POST",
            cache: false,
            async: false,			
            url: 'cargarOpcionesDivision.php',
            data: {opcion: "cargarListaDivisionCurso", CurID: CurID, LecID: LecID},
            success: function (data){
            	//alert(data);
                $("#DivID").html(data);
                $("#cargando").hide();
            }
        });//fin ajax
	}//fin function

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
    <td width="48"><button id="barraFamilias" class="barra_boton"><div align="center"><img src="imagenes/Users.png" alt="Ver Familia" width="48" height="48" border="0" /><br />
      Familias</div></button></td>

    <td width="48"><button id="barraRequisitos" class="barra_boton"><img src="imagenes/table_edit_req.png" alt="Requisitos de ingreso" width="48" height="48" border="0" /><br />
      Requisitos</button></td>

       <?php
	   if ($_SESSION['sesion_UsuCaja']==1){
	   ?> 
    <td width="48"><button id="barraDeuda" class="barra_boton"><img src="imagenes/table_cuotas.png" alt="Ver Deuda" width="48" height="48" border="0" /><br />
      Cuotas</button></td>
      <?php
	   }//fin if
	   ?>
      <?php
      //if ($_SESSION['sesion_UsuID']==2){
		?>
        <!--<td width="48"><button id="barraImprimir" class="barra_boton">
        <div align="center"><img src="imagenes/printer.png" alt="Ver Deuda" width="32" height="32" border="0" align="middle" /><br />
      Constancia</div></button></td>-->
        <?php  
		//}
	  ?>

  </tr>
</table>

	<div id="mostrarNuevo">

	  <table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="3"><div align="center"><span class="titulo_noticia"><img src="imagenes/report_edit.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia"> Inscripci&oacute;n al Ciclo Lectivo de Colegios</span></div></td>
        </tr>
       <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="number" class="texto_buscador required digits" max="99999999" min="10000000" id="DNI" <?php if (!empty($_POST['DNI'])) echo " value='".$_POST['DNI']."'";?>/>
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input name="LegID" type="hidden" id="LegID" />
          <input name="TipoLegajo" type="hidden" id="TipoLegajo" value="<?php echo $_SESSION['sesion_Unidad'];?>" />
          <input name="UsuID" type="hidden" id="UsuID" value="<?php echo $_SESSION['sesion_UsuID'];?>" />
               
        <td><div class="ui-state-error ui-corner-all" id="noTieneLegajo" style="font-size:14px"></div>
        	<div class="" id="borrarInscripcion" style="font-size:14px"><button id="btnBorrarInscripcion">Eliminar inscripcion</button></div>
       </tr>
       <tr>
         <td align="right" class="texto"><strong>Buscar Persona:</strong></td>
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
			cargarListaLectivoInscripcion("LecID", $UniID);
			//cargarListaLectivo("LecID");
			
			?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Curso:</div></td>
         <td colspan="2"><?php cargarListaCursosColegioTraerNivel("CurID");?> 
           	<div id="mostrarVacantes" style=" font-size:18px; color:#F00"></div></tr>
       <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td colspan="2"><?php cargarListaDivision("DivID");?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td colspan="2"><?php cargarListaNivel("NivID");?> 
           
             </tr>		
       <tr>       
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Otros Datos obligatorios</div></td>
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
      </table>
	  <p>&nbsp;</p>
		
</div>

<div id="mostrar"></div>
<div id="datosInscripcion"></div>

