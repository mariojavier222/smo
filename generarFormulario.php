<?php  
// Conexion al servidor & base de datos 
 mysqli_connect('localhost','root','123456') or die("ERROR:". mysqli_error ()); 
 mysql_select_db('napta_staJulio2018') or die("ERROR:". mysqli_error ()); 
 // nombre de la tabla 
 $tabla = "Colegio_Clase";
  
 $sql = " SELECT * FROM  ".$tabla; 
 	echo "OPCIONES<br />";
 	echo '//---------------------------<br />';
	echo 'case "guardar'.$tabla.'";<br />';
    echo '    guardar'.$tabla.'();<br />';
    echo '    break;<br />';
	echo 'case "buscar'.$tabla.'";<br />';
    echo '    buscar'.$tabla.'();<br />';
    echo '    break;<br />';
	echo 'case "buscarDatos'.$tabla.'":<br />';
    echo '    buscarDatos'.$tabla.'();<br />';
    echo '    break;<br />';
	echo 'case "eliminar'.$tabla.'":<br />';
    echo '    eliminar'.$tabla.'();<br />';
    echo '    break;		<br /><br />';
  
 $query = mysql_query($sql) or die("ERROR:Query->".mysqli_error ());  
  
  echo '<form method="POST">'; 
  ?>
  <table width="80%" border="0" align="center" class="borde_recuadro">
  <?php
  $i = 0; 
  // Mostramos los campos y los generamos 
  echo "Formulario de la tabla [ <B> $tabla </B> ]"; 
  echo "<BR><BR>"; 
  while($i < mysql_num_fields($query)) 
  { 
  	$campo = mysql_field_name($query,$i);
  	$textoFuncion .= "$".$campo." = $"."_POST['".$campo."'];<br />";
	$textoUpdate .= "$campo='$".$campo."', ";
	$textoInsert .= "'$".$campo."', ";
	$textoInsertPOST .= "'$"."_POST[".$campo."]', ";
	$textoBuscarDatos .='$datos .= "'.$campo.'\": \"" . $row['.$campo.'] . "\",\"";<br />';
	$textoFuncionBuscarDatos .= '$("#'.$campo.'").val(obj.'.$campo.');<br />';
	$textoAsignar .= "$"."$campo=$"."row[".$campo."];<br />";
	$textoClass .= "var $"."$campo;<br />";
	$textoConstructor .= "$"."this->$campo = $".$campo.";<br />";
	$textoClase2 .= "$"."this->$campo = $"."row[".$campo."];<br />";
	$textoParametros .= "$".$campo.", ";
	$textoParaEmail .= "$campo: "."$"."$campo&lt;br /&gt;<br />";
	//$textoClase4 .= "$"."this->$campo = $"."row[".$campo."];<br />";
	
  ?>
  <tr>
	  <td class="texto" align="right"><?php echo ucfirst(mysql_field_name($query,$i));?>:</td>
          <td align="left"><input name="<?php echo mysql_field_name($query,$i);?>" type="text" id="<?php echo mysql_field_name($query,$i);?>"/></td>
        </tr>
        <?php
   
   $i++; 
   
   }  
  // mostramos un boton para enviar la informacion 
  //echo '<input type="submit" name="boton" id="boton" value="Insertar !">'; 
  echo '</table></form>'; 
  echo "FUNCION: <br />$textoFuncion<br />";
  echo "UPDATE: <br />$textoUpdate<br /><br />";
  echo "INSERT: <br />$textoInsert<br /><br />";
   echo "INSERT POST: <br />$textoInsertPOST<br /><br />";
  echo "BUSCAR DATOS: <br />$textoBuscarDatos<br /><br />";
  echo "FUNCION BUSCAR DATOS: <br />$textoFuncionBuscarDatos<br /><br />";
  echo "ASIGNAR: <br />$textoAsignar<br /><br />";
  echo "CLASE 1: <br />$textoClass<br /><br />";
  echo "CLASE 2: <br />$textoConstructor<br /><br />";
  echo "CLASE 3: <br />$textoClase2<br /><br />";
  echo "PARAMETROS: <br />$textoParametros<br /><br />";
  echo "PARA EMAIL: <br />$textoParaEmail<br /><br />";

?>