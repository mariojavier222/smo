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
	$(".botones").button();

 	$("#barraSinIdentificar").click(function(evento){
		evento.preventDefault();		
		$("#mostrarTabla").show();	

		$.ajax({
			type: "POST",
            cache: false,
            async: false,			
			url: 'opcionesTransferencias.php',
			data: {opcion: 'cargarSinIdentificar'},
			success: function (data){
				$("#mostrarTabla").html(data);
			}
		});		
	});
	$("#barraVinculadas").click(function(evento){
		evento.preventDefault();		
		$("#mostrarTabla").show();	

		$.ajax({
			type: "POST",
            cache: false,
            async: false,			
			url: 'opcionesTransferencias.php',
			data: {opcion: 'cargarVinculadas'},
			success: function (data){
				$("#mostrarTabla").html(data);
			}
		});		
	});

	$("#barraAnuladas").click(function(evento){
		evento.preventDefault();		
		$("#mostrarTabla").show();	

		$.ajax({
			type: "POST",
            cache: false,
            async: false,			
			url: 'opcionesTransferencias.php',
			data: {opcion: 'cargarAnuladas'},
			success: function (data){
				$("#mostrarTabla").html(data);
			}
		});		
	});

	$("#barraImputadas").click(function(evento){
		evento.preventDefault();		
		$("#mostrarTabla").show();	

		$.ajax({
			type: "POST",
            cache: false,
            async: false,			
			url: 'opcionesTransferencias.php',
			data: {opcion: 'cargarImputadas'},
			success: function (data){
				$("#mostrarTabla").html(data);
			}
		});		
	});



	/*$("#barraVolver").click(function(evento){
        evento.preventDefault();
        vDNI = $("#DNI_Volver").val();
        //alert(vDNI.length);
        if (vDNI.length>0){
            $.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarInscripcionLectivo.php',
                data: {DNI: vDNI},
                success: function (data){
                    $("#principal").html(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        }//fin if
    });*/
});

</script>

<?php

?>

<div id="cargaArchivo">
	<table width="98%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td><div align="center" class="titulo_noticia"> Listado de transferencias cargadas</div></td>
        </tr>
        <tr>
        	<td align="center" class="texto">
        		<button class="botones" id="barraSinIdentificar">Sin identificar</button> 
        		<button class="botones" id="barraImputadas">Imputadas</button> 
        		<button class="botones" id="barraVinculadas">Vinculadas</button> 
        		<button class="botones" id="barraAnuladas">Anuladas</button> 
        	</td>
        </tr>
    </table>
</div>
	
<div id="mostrarTabla">

</div>
