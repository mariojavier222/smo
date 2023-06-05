<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
//include_once("comprobar_sesion.php");

date_default_timezone_set('America/Argentina/San_Juan');

$dir = 'uploads/alu06012023.Txt';
$file = $dir;
$nomArchivo = substr($file,8,50);
$diaHora = date("_d-m-Y-H-i-s");
$nomArchLog = "log-".substr($nomArchivo,0,11).$diaHora.".txt";

$archivo = file($file); 
$lineas = count($archivo); 
//$lineas = 2;
$a=0;//alumnos guardados
$p=0;//padres guardados
$f=0;//famlias armadas
$msj='';
$cantErr=0;

$fecha = date("Y-m-d");
$hora = date("H:i:s");
$UsuID = 2;//$_SESSION['sesion_UsuID'];
$cadena='';

//controlo el nombre del archivo
if (substr($nomArchivo,0,3) != "alu") {
	$msj= "ERROR: el archivo ".$nomArchivo." no tiene el nombre correcto.";
	echo "El archivo ".$nomArchivo." no tiene el nombre correcto.<br />";
	guardarLog($msj,$cadena,$nomArchLog);
	
}else {
	if (substr($archivo[0],0,3) != "DET"){
		echo "ERROR: el archivo ".$nomArchivo." no tiene el formato correcto.<br />";
		$msj= "El archivo ".$nomArchivo." no tiene el formato correcto.";
		guardarLog($msj,$cadena,$nomArchLog);
	}else {
		for($i=0; $i < $lineas; $i++){ 
			$tipo = substr($archivo[$i],0,3);
			if ($tipo == "DET"){
				$Alu_ID = trim(substr($archivo[$i],3,6));
				$Alu_Matricula = trim(substr($archivo[$i],9,6));
				$Alu_Usu_ID = trim(substr($archivo[$i],15,3));
				$Alu_DNI = trim(substr($archivo[$i],18,10));
				$Alu_Nombre = trim(substr($archivo[$i],28, 60));
				$Alu_GRUPO_S = trim(substr($archivo[$i],87, 10));
				$Alu_SEXO = trim(substr($archivo[$i],97, 1));
				$Alu_FECHA_NAC = trim(substr($archivo[$i],98, 8));
				$Alu_DIRECCION = trim(substr($archivo[$i],106, 80));
				$Alu_TELEFONO_1 = trim(substr($archivo[$i],186, 15));
				$Alu_TELEFONO_2 = trim(substr($archivo[$i],201, 15));
				$Alu_TELEFONO_E = trim(substr($archivo[$i],216, 15));
				$Alu_NOM_PADRE = trim(substr($archivo[$i],231, 60));
				$Alu_DNI_PADRE = trim(substr($archivo[$i],291, 10));
				$Alu_CONTRATO_ED = trim(substr($archivo[$i],301, 10));
				$Alu_RENOVACION = trim(substr($archivo[$i],311, 10));
				$Alu_EMAIL_PADRE = trim(substr($archivo[$i],321, 30));
				$Alu_PROVINCIA = trim(substr($archivo[$i],351, 20));
				$Alu_PAIS = trim(substr($archivo[$i],371, 20));
				$Alu_OBSERVACION = trim(substr($archivo[$i],391, 50));
				$Alu_ANULADO = trim(substr($archivo[$i],441, 10));
				$Alu_FECHA_ANU = trim(substr($archivo[$i],451, 8));
				$anio = substr($Alu_FECHA_NAC,0,4);
				$mes = substr($Alu_FECHA_NAC,4,2);
				$dia = substr($Alu_FECHA_NAC,6,2);
				//lo transformo a entero
				$Alu_DNI = (int)$Alu_DNI;				
				//lo transformo a entero
				$Alu_DNI_PADRE = (int)$Alu_DNI_PADRE;
				if ($Alu_DNI_PADRE == 0) $Alu_DNI_PADRE = '';
				
				if ($Alu_DNI == 0){
					//echo "ERROR: el DNI del alumno no puede ser 0.<br />";
					$msj= "DNI = 0: Matr.: $Alu_Matricula, ID: $Alu_ID.";
					$cantErr++;
					guardarLog($msj,$cadena,$nomArchLog);
				}else {
					
					//formateo de los datos
					$Per_Doc_ID = 1;
					$Per_DNI = $Alu_DNI;
					
					//divido el nombre del apellido porque vienen juntos
					$porciones = explode(",", $Alu_Nombre);
					$Per_Apellido = $porciones[0];
					$Per_Nombre = $porciones[1];
					
					$Per_Apellido=utf8_encode($Per_Apellido);
					$Per_Nombre = utf8_encode($Per_Nombre);

					$Per_Apellido = mb_convert_case($Per_Apellido, MB_CASE_UPPER,"UTF-8");
				    $Per_Apellido = str_replace("'","´",$Per_Apellido);

					$Per_Nombre = mb_convert_case($Per_Nombre, MB_CASE_TITLE,"UTF-8");
				    $Per_Nombre = str_replace("'","´",$Per_Nombre);

				    //nombre del padre/madre
				    $porcionesp = explode(",", $Alu_NOM_PADRE);
					$Per_Apellido_Padre = $porcionesp[0];
					$Per_Nombre_Padre = $porcionesp[1];
					
					$Per_Apellido_Padre=utf8_encode($Per_Apellido_Padre);
					$Per_Nombre_Padre = utf8_encode($Per_Nombre_Padre);

					$Per_Apellido_Padre = mb_convert_case($Per_Apellido_Padre, MB_CASE_UPPER);
				    $Per_Apellido_Padre = str_replace("'","´",$Per_Apellido_Padre);

					$Per_Nombre_Padre = mb_convert_case($Per_Nombre_Padre, MB_CASE_TITLE);
				    $Per_Nombre_Padre = str_replace("'","´",$Per_Nombre_Padre);
					//**********************

					$Per_Sexo = $Alu_SEXO;
					$Per_Sexo_Padre='N';
					$Per_Fecha = $fecha;
					$Per_Hora = $hora;

					//PersonaDatos

			        $Dat_Nac_Pai_ID=1;
			        $Dat_Nac_Pro_ID=19;
			        $Dat_Nac_Loc_ID=1735;
			        
			    	$Dat_Dom_Pai_ID=1;
			        $Dat_Dom_Pro_ID=19;
			        $Dat_Dom_Loc_ID=1735;
				    
				    $Dat_Nacimiento = $anio."-".$mes."-".$dia;
				    $Dat_Nacimiento_Padre = '2000-01-01';
				    
				    $Dat_Domicilio = utf8_encode($Alu_DIRECCION);
					//$Dat_Domicilio = utf8_decode($Dat_Domicilio);
					$Dat_Domicilio = arreglarCadenaMalEscrita($Dat_Domicilio);

					$Dat_CP = '5400';
					$Dat_Email = $Alu_EMAIL_PADRE;
					$Dat_Telefono = $Alu_TELEFONO_1;
					$Dat_Celular = $Alu_TELEFONO_2;
					$Dat_Observaciones = 'importado por file '.$nomArchivo;
				    $Dat_Ocupacion = '';
				    $Dat_Retira = '';
					$Dat_Fecha = $fecha;
					$Dat_Hora = $hora;

					//echo $Alu_ANULADO."</br>";

					if ($Alu_ANULADO=='Falso'){ 

						//me fijo si existe como persona
						$sql = "SELECT Per_ID FROM Persona WHERE Per_DNI = '$Alu_DNI';";
						$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

						if (mysqli_num_rows($result)==0){
							
							//SECTOR ALUMNO ***************************
							//Inserto
		        			$sql = "INSERT INTO Persona (Per_Doc_ID, Per_DNI, Per_Apellido, Per_Nombre, Per_Sexo, Per_Fecha, Per_Hora, Per_Token) VALUES('$Per_Doc_ID', '$Per_DNI', '$Per_Apellido', '$Per_Nombre', '$Per_Sexo', '$Per_Fecha', '$Per_Hora', UUID())";
		        			//echo $sql."<br>";
				            $res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
		        		    
		        		    if ($res["success"] == true){
		                		$PerIDa = $res['id'];
		                		$a++;//sumo un alumno guardado
		            		}else{
								echo 'Error en la consulta!'.$sql;
	                			exit();
	            			}
							
							$Dat_Per_ID = $PerIDa;
	        	   
	        	   			//guardo el usuario
	        	   			guardarCuentaUsuario($Per_DNI, $Per_DNI, "$Per_Apellido, $Per_Nombre");

	        	  			$sqld = "INSERT INTO PersonaDatos (Dat_Per_ID, Dat_Dom_Pro_ID, Dat_Dom_Pai_ID, Dat_Dom_Loc_ID, Dat_Nac_Pro_ID, Dat_Nac_Pai_ID, Dat_Nac_Loc_ID, Dat_Nacimiento, Dat_Domicilio, Dat_CP, Dat_Email, Dat_Telefono, Dat_Celular, Dat_Observaciones, Dat_Ocupacion, Dat_Retira, Dat_Fecha, Dat_Hora) VALUES('$Dat_Per_ID', '$Dat_Dom_Pro_ID', '$Dat_Dom_Pai_ID', '$Dat_Dom_Loc_ID', '$Dat_Nac_Pro_ID', '$Dat_Nac_Pai_ID', '$Dat_Nac_Loc_ID', '$Dat_Nacimiento', '$Dat_Domicilio', '$Dat_CP', '$Dat_Email', '$Dat_Telefono', '$Dat_Celular', '$Dat_Observaciones', '$Dat_Ocupacion', '$Dat_Retira', '$Dat_Fecha', '$Dat_Hora')";
	        	  			$res=ejecutar_2022($sqld,basename(__FILE__),__LINE__);
	        	  			if ($res["success"] == true){
		                		//$PerIDa = $res['id'];
		                		//$a++;//sumo un alumno guardado
		            		}else{
								echo 'Error en la consulta!'.$sqld;
	                			exit();
	            			}
							//*****************************************
							
							//SECTOR PADRE ****************************
							//guardo el padre como persona
							if ((strlen(trim($Alu_DNI_PADRE))>5)&&($Alu_DNI_PADRE > 0)){
								$sqlp = "SELECT Per_ID FROM Persona WHERE Per_DNI = '$Alu_DNI_PADRE';";
								$resultp = consulta_mysql_2022($sqlp,basename(__FILE__),__LINE__);
								//guardo la nueva persona padre
								if (mysqli_num_rows($resultp)==0){
									$sqlpa = "INSERT INTO Persona (Per_Doc_ID, Per_DNI, Per_Apellido, Per_Nombre, Per_Sexo, Per_Fecha, Per_Hora, Per_Token) VALUES('$Per_Doc_ID', '$Alu_DNI_PADRE', '$Per_Apellido_Padre', '$Per_Nombre_Padre', '$Per_Sexo_Padre', '$Per_Fecha', '$Per_Hora', UUID())";
						            $res=ejecutar_2022($sqlpa,basename(__FILE__),__LINE__);
				        		    if ($res["success"] == true){
				                		$PerIDp = $res['id'];
				                		$p++;//sumo un padre guardado
				            		}else{
										echo 'Error en la consulta!'.$sqlpa;
			                			exit();
			            			}
									
									//guardo el usuario
	        	   					guardarCuentaUsuario($Alu_DNI_PADRE, $Alu_DNI_PADRE, "$Per_Apellido_Padre, $Per_Nombre_Padre");

									//guardo los otros datos de la persona padre
									$sqlp = "INSERT INTO PersonaDatos (Dat_Per_ID, Dat_Dom_Pro_ID, Dat_Dom_Pai_ID, Dat_Dom_Loc_ID, Dat_Nac_Pro_ID, Dat_Nac_Pai_ID, Dat_Nac_Loc_ID, Dat_Nacimiento_Padre, Dat_Domicilio, Dat_CP, Dat_Email, Dat_Telefono, Dat_Celular, Dat_Observaciones, Dat_Ocupacion, Dat_Retira, Dat_Fecha, Dat_Hora) VALUES('$PerIDp', '$Dat_Dom_Pro_ID', '$Dat_Dom_Pai_ID', '$Dat_Dom_Loc_ID', '$Dat_Nac_Pro_ID', '$Dat_Nac_Pai_ID', '$Dat_Nac_Loc_ID', '$Dat_Nacimiento_Padre','$Dat_Domicilio', '$Dat_CP', '$Dat_Email', '$Dat_Telefono', '$Dat_Celular', '$Dat_Observaciones', '$Dat_Ocupacion', '$Dat_Retira', '$Dat_Fecha', '$Dat_Hora')";
									consulta_mysql_2022($sqlp,basename(__FILE__),__LINE__);
		
									//armo la familia
									//me fijo si la relación existe
									$sqlb = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerIDa' AND Fam_Vin_Per_ID = '$PerIDp' AND Fam_FTi_ID = 1;";
									$resultb = consulta_mysql_2022($sqlb,basename(__FILE__),__LINE__);
									if (mysqli_num_rows($resultb)==0){
										//guardo la nueva relación en la tabla Familia
										$sqlc = "INSERT INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerIDa', '$PerIDp', 1, '$UsuID', '$fecha', '$hora')";
										consulta_mysql_2022($sqlc,basename(__FILE__),__LINE__);
										$sqlc = "INSERT INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerIDp', '$PerIDa', 2, '$UsuID', '$fecha', '$hora')";
										consulta_mysql_2022($sqlc,basename(__FILE__),__LINE__);
										$f++;//sumo una familia
									}

								}//del if
								//*****************************************
							
							}//del if $DNI_Padre > 0	
								
						}//del if Anulado

					}//del if Per_ID
						
				}//del esde ($Alu_DNI == 0)
			}//del if $tipo == DET
		}//del for				

		echo "Se procesaron ".($lineas - 1)." registros<br />";
		echo "Se guardaron ".$a." alumnos.<br />";
		echo "Se guardaron ".$p." padres.<br />";
		echo "Se armaron ".$f." familias.<br /><br />";
		if ($cantErr > 0)
			echo "Hubo $cantErr errores. Ver Log: $nomArchLog.";	
	}//del else archivo

}//del else nomArchivo "alu"

?>