<?php
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$Nombre = $_POST['Nombre'];

if (!empty($Nombre))
	$sql = "SELECT * FROM EstudioEnte WHERE Ent_Nombre LIKE '%$Nombre%' ORDER BY Ent_Nombre";
else 
	$sql = "SELECT * FROM EstudioEnte ORDER BY Ent_Nombre";

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	?>
	
<script language="javascript">
$(document).ready(function(){

	$(".input_editar").hide();
	$("a[name^='ver']").click(function(evento){
		evento.preventDefault();
		var i = this.name.substr(3,10);		
		var vID = $("#Nuevo" + i).val();
		var vEtiqueta = $('#etiqueta' + i).text();
		if (vEtiqueta=="(ver)"){
			$("#cargando").show();
			$.post("cargarOpciones.php", {opcion: 'mostrarNivelEstudios', ID: vID}, function(data){
				$('#div' + i).html(data);
				$('#etiqueta' + i).text("(ocultar)");
				$("#cargando").hide();
			});
			/*$('#div' + i).load("mostrarNivelEstudios.php", {opcion: 'mostrarNivelEstudios', ID: vID});
			$('#etiqueta' + i).text("(ocultar)");
			$("#cargando").hide();//*/
		}else{
			$('#etiqueta' + i).text("(ver)");
			$('#div' + i).empty();
		}
	 });//fin evento click//*/
	 
	 $("input[id^='editar']").keyup(function(evento){
	 	if (evento.keyCode == 13){
			var i = this.id.substr(6,10);
			guardarNombre(i);
		}
	 });
	 $("input[id^='editar']").focusout(function(evento){
		var i = this.id.substr(6,10);
		guardarNombre(i);
	 });
	 function guardarNombre(i){
		var vNombre = $("#editar" + i).val();
		var vID = $("#Nuevo" + i).val();
		vNombre1 = $("#nombreEtiqueta" + i).text();
		vNombre2 = $("#editar" + i).val();
		if (vNombre1 != vNombre2){
			$.post("cargarOpciones.php", { opcion: "guardarEntidadEducativa", Nombre: vNombre, ID: vID },function(data){
			if (data=='Ya existe')
				alert("El nombre que intenta ingresar ya existe.");
			else
				$("#nombreEtiqueta" + i).text(data);
			});
		}//fin if
		$("#nombreEtiqueta" + i).show();
		$("#editar" + i).hide();
	 
	 }//fin funcion
	 $("span[id^='nombreEtiqueta']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(14,10);		
		$("#nombreEtiqueta" + i).hide();
		$("#editar" + i).val($("#nombreEtiqueta" + i).text());
		$("#editar" + i).show();
		$("#editar" + i).focus();
	 });//fin evento click//*/	 
 
 	$("#barraEliminar").click(function(evento){
		evento.preventDefault();	
		 $(":checked").each(function(i){
		 	var i = this.id.substr(5,10);
			vID = $(this).val();
			vNombre = $("#nombreEtiqueta" + i).text();
			//jAlert(vNombre + ' - ' + i);
			jConfirm('¿Está seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarEntidadEducativa", ID: vID }, function(data){
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
});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />

<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
<div id="listado">	
 <table width="100%" border="0">
    <tr>
      <td class="fila_titulo" width="90">&nbsp;</td>
      <td class="fila_titulo"><div align="left">Nombre de la Entidad Educativa </div></td>
      <td class="fila_titulo"><div align="left">Cantidad de Niveles de Estudio </div></td>
    </tr>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		$totales = buscarNivelEstudiosTotal($row[Ent_ID]);
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>">
      <td><input name="Nuevo<?php echo $i;?>" type="checkbox" id="Nuevo<?php echo $i;?>" value="<?php echo $row[Ent_ID];?>"></td>
      <td><span id="nombreEtiqueta<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre"><?php echo $row[Ent_Nombre];?></span>
      <input name="editar<?php echo $i;?>" type="text" class="input_editar" id="editar<?php echo $i;?>" value="<?php echo $row[Ent_Nombre];?>" size="30" /></td>
      
      <td><?php echo $totales;
	  if ($totales>0){
	  ?> <a href="#" name="ver<?php echo $i;?>"><span id="etiqueta<?php echo $i;?>">(ver)</span></a><div id="div<?php echo $i;?>"></div>
	  <?php
	  }
	  ?>	  </td>
    </tr>
		  <?php		  
	}//fin del while
	?>  
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior">
<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se econtraron $total entidades educativas";?>
</fieldset>	
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron entidades educativas asociadas a la búsqueda.</span>
<?php
}
?>
