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
		$("#listadoImprimir").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Iniciar Sesi�n Padre',overrideElementCSS:['css/general.css',{ href:'css/general.css',media:'print'}]
										
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
	
	//Guardamos la cuenta de correo proporcionada
	$para = $_POST['Correo'];
	//$para = "seccionacademica@uccuyo.edu.ar";
	$nombrePara = $persona;
	$asunto = "Datos de Acceso a GITeCo";
	
	$from = "infocolegio@uccuyo.edu.ar";
	$nombreFrom = "Administracion Colegios";	//*/
	
	$cuerpo = "<table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\" style=\"border-color:#999; border:solid; border-width:thin; font-size:12px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif\">
  <tr>
    <td ><table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">
      <tr>
        <td colspan=\"2\" align=\"center\"><p><img src=\"http://www.uccuyo.edu.ar/uccdigital/logos/logo_college.png\" alt=\"\" width=\"90\" height=\"106\"></p>
          <p>&nbsp;</p></td>
        <td align=\"center\"><img src=\"http://www.uccuyo.edu.ar/uccdigital/logos/logo_Giteco.png\" width=\"161\" height=\"34\" alt=\"GITeCo\"></td>
        </tr>
      <tr>
        <td colspan=\"3\" align=\"center\">Muchas gracias <strong>$Nombre</strong> por registrarse en <strong>GITeCo</strong>. A trav�s de este medio podremos estar comunicados en forma permanente.</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=\"3\">Sus datos de acceso son: </td>
        </tr>
      <tr>
        <td>1)</td>
        <td>Ingreso a  <strong>GITeCo</strong>:</td>
        <td><a href=\"http://www.uccuyo.edu.ar/uccdigital\" target=\"_blank\">www.uccuyo.edu.ar/uccdigital</a></td>
      </tr>
      <tr>
        <td>2)</td>
        <td>Usuario:</td>
        <td>$DNI</td>
      </tr>
      <tr>
        <td>3)</td>
        <td>Clave:</td>
        <td>****** (la que Usted eligi�)</td>
      </tr>
    </table></td>
  </tr>
</table>";
	//*/
	actualizarCorreo($DNI, $para);
	if (!enviarEmail($asunto, $cuerpo, $para, $nombre)){
		echo "Ocurri� un error al enviar un email a su casilla de correo.";
	}
	//if (!enviarEmail("Se ha dado de alta un padre", "El padre $persona con DNI $DNI ha creado su cuenta de usuario en GITeCo. ", "seccionacademica@uccuyo.edu.ar", "Ivana Carrizo")){ 	}
	//if (!enviarEmail("Se ha dado de alta un padre", "El padre $persona con DNI $DNI ha creado su cuenta de usuario en GITeCo. ", "administracioncolegios@uccuyo.edu.ar", "Dra. Silvia Berrino")){	}
	/*if ( !enviarEmail($asunto, $cuerpo, $para, $nombrePara, $from, $nombreFrom) ){
			echo "Ocurri� un error al tratar de enviar el mensaje de bienvenida a su casilla de correo.<br />";
		}//*/
	?>
    
    <table width="95%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="2">		
		<div align="center" class="titulo_noticia"><img src="botones/system-users.gif" width="32" height="32" align="absmiddle" /> Iniciar Sesi&oacute;n  Padre por primera vez <span class="textoInformativo">(Paso 4 de 5)</span></div></td>
      </tr>
	  <tr>
	    <td colspan="2"> <br />


<p class="textoInformativo"><strong><?php echo $Nombre;?></strong> hemos encontrado en nuestro sistema los siguientes v&iacute;nculos familiares que se detallan m&aacute;s abajo. Le pedimos por favor que los identifique y corrobore que sean correctos. En caso de que falten algunos o existan v&iacute;nculos equ&iacute;vocos, tiene que dirigirse al Departamento  Alumnos del Colegio para su correcci&oacute;n.
<p>
<?php
	$sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID)
WHERE (Familia.Fam_Per_ID = $PerID);";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){
	
	}else{
		
	$LecID = gLectivoActual();
	while ($row = mysqli_fetch_array($result)){
?>
        <div class="cuadroVinculoFamiliar">
          <p><strong><?php echo "$row[Per_Nombre] $row[Per_Apellido]";?></strong><br />
            <br />
            DNI: <strong><?php echo $row[Per_DNI];?></strong><br />
            Vinculo: <strong><?php
            list($masc, $fem) = explode("/", $row[FTi_Nombre]);
			if ($row[Per_Sexo]=="M") $tipo = $masc;else $tipo = $fem;
			if (empty($tipo)) $tipo = $masc;
			echo $tipo;
			?></strong><br />
            Edad: <strong><?php echo obtenerEdad($row[Per_ID]);?> a�os</strong><br />
            <?php
            
			$datos = Obtener_DatosInscripcionLectivo($row[Per_DNI], $LecID);
			if (!empty($datos)){
				list($Nivel, $Curso, $Division) = explode(";", $datos);
				echo "Cursando: <strong>$Curso \"$Division\"</strong>";
			}
			?>
          </p>
        </div>
<?php
	}//fin while
	}//fin if si tiene vinculos
?>


</td>
    </tr>
		<tr>
		  <td colspan="2" class="texto"><p>&nbsp;</p>
	      <p class="textoInformativo">A continuaci&oacute;n le detallamos la informaci&oacute;n que tenemos de Usted en nuestro sistema. Es importante para nosotros disponer de todos los datos completos por si ocurre alguna emergencia y necesitamos contactarlo. En caso de que falten datos o existan datos err&oacute;neos, tiene que dirigirse al Departamento Alumnos del Colegio para su correcci&oacute;n. </p>
	      <p>
	      <div class="cuadroDatosPersonales">
            <p>
              <?php
          $sql = "SELECT * FROM PersonaDatos
    INNER JOIN Persona 
        ON (PersonaDatos.Dat_Per_ID = Persona.Per_ID) WHERE Per_ID = $PerID;";
		//echo $sql;
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){
			//No tiene datos cargados
		}else{
			$row = mysqli_fetch_array($result);
			
			?>
            </p>
            <table width="580" border="0" align="center" cellpadding="1" cellspacing="1" class="texto">
              <tr>
                <td height="40" colspan="2" valign="top" class="textoInformativo">Nombre y Apellido: <strong><?php echo "$row[Per_Nombre] $row[Per_Apellido]";?></strong></td>
              </tr>
              <tr>
                <td width="258" height="40" valign="top">Fecha de Nacimiento: <strong><?php echo cfecha($row[Dat_Nacimiento]);?></strong></td>
                <td width="315" height="40" valign="top">Lugar: <strong><?php echo obtenerLugar($row[Dat_Nac_Pai_ID], $row[Dat_Nac_Pro_ID], $row[Dat_Nac_Loc_ID]);?></strong></td>
              </tr>
              <tr>
                <td height="40" colspan="2" valign="top">Domicilio actual: <strong><?php echo $row[Dat_Domicilio];?></strong></td>
              </tr>
              <tr>
                <td height="40" colspan="2" valign="top">Localidad: <strong><?php echo obtenerLugar($row[Dat_Dom_Pai_ID], $row[Dat_Dom_Pro_ID], $row[Dat_Dom_Loc_ID]);?></strong></td>
              </tr>
              <tr>
                <td height="40" valign="top">Tel&eacute;fono fijo: <strong><?php echo validarVacio($row[Dat_Telefono]);?></strong></td>
                <td height="40" valign="top">Tel&eacute;fono celular: <strong><?php echo validarVacio($row[Dat_Celular]);?></strong></td>
              </tr>
              <tr>
                <td height="40" colspan="2" valign="top">Observaciones: <?php echo validarVacio($row[Dat_Observaciones]);?></td>
              </tr>
              <tr>
                <td height="40" valign="top">&nbsp;</td>
                <td height="40" valign="top">&nbsp;</td>
              </tr>
            </table>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>
  <?php
		}//fin else
		
		  ?>
            </p>
	      </div>
	      </p></td>
	  </tr>      
		<tr>
	  <td colspan="2" class="texto">&nbsp;</td>
      </tr>
		<tr>
		  <td colspan="2" class="texto">&nbsp;</td>
	  </tr>      

      <tr>
        <td width="50%" class="texto">&nbsp; <input name="DNI" type="hidden" id="DNI" value="<?php echo $DNI;?>" />
          <input name="PerID" type="hidden" id="PerID" value="<?php echo $PerID;?>" /></td>
        <td width="49%" class="texto"><button align="center" class="barra_boton" id="barraGuardar">  Continuar <img src="imagenes/go-next.png" alt="Guardar la clave nueva" width="22" height="22" border="0" align="absmiddle" title="Guardar la clave nueva" /></button>
        <br /></td>
      </tr>
    </table>
    <?php
}//fin else error
	?>
  </form>
	</div>
<p>&nbsp;</p>
	