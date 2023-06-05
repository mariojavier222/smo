<?php
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$Nombre = $_POST['Nombre'];

if (!empty($Nombre))
	$sql = "SELECT * FROM EstudioNivel WHERE (Niv_Tit_Mas LIKE '%$Nombre%' OR Niv_Tit_Fem LIKE '%$Nombre%') ORDER BY Niv_Tit_Mas, Niv_Tit_Fem";
else 
	$sql = "SELECT * FROM EstudioNivel ORDER BY Niv_Tit_Mas";

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
			$.post("cargarOpciones.php", {opcion: 'mostrarEntidadEducativa', ID: vID}, function(data){
				$('#div' + i).html(data);
				$('#etiqueta' + i).text("(ocultar)");
				$("#cargando").hide();
			});
		}else{
			$('#etiqueta' + i).text("(ver)");
			$('#div' + i).empty();
		}
	 });//fin evento click//*/
	 
	 $("input[id^='editarMasc']").keyup(function(evento){
	 	if (evento.keyCode == 13){
			var i = this.id.substr(10,10);
			guardarNombre(i, 'Masc', 'Niv_Tit_Mas');
		}
	 });
	 $("input[id^='editarMasc']").focusout(function(evento){
		var i = this.id.substr(10,10);
		guardarNombre(i, 'Masc', 'Niv_Tit_Mas');
	 });
	 $("input[id^='editarFem']").keyup(function(evento){
	 	if (evento.keyCode == 13){
			var i = this.id.substr(9,10);
			guardarNombre(i, 'Fem', 'Niv_Tit_Fem');
		}
	 });
	 $("input[id^='editarFem']").focusout(function(evento){
		var i = this.id.substr(9,10);
		guardarNombre(i, 'Fem', 'Niv_Tit_Fem');
	 });

	 function guardarNombre(i, pCampo, pTitulo){
		var vNombre = $("#editar" + pCampo + i).val();
		var vID = $("#Nuevo" + i).val();
		vNombre1 = $("#nombreEtiqueta" + pCampo + i).text();
		vNombre2 = $("#editar" + pCampo + i).val();
		if (vNombre1 != vNombre2){
			$.post("cargarOpciones.php", { opcion: "guardarNivelEstudio", Nombre: vNombre, ID: vID, Campo: pTitulo },function(data){
			if (data=='Ya existe')
				alert("El nombre que intenta ingresar ya existe.");
			else
				$("#nombreEtiqueta" + pCampo + i).text(data);
			});
		}//fin if
		$("#nombreEtiqueta" + pCampo + i).show();
		$("#editar" + pCampo + i).hide();
	 
	 }//fin funcion
	 $("span[id^='nombreEtiquetaMasc']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(18,10);		
		$("#nombreEtiquetaMasc" + i).hide();
		$("#editarMasc" + i).val($("#nombreEtiquetaMasc" + i).text());
		$("#editarMasc" + i).show();
		$("#editarMasc" + i).focus();
	 });//fin evento click//*/	 
	 $("span[id^='nombreEtiquetaFem']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(17,10);		
		$("#nombreEtiquetaFem" + i).hide();
		$("#editarFem" + i).val($("#nombreEtiquetaFem" + i).text());
		$("#editarFem" + i).show();
		$("#editarFem" + i).focus();
	 });//fin evento click//*/	 
 	
	$("#barraEliminar").click(function(evento){
		evento.preventDefault();	
		 $(":checked").each(function(i){
		 	var i = this.id.substr(5,10);
			vID = $(this).val();
			vNombre = $("#nombreEtiquetaMasc" + i).text() + " y " + $("#nombreEtiquetaFem" + i).text();
			//jAlert(vNombre + ' - ' + i);
			jConfirm('¿Está seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarNivelEstudio", ID: vID }, function(data){
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
      <td class="fila_titulo"><div align="left">T&iacute;tulo Masculino </div></td>
      <td class="fila_titulo">T&iacute;tulo Femenino </td>
      <td class="fila_titulo"><div align="left">Cantidad de Entidades Educativas </div></td>
    </tr>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		$totales = buscarEntidadEducativaTotal($row[Niv_ID]);
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>">
      <td><input name="Nuevo<?php echo $i;?>" type="checkbox" id="Nuevo<?php echo $i;?>" value="<?php echo $row[Niv_ID];?>"></td>
      <td><span id="nombreEtiquetaMasc<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre"><?php echo $row[Niv_Tit_Mas];?></span>
      <input name="editarMasc<?php echo $i;?>" type="text" class="input_editar" id="editarMasc<?php echo $i;?>" value="<?php echo $row[Niv_Tit_Mas];?>" size="30" /></td>
      
      <td><span id="nombreEtiquetaFem<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre"><?php echo $row[Niv_Tit_Fem];?></span>
      <input name="editarFem<?php echo $i;?>" type="text" class="input_editar" id="editarFem<?php echo $i;?>" value="<?php echo $row[Niv_Tit_Fem];?>" size="30" /></td>
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
<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se econtraron $total niveles de estudio";?>
</fieldset>	
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron niveles de estudio asociados a la búsqueda.</span>
<?php
}
?>
