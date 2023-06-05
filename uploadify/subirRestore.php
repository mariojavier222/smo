<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/RestoreSistema'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	if ($_SERVER['SERVER_NAME']=="localhost"){
		$subcarpeta = "/excellence";
	}else{
		$subcarpeta = "/napta";
	}
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $subcarpeta . $targetFolder;
	//$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	$archivo = "RestoreSistema".date("Ymd_His").".tar";
	$targetFile = rtrim($targetPath,'/') . '/' . $archivo;
	
	// Validate the file type
	//$fileTypes = array('jpg','jpeg','gif','sql'); // File extensions
	$fileTypes = array('tar'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
		echo 'Sólo se admiten archivos con extensión TAR.';
	}
}
?>