<?php
  require_once("conexion.php");
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){
 
   $("select").change(function(){
  
              // Vector para saber cu�l es el siguiente combo a llenar
  
              var combos = new Array();
  
              combos['pais'] = "estado";
  
              combos['estado'] = "ciudad";
 
              // Tomo el nombre del combo al que se le a dado el clic por ejemplo: pa�s
  
              posicion = $(this).attr("name");
  
              // Tomo el valor de la opci�n seleccionada
  
              valor = $(this).val()       
  
              // Evalu�  que si es pa�s y el valor es 0, vaci� los combos de estado y ciudad
  
              if(posicion == 'pais' && valor==0){
  
                  $("#estado").html('    <option value="0" selected="selected">----------------</option>')
  
                  $("#ciudad").html('    <option value="0" selected="selected">----------------</option>')
  
              }else{
  
              /* En caso contrario agregado el letreo de cargando a el combo siguiente
 
              Ejemplo: Si seleccione pa�s voy a tener que el siguiente seg�n mi vector combos es: estado  por qu�  combos [pa�s] = estado
  
                  */
  
                  $("#"+combos[posicion]).html('<option selected="selected" value="0">Cargando...</option>')
  
                  /* Verificamos si el valor seleccionado es diferente de 0 y si el combo es diferente de ciudad, esto porque no tendr�a caso hacer la consulta a ciudad porque no existe un combo dependiente de este */
  
                  if(valor!="0" || posicion !='ciudad'){
  
                  // Llamamos a pagina de combos.php donde ejecuto las consultas para llenar los combos
  
                      $.post("combos.php",{
  
                                          combo:$(this).attr("name"), // Nombre del combo
  
                                          id:$(this).val() // Valor seleccionado
  
                                          },function(data){
  
                                                          $("#"+combos[posicion]).html(data);    //Tomo el resultado de pagina e inserto los datos en el combo indicado                                                                               
  
                                                          })                                               
  
                  }
  
              }
  
          })       
  
      })
  
      </script>
  
      <form id="form1" name="form1">
  
      <div>
        <select name="pais" id="pais">
          <option selected="selected" value="0">---------</option>
          <?php
 
      $sql = "SELECT * FROM Pais" ;
      $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

while ($row = mysqli_fetch_array($result)){ 
?>
          <option value= "<?php echo $row['Pai_ID'] ;?>"><?php echo $row['Pai_Nombre'] ;?></option>
          <?php
  
           
      }
  
      ?>
	
        </select>
     </div>
 
      <div>
  
      <select id="estado" name="estado">
  
          <option value="0" selected="selected">----------------</option>
  
      </select>
  
      </div>
  
       
  
      <div>
        <select id="ciudad" name="ciudad">
          <option value="0" selected="selected">-------------------</option>
        </select>
      </div>
  
      </form>

