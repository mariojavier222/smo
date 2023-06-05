<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");
   
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
    $(document).ready(function(){
	
	$("button[id^='btnguardar']").click(function(evento){
            evento.preventDefault();
            i = this.id.substr(10,10);
            vimporte = $("#CRe_Importe"+ i).val();
            vforma = $("#forma"+ i).val();
			vnrocaja = $("#nrocaja").val();                 
            $.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarOpciones.php',
                data: {opcion: "guardarRendicionParcial", Importe: vimporte, Forma: vforma, Nrocaja: vnrocaja},
                success: function (data){
                    mostrarAlerta1(data, "Resultado de la operaci&oacute;n");
                    //                                $("#cargando").hide();
						
                }
            });//fin ajax
        });
		
		$("#barraVerCierre").click(function(evento){
		vNumeroCaja = $("#numerocaja").val(); 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {numerocaja: vNumeroCaja},
				url: 'cargarVerificarRendidaParcial.php',
				success: function(data){ 
					$("#mostrar").html(data);
				}
			});//fin ajax//*/ 
		});//fin click
		
		$("#btnRendirCaja").click(function(evento){
		vNumeroCaja = <?php echo $_POST['numerocaja'];?>; 
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "guardarCajaRendicion", numerocaja: vNumeroCaja},
				url: 'cargarOpciones.php',
				success: function(data){ 
					jAlert(data, "Resultado");
					$("#btnRendirCaja").hide();
				}
			});//fin ajax//*/ 
		});//fin click
	

	
	$(".botones").button();	
});//fin de la funcion ready


</script>


<link href="css/general.css" rel="stylesheet" type="text/css" />
<div id="listado" class="page-break">    
        <div align="center" class="titulo_noticia">Cierre total de Caja de Usuario</div>	
		<?php
		$nrocaja = $_POST['numerocaja'];
		$UsuID = $_SESSION['sesion_UsuID'];
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	    $fecha = date("Y-m-d H:i:s");
		//echo $fecha;return;    	
	   obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	   $sql_importe = "SELECT SUM(CCC_Credito - CCC_Debito) AS importe FROM CajaCorriente WHERE CCC_Caja_ID = $nrocaja";
	   $result_importe = consulta_mysql_2022($sql_importe,basename(__FILE__),__LINE__);
	   $row_importe = mysqli_fetch_array($result_importe);
	   //echo $row_importe[importe];return;
	   $importe = $row_importe[importe];
	   if (empty($importe)) $importe=0;
	   
	   
		
		/*$sql = " SELECT For_ID, For_Nombre, SUM(CCC_Credito) FROM
    CajaCorriente
	 INNER JOIN Caja 
        ON (CCC_Caja_ID = Caja_ID)
    INNER JOIN Usuario 
        ON (Caja_Usu_ID = Usu_ID)
    INNER JOIN FormaPago 
        ON (CCC_For_ID = FormaPago.For_ID)   
        WHERE CCC_Caja_ID =$nrocaja GROUP BY For_ID";
		//echo $sql;*/
        $sql = "SELECT For_ID,For_Nombre, SUM(CCC_Credito) - SUM(CCC_Debito)  AS total
FROM CajaCorriente INNER JOIN FormaPago ON (CajaCorriente.CCC_For_ID = FormaPago.For_ID) WHERE CCC_Caja_ID = $nrocaja GROUP BY For_ID";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
     <table width="60%" border="0" id="tablarenpar" class="display texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Forma de Pago</th>
              <th align="center" class="fila_titulo">Importe en Caja</th>
              <th align="center" class="fila_titulo">Acción</th>              
            </tr>
          </thead>
          <tbody>
            <?php
			            while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        $SUM += $row[total];
                        ?>
                    <tr style="background-color: gainsboro;">
                    <input name="nrocaja" type="hidden"  id="nrocaja"  value="<?php echo $nrocaja;?>">
                      <td align="left"><?php echo $row[For_Nombre]; ?><input name="forma" type="hidden"  id="forma<?php echo $i;?>"  value="<?php echo $row[For_ID];?>"></td>
                      <td align="right">$<?php echo number_format($row[total],0,",","."); ?></td>
                      <td align="center"></td>        
                    </tr>	
            <?php
                    }//fin while
                    ?>
                    <tr class="fila">                    
                      <td align="left"><strong>TOTALES</strong></td>
                      <td align="right"><strong>$<?php echo number_format($SUM,0,",","."); ?></strong></td>
                      <td align="center"></td>        
                    </tr>
          </tbody>
            <tfoot>
    <tr>
      <th colspan="12" class="fila_titulo"></th>
    </tr>
  </tfoot>
      </table>    
      <button id="btnRendirCaja" class="botones">Rendir Caja y Cerrar Definitivamente</button>
          <?php
            } else {
                ?>
          No existen datos cargados.
  <?php
            }
            ?>

</div>
<p><br />
    <br />
</p>
<div id="mostrar"></div>
<p>&nbsp;</p>	
<p><br />
    <br />
</p>
<div id="mostrar"></div>
<p>&nbsp;</p>