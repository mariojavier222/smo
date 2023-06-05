<?php
header("Cache-Control: no-cache, must-revalidate"); 
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql = "SELECT * FROM Menu WHERE Men_Orden <> 99";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)==0){
	$i=0;
	$sql = "SELECT * FROM Menu ORDER BY Men_Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ( $row = mysqli_fetch_array($result) ){		
		$i++;
		$sql = "UPDATE Menu SET Men_Orden = $i WHERE Men_ID = $row[Men_ID]";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}//fin while
}
$sql = "SELECT * FROM Menu ORDER BY Men_Orden";

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
			$.post("cargarOpciones.php", {opcion: "mostrarMenuOpciones", ID: vID}, function(data){
				$('#div' + i).html(data);	
			});
			$('#etiqueta' + i).text("(ocultar)");
			$("#cargando").hide();
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
		vNombre1 = $("#nombrePais" + i).text();
		vNombre2 = $("#editar" + i).val();
		if (vNombre1 != vNombre2){
			$.post("cargarOpciones.php", { opcion: "guardarMenu", Nombre: vNombre, ID: vID },function(data){
			if (data=='Ya existe')
				mostrarAlerta("El nombre que intenta ingresar ya existe.", "");
			else
				$("#nombrePais" + i).text(data);
			});
		}//fin if
		$("#nombrePais" + i).show();
		$("#editar" + i).hide();
	 
	 }//fin funcion
	 $("span[id^='nombrePais']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(10,10);		
		$("#nombrePais" + i).hide();
		$("#editar" + i).val($("#nombrePais" + i).text());
		$("#editar" + i).show();
		$("#editar" + i).focus();
	 });//fin evento click//*/	 
  	$("img[id^='arriba']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(6,10);
		//alert(i);
		vMenID = $("#Nuevo" + i).val();
		vOrden = $("#Orden" + i).val();
		$.post("cargarOpciones.php", {opcion: "ordenarMenuArriba", MenID: vMenID, Orden: vOrden}, function(){
			cargarMenuOrden();
		});
	 });//fin evento click//*/	
  	$("img[id^='abajo']").click(function(evento){
		evento.preventDefault();		
		var i = this.id.substr(5,10);
		//alert(i);
		vMenID = $("#Nuevo" + i).val();
		vOrden = $("#Orden" + i).val();
		$.post("cargarOpciones.php", {opcion: "ordenarMenuAbajo", MenID: vMenID, Orden: vOrden}, function(){
			cargarMenuOrden();
		});
	 });//fin evento click//*/		 
	 function cargarMenuOrden(){
		$("#cargando").show();
		$("#mostrar").load("buscarMenuOrdenar.php");
		$("#cargando").hide();
		
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
});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />

<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la búsqueda</legend>
<div id="listado">	
 <table width="100%" border="0">
    <tr>
      <td class="fila_titulo" width="90">&nbsp;</td>
      <td class="fila_titulo" width="90"><div align="center">Orden</div></td>
      <td class="fila_titulo"><div align="left">Nombre del Men&uacute; </div></td>
      <td class="fila_titulo"><div align="left">Cantidad de Opciones </div></td>
    </tr>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		$total_opciones = buscarMenuOpcionesTotal($row[Men_ID]);
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>">
      <td><input name="Nuevo<?php echo $i;?>" type="checkbox" id="Nuevo<?php echo $i;?>" value="<?php echo $row[Men_ID];?>"><input type="hidden" id="Orden<?php echo $i;?>" value="<?php echo $row[Men_Orden];?>">
      <?php
	  if ($i==1) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	  if ($i>1){
	  ?><img id="arriba<?php echo $i;?>" src="botones/orden_arriba.gif" alt="Mover arriba" title="Mover arriba" width="20" height="20" style="cursor:pointer" />
	  <?php
	  }//fin if
	  if ($i<$total){
	  ?>
	  <img id="abajo<?php echo $i;?>" src="botones/orden_abajo.gif" alt="Mover abajo" title="Mover abajo" style="cursor:pointer" width="20" height="20" />
	  <?php
	  }//fin if
	  ?>	  </td>
      <td><div align="center"><?php echo $row[Men_Orden];?></div></td>
      <td><span id="nombrePais<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre"><?php echo $row[Men_Nombre];?></span>
      <input name="editar<?php echo $i;?>" type="text" id="editar<?php echo $i;?>" value="<?php echo $row[Men_Nombre];?>" class="input_editar" /></td>
      
      <td><?php echo $total_opciones;
	  if ($total_opciones>0){
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
<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se econtraron $total menúes cargados";?>
</fieldset>	
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron menúes asociados a la búsqueda.</span>
<?php
}
?>
