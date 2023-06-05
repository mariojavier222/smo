<?php
// 12012013 
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");
require_once("listas.php");
consulta_mysql("SET NAMES utf8");
$Apellido = $_POST['Apellido'];
$sql = "SELECT DISTINCTROW * FROM Persona WHERE Per_Apellido like '$Apellido%' ORDER BY Per_Apellido, Per_Nombre";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);
if ($total > 0) {
    ?>

    <script type="text/javascript" >
        $(document).ready(function(){
            // para editar 
            $("a[id^='edi']").click(function(evento){
                evento.preventDefault();
                vID = this.id.substr(3,10);
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    url:'editarDatosAspirante.php',// 'PersonaEditar.php',	
                    data: {PerID: vID},
                    success: function(data){
                        $("#listadoPersonas").hide();
                        $("#editar").html(data);
                        $("#editar").show();
                    }//fin success
                });//fin ajax//
            });//fin click eliminar
            // fin editar
                    
                    
                    
            $("button[id^='deuda']").click(function(evento){
                evento.preventDefault();
                vID = this.id.substr(5,10);
                $("#mostrar").empty();
                $("#tr_deuda" + vID).show();
                
            });
                    
            $("button[id^='btn_vista_previa_cerrar']").click(function(evento){
                evento.preventDefault();
                vID = this.id.substr(23,30);
                $("#tr_deuda" + vID).hide();
            });
        });//fin de la funcion ready
    </script>

    <link href="css/general.css" rel="stylesheet" type="text/css" />

    <fieldset class="recuadro_simple texto" id="resultado_buscador">
        <legend>Resultado de la b&uacute;squeda</legend>

        <div id="listado" class="texto">	

            <table width="80%" border="0" id="listadoTabla" class="display">
                <thead>
                    <tr>
                        <th class="fila_titulo" width="120"><div align="left">Documento</div></th>
                <th class="fila_titulo"><div align="left">Apellido</div></th>
                <th class="fila_titulo"><div align="left">Nombre</div></th>
                <th class="fila_titulo"><div align="left">Sexo</div></th>
<!--                <th class="fila_titulo"><div align="left">Editar</div></th>
                <th class="fila_titulo"><div align="left">Datos</div></th>-->
                <th class="fila_titulo"><div align="left">accion</div></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $foto = buscarFoto($row[Per_DNI], $row[Per_Doc_ID], 60);
                        $i++;
                        // preguntar si tiene datos PersonaDatos
                        $sql1 = "SELECT DISTINCTROW * FROM PersonaDatos WHERE Dat_Per_ID=" . $row[Per_ID];
                        $result1 = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
                        $total1 = mysqli_num_rows($result1);
                        $Per_ID = $row[Per_ID];
                        if ($total1 > 0)
                            $datos = "Si";
                        else
                            $datos = "No";

                        //

                        if (($i % 2) == 0)
                            $clase = "fila"; else
                            $clase = "fila2";
                        ?>
                        <tr class="gradeA <?php echo $clase; ?>">
                            <td><?php echo "$row[Doc_Nombre] $row[Per_DNI]"; ?> </td>
                            <td><?php echo $row[Per_Apellido]; ?> </td>
                            <td><?php echo $row[Per_Nombre]; ?> </td>
                            <td><?php echo $row[Per_Sexo]; ?></td>
<!--                            <td><a href="#" id="edi<?php echo $row[Per_ID]; ?>"> Editar </a></td>
                            <td><?php echo $datos; ?></td>-->
                            <td colspan="2" align="center" class="texto"><button class="botones" id="deuda<?php echo $i?>">Mostrar Deuda</button>
                            <td colspan="2"><input style="display:none" value="ListarDeuda" id="opcion" name="opcion" /></td> 

                        </tr>
                        <tr id="tr_deuda<?php echo $i?>" style="display:none">
                            <td colspan="2" width="48">
                                <div id="div_deuda" ><input style="display:none;" name ="Per_ID" id="Per_ID"/>
                                    <?php
                                    ListarDeuda($row[Per_ID]);
                                    ?>
                                    <button id="btn_vista_previa_cerrar<?php echo $i?>">Cerrar</button>
                                </div>

                            </td>
                        </tr>
                        <?php
                    }//fin del while
                    ?>  
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="fila_titulo" height="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </fieldset>
    <fieldset class="recuadro_inferior">
        <?php echo "Se econtraron $total personas"; ?>
    </fieldset>	
    <?php
}else {
    echo "No se encontraron personas asociadas a la búsqueda";
}
?>