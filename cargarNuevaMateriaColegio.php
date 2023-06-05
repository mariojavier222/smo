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
		limpiarDatos();
	});
	function limpiarDatos(){
		$("#materia").val("");
		$("#Siglas").val("");
		$("#materia").focus();
	}
	$("#barraGuardar").click(function(evento){
		evento.preventDefault();
		

		if ($('#mostrarNuevo').is (':visible') && $('#mostrarNuevo').parents (':hidden').length == 0){			
			$("#mostrar").empty();
			vMateria = $("#materia").val();
			vSiglas = $("#Siglas").val();
			OriID = $("#OriID").val();
			Mat_Convivencia = $("#Mat_Convivencia").val();
			Mat_Curricular = $("#Mat_Curricular").val();
			if (vSiglas==-1){
				jAlert("Por favor seleccione una Sigla","Error");
				return;
			}
			if (vMateria==""){
				jAlert("Por favor escriba una nueva Materia","Error");
				return;
			}
			if (OriID==-1){
				jAlert("Por favor seleccione una Orientación","Error");
				return;
			}
			$.ajax({
				type: "POST",
				cache: false,
				async: false,			
				url: 'cargarOpciones.php',
				data: {opcion: "guardarMateriaColegio", Materia: vMateria, Siglas: vSiglas, OriID: OriID, Mat_Convivencia: Mat_Convivencia, Mat_Curricular: Mat_Curricular},
				success: function (data){
						if (data=="Ya existe"){
							jAlert("Por favor elija otro nombre de Materia porque la Materia <strong>" + vMateria + " ingresada para esa orientación</strong> ya existe.", "Error");
						}else{
							jAlert("Se guardó la materia <strong>" + data + "</strong> correctamente.", "Resultado de la operación");
							
						}
						$("#cargando").hide();
						
					}
			});//fin ajax
			limpiarDatos();
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
		var vMat = $("#MatID" + i).val();
		var vSig = $("#SigOculto" + i).val();
		var vOriID = $("#OriID" + i).val();
		var vNombreMat = $("#Editar" + i).val();
		var vNomConvivencia = $("#Convivencia" + i + " option:selected").text();
		var vNomCurricular = $("#Curricular" + i + " option:selected").text();
		var vConvivencia = $("#Convivencia" + i).val();
		var vCurricular = $("#Curricular" + i).val();
		//var vNombreSig = vSig;//$("#NombreSig" + i + " option:selected").text();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: { opcion: "guardarMateriaColegio", MatID: vMat, Materia: vNombreMat, Siglas: vSig, OriID: vOriID, Mat_Convivencia: vConvivencia, Mat_Curricular: vCurricular },
			url: "cargarOpciones.php",
			success: function (data){
//					recargarPagina();
//					alert(data);
					$("#NombreMat" + i).text(vNombreMat);
					//$("#NombreMat" + i).text(data);
					$("#NombreSig" + i).text(vSig);
					
					$("#NomConvivencia" + i).text(vNomConvivencia);
					$("#NomCurricular" + i).text(vNomCurricular);
					$("#cargando").hide();//*/
					}
		});//fin ajax
		$(".mostrar" + i).show();
		$(".ocultar" + i).hide();
	 
	 }//fin funcion

	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);	
		$(".mostrar" + i).hide();	
		$("#NombreMat" + i).hide();
		$("#NombreSig" + i).hide();
		$("#Editar" + i).val($("#NombreMat" + i).text());
		$("#Editar" + i).show();
		var vSigla = $("#SigOculto" + i).val();
		$("#SigOculto" + i).val($("#NombreSig" + i).text());
		$("#SigOculto" + i).show();
		
		if ($("#NomConvivencia" + i).text()=="SI") $("#Convivencia" + i).val(1); else $("#Convivencia" + i).val(0);		
		$("#Convivencia" + i).show();
		if ($("#NomCurricular" + i).text()=="SI") $("#Curricular" + i).val(1); else $("#Curricular" + i).val(0);		
		$("#Curricular" + i).show();
		
		$("#Editar" + i).focus();
	 });//fin evento click//*/	 
	$("a[id^='botBorrar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);
		vNombre = $("#NombreMat" + i).text();
		vID = $("#MatID" + i).val();
		jConfirm('¿Está seguro que desea eliminar la materia <strong>' + vNombre + '</strong>?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminarMateriaColegio", ID: vID }, function(data){
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
        <td colspan="2" valign="middle"><div align="center" class="titulo_noticia"><img src="imagenes/application_form_edit.png" alt="Paises" width="32" height="32" align="absmiddle" /> Cargar Nueva Materia para el Colegio</div></td>
      </tr>
	  <td class="texto"><div align="right">Nombre de la Materia:</div></td>
          <td><input name="materia" type="text" id="materia" size="40"/></td>
        </tr><tr>
          <td align="right" class="texto">Siglas:</td>
          <td><input type="text" name="Siglas" id="Siglas" /></td>
      </tr>
        <tr>
          <td align="right" class="texto">Orientaci&oacute;n:</td>
          <td><?php cargarListaOrientacion("OriID");?></td>
        </tr>
        <tr>
          <td align="right" class="texto">Materia Convivencia:</td>
          <td><select name="Mat_Convivencia" id="Mat_Convivencia">
            <option value="1">Si</option>
            <option selected="selected" value="0">No</option>
          </select></td>
        </tr>
        <tr>
          <td align="right" class="texto">Materia Curricular:</td>
          <td><select name="Mat_Curricular" id="Mat_Curricular">
            <option value="1">Si</option>
            <option selected="selected" value="0">No</option>
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
            <td><div align="center" class="titulo_noticia"><img src="imagenes/report.png" width="32" height="32" align="absmiddle" /> Listado de Materias cargadas</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            <?php
			
			$sql = "SET NAMES UTF8;";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $sql = "SELECT * FROM Colegio_Materia
    INNER JOIN Colegio_Orientacion 
        ON (Mat_Ori_ID = Ori_ID) ORDER BY Ori_ID, Mat_ID, Mat_Nombre";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">C&oacute;d</th>
                  <th align="center">Orientaci&oacute;n</th>
                  <th align="center">Materia</th>
                  <th align="center">Siglas</th>
                  <th align="center">Convivencia</th>
                  <th align="center">Curricular</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td><?php echo $row['Mat_ID'];?>
                  <input type="hidden" id="MatID<?php echo $i;?>" value="<?php echo $row['Mat_ID'];?>" />
                  <input type="hidden" id="OriID<?php echo $i;?>" value="<?php echo $row['Mat_Ori_ID'];?>" />
                  </td>
                  <td><?php echo $row['Ori_Nombre'];?></td>
                  <td><span id="NombreMat<?php echo $i;?>" title="Haga click en el botón EDITAR para modificar el nombre" alt="Haga click en el botón EDITAR para modificar el nombre" class="mostrar<?php echo $i;?>"><?php echo $row[Mat_Nombre];?></span>
                  <input type="text" class="ocultar<?php echo $i;?>" id="Editar<?php echo $i;?>" value="<?php echo $row[Mat_Nombre];?>" size="50" /></td>
                  <td align="center"><span class="mostrar<?php echo $i;?>" id="NombreSig<?php echo $i;?>"><?php echo $row[Mat_Siglas];?></span>                    
                    <input type="text" id="SigOculto<?php echo $i;?>" value="<?php echo $row[Mat_Siglas];?>" class="ocultar<?php echo $i;?>"/></td>
                  <td align="center"><span class="mostrar<?php echo $i;?>" id="NomConvivencia<?php echo $i;?>"><?php if ($row[Mat_Convivencia]==1) echo "SI"; else echo "NO";?></span>
                    <select id="Convivencia<?php echo $i;?>"  class="ocultar<?php echo $i;?>">
                      <option value="1">SI</option>
                      <option value="0">NO</option>
                  </select></td>
                  <td align="center"><span class="mostrar<?php echo $i;?>" id="NomCurricular<?php echo $i;?>"><?php if ($row[Mat_Curricular]==1) echo "SI"; else echo "NO";?></span>
                    <select id="Curricular<?php echo $i;?>"  class="ocultar<?php echo $i;?>">
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
	