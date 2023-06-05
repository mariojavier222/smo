<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es_ES" lang="es_ES" class="wf-opensans-i3-active wf-opensans-i4-active wf-opensans-i6-active wf-opensans-i7-active wf-opensans-n4-active wf-opensans-n3-active wf-opensans-n6-active wf-opensans-n7-active wf-active"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<?php 
require_once("globalesConstantes.php");


//para poner el sitio en mantenimiento
global $gMantenimiento;
global $gFechaIniMant;
global $gFechaFinMant;
$fechaHoy=date("Y-m-d");
if ($fechaHoy>=$gFechaIniMant && $fechaHoy<=$gFechaFinMant){
    if ($gMantenimiento) {
        header("Location: index_mantenimiento.php");
    }
}
//************************************


?>
<title>Acceso a Napta Colegios -  <?php echo COLEGIO_NOMBRE;?></title><meta content="Acceso a Napta Colegio - <?php echo COLEGIO_NOMBRE;?>" name="Description"><meta content="Acceso a Napta Colegio - <?php echo COLEGIO_NOMBRE;?>" name="Keywords">
<meta name="revisit-after" content="5 days"><meta name="copyright" content="NAPTA"><meta name="publisher" content="NAPTA"><meta name="distribution" content="Global"><meta name="city" content="San Juan"><meta name="country" content="Argentina"><meta name="geography" content="San Juan - Argentina"><meta content="INDEX, FOLLOW" name="ROBOTS">
<link href="favicon.ico" rel="Shortcut Icon"><link href="favicon.gif" rel="icon" type="image/gif">
<!--<link href="https://donweb.com/es-ar/ingresar" rel="canonical">-->
<link xmlns="" type="text/css" href="index-nuevo/ingresar.css" rel="stylesheet">
<link xmlns="" type="text/css" href="index-nuevo/redmond/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
<script language="javascript" type="text/javascript"></script>
        
	<!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&subset=latin"></head><body id="body">-->
    <div class="wrap" style="background-image: url(fondos-web/fondo1.jpg); background-position: 100% 100%;"><div id="content">
    <!--<img xmlns="" alt="" src="index-nuevo/eliminar-cookies-dattatec.php">-->
    <p xmlns="" class="text_firma_container" style="position: absolute; left: 90px; bottom: 3%;"><span style="color: rgb(170, 170, 170); font-size: 12px; font-weight: 300; font-style: italic; text-decoration: none;">Bienvenidos a Napta Colegios - Versión Online 2.0</span></p>
<div xmlns="" class="text_img_container"><!--<a href="http://malvinas.donweb.com/" title="Ver más" id="fdo_description_lk" target="_blank"><img width="470" height="428" alt="" src="index-nuevo/malvinas_cong-txt.png" class="" id="fdo_description" style="position: absolute; right: 10%; bottom: 28%;"></a>-->
</div>

<div class="clear"></div>
</div>
<div xmlns="" id="access_box"><div class="access_box_interior">
<div class="logo"><a href="index-nuevo.html/" class="logo donweb" title="Napta Colegios"></a></div>
<div class="login_elements">
<h2>Acceso Privado</h2>
<form data-name="formDatos" name="formDatos" id="formDatos" action="" method="post" autocomplete="OFF">
<div>
<input name="Usuario" type="text" id="Usuario" placeholder="Email o Usuario o DNI" data-name="user" />
<input name="Clave" type="password" id="Clave" placeholder="Ingrese su Clave" data-name="pass" />
</div>
<a href="#" title="Ingresar" class="bt_ingresar" id="barraGuardar">Ingresar</a>
</form>

<a class="forget_lk" title="Reestablecer tu contrase&ntilde;a" href="#">&iquest;Olvidaste tu <br> 
contrase&ntilde;a?</a><a class="new_lk" title="Ingresar por primera vez" href="#">&iquest;Primera vez que ingresa?</a>
<div class="clear"></div>
</div>
<div class="login_spinner" style="display:none;">
<h2>Un momento por favor</h2>
<p class="fs13">Estamos verificando <br> la informaci&oacute;n ingresada...</p>
<span></span><img src="index-nuevo/ingresar_spinner.gif" width="54" height="55">
</div>
<div class="login_result" style="display:none;"></div>
</div></div>

<script type="text/javascript" src="index-nuevo/jquery-1.10.2.js"></script>
<script type="text/javascript" src="index-nuevo/jquery-ui-1.10.4.custom.min.js"></script>
<script src="index-nuevo/plugin/jquery.validate.js"></script>
<script src="index-nuevo/auxiliar.js"></script>

<!--<script type="text/javascript" src="JAjax.js"></script>-->
<script language="javascript">


	$.validator.setDefaults({
	submitHandler: function() { 
		Usuario = $("#Usuario").val();
		Clave = $("#Clave").val();
		
		//alert(Usuario);
		datos = $("#formDatos").serialize();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			data: {sesion_usuario: Usuario, sesion_clave: Clave},
            url: 'registro_sesion.php',
			success: function (data){
				//alert(data);
				data=data.trim();
				switch (data){	
				  case "Bien":
				  	document.location = "index.php";
					break;
				case "BienEspecial":
				  	document.location = "index.php";
					break;
				  case "error":
					  var ErrorSTR = 'Alguno de los datos ingresados no es correcto.<br />Por favor, verifícalo y vuelve a intentarlo.';
						$('#access_box .login_spinner').fadeOut('fast',function(){
							$('#access_box .login_elements').show();
							$('#access_box [data-name="user"],#access_box [data-name="pass"]').addClass('error');
							$('#access_box [data-name="user"]').parent().msgError({mensaje:ErrorSTR});
						});
						break;				  
					default:
						var ErrorSTR = 'Ocurri&oacute; un error al intentar ingresar al Sistema. Consulte con el administrador. Error: ' + data;
						$('#access_box .login_spinner').fadeOut('fast',function(){
							$('#access_box .login_elements').show();
							$('#access_box [data-name="user"],#access_box [data-name="pass"]').addClass('error');
							$('#access_box [data-name="user"]').parent().msgError({mensaje:ErrorSTR});
						});
				}//fin switch
				
			}
		});//fin ajax*/
	}
	});
    $(function(){
		
		$('#access_box [data-name="user"]').focus();
		
		$('#access_box [data-name="user"]').focus(function(evento){
			evento.preventDefault();
			$(this).removeClass('error');
			$.msgError.close();
		});
		$('#access_box [data-name="pass"]').focus(function(evento){
			evento.preventDefault();
			$(this).removeClass('error');
			$.msgError.close();
		});
		
		
		$("#formDatos").validate();//fin validation
		$("#barraGuardar").click(function(evento){
			evento.preventDefault();
			$(".login_elements").hide();
			$(".login_spinner").show();
			//alert("Bien");
			$("#formDatos").submit();
		});
		
		$("#Usuario, #Clave").keyup(function(evento){
            evento.preventDefault();
			var cClave = $("#Clave").val();
            if (evento.keyCode == 13 && cClave!=""){			
                $("#formDatos").submit();					
            }//fin if
        });//fin de prsionar enter		
		
		/*var Fondos = new Array();
		<?php //include_once("cargarFondos.php");?>
		iActual = 1;
		archivo = Fondos[iActual];
		$(this).everyTime(15000, function() {								   			  
			if (Total > 0){
				iActual++;
				if (iActual > Total) iActual = 1;  
				archivo = Fondos[iActual];
			}//fin if
				  $('.wrap')
					  .animate({opacity: 0}, 'slow', function() {
						  $(this)
							  .css({'background-image': 'url(./fondos-web/'+ archivo +')', 'backgroundPosition': '100% 100%'})
							  .animate({opacity: 1});
							  //alert(archivo);
					  });
						  
			});//fin del everyTime //*/
	});
</script>        
<!--<script type="text/javascript" src="index-nuevo/ingresar_librerias.js"></script>
<script type="text/javascript" src="index-nuevo/ingresar.js"></script>-->
</body></html>