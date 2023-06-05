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





$("a[id^='cargarFoto']").click(function(evento){
    evento.preventDefault();		
    PerID = this.id.substr(10,10);
		
	$.ajax({
	  type: "POST",
	  cache: false,
	  async: false,
	  data:{PerID:PerID},
	  url: 'cargarSubirFotosHijos.php',
	  success: function(data){ 
	  $("#principal").html(data);
	  	//return false;
	  }
	  });//fin ajax///
	
})//fin function click 

$("a[id^='cargarDatos']").click(function(evento){
    evento.preventDefault();		
    PerID = this.id.substr(11,10);
		
	$.ajax({
	  type: "POST",
	  cache: false,
	  async: false,
	  data:{opcion:"mostrarDatosHijos",PerID:PerID},
	  url: 'cargarOpcionesDatosPersonalesPadre.php',
	  success: function(data){ 
	  $("#principal").html(data);
	  	//return false;
	  }
	  });//fin ajax///
	
})//fin function click

$("a[id^='cargarClases']").click(function(evento){
    evento.preventDefault();		
    PerID = this.id.substr(12,10);
		
	$.ajax({
	  type: "POST",
	  cache: false,
	  async: false,
	  data:{PerID:PerID},
	  url: 'cargarSituacionAcademicaHijoSolo.php',
	  success: function(data){ 
	  $("#principal").html(data);
	  	//return false;
	  }
	  });//fin ajax///
	
})//fin function click

$("a[id^='cargarNotas']").click(function(evento){
    evento.preventDefault();		
    PerID = this.id.substr(11,10);
		
	$.ajax({
	  type: "POST",
	  cache: false,
	  async: false,
	  data:{PerID:PerID},
	  url: 'mostrarNotasHijoDetalle.php',
	  success: function(data){ 
	  $("#principal").html(data);
	  	//return false;
	  }
	  });//fin ajax///
	
})//fin function click

$("a[id^='cargarNotasProm']").click(function(evento){
    evento.preventDefault();		
    PerID = this.id.substr(15,10);
		
	$.ajax({
	  type: "POST",
	  cache: false,
	  async: false,
	  data:{PerID:PerID},
	  url: 'mostrarNotasHijoPromedio.php',
	  success: function(data){ 
	  $("#principal").html(data);
	  	//return false;
	  }
	  });//fin ajax///
	
})//fin function click

$("a[id^='cargarInasistencia']").click(function(evento){
    evento.preventDefault();			
	$.ajax({
	  type: "POST",
	  cache: false,
	  async: false,
	  url: 'cargarInasistenciasPadres.php',
	  success: function(data){ 
	  $("#principal").html(data);
	  	//return false;
	  }
	  });//fin ajax///
	
})//fin function click


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
            
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr>
              <?php while ($row = mysqli_fetch_array($result)){?>
                <td><div class="bordeHijo" align="center"><?php 
				$DNIHijo = gbuscarDNI($row[Fam_Vin_Per_ID]);
				gObtenerApellidoNombrePersona($DNIHijo, $Apellido, $Nombre, false);
				$foto = buscarFoto($DNIHijo, 1, 300);
				echo $foto;?>
                  <div>
                    <table width="250" border="0" align="center" cellpadding="1" cellspacing="1" class="texto">
                      <tr>
                        <td colspan="2" class="noticia_titulo"><?php echo $Nombre;?></td>
                      </tr>
                      <tr>
                        <td width="35"><img src="imagenes/camera_add.png" width="32" height="32" /></td>
                        <td width="338"><a href="#" id="cargarFoto<?php echo $row[Fam_Vin_Per_ID];?>">Cambiar foto</a></td>
                      </tr>
                      <tr>
                        <td><img src="imagenes/User.png" width="32" height="32" /></td>
                        <td><a href="#" id="cargarDatos<?php echo $row[Fam_Vin_Per_ID];?>">Datos personales</a></td>
                      </tr>
                      <tr>
                        <td><img src="imagenes/icono_asignatura.png" width="32" height="32" /></td>
                        <td><a href="#" id="cargarClases<?php echo $row[Fam_Vin_Per_ID];?>">Listar clases</a></td>
                      </tr>
                      <tr>
                        <td><img src="imagenes/application_form_edit.png" width="32" height="32" /></td>
                        <td><a href="#" id="cargarNotas<?php echo $row[Fam_Vin_Per_ID];?>">Ver Notas</a></td>
                      </tr>
                      <tr>
                        <td><img src="imagenes/report.png" width="32" height="32" /></td>
                        <td><a href="#" id="cargarNotasProm<?php echo $row[Fam_Vin_Per_ID];?>">Ver Promedios</a></td>
                      </tr>
                      <tr>
                        <td><img src="imagenes/report_edit.png" width="32" height="32" /></td>
                        <td><a href="#" id="cargarInasistencia<?php echo $row[Fam_Vin_Per_ID];?>">Ver Inasistencias</a></td>
                      </tr>
                    </table>
                  <p>&nbsp;</p></div>
                </div></td>
                <?php }//fin while?>
                
              </tr>
            </table>
<?php
			
			
			}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron hijos relacionados. Por favor dirigirse a Secretaría del Colegio para asociar su/s hijo/s.</span></div>
<?php
}

?>
