<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
?>
<link href="js/jQuery-Visualize-master/css/basic.css" type="text/css" rel="stylesheet" />
<link href="js/jQuery-Visualize-master/css/visualize.css" type="text/css" rel="stylesheet" />
<link href="js/jQuery-Visualize-master/css/visualize-light.css" type="text/css" rel="stylesheet" />
<!--<link href="jQuery-Visualize-master/css/visualize-dark.css" type="text/css" rel="stylesheet" />-->
<script type="text/javascript" src="js/jquery-1.6.2.js"></script>
<script type="text/javascript" src="js/jQuery-Visualize-master/js/visualize.jQuery.js"></script>	

<script type="text/javascript">
$(document).ready(function(){
	
$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				//data: {fechaDesde: vFechaDesde, fechaHasta: vFechaHasta, LecID: vLecID, FacID: vFacID, TChID: vTChID},
				url: 'mostrarEstadisticaDeudoresLectivoImportes.php',
				success: function(data){ 
					$("#mostrar-resto").html(data);
				}
			});//fin ajax//*/	
	
<!--$('.tablaEstadistica table').visualize({width: '420px'});-->
//$('.tablaEstadistica table').visualize({type: 'pie', height: '300px', width: '420px'});
$('.tablaEstadistica table').visualize({type: 'bar', width: '600px', height: '400px'});
//$('.tablaEstadistica table').visualize({type: 'area', width: '420px'});
//$('.tablaEstadistica table').visualize({type: 'line', width: '420px'});

$('.tablaEstadistica table td')
		.click(function(){
			if( !$(this).is('.input') ){
				$(this).addClass('input')
					.html('<input type="text" value="'+ $(this).text() +'" />')
					.find('input').focus()
					.blur(function(){
						//remove td class, remove input
						$(this).parent().removeClass('input').html($(this).val() || 0);
						//update charts	
						$('.visualize').trigger('visualizeRefresh');
					});					
			}
		})
		.hover(function(){ $(this).addClass('hover'); },function(){ $(this).removeClass('hover'); });
})
</script>
<p>Este informe muestra el total de personas que adeudan cuotas de matricula, materiales, mensuales, etc. desde una mirada econ&oacute;mica</p>

<p>&nbsp;</p>

<div class="tablaEstadistica">
<table>
	<caption>
	Totales Deudores (<?php 
	$Lec_ID = gLectivoActual($LecActual);
	echo $LecActual;?>)
	</caption>
	
    <thead>
		<tr>
			<td></td>
			<th scope="col">Al d&iacute;a</th>
			<th scope="col">Deben 1 cuota</th>
			<th scope="col">Deben 2 cuotas</th>
			<th scope="col">Deben 3 cuotas</th>
			<th scope="col">Deben 4 cuotas</th>
			<th scope="col">5 o más </th>
            <th scope="col">Total Alumnos</th>			
		</tr>
	</thead>
	<tbody>
		<?php
	
	//$Nivel = " Cuo_Niv_ID < 4 AND ";
	$fechaHoy = date("Y-m-d");
	
	
		$sqlOpcion = "COUNT(DISTINCT Cuo_Per_ID) AS Campo";
		$sqlPersonaBaja = "INNER JOIN Persona ON (Cuo_Per_ID = Per_ID) INNER JOIN Legajo 
        ON (Leg_Per_ID = Per_ID) WHERE Per_Baja = 0 AND Leg_Baja = 0 AND";

		//$sqlOpcion = "SUM(Cuo_Importe) AS Campo";
	
    $sql = "SELECT $sqlOpcion FROM CuotaPersona INNER JOIN Persona ON (Cuo_Per_ID = Per_ID) INNER JOIN Legajo 
        ON (Leg_Per_ID = Per_ID) WHERE Cuo_Niv_ID = 1 AND Per_Baja = 0 AND Leg_Baja = 0 AND Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID NOT IN (SELECT Cuo_Per_ID FROM CuotaPersona WHERE  Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0  AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento < '$fechaHoy') ";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//$alDia = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$alDiaPrimaria = $row['Campo'];
	$sql = "SELECT $sqlOpcion FROM CuotaPersona INNER JOIN Persona ON (Cuo_Per_ID = Per_ID) INNER JOIN Legajo 
        ON (Leg_Per_ID = Per_ID) WHERE Cuo_Niv_ID = 2 AND Per_Baja = 0 AND Leg_Baja = 0 AND Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID NOT IN (SELECT Cuo_Per_ID FROM CuotaPersona WHERE  Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0  AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento < '$fechaHoy') ";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//$alDia = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$alDiaSecundaria = $row['Campo'];
	$sql = "SELECT $sqlOpcion FROM CuotaPersona $sqlPersonaBaja Cuo_Niv_ID = 3 AND Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID NOT IN (SELECT Cuo_Per_ID FROM CuotaPersona WHERE  Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0  AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento < '$fechaHoy') ";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//$alDia = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$alDiaInicial = $row['Campo'];
	
	$Nivel = "Cuo_Niv_ID = 1 AND ";
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe1Cuota = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=2";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe2Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=3";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe3Cuotas = mysqli_num_rows($result);	
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=4";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe4Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)>=5";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe5Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$totalAlumnos = $row['Campo'];
	?>
        <tr>
			<th scope="row">Alumnos Primaria</th>
			<td><?php echo $alDiaPrimaria;?></td>
			<td><?php echo $debe1Cuota;?></td>
			<td><?php echo $debe2Cuotas;?></td>
			<td><?php echo $debe3Cuotas;?></td>
			<td><?php echo $debe4Cuotas;?></td>
			<td><?php echo $debe5Cuotas;?></td>
            <td><?php echo $totalAlumnos;?></td>			
		</tr>	
     <?php
	//$Nivel = " Cuo_Niv_ID = 4 AND ";
	$fechaHoy = date("Y-m-d");
    $Nivel = "Cuo_Niv_ID = 2 AND ";
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe1Cuota = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=2";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe2Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=3";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe3Cuotas = mysqli_num_rows($result);	
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=4";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe4Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)>=5";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe5Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result); 
	$totalAlumnos = $row['Campo'];		
	?>
        <tr>
			<th scope="row">Alumnos Secundaria</th>
			<td><?php echo $alDiaSecundaria;?></td>
			<td><?php echo $debe1Cuota;?></td>
			<td><?php echo $debe2Cuotas;?></td>
			<td><?php echo $debe3Cuotas;?></td>
			<td><?php echo $debe4Cuotas;?></td>
			<td><?php echo $debe5Cuotas;?></td>	
            <td><?php echo $totalAlumnos;?></td>		
	  </tr>
      
    <?php 
	
	$Nivel = "Cuo_Niv_ID = 3 AND ";
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe1Cuota = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=2";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe2Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=3";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe3Cuotas = mysqli_num_rows($result);	
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)=4";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe4Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT DISTINCTROW Cuo_Per_ID FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' GROUP BY Cuo_Per_ID HAVING COUNT(*)>=5";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe5Cuotas = mysqli_num_rows($result);
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona $sqlPersonaBaja $Nivel Cuo_Lec_ID = $Lec_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$totalAlumnos = $row['Campo'];
	?>  
        <tr>
          <th scope="row">Alumnos Nivel Inicial</th>
          <td><?php echo $alDiaInicial;?></td>
          <td><?php echo $debe1Cuota;?></td>
          <td><?php echo $debe2Cuotas;?></td>
          <td><?php echo $debe3Cuotas;?></td>
          <td><?php echo $debe4Cuotas;?></td>
          <td><?php echo $debe5Cuotas;?></td>	
          <td><?php echo $totalAlumnos;?></td>
        </tr>	   	
	</tbody>
</table>
</div>
<div id="mostrar-resto"></div>