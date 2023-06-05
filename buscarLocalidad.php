<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
//sleep(3);

$opcion = $_POST['opcion'];
//echo "MAL";exit;
if ($opcion=="cargarLocalidad"){
	$Pais = $_POST['Pais'];
	$Prov = $_POST['Prov'];
	//echo "$Pais-$Prov";exit;
	cargarListaLocalidad2("LocID", $Pais, $Prov);
	exit;
}
if ($opcion=="cargarProvincia"){
	$Pais = $_POST['Pais'];
	//echo "$Pais-$Prov";exit;
	cargarListaProvincia2("ProID", $Pais);
	exit;
}
$sql = "SET NAMES UTF8";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$Nombre = $_POST['Nombre'];
$Nombre = utf8_decode($Nombre);
if (!empty($Nombre)){
	$sql = "SELECT
    Loc_Nombre
	, Pro_Nombre
    , Pai_Nombre
    , Loc_Pro_ID
    , Loc_Pai_ID
	, Loc_ID
FROM
    Localidad
    INNER JOIN Pais 
        ON (Loc_Pai_ID = Pai_ID)
		INNER JOIN Provincia 
        ON (Loc_Pai_ID = Pai_ID AND Loc_Pro_ID = Pro_ID) WHERE Loc_Nombre LIKE '%$Nombre%' ORDER BY Loc_Nombre, Pai_Nombre, Pro_Nombre";
}else{
	$sql = "SELECT
    Loc_Nombre
	, Pro_Nombre
    , Pai_Nombre
    , Loc_Pro_ID
    , Loc_Pai_ID
	, Loc_ID
FROM
    Localidad
    INNER JOIN Pais 
        ON (Loc_Pai_ID = Pai_ID)
		INNER JOIN Provincia 
        ON (Loc_Pai_ID = Pai_ID AND Loc_Pro_ID = Pro_ID) ORDER BY Loc_Nombre, Pai_Nombre, Pro_Nombre";
}
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	?>
<script language="javascript">
$(document).ready(function(){
	
	$(".input_editar").hide();
	
	function guardarNombre(i){
		var vNombre = $("#editar" + i).val();
		var vID = $("#Loc_" + i).val();
		var vPais = $("#Pais" + i).val();
		var vProv = $("#Prov" + i).val();
		vNombre1 = $("#nombreProv" + i).text();
		vNombre2 = $("#editar" + i).val();
		if (vNombre1 != vNombre2){
			$.post("cargarOpciones.php", { opcion: "guardarLocalidad", Nombre: vNombre, Pais: vPais, Prov: vProv, ID: vID },function(data){
				if (data=='Ya existe')
					alert("El nombre que intenta ingresar ya existe.");
				else
					$("#nombreProv" + i).text(data);
			});
		}//fin if
		$("#nombreProv" + i).show();
		$("#editar" + i).hide();

	}
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

	 $("span[id^='nombreProv']").click(function(evento){
		evento.preventDefault();
		var i = this.id.substr(10,10);		
		$(this).hide();
		$("#editar" + i).val($("#nombreProv" + i).text());
		$("#editar" + i).show();
		$("#editar" + i).focus();

	 });//fin evento click//*/
  	$("#barraEliminar").click(function(evento){
		evento.preventDefault();	
		 $(":checked").each(function(i){
		 	var i = this.id.substr(4,10);
			vID = $(this).val();
			vPais = $("#Pais" + i).val();
			vProv = $("#Prov" + i).val();
			vNombre = $("#nombreProv" + i).text();
			//jAlert(vNombre + ' - ' + i);
			jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarLocalidad", ID: vID, Pais: vPais, Prov: vProv }, function(data){
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
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado">	
 <table width="100%" border="0">
    <tr>
      <td class="fila_titulo" width="90">&nbsp;</td>
      <td class="fila_titulo"><div align="left">Nombre de la Localidad </div></td>      
      <td class="fila_titulo"><div align="left">Pertenece a la Provincia  </div></td>
	  <td class="fila_titulo"><div align="left">Pertenece al Pa&iacute;s </div></td>
    </tr>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>">
      <td><input name="Loc_<?php echo $i;?>" type="checkbox" id="Loc_<?php echo $i;?>" value="<?php echo $row[Loc_ID];?>">
	  <input id="Pais<?php echo $i;?>" name="Pais<?php echo $i;?>" type="hidden" value="<?php echo $row[Loc_Pai_ID];?>" /><input id="Prov<?php echo $i;?>" name="Prov<?php echo $i;?>" type="hidden" value="<?php echo $row[Loc_Pro_ID];?>" />
	  </td>
      <td><span id="nombreProv<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre"><?php echo $row[Loc_Nombre];?></span> <input name="editar<?php echo $i;?>" type="text" id="editar<?php echo $i;?>" value="<?php echo $row[Loc_Nombre];?>" class="input_editar" /></td>      
      <td><?php echo $row[Pro_Nombre];?>  </td>
	  <td><?php echo $row[Pai_Nombre];?> </td>	  
    </tr>
		  <?php		  
	}//fin del while
	?>  
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior">
<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se econtraron $total localidades";?>
</fieldset>	
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron provincias asociados a la b&uacute;squeda.</span>
<?php
}


?>
