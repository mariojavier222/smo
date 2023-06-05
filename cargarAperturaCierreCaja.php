<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");

?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
    $(document).ready(function(){
	
	
	//ABRIR CAJA
	$("#barraAbrir").click(function(evento){
		v_importe = $("#importe").val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				url: 'cargarOpciones.php',
				data: {opcion: 'guardarCajaApertura', Importe: v_importe},
				success: function(data){ 
					if (data.trim()=='OK'){
						jAlert("Se guardaron correctamente los datos de Apertura.", "Confirmación");
					}
					$("#barraAbrir").hide();
					
					
				}
			});//fin ajax//*/ 
			$.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarCuotasImpagas.php',
                data: {},
                success: function (data){
                    $("#principal").html(data);
                }
            });//fin ajax
	});
	
	//Retiro de Dinero
	$("#barraRetirar").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'cargarTotalImportes.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});
	
	$("#barraIngresoDinero").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'cargarIngresoDinero.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});
	
	$("#ListarRecibo").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'ListarFactura.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});
	$("#barraCobrosOnline").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'mostrarCobrosOnline.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});
	
	
	
	
	//Carga de Pagos Varios 
	$("#barraPagVarios").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'cargarPagosVarios.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});
	
	$("#barraLisCaja").click(function(evento){
		vnumeroCaja = $("#numerocaja").val();
		vUsuario = 1;
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numeroCaja: vnumeroCaja, Usuario: vUsuario},
				url: 'mostrarListadoCajaCorriente.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
	});

	$("#barraArreglarCaja").click(function(evento){
		vnumeroCaja = $("#numerocaja").val();
		vUsuario = 1;
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numeroCaja: vnumeroCaja, Usuario: vUsuario, Arreglar: true},
				url: 'mostrarListadoCajaCorriente.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/
	});
	
	
	//Rendir Parcialmente barraPagVarios
	$("#barraRenPar").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
		//alert(vNumeroCaja);		return;
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'cargarRendidaParcial.php',
				success: function(data){ 
					$("#mostrar").html(data);
					$("#barraRenPar").hide();
					$("#barraRetirar").hide();
					$("#barraPagVarios").hide();
					$("#barraIngresoDinero").hide();
					
				}
			});//fin ajax//*/ 
	});

	$("#barraCuotaCero").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'mostrarCuotasCero.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
	});

	
	$(".botones").button();	
	//$(".botones").css("background-color:","#F9C");
	
	
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">       
      <?php
	  
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
//echo $UsuID;
	//$UsuID=4;  
$CajaID = cajaAbiertaUsuario($UsuID);
?>

<?php
if (!$CajaID){
	$disabled = "disabled=disabled";
?>
<div>
<table id="tablaCajaApertura" width="100%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia">
		  <p> Apertura de Caja
	      </p>
        </div></td>
      </tr>      
      <tr>
        <td colspan="3" align="center" class="texto"><button class="botones" id="barraAbrir">
        Abrir Caja</button>   
        <input value="0" name="importe" type="hidden" id="importe"/>                
        </td>
      </tr>
      </table>
		  </div>
<?php
}else{
?>
<div>
<table id="tablaCajaCierre" width="100%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia">
		  <p> Cierre de Caja
	      </p>
        </div></td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="texto">
        <input type="hidden" name="numerocaja" id="numerocaja" value="<?php echo $CajaID ?>"/>
        <button class="botones" id="barraRetirar">
        Retiro de Dinero</button>
        <button class="botones" id="barraPagVarios">
        Pagos Varios</button>
       
        <button class="botones" id="barraLisCaja">
        Listar Caja</button> 
        <button class="botones" id="barraCuotaCero">
        Listar Cuotas Cero</button> 

        <br>
        <!-- <button class="botones" id="barraIngresoDinero">
        Ingreso Dinero</button> -->
        <button class="botones" id="ListarRecibo">
        Listar Recibo</button>
        <button class="botones" id="barraArreglarCaja">
        Arreglar Caja</button>  
        <button class="botones" id="barraCobrosOnline">
        Cobros Online</button>  
         <button class="botones" id="barraRenPar">
        Cerrar Caja</button>               
        </td>
      </tr>
      </table>
      <tr>
        <td colspan="3" align="center" class="texto"><div id="mostrar"></div></td>
      </tr>
  </table>
  
</fieldset>
	</div>
<?php
}
  ?>	
	<p>&nbsp;</p>

	