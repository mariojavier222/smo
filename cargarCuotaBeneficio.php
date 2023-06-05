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
		

		
$("#editartd").hide();
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
			
			$("#guardartd").show();
			$("#editartd").hide();
						
            $("#mostrarNuevo").show();
            $("#mostrar").empty();		
            $("#listado").hide();
            limpiarDatos();
        });
        function limpiarDatos(){
            $("#Sigla").val("");
            $("#Nombre").val("");
        }
	
        // 21012013 modificaciones
        // para guardar una sicopedagoga
        
	
        
        $("#barraGuardar").click(function(evento){
            evento.preventDefault();
		

            if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
                $("#mostrar").empty();

                vNombre= $("#Nombre").val();
                vSigla = $("#Sigla").val();
				
		
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
                    url: 'cargarOpcionesCuotaBeneficio.php',
                    data: {opcion: "guardarCuotaBeneficio", Nombre: vNombre, Sigla: vSigla},
                    success: function (data){
                        mostrarAlerta(data, "Resultado de la operaci&oacute;n");
						
						$("#barraBuscar").click();
						
						
                        //                                $("#cargando").hide();
						
                    }
                });//fin ajax
            }else{
                jAlert("Antes de guardar, haga click en el bot&oacute;n <strong>Nuevo</strong>","Alerta");
            }//fin if
        });	

        // para editar sicopedagogas
 
        $("a[id^='botBorrar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(9,10);
            vAu_Marca = $("#Au_Marca" + i).val();
            vID = $("#Au_ID" + i).val();
            
		
            jConfirm('Est&aacute; seguro que desea eliminar a <strong>' + vBen_Nombre + '</strong>?', 'Confirmar la eliminación', function(r){
                if (r){//eligio eliminar
                    $.post("cargarOpcionesAuto.php", { opcion: "eliminarAuto", ID: vID }, function(data){
                        jAlert(data, 'Resultado de la eliminación');
                        recargarPagina();
                    });//fin post					
                }//fin if
            });//fin del confirm//*/
	
        });//fin evento click//*/	

        // 21012013 fin de modificaciones

$("#barraGuardarEditar").click(function(evento){
            evento.preventDefault();
		

            if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
                $("#mostrar").empty();

                vNombre= $("#Nombre").val();
				Ben_ID= $("#Ben_ID").val();
                vSigla = $("#Sigla").val();
				
		
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
                    url: 'cargarOpcionesCuotaBeneficio.php',
                    data: {opcion: "editarCuotaBeneficio", Nombre: vNombre, Sigla: vSigla,Ben_ID:Ben_ID},
                    success: function (data){
                        mostrarAlerta(data, "Resultado de la operaci&oacute;n");
						
						$("#barraBuscar").click();
						
						
                        //                                $("#cargando").hide();
						
                    }
                });//fin ajax
            }else{
                jAlert("Antes de guardar, haga click en el bot&oacute;n <strong>Nuevo</strong>","Alerta");
            }//fin if
        })


        
   /*     $("input[id^='Editar']").keyup(function(evento){
            if (evento.keyCode == 13){
                var i = this.id.substr(6,10);
                guardarNombre(i);
            }
        });
		
		*/
        
        $('#listadoTabla').dataTable( {
            "bPaginate": true,
            //"aaSorting": [[ 1, "asc" ]],
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
            "bLengthChange": false,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true } );//*/

$("#barraBuscar").click(function(){
	
	
	$("#Nombre").val('');
    $("#Sigla").val('');
	
	$("#guardartd").show();
	$("#editartd").hide();
	
     $("#mostrar").empty();	
	 $("#mostrarNuevo").hide();	
     $("#listado").show();
	 
	$.ajax({
	type: "POST",
	cache: false,
	async: false,			
	url: 'cargarOpcionesCuotaBeneficio.php',
	data: {opcion: "listadoBeneficio"},
	success: function (data){
	 $("#listado").html(data);	
	
	//                                $("#cargando").hide();
	
	}
	});//fin ajax
	  
	
	});
	
	$("#barraBuscar").click();
			
    });//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
    <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un Pa�s Nuevo" width="48" height="48" border="0" title="Ingresar un Pa�s Nuevo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
                Listar</button></td>
        <td width="48" id="guardartd"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo Beneficio" width="48" height="48" border="0" title="Guardar nuevo Beneficio" /><br />Guardar</button></td>
        <td width="48" id="editartd"><button class="barra_boton"  id="barraGuardarEditar">  <img src="botones/guardar.png" alt="Editar Beneficio" width="48" height="48" border="0" title="Editar Beneficio" /><br />Editar</button></td>
    </tr>
</table>

<div id="mostrarNuevo">
    <table width="80%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Nuevo Beneficio</div></td>
        </tr>
        <tr>
            <td>
                <div align="right" class="titulo_noticia">Datos del Beneficio</div>
            </td>
        </tr>
        <tr>            
            <td class="texto"><div align="right">Nombre :</div></td>
            <td><input name="Nombre" type="text" id="Nombre" size="40"/></td>
        </tr>
        <tr>
            <td align="right" class="texto">Sigla :</td>
            <td><input name="Sigla" type="text" id="Sigla" size="40"/>
            <input type="hidden" name="Ben_ID" id="Ben_ID"/>
            </td>
        </tr>
    </table>
</div> 
<div id="listado" class="page-break">
</div>

<div id="mostrar"></div>
