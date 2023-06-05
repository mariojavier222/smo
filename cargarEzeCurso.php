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
		$("#CurID").val("");
		$("#CurNivID").val("");
		$("#CurNombre").val("");
		$("#CurSiglas").val("");
		$("#CurCurso").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vCurID = $("#CurID").val();
			vCurNivID = $("#CurNivID").val();
			vCurNombre = $("#CurNombre").val();
			vCurSiglas = $("#CurSiglas").val();
			vCurTurno = $('input[name="CurTurno"]:checked').val();
			vCurCurso = $("#CurCurso").val();
			vCurColegio = 1;
			
			$.ajax({
				type: "POST",
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarCurso", CurID: vCurID, CurNivID: vCurNivID, CurNombre: vCurNombre, CurSiglas: vCurSiglas, CurTurno: vCurTurno, CurCurso: vCurCurso},
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

		$("#CurID").val($("#CurID" + i).val());
		$("#CurNivID").val($("#CurNivID" + i).val());
		$("#CurNombre").val($("#CurNombre" + i).val());
		$("#CurSiglas").val($("#CurSiglas" + i).val());
		$("#CurCurso").val($("#CurCurso" + i).val());
		/*if ($("#CurTurno" + i).val() == "Tarde") {
			$('input[id="Tarde"]').prop( "checked", true );
		}
		else {
			$('input[id="Mañana"]').prop( "checked", true );
		}*/

		$('input[id="' + $("#CurTurno" + i).val() + '"]').prop( "checked", true );
		//$( "#" + ("#CurTurno" + i).val()).prop( "checked", true );

		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 

    $("a[id^='botBorrar']").click(function(evento){											  
        evento.preventDefault();
        var i = this.id.substr(9,10);
        vCurID = $("#CurID" + i).val();
        vCurNombre = $("#CurNombre" + i).val();
		
        jConfirm('¿Est&aacute; seguro que desea eliminar el curso ?', 'Confirmar la eliminaci&oacute;n', function(r){
            if (r){//eligi� eliminar
                $.post("cargarOpciones.php", { opcion: "eliminarCurso", CurID: vCurID, CurNombre: vCurNombre }, function(data){
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
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nuevo Curso" width="48" height="48" border="0" title="Nuevo Curso" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Curso" width="48" height="48" border="0" title="Buscar Curso" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nuevo Curso</div></td>
      </tr>
	  <tr>
	    <td align="right" class="texto">&nbsp;</td>
	    <td><input name="CurID" type="hidden" id="CurID"/></td>
      </tr>
	  <td class="texto"><div align="right">Nivel</div></td>
          <td>

          <?php 	
		    $sql = "SET NAMES UTF8;";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			$sql = "SELECT Niv_ID, Niv_Nombre FROM Colegio_Nivel ORDER BY Niv_ID";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "<select name='CurNivID' id='CurNivID' style='position:relative;z-index:1'>";
			echo "<option value='-1'>Seleccione una opci&oacute;n</option>";
			while ($row = mysqli_fetch_array($result)){
				echo "<option value='$row[Niv_ID]'>$row[Niv_Nombre]</option>";
			}//fin del while

			echo "</select>"; ?>
          	
          </td>
        </tr>
        <tr>
          <td align="right" class="texto">Nombre del Curso</td>
          <td><label>
            <input name="CurNombre" type="text" id="CurNombre" size="40" />
          </label></td>
        </tr>
        <tr>
          <td align="right" class="texto">Siglas</td>
          <td><label>
            <input name="CurSiglas" type="text" id="CurSiglas" size="40" />
          </label></td>
        </tr>
	  	<tr>
<!-- 	    <td align="right" class="texto">&nbsp;</td>
	    <td><input type="radio" name="CurTipo" id="CurColegio" value="CurColegio" checked/>
			<label for="CurColegio">Colegio</label>

			<input type="radio" name="CurTipo" id="CurGrado" value="CurGrado"/>
			<label for="CurGrado">Grado</label>

			<input type="radio" name="CurTipo" id="CurPosgrado" value="CurPosgrado"/>
			<label for="CurPosgrado">Posgrado</label></td>
      	</tr> -->
	  	<tr>
	    <td align="right" class="texto">Turno</td>
	    <td style="font-size: 13px;"><input type="radio" name="CurTurno" id="Mañana" value="Mañana" checked/>
			<label for="Mañana">Mañana</label>

			<input type="radio" name="CurTurno" id="Tarde" value="Tarde"/>
			<label for="Tarde">Tarde</label></td>
      	</tr>
        <tr>
          <td align="right" class="texto">Año del nivel</td>
          <td><label>
            <input name="CurCurso" type="number" id="CurCurso" size="4" min="0" max="10" />
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
            <td><div align="center" class="titulo_noticia"> Listado de Cursos</div></td>
        </tr>
        <tr>
            <td align="center" class="texto">
                <?php
				$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $sql = "SELECT Niv_Nombre, Curso.* FROM Colegio_Nivel INNER JOIN Curso ON (Niv_ID = Cur_Niv_ID);";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    ?>
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
                        <thead>
                            <tr class="ui-widget-header">
                                <th align="center">Nivel</th>
                                <th align="center">Curso</th>
                                <th align="center">Turno</th>
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
                                     <input name="CurID" type="hidden" id="CurID<?php echo $i;?>" value="<?php echo $row[Cur_ID]; ?>" />
                                     <input name="CurNivID" type="hidden" id="CurNivID<?php echo $i;?>" value="<?php echo $row[Cur_Niv_ID]; ?>" />
                                     <input name="CurNombre" type="hidden" id="CurNombre<?php echo $i;?>" value="<?php echo $row[Cur_Nombre]; ?>" />
                                     <input name="CurSiglas" type="hidden" id="CurSiglas<?php echo $i;?>" value="<?php echo $row[Cur_Siglas]; ?>" />
                                     <input name="CurColegio" type="hidden" id="CurColegio<?php echo $i;?>" value="<?php echo $row[Cur_Colegio]; ?>" />
                                     <input name="CurGrado" type="hidden" id="CurGrado<?php echo $i;?>" value="<?php echo $row[Cur_Grado]; ?>" />
                                     <input name="CurPosgrado" type="hidden" id="CurPosgrado<?php echo $i;?>" value="<?php echo $row[Cur_Posgrado]; ?>" />
                                     <input name="CurTurno" type="hidden" id="CurTurno<?php echo $i;?>" value="<?php echo $row[Cur_Turno]; ?>" />
                                     <input name="CurCurso" type="hidden" id="CurCurso<?php echo $i;?>" value="<?php echo $row[Cur_Curso]; ?>" />
                                      <?php echo $row[Niv_Nombre]; ?> </td>
                                    <td align="center"><?php echo $row[Cur_Nombre]; ?></td>
                                    
                                    <td align="center"><?php echo $row[Cur_Turno]; ?></td>

                                    <td align="center">                                    
                                    <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar Curso" title="Editar Curso" width="32" height="32" border="0" /></a>
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
	