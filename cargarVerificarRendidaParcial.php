<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");
include_once("cargarOpciones.php");
?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
<link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />


<script language="javascript">
    $(document).ready(function(){
		
		$(".botones").button();	
		
		$("a[id^='campos']").click(function(evento){
            evento.preventDefault();
            var i = this.id.substr(6,15);
            $("#tr_campos"+ i).show(); 
			
			Caja_ID=$("#CRe_Caja_ID"+i).val();
			
			
			  
						$.ajax({
                    type: "POST",
                    cache: false,
                    async: false,			
                    url: 'cargarOpciones.php',
                    data: {opcion: "mostrarDetalleAuditada", Caja_ID: Caja_ID, i:i},
                    success: function (data){
                       // $("#cargando").hide();
						$("#div_campos"+i).html(data);
						//mostrarAlerta("Se guardaron correctamente los cambios.", "Confirmación")
                    }
                });//fin ajax///         
            
                
        });// fin funcion campos 
		

		
		});//fin de la funcion ready

</script>

<?php
function verificarAuditada($id){
	
	$sql = "SELECT CRe_Auditado FROM CajaRendida WHERE CRe_Caja_ID = $id AND CRe_Auditado = 0";
	//echo $sql;
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	 if(mysqli_num_rows($result)>0){	
	 	//echo "123";
		if ($row = mysqli_fetch_array($result)) {
			$i++;
			  return "Falta Auditar";              
	 }//fin while
	 }// fin if
	 else{
		return "Auditada";
		 } 
}//fin function



?>

<div id="listado" class="page-break">

      
    <fieldset class="recuadro_simple" id="resultado_buscador">
        <legend>Resultado de la b&uacute;squeda</legend>    
        <br />
        <br />       
      <div align="center" class="titulo_noticia">Auditar cajas</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        $sql_1 = "SELECT Caja_ID, SUM(CRe_Importe) AS total, Usu_Persona, For_Nombre, CRe_Importe, CRe_Fecha_Rendida, CRe_Usu_Auditado
FROM
    CajaRendida
    INNER JOIN Usuario 
        ON (CajaRendida.CRe_Usu_ID = Usuario.Usu_ID)
    INNER JOIN FormaPago 
        ON (CajaRendida.CRe_For_ID = FormaPago.For_ID)
    INNER JOIN Caja 
        ON (CajaRendida.CRe_Caja_ID = Caja.Caja_ID)
        WHERE CRe_Auditado = 0
    GROUP BY Caja_ID";
	//echo $sql_1."<br />";
        $result = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
      <table border="0" id="listadoTabla" class="display">
          <thead>
            <tr class="gradeA" id="fila">
              <th align="center" class="fila_titulo">Nro Caja</th>
              <th align="center" class="fila_titulo">Importe Total a Verificar</th>
              <th align="center" class="fila_titulo">Acción</th>
              <th align="center" class="fila_titulo">Rendida</th>
            </tr>
          </thead>
          <tbody>
            <?php
			$i=0;
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
						$nocaja = $row['Caja_ID'];
                        ?>
            <tr style="background-color: gainsboro;">
              <td align="center"><?php echo $row[Caja_ID]; ?>
                <input type="hidden" name="CRe_Caja_ID" id="CRe_Caja_ID<?php echo $i; ?>" value="<?php echo $row[Caja_ID]; ?>" /></td>
              <td align="center"><?php echo $row[total]; ?></td>
              <td align="center"><a id="campos<?php echo $i; ?>" href="#"> <img  style ="margin-left: 20px; margin-bottom: 7px;" src="imagenes/go-jump.png" alt="Editar Campos" title="Editar Campos" width="22" height="22" border="0" /></a></td>
              <td align="center">
			  <?php echo verificarAuditada($row[Caja_ID]);  
			  ?>
              </td>
            </tr>
            <tr id="tr_campos<?php echo $i; ?>" style="display:none;">
              <td colspan="4" align="center">
              <div id="div_campos<?php echo $i; ?>">
                
              </div>
              </td>
            </tr>
            <?php
                    }//fin while externo
                    ?>
          </tbody>
          <?php
            } else {
                ?>
          No existen datos cargados.
  <?php
            }
            ?>
  <tfoot>
    <tr>
      <th colspan="12" class="fila_titulo"></th>
    </tr>
  </tfoot>
      </table>
    </fieldset>
</div>



	




	
