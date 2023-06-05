<?php
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

$Dat_DNI = ''; 
$Dat_Persona = ''; 
$Dat_Sexo = ''; 
$Dat_Foto = ''; 
$Dat_FechaNac = ''; 
$Dat_LocalidadNac = ''; 
$Dat_Domicilio = ''; 
$Dat_LocalidadDom = ''; 
$Dat_CP = ''; 
$Dat_Telefono = ''; 
$Dat_Celular = ''; 
$Dat_Observaciones = ''; 
$Dat_Legajo = '';		
	
//Cargamos la Tabla
//Datos Alumno
$mysqli->query("SET NAMES UTF8");
$mysqli->query("DELETE FROM DatosAlumno");
$sql_prueba = "INSERT IGNORE INTO DatosAlumno SELECT
    Per_DNI
    , CONCAT(Per_Apellido, ', ', Per_Nombre)
    , Per_Sexo
    , Per_Foto
    , Dat_Nacimiento
	, CONCAT(L2.Loc_Nombre, ', ',P2.Pro_Nombre, ' - ',PP2.Pai_Nombre)
    , Dat_Domicilio
	, CONCAT(L1.Loc_Nombre, ', ',P1.Pro_Nombre, ' - ',PP1.Pai_Nombre)
    , Dat_CP
    , Dat_Telefono
    , Dat_Celular
    , Dat_Observaciones
    , Leg_Numero
FROM
    PersonaDatos
    INNER JOIN Persona 
        ON (Dat_Per_ID = Per_ID)
    INNER JOIN Legajo 
        ON (Leg_Per_ID = Per_ID)
	INNER JOIN Localidad AS L1
        ON (Dat_Dom_Loc_ID = L1.Loc_ID) AND (Dat_Dom_Pai_ID = L1.Loc_Pai_ID) AND (Dat_Dom_Pro_ID = L1.Loc_Pro_ID)
    INNER JOIN Provincia AS P1
        ON (L1.Loc_Pro_ID = P1.Pro_ID) AND (L1.Loc_Pai_ID = P1.Pro_Pai_ID)
    INNER JOIN Pais AS PP1 
        ON (P1.Pro_Pai_ID = PP1.Pai_ID)
    INNER JOIN Localidad AS L2
        ON (Dat_Nac_Loc_ID = L2.Loc_ID) AND (Dat_Nac_Pai_ID = L2.Loc_Pai_ID) AND (Dat_Nac_Pro_ID = L2.Loc_Pro_ID)
    INNER JOIN Provincia AS P2
        ON (L2.Loc_Pro_ID = P2.Pro_ID) AND (L2.Loc_Pai_ID = P2.Pro_Pai_ID)
    INNER JOIN Pais AS PP2 
        ON (P2.Pro_Pai_ID = PP2.Pai_ID);";
$mysqli->query($sql_prueba);
echo "Cargada DatosAlumno<br />";

//Datos del Padre
$mysqli->query("SET NAMES UTF8");
$mysqli->query("DELETE FROM DatosPadre");
$sql_prueba = "INSERT IGNORE INTO DatosPadre SELECT
    Padre.Per_DNI
    , Hijo.Per_DNI
	, ''
    , FamiliaTipo.FTi_Nombre    
    , CONCAT(Padre.Per_Apellido, ', ', Padre.Per_Nombre)
    , Padre.Per_Sexo
    , PersonaDocumento.Doc_Nombre
FROM
    Familia
    INNER JOIN FamiliaTipo 
        ON (Fam_FTi_ID = FTi_ID)
    INNER JOIN Persona AS Hijo 
        ON (Fam_Vin_Per_ID = Hijo.Per_ID)
    INNER JOIN Persona AS Padre 
        ON (Fam_Per_ID = Padre.Per_ID)
    INNER JOIN Legajo 
        ON (Leg_Per_ID = Hijo.Per_ID)
    INNER JOIN PersonaDocumento 
        ON (Padre.Per_Doc_ID = Doc_ID);";
$mysqli->query($sql_prueba);

echo "Cargada DatosPadre<br />";
echo "Arreglada DatosPadre<br />";

//Datos de las Cuotas
$mysqli->query("SET NAMES UTF8");
$mysqli->query("DELETE FROM DatosCuota");
$sql_prueba = "INSERT IGNORE INTO DatosCuota SELECT
    Per_DNI
    , Cuo_Niv_ID
    , CONCAT(Lec_Nombre, ' - ', CTi_Nombre, ' - ', Cuo_Numero)
    , Ben_Nombre
    , CONCAT(Cuo_Mes, '-', Cuo_Anio)
    , Cuo_1er_Vencimiento
    , Cuo_Importe
    , CONCAT(Cuo_Pagado, '-', Cuo_Cancelado, '-', Cuo_Anulado)
FROM
    CuotaPersona
    INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID)
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID) WHERE Cuo_Lec_ID >=14 AND Per_DNI IN (SELECT DISTINCTROW Per_DNI
FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID)
WHERE (Ins_Lec_ID >=14));";
$mysqli->query($sql_prueba);
echo "Cargada DatosCuota<br />";

//Datos de Inscripción
$mysqli->query("SET NAMES UTF8");
$mysqli->query("DELETE FROM DatosInscripcion");
$sql_prueba = "INSERT IGNORE INTO DatosInscripcion SELECT
    Per_DNI
    , Lec_Nombre
    , Niv_Nombre
    , Cur_Nombre
    , Div_Nombre
FROM
    Colegio_Inscripcion
    INNER JOIN Curso 
        ON (Ins_Cur_ID = Cur_ID)
    INNER JOIN Division 
        ON (Ins_Div_ID = Div_ID)
    INNER JOIN Legajo 
        ON (Ins_Leg_ID = Leg_ID)
    INNER JOIN Lectivo 
        ON (Ins_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cur_Niv_ID = Niv_ID)
    INNER JOIN Persona 
        ON (Leg_Per_ID = Per_ID)
WHERE (Ins_Lec_ID=14);";
$mysqli->query($sql_prueba);
echo "Cargada DatosInscripcion<br />";

//Datos de Percibidos Diarios
$mysqli->query("SET NAMES UTF8");
$mysqli->query("DELETE FROM DatosPercibidoDiario");
$sql_prueba = "INSERT IGNORE INTO DatosPercibidoDiario SELECT
    CuP_Fecha
    , SUM(CuP_Importe)
FROM
    CuotaPago
WHERE (CuP_Anulada =0 AND CuP_CTi_ID <>5 AND CuP_CTi_ID <>6 AND CuP_CTi_ID <>8 AND CuP_Fecha>'2015-01-01')
GROUP BY CuP_Fecha;";
$mysqli->query($sql_prueba);
echo "Cargada DatosInscripcion<br />";

echo "FIN<br />";

function buscarPadre($RolHijo){
$sql_prueba = "SELECT
    F.FTi_Nombre
FROM
    FamiliaTipo
    INNER JOIN FamiliaTipo AS F 
        ON (FamiliaTipo.FTi_Relaciona = F.FTi_ID) WHERE FamiliaTipo.FTi_Nombre = '$RolHijo';";
$result = consulta_mysql_2022($sql_prueba,basename(__FILE__),__LINE__);
//echo $sql_prueba;exit;
$row = mysqli_fetch_array($result);
return $row[FTi_Nombre];
	//echo "$sql_prueba<br />";

}
/*$sql = '';
$mysqli->query("SET NAMES UTF8");
//$mysqli->query("SET global max_allowed_packet=16M");
echo $mysqli->error."<br />";

	echo "FIN DE IMPORTAR";*/
//}
?>
