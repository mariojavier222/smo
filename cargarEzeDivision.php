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
		$("#DivID").val("");
		$("#DivNombre").val("");
		$("#DivSiglas").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		
		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vDivID = $("#DivID").val();
			vDivNombre = $("#DivNombre").val();
			vDivSiglas = $("#DivSiglas").val();
				
			$.ajax({
				type: "POST",
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarDivision", DivID: vDivID, DivNombre: vDivNombre, DivSiglas: vDivSiglas},
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
	    $("#barraGuardar").show();								  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		$("#DivID").val($("#DivID" + i).val());
		$("#DivNombre").val($("#DivNombre" + i).val());
		$("#DivSiglas").val($("#DivSiglas" + i).val());

		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 

    $("a[id^='botBorrar']").click(function(evento){											  
        evento.preventDefault();
        var i = this.id.substr(9,10);
        vDivID = $("#DivID" + i).val();
        vDivNombre = $("#DivNombre" + i).val();
		
        jConfirm('¿Est&aacute; seguro que desea eliminar la division ?', 'Confirmar la eliminaci&oacute;n', function(r){
            if (r){//eligi� eliminar
                $.post("cargarOpciones.php", { opcion: "eliminarDivision", DivID: vDivID, DivNombre: vDivNombre }, function(data){
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
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nueva Division" width="48" height="48" border="0" title="Nueva Division" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Division" width="48" height="48" border="0" title="Buscar Division" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar Division" width="48" height="48" border="0" title="Guardar Division" /><br />Guardar</button></td>
      </tr>
</table>
	
<div id="mostrarNuevo">
    <table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nueva Division</div></td>
      </tr>
	  <tr>
	    <td align="right" class="texto">&nbsp;</td>
	    <td><input type="hidden" name="DivID" id="DivID" /></td>
      </tr>
        <tr>
          <td align="right" class="texto" width="30%">Division</td>
          <td><label>
            <input name="DivNombre" type="text" id="DivNombre" size="40" />
          </label></td>
        </tr>
        <tr>
          <td align="right" class="texto" width="30%">Siglas</td>
          <td><label>
            <input name="DivSiglas" type="text" id="DivSiglas" size="40" />
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
            <td><div align="center" class="titulo_noticia"> Listado de Divisiones</div></td>
        </tr>
        <tr>
            <td align="center" class="texto">
                <?php
				$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "SELECT * FROM Division";
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
                                     <input name="DivID" type="hidden" id="DivID<?php echo $i;?>" value="<?php echo $row[Div_ID]; ?>" />
                                     <input name="DivNombre" type="hidden" id="DivNombre<?php echo $i;?>" value="<?php echo $row[Div_Nombre]; ?>" />
                                     <input name="DivSiglas" type="hidden" id="DivSiglas<?php echo $i;?>" value="<?php echo $row[Div_Siglas]; ?>" />
                                      <?php echo $row[Div_Nombre]; ?> </td>
                                    <td align="center"><?php echo $row[Div_Siglas]; ?></td>

                                    <td align="center">                                    
                                    <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar Division" title="Editar Division" width="32" height="32" border="0" /></a>
                                    <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar Division" title="Borrar Division" width="32" height="32" border="0" /></a>
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
	