<?php
require_once("conexion.php");
require_once("funciones_generales.php");
//require_once("cargarOpciones.php");
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
        ON (IMa_Cla_ID = Cla_ID)
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
    INNER JOIN persona 
        ON (Leg_Per_ID = Per_ID)
    INNER JOIN Lectivo 
        ON (Cla_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Materia 
        ON (Cla_Mat_ID = Mat_ID)
    INNER JOIN Curso 
        ON (Cla_Cur_ID = Cur_ID)
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
	obtenerDatosClase($ClaID, $Nivel, $Materia, $Curso, $Division, $NivID, $LecID);
	
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
			$Ins = new Instancia($row_ins[Cia_ID], $row_ins[Tri_ID], $row_ins[Cia_Lec_ID], $row_ins[Cia_Niv_ID], $row_ins[Cia_Nombre], $row_ins[Cia_Siglas], $row_ins[Cia_Orden], $row_ins[Cia_Opcional], $row_ins[Cia_Promedio], $row_ins[Tri_Nombre], $row_ins[Tri_Desde], $row_ins[Tri_Hasta]);
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
  <p><a name="arriba" id="arriba"></a><img src="imagenes/font_add.png" width="32" height="32" align="absmiddle" /> Calificaciones de los alumnos</p>  
</div>
<div id="listado" >	
<div align="left" class="titulo_noticia">
  <p>Materia: <?php echo $Materia;?></p>
  <p>Nivel: <?php echo $Nivel;?> <br />
Curso: <?php echo $Curso;?>  <?php echo $Division;?></p>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="50" align="center"> <button id="botOcultar">Ocultar/Mostrar<br />
notas parciales </button></td>
    </tr>
    <tr>
      <td align="center">&nbsp;<?php echo $market;?></td>
    </tr>
  </table>
 
</div>
 
 <table width="100%" border="0" id="listadoTabla" class="display texto">
    <thead>
        <tr>
          <th rowspan="2" align="center" class="fila_titulo">#</th>
          <th rowspan="2" align="center" class="fila_titulo">Apellido y Nombre</th>          
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
		
		      
				  ?>
      	<tr class="<?php echo $clase?>" id="fila<?php echo $i;?>" height="40px">
      	  <td><?php echo $i;?></td>
      	  <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>
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
			  if ($APR>=1) $resultado = "POE";
			  if ($APL==2) $resultado = "DIC.";
			  
		  }
		  if ($REP==2 && $APL==1) $resultado = "DIC.";
		  if ($REP==1 && $APL==2) $resultado = "MARZO";
		  if ($APR==$p) $resultado = "...";
		  if ($REP==$p) $resultado = "POE";
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
        <!--<tr>
          <th colspan="<?php echo $cant;?>" class="fila_titulo"> 
</th>
        </tr> -->       
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
	

	$("#botOcultar").button();
	$("#botOcultar").click(function(evento) {
		evento.preventDefault();
		$("#cargando").show();
		$(".ocultar").slideToggle();
		$("#cargando").hide();
	});
	
	

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