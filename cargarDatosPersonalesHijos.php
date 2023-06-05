<?php
header("Cache-Control: no-cache, must-revalidate"); 
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
require_once("funciones_generales.php");

//sleep(3);
?>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<script language="javascript">

$("button").button();





$("#mostrarDatosHijos").click(function(){
	
	
	PerID=$("#PerID").val();
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	data:{opcion:"mostrarDatosHijos",PerID:PerID},
	url: 'cargarOpcionesDatosPersonalesPadre.php',
	success: function(data){ 
	$("#mostrarDatosHijo").html(data);
	//return false;
	}
	});//fin ajax///
	
	})//fin function click cargar pais


</script>
<?php
$DNI = $_SESSION['sesion_usuario'];
//$DNI = "18618965";//Ivana
//$DNI = "18495898";//Ricardo
if (!is_numeric($DNI)){
	?>
	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible para Usted.</span></div></p><p></p>
<?php
	exit;
}

$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_array($result);
	$usuario_nombre = $row[Per_Nombre];
	$usuario_apellido = $row[Per_Apellido];
	$PerID = $row[Per_ID];
}


         $sql = "SELECT * FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
    INNER JOIN Persona 
        ON (Familia.Fam_Vin_Per_ID = Persona.Per_ID)
WHERE (Familia.Fam_Per_ID = $PerID AND Fam_FTi_ID = 2);";
		//echo $sql;
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)>0){
			
			?>
            <br />
<br />
<div id="mostrarDatosHijo" align="center">

<br />

            <table align="center">
            <tr align="center" style="font-size:18px;color:#faa71c"><td><img src="imagenes/hombre-mujer.png" alt="" width="32" height="32" align="absmiddle" />Mis Datos Personales</td></tr>
            <tr><td>  <p><span class="texto">Seleccione el nombre de su hijo/a:</span>
<?php cargarListaHijosPadre("PerID", $PerID);?>
  </p></td></tr>
  <tr align="center"><td><button id="mostrarDatosHijos">Mostrar Datos</button></td></tr>
            </table>
            
  </div>
            <?php
			
			
			}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron datos cargados.</span></div>
<?php
}

?>
