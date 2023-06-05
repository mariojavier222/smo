<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarOpciones.php");
 
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript">
    $(document).ready(function(){
		
		$(".botones").button();
		
		
        //$("#barraGuardar").click(function(evento){
//            evento.preventDefault();
            $("button[id^='btnguardar']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(10,10);
			
                vBen_ID = $("#BenID" + i).val();
				//alert(vBen_ID);
				vCTi_ID = $("#CTi_ID" + i).val();
				//alert(vCTi_ID);
                vPorValor = $("#valor" + i).val();
                vPorValorPorcentaje = $("#valorPorcentaje" + i).val();
				//alert(vPorValor);
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "guardarCuotaPorcentaje", Ben_ID: vBen_ID, CTi_ID: vCTi_ID, PorValor: vPorValor, PorValorPorcentaje: vPorValorPorcentaje},
                    success: function (data){
                        //$("#cargando").hide();
						$("#asdasd").html(data);
						mostrarAlerta("Se guardaron correctamente los cambios.", "Confirmación");
                    }
                });//fin ajax//*/				
            });
			
           
       // });      		
	$("#barraVolver").click(function(evento){
	$.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarCuotaBeneficio.php',
                data: {},
                success: function (data){
                    $("#principal").html(data);
                }
            });//fin ajax
	});
	
    });//fin de la funcion ready
</script>

<?php

function buscarValor($BenID,$Por_CTi_ID) {
	    
    $sql = "SELECT * FROM CuotaPorcentaje WHERE Por_Ben_ID = $BenID AND Por_CTi_ID=$Por_CTi_ID";
    $resultado = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	//echo $sql;
	$i=0;
		while($row = mysqli_fetch_array($resultado)){
		$i++;
		return "$row[Por_Valor];$row[Por_Porcentaje]";
	}//fin while
	return 0;
}
?>

<table>
    <tr>
        <td colspan="2"><div align="center"><strong> Porcentaje de Tipos de Cuota</strong></div></td>
    </tr>    
</table>
<fieldset class="recuadro_simple" id="resultado_buscador">
<?php

        $BenID = $_POST['BenID'];
?>

    <div id="listado">	
        <table width="100%" border="0" id="listadoTabla" class="texto">
            <thead>
                <tr class="gradeA" id="fila">		  
                    <th align="center" class="fila_titulo">Tipo de Cuota</th>
                    <th align="center" class="fila_titulo">Valor Fijo ($)</th>
                    <th align="center" class="fila_titulo">Porcentaje</th>
                    <th align="center" class="fila_titulo">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqel = "SELECT * FROM CuotaTipo";
                $result = consulta_mysql_2022($sqel,basename(__FILE__),__LINE__);
                $i = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $i++;
                    ?>
                    <tr style="background-color: #F0C89B; border-color: black ">

                        <td align="center"><input type="hidden"  id="BenID<?php echo $i; ?>" value="<?php echo $BenID; ?>" />
                      <input type="hidden"  id="CTi_ID<?php echo $i; ?>" value="<?php echo $row[CTi_ID]; ?>" />
					  <?php echo $row[CTi_Nombre]; ?></td>

                <td align="center">
                        $<input id="valor<?php echo $i; ?>" value="<?php 
                        list($Por_Valor, $Por_Porcentaje) =explode(";", buscarValor($BenID,$row[CTi_ID]));echo $Por_Valor;?>" size="5" />

                </td>
                <td align="center">
                        <input id="valorPorcentaje<?php echo $i; ?>" value="<?php echo $Por_Porcentaje;?>" size="5" />%

                </td>
                <td><button class="botones" id="btnguardar<?php echo $i ?>">Guardar</button></td>    
                </tr>
                <?php
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="12" class="fila_titulo"></th>
                </tr>
            </tfoot>
        </table>
        <button class="botones" id="barraVolver">
        Volver</button>
    </div>
</fieldset>
<div id="asdasd"></div>
<div style="margin-top: 90px;" class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />1 - Ingrese Importe o Porcentaje de descuento. Si ambos están llenos, se tomará el valor fijo</span></div>


