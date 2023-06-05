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
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vCarrera = $("#carrera").val();
			vUniID = $("#UniID").val();
			if (vUniID==-1){
				mostrarAlerta("Por favor seleccione una Unidad","Error");
				return;
			}
			if (vCarrera==""){
				mostrarAlerta("Por favor escriba una nueva Carrera","Error");
				return;
			}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarCarrera", Carrera: vCarrera, UniID: vUniID},
				success: function (data){
						if (data=="Ya existe"){
							mostrarAlerta("Por favor elija otro nombre de Carrera porque la Carrera ingresada <strong>" + vCarrera + "</strong> ya existe.", "Error");
						}else{
							mostrarAlerta("Se guardó la carrera <strong>" + data + "</strong> correctamente.", "Resultado de la operación");
						}
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

	 $("a[id^='botGuardar']").click(function(evento){
		evento.preventDefault();
		var i = this.id.substr(10,10);
		//alert(i);
		guardarNombre(i);
	 });
	function guardarNombre(i){
		var vCar = $("#CarID" + i).val();
		var vUni = $("#Uni" + i).val();
		var vNombreCar = $("#Editar" + i).val();
		var vNombreUni = $("#NombreUni" + i + " option:selected").text();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: { opcion: "guardarCarrera", CarID: vCar, Carrera: vNombreCar, UniID: vUni },
			url: "cargarOpciones.php",
			success: function (data){
//					recargarPagina();
//					alert(data);
					$("#NombreCar" + i).text(vNombreCar);
					//$("#NombreCar" + i).text(data);
					$("#Uni" + i).val(vUni);
					$("#cargando").hide();//*/
					}
		});//fin ajax
		$(".mostrar" + i).show();
		$(".ocultar" + i).hide();
	 
	 }//fin funcion

	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		$("#NombreCar" + i).hide();
		$("#NombreUni" + i).hide();
		$("#Editar" + i).val($("#NombreCar" + i).text());
		$("#Editar" + i).show();
		var vUnidad = $("#UniOculto" + i).val();
		$("#Uni" + i).val(vUnidad);
		$("#Uni" + i).show();
		
		$("#Editar" + i).focus();
	 });//fin evento click//*/	 
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		vNombre = $("#NombreCar" + i).text();
		vID = $("#CarID" + i).val();
		jConfirm('¿Está seguro que desea eliminar la carrera <strong>' + vNombre + '</strong>?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarCarrera", ID: vID }, function(data){
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nueva Carrera </div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre de la Carrera:</div></td>
          <td><input name="carrera" type="text" id="carrera" size="40"/></td>
        </tr><tr>
          <td align="right" class="texto">Unidad:</td>
          <td><?php cargarListaUnidad("UniID");?></td>
      </tr>
      
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Carreras cargadas</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            <?php
        $sql = "SELECT * FROM Carrera
    INNER JOIN Unidad 
        ON (Carrera.Car_Uni_ID = Unidad.Uni_ID) ORDER BY Car_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">N&deg;</th>
                  <th align="center">Carrera</th>
                  <th align="center">Unidad</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td><?php echo $row[Car_ID];?>
                  <input type="hidden" id="CarID<?php echo $i;?>" value="<?php echo $row[Car_ID];?>" />
                  </td>
                  <td><span id="NombreCar<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre" class="mostrar<?php echo $i;?>"><?php echo $row[Car_Nombre];?></span>
                  <input type="text" class="ocultar<?php echo $i;?>" id="Editar<?php echo $i;?>" value="<?php echo $row[Car_Nombre];?>" size="50" /></td>
                  <td align="center"><span class="mostrar<?php echo $i;?>" id="NombreUni<?php echo $i;?>"><?php echo $row[Uni_Siglas];?></span>
                    <?php cargarListaUnidad("Uni".$i, "ocultar".$i);?>
                    <input type="hidden" id="UniOculto<?php echo $i;?>" value="<?php echo $row[Uni_ID];?>" /></td>
                  <td align="center"><a href="#" id="botGuardar<?php echo $i;?>"><img src="imagenes/page_save.png" alt="Guardar los cambios" title="Guardar los cambios" width="32" height="32" border="0" /></a> <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
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
	