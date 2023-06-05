<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");
$Tabla = "Egreso_Recibo";

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="plugin/css/screen.css" />


<script language="javascript">

$(document).ready(function(){
	
	
	$("#mostrar").empty();
	
	
	
	function recargarPagina(){
		$("#mostrar").empty();

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
	
	$("#Anio").change(function () {
   		$("#Anio option:selected").each(function () {
			//alert($(this).val());
				//Anio=$(this).val();
				
				//$("#barraBuscar").click();
				buscarIngresos();
        });
   });
   $("#TipoEgreso").change(function () {
   		$("#TipoEgreso option:selected").each(function () {
			//alert($(this).val());
				//TipoEgreso=$(this).val();
				
				$("#barraBuscar").click();
        });
   });
   buscarIngresos();
	function buscarIngresos(){

		Anio = $("#Anio").val();
		$("#cargando").show();
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {Anio: Anio},
				url: 'opcionesCargarIngreso.php',
				success: function(data){ 
					//alert(data);
					$("#mostrarResultado").html(data);
					$("#cargando").hide();
				}
			});//fin ajax//*/
		//recargarPagina();
		$("#cargando").hide();
	};//fin function
	
	
	//Datos iniciales
	//$("#loading").hide();
	//$("#Cuenta").focus();

	 
});//fin de la funcion ready


</script>

<p></p>
<div id="divBuscador">
      
   <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Ingresos por Cuotas</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">Año para comparar: <?php cargarListaAnios("Anio");?></td>
          </tr>
          <tr>
            <td align="center" class="texto">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" class="texto">
            
            <div id="mostrarResultado">
            
            </div>
            
            
                      
            </td>
          </tr>
        </table>
      
</div>
	<p><br />
	  <br />
    </p>
	<div id="mostrar"></div>
	<p>&nbsp;</p>
	