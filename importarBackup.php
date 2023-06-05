<?php
/*
 * Function to import SQL for a given $file
 */
//function import_sql($file, $delimiter = ';') {
//phpinfo();exit;
$targetFolder = '/RestoreSistema/'; // Relative to the root	
if ($_SERVER['SERVER_NAME']=="localhost"){
	$subcarpeta = "/excellence";
}else{
	$subcarpeta = "/napta";
}
$targetPath = $_SERVER['DOCUMENT_ROOT'] . $subcarpeta . $targetFolder;
		
$file = $targetPath . $_GET['archivo'];
//echo $file; exit;
$delimiter = ';';	
	require_once("globales.php");
	global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;
	
	//$mysqli = new mysqli($gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db);
	mysqli_connect($gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db);
	/* check connection */
	
	/*if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}*/
	        
		//$handle = fopen($file, 'r');
		$handle = gzopen($file, 'r9');//@gzopen($filename, "w9");
        $sql = '';

        if($handle) {
                /*
                 * Loop through each line and build
                 * the SQL query until it detects the delimiter
                 */
                //while(($line = fgets($handle, 4096)) !== false) {
				while(($line = gzgets($handle, 4096)) !== false) {	
                        $sql .= trim(' ' . trim($line));
                        if(substr($sql, -strlen($delimiter)) == $delimiter) {
                                //mysqli_query($sql);
								//$mysqli->query($sql);
								//echo "$sql<br>";
                                $sql = '';
                        }
                }

                //fclose($handle);
				gzclose($handle);
        }
//}
?>
