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
		$('#email').val("");
	}

	$("#barraImprimir").click(function(evento){
		evento.preventDefault();

		$("#listadoImprimir").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Iniciar Sesión Padre',overrideElementCSS:['css/general.css',{ href:'css/general.css',media:'print'}]
										
										});
		$("#cargando").hide();
	 });//fin evento click//*/
	 
	 
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		vCorreo = $("#email").val();
		vDNI = $("#DNI").val();
		vPerID = $("#PerID").val();

		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  data: {DNI: vDNI, PerID: vPerID, Correo: vCorreo},
		  url: 'cargarSesionPadrePaso4.php',
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
		$PerID = gbuscarPerID($DNI);
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
        <p>Ocurri&oacute; un error en la comunicaci&oacute;n. </p>
        <p>Haga click en el siguiente bot&oacute;n 
           <button class="barra_boton" id="barraVolver"> <img src="imagenes/go-previous.png" alt="Guardar la clave nueva" width="22" height="22" border="0" align="absmiddle" title="Guardar la clave nueva" /> Volver atr&aacute;s</button> 
        y vuelva a ingresar su n&uacute;mero de documento.</p></td>
      </tr>
    </table>
    <?php
}else{
	
	$sql = "SELECT * FROM Usuario, Sede WHERE Sed_ID = Usu_Sed_ID AND Usu_Nombre = '$DNI'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
?>
    <table width="80%" border="0" align="center" cellpadding="1" cellspacing="1" class="borde_recuadro">
      <tr>
        <td class="textoInformativo"><p><span class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez (</span><span class="textoInformativo">ERROR DE ACCESO</span><span class="titulo_noticia">)</span></p>
        <p>El n&uacute;mero de documento <strong><?php echo $DNI;?></strong> ya existe como usuario en nuestro sistema. No necesita continuar con este apartado. Lo &uacute;nico que tiene que hacer es identificarse con su n&uacute;mero de documento y clave que usted eligi&oacute; al momento de registrarse.</p>
        <p>Si Usted considera que existe un error y es la primera vez que ingresa a <strong>GITeCo</strong>, por favor dir&iacute;jase al Departamento  Alumnos para solucionar el problema.</p>
        <p>Disculpe las molestias.</p>
        <p>&nbsp;</p></td>
      </tr>
    </table>
    <?php	
	}else{
	$Usuario = $_POST['DNI'];
	$Clave = md5($_POST['Clave']);
	$SedID = 1;	//por defecto Rivadavia
	$sql = "INSERT INTO Usuario (Usu_Nombre, Usu_Persona, Usu_Clave, Usu_Sed_ID) VALUES ('$Usuario', '$persona', '$Clave', '$SedID')";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT LAST_INSERT_ID();";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$UsuID = $row[0];
	$Rol = 11;//Rol de Padre de Colegio
	guardarRolUsuario($UsuID, $Rol);
?>
    
    <table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez <span class="textoInformativo">(Paso 3 de 5)</span></div></td>
      </tr>
	  <tr>
	    <td colspan="2" class="textoInformativo"><br />


<p>
<div id="listadoImprimir">
<strong>&iexcl;<?php echo $Nombre;?>, Se ha creado su cuenta de usuario correctamente!
        
        </strong></p>
	      <p><img src="logos/logo_Giteco.png" width="161" height="34" alt="GITeCo" /> </p>
	      <p>Sus datos de acceso son: </p>
	      <ol>
	        <li>Portal de Acceso a <strong>GITeCo</strong>: www.uccuyo.edu.ar/uccdigital
	        </li>
	        <li>Usuario: <strong><?php echo $Usuario;?></strong>
	        </li>
	        <li>Clave <span class="texto">(la que Usted eligi&oacute;)</span>: <strong>******</strong></li>
          </ol>
</div>
<p><button align="center" class="barra_boton" id="barraImprimir"><img src="imagenes/printer.png" alt="Guardar la clave nueva" width="32" height="32" border="0" align="absmiddle" title="Guardar la clave nueva" /><br />
  Imprimir
</button></p></td>
    </tr>
		<tr>
		  <td colspan="2" class="textoInformativo"><p>&nbsp;</p>
	      <p>Gracias <?php echo $Nombre;?> por darse de alta<em></em> en GITeCo, ahora s&oacute;lo le pedimos unos minutos m&aacute;s para contestar unas preguntas sobre su/s hijo/s y validar sus datos cargados en nuestro sistema. </p>
	      <p>Antes de continuar con las preguntas, ser&iacute;a muy beneficioso para Usted que nos brinde una cuenta de correo electr&oacute;nica. Con ella podremos enviarle notificaciones y recordartorios relacionados con el cursado de su/s hijo/s. Si no tiene una cuenta o desea ingresarla en otro momento, s&oacute;lo haga click en continuar.</p></td>
	  </tr>      
		<tr>
	  <td colspan="2" class="texto">&nbsp;</td>
      </tr>      
      
		<tr class="input_editar">
		  <td width="50%" align="right" class="texto"><strong>Ingrese una cuenta de correo<br />
que use regularmente</strong><strong>:</strong></td>
		  <td width="49%" class="texto"><input name="email" type="text" id="email" size="40" maxlength="60" />
		  *
	      <input name="DNI" type="hidden" id="DNI" value="<?php echo $DNI;?>" />
          <input name="PerID" type="hidden" id="PerID" value="<?php echo $PerID;?>" /></td>
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
	}//fin else ya existe como usuario
}//fin else error
	?>
  </form>
	</div>
<p>&nbsp;</p>
	