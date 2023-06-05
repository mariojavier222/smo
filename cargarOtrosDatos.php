<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("cargarOpciones.php");

?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$(document).ready(function(){

// definimos las opciones del plugin AJAX FORM
	opciones= {
					   beforeSubmit: validarForm, //funcion que se ejecuta antes de enviar el form
					   success: datosEncontrados //funcion que se ejecuta una vez enviado el formulario
	};
	 //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
	
	$("#fechaNac").datepicker($.datepicker.regional['es']);
	$("#NacPaisID").attr("value","1");
	$("#DomPaisID").attr("value","1");
	
	vPaisDefecto = 1;
	vProDefecto = 1;
	vLocDefecto = 1;
	vDNI = 0;
	limpiarDatos();
	$("#DNI").focus();
	$("#Persona").hide();
	
		
	<?php 
	if (isset($_POST['DNI'])){
		$DNI_Asp = $_POST['DNI_Asp'];
		$DNI_Padre = $_POST['DNI_Padre'];
		//echo "buscarDNI($DNI);";
		//echo "cargarFamilia($DNI);";

	}
	?>
	
	$("#barraSiguiente").click(function(evento){
		evento.preventDefault();
		vDNI = $("#DNI_Asp").val();
		vDNI_Padre = $("#DNI_Padre").val();
		vApellido_Asp = $("#Apellido_Asp").val();
		vNombre_Asp = $("#Nombre_Asp").val();
		vApellido_Padre = $("#Apellido_Padre").val();
		vNombre_Padre = $("#Nombre_Padre").val();
		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarFamilia.php',
				data: {DNI_Asp: vDNI_Asp, DNI_Padre: vDNI_Padre, Apellido_Asp: vApellido_Asp, Nombre_Asp: vNombre_Asp, Apellido_Padre: vApellido_Padre, Nombre_Padre: vNombre_Padre, pag_Volver: "cargarOtrosDatos"},
				success: function (data){
						$("#principal").html(data);
						//mostrarAlerta(data);
						$("#cargando").hide();
						}
			});//fin ajax
	});
		
	
	
	<?php
	if (isset($_POST['PerID'])){
		$DNI_Volver = $_POST['DNI_Volver'];
		$DNI = gbuscarDNI($_POST['PerID']);
		echo "$('#DNI').val($DNI);";
		//echo "alert($('#DNI').val());";
		echo "cargarDNI();";
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
			limpiarDatos();
			$("#cargando").show();
			cargarDNI();
		}//fin if
	});//fin de prsionar enter			
	function buscarDNI(DNI){
		$("#PerID").attr("value",0);
		if (validarNumero(DNI)){
			if (DNI != vDNI){
				vDNI = DNI;
				$.post("cargarOpciones.php",{opcion: 'buscarDNI', DNI: vDNI}, function(data){
					$("#Persona").show();
					$("#Persona").html(data);
					$.post("cargarOpciones.php",{opcion: 'buscarPerID', DNI: vDNI}, function(data){
						$("#PerID").attr("value",data);
						vPerID = $("#PerID").val();
						//alert(vPerID);
						if (vPerID>0){
							$.post("cargarOpciones.php",{opcion: 'buscarOtrosDatos', PerID: vPerID}, function(data){
								if (data!="{}"){
									var obj = $.parseJSON(data);									
									$("#fechaNac").val(obj.Dat_Nacimiento);
									$("#NacPaisID").val(obj.Dat_Nac_Pai_ID);
									llenarProvincia("Nac", obj.Dat_Nac_Pai_ID, obj.Dat_Nac_Pro_ID);								
									llenarLocalidad("Nac", obj.Dat_Nac_Pro_ID, obj.Dat_Nac_Pai_ID, obj.Dat_Nac_Loc_ID);
									$("#DomPaisID").val(obj.Dat_Dom_Pai_ID);
									llenarProvincia("Dom", obj.Dat_Dom_Pai_ID, obj.Dat_Dom_Pro_ID);								
									llenarLocalidad("Dom", obj.Dat_Dom_Pro_ID, obj.Dat_Dom_Pai_ID, obj.Dat_Dom_Loc_ID);
									$("#direccion").val(obj.Dat_Domicilio);
									$("#cp").val(obj.Dat_CP);
									$("#EntID").val(obj.Dat_Ent_ID);
									llenarNiveles(obj.Dat_Ent_ID);
									$("#correo").val(obj.Dat_Email);
									$("#telefono").val(obj.Dat_Telefono);
									$("#celular").val(obj.Dat_Celular);
									$("#Ocupacion").val(obj.Dat_Ocupacion);
									$("#observ").val(obj.Dat_Observaciones);
								}else {
									//limpiarDatos();
									}
							});
						}else{
							limpiarDatos();
						}//fin if

					});

				});
			}
		}//fin if
	}//fin funcion
	function limpiarDatos(){
		//$("#DNI").val("");
		$("#fechaNac").val("");
		$("#direccion").val("");
		$("#PerID").val("");
		$("#Persona").empty();		
		llenarProvincia("Nac", vPaisDefecto, vProDefecto);
		llenarLocalidad("Nac", vProDefecto, vPaisDefecto, vLocDefecto);
		llenarProvincia("Dom", vPaisDefecto, vProDefecto);
		llenarLocalidad("Dom", vProDefecto, vPaisDefecto, vLocDefecto);
		$("#cp").val("");
		$("#correo").val("");
		$("#telefono").val("");
		$("#celular").val("");
		$("#observ").val("");
		

	}//fin funcion
	
	
	 //lugar donde defino las funciones que utilizo dentro de "opciones"
	 function validarForm(formData, jqForm, options){
		  $("#cargando").fadeIn(); //muestro el loader de ajax
		  var form = jqForm[0]; 
    	  var error="";
			//if (!form.DNI.value) { 
				//alert('Por favor ingrese un DNI para buscar otros datos');         		
				$("#cargando").fadeOut();
				//return false; 
    		//} 
    		//alert('Todo bien.'); 		  
	 };
	 
	
	 function datosEncontrados(responseText){		 
   	 	mostrarAlerta(responseText, "Resultado de guardar los cambios");		
		$("#cargando").fadeOut();
	 };

	$("#form_nuevo").validate({
	   		submitHandler: function(form) {   	
				$(form).ajaxForm(opciones);
				//return false;
	   		}/*,
			rules :
            myDate : {
                fechaCompleta : true
            }//*/
	});//*/
	 $("#barraGuardar").click(function(evento){
		evento.preventDefault();
		$("#form_nuevo").submit();
	});
   //---------------------------
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
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },		function(data){
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
   //---------------------------
   //---Eventos de los botones----------
   $("#barraDefecto").click(function(evento){
		evento.preventDefault();				
		//Carga al pa�s Argentina, la provincia San Juan y la ciudad Capital por defecto.
		vPaisDefecto = 1;
		vProDefecto = 2;
		vLocDefecto = 2;
		mostrarAlerta("Se seleccionó al país <strong>Argentina</strong> y la provincia <strong>San Juan</strong> por defecto.<br />Haga click en el botón <strong>Nuevo</strong> para ver los resultados", "Información");
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
    $("#EntID").change(function () {
   		$("#EntID option:selected").each(function () {
			//alert($(this).val());
				vEntID=$(this).val();
				llenarNiveles(vEntID);
        });
   	})
	function llenarNiveles(vID){
		$.post("cargarOpciones.php", { opcion: 'llenarNiveles', ID: vID }, function(data){
				//alert(data);
     			$("#NivID").html(data);
   		});
	}//fin funcion
	
	
	//------------Llenar Datos de Ocupacion-----------//
	
	$("#Ocupacion").result(colocarValorOcupacion);	
	$("#Ocupacion").autocomplete("buscarDatosOcupacion.php", {
		//multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,		
		formatItem:function(item, index, total, query){
		   return item.Dat_Ocupacion;
       },
       formatMatch:function(item){
           return item.Dat_Ocupacion;
       },
       formatResult:function(item){
           return item.Dat_Ocupacion;
       },

		dataType: "json",
		//parse: prep_data,
		parse:function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.Dat_Ocupacion,
					result: row.Dat_Ocupacion
				}
				$("#cargando").hide();
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	
	function colocarValorOcupacion(event, data, formatted) {
		if (data){
			$("#Ocupacion").val(data.Dat_Ocupacion);
		}
	}
	//------------FIn---------------------------------//
	
});//fin de la funcion ready
</script>
<style type="text/css">
<!--
.Estilo2 {font-size: 12pt; font-weight: bold;}
-->
</style>
<table border="0" align="center" cellspacing="4">
  <tr>
    <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
      Nuevo </button></td>
    <td width="48"><button class="barra_boton" id="barraBuscar"> <img src="botones/Search.png" alt="Buscar" width="48" height="48" border="0" title="Buscar Persona" /><br />
      Buscar</button></td>
    <td width="48"><button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      Guardar</button></td>
<td width="48"><button class="barra_boton" id="barraDefecto"><img src="botones/pais_defecto.png" alt="Seleccionar al pa&iacute;s Argentina y San Juan como localidades por defecto" title="Seleccionar al pa&iacute;s Argentina y San Juan como localidades por defecto" width="48" height="48" border="0" /><br />
      Defecto</button></td>      
<?php
if (isset($_POST['DNI_Padre'])){ 
	 
	 ?>
<td width="48"><button style="width:58px" class="barra_boton" id="barraSiguiente"><img src="imagenes/go-next.png" alt="Siguiente" title="siguiente" width="22" height="22" border="0" /><br />
      Siguiente</button></td>
<?php }//fin if?>
  </tr>
</table>

	<div id="mostrarNuevo">

	<form action="guardarOtrosDatos.php" method="post" name="form_nuevo" id="form_nuevo" >
	  <table width="80%" border="0" align="center" class="borde_recuadro">
	  <tr></tr>
      <tr>
        <td colspan="2"><div align="center"><span class="titulo_noticia"><img src="imagenes/hombre-mujer.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia">Otros datos de la persona </span></div></td>
      </tr>
      <tr>
        <td class="texto"><div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" value="<?php echo $_SESSION['sesion_ultimoDNI'];?>"/>
          *
          <input name="PerID" type="hidden" id="PerID" />
          <input name="DNI_Asp" type="hidden" id="DNI_Asp" value="<?php echo $_POST['DNI_Asp'];?>" />
          <input name="DNI_Padre" type="hidden" id="DNI_Padre" value="<?php echo $_POST['DNI_Padre'];?>" />
          <input type="hidden" name="Apellido_Asp" id="Apellido_Asp" value="<?php echo $_POST['Apellido_Asp'];?>" />
          <input type="hidden" name="Nombre_Asp" id="Nombre_Asp" value="<?php echo $_POST['Nombre_Asp'];?>" />
          <input name="Apellido_Padre" type="hidden" id="Apellido_Padre" value="<?php echo $_POST['Apellido_Padre'];?>" />
          <input name="Nombre_Padre" type="hidden" id="Nombre_Padre" value="<?php echo $_POST['Nombre_Padre'];?>" />
          <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" />
          <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/></td>
      </tr>
      <tr>
        <td align="right" class="texto"><strong>Buscar Persona   :</strong></td>
        <td><input name="personaNombre" type="text" id="personaNombre" size="35" /></td>
      </tr>
      <tr>
        <td colspan="2" class="texto"><div id="Persona"></div></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos de Nacimiento </div></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Fecha de nacimiento: </div></td>
        <td><input name="fechaNac" type="text" id="fechaNac" class="required fechaCompleta" />
          * </td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Pa&iacute;s de nacimiento </div></td>
        <td><?php cargarListaPais("NacPaisID");    ?></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Provincia de nacimiento </div></td>
        <td><?php cargarListaProvincia("NacProID",0);    ?></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Localidad de nacimiento </div></td>
        <td><?php cargarListaLocalidad("NacLocID",0,0);    ?></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos de domicilio real</div></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Direcci&oacute;n completa:</div></td>
        <td><input name="direccion" type="text" class="required" id="direccion" size="50" />
          * </td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Pa&iacute;s </div></td>
        <td><?php cargarListaPais("DomPaisID");    ?></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Provincia </div></td>
        <td><?php cargarListaProvincia("DomProID",0);    ?></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Localidad </div></td>
        <td><?php cargarListaLocalidad("DomLocID",0,0);    ?></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">C&oacute;digo Postal: </div></td>
        <td><input name="cp" type="text" id="cp" /></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos adicionales </div></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Correo electr&oacute;nico: </div></td>
        <td><input name="correo" type="text" class="email" id="correo" size="50" /></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Tel&eacute;fono fijo: </div></td>
        <td><input name="telefono" type="text" id="telefono" size="50" /></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Tel&eacute;fono celular: </div></td>
        <td><input name="celular" type="text" id="celular" size="50" /></td>
      </tr>
      <tr>
        <td align="right" bgcolor="#FFF7D5" class="texto">Ocupaci&oacute;n:</td>
        <td bgcolor="#FFF7D5"><input name="Ocupacion" type="text" id="Ocupacion" size="40" maxlength="60" /></td>
      </tr>
      <tr>
        <td class="texto"><div align="right">Observaciones:</div></td>
        <td><textarea name="observ" cols="20" rows="5" id="observ"></textarea></td>
      </tr>
      <tr></tr>
      <tr>
        <td class="texto">&nbsp;</td>
        <td></td>
      </tr>
<td></td>
</tr>
      </table>
	</form>
		
</div>

<div id="mostrar"></div>

