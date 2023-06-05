<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agenda de Clases</title>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" >  
<link rel='stylesheet' type='text/css' href='js/jquery.weekcalendar.css' />
<script type='text/javascript' src='js/calendar/jquery.min.js'></script>
<script type='text/javascript' src='js/calendar/jquery-ui.min.js'></script>

<script type='text/javascript' src="js/jquery.weekcalendar.js"></script>

<script language="javascript">
	/*var year = new Date().getFullYear();
	var month = new Date().getMonth();
	var day = new Date().getDate();//*/

	   
	$(document).ready(function() {
		//$('#calendar').weekCalendar();
		//$("#calendar").weekCalendar("today");
		
	
		<?php
		require_once("conexion.php");
		//include_once("comprobar_sesion.php");
		require_once("funciones_generales.php");

		$PerID = $_GET['PerID'];
		$LecID = $_GET['LecID'];
		
		$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//Cargamos los feriados del ciclo lectivo
		$sql = "SELECT * FROM Feriado WHERE Fer_Lec_ID = $LecID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$total = mysqli_num_rows($result);
		
		
		if ($total>0){	
			$mostrar = "[";
		   	//$mostrar .= ",";
			$i=0;
			$diaHoy = date("w");
			$fechaHoy = date("d/m/Y");
			while ($row = mysqli_fetch_array($result)){
				$fechaMostrar = $row[Fer_Fecha];
				$mostrar .= "{'id':'feriado',
				'start':'".$fechaMostrar."T00:00:00.000+10:00',
				'end':'".$fechaMostrar."T18:59:59.000+10:00',
				'title':'Feriado: $row[Fer_Motivo] '}";
				if ($i<$total) 
					$mostrar .= ", ";

				
			}//fin while
			//$mostrar .= "]";
		}//fin if
		//*/
		
		
		$sql = "SELECT * FROM
			Colegio_InscripcionClase
    INNER JOIN Colegio_Clase 
        ON (Colegio_InscripcionClase.IMa_Cla_ID = Colegio_Clase.Cla_ID)
    INNER JOIN Colegio_Inscripcion 
        ON (Colegio_Clase.Cla_Lec_ID = Colegio_Inscripcion.Ins_Lec_ID) AND (Colegio_Clase.Cla_Niv_ID = Colegio_Inscripcion.Ins_Niv_ID) AND (Colegio_Clase.Cla_Cur_ID = Colegio_Inscripcion.Ins_Cur_ID) AND (Colegio_Clase.Cla_Div_ID = Colegio_Inscripcion.Ins_Div_ID) AND (Colegio_InscripcionClase.IMa_Lec_ID = Colegio_Inscripcion.Ins_Lec_ID) AND (Colegio_InscripcionClase.IMa_Leg_ID = Colegio_Inscripcion.Ins_Leg_ID)
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Horario 
        ON (Colegio_Horario.Hor_Cla_ID = Colegio_Clase.Cla_ID)
    INNER JOIN Colegio_Materia 
        ON (Colegio_Clase.Cla_Mat_ID = Colegio_Materia.Mat_ID)
			WHERE Leg_Per_ID = '$PerID' 
			AND Cla_Lec_ID = $LecID ORDER BY Hor_Dia_ID";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$total = mysqli_num_rows($result);
		
		
		if ($total>0){	
			//$mostrar = "[";
			$i=0;
			$diaHoy = date("w");
			$fechaHoy = date("d/m/Y");
			while ($row = mysqli_fetch_array($result)){
				/*$i++;
				$ndias = intval($row[Hor_Dia_ID]) - intval($diaHoy);
				$fechaMostrar = sumarFecha($fechaHoy,$ndias);
				$fechaMostrar = cambiaf_a_mysql($fechaMostrar);
				$mostrar .= "{'id':$row[Cla_ID],
			  'start':'".$fechaMostrar."T".$row[Hor_Inicio].".000+10:00',
			  'end':'".$fechaMostrar."T".$row[Hor_Fin].".000+10:00',
			  'title':'$row[Mat_Nombre] <br />($row[Cur_Siglas] $row[Div_Siglas])'}";
				
				if ($i<$total) 
					$mostrar .= ", ";
				//*/	
				$i++;
				for ($j=1;$j<=5;$j++){
					$ndias = intval($row[Hor_Dia_ID]) - intval($diaHoy);
					
					$fechaMostrar = sumarFecha($fechaHoy,$ndias);
					if ($j>1) {
						$ndias = 7 * ($j - 1); 
						$fechaMostrar = sumarFecha($fechaMostrar,$ndias);
					}
					$fechaMostrar = cambiaf_a_mysql($fechaMostrar);
					
					$mostrar .= "{'id':$row[Cla_ID],
			  		'start':'".$fechaMostrar."T".$row[Hor_Inicio].".000+10:00',
			  		'end':'".$fechaMostrar."T".$row[Hor_Fin].".000+10:00',
			  		'title':'$row[Mat_Nombre]'}";
					if ($j<5) 
						$mostrar .= ", ";
						
				}//fin for
				if ($i<$total) 
						$mostrar .= ", ";//*/
				
			}//fin while
			$mostrar .= "]";
		}else{
			$mostrar .= "{'id':1,
			  		'start':'".$fechaHoy."T07:00:00.000+10:00',
			  		'end':'".$fechaHoy."T18:59:59.000+10:00',
			  		'title':'Horarios no asignados'}]";
		}//fin if
		
		
		//$NivID = buscarNivelDocente($DocID, $LecID);
		
		
		
		/*
		//Cargamos los recreos del nivel del Docente
		$sql = "SELECT * FROM Colegio_HorarioModulo WHERE Mod_Niv_ID = $NivID AND Mod_Nombre LIKE '%receso%'";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		$total = mysqli_num_rows($result);
		
		
		if ($total>0){	
			//$mostrar = "[";
		   	$mostrar .= ",";
			$i=0;
			$diaHoy = date("w");
			$fechaHoy = date("d/m/Y");
			while ($row = mysqli_fetch_array($result)){
				$i++;
				for ($j=1;$j<=5;$j++){
					$ndias = intval($j) - intval($diaHoy);
					$fechaMostrar = sumarFecha($fechaHoy,$ndias);
					$fechaMostrar = cambiaf_a_mysql($fechaMostrar);
					$mostrar .= "{'id':'recreo',
				  	'start':'".$fechaMostrar."T".$row[Mod_Inicio].".000+10:00',
				  	'end':'".$fechaMostrar."T".$row[Mod_Fin].".000+10:00',
				  	'title':'$row[Mod_Nombre] '}";
					if ($i<=$total) 
						$mostrar .= ", ";
				}//fin for
				
			}//fin while
			$mostrar .= "]";
		}//fin if
		//*/
		?>
		function mostrarAlertaIframe(titulo, pID){
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				error: function (XMLHttpRequest, textStatus){
					alert(textStatus);},
				data: {opcion: "obtenerDatosClaseDocente", ID: pID},
				url: 'cargarOpciones.php',
				success: function(data){ 
					cuerpo = data;
				}
			});//fin ajax//*/
			cuerpo = "<p align='center'>" + cuerpo + "</p>";
			//alert(cuerpo);
			$("#dialog").html(cuerpo);
			$("#dialog").dialog({ draggable: true, hide: 'slide', title: titulo, zIndex: 3900, resizable: false, modal: false, 
				buttons: {
						'Aceptar': function() {
						$(this).dialog('close');
					}
				}//fin buttons
			});//fin dialog
		}//fin funcion		
		
		
		$('#calendar').weekCalendar({
			timeslotsPerHour: 3,
			use24Hour : true,
			readonly: true,
			businessHours: {start: 7, end: 13, limitDisplay: true},
			height: function($calendar){
				return $(window).height() - $("h1").outerHeight();
			},
			eventRender : function(calEvent, $event) {
				if(calEvent.end.getTime() < new Date().getTime()) {
					$event.css("backgroundColor", "#aaa");
					$event.find(".time").css({"backgroundColor": "#999", "border":"1px solid #888"});
				}
				if(calEvent.id=='recreo') {
					$event.css("backgroundColor", "#00ff00");
					$event.find(".time").css({"backgroundColor": "#ff0000", "border":"1px solid #888"});
					//$event.find(".title").css({"backgroundColor": "#ff0000", "border":"1px solid #888"});
				}
				if(calEvent.id=='feriado') {
					$event.css("backgroundColor", "#ff0000");
					$event.find(".time").css({"backgroundColor": "#FF8888", "border":"1px solid #888"});
					//$event.find(".title").css({"backgroundColor": "#ff0000", "border":"1px solid #888"});
				}
				
			},//*/
			
			eventClick : function(calEvent, $event) {
				//mostrarAlertaIframe("Clase elegida", calEvent.id);
				var $dialogContent = $("#event_edit_container");
				vID = calEvent.id;
				if (validarNumero(vID)){
					$.ajax({
						type: "POST",
						cache: false,
						async: false,
						error: function (XMLHttpRequest, textStatus){
							alert(textStatus);},
						data: {opcion: "obtenerDatosClaseDocente", ID: vID},
						url: 'cargarOpciones.php',
						success: function(data){ 
							cuerpo = data;
						}
					});//fin ajax//*/
					$("#title").html(cuerpo);
					$dialogContent.dialog({
						modal: true,
						title: "Clase elegida",
						close: function() {
						   $dialogContent.dialog("destroy");
						   $dialogContent.hide();
						   $('#calendar').weekCalendar("removeUnsavedEvents");
						},
						buttons: {					   
						   Aceptar : function() {
							  $dialogContent.dialog("close");
						   }
						}
					 }).show();
				}else{
					$("#title").html(calEvent.title);
					$dialogContent.dialog({
						modal: true,
						title: "Motivo del evento",
						close: function() {
						   $dialogContent.dialog("destroy");
						   $dialogContent.hide();
						   $('#calendar').weekCalendar("removeUnsavedEvents");
						},
						buttons: {					   
						   Aceptar : function() {
							  $dialogContent.dialog("close");
						   }
						}
					 }).show();
				}
				
			},			
			noEvents : function() {
				mostrarAlertaIframe("No hay clases asignadas");
			},
			data: <?php echo $mostrar;?>

		});

	});
function validarNumero(numero){
	if (!/^([0-9])*$/.test(numero))
		return false;
	else
		return true;
}//fin funcion

</script>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>

<body>
<?php //echo $mostrar;
gObtenerApellidoNombrePersona($PerID, $Apellido, $Nombre, true);
?>
<span class="titulo_noticia">Horario de clases de <?php echo "$Nombre";?></span>
<div id="calendar"></div>
  <div id="dialog"></div>
  <div id="event_edit_container">
         <div id="title"></div>
	</div>
</body>
</html>