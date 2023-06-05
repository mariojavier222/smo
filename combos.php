  
      <?php
  
      require_once("conexion.php");
      
 
      $idcombo = $_POST["id"];

      $action =$_POST["combo"];

      switch($action){
 
          case "pais":{
  
              $sql= "SELECT * FROM Provincia WHERE Pro_ID = $idcombo order by Pro_Nombre ASC";
			  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

              while ($row = mysqli_fetch_array($result)){
              
    ?>
               <option value="<?php echo $row['Pro_ID'];?> " > <?php echo $row['Pro_Nombre']; ?></option>  
    <?php
  					}
                  break;
  
                       }
  
          case "Localidad":{       
 
              $sql="SELECT * FROM Localidad WHERE Loc_Pro_ID= $idcombo order by Loc_Nombre ASC";
  				 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

              while ($row = mysqli_fetch_array($result)){
              
   ?>
               <option value="<?php echo $row['Loc_ID'] ;?>" > <?php echo $row['Loc_Nombre']; ?></option>  
  <?php
  		}
              break;
 
                             }
  
      }
 
      ?>

