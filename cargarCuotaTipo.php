<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){

		
	$('table.tabla tbody tr:odd').addClass('fila');

 	$('table.tabla tbody tr:even').addClass('fila2');
	
	$("#mostrarNuevo").hide();
	$("#barraGuardar").hide();
	//$(".ocultar").hide();
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
		$("#barraGuardar").show();
		limpiarDatos();		
	});
	function limpiarDatos(){
		$("#LecID").val("");
		$("#Ciclo").val("");
		$("#Desde").val("");
		$("#Hasta").val("");
		$("#Actual").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vLecID = $("#LecID").val();
			vCiclo = $("#Ciclo").val();
			vDesde = $("#Desde").val();
			vHasta = $("#Hasta").val();
			if( $('#Actual').prop('checked') ) {
    			vActual = 1;
			}
			else {
				vActual = 0;
			}
			
			
			$.ajax({
				type: "POST",
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarLectivo", LecID: vLecID, Ciclo: vCiclo, Desde: vDesde, Hasta: vHasta, Actual: vActual},
				success: function (data){
						mostrarAlerta(data, "Resultado de la operacion");
						recargarPagina();
						$("#cargando").hide();
					}
			});//fin ajax
		}else{
			jAlert("Antes de guardar, haga click en el bot�n <strong>Nuevo</strong>","Alerta");
		}//fin if
	});	
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
	 

	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		$("#barraGuardar").show();
		var i = this.id.substr(9,10);
		$("#LecID").val($("#LecID" + i).val());
		$("#Ciclo").val($("#Ciclo" + i).val());
		$("#Desde").val($("#Desde" + i).val());
		$("#Hasta").val($("#Hasta" + i).val());
		if( $("#Actual" + i).val() == 1){
			$( "#Actual" ).prop( "checked", true );
		}
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 

    $("a[id^='botBorrar']").click(function(evento){											  
        evento.preventDefault();
        var i = this.id.substr(9,10);
        vLecID = $("#LecID" + i).val();
        vCiclo = $("#Ciclo" + i).val();
		
        jConfirm('¿Est&aacute; seguro que desea eliminar el ciclo ?', 'Confirmar la eliminaci&oacute;n', function(r){
            if (r){//eligi� eliminar
                $.post("cargarOpciones.php", { opcion: "eliminarLectivo", LecID: vLecID, Ciclo: vCiclo }, function(data){
                    jAlert(data, 'Resultado de la eliminación');
                    recargarPagina();
                });//fin post					
            }//fin if
        });//fin del confirm//*/
	
    });//fin evento click//*/

});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo Ciclo Lectivo" width="48" height="48" border="0" title="Nuevo Ciclo Lectivo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Ciclo Lectivo" width="48" height="48" border="0" title="Buscar Ciclo Lectivo" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nuevo Ciclo Lectivo</div></td>
      </tr>
	  <tr>
	    <td align="right" class="texto">&nbsp;</td>
	    <td><input type="hidden" name="LecID" id="LecID" /></td>
      </tr>
	  <td class="texto"><div align="right">Ciclo Lectivo</div></td>
          <td><input name="Ciclo" type="number" id="Ciclo" size="4" min="1980" max="2099"/></td>
        </tr>
        <tr>
          <td align="right" class="texto">Desde:</td>
          <td><label>
            <input name="Desde" type="date" id="Desde" size="40" />
          </label></td>
        </tr>
        <tr>
          <td align="right" class="texto">Hasta:</td>
          <td><label>
            <input name="Hasta" type="date" id="Hasta" size="40" />
          </label></td>
        </tr>
	  <tr>
	    <td align="right" class="texto">¿Ciclo lectivo actual?</td>
	    <td><input type="checkbox" name="Actual" id="Actual"/></td>
      </tr>
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Ciclos Lectivos</div></td>
        </tr>
        <tr>
            <td align="center" class="texto">
                <?php
				$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "SELECT * FROM Lectivo";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    ?>
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
                        <thead>
                            <tr class="ui-widget-header">
                                <th align="center">Ciclo</th>
                                <th align="center">Desde</th>
                                
                                <th align="center">Hasta</th>
                                <th align="center">Actual</th>
                                <th align="center">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                $i++;
                                ?>
                                <tr>
                                    
                                    <td align="center">
                                     <input name="LecID" type="hidden" id="LecID<?php echo $i;?>" value="<?php echo $row[Lec_ID]; ?>" />
                                     <input name="Ciclo" type="hidden" id="Ciclo<?php echo $i;?>" value="<?php echo $row[Lec_Nombre]; ?>" />
                                     <input name="Desde" type="hidden" id="Desde<?php echo $i;?>" value="<?php echo $row[Lec_Desde]; ?>" />
                                     <input name="Hasta" type="hidden" id="Hasta<?php echo $i;?>" value="<?php echo $row[Lec_Hasta]; ?>" />
                                     <input name="Actual" type="hidden" id="Actual<?php echo $i;?>" value="<?php echo $row[Lec_Actual]; ?>" />
                                      <?php echo $row[Lec_Nombre]; ?> </td>
                                    <td align="center"><?php echo $row[Lec_Desde]; ?></td>
                                    
                                    <td align="center"><?php echo $row[Lec_Hasta]; ?></td>
                                   
                                    <td align="center"><?php if ($row[Lec_Actual]) {?>
                                    <img src="imagenes/ins_definitiva.png" width="32" height="32" border="0" /><?php } ?></td>

                                    <td align="center">                                    
                                    <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar Ciclo" title="Editar Ciclo" width="32" height="32" border="0" /></a>
                                    <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a>
                                  </td>
                                </tr>

                                <?php
                            }//fin while
                            ?>
                        </tbody>
                    </table>

                    <?php
                } else {
                    ?>
                    No existen datos cargados.
                    <?php
                }
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
	