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
		$("#MatID").val("");
		$("#Materia").val("");
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vMateria = $("#Materia").val();
			vCarID = $("#CarID").val();
			vCurID = $("#CurID").val();
			vMatID = $("#MatID").val();
			vOptativa = $("#Optativa:checked").length;
			if (vOptativa>0) vOptativa = 1;else vOptativa=0;
			
			if (vMatID==-1){
				mostrarAlerta("Por favor escriba un c�digo de Materia","Error");
				return;
			}
			if (vCarID==-1){
				mostrarAlerta("Por favor seleccione una Carrera","Error");
				return;
			}
			if (vCurID==-1){
				mostrarAlerta("Por favor seleccione un Curso","Error");
				return;
			}

			if (vMateria==""){
				mostrarAlerta("Por favor escriba un nombre para la Materia","Error");
				return;
			}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarMateria", Materia: vMateria, MatID: vMatID, CarID: vCarID, CurID: vCurID, Optativa: vOptativa},
				success: function (data){
						mostrarAlerta(data, "Resultado de la operación");
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
		$("#MatID").val($("#MatID" + i).val());
		$("#CarID").val($("#CarID" + i).val());
		$("#CurID").val($("#CurID" + i).val());
		$("#Materia").val($("#NombreMat" + i).val());
		var vOpt = $("#Optativa" + i).val();
		if (vOpt == 1){
			$("#Optativa").attr("checked", "checked");
		}else{
			$("#Optativa").attr("checked", "");
		}
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 
	$("#matCarID").change(function () {
   		$("#matCarID option:selected").each(function () {
			//alert($(this).val());
				vmatCarID=$(this).val();
				$.ajax({
					cache: false,
					async: false,
					type: "POST",
					data: {matCarID: vmatCarID},
					url: "<?php echo $_SERVER['PHP_SELF'];?>",
					success: function (data){
							$("#principal").html(data);
							$("#cargando").hide();
							}
				});//fin ajax
        });
   	});
	<?php
	if (isset($_POST['matCarID'])){
	?>		
		$("#matCarID").val("<?php echo $_POST['matCarID']?>");
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Materias por Carrera </div></td>
      </tr>
	  <tr>
	    <td align="right" class="texto">C&oacute;digo:</td>
	    <td><input name="MatID" type="text" id="MatID" size="5" maxlength="3" /></td>
      </tr>
	  <td class="texto"><div align="right">Nombre de la Materia:</div></td>
          <td><input name="Materia" type="text" id="Materia" size="40"/></td>
        </tr><tr>
          <td align="right" class="texto">Pertenece a la Carrera:</td>
          <td><?php cargarListaCarrera("CarID");?></td>
      </tr>
        <tr>
          <td align="right" class="texto">Curso:</td>
          <td><?php cargarListaCursos("CurID");?></td>
        </tr>
        <tr>
          <td align="right" class="texto">&iquest;Es optativa?:</td>
          <td><input name="Optativa" type="checkbox" id="Optativa" /></td>
        </tr>
      
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Materias cargadas</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            Filtrar por Carrera: <?php cargarListaCarrera("matCarID");?><br /><br />
            <?php
        $matCarID = $_POST['matCarID'];
		if (!empty($matCarID)) $where = " WHERE Mat_Car_ID = $matCarID ";
		$sql = "SELECT * FROM     Materia
    INNER JOIN Carrera 
        ON (Materia.Mat_Car_ID = Carrera.Car_ID)
    INNER JOIN Curso 
        ON (Materia.Mat_Cur_ID = Curso.Cur_ID) $where ORDER BY Car_ID, Mat_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">C&oacute;d.</th>
                  <th align="center">Materia</th>
                  <th align="center">Carrera</th>
                  <th align="center">Curso</th>
                  <th align="center">Optativa</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td align="center"><?php echo $row[Mat_ID];?>
                  <input type="hidden" id="MatID<?php echo $i;?>" value="<?php echo $row[Mat_ID];?>" />
                  <input type="hidden" id="CarID<?php echo $i;?>" value="<?php echo $row[Mat_Car_ID];?>" />
                  <input type="hidden" id="CurID<?php echo $i;?>" value="<?php echo $row[Mat_Cur_ID];?>" />
                  <input type="hidden" id="NombreMat<?php echo $i;?>" value="<?php echo $row[Mat_Nombre];?>" />
                  <input type="hidden" id="Optativa<?php echo $i;?>" value="<?php echo $row[Mat_Optativa];?>" />
                  </td>
                  <td><?php echo $row[Mat_Nombre];?></td>
                  <td><?php echo $row[Car_Nombre];?></td>
                  <td><?php echo $row[Cur_Nombre];?></td>
                  <td align="center"><?php 
				  if ($row[Mat_Optativa]==1) echo "SI";else echo"NO";
				  ?></td>
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
	