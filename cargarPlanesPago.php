<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

?>

<script language="javascript">
$.validator.setDefaults({
	submitHandler: function() { 
		
		if (tipo==1)
			datos = $("#formDatos").serialize();
		else
			datos = $("#formDatosAdelanto").serialize();
		//alert($("#opcion").val());
		//return;
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
				url: 'cargarOpciones.php',
			data: datos,
			success: function (data){
				jAlert(data, "Resultado de guardar los datos");				
			}
		});//fin ajax
	}
});
$(document).ready(function(){
	
	
  $("#formDatos").validate();
  $("#vistaPrevia").click(function(e){
	e.preventDefault();
	
	PerID=$('#PerID').val();
	CTi_ID=$('#CTi_ID').val();
	Alt_ID=$('#Alt_ID').val();
	CMo_CantCuotas=$('#CMo_CantCuotas').val();
	CMo_Importe=$('#CMo_Importe').val();
	CMo_Adelanto=$('#CMo_Adelanto').val();
	CMo_1er_Vencimiento=$('#CMo_1er_Vencimiento').val();
	CMo_Mes=$('#CMo_Mes').val();
	CMo_Anio=$('#CMo_Anio').val();
	CMo_Recargo_Mensual=$('#CMo_Recargo_Mensual').val();
	
	//VALIDACION
                
                if (CTi_ID==-1){
                    mostrarAlerta("Por favor elija una opción para el Tipo de Cuota","Error");
                    return;
                }
                if (Alt_ID==-1){
                    mostrarAlerta("Por favor elija una opción para la Alternativa","Error");
                    return;
                }
                if (CMo_Importe==""){
                    mostrarAlerta("Por favor escriba un importe valido","Error");
                    return;
                }
                if (CMo_1er_Vencimiento==""){
                    mostrarAlerta("Por favor elija una fecha para el primer vencimiento","Error");
                    return;
                }
                   
                if (CMo_Mes==""){
                    mostrarAlerta("Por favor elija un opción para el mes","Error");
                    return;
                }
                if (CMo_Anio==""){
                    mostrarAlerta("Por favor escriba un año valido","Error");
                    return;
                }
				
				 if (CMo_Recargo_Mensual==""){
                    mostrarAlerta("Por favor ingrese recargo mensual","Error");
                    return;
                }
	
	//FIN DE LA VALIDACION
	
	$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion:"mostrarVistaPreviaPlanPagos",PerID: PerID, CTi_ID:CTi_ID, Alt_ID:Alt_ID, CMo_CantCuotas:CMo_CantCuotas, CMo_Importe:CMo_Importe, CMo_Adelanto: CMo_Adelanto, CMo_1er_Vencimiento:CMo_1er_Vencimiento, CMo_Mes:CMo_Mes, CMo_Anio:CMo_Anio, CMo_Recargo_Mensual:CMo_Recargo_Mensual},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#mostrar22").html(data);
				
			}
		});//fin ajax//*/
	
	       
	
	});
	
			
<?php

  //Obtener_LectivoActual($LecID, $LecNombre);
  $LecID = gLectivoActual($LecNombre);
  obtenerLimitesLectivo($LecID, $LecDesde, $LecHasta);
  $LimiteFecha = "{minDate: '".cfecha($LecDesde)."', maxDate: '".cfecha($LecHasta)."'}";// */
?>

        
		$("#CMo_1er_Vencimiento").datepicker(<?php echo $LimiteFecha; ?>);
		
		
		
		$(".botones").button();
		
	//Inicializamos algunos valores de los input
	$.ajax({
		type: "POST",
		cache: false,
		async: false,
		error: function (XMLHttpRequest, textStatus){
			alert(textStatus);},
		data: {opcion: "buscarCursoDivision", PerID: $("#PerID").val()},
		url: 'cargarOpciones.php',
		success: function(data){ 
			$("#textoCurso").val(data);
			//alert("no entre");
		}
	});//fin ajax//*/
	$("input[id^='Nuevo']").click(function(evento){
		//evento.preventDefault();
		i = this.id.substr(5,10);
		
		//vCuota = $("#Nuevo" + i).val();
		vImporte = parseInt($("#cuotas" + i).val());
		//alert(vImporte);
		vTotal = parseInt($("#CMo_Importe").val());
		vTotalCuotas = parseInt($("#totalCuotas").val());
		if (this.checked){
			vTotal += parseInt(vImporte);
			vTotalCuotas += 1;
		}else{
			vTotal -= parseInt(vImporte);
			vTotalCuotas -= 1;
		}
		$("#CMo_Importe").val(vTotal);
		vAdelanto = vTotal * 0.15;//alert(vAdelanto);
		vAdelanto = Math.round(vAdelanto);
		$("#CMo_Adelanto").val(vAdelanto);
		$("#totalCuotas").val(vTotalCuotas);
	 });//fin evento click//*/
		
});//fin domready
</script>
<?php
$PerID = $_POST['PerID'];
//echo $PerID;

		$sql = "SELECT * FROM PlanPago INNER JOIN Usuario ON (PPa_Usu_ID = Usu_ID) WHERE PPa_Per_ID = $PerID ORDER BY PPa_ID DESC";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
            <div align="center" class="titulo_noticia">Planes de pago otorgados</div>
     <table width="100%" border="0" class="texto">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Plan Nº</th>
              <th align="center" class="fila_titulo">Total adeudado</th>
              <th align="center" class="fila_titulo">Usuario que lo hizo</th>
              <th align="center" class="fila_titulo">Fecha/Hora</th> 
               <th align="center" class="fila_titulo">Imprimir acta</th>
            </tr>
          </thead>
          <tbody>
            <?php
                    $i = 0;
					while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        ?>
                    <tr style="background-color: gainsboro;">
                      <td align="center"><?php echo $row['PPa_ID']; ?>
                      <input type="hidden"  id="PPa_ID<?php echo $i;?>2"  value="<?php echo $row['PPa_ID'];?>" /></td>
                      <td align="center">$<?php echo $row['PPa_DeudaTotal']; ?></td>                      
                    
                      <td align="center"><?php echo $row['Usu_Persona']; ?> </td>
                      <td align="center"><?php echo cfecha($row['PPa_Fecha'])." $row[PPa_Hora]"; ?></td>
                      
                        <td align="center"><a href="imprimirPlanPago.php?id=<?php echo $row['PPa_ID'];?>" target="_blank"><img src="imagenes/printer.png" width="32" height="32" /></a></td>
                    </tr>	
            <?php
                    }//fin while
                    ?>
          </tbody>
          <?php
            } else {
                ?>
          <span class="texto">No existen retiros realizados hasta el momento.</span>
  <?php
            }
            ?>
  <tfoot>
    <tr>
      <th colspan="5" class="fila_titulo"></th>
    </tr>
  </tfoot>
      </table>
<form id="formDatos" method="post" action="">
      <table width="90%" border="0" align="center" class="borde_recuadro">
            <tr>
                <td colspan="2" valign="middle" height="60px"><div align="center" class="titulo_noticia"> Crear un nuevo Plan de Pagos
                  
                   <!-- <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />-->
                  
                   <input type="hidden" name="Alt_ID" id="Alt_ID" value="1" />
                  
                </div></td>
            </tr>
            <tr>
              <td colspan="2" align="center" class="titulo_noticia">Datos del Padre/Madre y del Alumno</td>
            </tr>
            <tr class="texto">
              <td align="right">Curso y División:</td>
              <td><input name="textoCurso" type="text" id="textoCurso" size="50" readonly="readonly" /></td>
            </tr>
            <tr class="texto">
              <td align="right">Apellido Padre/Madre:</td>
              <td>
              <?php
              //Cargar lista de los padres
			  $sql = "SET NAMES UTF8;";
			  consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			  $sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)		
    INNER JOIN Persona 
        ON (Familia.Fam_Per_ID = Persona.Per_ID) 
	WHERE Fam_Vin_Per_ID = $PerID AND Fam_FTi_ID = 2 ORDER BY FTi_ID, Per_Sexo DESC, Per_Apellido, Per_Nombre;";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			$i = 0;
			if (mysqli_num_rows($result)>0){
				?><select name="DNITutor" id="DNITutor">
				<?php
                while ($row = mysqli_fetch_array($result)){
					$i++;
					if ($row[Per_Sexo]=="M") $rol = "Padre"; else $rol = "Madre";
					$texto = "($rol) $row[Per_Apellido], $row[Per_Nombre] (DNI: $row[Per_DNI])";					
					$PerIDPadre = $row[Per_ID];	
					echo "<option value='$PerIDPadre'>$texto</option>";
				}//fin while
                ?>
                <?php
			}//fin if
			  ?>
              
                
              </select><input type="hidden" name="CTi_ID" id="CTi_ID" value="0" /></td>
            </tr>   
            <tr>
              <td colspan="2" align="center" class="titulo_noticia">Detalles de la Deuda Vencida</td>
            </tr>
            <tr>
              <td colspan="2" align="center" class="texto">
              <?php //Cargar Deuda
              $sql = "SELECT * FROM
				CuotaPersona
				INNER JOIN Lectivo 
					ON (Cuo_Lec_ID = Lec_ID)
				INNER JOIN Colegio_Nivel 
					ON (Cuo_Niv_ID = Niv_ID)
				INNER JOIN CuotaTipo 
					ON (Cuo_CTi_ID = CTi_ID)
				INNER JOIN CuotaAlternativa 
					ON (Cuo_Alt_ID = Alt_ID)
				INNER JOIN CuotaBeneficio 
					ON (Cuo_Ben_ID = Ben_ID)
				INNER JOIN Usuario 
					ON (Cuo_Usu_ID = Usu_ID)
			WHERE (Cuo_Per_ID = $PerID AND Cuo_Alt_ID = 1 AND Cuo_Pagado = 0 AND Cuo_Cancelado = 0 AND Cuo_Anulado =0 AND Cuo_1er_Vencimiento < '".date("Y-m-d")."') ORDER BY Cuo_1er_Vencimiento, Cuo_Alt_ID ;";
			$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			$total = mysqli_num_rows($result);
			if ($total>0){	
			?>
            <table width="100%" border="0">
                <tr>
                  <td class="fila_titulo">&nbsp;</td>
                  <td align="center" class="fila_titulo">Concepto adeudado</td>
                  <td align="center" class="fila_titulo">Lectivo</td>
                  <td align="center" class="fila_titulo">Vencimiento</td>
                  <td align="center" class="fila_titulo">Importe</td>
                  <td align="center" class="fila_titulo">Recargo p/mora</td>
                  <td align="center" class="fila_titulo">Importe Total</td>
                </tr>
                <?php 
				while ($row = mysqli_fetch_array($result)){		
					$i++;
				//Creamos una variable que guarde todos los datos de identificaci�n de la Cuota
				$datosCuota = $row[Cuo_Lec_ID].";".$row[Cuo_Per_ID].";".$row[Cuo_Niv_ID].";".$row[Cuo_CTi_ID].";".$row[Cuo_Alt_ID].";".$row[Cuo_Numero];
				//Calculamos el importe que deber�a pagar al d�a de hoy
				$importe = $row[Cuo_Importe];
				//Recalculamos el importe de la cuota por si el alumnos ha pagado algo a cuenta
				$importeOriginal = $importe;
				recalcularImporteCuota($datosCuota, $importe);
				$importeAbonado = buscarPagosTotales($datosCuota);
				$importeActual = $importeOriginal - $importeAbonado;
				$recargoCuota = $importe - $importeActual;
				$fechaCuota = cfecha($row[Cuo_1er_Vencimiento]);
				$clase = "vencida_roja";
				$fechaHoy = date("d-m-Y");
				$ya_vencida=1;
				$fecha = restarFecha($fechaCuota, $fechaHoy);
				$fechaCuota2 = $row[Cuo_1er_Vencimiento];
				$fechaHoy2 = date("Y-m-d");
				$mesesAtrazo = 0;
				if ( $fecha > 0 ){
					$ya_vencida=1;
				}else{
					if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
				}
				$importe = intval($importe);
				
			  ?>
			<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px" title="<?php echo $info;?>">
			  <td><input type="checkbox" id="Nuevo<?php echo $i;?>" name="Nuevo<?php echo $i;?>" value="<?php echo $datosCuota;?>" <?php echo $desabilitada;?> class="activo"><input type="hidden" id="cuotas<?php echo $i;?>" value="<?php echo $importe;?>"></td>      
			  <td><?php echo $row[CTi_Nombre];?> (<?php echo buscarMes($row[Cuo_Mes]);?>/<?php echo $row[Cuo_Anio];?>)</td>
			  <td align="center"><?php echo $row[Lec_Nombre];?></td>
			  <td align="center"><?php echo $fechaCuota;?></td>
			  <td align="right"><?php echo "$".$importeActual;  ?></td>
			  <td align="right"><?php echo "$".$recargoCuota;  ?></td>
			  <td align="right"><?php echo "$".$importe;  ?></td>			  
			</tr>
				  <?php
				}//fin while
				?>
             </table>
            <?php
			}else{
				echo "El alumno no tiene Deuda Vencida para poder generar un Plan de Pagos.";
			}
              //Fin Cargar Deuda
			  ?>
              <input name="totalCuotas" type="hidden" id="totalCuotas" value="0" /></td>
            </tr>                  
            <tr>
              <td colspan="2" align="center" class="titulo_noticia">Datos del Plan</td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Importe Adeudado ($):</div></td>
                <td  class="texto"><input name="CMo_Importe" type="text" style="border:solid; font-size:16px; color:#F00" id="CMo_Importe" value="0" size="10" maxlength="10" readonly="readonly" /> 
            </tr>
            <tr>
                <td class="texto"><div align="right">Requiere adelanto de 15%($):</div></td>
                <td  class="texto"><input name="CMo_Adelanto" type="text" class="required digits" id="CMo_Adelanto" value="0" size="10" maxlength="10" /> 
        </tr>
            <tr>
                <td align="right" class="texto">Cantidad de Cuotas:</td>
                <td><?php cargaCantCuotas("CMo_CantCuotas"); ?></td>
            </tr>            
            <tr>
                <td align="right" class="texto"> 1º Vencimiento:</td>
                <td><input name="CMo_1er_Vencimiento" type="text" id="CMo_1er_Vencimiento" class="required fechaCompleta" alt="fecha de primer vencimiento"  title="Ingrese la fecha del primer vencimiento"/></td>
            </tr>
             <tr>
                <td align="right" class="texto">Mes:</td>
                <td><?php cargaMes("CMo_Mes") ?></td>
            </tr>
            <tr>
                <td class="texto"><div align="right">Año:</div></td>
                <td colspan="2"><!--<input style="display:none" value="cargaListado" id="opcion" name="opcion" />--><input name="CMo_Anio" type="text" id="CMo_Anio" value="<?php echo date("Y");?>" size="6" maxlength="4"/></td>            
            </tr>
            <tr>
                <td class="texto"><div align="right">Recargo Mensual:</div></td>
                <td colspan="2" class="texto">$
                  <input name="CMo_Recargo_Mensual" type="text" class="required digits" id="CMo_Recargo_Mensual" size="10" maxlength="10" /> 
            </tr>
            
            <tr><td colspan="2" align="center" style="padding-top:25px;"><button class="botones" id="vistaPrevia">Vista Previa del Plan de Pagos</button></td></tr>
            
            </table>
            
</form>            
<p>&nbsp;</p>
	<div id="mostrar22" class="texto"></div>
          

