// JavaScript Document
//23012013 12 h
		  //$("#nuevo").hide();
		  //para buscar por dni
	$("#DNI2E").keyup(function(event){	
		event.preventDefault();
		//alert("Hola");
		vDNI = $("#DNI2E").val();
		if (event.keyCode == '13' || vDNI.length>4 ){
			//alert(vDNI);   		
			$("#nuevo").hide();	
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {DNI2: vDNI},
				url:'buscarPersonaDNI.php',//'editarDatosAspirante.php', // 'buscarPersona2.php',
				success: function(data){ 
				    $("#listadoEntrevista").hide();
					$("#nuevaEntrevista").hide(); 
					$("#editarEntrevista").html(data);
					$("#editarEntrevista").show();
				}
			});//fin ajax//*/
		}//fin del if
	});//fin boton buscar por dni
	
	
	// para buscar por apellido
		
	$("#ApellidoE").keyup(function(event){
		event.preventDefault();
		var vApellido = $("#ApellidoE").val();
		//alert("Hola " + event.keyCode + " Tecla");
		if (event.keyCode == 13 || vApellido.length>2)
		//alert(vApellido);
		//if (event.keyCode == 13)
		 { 	//alert("Apretó enter"); 
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {Apellido: vApellido},
					url: 'buscarEntrevistaApellido.php',// 'buscarPersona2.php',
					success: function(data){
						//alert(data);
						$("#nuevaEntrevista").hide(); 
						$("#editarEntrevista").hide();
						$("#listadoEntrevista").html(data);
						$("#listadoEntrevista").show();
						$("#cargando").hide(); 
						
						}
			});//fin ajax//*/
   		}//fin del if
	});//fin boton buscar por apellido

  // guardar entrevista
 $("#botonGuardarEntrevista").click(function(evento){
    evento.preventDefault();
    var dataString = $('#formEntrevista').serialize();
    //alert('Datos serializados: '+ dataString);

      $.ajax({
            type: "POST",
            url: "cargarOpcionesEntrevista.php",
            data: dataString,
            success: function(data) {
             mostrarAlerta(data, "Resultado de guardar la entrevista");
             $("#entrevista").hide();
             //$("#formID")[0].reset(); //para limpiar el formulario
			 $("#entrevista").show();

			 }
        });//fin del ajax
      
  });//fin boton guardar


	
	//al cambiar combos

	//----- para los combos de nivel ----------------------
   $("#EntNivID").change(function () {
	   		vNiv=$(this).val();
        //alert(vNiv);
				llenarCurso("EntCurID",vNiv);
		 })
   
   function llenarCurso(vObj, vNiv){
   	$.post("cargarOpcionesEntrevista.php", { nombre: vObj, opcion: 'cargarCurso', nivel: vNiv },function(data){
     			$("#" + vObj ).html(data);
					
   			});
	}	// llenarCurso
	
	// llenar combo sicopedagogas
	function llenarSicopedagoga(vObj){
   	$.post("cargarOpcionesSicopedagoga.php",
	 { nombre: vObj, opcion: 'listado' },
	 function(data){
     			$("#" + vObj ).html(data);
					
   			});
	}	// llenar listado sicopedagogas
	
	//funciones para validar
	$("#formEntrevista").validationEngine();

	//llenarSicopedagoga("Ent_Sic_ID");
   