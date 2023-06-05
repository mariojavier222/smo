// JavaScript Document
//23012013 12 h

//inicializar combos


$("#NacPaisID").attr("value","1");
$("#DomPaisID").attr("value","1");
	
// para limpiar
$("#botonLimpiar").click(function(evento){
      
    limpiar();
         					
});//fin boton guardar

// ocultar entrevista
$("#entrevista").hide();
$("#padre").hide();
$("#nuevorequisito").hide();
        
	
//para buscar por dni
$("#DNI2").keyup(function(event){	
    event.preventDefault();
    vApellido = $("#Apellido").val();
    vDNI = $("#DNI2").val();
    vLecID = $("#LecID").val();
    if (event.keyCode == '13' || vDNI.length>4 ){
        //alert(vDNI);   		
        $("#nuevo").hide();	
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);
            },
            data: {
                Apellido: vApellido,
                DNI2: vDNI, 
                LecID: vLecID
            },
            url:'buscarPersonaDNI.php',//'editarDatosAspirante.php', // 'buscarPersona2.php',
            success: function(data){ 
                $("#listadoPersonas").hide();
                $("#nuevo").hide();
                $("#padre").hide(); 
                $("#editar").html(data);
                $("#editar").show();
					
            }
        });//fin ajax//*/
    }//fin del if
});//fin boton buscar por dni
	
	
// para buscar por apellido
		
$("#Apellido").keyup(function(event){
    event.preventDefault();
    vApellido = $("#Apellido").val();
    vLecID = $("#LecID").val();
    //alert("Hola " + event.keyCode);
    if (event.keyCode == 13 || vApellido.length>2)
    { 	//alert("Apretó enter"); 
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);
            },
            data: {
                Apellido: vApellido, 
                LecID: vLecID
            },
            url: 'buscarPersonaApellido.php',// 'buscarPersona2.php',
            success: function(data){
                //$("#nuevo").hide(); 
                //$("#listadoPersonas").html(data);
                //$("#listadoPersonas").show();
                $("#listadoPersonas").hide();
                $("#nuevo").hide();
                $("#padre").hide(); 
                $("#editar").html(data);
                $("#editar").show();



            }
        });//fin ajax//*/
    }//fin del if
});//fin boton buscar por apellido
	
	
// para guardar el formulario y mostrar uno nuevo
$("#botonGuardar").click(function(evento){
      	
    evento.preventDefault();
    var dataString = $('#formID').serialize();
    // alert('Datos serializados: '+dataString);
	vDNI = $("#DNI").val();
        vApellidos = $("#Apellidos").val();
        vNombre = $("#Nombre").val();
        vSexo = $("#Sexo").val();
        vLegajo = $("#Legajo").val();
        vEntNivID = $("#EntNivID").val();
        vEntCurID = $("#EntCurID").val();
        vFechaNac = $("#fechaNac").val();
        vDireccion = $("#direccion").val();
        vCorreo = $("#correo").val();
        vTelefono = $("#telefono").val();
        vCelular = $("#celular").val();
        	
	//alert($("#EntNivID").val());
    var dataString = $('#formID').serialize();
    //alert('Datos serializados: '+dataString);
	
	vError = false;
	vTexto_Error = '';
	
                if (vDNI==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "DNI invalido </br>" ;
					$("#DNI").attr("class","input_error");
                    					
					
                }
				else {
					$("#DNI").attr("class","input_sesion");
					}
                if (vApellidos==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "Apellido invalido </br>" ;
					$("#Apellidos").attr("class","input_error");
                    					
					
                }
				else {
					$("#Apellidos").attr("class","input_sesion");
					}
                if (vCorreo==""){
					vError = true;
                    vTexto_Error = vTexto_Error +  "Correo invalido </br>" ;
					$("#Correo").attr("class","input_error");
                    
                }
				else {
					$("#Correo").attr("class","input_sesion");
					}
                if (vNombre==""){
					vError = true;
                   vTexto_Error = vTexto_Error +  "Nombre invalido </br>" ;
					$("#Nombre").attr("class","input_error");
                    
                }
				else {
					$("#Nombre").attr("class","input_sesion");
					}
                if (vSexo==-1){
					vError = true;
                   vTexto_Error = vTexto_Error +  "Sexo invalido </br>" ;
					$("#Sexo").attr("class","input_error");
                    
                }
				else {
					$("#Sexo").attr("class","input_sesion");
					}
                if (vLegajo==""){
                    vError = true;
                   vTexto_Error = vTexto_Error +  "Legajo invalido </br>" ;
					$("#Legajo").attr("class","input_error");
                    
                }
				else {
					$("#Legajo").attr("class","input_sesion");
					}
                if (vEntNivID==-1){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Nivel invalido </br>" ;
					$("#EntNivID").attr("class","input_error");
                    
                }
				else {
					$("#EntNivID").attr("class","input_sesion");
					}
                if (vEntCurID==-1){
                   vError = true;
                   vTexto_Error = vTexto_Error +  "Curso invalido </br>" ;
					$("#EntCurID").attr("class","input_error");
                    
                }
				else {
					$("#EntCurID").attr("class","input_sesion");
					}
                if (vFechaNac==""){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Fecha invalida </br>" ;
					$("#fechaNac").attr("class","input_error");
                    
                }
				else {
					$("#fechaNac").attr("class","input_sesion");
					}
                if (vDireccion==""){
                   vError = true;
                   vTexto_Error = vTexto_Error +  "Direccion invalida </br>" ;
					$("#direccion").attr("class","input_error");
                    
                }
				else {
					$("#direccion").attr("class","input_sesion");
					}
                if (vTelefono==""){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Telefono invalido </br>" ;
					$("#telefono").attr("class","input_error");
                    
                }
				else {
					$("#telefono").attr("class","input_sesion");
					}
                if (vCelular==""){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Celular invalido </br>" ;
					$("#celular").attr("class","input_error");
                    
                }
				else {
					$("#celular").attr("class","input_sesion");
					}
				if(vError) {
					mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
					return;
					}
    $.ajax({
        type: "POST",
        url: "guardarDatosAspirantes.php",
        data: dataString,
        success: function(data) {
            mostrarAlerta(data, "Resultado de guardar los datos");
            $("#nuevo").show();
            $("#padre").show();
			 
            $("#formID")[0].reset(); //para limpiuar
            $("#Apellido").val("");
            $("#DNI2").val("");
        }
    });//fin del ajax
   			
});//fin boton guardar

// para guardar el formulario y cargar la entrevista
   
$("#botonEntrevista").click(function(evento){
    evento.preventDefault();
        $("#LecID2").val($("#LecID").val());
	$("#EntNivID").val($("#EntNivID").val());
	$("#EntCurID").val($("#EntCurID").val());
        alert($("#EntCurID").val());
        $("#Legajo").val($("#Legajo").val());
        vDNI = $("#DNI").val();
        vApellidos = $("#Apellidos").val();
        vNombre = $("#Nombre").val();
        vSexo = $("#Sexo").val();
        vLegajo = $("#Legajo").val();
        vEntNivID = $("#EntNivID").val();
        vEntCurID = $("#EntCurID").val();
        vFechaNac = $("#fechaNac").val();
        vDireccion = $("#direccion").val();
        vCorreo = $("#correo").val();
        vTelefono = $("#telefono").val();
        vCelular = $("#celular").val();
        	
	//alert($("#EntNivID").val());
    var dataString = $('#formID').serialize();
    //alert('Datos serializados: '+dataString);
	
	vError = false;
	vTexto_Error = '';
	
                if (vDNI==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "DNI invalido </br>" ;
					$("#DNI").attr("class","input_error");
                    					
					
                }
				else {
					$("#DNI").attr("class","input_sesion");
					}
                if (vApellidos==""){
                    vError = true;
					vTexto_Error = vTexto_Error +  "Apellido invalido </br>" ;
					$("#Apellidos").attr("class","input_error");
                    					
					
                }
				else {
					$("#Apellidos").attr("class","input_sesion");
					}
                if (vCorreo==""){
					vError = true;
                    vTexto_Error = vTexto_Error +  "Correo invalido </br>" ;
					$("#Correo").attr("class","input_error");
                    
                }
				else {
					$("#Correo").attr("class","input_sesion");
					}
                if (vNombre==""){
					vError = true;
                   vTexto_Error = vTexto_Error +  "Nombre invalido </br>" ;
					$("#Nombre").attr("class","input_error");
                    
                }
				else {
					$("#Nombre").attr("class","input_sesion");
					}
                if (vSexo==-1){
					vError = true;
                   vTexto_Error = vTexto_Error +  "Sexo invalido </br>" ;
					$("#Sexo").attr("class","input_error");
                    
                }
				else {
					$("#Sexo").attr("class","input_sesion");
					}
                if (vLegajo==""){
                    vError = true;
                   vTexto_Error = vTexto_Error +  "Legajo invalido </br>" ;
					$("#Legajo").attr("class","input_error");
                    
                }
				else {
					$("#Legajo").attr("class","input_sesion");
					}
                if (vEntNivID==-1){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Nivel invalido </br>" ;
					$("#EntNivID").attr("class","input_error");
                    
                }
				else {
					$("#EntNivID").attr("class","input_sesion");
					}
                if (vEntCurID==-1){
                   vError = true;
                   vTexto_Error = vTexto_Error +  "Curso invalido </br>" ;
					$("#EntCurID").attr("class","input_error");
                    
                }
				else {
					$("#EntCurID").attr("class","input_sesion");
					}
                if (vFechaNac==""){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Fecha invalida </br>" ;
					$("#fechaNac").attr("class","input_error");
                    
                }
				else {
					$("#fechaNac").attr("class","input_sesion");
					}
                if (vDireccion==""){
                   vError = true;
                   vTexto_Error = vTexto_Error +  "Direccion invalida </br>" ;
					$("#direccion").attr("class","input_error");
                    
                }
				else {
					$("#direccion").attr("class","input_sesion");
					}
                if (vTelefono==""){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Telefono invalido </br>" ;
					$("#telefono").attr("class","input_error");
                    
                }
				else {
					$("#telefono").attr("class","input_sesion");
					}
                if (vCelular==""){
                  vError = true;
                   vTexto_Error = vTexto_Error +  "Celular invalido </br>" ;
					$("#celular").attr("class","input_error");
                    
                }
				else {
					$("#celular").attr("class","input_sesion");
					}
				if(vError) {
					mostrarAlerta1(vTexto_Error,"Existen datos incorrectos");
					return;
					}
    $.ajax({
        type: "POST",
        url: "guardarDatosAspirantes.php",
        data: dataString, 
        success: function(data) {
            mostrarAlerta(data, "Resultado de guardar la entrevista");
             
            $("#buscar").hide();
             
            $("#DNI_Ent").val( $("#DNI").val() );// para inicializar
            $("#Apellidos_Ent").val($("#Apellidos").val());
            $("#Nombre_Ent").val($("#Nombre").val());
            $("#nuevo").hide();
            $("#entrevista").show();
            $("#padre").hide();

        }
    });//fin del ajax
			
});//fin boton guardar
        
// para guardar el formulario y mostrar uno nuevo
$("#botonGuardarEntrevista").click(function(evento){
      	
    evento.preventDefault();
    var dataString = $('#formEntrevista').serialize();
    //alert('Datos serializados: '+ dataString);
      vFechaEnt = $("#Ent_Fecha").val();
      vEnt_Hora = $("#Ent_Hora").val();
       vError = false;
	   vTexto_Error = '';        
			   if (vFechaEnt==""){
                    vError = true;
                   vTexto_Error = vTexto_Error +  "Fecha invalida</br>" ;
					$("#Ent_Fecha").attr("class","input_error");
                    
                }
				else {
					$("#Ent_Fecha").attr("class","input_sesion");
					}                
                if (vEnt_Hora==""){
                   vError = true;
                   vTexto_Error = vTexto_Error +  "Hora invalida </br>" ;
					$("#Ent_Hora").attr("class","input_error");
                    
                }
				else {
					$("#Ent_Hora").attr("class","input_sesion");
					}
				if(vError) {
					mostrarAlerta1(vTexto_Error,"Existen datos incorrectos");
					return;
					}
      $.ajax({
            type: "POST",
            url: "cargarOpcionesEntrevista.php",
            data: dataString,
            success: function(data) {
             mostrarAlerta(data, "Resultado de guardar la entrevista");
             $("#entrevista").show();
             //$("#formID")[0].reset(); //para limpiar el formulario
			 $("#nuevopadre").hide();

			 }
        });//fin del ajax
      
  });//fin boton guardar


$("#barraGuardarPadre").click(function(evento){
    evento.preventDefault();
    $("#LecID2").val($("#LecID").val());
    //alert($("#LecID2").val());
    //alert($("#LecID").val());
    var dataString = $('#formID').serialize();
    //alert('Datos serializados: '+dataString);
    $.ajax({
        type: "POST",
        url: "guardarDatosAspirantes.php",
        data: dataString,
        success: function(data) {
            mostrarAlerta(data, "Resultado de guardar la entrevista");
             
            $("#buscar").hide();
             
            $("#DNI_Ent").val( $("#DNI").val() );// para inicializar
            $("#Apellidos_Ent").val($("#Apellidos").val());
            $("#Nombre_Ent").val($("#Nombre").val());
            $("#nuevo").hide();
            $("#entrevista").hide();
            $("#padre").hide();
            $("#nuevorequisito").show();

        }
    });//fin del ajax
			
});//fin boton guardar

	
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

// FUNCIONES
	
function limpiar(){
    $("#listadoPersonas").hide();	
    $("#editar").hide();
    $("#editarEntrevista").hide();
    $("#nuevo").show();
    $("#formID")[0].reset(); //para limpiar el formulario
    $("#Apellido").val("");
    $("#DNI2").val("");
    $("#Apellido").focus();
}
     
function llenarLocalidad(vObj, vProv, vPais, vOpcion){
    $.post("buscarLocalidad.php", {
        opcion: 'cargarLocalidad', 
        Pais: vPais, 
        Prov: vProv
    },function(data){
        $("#" + vObj + "LocID").html(data);
        if (vOpcion) $("#" + vObj + "LocID").attr("value",vOpcion);
    });
}
	
function llenarProvincia(vObj, vPais, vOpcion){
    $.post("buscarLocalidad.php", {
        opcion: 'cargarProvincia', 
        Pais: vPais
    },function(data){
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