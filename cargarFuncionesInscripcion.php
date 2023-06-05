<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");

session_name("sesion_abierta"); 
// incia sessiones
session_start();
date_default_timezone_set('America/Argentina/San_Juan');


function guardarInscripcionLectivo() {
    $LegID = $_POST['LegID'];
    $LecID = $_POST['LecID'];
    $CurID = $_POST['CurID'];
    $NivID = $_POST['NivID'];
    $DivID = $_POST['DivID'];
    $PerID = $_POST['PerID'];
    //$ArTID = $_POST['ArTID'];
    $DNI = $_POST['DNI'];
    $_SESSION['sesion_ultimoDNI'] = $DNI;

    //$Contrato = $_POST['Contrato'];
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
    //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
    $sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = '$LecID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    //echo $sql;
    if (mysqli_num_rows($result) > 0) {//ya existe, actualizamos la inscripcion
        $sql = "UPDATE Colegio_Inscripcion SET Ins_Cur_ID = $CurID, Ins_Niv_ID = $NivID, Ins_Div_ID = $DivID, Ins_Usu_ID = $UsuID, Ins_Fecha = '$Fecha', Ins_Hora = '$Hora' WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = $LecID";
        $mensaje = "Se actualizó  la inscripción del alumno.";
    } else {
        $sql = "INSERT INTO Colegio_Inscripcion (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora) VALUES ($LegID, $LecID, $CurID, $NivID, $DivID, $UsuID, '$Fecha', '$Hora')";
        $mensaje = "Se agregó correctamente la inscripción del alumno.";
    }
    $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($res==true){
        guardarClaseAlumnoLegajo($LegID, $LecID);
	    
        //para descontar la reserva de vacante a quien la pagó
        if (COLEGIO_SIGLAS=="cesap"){
            $CTiID=30;//reserva para el ciclo 2023 es ese ID
            $AltID=1;
            if (tieneReservaGenerada($PerID, $LecID, $CTiID)==true){
                //echo "tiene reserva!";
                $CTiID=35;//cancela inscr 2023 este ID
                guardarAsignacionCuotaEspecial($PerID, $LecID, $NivID, $CTiID, $AltID, 1);
				//cuota mensual y serv. digitales
                guardarAsignacionCuotaMensual($PerID, $LecID, $NivID);
            }else guardarAsignacionCuota($PerID, $LecID, $NivID);
        }else guardarAsignacionCuota($PerID, $LecID, $NivID);

    } else $mensaje="Error!";
    echo $mensaje;     
}

function borrarInscripcionLectivo() {
//echo "Hola";exit;
    $Lec_ID = $_POST['LecID'];
    $Leg_ID = $_POST['LegID'];
    $sql = "SELECT * FROM Colegio_Inscripcion WHERE Ins_Lec_ID = '$Lec_ID' AND Ins_Leg_ID = '$Leg_ID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "El alumno no tiene inscripción o ya fue eliminada.";
    } else {
        $sql = "INSERT INTO Colegio_InscripcionEliminado (Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Provisoria, Ins_Fecha, Ins_Hora) SELECT Ins_Leg_ID, Ins_Lec_ID, Ins_Cur_ID, Ins_Niv_ID, Ins_Div_ID, Ins_Usu_ID, Ins_Provisoria, Ins_Fecha, Ins_Hora FROM Colegio_Inscripcion WHERE Ins_Lec_ID = '$Lec_ID' AND Ins_Leg_ID = '$Leg_ID'";
        $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($res==true){
            $sql = "DELETE FROM Colegio_Inscripcion WHERE Ins_Lec_ID = $Lec_ID AND Ins_Leg_ID = $Leg_ID";
            //echo $sql;
            $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if ($res==true) echo "Se eliminó la inscripción correctamente.";
            else echo "Error!";        
        }else echo "Error!";     
    }
}//fin funcion


function guardarClaseAlumnoLegajo($LegID, $LecID) {
    $sql = "INSERT IGNORE INTO Colegio_InscripcionClase (IMa_Leg_ID, IMa_Lec_ID, IMa_Cla_ID, IMa_Usu_ID, IMa_Fecha, IMa_Hora)
        SELECT Ins_Leg_ID, Ins_Lec_ID, Cla_ID, Ins_Usu_ID, Ins_Fecha, Ins_Hora
        FROM Colegio_Clase
        INNER JOIN Colegio_Inscripcion
        ON (Colegio_Clase.Cla_Lec_ID = Colegio_Inscripcion.Ins_Lec_ID)
        AND (Colegio_Clase.Cla_Niv_ID = Colegio_Inscripcion.Ins_Niv_ID)
        AND (Colegio_Clase.Cla_Cur_ID = Colegio_Inscripcion.Ins_Cur_ID)
        AND (Colegio_Clase.Cla_Div_ID = Colegio_Inscripcion.Ins_Div_ID)
        WHERE (Ins_Lec_ID=$LecID AND Ins_Leg_ID=$LegID);";
    $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

}//fin function

function tieneReservaGenerada($PerID, $LecID, $CTiID){
    $sql = "SELECT * FROM CuotaPersona WHERE Cuo_Per_ID = $PerID AND Cuo_Lec_ID = $LecID AND Cuo_CTi_ID = $CTiID;";
    //echo $sql;
    $result_verif = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result_verif)>0) return true;
    else return false;

}//fin funcion


function datosRegistroInscripcion(){
    $LegID = $_POST['Leg_ID'];
    $LecID = $_POST['Lec_ID'];
    $NivID = $_POST['Niv_ID'];
    $UsuID = $_POST['Usu_ID'];
    $sql = "SELECT * FROM Colegio_Inscripcion
       INNER JOIN Usuario ON (Colegio_Inscripcion.Ins_Usu_ID = Usuario.Usu_ID) 
       WHERE Ins_Leg_ID = '$LegID' AND Ins_Lec_ID = '$LecID' AND Ins_Niv_ID='$NivID' AND Ins_Usu_ID = '$UsuID';";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        echo "Usuario: ".$row['Usu_Persona']." - Fecha - Hora: ".cfecha($row['Ins_Fecha'])." - ".$row['Ins_Hora'];
    }
    //echo $sql;
}


?>