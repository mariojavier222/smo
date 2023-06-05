<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

session_name("sesion_abierta");
// incia sessiones
session_start();

//echo "NAHUEL";
$Per_ID = $_POST['PerID'];
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($Per_ID);

$Lec_ID=23;//2023
$FDesde='2023-04-01';
$FHasta='2023-12-31';
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script src="js/jquery.printElement.js" language="javascript"></script>
<script language="javascript">
$(document).ready(function(){

	function recargarPagina(){
		$.ajax({
			cache: false,
			async: false,			
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
				$("#principal").html(data);
			}
		});//fin ajax
	}//fin function

	function limpiarDatos(){
		$("#CBeID").val(0);
		//$("#Per_ID").val(0);				
		$("#Lec_ID").val(23);
		$("#Desde").val('2023-04-01');
		$("#Hasta").val('2023-12-31');
		$("#Ben_ID").val(1);
		$("#CTi_ID").val(2);
	}
	
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		var errores=0;
		vCBeID = $("#CBeID").val();
		vPerID = $("#Per_ID").val();				
		vLecID = $("#Lec_ID").val();
		vDesde = $("#Desde").val();
		vHasta = $("#Hasta").val();
		vBenID = $("#Ben_ID").val();
		vCTiID = $("#CTi_ID").val();

		//valido
		if (vDesde.length==0) {
			jAlert("Fecha Desde incorrecta!", "Error");
			errores++;
		}
		if (vHasta.length==0) { 
			jAlert("Fecha Hasta incorrecta!", "Error");
			errores++;
		}

		if (errores==0){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpcionesBeneficio.php',
				data: {opcion: "guardarBeneficioPersona", CBeID: vCBeID, PerID: vPerID, LecID: vLecID, Desde: vDesde, Hasta: vHasta, BenID: vBenID, CTiID: vCTiID},
				success: function (data){
					jAlert(data, "Resultado de la operación");
					limpiarDatos();
					$("#divBuscador").empty();
					$("#mostrarNuevo").empty();
					/*
					$.ajax({
						type: "POST",
						cache: false,
						async: false,			
						url: 'cargarOpcionesBeneficio.php',
						data: {opcion: "listarBeneficioPersona", PerID: vPerID},
						success: function (data){
							$("#divBuscador").html(data);
							$("#divBuscador").fadeIn();					
						}
					});//fin ajax
					*/
				}
			});//fin ajax
		}
	});	

	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,18);

		$("#CBeID").val($("#CBe_ID" + i).val());
		$("#Per_ID").val($("#CBe_Per_ID" + i).val());
		$("#Lec_ID").val($("#CBe_Lec_ID" + i).val());
		$("#Desde").val($("#CBe_Desde" + i).val());
		$("#Hasta").val($("#CBe_Hasta" + i).val());
		$("#Ben_ID").val($("#CBe_Ben_ID" + i).val());
		$("#CTi_ID").val($("#CBe_CTi_ID" + i).val());
		
		//$("#mostrarNuevo").fadeIn();
		//$("#divBuscador").fadeOut();
		
	});//fin evento click//*/	 

	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,18);
		vID = $("#CBe_ID" + i).val();
		vPerID = $("#CBe_Per_ID" + i).val();

		jConfirm('Est&aacute; seguro que desea eliminar el registro '+vID+'?', 'Confirmar la eliminación', function(r){
			if (r){//eligió eliminar
				$.post("cargarOpcionesBeneficio.php", { opcion: "eliminarBeneficioPersona", ID: vID }, function(data){
					jAlert(data, 'Resultado de la operación');
					limpiarDatos();
					$("#divBuscador").empty();
					$("#mostrarNuevo").empty();
/*
					$.ajax({
						type: "POST",
						cache: false,
						async: false,			
						url: 'cargarOpcionesBeneficio.php',
						data: {opcion: "listarBeneficioPersona", PerID: vPerID},
						success: function (data){
							//alert (data);
							$("#divBuscador").html(data);
							$("#divBuscador").fadeIn();					
						}
					});//fin ajax
*/					
				});//fin post					
			}//fin if
		});//fin del confirm
		
	});//fin del eliminar

	limpiarDatos();
});

</script>

<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">

		<tr>
			<td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nuevo Beneficio</div></td>
		</tr>
		<td class="texto"><div align="right">Ciclo Lectivo</div></td>
		<td class="texto">2023</td>
			<input type="hidden" name="Lec_ID" id="Lec_ID" value="<?php echo $Lec_ID;?>" />
			<input type="hidden" name="CBeID" id="CBeID" value="0" />
			<input type="hidden" name="Per_ID" id="Per_ID" value="<?php echo $Per_ID;?>" />			
		</td>
	</tr>
	<tr>
		<td align="right" class="texto">Desde:</td>
		<td><label>
			<input name="Desde" type="date" id="Desde" size="40" value="<?php echo $FDesde;?>" />
		</label></td>
	</tr>
	<tr>
		<td align="right" class="texto">Hasta:</td>
		<td><label>
			<input name="Hasta" type="date" id="Hasta" size="40" value="<?php echo $FHasta;?>" />
		</label></td>
	</tr>
	<tr>
		<td align="right" class="texto">Beneficio</td>
		<td><?php ListarBeneficios('Ben_ID');?></td>
	</tr>
	<tr>
		<td align="right" class="texto">Tipo de cuota</td>
		<td><?php cargarListaTipoCuota('CTi_ID');?></td>
	</tr>
	<tr>
		<td colspan="2" class="texto"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar cambios" width="48" height="48" border="0" title="Guardar cambios" /><br />Guardar</button></td>
	</tr>
	<tr>
		<td colspan="2" class="texto"></td>
	</tr>

</table>

</div>

<div id="divBuscador">

	<table width="98%" border="0" align="center" class="borde_recuadro">
		<tr>
			<td><div align="center" class="titulo_noticia"> Listado de Beneficios otorgados por Ciclo Lectivo</div></td>
		</tr>
		<tr>
			<td align="center" class="texto">
				<?php
				$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				$sql = "SELECT PersonaBeneficio.*, Ben_Nombre, Lec_Nombre, CTi_Nombre 
				FROM PersonaBeneficio 
				INNER JOIN CuotaBeneficio ON (CBe_Ben_ID = Ben_ID)    
				INNER JOIN CuotaTipo ON (CBe_CTi_ID = CTi_ID)    
				INNER JOIN Lectivo ON (CBe_Lec_ID = Lec_ID)    
				WHERE CBe_Per_ID = '$Per_ID' AND 
				CBe_Activo=1 AND 
				CBe_Lec_ID=$Lec_ID";
				$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				if (mysqli_num_rows($result) > 0) {
					?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
						<thead>
							<tr class="ui-widget-header">
								<th align="center">Lectivo</th>
								<th align="center">Tipo de cuota</th>
								<th align="center">Desde</th>
								<th align="center">Hasta</th>
								<th align="center">Beneficio</th>
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
									<input name="CBe_ID<?php echo $i;?>" type="hidden" id="CBe_ID<?php echo $i;?>" value="<?php echo $row['CBe_ID']; ?>" />
									<input name="CBe_Per_ID<?php echo $i;?>" type="hidden" id="CBe_Per_ID<?php echo $i;?>" value="<?php echo $row['CBe_Per_ID']; ?>" />			
									<input name="CBe_Lec_ID<?php echo $i;?>" type="hidden" id="CBe_Lec_ID<?php echo $i;?>" value="<?php echo $row['CBe_Lec_ID']; ?>" />
									<input name="CBe_Desde<?php echo $i;?>" type="hidden" id="CBe_Desde<?php echo $i;?>" value="<?php echo $row['CBe_Desde']; ?>" />
									<input name="CBe_Hasta<?php echo $i;?>" type="hidden" id="CBe_Hasta<?php echo $i;?>" value="<?php echo $row['CBe_Hasta']; ?>" />
									<input name="CBe_Ben_ID<?php echo $i;?>" type="hidden" id="CBe_Ben_ID<?php echo $i;?>" value="<?php echo $row['CBe_Ben_ID']; ?>" />
									<input name="CBe_CTi_ID<?php echo $i;?>" type="hidden" id="CBe_CTi_ID<?php echo $i;?>" value="<?php echo $row['CBe_CTi_ID']; ?>" />
									<?php echo $row['Lec_Nombre']; ?> 
								</td>
								<td align="center"><?php echo $row['CTi_Nombre']; ?></td>
								<td align="center"><?php echo cfecha($row['CBe_Desde']); ?></td>
								<td align="center"><?php echo cfecha($row['CBe_Hasta']); ?></td>
								<td align="center"><?php echo $row['Ben_Nombre']; ?></td>
								<td align="center">
									<a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar" title="Editar" width="32" height="32" border="0" /></a>
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
                	El alumno no tiene beneficios cargados.
                	<?php
                }
                ?>

            </td>
        </tr>
    </table>

</div>
