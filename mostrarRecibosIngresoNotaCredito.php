<?php
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
?>
<script language="javascript">

$(document).ready(function(){

	$("#NdecBot").click(function(evento){                        
    evento.preventDefault();
    //var i = this.id.substr(11,100);
    //Validamos la autorización
    UsuAut = $("#UsuAut option:selected").val();
    if (UsuAut==-1){
        alert("ERROR: Debe seleccionar una persona para autorizar");
        return;
    }
    $.ajax({
        type: "POST",
        cache: false,
        async: false,
        error: function (XMLHttpRequest, textStatus){
          alert(textStatus);},
        data: {opcion: "buscarCodigoAutorizacion"},
        url: 'cargarOpciones.php',
        success: function(data){ 
           jPrompt('Ingrese los 4 digitos seguidos, sin espacios, que indica la Tarjeta de Coordenadas: <br>' + data , '', 'Busque la posición', function(r){
            if (r){
                //alert("Bien");
                cod = data;
                $.ajax({
                    type: "POST",
                    cache: false,
                    async: false,
                    error: function (XMLHttpRequest, textStatus){
                      alert(textStatus);},
                    data: {opcion: "buscarAutorizacion", UsuAut: UsuAut, Coordenadas: cod, valor: r},
                    url: 'cargarOpciones.php',
                    success: function(data){ 
                        if (data!="Bien"){
                            jAlert(data, "Resultado");
                        }else{
                            DRe_ID = <? echo $_POST['DRe_ID'];?>;
                            NdecNumero = $("#NdecNumero").val();
                            $("#NdecBot").hide();
                            //alert(i);
                            $.ajax({
                                type: "POST",
                                cache: false,
                                async: false,
                                error: function (XMLHttpRequest, textStatus){
                                  alert(textStatus);},
                                data: {opcion: "eliminarDeudorRecibo", DRe_ID: DRe_ID, NdecNumero: NdecNumero, UsuAut: UsuAut},
                                url: 'cargarOpciones.php',
                                success: function(data){ 
                                   jAlert(data, 'Resultado de generar Nota de Crédito');
                                   
                                }
                              });//fin ajax//*/ 
                        }
                       
                    }
                  });//fin ajax//*/                                 
            }else{
                alert("ERROR: Ingrese un valor");return;
            }//fin if
        });//fin del confirm//*/
           
        }
    });//fin ajax//*/ 
    //return;
    

    
  
   });//fin evento click//*/

  
  $("#NdecNumero").mask("9999-99999999");

    $.ajax({
        type: "POST",
        cache: false,
        async: false,
        data: {opcion: 'buscarNdecUltima'},
        url: 'cargarOpciones.php',
        success: function(data){ 
                if (data)
                    $("#NdecNumero").val(data);
        }
        
    });//fin ajax///
	
});//fin domready 
</script>
<script type='text/javascript' src='js/jquery.maskedinput-1.3.min.js'></script>
<?php
/*require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");*/

    $DRe_ID = $_POST['DRe_ID'];
    //generarFacturaDeudor($DRe_RazonSocial, $row['Deu_CUIT'], $DRe_ReciboNumero, $DRe_Importe, $ForID, $DRe_Detalle, 3);
		
		$sql = "SELECT * FROM Deudor INNER JOIN DeudorRecibo ON (DRe_Deu_ID = Deu_ID) WHERE DRe_ID = $DRe_ID";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$Cuenta = $row['Deu_Nombre'];
		}
		global $gMes;
		?>
        <div align="center" class="titulo_noticia">Generar Nota de Crédito para <?php echo $Cuenta;?></div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        
        $sql = "SELECT * FROM DeudorRecibo INNER JOIN Usuario ON (Usu_ID = DRe_Usu_ID) WHERE DRe_ID = $DRe_ID";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        $row = mysqli_fetch_array($result);
		//echo $sql;exit;
        ?>

        <table width="85%" border="0" align="center" cellpadding="2" cellspacing="2" class="texto">
        <tr>
        <td align="right">Importe total</td>
        <td align="left">$<? echo $row['DRe_Importe'];?>
        </td>
        <td></td>
        </tr>
        <tr>
        <td align="right">Nota de Crédito Nº:</td>
        <td align="left"><input name="NdecNumero" type="text" class="input_editar" style="font-size:18px" id="NdecNumero" size="20" maxlength="13" />
        </td>
        <td></td>
        </tr>
        <tr>
        <td align="right">Pedir autorización</td>
        <td align="left"><?php cargarListaAdministradores("UsuAut");?></td>
        <td><button name="NdecBot" id="NdecBot"> Generar Nota de Crédito </button></td>
        </tr>
        </table>
            
  <tfoot>
    <tr>
      <th colspan="7" class="fila_titulo" align="right">
      </th>      
    </tr>
  </tfoot>      
      </table>     
  