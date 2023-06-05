<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

//sleep(3);
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
$Usuario = $_SESSION['sesion_usuario'];
//$DNI = "18618965";//Ivana
//$DNI = "18495898";//Ricardo



?>
<script language="javascript">
$(document).ready(function(){
	
	$("#botCargar").button();
	$("#botCargar").click(function(evento){
		evento.preventDefault();
		vPerID = $("#PerID").val();
		vImage = $("#image_foto").val();
		
		if (vPerID == -1){
			mostrarAlerta("Debe seleccionar uno de sus hijos/as","Atenci&oacute;n");
			return false;
		}
		
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

		$("#photo").submit();

	});//fin del click

	$(".botones").button();
	//Script para subir la foto
    var button = $('#upload_button'), interval;
    var upload = new AjaxUpload('#upload_button', {
        action: 'subirArchivoTemporal.php',	
        onSubmit : function(file , ext){
			if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
				// extensiones permitidas
				alert('Error: Solo se permiten fotos en formato JPG, PNG o GIF');
				// cancela upload
				return false;
			} else {
				
					//Cambio el texto del boton y lo deshabilito
					//upload.setData({'PerID': vPerID});
					button.text('Subiendo foto...por favor espere');
					this.disable();
				
			}
        },
        onComplete: function(file, response){            
			if (response=='error'){
				mostrarAlerta("La foto que intenta subir tiene un tama&ntilde;o mayor al permitido. Aseg&uacute;erese de el ancho de la foto no supere los 1600 p&iacute;xeles y/o que altura sea menor a 1200 p&iacute;xeles", "Error al intentar subir la foto");
				return;
			}
			button.text('Archivo subido');
			//alert(file);
			//alert(response);
			//$('#lista').html(response);
			vUsuario = "<?php echo $_SESSION['sesion_usuario'];?>";
			$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,
			  data: {foto: response, Usuario: vUsuario},
			  url: 'vista_previa_foto_usuario.php',
			  success: function(data){
				  $("#principal").html(data);
				  $("#cargando").hide();
				 
			  }//fin success
			});//fin ajax//*/
        }  
    });
//fin de subir foto
});//fin de la funcion ready

</script>
<div align="center"> <span class="titulo_noticia"><img src="imagenes/camera_add.png" alt="" width="32" height="32" align="absmiddle" /> Subir una foto Foto m&iacute;a</span><br />
  <br />
</div>
<form action="subir_foto_temporal.php" method="post" enctype="multipart/form-data" name="photo" id="photo">
  <p>&nbsp;</p>
  <table border="0">
              
              <tr>
              <td><span class="texto">Seleccione la foto que desea cargar</span>:<br />
<span class="advertencia_sesion">(La fotograf&iacute;a no puede ser mayor a 2 MegaPixels - 1600x1200.)</span> </td>
                <td width="80">
				<div align="center" id="upload_button" class="barra_boton" >
            <img src="imagenes/camera_add.png" alt="Subir foto" /><br />
              Seleccionarr foto</div><div id="lista"></div></td>
              </tr>
            </table>
</form>
