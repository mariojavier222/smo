<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();	

$opcion = $_POST['opcion'];
switch ($opcion) {
	
    case "mostrarListadoImprimir":
        mostrarListadoImprimir();
        break;
    case "mostrarListadoImprimir2":
        mostrarListadoImprimir2();
        break;
    case "mostrarListadoImprimirRecibo":
        mostrarListadoImprimirRecibo();
        break;
		
		
		
		
		
}

function mostrarListadoImprimir()
{
	?>
    <script type="text/javascript">
			$("#imprimirTodas22").click(function(evento){
		evento.preventDefault();
	
		$("#listadoNahuel").printElement({leaveOpen:true, printMode:'popup'						
		});
		$("#cargando").hide();
	 });//fin evento click//*/
	 
	 $('button').button();
	</script>
    <?php
	$letra = $_POST['letra'];
	$tamanio = $_POST['tamanio'];
	$Fecha2 = $_POST['Fecha'];
	//echo $Fecha."<br>";
	$Concepto = $_POST['Concepto'];
	$Ingreso = $_POST['Ingreso'];
	$Egreso = $_POST['Egreso'];
	$Caja = $_POST['Caja'];
	$Usuario = $_POST['Usuario'];
	$SaldoUsuario = $_POST['SaldoUsuario'];
	$FormaPago = $_POST['FormaPago'];
?>
<style type="text/css">
#listadoNahuel {
font-family: <?php echo $letra ?>;
font-size: <?php echo $tamanio ?>px;
font-weight: bold;
color: #000;
text-align: center;
}
</style>
<?php	
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
//echo $UsuID."<br>";
	//$UsuID=4;  
$NumCaja = cajaAbiertaUsuario($UsuID);
	//echo $CajaID."<br>";
	 $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
     
	 $sql_2 = "SELECT * FROM Caja INNER JOIN CajaCorriente ON (Caja_ID = CCC_Caja_ID) INNER JOIN Usuario ON (CCC_Usu_ID = Usu_ID) INNER JOIN FormaPago ON (CCC_For_ID = For_ID) 
 WHERE ";
 	$colocarAND = false;
	if (!empty($NumCaja)){
 		$sql_2 .=" CCC_Caja_ID = $NumCaja";
		$colocarAND = true;
	}
	
	if ($UsuID > 0){
 		if ($colocarAND) {$sql_2 .= " AND ";$colocarAND = false;}
		$sql_2 .=" Caja_Usu_ID = $UsuID";
		$colocarAND = true;
	}
	
	if (!empty($fechaDesde)){
 		if ($colocarAND) {$sql_2 .= " AND ";$colocarAND = false;}
		$sql_2 .=" Caja.Caja_Apertura >= '".cambiaf_a_mysql($fechaDesde)."' AND Caja.Caja_Apertura <='".cambiaf_a_mysql($fechaHasta)."'";
		$colocarAND = true;
	}
	$sql_2 .="  ORDER BY CCC_ID";
        $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
	
     <table width="100%" border="0" id="listadoNahuel" class="display" style="font-family:<?php echo $letra ?>;
font-size:<?php echo $tamanio ?>px;">
        <tr>	
         <th align="center" >#</th>	  
          <?php if ($Fecha2){?><th align="center">Fecha</th><?php }?>
          <?php if ($Concepto){?><th align="center">Concepto</th><?php }?>
          <?php if ($Ingreso){?><th align="center">Ingreso</th><?php }?>
          <?php if ($Egreso){?><th align="center">Egreso</th><?php }?>
          <?php if ($Caja){?><th align="center">Caja</th><?php }?>
          <?php if ($Usuario){?><th align="center">Usuario</th><?php }?>
          <?php if ($SaldoUsuario){?><th align="center">Saldo de Usuario</th><?php }?>
          <?php if ($FormaPago){?><th align="center">Forma de Pago</th><?php }?>
    </tr>      
    <?php
	  
			
			        $acum[] = array();
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$UsuID = $row[CCC_Usu_ID];
						$Saldo = ($row[CCC_Credito]-$row[CCC_Debito]);
                        $acum[$UsuID] = $acum[$UsuID] + $Saldo;
						$Saldo_Acutal = $acum[$UsuID];
						
						$valor = $row[CCC_Ret_Dinero];
						//echo $valor;	
						$clase = "";					
						if ($valor == 1){
							$clase = "retiro_dinero";										
						}else{
							$clase = ""; //"caja_abierta";
							}
												
						?>
            <tr class="<?php echo $clase; ?>" title="<?php echo $row[CCC_Detalle]; ?>-<?php echo $row[CCC_Referencia]; ?>">
              <td align="center"><?php echo $i;?></td>
             <?php if ($Fecha2){?><td align="left">
			<?php
			if($row[CCC_Fecha] != ''){
               echo cfecha($row[CCC_Fecha]);
				  }
				  else{
					  echo '--';
					  } 
			  ?>
              </td><?php } ?>
              
             <?php if ($Concepto){?> <td align="left"><?php echo $row[CCC_Concepto]; ?></td><?php }?>
              <?php if ($Ingreso){?> <td align="right">$<?php echo intval($row[CCC_Debito]); ?></td><?php }?>
               <?php if ($Egreso){?> <td align="right">$<?php echo intval($row[CCC_Credito]); ?></td><?php }?>
             <?php if ($Caja){?> <td align="right">$<?php echo intval($Saldo_Acutal); ?></td><?php }?>
              <?php if ($Usuario){?> <td align="left"><?php echo $row[Usu_Nombre]; ?></td><?php }?>
              <?php if ($SaldoUsuario){?> <td align="right">$<?php echo intval($Saldo_Acutal); ?></td><?php }?>
              <?php if ($FormaPago){?> <td align="left"><?php echo $row[For_Nombre]; ?></td><?php }?>
              </tr>
            
            <?php
                    }//fin while
						?>
                      </table>  
                        
	<?php
		}//if
		
		?>
        <button id="imprimirTodas22">Imprimir</button>
        <?php
}

function mostrarListadoImprimir2()
{
?>
<script language="javascript">
$(document).ready(function(){
	
	$("#barraMostrar").click(function(evento){
	

	Fecha = $("#Fecha:checked").length;
	Recibo = $("#Recibo:checked").length;
	Apellido = $("#Apellido:checked").length;
	Monto = $("#Monto:checked").length;
	Concepto = $("#Concepto:checked").length;
	letra= $("#letra").val();
	tamanio= $("#tamanio").val();
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	error: function (XMLHttpRequest, textStatus){
	alert(textStatus);},
	data: {opcion:"mostrarListadoImprimirRecibo",Fecha: Fecha, Recibo: Recibo, Apellido: Apellido, Monto: Monto, Concepto: Concepto,letra:letra,tamanio:tamanio},
	url: 'cargarOpcionesImprimir.php',
	success: function(data){ 
	$("#mostrarImprimir2").html(data);
	}
	});//fin ajax//*/
	
	});	
	
	$("#barraMostrar").click();
	$('button').button();
})
</script>

<table width="95%" border="0" align="center" class="borde_recuadro" style="font-size:16px">
       <tr>
         <td valign="top" >
           <input name="Recibo" type="checkbox"  id="Recibo" checked="checked" />
           <label for="Recibo">Recibo</label>
          </td>
          <td>
           <input name="Fecha" type="checkbox"  id="Fecha" checked="checked" />
           <label for="Fecha">Fecha Y Hora</label>
</td>
<td>
           <input type="checkbox" name="Apellido" id="Apellido" checked="checked" />
           <label for="Ingreso">Apellido Y Nombre</label>
</td>
<td>
           <input name="Monto" type="checkbox" id="Monto" checked="checked" />
           <label for="Monto">Monto</label>
</td>
<td>
           <input name="Concepto" type="checkbox" id="Concepto" checked="checked" />
           <label for="Concepto">Concepto</label>
</td>
</tr>
<tr>
<td colspan="2"><strong>Estilo de Letra:</strong><select name="letra" id="letra">
<option value="Arial">Arial</option>
<option value="Times New Roman">Times New Roman</option>
<option value="Verdana">Verdana</option>
</select></td>
<td colspan="2"><strong>Tamaño de Letra:</strong><select name="tamanio" id="tamanio">
<option value="6">6px</option>
<option value="8">8px</option>
<option value="10" selected="selected">10px</option>
<option value="12">12px</option>
<option value="14">14px</option>
<option value="16">16px</option>
</select></td>
</tr>
<tr>
        <td colspan="8" align="center" class="texto"><button class="botones" id="barraMostrar">
        Mostrar Datos Imprimir</button>      
        </td>
      </tr>
  </table>
  <br>
<div id="mostrarImprimir2"></div>
<?php
}
function mostrarListadoImprimirRecibo()
{
		?>
    <script type="text/javascript">
			$("#imprimirTodas22").click(function(evento){
		evento.preventDefault();
	
		$("#listadoNahuel").printElement({leaveOpen:true, printMode:'popup'						
		});
		$("#cargando").hide();
	 });//fin evento click//*/
	 
	 $('button').button();
	</script>
    <?php
	$Fecha2 = $_POST['Fecha'];
	$Recibo = $_POST['Recibo'];
	$Apellido = $_POST['Apellido'];
	$Monto = $_POST['Monto'];
	$Concepto = $_POST['Concepto'];
	$letra = $_POST['letra'];
	$tamanio = $_POST['tamanio'];
	
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
		
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
$NumCaja = cajaAbiertaUsuario($UsuID); 
		//echo $NumCaja;
		$importeTotal = 0;
	 $sql_2 = "SELECT * FROM
    FacturaDetalle
    INNER JOIN Factura 
        ON (FDe_Fac_ID = Fac_ID)
    INNER JOIN CuotaPago 
        ON (FDe_Fac_ID = CuP_Fac_ID) AND (FDe_Item = CuP_FDe_Item)
    INNER JOIN Caja 
        ON (CuP_Caja_ID = Caja_ID)
    INNER JOIN Persona 
        ON (Per_ID = CuP_Per_ID) WHERE Caja_ID=$NumCaja GROUP BY Fac_ID;";
        $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" id="listadoNahuel" style="font-family:<?php echo $letra ?>;
font-size:<?php echo $tamanio ?>px;">
          <thead>
            <tr class="gradeA" id="fila" style="height:30px;">
           <th>#</th>
            <?php if ($Recibo){?> <th>Recibo</th> <?php } ?>
           <?php if ($Fecha2){?> <th>Fecha y Hora</th><?php } ?>
           <?php if ($Apellido){?> <th>Apellido y Nombre</th><?php } ?>
          <?php if ($Monto){?>  <th>Monto</th><?php } ?>
          <?php if ($Concepto){?>  <th>Concepto</th><?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
			
			     $i=0;
                    while ($row = mysqli_fetch_array($result)) {

$i++;
?>
            <tr style="height:30px;" <?php echo $clase; echo " $title";?>>
           <td><?php echo $i ?></td>
           <?php if ($Recibo){?> <td><?php echo $row[Fac_Sucursal]."-".$row[Fac_Numero];if ($row[Fac_Anulada]==1) echo "-ANU"; ?></td><?php } ?>
          <?php if ($Fecha2){?>  <td><?php echo $row[Fac_Fecha]." ".$row[Fac_Hora] ?></td><?php } ?>
           <?php if ($Apellido){?> <td><?php echo $row[Per_Apellido]." ".$row[Per_Nombre] ?></td><?php } ?>
           <?php if ($Monto){?> <td align="right"><?php echo "$".$row[Fac_ImporteTotal] ?></td><?php } ?>
         <?php if ($Concepto){?>   <td align="left"> 
		   <?php 
		   if ($row[Fac_Anulada]==0) $importeTotal += $row[Fac_ImporteTotal];
		   $Fac_ID=$row[Fac_ID];
		    $sql_3 = "SELECT 	*
	 
	FROM 
	FacturaDetalle WHERE FDe_Fac_ID=$Fac_ID;";
        $result2 = consulta_mysql_2022($sql_3,basename(__FILE__),__LINE__);
		 while ($row2 = mysqli_fetch_array($result2)) {   
		   
		   echo $row2[FDe_Detalle]."<br>";
		   
		 }?></td><?php } ?>
              </tr>
            
            <?php
                    }//fin while
                    ?>
          </tbody>
          <?php
            } else {
                ?>
          No existen datos cargados.
  <?php
            }
            ?>     
      </table>
      <button id="imprimirTodas22">Imprimir</button>
      <?php
}
