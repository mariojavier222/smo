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
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
    $(document).ready(function(){

	
        //$("#fechaNac").datepicker($.datepicker.regional['es']);
        $("#NacPaisID").attr("value","1");
        $("#DomPaisID").attr("value","1");
        vDNI = 0;

        $("#DNI").focus();
        $("#Persona").hide();
	
        //boton siguiente
	
	
        $("#barraSiguiente").click(function(evento){
            evento.preventDefault();		
            vDNI_Asp = $("#DNI_Ent").val();
            vApellido_Asp = $("#Apellidos_Ent").val();
            vNombre_Asp = $("#Nombre_Ent").val();
            $.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarDatosPersonales.php',
                data: {DNI_Asp: vDNI_Asp, Apellido_Asp: vApellido_Asp, Nombre_Asp: vNombre_Asp, pag_Volver: "cargarDatosAspirante"},
                success: function (data){
                    $("#principal").html(data);
                    //mostrarAlerta(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        });

        $("#GenerarLegajoColegio").click(function(evento){
            evento.preventDefault();
            $("#cargando").show();
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: "GenerarLegajoColegio"},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    $("#Legajo").val(data);
                    $("#cargando").hide();
                }
            });//fin ajax//*/
        });//fin de prsionar enter
	
	
<?php
if (isset($_POST['DNI'])) {
    $DNI_Volver = $_POST['DNI_Volver'];
    $DNI = $_POST['DNI'];
    echo "$('#DNI').val($DNI);";
    //echo "alert($('#DNI').val());";
    echo "cargarDNI();";
    if (isset($_POST['NivID']) && isset($_POST['PerID'])) {
        $PerID = $_POST['PerID'];
        $NivID = $_POST['NivID'];
        echo "$('#NivID').val($NivID);";
        echo "cargarRequisitoPersona($NivID, $PerID);;";
    }
    //echo "buscarDNI($DNI);";
}
?>
	
        $("#personaNombre").result(colocarValor);	
        $("#personaNombre").autocomplete("buscarDatosPersona.php", {
            //multiple: true,
            mustMatch: false,
            minChars: 1,
            max: 50,		
            formatItem:function(item, index, total, query){
                return item.Per_Apellido + ', ' + item.Per_Nombre;
            },
            formatMatch:function(item){
                return item.Per_Apellido + ', ' + item.Per_Nombre;
            },
            formatResult:function(item){
                return item.Per_Apellido + ', ' + item.Per_Nombre;
            },

            dataType: "json",
            //parse: prep_data,
            parse:function(data) {
                return $.map(data, function(row) {
                    return {
                        data: row,
                        value: row.Per_Apellido,
                        result: row.Per_Apellido + ", " + row.Per_Nombre
                    }
                    $("#cargando").hide();
                });
            },//*/
            selectFirst: false,
            autoFill: true
        });
	
        function colocarValor(event, data, formatted) {
            if (data){
                $("#DNI").val(data.Per_DNI);
                buscarDNI(data.Per_DNI);
            }
        }
	
        /*$("#DNI").focusout(function(evento){
                evento.preventDefault();
                //buscarDNI($(this).val());
        });//*///fin focusout
        function cargarDNI(){
            iDNI = $("#DNI").val();
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: "obtenerApellidoNombre", DNI: iDNI, conDNI: "true"},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    $("#personaNombre").val(data);
                    buscarDNI(iDNI);
                    $("#cargando").hide();
                }
            });//fin ajax//*/
	
        }
        $("#DNI").keyup(function(evento){
            evento.preventDefault();
            iDNI = $("#DNI").val();
            if (evento.keyCode == 13 && iDNI.length>2){
                $("#cargando").show();
                cargarDNI();
            }//fin if
        });//fin de prsionar enter			

        function buscarDNI(DNI){
            $("#PerID").attr("value",0);
            if (validarNumero(DNI)){
                vDNI = DNI;
                $.post("cargarOpciones.php",{opcion: 'buscarDNI', DNI: vDNI}, function(data){
                    $("#Persona").show();
                    $("#Persona").html(data);
                    $.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
                        $("#PerID").attr("value",data);
                        vPerID = $("#PerID").val();
                        vNivID = -1;
                        vNivID = $("#NivID").val();
                        if (vNivID==-1){
						
                        }else{
                            cargarRequisitoPersona(vNivID, vPerID);
                        }
                        //alert(vPerID);
                    });

                });

            }//fin if
        }//fin funcion

        $("#barraGuardar").click(function(evento){
            evento.preventDefault();
            vNivID = $("#NivID").val();
            vPerID = $("#PerID").val();		
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                data: {opcion: 'eliminarRequisitoPersona', NivID: vNivID, PerID: vPerID},
                url: 'cargarOpciones.php'
            });//fin ajax//*/
            $("input[id^='Defin']:checked").each(function () {
                var i = this.id.substr(5,10);
                vValor = 1;
                /*if ($('#Defin' + i + ':checked').val() !== null) {
                                vValor = 0;
                        }//*/
                vReqID = $("#Defin" + i).val();
                //alert(vReqID);			
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "guardarRequisitoPersona", ReqID: vReqID, NivID: vNivID, PerID: vPerID, Campo: "Pre_Presento", Valor: vValor},
                    success: function (data){
                        $("#cargando").hide();
                    }
                });//fin ajax///
            });//*/
            $("input[id^='Const']:checked").each(function () {
                var i = this.id.substr(5,10);
                vReqID = $("#Const" + i).val();
                vValor = 1;
                /*if ($('#Const' + i + ':checked').val() !== null) {
                                vValor = 0;
                        }//*/

                //alert(vReqID);			
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "guardarRequisitoPersona", ReqID: vReqID, NivID: vNivID, PerID: vPerID, Campo: "Pre_Constancia", Valor: vValor},
                    success: function (data){
                        $("#cargando").hide();
                    }
                });//fin ajax//*/
            });

            mostrarAlerta("Se guardaron correctamente los cambios.", "Confirmaci�n");
        });
        //---------------------------
   
        //---------------------------
        //---Eventos de los botones----------
        $("#barraDefecto").click(function(evento){
            evento.preventDefault();				
            //Carga al pa�s Argentina, la provincia San Juan y la ciudad Capital por defecto.
            vPaisDefecto = 1;
            vProDefecto = 2;
            vLocDefecto = 2;
            mostrarAlerta("Se seleccion� al pa�s <strong>Argentina</strong> y la provincia <strong>San Juan</strong> por defecto.<br />Haga click en el bot�n <strong>Nuevo</strong> para ver los resultados", "Informaci�n");
        });
        $("#barraNuevo").click(function(evento){
            evento.preventDefault();
            limpiarDatos();
            $("#mostrarNuevo").fadeIn();
            $("#mostrar").empty();		
            $("#divBuscador").fadeOut();
            $("#DomPaisID").attr("value",vPaisDefecto);
            llenarProvincia("Dom", vPaisDefecto, vProDefecto);
            $("#NacPaisID").attr("value",vPaisDefecto);
            llenarProvincia("Nac", vPaisDefecto, vProDefecto);
            llenarLocalidad("Dom", vProDefecto, vPaisDefecto, vLocDefecto);
            llenarLocalidad("Nac", vProDefecto, vPaisDefecto, vLocDefecto);
        });	
        $("#barraVolver").click(function(evento){
            evento.preventDefault();
            vDNI = $("#DNI_Volver").val();
            vPagVolver = $("#pag_Volver").val() + ".php";
            //alert(vDNI.length);
            if (vDNI.length>0){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: vPagVolver,
                    data: {DNI: vDNI},
                    success: function (data){
                        $("#principal").html(data);
                        $("#cargando").hide();
                    }
                });//fin ajax
            }//fin if
        });
        //-----------------------------------
        //---------------------------Llenamos la entidad y los niveles de estudio

	
        $("#NivID").change(function () {
            $("#NivID option:selected").each(function () {
                //alert($(this).val());
                vNivID=$(this).val();
                vPerID = -1;
                vPerID=$("#PerID").val();
                //alert(vPerID);
                if (vPerID == -1){
                    mostrarAlerta("Debe seleccionar una persona", "ERROR");
                    return;
                }
                cargarRequisitoPersona(vNivID, vPerID);
            });
        })
	
        function cargarRequisitoPersona(vNivID, vPerID){
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                data: {NivID: vNivID, PerID: vPerID},
                url: 'buscarRequisitoPersona.php',
                success: function (data){
                    $("#mostrar").html(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        }
        // guardar nueva persona (padre)
        $(document).ready(function(){
            //$("#mostrarBuscador").hide();
            $("#mostrarNuevo").show();
            $("#mostrarNuevo").show();
            $("#mostrarEditar").hide();
            $('.error_buscador').hide();
            $('input.texto_buscador').css({
                backgroundColor:"#FFFFFF"
            });
            $('input.texto_buscador').focus(function(){
                $(this).css({
                    backgroundColor:"#FFDDAA"
                });
            });
            $('input.texto_buscador').blur(function(){
                $(this).css({
                    backgroundColor:"#FFFFFF"
                });
            });				
            $("#cargando").hide();

            // definimos las opciones del plugin AJAX FORM
            var opciones_buscador= {
                beforeSubmit: validarForm, //funcion que se ejecuta antes de enviar el form
                success: mostrarRespuesta //funcion que se ejecuta una vez enviado el formulario
            };
            var opciones_nuevo= {
                beforeSubmit: validarFormNuevo, //funcion que se ejecuta antes de enviar el form
                success: mostrarRespuestaNuevo //funcion que se ejecuta una vez enviado el formulario
            };//*/


            //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
            $("#form_persona").ajaxForm(opciones_buscador) ;
            //$("#form_nueva").ajaxForm(opciones_nuevo) ;

            //lugar donde defino las funciones que utilizo dentro de "opciones"
            function validarForm(formData, jqForm, options){
                var bError = false;
                $("#cargando").fadeIn(); //muestro el loader de ajax
                var form = jqForm[0];
                if ((!form.DNI2.value) && (!form.Apellido.value)) {
                    $("#dni_error").show();
                    $("#apellido_error").show();
                    $("#cargando").fadeOut();
                    return false;
                }
                $("#dni_error").hide();
                $("#apellido_error").hide();
                $("#cargando").fadeOut();
                return true;
            };
            function mostrarRespuesta (responseText){
                //responseText es lo que devuelve la p�gina buscarPersona.php. Si en buscarPersona.php hacemos echo "Hola" , la variable responseText = "Hola"				  
                $("#mostrar").html(responseText); // Aca utilizo la funci�n append de JQuery para a�adir el responseText  dentro del div "ajax_loader"
                $("#cargando").fadeOut();
            };
		
		

            //lugar donde defino las funciones que utilizo dentro de "opciones"
            function validarFormNuevo(formData, jqForm, options){
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

            function mostrarRespuestaNuevo (responseText){
                //responseText es lo que devuelve la p�gina buscarPersona.php. Si en buscarPersona.php hacemos echo "Hola" , la variable responseText = "Hola"				  
                $("#mostrar").html(responseText); // Aca utilizo la funci�n append de JQuery para a�adir el responseText  dentro del div "ajax_loader"
                $("#cargando").fadeOut();
            };

            $("#mostrarBuscador").hide();
            $("#mostrarNuevo").show();
            $("#mostrarEditar").hide();
            $("#mostrarEditar").empty();
            $("#mostrar").empty();
            $("#form_nueva2 #DNI").focus();
            $("#barraBuscar").click(function(evento){
                evento.preventDefault();
                $("#mostrarBuscador").fadeIn("slow");
                $("#mostrarNuevo").hide();
                $("#mostrarEditar").hide();
                $("#mostrarEditar").empty();
                $("#mostrar").empty();
            });
            $("#barraGuardar").click(function(evento){
                evento.preventDefault();
                $("#form_nueva2").submit();
            });
            $("#barraGuardar2").click(function(evento){
                evento.preventDefault();
                $("#form_nueva2").submit();
            });

            $("#barraNuevo").click(function(evento){
                evento.preventDefault();
                $("#mostrarBuscador").hide();
                $("#mostrarEditar").hide();
                $("#mostrarEditar").empty();
                $("#form_nueva2 #DNI").attr("value","");
                $("input#Apellidos").attr("value","");
                $("input#Nombre").attr("value","");
                $("select#Sexo").attr("value","-1");
                $("select#Extranjero").attr("value","0");
                $("select#DocID").attr("value","1");
                $("input#Alternativo").attr("value","");
                $("input#file").attr("value","");
                $("#upload_button").html("<img src='botones/Load.png' alt='Subir foto' title='Subir foto' /><br />Subir foto");
                $("#lista").empty();
                $("#mostrarNuevo").fadeIn();
                $("#mostrar").empty();
            });//fin evento click
            //*/


            $("#form_nueva2").validate({
                submitHandler: function(form) {   	
                    $(form).ajaxForm(opciones_nuevo);
                    return false;
                }
            });

            //Evento que se ejecuta
            $("#form_nueva2 #DNI").blur(function() {
                $('#lista').load("cargar_foto.php", {
                    DNI: $("#form_nueva2 #DNI").get(0).value, 
                    DocID: $("#DocID").get(0).value
                });
                iDNI = $("#form_nueva2 #DNI").val();
                if (iDNI.length==0) return;
                if (iDNI.length>8 || iDNI.length<7)
                    mostrarAlerta("Ha ingresado un n�mero de documento superior a <strong>8 d�gitos o menor a 7</strong>. Por favor verifique el n�mero. Igual puede continuar", "Atenci�n");
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
                        var valor = $("#form_nueva2 #DNI").get(0).value;
                        if (valor.length==0){
                            alert('Error: Escriba un n�mero de documento antes de subir un archivo.');
                            return false;
                        }else{
                            //Cambio el texto del boton y lo deshabilito
                            var vDNI = $('#form_nueva2 #DNI').get(0).value;
                            var vDocID = $('#DocID').get(0).value;
                            upload.setData({
                                'DNI': vDNI, 
                                'DocID': vDocID
                            });
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
            //fin de subir foto

            $("#boton_buscar").click(function(evento){
                evento.preventDefault();
                $("#form_persona").submit();
		
            });
	
            function buscarDatos(vDNI){
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                        alert(textStatus);
                    },
                    data: {
                        opcion: "buscarDatosPersona", 
                        DNI: vDNI
                    },
                    url: 'cargarOpciones.php',
                    success: function(data){ 
                        if (data!="{}"){
                            var obj = $.parseJSON(data);
                            $("#Apellidos").val(obj.Per_Apellido);
                            $("#Nombre").val(obj.Per_Nombre);
                            $("#Sexo").val(obj.Per_Sexo);
                            $("#Extranjero").val(obj.Per_Extranjero);
                            $("#Alternativo").val(obj.Per_Alternativo);
					
                        }else {
                            //$("#mostrar").empty();					
                        }
                    }
                });//fin ajax//*/
            }
	
            $("#DNI").keyup(function(evento){	
                evento.preventDefault();
                //alert("Hola");
                vDNI = $("#DNI").val();
                if (evento.keyCode == '13'){
                    $("#mostrar").empty();
                    $("#cargando").show();
                    buscarDatos(vDNI);
                    $("#cargando").hide();
                }
            });
            $("#DNI").focusout(function(evento){	
                evento.preventDefault();
                vDNI = $("#DNI").val();
                buscarDatos(vDNI);
            });
	
            $("#Apellido").keyup(function(event){
                event.preventDefault();
                vApellido = $("#Apellido").val();
                //alert("Hola " + event.keyCode);
                if (event.keyCode == 13 || vApellido.length>2) {  
                    //alert("Apret� enter");   		
                    $.ajax({
                        type: "POST",
                        cache: false,
                        async: false,
                        error: function (XMLHttpRequest, textStatus){
                            alert(textStatus);
                        },
                        data: {
                            Apellido: vApellido
                        },
                        url: 'buscarPersona.php',
                        success: function(data){ 
                            $("#mostrar").html(data);
                        }
                    });//fin ajax//*/
                }

            });
        });//fin de la funcion ready

        $(function() {
            $(".botones").button();
        });
    });//fin de la funcion ready
</script>
<style type="text/css">
    <!--
    .Estilo2 {font-size: 12pt; font-weight: bold;}
    -->
</style>
<body >

    <div id="buscar" class="texto">
        <fieldset>
            <legend>Buscar aspirante</legend>
            Ciclo Lectivo: <?php cargarListaLectivo("LecID");
?><br>
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
    </div>


    <div id="nuevo" class="texto">

        <h2> Nuevo Aspirante </h2>

        <form id="formID" class="formular" method="get">

            <fieldset>
                <legend>Datos Básicos</legend>
                <input type="hidden" name="LecID2" id="LecID2">


                <p>
                    <label for="textfield">Tipo de Documento:</label>
                    <?php cargarListaTipoDoc("DocID"); ?>
                </p>
                <p>
                    <label for="textfield">Nº Documento:</label>
                    <input name="DNI" type="TEXT" id="DNI"  class="input_sesion" value=""  min="8" max="8" maxlength="8">
                </p>

                <p>
                    <label for="textfield">Apellidos:</label>
                    <input name="Apellidos" type="text" id="Apellidos"  class="required" value="">
                    <label class="error_buscador" id="errorNuevoApellido">falta cargar </label></td>
                </p>

                <p>
                    <label for="textfield">Nombres:</label>
                    <input name="Nombre" type="text" id="Nombre"  class="required" value="">
                    <label class="error_buscador" id="errorNuevoNombre">falta cargar </label>
                </p>

                <p>
                    <label for="textfield">Sexo:</label>

                    <select name="Sexo" id="Sexo" class="required" >
                        <option value="-1">Elegir una opción</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </p>

            </fieldset>
            <fieldset>
                <legend>DatosAcademicos</legend>    

                <p><label for="textfield">Numero de Legajo: </label>
                    <input name="Legajo" type="text" id="Legajo"  class="required" value="">
                    <button id="GenerarLegajoColegio" title="Genera un número de legajo" class="botones"><img src="imagenes/bg-checkbox-checked.gif" width="18" height="18" align="absmiddle" /> Generar legajo</button>
                </p>

                <p>
                    <label for="textfield">Lectivo: </label>
                    <input name="Legajo" type="text" id="Legajo"  class="required" value="2013" disabled>


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
cargarListaCursos("EntCurID", true);
?>
                </p>

            </fieldset>
            <fieldset>
                <legend>Datos de Nacimiento</legend>
                <p>
                    <label for="textfield">Fecha de nacimiento:</label>
                    <input name="fechaNac" type="input" id="fechaNac" class="required" >
                </p>

                <p>
                    <label for="textfield">País de nacimiento:</label>
<?php cargarListaPais("NacPaisID", 1); ?>
                </p>

                <p>
                    <label for="textfield">Provincia de nacimiento:</label>
<?php cargarListaProvincia("NacProID", 1); ?>
                </p>

                <p>
                    <label for="textfield">Localidad de nacimiento:</label>
<?php cargarListaLocalidad("NacLocID", 1, 2); ?>
                </p>

            </fieldset>

            <fieldset>
                <legend>Datos del Domicilio Real</legend>
                <p>
                    <label for="textfield">Dirección completa:</label>
                    <input name="direccion" type="text" id="direccion" size="50" class="required">
                </p>
                <p>
                    <label for="textfield">País:</label>
<?php cargarListaPais("DomPaisID"); ?>
                </p>
                <p>
                    <label for="textfield">Provincia:</label>
<?php cargarListaProvincia("DomProID", 1); ?>
                </p> 
                <p>
                    <label for="textfield">Localidad:</label>
<?php cargarListaLocalidad("DomLocID", 1, 2); ?>
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
                    <input name="telefono" type="text"  id="telefono" size="50" class="required">
                </p>
                <p>
                    <label for="textfield">Teléfono celular:</label>
                    <input name="celular" type="text" id="celular" size="50">
                    <input name="Ocupacion" type="hidden" id="Ocupacion" size="40" maxlength="60" autocomplete="off" class="ac_input">
                </p>
                <p>&nbsp;</p>
            <!--   <p>
                <label for="textfield">Observaciones:</label>
                <textarea name="observ" cols="20" rows="5" id="observ"></textarea>
               </p>-->
                <p>
                    <input class="submit" type="submit" value="Guardar y Nuevo" id="botonGuardar" name="botonGuardar" />
                    <input type="button" value="Guardar y Cargar Entrevista" id="botonEntrevista" name="botonEntrevista" />     
                </p>
            </fieldset> 
        </form>
    </div>

    <div id="entrevista" class="texto">

        <h2> Nueva Entrevista </h2>
        <form id="formEntrevista" class="formular" method="post">

            <fieldset>


                <input type="hidden" name="opcion" id="opcion" value="guardarEntrevista" />
                <p>
                    <label for="textfield">Nº Documento:</label>
                    <input name="DNI_Ent" type="TEXT" id="DNI_Ent"  class="required" value=""  min="8" max="8" maxlength="8">
                </p>

                <p>
                    <label for="textfield">Apellidos:</label>
                    <input name="Apellidos_Ent" type="text" id="Apellidos_Ent"  class="required" value="">
                </p>

                <p>
                    <label for="textfield">Nombres:</label>
                    <input name="Nombre_Ent" type="text" id="Nombre_Ent"  class="required" value="">
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

<!--                <p>
                    <label for="textfield">Nivel: </label>
                    < ?php
                    cargarListaNivel("EntNivID");
                    ?>
                </p>
 
                <p>
                    <label for="textfield">Curso: </label>
                    < ?php
                    cargarListaCursos("EntCurID", true);
                    ?>
                </p>-->


                <p>
                    <label for="textfield">Fecha de la Entrevista: </label>
                    <input name="Ent_Fecha" type="input" id="Ent_Fecha"  class="required" value="">
                </p>

                <p>
                    <label for="textfield">Hora de la Entrevista: </label>
                    <input name="Ent_Hora" type="text" id="Ent_Hora"  class="required" value="">
                </p>

                <p> 
                    <label for="textfield">Psicopedagoga: </label>



<!--<select id="Ent_Sic_ID" name="Ent_Sic_ID">
  </select>-->
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

                <td width="48"><button style="width:48px" class="barra_boton" id="barraSiguiente"><img src="imagenes/go-next.png" alt="Siguiente" title="siguiente" width="22" height="22" border="0" /><br />
                        Siguiente</button></td> 
                </p>

            </fieldset> 


        </form>
    </div>


