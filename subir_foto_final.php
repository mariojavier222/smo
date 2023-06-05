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


require_once "upload/class.upload.php"; //Incluyes la clase
require_once("conexion.php");
require_once("funciones_generales.php");

$PerID = $_POST['PerID'];

$DNI = gbuscarDNI($PerID);

//echo $DNI;exit;
buscarTipoDoc($DNI, $DocuID);
$DNI = substr("000000000".$DNI,-9);
$DNI = $DocuID.$DNI;
//echo $DNI;

$foto = $_POST['foto'];
$x1 = $_POST['x1'];
$y1 = $_POST['y1'];
$x2 = $_POST['x2'];
$y2 = $_POST['y2'];
$w = $_POST['w'];
$h = $_POST["h"];

$nombre = $foto;
//$path_images = $_SERVER['DOCUMENT_ROOT']."/steresita/fotos";
$path_images = $_SERVER['DOCUMENT_ROOT']."/local/cesap/fotos";
$archivo = $path_images."/temp/".$foto.".jpg";
$ancho = getWidth($archivo);
//echo $nombre;//exit;
//if ($ancho > 800) $ancho = 800;

//You do not need to alter these functions

//$scale = $thumb_width/$w;
$scale = $ancho/$w;
$scale = 1;
$cropped = resizeThumbnailImage("fotos/resized/$DNI.jpg", "fotos/temp/$foto.jpg",$w,$h,$x1,$y1,$scale);


$archivo = $path_images."/resized/$DNI.jpg";
/*subirFoto($archivo, $path_images."/grande",500);
subirFoto($archivo, $path_images."/chica",60);*/

//Subimos la fotos al directorio local
subirFoto($archivo, $path_images."/grande",500);
subirFoto($archivo, $path_images."/chica",60);

//Subimos la fotos al directorio remoto
$path_images = $_SERVER['DOCUMENT_ROOT']."/cesap/fotos";
subirFoto($archivo, $path_images."/grande",500);
subirFoto($archivo, $path_images."/chica",60);

unlink("fotos/temp/$foto.jpg");
$fecha = date("YmdHis");
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />

<p class="titulo_noticia">La foto elegida ha sido guardada en los siguientes tama&ntilde;os: </p>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="center"><img src="fotos/chica/<?php echo "$DNI.jpg?$fecha";?>" width="60" class="foto" /></td>
    <td align="center"><img src="fotos/grande/<?php echo "$DNI.jpg?$fecha";?>" width="500" class="foto" /></td>
  </tr>
</table>
<p>&nbsp;</p>
<?php

function subirFoto($archivo, $path, $ancho, $marcar=false){
	$handle = new upload($archivo, 'es_ES');
	if ($handle->uploaded) {		  
		$handle->file_new_name_ext = 'jpg';
		$handle->image_resize         = true;
		$handle->image_x              = $ancho;
		$handle->image_ratio_y        = true;//*/
		$handle->file_overwrite = true;	  
		if ($marcar){
		  $handle->image_text 			= 'www.naptacolegios.com.ar';
		  $handle->image_text_color 	= '#FFFFFF';
		  $handle->image_text_direction = 'v';
		  $handle->image_text_x 		= 5;
		  $handle->image_text_y 		= 5;//*/

		}
		$handle->process($path);
		if (!$handle->processed) {
			echo 'Error : ' . $handle->error;
		}
	}
}//fin function
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


?>
