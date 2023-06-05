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
	
	//NAHUEL 22-04-2013
	$("#UsuTras").hide();
	$("button[name^='traspaso']").hide();
	$("#traspaso").click(function(evento){	
	  if (this.checked){
		  $("button[name^='retirar']").hide();
		  $("button[name^='traspaso']").show();	
		  $("#UsuTras").show();
	  }
	  else
	  {
		  $("button[name^='retirar']").show();
		  $("button[name^='traspaso']").hide();	
		  $("#UsuTras").hide();
	  }	
	});//fin click
	
	
	$("button[id^='traspasodinero']").click(function(evento){
			evento.preventDefault();
            var i = this.id.substr(14,10);
			var Usu_ID_traspaso=$('#Usu_ID_traspaso').val();
			if(Usu_ID_traspaso=='-1')
			{
				mostrarAlerta("Ingrese Usuario para realizar el traspaso de Dinero");
				return;
			}
			
			vforma = $("#forma"+ i).val();
			vcaja = $("#nrocaja").val();
			vtotal = parseFloat($("#importe"+ i).val());			
			vobservaciones = $("#observaciones"+ i).val();
			//alert(vobservaciones);
			vimporte =  parseFloat($("#valor"+ i).val());
			if(vobservaciones == ''){
				mostrarAlerta("Ingrese Observaciones");
				return;
				}
			if((isNaN(vimporte))){
				mostrarAlerta("Ingrese Importe");
				return;
				}	
				
			vError = false;
	        vTexto_Error = '';
			//alert("importe "+vimporte);
		//	alert("vtotal "+vtotal);
	
                if (vimporte > vtotal){
                    vError = true;
					vTexto_Error = vTexto_Error +  "ingrese importe menor a recaudación actual </br>" ;
					$("#valor").attr("class","input_error");
					$("#valor"+ i).val("");
					$("#valor"+i).focus();
                    					
					
                }else {
					$("#valor").attr("class","input_sesion");
					}	
					if(vError) {
					mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
					return;
					}
			$.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarOpciones.php',
                data: {opcion: 'guardarTraspasoDinero', forma: vforma, caja: vcaja, total: vtotal, observaciones: vobservaciones , valor: vimporte, Usu_ID_traspaso:Usu_ID_traspaso},
                success: function (data){
                    //$("#principal").html(data);
                    //mostrarAlerta(data);
					$("#listado").show();
                    mostrarAlerta("Se retiro correctamente el dinero." + data, "Confirmación")
					$('#barraRetirar').click();
                }
            });//fin ajax
			
			//alert(i);
	
	})
	//FIN NAHUEL 22-04-2013
	
	
	//Retiro de dinero
	$("#barraRetiro").click(function(evento){
		vtotal = $("#total"+ i).val();
		//alert(vtotal);
		vretiro = $("#retiro"+ i).val();
		//alert(vretiro);return;
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
	
	$("button[id^='retirodinero']").click(function(evento){							  
            evento.preventDefault();
            var i = this.id.substr(12,10);
			vforma = $("#forma"+ i).val();
			vcaja = $("#nrocaja").val();
			vtotal = parseFloat($("#importe"+ i).val());			
			vobservaciones = $("#observaciones"+ i).val();
			//alert(vobservaciones);
			vimporte =  parseFloat($("#valor"+ i).val());
			if(vobservaciones == ''){
				jAlert("ERROR: Ingrese Observaciones", "ERROR");
				return;
				}
			if((isNaN(vimporte))){
				jAlert("ERROR: Ingrese Importe", "ERROR");
				return;
				}	
				
			vError = false;
	        vTexto_Error = '';
			//alert("importe "+vimporte);
		//	alert("vtotal "+vtotal);
	
                if (vimporte > vtotal){
                    vError = true;
					vTexto_Error = vTexto_Error +  "ingrese importe menor a recaudación actual </br>" ;
					$("#valor").attr("class","input_error");
					$("#valor"+ i).val("");
					$("#valor"+i).focus();
                    					
					
                }else {
					$("#valor").attr("class","input_sesion");
					}	
					if(vError) {
					jAlert("ERROR: " + vTexto_Error,"Existen datos incorrectos");
					return;
					}
			
			jPrompt('Escriba el clave para HACER EL RETIRO:', '', 'CLAVE - HACE RETIRO', function(r) {
				if( r == "175" ){//cambiar clave
					$.ajax({
						type: "POST",
						cache: false,
						async: false,			
						url: 'cargarOpciones.php',
						data: {opcion: 'guardarRetiroDinero', forma: vforma, caja: vcaja, total: vtotal, observaciones: vobservaciones , valor: vimporte},
						success: function (data){
							if (data.substr(0,2)=="No"){								
								jAlert(data, "ERROR")
								//$('#barraRetirar').click();
							}else{
								$("#listado").show();
								jAlert("Se retiró correctamente el dinero." + data, "Confirmación")
								$('#barraRetirar').click();
							}
						}
					});//fin ajax
				}else{
					jAlert("La Clave ingresada no es correcta", "CLAVE INCORRECTA");
				} 
			});
			
			
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
        <div align="center" class="titulo_noticia">Total de Importes</div>
        <?php
		$nrocaja = $_POST['numerocaja'];
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        $sql = "SELECT For_ID,For_Nombre, SUM(CCC_Credito) - SUM(CCC_Debito)  AS total
FROM CajaCorriente INNER JOIN FormaPago ON (CajaCorriente.CCC_For_ID = FormaPago.For_ID) WHERE CCC_Caja_ID = $nrocaja GROUP BY For_Nombre";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
     <table width="100%" border="0" id="listadoTabla" class="texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Forma de pago</th>
              <th align="center" class="fila_titulo">Importe en Caja</th>
              <th align="center" class="fila_titulo">Importe a Retirar</th> 
               <th align="center" class="fila_titulo">Nombre de quién retira</th>
              <th align="center" class="fila_titulo">Accion</th>              
            </tr>
          </thead>
          <tbody>
            <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        ?>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><?php echo $row[For_Nombre]; ?><input type="hidden"  id="forma<?php echo $i;?>"  value="<?php echo $row[For_ID];?>"><input type="hidden" id="nrocaja"  value="<?php echo $nrocaja;?>"></td>
                      <td align="center">$<?php echo $row['total']; ?><input type="hidden" id="importe<?php echo $i;?>"  value="<?php echo $row[total];?>"></td>                      
                    
                      <td align="center">Importe ($) <input style="width: 85px;" id="valor<?php echo $i;?>" value=""/></td>
                      
                        <td align="center">
                      <input id="observaciones<?php echo $i;?>" value=""/>
                      </td>
                      <td width="100px"><button class="botones" name="retirar" id="retirodinero<?php echo $i?>">Hacer Retiro</button>
                   <button class="botones" name="traspaso" id="traspasodinero<?php echo $i?>">Traspaso</button>   
                      </td>      
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
      </table>
<br /><br /><br />
<?php
		$sql = "SELECT * FROM SuperCajaCorriente WHERE SCC_Caja_ID = $nrocaja AND SCC_Concepto = 'RETIRO DE RECEPCIÓN'";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
            <div align="center" class="titulo_noticia">Retiros realizados</div>
     <table width="100%" border="0" class="texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Concepto</th>
              <th align="center" class="fila_titulo">Importe</th>
              <th align="center" class="fila_titulo">Retiró</th>
              <th align="center" class="fila_titulo">Fecha/Hora</th> 
               <th align="center" class="fila_titulo">Imprimir comprobante</th>
            </tr>
          </thead>
          <tbody>
            <?php
                    $i = 0;
					while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        ?>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><?php echo $row['SCC_Concepto']; ?>
                      <input type="hidden"  id="SCC_ID<?php echo $i;?>2"  value="<?php echo $row['SCC_ID'];?>" /></td>
                      <td align="center">$<?php echo $row['SCC_Credito']; ?></td>                      
                    
                      <td align="center"><?php echo $row['SCC_Detalle']; ?> </td>
                      <td align="center"><?php echo $row['SCC_FechaHora']; ?></td>
                      
                        <td align="center"><a href="imprimirRetiroDinero.php?id=<?php echo $row['SCC_ID'];?>" target="_blank"><img src="imagenes/printer.png" width="32" height="32" /></a></td>
                    </tr>	
            <?php
                    }//fin while
                    ?>
          </tbody>
          <?php
            } else {
                ?>
          <span class="texto">No existen retiros realizados hasta el momento.</span>
  <?php
            }
            ?>
  <tfoot>
    <tr>
      <th colspan="5" class="fila_titulo"></th>
    </tr>
  </tfoot>
      </table>

      <!--<table class="texto">
      <tr><td colspan="2"><input type="checkbox" name="traspaso" id="traspaso" value="Traspaso" />Traspaso de Dinero a otro Usuario</td></tr>
      <tr id="UsuTras"><td>Usuario:</td><td><?php //mostrarUsuariosCajasAbiertas('Usu_ID_traspaso',$UsuID) ?></td></tr>
      </table>-->
<?php
// NAHUEL NUEVO 22-04-2013





//FIN NAHUEL NUEVO 22-04-2013
?>
<br />
<br />

      
      <input name="botonLimpiar" type="button" class="botones" id="botonLimpiar"  value="Cerrar y Volver">
</div>
<p><br />
    <br />
</p>
<div id="mostrar"></div>
<p>&nbsp;</p>