<?php
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$DNI = $_POST['DNI'];
$DNI2 = $_POST['DNI2'];
$Apellido = $_POST['Apellido'];

if (!empty($DNI))
	$sql = "SELECT DISTINCTROW * FROM Persona, PersonaDocumento WHERE Per_DNI = $DNI AND Per_Doc_ID=Doc_ID";
else 
	$sql = "SELECT DISTINCTROW * FROM Persona, PersonaDocumento WHERE Per_Apellido like '$Apellido%' AND Per_Doc_ID=Doc_ID ORDER BY Per_Apellido";
	
if (!empty($DNI2))
	$sql = "SELECT DISTINCTROW * FROM Persona, PersonaDocumento WHERE Per_DNI = $DNI2 AND Per_Doc_ID=Doc_ID";
	

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0){	
	?>
<script language="javascript">
$(document).ready(function(){

 $("input[name^='chk']").click(function(){
 	$("#cargando").show();
	
	$("#mostrarEditar").show();
	var valor = this.value;
	if (this.checked){
		//muestro para editar
		$("#mostrarEditar").append("<div id='Editar" + valor + "'></div>");
		
		$.post("PersonaEditar.php", {Per_ID: valor}, function(data){
				$("#mostrarBuscador").hide();
				$("#mostrarEditar").show();
				$("#Editar" + valor).html(data);
				$("#Editar" + valor + " .error_buscador").hide();
				//alert("Hola " + this.value);			
				$("#cargando").hide();
		
		});	//fin load//*/
	}else{
	//oculto y elimino para editar
		$("#Editar" + valor).remove();
	}

	$("#cargando").hide();
 });//fin input click
 
 $('#listadoTabla').dataTable( {
			"bPaginate": true,
			//"aaSorting": [[ 2, "asc" ], [ 3, "asc" ]],
			"aLengthMenu": [[15, 25, 50, 100, 200, -1], [15, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true }
	);//*/
 
});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />

<fieldset class="recuadro_simple texto" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" class="texto">	
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
    <tr>
      <th class="fila_titulo" width="90">&nbsp;</th>
      <th class="fila_titulo" width="120"><div align="left">Documento</div></th>
      <th class="fila_titulo"><div align="left">Apellido</div></th>
      <th class="fila_titulo"><div align="left">Nombre</div></th>
      <th class="fila_titulo"><div align="left">Sexo</div></th>
    </tr>
    </thead>
    <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){
		$foto = buscarFoto($row[Per_DNI], $row[Per_Doc_ID], 60);
		$i++;
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="gradeA <?php echo $clase;?>">
      <td><input name="chkPer_<?php echo $row[Per_ID];?>" type="checkbox" id="chkPer_<?php echo $row[Per_ID];?>" value="<?php echo $row[Per_ID];?>"><?php echo $foto;?></td>
      <td><?php echo "$row[Doc_Nombre] $row[Per_DNI]";?> </td>
      <td><?php echo $row[Per_Apellido];?> </td>
      <td><?php echo $row[Per_Nombre];?> </td>
      <td><?php echo $row[Per_Sexo];?></td>
    </tr>
		  <?php		  
	}//fin del while
	?>  
    </tbody>
    <tfoot>
    <tr>
      <th colspan="5" class="fila_titulo" height="2"></th>
      </tr>
    </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior">
<?php echo "Se econtraron $total personas";?>
</fieldset>	
<?php
}else{
	echo "No se encontraron personas asociadas a la búsqueda";
}
?>
