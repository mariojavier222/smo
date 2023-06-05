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

//echo "NAHUEL";
$PerID = $_POST['PerID'];
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);

/*echo "PerID ".$PerID."<br>";

echo "UsuID ".$UsuID."<br>";*/
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script src="js/jquery.printElement.js" language="javascript"></script>
<script language="javascript">
$(document).ready(function(){


var PerID=$("#PerID").val();

	$.ajax({
		type: "POST",
		cache: false,
		async: false,
		data: {opcion: 'mostrarDatosPersonales1', PerID: PerID},
		url: 'cargarOpciones.php',
		success: function(data){ 
		$("#editarDatosPersonales").html(data);
		
		}
	});//fin ajax///
		
	$.ajax({
		type: "POST",
		cache: false,
		async: false,
		data: {opcion: 'mostrarDatosAdiccionales', PerID: PerID},
		url: 'cargarOpciones.php',
		success: function(data){ 
		$("#editarDatosPersonalesAdicionales").html(data);
		
		}
	});//fin ajax///

	$("#imprimir").click(function(evento){
		evento.preventDefault();

		
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:''
		,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
										});
//		$("div#listado").printArea(options);
		$("#cargando").hide();
	 });//fin evento click//*/
	
});
</script>

<input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>"
<br />
<br />
<div id="listadoTabla">
<div id="editarDatosPersonales"></div>

<div id="editarDatosPersonalesAdicionales"></div>
</div>
<a href="#" id="imprimir"><img src="imagenes/printer.png" alt="Imprimir Ficha del Alumno" title="Imprimir Ficha del Alumno" width="32" border="0" align="absmiddle" /></a>


