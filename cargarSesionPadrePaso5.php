<?php
header("Cache-Control: no-cache, must-revalidate"); 
//include_once("comprobar_sesion.php");
require_once("conexion.php");
//include_once("guardarAccesoOpcion.php");
require_once("funciones_generales.php");



?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.printElement.js" language="javascript"></script>


<script language="javascript">
$(document).ready(function(){

	
	limpiarDatos();	
	function limpiarDatos(){
		$('#clave').val("");
	}

	$("#barraImprimir").click(function(evento){
		evento.preventDefault();

		vLectivo = $("#LecID option:selected").text();
		$("#listadoImprimir").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Iniciar Sesión Padre',overrideElementCSS:['css/general.css',{ href:'css/general.css',media:'print'}]
										
										});
		$("#cargando").hide();
	 });//fin evento click//*/
	 
	 
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI").val();
		vPerID = $("#PerID").val();

		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  data: {DNI: vDNI, PerID: vPerID},
		  url: 'cargarSesionPadrePaso5.php',
		  success: function(data){
			  $("#principal").html(data);
			  $("#cargando").hide();
			 
		  }//fin success
		});//fin ajax
		
	});//fin del boton guardar
	
	
	$("#barraVolver").click(function(evento){
		evento.preventDefault();
		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  url: 'cargarSesionPadre.php',
		  success: function(data){
			  $("#principal").html(data);
			  $("#cargando").hide();
			 
		  }//fin success
		});//fin ajax	
	});//fin del boton guardar	


});//fin de la funcion ready


</script>

<div id="mostrarUsuario">
    <?php
		//$DNI = 18618965;
		$DNI = $_POST['DNI'];
		$PerID = $_POST['PerID'];
		//$PerID = 3602;
		gObtenerApellidoNombrePersona($DNI, $Apellido, $Nombre);
		$persona = "$Nombre $Apellido";
		$asunto = "Se ha dado de ALTA un Padre";
		$cuerpo = "El padre $Nombre $Apellido con DNI $DNI ha creado su cuenta de usuario en GITeCo.";
		$para = "fabricioeche@gmail.com";
		$nombre = "Fabricio Echegaray";
		//if (!enviarEmail($asunto, $cuerpo, $para, $nombre)){		}
        ?>
  <form id="signupform" autocomplete="off" method="get" onsubmit="return false;">
	<?php
if ($Apellido=="DNI Inexistente"){

?>
    <table width="80%" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
      <tr>
        <td class="textoInformativo"><p><span class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez (</span><span class="textoInformativo">ERROR DE ACCESO</span><span class="titulo_noticia">)</span></p>
        <p>Ocurri&oacute; un error en la comunicaci&oacute;n. </p>
        <p>Haga click en el siguiente bot&oacute;n 
           <button class="barra_boton" id="barraVolver"> <img src="imagenes/go-previous.png" alt="Guardar la clave nueva" width="22" height="22" border="0" align="absmiddle" title="Guardar la clave nueva" /> Volver atr&aacute;s</button> 
        y vuelva a ingresar su n&uacute;mero de documento.</p></td>
      </tr>
    </table>
    <?php
}else{	
	
	?>
    
    <table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td width="99%">		
		<div align="center" class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez <span class="textoInformativo">(Paso 5 de 5)</span></div></td>
      </tr>
	  <tr>
	    <td> <br />


<p class="textoInformativo">Muchas Gracias <strong><?php echo $Nombre;?></strong> por completar todos los pasos. Ahora Usted ya puede ingresar a GITeCo con su clave personal y consultar la situaci&oacute;n acad&eacute;mica y econ&oacute;mica de sus hijos. 
<p class="textoInformativo">Por cualquier duda, mejora o consulta estamos a su disposici&oacute;n.
<p>

</td>
    </tr>
		<tr>
		  <td class="texto">&nbsp;</td>
	  </tr>
    </table>
    <?php
}//fin else error
	?>
  </form>
	</div>
<p>&nbsp;</p>
	