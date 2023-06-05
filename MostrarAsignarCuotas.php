<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

?>

<script language="javascript">
$(document).ready(function(){
	
$("#copiarVencimiento").click(function(){
    var checked = $("#copiarVencimiento:checked").length;
    if (checked == 0){
        $('#CMo_2do_Vencimiento').val("");
        $('#CMo_3er_Vencimiento').val("");
    }else{
        var valor = $('#CMo_1er_Vencimiento').val();
        $('#CMo_2do_Vencimiento').val(valor);
        $('#CMo_3er_Vencimiento').val(valor);
    }
});	//fin click

$("#copiarRecargo").click(function(){
    var checked = $("#copiarRecargo:checked").length;
    if (checked == 0){
        $('#CMo_2do_Recargo').val("0");
        $('#CMo_Recargo_Mensual').val("0");
    }else{
        var valor = $('#CMo_1er_Recargo').val();
        $('#CMo_2do_Recargo').val(valor);
        $('#CMo_Recargo_Mensual').val(valor);
    }
}); //fin click

$("#ponerFechaHoy").click(function(){
    var checked = $("#ponerFechaHoy:checked").length;
    var valor = "<?php echo date('d/m/Y');?>";
    if (checked != 0){
        $('#CMo_1er_Vencimiento').val(valor);
        $('#CMo_2do_Vencimiento').val(valor);
        $('#CMo_3er_Vencimiento').val(valor);
    }
}); //fin click
	
$("#vistaprevia").click(function(){
	
	
	PerID=$('#PerID').val();
	CTi_ID=$('#CTi_ID').val();
    Lec_ID=$('#Lec_ID').val();
	Alt_ID=$('#Alt_ID').val();
	CMo_CantCuotas=$('#CMo_CantCuotas').val();
	CMo_Importe=$('#CMo_Importe').val();
	CMo_1er_Vencimiento=$('#CMo_1er_Vencimiento').val();
    CMo_2do_Vencimiento=$('#CMo_2do_Vencimiento').val();
    CMo_3er_Vencimiento=$('#CMo_3er_Vencimiento').val();
	CMo_Mes=$('#CMo_Mes').val();
	CMo_Anio=$('#CMo_Anio').val();
	CMo_Recargo_Mensual=$('#CMo_Recargo_Mensual').val();
	CMo_1er_Recargo=$('#CMo_1er_Recargo').val();
    CMo_2do_Recargo=$('#CMo_2do_Recargo').val();
	//VALIDACION
    
                if (CTi_ID==-1){
                    jAlert("Por favor elija una opción para el Tipo de Cuota","Error");
                    return;
                }
                if (Alt_ID==-1){
                    jAlert("Por favor elija una opción para la Alternativa","Error");
                    return;
                }  
                if (CMo_Importe==""){
                    jAlert("Por favor escriba un importe valido","Error");
                    return;
                }
                if (CMo_1er_Vencimiento==""){
                    jAlert("Por favor elija una fecha para el primer vencimiento","Error");
                    return;
                }

                if (CMo_2do_Vencimiento==""){
                    jAlert("Por favor elija una fecha para el segundo vencimiento","Error");
                    return;
                }

                if (CMo_3er_Vencimiento==""){
                    jAlert("Por favor elija una fecha para el tercer vencimiento","Error");
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
				
				if (CMo_1er_Recargo==""){
                    jAlert("Por favor ingrese 1º recargo","Error");
                    return;
                }    
                if (CMo_2do_Recargo==""){
                    jAlert("Por favor ingrese 2º recargo","Error");
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
			data: {opcion:"mostrarVistaPrevia",PerID: PerID,CTi_ID:CTi_ID,Alt_ID:Alt_ID,CMo_CantCuotas:CMo_CantCuotas,CMo_Importe:CMo_Importe,CMo_1er_Vencimiento:CMo_1er_Vencimiento,CMo_2do_Vencimiento:CMo_2do_Vencimiento,CMo_3er_Vencimiento:CMo_3er_Vencimiento,CMo_Mes:CMo_Mes,CMo_Anio:CMo_Anio, CMo_1er_Recargo: CMo_1er_Recargo, CMo_2do_Recargo: CMo_2do_Recargo, CMo_Recargo_Mensual:CMo_Recargo_Mensual, Lec_ID:Lec_ID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#mostrar22").html(data);
				
			}
		});//fin ajax//*/
	
});//fin click
	
			
<?php

  //Obtener_LectivoActual($LecID, $LecNombre);
  $LecID = gLectivoActual($LecNombre);
  obtenerLimitesLectivo($LecID, $LecDesde, $LecHasta);
  $LimiteFecha = "{minDate: '".cfecha($LecDesde)."', maxDate: '".cfecha($LecHasta)."'}";// */
?>

        
		$("#CMo_1er_Vencimiento").datepicker();
        $("#CMo_2do_Vencimiento").datepicker();
        $("#CMo_3er_Vencimiento").datepicker();
		
		
		
		$(".botones").button();
		
	
		

  });  
</script>
<?php
$PerID = $_POST['PerID'];
//echo $PerID;
?>

      <table width="100%" border="0" align="center" class="borde_recuadro">
            <tr>
                <td colspan="2" valign="middle" height="60px"><div align="center" class="titulo_noticia"> Configuración de Cuotas
                  
                  <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
                  
                  <input type="hidden" name="Alt_ID" id="Alt_ID" value="<?php echo "1" ?>" />
                  
                </div></td>
            </tr>
             <tr>
                <td align="right" class="texto">Tipo de Cuota:</td>
                <td><?php cargarListaTipoCuota("CTi_ID"); ?></td>
            </tr>
            <tr>
                <td align="right" class="texto">Ciclo Lectivo:</td>
                <td><?php cargarListaLectivo("Lec_ID"); ?></td>
            </tr>
           <!-- <tr>
                <td align="right" class="texto">Alternativas de Cuota:</td>
                <td><?php cargarListaAlternativaCuota("Alt_ID"); ?></td>
            </tr> -->
            <tr>
                <td align="right" class="texto">Cantidad de Cuotas:</td>
                <td><?php cargaCantCuotas("CMo_CantCuotas"); ?></td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Importe ($):</div></td>
                <td  class="texto"><input name="CMo_Importe" type="text" class="required digits" id="CMo_Importe" size="10" maxlength="10" /> 
            </tr>
            <tr>
                <td align="right" class="texto">1º Vencimiento:</td>
                <td><input name="CMo_1er_Vencimiento" type="text" id="CMo_1er_Vencimiento" class="required fechaCompleta" alt="fecha de primer vencimiento"  title="Ingrese la fecha del primer vencimiento"/><input type="checkbox" name="copiarVencimiento" id="copiarVencimiento" value="male"> <label for="copiarVencimiento">copiar vencimiento</label><input type="checkbox" name="ponerFechaHoy" id="ponerFechaHoy" value="male"> <label for="ponerFechaHoy">Fecha Hoy</label></td>
            </tr>
            <tr>
                <td align="right" class="texto">2º Vencimiento:</td>
                <td><input name="CMo_2do_Vencimiento" type="text" id="CMo_2do_Vencimiento" class="required fechaCompleta" alt="fecha del segundo vencimiento"  title="Ingrese la fecha del segundo vencimiento"/></td>
            </tr>
            <tr>
                <td align="right" class="texto">3º Vencimiento:</td>
                <td><input name="CMo_3er_Vencimiento" type="text" id="CMo_3er_Vencimiento" class="required fechaCompleta" alt="fecha del tercer vencimiento"  title="Ingrese la fecha del tercer vencimiento"/></td>
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
                <td class="texto"><div align="right">1º Recargo ($):</div></td>
                <td colspan="2" class="texto">
                  <input name="CMo_1er_Recargo" type="text" class="required digits" id="CMo_1er_Recargo" size="10" maxlength="10" /> <input type="checkbox" name="copiarRecargo" id="copiarRecargo" value="male"> <label for="copiarRecargo">copiar recargo</label>
            </tr>
            <tr>
                <td class="texto"><div align="right">2º Recargo ($):</div></td>
                <td colspan="2" class="texto">
                  <input name="CMo_2do_Recargo" type="text" class="required digits" id="CMo_2do_Recargo" size="10" maxlength="10" /> 
            </tr>
            <tr>
                <td class="texto"><div align="right">Recargo Mensual ($):</div></td>
                <td colspan="2" class="texto">
                  <input name="CMo_Recargo_Mensual" type="text" class="required digits" id="CMo_Recargo_Mensual" size="10" maxlength="10" /> 
            </tr>
            
            <tr><td colspan="2" align="center" style="padding-top:25px;"><button class="botones" id="vistaprevia">Mostrar Vista Previa</button></td></tr>
            
            </table>
            
            
            <p>&nbsp;</p>
	<div id="mostrar22" class="texto"></div>
          

