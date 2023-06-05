<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
include_once("cargarOpciones.php");
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
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
            $("#listado").fadeOut();
            limpiarDatos();
        });
        
        function limpiarDatos(){
            $("#TriID").val("");
            $("#Nombre").val("");
        }
	
        // 21012013 modificaciones
        // para guardar una sicopedagoga
        
        //        $("a[id^='campos']").click(function(evento){											  
        //            evento.preventDefault();
        //            var i = this.id.substr(9,10);
        //            vForID = $("#For_ID").val();
        //            $.ajax({
        //                type: "POST",
        //                cache: false,
        //                async: false,			
        //                url: 'cargarFormaPagoDetalle.php',
        //                data: {For_ID: vForID, pag_Volver: "cargarFormaPago"},
        //                success: function (data){
        //                    $("#principal").html(data);
        //                    //mostrarAlerta(data);
        //                    $("#cargando").hide();
        //                }
        //            });//fin ajax
        //        });	
        
        $("#barraGuardar").click(function(evento){
            evento.preventDefault();
		

            if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
                $("#mostrar").empty();

                vNombre= $("#For_Nombre").val();
                vSigla = $("#Sigla").val();
                vCue_ID = $("#For_Cue_ID").val();
                vFor_ID = $("#For_ID").val();
				
		
                vError = false;
                vTexto_Error = '';
                
                if (vNombre==""){
                    vError = true;
                    vTexto_Error = vTexto_Error +  "Nombre invalido </br>" ;
                    $("#Nombre").attr("class","input_error");
                    					
					
                }
                else {
                    $("#Nombre").attr("class","input_sesion");
                }
                if (vSigla==""){
                    vError = true;
                    vTexto_Error = vTexto_Error +  "Sigla invalido </br>" ;
                    $("#Sigla").attr("class","input_error");
                    					
					
                }
                else {
                    $("#Sigla").attr("class","input_sesion");
                }
                
                if(vError) {
                    mostrarAlerta(vTexto_Error,"Existen datos incorrectos");
                    return;
                }
			
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpcionesFormaPago.php',
                    data: {opcion: "guardarFormaPago", Nombre: vNombre, Sigla: vSigla, For_ID: vFor_ID, For_Cue_ID: vCue_ID},
                    success: function (data){
                        mostrarAlerta1(data, "Resultado de la operaci&oacute;n");
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
            
            $("#For_ID").val($("#For_ID" + i).val());
            $("#For_Cue_ID").val($("#For_Cue_ID" + i).val());
            $("#For_Nombre").val($("#For_Nombre" + i).val());
            $("#Sigla").val($("#For_Siglas" + i).val());
            $("#mostrarNuevo").fadeIn();
            $("#mostrar").empty();		
            $("#listado").fadeOut();
		
        });//fin evento click//*/	 
        $("a[id^='botBorrar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(9,10);
            vFor_Nombre = $("#For_Nombre" + i).val();
            vID = $("#For_ID" + i).val();
            
		
            jConfirm('Est&aacute; seguro que desea eliminar a <strong>' + vFor_Nombre + '</strong>?', 'Confirmar la eliminación', function(r){
                if (r){//eligio eliminar
                    $.post("cargarOpcionesFormaPago.php", { opcion: "eliminarFormaPago", ID: vID }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
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
                    //$("#cargando").hide();
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
        
        //        $('#listadoTabla').dataTable( {
        //            "bPaginate": true,
        //            //"aaSorting": [[ 1, "asc" ]],
        //            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
        //            "bLengthChange": false,
        //            "bFilter": true,
        //            "bSort": true,
        //            "bInfo": true,
        //            "bAutoWidth": true } );//*/
        
        $("a[id^='campos']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(6,15);
            $("#mostrar").empty();
            $("#tr_campos" + i).show();            
            
                
        });  
        $("button[id^='btn_vista_previa_guardar']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(24,30);
            vForID = $("#For_ID" + i).val();
            vFDe_Nombre1 = $("#FDe_Nombre1"+ i).val();
            vFDe_Nombre2 = $("#FDe_Nombre2"+ i).val();
            vFDe_Nombre3 = $("#FDe_Nombre3"+ i).val();
            vFDe_Nombre4 = $("#FDe_Nombre4"+ i).val();
            vFDe_Nombre5 = $("#FDe_Nombre5"+ i).val();
            vFDe_Nombre6 = $("#FDe_Nombre6"+ i).val();
            vFDe_Nombre7 = $("#FDe_Nombre7"+ i).val();
            vFDe_Nombre8 = $("#FDe_Nombre8"+ i).val();
            vFDe_Nombre9 = $("#FDe_Nombre9"+ i).val();
            vFDe_Nombre10 = $("#FDe_Nombre10"+ i).val();            
                         
            $.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarOpciones.php',
                data: {opcion: "guardarDetallePago", For_ID: vForID, FDe_Nombre1: vFDe_Nombre1, FDe_Nombre2: vFDe_Nombre2, FDe_Nombre3: vFDe_Nombre3, FDe_Nombre4: vFDe_Nombre4,
                    FDe_Nombre5: vFDe_Nombre5, FDe_Nombre6: vFDe_Nombre6, FDe_Nombre7: vFDe_Nombre7, FDe_Nombre8: vFDe_Nombre8,FDe_Nombre9: vFDe_Nombre9, FDe_Nombre10: vFDe_Nombre10},
                success: function (data){
                    mostrarAlerta1(data, "Resultado de la operaci&oacute;n");
                    //                                $("#cargando").hide();
						
                }
            });//fin ajax
        });
        $("button[id^='btn_vista_previa_cerrar']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(23,10);
            $("#tr_campos"+ i).hide();
        }); 
        
    });//fin de la funcion ready


</script>
<?php

function buscarValor($ForID,  $FDeID) {
        
    $sql = "SELECT * FROM FormaPagoDetalle WHERE FDe_For_ID = $ForID AND FDe_ID = $FDeID ORDER BY FDe_ID";
    $resultado = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $total = mysqli_num_rows($resultado);
	if($total>0){
		$row = mysqli_fetch_array($resultado);
		echo $row[FDe_Nombre];
	}else{
			echo '';
			}       
         
}
?>
<table border="0" align="center" cellspacing="4">
    <tr>
        <td width="59"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un Pa�s Nuevo" width="48" height="48" border="0" title="Ingresar un Pa�s Nuevo" /><br />Nuevo</button> </td>
        <td width="59"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
                Listar</button></td>
        <td width="59"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
        <td width="12"></td>
    </tr>
</table>

<div id="mostrarNuevo">
    <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Nueva Detalle de Forma de Pago</div></td>
        </tr>
        <tr>            
            <td class="texto"><div align="right">Nombre :</div></td>
            <td><input name="For_Nombre" type="text" id="For_Nombre" size="40"/>
                <input name="For_ID" type="hidden" id="For_ID" size="40"/>
            </td>
        </tr>
        <tr>
            <td align="right" class="texto">Sigla :</td>
            <td><input name="Sigla" type="text" id="Sigla" size="40"/>
            </td>
        </tr>
        <tr>
            <td align="right" class="texto">Cuenta Contable Asociada :</td>
            <td><?php cargarListaCuentaContable("For_Cue_ID");?>
            </td>
        </tr>
    </table>
</div> 
<div id="listado" class="page-break">


    <fieldset class="recuadro_simple" id="resultado_buscador">
        <legend>Resultado de la b&uacute;squeda</legend>    
        <br />
        <br />
        <div align="center" class="titulo_noticia">Listado de Forma de Pago</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        $sql_1 = "SELECT * FROM FormaPago";
        $result = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table width="100%" border="0" id="listadoTabla" class="display texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Codigo</th>
              <th align="center" class="fila_titulo">Nombre</th>
              <th align="center" class="fila_titulo">Sigla</th>
              <th align="center" class="fila_titulo">Cuenta Contable Asociada</th>
              <th align="center" class="fila_titulo">Acci&oacute;n</th>
            </tr>
          </thead>
          <tbody>
            <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        ?>
            <tr style="background-color: gainsboro;">
              <td align="center"><?php echo $row[For_ID]; ?>
                <input type="hidden" name="For_ID" id="For_ID<?php echo $i; ?>" value="<?php echo $row[For_ID]; ?>" />
                <input type="hidden" name="For_Cue_ID" id="For_Cue_ID<?php echo $i; ?>" value="<?php echo $row[For_Cue_ID]; ?>" />
                <input type="hidden" name="For_Nombre" id="For_Nombre<?php echo $i; ?>" value="<?php echo $row[For_Nombre]; ?>" />
                <input type="hidden" name="For_Siglas" id="For_Siglas<?php echo $i; ?>" value="<?php echo $row[For_Siglas]; ?>" /></td>
              <td align="center"><?php echo $row[For_Nombre]; ?></td>
              <td align="center"><?php echo $row[For_Siglas]; ?></td>
              <td align="center"><?php echo (!empty($row['For_Cue_ID']))?buscarNombreCuentaContable($row['For_Cue_ID']):"No tiene"; ?></td>
              <td align="center"><a href="#" id="botEditar<?php echo $i; ?>"> <img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /> </a> <a href="#" id="botBorrar<?php echo $i; ?>"> <img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /> </a> <a id="campos<?php echo $i; ?>"> <img  style ="margin-left: 20px; margin-bottom: 7px;" src="imagenes/go-jump.png" alt="Editar Campos" title="Editar Campos" width="22" height="22" border="0" /> </a></td>
            </tr>
            <tr id="tr_campos<?php echo $i; ?>" style="display:none;">
              <td colspan="2" width="48"><div id="div_campos">
                <table>
                  <thead>
                    <tr class="gradeA" id="fila">
                      <th align="center" class="fila_titulo">Detalle</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre1" id="FDe_Nombre1<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 1);?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre2" id="FDe_Nombre2<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 2);?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre3" id="FDe_Nombre3<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 3); ?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre4" id="FDe_Nombre4<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 4); ?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre5" id="FDe_Nombre5<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 5); ?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre6" id="FDe_Nombre6<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 6); ?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre7" id="FDe_Nombre7<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 7); ?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre8" id="FDe_Nombre8<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 8); ?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre9" id="FDe_Nombre9<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 9); ?>"/></td>
                    </tr>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><input name="FDe_Nombre10" id="FDe_Nombre10<?php echo $i; ?>" value="<?php buscarValor($row[For_ID], 10); ?>"/></td>
                    </tr>
                    <tr>
                      <td><button id="btn_vista_previa_guardar<?php echo $i ?>">Guardar</button>
                        <button id="btn_vista_previa_cerrar<?php echo $i ?>">Cerrar</button></td>
                    </tr>
                  </tbody>
                </table>
              </div></td>
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
</div>
<p><br />
    <br />
</p>
<div id="mostrar"></div>
<p>&nbsp;</p>
