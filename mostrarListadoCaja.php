<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
    $(document).ready(function(){
		
		$(".botondiego").button();
		
			$("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		vLectivo = $("#LecID option:selected").text();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Inscriptos para el Ciclo Lectivo ' + vLectivo,overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css', media:'print'}]									
		});
		$("#cargando").hide();
	 });//fin evento click//*/
	 
	 $("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
				
	//Filtro la caja por Fecha para ver individualmente
	
	$("a[id^='campos']").click(function(evento){
            evento.preventDefault();			
            var i = this.id.substr(6,15);
            //alert(i);return;
            $("#tr_campos" + i).show();          
        });
			
		
	$("button[id^='btn_vista_previa_cerrar']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(23,10);
            $("#tr_campos"+ i).hide();
        });
		
		$("table[id^='tablaDetalle']").dataTable( {
            "bPaginate": true,
            //"aaSorting": [[ 1, "asc" ]],
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true } );//*/	
	
    				
});//fin de la funcion ready


</script>
<?php
$fechaDesde = $_POST['fechaDesde'];
$fechaHasta = $_POST['fechaHasta'];

function buscarRecaudacion($id){
	$sql = "SELECT SUM(CuP_Importe) AS total FROM CuotaPago WHERE CuP_Caja_ID = $id";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);
	$total = $row[total];
	return $total; 
	}
	
function buscarFormaPago($Lectivo,$Nivel,$Tipo,$Persona,$Alter,$Numero,$Orden){
	$sql = "SELECT *
FROM
    CuotaPago
    INNER JOIN CuotaPagoDetalle 
        ON (CuotaPago.CuP_Alt_ID = CuotaPagoDetalle.CPD_Alt_ID) AND (CuotaPago.CuP_Lec_ID = CuotaPagoDetalle.CPD_Lec_ID) AND (CuotaPago.CuP_Niv_ID = CuotaPagoDetalle.CPD_Niv_ID) AND (CuotaPago.CuP_CTi_ID = CuotaPagoDetalle.CPD_CTi_ID) AND (CuotaPago.CuP_Per_ID = CuotaPagoDetalle.CPD_Per_ID) AND (CuotaPago.CuP_Numero = CuotaPagoDetalle.CPD_Numero) AND (CuotaPago.CuP_Orden = CuotaPagoDetalle.CPD_Orden)
    INNER JOIN FormaPagoDetalle 
        ON (CuotaPagoDetalle.CPD_FDe_ID = FormaPagoDetalle.FDe_ID) AND (CuotaPagoDetalle.CPD_For_ID = FormaPagoDetalle.FDe_For_ID)
		INNER JOIN FormaPago 
        ON (FormaPagoDetalle.FDe_For_ID = FormaPago.For_ID)
        WHERE CPD_Lec_ID = $Lectivo AND CPD_Niv_ID = $Nivel  AND CPD_CTi_ID = $Tipo AND CPD_Per_ID = $Persona AND CPD_Alt_ID = $Alter AND CuP_Numero = $Numero AND CuP_Orden = $Orden";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
	while($row = mysqli_fetch_array($result)){
		$i++;
		$forid = -1;
		if($forid != $row[For_ID]){
			echo "<strong>$row[For_Nombre]";
            echo "<br />";
			$forid = $row[For_ID];
			}
		echo "$row[FDe_Nombre]: $row[CPD_Valor]"
;
echo "<br />"
;
		}
	}	
	
?>
<div id="listado" >	
<br />
<br />
		<?php
		$usuario = $_SESSION['sesion_UsuID'];
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);        
        
		$opcion = $_POST['opcion'];
		
		//CONSULTA PARA MOSTRAR LISTADO POR FECHA Y USUARIO
		if ($opcion=="ListarPorFecha"){        
        
		  $Usuario = $_POST['Usuario'];
		  if($Usuario != -1){
		  $sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) WHERE Usuario.Usu_ID=$Usuario AND Caja.Caja_Apertura >= '".cambiaf_a_mysql($fechaDesde)."' AND Caja.Caja_Apertura <='".cambiaf_a_mysql($fechaHasta)."' ORDER BY Caja_ID";
		  $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
		  }
		  else{
			  $sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) WHERE 
			   Caja.Caja_Apertura >= '".cambiaf_a_mysql($fechaDesde)."' AND Caja.Caja_Apertura <='".cambiaf_a_mysql($fechaHasta)."' ORDER BY Caja_ID";
			   $result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
			   
			  }//fin if condicion usuario
			//echo $sql_2;
		}//fin if mostrar
		
		
		
		//CONSULTA PARA MOSTRAR LISTADO POR CAJA Y USUARIO
		
		else if($opcion=="ListarPorCaja"){        
		
		$Caja = $_POST['numeroCaja'];
		$Usuario = $_POST['Usuario'];
        
		if($Usuario != -1){
		$sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) WHERE Usuario.Usu_ID=$Usuario AND Caja.Caja_ID = $Caja ORDER BY Caja_ID";
		$result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
		}
		else{
			$sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) WHERE Caja.Caja_ID = $Caja ORDER BY Caja_ID";
			$result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
			}//fin if condicion usuario
			}//fin if mostrar1
			
		//CONSULTA PARA MOSTRAR LISTADO POR CAJA Y USUARIO PARA CAJA CORRIENTE
		
		else if($opcion=="ListarParaCajaCorriente"){        
		
		$Caja = $_POST['numeroCaja'];
		$Usuario = $_POST['Usuario'];
        
		if($Usuario != -1){
		$sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) WHERE Usuario.Usu_ID=$Usuario AND Caja.Caja_ID = $Caja ORDER BY Caja_ID";
		$result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
		}
		else{
			$sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) WHERE Caja.Caja_ID = $Caja ORDER BY Caja_ID";
			$result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
			}//fin if condicion usuario
			}//fin if mostrar1	
			
		//CONSULTA POR USUARIO
		else if($opcion=="ListarPorUsuario"){     
		$Usuario = $_POST['Usuario'];
		if($Usuario != -1){
		$sql_2 = "SELECT * FROM Caja INNER JOIN Usuario ON (Caja.Caja_Usu_ID = Usuario.Usu_ID) WHERE Usuario.Usu_ID=$Usuario ORDER BY Caja_ID";
		$result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);;
		}
		else{
			$sql_2 = "SELECT * FROM Caja ORDER BY Caja_ID";
			$result = consulta_mysql_2022($sql_2,basename(__FILE__),__LINE__);
			}//fin if condicion usuario
			}//fin if mostrar1      

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Numero</th>
              <th align="center" class="fila_titulo">Fecha Apertura</th>
              <th align="center" class="fila_titulo">Fecha Cierre</th>
              <th align="center" class="fila_titulo">Usuario</th>
              <!--<th align="center" class="fila_titulo">Usuario Cierre</th>-->
              <th align="center" class="fila_titulo">Total</th>
              <th align="center" class="fila_titulo">Detalle</th>
            </tr>
          </thead>
          <tbody>
            <?php
			$acum = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$id = $row[Caja_ID];
						$valor = $row[Caja_Cierre];						
						if ($valor == ''){
				$clase = "caja_abierta";										
			}else{
				$clase = "caja_cerrada";
				}
                        ?>
            <tr class ="<?php echo $clase?>">
              <td align="center"><?php echo $row[Caja_ID]; ?></td>
              <td align="center"><?php echo cfecha(substr($row[Caja_Apertura],0,10))." (".substr($row[Caja_Apertura],11,5)." hs)"; ?></td>
              <?php
              if($row[Caja_Cierre]!= ''){
				  ?>
                  <td align="center"><?php echo cfecha(substr($row[Caja_Cierre],0,10))." (".substr($row[Caja_Cierre],11,5)." hs)";; ?></td>
                  <?php
				  }else{
				  ?>
                  <td align="center">Caja Abierta</td>
                  <?php
				  }
			  ?>
              
              <td align="left"><?php echo $row[Usu_Persona]; ?></td>
              <!--<td align="left"><?php echo $row[Usu_Persona]; ?></td>-->
              <td align="right">$<?php 
			  $totalCaja = buscarRecaudacion($id);
			  $acum = $acum +$totalCaja;
			  //echo "$totalCaja/$acum"; 
			  echo $totalCaja;
			  ?></td>
              <td align="center">
              <a id="campos<?php echo $i; ?>"> <img  style ="margin-left: 20px; margin-bottom: 7px;" src="imagenes/go-jump.png" alt="Recaudacion" title="Recaudacion de Cuotas" width="22" height="22" border="0" /> </a></td>
            </tr>
            <tr id="tr_campos<?php echo $i; ?>" style="display:none;">
              <td colspan="7"><div id="div_campos<?php echo $i; ?>">
              <?php
              $sql1 = "SELECT *
FROM
    Caja
    INNER JOIN CuotaPago 
        ON (Caja.Caja_ID = CuotaPago.CuP_Caja_ID)
    INNER JOIN CuotaPersona 
        ON (CuotaPago.CuP_Numero = CuotaPersona.Cuo_Numero) AND(CuotaPago.CuP_Per_ID = CuotaPersona.Cuo_Per_ID) AND (CuotaPago.CuP_Niv_ID = CuotaPersona.Cuo_Niv_ID) AND (CuotaPago.CuP_Lec_ID = CuotaPersona.Cuo_Lec_ID) AND (CuotaPago.CuP_CTi_ID = CuotaPersona.Cuo_CTi_ID) AND (CuotaPago.CuP_Alt_ID = CuotaPersona.Cuo_Alt_ID)
    INNER JOIN Persona 
        ON (CuotaPersona.Cuo_Per_ID = Persona.Per_ID)
    INNER JOIN CuotaTipo 
        ON (CuotaPersona.Cuo_CTi_ID = CuotaTipo.CTi_ID)
	INNER JOIN CuotaAlternativa
        ON (CuotaPersona.Cuo_Alt_ID = CuotaAlternativa.Alt_ID)
    INNER JOIN Colegio_Nivel 
        ON (CuotaPersona.Cuo_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Lectivo 
        ON (CuotaPersona.Cuo_Lec_ID = Lectivo.Lec_ID) 
		WHERE Caja_ID = $row[Caja_ID] 
		ORDER BY CuotaPago.CuP_Fecha ";
				  $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
				  if(mysqli_num_rows($result1) > 0){			  
			  ?>
                            
                <fieldset class="recuadro_inferior">
  Total a pagar: 
  $<?php echo buscarRecaudacion($id); ?>
  <button id="btn_vista_previa_cerrar<?php echo $i ?>" class="botondiego" style="margin-top: -6px;">Cerrar</button>
</fieldset>
                <table width="100%" border="0" id="tablaDetalle<?php echo $i; ?>">
          <thead>
            <tr id="fila">
              <th align="center" class="fila_titulo1">Importe</th>
              <th align="center" class="fila_titulo1">Lectivo</th>
              <th align="center" class="fila_titulo1">Tipo de Cuota</th>
              <th align="center" class="fila_titulo1">Nivel</th>
              <th align="center" class="fila_titulo1">Fecha/Hora</th>
              <th align="center" class="fila_titulo1">Persona</th>
              <th align="center" class="fila_titulo1">Forma</th>
            </tr>
          </thead>
          <tbody>
                  <?php				  
                    while ($row1 = mysqli_fetch_array($result1)) {
                        $j++;						
                        ?>
                    <tr style="background-color: gainsboro;">
              <td align="right">$<?php echo intval($row1[CuP_Importe]); ?></td>
              <td align="center"><?php echo $row1[Lec_Nombre]; ?></td>
              <td align="center"><?php echo $row1[CTi_Nombre]; ?></td>
              <td align="center"><?php echo $row1[Niv_Nombre]; ?></td>
              <td align="center"><?php echo $row1[CuP_Fecha]; ?>/<?php echo $row1[CuP_Hora]; ?></td>
              <td align="left"><?php echo $row1[Per_Apellido]; ?>,<?php echo $row1[Per_Nombre]; ?></td>
              <?php
              
			  $Lectivo = $row1[Lec_ID]; 
			  $Nivel = $row1[Niv_ID];
			  $Tipo = $row1[CTi_ID];
			  $Persona = $row1[Per_ID];
              $Alter = $row1[Alt_ID];
			  $Numero = $row1[CuP_Numero];
			  $Orden = $row1[CuP_Orden];
			  ?>
              <td align="left">Se pago con:<?php echo buscarFormaPago($Lectivo,$Nivel,$Tipo,$Persona,$Alter,$Numero,$Orden);?>
              </td> 
              
                    <?php
                    }//fin while Recaudacion
                    ?> 
                  </tbody>
                </table>
                <?php
					} else {
						?>
						<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Hasta el momento no se registran cuotas pagas.</span><button id="btn_vista_previa_cerrar<?php echo $i ?>" class="botondiego" style="margin-top: 2px;">Cerrar</button></div>
						<?php
                        }
                ?>                
              </td>
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
  <tfoot>
    <tr>
      <th colspan="12" class="fila_titulo"></th>
    </tr>
  </tfoot>
      </table>                   
    </fieldset>
    <div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> Total: <?php echo $acum;?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>
</div> 
<p><br />
    <br />
</p>
<div id="mostrar"></div>
<p>&nbsp;</p>
