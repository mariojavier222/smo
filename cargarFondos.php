<?php


if ($_SERVER['SERVER_NAME']=="localhost"){
	$subcarpeta = "/gateway";
}else{
	$subcarpeta = "/napta";
}

if ($_SERVER['SERVER_NAME']=="servidor"){
	$subcarpeta = "/gateway";
}
if ($_SERVER['SERVER_NAME']=="192.168.1.101"){
	$subcarpeta = "/gateway";
}
//$subcarpeta = "/sigso";
$targetFolder = '/fondos-web/'; // Relative to the root	
$targetPath = $_SERVER['DOCUMENT_ROOT'] . $subcarpeta . $targetFolder;

	$directorio = opendir($targetPath);
	while ($files[] = readdir($directorio));
	rsort($files);
	closedir($directorio);
    foreach ($files as $archivo)
    {
        if (preg_match("gif", $archivo) || preg_match("jpg", $archivo) || preg_match("png", $archivo)){
            $i++;
			echo "Fondos[$i] = '$archivo';\n";
			
			//echo $archivo;
        }//fin if
    }//fin while
	echo "Total = $i;\n";
    	
?>