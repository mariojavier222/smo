<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();	

$opcion = $_POST['opcion'];

switch ($opcion) {

	case "procesarCobrosOnline":        
        procesarCobrosOnline();  

	default:
//        echo "La opción elegida no es válida";
}//fin switch


function procesarCobrosOnline() {
//echo "Hola";exit;
    $CajaID = $_POST['CajaID'];    

    $sql = "SELECT * FROM CuotaPersona
    INNER JOIN CuotaTipo 
        ON (CuotaPersona.Cuo_CTi_ID = CuotaTipo.CTi_ID)
    INNER JOIN Persona 
        ON (CuotaPersona.Cuo_Per_ID = Persona.Per_ID)
    INNER JOIN Lectivo 
        ON (CuotaPersona.Cuo_Lec_ID = Lectivo.Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (CuotaPersona.Cuo_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN CuotaBeneficio 
        ON (CuotaPersona.Cuo_Ben_ID = CuotaBeneficio.Ben_ID) 
    INNER JOIN CuotaUnica 
        ON (CuotaPersona.Cuo_Numero = CuotaUnica.Rec_Numero) AND (CuotaPersona.Cuo_Per_ID = CuotaUnica.Rec_Per_ID) AND (CuotaPersona.Cuo_Niv_ID = CuotaUnica.Rec_Niv_ID) AND (CuotaPersona.Cuo_CTi_ID = CuotaUnica.Rec_CTi_ID) AND (CuotaPersona.Cuo_Lec_ID = CuotaUnica.Rec_Lec_ID) AND (CuotaPersona.Cuo_Alt_ID = CuotaUnica.Rec_Alt_ID)
    WHERE Cuo_MarcadaOnline=1 AND Cuo_Procesada = 0 ORDER BY Cuo_FechaMarcada ASC, Cuo_HoraMarcada ASC;";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) == 0) {
        echo "No hay cuotas para procesar.";
    } else {        
        $i=0;
        while ($row = mysqli_fetch_array($result)) {
            $Cuo_ImporteMarcada = $row['Cuo_ImporteMarcada'];
            $Rec_idOrdenCobro = $row['Rec_idOrdenCobro'];
            $FechaPago = $row['Cuo_FechaMarcada'];
            $HoraPago = $row['Cuo_HoraMarcada'];
            $datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
            $datosCurso = utf8_decode(buscarCursoDivisionPersonaActual($row['Cuo_Per_ID']));
            $datosCurso = strip_tags($datosCurso);
            $Razon = "$row[Per_Apellido], $row[Per_Nombre] (DNI: $row[Per_DNI]) $datosCurso";
            generarFacturaPagoOnline($CajaID, $Cuo_ImporteMarcada, $datosCuota, $Razon, $Rec_idOrdenCobro, $FechaPago, $HoraPago);
            $i++;
        }
        echo "Se procesaron $i cuotas correctamente.";

    }
}//fin funcion



function generarFacturaPagoOnline($CajaID, $FacturaImporteTotal, $datosCuota, $Razon, $OrdenCobro, $FechaPago, $HoraPago) {
    //echo "Hola";exit;
    
    $todo_bien = false;
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);    
    if (!$CajaID){
        echo "Error: La Caja se encuentra cerrada";
        exit;
    }
 
    $FacturaNumero = buscarReciboUltima(false);
    //$FacturaNumero = $_POST['FacturaNumero'];
    list($Sucursal, $Numero) = explode("-",$FacturaNumero);
    $Domicilio = '';//$_POST['Domicilio'];
    $Razon = mysql_real_escape_string($Razon);
    //$Razon = addslashes($Razon);
    $CUIT = '';//$_POST['CUIT'];
    $Fac_Detalle = 'Fecha - Hora de Pago OnLine: '.cfecha($FechaPago).' - '.$HoraPago.' hs.'; 
    //$FacturaImporteTotal = $_POST['FacturaImporteTotal'];
    $FTiID = 2;//Recibos $_POST['FTiID'];
    $CVeID = 1;//$_POST['CVeID'];
    $IvaID = 5;//$_POST['IvaID'];
    
    $sql = "SET NAMES 'utf8mb4';";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SET CHARACTER SET utf8mb4;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
 
     //inicio transacción mysql
    consulta_iniciar();
    $sql = "INSERT INTO Factura (Fac_FTi_ID, Fac_Iva_ID, Fac_CVe_ID, Fac_Fecha, Fac_Hora, Fac_Usu_ID, Fac_CUIT, Fac_Sucursal, Fac_Numero, Fac_PersonaNombre, Fac_PersonaDomicilio, Fac_ImporteTotal, Fac_Pagada, Fac_Anulada, Fac_Detalle) VALUES($FTiID, $IvaID, $CVeID, '$Fecha', '$Hora', $UsuID, '$CUIT', '$Sucursal', '$Numero', '$Razon', '$Domicilio', '$FacturaImporteTotal', 1, 0, '$Fac_Detalle')";
    //echo $sql;

    $resultado=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    
    if ($resultado) {
        consulta_terminar();//si no hay error ejecuta y sigue

        $sql = "SELECT LAST_INSERT_ID();";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result)>0){     
            $row = mysqli_fetch_array($result);
            $FacID = $row[0];
        }else{
            echo "Mal";
        }
        
        $ForID = 14;//AQUICOBRO $_POST['ForID'];
        $Item = 0;
                    
        $ImporteCuota = $FacturaImporteTotal;
        
        list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(';', $datosCuota);
            
        $Orden = buscarOrdenPagoCuota($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero);
        if ($Orden==1){
            //Guardo en la Cuenta Corriente del Alumno el importe total de la cuota, generamos por primera vez el concepto de cuota
            guardarConceptoCtaCte($Cuo_Per_ID, $datosCuota, "debe",1);
        }
        //Inserto el pago corespondiente
        $CCoID = guardarPagoCtaCte($Cuo_Per_ID, $datosCuota, $ImporteCuota);
        if ($CCoID>0){
            $sql = "INSERT INTO CuotaPago (CuP_Lec_ID, CuP_Per_ID, CuP_Niv_ID, CuP_CTi_ID, CuP_Alt_ID, CuP_Numero, CuP_Orden, CuP_Fac_ID, CuP_FDe_Item, CuP_Caja_ID, CuP_Fecha, CuP_Hora, CuP_Usu_ID, CuP_Importe, CuP_CCo_ID) VALUES($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, $Orden, $FacID, $Item, $CajaID, '$Fecha', '$Hora', $UsuID, $ImporteCuota, $CCoID)";
            //echo "$sql<br />";
            
            consulta_iniciar();
            $resultadoP=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            if ($resultadoP) {
                consulta_terminar();//si no hay error ejecuta y sigue        

                //Actualizar Alternativa de pago de la cuota
                actualizarAlernativaPago($datosCuota);
                
                //Insertamos el pago en la Caja Corriente
                guardarPagoCajaCorrienteOnLine($CajaID, $datosCuota, $ImporteCuota, $ForID, $FacturaNumero, $Fac_Detalle);
                            
                //Insertamos el detalle de la factura
                
                $Detalle = "Cancela ";                
                //Actualizo la cuota como pagada si los pagos parciales cubren el total del importe
                $sql = "UPDATE CuotaPersona SET Cuo_Procesada = 1, Cuo_FechaProcesada = '$Fecha $Hora', Cuo_UsuProcesada = '$UsuID', Cuo_Pagado = 1 WHERE Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);//echo $sql;
                
                $sql = "SELECT * FROM CuotaPersona
                INNER JOIN Lectivo 
                ON (Cuo_Lec_ID = Lec_ID)
                INNER JOIN Colegio_Nivel 
                ON (Cuo_Niv_ID = Niv_ID)
                INNER JOIN CuotaTipo 
                ON (Cuo_CTi_ID = CTi_ID)
                INNER JOIN CuotaAlternativa 
                ON (Cuo_Alt_ID = Alt_ID)
                INNER JOIN CuotaBeneficio 
                ON (Cuo_Ben_ID = Ben_ID)
                INNER JOIN Usuario 
                ON (Cuo_Usu_ID = Usu_ID)
                WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero);";
                $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    $Detalle .= "$row[CTi_Nombre] ".buscarMes($row[Cuo_Mes])."/$row[Cuo_Anio]";
                    
                }
                $sql = "INSERT INTO FacturaDetalle (FDe_Fac_ID, FDe_Item, FDe_Cantidad, FDe_Detalle, FDe_ImporteUnitario, FDe_Importe) VALUES($FacID, $Item, 1, '$Detalle', $ImporteCuota, $ImporteCuota)";
                //echo "$sql<br />";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                
                //Inserto el detalle del pago            
                $sql = "INSERT INTO CuotaPagoDetalle (CPD_Lec_ID, CPD_Per_ID, CPD_Niv_ID, CPD_CTi_ID, CPD_Alt_ID, CPD_Numero, CPD_Orden, CPD_FDe_ID, CPD_For_ID, CPD_Fecha, CPD_Hora, CPD_Valor) VALUES($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, $Orden, 2, $ForID, '$Fecha', '$Hora', '".$OrdenCobro."')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                
                //Hacemos una inserción solamente para cuando el FDeID = 1, es decir el importe
                $sql = "INSERT INTO CuotaPagoDetalle (CPD_Lec_ID, CPD_Per_ID, CPD_Niv_ID, CPD_CTi_ID, CPD_Alt_ID, CPD_Numero, CPD_Orden, CPD_FDe_ID, CPD_For_ID, CPD_Fecha, CPD_Hora, CPD_Valor) VALUES($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, $Orden, 1, $ForID, '$Fecha', '$Hora', '$ImporteCuota')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                $todo_bien = true;


                if (!$todo_bien){ 
                    consulta_retroceder();
                    echo "AquiCobro - Error al guardar el detalle del pago!";
                }else{
                    consulta_terminar();
                    vaciarColaPagoUsuario();
                }
            
            }else {
                consulta_retroceder(); //del CuotaPago       
                echo "AquiCobro - Error al guardar datos del pago!";
            }
    
        }else echo "AquiCobro - Error al obtener el ID CCoID!";
    
    }else {
        consulta_retroceder(); //del Factura
        echo "AquiCobro - Error al generar el recibo de pago!";
        }


}//fin function



function guardarPagoCajaCorrienteOnLine($CajaID, $datosCuota, $importe, $ForID, $Recibo, $Fac_Detalle){
    
    obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
    
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
    $sql = "SELECT * FROM CuotaPersona
    INNER JOIN Lectivo 
        ON (Cuo_Lec_ID = Lec_ID)
    INNER JOIN Colegio_Nivel 
        ON (Cuo_Niv_ID = Niv_ID)
    INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CTi_ID)
    INNER JOIN CuotaAlternativa 
        ON (Cuo_Alt_ID = Alt_ID)
    INNER JOIN CuotaBeneficio 
        ON (Cuo_Ben_ID = Ben_ID)
    INNER JOIN Usuario 
        ON (Cuo_Usu_ID = Usu_ID)
     INNER JOIN Persona 
        ON (Cuo_Per_ID = Per_ID)
WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero);";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $Concepto = "[$Recibo: $row[Per_Apellido], $row[Per_Nombre]] $row[CTi_Nombre] ".buscarMes($row['Cuo_Mes'])."/$row[Cuo_Anio]";
        $Concepto = addslashes($Concepto);
        $Detalle = addslashes(utf8_encode("$row[Niv_Nombre] - $row[Ben_Nombre] - $Fac_Detalle"));
        
        consulta_iniciar();
        $sql = "INSERT INTO CajaCorriente (CCC_Caja_ID, CCC_Concepto, CCC_Credito, CCC_Usu_ID, CCC_Fecha, CCC_Hora, CCC_Detalle, CCC_Referencia, CCC_For_ID) VALUES($CajaID, '$Concepto', $importe, $UsuID, '$Fecha', '$Hora', '$Detalle', '$datosCuota', $ForID)";
        $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($res) {
            consulta_terminar();     
            $sql = "SELECT LAST_INSERT_ID()";
            $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            $row = mysqli_fetch_array($result);
            $CCCID = $row[0];
            actualizarSaldoCajaCorriente($CajaID, $CCCID);
        }else {
            consulta_retroceder(); 
            echo "AquiCobro - Error al cargar el pago en CajaCorriente!";
        }
    }
    return $CCCID;
    

}//fin function

?>