<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();	

    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);

    $Sucursal='0002';
    $Numero='00005053';
    $Domicilio = '';//$_POST['Domicilio'];
    $Razon = utf8_decode('QUIROGA VARGAS, Facundo Lautaro (DNI: 45884759) Secundaria Técnica: 6to Año C');
    //$Razon = addslashes($Razon);
    $CUIT = '';//$_POST['CUIT'];
    $Fac_Detalle = 'Fecha - Hora de Pago OnLine: 20/04/2022 - 15:09:05 hs.'; 
    $FacturaImporteTotal ='1700.00';
    $FTiID = 2;//Recibos $_POST['FTiID'];
    $CVeID = 1;//$_POST['CVeID'];
    $IvaID = 5;//$_POST['IvaID'];
    
    /*
    $sql = "SET NAMES 'utf8mb4';";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SET CHARACTER SET utf8mb4;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    */

    //inicio transacción mysql
    consulta_iniciar();
    $sql = "INSERT INTO FacturaCopia (Fac_FTi_ID, Fac_Iva_ID, Fac_CVe_ID, Fac_Fecha, Fac_Hora, Fac_Usu_ID, Fac_CUIT, Fac_Sucursal, Fac_Numero, Fac_PersonaNombre, Fac_PersonaDomicilio, Fac_ImporteTotal, Fac_Pagada, Fac_Anulada, Fac_Detalle) VALUES($FTiID, $IvaID, $CVeID, '$Fecha', '$Hora', $UsuID, '$CUIT', '$Sucursal', '$Numero', '$Razon', '$Domicilio', '$FacturaImporteTotal', 1, 0, '$Fac_Detalle')";
    //echo $sql;

    $resultado=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    if ($resultado) {
        consulta_terminar();//si no hay error ejecuta y sigue
    
    }else {
        consulta_retroceder(); //del Factura
        echo "AquiCobro - Error al generar el recibo de pago!";
        }

?>