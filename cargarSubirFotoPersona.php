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
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<?php
$Usuario = $_SESSION['sesion_usuario'];


?>
<script language="javascript">
$(document).ready(function(){
	
	$("#PersonaDatos").hide();
	function cargarDNI(){
		vDNI = $("#DNI").val();
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerApellidoNombre", DNI: vDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#persona").val(data);
				//alert("no entre");
			}
		});//fin ajax//*/
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarDNI", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#PersonaDatos").show();
				$("#PersonaDatos").html(data);
				//alert("no entre");
			}
		});//fin ajax//*/
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarPerID", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#PerID").val(data);				
				validarPadre(data)
				//alert("no entre");
			}
		});//fin ajax//*/

	}
	function validarPadre(Per_ID){
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "validarPadreDatos", Per_ID: Per_ID},
			url: 'cargarOpciones.php',
			success: function(data){ 				
				if (data)
					$("#noTienePadreAsociado").html(data);
				//alert(data);
			}
		});//fin ajax//*/
	}
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		//alert("Enter");
		if (evento.keyCode == '13'){
			
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosPersona.php", {
		//multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,		
		formatItem:function(item, index, total, query){
		   return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatMatch:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatResult:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },

		dataType: "json",
		//parse: prep_data,
		parse:function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.Per_Apellido,
					result: row.Per_Apellido + ", " + row.Per_Nombre
				}
				$("#cargando").hide();
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	
	function colocarValor(event, data, formatted) {
		if (data){
			$("#DNI").val(data.Per_DNI);
			$("#PerID").val(data.Per_ID);
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarDNI", DNI: data.Per_DNI},
				url: 'cargarOpciones.php',
				success: function(data){ 
					$("#PersonaDatos").show();
					$("#PersonaDatos").html(data);
					//alert("no entre");
				}
			});//fin ajax//*/			
			validarPadre(data.Per_ID)
			$("#mostrar").empty();
		}
	}
	
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
        	alert(response);return;
			if (response=='error'){
				mostrarAlerta("La foto que intenta subir tiene un tama&ntilde;o mayor al permitido. Aseg&uacute;erese de el ancho de la foto no supere los 1600 p&iacute;xeles y/o que altura sea menor a 1200 p&iacute;xeles", "Error al intentar subir la foto");
				return;
			}
			button.text('Archivo subido');
			//alert(file);
			//alert(response);
			//$('#lista').html(response);
			Usuario = $("#DNI").val();;
			$.ajax({
			  type: "POST",
			  cache: false,
			  async: false,
			  data: {foto: response, Usuario: Usuario},
			  url: 'vista_previa_foto_usuario.php',
			  success: function(data){
				  $("#principal").html(data);
				  //$("#mostrar").html(data);
				  $("#cargando").hide();
				 
			  }//fin success
			});//fin ajax//*/
        }  
    });
//fin de subir foto
});//fin de la funcion ready

</script>
<div align="center"> <span class="titulo_noticia"><img src="imagenes/camera_add.png" alt="" width="32" height="32" align="absmiddle" /> Subir Foto de una Persona</span><br />
  <br />
</div>
<form action="subir_foto_temporal.php" method="post" enctype="multipart/form-data" name="photo" id="photo">
  <p>&nbsp;</p>
  <table border="0">       
	  <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" />
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" /> <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/></td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona:</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="50" />
          </td>
      </tr>
      <tr>
        <td colspan="2" class="texto">        
        <div id="PersonaDatos"></div></td>
      </tr>       
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
<div id="mostrar"></div>