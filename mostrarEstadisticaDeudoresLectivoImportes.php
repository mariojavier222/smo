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
<!--$('.tablaEstadistica table').visualize({width: '420px'});-->
//$('.tablaEstadistica table').visualize({type: 'pie', height: '300px', width: '420px'});
$('#tablaImporte .tablaEstadistica table').visualize({type: 'bar', width: '600px', height: '400px'});
//$('.tablaEstadistica table').visualize({type: 'bar', width: '300px'});
//$('.tablaEstadistica table').visualize({type: 'area', width: '420px'});
//$('.tablaEstadistica table').visualize({type: 'line', width: '420px'});

$('#tablaImporte .tablaEstadistica table td')
		.click(function(){
			if( !$(this).is('.input') ){
				$(this).addClass('input')
					.html('<input type="text" value="'+ $(this).text() +'" />')
					.find('input').focus()
					.blur(function(){
						//remove td class, remove input
						$(this).parent().removeClass('input').html($(this).val() || 0);
						//update charts	
						$('#tablaImporte .visualize').trigger('visualizeRefresh');
					});					
			}
		})
		.hover(function(){ $(this).addClass('hover'); },function(){ $(this).removeClass('hover'); });
})
</script>
<p>Este informe muestra el total de personas que adeudan cuotas de matricula, materiales, mensuales, etc. desde una mirada econ&oacute;mica</p>


<p>&nbsp;</p>

<div class="tablaEstadistica" id="tablaImporte">
<table>
	<caption>
	Totales Deudores (<?php 
	$Lec_ID = gLectivoActual($LecActual);
	echo $LecActual;?>)
	</caption>
	
    <thead>
		<tr>
			<td></td>
			
			<th scope="col">Deben 1 cuota</th>
			<th scope="col">Deben 2 o m&aacute;s</th>
            <th scope="col">Total Deuda</th>			
		</tr>
	</thead>
	<tbody>
		<?php
	
	$Nivel = " Cuo_Niv_ID < 4 AND ";
	$fechaHoy = date("Y-m-d");
	
	
		//$sqlOpcion = "COUNT(DISTINCT Cuo_Per_ID) AS Campo";

		$sqlOpcion = "SUM(Cuo_Importe) AS Campo";
	
    $sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID NOT IN (SELECT Cuo_Per_ID FROM CuotaPersona WHERE  Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0  AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy') ";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//$alDia = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$alDia = intval($row[Campo]);
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado = 0 AND Cuo_1er_Vencimiento < '$fechaHoy'  GROUP BY Cuo_Per_ID HAVING COUNT(*)=1";
	//echo "<br />$sql<br />";exit;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		//$debe1Cuota = mysqli_num_rows($result);
		$debe1Cuota += intval($row[Campo]);
	}
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy'  GROUP BY Cuo_Per_ID HAVING COUNT(*)>1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row = mysqli_fetch_array($result)){
		//$debe2Cuotas = mysqli_num_rows($result);	
		$debe2Cuotas += intval($row[Campo]);
	}
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado =0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' ";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	//$totalAlumnos = mysqli_num_rows($result);	
	$totalAlumnos = intval($row[Campo]);
	?>
        <tr>
			<th scope="row">Alumnos Colegio</th>
			<td><?php echo $debe1Cuota;?></td>
			<td><?php echo $debe2Cuotas;?></td>
            <td><?php echo $totalAlumnos;?></td>			
		</tr>	
     <?php
	$Nivel = " Cuo_Niv_ID = 4 AND ";
	$fechaHoy = date("Y-m-d");
    $sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Per_ID NOT IN (SELECT Cuo_Per_ID FROM CuotaPersona WHERE Cuo_Alt_ID = 1 AND Cuo_Pagado =0     AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy') ";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$alDia = $row[Campo];
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado =0     AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy'  GROUP BY Cuo_Per_ID HAVING COUNT(*)=1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe1Cuota = 0;
	while ($row = mysqli_fetch_array($result)){
		//$debe1Cuota = mysqli_num_rows($result);
		$debe1Cuota += intval($row[Campo]);
	}
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy'  GROUP BY Cuo_Per_ID HAVING COUNT(*)>1";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$debe2Cuotas = 0;
	while ($row = mysqli_fetch_array($result)){
		//$debe2Cuotas = mysqli_num_rows($result);	
		$debe2Cuotas += intval($row[Campo]);
	}	
	
	$sql = "SELECT $sqlOpcion FROM CuotaPersona WHERE $Nivel Cuo_Lec_ID = $Lec_ID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado =0     AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '$fechaHoy' ";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	//$totalAlumnos = mysqli_num_rows($result);	
	$totalAlumnos = intval($row[Campo]);		
	?>
        <tr>
			<th scope="row">Alumnos Instituto</th>
			<td><?php echo $debe1Cuota;?></td>
			<td><?php echo $debe2Cuotas;?></td>	
            <td><?php echo $totalAlumnos;?></td>		
	  </tr>	   	
	</tbody>
</table>
</div>