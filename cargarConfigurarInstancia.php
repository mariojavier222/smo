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
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
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
		$("#CiaID").val("");
		$("#Nombre").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vNombre = $("#Nombre").val();
			vTriID = $("#TriID").val();
			vCiaID = $("#CiaID").val();
			vOrden = $("#Orden").val();
			vOpcional = $("#Opcional").val();
			
			
			if (vTriID==-1){
				mostrarAlerta("Por favor seleccione un Periodo o Trimestre","Error");
				return;
			}
			if (vOpcional==-1){
				mostrarAlerta("Por favor seleccione si la instancia es Opcional o no","Error");
				return;
			}

			if (vNombre==""){
				mostrarAlerta("Por favor escriba un nombre para la Instancia","Error");
				return;
			}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarInstanciaTrimestre", Nombre: vNombre, TriID: vTriID, CiaID: vCiaID, Orden: vOrden, Opcional: vOpcional},
				success: function (data){
						mostrarAlerta(data, "Resultado de la operaci�n");
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
		$("#CiaID").val($("#CiaID" + i).val());
		$("#TriID").val($("#TriID" + i).val());
		$("#NivID").val($("#NivID" + i).val());
		$("#LecID").val($("#LecID" + i).val());
		$("#Nombre").val($("#Nombre" + i).val());
		$("#Orden").val($("#Orden" + i).val());
		$("#Opcional").val($("#Opcional" + i).val());
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		vNombre = $("#Nombre" + i).val();
		vID = $("#CiaID" + i).val();
		jConfirm('�Est� seguro que desea eliminar la instancia <strong>' + vNombre + '</strong>?', 'Confirmar la eliminaci�n', function(r){
    			if (r){//eligi� eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarInstanciaTrimestre", ID: vID }, function(data){
						jAlert(data, 'Resultado de la eliminaci�n');
						recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	 
	
	$("#TriID").change(function () {
   		$("#TriID option:selected").each(function () {
			//alert($(this).val());
				var vTri=$(this).val();
				llenarResto(vTri);
        });
   });
	function llenarResto(vTri){
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "obtenerTrimestreLectivo", TriID: vTri},
				success: function (data){
						$("#LecID").val(data);
						$("#cargando").hide();						
					}
			});//fin ajax
		$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "obtenerTrimestreNivel", TriID: vTri},
				success: function (data){
						$("#NivID").val(data);
						$("#cargando").hide();						
					}
			});//fin ajax
		
	}//fin function
	
	$("#ciaNivID").change(function () {
   		$("#ciaNivID option:selected").each(function () {
			//alert($(this).val());
				vciaNivID=$(this).val();
				$.ajax({
					cache: false,
					async: false,
					type: "POST",
					data: {ciaNivID: vciaNivID},
					url: "<?php echo $_SERVER['PHP_SELF'];?>",
					success: function (data){
							$("#principal").html(data);
							$("#cargando").hide();
							}
				});//fin ajax
        });
   	});
	<?php
	if (isset($_POST['ciaNivID'])){
	?>		
		$("#ciaNivID").val("<?php echo $_POST['ciaNivID']?>");
	<?php
	}
	?>
	
	llenarResto($("#TriID").val());
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Configurar Instancias de Trimestres</div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre de la Instancia:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" size="40"/>
          <input type="hidden" name="CiaID" id="CiaID" /></td>
        </tr><tr>
          <td align="right" class="texto">Pertenece al Periodo o Trimestre</td>
          <td><?php cargarListaTrimestre("TriID");?></td>
        </tr>
        <tr>
          <td align="right" class="texto">Ciclo Lectivo:</td>
          <td><input name="LecID" type="text" disabled="disabled" id="LecID" /></td>
        </tr>
        <tr>
          <td align="right" class="texto"> Nivel:</td>
          <td><input name="NivID" type="text" disabled="disabled" id="NivID" /></td>
      </tr>
        <tr>
          <td align="right" class="texto">Orden:</td>
          <td><input name="Orden" type="text" class="required fechaCompleta" id="Orden" size="5" maxlength="2" /></td>
        </tr>
        <tr>
          <td align="right" class="texto">Opcional:</td>
          <td><select name="Opcional" id="Opcional" title="Si la opci�n es SI se exigir� la obligatoriedad de las evaluaciones cargadas al momento de procesar el Trimestre">
            <option value="-1">Elija una opci�n</option>
            <option value="1">Si</option>
            <option value="0">No</option>
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
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Instancias de Trimestres cargados</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            Filtrar por Nivel: <?php cargarListaNivel("ciaNivID");?><br /><br />
            <?php
        $ciaNivID = $_POST['ciaNivID'];
		
		if (!empty($ciaNivID)) $where = " WHERE Cia_Niv_ID = $ciaNivID ";
		$sql = "SELECT * FROM
    Colegio_Instancia
    INNER JOIN Colegio_Trimestre 
        ON (Colegio_Instancia.Cia_Tri_ID = Colegio_Trimestre.Tri_ID) AND (Colegio_Instancia.Cia_Niv_ID = Colegio_Trimestre.Tri_Niv_ID) AND (Colegio_Instancia.Cia_Lec_ID = Colegio_Trimestre.Tri_Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Trimestre.Tri_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Lectivo 
        ON (Colegio_Trimestre.Tri_Lec_ID = Lectivo.Lec_ID) $where ORDER BY Cia_Niv_ID, Cia_Lec_ID, Cia_Tri_ID, Cia_Orden";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">Orden</th>
                  <th align="center">Nombre</th>
                  <th align="center">Trimestre</th>
                  <th align="center">Nivel</th>
                  <th align="center">Lectivo</th>
                  <th align="center">Opcional</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td align="center"><?php echo $row[Cia_Orden];?>
                  <input type="hidden" id="CiaID<?php echo $i;?>" value="<?php echo $row[Cia_ID];?>" />
                  <input type="hidden" id="TriID<?php echo $i;?>" value="<?php echo $row[Tri_ID];?>" />
                  <input type="hidden" id="NivID<?php echo $i;?>" value="<?php echo $row[Niv_Nombre];?>" />
                  <input type="hidden" id="LecID<?php echo $i;?>" value="<?php echo $row[Lec_Nombre];?>" />
                  <input type="hidden" id="Nombre<?php echo $i;?>" value="<?php echo $row[Cia_Nombre];?>" />
                  <input type="hidden" id="Orden<?php echo $i;?>" value="<?php echo $row[Cia_Orden];?>" />
                  <input type="hidden" id="Opcional<?php echo $i;?>" value="<?php echo $row[Cia_Opcional];?>" />
                  </td>
                  <td><?php echo $row[Cia_Nombre];?></td>
                  <td><?php echo $row[Tri_Nombre];?></td>
                  <td><?php echo $row[Niv_Nombre];?></td>
                  <td align="center"><?php echo $row[Lec_Nombre];?></td>
                  <td align="center"><?php if ($row[Cia_Opcional]==1) echo "SI"; else echo "NO";?></td>
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
	