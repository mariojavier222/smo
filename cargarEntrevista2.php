<?php
// 22012013 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
consulta_mysql("SET NAMES utf8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>cargar datos de las entrevistas </title>
<link rel="stylesheet" href="Jquery_Val/css/validationEngine.jquery.css" type="text/css"/>
<!--<script type="text/javascript" src="js/jquery-1.6.2.js"></script>-->

<script src="Jquery_Val/js/jquery-1.8.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="Jquery_Val/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="Jquery_Val/js/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>

<script src="jDatosAspirantes.js" type="text/javascript"></script>
<script src="jDatosEntrevista.js" type="text/javascript"></script>	

</head>

<body>

<div id="buscar">
<fieldset>
  <legend>Buscar</legend>
  
    <p>
    <label for="textfield">por Apellido:</label>
    <input name="ApellidoE" type="text" class="texto_buscador" id="ApellidoE" />
   
    <label for="textfield">por N&ordm; de Documento:</label>
    <input name="DNI2E" type="text" class="texto_buscador" id="DNI2E" >
   </p>
   </fieldset>
</div>

<div id="listadoEntrevista" >
</div>

<div id="editarEntrevista" >
</div>


<div id="nuevaEntrevista" >

<h2> Nueva Entrevista </h2>

<form id="formEntrevista" class="formular" method="post">

<fieldset>
  <input type="hidden" name="opcion" id="opcion" value="guardarEntrevista" />
  
  <input type="hidden" name="Ent_Per_ID" id="Ent_Per_ID" value="<?php echo $row[Per_ID]?>" />
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
      <label for="textfield">Turno: </label>
      <select id="Ent_Turno" name="Ent_Turno">
        <?php
           echo "<option value='Mañana'>Mañana</option> ";
           echo "<option value='Tarde'>Tarde</option> ";
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
      <input name="Ent_Fecha" type="date" id="Ent_Fecha"  class="validate[required]" value="">
      </p>

      <p>
      <label for="textfield">Hora de la Entrevista: </label>
      <input name="Ent_Hora" type="time" id="Ent_Hora"  class="validate[required]" value="">
      </p>

      <p> 
      <label for="textfield">Sicopedagoga: </label>
      <select id="Ent_Sic_ID" name="Ent_Sic_ID">
        </select>
      </p>

       <p>
      <label for="textfield">Asistio: </label>
      <select id="Ent_Asistio" name="Ent_Asistio">
                        <?php
                        echo "<option value='NO'>NO</option> ";
                        echo "<option value='SI'>SI</option> ";
                        ?>
                    </select>
      </p>
      
      <p>
      <label for="textfield">Estado: </label>
       <select id="Ent_Estado" name="Ent_Estado">
                        <?php
                        echo "<option value='NO'>NO</option> ";
                        echo "<option value='SI'>SI</option> ";
                        ?>
       </select>
     </p>

     <p>
     <input class="submit" type="submit" value="Guardar Datos" id="botonGuardarEntrevista" name="botonGuardarEntrevista" />
     
   </p>
     
</fieldset> 
</form>
</div>
</body>
</html>
