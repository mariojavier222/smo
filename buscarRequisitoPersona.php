<?php
require_once("conexion.php");
require_once("funciones_generales.php");
//sleep(3);
$NivID = $_POST['NivID'];
$LecID = $_POST['LecID'];
$PerID = $_POST['PerID'];



$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql = "SELECT * FROM Requisito
    INNER JOIN Colegio_Nivel 
        ON (Requisito.Req_Niv_ID = Colegio_Nivel.Niv_ID) WHERE Niv_ID = '$NivID'";
//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);


if ($total>0 && !empty($PerID)){	
	?>
	
<script language="javascript">
$(document).ready(function(){

	
	
 	
});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />

<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado">	
 <table width="100%" border="0">
    <tr>
      <td align="center" class="fila_titulo"><div align="left">Definitivo</div></td>
      <td class="fila_titulo"><div align="left">Requisito</div></td>
      <td align="center" class="fila_titulo">Constancia</td>
      <td align="center" class="fila_titulo">Fecha presentaci&oacute;n</td>
    </tr>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		$sql = "SELECT * FROM RequisitoPresentado WHERE Pre_Niv_ID = '$NivID' AND Pre_Per_ID = '$PerID' AND Pre_Req_ID = '$row[Req_ID]' AND Pre_Lec_ID = $LecID";

		$result_pre = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$total_pre = mysqli_num_rows($result_pre);
		$Const = 0;
		$Defin = 0;
		$Fecha = "---";
		$valorConst = "";
		$valorDefin = "";
		if ($total_pre > 0){
			$row_pre = mysqli_fetch_array($result_pre);
			$Const = $row_pre[Pre_Constancia];
			$Defin = $row_pre[Pre_Presento];
			$Fecha = cfecha($row_pre[Pre_Fecha]);
		}
		if ($Const >0) $valorConst = "checked='checked'";
		if ($Defin >0) $valorDefin = "checked='checked'";
		

		
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>">
      <td align="center"><input type="checkbox" id="Defin<?php echo $i;?>" <?php echo $valorDefin;?> value="<?php echo $row[Req_ID];?>"/></td>
      <td><?php echo $row[Req_Nombre];?></td>
      
      <td align="center">
      	<?php if ($row[Req_Constancia] > 0){?>
      	<input type="checkbox" id="Const<?php echo $i;?>" <?php echo $valorConst;?> value="<?php echo $row[Req_ID];?>"/>
        <?php }else{
			echo "---";
			}?>
        </td>
      <td align="center"><?php echo $Fecha;?></td>
    </tr>
		  <?php		  
	}//fin del while
	?>  
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior">
  <?php echo "Se econtraron $total requisitos de ingreso";?>
</fieldset>	
<?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron requisitos de ingreso asociados al nivel elegido.</span>
<?php
}
?>
