<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
include_once("guardarAccesoOpcion.php");
require_once("listas.php");
?>

<script language="javascript">
$(document).ready(function(){
	
		$("#barraNuevo_dos").click(function(evento){
		//evento.preventDefault();
		
		$("#formularioNuevo").empty();
		$("#guardarDatos").hide();
		$("#guardarDatos2").hide();
		$.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'mostrarDatos'},
                url: 'cargarOpcionesAltaPadre.php',
                success: function(data){ 
                   
                        //alert(data);
						$("#mostrarDatos").html(data);
                        //mostrarAlerta(data, "Resultado de guardar los datos");
                  
                }
            });//fin ajax////
	});
	
	$("#barraBuscar").click(function(evento){
		//evento.preventDefault();
		$("#guardarDatos").hide();
		$("#guardarDatos2").hide();
		
		$.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'BuscarPadre'},
                url: 'cargarOpcionesAltaPadre.php',
                success: function(data){ 
                   
                        //alert(data);
						$("#mostrarDatos").html(data);
                        //mostrarAlerta(data, "Resultado de guardar los datos");
                  
                }
            });//fin ajax////
	});
	
	
	$("#barraNuevo_dos").click();
	$("#guardarDatos").hide();
	$("#guardarDatos2").hide();
	
	
	$("#barraGuardar_dos").click(function(evento){
		//evento.preventDefault();
		DocID = $("#DocID").val();
		//alert(DocID)
		Apellidos = $("#Apellidos").val();
		Nombre = $("#Nombre").val();
		Sexo = $("#Sexo").val();
		Vinculos = $("#Vinculos option").val();
		UsuID = $("#UsuID").val();
		
		PerID = $("#PerID33").val();

	if(PerID!='')
	{
		var DNI = $("#DNI2").val();
	}
	else
	{
		var DNI = $("#DNI").val();
		
		
		}
	if(Apellidos=='')
	{
	jAlert("Ingrese Apellidos","Datos Personales");
	return false;
	}
	if(Nombre=='')
	{
	jAlert("Ingrese Nombre","Datos Personales");
	return false;
	}
	if(Sexo=='-1')
	{
	jAlert("Ingrese Sexo","Datos Personales");
	return false;
	}
	if(Vinculos=='')
	{
	jAlert("Ingrese Hijos","Datos Personales");
	return false;
	}
		
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	data: {opcion: 'guardarDatosPersonaUsuario', DNI: DNI, Apellidos: Apellidos, Nombre:Nombre, Sexo:Sexo, Vinculos:Vinculos,DocID:DocID,PerID:PerID},
	url: 'cargarOpcionesAltaPadre.php',
	success: function(data){
		 $("#mostrarresueltado").html(data);
		 }
	});//fin ajax///
		
		//return false;
		
			$.ajax({
						type: "POST",
						cache: false,
						async: false,
						data: {opcion: 'eliminarFamilia', DNI: DNI, UsuID: UsuID},
						url: 'cargarOpciones.php'
						//success: function(msg){ alert(msg);}
			});//fin ajax///
			
			
			$("#Vinculos option").each(function(){			
				vValor = $(this).val();
				arreglo = vValor.split(",");
				vDNI_Vinc = arreglo[0];
				vFTiID = arreglo[1];			
				if (DNI && vDNI_Vinc){
					
					//alert(vDNI_Vinc)
					//alert(vFTiID)
					//alert(DNI)
					//alert(UsuID)
									
					$.ajax({
						type: "POST",
						cache: false,
						async: false,
						data: {opcion: 'guardarFamilia', DNI: DNI, DNI_Vinc: vDNI_Vinc, FTiID: vFTiID, UsuID: UsuID},
						url: 'cargarOpciones.php'
						//success: function(msg){ alert(msg);}
					});//fin ajax///
				}//fin if//*/
				//else alert("Error");
			});
			//vDNI = $("#DNI").val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'armarFamilia', DNI: DNI, UsuID: UsuID},
				url: 'cargarOpciones.php',
				success: function(msg){
					 mostrarAlerta("Se ha guardado correctamente", "Resultado de la operación");
					$("#barraNuevo_dos").click(); 
					 }
			});//fin ajax///
			
		
		//
		
	});//*/
	
	$("#barraGuardar_tres").click(function(evento){
		//evento.preventDefault();
		DocID = $("#DocID").val();
		//alert(DocID)
		Apellidos = $("#Apellidos").val();
		Nombre = $("#Nombre").val();
		Sexo = $("#Sexo").val();
		Vinculos = $("#Vinculos option").val();
		UsuID = $("#UsuID").val();
		
		PerID = $("#PerID33").val();

	if(PerID!='')
	{
		var DNI = $("#DNI2").val();
	}
	else
	{
		var DNI = $("#DNI").val();
		
		
		}
	if(Apellidos=='')
	{
	jAlert("Ingrese Apellidos","Datos Personales");
	return false;
	}
	if(Nombre=='')
	{
	jAlert("Ingrese Nombre","Datos Personales");
	return false;
	}
	if(Sexo=='-1')
	{
	jAlert("Ingrese Sexo","Datos Personales");
	return false;
	}
	if(Vinculos=='')
	{
	jAlert("Ingrese Hijos","Datos Personales");
	return false;
	}
		
	$.ajax({
	type: "POST",
	cache: false,
	async: false,
	data: {opcion: 'guardarDatosPersonaUsuario', DNI: DNI, Apellidos: Apellidos, Nombre:Nombre, Sexo:Sexo, Vinculos:Vinculos,DocID:DocID,PerID:PerID},
	url: 'cargarOpcionesAltaPadre.php',
	success: function(data){
		 $("#mostrarresueltado").html(data);
		 }
	});//fin ajax///
		
		//return false;
		
			$.ajax({
						type: "POST",
						cache: false,
						async: false,
						data: {opcion: 'eliminarFamilia', DNI: DNI, UsuID: UsuID},
						url: 'cargarOpciones.php'
						//success: function(msg){ alert(msg);}
			});//fin ajax///
			
			
			$("#Vinculos option").each(function(){			
				vValor = $(this).val();
				arreglo = vValor.split(",");
				vDNI_Vinc = arreglo[0];
				vFTiID = arreglo[1];			
				if (DNI && vDNI_Vinc){
					
					//alert(vDNI_Vinc)
					//alert(vFTiID)
					//alert(DNI)
					//alert(UsuID)
									
					$.ajax({
						type: "POST",
						cache: false,
						async: false,
						data: {opcion: 'guardarFamilia', DNI: DNI, DNI_Vinc: vDNI_Vinc, FTiID: vFTiID, UsuID: UsuID},
						url: 'cargarOpciones.php'
						//success: function(msg){ alert(msg);}
					});//fin ajax///
				}//fin if//*/
				//else alert("Error");
			});
			//vDNI = $("#DNI").val();
			$.ajax({
				type: "POST",
				cache: false,
				async: false,
				data: {opcion: 'armarFamilia', DNI: DNI, UsuID: UsuID},
				url: 'cargarOpciones.php',
				success: function(msg){
					 mostrarAlerta("Se ha guardado correctamente", "Resultado de la operación");
					$("#barraNuevo_dos").click(); 
					 }
			});//fin ajax///
			
		
		//
		
	});//*/
	
	
	
	
})
</script>

<link href="css/general.css" rel="stylesheet" type="text/css" />


	<table border="0" align="center" cellspacing="4">
      <tr>
        <td width="48"><button class="barra_boton" id="barraNuevo_dos"> <img src="botones/Add.png" alt="Nuevo" width="48" height="48" border="0" title="Ingresar Nueva Persona" /><br />
       Nuevo </button> </td>
        <td width="48"><button class="barra_boton" id="barraBuscar"> <img src="botones/Search.png" alt="Buscar" width="48" height="48" border="0" title="Buscar Persona" /><br />
        Buscar</button></td>
        <td width="48" id="guardarDatos"><button class="barra_boton" id="barraGuardar_dos"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Guardar</button></td>
         <td width="48" id="guardarDatos2"><button class="barra_boton" id="barraGuardar_tres"><img src="botones/guardar.png" alt="Guardar" width="48" height="48" border="0" /><br />
        Editar</button></td>
      </tr>
</table>
    
    
<div id="mostrarDatos">
     
</div>
