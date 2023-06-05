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
	$LecID = gLectivoActual($LecNombre);
	?>
	
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
		$("input").val("");
		//$("#Nombre").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vNombre = $("#Nombre").val();
			//vNivID = $("#NivID").val();
			vLecID = $("#LecID").val();
			vDimID = $("#DimID").val();
			Dim_Detalle = $("#Dim_Detalle").val();
			Dim_Transversal = $("#Dim_Transversal").val();
			

			if (vLecID==-1){
				mostrarAlerta("Por favor seleccione un Ciclo Lectivo","Error");
				return;
			}

			if (vNombre==""){
				mostrarAlerta("Por favor escriba un nombre para la Dimensión","Error");
				return;
			}
			
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarDimension", Nombre: vNombre, DimID: vDimID, LecID: vLecID, Dim_Detalle: Dim_Detalle, Dim_Transversal: Dim_Transversal},
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
		$("#DimID").val($("#DimID" + i).val());
		$("#LecID").val($("#LecID" + i).val());
		$("#Nombre").val($("#Nombre" + i).val());
		$("#Dim_Detalle").val($("#Dim_Detalle" + i).val());
		$("#Dim_Transversal").val($("#Dim_Transversal" + i).val());
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		vNombre = $("#Nombre" + i).val();
		vID = $("#DimID" + i).val();
		jConfirm('Esté seguro que desea eliminar la Dimensión <strong>' + vNombre + '</strong>?', 'Confirmar la eliminación', function(r){
    			if (r){//eligi� eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarDimension", ID: vID }, function(data){
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
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un Pa�s Nuevo" width="48" height="48" border="0" title="Ingresar un Pa�s Nuevo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="iconos/mod_listar.png" alt="Paises" width="32" height="32" align="absmiddle" /> Configurar Dimensiones por Ciclo Lectivo</div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre de la Dimensión:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" size="40"/>
          <input type="hidden" name="DimID" id="DimID" /></td>
        </tr><tr>
          <td align="right" class="texto">Ciclo Lectivo:</td>
          <td><?php cargarListaLectivo("LecID");?></td>
        </tr>
        <tr>
          <td align="right" class="texto">Detalle adicional:</td>
          <td><textarea name="Dim_Detalle" id="Dim_Detalle" cols="45" rows="5"></textarea></td>
        </tr>
        <tr>
          <td align="right" class="texto">¿Es transversal?:</td>
          <td><select name="Dim_Transversal" id="Dim_Transversal">
            <option value="1">Si</option>
            <option value="0" selected="selected">No</option>
          </select></td>
        </tr>
      
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="iconos/mod_listar.png" width="32" height="32" align="absmiddle" /> Listado de Dimensiones en el Lectivo Actual</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            <br />
            <?php
		
		$sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$sql = "SELECT * FROM
    Colegio_Dimension
    INNER JOIN Lectivo 
        ON (Dim_Lec_ID = Lec_ID) WHERE Dim_Lec_ID = $LecID ORDER BY Dim_Lec_ID, Dim_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">C&oacute;d.</th>
                  <th align="center">Nombre</th>
                  <th align="center">Lectivo</th>
                  <th align="center">Detalle</th>
                  <th align="center">¿Es transversal?</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td align="center"><?php echo $row[Dim_ID];?>
                  <input type="hidden" id="DimID<?php echo $i;?>" value="<?php echo $row[Dim_ID];?>" />
                  <input type="hidden" id="Dim_Detalle<?php echo $i;?>" value="<?php echo $row[Dim_Detalle];?>" />
                  <input type="hidden" id="LecID<?php echo $i;?>" value="<?php echo $row[Dim_Lec_ID];?>" />
                  <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Dim_Nombre];?>" />
                  <input type="hidden" id="Dim_Transversal<?php echo $i;?>" value="<?php echo $row[Dim_Transversal];?>" />
                  
                  </td>
                  <td><?php echo $row[Dim_Nombre];?></td>
                  <td align="center"><?php echo $row[Lec_Nombre];?></td>
                  <td align="center"><?php echo $row[Dim_Detalle];?></td>
                  <td align="center">
				  <?php 
				  if ($row[Dim_Transversal]==1)
				  	echo "SI";
				else
					echo "NO";
				  ?>
                  </td>
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
	