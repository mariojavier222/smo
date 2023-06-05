<?php

//ppppepepr
function Anular_Factura()
{
	$Fac_ID = $_POST['Fac_ID'];
	$Fac_NotaCredito = $_POST['Fac_NotaCredito']; //ID de la Factura deNota de Credito
	$infoFacturaNotaCredito = buscarInfoFactura($Fac_NotaCredito);
	obtenerRegistroUsuario($UsuID, $Fecha, $Hora);
	if ((!empty($Fac_ID)) && (!empty($Fac_NotaCredito))) {

		$sql = "SET NAMES UTF8;";
		consulta_mysql_2022($sql, basename(__FILE__), __LINE__);

		$sql2 = "SELECT * FROM FacturaDetalle where FDe_Fac_ID='$Fac_ID' ORDER BY FDe_Item ASC;";
		$result2 = consulta_mysql_2022($sql2, basename(__FILE__), __LINE__);

		while ($row2 = mysqli_fetch_array($result2)) {
			$FDe_Item = $row2['FDe_Item'];
			$sqlCup = "UPDATE CuotaPago SET CuP_Anulada = '1'
		WHERE CuP_Fac_ID='$Fac_ID' AND CuP_FDe_Item='$FDe_Item';";
			consulta_mysql_2022($sqlCup, basename(__FILE__), __LINE__);

			$sql = "SELECT * FROM CuotaPago WHERE  CuP_FDe_Item='$FDe_Item' AND CuP_Fac_ID='$Fac_ID'";
			$result = consulta_mysql_2022($sql, basename(__FILE__), __LINE__);
			$row = mysqli_fetch_array($result);
			$CuP_CTi_ID = $row[CuP_CTi_ID];
			$CuP_CCo_ID = $row[CuP_CCo_ID];
			$CCo_Debito = $row[CuP_Importe];
			$CuP_Per_ID = $row[CuP_Per_ID];
			$CuP_Lec_ID = $row[CuP_Lec_ID];
			$CuP_Per_ID = $row[CuP_Per_ID];
			$CuP_Niv_ID = $row[CuP_Niv_ID];
			$CuP_CTi_ID = $row[CuP_CTi_ID];
			$CuP_Alt_ID = $row[CuP_Alt_ID];
			$CuP_Numero = $row[CuP_Numero];

			$CCC_Referencia = $row[CuP_Lec_ID] . ";" . $row[CuP_Per_ID] . ";" . $row[CuP_Niv_ID] . ";" . $row[CuP_CTi_ID] . ";" . $row[CuP_Alt_ID] . ";" . $row[CuP_Numero];

			if ($CuP_CTi_ID == 16 || $CuP_CTi_ID == 17) {
				$sqlCup = "UPDATE CuotaPersona SET Cuo_Pagado = '0', Cuo_Anulado='1', Cuo_Motivo = 'Dado de baja por nota de credito' WHERE
        Cuo_Lec_ID = '$CuP_Lec_ID' AND Cuo_Per_ID = '$CuP_Per_ID' AND Cuo_Niv_ID = '$CuP_Niv_ID' AND Cuo_CTi_ID = '$CuP_CTi_ID' AND Cuo_Alt_ID = '$CuP_Alt_ID' AND Cuo_Numero = '$CuP_Numero' ;";
				consulta_mysql_2022($sqlCup, basename(__FILE__), __LINE__);
			}
			else {
				$sqlCup = "UPDATE CuotaPersona SET Cuo_Pagado = '0' WHERE
        Cuo_Lec_ID = '$CuP_Lec_ID' AND Cuo_Per_ID = '$CuP_Per_ID' AND Cuo_Niv_ID = '$CuP_Niv_ID' AND Cuo_CTi_ID = '$CuP_CTi_ID' AND Cuo_Alt_ID = '$CuP_Alt_ID' AND Cuo_Numero = '$CuP_Numero' ;";
				consulta_mysql_2022($sqlCup, basename(__FILE__), __LINE__);
			}
			//FIN CUOTA PERSONA

			$sql34 = "SELECT * FROM CuotaTipo WHERE CTi_ID='$CuP_CTi_ID'";
			$result34 = consulta_mysql_2022($sql34, basename(__FILE__), __LINE__);
			$row34 = mysqli_fetch_array($result34);
			$CTi_Nombre = $row34[CTi_Nombre];

			//CUENTA CORRIENTE
			$sqCCO = "SELECT * FROM CuentaCorriente WHERE CCo_ID='$CuP_CCo_ID'";
			$resultCCO = consulta_mysql_2022($sqCCO, basename(__FILE__), __LINE__);
			$rowCCO = mysqli_fetch_array($resultCCO);
			$CCo_Concepto = $rowCCO[CCo_Concepto];
			//FIN CUENTA CORRIENTE

			$CCo_Usu_ID = $_SESSION['sesion_usuario'];

			$sq55 = "SELECT * FROM Usuario WHERE Usu_Nombre='$CCo_Usu_ID';";
			$result55 = consulta_mysql_2022($sq55, basename(__FILE__), __LINE__);
			$row55 = mysqli_fetch_array($result55);
			$CCo_Usu_ID = $row55[Usu_ID];

			$CCo_Concepto = "$infoFacturaNotaCredito. Anula Pago " . $CTi_Nombre . " - " . $CCo_Concepto;

			$sqlCup = "INSERT INTO CuentaCorriente (
		CCo_Per_ID, 
		CCo_Concepto, 
		CCo_Debito,  
		CCo_Saldo, 
		CCo_Fecha, 
		CCo_Hora, 
		CCo_Usu_ID)
		VALUES (
		'$CuP_Per_ID', 
		'$CCo_Concepto', 
		'$CCo_Debito', 
		'0', 
		CURDATE(), 
		CURTIME(), 
		'$CCo_Usu_ID'
		)";
			consulta_mysql_2022($sqlCup, basename(__FILE__), __LINE__);

			$sql12 = "select last_insert_id()";
			$result12 = consulta_mysql_2022($sql12, basename(__FILE__), __LINE__);
			$row12 = mysqli_fetch_array($result12);

			actualizarSaldoCtaCte($CuP_Per_ID, $row12[0]);

			$CajaID = cajaAbiertaUsuario($UsuID);
			if (!$CajaID) {
				$disabled = "disabled=disabled";
			}
			else {
				//NUEVO CAJA CORRIENTE
				//$CCC_Referencia = $row[CuP_Lec_ID].";".$row[CuP_Per_ID].";".$row[CuP_Niv_ID].";".$row[CuP_CTi_ID].";".$row[CuP_Alt_ID].";".$row[CuP_Numero];
				$sqlCaja = "SELECT * FROM CajaCorriente WHERE CCC_Referencia='$CCC_Referencia'";
				$resultCaja = consulta_mysql_2022($sqlCaja, basename(__FILE__), __LINE__);
				$rowCaja = mysqli_fetch_array($resultCaja);
				$CajaID2 = $rowCaja[CCC_Caja_ID];
				$CCC_For_ID = $rowCaja[CCC_For_ID];
				$CCC_Detalle = $rowCaja[CCC_Detalle];

				if ($CajaID2 == $CajaID) {
					$bandera = 0;
					$sqlfac2 = "SELECT * FROM Factura INNER JOIN FacturaTipo ON (Fac_FTi_ID = FTi_ID) WHERE Fac_ID=$Fac_ID";
					$resultfac2 = consulta_mysql_2022($sqlfac2, basename(__FILE__), __LINE__);
					$rowfac2 = mysqli_fetch_array($resultfac2);

					$CCC_Concepto = $CCo_Concepto . ". Recibo: $rowfac2[FTi_Nombre] $rowfac2[Fac_Sucursal]-$rowfac2[Fac_Numero]";

					$sqlCup = "INSERT INTO CajaCorriente ( 
				CCC_Caja_ID, 
				CCC_Concepto, 
				CCC_Debito, 
				CCC_Fecha, 
				CCC_Hora, 
				CCC_Usu_ID, 
				CCC_For_ID,
			    CCC_Detalle,
			    CCC_Referencia)
				VALUES (
				'$CajaID', 
				'$CCC_Concepto', 
				'$CCo_Debito',  
				 CURDATE(), 
				 CURTIME(), 
				'$CCo_Usu_ID', 
				'$CCC_For_ID',
			    '$CCC_Detalle',
			    '$CCC_Referencia'
				);";
					consulta_mysql_2022($sqlCup, basename(__FILE__), __LINE__);
					$sql12 = "select last_insert_id()";
					$result12 = consulta_mysql_2022($sql12, basename(__FILE__), __LINE__);
					$row12 = mysqli_fetch_array($result12);
					actualizarSaldoCajaCorriente($CajaID, $row12[0]);
				}
				else {
					$bandera = 1;
				}
			//FIN DE NUEVO CAJA CORRIENTE
			} // else de la caja si esta abierta
		} // FIN WHILE


		$sqlCup = "UPDATE Factura SET Fac_Pagada = '0', Fac_Anulada = '1' WHERE Fac_ID = '$Fac_ID';";
		consulta_mysql_2022($sqlCup, basename(__FILE__), __LINE__);
	}
	else
		echo "ERROR: faltan datos!";



} //fin de function


?>