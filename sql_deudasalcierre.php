<?php //

function aplicarDebitoAlumno($DNI, $DocID, $FacID, $TChID, $ChSID){
    
    $sql = "SELECT * FROM Persona_Tarjeta 
		WHERE  PTa_Per_ID = $DNI AND PTa_Doc_ID = $DocID
	 AND PTa_Estado = 1 AND (PTa_Cuenta <> 0 AND PTa_Cuenta <> '')";
	$result = consulta_mysql($sql);	
    if (mysqli_num_rows($result)>0){
		$row = mysqli_fetch_array($result);
        $PTaID = $row[PTa_ID];
        //Buscamos las cuotas que se encuentran entre la fecha de alta y baja
        $sql = "SELECT * FROM Chequera_Cuota WHERE ChC_Fac_ID = $FacID
		AND ChC_TCh_ID = $TChID AND ChC_ChS_ID = $ChSID ";
        if (!empty($row[PTa_Alta])){
			$sql .= " AND ChC_1er_Vencimiento >= '$row[PTa_Alta]'";
		} 
		if (!empty($row[PTa_Baja])){
 	       $sql .= " AND ChC_1er_Vencimiento <= '$row[PTa_Baja]'";
		}
		$result = consulta_mysql($sql);
		while ($row = mysqli_fetch_array($result)){
            ponerDebitoCuota($row[ChC_Fac_ID], $row[ChC_TCh_ID], $row[ChC_ChS_ID], $row[ChC_Pro_ID], $row[ChC_Alt_ID], $row[ChC_Cuo_ID], $row[ChC_Mes], $row[ChC_Anio], $PTaID);
		}//fin while
	}//fin if

}//fin function


//function ponerDebitoCuota($FacID, $TChID, $ChSID, $ProID, $AltID, $CuoID, $Mes, $Anio, $PTaID){
//Dim strSQL As String
//Dim rstQuery As Recordset
//
//    // Si el PTaID = 0 (sin debito) elimina el registro
//    if ($PTaID == 0){
//        $sql = "DELETE FROM Chequera_Debito 
//		WHERE ChD_Fac_ID = $FacID 
//		AND ChD_TCh_ID = $TChID
//        AND ChD_ChS_ID = $ChSID 
//		AND ChD_Pro_ID = $ProID 
//		AND ChD_Alt_ID = $AltID
//        AND ChD_Cuo_ID = $CuoID;";
//        consulta_mysql($sql);
//        exit;
//	}//fin if
//    
//	// Verifica si existe y actualiza
//    $sql = "SELECT * FROM Chequera_Debito 
//	WHERE ChD_Fac_ID = $FacID 
//	AND ChD_TCh_ID = $TChID
//	AND ChD_ChS_ID = $ChSID 
//	AND ChD_Pro_ID = $ProID 
//	AND ChD_Alt_ID = $AltID
//	AND ChD_Cuo_ID = $CuoID;";
//
//    $result = consulta_mysql($sql);	
//    if (mysqli_num_rows($result)==0){
//        $sql = "INSERT INTO Chequera_Debito (ChD_Fac_ID, ChD_TCh_ID, ChD_ChS_ID, ChD_Pro_ID, ChD_Alt_ID, ChD_Cuo_ID, ChD_Mes, ChD_Anio, ChD_PTa_ID) VALUES ($FacID
//        , $TChID, $ChSID, $ProID, $AltID, $CuoID, $Mes, $Anio, $PTaID);";
//		consulta_mysql($sql);	
//	}//fin if
//
//}//fin function

























//////////////////////////
Public Sub aplicarDebitoAlumno(ByVal pPerID As Long, ByVal pDocID As Integer, ByVal pFacID As Integer, ByVal pTChID As Integer, ByVal pChSID As Long)
Dim strSQL As String
Dim rstQuery As Recordset
Dim vDebito As New clsDebito
Dim lngPTaID As Long
    
    strSQL = "SELECT * FROM Persona_Tarjeta WHERE "
    strSQL = strSQL & " PTa_Per_ID = " & pPerID
    strSQL = strSQL & " AND PTa_Doc_ID = " & pDocID
    strSQL = strSQL & " AND PTa_Estado = 1"
    strSQL = strSQL & " AND (PTa_Cuenta <> 0 AND PTa_Cuenta <> '')"
    Set rstQuery = DB.resultados(strSQL)
    If Not rstQuery.EOF Then
        lngPTaID = rstQuery!PTa_ID
        'Buscamos las cuotas que se encuentran entre la fecha de alta y baja
        strSQL = "SELECT * FROM Chequera_Cuota "
        strSQL = strSQL & " WHERE ChC_Fac_ID = " & pFacID
        strSQL = strSQL & " AND ChC_TCh_ID = " & pTChID
        strSQL = strSQL & " AND ChC_ChS_ID = " & pChSID
        If Not IsNull(rstQuery!PTa_Alta) Then strSQL = strSQL & " AND ChC_1er_Vencimiento >= " & FechaDB(rstQuery!PTa_Alta)
        If Not IsNull(rstQuery!PTa_Baja) Then strSQL = strSQL & " AND ChC_1er_Vencimiento <= " & FechaDB(rstQuery!PTa_Baja)
        rstQuery.Close
        Set rstQuery = DB.resultados(strSQL)
        Do While Not rstQuery.EOF
            vDebito.ponerDebitoCuota rstQuery!ChC_Fac_ID, rstQuery!ChC_TCh_ID, rstQuery!ChC_ChS_ID, rstQuery!ChC_Pro_ID, rstQuery!ChC_Alt_ID, rstQuery!ChC_Cuo_ID, rstQuery!ChC_Mes, rstQuery!ChC_Anio, lngPTaID
            rstQuery.MoveNext
        Loop
    End If
End Sub


Public Sub ponerDebitoCuota(ByVal pFacID As Integer, ByVal pTChID As Integer, ByVal pChSID As Long, ByVal pProID As Integer, ByVal pAltID As Integer, ByVal pCuoID As Integer, ByVal pMes As Integer, ByVal pAnho As Integer, ByVal pPTaID As Long)
Dim strSQL As String
Dim rstQuery As Recordset

    ' Si el PTaID = 0 (sin debito) elimina el registro
    If pPTaID = 0 Then
        strSQL = "DELETE FROM Chequera_Debito"
        strSQL = strSQL & " WHERE ChD_Fac_ID = " & pFacID
        strSQL = strSQL & " AND ChD_TCh_ID = " & pTChID
        strSQL = strSQL & " AND ChD_ChS_ID = " & pChSID
        strSQL = strSQL & " AND ChD_Pro_ID = " & pProID
        strSQL = strSQL & " AND ChD_Alt_ID = " & pAltID
        strSQL = strSQL & " AND ChD_Cuo_ID = " & pCuoID & ";"
        DB.ejecutarSQL strSQL
        Exit Sub
    End If
    ' Verifica si existe y actualiza
    strSQL = "SELECT * FROM Chequera_Debito"
    strSQL = strSQL & " WHERE ChD_Fac_ID = " & pFacID
    strSQL = strSQL & " AND ChD_TCh_ID = " & pTChID
    strSQL = strSQL & " AND ChD_ChS_ID = " & pChSID
    strSQL = strSQL & " AND ChD_Pro_ID = " & pProID
    strSQL = strSQL & " AND ChD_Alt_ID = " & pAltID
    strSQL = strSQL & " AND ChD_Cuo_ID = " & pCuoID & ";"
    Set rstQuery = DB.resultados(strSQL)
    If rstQuery.EOF Then
        strSQL = "INSERT INTO Chequera_Debito (ChD_Fac_ID, ChD_TCh_ID, ChD_ChS_ID, ChD_Pro_ID, ChD_Alt_ID, ChD_Cuo_ID, ChD_Mes, ChD_Anio, ChD_PTa_ID)"
        strSQL = strSQL & " VALUES (" & pFacID
        strSQL = strSQL & ", " & pTChID
        strSQL = strSQL & ", " & pChSID
        strSQL = strSQL & ", " & pProID
        strSQL = strSQL & ", " & pAltID
        strSQL = strSQL & ", " & pCuoID
        strSQL = strSQL & ", " & pMes
        strSQL = strSQL & ", " & pAnho
        strSQL = strSQL & ", " & pPTaID & ");"
'Eliminado para que no pise el d�bito que ya tiene generado
'''    Else
'''        strSQL = "UPDATE Chequera_Debito"
'''        strSQL = strSQL & " SET ChD_PTa_ID = " & pPTaID
'''        strSQL = strSQL & " WHERE ChD_Fac_ID = " & pFacId
'''        strSQL = strSQL & " AND ChD_TCh_ID = " & pTChID
'''        strSQL = strSQL & " AND ChD_ChS_ID = " & pChSID
'''        strSQL = strSQL & " AND ChD_Pro_ID = " & pProID
'''        strSQL = strSQL & " AND ChD_Alt_ID = " & pAltID
'''        strSQL = strSQL & " AND ChD_Cuo_ID = " & pCuoID & ";"
    End If
    rstQuery.Close
    DB.ejecutarSQL strSQL
End Sub
?>
