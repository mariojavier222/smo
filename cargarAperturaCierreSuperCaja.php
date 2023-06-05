<?php
/**
 * Description of guardarCajaAuditada
 *
 * @author Balmaceda Diego
 */
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
		//v_importe = $("#importe").val();
		v_importe = 0;
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				url: 'cargarOpciones.php',
				data: {opcion: 'guardarSuperCajaApertura', Importe: v_importe},
				success: function(data){ 
					if (data){
						jAlert("Se guardaron correctamente los datos de Apertura.", "Confirmación");
					}
					$("#barraAbrir").hide();
					
					
				}
			});//fin ajax//*/ 
			$.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarAperturaCierreSuperCaja.php',
                data: {},
                success: function (data){
                    $("#principal").html(data);
                }
            });//fin ajax
	});
	
	//CERRAR SUPERCAJA
	$("#barraCerrar").click(function(evento){
		vNumeroCaja = $("#numerocaja").val();
		jConfirm('¿Está seguro que desea cerrar la Super Caja?', 'Confirmar el cierre de la Super Caja', function(r){
			if (r){//eligió eliminar
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					url: 'cargarOpciones.php',
					data: {opcion: 'guardarSuperCajaCierre', NumeroCaja: vNumeroCaja},
					success: function(data){ 
						if (data){
							jAlert(data + "Se guardaron correctamente los datos de Cierre.", "Confirmación");
						}
						$("#barraCerrar").hide();
						$("#barraRetirar").hide();
						$("#barraPagVarios").hide();
						$("#barraIngresoDinero").hide();
						$("#textoTitulo").html("Super Caja se ha cerrado");					 
						
						
					}
				});//fin ajax//*/ 					
			}//fin if
		});//fin del confirm//*/
			
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
				url: 'cargarIngresoDineroSuperCaja.php',
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
				url: 'cargarEgresoRecibo.php',
				success: function(data){ 
					$("#principal").html(data);
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
				url: 'mostrarListadoSuperCajaCorriente.php',
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

	
	$(".botones").button();	
	//$(".botones").css("background-color:","#F9C");
	
	
});//fin de la funcion ready


</script>


<div id="mostrarCuotas">       
      <?php
	  
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
//echo $UsuID;
	//$UsuID=4;  
$SCaID = cajaSuperAbiertaUsuario();
?>

<?php
if (!$SCaID){
	$disabled = "disabled=disabled";
?>
<div>
<table id="tablaCajaApertura" width="100%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="3">		
		<div align="center" class="titulo_noticia">
		  <p> Apertura de la Super Caja
	      </p>
        </div></td>
      </tr>
      <!--<tr>
            <td colspan="3" align="center" class="texto">Saldo inicial:
                <input name="importe" type="text" id="importe"/>        
        </td>
      </tr>-->
      <tr>
        <td colspan="3" align="center" class="texto"><button class="botones" id="barraAbrir">
        Abrir</button>           
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
		<div align="center" class="titulo_noticia" id="textoTitulo">
		  <p>  Super Caja
	      se encuentra abierta</p>
        </div></td>
      </tr>
      <tr>
        <td colspan="3" align="center" class="texto">
        <input type="hidden" name="numerocaja" id="numerocaja" value="<?php echo $SCaID ?>"/>
        <!--<button class="botones" id="barraRetirar">
        Retiro de Dinero</button>-->
        <button class="botones" id="barraPagVarios">
        Egresos varios</button>
       
        <button class="botones" id="barraLisCaja">
        Listar Caja</button> 
         <button class="botones" id="barraIngresoDinero">
        Ingresos de Dinero</button> 
       <!-- <button class="botones" id="ListarRecibo">
        Listar Recibo</button>-->  
         <button class="botones" id="barraCerrar">
        Cerrar Super Caja</button>               
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

	