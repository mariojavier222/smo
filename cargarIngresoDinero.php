<?php
/**
 * Description of guardarCajaAuditada
 *
 * @author Balmaceda Diego
 */

header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");


?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
        
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>

<script language="javascript">
$(document).ready(function(){
	
	
	
	$("button[id^='pago']").click(function(evento){							  
            evento.preventDefault();
            var i = this.id.substr(4,10);
			vforma = $("#forma"+ i).val();
			vcaja = $("#nrocaja").val();
			vimporte =  parseFloat($("#valor"+ i).val());
			vobservaciones = $("#observaciones"+ i).val();
			vtotal = parseFloat($("#importe"+ i).val());
			if(vobservaciones == ''){
				mostrarAlerta("Ingrese Observaciones");
				return;
				}
			if(isNaN(vimporte)){
				mostrarAlerta("Ingrese Importe");
				return;
				}	
			vError = false;
	        vTexto_Error = '';
		
					if(vError) {
					mostrarAlerta1(vTexto_Error,"Existen datos incorrectos");
					return;
					}
			$.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarOpciones.php',
                data: {opcion: 'IngresoDineroEfectivo', forma: vforma, caja: vcaja, total: vtotal, observaciones: vobservaciones , valor: vimporte},
                success: function (data){
                    //$("#principal").html(data);
                    //mostrarAlerta(data);
					$("#listado").show();
                    mostrarAlerta("Se guardaron correctamente los cambios." + data, "Confirmación")
					$('#barraIngresoDinero').click();
                }
            });//fin ajax
        });	
	
	$("#botonLimpiar").click(function(evento){
      
    limpiar();
         					
});

function limpiar(){	
    $("#listado").hide();
}
	
	$(".botones").button();	
});//fin de la funcion ready


</script>


<div id="listado" class="page-break">    
        <div align="center" class="titulo_noticia">Ingreso de Dinero</div>
        <?php
		$nrocaja = $_POST['numerocaja'];
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        $sql = "SELECT For_ID,For_Nombre, SUM(CCC_Credito) - SUM(CCC_Debito)  AS total
FROM CajaCorriente INNER JOIN FormaPago ON (CajaCorriente.CCC_For_ID = FormaPago.For_ID) WHERE CCC_Caja_ID = $nrocaja AND For_ID = 1 GROUP BY For_Nombre";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
     <table width="100%" border="0" id="listadoTabla" class="texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Recaudación</th> 
            
              <th align="center" class="fila_titulo">Importe a Pagar</th>  
              <th align="center" class="fila_titulo">Observaciones</th> 
              <th align="center" class="fila_titulo">Accion</th>             
            </tr>
          </thead>
          <tbody>
            <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        ?>
                    <tr style="background-color: gainsboro;">
                      <td align="center"> <?php echo $row[total];?>
                      </td>        
                       <td align="center">Importe (Efectivo) $<input style="width: 85px;" id="valor<?php echo $i;?>" value=""/>
                      <input type="hidden"  id="forma<?php echo $i;?>"  value="<?php echo $row[For_ID];?>">
                      <input type="hidden" id="nrocaja"  value="<?php echo $nrocaja;?>">
                      <input type="hidden" id="importe<?php echo $i;?>"  value="<?php echo $row[total];?>">
                      </td>           
                      <td align="center">
                      <input id="observaciones<?php echo $i;?>" value=""/>
                      </td>
                     
                      <td>
                      <button class="botones" id="pago<?php echo $i?>">Ingresar</button></td>      
                    </tr>	
            <?php
                    }//fin while
                    ?>
          </tbody>
          <?php
            } else {
                ?>
          No existen datos cargados.
  <?php
            }
            ?>
  <tfoot>
    <tr>
      <th colspan="12" class="fila_titulo"></th>
    </tr>
  </tfoot>
      </table><input name="botonLimpiar" type="button" class="botones" id="botonLimpiar"  value="Cerrar y Volver">
</div>
<p><br />
    <br />
</p>
<div id="mostrar"></div>
<p>&nbsp;</p>