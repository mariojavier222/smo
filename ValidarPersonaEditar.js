// JavaScript Document

$(document).ready(function(){
						   
						   
	var opciones_editar= {
					   beforeSubmit: validarFormEditar,
					   success: mostrarRespuestaEditar 
	};
//lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarFormEditar(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0];

			$("#mostrar").empty();
    		if ((form.Sexo.value=="-1") ) {
				$("#errorNuevoSexo").show();
				$("#cargando").fadeOut();
				return false;
			}else $("#errorNuevoSexo").hide();
			
    		if ((form.Extranjero.value=="1") ) {
	    		if ((!form.Alternativo.value) ) {
					$("#errorNuevoAlternativo").show();
					$("#cargando").fadeOut();
					return false;
				}
			}else $("#errorNuevoAlternativo").hide();

			$("#errorNuevoDNI").hide();
			$("#errorNuevoApellido").hide();
			$("#errorNuevoNombre").hide();
			$("#errorNuevoSexo").hide();
			$("#errorNuevoAlternativo").hide();
			$("#cargando").fadeOut();
			//return true;

	 };

	 function mostrarRespuestaEditar (responseText){
		  $("#mostrar").html(responseText); 
		  $("#cargando").fadeOut();
	 };
//Evento que se ejecuta
	$("form input#DNI").blur(function() {
  		$('#lista').load("cargar_foto.php", {DNI: $("#DNI").get(0).value, DocID: $("#DocID").get(0).value});
	});

//*/
//Script para subir la foto
    var button = $('#upload_button'), interval;
    var upload = new AjaxUpload('#upload_button', {
        action: 'subirArchivo.php',	
        onSubmit : function(file , ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
            // extensiones permitidas
            alert('Error: Solo se permiten imagenes');
            // cancela upload
            return false;
        } else {
            var valor = $("#DNIN").get(0).value;
			if (valor.length==0){
				alert('Error: Escriba un número de documento antes de subir un archivo: Valor:' + valor);
				return false;
			}else{
				//Cambio el texto del boton y lo deshabilito
				var vDNI = $('#DNIN').get(0).value;
				var vDocID = $('#DocID').get(0).value;
				upload.setData({'DNI': vDNI, 'DocID': vDocID});
				button.text('Subiendo...');
				this.disable();
			}
        }
        },
        onComplete: function(file, response){
            button.text('Archivo subido');
			$('#lista').html(response);
        }  
    });	 


});//fin funcion ready