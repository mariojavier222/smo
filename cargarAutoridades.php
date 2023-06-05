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
		$("#AutID").val("");
		$("#Nombre").val("");
		$("#Cargo").val("");
		$("#Email").val("");
		$("#Orden").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vNombre = $("#Nombre").val();
			vAutID = $("#AutID").val();
			vUniID = $("#UniID").val();
			vCargo = $("#Cargo").val();
			vEmail = $("#Email").val();
			vOrden = $("#Orden").val();
			
			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarAutoridad", Nombre: vNombre, AutID: vAutID, UniID: vUniID, Cargo: vCargo, Email: vEmail, Orden: vOrden},
				success: function (data){
						mostrarAlerta(data, "Resultado de la operaci�n");
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
		var i = this.id.substr(9,10);
		$("#AutID").val($("#AutID" + i).val());
		$("#Nombre").val($("#Nombre" + i).val());
		$("#Cargo").val($("#Cargo" + i).val());
		$("#UniID").val($("#UniID" + i).val());
		$("#Email").val($("#Email" + i).val());
		$("#Orden").val($("#Orden" + i).val());
		
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 
	$("#autUniID").change(function () {
   		$("#autUniID option:selected").each(function () {
			//alert($(this).val());
				vautUniID=$(this).val();
				$.ajax({
					cache: false,
					async: false,
					type: "POST",
					data: {autUniID: vautUniID},
					url: "<?php echo $_SERVER['PHP_SELF'];?>",
					success: function (data){
							$("#principal").html(data);
							$("#cargando").hide();
							}
				});//fin ajax
        });
   	});
	<?php
	if (isset($_POST['autUniID'])){
	?>		
		$("#autUniID").val("<?php echo $_POST['autUniID']?>");
	<?php
	}
	?>
});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un Pa�s Nuevo" width="48" height="48" border="0" title="Ingresar un Pa�s Nuevo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nueva Autoridad por Unidad Acad&eacute;mica</div></td>
      </tr>
	  <tr>
	    <td align="right" class="texto">&nbsp;</td>
	    <td><input type="hidden" name="AutID" id="AutID" /></td>
      </tr>
	  <td class="texto"><div align="right">Nombre y Apellido:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" size="40"/></td>
        </tr><tr>
          <td align="right" class="texto">Cargo:</td>
          <td><label>
            <input name="Cargo" type="text" id="Cargo" size="40" />
          </label></td>
        </tr>
        <tr>
          <td align="right" class="texto">Pertenece a la Unidad:</td>
          <td><?php cargarListaUnidad("UniID");?></td>
      </tr>
        <tr>
          <td align="right" class="texto">Correo electr&oacute;nico:</td>
          <td><label>
            <input name="Email" type="text" id="Email" size="40" />
          </label></td>
        </tr>
        <tr>
          <td align="right" class="texto">Orden:</td>
          <td><label>
            <input name="Orden" type="text" id="Orden" size="5" maxlength="2" />
          </label></td>
        </tr>
        <tr>
          <td align="right" class="texto">Foto:</td>
          <td>&nbsp;</td>
        </tr>
      
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Autoridades cargadas</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            Filtrar por Unidad: <?php cargarListaUnidad("autUniID");?><br /><br />
            <?php
        $autUniID = $_POST['autUniID'];
		if (!empty($autUniID)) $where = " WHERE Aut_Uni_ID = $autUniID ";
		$sql = "SELECT * FROM     Autoridad
    INNER JOIN Unidad 
        ON (Aut_Uni_ID = Uni_ID) $where ORDER BY Uni_ID, Aut_Orden";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">Orden</th>
                  <th align="center">Unidad</th>
                  <th align="center">Nombre y Apellido</th>
                  <th align="center">Cargo</th>
                  <th align="center">Email</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td align="center"><?php echo $row[Aut_Orden];?>
                  <input type="hidden" id="AutID<?php echo $i;?>" value="<?php echo $row[Aut_ID];?>" />
                  <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Aut_Nombre];?>" />
                  <input type="hidden" id="Cargo<?php echo $i;?>" value="<?php echo $row[Aut_Cargo];?>" />
                  <input type="hidden" id="UniID<?php echo $i;?>" value="<?php echo $row[Aut_Uni_ID];?>" />
                  <input type="hidden" id="Email<?php echo $i;?>" value="<?php echo $row[Aut_Email];?>" />
                  <input type="hidden" id="Orden<?php echo $i;?>" value="<?php echo $row[Aut_Orden];?>" />
                  </td>
                  <td><?php echo $row[Uni_Siglas];?></td>
                  <td><?php echo $row[Aut_Nombre];?></td>
                  <td><?php echo $row[Aut_Cargo];?></td>
                  <td><?php echo $row[Aut_Email];?></td>
                  <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> </td>
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
	