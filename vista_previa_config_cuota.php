<link type="text/css" href="css/dot-luv/jquery-ui-1.8.1.custom.css" rel="Stylesheet" /> 
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.1.custom.min.js"></script> 
<?php
require_once("funciones_generales.php");

function cargaListado() {
    $CMo_Uni_ID = $_POST['CMo_Uni_ID'];
    $CMo_Niv_ID = $_POST['CMo_Niv_ID'];
    $CMo_Lectivo = $_POST['CMo_Lectivo'];
    $CTi_ID = $_POST['CTi_ID'];
//                        $CMo_Usu_ID = $_SESSION['sesion_UsuID'];
    $Alt_ID = $_POST['Alt_ID'];
//                        $CMo_Hora = cambiaf_a_mysql($_POST['CMo_Hora']);
    $CMo_CantCuotas = $_POST['CMo_CantCuotas'];
    $CMo_Importe = $_POST['CMo_Importe'];
    $CMo_1er_Recargo = $_POST['CMo_1er_Recargo'];
    $CMo_2do_Recargo = $_POST['CMo_2do_Recargo'];
    $CMo_Mes = $_POST['CMo_Mes'];
    echo $CMo_Anio = $_POST['CMo_Anio'];
    
    ?>
    <div id="vistaprevia">    
        <table>
            <thead>
                <tr class="ui-widget-header">
                    <th align="center">Unidad</th>
                    <th align="center">Nivel</th>
                    <th align="center">Lectivo</th>
                    <th align="center">Tipo de Cuota</th>
                    <th align="center">Alternativa de Cuota</th>
                    <th align="center">Usuario</th>
                    <th align="center">Fecha Actual</th>
                    <th align="center">Hora Actual</th>
                    <th align="center">Cant. Cuotas</th>
                    <th align="center">Importe</th>
                    <th align="center">1º Recargo</th>
                    <th align="center">2º Recargo</th>
                    <th align="center">Mes</th>
                    <th align="center">Año</th>
                </tr>
            <tbody>
                    <tr>
                        <td align="center"><?php echo $CMo_Uni_ID; ?></td>
                        <td align="center"><?php echo $CMo_Niv_ID; ?></td>
                        <td align="center"><?php echo $CMo_Lectivo; ?></td>
                        <td align="center"><?php echo $CTi_ID; ?></td>
                        <td align="center"><?php echo $Alt_ID; ?></td>
                        <td><?php echo $_SESSION['sesion_usuario']; ?></td>
                        <td align="center"><?php echo date("d/m/Y"); ?></td>
                        <td align="center"><?php echo date("H:i:s"); ?></td>
                        <td align="center"><?php echo $CMo_CantCuotas; ?></td>
                        <td align="center"><?php echo $CMo_Importe; ?></td>
                        <td align="center"><?php echo $CMo_1er_Recargo; ?></td>
                        <td align="center"><?php echo $CMo_2do_Recargo; ?></td>                           
                        <td align="center">
                            <?php
                            if ($CMo_Mes == '1')
                                echo "Enero";
                            else if ($CMo_Mes == '2')
                                echo "Febrero";
                            else if ($CMo_Mes == '3')
                                echo "Marzo";
                            else if ($CMo_Mes == '4')
                                echo " Abril";
                            else if ($CMo_Mes == '5')
                                echo "Mayo";
                            else if ($CMo_Mes == '6')
                                echo"Junio";
                            else if ($CMo_Mes == '7')
                                echo "Julio";
                            else if ($CMo_Mes == '8')
                                echo "Agosto";
                            else if ($CMo_Mes == '9')
                                echo "Septiembre";
                            else if ($CMo_Mes == '10')
                                echo "Octubre";
                            else if ($CMo_Mes == '11')
                                echo "Noviembre";
                            else if ($CMo_Mes == '12')
                                echo "Diciembre";
                            ?>
                        </td>
                        <td align="center"><?php echo $CMo_Anio; ?></td>

                    </tr>
            </tbody>
        </table>
    </div>
    <?php
}
?>

