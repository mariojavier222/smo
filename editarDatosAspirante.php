<?php
//23012013 10 h
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>
<link rel="stylesheet" href="Jquery_Val/css/validationEngine.jquery.css" type="text/css"/>

<script src="Jquery_Val/js/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8">
</script>

<?php
$PerID = $_POST['PerID'];
$PerDNI = $_POST['DNI2'];
consulta_mysql("SET NAMES utf8");
if (!empty($PerDNI))
    {
      $sql = "SELECT * FROM Persona WHERE Per_DNI=$PerDNI";
	}
	else
       $sql = "SELECT * FROM Persona WHERE Per_ID=$PerID";
		

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

if ($total > 0){
	
$row = mysqli_fetch_array($result);

if (empty($PerID))
    $PerID=$row[Per_ID];
	
?>
<script src="jDatosAspirantes.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	// cargar combos	
	$("#DocID").attr('value','<?php echo $row[Per_Doc_ID];?>');
	$("#Sexo").attr('value','<?php echo $row[Per_Sexo];?>');
		
	// para guardar el formulario
	 $("#botonEditar").click(function(evento){
		evento.preventDefault();
		var dataString = $('#formEditar').serialize();
        //alert('Datos serializados: '+dataString);
        $.ajax({
            type: "POST",
            url: "actualizarDatosAspirantes.php",
            data: dataString,
            success: function(data) {
                alert(data);
      			$("#editar").hide();
      	    	$("#nuevo").show();
			    }
        });//fin ajax
	});//fin boton editar
	
	// para cancelar el formulario
	 $("#botonVolver").click(function(evento){
		limpiar();
				 
      });//fin boton volver
	
});//fin document
</script>

<h2> Editar Datos Aspirante </h2>

<form id="formEditar" class="formular" method="post">

<fieldset>
  <legend>Datos Basicos</legend>
  <input type="hidden" name="Per_ID" id="Per_ID" value="<?php echo $row[Per_ID]?>" />
  <p>
    <label for="textfield">Tipo de Documento:</label>
      <?php cargarListaTipoDoc("DocID");?>
  </p>
  <p>
    <label for="textfield">Nº Documento:</label>
      <input name="DNI" type="TEXT" id="DNI"  class="validate[required]" value="<?php echo $row[Per_DNI];?>"  min="8" max="8" maxlength="8">
  </p>
  
  <p>
    <label for="textfield">Apellidos:</label>
    <input name="Apellidos" type="text" id="Apellidos"  class="validate[required]" value="<?php echo $row[Per_Apellido];?>">
  </p>
  
  <p>
    <label for="textfield">Nombres:</label>
    <input name="Nombre" type="text" id="Nombre"  class="validate[required]" value="<?php echo $row[Per_Nombre];?>">
   </p>
   
   <p>
    <label for="textfield">Sexo:</label>
    
    <select name="Sexo" id="Sexo" class="validate[required]" >
            <option value="">Elegir una opción</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
   </p>
   
</fieldset>

<?php

$sql = "SELECT * FROM PersonaDatos WHERE Dat_Per_ID=$PerID";

$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);
$row = mysqli_fetch_array($result);
?>

<fieldset>
  <legend>Datos de Nacimiento</legend>
   <p>
    <label for="textfield">Fecha de nacimiento:</label>
     <input name="fechaNac" type="date" id="fechaNac" class="validate[required]" value="<?php echo $row[Dat_Nacimiento];?>">
    </p>
    
    <p>
    <label for="textfield">País de nacimiento:</label>
    
    <?php cargarListaPais("NacPaisID");?>
    </p>
    
    <p>
    <label for="textfield">Provincia de nacimiento:</label>
    <?php 
	   if ($total)
	  	cargarListaProvincia("NacProID",$row[Dat_Nac_Pai_ID]);
	   else
		  cargarListaProvincia("NacProID", 0);
	    ?>
    </p>
    
     <p>
    <label for="textfield">Localidad de nacimiento:</label>
    <?php
    if ($total)
     cargarListaLocalidad("NacLocID",$row[Dat_Nac_Pai_ID],$row[Dat_Nac_Pro_ID]); 
	 else
	     cargarListaLocalidad("NacLocID",0,0); 
	 
	    ?>
    </p>

</fieldset>

<fieldset>
  <legend>Datos de domicilio real</legend>
   <p>
    <label for="textfield">Dirección completa:</label>
    <input name="direccion" type="text" id="direccion" size="50" class="validate[required]" value="<?php echo $row[Dat_Domicilio];?>">
   </p>
   <p>
    <label for="textfield">País:</label>
    <?php	cargarListaPais("DomPaisID");?>
   </p>
   <p>
    <label for="textfield">Provincia:</label>
    <?php
	if ($total)
	 cargarListaProvincia("DomProID",$row[Dat_Dom_Pai_ID]); 
	 else
	     cargarListaProvincia("DomProID",0); 
	 ?>
   </p> 
   <p>
    <label for="textfield">Localidad:</label>
    <?php 
	  if ($total)
	  cargarListaLocalidad("DomLocID",$row[Dat_Dom_Pai_ID],$row[Dat_Dom_Pro_ID]);
	  else 
	      cargarListaLocalidad("DomLocID",0,0);
	      ?>
   </p> 
</fieldset>

   
<fieldset>
  <legend>Datos adicionales</legend>
    <p>
    <label for="textfield">Correo electrónico:</label>
    <input name="correo" type="text" class="email" id="correo" size="50" value="<?php echo $row[Dat_Email];?>">
   </p>
   <p>
    <label for="textfield">Teléfono fijo:</label>
    <input name="telefono" type="text"  id="telefono" size="50" class="validate[required]" value="<?php echo $row[Dat_Telefono];?>">
   </p>
   <p>
    <label for="textfield">Teléfono celular:</label>
    <input name="celular" type="text" id="celular" size="50" value="<?php echo $row[Dat_Celular];?>">
   </p>
    <p>
    <label for="textfield">Ocupación:</label>
    <input name="Ocupacion" type="text" id="Ocupacion" size="40" maxlength="60" value="<?php echo $row[Dat_Ocupacion];?>">
   </p>
   <p>
    <label for="textfield">Observaciones:</label>
    <textarea name="observ" cols="20" rows="5" id="observ" ><?php echo $row[Dat_Observaciones];?></textarea>
   </p>
   <p>
     <input class="submit" type="submit" value="Guardar Datos" id="botonEditar" name="botonEditar" />
    <input type="button" value="Volver" id="botonVolver" name="botonVolver" />
   </p>

 </fieldset> 
</form>

<script type="text/javascript">
$(document).ready(function(){
	// js de editarDatosAspirantes.php
	// cargar combos	
	
	
	$("#NacPaisID").attr("value","<?php echo $row[Dat_Nac_Pai_ID];?>");
	$("#NacProID").attr("value","<?php echo $row[Dat_Nac_Pro_ID];?>");
	$("#NacLocID").attr("value","<?php echo $row[Dat_Nac_Loc_ID];?>");
	
	$("#DomPaisID").attr("value","<?php echo $row[Dat_Dom_Pai_ID];?>");
	$("#DomProID").attr("value","<?php echo $row[Dat_Dom_Pro_ID];?>");
	$("#DomLocID").attr('value','<?php echo $row[Dat_Dom_Loc_ID];?>');
	
	
	
});//fin document
</script>
<?php

}else{
	echo "No se encontraron personas asociadas a la búsqueda";
}
?>



