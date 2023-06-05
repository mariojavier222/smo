<?php
// 23012013 10 h

require_once("conexion.php");
require_once("funciones_generales.php");
consulta_mysql("SET NAMES utf8");

$Apellido = $_POST['Apellido'];
$sql = "SELECT DISTINCTROW * FROM Persona WHERE Per_Apellido like '$Apellido%' ORDER BY Per_Apellido, Per_Nombre";
//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);
if ($total>0){	

?>

<script type="text/javascript" >
$(document).ready(function(){
	// para editar 
	 $("a[id^='edi']").click(function(evento){
		 
	    evento.preventDefault();
		vID = this.id.substr(3,10);
		//vDNI=
		vApellido=$("#Per_Apellido").val();
		//vNombre
		alert(vApellido);
		$("#nuevaEntrevista").show();
		
		$("#Apellidos").val(vApellido);
		
		
       	
	});// fin editar
	
  
});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />

<fieldset class="recuadro_simple texto" id="resultado_buscador">
 <legend>Resultado de la b&uacute;squeda</legend>
 
 <div id="listado" class="texto">	
 
 <table width="80%" border="0" id="listadoTabla" class="display">
    <thead>
    <tr>
      <th class="fila_titulo" width="120"><div align="left">Documento</div></th>
      <th class="fila_titulo"><div align="left">Apellido</div></th>
      <th class="fila_titulo"><div align="left">Nombre</div></th>
      <th class="fila_titulo"><div align="left">Sexo</div></th>
      <th class="fila_titulo"><div align="left">Editar</div></th>
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
    
    
    
      <td><?php echo "$row[Doc_Nombre] $row[Per_DNI]";?> </td>
      <td id="Per_Apellido"><?php echo $row[Per_Apellido];?> </td>
      <td><?php echo $row[Per_Nombre];?> </td>
      <td><?php echo $row[Per_Sexo];?></td>
      <td><a href="#" id="edi<?php echo $row[Per_ID];?>"> Editar </a></td>
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
