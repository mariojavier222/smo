<?php
require_once("conexion.php");
echo "Comienzo<br />";
$sql = "SELECT * FROM Persona_Tarjeta 
		WHERE  PTa_Per_ID = 32454647 AND PTa_Doc_ID = 1
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
		echo $sql;
	}//fin if
	echo "Alta: $row[PTa_Alta]<br />";
echo "<br />FIN";
?>
