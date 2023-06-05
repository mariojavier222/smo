<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript">
$(document).ready(function(){
	$("#barraGuadarRelacion").click(function(evento){
		evento.preventDefault();
		vEntID = $("#EntID").val();
		vNivID = $("#NivID").val();
		vPaiID = $("#PaisID").val();
		vProID = $("#ProID").val();
		vLocID = $("#LocID").val();
		if ( (vEntID <= 1) || (vNivID <= 1) ){
			jAlert("Debe seleccionar una <strong>Entidad Educativa</strong> y un <strong>Nivel de Estudio</strong> antes de guardar", "Atención");
		}else{
			$.post("cargarOpciones.php", { opcion: 'guardarEstudio', EntID: vEntID,  NivID: vNivID, PaiID: vPaiID, ProID: vProID, LocID: vLocID},	function(data){
				if (data=="Ya existe")
					jAlert("La <strong>Entidad Educativa</strong> y el <strong>Nivel de Estudio</strong> ya se encuentran relacionados", "Error");
				else{
     				$("#mostrarResultado").html(data);
					llenarNiveles(vEntID);
				}
   			});
		}//fin if
	});
   //---------------------------Llenamos la entidad y los niveles de estudio
    $("#EntID").change(function () {
   		$("#EntID option:selected").each(function () {
			//alert($(this).val());
				vEntID=$(this).val();
				llenarNiveles(vEntID);
        });
   	})
	$("#EntIDRel").change(function () {
   		$("#EntIDRel option:selected").each(function () {
			//alert($(this).val());
				vEntID=$("#EntID").val();
				vNivID = $("#EntIDRel").val();
				llenarPaiProLoc(vEntID, vNivID);
        });
   	})	
	function llenarNiveles(vID){
//		alert(vID);
		$.post("cargarOpciones.php", { opcion: 'llenarNiveles', ID: vID }, function(data){
				//alert(data);
     			$("#EntIDRel").html(data);

				vEntID=$("#EntID").val();
				vNivID = $("#EntIDRel").val();
				llenarPaiProLoc(vEntID, vNivID);
   		});
	}//fin funcion
   //---------------------------	
   //---------------------------Llenamos los paises, provincias y localidades de las entidades
      $("#PaisID").change(function () {
   		$("#PaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia(vPais);
        });
   })
   	// Parametros para el combo2
	$("#ProID").change(function () {
   		$("#ProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#PaisID").val();
			llenarLocalidad(vProv, vPais);
        });
   })
	gPaiID = 0;
	gProID = 0;
	gLocID = 0;//*/
	function llenarPaiProLoc(vEntID, vNivID){
		$.post("cargarOpciones.php", { opcion: 'buscarEntPaiProLoc', EntID: vEntID, NivID: vNivID, Buscar: 'Pai' },	function(data){
			gPaiID = data;
			$.post("cargarOpciones.php", { opcion: 'buscarEntPaiProLoc', EntID: vEntID, NivID: vNivID, Buscar: 'Pro' },	function(data){
     			gProID = data;
				$.post("cargarOpciones.php", { opcion: 'buscarEntPaiProLoc', EntID: vEntID, NivID: vNivID, Buscar: 'Loc' },	function(data){
     				gLocID = data;
					$("#PaisID").attr("value",gPaiID);
					llenarProvincia(gPaiID, gProID);
					llenarLocalidad(gProID, gPaiID, gLocID);		
  		 		});
   			});
   		});
		
		
		//alert(vPaiID + " - " + vProID + " - " + vLocID)
		
	}
	function llenarLocalidad(vProv, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },		function(data){
     			$("#LocID").html(data);
				if (vOpcion) $("#LocID").attr("value",vOpcion);
   		});
	}
	function llenarProvincia(vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarProvincia', Pais: vPais },function(data){
     			$("#ProID").html(data);
					vProv = $("#ProID").val();					
					if (vOpcion){
						//alert(vOpcion);
						$("#ProID").attr("value",vOpcion);
					}else{
						llenarLocalidad(vProv, vPais);
					}

   			});
	}
   //---------------------------
	//Valores iniciales
	$(".error_buscador").hide();
	$("#PaisID").attr("value","0");
	llenarProvincia(0);
	llenarLocalidad(0, 0);
	//Fin valores iniciales
   
});//fin de la funcion ready


</script>
<div id="mostrarNuevo">	
	<table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td colspan="4"><div align="center" class="titulo_noticia">Relacionar Entidades Educativas con Nivel de Estudios </div></td>
      </tr>
	  <tr>
	  <td class="texto"><div align="right">Entidad Educativa  :</div></td>
          <td><?php cargarListaEntidadEducativa("EntID");    ?>            </td>
          <td class="borde_azul_derecha_sup">&nbsp;</td>
          <td>&nbsp;</td>
	  </tr>
	  <tr>
	  <td class="texto"><div align="right">Nivel de Estudio relacionados :</div></td>
          <td><select name='EntIDRel' id='EntIDRel'><option value='-1'>NO HAY NIVELES CARGADOS</option></select>
            <label class="error_buscador" id="listaNivel_error"></label></td>
			<td class="borde_azul_derecha">&nbsp;</td>
			<td>&nbsp;</td>
	  </tr>

	  <tr>
	  <td class="texto"><div align="right">Nivel de Estudio disponibles :</div></td>
          <td><?php cargarListaNivelEstudio("NivID");    ?>            </td>
			<td class="borde_azul_derecha_inf">&nbsp;</td>
			<td>&nbsp;</td>
	  </tr>
      
      <tr>
        <td colspan="4" class="texto"><div align="center">
        </div></td>
      </tr>
	        <tr>
        <td colspan="4" align="center"><div align="center" class="barra_boton" id="guardarRelacion" style="width:100px"><a href="#" id="barraGuadarRelacion">  <img src="botones/guardar_estudio.png" alt="Guardar la relación" width="48" height="48" border="0" title="Guardar la relación" /> <br /> 
          Guardar Relación </a>		  
		 </div></td>
      </tr>
	  <tr>
	  <td colspan="4" class="texto"><div align="center"><strong>Procedencia de la Entidad y el título: Opcional </strong></div></td>
      </tr>
	  <tr>
	  <td class="texto"><div align="right">Pa&iacute;s :</div></td>
          <td><?php cargarListaPais("PaisID");?>            </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
	  </tr>
        <tr>
          <td class="texto"><div align="right">Provincia: </div></td>
          <td><select name="ProID" id="ProID"><option value="-1">NO HAY PROVINCIAS</option></select></td>		 
			<td>&nbsp;</td>
			<td>&nbsp;</td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Localidad: </div></td>
          <td><select name="LocID" id="LocID"><option value="-1">NO HAY LOCALIDADES</option></select>	</td>		 
  			<td>&nbsp;</td>
			<td>&nbsp;</td>
        </tr>
    </table>
	</div>	
<div id="mostrarResultado"></div>	