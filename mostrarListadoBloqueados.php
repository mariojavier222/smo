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

		//alert("");
	$('table.tabla tbody tr:odd').addClass('fila');

 	$('table.tabla tbody tr:even').addClass('fila2');
	
	
	//$(".ocultar").hide();
	$("input[class^='ocultar']").hide();
	$("select[class^='ocultar']").hide();
	
	
	
	function recargarPagina(){
		$("#mostrar").empty();

		$.ajax({
			cache: false,
			async: false,			
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
					$("#mostrar").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	}//fin function
	

	 $("a[id^='botGuardar']").click(function(evento){
		evento.preventDefault();
		var i = this.id.substr(10,10);
		//alert(i);
		PerID = $("#PerID" + i).val();
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: { opcion: "levantarBloqueo", PerID: PerID },
			url: "cargarOpciones.php",
			success: function (data){
					jAlert(data, "Resultado de la operación");
					recargarPagina();					
					$("#cargando").hide();//*/
					}
		});//fin ajax
	 });
		 
});//fin de la funcion ready


</script>

<div id="divBuscador">
      
    <table width="98%" border="0" align="center" class="borde_recuadro">
          <tr>
            <td><div align="center" class="titulo_noticia"><img src="imagenes/borrar_activo.png" width="32" height="32" align="absmiddle" /> Listado de Personas Bloqueadas</div></td>
          </tr>
          <tr>
            <td align="center" class="texto">
            <?php
			$sql = "SET NAMES UTF8;";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            $sql = "SELECT * FROM Bloqueo
    INNER JOIN BloqueoTipo 
        ON (Blo_BTi_ID = BTi_ID)
    INNER JOIN Persona 
        ON (Blo_Per_ID = Per_ID)
	INNER JOIN Usuario
		ON (Usu_ID = Blo_Usu_ID);";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			?>
            <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="ui-widget-header">
                  <th align="center">#</th>
                  <th align="center">Persona</th>
                  <th align="center">Motivo</th>
                  <th align="center">Tipo</th>
                  <th align="center">Acci&oacute;n</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row=mysqli_fetch_array($result)){
					$i++;
				?>
                <tr>
                  <td><?php echo $row[Per_DNI];?>
                  <input type="hidden" id="PerID<?php echo $i;?>" value="<?php echo $row[Per_ID];?>" />
                 </td>
                  <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
                  <td align="left" title="<?php echo "$row[Usu_Nombre]:".cfecha($row[Blo_Fecha])." $row[Blo_Hora]";?>"><?php echo "$row[Blo_Motivo]";?></td>
                  <td align="left"><?php echo "$row[BTi_Nombre]";?></td>
                  <td align="center"><a href="#" id="botGuardar<?php echo $i;?>"><img src="imagenes/ins_definitiva.png" alt="Restaurar Bloqueo" title="Restaurar Bloqueo" width="32" height="32" border="0" /></a> </td>
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
	