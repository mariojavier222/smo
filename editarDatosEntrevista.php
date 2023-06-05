<?php
//23012013 10 h

include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
consulta_mysql("SET NAMES utf8");
?>
<link rel="stylesheet" href="Jquery_Val/css/validationEngine.jquery.css" type="text/css"/>

<!--<script src="Jquery_Val/js/jquery-1.8.2.min.js" type="text/javascript" charset="utf-8"></script>-->
<script src="Jquery_Val/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="Jquery_Val/js/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>

<script src="jDatosAspirantes.js" type="text/javascript"></script>
<script src="jDatosEntrevista.js" type="text/javascript"></script>	

<?php


$PerID = $_POST['PerID'];

$sql = "SELECT * FROM Persona INNER JOIN Entrevista ON (Per_ID = Ent_Per_ID) WHERE Per_ID= $PerID";

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

if ($total > 0){
	
$row = mysqli_fetch_array($result);

if (empty($PerID))
    $PerID=$row[Per_ID];
	
?>
<script type="text/javascript">
$(document).ready(function(){
	// cargar combos	
	$("#Ent_Turno").attr('value','<?php echo $row[Ent_Turno];?>');
	$("#EntLecID").attr('value','<?php echo $row[Ent_Lec_ID];?>');
	$("#EntNivID").attr('value','<?php echo $row[Ent_Niv_ID];?>');
	$("#EntCurID").attr('value','<?php echo $row[Ent_Cur_ID];?>');
	$("#Ent_Sic_ID").attr('value','<?php echo $row[Ent_Sic_ID];?>');
	$("#Ent_Asistio").attr('value','<?php echo $row[Ent_Asistio];?>');
	$("#Ent_Estado").attr('value','<?php echo $row[Ent_Estado];?>');
	
		
	// para guardar el formulario
	 $("#botonActualizarEntrevista").click(function(evento){
		evento.preventDefault();
		var dataString = $('#formEditarEntrevista').serialize();
        //alert('Datos serializados: '+dataString);
        $.ajax({
            type: "POST",
            url: "cargarOpcionesEntrevista.php",
            data: dataString,
            success: function(data) {
                alert(data);
      			$("#editarEntrevista").hide();
				$("#listadoPersonas").hide();	
				 $("#editar").hide();
				 $("#nuevo").show();
				 $("#formID")[0].reset(); //para limpiar el formulario
				 $("#Apellido").val("");
				 $("#DNI2").val("");
			    }
        });//fin ajax
	});//fin boton editar
	
	// para cancelar el formulario
	 $("#botonVolver").click(function(evento){
		 $("#editar").hide();
		 $("#nuevo").show();
		 $("#Apellido").val("");
		 $("#DNI2").val("");
		 
      });//fin boton volver
	
});//fin document
</script>





<h2> Editar Entrevista </h2>

<form id="formEditarEntrevista" class="formular" method="post">

<fieldset>
  <input type="hidden" name="opcion" id="opcion" value="actualizarEntrevista" />
  
  <input type="hidden" name="Ent_Per_ID" id="Ent_Per_ID" value="<?php echo $row[Ent_Per_ID]?>" />
  
  
  <p>
    <label for="textfield"> Apellidos: <?php echo $row[Per_Apellido];?></label>
  </p>
  
  <p>
    <label for="textfield"> Nombres: <?php echo $row[Per_Nombre];?></label>
   </p>
   
   <p>
    <label for="textfield"> Nº Documento: <?php echo $row[Per_DNI];?> </label>
  </p>
   
   <p>
      <label for="textfield">Turno: </label>
      <select id="Ent_Turno" name="Ent_Turno">
        <?php
           echo "<option value='Matutino'>Matutino</option> ";
           echo "<option value='Vespertino'>Vespertino</option> ";
        ?>
      </select>
     </p>
     
     <p>
      <label for="textfield">Lectivo: </label>
     
        <?php
          cargarListaLectivo("EntLecID");
        ?>
    
     </p>

     <p>
      <label for="textfield">Nivel: </label>
        <?php
          cargarListaNivel("EntNivID");
        ?>
     </p>
      
     <p>
      <label for="textfield">Curso: </label>
      <?php
       cargarListaCursos("EntCurID",true);
               ?>
      </p>
     

      <p>
      <label for="textfield">Fecha de la Entrevista: </label>
      <input name="Ent_Fecha" type="date" id="Ent_Fecha"  class="validate[required]" value="<?php echo $row[Ent_Fecha];?>">
      </p>

      <p>
      <label for="textfield">Hora de la Entrevista: </label>
      <input name="Ent_Hora" type="text" id="Ent_Hora"  class="validate[required]" value="<?php echo $row[Ent_Hora];?>">
      </p>

      <!--<p> 
	 
       <label for="textfield">Sicopedagoga: </label>
        <select id="Ent_Sic_ID" name="Ent_Sic_ID">
      
       </select>
       </p>-->

      <p>
      <label for="textfield">Asistio: </label>
      <select id="Ent_Asistio" name="Ent_Asistio">
                        <?php
							
                        echo "<option value='0'>NO</option> ";
                        echo "<option value='1'>SI</option> ";
                        ?>
                    </select>
      </p>
      
      <p>
      <label for="textfield">Estado: </label>
       <select id="Ent_Estado" name="Ent_Estado">
                        <?php
                        echo "<option value='0'>NO</option> ";
                        echo "<option value='1'>SI</option> ";
                        ?>
       </select>
     </p>

     <p>
     <input class="submit" type="submit" value="Actualizar Datos" id="botonActualizarEntrevista" name="botonActualizarEntrevista" />
     
   </p>
     
</fieldset> 
</form>


<script type="text/javascript">
$(document).ready(function(){
	// js de editarDatosAspirantes.php
	// cargar combos	
	
	$("#Ent_Sic_ID").attr('value','<?php echo $row[Ent_Sic_ID];?>');
	
});//fin document
</script>
<?php

}else{
	echo "No se encontraron personas asociadas a la búsqueda";
}
?>



