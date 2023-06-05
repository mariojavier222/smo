<?php
//header('WWW-Authenticate: NTLM');
echo 'get_current_user: ' . get_current_user();
echo '<br>_SERVER[LOGON_USER]: ' . $_SERVER['LOGON_USER'];
echo '<br>_SERVER[AUTH_USER]: ' . $_SERVER['AUTH_USER'];
?>