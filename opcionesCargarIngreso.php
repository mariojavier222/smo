<?php

header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

	//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Anio = $_POST['Anio'];
	
	//$sql = "SELECT * FROM CuotaTipo ORDER BY CTi_ID";
	$sql = "SELECT CTi_ID, CTi_Nombre, SUM(CuP_Importe) AS Importe FROM CuotaTipo  INNER JOIN CuotaPago 
        ON (CTi_ID = CuP_CTi_ID) INNER JOIN Caja 
        ON (CuP_Caja_ID = Caja_ID) WHERE CuP_Anulada = 0 AND YEAR(CuP_Fecha)=$Anio GROUP BY CTi_ID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);//echo $sql;//exit;
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	

	 $("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
	 
	 $("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", <?php echo $IDTabla;?>: <?php echo $IDTabla;?> }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	
	 
	 $("a[id^='link']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(4,100);
		
		/*<?php echo $IDTabla;?> = $("#<?php echo $IDTabla;?>" + i).val();
		
		vNombre = $("#Nombre" + i).text();*/
		
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {i: i},
				url: 'mostrarRecibosDetalle.php',
				success: function(data){ 
					 mostrarAlertaGrande(data, 'Detalle de los recibos');
					 //jAlert(data, 'Detalle de los recibos');
					
				}
			});//fin ajax//*/ 
	
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Rec_ID, Anio){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Rec_ID: Rec_ID, Anio: Anio}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Rec_ID").val(obj.Rec_ID);
				$("#Rec_Cue_ID").val(obj.Rec_Cue_ID);
				$("#Rec_FechaCompra").val(obj.Rec_FechaCompra);
				$("#Rec_Usu_ID").val(obj.Rec_Usu_ID);
				$("#Rec_Fecha").val(obj.Rec_Fecha);
				$("#Rec_Hora").val(obj.Rec_Hora);
				$("#Rec_Importe").val(obj.Rec_Importe);
			}//fin if
		});		
	}//fin function
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="listadoTabla">
               <thead>
                <tr class="fila_titulo">
                  
                  
                  <th align="center">Tipo de Cuota</th>
                  
                  <th align="center">Importe</th>
                  <?php
				  global $gMes;
                  $sql = "SELECT MONTH(CuP_Fecha) AS Mes, SUM(CuP_Importe) AS Total FROM Caja
    INNER JOIN CuotaPago ON (Caja.Caja_ID = CuotaPago.CuP_Caja_ID) WHERE CuP_Anulada = 0 AND YEAR(CuP_Fecha)=$Anio GROUP BY MONTH(CuP_Fecha) ORDER BY Mes";
				  
				  $result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    			  if (mysqli_num_rows($result2) > 0){
					  $iMes = 0;
				  	while ($row2 = mysqli_fetch_array($result2)){
						$iMes++;
						$Meses[$iMes] = $row2['Mes'];
						echo '<th align="center">'.$gMes[$row2['Mes']].'</th>';
					}//fin while
				  }//FIN IF
				  ?>
                  
                  
                </tr>
  </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">
                <td><?php echo $row[CTi_Nombre];?></td>                 
                 <td align="center"><?php echo number_format($row['Importe'],2,',','.');?></td>                 
                 <?php
                 	for ($k = 1; $k <= $iMes; $k++){
						$sql = "SELECT SUM(CuP_Importe) AS Importe FROM Caja
    INNER JOIN CuotaPago ON (Caja.Caja_ID = CuotaPago.CuP_Caja_ID) WHERE CuP_Anulada = 0 AND CuP_CTi_ID = $row[CTi_ID] AND MONTH(CuP_Fecha) = $Meses[$k] AND YEAR(CuP_Fecha)=$Anio"; //echo $sql;
	
				 		$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
						if (mysqli_num_rows($result2) > 0){
							$row2 = mysqli_fetch_array($result2);
							if ($row2['Importe']>0){
								$link = $Meses[$k]."-".$row['CTi_ID'];
								//echo '<td align="right"><a href="#" id="link'.$link.'">'.number_format($row2['Importe'],2,',','.').'</a></td>';
								echo '<td align="right">'.number_format($row2['Importe'],2,',','.').'</td>';
							}else{
								echo '<td align="right">----</td>';
							}
						}else{
							echo '<td align="right">----</td>';
						}
					}//fin for
				 ?>
                 
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
  </table>
 
<?php  //$sql = "SELECT SUM(Rec_Importe)AS Importe FROM Egreso_Recibo $where ";
	 $sql = "SELECT SUM(CuP_Importe) AS Importe FROM Caja
    INNER JOIN CuotaPago ON (Caja.Caja_ID = CuotaPago.CuP_Caja_ID) WHERE CuP_Anulada = 0 AND YEAR(CuP_Fecha)=$Anio";
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result2) > 0){
		$row2 = mysqli_fetch_array($result2); 
		echo "Total: $".number_format($row2['Importe'],2,',','.');
	}//fin if
?>
 <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a>
  <form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if

?>