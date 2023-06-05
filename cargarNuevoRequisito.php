<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
include_once("guardarAccesoOpcion.php");

?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){

		
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
	});
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vRequisito = $("#requisito").val();
			vNivID = $("#NivID").val();
			vObligatorio = $("#Obligatorio").val();
			vConstancia = $("#Constancia").val();
			vInscripcion = $("#Inscripcion").val();
			if (vNivID==-1){
				mostrarAlerta("Por favor seleccione un Nivel","Error");
				return;
			}
			if (vRequisito==""){
				mostrarAlerta("Por favor escriba un requisito","Error");
				return;
			}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarRequisitos", Requisito: vRequisito, NivID: vNivID, Obligatorio: vObligatorio, Constancia: vConstancia, Inscripcion: vInscripcion},
				success: function (data){
						//$("#mostrar").html(data);
						mostrarAlerta("Se guardó el requisito <strong>" + data + "</strong> correctamente");
						$("#cargando").hide();
						}
			});//fin ajax
		}else{
			jAlert("Antes de guardar, haga click en el botón <strong>Nuevo</strong> primero","Alerta");
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
		recargarPagina()
	});
	$("input[id^='Editar']").keyup(function(evento){
	 	if (evento.keyCode == 13){
			var i = this.id.substr(6,10);
			guardarNombre(i);
		}
	 });

	 $("a[id^='botGuardar']").click(function(evento){
		evento.preventDefault();
		var i = this.id.substr(10,10);
		//alert(i);
		guardarNombre(i);
	 });
	function guardarNombre(i){
		var vReq = $("#ReqID" + i).val();
		var vNiv = $("#NivID" + i).val();
		var vNombreReq = $("#Editar" + i).val();
		var vNombreOblig = $("#Oblig" + i + " option:selected").text();
		var vNombreConst = $("#Const" + i + " option:selected").text();
		var vNombreInsc = $("#Insc" + i + " option:selected").text();
		var vOblig = $("#Oblig" + i).val();
		var vConst = $("#Const" + i).val();
		var vInsc = $("#Insc" + i).val();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: { opcion: "guardarRequisitos", ReqID: vReq, Requisito: vNombreReq, NivID: vNiv, Obligatorio: vOblig, Constancia: vConst,  Inscripcion: vInsc },
			url: "cargarOpciones.php",
			success: function (data){
//					recargarPagina();
					$("#NombreReq" + i).text(vNombreReq);
					//$("#NombreReq" + i).text(data);
					$("#NombreOblig" + i).text(vNombreOblig);
					$("#NombreConst" + i).text(vNombreConst);
					$("#NombreInsc" + i).text(vNombreInsc);//
					$("#cargando").hide();//*/
					}
		});//fin ajax
		$(".mostrar" + i).show();
		$(".ocultar" + i).hide();
	 
	 }//fin funcion

	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		$("#NombreReq" + i).hide();
		$("#NombreOblig" + i).hide();
		$("#NombreConst" + i).hide();
		$("#NombreInsc" + i).hide();
		$("#Editar" + i).val($("#NombreReq" + i).text());
		$("#Editar" + i).show();
		if ($("#NombreOblig" + i).text()=="SI") $("#Oblig" + i).val(1); else $("#Oblig" + i).val(0);		
		$("#Oblig" + i).show();
		if ($("#NombreConst" + i).text()=="SI") $("#Const" + i).val(1); else $("#Const" + i).val(0);		
		$("#Const" + i).show();
		if ($("#NombreInsc" + i).text()=="SI") $("#Insc" + i).val(1); else $("#Insc" + i).val(0);
		$("#Insc" + i).show();
		$("#Editar" + i).focus();
	 });//fin evento click//*/	 
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		vNombre = $("#NombreReq" + i).text();
		vID = $("#ReqID" + i).val();
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarRequisito", ID: vID }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm//*/
	
	 });//fin evento click//*/	 
});//fin de la funcion ready


</script>

<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo"> <img src="botones/Add.png" alt="Ingresar un País Nuevo" width="48" height="48" border="0" title="Ingresar un País Nuevo" /><br />Nuevo</button> </td>
        <td width="48"><button class="barra_boton"  id="barraBuscar"> <img src="botones/Search.png" alt="Buscar Paises" width="48" height="48" border="0" title="Buscar Paises" /><br />
      Listar</button></td>
          <td width="48"><button class="barra_boton"  id="barraGuardar">  <img src="botones/guardar.png" alt="Guardar nuevo pais" width="48" height="48" border="0" title="Guardar nuevo pais" /><br />Guardar</button></td>
      </tr>
</table>
	
	<div id="mostrarNuevo">
	<table width="80%" border="0" align="center" class="borde_recuadro">
      
      <tr>
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nuevo  Requisito de ingreso</div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre del Requisito:</div></td>
          <td><input name="requisito" type="text" id="requisito" size="40"/></td>
        </tr><tr>
          <td align="right" class="texto">Nivel:</td>
          <td><?php cargarListaNivel("NivID");?></td>
      </tr>
        <tr>
          <td align="right" class="texto">&iquest;Es obligatorio?</td>
          <td><select name="Obligatorio" id="Obligatorio">
            <option value="1">Si</option>
            <option value="0">No</option>
          </select></td>
      </tr>
        <tr>
          <td align="right" class="texto">&iquest;Acepta constancia?</td>
          <td><select name="Constancia" id="Constancia">
            <option value="1">Si</option>
            <option value="0">No</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" class="texto">&iquest;Se utiliza en la inscripci&oacute;n?</td>
          <td><select name="Inscripcion" id="Inscripcion">
            <option value="1">Si</option>
            <option value="0">No</option>
          </select></td>
      </tr>
      
      <tr>
        <td colspan="2" class="texto"></td>
      </tr>
    </table>

</div>
	<div id="divBuscador">
      
        <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="imagenes/table_edit_req.png" width="32" height="32" align="absmiddle" /> Listado de Requisitos cargados</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            <?php
            $sql = "SELECT * FROM Requisito
    INNER JOIN Colegio_Nivel 
        ON (Requisito.Req_Niv_ID = Colegio_Nivel.Niv_ID)";
		if ($_SESSION['sesion_UniID']==3)
			$sql .= " AND Niv_ID = 4";
		else
			$sql .= " AND Niv_ID < 4";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">Nivel</th>
                  <th align="center">Requisito</th>
                  <th align="center">Obligat.</th>
                  <th align="center">Const.</th>
                  <th align="center">Inscrip.</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td><?php echo $row[Niv_Nombre];?>
                  <input type="hidden" id="NivID<?php echo $i;?>" value="<?php echo $row[Req_Niv_ID];?>" />
                  <input type="hidden" id="ReqID<?php echo $i;?>" value="<?php echo $row[Req_ID];?>" /></td>
                  <td><span id="NombreReq<?php echo $i;?>" title="Haga click para modificar el nombre" alt="Haga click para modificar el nombre" class="mostrar<?php echo $i;?>"><?php echo $row[Req_Nombre];?></span>
                  <input type="text" class="ocultar<?php echo $i;?>" id="Editar<?php echo $i;?>" value="<?php echo $row[Req_Nombre];?>" size="50" /></td>
                  <td align="center"><span class="mostrar<?php echo $i;?>" id="NombreOblig<?php echo $i;?>"><?php if ($row[Req_Obligatorio]==1) echo "SI"; else echo "NO";?></span>
                    <select id="Oblig<?php echo $i;?>"  class="ocultar<?php echo $i;?>">
                      <option value="1">SI</option>
                      <option value="0">NO</option>
                  </select></td>
                  <td align="center"><span class="mostrar<?php echo $i;?>" id="NombreConst<?php echo $i;?>"><?php if ($row[Req_Constancia]==1) echo "SI"; else echo "NO";?></span>
                    <select id="Const<?php echo $i;?>"  class="ocultar<?php echo $i;?>">
                      <option value="1">SI</option>
                      <option value="0">NO</option>
                  </select></td>
                  <td align="center"><span class="mostrar<?php echo $i;?>" id="NombreInsc<?php echo $i;?>"><?php if ($row[Req_Inscripcion]==1) echo "SI"; else echo "NO";?></span>
                    <select id="Insc<?php echo $i;?>" class="ocultar<?php echo $i;?>">
                      <option value="1">SI</option>
                      <option value="0">NO</option>
                  </select></td>
                  <td align="center"><a href="#" id="botGuardar<?php echo $i;?>"><img src="imagenes/page_save.png" alt="Guardar los cambios" title="Guardar los cambios" width="32" height="32" border="0" /></a> <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a> <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a></td>
                </tr>
              
              <?php
				}//fin while
			  ?>
            </tbody>
            </table>
            
            <?php
		}else{
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
	