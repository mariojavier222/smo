<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
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

                vSicID= $("#Sic_ID").val();
                vSicNombre = $("#Sic_Nombre").val();
                vSicDNI = $("#Sic_DNI").val();
                vSicTel = $("#Sic_Tel").val();
				
			
                if (vSicNombre==""){
                    mostrarAlerta("Por favor escriba un nombre ","Error");
                    return;
                }
                if (vSicDNI==""){
                    mostrarAlerta("Por favor escriba un DNI","Error");
                    return;
                }
                if (vSicTel==""){
                    mostrarAlerta("Por favor escriba un Telefono","Error");
                    return;
                }
			
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpcionesSicopedagoga.php',
                    data: {opcion: "guardarSicopedagoga", nombre: vSicNombre, dni: vSicDNI, tel: vSicTel },
                    success: function (data){
                        mostrarAlerta(data, "Resultado de la operaci&oacute;n");
                        //                                $("#cargando").hide();
						
                    }
                });//fin ajax
            }else{
                jAlert("Antes de guardar, haga click en el bot&oacute;n <strong>Nuevo</strong>","Alerta");
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
    <table width="80%" border="0" align="center" class="borde_recuadro">

        <tr>
            <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Nueva Sicopedagoga</div></td>
        </tr>
        <tr>
            <td class="texto"><div align="right">Nombre :</div></td>
            <td>
                <input name="Sic_Nombre" type="text" id="Sic_Nombre" size="40"/>
                <input type="hidden" name="TriID" id="TriID" />
            </td>
        </tr>
        <tr>
            <td align="right" class="texto">DNI :</td>
            <td><input name="Sic_DNI" type="text" id="Sic_DNI" size="40"/>
                <input type="hidden" name="TriID" id="TriID" />
            </td>
        </tr>
        <td align="right" class="texto">Telefono :</td>
        <td>
            <input name="Sic_Tel" type="text" id="Sic_Tel" size="40"/>
            <input type="hidden" name="TriID" id="TriID" />
        </td>

    </table>

</div>    
    <div id="divBuscador">

        <table width="98%" border="0" align="center" class="borde_recuadro">
            <tr>
                <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Sicopedagogas </div></td>
            </tr>
            <tr>
                <td align="center" class="texto">

                    <?php
                    $sql = "SET NAMES UTF8;";
                    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//$triNivID = $_POST['triNivID'];
//if (!empty($triNivID)) $where = " WHERE Tri_Niv_ID = $triNivID ";

                    $sql = "SELECT * FROM Sicopedagoga";

                    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                    if (mysqli_num_rows($result) > 0) {
                        ?>
                        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
                            <thead>
                                <tr class="ui-widget-header">
                                    <th align="center">C&oacute;d.</th>
                                    <th align="center">Nombre</th>
                                    <th align="center">DNI</th>
                                    <th align="center">Telefono</th>
                                    <th align="center">Acci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td align="center"><?php echo $row[Sic_ID]; ?>
                                            <input type="hidden" id="Sic_ID<?php echo $i; ?>" value="<?php echo $row[Sic_ID]; ?>" />
                                            <input type="hidden" id="Sic_Nombre<?php echo $i; ?>" value="<?php echo $row[Sic_Nombre]; ?>" />
                                            <input type="hidden" id="Sic_DNI<?php echo $i; ?>" value="<?php echo $row[Sic_DNI]; ?>" />
                                        </td>
                                        <td><?php echo $row[Sic_Nombre]; ?></td>
                                        <td><?php echo $row[Sic_DNI]; ?></td>
                                        <td><?php echo $row[Sic_Tel]; ?></td>                                    
                                        <td align="center"><a href="#" id="botEditar<?php echo $i; ?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $row[Sic_ID]; ?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
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
