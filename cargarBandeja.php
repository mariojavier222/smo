<?php
include_once("comprobar_sesion.php");
header("Cache-Control: no-cache, must-revalidate"); 
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");


?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="js/style.css" type="text/css" />-->
<!--<script type="text/javascript" src="js/tooltips.js"></script>-->

<script language="javascript">
$(document).ready(function(){
	
	vUsuID = <?php echo $_SESSION['sesion_UsuID'];?>;
	
	
	function leerMensaje(j){
		vMenID = $("#Mensaje" + j).val();
		$(this).attr("style", "cursor:pointer");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: 'mostrarMensajeUsuario', MenID: vMenID, Leido: true, UsuID: vUsuID},
			url: 'cargarOpciones.php',
			success: function(data){ 
				mostrarMensaje(data,"Leyendo mensaje...");
				$("#fila" + j).attr("style", "cursor:pointer");
				$("#icono" + j).hide();
			}
		});//fin ajax//*/
	}
	function mostrarMensaje(cuerpo, titulo){
		cuerpo = "<h3>" + titulo + "</h3><p>" + cuerpo + "</p>Presione ESC para salir";
		
		$("#cargando").show();
		$("#detalle").html(cuerpo);
		$("#detalle").bPopup();
		$("#cargando").hide();
					
	}//fin funcion//*/
	
	$("td[id^='Mensaje']").click(function(evento){
		evento.preventDefault();
		var iFila = this.id.substr(7,10);
		leerMensaje(iFila);
		
	});
	$("input[id^='Nuevo']").click(function(evento){
		//evento.preventDefault();
		i = this.id.substr(5,10);
		
		vTotalMensajes = parseInt($("#totalMensajes").val());
		if (this.checked){
			vTotalMensajes += 1;
		}else{
			vTotalMensajes -= 1;
		}
		$("#totalMensajes").val(vTotalMensajes);
		vClaseAnterior = $("#fila" + i).attr("class");
		if (vClaseAnterior=="fila" || vClaseAnterior=="fila2")
			$("#fila" + i).attr("class", "filaRemarcada");
		else
			if ((i % 2)==0) vClaseAnterior = "fila"; else vClaseAnterior = "fila2";
			//vClaseAnterior = "fila";
			//vClaseAnterior = "fila2";
			$("#fila" + i).attr("class", vClaseAnterior);
	 });//fin evento click//*/
	
	//marcar y desmarcar todo
	$("#marcar").click(function(evento){
		evento.preventDefault();		
		vTotal = 0;
		$( ":checkbox").attr('checked', 'checked');
		$("#totalMensajes").val($("#totalesMensajes").val());		
	});
	$("#desmarcar").click(function(evento){
		evento.preventDefault();
		$( ":checkbox").attr('checked', '');
		$("#totalMensajes").val(0);
		
	}); //*/
	
	$("#barraResponder").click(function(evento){
		evento.preventDefault();
		
	}); //*/
	
	$(".botones").button();
});//fin de la funcion ready


</script>

<div id="mostrar">
	<p>&nbsp;</p>
	
    <table width="80%" border="0" align="center" class="borde_recuadro">
      <tr>
        <td>		
		<div align="center" class="titulo_noticia"><img src="imagenes/comment_48.png" width="48" height="48" align="absmiddle" />Bandeja de Entrada de Mensajes</div></td>
      </tr>
	  <tr>
	    <td class="texto">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class="texto">
        <?php
		$sql = "SET NAMES UTF8";
  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM
    Colegio_MensajeDestino
    INNER JOIN Colegio_Mensaje 
        ON (Colegio_MensajeDestino.Des_Men_ID = Colegio_Mensaje.Men_ID)
    INNER JOIN Usuario 
        ON (Colegio_MensajeDestino.Des_De_Usu_ID = Usuario.Usu_ID)
WHERE (Des_Para_Usu_ID = '".$_SESSION['sesion_UsuID']."' AND Men_MTi_ID=1 AND Des_Borrado=0)
ORDER BY Colegio_MensajeDestino.Des_Fecha DESC, Colegio_MensajeDestino.Des_Hora DESC;";
//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)>0){//existe
		$total = mysqli_num_rows($result);
	?><fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Mensajes recibidos</legend>

        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
	      <tr class="fila_titulo">
	        <td>&nbsp;</td>
	        <td>Enviado por</td>
	        <td>Asunto</td>
	        <td>Enviado</td>
	        </tr>
	      <?php
		  	$i=0;
			$total = mysqli_num_rows($result);
          	while ($row = mysqli_fetch_array($result)){
				$i++;
				$estilo = "style='cursor:pointer";
			  	if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
				if ($row[Des_Leido]==0) $estilo .= "; font-weight:bold;'";
				if ($estilo=="style='cursor:pointer") $estilo.="'";
				$fechaHoy = date("Y-m-d");
				if ($fechaHoy==$row[Des_Fecha]) $fecha = "Hoy a las ".$row[Des_Hora]; else $fecha = cfecha($row[Des_Fecha]);
		  ?>
          <tr id="fila<?php echo $i;?>" class="<?php echo $clase;?>" <?php echo $estilo;?>>
	        <td height="32"><input type="checkbox" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $row[Des_ID];?>"><input type="hidden" id="Mensaje<?php echo $i;?>" name="Mensaje<?php echo $i;?>" value="<?php echo $row[Men_ID];?>">
	          <?php if ($row[Des_Leido]==0) {
				  ?>
				  <img id="icono<?php echo $i;?>" src="imagenes/comment.png" alt="Mensaje sin leer" width="32" height="32" align="absmiddle" title="Mensaje sin leer"/>
                  <?php }?>
                  
                  </td>
                  
	        <td id="Mensaje<?php echo $i;?>"><?php echo $row[Usu_Persona];?></td>
	        <td id="Mensaje<?php echo $i;?>"><?php echo $row[Men_Titulo];?></td>
	        <td ><?php echo $fecha;?></td>
	        </tr>
            <?php
			}//fin while
			?>
        </table>
        </fieldset>
        <fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<img src="imagenes/flechita_arriba.png" alt="Marcar/Desmarcar todo" width="38" height="22" border="0" align="absmiddle"/> <a href="#" id="marcar">Marcar</a> / <a href="#" id="desmarcar">Desmarcar todo</a> - <?php echo "Se econtraron $total mensajes";?></div>
<br /><br /></fieldset>
      <?php
	}//fin if
	else{
		echo "No se encontraron mensajes";
		}
		?>
        
        </td>
	  </tr>      
		<tr>
		  <td class="texto">  <input name="totalMensajes" type="hidden" id="totalMensajes" value="0" />
  <input name="totalesMensajes" type="hidden" id="totalesMensajes" value="<?php echo $total;?>" />
</td>
	  </tr>      

      <tr>
        <td align="left" class="texto"><button class="botones" id="barraResponder"><img src="imagenes/comment_edit.png" alt="Responder Mensaje" title="Responder Mensaje" width="32" height="32" border="0" align="absmiddle" /> Responder</button> <button class="botones" id="barraEliminar"><img src="imagenes/comment_delete.png" alt="Eliminar Mensajes" title="Eliminar Mensajes" width="32" height="32" border="0" align="absmiddle" /> Eliminar</button>   </td>
      </tr>
      <tr>
        <td align="center" class="texto">&nbsp;</td>
      </tr>
    </table>

	</div>
	<div class="borde_alerta" id="detalle"></div>	
	<p>&nbsp;</p>
	