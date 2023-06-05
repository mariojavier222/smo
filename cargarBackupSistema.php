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
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />

<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

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
        mostrarBackups();
		function mostrarBackups(){
			
			$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "mostrarBackups"},
                    success: function (data){
                        $("#mostrar").html(data);
                        $("#loading").hide();
                    }
                });//fin ajax
		}//fin function
		
	 $("#loading").hide();	
    });//fin de la funcion ready


</script>


<div id="mostrarCuotas">

    <p>&nbsp;</p>
    <table width="95%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td width="50">		
                <div align="center" class="titulo_noticia"><img src="iconos/mod_backup.png" width="32" height="32" align="absmiddle" /> Realizar un Backup del Sistema</div></td>
        </tr>
        
        <tr>
          <td align="center" class="texto"><fieldset style="width:auto">
          <legend></legend>
          <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr class="texto">
              <td align="right">Tipo de Backup:</td>
              <td><select name="tipoBackup" size="1" id="tipoBackup">
                <option value="crearBackupCompleto" selected="selected">Completo</option>             
                <option value="crearBackupWeb">Para la Web</option>
              </select></td>
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
            <button class="botones" id="barraMostrar">Comenzar Backup</button>
            <img id="loading" src="iconos/loading.gif" width="31" height="31" style="vertical-align:middle" />
            </td>
        </tr>
        <tr>
            <td align="center" class="texto"><div id="mostrar"></div></td>
        </tr>
    </table>

</div>

<p>&nbsp;</p>
