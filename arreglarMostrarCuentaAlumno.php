<?php
require_once("conexion.php");
require_once("funciones_generales.php");

$sql = "SET NAMES UTF8;";
//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

$sql = "SELECT DISTINCTROW Niv_Siglas, Cur_Siglas, Div_Siglas, Per_Apellido, Per_Nombre, Per_DNI, Per_ID FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Per_ID)
    INNER JOIN CuentaAlumno 
        ON (CuentaAlumno.CAl_Per_ID = Per_ID) WHERE Ins_Lec_ID = 17 ORDER BY Niv_ID, Cur_ID, Div_ID, Per_Apellido, Per_Nombre;"; 
        //echo $sql; exit;       
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
echo "<table border=1>";
echo "<tr>";
	echo "<td>NIVEL</td>";
	echo "<td>CURSO</td>";	
	echo "<td>ALUMNO</td>";	
	echo "<td>DNI</td>";
	echo "<td>DEBITO</td>";
	echo "<td>CREDITO</td>";
	echo "<td>FECHA</td>";
	echo "<td>CONCEPTO</td>";
	echo "<td>DETALLE</td>";
	echo "<td>FORMA PAGO</td>";
	echo "<td>DETALLE PAGO</td>";
	echo "</tr>";
while ($row = mysqli_fetch_array($result)){
	echo "<tr>";
	echo "<td>$row[Niv_Siglas]</td>";
	echo "<td>$row[Cur_Siglas]-$row[Div_Siglas]</td>";	
	echo "<td>$row[Per_Apellido], $row[Per_Nombre]</td>";	
	echo "<td>$row[Per_DNI]</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	/*$sql = "SELECT DISTINCTROW Niv_Siglas, Cur_Siglas, Div_Siglas, Per_Apellido, Per_Nombre, Per_DNI, Per_ID, CAl_Debito, CAl_Fecha, CAl_Concepto, CAl_Referencia FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Per_ID)
    INNER JOIN CuentaAlumno 
        ON (CuentaAlumno.CAl_Per_ID = Per_ID) WHERE Ins_Lec_ID = 17 AND (MONTH(CAl_Fecha)=11 OR MONTH(CAl_Fecha)=12 OR MONTH(CAl_Fecha)=10) AND YEAR(CAl_Fecha)=2017 AND CAl_Credito=0 AND CAl_Per_ID=$row[Per_ID] ORDER BY Niv_ID, Cur_ID, Div_ID, Per_Apellido, Per_Nombre;"; */
        $sql = "SELECT DISTINCTROW Niv_Siglas, Cur_Siglas, Div_Siglas, Per_Apellido, Per_Nombre, Per_DNI, Per_ID, CAl_Debito, CAl_Fecha, CAl_Concepto, CAl_Referencia FROM
    Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Per_ID)
    INNER JOIN CuentaAlumno 
        ON (CuentaAlumno.CAl_Per_ID = Per_ID) WHERE Ins_Lec_ID = 17 AND (MONTH(CAl_Fecha)=11 OR MONTH(CAl_Fecha)=12 OR MONTH(CAl_Fecha)=10) AND YEAR(CAl_Fecha)=2017 AND CAl_Credito=0 AND CAl_Per_ID=$row[Per_ID] ORDER BY Niv_ID, Cur_ID, Div_ID, Per_Apellido, Per_Nombre;";        
	$result2 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	while ($row2 = mysqli_fetch_array($result2)){
		echo "<tr>";		
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td>".number_format($row2[CAl_Debito],0,",",".")."</td>";
		echo "<td></td>";
		echo "<td>$row2[CAl_Fecha]</td>";
		echo "<td>$row2[CAl_Concepto]</td>";
		echo "<td>$row2[CAl_Detalle]</td>";
		echo "<td>$row2[CAl_FormaPago]</td>";
		echo "<td>$row2[CAl_PagoDetalle]</td>";
		echo "</tr>";
		$sql = "SELECT * FROM CuentaAlumno WHERE CAl_Referencia = '$row2[CAl_Referencia]' AND CAl_Debito=0";
		$result3 = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		while ($row3 = mysqli_fetch_array($result3)){
			echo "<tr>";		
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td>".number_format($row3[CAl_Credito],0,",",".")."</td>";
			echo "<td>$row3[CAl_Fecha]</td>";
			echo "<td>$row3[CAl_Concepto]</td>";
			echo "<td>$row3[CAl_Detalle]</td>";
			echo "<td>$row3[CAl_FormaPago]</td>";
			echo "<td>$row3[CAl_PagoDetalle]</td>";
			echo "</tr>";
		}//fin while 3
	}//fin while
}//fin while
echo "</table>";
?>