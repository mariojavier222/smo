<html>
<head>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/jquery-ui-1.8.9.custom.min.js"></script>
<script language="javascript">

function mostrarAlerta(cuerpo, titulo){
	cuerpo = "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {
					'Aceptar': function() {
					$(this).dialog('close');
				}
			}//fin buttons
 		});//fin dialog
}//fin funcion

$(document).ready(function(){

	mostrarAlerta("La foto fue guardada correctamente!","Atenci&oacute;n");

});//fin de la funcion ready

</script>
</head>

<body>
<table class="" align="left">
<tr>
<td>       
<img src="images/500/<?php echo $_POST['foto'];?>.jpg" border="1"/>
</td>
</tr>
</table>
<div id="dialog"></div>

<?php

//You do not need to alter these functions
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}
require_once("conexion.php");
require_once "upload/class.upload.php";
include_once("variables_globales.php");

$Doc = $_POST['Doc'];
$Tipo = $_POST['Tipo'];
$foto = $_POST['foto'];
$x1 = $_POST['x'];
$y1 = $_POST['y'];
$x2 = $_POST['x2'];
$y2 = $_POST['y2'];
$w = $_POST['w'];
$h = $_POST["h"];

$nombre = $foto;
$archivo = $path_images."temp/".$foto.".jpg";
$archivo1 = $path_images."resized/".$foto.".jpg";
$ancho = getWidth($archivo);
$scale = $ancho/$w;
$scale = 1;
$cropped = resizeThumbnailImage("images/resized/".$foto.".jpg", "images/temp/".$foto.".jpg",$w,$h,$x1,$y1,$scale);

/*
echo "Doc: ".$Doc."<br />";
echo "Tipo: ".$Tipo."<br />";
echo "img: ".$archivo."<br />";
echo "img1: ".$archivo1."<br />";
echo "w: ".$w."<br />";
echo "h: ".$h."<br />";
echo "x1: ".$x1."<br />";
echo "y1: ".$y1."<br />";
echo "scale: ".$scale."<br />";
*/

//subo las fotos en los tres tamaños
subirFoto($archivo1, $path_images."500",500, false);
subirFoto($archivo1, $path_images."385",385);
subirFoto($archivo1, $path_images."60",60);

//subirFoto($archivo1, $path_alumno."fotos 500x500",500, false);
//subirFoto($archivo1, $path_alumno."fotos 60x60",60);

//elimino la foto del temp
eliminarFoto($archivo);

//elimino la foto del resize
eliminarFoto($archivo1);

$nombreFoto=$foto.".jpg";

//guardo el nombre de la foto en la tabla persona
guardarFotoSIUCC($Doc,$Tipo,$nombreFoto);

function subirFoto($archivo, $path, $ancho, $marcar=false){
	$handle = new upload($archivo, 'es_ES');
	if ($handle->uploaded) {		  
		$handle->file_new_name_ext = 'jpg';
		$handle->image_resize         = true;
		$handle->image_x              = $ancho;
		$handle->image_ratio_y        = true;
		$handle->file_overwrite = true;	  
		if ($marcar){
		  $handle->image_text 			= 'www.uccuyo.edu.ar';
		  $handle->image_text_color 	= '#FFFFFF';
		  $handle->image_text_direction = 'v';
		  $handle->image_text_x 		= 5;
		  $handle->image_text_y 		= 5;

		}
		$handle->process($path);
		if (!$handle->processed) {
			echo 'Error : ' . $handle->error;
		}
	}
}

function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}

function eliminarFoto($archivo){
	$handle = new upload($archivo, 'es_ES');
    $handle-> Clean();		
	if (!$handle->processed) {
		echo 'Error : ' . $handle->error;
	}
}

function guardarFotoSIUCC($Doc,$Tipo,$foto){
//si ya está cargada no hago nada
$sql = "SELECT Per_CFoto FROM Persona WHERE Per_ID = '$Doc' AND Per_Doc_ID = '$Tipo';";
$result = consulta_mysql($sql);
$row = mysqli_fetch_array($result);
if (strlen($row[Per_CFoto])<6){
	$sql = "UPDATE Persona SET Per_CFoto = '$foto', Per_PFoto = 'E:\\\\siucc\\\\Fotos\\\\' WHERE Per_ID = '$Doc' AND Per_Doc_ID = '$Tipo';";
	consulta_mysql($sql);
}
}
?>
<input type="hidden" name="Doc" id="Doc" value="<?php echo $DNI;?>"/>
<input type="hidden" name="TipoDoc" id="TipoDoc" value="<?php echo $Tipo;?>"/>
</body>