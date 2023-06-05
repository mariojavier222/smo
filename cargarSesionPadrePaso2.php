<?php
header("Cache-Control: no-cache, must-revalidate"); 
//include_once("comprobar_sesion.php");
require_once("conexion.php");
//include_once("guardarAccesoOpcion.php");
require_once("funciones_generales.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="js/jquery.validate.password.css" />
<link rel="stylesheet" type="text/css" href="js/checkboxtree.css" charset="utf-8">
<script type="text/javascript" src="js/jquery.validate.password.js"></script>
<script type="text/javascript" src="js/jquery.checkboxtree.js"></script>
<script language="javascript">
$(document).ready(function(){

	
	limpiarDatos();	
	function limpiarDatos(){
		$('#clave').val("");
	}


	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		vClave = $("#clave").val();
		vDNI = $("#DNI").val();
		//alert(vClave);
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			url: 'cargarOpciones.php',
			data: {opcion: "verificarLongitudTexto", Texto: vClave, Long: 6},
			success: function (data){
					if (data){
						mostrarAlerta("La clave ingresada no cumple con los requisitos solicitados", "ERROR");
						return;
					}else{
						$.ajax({
						  type: "POST",
						  cache: false,
						  async: false,
						  data: {DNI: vDNI, Clave: vClave},
						  url: 'cargarSesionPadrePaso3.php',
						  success: function(data){
							  $("#principal").html(data);
							  $("#cargando").hide();
							 
						  }//fin success
						});//fin ajax
					}

					}//fin success
		});//fin ajax//*/
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
		gObtenerApellidoNombrePersona($DNI, $Apellido, $Nombre);
		$persona = "$Nombre $Apellido";
		
		$sexo = buscarSexoPersona($DNI);
		if ($sexo == "F"){
			$tutoria = "mamá";
		}else{
			$tutoria = "papá";
		}
   
        ?>
  <form id="signupform" autocomplete="off" method="get" onsubmit="return false;">
	<?php
if ($Apellido=="DNI Inexistente"){

?>
    <table width="80%" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
      <tr>
        <td class="textoInformativo"><p><span class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez (</span><span class="textoInformativo">ERROR DE ACCESO</span><span class="titulo_noticia">)</span></p>
        <p>El n&uacute;mero de documento ingresado no corresponde con una persona registrada en nuestro sistema. </p>
        <p>Haga click en el siguiente bot&oacute;n 
           <button class="barra_boton" id="barraVolver"> <img src="imagenes/go-previous.png" alt="Guardar la clave nueva" width="22" height="22" border="0" align="absmiddle" title="Guardar la clave nueva" /> Volver atr&aacute;s</button> 
        y vuelva a ingresar su n&uacute;mero de documento.</p></td>
      </tr>
    </table>
    <?php
}else{
?>
    
    <table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez <span class="textoInformativo">(Paso 2 de 5)</span></div></td>
      </tr>
	  <tr>
	    <td colspan="2" class="textoInformativo"><br />


<p><strong>&iexcl;Gracias <?php echo $persona;?> por ingresar a GITeCo!
        
        </strong></p>
	      <p>        Sabemos que Usted es un<?php if ($sexo=="F") echo "a";?> <?php echo $tutoria;?> que dispone de poco tiempo, por eso hemos desarrollado una herramienta que le permitir&aacute; realizar consultas sencillas y r&aacute;pidas de toda la informaci&oacute;n clasificada y preparada que disponemos de sus hijos.</p>
	      <p>Por ser la primera vez que ingresa a <strong>GITeCo</strong> y por cuestiones de seguridad, le solicitaremos una serie de datos para validar que s&oacute;lo Usted pueda tener acceso a la informaci&oacute;n de sus hijos. Le pedimos que lea atentamente los datos solicitados y los que son considerados obligatorios  sean completados a conciencia. Aquellos datos que no sean obligatorios, podr&aacute;n ser llenados en otro momento.</p>
	      <p>Cabe aclarar que toda la informaci&oacute;n solicitada no ser&aacute; revelada y que s&oacute;lo ser&aacute; usada con fines acad&eacute;micos.</p>
<p>&nbsp;</p></td>
      </tr>
		<tr>
		  <td colspan="2" class="textoInformativo">Para comenzar con el ingreso de datos es necesario contar con una clave de identificaci&oacute;n. Le recomendamos que <strong>NO</strong> utilice claves predecibles, como por ejemplo: <em>123456, 654321, 111111</em></td>
	  </tr>      
		<tr>
	  <td colspan="2" class="texto">&nbsp;</td>
      </tr>      
      
		<tr class="input_editar">
		  <td width="50%" align="right" class="texto"><strong>Ingrese una clave nueva<br />
</strong>(6 d&iacute;gitos como m&iacute;nimo)<strong>:</strong></td>
		  <td width="49%" class="texto"><input name="clave" type="password" id="clave" size="12" maxlength="12" />
		  *
	      <input name="DNI" type="hidden" id="DNI" value="<?php echo $DNI;?>" /></td>
      </tr>
		<tr>
	  <td colspan="2" class="texto">&nbsp;</td>
      </tr>      

      <tr>
        <td class="texto">&nbsp;</td>
        <td class="texto"><button align="center" class="barra_boton" id="barraGuardar">  Continuar <img src="imagenes/go-next.png" alt="Guardar la clave nueva" width="22" height="22" border="0" align="absmiddle" title="Guardar la clave nueva" /></button>
        <br /></td>
      </tr>
    </table>
    <?php
}//fin else
	?>
  </form>
	</div>
<p>&nbsp;</p>
	