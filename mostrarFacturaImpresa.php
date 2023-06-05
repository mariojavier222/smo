<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
 ?>
 	<script src="js/jquery.printElement.js" language="javascript"></script>
   <!-- <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />-->

<script src="js/jquery.dataTables.js" language="javascript"></script>
 <script type="text/javascript">
 $(document).ready(function(){
 	 $("#imprimirTodas222").click(function(evento){
		 //alert("asdasd");
		evento.preventDefault();
		
		$("#listadoTabla2").printElement({leaveOpen:true, printMode:'iframe', pageTitle:'',overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
		});
		
		$("#cargando").hide();
	 });//fin evento click//*/
 })
 
 </script>
 <?php
 $sql = "SET NAMES UTF8;";
 consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	/*$sql = "SELECT LAST_INSERT_ID(Fac_ID) FROM Factura;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$row = mysqli_fetch_array($result);*/

//$Fac_Numero = $FacturaNumero;
$Fac_Numero = $_POST['FacturaNumero'];
//echo $Fac_Numero."<br />";
list($Sucursal, $Numero) = explode("-",$Fac_Numero);
//echo $Numero;
$sql = "SELECT * FROM Factura WHERE Fac_Numero ='$Numero' AND Fac_Sucursal = '$Sucursal'";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$row = mysqli_fetch_array($result);
$FacID=$row[Fac_ID];
?>
 <style type="text/css">
 body {
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
 .anchoTabla {
	width: 750px;
}
 body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
 </style>
 
<div id="listado" class="page-break2">
    <table border="0" align="center" cellpadding="1" cellspacing="1" class="anchoTabla" id="listadoTabla2">
        <tr style="background-color: gainsboro;">
          <td align="right" style="font-size:16pt"><?php echo $Numero;?></td>
          <td >&nbsp;</td>
        </tr>
        <tr style="background-color: gainsboro;">
          <td align="right" style="font-size:13pt"><?php echo cfecha($row[Fac_Fecha]); ?></td>
          <td >&nbsp;</td>
        </tr>
        <tr style="background-color: gainsboro;">
            <td><div align="left" style="margin-top: 10px; font-size:14px" class="titulo_noticia"><?php echo $row[Fac_PersonaNombre]; ?></div>
               
            </td>
            <td>
               
            </td>
        </tr>
       
        
                <tr style="background-color: gainsboro; height: 450px;">
                    <td colspan="2"><?php
        $sql_1 = "SELECT * FROM FacturaDetalle WHERE FDe_Fac_ID =$FacID";
        $resultado = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($resultado) > 0) {
            while ($row1 = mysqli_fetch_array($resultado)) {
                $i++;
                ?>
                <div align="left"  class="titulo_noticia">
                <table class="anchoTabla">
                <tr><td align="left"><?php echo $row1[FDe_Detalle]; ?></td><td align="right">$<?php echo $row1[FDe_Importe]; ?></td></tr></table></div>               
                <?php
            }//fin while
        } //fin if
        ?></td>
                </tr>
        <tr style="background-color: gainsboro;">
            <td colspan="2"><div style="margin-top: 25px;" align="right" class="titulo_noticia">Total: $<?php echo $row[Fac_ImporteTotal]; ?></div></td>
        </tr>
    </table>
</div>

<fieldset class="recuadro_inferior" style="height:32px">
  <div align="left"><a href="imprimir_recibo_gateway.php?FacturaNumero=<?php echo $_POST['FacturaNumero'];?>" target="_blank" id="imprimirTodas222_NO"><img src="imagenes/printer.png" alt="Imprimir las cuotas seleccionadas" title="Imprimir las cuotas seleccionadas" width="32" border="0" align="absmiddle" /></a></div>
<br /><br /></fieldset>





