<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
/*$sql = "TRUNCATE CuentaAlumno;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);*/
$Usu_ID = 2858;

$Letras[1]="A";
$Letras[2]="B";
$Letras[3]="C";
$Letras[4]="D";
$Letras[5]="E";
$Letras[6]="F";
$Letras[7]="G";
$Letras[8]="H";
$Letras[9]="I";
$Letras[10]="J";
  
for ($iLetra=1; $iLetra<=10;$iLetra++){
	for ($iNumero=1; $iNumero<=10;$iNumero++){
		$aleatorio = rand(0, 99);
		$valor = substr("00".$aleatorio, -2);
		//echo $Letras[$iLetra].$iNumero.":$valor;";
		$letra = $Letras[$iLetra];
		$num = $iNumero;
		$sql = "INSERT INTO TarjetaCoordenadas (TCo_Usu_ID, TCo_Letra, TCo_Numero, TCo_Valor) VALUES($Usu_ID, '$letra', '$num', '$valor')";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}
	//echo "<br>";
}//fin FOR iLetra

echo "FIN";


?>