<?php

//enviar email con error de ejecucion
function enviarEmailError($asunto, $cuerpo, $para, $nombre, $from = "noreply@barceloempresas.com.ar", $nombreFrom = "Errores SQL"){	
	
	require("phpmailer/class.phpmailer.php");

	//envio el mail 
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPSecure = "ssl";		
	$mail->SMTPAuth = true;
	$mail->Host = "mail.barceloempresas.com.ar"; // SMTP a utilizar. Por ej. smtp.elserver.com
	$mail->Username = "registro@barceloempresas.com.ar"; // Correo completo a utilizar
	$mail->Password = "Barcelona&3022"; // Contraseña
	$mail->Port = 465; // Puerto a utilizar
	$mail->From = $from; // Desde donde enviamos (Para mostrar)
	$mail->FromName = $nombreFrom;
	$mail->AddAddress($para); // Esta es la dirección a donde enviamos
	$mail->addBCC ("fabricioeche@gmail.com");
	$mail->IsHTML(true); // El correo se envía como HTML
	$mail->Subject = $asunto; // Este es el titulo del email.
	$body = $cuerpo;

	$mail->Body = $body; // mensaje a enviar
	$mail->AltBody = $cuerpo; // Texto sin html
	$exito = $mail->Send(); // Envía el correo.
	//fin envio de email
	if ($exito)
		return true;
	else
		return false;
}//fin function 

?>