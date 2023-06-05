<?php
require_once "upload/class.upload.php"; //Incluyes la clase
require("funciones_generales.php");

//ini_set("memory_limit","20M");
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



$PerID = $_POST['PerID'];
$ancho = getWidth($_FILES['image_foto']["tmp_name"]);
$nombre = strtotime(date('Y-m-d H:i:s'));
//echo $nombre;exit;
if ($ancho > 800) $ancho = 800;
//echo $_SERVER['DOCUMENT_ROOT'];exit;
//echo $ancho;exit;
//$xalto = $alto * 0.3;


//$raiz = $_SERVER['DOCUMENT_ROOT']."/sta/fotos";
$raiz = $_SERVER['DOCUMENT_ROOT']."/local/cesap/fotos/";


if ($_FILES['image_foto']){
	$handle = new upload($_FILES['image_foto'], 'es_ES');
	  if ($handle->uploaded) {		  
		  $handle->file_new_name_body   = $nombre;//$_FILES['image_field'];
		  $handle->file_new_name_ext = 'jpg';
		  $handle->image_resize         = true;
		  $handle->image_x              = $ancho;
		  $handle->image_ratio_y        = true;		  
		  $handle->file_overwrite = true;	  
		  /*$handle->process();//*/
		  $handle->process($raiz."/temp/");
		  if ($handle->processed) {
			  //echo "Foto subida correctamente<br />";
			  ?>
              <script language="javascript" src="js/jquery.js"></script>
			  <script language="javascript" src="js/jquery-ui-1.8.9.custom.min.js"></script>

			  <script language="javascript">
	              $.ajax({
					  type: "POST",
					  cache: false,
					  async: false,
					  //data: {sesion_usuario: usuario, sesion_clave: clave},
					  url: 'vista_previa_foto.php?foto=<?php echo $nombre;?>&PerID=<?php echo $PerID;?>',
					  success: function(data){
						  $("#principal").html(data);
						  $("#cargando").hide();
						 
					  }//fin success
					});//fin ajax
				  //document.location = "vista_previa_foto.php?foto=<?php echo $nombre;?>&PerID=<?php echo $PerID;?>";
			  </script>
              <?php
			  //header("Location:vista_previa_foto.php?foto=$nombre&PerID=$PerID");
		  } else {
			  echo 'Error : ' . $handle->error;
		  }
	  }
}else{
	echo "Vamos mal";
}//fin if si existe 


?>
