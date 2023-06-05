<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");
?>
<script language="javascript">
$(document).ready(function(){
	
	
	$("#vistaprevia").click(function(evento){
	evento.preventDefault();
	
	PerID=$('#PerID').val();
	CMo_CantCuotas=$('#CMo_CantCuotas').val();
	CMo_Importe=$('#CMo_Importe').val();
	CMo_1er_Vencimiento=$('#CMo_1er_Vencimiento').val();
	CMo_Mes=$('#CMo_Mes').val();
	CMo_Anio=$('#CMo_Anio').val();
	CMo_Recargo_Mensual=$('#CMo_Recargo_Mensual').val();
	
	//VALIDACION
             
                
                if (CMo_Importe==""){
                    jAlert("Por favor escriba un importe valido","Error");
                    return;
                }
				
                
				
                if (CMo_1er_Vencimiento==""){
                    jAlert("Por favor elija una fecha para el primer vencimiento","Error");
                    return;
                }
                   
                if (CMo_Mes==""){
                    jAlert("Por favor elija un opción para el mes","Error");
                    return;
                }
                if (CMo_Anio==""){
                    jAlert("Por favor escriba un año valido","Error");
                    return;
                }
				
				 if (CMo_Recargo_Mensual==""){
                    jAlert("Por favor ingrese recargo mensual","Error");
                    return;
                }
	
	//FIN DE LA VALIDACION
	
	
	$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion:"mostrarVistaPreviaLibro",PerID: PerID,CMo_CantCuotas:CMo_CantCuotas,CMo_Importe:CMo_Importe,CMo_1er_Vencimiento:CMo_1er_Vencimiento,CMo_Mes:CMo_Mes,CMo_Anio:CMo_Anio,CMo_Recargo_Mensual:CMo_Recargo_Mensual},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#mostrar22").html(data);
				
			}
		});//fin ajax//*/
	
	       
	
	})
	
			
<?php

  //Obtener_LectivoActual($LecID, $LecNombre);
  $LecID = gLectivoActual($LecNombre);
  obtenerLimitesLectivo($LecID, $LecDesde, $LecHasta);
  $LimiteFecha = "{minDate: '".cfecha($LecDesde)."', maxDate: '".cfecha($LecHasta)."'}";// */
?>

        
		$("#CMo_1er_Vencimiento").datepicker(<?php echo $LimiteFecha; ?>);
		
		
		
		$(".botones").button();
		
	
		
})
</script>
<?php
$PerID = $_POST['Per_ID'];
//echo $PerID;
?>

      <table width="90%" border="0" align="center" class="borde_recuadro">
            <tr>
                <td colspan="2" valign="middle" height="60px"><div align="center" class="titulo_noticia"> Configurar la financiación
                  
                    <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
                  
                  
                  
              </div></td>
            </tr>
             
            <tr>
                <td align="right" class="texto">Cantidad de Cuotas:</td>
                <td><?php cargaCantCuotas("CMo_CantCuotas"); ?></td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Importe:</div></td>
                <td  class="texto">$
                  <input name="CMo_Importe" type="text" class="required digits" id="CMo_Importe" size="10" maxlength="10" value="<?php echo $_POST['totalPagar'];?>" readonly="readonly" /> 
            </tr>
            <tr>
                <td align="right" class="texto"> Vencimiento:</td>
                <td><input name="CMo_1er_Vencimiento" type="text" id="CMo_1er_Vencimiento" class="required fechaCompleta" alt="fecha de primer vencimiento"  title="Ingrese la fecha del primer vencimiento"/></td>
            </tr>
             <tr>
                <td align="right" class="texto">Mes:</td>
                <td><?php cargaMes("CMo_Mes") ?></td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Año:</div></td>
                <td colspan="2"><!--<input style="display:none" value="cargaListado" id="opcion" name="opcion" />--><input name="CMo_Anio" type="text" id="CMo_Anio" value="<?php echo date("Y");?>" size="6" maxlength="4"/></td>            
            </tr>
            <tr>
                <td class="texto"><div align="right">Recargo Mensual:</div></td>
                <td colspan="2" class="texto">$
                  <input name="CMo_Recargo_Mensual" type="text" class="required digits" id="CMo_Recargo_Mensual" size="10" maxlength="10" /> 
            </tr>
            
            <tr><td colspan="2" align="center" style="padding-top:25px;"><button class="botones" id="vistaprevia">Mostrar Vista Previa</button></td></tr>
            
            </table>
            
            
            <p>&nbsp;</p>
	<div id="mostrar22" class="texto"></div>
          

