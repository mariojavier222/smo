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
		$("#TriID").val("");
		$("#Nombre").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vNombre = $("#Nombre").val();
			vNivID = $("#NivID").val();
			vLecID = $("#LecID").val();
			vTriID = $("#TriID").val();
			vFechaDesde = $("#FechaDesde").val();
			vFechaHasta = $("#FechaHasta").val();
			
			
			if (vNivID==-1){
				mostrarAlerta("Por favor seleccione un Nivel","Error");
				return;
			}
			if (vLecID==-1){
				mostrarAlerta("Por favor seleccione un Ciclo Lectivo","Error");
				return;
			}

			if (vNombre==""){
				mostrarAlerta("Por favor escriba un nombre para el Periodo o Trimestre","Error");
				return;
			}
			if (vFechaDesde==""){
				mostrarAlerta("Por favor escriba una Fecha Desde para el Periodo o Trimestre","Error");
				return;
			}
			if (vFechaHasta==""){
				mostrarAlerta("Por favor escriba una Fecha Hasta para el Periodo o Trimestre","Error");
				return;
			}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarTrimestre", Nombre: vNombre, TriID: vTriID, NivID: vNivID, LecID: vLecID, FechaDesde: vFechaDesde, FechaHasta: vFechaHasta},
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
	$("input[id^='Editar']").keyup(function(evento){
	 	if (evento.keyCode == 13){
			var i = this.id.substr(6,10);
			guardarNombre(i);
		}
	 });

	 
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		$("#TriID").val($("#TriID" + i).val());
		$("#NivID").val($("#NivID" + i).val());
		$("#LecID").val($("#LecID" + i).val());
		$("#Nombre").val($("#Nombre" + i).val());
		$("#FechaDesde").val($("#FechaDesde" + i).val());
		$("#FechaHasta").val($("#FechaHasta" + i).val());
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		vNombre = $("#Nombre" + i).val();
		vID = $("#TriID" + i).val();
		jConfirm('Esté seguro que desea eliminar el periodo o trimestre <strong>' + vNombre + '</strong>?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarTrimestre", ID: vID }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	 
	$("#triNivID").change(function () {
   		$("#triNivID option:selected").each(function () {
			//alert($(this).val());
				vtriNivID=$(this).val();
				$.ajax({
					cache: false,
					async: false,
					type: "POST",
					data: {triNivID: vtriNivID},
					url: "<?php echo $_SERVER['PHP_SELF'];?>",
					success: function (data){
							$("#principal").html(data);
							$("#cargando").hide();
							}
				});//fin ajax
        });
   	});
	<?php
	if (isset($_POST['triNivID'])){
	?>		
		$("#triNivID").val("<?php echo $_POST['triNivID']?>");
	<?php
	}
	?>
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Configurar Trimestres por Ciclo Lectivo</div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre del Trimestre o Periodo:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" size="40"/>
          <input type="hidden" name="TriID" id="TriID" /></td>
        </tr><tr>
          <td align="right" class="texto">Ciclo Lectivo:</td>
          <td><?php cargarListaLectivo("LecID");?></td>
        </tr>
        <tr>
          <td align="right" class="texto">Pertenece al Nivel:</td>
          <td><?php cargarListaNivel("NivID");?></td>
      </tr>
        <tr>
          <td align="right" class="texto">Desde:</td>
          <td><input name="FechaDesde" type="text" id="FechaDesde" class="required fechaCompleta" alt="fecha de inicio del periodo"  title="fecha de inicio del periodo"/></td>
        </tr>
        <tr>
          <td align="right" class="texto">Hasta:</td>
          <td><input name="FechaHasta" type="text" id="FechaHasta" class="required fechaCompleta" alt="fecha de finalización del periodo"  title="fecha de finalización del periodo"/>
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
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Trimestres cargados en el Lectivo Actual</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            Filtrar por Nivel: <?php cargarListaNivel("triNivID");?><br /><br />
            <?php
        $triNivID = $_POST['triNivID'];
		
		if (!empty($triNivID)) $where = " WHERE Tri_Niv_ID = $triNivID ";
		$sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "SELECT * FROM
    Colegio_Trimestre
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Trimestre.Tri_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Lectivo 
        ON (Colegio_Trimestre.Tri_Lec_ID = Lectivo.Lec_ID) $where ORDER BY Tri_Niv_ID, Tri_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">C&oacute;d.</th>
                  <th align="center">Nombre</th>
                  <th align="center">Nivel</th>
                  <th align="center">Lectivo</th>
                  <th align="center">Fecha Desde</th>
                  <th align="center">Fecha Hasta</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td align="center"><?php echo $row[Tri_ID];?>
                  <input type="hidden" id="TriID<?php echo $i;?>" value="<?php echo $row[Tri_ID];?>" />
                  <input type="hidden" id="NivID<?php echo $i;?>" value="<?php echo $row[Tri_Niv_ID];?>" />
                  <input type="hidden" id="LecID<?php echo $i;?>" value="<?php echo $row[Tri_Lec_ID];?>" />
                  <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Tri_Nombre];?>" />
                  <input type="hidden" id="FechaDesde<?php echo $i;?>" value="<?php echo cfecha($row[Tri_Desde]);?>" />
                  <input type="hidden" id="FechaHasta<?php echo $i;?>" value="<?php echo cfecha($row[Tri_Hasta]);?>" />
                  </td>
                  <td><?php echo $row[Tri_Nombre];?></td>
                  <td><?php echo $row[Niv_Nombre];?></td>
                  <td align="center"><?php echo $row[Lec_Nombre];?></td>
                  <td align="center"><?php echo cfecha($row[Tri_Desde]);?></td>
                  <td align="center"><?php echo cfecha($row[Tri_Hasta]);?></td>
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
	