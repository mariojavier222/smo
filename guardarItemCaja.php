<?php 
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
session_name("sesion_abierta");
// incia sessiones
session_start();
//echo "Empezamos...<br />";

$ForID = $_POST['ForID'];
$Item = 0;
obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
foreach($_POST as $nombre_campo => $valor){ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   /*echo "$asignacion<br />"; 
   echo $i++;//*/
   
   
   if (substr($nombre_campo,0,6)=="CCC_ID"){
        $CCC_ID = $valor;
        $j = substr($nombre_campo,6,10);
        $valorForID = "CCC_For_ID".$j;
        $CCC_For_ID = $_POST[$valorForID];
        $valorForID = "For_ID".$j;
        $For_ID = $_POST[$valorForID];
        $valorNumCaja = "NumCaja".$j;
        $CajaID = $_POST[$valorNumCaja];
        $sql = "SELECT * FROM CajaCorriente WHERE CCC_ID=$CCC_ID;";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $datosCuota = $row['CCC_Referencia'];
            $CCC_Fecha = $row['CCC_Fecha'];            
        } 
        //echo $ImporteCuota;exit;
        list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(';', $datosCuota);       
        
        $sql = "UPDATE CajaCorriente SET CCC_For_ID = $For_ID WHERE CCC_ID=$CCC_ID;";
        //echo "$sql<br />";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        actualizarSaldoCajaCorriente($CajaID, $CCC_ID);

        $sql = "SELECT * FROM CuotaPagoDetalle
WHERE (CPD_Lec_ID = $Cuo_Lec_ID AND CPD_Per_ID = $Cuo_Per_ID AND CPD_Niv_ID = $Cuo_Niv_ID AND CPD_CTi_ID=$Cuo_CTi_ID AND CPD_Alt_ID=$Cuo_Alt_ID AND CPD_Numero=$Cuo_Numero AND CPD_Fecha = '$CCC_Fecha' AND CPD_For_ID = $CCC_For_ID);";
        $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)){
            	if ($row['CPD_FDe_ID'] = 1){
            		$Orden = $row['CPD_Orden'];
            		$ImporteCuota = $row[CPD_Valor];
            	}
            	//echo "$row[CPD_For_ID] - $row[CPD_FDe_ID]  - $row[CPD_Valor]<br />";
            }
            
        }
        $sql = "DELETE FROM CuotaPagoDetalle
WHERE (CPD_Lec_ID = $Cuo_Lec_ID AND CPD_Per_ID = $Cuo_Per_ID AND CPD_Niv_ID = $Cuo_Niv_ID AND CPD_CTi_ID=$Cuo_CTi_ID AND CPD_Alt_ID=$Cuo_Alt_ID AND CPD_Numero=$Cuo_Numero AND CPD_Fecha = '$CCC_Fecha' AND CPD_Orden = $Orden AND CPD_For_ID = $CCC_For_ID);";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		//Inserto el detalle del pago
        for ($i=2;$i<=10;$i++){
            $nombreCampoFDe = "campo".$i;
            
            if (isset($_POST[$nombreCampoFDe])){
                $sql = "INSERT INTO CuotaPagoDetalle (CPD_Lec_ID, CPD_Per_ID, CPD_Niv_ID, CPD_CTi_ID, CPD_Alt_ID, CPD_Numero, CPD_Orden, CPD_FDe_ID, CPD_For_ID, CPD_Fecha, CPD_Hora, CPD_Valor) VALUES($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, $Orden, $i, $For_ID, '$Fecha', '$Hora', '".$_POST[$nombreCampoFDe]."')";
                consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
            }            
        }//fin for
        //Hacemos una inserción solamente para cuando el FDeID = 1, es decir el importe
        $sql = "INSERT INTO CuotaPagoDetalle (CPD_Lec_ID, CPD_Per_ID, CPD_Niv_ID, CPD_CTi_ID, CPD_Alt_ID, CPD_Numero, CPD_Orden, CPD_FDe_ID, CPD_For_ID, CPD_Fecha, CPD_Hora, CPD_Valor) VALUES($Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero, $Orden, 1, $For_ID, '$Fecha', '$Hora', '$ImporteCuota')";
        consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        
        
        
    }//fin if
    //*/
} //fin foreach
echo "Se actualizó la Forma de Pago correctamente. Actualice para ver los cambios reflejados.";
?>