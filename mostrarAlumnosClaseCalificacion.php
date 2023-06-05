<?php
require_once("conexion.php");
require_once("funciones_generales.php");
require_once("listas.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

	$ClaID = $_POST['ClaID'];
	//echo "Dato. $ClaID"; exit;
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT DISTINCTROW * FROM 
	 Colegio_InscripcionClase
    INNER JOIN Colegio_Inscripcion 
        ON (IMa_Leg_ID = Ins_Leg_ID) AND (IMa_Lec_ID = Ins_Lec_ID)
    INNER JOIN Colegio_Clase 
        ON (IMa_Cla_ID = Colegio_Clase.Cla_ID)
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN persona 
        ON (Leg_Per_ID = persona.Per_ID)
    INNER JOIN Lectivo 
        ON (Cla_Lec_ID = lectivo.Lec_ID)
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Mat_ID)
    INNER JOIN Curso 
        ON (Colegio_Clase.Cla_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Cla_Div_ID = Div_ID)
		WHERE (IMa_Cla_ID = $ClaID AND Leg_Baja = 0) ORDER BY Per_Sexo, Per_Apellido, Per_Nombre";


$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
	<script src="js/jquery.printElement.js" language="javascript"></script>

    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
    <link href="css/general.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>

<?php
if ($total>0){	
	obtenerDatosClase($ClaID, $Nivel, $Materia, $Curso, $Division, $NivID, $LecID);
	?>
<div class="texto">
<div align="center" class="titulo_noticia">
  <p><a name="arriba" id="arriba"></a><img src="imagenes/font_add.png" width="32" height="32" align="absmiddle" /> Calificaciones de los alumnos</p>  
</div>
<fieldset class="recuadro_simple" id="resultado_buscador">

<div id="listado" >	
<div align="center" class="titulo_noticia">
  <p>Materia: <?php echo $Materia;?></p>
  <p>Nivel: <?php echo $Nivel;?> <br />
Curso: <?php echo $Curso;?>  <?php echo $Division;?></p>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="50%" align="right" class="texto">Seleccionar instancia</td>
      <td align="left"><?php cargarListaInstanciaTrimestre("CiaID", $LecID, $NivID);?></td>
    </tr>
    <tr>
      <td align="right" class="texto">Fecha de la Evaluaci&oacute;n</td>
      <td align="left"><input type="text" name="FechaEval" id="FechaEval" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center"> <!--<button id="botReCargar">ReCargar página</button>--></td>
      </tr>
    <tr>
      <td colspan="2" align="center">

      <!--Comienzo codigo referencia-->
      <?php 
	  $sql = "SELECT DISTINCTROW Not_Siglas FROM Colegio_Notas";
	  $result_notas = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	  if (mysqli_num_rows($result_notas)>0){
		  $porc = 100 / mysqli_num_rows($result_notas);

	  ?>
      <table width="100%" border="0" cellspacing="1" cellpadding="1" class="borde_recuadro">
        <tr>
        	<td colspan="<?php echo mysqli_num_rows($result_notas);?>" class="fila_titulo">
            Escala de calificaciones permitidas</td>
        </tr>
        
        <tr>
        <?php 
		while ($row_notas = mysqli_fetch_array($result_notas)){
			
		?>
          <td width="<?php echo $porc;?>%" class="fila"><?php echo $row_notas[Not_Siglas];?></td>
         
         <?php
		}//fin while
		 ?>
        </tr>
      </table>
      
      <?php 
	  }//fin if
	  ?>
      <!--fin codigo referencia-->
      </td>
    </tr>
  </table>
  <p><div style="width:100px" class="barra_boton" id="botGuardar"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Guardar cambios</div></p>  
</div>
 
 <table width="100%" border="0" id="listadoTabla" class="display texto">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">#</th>
          <th align="center" class="fila_titulo">&nbsp;</th>
          <th align="center" class="fila_titulo">Apellido</th>
          <th align="center" class="fila_titulo">Nombre </th>
          <th width="15" align="center" nowrap="nowrap" class="fila_titulo">Sexo</th>
          <th align="center" class="fila_titulo">Ingresar nota</th>
          <th align="center" class="fila_titulo">Notas cargadas</th>
        </tr>
      </thead>
       <tbody>
	<?php $i=0;
	
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		      
				  ?>
      	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      	  <td><?php echo $i;?></td>
      	  <td><?php buscarTipoDoc($row[Per_DNI], $DocAluID);
	  	$foto = buscarFoto($row[Per_DNI], $DocAluID, 30);
		echo $foto;?></td>
      <td><?php echo $row[Per_Apellido];?></td>
      <td><?php echo $row[Per_Nombre];?></td>
      <td align="center"><?php if ($row[Per_Sexo]=="F") echo "Fem.";else echo "Masc.";?></td>
      <td align="center"><input name="LegID" type="hidden" id="LegID<?php echo $i;?>" value="<?php echo $row[IMa_Leg_ID];?>" />
        <input name="Nota" type="text" class="mayuscula" id="Nota<?php echo $i;?>" tabindex="<?php echo $i;?>" size="5" maxlength="3" AUTOCOMPLETE=OFF/><span id="error<?php echo $i;?>"></span></td>
      <td align="left"><div id="notasCargadas<?php echo $i;?>"></div></td>
      </tr>
    
		  <?php		 
			
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="7" class="fila_titulo"> 
</th>
        </tr>
        <tr>
          <th colspan="7">  <table border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="48"><div class="barra_boton" id="botGuardar2"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Guardar cambios</div></td>
    <td width="48"><div class="barra_boton" id="botEstadistica"><img src="imagenes/go-jump.png" alt="Calcular estadísticas" width="48" height="48" border="0" /><br />
        Calcular aprobados</div></td>
    <td width="100" align="center"><a href="#arriba"><img src="imagenes/go-up.png" alt="Ir arriba" title="Ir arriba" width="22" height="22" border="0" /></a></td>
  </tr>
</table>
            <p><div id="divEstaditica"></div></p>
          </th>
        </tr>
      </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total alumnos en esta clase";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>

<script language="javascript">
$(document).ready(function(){

	
 	vTotal = <?php echo $total;?>;
	vActual = 0;
	$("#FechaEval").datepicker();
	
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();
		
		$("#listado").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Calificaciones',overrideElementCSS:['js/demo_table_impresora.css', { href:'js/demo_table_impresora.css',media:'print'}]										
		});
		
		$("#cargando").hide();
	 });//fin evento click//*/
 
 		
	$("#barraExportar").click(function(evento){
		evento.preventDefault();
		jPrompt('Escriba el nombre del archivo a exportar:', 'listado', 'Exportar listado a Excel', function(r) {
    		if( r ){
				$("#archivo").val(r);
				$("#formExportarExcel").submit();
			} 
		});
		
	});
	
	$("input[name^='Nota']").attr("disabled", "disabled");
	
	function recargarPagina(){
		$("#mostrar").empty();

		$.ajax({
			type: "POST",
			cache: false,
			async: false,
			data: {ClaID: <?php echo $ClaID;?>},
			url: "<?php echo $_SERVER['PHP_SELF'];?>",
			success: function (data){
					$("#principal").html(data);
					$("#cargando").hide();
					}
		});//fin ajax
	}//fin function
	
	//$("input[name^='Nota']").attr("disabled", "");
	
	$("#botEstadistica").click(function(evento){
		evento.preventDefault();
		$("#cargando").show();
		vAprobado = 0;
		vReprobado = 0;
		vAusente = 0;
		vAplazado = 0;
		$("input[name^='Nota']").each(function(index){
			vNotSigla = $(this).val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: 'obtenerNotaTipoArray', NotSigla: vNotSigla},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					if (data){
						var vArreglo = data.split(";");
						var vTemp = vArreglo[0];//alert(vTemp);
						switch (vTemp){
							case '1': 
										vAprobado++;
										//alert(vAprobado);
										break;
							case '2': 
										vReprobado++;
										break;
							case '3': 
										vAusente++;
										break;
							case '4': 
										vAplazado++;
										break;
						}//fin switch
					}//*/
				}
			});//fin ajax//*/
			
		});
		var Datos = "Aprobados: " + vAprobado + "<br />Reprobados: " + vReprobado + "<br />Aplazados: " + vAplazado + "<br />Ausentes: " + vAusente;
		$("#divEstaditica").show();
		$("#divEstaditica").html(Datos);
		$("#cargando").hide();
		
	});
	$("#botGuardar, #botGuardar2").click(function(evento){
		evento.preventDefault();
		//alert("");return;
		vCant = 0;
		vLegID = 0;
		$("#cargando").show();
		$("input[name^='Nota']").each(function(index){
			vNotSigla = $(this).val();
			vCiaID = $("#CiaID").val();
			vFechaEval = $("#FechaEval").val();
			vID = this.id.substr(4,10);
			//alert(vNotSigla);
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: 'obtenerNotaTipoArray', NotSigla: vNotSigla},
				url: 'cargarOpciones.php',
				success: function(data){ 
					//alert(data);
					if (data){
						var vArreglo = data.split(";");
						vNotID = vArreglo[2];//alert(vTemp);
						vLegID = $("#LegID" + vID).val();
						//alert(vNotID);
						$.ajax({
							type: "POST",
							cache: false,
							async: false,
							error: function (XMLHttpRequest, textStatus){
								alert(textStatus);},
							data: {opcion: 'guardarNotaClase', LegID: vLegID, LecID: <?php echo $LecID;?>, ClaID: <?php echo $ClaID;?>, CiaID: vCiaID, NotID: vNotID, FechaEval: vFechaEval},
							url: 'cargarOpciones.php',
							success: function(data){ 
								if (data){									
									//alert(data);
									vCant++;
								}
							}
						});//fin ajax//*/
					}//fin data///
				}//fin success
			});//fin ajax//*/
			
		});
		mostrarAlerta("Total de notas cargadas: " + vCant, "Notas guardadas");
		cargarNotas();
		$("input[id^='Nota']").val("");
		$("#cargando").hide();
		
	});//*/
	

	$("input[id^='Nota']").focusin(function(evento){
		if (vActual==0) {
			vID = this.id.substr(4,10);
			vActual = vID;
		}
		//$("#Nota" + vActual).css("background-color", "#FF6");
		$(this).css("background-color", "#FF6");
		
		
		
	});
	$("input[name^='Nota']").focusout(function(evento){
		$(this).css("background-color", "#FFF");

	});//*/
	$("input[id^='Nota']").keyup(function(evento){						
		//alert("Entre");
		evento.preventDefault();
		vID = this.id.substr(4,10);
		var Valor = $(this).val();
		switch(evento.keyCode){
			
			case 13:
				
				if (validarDatoIngresado(Valor)){
					$("#error" + vID).hide();
					vActual = parseInt(vID) + 1;
					$(this).css("background-color", "#FFF");
					if (vActual>vTotal) vActual = 1;				
					$("#Nota" + vActual).css("background-color", "#FF6");
					$("#Nota" + vActual).focus();
				}else{
					//$(this).focus();
					$(this).css("background-color", "#FF0000");
					$("#error" + vID).show();
					$("#error" + vID).text("Incorrecta");
					$("#error" + vID).css("color", "#FF0000");
				}
				break;
			case 40:
				if (validarDatoIngresado(Valor)){
					$("#error" + vID).hide();
					vActual = parseInt(vID) + 1;
					$(this).css("background-color", "#FFF");
					if (vActual>vTotal) vActual = 1;				
					$("#Nota" + vActual).css("background-color", "#FF6");
					$("#Nota" + vActual).focus();
				}else{
					//$(this).focus();
					$(this).css("background-color", "#FF0000");
					$("#error" + vID).show();
					$("#error" + vID).text("Incorrecta");
					$("#error" + vID).css("color", "#FF0000");
				}

				break;//*/
			case 38:
				if (validarDatoIngresado(Valor)){
					$("#error" + vID).hide();
					vActual = parseInt(vID) - 1;
					$(this).css("background-color", "#FFF");
					if (vActual==0) vActual = vTotal;				
					$("#Nota" + vActual).css("background-color", "#FF6");
					$("#Nota" + vActual).focus();
				}else{
					//$(this).focus();
					$(this).css("background-color", "#FF0000");
					$("#error" + vID).show();
					$("#error" + vID).text("Incorrecta");
					$("#error" + vID).css("color", "#FF0000");
				}
				
				break;//*/
				
		}//fin switch
	});
	$("#CiaID").change(function() {
		$("#divEstaditica").hide();						
		if ($("#CiaID").val()==-1){
			$("input[name^='Nota']").attr("disabled", "disabled");
		}else{
			$("input[name^='Nota']").removeAttr("disabled");
			$("#Nota1").focus();
			$("#Nota1").css("background-color", "#FF6");		
			$("input[id^='Nota']").val("");
			cargarNotas();

		}
	});
	function cargarNotas(){
			$("input[name^='Nota']").each(function(index){
				vID = this.id.substr(4,10);
				vLegID = $("#LegID" + vID).val();
				vCiaID = $("#CiaID").val();
				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					error: function (XMLHttpRequest, textStatus){
						alert(textStatus);},
					data: {opcion: 'cargarNotaClaseAlumnoTabla', LegID: vLegID, LecID: <?php echo $LecID;?>, ClaID: <?php echo $ClaID;?>, CiaID: vCiaID},
					url: 'cargarOpciones.php',
					success: function(data){ 
						//alert(data);
						if (data){
							$("#notasCargadas" + vID).html(data);
						}else{
							$("#notasCargadas" + vID).html("");
						}
					}
				});//fin ajax//*/
			});
	}
	function validarDatoIngresado(dato){
		<?php
		 $sql = "SELECT DISTINCTROW Not_Siglas FROM Colegio_Notas";
		 $result_notas = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		 if (mysqli_num_rows($result_notas)>0){
			 $mostrar = "var lista = new Array(";
			$k=0;
		 	while ($row_notas = mysqli_fetch_array($result_notas)){
				$k++;
				$mostrar .= "'$row_notas[Not_Siglas]'";
				if ($k < mysqli_num_rows($result_notas))
					$mostrar .= ",";
			}//fin while
			$mostrar .= ");";
		 }//if
		?>
		//alert("<?php echo $mostrar;?>");
		<?php 
		echo $mostrar;
		?>
		dato = String(dato);
		if (vacio(dato)) return true;
		var pos = buscarItem(lista, dato);
		if (pos >=0){			
		   return true;
		}else{
			//mostrarAlerta("Por favor elija una nota v&aacute;lida", "Nota incorrecta");
			return false;
		}
		//var lista1 = new Array('Juan', 'Pedro', 'Luis', 'María', 'Julia');
	}//fin function
	
	function buscarItem(pLista, valor){
		var ind, pos;
		for(ind=0; ind<pLista.length; ind++)
		   {
			if (pLista[ind] == valor.toUpperCase())
			  break;
			}
		pos = (ind < pLista.length)? ind : -1;
		return (pos);
	} //fin function
	
	function vacio(cadena)
  	{                                    // DECLARACION DE CONSTANTES
    	var blanco = " \n\t" + String.fromCharCode(13); // blancos
                                       // DECLARACION DE VARIABLES
		var i;                             // indice en cadena
		var es_vacio;                      // cadena es vacio o no
		for(i = 0, es_vacio = true; (i < cadena.length) && es_vacio; i++) // INICIO
		  es_vacio = blanco.indexOf(cadena.charAt(i)) != - 1;
		return(es_vacio);
    }
	
	$("#divEstaditica").hide();

});//fin de la funcion ready
</script>

 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron alumnos inscriptos.</span>
<?php
}
?>
</div>