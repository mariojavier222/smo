<?php
//23012013 10 h

require_once("conexion.php");
require_once("funciones_generales.php");
consulta_mysql("SET NAMES utf8");
$Apellido = $_POST['Apellido'];
$LecID = $_POST['LecID'];
$sql = "SELECT DISTINCTROW * FROM Persona WHERE Per_Apellido like '$Apellido%' ORDER BY Per_Apellido, Per_Nombre";

$sql="SELECT DISTINCTROW * FROM Persona
    INNER JOIN Aspirante 
        ON (Persona.Per_ID = Aspirante.Asp_Per_ID)
        WHERE Per_Apellido like '$Apellido%' AND Asp_Lec_ID = $LecID ORDER BY Per_Apellido, Per_Nombre";
		
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
       	$.ajax({
				type: "POST",
				cache: false,
				async: false,
				url:'editarDatosAspirante.php',// 'PersonaEditar.php',	
				data: {PerID: vID},
				success: function(data){
					  $("#listadoPersonas").hide();
					  $("#editar").html(data);
					  $("#editar").show();
				}//fin success
			});//fin ajax//
	});//fin click eliminar
	// fin editar

// para editar la entrevista
	 $("a[id^='ent']").click(function(evento){
	    evento.preventDefault();
		vID = this.id.substr(3,10);
       	$.ajax({
				type: "POST",
				cache: false,
				async: false,
				url:'editarDatosEntrevista.php',// 'PersonaEditar.php',	
				data: {PerID: vID},
				success: function(data){
					  $("#listadoPersonas").hide();
					  $("#editarEntrevista").html(data);
					  $("#editarEntrevista").show();
				}//fin success
			});//fin ajax//
	});//fin click eliminar
	// fin editar ENTREVISTA
	
  
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
      <th class="fila_titulo"><div align="left">Entrevista</div></th>
      <th class="fila_titulo"><div align="left">Datos</div></th>
    </tr>
    </thead>
    <tbody>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){
		$foto = buscarFoto($row[Per_DNI], $row[Per_Doc_ID], 60);
		$i++;
		// preguntar si tiene datos PersonaDatos
		$sql1 = "SELECT DISTINCTROW * FROM PersonaDatos WHERE Dat_Per_ID=".$row[Per_ID];
        $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
        $total1 = mysqli_num_rows($result1);
		if ($total1>0)
		    $datos="Si";
			 else $datos="No";
		
		//
				
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
	?>
	<tr class="gradeA <?php echo $clase;?>">
      <td><?php echo "$row[Doc_Nombre] $row[Per_DNI]";?> </td>
      <td><?php echo $row[Per_Apellido];?> </td>
      <td><?php echo $row[Per_Nombre];?> </td>
      <td><?php echo $row[Per_Sexo];?></td>
      <td><a href="#" id="edi<?php echo $row[Per_ID];?>"> Editar </a></td>
      <td><a href="#" id="ent<?php echo $row[Per_ID];?>"> Entrevista </a></td>
      <td><?php echo $datos;?></td>
      
    </tr>
  <?php		  
	}//fin del while
	?>  
    </tbody>
    <tfoot>
    <tr>
      <th colspan="7" class="fila_titulo" height="2"></th>
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
	echo "No se encontraron personas asociadas a la b&uacute;squeda";
}
?>