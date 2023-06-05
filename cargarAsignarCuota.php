<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
include_once("cargarOpciones.php");
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
    $(document).ready(function(){

		
<?php
/*
  Obtener_LectivoActual($LecID, $LecNombre);
  obtenerLimitesLectivo($LecID, $LecDesde, $LecHasta);
  $LimiteFecha = "{minDate: '".cfecha($LecDesde)."', maxDate: '".cfecha($LecHasta)."'}";// */
?>

        // $("#FechaDesde").datepicker(<?php echo $LimiteFecha; ?>);
        // $("#FechaHasta").datepicker(<?php echo $LimiteFecha; ?>);
	
        $('table.tabla tbody tr:odd').addClass('fila');

        $('table.tabla tbody tr:even').addClass('fila2');
	
        $("#mostrarNuevo").hide();
        //$(".ocultar").hide();
        $("input[class^='ocultar']").hide();
        $("select[class^='ocultar']").hide();
        $("#mostrar").empty();
        $("#nombre").focus();
        $("#form_Buscador").unbind('submit');
	
        $("#barraNuevo").click(function(evento){
            evento.preventDefault();				
            $("#mostrarNuevo").fadeIn();
            $("#mostrar").empty();		
            $("#divBuscador").fadeOut();
            limpiarDatos();
        });
        function limpiarDatos(){
            $("#TriID").val("");
            $("#Nombre").val("");
        }
	
        // 21012013 modificaciones
        // para guardar una sicopedagoga

        $("#barraGuardar").click(function(evento){
            evento.preventDefault();
		

            if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
                $("#mostrar").empty();
                vCuo_Per_ID = $("#Cuo_Per_ID").val();
                alert(vCuo_Per_ID);
                vCuo_Uni_ID = $("#Cuo_Uni_ID").val();
                vCuo_Niv_ID = $("#Cuo_Niv_ID").val();
                vCuo_Lectivo = $("#Cuo_Lectivo").val();
                
                
                
                if (vCuo_Per_ID==""){
                    mostrarAlerta("Por favor elija una opción","Error");
                    return;
                }
                if (vCuo_Uni_ID==""){
                    mostrarAlerta("Por favor elija una opción","Error");
                    return;
                }
                if (vCuo_Niv_ID==""){
                    mostrarAlerta("Por favor elija una opción","Error");
                    return;
                }
                if (vCuo_Lectivo==""){
                    mostrarAlerta("Por favor elija una opción","Error");
                    return;
                }
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "guardarAsignacionCuota", Cuo_Per_ID: vCuo_Per_ID,Cuo_Uni_ID: vCuo_Uni_ID, Cuo_Niv_ID: vCuo_Niv_ID , Cuo_Lectivo: vCuo_Lectivo},
                    success: function (data){
                        mostrarAlerta(data, "Resultado de la operación");
                        $("#cargando").hide();
                                                
						
                    }
                });//fin ajax  
                mostrarAlerta("Se guardo correctamente la configuración", "Resultado de la operación");
                
                
            }else{
                jAlert("Antes de guardar, haga click en el botón <strong>Nuevo</strong>","Alerta");
            }//fin if
        });

        // para editar sicopedagogas
        $("a[id^='botEditar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(9,10);
		
            $("#Sic_ID").val($("#SicID" + i).val());
            // $("#NivID").val($("#NivID" + i).val());
            // $("#LecID").val($("#LecID" + i).val());
            $("#Sic_Nombre").val($("#Sic_Nombre" + i).val());
            $("#Sic_DNI").val($("#Sic_DNI" + i).val());
            $("#Sic_Tel").val($("#Sic_Tel" + i).val());
            //$("#FechaHasta").val($("#FechaHasta" + i).val());
            $("#mostrarNuevo").fadeIn();
            $("#mostrar").empty();		
            $("#divBuscador").fadeOut();
		
        });//fin evento click//*/	 
        $("a[id^='botBorrar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(9,10);
            vNombre = $("#Sic_Nombre" + i).val();
            vID = $("#Sic_ID" + i).val();
		
            jConfirm('Est&aacute; seguro que desea eliminar a <strong>' + vNombre + '</strong>?', 'Confirmar la eliminaci&oacute;n', function(r){
                if (r){//eligi� eliminar
                    $.post("cargarOpcionesSicopedagoga.php", { opcion: "eliminarSicopedagoga", ID: vID }, function(data){
                        jAlert(data, 'Resultado de la eliminaci&oacute;n');
                        recargarPagina();
                    });//fin post					
                }//fin if
            });//fin del confirm//*/
	
        });//fin evento click//*/	

        // 21012013 fin de modificaciones




        function recargarPagina(){
            $("#mostrar").empty();

            $.ajax({
                cache: false,
                async: false,			
                url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                success: function (data){
                    $("#principal").html(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        }//fin function
        $("#barraBuscar").click(function(evento){
            evento.preventDefault();
            recargarPagina()
        });
        $("input[id^='Editar']").keyup(function(evento){
            if (evento.keyCode == 13){
                var i = this.id.substr(6,10);
                guardarNombre(i);
            }
        });

	 
	

        $("#triNivID").change(function () {
            $("#triNivID option:selected").each(function () {
                //alert($(this).val());
                vtriNivID=$(this).val();
                $.ajax({
                    cache: false,
                    async: false,
                    type: "POST",
                    data: {triNivID: vtriNivID},
                    url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                    success: function (data){
                        $("#principal").html(data);
                        $("#cargando").hide();
                    }
                });//fin ajax
            });
        });
<?php
if (isset($_POST['triNivID'])) {
    ?>		
                $("#triNivID").val("<?php echo $_POST['triNivID'] ?>");
    <?php
}
?>
    });//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
    <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un Pa�s Nuevo" width="48" height="48" border="0" title="Ingresar un Pa�s Nuevo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
                Listar</button></td>
        <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
    </tr>
</table>


<div id="mostrarNuevo">
    <form>
        <table width="80%" border="0" align="center" class="borde_recuadro">
            <tr>
                <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Asignacion de Deuda</div></td>
            </tr>
            <tr>
                <td align="right" class="texto">Alumno:</td>
                <td><?php cargarListaPersonas("Cuo_Per_ID"); ?></td>
            </tr>
            <tr>
                <td align="right" class="texto">Unidad:</td>
                <td><?php cargarUnidad("Cuo_Uni_ID"); ?></td>
            </tr>
            <tr>
                <td align="right" class="texto">Nivel:</td>
                <td>
                    <?php //cargarListaConfigCuota("Cuo_Niv_ID"); 
                    cargarListaNivel("Cuo_Niv_ID");?>
                </td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Lectivo:</div></td>
                <td colspan="2"><?php cargarListaLectivo("Cuo_Lectivo"); ?></td> 
            </tr>
            <tr>
                <td colspan="2" class="texto"></td>
            </tr>
        </table>
    </form>
</div>   
    <div id="divBuscador">

    <table width="98%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Configuracíon de Cuotas</div></td>
        </tr>
        <tr>
            <td align="center" class="texto">
                <!--Filtrar por Año: <?php; ?><br /><br />-->
                <?php
                $sql = "SELECT * FROM cuotamodelo";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    ?>
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
                        <thead>
                            <tr class="ui-widget-header">
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
                                <th align="center">Recargo Mensual</th>
                                <th align="center">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                $i++;
                                ?>
                                <tr>
                                    <?php
                                    $sqL = "SELECT Niv_Nombre FROM Colegio_Nivel where Niv_ID = $row[CMo_Niv_ID]";
                                    $resultado = consulta_mysql_2022($sqL,basename(__FILE__),__LINE__);
                                    $resu1 = mysqli_fetch_array($resultado)
                                    ?>
                                    <td align="center"><?php echo $resu1[Niv_Nombre]; ?></td>
                                    <?php
                                    $SQL = "SELECT Lec_Nombre FROM Lectivo where Lec_ID = $row[CMo_Lec_ID]";
                                    $RES = consulta_mysql_2022($SQL,basename(__FILE__),__LINE__);
                                    $RES1 = mysqli_fetch_array($RES)
                                    ?>
                                    <td align="center"><?php echo $RES1[Lec_Nombre]; ?></td>
                                    <?php
                                    $sql1 = "SELECT CTi_Nombre FROM CuotaTipo where CTi_ID = $row[CMo_CTi_ID]";
                                    $res = consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);
                                    $res2 = mysqli_fetch_array($res)
                                    ?>
                                    <td align="center"><?php echo $res2[CTi_Nombre]; ?></td>
                                    <?php
                                    $sql2 = "SELECT Alt_Nombre FROM CuotaAlternativa where Alt_ID = $row[CMo_Alt_ID]";
                                    $resul = consulta_mysql_2022($sql2,basename(__FILE__),__LINE__);
                                    $res3 = mysqli_fetch_array($resul)
                                    ?>
                                    <td align="center"><?php echo $res3[Alt_Nombre]; ?></td>
                                    <td><?php echo $_SESSION['sesion_usuario']; ?></td>
                                    <td align="center"><?php echo date("d/m/Y"); ?></td>
                                    <td align="center"><?php echo $Hora = date("H:i:s"); ?></td>
                                    <td align="center"><?php echo $row[CMo_CantCuotas]; ?></td>
                                    <td align="center"><?php echo $row[CMo_Importe]; ?></td>
                                    <td align="center"><?php echo $row[CMo_1er_Recargo]; ?></td>
                                    <td align="center"><?php echo $row[CMo_1er_Recargo]; ?></td>                           
                                    <td align="center">
                                        <?php
                                        if ($row[CMo_Mes] == '1')
                                            echo "Enero";
                                        else if ($row[CMo_Mes] == '2')
                                            echo "Febrero";
                                        else if ($row[CMo_Mes] == '3')
                                            echo "Marzo";
                                        else if ($row[CMo_Mes] == '4')
                                            echo " Abril";
                                        else if ($row[CMo_Mes] == '5')
                                            echo "Mayo";
                                        else if ($row[CMo_Mes] == '6')
                                            echo"Junio";
                                        else if ($row[CMo_Mes] == '7')
                                            echo "Julio";
                                        else if ($row[CMo_Mes] == '8')
                                            echo "Agosto";
                                        else if ($row[CMo_Mes] == '9')
                                            echo "Septiembre";
                                        else if ($row[CMo_Mes] == '10')
                                            echo "Octubre";
                                        else if ($row[CMo_Mes] == '11')
                                            echo "Noviembre";
                                        else if ($row[CMo_Mes] == '12')
                                            echo "Diciembre";
                                        ?>
                                    </td>
                                    <td align="center"><?php echo $row[CMo_Anio]; ?></td>
                                    <td align="center"><?php echo $row[CMo_Recargo_Mensual]; ?></td>
                                    <td align="center"><a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                                </tr>

                                <?php
                            }//fin while
                            ?>
                        </tbody>
                    </table>

                    <?php
                } else {
                    ?>
                    No existen datos cargados.
                    <?php
                }
                ?>

            </td>
        </tr>
    </table>

</div>
    <p><br />
        <br />
    </p>
    <div id="mostrar"></div>
    <p>&nbsp;</p>
