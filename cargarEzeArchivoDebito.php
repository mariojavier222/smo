<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">

$(document).ready(function(){

	$("#mostrarTabla").hide();

 	$("#subirArchivo").click(function(evento){
		evento.preventDefault();
		$("#cargaArchivo").hide();
		$("#mostrarTabla").show();
		var formData = new FormData();
		formData.append('file', $('#file')[0].files[0]);

		$.ajax({
			type: "POST",
			async: false,
			processData: false,
       		contentType: false,
			url: 'funcionEzeArchivo.php',
			data: formData,
			success: function (data){
				$("#mostrarTabla").html(data);
			}
		});
		$('table.tabla tbody tr:odd').addClass('fila');
		$('table.tabla tbody tr:even').addClass('fila2');
	});
});

</script>

<?php

?>

<div id="cargaArchivo">
	<table width="98%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td><div align="center" class="titulo_noticia"> Cargar Archivo</div></td>
        </tr>
        <tr>
        	<td align="center" class="texto">
        		<h3> Seleccione el archivo a importar </h3>
        		<input size='50' type='file' name='file' id='file' />
        		<input type='submit' name='subirArchivo' id='subirArchivo' value='Importar' />
        	</td>
        </tr>
    </table>
</div>
	
<div id="mostrarTabla">

</div>
