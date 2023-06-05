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
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
$(document).ready(function(){
	
	$(".botones").button();
	
	  $("#barraGuardarRequisitos").click(function(evento){
            evento.preventDefault();
            vNivID = $("#NivID").val();
            vPerID = $("#PerID").val();	
			vLecID = $("#LecID").val();	
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                data: {opcion: 'eliminarRequisitoPersona', NivID: vNivID, PerID: vPerID, LecID: vLecID},
                url: 'cargarOpciones.php'
            });//fin ajax//*/
            $("input[id^='Defin']:checked").each(function () {
                var i = this.id.substr(5,10);
                vValor = 1;
                /*if ($('#Defin' + i + ':checked').val() !== null) {
                                vValor = 0;
                        }//*/
                vReqID = $("#Defin" + i).val();
				vFechaPresentada = $("#FechaPresentada" + i).val();
				vUsuID = $("#UsuID" + i).val();
                //alert(vReqID);			
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "guardarRequisitoPersona", ReqID: vReqID, NivID: vNivID, PerID: vPerID, Campo: "Pre_Presento", Valor: vValor,  LecID: vLecID, FechaPresentada: vFechaPresentada, UsuID: vUsuID},
                    success: function (data){
                        $("#cargando").hide();
                    }
                });//fin ajax///
            });//*/
            $("input[id^='Const']:checked").each(function () {
                var i = this.id.substr(5,10);
                vReqID = $("#Const" + i).val();
                vValor = 1;
                /*if ($('#Const' + i + ':checked').val() !== null) {
                                vValor = 0;
                        }//*/

                //alert(vReqID);			
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "guardarRequisitoPersona", ReqID: vReqID, NivID: vNivID, PerID: vPerID, Campo: "Pre_Constancia", Valor: vValor, LecID: vLecID, FechaPresentada: vFechaPresentada, UsuID: vUsuID},
                    success: function (data){
                        $("#cargando").hide();
                    }
                });//fin ajax//*/
            });

            jAlert("Se guardaron correctamente los cambios.", "Confirmación");
            
        });
	
})
</script>
<?php
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$PerID = $_POST['PerID'];
//Obtener_LectivoActual($Lec_ID,$nombre);
//$Lec_ID = gLectivoActual($nombre);

//$sql = "SELECT ";

$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
	INNER JOIN Lectivo
		ON (Ins_Lec_ID = Lec_ID)
WHERE (Leg_Per_ID =$PerID) ORDER BY Ins_Lec_ID DESC;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)==0){
	echo "La persona no ha sido inscripto al ciclo lectivo todavía o existe un problema en la configuración del ciclo lectivo";
	exit;
}
$row = mysqli_fetch_array($result);
$NivID=$row[Ins_Niv_ID];
$Lec_ID=$row[Ins_Lec_ID];
$Lec_Nombre = $row[Lec_Nombre];
//echo $sql;
//return false;



$sql = "SELECT * FROM Requisito
    INNER JOIN Colegio_Nivel 
        ON (Requisito.Req_Niv_ID = Colegio_Nivel.Niv_ID) WHERE Niv_ID = '$NivID'";
//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>

<div id="listado">	

           
<input type="hidden" name="NivID" id="NivID" value="<?php echo $NivID ?>">
<input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>">
<input type="hidden" name="LecID" id="LecID" value="<?php echo $Lec_ID ?>">
<br>
<span class="titulo_noticia">Requisitos para el Ciclo Lectivo <?php echo $Lec_Nombre;?></span>
 <table width="100%" border="0" align="center" class="borde_recuadro">
    <tr>
      <td align="center" class="fila_titulo"><div align="left">Definitivo</div></td>
      <td class="fila_titulo"><div align="left">Requisito</div></td>
      <td align="center" class="fila_titulo">Constancia</td>
      <td align="center" class="fila_titulo">Fecha presentaci&oacute;n</td>
      <td align="center" class="fila_titulo">Usuario</td>
    </tr>
	<?php $i=0;
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		$sql = "SELECT * FROM RequisitoPresentado WHERE Pre_Niv_ID = '$NivID' AND Pre_Per_ID = '$PerID' AND Pre_Req_ID = '$row[Req_ID]' AND Pre_Lec_ID = $Lec_ID";
//echo $sql."<br>";
		$result_pre = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$total_pre = mysqli_num_rows($result_pre);
		$Const = 0;
		$Defin = 0;
		$Fecha = "---";
		$valorConst = "";
		$valorDefin = "";
		$Usuario = "";
		if ($total_pre > 0){
			$row_pre = mysqli_fetch_array($result_pre);
			$Const = $row_pre[Pre_Constancia];
			$Defin = $row_pre[Pre_Presento];
			$Fecha = cfecha($row_pre[Pre_Fecha]);
			$Usuario = gbuscarPersonaUsuID($row_pre[Pre_Usu_ID]);
			
		}
		if ($Const >0) $valorConst = "checked='checked'";
		if ($Defin >0) $valorDefin = "checked='checked'";
		

		
	?>
	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>">
      <td align="center"><input type="checkbox" id="Defin<?php echo $i;?>" <?php echo $valorDefin;?> value="<?php echo $row[Req_ID];?>"/></td>
      <td><?php echo $row[Req_Nombre];?></td>
      
      <td align="center">
      	<?php if ($row[Req_Constancia] > 0){?>
      	<input type="checkbox" id="Const<?php echo $i;?>" <?php echo $valorConst;?> value="<?php echo $row[Req_ID];?>"/>
        <?php }else{
			echo "---";
			}?>
        </td>
      <td align="center"><?php echo $Fecha;?>
      <input name="FechaPresentada<?php echo $i;?>" type="hidden" id="FechaPresentada<?php echo $i;?>" value="<?php echo $Fecha;?>" />
      <input name="UsuID<?php echo $i;?>" type="hidden" id="UsuID<?php echo $i;?>" value="<?php echo $row_pre[Pre_Usu_ID];?>" />
      </td>
      <td align="center"><?php
      echo $Usuario;
	  ?></td>
    </tr>
		  <?php		  
	}//fin del while
	?>  
</table>

</div>
<fieldset class="recuadro_inferior">
  <?php echo "Se econtraron $total requisitos de ingreso";?>
</fieldset>
<br>
<div align="right"><button id="barraGuardarRequisitos" class="botones">Guardar Requisitos</button></div>