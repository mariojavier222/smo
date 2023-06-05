// JavaScript Document

// esperamos que el DOM cargue
$(document).ready(function() {
	// definimos las opciones del plugin AJAX FORM
	var opciones= {
					   beforeSubmit: mostrarLoader, //funcion que se ejecuta antes de enviar el form
					   success: mostrarRespuesta //funcion que se ejecuta una vez enviado el formulario
	};
	 //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
	$('#form_persona').ajaxForm(opciones) ;

	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function mostrarLoader(){
		  $('#cargando').fadeIn("slow"); //muestro el loader de ajax
	 };
	 function mostrarRespuesta (responseText){
				   alert("Mensaje enviado: "+responseText);  //responseText es lo que devuelve la página buscarPersona.php. Si en buscarPersona.php hacemos echo "Hola" , la variable responseText = "Hola" . Aca hago un alert con el valor de response text
				  $("#cargando").fadeOut("slow"); // Hago desaparecer el loader de ajax
				  $("#principal").append("<br>Mensaje: "+responseText); // Aca utilizo la función append de JQuery para añadir el responseText  dentro del div "ajax_loader"
	 };

}); 