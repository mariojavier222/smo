<?php
/*
 * Function to import SQL for a given $file
 */
//function import_sql($file, $delimiter = ';') {
//phpinfo();exit;
$targetFolder = '/RestoreWeb/'; // Relative to the root	
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
	require_once("conexion.php");
	global $gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db;
	
	$mysqli = new mysqli($gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db);
	//mysqli_connect($gSQL_host, $gSQL_usuario, $gSQL_pass, $gSQL_db);
	/* check connection */
	
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}//*/
	        
		//$handle = fopen($file, 'r');
		$handle = gzopen($file, 'r9');//@gzopen($filename, "w9");
		
		$sql_prueba = "SHOW TABLES";
		$result = consulta_mysql_2022($sql_prueba,basename(__FILE__),__LINE__);
		while ($row = mysqli_fetch_array($result)){
			$sql_prueba = "TRUNCATE $row[0]";
			consulta_mysql_2022($sql_prueba,basename(__FILE__),__LINE__);
			//echo "$sql_prueba<br />";
		}
		echo "Tablas de la BD vaciadas<br />";
        $sql = '';
		$mysqli->query("SET NAMES UTF8");
		//$mysqli->query("SET global max_allowed_packet=16M");
		echo $mysqli->error."<br />";
		//exit;
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
								$mysqli->query($sql);
								//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
								echo $mysqli->error."<br />";
								//echo "INICIO:".$sql."FIN<br>";
                                $sql = '';
                        }
						$i++;
						//if ($i==1000) exit;
                }

                //fclose($handle);
				gzclose($handle);
        }
		echo "FIN DE IMPORTAR";
//}
?>
