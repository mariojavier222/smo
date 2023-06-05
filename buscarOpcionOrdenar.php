<?php
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("listas.php");



?>
	
<script language="javascript">
$(document).ready(function(){

	$(".input_editar").hide();
 
	 
	 function cargarOpcionOrden(){
		alert("");
		$("#mostrar").load("buscarOpcionOrdenar.php");
		
	}
 	$("#barraEliminar").click(function(evento){
		evento.preventDefault();	
		 $(":checked").each(function(i){
		 	var i = this.id.substr(5,10);
			vID = $(this).val();
			vNombre = $("#nombrePais" + i).text();
			//jAlert(vNombre + ' - ' + i);
			jConfirm('¿Está seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarPais", ID: vID }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).remove();
					});//fin post					
				}//fin if
			});//fin del confirm//*/

		 });//fin del nombre seleccionado//
	});//fin del eliminar


	//marcar y desmarcar todo
	$("#marcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").attr('checked', 'checked');
	});
	$("#desmarcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").attr('checked', '');
	}); 
	$("#MenuID").change(function () {
   		$("#MenuID option:selected").each(function () {
			//alert($(this).val());
				vMenu=$(this).val();
				llenarOpcionesMenu(vMenu);
        });
    });
    function llenarOpcionesMenu(vMenu){
		$.post("cargarOpciones.php", { opcion: 'llenarOpcionesTabla', Menu: vMenu },	function(data){
     			$("#listado").html(data);
				$(".input_editar").hide();
   		});
	}//*/
	llenarOpcionesMenu($("#MenuID").val());
});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />

Filtrar  Men&uacute;: <?php echo cargarListaMenu("MenuID");?>
<div id="listado">	

</div>
