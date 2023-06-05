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
$DNI = $_SESSION['sesion_usuario'];
//$DNI = "18618965";//Ivana
//$DNI = "18495898";//Ricardo
if (!is_numeric($DNI)){
	?>
	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible para Usted.</span></div></p><p></p>
<?php
	exit;
}

$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$usuario_nombre = $row[Per_Nombre];
	$usuario_apellido = $row[Per_Apellido];
	$PerID = $row[Per_ID];
}
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
            vPerID = $("#PerID").val();
			
			if (vPerID == -1){
				mostrarAlerta("Debe seleccionar uno de sus hijos/as","Atenci&oacute;n");
				return false;
			}else{
				//Cambio el texto del boton y lo deshabilito
				upload.setData({'PerID': vPerID});
				button.text('Subiendo foto...por favor espere');
				this.disable();
			}
        }
        },
        onComplete: function(file, response){
            button.text('Archivo subido');
			//alert(file);
			//alert(response);
			//$('#lista').html(response);
			vPerID = $("#PerID").val();
			$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,
			  data: {foto: response, PerID: vPerID},
			  url: 'vista_previa_foto.php',
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
<div align="center"> <span class="titulo_noticia"><img src="imagenes/camera_add.png" alt="" width="32" height="32" align="absmiddle" /> Subir Fotos  de mis hijos/as</span><br />
  <br />
</div>
<form action="subir_foto_temporal.php" method="post" enctype="multipart/form-data" name="photo" id="photo">
  <p><span class="texto">Seleccione el nombre de su hijo/a:</span>
<?php 

cargarListaHijosPadre("PerID", $PerID);
if (isset($_POST['PerID'])){
	$PerIDHijo = $_POST['PerID'];
	echo "<script language='javascript'>
	$('#PerID').val($PerIDHijo);
	</script>";
}?>
  </p>
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
