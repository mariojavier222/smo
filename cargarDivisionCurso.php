<?php 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

?> 

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){

	function validarDatos(){
        vLecID = $("#LecID").val();
        vCurID = $("#CurID").val();
        vNivID = $("#NivID").val();
        vDivID = $("#DivID").val(); 
        vSedID = 1;

        if (vLecID==-1){
            jAlert("Debe seleccionar un Ciclo Lectivo", "ERROR");
            $("#LecID").focus();
            return false;
        }
		if (vNivID==-1){
            jAlert("Debe seleccionar un Nivel", "ERROR");
            $("#NivID").focus();
            return false;
        }        
        if (vCurID==-1){
            jAlert("Debe seleccionar un Curso", "ERROR");
            $("#CurID").focus();
            return false;
        }
        if (vDivID==-1){
			jAlert("Debe seleccionar una División", "ERROR");
            $("#DivID").focus();            
            return false;
        }
        return true;
    }

	$('table.tabla tbody tr:odd').addClass('fila');

 	$('table.tabla tbody tr:even').addClass('fila2');
	
	$("#mostrarNuevo").hide();
	$("#barraGuardar").hide();
	//$(".ocultar").hide();
	$("input[class^='ocultar']").hide();
	$("select[class^='ocultar']").hide();
	$("#mostrar").empty();
	$("#nombre").focus();
	
	$("#barraNuevo").click(function(evento){
		evento.preventDefault();				
		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		$("#barraGuardar").show();
		limpiarDatos();		
	});

	function limpiarDatos(){
		$("#ID").val("");
		$("#LecID").val("");
		$("#NivID").val("");
		$("#CurID").val("");
		$("#DivID").val("");
	}

	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		
		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			
			if (validarDatos()){
				$("#mostrar").empty();
				
				vID=$("#ID").val();
				vLecID = $("#LecID").val();
				vNivID = $("#NivID").val();
				vCurID = $("#CurID").val();
				vDivID = $("#DivID").val();
					
				$.ajax({
					type: "POST",
					async: false,			
					url: 'cargarOpcionesDivision.php',
					data: {opcion: "guardarDivisionCurso", ID:vID, LecID: vLecID, NivID: vNivID, CurID: vCurID, DivID: vDivID},
					success: function (data){
							mostrarAlerta(data, "Resultado de la operacion");
							recargarPagina();
							$("#cargando").hide();
						}
				});//fin ajax
				
			}
			
		}else{
			jAlert("Antes de guardar, haga click en el botón <strong>Nuevo</strong>","Alerta");
		}//fin if
	});	

	function recargarPagina(){
		$("#mostrar").empty();

		$.ajax({
			cache: false,
			async: false,			
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
					$("#principal").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	}//fin function

	$("#barraBuscar").click(function(evento){
		evento.preventDefault();
		$("#LecID2").click();
		recargarPagina()
	});

	$("a[id^='botEditar']").click(function(evento){			
	    $("#barraGuardar").show();								  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		$("#DivID").val($("#DivID" + i).val());
		$("#NivID").val($("#NivID" + i).val());
		$("#CurID").val($("#CurID" + i).val());
		$("#LecID").val($("#LecID" + i).val());
		$("#ID").val($("#ID" + i).val());

		$("#mostrarNuevo").fadeIn();
		$("#mostrar").empty();		
		$("#divBuscador").fadeOut();
		
	 });//fin evento click//*/	 

    $("a[id^='botBorrar']").click(function(evento){											  
        evento.preventDefault();
        var i = this.id.substr(9,10);
		vID = $("#ID" + i).val();

        jConfirm('¿Est&aacute; seguro que desea eliminar el registro?', 'Confirmar la eliminaci&oacute;n', function(r){
            if (r){//eligió eliminar
                $.post("cargarOpcionesDivision.php", { opcion: "eliminarDivisionCurso", ID:vID }, function(data){
                    jAlert(data, 'Resultado de la eliminación');
                    recargarPagina();
                });//fin post					
            }//fin if
        });//fin del confirm//*/
	
    });//fin evento click//*/

	function llenarCursoTurnoST(){
		NivID = $("#NivID").val();
		TurID = $("#Turno").val();
		$.ajax({
            type: "POST",
            cache: false,
            async: false,			
            url: 'cargarOpcionesDivision.php',
            data: {opcion: "llenarCursoTurnoST", NivID: NivID, TurID: TurID},
            success: function (data){
            	//alert(data);
                $("#CurID").html(data);
                $("#cargando").hide();
            }
        });//fin ajax
	}//fin function

	function llenarListaCursoDivision(){
		vLecID2 = $("#LecID2").val();
		$.ajax({
            type: "POST",
            cache: false,
            async: false,			
            url: 'cargarOpcionesDivision.php',
            data: {opcion: "llenarListaCursoDivision", LecID2: vLecID2},
            success: function (data){
            	//alert(data);
                $("#mostrar").html(data);
                $("#cargando").hide();
            }
        });//fin ajax
	}//fin function

	$("#NivID").change(function () {
		$("#NivID option:selected").each(function () {
			//validarCursoDivision();
			llenarCursoTurnoST();
		});
    });

	$("#NivID").click(function () {
		$("#NivID option:selected").each(function () {
			//validarCursoDivision();
			llenarCursoTurnoST();
		});
    });

	$("#LecID2").change(function () {
		$("#LecID2 option:selected").each(function () {
			llenarListaCursoDivision();
		});
    });

	$("#LecID2").click(function () {
		$("#LecID2 option:selected").each(function () {
			llenarListaCursoDivision();
		});
    });

	$("#LecID2").click();

});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Nueva Division" width="48" height="48" border="0" title="Nueva Division" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Division" width="48" height="48" border="0" title="Buscar Division" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar Division" width="48" height="48" border="0" title="Guardar Division" /><br />Guardar</button></td>
      </tr>
</table>
	
<div id="mostrarNuevo">
    <table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nueva Division</div></td>
      </tr>
	  <tr>
	    <td align="right" class="texto">&nbsp;</td>
	    <td><input type="hidden" name="ID" id="ID" /></td>
      </tr>
 	  <tr>
        <td width="50%" class="texto"><div align="right">Ciclo Lectivo:</div></td>
        <td><?php cargarListaLectivo("LecID");  ?> 
          <input name="Turno" id="Turno" value="999999" type="hidden" /></td>
      </tr>      
      <tr>
        <td class="texto"><div align="right">Nivel:</div></td>
        <td><?php cargarListaNivel("NivID", false); ?> </td>

      </tr>
      <tr>
        <td class="texto"><div align="right">Curso:</div></td>
        <td><?php cargarListaCursos("CurID", false); ?> </td>
      </tr>
      <tr>
        <td align="right" class="texto" width="30%">Division</td>
        <td><?php cargarListaDivision("DivID", false); ?> </td>
      </tr>
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>
</div>
<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
        <tr>
            <td><div align="center" class="titulo_noticia"> Listado de Divisiones</div></td>
        </tr>
        <tr>
        <td class="texto"><div align="center">Ciclo Lectivo: <?php cargarListaLectivo("LecID2");  ?></div></td>
          </tr>    
        <tr>
            <td align="center" class="texto">
            <div id="mostrar"></div>    
				
            </td>
        </tr>
    </table>
      
</div>

	<p>&nbsp;</p>
	