<?php
header("Cache-Control: no-cache, must-revalidate");
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script src="uploadify/jquery.uploadify.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css">

<script language="javascript">
    $(document).ready(function(){
	
<?php
if (isset($_SESSION['sesion_UsuID']))
    echo "vUsuID = " . $_SESSION['sesion_UsuID'];
else
    echo "document.location = 'index.php'"; //*/
?>;
	
        function validarDatos(){
            tipoBackup = $("#tipoBackup").val();
			
        }

        $("#barraMostrar").click(function(evento){
		 	$("#loading").show();
            tipoBackup = $("#tipoBackup").val();
			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				//data: {tipoBackup: tipoBackup},
				url: tipoBackup + '.php',
				success: function(data){ 
					//$("#mostrar").html(data);
					$("#loading").hide();
					jAlert("Copia realizada " + data, "Resultado de realizar el Backup");
					mostrarBackups();
				}
			});//fin ajax//*/
            
        });
       
        $(".botones").button();
        mostrarRestore();
		
		
	 $("#loading").hide();	
    });//fin de la funcion ready

function mostrarRestore(){
			
			$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "mostrarRestore"},
                    success: function (data){
                        $("#mostrar").html(data);
                        $("#loading").hide();
                    }
                });//fin ajax
		}//fin function
</script>


<div id="mostrarCuotas">

    <p>&nbsp;</p>
    <table width="95%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td width="50">		
                <div align="center" class="titulo_noticia"><img src="iconos/mod_restore.png" width="32" height="32" align="absmiddle" /> Restore del Sistema</div></td>
        </tr>
        
        <tr>
          <td align="center" class="texto"><fieldset style="width:auto">
          <legend></legend>
          <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr class="texto">
              <td><form>
		<div id="queue"></div>
		<input id="file_upload" name="file_upload" type="file" multiple="true">
	</form>

	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			$('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'swf'      : 'uploadify/uploadify.swf',
				'uploader' : 'uploadify/subirRestore.php',
				'onUploadSuccess' : function(file, data, response) {
					//alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
					mostrarRestore();
				} 
			});
		});
	</script></td>
            </tr>
            <!--<tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>-->
          </table>
          </fieldset>
          </td>
        </tr>
        <tr>
            <td align="center" class="texto">
            <button class="botones" id="barraMostrar">Subir Restore</button>
            <img id="loading" src="iconos/loading.gif" width="31" height="31" style="vertical-align:middle" />
            </td>
        </tr>
        <tr>
            <td align="center" class="texto"><div id="mostrar"></div></td>
        </tr>
    </table>

</div>

<p>&nbsp;</p>
