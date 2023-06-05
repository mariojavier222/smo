<?php
require_once("conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Subir fotos al SIUCC</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.form.js"></script> 
<script language="javascript">
$(document).ready(function(){
	
	$("#botonFoto").click(function(evento){
		vDNI = $("#Doc").val();
		vTipo = $("#TipoDoc").val();
		vImage = $("#image_foto").val();
		if ($.browser.msie) {
			if (vImage==null){
				mostrarAlerta("Debe seleccionar una imagen","Atenci&oacute;n");
				return false;
			}
		}else{
			if (vImage.length==0){
				mostrarAlerta("Debe seleccionar una imagen","Atenci&oacute;n");
				return false;
			}

		}

	document.photo.submit();

	});//fin del click

	$(".botones").button();
});//fin de la funcion ready

</script>
</head>

<?php 
$DNI = $_POST["DNI"];
$Tipo = $_POST["DocID"];
?>

<body>
<br /><div class="" align="left"><p class="texto"> 
	<form action="foto_subir_temporal.php" method="post" enctype="multipart/form-data" name="photo" id="photo" target="webFoto">
	<input type="hidden" name="Doc" id="Doc" value="<?php echo $DNI;?>"/>
	<input type="hidden" name="TipoDoc" id="TipoDoc" value="<?php echo $Tipo;?>"/>
	<span class="texto_sesion">	  Seleccione la foto y haga click en Cargar:</span><br />
  <span class="advertencia_sesion">(La fotograf&iacute;a no puede ser mayor a 2 MegaPixels - 1600x1200.)</span><br />
		<input type="file" name="image_foto" id="image_foto" />&nbsp;&nbsp;
		<button class="botones" id="botonFoto">Cargar</button>
	</form>
  
</p></div><br />
</body>
</html>