<?php
header("Cache-Control: no-cache, must-revalidate");
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "LibroVenta";
session_name("sesion_abierta");
// incia sessiones
session_start();

//echo "NAHUEL";
$PerID = $_POST['PerID'];
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);

$Lec_ID = gLectivoActual($Lec_Nombre);

$sql = "SELECT
    Persona.Per_DNI
    , Curso.Cur_Nombre
    , Colegio_Nivel.Niv_Nombre
    , Division.Div_Nombre
    , Colegio_Inscripcion.Ins_Lec_ID
	, Cur_ID, Div_ID, Niv_ID
FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID) WHERE Per_ID = $PerID AND Ins_Lec_ID = $Lec_ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
		$Cur_ID = $row[Cur_ID];	
		$Div_ID = $row[Div_ID];	
		$Niv_ID = $row[Niv_ID];	
	}//fin if
	

?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="plugin/css/screen.css" />

<script src="plugin/jquery.validate.js"></script>
<script src="plugin/localization/messages_es.js"></script>
<script src="plugin/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>

<script language="javascript">
$.validator.setDefaults({
	submitHandler: function() { 
		
		datos = $("#formDatos").serialize();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
				url: 'cargarOpciones.php',
			data: datos,
			success: function (data){
				jAlert(data, "Resultado de guardar los datos");
			}
		});//fin ajax
	}
});
$(document).ready(function(){
	
	//Datos iniciales
	$("#loading").hide();
	
	$("#formDatos").validate();//fin validation
		
	$(".botones").button();
	
	//$("#mostrarNuevo").hide();
	$("#divBuscador").hide();
	//$(".ocultar").hide();
	$("input[class^='ocultar']").hide();
	$("select[class^='ocultar']").hide();
	$("#mostrarLibroVenta").empty();
	$("#nombre").focus();
	$("#form_Buscador").unbind('submit');
	
	$("#buscarLibros").click(function(evento){
		evento.preventDefault();
		Cur_ID = $("#Lib_Cur_ID").val();		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarLibrosTabla", Cur_ID: Cur_ID, Per_ID: <?php echo $PerID;?>},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					//$("#loading").hide();
				}
			});//fin ajax//*/		
	});
	
	$("#buscarCuotas").click(function(evento){
		evento.preventDefault();
		Cur_ID = $("#Lib_Cur_ID").val();		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscarLibroCuotaPersona", Lec_ID: <?php echo $Lec_ID;?>, Per_ID: <?php echo $PerID;?>},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					//$("#loading").hide();
				}
			});//fin ajax//*/		
	});
	
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		$("#formDatos").submit();
	});
	
	function recargarPagina(){
		$("#mostrarLibroVenta").empty();

		$.ajax({
			cache: false,
			async: false,			
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
					$("#principal").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	}//fin function
	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#barraGuardar").hide();
		$("#mostrarNuevo").hide();
		$("#divBuscador").fadeIn();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Texto: "todos"},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					//$("#loading").hide();
				}
			});//fin ajax//*/
		//recargarPagina();
	});
	$("#mostrarTodo").click(function(evento){
		evento.preventDefault();
		$("#textoBuscar").val("todos");
		$("#textoBuscar").keyup();
	});
	$("#textoBuscar").keyup(function(event){
		event.preventDefault();
		$("#loading").show();
		vTexto = $("#textoBuscar").val();
		//alert("Hola " + event.keyCode);
		if (event.keyCode == 13 || vTexto.length>2) {  
			//alert("Hola " + event.keyCode);   	
			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "buscar<?php echo $Tabla;?>", Texto: vTexto},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					$("#loading").hide();
				}
			});//fin ajax//*/
   		}

	});
	
		
	function limpiarDatos(){
		$("input:text").val("");
		$("select").val(-1);
		$("textarea").val("");
	}
	
	<?php
	if ($Cur_ID>0){
		
	?>
		$("#Lib_Cur_ID").val("<?php echo $Cur_ID;?>");
	<?php
	}//fin if
	?>
	
	
	 
});//fin de la funcion ready


</script>

<div id="mostrarNuevo">
  <form id="formDatos" method="post" action="" class="cmxformNOOO">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia">  Venta de Libros</div></td>
      </tr>
          <tr>
            <td width="50%" align="right" class="texto">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td class="texto" align="right">
           <strong>Curso</strong>:</td>
            <td align="left"><?php
          cargarListaCursos("Lib_Cur_ID", false);
		  ?>
            <input name="opcion" type="hidden" id="opcion" value="guardarLibro" /></td>
        </tr>
          <tr>
            <td class="texto" align="right">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="texto"><button id="buscarLibros" class="botones">Buscar Libros</button><button id="buscarCuotas" class="botones">Buscar Cuotas</button></td>
          </tr>   
    </table>
    </form>

</div>
<div id="mostrarResultado"></div>