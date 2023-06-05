<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();	

$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch ($opcion) {
	
	 case "editarDatosAdiccionalesPadre":
        editarDatosAdiccionalesPadre();
        break;
	 case "GuardarDatosAdicionalesPadre":
        GuardarDatosAdicionalesPadre();
        break;
		case "mostrarDatosHijos":
        mostrarDatosHijos();
        break;
		
		
		
}


function editarDatosAdiccionalesPadre()
{	
$PerID = $_POST['PerID'];
$auxiliar = $_POST['auxiliar'];

//echo "PerID ".$PerID."<br />";
$UsuID = $_SESSION['sesion_UsuID'];
$_SESSION['sesion_ultimoDNI'] = gbuscarDNI($PerID);

//return false;

?>


<script language="javascript">
$(document).ready(function(){
		

   $("#NacPaisID").change(function () {
   		$("#NacPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Nac", vPais);
        });
   })
   	// Parametros para el combo2
	$("#NacProID").change(function () {
   		$("#NacProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#NacPaisID").val();
			llenarLocalidad("Nac", vProv, vPais);
        });
   })
   $("#DomPaisID").change(function () {
   		$("#DomPaisID option:selected").each(function () {
			//alert($(this).val());
				vPais=$(this).val();
				llenarProvincia("Dom", vPais);
        });
   })
   	// Parametros para el combo2
	$("#DomProID").change(function () {
   		$("#DomProID option:selected").each(function () {
			//alert($(this).val());
			vProv=$(this).val();
			vPais = $("#DomPaisID").val();
			llenarLocalidad("Dom", vProv, vPais);
        });
   })

$(".botones").button();

$("#Dat_Nacimiento").datepicker({
	changeYear: true,
	yearRange: '1800:2050'
	});

$("#GuardarDatosAdicionales").click(function(){
	//alert("asdasd");
	var auxiliar=$("#auxiliar").val();
	//alert("auxiliar"+auxiliar);
	//return false;
	var PerID=$("#PerID").val();
	//alert("PerID"+PerID);
	var DomPaisID=$("#DomPaisID").val();
	//alert("DomPaisID"+DomPaisID);
	var DomProID=$("#DomProID").val();
	//alert("DomProID"+DomProID);
	var DomLocID=$("#DomLocID").val();
	//alert("DomLocID"+DomLocID);
	var Dat_Domicilio=$("#Dat_Domicilio").val();
	//alert("Dat_Domicilio"+Dat_Domicilio);
	var NacPaisID=$("#NacPaisID").val();
	//alert("NacPaisID"+NacPaisID);
	var NacProID=$("#NacProID").val();
	//alert("NacProID"+NacProID);
	var NacLocID=$("#NacLocID").val();
	//alert("NacLocID"+NacLocID);
	var Dat_Nacimiento=$("#Dat_Nacimiento").val();
	//alert("Dat_Nacimiento"+Dat_Nacimiento);
	var Dat_CP=$("#Dat_CP").val();
	//alert("Dat_CP"+Dat_CP);
	var Dat_Email=$("#Dat_Email").val();
	//alert("Dat_Email"+Dat_Email);
	var Dat_Telefono=$("#Dat_Telefono").val();
	//alert("Dat_Telefono"+Dat_Telefono);
	var Dat_Celular=$("#Dat_Celular").val();
	//alert("Dat_Celular"+Dat_Celular);
	var Dat_Ocupacion=$("#Dat_Ocupacion").val();
	//alert("Dat_Ocupacion"+Dat_Ocupacion);
	var Dat_Observaciones=$("#Dat_Observaciones").val();
	//alert("Dat_Observaciones"+Dat_Observaciones);
	
	//return false;
	if(Dat_Domicilio=='')
	{
		jAlert("Ingrese Direccion","Datos Personales");
		return false;
	}
	if((Dat_Telefono=='')&&(Dat_Celular==''))
	{
		jAlert("Ingrese Telofono o Celular","Datos Personales");
		return false;
	}
	//return false;
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'GuardarDatosAdicionalesPadre', PerID: PerID,			DomPaisID:DomPaisID,
					DomProID:DomProID,
					DomLocID:DomLocID,
					Dat_Domicilio:Dat_Domicilio,
					NacProID:NacProID,
					NacPaisID:NacPaisID,
					NacLocID:NacLocID,
					Dat_Nacimiento:Dat_Nacimiento,
					Dat_CP:Dat_CP,
					Dat_Email:Dat_Email,
					Dat_Telefono:Dat_Telefono,
					Dat_Celular:Dat_Celular,
					Dat_Ocupacion:Dat_Ocupacion,
					Dat_Observaciones:Dat_Observaciones,
					},
					url: 'cargarOpcionesDatosPersonalesPadre.php',
					success: function(data){ 
					$("#asdasd").html(data);
				jAlert("Guardado Correctamente","Datos Personales");
		
		if(auxiliar!=2)
		{
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					//data: {opcion: 'mostrarDatosAdiccionales', PerID: PerID},
					url: 'cargarDatosPersonalesPadre.php',
					success: function(data){ 
					$("#principal").html(data);
					
					}
		});//fin ajax///
		}
		else
		{
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					//data: {opcion: 'mostrarDatosAdiccionales', PerID: PerID},
					url: 'cargarDatosPersonalesHijos.php',
					success: function(data){ 
					$("#principal").html(data);
					
					}
		});//fin ajax///
			}
		
					}
		});//fin ajax///
	
	})

function mostrarAlerta3(cuerpo, titulo,ancho,alto){
	cuerpo = "<p><span style='float:left; margin:0 7px 20px 0;'></span>" + cuerpo + "</p>";
	$("#dialog").html(cuerpo);
		$("#dialog").dialog({ draggable: true, width: ancho, height:alto, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: true, 
			buttons: {
					'Aceptar': function() {
						
						var PerID=$("#PerID").val();
						var auxiliar=$("#auxiliar").val();
						$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'editarDatosAdiccionalesPadre', PerID: PerID,auxiliar:auxiliar},
					url: 'cargarOpcionesDatosPersonalesPadre.php',
					success: function(data){ 
					$("#editarDatosPersonales2").html(data);
					
					}
		});//fin ajax///
						
						
					$(this).dialog('close');
				}
				
			}//fin buttons
 		});//fin dialog
		
		return false;
}//fin funcion

$("#CargarNuevoPais, #CargarNuevoPais2").click(function(){
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	url: 'cargarNuevoPais.php',
	success: function(data){ 
	mostrarAlerta3(data,"Cargar Nuevo Pais",700,500);
	//return false;
	}
	});//fin ajax///
	
	})//fin function click cargar pais
	
$("#CargarNuevoProvincia, #CargarNuevoProvincia2").click(function(){
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	url: 'cargarNuevaProvincia.php',
	success: function(data){ 
	mostrarAlerta3(data,"Cargar Nueva Provincia",700,500);
	//return false;
	}
	});//fin ajax///
	
	})//fin function click cargar Procincia
	
	$("#CargarNuevaLocalidad, #CargarNuevaLocalidad2").click(function(){
	
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	url: 'cargarNuevaLocalidad.php',
	success: function(data){ 
	mostrarAlerta3(data,"Cargar Nueva Localidad",700,500);
	//return false;
	}
	});//fin ajax///
	
	})//fin function click cargar Procincia
	
	

})//document
	function llenarLocalidad(vObj, vProv, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarLocalidad', Pais: vPais, Prov: vProv },		function(data){
     			$("#" + vObj + "LocID").html(data);
				if (vOpcion) $("#" + vObj + "LocID").attr("value",vOpcion);
   		});
	}
	function llenarProvincia(vObj, vPais, vOpcion){
		$.post("buscarLocalidad.php", { opcion: 'cargarProvincia', Pais: vPais },function(data){
     			$("#" + vObj + "ProID").html(data);
					vProv = $("#" + vObj + "ProID").val();
					if (vOpcion)
						$("#" + vObj + "ProID").attr("value", vOpcion);
					else
						llenarLocalidad(vObj, vProv, vPais);

   			});
	}


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
input
{
	height:15px;
	font-size:13px;
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
<?php	


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
		//echo "PerID".$PerID."<br>";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if(mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_array($result);
		?>
<script language="javascript">

$("#Dat_Domicilio").val('<?php echo $row[Dat_Domicilio]  ?>');

$("#Dat_Nacimiento").val('<?php echo cfecha($row[Dat_Nacimiento])  ?>');

$("#Dat_Email").val('<?php echo $row[Dat_Email]  ?>');

$("#Dat_Telefono").val('<?php echo $row[Dat_Telefono]  ?>');

$("#Dat_Celular").val('<?php echo $row[Dat_Celular]  ?>');

$("#Dat_Ocupacion").val('<?php echo $row[Dat_Ocupacion]  ?>');

$("#Dat_Observaciones").val('<?php echo $row[Dat_Observaciones]  ?>');

$("#Dat_CP").val('<?php echo $row[Dat_CP]  ?>');

$("#NacPaisID").val(<?php echo $row[Dat_Nac_Pai_ID] ?>);
llenarProvincia("Nac", <?php echo $row[Dat_Nac_Pai_ID] ?>, <?php echo $row[Dat_Nac_Pro_ID] ?>);
llenarLocalidad("Nac", <?php echo $row[Dat_Nac_Pro_ID] ?>, <?php echo $row[Dat_Nac_Pai_ID] ?>, <?php echo $row[Dat_Nac_Loc_ID] ?>);
$("#DomPaisID").val(<?php echo $row[Dat_Dom_Pai_ID] ?>);
llenarProvincia("Dom", <?php echo $row[Dat_Dom_Pai_ID] ?>, <?php echo $row[Dat_Dom_Pro_ID] ?>);								
llenarLocalidad("Dom", <?php echo $row[Dat_Dom_Pro_ID] ?>, <?php echo $row[Dat_Dom_Pai_ID] ?>, <?php echo $row[Dat_Dom_Loc_ID] ?>);
</script>
<?php		
	}		
		?>
        
         <input type="hidden" name="auxiliar" id="auxiliar" value="<?php echo $auxiliar ?>">
          <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
         <table class="tablilla1" width="80%">
    
    <tr>
   <th  colspan="3" align="center" height="35px;" style="font-size:15px;color:#faa71c"><img src="imagenes/hombre-mujer.png" alt="" width="32" height="32" align="absmiddle" />Mis Datos Personales</th>
    </tr>
    <tr>
    <td class="titulotd" rowspan="5"><strong>Domicilio:</strong></td>
    </tr>
    <tr valign="middle"><td class="titulotd2"><strong>Pais:</strong></td><td><?php cargarListaPais('DomPaisID') ?><a style="cursor:pointer" id="CargarNuevoPais" title="Cargar Nuevo Pais"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td class="titulotd2"><strong>Provincia:</strong></td><td><?php cargarListaProvincia('DomProID',0) ?><a style="cursor:pointer" id="CargarNuevoProvincia" title="Cargar Nueva Provincia"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td class="titulotd2"><strong>Localidad:</strong></td><td><?php cargarListaLocalidad('DomLocID',0,0) ?><a style="cursor:pointer" id="CargarNuevaLocalidad" title="Cargar Nueva Localidad"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
     <tr><td class="titulotd2"><strong>Direccion:</strong></td><td><input type="text" name="Dat_Domicilio" id="Dat_Domicilio" size="40" />*</td></tr>

<tr>
    <td class="titulotd" rowspan="5"><strong>Nacimiento:</strong></td>
    </tr>
    <tr><td class="titulotd2"><strong>Pais:</strong></td><td><?php cargarListaPais('NacPaisID') ?><a style="cursor:pointer" id="CargarNuevoPais2" title="Cargar Nuevo Pais"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td class="titulotd2"><strong>Provincia:</strong></td><td><?php cargarListaProvincia('NacProID',0) ?><a style="cursor:pointer" id="CargarNuevoProvincia2" title="Cargar Nueva Provincia"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
    <tr><td class="titulotd2"><strong>Localidad:</strong></td><td><?php cargarListaLocalidad('NacLocID',0,0) ?><a style="cursor:pointer" id="CargarNuevaLocalidad2" title="Cargar Nueva Localidad"><img src="imagenes/add2.png" width="20" height="20" /></a></td></tr>
     <tr><td class="titulotd2"><strong>Fecha:</strong></td><td><input type="text" name="Dat_Nacimiento" id="Dat_Nacimiento" /></td></tr>

     <tr>
    <td class="titulotd" ><strong>Codigo Postal:</strong></td><td colspan="2"><input type="text" size="5" name="Dat_CP" id="Dat_CP" /></td>
    </tr>
     <tr>
    <td class="titulotd" ><strong>Mail:</strong></td><td colspan="2"><input type="text" name="Dat_Email" size="40" id="Dat_Email" /></td>
    </tr>
         <tr>
    <td class="titulotd" ><strong>Telefono:</strong></td><td colspan="2"><input type="text" name="Dat_Telefono" id="Dat_Telefono" />*</td>
    </tr>
         <tr>
    <td class="titulotd" ><strong>Celular:</strong></td><td colspan="2"><input type="text" name="Dat_Celular" id="Dat_Celular" size="40" />*</td>
    </tr>
             <tr>
    <td class="titulotd" ><strong>Ocupacion:</strong></td><td colspan="2"><input type="text" size="40" name="Dat_Ocupacion" id="Dat_Ocupacion" /></td>
    </tr>
             <tr>
    <td class="titulotd" ><strong>Observacion:</strong></td><td colspan="2"><input type="text" size="40" name="Dat_Observaciones" id="Dat_Observaciones" /></td>
    </tr>
</table>

<br />
<table width="80%">
<tr align="right"><td><button class="botones" id="GuardarDatosAdicionales">Guardar</button></td></tr>
</table>
<div id="asdasd"></div>
<?php


}

function GuardarDatosAdicionalesPadre()
{
	
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$PerID = $_POST['PerID'];
//echo "PerID".$PerID."<br />";
$DomPaisID = $_POST['DomPaisID'];
//echo "DomPaisID".$DomPaisID."<br />";
$DomProID = $_POST['DomProID'];
//echo "DomProID".$DomProID."<br />";
$DomLocID = $_POST['DomLocID'];
//echo "DomLocID".$DomLocID."<br />";
$Dat_Domicilio = $_POST['Dat_Domicilio'];
//echo "Dat_Domicilio".$Dat_Domicilio."<br />";
$NacPaisID = $_POST['NacPaisID'];
//echo "NacPaisID".$NacPaisID."<br />";
	
$NacProID = $_POST['NacProID'];
//echo "NacProID".$NacProID."<br />";
$NacLocID = $_POST['NacLocID'];
//echo "NacLocID".$NacLocID."<br />";

$Dat_Nacimiento = cambiaf_a_mysql($_POST['Dat_Nacimiento']);
//echo "Dat_Nacimiento".$Dat_Nacimiento."<br />";
$Dat_CP = $_POST['Dat_CP'];
//echo "Dat_CP".$Dat_CP."<br />";
$Dat_Email = $_POST['Dat_Email'];
//echo "Dat_Email".$Dat_Email."<br />";

$Dat_Telefono = $_POST['Dat_Telefono'];
//echo "Dat_Telefono".$Dat_Telefono."<br />";
$Dat_Celular = $_POST['Dat_Celular'];
//echo "Dat_Celular".$Dat_Celular."<br />";
$Dat_Ocupacion = $_POST['Dat_Ocupacion'];
//echo "Dat_Ocupacion".$Dat_Ocupacion."<br />";

$Dat_Observaciones = $_POST['Dat_Observaciones'];
//echo "Dat_Observaciones".$Dat_Observaciones."<br />";

$sql="SELECT *
FROM
    PersonaDatosPadre
    INNER JOIN pais 
        ON (Dat_Dom_Pai_ID = Pai_ID)
    INNER JOIN provincia 
        ON (Dat_Dom_Pro_ID = Pro_ID)
    INNER JOIN localidad 
        ON (Dat_Dom_Loc_ID = Loc_ID) WHERE Dat_Per_ID=$PerID;";
		//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if(mysqli_num_rows($result)>0)
	{

$sql = "UPDATE PersonaDatosPadre
	SET
	Dat_Dom_Pro_ID = '$DomProID' , 
	Dat_Dom_Pai_ID = '$DomPaisID' , 
	Dat_Dom_Loc_ID = '$DomLocID' , 
	Dat_Nac_Pro_ID = '$NacProID' , 
	Dat_Nac_Pai_ID = '$NacPaisID' , 
	Dat_Nac_Loc_ID = '$NacLocID' , 
	Dat_Nacimiento = '$Dat_Nacimiento' , 
	Dat_Domicilio = '$Dat_Domicilio' , 
	Dat_CP = '$Dat_CP' , 
	Dat_Email = '$Dat_Email' , 
	Dat_Telefono = '$Dat_Telefono' , 
	Dat_Celular = '$Dat_Celular' , 
	Dat_Ocupacion = '$Dat_Ocupacion' , 
	Dat_Observaciones = '$Dat_Observaciones'
	
	WHERE
	Dat_Per_ID = '$PerID';";
//echo $sql;
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
	else
	{
		
		$sql = "INSERT INTO PersonaDatosPadre
	(Dat_Per_ID, 
	Dat_Dom_Pro_ID, 
	Dat_Dom_Pai_ID, 
	Dat_Dom_Loc_ID, 
	Dat_Nac_Pro_ID, 
	Dat_Nac_Pai_ID, 
	Dat_Nac_Loc_ID, 
	Dat_Nacimiento, 
	Dat_Domicilio, 
	Dat_CP, 
	Dat_Email, 
	Dat_Telefono, 
	Dat_Celular, 
	Dat_Ocupacion, 
	Dat_Observaciones, 
	Dat_Fecha, 
	Dat_Hora
	)
	VALUES
	('$PerID', 
	'$DomProID', 
	'$DomPaisID', 
	'$DomLocID', 
	'$NacProID', 
	'$NacPaisID', 
	'$NacLocID', 
	'$Dat_Nacimiento', 
	'$Dat_Domicilio', 
	'$Dat_CP', 
	'$Dat_Email', 
	'$Dat_Telefono', 
	'$Dat_Celular', 
	'$Dat_Ocupacion', 
	'$Dat_Observaciones', 
	CURDATE(), 
	CURTIME()
	);";
//echo $sql;
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		
	}

}

function mostrarDatosHijos()
{

$PerID=$_POST['PerID'];

//echo $PerID."asdasd";
?>
<script language="javascript">
$(document).ready(function(){
$("button").button();

$("#AtrasElegirHijos").click(function (){
	
	$.ajax({
					type: "POST",
					cache: false,
					async: false,
					url: 'cargarDatosPersonalesHijos.php',
					success: function(data){ 
					$("#principal").html(data);
					
					}
		});//fin ajax///
	
	}); //fin function
	
$("#editarDatosAdicionales").click(function (){
	
		PerID=$("#PerID").val();
		//alert(PerID);
		auxiliar=2;
	
		$.ajax({
					type: "POST",
					cache: false,
					async: false,
					data: {opcion: 'editarDatosAdiccionalesPadre', PerID: PerID,auxiliar:auxiliar},
					url: 'cargarOpcionesDatosPersonalesPadre.php',
					success: function(data){ 
					$("#editarDatosPersonales2").html(data);
					
					}
		});//fin ajax///
	
	})//fin function


})//fin ready
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
<?php
$sql = "SELECT * FROM Persona WHERE Per_ID=$PerID";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
if (mysqli_num_rows($result)>0){
	$rowDatos = mysqli_fetch_array($result);
	$usuario_nombre = $rowDatos[Per_Nombre];
	$usuario_apellido = $rowDatos[Per_Apellido];
	$PerID = $rowDatos[Per_ID];


$sql="SELECT *
FROM
    personadatos
    JOIN pais 
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
		$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$sql12="SELECT * FROM persona WHERE Per_ID='$PerID'";
$result12 = consulta_mysql_2022($sql12,basename(__FILE__),__LINE__);
$row12 = mysqli_fetch_array($result12);
$foto = buscarFoto($row12[Per_DNI], $row12[Per_Doc_ID], 60);
		?>
        
        <input type="hidden" name="PerID" id="PerID" value="<?php echo $PerID ?>" />
         <table class="tablilla1" style="" width="80%" >   
          <tr><td rowspan="4" width="60px"><?php echo $foto ?></td></tr>  
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
<tr><td align="left"><button class="botones" id="AtrasElegirHijos">Atras</button></td><td align="right"><button class="botones" id="editarDatosAdicionales">Editar Datos Personales</button></td></tr>
</table>
</div>
<br />
<?php
}
}
?>