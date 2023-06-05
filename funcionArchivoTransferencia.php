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

    /*$.ajax({
        type: "POST",
        async: false,
        cache: false,
        url: 'cargarOpciones.php',
        data: {opcion: "guardarDebito", consulta: vConsulta},
        success: function (data){
                mostrarAlerta(data, "Resultado de la operacion");
            }
    });//fin ajax*/
});
</script>

<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="tabla_deb">
    <tr>
        <th>Nº</th>
        <th>Fecha Movimiento</th>
        <th>Importe</th> 
        <th>Referencia</th>
        <th>Concepto</th>
        <th>Persona</th>
        <th>Grupo Familiar</th>
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
//$sql = "INSERT INTO FacturaDebitoAutomatico (FDA_NroTarj, FDA_NroInt, FDA_Fecha, FDA_Monto, FDA_IDVisa, FDA_Estado)";
$persona='';
$alumno='';
$fecha_creacion = date('Y-m-d');
$hora_creacion = date('H:i:s');
$detalle='';
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

        list($fechaMov, $importe, $referencia, $concepto) = explode(';', $l);

        

        $dni_persona = substr($concepto, 13, 8);
        $cuil_persona = substr($concepto, 11, 11);
        $sql = "SELECT * FROM Persona WHERE Per_DNI = '$dni_persona'";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result)>0){
            $row = mysqli_fetch_array($result);
            $persona = $row['Per_Apellido'].', '.$row['Per_Nombre']." ($dni_persona)";
            $Per_ID = $row['Per_ID'];
            //$sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$Per_ID' AND Fam_FTi_ID = 2";
            $grupo_familiar = Obtener_Grupo_Familiar($Per_ID, true);
            $sin_identificar=0;
            $vinculado=1;

        }else{
            $persona = 'No se encuentra cargado';
            $grupo_familiar = '';
            $dni_persona='';
            $cuil_persona='';
            $sin_identificar=1;
            $vinculado=0;
        }



        $sql = "SELECT * FROM transferencias WHERE referencia = '$referencia'";
        $result_buscar = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result_buscar)==0){
            list($dia,$mes,$anio) = explode('/', $fechaMov);
            $dia = substr('00'.$dia, -2);
            $mes = substr('00'.$mes, -2);
            $fecha_movimiento = "$anio-$mes-$dia";
            $importe_subir = str_replace('.', '', $importe);
            $importe_subir = str_replace(',', '.', $importe_subir);
            $sql = "INSERT INTO transferencias (fecha_creacion, hora_creacion, fecha_movimiento, importe, referencia, concepto, detalle, dni, cuil, persona, grupo_familiar, vinculado, sin_identificar) VALUES('$fecha_creacion', '$hora_creacion', '$fecha_movimiento', '$importe_subir', '$referencia', '$concepto', '$detalle', '$dni_persona', '$cuil_persona', '$persona', '$grupo_familiar', '$vinculado', '$sin_identificar')";
            //echo "$sql<br>";
            consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        }

        $i++;
        ?>
        <tr>
            <td><?php echo ($i - 1); ?></td>
            <td><?php echo $fechaMov; ?></td>
            <td>$<?php echo $importe; ?></td>            
            <td><?php echo $referencia; ?></td>
            <td><?php echo $concepto; ?></td>
            <td><?php echo $persona; ?></td>
            <td><?php echo $grupo_familiar; ?></td>
        </tr>
        <?php
    }
} //fin foreach
?>
</table>
<!-- <button id="botCargarr" name="botCargar" value="<?php //echo $sql ?>"> Cargar Informacion </button> -->