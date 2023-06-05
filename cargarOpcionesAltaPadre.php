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

switch ($opcion) {
	
    case "mostrarDatos":
        mostrarDatos();
        break;
	case "comprobarDNI":
        comprobarDNI();
        break;
	case "guardarDatosPersonaUsuario":
        guardarDatosPersonaUsuario();
        break;
	case "BuscarPadre":
        BuscarPadre();
        break;
	case "restablecerContrasenaPadre":
        restablecerContrasenaPadre();
        break;
		
}

function mostrarDatos()
{
	
	$PerID = $_POST['PerID'];
	//echo "mostrarDatos.".$PerID;
	$DNI = $_POST['DNI'];
	?>
	    <script type="text/javascript">
			$("#DNI").focusout(function(evento){
			
		evento.preventDefault();
		
		DNI=$("#DNI").val();
		//alert(DNI);
		
		$.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'comprobarDNI',DNI:DNI},
                url: 'cargarOpcionesAltaPadre.php',
                success: function(data){ 
						var DNI = data
						//alert(data)
						if(data=='Ya existe esa Persona cargada en el sistema')
						{
                         jAlert(data, "ERROR");
						//$("#cuentaUsuario").html('usuario');
						//$("#cuentaClave").html('Clave');
						//$("#DNI").val("");
						//$("#DNI").focus();
						$("#guardarDatos").hide();
						}
						 else
						 {
						$("#cuentaUsuario").text(DNI);
						$("#cuentaClave").text(DNI);
						$("#guardarDatos").show();
						 }
               
                }
            });//fin ajax////
			return false;
	}); //fin function
	
		$("#restablecerContrasenaPadre").click(function(evento){
		
	DNI = $("#DNI2").val();	
	//alert(DNI);
	//return false;
	jConfirm('¿Está seguro que desea Restablecer Contraseña?', 'Confirmar Operacion', function(r){
    			if (r){
		$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'restablecerContrasenaPadre', DNI: DNI},
				url: 'cargarOpcionesAltaPadre.php'
				//success: function(msg){ alert(msg);	}
			});//fin ajax///
			jAlert("Se ha restablecido correctamente la contraseña", "Resultado de la operación");
	}	
	})
	return false;
	});
		</script>
	<?php
	if($PerID)
	{
		
		 $sql = "SELECT * FROM Persona WHERE Per_ID=$PerID AND Per_DNI=$DNI;";
         $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
         if (mysqli_num_rows($result) > 0) {//existe
		 $row = mysqli_fetch_array($result);
		 
		$Per_DNI=$row[Per_DNI];
		$Per_Doc_ID=$row[Per_Doc_ID];
		$Per_Apellido=$row[Per_Apellido];
		$Per_Nombre=$row[Per_Nombre];
		$Per_Sexo=$row[Per_Sexo];
		
		  }//if
		 
		 ?>
                  <script type="text/javascript">
				
				$("#DNI2").text('<?php echo $Per_DNI ?>');
				$("#Apellidos").val('<?php echo $Per_Apellido ?>');
				$("#Nombre").val('<?php echo $Per_Nombre ?>');
				$("#Sexo").val('<?php echo $Per_Sexo ?>');
				$("#DocID").val('<?php echo $Per_Doc_ID ?>');
					$("#cuentaUsuario").text('<?php echo $Per_DNI ?>');
					$("#cuentaClave").text('<?php echo $Per_DNI ?>');
						//$("#").text(DNI);
						$("#guardarDatos2").show();
						
						
		 </script>
         <?php
		 
		$sql = "SELECT *
FROM
    Familia
    INNER JOIN Persona 
        ON (Fam_Vin_Per_ID = Per_ID) WHERE Fam_Per_ID=$PerID AND Fam_FTi_ID=2";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		mysqli_num_rows($result);
		while($row = mysqli_fetch_array($result))
		{
		?>
         <script type="text/javascript">
		
		DNI_Vinc='<?php echo $row[Per_DNI] ?>'
		persona_vinc='<?php echo $row[Per_Apellido].", ".$row[Per_Nombre] ?>'
		if (DNI_Vinc && persona_vinc){
			Vinculos="Hijo/Hija";
			FTiID=2
			encontrado=false;
			opcion = "(" + Vinculos + ") " + persona_vinc;
			id = DNI_Vinc + "," + FTiID;
			getNuevoCombo= "<option value='" + id + "'>" + opcion + "</option>";
			$("#Vinculos option").each(function(i){			 
			   valor = $(this).val();
			   arreglo = valor.split(",");
			   if (arreglo[0]==DNI_Vinc)
				  encontrado=true;
			});
			$("#persona").val("");
			$("#persona").focus();
			if (encontrado==false) 
			  $("#Vinculos").append(getNuevoCombo);
		 	}
			
		 </script>
        <?php	
		}
		 
		 $Titulo="Editar";
		
		 

		 
		 //USUARIOS
		 /*
		 $sql = "SELECT	* FROM Usuario WHERE Usu_Nombre=$DNI;";
         $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
         if (mysqli_num_rows($result) > 0) {//existe
		 $row = mysqli_fetch_array($result);
		 
		 $Per_DNI=$row[Per_DNI];
		 $Per_Doc_ID=$row[Per_Doc_ID];
		 $Per_Apellido=$row[Per_Apellido];
		 $Per_Nombre=$row[Per_Nombre];
		 $Per_Sexo=$row[Per_Sexo];
		 
		 
		 
		 }
		*/
		
		
	} //FIN IF PER_ID
	else
	{
		$Titulo="Crear";
	}
	
	?>
    <link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
    <script type="text/javascript">
	$(document).ready(function(){
		
		$(".botones").button();
		
	
	$("#persona2").result(colocarValor);	
	$("#persona2").autocomplete("buscarDatosPersona.php", {
		//multiple: true,
		mustMatch: false,
		minChars: 1,
		max: 50,		
		formatItem:function(item, index, total, query){
		   return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatMatch:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },
       formatResult:function(item){
           return item.Per_Apellido + ', ' + item.Per_Nombre;
       },

		dataType: "json",
		//parse: prep_data,
		parse:function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.Per_Apellido,
					result: row.Per_Apellido + ", " + row.Per_Nombre
				}
				$("#cargando").hide();
			});
		},//*/
		selectFirst: false,
		autoFill: true
	});
	function colocarValor(event, data, formatted) {
		if (data){
			$("#DNI_Vinc").val(data.Per_DNI);
			$("#PerID").val(data.Per_ID);
			//$("#mostrar").empty();
		}
	}
	
	
		$("#agregar").click(function(evento){
		evento.preventDefault();
		DNI_Vinc=$("#DNI_Vinc").val();
		persona_vinc=$("#persona2").val();
		if (DNI_Vinc && persona_vinc){
			Vinculos="Hijo/Hija";
			FTiID=2
			encontrado=false;
			opcion = "(" + Vinculos + ") " + persona_vinc;
			id = DNI_Vinc + "," + FTiID;
			getNuevoCombo= "<option value='" + id + "'>" + opcion + "</option>";
			$("#Vinculos option").each(function(i){			 
			   valor = $(this).val();
			   arreglo = valor.split(",");
			   if (arreglo[0]==DNI_Vinc)
				  encontrado=true;
			});
			$("#persona2").val("");
			$("#persona2").focus();
			if (encontrado==false) 
			  $("#Vinculos").append(getNuevoCombo);
		 	}
		});
		
		
		$("#eliminarVinculo").click(function (evento){
		evento.preventDefault();
		//alert($("#Vinculos option:selected").val());
		$("#Vinculos option:selected").remove();
	});
	
	
	}); //fin document ready
	</script>
    
    <?php
    obtenerRegistroUsuario($UsuID,$Fecha,$Hora);
	?>
    <form id="formularioNuevo">
    <input type="hidden" name="UsuID" id="UsuID" value="<?php echo $UsuID ?>" />
    <input type="hidden" name="PerID33" id="PerID33" value="<?php echo $PerID ?>" />
	<table width="80%" border="0" align="center" class="borde_recuadro">
       <tr>
          <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/hombre-mujer.png" width="32" height="32" align="absmiddle" /><?php echo $Titulo ?> un Padre </div>
          </td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Tipo de Documento: </div></td>
          <td>
            <?php cargarListaTipoDoc("DocID");?>         </td>
        </tr>
        <?php  if(!$PerID){ ?>
        <tr>
          <td class="texto"><div align="right">N&ordm; Documento: </div></td>
        
          <td><input type="text" name="DNI" id="DNI" /></td>
         
        </tr>
         <?php }
		 else
		 {
			 ?>
             <input type="hidden" name="DNI2" id="DNI2" value="<?php echo $DNI ?>" />
             <?php
		 }
		  ?>
         <tr>
          <td class="texto"><div align="right">Apellidos:</div></td>
          <td><input name="Apellidos" type="text" id="Apellidos"  /> 
          </td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Nombres:</div></td>
          <td><input name="Nombre" type="text" id="Nombre" /></td>
        </tr>
        <tr>
          <td class="texto"><div align="right">Sexo:</div></td>
          <td><select name="Sexo" id="Sexo">
            <option value="-1">Elegir una opci&oacute;n</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
          </select>
          </td>
        </tr>
         <tr>       
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Datos de la cuenta de usuario</div></td>
        </tr>
       <tr>
         <td class="texto"><div align="right">Usuario por defecto (DNI):</div></td>
        <td colspan="2"><div id="cuentaUsuario" class="ui-state-highlight ui-corner-all" style="width:200px; font-size:14px" align="center">usuario</div>
        </tr>		
	<tr>
         <td class="texto"><div align="right">Clave por defecto (DNI):</div></td>
        <td colspan="2"><div id="cuentaClave" class="ui-state-highlight ui-corner-all" style="width:200px; font-size:14px" align="center">Clave</div>
</td>
<tr><td></td><td>
        <?php
		if($PerID)
		{
			?>
            <button style="font-size:14px" class="botones" id="restablecerContrasenaPadre">Restablecer Contraseña</button>
            <?php
		}
		?>
        </td>
        </tr>
          <tr>       
         <td colspan="3" bgcolor="#FFCC00" class="texto"><div align="center" class="titulo_noticia">Agregar Hijos</div></td>
        </tr>
	  <tr>
	    <td class="texto"><div align="right">Buscar Agregar Hijo:</div></td>
          <td><input name="persona2" type="text" id="persona2" size="35" /> 
           <input name="PerID" type="hidden" id="PerID" />
           <input name="DNI_Vinc" type="hidden" id="DNI_Vinc" />   
                 
            <button id="agregar" class="botones">Agregar</button>
             </td>
             <tr>
             <td rowspan="2" align="right" class="texto"><div align="right">Se vincula con... </div>
         <p><a href="#" id="eliminarVinculo"><img src="imagenes/folder_delete.png" alt="Eliminar Vinculo" width="32" height="32" border="0" /></a></p></td>
                  <td rowspan="2">       
           <select name="Vinculos" size="5" multiple="multiple" class="bordeLista" id="Vinculos">
        </select>  
        </td>
             </tr>

        </table>
        <div id="mostrarresueltado"></div>
        	
            </form>
         
        <?php
}


function comprobarDNI()
{
	$DNI = $_POST['DNI'];
	//echo $DNI;
	 $sql = "SELECT * FROM Persona WHERE Per_DNI='$DNI';";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if (mysqli_num_rows($result) == 0) {
             echo $DNI;  
            }
			else
			{
				 echo "Ya existe esa Persona cargada en el sistema";
			}
}

function guardarDatosPersonaUsuario()
{
	$DNI = $_POST['DNI'];
	$Apellidos = strtoupper(addslashes(utf8_decode($_POST['Apellidos'])));
	$Nombre = ucwords(strtolower(addslashes(utf8_decode($_POST['Nombre']))));
	//$Apellidos = $_POST['Apellidos'];
	//$Nombre = $_POST['Nombre'];
	$Sexo = $_POST['Sexo'];
	$DocID = $_POST['DocID'];
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$PerID = $_POST['PerID'];
	//echo "PerID".$PerID."<br />";
	if($PerID!="")
	{
	$sql = "UPDATE Persona 
	SET
	Per_Doc_ID = '$DocID' , 
	Per_Apellido = '$Apellidos' , 
	Per_Nombre = '$Nombre' , 
	Per_Sexo = '$Sexo'
	WHERE
	Per_ID = '$PerID' ;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	}
	else
	{
	
	
	
	$sql = "INSERT INTO Persona 
	(
	Per_Doc_ID, 
	Per_DNI, 
	Per_Apellido, 
	Per_Nombre, 
	Per_Sexo, 
	Per_Foto, 
	Per_Fecha, 
	Per_Hora
	)
	VALUES
	(
	'$DocID', 
	'$DNI', 
	'$Apellidos', 
	'$Nombre', 
	'$Sexo', 
	'Per_Foto', 
	'$Fecha', 
	'$Hora'
	);";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$sql12 = "select last_insert_id()";
	$result12 = consulta_mysql_2022($sql12,basename(__FILE__),__LINE__);
	$row12 = mysqli_fetch_array($result12);
	$Per_ID=$row12[0];
	
	//echo "Per_ID".$Per_ID;
	
	$Usu_Persona=$Nombre." ".$Apellidos;
	$Usu_Clave=md5($DNI);
	$sql = "INSERT INTO Usuario 
	(
	Usu_Nombre, 
	Usu_Persona, 
	Usu_Clave,
	Usu_Sed_ID
	)
	VALUES
	(
	'$DNI', 
	'$Usu_Persona', 
	'$Usu_Clave',
	'1'
	);";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql12 = "select last_insert_id()";
	$result12 = consulta_mysql_2022($sql12,basename(__FILE__),__LINE__);
	$row12 = mysqli_fetch_array($result12);
	$UsuID=$row12[0];
	$Rol = 11;//Rol de Padre de Colegio
	guardarRolUsuario($UsuID, $Rol);
	}
}

function BuscarPadre()
{
	?>
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
    <script type="text/javascript">
	
	$(document).ready(function(){
		
		function cargarDNI(){
		vDNI = $("#DNI2").val();
		//alert("");
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "obtenerApellidoNombre", DNI: vDNI, conDNI: "true"},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#persona").val(data);
				//alert("no entre");
			}
		});//fin ajax//*/
		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			error: function (XMLHttpRequest, textStatus){
				alert(textStatus);},
			data: {opcion: "buscarPerID", DNI: vDNI},
			url: 'cargarOpciones.php',
			success: function(data){ 
				$("#PerID2").val(data);
				//alert("no entre");
			}
		});//fin ajax//*/

	}
	$("#DNI2").keyup(function(evento){	
		//evento.preventDefault();
		vDNI = $("#DNI2").val();
		//alert("Enter");
		if (evento.keyCode == '13'){
			
			$("#mostrar").empty();
			$("#cargando").show();
			cargarDNI();
			$("#cargando").hide();
		}
	});
		
	$("#persona").result(colocarValor);	
	$("#persona").autocomplete("buscarDatosPersona.php", {
	//multiple: true,
	mustMatch: false,
	minChars: 1,
	max: 50,		
	formatItem:function(item, index, total, query){
	return item.Per_Apellido + ', ' + item.Per_Nombre;
	},
	formatMatch:function(item){
	return item.Per_Apellido + ', ' + item.Per_Nombre;
	},
	formatResult:function(item){
	return item.Per_Apellido + ', ' + item.Per_Nombre;
	},
	
	dataType: "json",
	//parse: prep_data,
	parse:function(data) {
	return $.map(data, function(row) {
	return {
	data: row,
	value: row.Per_Apellido,
	result: row.Per_Apellido + ", " + row.Per_Nombre
	}
	$("#cargando").hide();
	});
	},//*/
	selectFirst: false,
	autoFill: true
	});
	
	function colocarValor(event, data, formatted) {
	if (data){
	$("#DNI2").val(data.Per_DNI);
	$("#PerID2").val(data.Per_ID);
	$("#mostrar").empty();
	}
	}
	
	$(".botones").button();
	
	$("#barraPadreMostrar").click(function(){
		
		
		PerID=$("#PerID2").val();
		DNI=$("#DNI2").val();
		
				$.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'mostrarDatos',PerID:PerID,DNI:DNI},
                url: 'cargarOpcionesAltaPadre.php',
                success: function(data){ 
                   
                        //alert(data);
						$("#MostrarEditarDatosPadre").html(data);
                        //mostrarAlerta(data, "Resultado de guardar los datos");
                  
                }
            });//fin ajax////
		
		})
	
	
});//fin de la funcion ready
	
	</script>
    
   <table width="80%" border="0" align="center" class="borde_recuadro">
          <tr>
          <td colspan="2"><div align="center" class="titulo_noticia"><img src="imagenes/hombre-mujer.png" width="32" height="32" align="absmiddle" />Buscar un Padre </div>
          </td>
        </tr>
     <tr>
	    <td width="50%" class="texto"><div align="right"><strong>DNI   :</strong></div></td>
          <td>
          <input name="DNI2" type="text" class="texto_buscador" id="DNI2" size="15" />
        *       
          <input name="PerID2" type="hidden" id="PerID2" />
          </td>
      </tr>
	  <tr>
	    <td class="texto"><div align="right"><strong>Persona   :</strong></div></td>
          <td><input name="persona" type="text" id="persona" size="35" />          </td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="texto"><button class="botones" id="barraPadreMostrar">Mostrar Padre</button>
        </td>
    </table>
    <br />
    <div id="MostrarEditarDatosPadre"></div>
    <?php
}


function restablecerContrasenaPadre()
{
	$DNI = $_POST['DNI'];
	$DNI2=md5($DNI);
	$sql = "UPDATE Usuario 
	SET
	Usu_Clave = '$DNI2' 
	WHERE Usu_Nombre=$DNI";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
}
/*
function EditarDatosPersonaUsuario()
{
	$DNI = $_POST['DNI'];
	$Apellidos = $_POST['Apellidos'];
	$Nombre = $_POST['Nombre'];
	$Sexo = $_POST['Sexo'];
	$DocID = $_POST['DocID'];
	$Per_ID = $_POST['Per_ID'];
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	
	$sql = "UPDATE Persona 
	SET
	Per_Doc_ID = '$DocID' , 
	Per_DNI = '$DNI' , 
	Per_Apellido = '$Apellidos' , 
	Per_Nombre = '$Nombre' , 
	Per_Sexo = '$Sexo'
	WHERE
	Per_ID = '$Per_ID' ;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	$Usu_Persona=$Nombre." ".$Apellidos;
	$Usu_Clave=md5($DNI);
	
	$sql = "UPDATE Usuario 
	SET
	Usu_ID = 'Usu_ID' , 
	Usu_Sed_ID = '1' , 
	Usu_Nombre = '$DNI' , 
	Usu_Persona = '$Usu_Persona' , 
	Usu_Clave = '$Usu_Clave'
	WHERE
	Usu_Nombre = '$DNI';";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
}
*/