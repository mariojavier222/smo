<?php
require_once("conexion.php");
require_once("funciones_generales.php");
//require_once("cargarOpciones.php");
require_once("listas.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

	$PerID = $_POST['PerID'];
	//echo "Dato. $ClaID"; exit;
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	$sql = "SELECT DISTINCTROW * FROM 
	 Colegio_InscripcionClase
    INNER JOIN Colegio_Inscripcion 
        ON (IMa_Leg_ID = Ins_Leg_ID) AND (IMa_Lec_ID = Ins_Lec_ID)
    INNER JOIN Colegio_Clase 
        ON (IMa_Cla_ID = Cla_ID)
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID)
    INNER JOIN Lectivo 
        ON (Cla_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Mat_ID)
    INNER JOIN Curso 
        ON (Colegio_Clase.Cla_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Cla_Div_ID = Div_ID)
	INNER JOIN Colegio_Nivel
		ON (Ins_Niv_ID = Niv_ID)
		WHERE (Per_ID = $PerID AND Leg_Baja = 0 AND Mat_Convivencia = 0) ORDER BY Lec_Nombre, Cur_Nombre, Div_Nombre, Mat_Nombre";

//echo $sql;
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
	<script src="js/jquery.printElement.js" language="javascript"></script>

    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
    <link href="css/general.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>

<?php
function buscarEnArreglo($A, $P, $c){

		if ($c==0) return false;
		for($i=1;$i<=$c;$i++){
			if ($P[$i]['TriID']==$A){	
				return $i;
			}				
		}
		return false;//*/
}
if ($total>0){	
	//$NivID = $_POST['NivID'];
	Obtener_LectivoActual($LecID, $LecNombre);
	$NivID = Obtener_Nivel($LecID, $PerID);
	
	//Cargamos en un arreglo las distintas instancias del nivel y ciclo lectivo elegido
	require_once("class.instancia.php");
	$sql_ins = "SELECT * FROM
    Colegio_Instancia
    INNER JOIN Colegio_Trimestre 
        ON (Colegio_Instancia.Cia_Tri_ID = Colegio_Trimestre.Tri_ID) AND (Colegio_Instancia.Cia_Niv_ID = Colegio_Trimestre.Tri_Niv_ID) AND (Colegio_Instancia.Cia_Lec_ID = Colegio_Trimestre.Tri_Lec_ID) WHERE Tri_Lec_ID = $LecID AND Tri_Niv_ID = $NivID ORDER BY Cia_Tri_ID, Cia_Orden;";
		//echo $sql_ins;
	$result_ins = consulta_mysql_2022($sql_ins,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result_ins)>0){
		$Trimestres = array();
		$t = 0;
		while ($row_ins = mysqli_fetch_array($result_ins)){
			$k++;
			$Ins = new Instancia($row_ins[Cia_ID], $row_ins[Tri_ID], $row_ins[Cia_Lec_ID], $row_ins[Cia_Niv_ID], $row_ins[Cia_Nombre], $row_ins[Cia_Nombre], $row_ins[Cia_Orden], $row_ins[Cia_Opcional], $row_ins[Cia_Promedio], $row_ins[Tri_Nombre], $row_ins[Tri_Desde], $row_ins[Tri_Hasta]);
			$Instancias[$k] = $Ins;
			//Cargamos la cantidad de trimestre			

			$clave = buscarEnArreglo($row_ins[Tri_ID], $Trimestres, $t);
			//echo "$row_ins[Tri_ID] / $clave<br />";
			if (!$clave){
				$t++;
				$Trimestres[$t] = array('Nombre' => $row_ins[Tri_Nombre], 'Instancias' => 1, 'TriID' => $row_ins[Tri_ID]);
			}else{
				$Trimestres[$clave]['Instancias']++;
			}

		}//fin while
	}//fin if
	//Script para probar la asignación correcta de variables
	/*foreach($Trimestres as $valor){
		foreach($valor as $valor2){
			echo "$valor2<br />";
		}
	}//*/
	
	
	?>
<div class="texto">
<div align="left" class="titulo_noticia">
  <p><a name="arriba" id="arriba"></a><img src="imagenes/font_add.png" width="32" height="32" align="absmiddle" /> Calificaciones de 
  <?php 
  $DNIHijo = gbuscarDNI($PerID);
  gObtenerApellidoNombrePersona($DNIHijo, $Apellido, $Nombre, false);
  echo "$Nombre $Apellido";?></p>  
</div>
<div id="listado" >	
<div align="center" class="titulo_noticia">
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="50" align="center">&nbsp;<?php echo $market;?></td>
    </tr>
  </table>
 
</div>
 
 <table width="100%" border="0" id="listadoTabla" class="display texto">
    <thead>
        <tr>
          <th rowspan="2" align="center" class="fila_titulo">#</th>
          <th rowspan="2" align="center" class="fila_titulo">Materia</th>          
          <?php
          for($cant=1;$cant<=$t;$cant++){
			  $col = $Trimestres[$cant]['Instancias'] + 1;
		  ?>
          <th colspan="<?php echo $col;?>" align="center" class="fila_titulo ocultar"><?php echo $Trimestres[$cant]['Nombre']."";?></th>
          <?php
		  }//fin for
		  ?>         
          <th colspan="<?php echo $t;?>" align="center" class="fila_titulo ocultar">No Aprob&oacute;</th>
          <th colspan="2" align="center" class="fila_titulo ocultar">FINALES</th>
        </tr>
        <tr>
        <?php
          for($cant=1;$cant<=$k;$cant++){
		  		  	
			if ($cant>1 && $Instancias[$cant]->TriID!=$Tri){
				?>
                <th align="center" class="fila_titulo">PROM. <?php echo $TriNombre;?></th>
                <?php
			}
			$Tri = $Instancias[$cant]->TriID;
			$TriNombre = $Instancias[$cant]->Trimestre;
		  ?>
          <th align="center" class="fila_titulo ocultar" title="<?php echo $Instancias[$cant]->Nombre;?>"><?php
          echo $Instancias[$cant]->Siglas;
		  ?></th>          
          <?php
  			if ($cant==$k){
				?>
                <th align="center" class="fila_titulo">PROM. <?php echo $TriNombre;?></th>
                <?php
			}

		  }//fin for
		  ?>
		<?php
          for($cant=1;$cant<=$t;$cant++){			  
		  ?>
          <th align="center" class="fila_titulo"><?php echo $cant;?></th>
          <?php
		  }//fin for
		  ?> 
          <th align="center" class="fila_titulo">INST.</th>
          <th align="center" class="fila_titulo">CALIF.<br />FINAL</th>

        </tr>  
              
      </thead>
       <tbody>
	<?php $i=0;
	
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		$p=0;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
		$ClaID = $row[Cla_ID];
		      
				  ?>
      	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      	  <td><?php echo $i;?></td>
      	  <td><?php echo "$row[Mat_Nombre]";?></td>
      <?php
         //for($iCiclo=1; $iCiclo<=$t;$iCiclo++){
		  for($cant=1;$cant<=$k;$cant++){

		  if ($cant>1 && $Instancias[$cant]->TriID!=$Tri){
		  ?>
          <td align="center"><strong><?php 
		  $p++;
		  $promPer[$p] = obtenerPromedioTrimestre($row[IMa_Leg_ID], $LecID, $ClaID, $Tri);
		  echo $promPer[$p];
		  ?></strong></td>          
          <?php
		  }//fin if
		  $Tri = $Instancias[$cant]->TriID;
		  ?>
          <td align="left" class="ocultar"><?php
          cargarNotaClaseAlumnoTabla($row[IMa_Leg_ID], $LecID, $ClaID, $Instancias[$cant]->CiaID);
		  ?></th>          
          <?php
  			if ($cant==$k){
				?>
                <td align="center"><strong>
				<?php 
				$p++;
		 	 	$promPer[$p] = obtenerPromedioTrimestre($row[IMa_Leg_ID], $LecID, $ClaID, $Tri);
		  		echo $promPer[$p];								
				?>
                </strong></td>          
                <?php
			}

          }//fin for
		  
		  //Mostramos el resultado de los trimestres aprobados
		  for($cant=1;$cant<=$p;$cant++){
			  ?>
                <td align="center"><strong>
				<?php 
			  echo obtenerNotaEstado($promPer[$cant]);
			  ?>
                </strong></td>          
                <?php
		  }//fin for trimestres apropados
		  
		  
		  //Calculamos si va al POE
  		  $APR = 0;
		  $AUS = 0;
		  $APL = 0;
		  $REP = 0;

		   for($cant=1;$cant<=$p;$cant++){
			  $cond = obtenerNotaEstado($promPer[$cant],false);
			  $$cond++;
			  
		  }//fin for 
		  $resultado = '';		  
		  if ($APR>0 && $APR<$p) {
			  if ($APR>=1) $resultado = "DIC";
			  if ($APL==2) $resultado = "DIC.";
			  
		  }
		  if ($REP==2 && $APL==1) $resultado = "DIC.";
		  if ($REP==1 && $APL==2) $resultado = "MARZO";
		  if ($APR==$p) $resultado = "...";
		  if ($REP==$p) $resultado = "DIC";
		  if ($APL==$p) $resultado = "MARZO";
		   ?>
                <td align="center"><strong>
				<?php echo $resultado;?>
                </strong></td>          
                <?php
		  //FIn Calculamos si va al POE
		  
		  //Calculamos el promedio de la Calif. Final
		  $suma = 0;
		  for($cant=1;$cant<=$p;$cant++){
			  $suma += $promPer[$cant];			  			  
		  }//fin for 
		  ?>
                <td align="center"><strong>
				<?php 
			  $promedio = floatval($suma / $p);
			  echo round($promedio,2);
			  ?>
                </strong></td>          
                <?php
		  //fin promedio de la Calif. Final
		  ?>
          
      </tr>
    
		  <?php		 
			
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="<?php echo $k + 10;?>" class="fila_titulo"> 
</th>
        </tr>        
    </tfoot>
</table>
</div>
<table border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="48">&nbsp;</td>
    <td width="48">&nbsp;</td>
    <td width="100" align="center"><a href="#arriba"><img src="imagenes/go-up.png" alt="Ir arriba" title="Ir arriba" width="22" height="22" border="0" /></a></td>
  </tr>
</table>
<fieldset class="recuadro_inferior" style="height:32px">
  <div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total materias";?></div>
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
	$("#botOcultar").button();
	$("#botOcultar").click(function(evento) {
		evento.preventDefault();
		$("#cargando").show();
		$(".ocultar").slideToggle();
		$("#cargando").hide();
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