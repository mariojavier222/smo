<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es_ES" lang="es_ES" class="wf-opensans-i3-active wf-opensans-i4-active wf-opensans-i6-active wf-opensans-i7-active wf-opensans-n4-active wf-opensans-n3-active wf-opensans-n6-active wf-opensans-n7-active wf-active"><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<!--<script src="index-nuevo/webfont.js" type="text/javascript" async=""></script>-->
<title>Acceso a Napta Colegios - Excellence College</title><meta content="Acceso a Napta Colegio - Excellence College" name="Description"><meta content="Acceso a Napta Colegio - Excellence College" name="Keywords">
<meta name="revisit-after" content="5 days"><meta name="copyright" content="NAPTA"><meta name="publisher" content="NAPTA"><meta name="distribution" content="Global"><meta name="city" content="San Juan"><meta name="country" content="Argentina"><meta name="geography" content="San Juan - Argentina"><meta content="INDEX, FOLLOW" name="ROBOTS">
<link href="favicon.ico" rel="Shortcut Icon"><link href="favicon.gif" rel="icon" type="image/gif">
<!--<link href="https://donweb.com/es-ar/ingresar" rel="canonical">-->
<link xmlns="" type="text/css" href="index-nuevo/ingresar-padres.css" rel="stylesheet">
<link xmlns="" type="text/css" href="index-nuevo/redmond/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
<script language="javascript" type="text/javascript"></script>
        
	<!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&subset=latin"></head><body id="body">-->
    <div class="wrap"><div id="content">
    <!--<img xmlns="" alt="" src="index-nuevo/eliminar-cookies-dattatec.php">-->
    <p xmlns="" class="text_firma_container" style="position: absolute; left: 90px; bottom: 3%;"><span style="color: rgb(170, 170, 170); font-size: 12px; font-weight: 300; font-style: italic; text-decoration: none;">Bienvenidos a Napta Colegios para Excellence College - Versión Online 2.0</span></p>
<div xmlns="" class="text_img_container">
</div>

<div class="clear"></div>
</div>
<div xmlns="" id="principal_box"><div class="principal_box_interior">
<div class="logo"><a href="index-nuevo.html/" class="logo donweb" title="Napta Colegios"></a></div>
<div class="login_elements">
<h2>Darse de alta como padre/madre por primera vez</h2>
<form data-name="formDatos" name="formDatos" id="formDatos" action="" method="post">
<div>
<input type="text" data-name="DNI_Padre" name="DNI_Padre" id="DNI_Padre" placeholder="Ingrese su DNI" />
</div>
<a href="index-ingresar.php" title="Continuar" class="bt_ingresar" id="barraGuardar">Continuar...</a>
</form>
</div>


<div class="login_alta" style="display:none;">
<h2>Elija una clave para identificarse en el Sistema</h2>
<form data-name="formDatos" name="formDatos" id="formDatos" action="" method="post">
<div>
<input type="password" data-name="Clave_Padre" name="Clave_Padre" id="Clave_Padre" placeholder="Ingrese una clave" />
<input type="password" data-name="Clave_Padre" name="Clave2_Padre" id="Clave2_Padre" placeholder="Repita la clave" />
</div>
<a href="index-ingresar.php" title="Continuar" class="bt_ingresar" id="barraGuardar2">Continuar...</a>
</form>
</div>

<div class="login_hijo" style="display:none;">
<h2>Elija una clave para identificarse en el Sistema</h2>
<form data-name="formDatos" name="formDatos" id="formDatos" action="" method="post">
<div>
<input type="password" data-name="Clave_Padre" name="Clave_Padre" id="Clave_Padre" placeholder="Ingrese una clave" />
<input type="password" data-name="Clave_Padre" name="Clave2_Padre" id="Clave2_Padre" placeholder="Repita la clave" />
</div>
<a href="index-ingresar.php" title="Continuar" class="bt_ingresar" id="barraGuardar2">Continuar...</a>
</form>
</div>

<div class="login_spinner" style="display:none;">
<h2>Un momento por favor</h2>
<p class="fs13">Estamos verificando <br> la información ingresada...</p>
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
		DNI_Padre = $("#DNI_Padre").val();
		//Clave = $("#Clave").val();
		
		//alert(Usuario);
		datos = $("#formDatos").serialize();
		
		$.ajax({
			type: "POST",
			cache: false,
			async: false,			
			data: {DNI_Padre: DNI_Padre},
            url: 'validar-padre.php',
			success: function (data){
				//alert(data);
				var opcion = data.substr(0,9);
				if (opcion == "Siguiente"){
					$(".login_elements").hide();
					$(".login_spinner").hide();
					$(".login_alta").show();
				}else{
					opcion = data.substr(0,5);
					if (opcion == "Error"){
						var ErrorSTR = data;
						$('#principal_box .login_spinner').fadeOut('fast',function(){
						$('#principal_box .login_elements').show();
						$('#principal_box [data-name="DNI_Padre"],#access_box [data-name="Clave_Padre"]').addClass('error');
						$('#principal_box [data-name="DNI_Padre"]').parent().msgError({mensaje:ErrorSTR});
				});
					}
				}
				
			}
		});//fin ajax*/
	}
	});
    $(function(){
		
		$('#access_box [data-name="DNI_Padre"]').focus();
		
		$('#access_box [data-name="DNI_Padre"]').focus(function(evento){
			evento.preventDefault();
			$(this).removeClass('error');
			$.msgError.close();
		});
		$('#access_box [data-name="Clave_Padre"]').focus(function(evento){
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
            if (evento.keyCode == 13){			
                $("#formDatos").submit();
            }//fin if
        });//fin de prsionar enter		
		
		
	});
</script>        
<!--<script type="text/javascript" src="index-nuevo/ingresar_librerias.js"></script>
<script type="text/javascript" src="index-nuevo/ingresar.js"></script>-->
</body></html>