<?php
require_once("conexion.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();

//sleep(3);
	$LecID = $_POST['LecID'];
	$CurID = $_POST['CurID'];
	$NivID = $_POST['NivID'];
	$DivID = $_POST['DivID'];
	$PerID = $_POST['PerID'];
	$Turno = $_POST['Turno'];
	$Filtro = $_POST['Filtro'];

	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	$sql = "SELECT * FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
WHERE (Colegio_Inscripcion.Ins_Lec_ID = $LecID  ";//AND Leg_Baja = 0";
   	if (!empty($PerID)) {
		$sql.=" AND Persona.Per_ID = $PerID";
	}else{
		//if ($Turno!=999999) $sql.=" AND Cur_Turno = '$Turno' ";
		if ($CurID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Cur_ID =$CurID ";
		if ($NivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Niv_ID = $NivID ";
		if ($DivID!=999999) $sql.=" AND Colegio_Inscripcion.Ins_Div_ID = $DivID";
	}//fin else
	$sql.=") ORDER BY Niv_ID, Cur_ID, Div_ID, Per_Sexo, Per_Apellido, Per_Nombre;";
	//echo $sql;


$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$total = mysqli_num_rows($result);

?>
<script src="js/jquery.printElement.js" language="javascript"></script>
<!--	<script src="js/jquery.printarea.js" language="javascript"></script>-->
    <link href="js/demo_page.css" rel="stylesheet" type="text/css" />
<link href="js/demo_table.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.dataTables.js" language="javascript"></script>

<script language="javascript">
$(document).ready(function(){

		$("a[id^='verFotoAlumno']").click(function(evento){
            evento.preventDefault();
			fDNI = this.id.substr(13,15);
			//alert(fDNI);
			data = "<img src='fotos/grande/"+fDNI+".jpg' title='Foto' width='500' border='1' align='absmiddle' class='foto'/>";	  
			$("#dialog").html(data);
			$("#dialog").dialog({ draggable: true, hide: 'slide', title: 'Foto del Alumno', zIndex: 3900, resizable: true, modal: true, height: 'auto', width: 520, 
				buttons: {
					'Aceptar': function() {
						$(this).dialog('close');
					}
				}//fin buttons
			});//fin dialog          
            //alert(vDNI);
            
        });
	 
	 $("#imprimirTodas").click(function(evento){
		evento.preventDefault();

		vLectivo = $("#LecID option:selected").text();
		$("#listadoTabla").printElement({leaveOpen:true, printMode:'popup', pageTitle:'Listado de Inscriptos para el Ciclo Lectivo ' + vLectivo
		,overrideElementCSS:['js/demo_table_impresora.css',{ href:'js/demo_table_impresora.css',media:'print'}]
										
										});
//		$("div#listado").printArea(options);
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

	$('#listadoTabla3').dataTable( {
			"bPaginate": true,
			"aaSorting": [[ 2, "asc" ], [ 1, "asc" ]],
			//"sPaginationType": "full_numbers",
			//"bJQueryUI": true,
			//"sDom": '<"toolbar">frtip',
			//"sDom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
			"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true } );//*/
	//$("div.toolbar").html('Custom tool bar! Text/images etc.');


	/*$('#listadoTabla').dataTable( {
					"aaSorting": [[ 4, "desc" ]]
				} );//*/


});//fin de la funcion ready
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />
<?php
if ($total>0){	
	?>


<fieldset class="recuadro_simple" id="resultado_buscador">
  <legend>Resultado de la b&uacute;squeda</legend>
<div id="listado" class="page-break">	
<br />
<br />

 
 <table width="100%" border="0" id="listadoTabla" class="display">
    <thead>
        <tr>
          <th align="center" class="fila_titulo">Foto</th>
          <th align="center" class="fila_titulo">DNI</th>
          <th align="center" class="fila_titulo">Alumno</th>
          <th align="center" class="fila_titulo">Padre</th>
          <th align="center" class="fila_titulo">Madre</th>
          <th align="center" class="fila_titulo">Curso</th>
          <th align="center" class="fila_titulo">Deuda</th>
          <th align="center" class="fila_titulo">Estado</th>
        </tr>
       </thead>
       <tbody>
	<?php $i=0;
/*	$div = $row[Div_Nombre];
	$cur = $row[Cur_Nombre];
	$niv = $row[Niv_Nombre];//*/
	while ($row = mysqli_fetch_array($result)){		
		$i++;
		
		if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
		
	$Deuda = Obtener_Deuda_Sistema($row['Per_ID']);	
	if ($Filtro == 1){
		$seguir = true;
	}else{
		
		if ($Filtro == 2){		
			if ($Deuda>0)$seguir = true;else $seguir = false;
		}
		if ($Filtro == 3){		
			if ($Deuda==0)$seguir = true;else $seguir = false;
		}
	}
	if ($seguir){	
	obtenerTutores($row['Per_ID'],$arrarTutores, $cant);
	$Padre = "NO CARGADO";
	$Madre = "NO CARGADO";
	$Email = "";
	$EmailPadre = "";
	$EmailMadre = "";
	if ($cant>0){
		if ($cant==1){			
			$PPa_Tutor = $arrarTutores[1]['Per_Apellido'].", ".$arrarTutores[1]['Per_Nombre'];
			$PerID_Tutor = $arrarTutores[1]['Per_ID'];
			$tutorSexo = $arrarTutores[1]['Per_Sexo'];		
			$PPa_DNITutor = $arrarTutores[1]['Per_DNI'];
			$Email = $arrarTutores[1]['Email'];
			if ($tutorSexo=="M") {
				$Padre = $PPa_Tutor;
				$EmailPadre = $Email; 
			}else{
				$Madre = $PPa_Tutor;
				$EmailMadre = $Email;	
			}
		}else{
			$PPa_Tutor = $arrarTutores[1]['Per_Apellido'].", ".$arrarTutores[1]['Per_Nombre'];
			$PerID_Tutor = $arrarTutores[1]['Per_ID'];
			$tutorSexo = $arrarTutores[1]['Per_Sexo'];		
			$PPa_DNITutor = $arrarTutores[1]['Per_DNI'];
			$Email = $arrarTutores[1]['Email'];
			if ($tutorSexo=="M") {
				$Padre = $PPa_Tutor;
				$EmailPadre = $Email; 
			}else{
				$Madre = $PPa_Tutor;
				$EmailMadre = $Email;	
			}
			$PPa_Tutor = $arrarTutores[2]['Per_Apellido'].", ".$arrarTutores[2]['Per_Nombre'];
			$PerID_Tutor = $arrarTutores[2]['Per_ID'];
			$tutorSexo = $arrarTutores[2]['Per_Sexo'];		
			$PPa_DNITutor = $arrarTutores[2]['Per_DNI'];
			$Email = $arrarTutores[1]['Email'];
			if ($tutorSexo=="M") {
				$Padre = $PPa_Tutor;
				$EmailPadre = $Email; 
			}else{
				$Madre = $PPa_Tutor;
				$EmailMadre = $Email;	
			}
		}
	}else{
		$Padre = "NO CARGADO";
		$Madre = "NO CARGADO";
		$Email = "";
		$EmailPadre = "";
		$EmailMadre = "";
	}
	?>
	<tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>" height="40px" title="<?php echo $title;?>">
      <td><?php 
	  $foto = buscarFoto($row['Per_DNI'], $row['Per_Doc_ID'], 60);
	  echo $foto;?></td>
      <td align="center"><?php echo $row['Per_DNI'];//$row[Leg_Numero];?>
        <input type="hidden" id="DNI<?php echo $i;?>" value="<?php echo $row['Per_DNI'];?>" />
        <input type="hidden" id="Leg<?php echo $i;?>" value="<?php echo $row['Leg_ID'];?>" />
        <input type="hidden" id="Lec<?php echo $i;?>" value="<?php echo $row['Ins_Lec_ID'];?>" />
        <input type="hidden" id="PerID<?php echo $i;?>" value="<?php echo $row['Per_ID'];?>" />
        <input type="hidden" id="Niv<?php echo $i;?>" value="<?php echo $row['Ins_Niv_ID'];?>" />
        <input type="hidden" id="Padre<?php echo $i;?>" value="<?php echo $Padre;?>" />
        <input type="hidden" id="EmailPadre<?php echo $i;?>" value="<?php echo $EmailPadre;?>" />
        <input type="hidden" id="Madre<?php echo $i;?>" value="<?php echo $Madre;?>" />
        <input type="hidden" id="EmailMadre<?php echo $i;?>" value="<?php echo $EmailMadre;?>" />
        <input type="hidden" id="Deuda<?php echo $i;?>" value="<?php echo $Deuda;?>" />
        <input type="hidden" id="Alumno<?php echo $i;?>" value="<?php echo "$row[Per_Apellido], $row[Per_Nombre]";?>" />
        <input type="hidden" id="Inscrip<?php echo $i;?>" value="<?php echo $row['Ins_Provisoria'];?>" /></td>
      <td><?php echo "$row[Per_Apellido], $row[Per_Nombre]";?></td>      
      <td><?php echo "$Padre<br />$EmailPadre";?></td>
      <td><?php echo "$Madre<br />$EmailMadre";?></td>
      <td><?php echo $row['Cur_Siglas']." ".$row['Div_Siglas'];?></td>
      <td align="right">
      <?php 
	  $UsuID = $_SESSION['sesion_UsuID'];
	  if ($UsuID==2 || $UsuID==11 || $UsuID==12 || $UsuID==13){
			echo "$".number_format($Deuda,0,",",".");
	   }else{
			if ($Deuda>0) echo "AL DIA"; else echo "MORA";
		}
		?>
      
      </td>
      <td align="center" bgcolor="#FFFFFF">
      <?php if ($row['Ins_Provisoria']==0) { ?>
      <img src="imagenes/ins_definitiva.png" alt="Inscripci&oacute;n Definitiva" width="32" height="32" id="ImgIns<?php echo $i;?>" title="Inscripci&oacute;n Definitiva" />
      <?php
	  }else{
	  ?>
      <img src="imagenes/ins_provisoria.png" alt="Inscripci&oacute;n Provisoria" width="32" height="32" id="ImgIns<?php echo $i;?>" title="Inscripci&oacute;n Provisoria"/>
      <?php
	  }
	  ?>
	  </td>
    </tr>
    
		  <?php	
	 }//fin If seguir
	}//fin del while
	?>  
</tbody>
     <tfoot>
        <tr>
          <th colspan="7" class="fila_titulo"></th>
        </tr>
        </tfoot>
</table>
</div>
</fieldset>
<fieldset class="recuadro_inferior" style="height:32px">
<div align="left">
<a href="#" id="imprimirTodas"><img src="imagenes/printer.png" alt="Imprimir el listado" title="Imprimir el listado" width="32" border="0" align="absmiddle" /></a> - <a href="#" id="barraExportar"><img src="imagenes/icono_excel.gif" width="32" height="32" align="absmiddle"   border="0"/></a> - <?php echo "Se encontraron $total alumnos inscriptos";?></div>
<br /><br /></fieldset>

<form id="formExportarExcel" action="saveToExcel.php" method="post" target="_blank"
onsubmit='$("#datatodisplay").val( $("<div>").append( $("#listadoTabla").eq(0).clone() ).html() )' >
<input type="hidden" id="datatodisplay" name="datatodisplay" /><input name="archivo" id="archivo" type="hidden" value="" /></form>


 <?php
}else{
?>
	<div class="borde_alerta"><span class="texto"><img src="botones/Warning.png" alt="Advertencia" width="24" height="24" align="absbottom" />No se encontraron alumnos inscriptos.</span>
<?php
}

?>
