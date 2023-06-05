<?php
/*
session_name("sesion_abierta");
// incia sessiones
session_start();

if (!isset($_SESSION['sesion_UsuID'])){
	session_name("sesion_abierta");
	session_start();
	// Destruye todas las variables de la sesi&oacute;n
	session_unset();
	// Finalmente, destruye la sesi&oacute;n
	session_destroy();

	echo "<script language='javascript'>window.parent.location='index.php';</script>";
}
*/

	$session_name = 'sesion_abierta';   // Set a custom session name 
    $secure = false;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=No se puede iniciar una sesión segura (ini_set)");
        exit();
    }


    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    //***********MODIFICADO FABRICIO*********************$cookieParams["domain"], $secure, $httponly);
	session_set_cookie_params(14400, $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
	//***********FIN MODIFICACION************************
	
    // Sets the session name to the one set above.
    session_name("sesion_abierta");

    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
	//echo session_name();

?>
