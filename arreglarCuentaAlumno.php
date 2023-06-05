<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
/*$sql = "TRUNCATE CuentaAlumno;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);*/
$fechaGenerada = "2017-12-01";
$mes = 12;
$anio = 2017;

$sql = "SELECT * FROM CuotaPersona INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CuotaTipo.CTi_ID) WHERE Cuo_Mes=$mes AND Cuo_Anio=$anio AND Cuo_Per_ID=95 ORDER BY Cuo_Per_ID";
$sql = "SELECT * FROM CuotaPersona INNER JOIN CuotaTipo 
        ON (Cuo_CTi_ID = CuotaTipo.CTi_ID) WHERE Cuo_Mes=$mes AND Cuo_Anio=$anio ORDER BY Cuo_Per_ID";// LIMIT 0,100";    
//echo "$sql<br>";     
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
while ($row = mysqli_fetch_array($result)){
	set_time_limit(0);
	//echo "$row[Cuo_Per_ID]<br>";
	$ConceptoOriginal = "$row[CTi_Nombre] ".buscarMes($row['Cuo_Mes'])."/".$row['Cuo_Anio'];
	$Debito = $row['Cuo_Importe'];
	if ($row['Cuo_ImporteOriginal']>$row['Cuo_Importe'] && $row['Cuo_Ben_ID']!=1) $Debito = $row['Cuo_ImporteOriginal'];
	$Credito = 0;
	$datosCuota = $row['Cuo_Lec_ID'].";".$row['Cuo_Per_ID'].";".$row['Cuo_Niv_ID'].";".$row['Cuo_CTi_ID'].";".$row['Cuo_Alt_ID'].";".$row['Cuo_Numero'];
	$detalle = "1º Vto: ".cfecha($row[Cuo_1er_Vencimiento]).". 1º Rec: $row[Cuo_1er_Recargo]. 2º Vto: ".cfecha($row[Cuo_2do_Vencimiento]).". 2º Rec: $row[Cuo_2do_Recargo]. Rec. Mensual: $row[Cuo_Recargo_Mensual]";
	$formaPago = "";
	$pagoDetalle = "";
	$fecha = $row['Cuo_Fecha'];
	$hora = $row['Cuo_Hora'];
	if ($fecha=="0000-00-00"){
		$fecha = $fechaGenerada;
		$hora = "15:00:00";
	}
	guardarAlumnoCuenta($row['Cuo_Per_ID'], $ConceptoOriginal, $Debito, $Credito, $datosCuota, $detalle, $formaPago, $pagoDetalle, $fecha, $hora);
	//Preguntamos si el alumno tiene un beneficio
	if ($row['Cuo_Ben_ID']!=1){
		//Tiene beneficio, calculamos el descuento
		$descuentoBeneficio = $row['Cuo_ImporteOriginal'] - $row['Cuo_Importe'];
		$Concepto = "Descuento beneficio ".obtenerBeneficio($row['Cuo_Ben_ID'])." $ConceptoOriginal";
		guardarAlumnoCuenta($row['Cuo_Per_ID'], $Concepto, 0, $descuentoBeneficio, $datosCuota, $detalle, $formaPago, $pagoDetalle, $fecha, $hora);
	}

	//Preguntamos si la cuota fue anulada
	if ($row['Cuo_Anulado']==1){
		//echo "Entre ANULADO<br>";
		$Concepto = "ANULADA $ConceptoOriginal";
		$detalle = $row['Cuo_Motivo'];
		guardarAlumnoCuenta($row['Cuo_Per_ID'], $Concepto, 0, $Debito, $datosCuota, $detalle, $formaPago, $pagoDetalle, $fecha, $hora);
	}

	//Preguntamos si la cuota fue pagada
	if ($row['Cuo_Pagado']==1){
		//echo "Entre pagado<br>";
		list( $Cuo_Lec_ID, $Cuo_Per_ID, $Cuo_Niv_ID, $Cuo_CTi_ID, $Cuo_Alt_ID, $Cuo_Numero ) = explode(";", $datosCuota);
		$sql = "SELECT * FROM CuotaPersona INNER JOIN CuotaPago
	    ON (CuP_Lec_ID = Cuo_Lec_ID) AND (CuP_Per_ID = Cuo_Per_ID) AND (CuP_Niv_ID = Cuo_Niv_ID) AND (CuP_CTi_ID = Cuo_CTi_ID) AND (CuP_Alt_ID = Cuo_Alt_ID) AND (CuP_Numero = Cuo_Numero)
	    INNER JOIN Factura 
        ON (CuotaPago.CuP_Fac_ID = Factura.Fac_ID)
	WHERE (Cuo_Lec_ID = $Cuo_Lec_ID AND Cuo_Per_ID = $Cuo_Per_ID AND Cuo_Niv_ID = $Cuo_Niv_ID AND Cuo_CTi_ID=$Cuo_CTi_ID AND Cuo_Alt_ID=$Cuo_Alt_ID AND Cuo_Numero=$Cuo_Numero);";
		$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result2) > 0) {
			while ($row2 = mysqli_fetch_array($result2)){
				$fecha = $row2['CuP_Fecha'];
				$hora = $row2['CuP_Hora'];
				$CuP_Importe = $row2['CuP_Importe'];
				$formaPago = buscarFormaPagoCuota($datosCuota, $Fac_ID, $detalle);
				$Concepto = "PAGO $ConceptoOriginal";
				$pagoDetalle = buscarFormaPagoCuotaDetalle($datosCuota);
				guardarAlumnoCuenta($row['Cuo_Per_ID'], $Concepto, 0, $CuP_Importe, $datosCuota, $detalle, $formaPago, $pagoDetalle, $fecha, $hora);
				if ($row2['CuP_Anulada']==1){
					$Concepto = "PAGO ANULADO $ConceptoOriginal";
					guardarAlumnoCuenta($row['Cuo_Per_ID'], $Concepto, $CuP_Importe, 0, $datosCuota, $detalle, $formaPago, $pagoDetalle, $fecha, $hora);
				}
			}
		}
	}//fin Pagado
}

function guardarAlumnoCuenta($PerID, $Concepto, $Debito=0, $Credito=0, $datosCuota="", $detalle="", $formaPago="", $pagoDetalle="", $fecha="", $hora=""){
	
	obtenerRegistroUsuario($CAl_Usu_ID, $CAl_Fecha, $CAl_Hora);
	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	if (!empty($fecha)) {
		$CAl_Fecha = $fecha;
		$CAl_Hora = $hora;
	}
	$sql = "SELECT * FROM CuentaAlumno WHERE CAl_Per_ID = $PerID AND CAl_Concepto = '$Concepto' AND CAl_Debito='$Debito' AND CAl_Credito='$Credito' AND CAl_Fecha='$CAl_Fecha' AND CAl_Hora='$CAl_Hora' AND CAl_Detalle='$detalle' AND CAl_Referencia='$datosCuota' AND CAl_FormaPago='$formaPago' AND CAl_PagoDetalle='$pagoDetalle'";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result) == 0){
			$sql = "INSERT INTO CuentaAlumno (CAl_Per_ID, CAl_Concepto, CAl_Debito, CAl_Credito, CAl_Fecha, CAl_Hora, CAl_Detalle, CAl_Referencia, CAl_Usu_ID, CAl_FormaPago, CAl_PagoDetalle) VALUES($PerID, '$Concepto', '$Debito', '$Credito', '$CAl_Fecha', '$CAl_Hora', '$detalle', '$datosCuota', '$CAl_Usu_ID', '$formaPago', '$pagoDetalle')";
			//echo "$sql<br>";
			//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			
		    	$res=ejecutar_2022($sql,basename(__FILE__),__LINE__);
		        if ($res['success'] == true){
		            $CAl_ID = $res['id'];
		        }else{
		            echo "Error"; exit;
		        }

			actualizarSaldoAlumnoCuenta($PerID, $CAl_ID);

			return $CAl_ID;
		}
	
	
	

}//fin function 

function actualizarSaldoAlumnoCuenta($PerID, $CAl_ID){
	$sql = "SELECT SUM(CAl_Debito) AS Debe, SUM(CAl_Credito) AS Haber FROM CuentaAlumno WHERE CAl_Per_ID = $PerID";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$Haber = intval($row['Haber']) - intval($row['Debe']);
		$sql = "UPDATE CuentaAlumno SET CAl_Saldo = '$Haber' WHERE CAl_ID = $CAl_ID";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	}

}//fin function


?>