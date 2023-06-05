<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$(document).ready(function(){

	
	$("#fechaNac").datepicker($.datepicker.regional['es']);
	$("#NacPaisID").attr("value","0");
	$("#DomPaisID").attr("value","0");
	
	vDNI = 0;
 
	$("#DNI").focus();
	$("#Persona").hide();
	
	<?php
	 if (isset($_SESSION['sesion_ultimoDNI'])){
		if (is_numeric($_SESSION['sesion_ultimoDNI'])){
			$DNI = $_SESSION['sesion_ultimoDNI'];
			//echo "alert('$DNI');";
			echo "$('#DNI').val($DNI);";
			echo "cargarDNI();";
		}

	}
	
	if (isset($_POST['DNI'])){
		$DNI_Volver = $_POST['DNI_Volver'];
		$DNI = $_POST['DNI'];		
		echo "$('#DNI').val($DNI);";
		//echo "alert($('#DNI').val());";
		echo "cargarDNI();";
		if (isset($_POST['NivID']) && isset($_POST['PerID'])){
			$PerID = $_POST['PerID'];
			$NivID = $_POST['NivID'];
			echo "$('#NivID').val($NivID);";
			echo "cargarRequisitoPersona($NivID, $PerID);";
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
		vLecID = $("#LecID").val();	
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {opcion: 'eliminarRequisitoPersona', NivID: vNivID, PerID: vPerID, LecID: vLecID},
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
				data: {opcion: "guardarRequisitoPersona", ReqID: vReqID, NivID: vNivID, PerID: vPerID, Campo: "Pre_Presento", Valor: vValor, LecID: vLecID},
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
				data: {opcion: "guardarRequisitoPersona", ReqID: vReqID, NivID: vNivID, PerID: vPerID, Campo: "Pre_Constancia", Valor: vValor, LecID: vLecID},
				success: function (data){
						$("#cargando").hide();
						}
			});//fin ajax//*/
		});

		mostrarAlerta("Se guardaron correctamente los cambios.", "Confirmación");
	});
   //---------------------------
   
   //---------------------------
   //---Eventos de los botones----------
   $("#barraDefecto").click(function(evento){
		evento.preventDefault();				
		//Carga al país Argentina, la provincia San Juan y la ciudad Capital por defecto.
		vPaisDefecto = 1;
		vProDefecto = 2;
		vLocDefecto = 2;
		mostrarAlerta("Se seleccionó al país <strong>Argentina</strong> y la provincia <strong>San Juan</strong> por defecto.<br />Haga click en el botón <strong>Nuevo</strong> para ver los resultados", "Información");
	});
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		//limpiarDatos();
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
   });
   $("#LecID").change(function () {
   		$("#LecID option:selected").each(function () {
			//alert($(this).val());
				vNivID=$("#NivID").val();
				vPerID = -1;
				vPerID=$("#PerID").val();
				//alert(vPerID);
				if (vPerID == -1){
					mostrarAlerta("Debe seleccionar una persona", "ERROR");
					return;
				}
				cargarRequisitoPersona(vNivID, vPerID);
        });
   });
	
	function cargarRequisitoPersona(vNivID, vPerID){
		vLecID = $("#LecID").val();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {NivID: vNivID, PerID: vPerID, LecID: vLecID},
				url: 'buscarRequisitoPersona.php',
				success: function (data){
						$("#mostrar").html(data);
						$("#cargando").hide();
						}
			});//fin ajax
	}
});//fin de la funcion ready
</script>
<style type="text/css">
<!--
.Estilo2 {font-size: 12pt; font-weight: bold;}
-->
</style>
<table border="0" align="center" cellspacing="4">
  <tr>

<!--    <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
      Nuevo </button></td>-->
    <td width="48"><button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
      Guardar</button></td>

  </tr>
</table>

	<div id="mostrarNuevo">

	  <table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
         <td colspan="2"><div align="center"><span class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /></span><span class="titulo_noticia"> Requisitos de ingreso del alumno</span></div></td>
        </tr>
       <tr> <td class="texto"> <div align="right"><strong>Documento:</strong></div></td>
        <td><input name="DNI" type="text" class="texto_buscador required digits" id="DNI" />
        *       
          <input name="PerID" type="hidden" id="PerID" />
          <input type="hidden" name="DNI_Volver" id="DNI_Volver" value="<?php echo $_POST['DNI_Volver'];?>" />
          <input type="hidden" name="pag_Volver" id="pag_Volver" value="<?php echo $_POST['pag_Volver'];?>"/>
        </tr>
       <tr>
         <td align="right" class="texto"><strong>Buscar Persona   :</strong></td>
         <td><input name="personaNombre" type="text" id="personaNombre" size="35" />       
        </tr>
       <tr>
         <td colspan="2" class="texto"><div id="Persona"></div>       </td>
        </tr>
       <tr>
         <td class="texto"><div align="right">Nivel: </div></td>
         <td><?php cargarListaNivel("NivID");?></tr>
          <tr>
         <td class="texto"><div align="right">Lectivo: </div></td>
         <td><?php cargarListaLectivoInscripcion("LecID");?></tr>
       <tr>
         <td colspan="2" class="texto"><div id="mostrar"></div>       </td>
        </tr>
    </table>

		
</div>



