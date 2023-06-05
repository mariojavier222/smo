<?php
$DNI = $_POST['DNI'];
$DocID = $_POST['DocID'];
$DNI = substr("000000000".$_POST['DNI'],-9);
$DNI = $_POST['DocID'].$DNI;

$raiz = $_SERVER['DOCUMENT_ROOT']."/uccdigital/fotos/chica";
$archivo = $raiz."/$DNI.jpg";
if (file_exists($archivo))
	echo "<img src='fotos/chica/$DNI.jpg' title='Foto'/>";
else
	echo '';
?>
