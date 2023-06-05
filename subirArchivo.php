<?php

require_once "upload/class.upload.php"; 

//$raiz = $_SERVER['DOCUMENT_ROOT']."/steresita/fotos";
$raiz = $_SERVER['DOCUMENT_ROOT']."/local/cesap/fotos";

$directorio = $raiz."/chica/";
$DNI = substr("000000000".$_POST['DNI'],-9);
$DNI = $_POST['DocID'].$DNI;

/*$total = strlen($_FILES['userfile']['name']);
$archivo = substr($_FILES['userfile']['name'],0,$total-4);//*/

$comprobar_archivo = $directorio.$_FILES['userfile']['name'];
//if (!file_exists($comprobar_archivo)){
  $handle = new upload($_FILES['userfile'], 'es_ES');
  if ($handle->uploaded) {
	  $handle->file_new_name_body   = $DNI;
	  $handle->file_new_name_ext = 'jpg';
      $handle->image_resize         = true;
      $handle->image_x              = 60;
      $handle->image_ratio_y        = true;
	  $handle->file_overwrite = true;	  
      $handle->process($directorio);
      if ($handle->processed) {
          //echo 'Archivo pequeño subido correctamente';
		  echo "<img src='fotos/chica/$DNI.jpg' title='Foto'/>";
          $directorio = $raiz."/grande/";  
		  $handle->file_new_name_body   = $DNI;
		  $handle->file_new_name_ext = 'jpg';
		  $handle->image_resize         = true;
		  $handle->image_x              = 500;
		  $handle->image_ratio_y        = true;
		  $handle->file_overwrite = true;	  
		  $handle->process($directorio);
		  if ($handle->processed) {
			  //echo 'Archivo grande subido correctamente';
			  $handle->clean();
		  } else {
			  echo "Hubo un error al intentar subir el archivo: " . $handle->error;
		  }

      } else {
          echo "Hubo un error al intentar subir el archivo: " . $handle->error;
      }
  }else{
  	echo "No se puede cargar la subida de archivos.";
  }
/*}else{
//ya existe el archivo subido
	echo "<img src='fotos/chica/$DNI.jpg' title='Foto'/>";
}//*/

?>
