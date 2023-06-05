<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();	

$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch ($opcion) {
	
	case "guardarCuotaBeneficio":
        guardarCuotaBeneficio();
        break;
		
	case "listado":
        listado();
        break;

	case "listadoBeneficio":
        listadoBeneficio();
        break;

	case "editarCuotaBeneficio":
        editarCuotaBeneficio();
        break;
	
	
		

	
}

function editarCuotaBeneficio()
{
	

	//$id = $_POST['Sic_ID'];
	$Nombre = $_POST['Nombre'];
	//echo $Nombre."<br />";
	$Sigla=$_POST['Sigla'];
	//echo $Sigla."<br />";
	$Ben_ID=$_POST['Ben_ID'];
	//echo $Ben_ID."<br />";
	
        
        
//return false;
	$sql = "UPDATE CuotaBeneficio 
	SET
	Ben_Nombre = '$Nombre' , 
	Ben_Siglas = '$Sigla'
	
	WHERE
	Ben_ID = '$Ben_ID';";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Editado Correctamente";

	
}

function guardarCuotaBeneficio()
{

	//$id = $_POST['Sic_ID'];
	$Nombre = $_POST['Nombre'];
	$Sigla=$_POST['Sigla'];
        
        

	$sql = "INSERT INTO CuotaBeneficio (Ben_Nombre,Ben_Siglas) VALUES ('$Nombre','$Sigla')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Guardado Correctamente";
}


function listado()
{

	$sql = "SELECT * FROM CuotaBeneficio ORDER BY Ben_Nombre";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	echo "<option value='0' selected='selected'> Seleccionar</option>";
	//echo "<select name='$sicopedagoga' id='$sicopedagoga' style='position:relative;z-index:1'>";
	while ($row = mysqli_fetch_array($result)){
		echo "<option value='$row[Ben_ID]'>$row[Ben_Nombre]</option>";
	}//fin del while
	//echo "</select>";

	
}

function listadoBeneficio()
{
	?>
    <script type="text/javascript">
	
	        $("a[id^='botEditar']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(9,10);
			
			
			$("#guardartd").hide();
			$("#editartd").show();
            
            $("#Ben_ID").val($("#Ben_ID" + i).val());
            $("#Nombre").val($("#Ben_Nombre" + i).val());
            $("#Sigla").val($("#Ben_Siglas" + i).val());
            $("#mostrarNuevo").show();
            $("#mostrar").empty();		
            $("#listado").hide();
		
        });//fin evento click//*/
		
		
		        $("a[id^='accdirecto']").click(function(evento){											  
            evento.preventDefault();
            var i = this.id.substr(10,10);
            vBenID = $("#Ben_ID"+ i).val();
            $.ajax({
                type: "POST",
                cache: false,
                async: false,			
                url: 'cargarCuotaPorcentaje.php',
                data: {BenID: vBenID, pag_Volver: "cargarCuotaBeneficio"},
                success: function (data){
                    $("#principal").html(data);
                    //mostrarAlerta(data);
                    $("#cargando").hide();
                }
            });//fin ajax
        });
			
	</script>
	<fieldset class="recuadro_simple texto" id="resultado_buscador">
        <legend>Resultado de la b&uacute;squeda</legend>    
        <br />
        <br />
        <div align="center" class="titulo_noticia">Listado de Beneficios</div>
        <?php
        $sql = "SET NAMES UTF8;";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

        $sql_1 = "SELECT * FROM CuotaBeneficio";
        $result = consulta_mysql_2022($sql_1,basename(__FILE__),__LINE__);

        if (mysqli_num_rows($result) > 0) {
            ?>
            <table width="100%" border="0" id="listadoTabla" class="display texto">
                <thead>
                    <tr class="gradeA" id="fila">
                        <th align="center" class="fila_titulo">Numero</th>
                        <th align="center" class="fila_titulo">Nombre</th>
                        <th align="center" class="fila_titulo">Sigla</th>
                        <th align="center" class="fila_titulo">Acci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        ?>
                        <tr style="background-color: gainsboro;">
                            <td align="center"><?php echo $row[Ben_ID]; ?>
                                <input type="hidden" id="Ben_ID<?php echo $i; ?>" value="<?php echo $row[Ben_ID]; ?>" />
                                <input type="hidden" id="Ben_Nombre<?php echo $i; ?>" value="<?php echo $row[Ben_Nombre]; ?>" />
                                <input type="hidden" id="Ben_Siglas<?php echo $i; ?>" value="<?php echo $row[Ben_Siglas]; ?>" />
                            </td>
                            <td align="center"><?php echo $row[Ben_Nombre]; ?></td>
                            <td align="center"><?php echo $row[Ben_Siglas]; ?></td>
                            <td align="center">
                                <a href="#" id="botEditar<?php echo $i; ?>">
                                    <img src="imagenes/editar_activo.png" alt="Editar registro" title="Editar registro" width="32" height="32" border="0" /></a>
                                <a href="#" id="accdirecto<?php echo $i; ?>"><img  style ="margin-left: 20px; margin-bottom: 7px;" src="imagenes/go-jump.png" alt="Ir a Porcentaje de Cuota  " title="Ir a Porcentaje de Cuota" width="22" height="22" border="0" />
                    </a>
                            </td>
                        </tr>

                        <?php
                    }//fin while
                    ?>
                </tbody>
            </table>

            <?php
        } else {
            ?>
            No existen datos cargados.
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
    </fieldset>
    <?php
}
?>
