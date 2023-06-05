<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");

?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#tabla_deb td, th {
    height: 25px;
}
</style>
<script type="text/javascript">

$("#botCargar").click(function(evento){

    vConsulta = $("#botCargar").val();

    $.ajax({
        type: "POST",
        async: false,
        cache: false,
        url: 'cargarOpciones.php',
        data: {opcion: "guardarDebito", consulta: vConsulta},
        success: function (data){
                mostrarAlerta(data, "Resultado de la operacion");
            }
    });//fin ajax
});
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="tabla_deb">
    <tr>
        <th>Nº</th>
        <th>Nro de tarjeta</th>
        <th>Nro interno</th> 
        <th>Fecha</th>
        <th>Monto</th>
        <th>ID VISA</th>
        <th>Estado</th>
    </tr>
<?php

$archiTxt = $_FILES['file']['tmp_name'];
$fh = fopen($archiTxt, "r");
if ( $fh ) {
  while ( !feof($fh) ) {
    $line[] = fgets($fh);
  }
  fclose($fh);
}

$cant = count($line);
$i = 0; $j = 0;
$TotS = 0;
$TotE = 0;
$Err = 0;
$sql = "INSERT INTO FacturaDebitoAutomatico (FDA_NroTarj, FDA_NroInt, FDA_Fecha, FDA_Monto, FDA_IDVisa, FDA_Estado)";

foreach ($line as $l) {
    
    if (!($i < $cant-2) || ($i == 0)) { $i++; }
    else
    {
        $NroTarj = substr($l, 0, 17);
        $NroInt = substr($l, 20, 8);
        $Fecha = substr($l, 28, 4)."/".substr($l, 32, 2)."/".substr($l, 34, 2);
        $Monto = substr($l, 47, 6).".".substr($l, 53, 2);
        $Monto = (float) $Monto;
        $IDVisa = substr($l, 66, 4);
        if (substr($l, 100, 1) == ' ') { $Estado = "CORRECTO"; $TotS += $Monto; }
        else { $Estado = substr($l, 100, 3)." ".substr($l, 103, 45); $TotE += $Monto; $Err++;}
        if ($j == 0) 
        {
            $sql .= " VALUES ('$NroTarj', '$NroInt', '$Fecha', '$Monto', '$IDVisa', '$Estado')"; $j = 1;
        }
        else
        {
            $sql .= ", ('$NroTarj', '$NroInt', '$Fecha', '$Monto', '$IDVisa', '$Estado')";
        }

        $i++;
        ?>
        <tr <?php if ($Estado != "CORRECTO") { ?> style="background-color: #ff8a8a;" <?php } ?> >
            <td><?php echo ($i - 1); ?></td>
            <td><?php echo $NroTarj; ?></td>
            <td><?php echo $NroInt; ?></td>
            <td><?php echo $Fecha; ?></td>
            <td align="right"><?php echo number_format ($Monto, 2, ",", "."); ?></td>
            <td><?php echo $IDVisa; ?></td>
            <td><?php echo $Estado; ?></td>
        </tr>
        <?php
    }
} //fin foreach
?>

        <tr>
        <td colspan="2"><strong> Monto total: $<?php echo ($TotS+$TotE); ?></strong></td>
        <td colspan="2"><strong> Total sin errores: $<?php echo $TotS; ?></strong></td>
        <td colspan="2"><strong> Total con errores (<?php echo $Err; ?>): $<?php echo $TotE; ?></strong></td>
        </tr>

</table>
<button id="botCargar" name="botCargar" value="<?php echo $sql ?>"> Cargar Informacion </button>