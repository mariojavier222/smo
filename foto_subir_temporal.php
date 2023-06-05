<?php
//Incluye la clase
require_once "upload/class.upload.php";
include_once("variables_globales.php");

//toma el alto
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
//toma el ancho
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}

$ancho1 = getWidth($_FILES['image_foto']["tmp_name"]);

//tomo los valores
$Doc=$_POST["Doc"];
$TipoDoc=$_POST["TipoDoc"];

//armo el nombre de la foto
$nombre = armarNombreFoto($TipoDoc,$Doc);

if ($ancho1 > 1600){ 
	echo "El ancho de la fotograf&iacute;a no puede ser mayor a 1600px.!";
	exit;	
}


//echo $g_PathRoot."<br />";
//echo $path_images;
//echo $_FILES['image_foto'];

//si el ancho de la foto es mayor a 800 la fijo en 800 para que me entre en la pantalla
if ($ancho1 > 800) 
	$ancho = 800;
else $ancho = $ancho1;	

if ($_FILES['image_foto']){
	$handle = new upload($_FILES['image_foto'], 'es_ES');
	  if ($handle->uploaded) {		  
		  $handle->file_new_name_body   = $nombre;
		  $handle->file_new_name_ext = 'jpg';
		  $handle->image_resize         = true;
		  $handle->image_x              = $ancho;
		  $handle->image_ratio_y        = true;
		  
		  $handle->file_overwrite = true;	  
		  $handle->process($path_images."temp/");
		  if ($handle->processed) {
			  header("Location:foto_vista_previa.php?foto=$nombre&Doc=$Doc&Tipo=$TipoDoc");
		  } else {
			  echo 'Error: '. $handle->error;
		  }
	  }
}else{
	echo "No se pudo conseguir el archivo!";
}//fin if si existe aviso

//arma le nombre de la foto
function armarNombreFoto($TipoDoc,$Doc){
$tamanio=strlen($Doc);
switch ($tamanio) {
    case 6:
        $Doc = "000".$Doc;
        break;
    case 7:
        $Doc = "00".$Doc;
        break;
    case 8:
        $Doc = "0".$Doc;
        break;
    case 9:
        $Doc = $Doc;
        break;
}
return $TipoDoc.$Doc;
}

?>

