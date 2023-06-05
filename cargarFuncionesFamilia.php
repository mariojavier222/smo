<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("comprobar_sesion.php"); 

//error_reporting(E_ALL); ini_set('display_errors', 1);

date_default_timezone_set('America/Argentina/San_Juan');

function armarFamilia() {
    //echo "Hola";exit;
    //Modificado para que arme la familia pero de hermanos solamente
    $DNI = $_POST['DNI'];
    $_SESSION['sesion_ultimoDNI'] = $DNI;
    $UsuID = $_POST['UsuID'];
    $Fecha = date("Y-m-d");
    $Hora = date("H:i:s");
    $PerID = gbuscarPerID($DNI); 
    $hermano = array();
    echo "$DNI-$PerID";
    $sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' AND Fam_FTi_ID=3 ORDER BY Fam_FTi_ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//existe
        $ip = 1;
        $ih = 1;
        $ihm = 1;
        while ($row = mysqli_fetch_array($result)) {
            $FTiID = $row['Fam_FTi_ID'];
            $VinPerID = $row['Fam_Vin_Per_ID'];            
            if ($FTiID == 3) {//es hermano
                $hermano[$ih] = $VinPerID;
                $ih++;
            }
        }//fin while        
        
        if (count($hermano) > 0) {
            //Relacionamos los hermanos entre si
            foreach ($hermano as $h1) {
                foreach ($hermano as $h2) {
                    if ($h1 != $h2) {
                        guardarFamilia($h1, $h2, 3, $UsuID);
                        //echo "$h1-$h2";
                    }
                }//fin foreach hermano2
            }//fin foreach hermano1
        }//fin if cont
        
    }
}//fin funcion armarFamilia

function eliminarFamilia() {
    $DNI = $_POST['DNI'];
    $PerID = gbuscarPerID($DNI);
    $sql = "DELETE FROM Familia WHERE Fam_Per_ID = '$PerID'";
    $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($res==true){
        $sql = "DELETE FROM Familia WHERE Fam_Vin_Per_ID = '$PerID'";
        $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($res==true) echo "Se elimino la relacion familiar de la persona con DNI $PerID.";
        else echo "Error!";   
    }else echo "Error!";
}
//fin function

function eliminarVinculo() {
    $PerID=$_POST['PerID'];
    $DNI_Vinc = $_POST['DNI_Vinc'];
    $PerID_Vinc = gbuscarPerID($DNI_Vinc);

    $sql = "DELETE FROM Familia WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
    $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($res==true) echo "Se elimino el vínculo!";
}
//fin function

function guardarFamilia($PerID, $PerID_Vinc, $DNI, $DNI_Vinc, $FTiID, $UsuID) {
    //echo "Hola";exit;
    $PerID = trim($PerID);
    $PerID_Vinc = trim($PerID_Vinc);
    $Fecha = date("Y-m-d");
    $Hora = date("H:i:s");
    $FTi_Relaciona = gbuscarFTiRelaciona($FTiID);
    $sql = "SELECT * FROM Familia WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {//no existe
        $sql = "INSERT INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerID', '$PerID_Vinc', '$FTiID', '$UsuID', '$Fecha', '$Hora')";
        $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($res==true) echo "Se agregó correctamente la nueva relación familiar";

        if ($FTiID==1){  
            $DNI_Padre = $DNI_Vinc;          
            gObtenerApellidoNombrePersona($DNI_Padre, $Apellido, $Nombre);
            guardarCuentaUsuario($DNI_Padre, $DNI_Padre, "$Nombre $Apellido");
        }
        if ($FTi_Relaciona > 0) {
            $sql = "INSERT IGNORE INTO Familia (Fam_Per_ID, Fam_Vin_Per_ID, Fam_FTi_ID, Fam_Usu_ID, Fam_Fecha, Fam_Hora) VALUES ('$PerID_Vinc', '$PerID', '$FTi_Relaciona', '$UsuID', '$Fecha', '$Hora')";
            $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if ($res==true) echo "Se agregó correctamente la nueva relación familiar";
        }
        
    } else {
        $sql = "UPDATE Familia SET Fam_FTi_ID = '$FTiID', Fam_Usu_ID = '$UsuID', Fam_Fecha = '$Fecha', Fam_Hora = '$Hora' WHERE Fam_Per_ID = '$PerID' AND Fam_Vin_Per_ID = '$PerID_Vinc'";
        $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($res==true) echo "Se actualizó la relación familiar.";
    }
}//fin funcion
?>