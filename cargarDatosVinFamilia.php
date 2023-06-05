<?php
// 23012013 10 h
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");

?>
<link rel="stylesheet" href="Jquery_Val/css/validationEngine.jquery.css" type="text/css"/>
<!--<script type="text/javascript" src="js/jquery-1.6.2.js"></script>-->

<!--<script src="Jquery_Val/js/jquery-1.8.2.min.js" type="text/javascript" charset="utf-8"></script>-->
<script src="Jquery_Val/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="Jquery_Val/js/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>

<script src="jDatosAspirantes.js" type="text/javascript"></script>
<script src="jDatosEntrevista.js" type="text/javascript"></script>	

</head>

<body >

<!--<div id="buscar" class="texto">
<fieldset>
  <legend>Buscar aspirante</legend>
    Ciclo Lectivo: < ? 
		 	$UniID = $_SESSION['sesion_UniID'];
			//echo $UniID;
			cargarListaLectivo("LecID");?><br>
    <p>
    <label for="textfield">      por Apellido:</label>
    <input name="Apellido" type="text" class="texto_buscador" id="Apellido" />
   
    <label for="textfield">por N&ordm; de Documento:</label>
    <input name="DNI2" type="text" class="texto_buscador" id="DNI2" >
    
    <input name="botonLimpiar" type="button" class="texto_buscador" id="botonLimpiar"  value="Limpiar">
    
   </p>
   </fieldset>
</div>

<div id="listadoPersonas" class="texto">
</div>

<div id="editar"  class="texto">
</div>

<div id="editarEntrevista"  class="texto">
</div>-->


<div id="nuevopadre" class="texto">

<h2> Padre/Madre </h2>

<form id="formID" class="formular" method="post">

<fieldset>
  <legend>Datos Básicos</legend>
  <input type="hidden" name="LecID2" id="LecID2">
  
  
  <p>
    <label for="textfield">Tipo de Documento:</label>
    <?php cargarListaTipoDoc("DocID");?>
</p>
  <p>
    <label for="textfield">Nº Documento:</label>
      <input name="DNI" type="TEXT" id="DNI"  class="validate[required]" value=""  min="8" max="8" maxlength="8">
  </p>
  
  <p>
    <label for="textfield">Apellidos:</label>
    <input name="Apellidos" type="text" id="Apellidos"  class="validate[required]" value="">
  </p>
  
  <p>
    <label for="textfield">Nombres:</label>
    <input name="Nombre" type="text" id="Nombre"  class="validate[required]" value="">
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

<fieldset>
  <legend>Datos de Nacimiento</legend>
   <p>
    <label for="textfield">Fecha de nacimiento:</label>
     <input name="fechaNac" type="input" id="fechaNac" class="validate[required]" >
    </p>
    
    <p>
    <label for="textfield">País de nacimiento:</label>
    <?php cargarListaPais("NacPaisID", 1);    ?>
    </p>
    
    <p>
    <label for="textfield">Provincia de nacimiento:</label>
    <?php cargarListaProvincia("NacProID",1);    ?>
    </p>
    
     <p>
    <label for="textfield">Localidad de nacimiento:</label>
    <?php cargarListaLocalidad("NacLocID",1,2);    ?>
    </p>

</fieldset>

<fieldset>
  <legend>Datos del Domicilio Real</legend>
   <p>
    <label for="textfield">Dirección completa:</label>
    <input name="direccion" type="text" id="direccion" size="50" class="validate[required]">
   </p>
   <p>
    <label for="textfield">País:</label>
    <?php cargarListaPais("DomPaisID");    ?>
   </p>
   <p>
    <label for="textfield">Provincia:</label>
    <?php cargarListaProvincia("DomProID",1);    ?>
   </p> 
   <p>
    <label for="textfield">Localidad:</label>
    <?php cargarListaLocalidad("DomLocID",1,2);    ?>
   </p> 
</fieldset>

   
<fieldset>
  <legend>Datos Adicionales</legend>
    <p>
    <label for="textfield">Correo electrónico:</label>
    <input name="correo" type="text" class="email" id="correo" size="50">
   </p>
   <p>
    <label for="textfield">Teléfono fijo:</label>
    <input name="telefono" type="text"  id="telefono" size="50" class="validate[required]">
   </p>
   <p>
    <label for="textfield">Teléfono celular:</label>
    <input name="celular" type="text" id="celular" size="50">
   </p>
    <p>
    <label for="textfield">Ocupación:</label>
    <input name="Ocupacion" type="text" id="Ocupacion" size="40" maxlength="60" autocomplete="off" class="ac_input">
   </p>
   <p>
    <label for="textfield">Observaciones:</label>
    <textarea name="observ" cols="20" rows="5" id="observ"></textarea>
   </p>
   <p>
     <input class="submit" type="submit" value="Guardar e Ingresar Requisitos de Ingreso" id="botonGuardarPadre" name="botonGuardarPadre" />
   </p>

 </fieldset> 
</form>
</div>

<!--<div id="entrevista" class="texto">

<h2> Otros Datos </h2>

<form id="formEntrevista" class="formular" method="post">

<fieldset>
  
 
  <input type="hidden" name="opcion" id="opcion" value="guardarEntrevista" />
  <p>
    <label for="textfield">Nº Documento:</label>
      <input name="DNI_Ent" type="TEXT" id="DNI_Ent"  class="validate[required]" value=""  min="8" max="8" maxlength="8">
  </p>
  
  <p>
    <label for="textfield">Apellidos:</label>
    <input name="Apellidos_Ent" type="text" id="Apellidos_Ent"  class="validate[required]" value="">
  </p>
  
  <p>
    <label for="textfield">Nombres:</label>
    <input name="Nombre_Ent" type="text" id="Nombre_Ent"  class="validate[required]" value="">
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
      <input name="Ent_Fecha" type="date" id="Ent_Fecha"  class="validate[required]" value="">
      </p>

      <p>
      <label for="textfield">Hora de la Entrevista: </label>
      <input name="Ent_Hora" type="text" id="Ent_Hora"  class="validate[required]" value="">
      </p>

      <p> 
      <label for="textfield">Psicopedagoga: </label>
      
      
      
      <select id="Ent_Sic_ID" name="Ent_Sic_ID">
        </select>
         <?php
          cargarListaSicopedagogas("Ent_Sic_ID");
        ?>
      </p>

     <p>
      <label for="textfield">Asistio: </label>
      <select id="Ent_Asistio" name="Ent_Asistio">
                       <?php 
                        echo "<option value='1'> SI </option> ";
                        echo "<option value='0' selected='selected'> NO </option> ";
                        ?>
                    </select>
      </p>
      
      <p>
      <label for="textfield">Estado: </label>
       <select id="Ent_Estado" name="Ent_Estado">
                        <?php
                        echo "<option value='1'>SI</option> ";
                        echo "<option value='0' selected='selected'>NO</option> ";
                        ?>
       </select>
     </p>

     <p>
     <input class="submit" type="submit" value="Guardar Datos" id="botonGuardarEntrevista" name="botonGuardarEntrevista" />
     
   </p>
     
</fieldset> 


</form>
</div>-->
