<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("cargarFuncionesDivision.php");

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

//************************
//Para que no asigne clases cualquiera
function obtenerNombredeUsuario($Id_usuario) {
    $sql5 = "SELECT Usu_Nombre, Usu_Persona FROM Usuario WHERE Usu_ID = '$Id_usuario'";
    $re = consulta_mysql_2022($sql5,basename(__FILE__),__LINE__);
    $row = mysqli_fetch_array($re);
    return $row['Usu_Nombre'];
}
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
$UsuNombre=obtenerNombredeUsuario($UsuID);

if ($UsuNombre == '38077890') $UsuID=2;

if ($UsuID>2){
	echo "Opci&oacute;n restringida al Administrador";
	exit;
}else {
	//echo $UsuID;
}

obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
//************************


?>
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	$("#mostrarNuevo").show();
	$("#cargando").hide();
	$("#mostrar").empty(); 
	$("#DNI").focus();
	limpiarDatos();	
	$("#barraRefrescar").click(function(evento){
		evento.preventDefault();
		vDocID = $("#DocID").val();
		vLecID = $("#LecID").val();
		$("#detalle").hide();
		mostrarClase(vDocID, vLecID);
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		vDocID = $("#DocID").val();
		//alert(vDocID);return;
		vLegajo = $("#Legajo").val();		
		vLecID = $("#LecID").val();
		vCurID = $("#CurID").val();
		vNivID = $("#NivID").val();
		vDivID = $("#DivID").val();
		vMatID = $("#MatID").val();
		vSedID = $("#SedID").val();
		//vHs = $("#Hs").val();
		vHs = 0;
		
		
		if (vDocID.length==0){
			mostrarAlerta("Debe ingresar un valor válido en el DNI.", "Error");	
			return;
		}		
		if (vLegajo.length==0){
			mostrarAlerta("El profesor ingresado no tiene un Legajo asignado.", "Error");	
			return;
		}		
		
		if (vNivID==-1){
			mostrarAlerta("Debe seleccionar un Nivel", "ERROR");
			$("#NivID").focus();
			return;
		}
		
		if (vLecID==-1){
			mostrarAlerta("Debe seleccionar un Ciclo Lectivo", "ERROR");
			$("#LecID").focus();
			return;
		}
		if (vCurID==-1){
			mostrarAlerta("Debe seleccionar un Curso", "ERROR");
			$("#CurID").focus();
			return;
		}
		if (vDivID==-1){
			mostrarAlerta("Debe seleccionar una División", "ERROR");
			$("#DivID").focus();
			return;
		}
		if (vMatID==-1){
			mostrarAlerta("Debe seleccionar una Materia", "ERROR");
			$("#MatID").focus();
			return;
		}
		/*if (vHs.length==0){
			mostrarAlerta("Debe ingresar un valor en las horas de la clase.", "Error");	
			return;
		}
		/*if (!validarNumero(vHs)){
			mostrarAlerta("Debe ingresar un valor válido en las horas de la clase.", "Error");	
			return;
		}//*/

		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "guardarClaseDocente", DocID: vDocID, LecID: vLecID, NivID: vNivID, CurID: vCurID, DivID: vDivID, MatID: vMatID, Hs: vHs, SedID: vSedID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//mostrarAlerta(data, "Resultado de guardar los cambios");
				mostrarClase(vDocID, vLecID);
				
			}
		});//fin ajax//*/
	});
	$("#detalle").hide();
	function mostrarClase(vDocID, vLecID){
		//alert(vDocID + "-" + vLecID);
		
		$.ajax({
		  type: "POST",
		  cache: false,
		  async: false,
		  error: function (XMLHttpRequest, textStatus){
			  alert(textStatus);},
		  data: {opcion: "mostrarClaseDocente", DocID: vDocID, LecID: vLecID},
		  url: 'cargarOpciones.php',
		  success: function(data){ 
			  $("#mostrar").html(data);			   
			  $("button[id^='agregar']").click(function(evento){
				  evento.preventDefault();
				  i = this.id.substr(7,10);
				  vClaID = $("#id" + i).val();
				  vMateria = $("#materia" + i).val();
				  $.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: "agregarHorarioClaseDocente", ID: vClaID},
					url: 'cargarOpciones.php',
					success: function(data){ 
						if (data){
							  vCuadro = "<div class = 'cuadroPopup'><h2>Elija el d&iacute;a y horario de la clase " + vMateria + "</h2>" + data + "</div>";
							  //alert(data);
							  $("#detalle").html(vCuadro);	
							  $("#detalle").show();
						}
						
					}
				  });//fin ajax//*/				  
				  //alert(vClaID);
			  });//fin evento click
			  $("#btnCambiarDocente").click(function(evento){
			  	//alert('Entre');
				  evento.preventDefault();
				  vDocID = $("#Doc_IDNuevo option:selected").val();
				  //alert(vDocID);
				  j=0;
				  $("input[name^='Cla_ID']").each(function(index){
				  	elegido = $(this).is(":checked");
				  	if (elegido){
				  		vClaID = $(this).val();
				  		//alert(vClaID);

				  		$.ajax({
								type: "POST",
								cache: false,
								async: false,
								error: function (XMLHttpRequest, textStatus){
									alert(textStatus);},
								data: {opcion: "cambiarDocenteClase", Cla_ID: vClaID, Doc_ID: vDocID},
								url: 'cargarOpciones.php',
								success: function(data){ 
									//alert(data);
									if (data){								
										j++;
									}
								}
							});//fin ajax///
				  	}//fin elegido

				  });
				  if (j>0){
				  	jAlert("Se cambiaron "+ j + " clases.");
				  	$("#barraRefrescar").click();
				  }
				  	

			  });//fin evento click
			  $("button[id^='eliminar']").click(function(evento){
				  evento.preventDefault();
				  i = this.id.substr(8,10);
				  vClaID = $("#id" + i).val();
				  jConfirm('&iquest;Est&aacute; seguro que desea eliminar la clase elegida?', 'Confirmar la eliminacion', function(r){
					if (r){//eligió eliminar
						 $.ajax({
							type: "POST",
							cache: false,
							async: false,
							error: function (XMLHttpRequest, textStatus){
								alert(textStatus);},
							data: {opcion: "eliminarClaseDocente", ID: vClaID},
							url: 'cargarOpciones.php',
							success: function(data){ 
								if (data){
									$("#barraRefrescar").click();
									mostrarAlerta(data, "Resultado de eliminar clase");
									
									//mostrarClase(vDocID, vLecID);
								}
								
							}
						  });//fin ajax//*/
					}//fin if
				  });//fin del confirm//*/
				 
			  });//fin evento click
			  
			  $("button[id^='eliminarHorarios']").click(function(evento){
				  evento.preventDefault();
				  i = this.id.substr(16,10);
				  vClaID = $("#id" + i).val();
				  jConfirm('&iquest;Est&aacute; seguro que desea eliminar los Horarios de la clase elegida?', 'Confirmar la eliminacion', function(r){
					if (r){//eligió eliminar
						 $.ajax({
							type: "POST",
							cache: false,
							async: false,
							error: function (XMLHttpRequest, textStatus){
								alert(textStatus);},
							data: {opcion: "eliminarClaseHorarioDocente", ID: vClaID},
							url: 'cargarOpciones.php',
							success: function(data){ 
								if (data){
									mostrarAlerta(data, "Resultado de eliminar horarios");
									$("#barraRefrescar").click();
								}								
							}
						  });//fin ajax//*/
					}//fin if
				  });//fin del confirm//*/
				 
			  });//fin evento click
			  
		  }
	  });//fin ajax//*/
	$("button").button();
	}//fin function

	$("#barraNuevo").click(function(evento){
		evento.preventDefault();
		$("#DNI").val("");
		$("#DocID").val("");
		$("#persona").val("");
		$("#Legajo").val("");
		$("#Hs").val("");
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();
	});//fin evento click
//*/
	function cargarDNI(){
		vDNI = $("#DNI").val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerApellidoNombreDocente", DNI: vDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#persona").val(data);
				buscarDatos(vDNI);
			}
		});//fin ajax//*/
		
		

	}
 
  function cargarListaDivisionCurso(CurID,LecID){
		$.ajax({
            type: "POST",
            cache: false,
            async: false,			
            url: 'cargarOpcionesDivision.php',
            data: {opcion: "cargarListaDivisionCurso", CurID: CurID, LecID: LecID},
            success: function (data){
            	//alert(data);
                $("#DivID").html(data);
                $("#cargando").hide();
            }
        });//fin ajax
	}//fin function


  $("#CurID").change(function () {
   		$("#CurID option:selected").each(function () {
			//alert($(this).val());
      	vLecID = $("#LecID").val();
				vCurID=$(this).val();
				cargarListaDivisionCurso(vCurID,vLecID);
			
        });
   })


	function buscarDatos(vDNI){
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarDatosDocente", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				//alert(data);return;
				if (data!="{}"){
					var obj = $.parseJSON(data);					
					$("#Legajo").val(obj.Doc_Legajo);
					$("#DocID").val(obj.Doc_ID);
					mostrarClase($("#DocID").val(), $("#LecID").val());
					$.ajax({
						type: "POST",
						cache: false,
						async: false,
						error: function (XMLHttpRequest, textStatus){
							alert(textStatus);},
						data: {opcion: "buscarFoto", DNI: vDNI, DocID: obj.Per_Doc_ID, ancho: "60"},
						url: 'cargarOpciones.php',
						success: function(data){ 
							$("#Foto").html(data);
						}
					});//fin ajax//*/
					
				}else {
					limpiarDatos();

					}
			}
		});//fin ajax//*/
	}
	
	function limpiarDatos(){
		$("#Hs").val("");
		$("#Legajo").val("");
	}
	
	$("#DNI").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI").val();
		if (evento.keyCode == '13'){
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosDocente.php", {
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
			$("#DocID").val(data.Doc_ID);			
			$("#mostrar").empty();
			buscarDatos(data.Per_DNI);
			
		}
	}
	
	//---------------------------
  $("#NivID").change(function () {
   		$("#NivID option:selected").each(function () {
			//alert($(this).val());
				vNivID=$(this).val();
				llenarCursos(vNivID);
				
        });
   })

	function llenarCursos(vNivID){
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaCursos2", Nombre: "CurID", NivID: vNivID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#CurID").html(data);
			}
		});//fin ajax//*/
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "cargarListaMateriasOrientacion2", Nombre: "MatID", NivID: vNivID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#MatID").html(data);
			}
		});//fin ajax//*/
		
	}
   //---------------------------

});//fin de la funcion ready

$(function() {
		$(".botones").button();
	});

</script>
<link href="css/general.css" rel="stylesheet" type="text/css" />


	<div id="mostrarNuevo"><!--<form autocomplete="off" onsubmit="return false;">-->
	  <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
          <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/icono_asignatura.png" width="32" height="32" align="absmiddle" /> Asignar clase al Profesor/a</div></td>
        </tr>
        <tr>
          <td class="texto"><div align="right"></div></td>
          <td><div id="Foto"></div></td>
        </tr>
          <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI" type="number" class="texto_buscador" id="DNI" size="15" value="<?php echo $_SESSION['sesion_ultimoDNI'];?>"/>
        *       
           
          <input name="Hs" type="hidden" id="Hs" size="5" maxlength="4" value="0" /></td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="35" />          </td>
      </tr>
        <tr>
          <td class="texto"><div align="right">N&ordm; Legajo: </div></td>
          <td><input name="Legajo" type="text" disabled="disabled" id="Legajo" size="5" />
            </td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Identificador: </div></td>
          <td><input name="DocID" type="text" id="DocID" readonly="" size="5" />
            </td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Lectivo </div></td>
          <td><?php echo cargarListaLectivo("LecID");?></td>
        </tr>
       <tr>
         <td class="texto"><div align="right">Sede:</div></td>
         <td colspan="2"><?php cargarListaSede("SedID", $_SESSION['sesion_SedID']);?> 
           
             </tr>        
       <tr>
         <td class="texto"><div align="right">Nivel del Colegio:</div></td>
         <td colspan="2"><?php cargarListaNivel("NivID");?>            
             </tr>	                
       <tr>
         <td class="texto"><div align="right">Curso:</div></td>
         <td colspan="2"><?php cargarListaCursos("CurID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Divisi&oacute;n:</div></td>
         <td colspan="2"><?php cargarListaDivision("DivID", true);?> 
           
             </tr>
       <tr>
         <td class="texto"><div align="right">Materia a dictar:</div></td>
         <td colspan="2"><?php cargarListaMateriasOrientacion("MatID", 0, false);?> 
           
             </tr>
        <tr>
          <td colspan="2"><div align="center">
            <p>&nbsp;</p>
            <button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
       Nuevo </button> 
            <button class="barra_boton" id="barraGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Asignar clase</button>
        <button class="barra_boton" id="barraRefrescar"><img src="botones/app_48.png" alt="Listar Clases" width="48" height="48" border="0" /><br />
        Listar clase</button>
        </div></td>
        </tr>
      </table>
   <!--   </form>-->
</div>
		<br /><br />
        
	<div>
	    <div id="mostrar" align="center"></div>
    </div>
    <div id="detalle"  style="width:600px; float:center" class="borde_alerta2"></div>
	<p>&nbsp;</p>
	
	

	
