<?php

require_once "upload/class.upload.php"; 

//$raiz = $_SERVER['DOCUMENT_ROOT']."/steresita/fotos/temp/";
$raiz = $_SERVER['DOCUMENT_ROOT']."/local/cesap/fotos/temp/";
//echo $raiz;exit;
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



$PerID = $_POST['PerID'];
$ancho = getWidth($_FILES['userfile']["tmp_name"]);
$alto = getHeight($_FILES['userfile']["tmp_name"]);
$nombre = strtotime(date('Y-m-d H:i:s'));
//echo $nombre;exit;
//echo "$ancho-$alto";
if ($ancho > 2000 || $alto > 2000) {
	echo "error";
	exit;
	//echo "La foto que intenta subir tiene un tama&ntilde;o mayor al permitido. Aseg&uacute;erese de el ancho de la foto no supere los 1600 p&iacute;xeles y/o que altura sea menor a 1200 p&iacute;xeles";
}


if ($ancho > 1000) $ancho = 1000;


//$comprobar_archivo = $directorio.$_FILES['userfile']['name'];
$handle = new upload($_FILES['userfile'], 'es_ES');
/*if ($handle->uploaded) {
	$handle->file_new_name_body   = $nombre;
	$handle->file_new_name_ext = 'jpg';
	$handle->image_resize         = true;
	$handle->image_x              = $ancho;
	$handle->image_ratio_y        = true;
	$handle->file_overwrite = true;	  
	$handle->process($raiz);
	if ($handle->processed) {   
		echo $nombre;
		$handle->clean();		  
	} else {
		echo "Hubo un error al intentar subir el archivo: " . $handle->error;
	}
}else{
  echo "No se puede cargar la subida de archivos.";
}*/
echo "Final";
/*}else{
//ya existe el archivo subido
	echo "<img src='fotos/chica/$DNI.jpg' title='Foto'/>";
}//*/

?>
