<?php
include_once("comprobar_sesion.php");
?>
 <script language="javascript">
    $(function(){
		$("#guardarDatos").click(function(evento){
            evento.preventDefault();
            vUsuID = $("#datosUsuID").val();
            vPersona = $("#datosPersona").val();
            vEmail = $("#datosEmail").val();
            if(!$("#datosEmail").correo()){
                mostrarAlerta("La direcci&oacute;n de correo proporcionada no es correcta.");
                $("#datosEmail").focus();
                return;
            }
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'actualizarDatosUsuario', UsuID: vUsuID, Persona: vPersona, Email: vEmail},
                url: 'cargarOpciones.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
                        mostrarAlerta(data, "Resultado de guardar los datos");
						document.location = "index.php";
                    }
                }
            });//fin ajax////*/
        });	
	});	
	</script>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                                                <tr>
                                                    <td width="22%" align="right"><strong>Usuario:</strong></td>
                                                    <td width="78%"><strong><?php echo $_SESSION['sesion_usuario']; ?>
                                                            <input name="datosUsuID" type="hidden" id="datosUsuID" value="<?php echo $_SESSION['sesion_UsuID']; ?>" />
                                                        </strong></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><strong>Nombre y Apellido:</strong></td>
                                                    <td><label>
                                                            <input name="datosPersona" type="text" id="datosPersona" value="<?php echo $_SESSION['sesion_persona']; ?>" size="60" />
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td align="right"><strong>Cuenta de correo:</strong></td>
                                                    <td><label>
                                                            <input name="datosEmail" type="text" id="datosEmail" value="<?php echo $_SESSION['sesion_email']; ?>" size="60" />
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td><button id="guardarDatos">Guardar datos</button></td>
                                                </tr>
                                            </table>