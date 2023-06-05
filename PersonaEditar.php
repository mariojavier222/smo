<?php
require_once("conexion.php");
require_once("listas.php");
//sleep(3);
$PerID = $_POST['Per_ID'];
$sql = "SELECT * FROM Persona, PersonaDocumento WHERE Per_ID = $PerID AND Per_Doc_ID=Doc_ID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);

?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<!--<script src="js/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script language="javascript" src="ValidarPersonaEditar.js" type="text/javascript"></script>	
-->

<script language="javascript">
$(document).ready(function(){
						   
 $("a[name^='div_']").click(function(evento){
 	evento.preventDefault();
	var nombre = this.name.substr(4,10);
 	$("#Editar" + nombre).remove();
	$("#chkPer_" + nombre).attr("checked",false);
 });//fin evento click//*/
 
	$('#form_<?php echo $PerID;?> #Sexo').attr('value','<?php echo $row[Per_Sexo];?>');
	$('#form_<?php echo $PerID;?> #Extranjero').val('<?php echo $row[Per_Extranjero];?>');
	$('#form_<?php echo $PerID;?> #DocID').val('<?php echo $row[Doc_ID];?>');
	
	$("#form_<?php echo $PerID;?>").validate({
		submitHandler: function(form) {   	
			$(form).ajaxForm(opciones_editar);
			return false;
		}
	});
	
	var valorPerID = "#form_<?php echo $PerID;?> ";
	var opciones_editar= {
					   beforeSubmit: validarFormEditar,
					   success: mostrarRespuestaEditar 
	};
//lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarFormEditar(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];
			
    		if ((form.Sexo.value=="-1") ) {
				$(valorPerID + "#errorNuevoSexo").show();
				$("#cargando").fadeOut();
				return false;
			}else $(valorPerID + "#errorNuevoSexo").hide();
			
    		if ((form.Extranjero.value=="1") ) {
	    		if ((!form.Alternativo.value) ) {
					$(valorPerID + "#errorNuevoAlternativo").show();
					$("#cargando").fadeOut();
					return false;
				}
			}else $(valorPerID + "#errorNuevoAlternativo").hide();

			$(valorPerID + "#errorNuevoDNI").hide();
			$(valorPerID + "#errorNuevoApellido").hide();
			$(valorPerID + "#errorNuevoNombre").hide();
			$(valorPerID + "#errorNuevoSexo").hide();
			$(valorPerID + "#errorNuevoAlternativo").hide();
			$(valorPerID + "#cargando").fadeOut();
			//return true;

	 };

	 function mostrarRespuestaEditar (responseText){
		  $("#Editar<?php echo $PerID;?>").html(responseText);
		  $("#chkPer_<?php echo $PerID;?>").attr("checked",false);
		  $("#cargando").fadeOut();
		  
  		  $("#Editar<?php echo $PerID;?>").delay(4000).fadeOut(2000);
	 };
//Evento que se ejecuta cuando se carga la página
	$(valorPerID + '#lista').load("cargar_foto.php", {DNI: $(valorPerID + "#DNI").get(0).value, DocID: $(valorPerID + "#DocID").get(0).value});

//*/
//Script para subir la foto
    var button = $(valorPerID + '#upload_button<?php echo $PerID;?>'), interval;
    var upload = new AjaxUpload('#upload_button<?php echo $PerID;?>', {
        action: 'subirArchivo.php',	
        onSubmit : function(file , ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
            // extensiones permitidas
            alert('Error: Solo se permiten imagenes');
            // cancela upload
            return false;
        } else {
            var valor = $(valorPerID + "#DNI").get(0).value;
			if (valor.length==0){
				alert('Error: Escriba un número de documento antes de subir un archivo:');
				return false;
			}else{
				//Cambio el texto del boton y lo deshabilito
				var vDNI = $(valorPerID + '#DNI').get(0).value;
				var vDocID = $(valorPerID + '#DocID').get(0).value;
				upload.setData({'DNI': vDNI, 'DocID': vDocID});
				button.text('Subiendo...');
				this.disable();
			}
        }
        },
        onComplete: function(file, response){
            button.text('Archivo subido');
			$(valorPerID + '#lista').html(response);
        }  
    });	 	
 
});//fin dom ready
</script>


	<form action="PersonaNueva.php" method="post" enctype="multipart/form-data" name="form_<?php echo $PerID;?>" id="form_<?php echo $PerID;?>">
	  <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
          <td colspan="2"><div align="center" class="titulo_noticia">
            <table width="100%" border="0">
              <tr>
                <td class="titulo_noticia"><div align="center">Editar una persona </div></td>
                <td width="48"><div align="right" class="barra_boton"><a href="#" id="div_<?php echo $PerID;?>" name="div_<?php echo $PerID;?>"><img src="botones/Delete.png" alt="Cerrar" width="48" height="48" border="0" /></a></div></td>
              </tr>
            </table>
            </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Tipo de Documento: </div></td>
          <td>
            <?php cargarListaTipoDoc("DocID");?>         </td>
        </tr>
        <tr>
          <td class="texto"><div align="right">N&ordm; Documento: </div></td>
          <td><input name="DNI" type="text" id="DNI" class="required digits" value="<?php echo $row[Per_DNI];?>" />
            <label class="error_buscador" id="errorNuevoDNI">falta cargar </label></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Apellidos:</div></td>
          <td><input name="Apellidos" type="text" id="Apellidos" class="required" value="<?php echo $row[Per_Apellido];?>" /> <label class="error_buscador" id="errorNuevoApellidos">falta cargar </label></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Nombres:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" class="required" value="<?php echo $row[Per_Nombre];?>"/> <label class="error_buscador" id="errorNuevoNombre">falta cargar </label></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Sexo:</div></td>
          <td><select name="Sexo" id="Sexo">
            <option value="-1">Elegir una opci&oacute;n</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
           <label class="error_buscador" id="errorNuevoSexo">debe elegir una opción </label></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Elegir una foto: </div></td>
          <td><table width="80" border="0">
              <tr>
                <td>
				<div align="center" id="upload_button<?php echo $PerID;?>" class="barra_boton" >
            <img src="imagenes/camera_add.png" alt="Subir foto" /><br />
              Subir foto</div><div id="lista"></div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">&iquest;Es extranjero? </div></td>
          <td><select name="Extranjero" id="Extranjero">
            <option value="1">Si</option>
            <option value="0" selected="selected">No</option>
          </select>
          <input name="Editar" type="hidden" id="Editar" value="si" />		<input name="PerID" type="hidden" id="PerID" value="<?php echo $PerID;?>" /></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Documento alternativo: </div></td>
          <td><input name="Alternativo" type="text" id="Alternativo" value="<?php echo $row[Per_Alternativo];?>" /><label class="error_buscador" id="errorNuevoAlternativo">falta cargar </label></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <input name="submit" type="submit" class="boton_buscador" value="Guardar" />
          </div></td>
        </tr>
      </table>
	</form><br /><br />

