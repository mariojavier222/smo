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
$(document).ready(function(){
$("button").button();

$("#editarDatosAdicionales").click(function(){
	
		PerID=$("#PerID").val();
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'editarDatosAdiccionalesPadre', PerID: PerID},
					url: 'cargarOpcionesDatosPersonalesPadre.php',
					success: function(data){ 
					$("#editarDatosPersonales2").html(data);
					
					}
		});//fin ajax///
	
	});



})
</script>
<style type="text/css">

.tablilla1
{	
	border: solid 1px;
}

.tablilla1 tr td
{
	border-collapse:collapse;
	font-size:13px;
	border: solid 1px;
	height:20px;
}
.botones
{
	font-size:14px;
}
.titulotd
{
	color:#e36d11;
	background-color:#F2F2F2;
}
.titulotd2
{
	color:#faa71c;
	/*background-color:#F2F2F2;*/
}
</style>
<br />
<div dir="DatosAdicionales" align="center">
<?php
$DNI = $_SESSION['sesion_usuario'];
//$DNI = "18618965";
if (!is_numeric($DNI)){
	?>
	<p>
<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />Esta opci&oacute;n no se encuentra disponible para Usted.</span></div></p><p></p>
<?php
	exit;
}
buscarTipoDoc($DNI, $DocID);

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "SELECT * FROM Persona WHERE Per_DNI = $DNI";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$rowDatos = mysqli_fetch_array($result);
	$usuario_nombre = $rowDatos[Per_Nombre];
	$usuario_apellido = $rowDatos[Per_Apellido];
	$PerID = $rowDatos[Per_ID];


$sql="SELECT *
FROM
    personadatos
    INNER JOIN pais 
        ON (Dat_Dom_Pai_ID = Pai_ID)
    INNER JOIN provincia 
        ON (Dat_Dom_Pro_ID = Pro_ID)
    INNER JOIN localidad 
        ON (Dat_Dom_Loc_ID = Loc_ID) WHERE Dat_Per_ID=$PerID;";
		//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$row = mysqli_fetch_array($result);
		
		if(($row[Dat_Nac_Pai_ID]!='')&&($row[Dat_Nac_Pro_ID]!='')&&($row[Dat_Nac_Loc_ID]!=''))
		{
			$sql3="SELECT 	Pai_ID, Pai_Nombre FROM pais WHERE Pai_ID=$row[Dat_Nac_Pai_ID];";
			$result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
			$row3 = mysqli_fetch_array($result3);
			$Pai_Nombre=$row3[Pai_Nombre];
			
			$sql3="SELECT 	Pro_ID, 
	Pro_Pai_ID, 
	Pro_Nombre FROM provincia WHERE Pro_ID=$row[Dat_Nac_Pro_ID]";
			$result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
			$row3 = mysqli_fetch_array($result3);
			$Pro_Nombre=$row3[Pro_Nombre];
			
			$sql3="SELECT 	Loc_ID, 
			Loc_Pai_ID, 
			Loc_Pro_ID, 
			Loc_Nombre 
			FROM 
			localidad  WHERE Loc_ID=$row[Dat_Nac_Loc_ID] ";
			$result3 = consulta_mysql_2022($sql3,basename(__FILE__),__LINE__);
			$row3 = mysqli_fetch_array($result3);
			$Loc_Nombre=$row3[Loc_Nombre];
			
		}
		
		?>
        
        <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
         <table class="tablilla1" style="" width="80%" >     
    <tr>
    <td class="titulotd"><strong>Apellido Y Nombre:</strong></td><td><?php echo $rowDatos[Per_Apellido]." ".$rowDatos[Per_Nombre] ?></td>
    </tr>
     <tr>
    <td class="titulotd"><strong>Nº Documento:</strong></td><td><?php echo $rowDatos[Per_DNI] ?></td>
    </tr>
     <tr>
    <td class="titulotd"><strong>Sexo:</strong></td><td><?php if($rowDatos[Per_Sexo]=='M')
	{
		echo "Masculino";
	}
	if($rowDatos[Per_Sexo]=='F')
	{
		echo "Femenino";
	}?></td>
    </tr>
</table>
<br />
<div id="editarDatosPersonales2">
         <table class="tablilla1" style="" width="80%">
    
    <tr>
    <th  colspan="3" align="center" height="35px;" style="font-size:15px;color:#faa71c"><img src="imagenes/hombre-mujer.png" alt="" width="32" height="32" align="absmiddle" />Mis Datos Personales</th>
    </tr>
    <tr>
    <td class="titulotd" rowspan="5"><strong>Domicilio:</strong></td>
    </tr>
    <tr><td class="titulotd2"><strong>Pais:</strong></td><td><?php echo $row[Pai_Nombre] ?></td></tr>
    <tr><td class="titulotd2"><strong>Provincia:</strong></td><td><?php echo $row[Pro_Nombre] ?></td></tr>
    <tr><td class="titulotd2"><strong>Localidad:</strong></td><td><?php echo $row[Loc_Nombre] ?></td></tr>
     <tr><td class="titulotd2"><strong>Direccion:</strong></td><td><?php echo $row[Dat_Domicilio] ?></td></tr>

<tr>
    <td class="titulotd" rowspan="5" ><strong>Nacimiento:</strong></td>
    </tr>
    <tr><td class="titulotd2"><strong>Pais:</strong></td><td><?php echo $Pai_Nombre ?></td></tr>
    <tr><td class="titulotd2"><strong>Provincia:</strong></td><td><?php echo $Pro_Nombre ?></td></tr>
    <tr><td class="titulotd2"><strong>Localidad:</strong></td><td><?php echo $Loc_Nombre ?></td></tr>
     <tr><td class="titulotd2"><strong>Fecha:</strong></td><td><?php echo cfecha($row[Dat_Nacimiento]) ?></td></tr>

     <tr>
    <td class="titulotd"><strong>Codigo Postal:</strong></td><td colspan="2"><?php echo $row[Dat_CP] ?></td>
    </tr>
     <tr>
    <td class="titulotd"><strong>Mail:</strong></td><td colspan="2"><?php echo $row[Dat_Email] ?></td>
    </tr>
         <tr>
    <td class="titulotd"><strong>Telefono:</strong></td><td colspan="2"><?php echo $row[Dat_Telefono] ?></td>
    </tr>
         <tr>
    <td class="titulotd"><strong>Celular:</strong></td><td colspan="2"><?php echo $row[Dat_Celular] ?></td>
    </tr>
             <tr>
    <td class="titulotd"><strong>Ocupacion:</strong></td><td colspan="2"><?php echo $row[Dat_Ocupacion] ?></td>
    </tr>
             <tr>
    <td class="titulotd"><strong>Observacion:</strong></td><td colspan="2"><?php echo $row[Dat_Observaciones] ?></td>
    </tr>
</table>

<br />
<table width="80%">
<tr align="right"><td><button class="botones" id="editarDatosAdicionales">Editar Datos Personales</button></td></tr>
</table>
</div>
<br />
<?php
	}
	else
	{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron datos cargados.</span></div>

<?php
	}
	?>
	</div>