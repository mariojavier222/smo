<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();
?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />

<script language="javascript">
$(document).ready(function(){
	$(".botones").button();
	$("#Ent_Fecha").datepicker();
	
	
	$("#botonGuardarEntrevista").click(function(){

	PerID=$("#PerID").val();
	Ent_Turno=$("#Ent_Turno").val();
	Ent_Fecha=$("#Ent_Fecha").val();
	Ent_Hora=$("#Ent_Hora").val();
	Ent_Sic_ID=$("#Ent_Sic_ID").val();
	Ent_Asistio=$("#Ent_Asistio").val();
	Ent_Estado=$("#Ent_Estado").val();
	
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion:"guardarEntrevista",PerID:PerID,Ent_Turno:Ent_Turno,Ent_Fecha:Ent_Fecha,Ent_Hora:Ent_Hora,Ent_Sic_ID:Ent_Sic_ID,Ent_Asistio:Ent_Asistio,Ent_Estado:Ent_Estado},
					url: 'cargarOpciones.php',
					success: function(data){ 
					jAlert("Guardado Correctamente","Datos Entrevista");
					$("#mostrarEntrevista").html(data);
					
					}
		});//fin ajax///
	
	})
	
})
</script>

<?php
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$UsuID = $_POST['UsuID'];
$PerID = $_POST['PerID'];

$sql="SELECT *
FROM
    entrevista
    INNER JOIN persona 
        ON (Ent_per_ID = Per_ID)
    INNER JOIN sicopedagoga 
        ON (Ent_Sic_ID = Sic_ID) WHERE Ent_per_ID='$PerID';";
		//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if(mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_array($result);
		$titulo="Editar Entrevista";
		?>
       


<script language="javascript">

$("#Ent_Turno").val('<?php echo $row[Ent_Turno] ?>');
$("#Ent_Fecha").val('<?php echo cfecha($row[Ent_Fecha]) ?>');
$("#Ent_Hora").val('<?php echo $row[Ent_Hora] ?>');
$("#Ent_Sic_ID").val('<?php echo $row[Ent_Sic_ID] ?>');
$("#Ent_Asistio").val('<?php echo $row[Ent_Asistio] ?>');
$("#Ent_Estado").val('<?php echo $row[Ent_Estado] ?>');

</script>
<?php
}
else
{
$titulo="Nueva Entrevista";
}
?>




 
<table width="80%" border="0" align="center" class="borde_recuadro">
  <tr>
                <td colspan="2" valign="middle" height="60px"><div align="center" class="titulo_noticia"> <?php echo $titulo ?>
                  
                  <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
                  
                </div></td>
            </tr>
<tr>
<td align="right">    <label for="textfield">Turno: </label></td>  
<td>    <select id="Ent_Turno" name="Ent_Turno">
        <?php
           echo "<option value='Vespertino'>Vespertino</option> ";
           echo "<option value='Matutino'>Matutino</option> ";
        ?>
      </select></td>
      </tr>
   
      
    <tr>
   <td align="right">
     
    
      <label for="textfield">Fecha de la Entrevista: </label>
      </td>
      <td>
      <input name="Ent_Fecha" type="input" id="Ent_Fecha"  class="validate[required]" value="">
      </td>
      </tr>

     <tr><td align="right">
      <label for="textfield">Hora de la Entrevista: </label>
      </td>
      <td>
      <input name="Ent_Hora" type="time" id="Ent_Hora"  class="validate[required]" value="">
      </td>
      </tr>

     <tr><td align="right">
      <label for="textfield">Sicopedagoga: </label>
      </td>
      <td>
      <?php
          cargarListaSicopedagogas("Ent_Sic_ID");
        ?>
      </td>
</tr>
       <tr>
       <td align="right">
      <label for="textfield">Asistio: </label>
      </td>
      <td>
      <select id="Ent_Asistio" name="Ent_Asistio">
                        <?php
                        echo "<option value='0'>NO</option> ";
                        echo "<option value='1'>SI</option> ";
                        ?>
                    </select>
     </td></tr>
      <tr>
      <td align="right">
      <label for="textfield">Estado: </label>
      </td>
      <td>
       <select id="Ent_Estado" name="Ent_Estado">
                        <?php
                        echo "<option value='0'>NO</option> ";
                        echo "<option value='1'>SI</option> ";
                        ?>
       </select>
     </td></tr>
     <tr>
     <td colspan="2" align="center" height="30px;">

     
      <button id="botonGuardarEntrevista" class="botones">Guardar Datos</button>
  </td></tr>
  </table>
 
 <p>&nbsp;</p>

<div id="mostrarEntrevista"></div>
