<?php  
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include_once("comprobar_sesion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
include_once("cargarOpciones.php");
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){
	$("#cargando").hide();
    <?php  
      Obtener_LectivoActual($LecID, $LecNombre);
      obtenerLimitesLectivo($LecID, $LecDesde, $LecHasta);
      $LimiteFecha = "{minDate: '".cfecha($LecDesde)."', maxDate: '".cfecha($LecHasta)."'}";
    ?>
  
    $("#CMo_1er_Vencimiento").datepicker();
  
    $('table.tabla tbody tr:odd').addClass('fila');

    $('table.tabla tbody tr:even').addClass('fila2');

    $("#mostrarNuevo").hide();
     $("#barraEditar").hide();

   $("input[class^='ocultar']").hide();
    $("select[class^='ocultar']").hide();
    $("#mostrar").empty();
    $("#nombre").focus();
    $("#form_Buscador").unbind('submit');

    $("#barraNuevo").click(function(evento){
        evento.preventDefault();				
        $("#mostrarNuevo").fadeIn();
        $("#mostrar").empty();		
        $("#divBuscador").fadeOut();
        limpiarDatos();
    });
    function limpiarDatos(){
        $("#TriID").val("");
        $("#Nombre").val("");
    }
	
        // 21012013 modificaciones
        // para guardar una sicopedagoga

        $("#barraGuardar").click(function(evento){
            evento.preventDefault();	

            if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
                $("#mostrar").empty();
                vCMo_Lectivo = $("#CMo_Lectivo").val();
                vCMo_Niv_ID = $("#CMo_Niv_ID").val();
                vCTi_ID = $("#CTi_ID").val();
                vAlt_ID = $("#Alt_ID").val();
                vCMo_Usu_ID = $("#CMo_Usu_ID").val();
                vCMo_Fecha = $("#CMo_Fecha").val();
                vCMo_CantCuotas = $("#CMo_CantCuotas").val();
                vCMo_Numero = $("#CMo_Numero").val();
                vCMo_Importe = $("#CMo_Importe").val();
                vCMo_1er_Recargo = $("#CMo_1er_Recargo").val();
                vCMo_2do_Recargo = $("#CMo_2do_Recargo").val();
                vCMo_1er_Vencimiento = $("#CMo_1er_Vencimiento").val();
                vCMo_2do_Vencimiento = $("#CMo_1er_Vencimiento").val();
                vCMo_3er_Vencimiento = $("#CMo_1er_Vencimiento").val();
                vCMo_Mes = $("#CMo_Mes").val();
                vCMo_Anio = $("#CMo_Anio").val();
                vCMo_Recargo_Mensual = $("#CMo_Recargo_Mensual").val();
                
                if (vCMo_Niv_ID==-1){
                    mostrarAlerta("Por favor elija una opción para el Nivel","Error");
                    return;
                }
                
                if (vCTi_ID==-1){
                    mostrarAlerta("Por favor elija una opción para el Tipo de Cuota","Error");
                    return;
                }
                if (vAlt_ID==-1){
                    mostrarAlerta("Por favor elija una opción para la Alternativa","Error");
                    return;
                }
                
                
                if (vCMo_Importe==""){
                    mostrarAlerta("Por favor escriba un importe valido","Error");
                    return;
                }
                if (vCMo_Importe=="0" || vCMo_Importe=="0.0" || vCMo_Importe=="0.00" || vCMo_Importe=="0,0" || vCMo_Importe=="0,00" ){
                    mostrarAlerta("Por favor escriba un importe mayor a cero","Error");
                    return;
                }
                
                if (vCMo_1er_Recargo==""){
                    mostrarAlerta("Por favor escriba un importe valido para el primer recargo","Error");
                    return;
                }
                if (vCMo_2do_Recargo==""){
                    mostrarAlerta("Por favor escriba un importe valido para el segundo recargo","Error");
                    return;
                }
				
                
				
                if (vCMo_1er_Vencimiento==""){
                    mostrarAlerta("Por favor elija una fecha para el primer vencimiento","Error");
                    return;
                }
                if (vCMo_2do_Vencimiento==""){
                    mostrarAlerta("Por favor elija una fecha para el segundo vencimiento","Error");
                    return;
                }
                if (vCMo_3er_Vencimiento==""){
                    mostrarAlerta("Por favor elija una fecha tercer vencimiento","Error");
                    return;
                }
                if (vCMo_Mes==""){
                    mostrarAlerta("Por favor elija un opción para el mes","Error");
                    return;
                }
                if (vCMo_Anio==""){
                    mostrarAlerta("Por favor escriba un año valido","Error");
                    return;
                }
				
				 if (vCMo_Recargo_Mensual==""){
                    mostrarAlerta("Por favor ingrese recargo mensual","Error");
                    return;
                }
				
				//$("#form1").validate().form();
				var a =$("#form1").validate().form();
				if(a) {
					$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: $('#form1').serialize(),					
                    success: function (data){
                        jAlert(data, "Resultado de la operación");
                        $("#cargando").hide();
                                                
						
                    }
                });//fin ajax
				}
				
                
                
                
            }else{
                jAlert("Antes de guardar, haga click en el botón <strong>Nuevo</strong>","Alerta");
            }//fin if
        });

		$("#barraEditar").click(function(evento){	
		
 			vLec_ID = $("#Lec_ID").val();
            vNiv_ID = $("#Niv_ID").val();
			vAlt_ID = $("#Alt_ID").val();
			vCTi_ID = $("#CTi_ID").val();
			CMo_Importe= $("#CMo_Importe").val();
			CMo_Recargo_Mensual= $("#CMo_Recargo_Mensual").val();
			CMo_1er_Recargo= $("#CMo_1er_Recargo").val();
            CMo_2do_Recargo= $("#CMo_2do_Recargo").val();
			CMo_Mes= $("#CMo_Mes").val();
			
			CMo_CantCuotas= $("#CMo_CantCuotas").val();
            CMo_Numero= $("#CMo_Numero").val();
            Numero = $("#Numero").val();
			CMo_1er_Vencimiento= $("#CMo_1er_Vencimiento").val();
            CMo_2do_Vencimiento= $("#CMo_2do_Vencimiento").val();
            CMo_3er_Vencimiento= $("#CMo_3er_Vencimiento").val();
			//alert(CMo_CantCuotas)
			//alert(CMo_1er_Vencimiento)
            CMo_Agrupa=$("#CMo_Agrupa").val();
            CMo_Especial=$("#CMo_Especial").val();
			
			
		//return false;
		$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
					data: {opcion:"editarConfiguracionCuotas",Lec_ID:vLec_ID,Niv_ID:vNiv_ID,Alt_ID:vAlt_ID,CTi_ID:vCTi_ID,CMo_Importe:CMo_Importe,CMo_Recargo_Mensual:CMo_Recargo_Mensual, CMo_1er_Recargo: CMo_1er_Recargo, CMo_2do_Recargo: CMo_2do_Recargo, CMo_Mes:CMo_Mes,CMo_CantCuotas:CMo_CantCuotas,CMo_1er_Vencimiento:CMo_1er_Vencimiento,CMo_Numero:CMo_Numero,Numero:Numero, CMo_Agrupa:CMo_Agrupa, CMo_Especial:CMo_Especial},
                    success: function (data){
                        jAlert(data, "Resultado de la operación");
                        $("#cargando").hide();
						recargarPagina();
		
		}
		})//fin ajax
		
		
		})
		
		
	
	
	 $("a[id^='botActualizar']").click(function(evento){	
	// alert("NAHUEL");
			evento.preventDefault();
            var i = this.id.substr(13,10);
			 vLec_ID = $("#Lec_ID" + i).val();
			//$("#Lec_ID").val(vLec_ID);
			//alert(vLec_ID)
            vNiv_ID = $("#Niv_ID" + i).val();
			//$("#Niv_ID").val(vNiv_ID);
			//alert(vNiv_ID)
			vAlt_ID = $("#Alt_ID" + i).val();
			//$("#Alt_ID").val(vAlt_ID);
			//alert(vAlt_ID)
			vCTi_ID = $("#CTi_ID" + i).val();

            vNumero = $("#CMo_Numero" + i).val();
			//$("#CTi_ID").val(vCTi_ID);
			//alert(vCTi_ID)
			
			$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
					data: {opcion:"actualizarConfiguracionCuotas",Lec_ID:vLec_ID,Niv_ID:vNiv_ID,Alt_ID:vAlt_ID,CTi_ID:vCTi_ID, Numero:vNumero},
                    success: function (data){
                        jAlert(data, "Resultado de la operación");
                        $("#cargando").hide();
						//recargarPagina();
		
		}
		})//fin ajax
	 
	 })
        // para editar sicopedagogas
        $("a[id^='botEditar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(9,10);
			
			 $("#barraEditar").show();
			  $("#barraGuardar").hide();
			
			
            $("#CMo_CTi_ID").val($("#CTi_ID" + i).val());
			$("#CMo_CTi_ID").prop('disabled', 'disabled');
			
			$("#CMo_Alt_ID").val($("#Alt_ID" + i).val());
			$("#CMo_Alt_ID").prop('disabled', 'disabled');
			
			$("#CMo_CantCuotas").val($("#CMo_CantCuotas" + i).val());
            $("#CMo_Numero").val($("#CMo_Numero" + i).val());
			//$("#CMo_CantCuotas").prop('disabled', 'disabled');
			$("#CMo_1er_Vencimiento").val($("#CMo_1er_Vencimiento" + i).val());
			//$("#CMo_1er_Vencimiento").attr('disabled', 'disabled');
			
			$("#CMo_Mes").val($("#CMo_Mes" + i).val());
			//$("#CMo_Mes").prop('disabled', 'disabled');
			$("#CMo_Anio").val($("#CMo_Anio" + i).val());
			$("#CMo_Anio").attr('disabled', 'disabled');
			
			$("#CMo_Importe").val($("#CMo_Importe" + i).val());
			//$("#CMo_Importe").attr('disabled', 'disabled');
			
			$("#CMo_Recargo_Mensual").val($("#CMo_Recargo_Mensual" + i).val());
			$("#CMo_1er_Recargo").val($("#CMo_1er_Recargo" + i).val());
            $("#CMo_2do_Recargo").val($("#CMo_2do_Recargo" + i).val());
			//$("#CMo_Recargo_Mensual").attr('disabled', 'disabled');
			
			vLec_ID = $("#Lec_ID" + i).val();
			$("#Lec_ID").val(vLec_ID);
			//alert(vLec_ID)
            vNiv_ID = $("#Niv_ID" + i).val();
			$("#Niv_ID").val(vNiv_ID);
			//alert(vNiv_ID)
			vAlt_ID = $("#Alt_ID" + i).val();
			$("#Alt_ID").val(vAlt_ID);
			//alert(vAlt_ID)
			vCTi_ID = $("#CTi_ID" + i).val();
			$("#CTi_ID").val(vCTi_ID);
            
            $("#Numero").val($("#CMo_Numero" + i).val());
			//alert(vCTi_ID)
			
            $("#CMo_Agrupa").val($("#CMo_Agrupa" + i).val());
            if ($("#CMo_Agrupa").val()==1) $("#CMo_Agrupa").prop("checked",true);
            else $("#CMo_Agrupa").prop("checked",false);

            $("#CMo_Especial").val($("#CMo_Especial" + i).val());
            if ($("#CMo_Especial").val()==1) $("#CMo_Especial").prop("checked",true);
            else $("#CMo_Especial").prop("checked",false);

            $("#CMo_Niv_ID").val($("#Niv_ID" + i).val());
		 	 // alert(NivID);
            $("#CMo_Lectivo").val($("#Lec_ID" + i).val());
		   
		    $("#CMo_Lectivo").prop('disabled', 'disabled');
		    $("#CMo_Niv_ID").prop('disabled', 'disabled');
           //$("#Sic_Nombre").val($("#Sic_Nombre" + i).val());
		 
          	 // $("#Sic_DNI").val($("#Sic_DNI" + i).val());
        	 //  $("#Sic_Tel").val($("#Sic_Tel" + i).val());
          	  //$("#FechaHasta").val($("#FechaHasta" + i).val());
            $("#mostrarNuevo").show();
            $("#mostrar").empty();		
            $("#divBuscador").hide();
			//$("#mostrarEditarNuevo").show();
			
			
			
		
        });//fin evento click//*/	 
        $("a[id^='botBorrar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(9,10);
            vLec_ID = $("#Lec_ID" + i).val();
            vNiv_ID = $("#Niv_ID" + i).val();
			vAlt_ID = $("#Alt_ID" + i).val();
			vCTi_ID = $("#CTi_ID" + i).val();
            //$("#Numero").val($("#CMo_Numero" + i).val());
            vNumero = $("#CMo_Numero" + i).val();
		
            jConfirm('¿Est&aacute; seguro que desea eliminar este registro ?', 'Confirmar la eliminaci&oacute;n', function(r){
                if (r){//eligió eliminar
                    $.post("cargarOpciones.php", { opcion: "eliminarConfigurarCuota", Lec_ID: vLec_ID, Niv_ID: vNiv_ID, Alt_ID: vAlt_ID, CTi_ID: vCTi_ID, Numero: vNumero }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
                        recargarPagina();
                    });//fin post					
                }//fin if
            });//fin del confirm//*/
	
        });//fin evento click//*/	

        // 21012013 fin de modificaciones




        function recargarPagina(){
            $("#mostrar").empty();

            $.ajax({
                cache: false,
                async: false,			
                url: "<?php   echo $_SERVER['PHP_SELF']; ?>",
                success: function (data){
                    $("#principal").html(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        }//fin function
        $("#barraBuscar").click(function(evento){
            evento.preventDefault();
            recargarPagina()
        });
        $("input[id^='Editar']").keyup(function(evento){
            if (evento.keyCode == 13){
                var i = this.id.substr(6,10);
                guardarNombre(i);
            }
        });

        $("#CMo_Agrupa").change(function() {
            if(this.checked) {
                $("#CMo_Agrupa").val(1); 
            }else{
                $("#CMo_Agrupa").val(0); 
            }
        });

        $("#CMo_Especial").change(function() {
            if(this.checked) {
                $("#CMo_Especial").val(1); 
            }else{
                $("#CMo_Especial").val(0); 
            }
        });

        $("#triNivID").change(function () {
            $("#triNivID option:selected").each(function () {
                //alert($(this).val());
                vtriNivID=$(this).val();
                $.ajax({
                    cache: false,
                    async: false,
                    type: "POST",
                    data: {triNivID: vtriNivID},
                    url: "<?php   echo $_SERVER['PHP_SELF']; ?>",
                    success: function (data){
                        $("#principal").html(data);
                        $("#cargando").hide();
                    }
                });//fin ajax
            });
        });
<?php  
if (isset($_POST['triNivID'])) {
    ?>		
                $("#triNivID").val("<?php   echo $_POST['triNivID'] ?>");
    <?php  
}
?>
});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
    <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar una opción nueva" width="48" height="48" border="0" title="Ingresar una opción nueva" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar datos" width="48" height="48" border="0" title="Buscar datos" /><br />
                Listar</button></td>
        <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" title="Guardar" /><br />Guardar</button>
        
        <button class="barra_boton"  id="barraEditar">  <img src="botones/guardar.png" alt="Editar" width="48" height="48" border="0" title="Editar" /><br />Editar</button>
      </td>
    </tr>
</table>


<div id="mostrarNuevo">
    <form id="form1" name="form1">
        <table width="80%" border="0" align="center" class="borde_recuadro">
            <tr>
                <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"> Configuración de Cuotas
                    <input name="CMo_Fecha" type="hidden" id="CMo_Fecha" value="<?php echo date("m/d/Y"); ?>" size="10" />
                  <input name="CMo_Hora" type="hidden" id="CMo_Hora" value="<?php  echo $Hora = date("H:i:s"); ?>" size="10" />
                  <input name="opcion" type="hidden" id="opcion" value="guardarConfiguracionCuota" />
                </div></td>
            </tr>

            <tr>
                <td class="texto"><div align="right">Lectivo:</div></td>
                <td><?php  cargarListaLectivo("CMo_Lectivo");?></td> 
            </tr>

            <tr>
                <td align="right" class="texto">Nivel:</td>
                <td><?php  cargarListaNivel("CMo_Niv_ID"); ?></td>
            </tr>            
            <tr>
                <td align="right" class="texto">Tipo de Cuota:</td>
                <td><?php  cargarListaTipoCuota("CMo_CTi_ID"); ?></td>
            </tr>
            <tr>
                <td align="right" class="texto">Número Cuota (desde):</td>
                <td><?php  cargaCantCuotas("CMo_Numero"); ?> <i>Elija el número de orden de la cuota que comenzará (Ej: Marzo será la 1, Abril será la 2)</i></td>
            </tr>
            <tr>
                <td align="right" class="texto">Alternativas de Cuota:</td>
                <td><?php  cargarListaAlternativaCuota("CMo_Alt_ID"); ?></td>
            </tr>            
            <tr>
                <td align="right" class="texto">Cantidad de Cuotas:</td>
                <td><?php  cargaCantCuotas("CMo_CantCuotas"); ?></td>
            </tr>
            <tr style="display: none">
                <td align="right" class="texto">Usuario:</td>
                <td><input name="CMo_Usu_ID" type="hidden" id="CMo_Usu_ID" value="<?php  obtenerIdUsuario("CMo_Usu_ID");?>"/></td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Importe:</div></td>
                <td colspan="2" class="texto">$
                  <input name="CMo_Importe" type="number" class="required digits" id="CMo_Importe" size="10" maxlength="10" min="0" max="99999999" /> 
            </tr>
            
            <tr>
                <td align="right" class="texto"> Vencimiento:</td>
                <td><input name="CMo_1er_Vencimiento" type="text" id="CMo_1er_Vencimiento" class="required fechaCompleta" alt="fecha de primer vencimiento"  title="Ingrese la fecha del primer vencimiento"/>
                </td>
            </tr>
           
            <tr>
                <td align="right" class="texto">Mes:</td>
                <td><?php  cargaMes("CMo_Mes"); ?></td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Año:</div></td>
                <td colspan="2"><!--<input style="display:none" value="cargaListado" id="opcion" name="opcion" />--><input name="CMo_Anio" type="text" id="CMo_Anio" value="<?php  echo date("Y");?>" size="6" maxlength="4"/></td>            
            </tr>
             <tr>
                <td class="texto"><div align="right">1º Recargo:</div></td>
                <td colspan="2" class="texto">$
                  <input name="CMo_1er_Recargo" type="number" id="CMo_1er_Recargo" class="required digits" size="10" maxlength="10" min="0" max="99999999" /> 
                  <input name="CMo_2do_Recargo" type="hidden" id="CMo_2do_Recargo" value="0" /> 
            </tr>            
            <tr>
                <td class="texto"><div align="right">Recargo Mensual:</div></td>
                <td colspan="2" class="texto">$
                  <input name="CMo_Recargo_Mensual" type="number" class="required digits" id="CMo_Recargo_Mensual" size="10" maxlength="10" min="0" max="99999999" /> 
            </tr>
            <tr>
                <td class="texto"><br><div align="right">Es cuota Agrupada con la mensual: <br>(Se cobra en medios electrónicos junto a la cuota mensual)</div></td>
                <td colspan="2" class="texto">
                  <label><input type="checkbox" id="CMo_Agrupa" name="CMo_Agrupa" value=""> Marcar si es cuota agrupada</label>
            </tr>
            <tr>
                <td class="texto"><br><div align="right">Es cuota Especial:<br> (No se genera masivamente, sólo manual)</div></td>
                <td colspan="2" class="texto">
                  <label><input type="checkbox" id="CMo_Especial" name="CMo_Especial" value=""> Marcar si es cuota especial</label>
            </tr>

            <tr>
                <td colspan="2" class="texto"></td>
            </tr>
        </table>
        <input type="hidden" name="Lec_ID" id="Lec_ID" />
         <input type="hidden" name="Niv_ID" id="Niv_ID" />
          <input type="hidden" name="Alt_ID" id="Alt_ID" />
           <input type="hidden" name="CTi_ID" id="CTi_ID" />
           <input type="hidden" name="Numero" id="Numero" />
           

    </form>

</div>   
 
    <div id="divBuscador">

   
    <table width="98%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Configuración de Cuotas</div></td>
        </tr>
        <tr>
            <td align="center" class="texto">
                <!--Filtrar por Año: <?php ; ?><br /><br />-->
                <?php 
				$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "SELECT * FROM CuotaModelo
    INNER JOIN Lectivo 
        ON (CMo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (CMo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (CMo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (CMo_Alt_ID = Alt_ID)
	INNER JOIN Usuario 
        ON (CMo_Usu_ID = Usu_ID) ORDER BY CMo_Lec_ID DESC, CMo_Niv_ID, CMo_CTi_ID, CMo_Alt_ID;";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    ?>
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
                        <thead>
                            <tr class="ui-widget-header">
                                <th align="center">Lectivo</th>
                                <th align="center">Nivel</th>
                                
                                <th align="center">Tipo de Cuota</th>
                                <th align="center">Alternativa</th>
                                <th align="center">Cant. Cuotas</th>
                                <th align="center">Importe</th>
                                <th align="center">1º Vencimiento</th>
                                <th align="center">Vigencia</th>
                                <th align="center">1º Recargo</th>
                                <th align="center">2º Recargo</th>
                                <th align="center">Recargo Mensual</th>
                                <th align="center">Agrupa</th>
                                <th align="center">Especial</th>
                                <th align="center">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while ($row = mysqli_fetch_array($result)) {
                                $i++;
                                $Agrupa="No";
                                $Especial="No";
                                if ($row['CMo_Agrupa']==1) $Agrupa="Si";
                                if ($row['CMo_Especial']==1) $Especial="Si";
                                ?>
                                <tr title="<?php  echo "Registro modificado por $row[Usu_Nombre] a las ".cfecha($row[CMo_Fecha])." $row[CMo_Hora]"; ?>">
                                    
                                    
                                    <td align="center">
                                     <input name="Alt_ID" type="hidden" id="Alt_ID<?php  echo $i;?>" value="<?php  echo $row[Alt_ID]; ?>" />
                                    
                                    <input name="CTi_ID" type="hidden" id="CTi_ID<?php  echo $i;?>" value="<?php  echo $row[CTi_ID]; ?>" />
                                      <input name="Niv_ID" type="hidden" id="Niv_ID<?php  echo $i;?>" value="<?php  echo $row[Niv_ID]; ?>" />
                                    <input name="Lec_ID" type="hidden" id="Lec_ID<?php  echo $i;?>" value="<?php  echo $row[Lec_ID]; ?>" />                                      <?php  echo $row[Lec_Nombre]; ?></td>
                                    <td align="center"><?php  echo $row[Niv_Nombre]; ?></td>
                                    
                                    <td align="center">
									
								  <?php  echo $row[CTi_Nombre]; ?></td>
                                   
                                    <td align="center"><?php  echo $row[Alt_Nombre]; ?></td>
                                    <td align="center">
									<input type="hidden" name="CMo_CantCuotas<?php  echo $i;?>" id="CMo_CantCuotas<?php  echo $i;?>" value="<?php  echo $row[CMo_CantCuotas]; ?>" />
								  <?php  echo $row[CMo_CantCuotas]; ?>
                                  <input type="hidden" name="CMo_Numero<?php  echo $i;?>" id="CMo_Numero<?php  echo $i;?>" value="<?php  echo $row[CMo_Numero]; ?>" />                                  
                                </td>
                                    <td align="center">
                                    <input type="hidden" name="CMo_Importe<?php  echo $i;?>" id="CMo_Importe<?php  echo $i;?>" value="<?php  echo $row[CMo_Importe]; ?>" />
                                  $<?php  echo $row[CMo_Importe]; ?></td>
                                    <td align="center">
									<input type="hidden" name="CMo_1er_Vencimiento<?php  echo $i;?>" id="CMo_1er_Vencimiento<?php  echo $i;?>" value="<?php  echo cfecha($row[CMo_1er_Vencimiento]); ?>" />
								  <?php  echo cfecha($row[CMo_1er_Vencimiento]); ?></td>
                                    <td align="center">
                                    <input type="hidden" name="CMo_Mes<?php  echo $i;?>" id="CMo_Mes<?php  echo $i;?>" value="<?php  echo $row[CMo_Mes]; ?>" />
                                  <?php 
									
									
                                        if ($row[CMo_Mes] == '1')
                                            echo "Enero";
                                        else if ($row[CMo_Mes] == '2')
                                            echo "Febrero";
                                        else if ($row[CMo_Mes] == '3')
                                            echo "Marzo";
                                        else if ($row[CMo_Mes] == '4')
                                            echo " Abril";
                                        else if ($row[CMo_Mes] == '5')
                                            echo "Mayo";
                                        else if ($row[CMo_Mes] == '6')
                                            echo"Junio";
                                        else if ($row[CMo_Mes] == '7')
                                            echo "Julio";
                                        else if ($row[CMo_Mes] == '8')
                                            echo "Agosto";
                                        else if ($row[CMo_Mes] == '9')
                                            echo "Septiembre";
                                        else if ($row[CMo_Mes] == '10')
                                            echo "Octubre";
                                        else if ($row[CMo_Mes] == '11')
                                            echo "Noviembre";
                                        else if ($row[CMo_Mes] == '12')
                                            echo "Diciembre";
											
                                        ?>/ <input type="hidden" name="CMo_Anio<?php  echo $i;?>" id="CMo_Anio<?php  echo $i;?>" value="<?php  echo $row[CMo_Anio] ?>" /><?php  echo $row[CMo_Anio]; ?></td>
                                    <td align="center">
									<input type="hidden" name="CMo_1er_Recargo<?php  echo $i;?>" id="CMo_1er_Recargo<?php  echo $i;?>" value="<?php  echo $row[CMo_1er_Recargo] ?>" />
								  <?php  echo $row[CMo_1er_Recargo]; ?></td>
                                  <td align="center">
                                    <input type="hidden" name="CMo_2do_Recargo<?php  echo $i;?>" id="CMo_2do_Recargo<?php  echo $i;?>" value="<?php  echo $row[CMo_2do_Recargo] ?>" />
                                  <?php  echo $row[CMo_2do_Recargo]; ?></td>
                                  <td align="center">
									<input type="hidden" name="CMo_Recargo_Mensual<?php  echo $i;?>" id="CMo_Recargo_Mensual<?php  echo $i;?>" value="<?php  echo $row[CMo_Recargo_Mensual]; ?>" />
								  <?php  echo $row[CMo_Recargo_Mensual]; ?></td>
                                  
                                  <td align="center">
                                    <input type="hidden" name="CMo_Agrupa<?php  echo $i;?>" id="CMo_Agrupa<?php  echo $i;?>" value="<?php  echo $row[CMo_Agrupa]; ?>" />
                                  <?php  echo $Agrupa; ?></td>
                                  
                                  <td align="center">
                                    <input type="hidden" name="CMo_Especial<?php  echo $i;?>" id="CMo_Especial<?php  echo $i;?>" value="<?php  echo $row[CMo_Especial]; ?>" />
                                  <?php  echo $Especial; ?></td>

                                    <td align="center"><a href="#" id="botBorrar<?php  echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a>
                                    
                                    <a href="#" id="botEditar<?php  echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar Registro" title="Editar Registro" width="32" height="32" border="0" /></a>
                               
                                <a href="#" id="botActualizar<?php  echo $i;?>"><img src="imagenes/table_refresh.png" alt="Actualizar  las cuotas de los alumnos" title="Actualizar las cuotas de los alumnos" width="32" height="32" border="0" /></a>
                               
                                  </td>
                                </tr>

                                <?php 
                            }//fin while
                            ?>
                        </tbody>
                    </table>

                <?php 
                } else echo "No existen datos cargados.";
                ?>
            </td>
        </tr>
    </table>

</div>
    <p><br />
        <br />
    </p>
    <div id="mostrar"></div>
    <p>&nbsp;</p>
