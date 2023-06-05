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
		

		vTitID = $("#TitID").val();
		if (vTitID==-1){
			mostrarAlerta("Por favor seleccione un Título","Error");
			return;
		}
		vSeguir = true;
		$("input:checked[id^='MatID']").each(function(index){
			vMatID = $(this).val();
			var i = this.id.substr(5,10);
			vCarID = $("#CarID" + i).val();
			vPlaID = $("#PlaID" + i).val();
			//alert(vMatID + "-" + vCarID + "-" + vPlaID + "-" + vTitID);//return;
			//Primero eliminamos todas las materias asociadas al titulo
			
			if (vSeguir){
				$.ajax({
					type: "POST",
					cache: false,
					async: false,			
					url: 'cargarOpciones.php',
					data: {opcion: "eliminarMateriasTitulo", CarID: vCarID, PlaID: vPlaID, TitID: vTitID},
					success: function (data){
						//alert(data);
						$("#cargando").hide();
						vSeguir = false;
							//resultado = resultado + data;
						}
				});//fin ajax//*/
			}//fin if
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarMateriaTitulo", MatID: vMatID, CarID: vCarID, PlaID: vPlaID, TitID: vTitID},
				success: function (data){
					//alert(data);
					$("#cargando").hide();
						//resultado = resultado + data;
						
					}
			});//fin ajax//*/
		});//fin each
		mostrarAlerta("Se guardaron correctamente las materias", "Resultado de la operación");

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

	$("#TitID").change(function () {
   		$("#TitID option:selected").each(function () {
			//alert($(this).val());
				vTitID=$(this).val();
				$.ajax({
					cache: false,
					async: false,
					type: "POST",
					data: {TitID: vTitID},
					url: "<?php echo $_SERVER['PHP_SELF'];?>",
					success: function (data){
							$("#principal").html(data);
							$("#cargando").hide();
							}
				});//fin ajax
        });
   	});
	<?php
	if (isset($_POST['TitID'])){
	?>		
		$("#TitID").val("<?php echo $_POST['TitID']?>");
	<?php
	}
	?>
	
	//marcar y desmarcar todo
	$("#marcar").click(function(evento){
		evento.preventDefault();
		vTotal = 0;
		$( ":checkbox").attr('checked', 'checked');
		$("#totalCuotas").val($("#totalesCuotas").val());
		$("input[id^='Nuevo']").each(function(){
			i = this.id.substr(5,10);					
			vImporte = parseInt($("#cuotas" + i).val());
			vTotal += parseInt(vImporte);		
		});
		$("#totalPagar").val(vTotal);
	});
	$("#desmarcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").attr('checked', '');
		$("#totalPagar").val(0);
		$("#totalCuotas").val(0);
	}); 
});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>        
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
      </tr>
</table>
<div id="divBuscador">
      
<table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Asignar  Materias por T&iacute;tulo</div></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="texto">&nbsp;</td>
          </tr>
          <tr>
            <td width="51%" align="right" class="texto">T&iacute;tulos disponibles:</td>
            <td width="49%" class="texto"><?php cargarListaTituloCarrera("TitID");?></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="texto"><br />
            <?php
        $TitID = $_POST['TitID'];
		$CarID = 0;//Inicializamos la variable
		if (!empty($TitID)) obtenerDatosTitulo($TitID, $CarID, $PlaID);
		$where = " WHERE Mat_Car_ID = $CarID ";
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
                  <th align="center">#</th>
                  <th align="center">C&oacute;d.</th>
                  <th align="center">Materia</th>
                  <th align="center">Carrera</th>
                  <th align="center">Curso</th>
                  <th align="center">Optativa</th>
                 </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					$sql = "SELECT * FROM TituloMateria WHERE TMa_Mat_ID = $row[Mat_ID] AND TMa_Car_ID = $CarID AND TMa_Pla_ID = $PlaID AND TMa_Tit_ID = $TitID";
					$resultTit = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
					if (mysqli_num_rows($resultTit)>0){
						$cheq = "checked='checked'";
					}else{
						$cheq = "";
					}
				?>
                <tr>
                  <td align="center">
                  
                  <input type="checkbox" id="MatID<?php echo $i;?>" value="<?php echo $row[Mat_ID];?>" <?php echo $cheq;?>/></td>
                  <td align="center"><?php echo $row[Mat_ID];?>
                  <input type="hidden" id="CarID<?php echo $i;?>" value="<?php echo $row[Mat_Car_ID];?>" />
                  <input type="hidden" id="PlaID<?php echo $i;?>" value="<?php echo $PlaID;?>" />                  
                  </td>
                  <td><?php echo $row[Mat_Nombre];?></td>
                  <td><?php echo $row[Car_Nombre];?></td>
                  <td><?php echo $row[Cur_Nombre];?></td>
                  <td align="center"><?php 
				  if ($row[Mat_Optativa]==1) echo "SI";else echo"NO";
				  ?></td>
                  </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
            <fieldset class="recuadro_inferior" style="height:32px"><div align="left">
<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se encontraron $i materias por carrera";?></div>
<br /><br /></fieldset>
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
	