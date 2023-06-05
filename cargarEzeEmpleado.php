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
		$("#ETiID").val("");
		$("#ETiNombre").val("");
		$("#ETiSiglas").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		
		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vETiID = $("#ETiID").val();
			vETiNombre = $("#ETiNombre").val();
			vETiSiglas = $("#ETiSiglas").val();
					
			$.ajax({
				type: "POST",
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarTipoEmpleado", ETiID: vETiID, ETiNombre: vETiNombre, ETiSiglas: vETiSiglas},
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
		$("#ETiID").val($("#ETiID" + i).val());
		$("#ETiNombre").val($("#ETiNombre" + i).val());
		$("#ETiSiglas").val($("#ETiSiglas" + i).val());

		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 

    $("a[id^='botBorrar']").click(function(evento){											  
        evento.preventDefault();
        var i = this.id.substr(9,10);
        vETiNombre = $("#ETiNombre" + i).val();
        vETiID = $("#ETiID" + i).val();
		
        jConfirm('¿Est&aacute; seguro que desea eliminar el Tipo ?', 'Confirmar la eliminaci&oacute;n', function(r){
            if (r){//eligi� eliminar
                $.post("cargarOpciones.php", { opcion: "eliminarTipoEmpleado", ETiNombre: vETiNombre, ETiID: vETiID }, function(data){
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
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo Tipo" width="48" height="48" border="0" title="Nuevo Tipo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Tipo" width="48" height="48" border="0" title="Buscar Tipo" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo tipo" width="48" height="48" border="0" title="Guardar nuevo tipo" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nuevo Tipo de Empleado</div></td>
      </tr>
	  <tr>
	    <td align="right" class="texto">&nbsp;</td>
	    <td><input type="hidden" name="ETiID" id="ETiID" /></td>
      </tr>
      <tr>
	  <td align="right" class="texto" width="30%">Tipo</td>
          <td><input name="ETiNombre" type="text" id="ETiNombre" size="40"/></td>
      </tr>
        <tr>
          <td align="right" class="texto" width="30%">Siglas</td>
          <td><label>
            <input name="ETiSiglas" type="text" id="ETiSiglas" size="40" />
          </label></td>
        </tr>
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Tipos de Empleados</div></td>
        </tr>
        <tr>
            <td align="center" class="texto">
                <?php
				$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "SELECT * FROM EmpleadoTipo";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    ?>
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
                        <thead>
                            <tr class="ui-widget-header">
                                <th align="center">Nombre</th>
                                <th align="center">Siglas</th>
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
                                     <input name="ETiID" type="hidden" id="ETiID<?php echo $i;?>" value="<?php echo $row[ETi_ID]; ?>" />
                                     <input name="ETiNombre" type="hidden" id="ETiNombre<?php echo $i;?>" value="<?php echo $row[ETi_Nombre]; ?>" />
                                     <input name="ETiSiglas" type="hidden" id="ETiSiglas<?php echo $i;?>" value="<?php echo $row[ETi_Siglas]; ?>" />
                                      <?php echo $row[ETi_Nombre]; ?> </td>
                                    <td align="center"><?php echo $row[ETi_Siglas]; ?></td>
                                    <td align="center">                                    
                                    <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar Tipo" title="Editar Tipo" width="32" height="32" border="0" /></a>
                                    <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar Tipo" title="Borrar Tipo" width="32" height="32" border="0" /></a>
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
	