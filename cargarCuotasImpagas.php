<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

session_name("sesion_abierta");
// incia sessiones
session_start();
 
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
	<?php if (isset($_SESSION['sesion_ultimoDNI'])){
		$DNI = $_SESSION['sesion_ultimoDNI'];
		echo "$('#DNI').val($DNI);";
		echo "cargarDNI();";

	}
	?>
	//vUsuID = 0;
	$("#PersonaDatos").hide();

	$("a[id^='cargarHermano']").click(function(evento){
        evento.preventDefault();		
        var i = this.id.substr(13,10);
        //alert(i);
        $("#DNI").val(i);
        //cargarDNI();
    });//fin evento click//*/
	
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
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarPerID", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#PerID").val(data.trim());

				buscarBloqueo(data);
				buscarObserva(data);
				validarPadre(data);
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
	$("#DNI").change(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		//alert("Enter");		
		if (vDNI.length > '5'){
			
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	
	function validarPadre(Per_ID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "validarPadreDatos", Per_ID: Per_ID},
			url: 'cargarOpciones.php',
			success: function(data){ 				
				if (data)
					$("#noTienePadreAsociado").html(data);
				//alert(data);
			}
		});//fin ajax//*/
	}
	
	$("#datosEntrevista").click(function(evento){
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {UsuID: vUsuID, PerID: vPerID},
			url: 'cargarEntrevista3.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	
	
	$("#barraCuotas").click(function(evento){
		
		if (validarIngreso()){
		//alert("");
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {UsuID: vUsuID, PerID: vPerID, Tipo: "cuotas"},
				url: 'buscarCuotasImpagas.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}//fin if
	});
	$("#barraRecibos").click(function(evento){
		
		if (validarIngreso()){
			//alert("");
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {UsuID: vUsuID, PerID: vPerID, Tipo: "recibos"},
				url: 'buscarCuotasImpagas.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}//fin if
	});
	function validarIngreso(){
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		return true;
	}
	$("#barraPagos").click(function(evento){
		
		if (validarIngreso()){
			//alert("");
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {UsuID: vUsuID, PerID: vPerID, Tipo: "pagos"},
				url: 'buscarCuotasImpagas.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}
	});
	$("#barraCuotas2").click(function(evento){
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/

		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {UsuID: vUsuID, PerID: vPerID},
			url: 'MostrarAsignarCuotas.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	$("#barraFacturas").click(function(evento){
		
		if (validarIngreso()){
			//alert("");
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcionnum: "persona", per_ID: vPerID, opcionvol: 1},
				url: 'listadoFactura.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
		}
	});
	$("#barraPlanesPago").click(function(evento){
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {UsuID: vUsuID, PerID: vPerID},
			url: 'cargarPlanesPago.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	$("#datosRequisitos").click(function(evento){
		
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {UsuID: vUsuID, PerID: vPerID},
			url: 'buscarRequisitoPersonas.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
		
	})//datos cta cte
	$("#datosCtaCte").click(function(evento){
		
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'mostrarCuentaCorrientePersona.php',
			data: {Per_ID: vPerID},
			success: function (data){
				$("#mostrar").show();
				$("#mostrar").html(data);
			}
		});//fin ajax
		
	})//datos requisitos

	$("#barraRapipago").click(function(evento){
		
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {PerID: vPerID},
            url: 'mostrarListadoRapipago.php',
            success: function(data){ 
                $("#mostrar").html(data);
            }
        });//fin ajax//*/
		
	})//datos requisitos

	$("#CuentaAlumno").click(function(evento){
		
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de mostrar las cuotas debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de mostrar las cuotas debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'mostrarCuentaAlumno.php',
			data: {Per_ID: vPerID},
			success: function (data){
				$("#mostrar").show();
				$("#mostrar").html(data);
			}
		});//fin ajax
		
	})//Cuenta Alumno
	
	$("#datosBloqueados").click(function(evento){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'mostrarListadoBloqueados.php',
			//data: {Per_ID: vPerID},
			success: function (data){
				$("#mostrar").show();
				$("#mostrar").html(data);
			}
		});//fin ajax
		
	})//datos requisitos
	

	$("#datosLibreDeuda").click(function(evento){
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de imprimir el Libre Deuda debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de imprimir el Libre Deuda debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/.

		jPrompt('Escriba la observación:', '', 'Observación', function(m) {
		    if( m ){
				//alert("");
				window.open("imprimirLibreDeuda.php?Per_ID="+vPerID+"&Obs="+m, '_blank');
		    }
		});
	
	})

	$("#datosContrato").click(function(evento){
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de imprimir el Contrato debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de imprimir el Contrato debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		window.open("imprimirContrato.php?ID="+vPerID, '_blank');
	})

	$("#datosPersonales").click(function(evento){
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Error: Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Error: Debe escribir un DNI","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Error: Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Error: Debe escribir un DNI","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {UsuID: vUsuID, PerID: vPerID},
			url: 'mostrarDatosPersonales.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	$("#datosBeneficios").click(function(evento){
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Error: Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Error: Debe escribir un DNI","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Error: Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Error: Debe escribir un DNI","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {UsuID: vUsuID, PerID: vPerID},
			url: 'mostrarDatosBeneficios.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	$("#datosLibro").click(function(evento){
		
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {PerID: vPerID},
			url: 'cargarLibroVenta.php',
			success: function(data){ 
				$("#mostrar").html(data);
			}
		});//fin ajax//*/
	});
	
	$("#mostrarBloqueo").hide();
	$("#bloquearPersona").click(function(evento){
		evento.preventDefault();
		$("#mostrarBloqueo").show();
	});

	$("#cerrarBloqueo").click(function(evento){
		$("#mostrarBloqueo").hide();
	});
	$("#guardarBloquearPersona").click(function(evento){
		evento.preventDefault();
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vPersona = $("#persona").val();
		vContador = $("input:checked").length;
		Motivo = $("#Motivo").val();
		BTiID = $("#BTiID").val();
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de bloquear una persona debe escribir un DNI","Atención");
				return false;
			}
			if (Motivo==null){
				jAlert("Antes de bloquear una persona debe escribir un Motivo","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de bloquear una persona debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de bloquear una persona debe escribir un DNI","Atención");
				return false;
			}
			if (Motivo.length==0){
				jAlert("Antes de bloquear una persona debe escribir un Motivo","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de bloquear una persona debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		
		if (BTiID==-1){
			jAlert("Antes de bloquear una persona debe seleccionar un tipo de bloqueo","Atención");
			return false;
		}
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "bloquearPersona", PerID: vPerID, Motivo: Motivo, BTiID: BTiID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				jAlert(data,"Resultado del bloqueo");
				$("#barraCuotas2").hide();
				$("#bloquearPersona").hide();
				$("#mostrarBloqueo").hide();
				$("#DNI").change();

			}
		});//fin ajax//*/
	});

//cargar de observaciones a una persona
//Mario. 05/08/2022
	$("#mostrarCargaObserva").hide();
	$("#datosObservada").click(function(evento){
		evento.preventDefault();
		$("#mostrarCargaObserva").show();
	});
	$("#cerrarObserva").click(function(evento){
		$("#mostrarCargaObserva").hide();
	});
	$("#guardarObservaPersona").click(function(evento){
		evento.preventDefault();
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vContador = $("input:checked").length;
		vPersona = $("#persona").val();
		Observ = $("#Observ").val();
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de continuar debe escribir un DNI","Atención");
				return false;
			}
			if (Observ==null){
				jAlert("Antes de continuar debe escribir la Observación","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de continuar debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de seguir debe escribir un DNI","Atención");
				return false;
			}
			if (Observ.length==0){
				jAlert("Antes de seguir debe escribir la Observación","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de seguir debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "observarPersona", PerID: vPerID, Observ: Observ},
			url: 'cargarOpcionesObservacion.php',
			success: function(data){ 
				jAlert(data,"Resultado");
				$("#barraCuotas2").hide();
				$("#mostrarCargaObserva").hide();
				$("#DNI").change();
			}
		});//fin ajax//*/
	});
	
	$("#quitarObserva").click(function(evento){
		evento.preventDefault();
		vPerID = $("#PerID").val();
		vDNI = $("#DNI").val();
		vContador = $("input:checked").length;
		vPersona = $("#persona").val();
		$("#Observ").val('');
		if ($.browser.msie) {
			if (vDNI==null){
				jAlert("Antes de continuar debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				//if (vPersona.length==0){
				if (vPersona==null){
					jAlert("Antes de continuar debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador
		}else{
			if (vDNI.length==0){
				jAlert("Antes de seguir debe escribir un DNI","Atención");
				return false;
			}
			if (vContador==0){
				if (vPersona.length==0){
					jAlert("Antes de seguir debe seleccionar una persona","Atención");
					return false;
				}
			}//fin contador			
		}//*/
		//alert("");
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "quitarObservaPersona", PerID: vPerID},
			url: 'cargarOpcionesObservacion.php',
			success: function(data){ 
				jAlert(data,"Resultado");
				$("#barraCuotas2").hide();
				$("#mostrarCargaObserva").hide();
				$("#PersonaObservada").hide();
				$("#DNI").change();
			}
		});//fin ajax//*/
	});
//*******************

	
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
			buscarBloqueo(data.Per_ID);
			buscarObserva(data.Per_ID);
			validarPadre(data.Per_ID);
			$("#mostrar").empty();
		}
	}
	
	$("#btnQuitarBloqueo").click(function(evento){
		evento.preventDefault();
		PerID = $("#PerID").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: { opcion: "levantarBloqueo", PerID: PerID },
			url: "cargarOpciones.php",
			success: function (data){
				jAlert(data, "Resultado de la operación");
				$("#cargando").hide();
				$("#DNI").change();
			}
		});//fin ajax
	 });

	function buscarBloqueo(PerID){
		//alert("");
		$("#PersonaBloqueada").empty();
		$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,
			  error: function (XMLHttpRequest, textStatus){
				  alert(textStatus);},
			  data: {opcion: "obtenerBloqueo", PerID: PerID},
			  url: 'cargarOpciones.php',
			  success: function(data){ 
				  //alert('*'+data+'*');
				  if (data.trim()!=''){
				  	$("#PersonaBloqueada").html("<div class='ui-widget'><div class='ui-state-error ui-corner-all' style='padding: 0 .7em;'><p><span style='float: left; margin-right: .3em;' class='ui-icon ui-icon-alert'></span>Esta persona se encuentra bloqueada. <br />Motivo: <strong>"+data+"</strong></p></div></div>");
					//jAlert("Esta persona se encuentra bloqueada. Motivo: " + data);
					$("#barraCuotas2").hide();
					$("#bloquearPersona").hide();
					$("#btnQuitarBloqueo").show();
				  }else{
					  $("#barraCuotas2").show();
					  /*$("#barraCuotas2").button();
					  $("#barraCuotas2").attr("class","botones");
					  $("#barraCuotas2").button();*/
					  $("#bloquearPersona").show();
					  $("#btnQuitarBloqueo").hide();
				  }
			  }
		  });//fin ajax//*/
	}
	$("#msj_para_ie").result(colocarValor);	
	$(".botones").button();
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
	$(".ocultar").hide();

	function buscarObserva(PerID){
		//alert("");
		$("#PersonaObservada").empty();
		$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,
			  error: function (XMLHttpRequest, textStatus){
				  alert(textStatus);},
			  data: {opcion: "obtenerObserva", PerID: PerID},
			  url: 'cargarOpcionesObservacion.php',
			  success: function(data){ 
				  //alert('*'+data+'*');
				  if (data.trim()!=''){
				  	$("#PersonaObservada").html("<div class='ui-widget'><div class='ui-state-error ui-corner-all' style='padding: 0 .7em;'><p><span style='float: left; margin-right: .3em;' class='ui-icon ui-icon-alert'></span>Esta persona se encuentra OBSERVADA. <br />Motivo: <strong>"+data+"</strong></p></div></div>");
					//$("#barraCuotas2").hide();
					$("#Observ").val(data);
					$("#mostrarCargaObserva").hide();
				  }else{
					  $("#barraCuotas2").show();
					  $("#Observ").val('');
				  }
			  }
		  });//fin ajax//*/
	}	

});//fin de la funcion ready


</script>

<div id="mostrarCuotas"> 

	<p>&nbsp;</p>
   <!-- <form autocomplete="off" action="">-->
	<table border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="imagenes/table_cuotas.png" width="48" height="48" align="absmiddle" /> Cuotas a pagar</div></td>
      </tr>
	  <tr>
	    <td width="30%" class="texto"><div align="right"><strong>DNI:</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" />
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" /> <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/></td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Alumno/a:</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="50" />
          <img src="imagenes/bullet_delete.png" alt="Bloquear persona" name="bloquearPersona" width="40" height="40" id="bloquearPersona" style="cursor:pointer; vertical-align:middle; alignment-baseline:middle" title="Bloquear persona" /><img src="imagenes/accept.png" id='btnQuitarBloqueo' name='btnQuitarBloqueo' width="32" height="32" style="cursor:pointer; vertical-align:middle; alignment-baseline:middle" title="Quitar Bloqueo" alt="Quitar Bloqueo" /></td>
      </tr>
      <tr>
        <td colspan="2" class="texto">
	        <div id="PersonaBloqueada"></div>
	        <div id="PersonaObservada"></div>
	        <div id="PersonaDatos"></div>
	    </td>    
      </tr>
	  <tr>
	    <td colspan="2" class="texto"><div id="mostrarBloqueo">
	      <table width="100%" border="0" cellspacing="1" cellpadding="1" style="border-color:#C00; border-style:solid; border-width:thin">
	        <tr>
	          <td colspan="2" align="center"><h3><img src="imagenes/borrar_activo.png" style="vertical-align:middle; alignment-baseline:middle" width="32" height="32" /> Bloquear esta persona</h3></td>
            </tr>
	        <tr>
	          <td width="50%" align="right">Tipo de Bloqueo:</td>
	          <td><?php cargarListaBloqueoTipo("BTiID");?></td>
            </tr>
	        <tr>
	          <td align="right">Motivo:</td>
	          <td><textarea name="Motivo" cols="50" rows="3" id="Motivo"></textarea></td>
            </tr>
	        <tr>
	          <td align="right">&nbsp;</td>
	          <td><button class="botones" id="guardarBloquearPersona">Guardar bloqueo</button><button class="botones" id="cerrarBloqueo">Cerrar</button></td>
            </tr>
          </table>
	    </div></td>
      </tr>
   
      <tr>
        <td colspan="2" align="center" class="texto">
      <fieldset class="recuadro_simple" id="resultado_buscador">
  		<legend>Datos sobre las Cuotas</legend>
        <!--<button class="botones" id="barraCuotas">Cuotas</button>--> 
        <button class="botones" id="barraRecibos">Recibos</button> 
        <button class="botones" id="barraPagos">Pagos, Anulados, Cancelados</button> 
        <button class="botones" id="barraCuotas2">Asignar Cuotas Manual</button>
        <button class="botones" id="barraFacturas">Listado de Facturas</button>
        <?php if ($UsuID==2 || $UsuID==11 || $UsuID==12 || $UsuID==13){
		?>
        <button class="botones" id="barraPlanesPago">Planes de Pago</button>
        <?php
		}//fin if
		?>
        </fieldset>
      <fieldset class="recuadro_simple" id="resultado_buscador">
  		<legend>Datos sobre la Persona</legend>
        <button class="botones" id="datosPersonales">Ficha Alumno</button>
        
        <button class="botones" id="datosBeneficios">Beneficios (Becas)</button>
        
        <button class="botones" id="datosRequisitos">Requisitos</button>
        <!-- <button class="botones" id="datosCtaCte">Cta. Cte.</button>
        <button class="botones" id="barraRapipago">RAPIPAGO</button> -->
        <button class="botones" id="CuentaAlumno">Cuenta Alumno</button>
        <button class="botones" id="datosBloqueados">Listar Bloqueados</button>
        <button class="botones" id="datosLibreDeuda">Libre Deuda</button>
        <!--<button class="botones" id="datosContrato">Contrato</button>-->
        <button class="botones" id="datosObservada"><span style="color:red">Observada</span></button> 
        <div id="mostrarCargaObserva">
	      <table width="100%" border="0" cellspacing="1" cellpadding="1" style="border-color:#C00; border-style:solid; border-width:thin">
	        <tr>
	          <td colspan="2" align="center"><h3><img src="imagenes/borrar_activo.png" style="vertical-align:middle; alignment-baseline:middle" width="32" height="32" /> Observar a esta persona</h3></td>
            </tr>
	        <tr>
	          <td align="right">Observación:</td>
	          <td><textarea name="Observ" cols="50" rows="3" id="Observ"></textarea></td>
            </tr>
	        <tr>
	          <td align="right">&nbsp;</td>
	          <td><button class="botones" id="guardarObservaPersona">Guardar Observación</button><button class="botones" id="cerrarObserva">Cerrar</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <button class="botones" id="quitarObserva">Borrar Observación</button></td>
            </tr>
          </table>
	    </div>       
      </fieldset>
        <!--<button class="botones" id="datosLibro">Venta Libro</button>-->
       
		<?php if (isset($_POST['DNI'])){?>
<button style="width:48px" class="barra_boton" id="barraVolver"><img src="imagenes/go-previous.png" alt="Volver atrás" title="Volver atrás" width="22" height="22" border="0" /><br />
      Volver</button>
<?php }//fin if?></td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>

	</div>
	
	<p>&nbsp;</p>
	