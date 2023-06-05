<?php
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link rel="stylesheet" href="Jquery_Val/css/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="Jquery_Val7/css/template.css" type="text/css"/>
<script src="Jquery_Val/js/jquery-1.8.2.min.js" type="text/javascript">
</script>
<script src="Jquery_Val/js/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8">
</script>
<script src="Jquery_Val/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
</script>

<script>
$(document).ready(function(){
	
	 
	
	//inicializar combos
	$("#NacPaisID").attr("value","0");
	$("#DomPaisID").attr("value","0");
	
	//para buscar por dni
		
	$("#DNI2").keyup(function(event){	
		event.preventDefault();
		//alert("Hola");
		vDNI = $("#DNI2").val();
		if (event.keyCode == '13' ){
			alert(vDNI);   		
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {DNI2: vDNI},
				url: 'buscarPersonaDeuda.php',
				success: function(data){ 
					$("#listadoPersonas").html(data);
				}
			});//fin ajax//*/
		}
	});
	
	
	// para buscar por apellido
		
	$("#Apellido").keyup(function(event){
		event.preventDefault();
		vApellido = $("#Apellido").val();
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13 || vApellido.length>2)
		 {  
			//alert("Apretó enter");   		
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {Apellido: vApellido},
				url: 'buscarPersonaDeuda.php',
				success: function(data){ 
					$("#listadoPersonas").html(data);
				}
			});//fin ajax//*/
   		}
	});
	
	
	// para guardar el formulario
	 $("#botonGuardar").click(function(evento){
		evento.preventDefault();
		var dataString = $('#formID').serialize();
        alert('Datos serializados: '+dataString);
        $.ajax({
            type: "POST",
            url: "guardarDatosAspirantes.php",
            data: dataString,
            success: function(data) {
             alert(data);
			  $("#mensajes").html(data);
			 
            }
        });
			
	});
		
		
	//----- para los combos ----------------------
   $("#NacPaisID").change(function () {
   		$("#NacPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Nac", vPais);
        });
   })
   	// Parametros para el combo2
	$("#NacProID").change(function () {
   		$("#NacProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#NacPaisID").val();
			llenarLocalidad("Nac", vProv, vPais);
        });
   })
   $("#DomPaisID").change(function () {
   		$("#DomPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Dom", vPais);
        });
   })
	// Parametros para el combo2
	$("#DomProID").change(function () {
   		$("#DomProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#DomPaisID").val();
			llenarLocalidad("Dom", vProv, vPais);
        });
   })

	function llenarLocalidad(vObj, vProv, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },function(data){
     			$("#" + vObj + "LocID").html(data);
				if (vOpcion) $("#" + vObj + "LocID").attr("value",vOpcion);
   		});
	}
	
	function llenarProvincia(vObj, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarProvincia', Pais: vPais },function(data){
     			$("#" + vObj + "ProID").html(data);
					vProv = $("#" + vObj + "ProID").val();
					if (vOpcion)
						$("#" + vObj + "ProID").attr("value", vOpcion);
					else
						llenarLocalidad(vObj, vProv, vPais);

   			});
	}	
	

	//funciones para validar
	$("#formID").validationEngine();
	
			
		});//fin document

</script>

	
</head>

<body>

<div id="buscar">
<fieldset>
  <legend>Buscar</legend>
    <p>
    <label for="textfield">por Apellido:</label>
    <input name="Apellido" type="text" class="texto_buscador" id="Apellido" />
   
    <label for="textfield">por N&ordm; de Documento:</label>
    <input name="DNI2" type="text" class="texto_buscador" id="DNI2" >
   </p>
   </fieldset>

</div>

<div id="listadoPersonas" >
</div>




<div id="nuevo">

<h2> Nuevo Aspirante </h2>

<form id="formID" class="formular" method="post">

<fieldset>
  <legend>Datos Basicos</legend>
  
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
     <input name="fechaNac" type="date" id="fechaNac" class="validate[required]" >
    </p>
    
    <p>
    <label for="textfield">País de nacimiento:</label>
    <?php cargarListaPais("NacPaisID");?>
    </p>
    
    <p>
    <label for="textfield">Provincia de nacimiento:</label>
    <?php cargarListaProvincia("NacProID",0);    ?>
    </p>
    
     <p>
    <label for="textfield">Localidad de nacimiento:</label>
    <?php cargarListaLocalidad("NacLocID",0,0);    ?>
    </p>

</fieldset>

<fieldset>
  <legend>Datos de domicilio real</legend>
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
    <?php cargarListaProvincia("DomProID",0);    ?>
   </p> 
   <p>
    <label for="textfield">Localidad:</label>
    <?php cargarListaLocalidad("DomLocID",0,0);    ?>
   </p> 
</fieldset>

   
<fieldset>
  <legend>Datos adicionales</legend>
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
     <input class="submit" type="submit" value="Guardar Datos" id="botonGuardar" name="botonGuardar" />
   </p>

 </fieldset> 
</form>
</div>

<div id="editar">
</div>



</body>
</html>
