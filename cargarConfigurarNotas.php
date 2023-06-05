<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){

		
	<?php
	Obtener_LectivoActual($LecID, $LecNombre);
	obtenerLimitesLectivo($LecID, $LecDesde, $LecHasta);
	$LimiteFecha = "{minDate: '".cfecha($LecDesde)."', maxDate: '".cfecha($LecHasta)."'}";//*/
	?>
	$("#FechaDesde").datepicker(<?php echo $LimiteFecha;?>);
	$("#FechaHasta").datepicker(<?php echo $LimiteFecha;?>);
	
	$('table.tabla tbody tr:odd').addClass('fila');

 	$('table.tabla tbody tr:even').addClass('fila2');
	
	$("#mostrarNuevo").hide();
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
		limpiarDatos();
	});
	function limpiarDatos(){
		$("#NotID").val("");
		$("#Nombre").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vNombre = $("#Nombre").val();
			vNTiID = $("#NTiID").val();
			vNotID = $("#NotID").val();
			vSiglas = $("#Siglas").val();
			vNumero = $("#Numero").val();
			
			
			if (vNTiID==-1){
				mostrarAlerta("Por favor seleccione un Tipo de Nota","Error");
				return;
			}
			if (vNombre==""){
				mostrarAlerta("Por favor escriba una Nota","Error");
				return;
			}
			if (vSiglas==""){
				mostrarAlerta("Por favor escriba una Sigla que represente a la nota","Error");
				return;
			}
			if (vNumero=="" || !validarNumero(vNumero)){
				mostrarAlerta("Por favor escriba un valor entero para reprentar a la nota numérica","Error");
				return;
			}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarNotaColegio", Nombre: vNombre, NotID: vNotID, NTiID: vNTiID, Siglas: vSiglas, Numero: vNumero},
				success: function (data){
						mostrarAlerta(data, "Resultado de la operación");
						$("#cargando").hide();
						
					}
			});//fin ajax
		}else{
			jAlert("Antes de guardar, haga click en el botón <strong>Nuevo</strong>","Alerta");
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
	

	 
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		$("#NotID").val($("#NotID" + i).val());
		$("#NTiID").val($("#NTiID" + i).val());
		$("#Nombre").val($("#Nombre" + i).val());
		$("#Siglas").val($("#Siglas" + i).val());
		$("#Numero").val($("#Numero" + i).val());
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		vNombre = $("#Nombre" + i).val();
		vID = $("#NotID" + i).val();
		jConfirm('¿Está seguro que desea eliminar el periodo o trimestre <strong>' + vNombre + '</strong>?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarNotaColegio", ID: vID }, function(data){
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
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un País Nuevo" width="48" height="48" border="0" title="Ingresar un País Nuevo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Configurar Notas para los Colegios</div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre de la Nota:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" size="20" maxlength="20"/>
          <input type="hidden" name="NotID" id="NotID" /></td>
        </tr><tr>
          <td align="right" class="texto">Tipo de Nota:</td>
          <td><?php cargarListaNotasTipo("NTiID");?></td>
        </tr>
        <tr>
          <td align="right" class="texto">Siglas de la nota:</td>
          <td><input name="Siglas" type="text" class="required fechaCompleta" id="Siglas"  title="Sigla que identifique a la nota en forma abreviada" size="5" maxlength="5" alt="Sigla que identifique a la nota en forma abreviada"/></td>
        </tr>
        <tr>
          <td align="right" class="texto">Nota en n&uacute;mero:</td>
          <td><input name="Numero" type="text" class="required fechaCompleta" id="Numero"  title="Número entero que represente en valor numérico a la Nota" size="5" maxlength="2" alt="Número entero que represente en valor numérico a la Nota"/>
          </td>
        </tr>
      
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Notas cargadas</div></td>
          </tr>
          <tr>
            <td align="center" class="texto"><br /><br />
            <?php
        
		$sql = "SELECT * FROM
    Colegio_Notas
    INNER JOIN Colegio_NotasTipo 
        ON (Colegio_Notas.Not_NTi_ID = Colegio_NotasTipo.NTi_ID) ORDER BY Not_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">Nota</th>
                  <th align="center">Tipo de Nota</th>
                  <th align="center">Siglas</th>
                  <th align="center">Nota en n&uacute;mero</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td align="center"><?php echo $row[Not_Nombre];?>
                    <input type="hidden" id="NotID<?php echo $i;?>" value="<?php echo $row[Not_ID];?>" />
                    <input type="hidden" id="NTiID<?php echo $i;?>" value="<?php echo $row[NTi_ID];?>" />
                    <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Not_Nombre];?>" />
                    <input type="hidden" id="Siglas<?php echo $i;?>" value="<?php echo $row[Not_Siglas];?>" />
                  <input type="hidden" id="Numero<?php echo $i;?>" value="<?php echo $row[Not_Numero];?>" /></td>
                  <td align="center"><?php echo $row[NTi_Nombre];?></td>
                  <td align="center"><?php echo $row[Not_Siglas];?></td>
                  <td align="center"><?php echo $row[Not_Numero];?></td>
                  <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
            
            <?php
		}else{
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
	