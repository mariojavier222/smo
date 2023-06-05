<?php
//23012013 17 h

require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
//sleep(3);

$opcion = $_POST['opcion'];

if ($opcion=="cargarCurso"){
	$nivel = $_POST['nivel'];
	$nombre = $_POST['objeto'];
	cargarListaCursos2($nombre, $agregarTodos=false, $nivel);
	exit;
}

if ($opcion=="guardarEntrevista"){
    $DNI=$_POST['DNI_Ent'];
    
//    $Ent_Lec_ID = $_POST['EntLecID'];
//    $Ent_Cur_ID= $_POST['EntCurID'];
//    $Ent_Niv_ID= $_POST['EntNivID'];

    $Ent_Sic_ID= $_POST['Ent_Sic_ID'];
  //  $Ent_Uni_ID= $_POST['Ent_Uni_ID'];

    $Ent_Turno= $_POST['Ent_Turno'];
    $Ent_Fecha= $_POST['Ent_Fecha'];
    $Ent_Hora= $_POST['Ent_Hora'];
    $Ent_Asistio = $_POST['Ent_Asistio'];
    $Ent_Estado = $_POST['Ent_Estado'];

    $sql=" SELECT Per_ID FROM Persona WHERE Per_DNI=$DNI ";
    
    //consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $row = mysqli_fetch_array($result);
    $Ent_Per_ID=$row[Per_ID];
	$sql1=" INSERT INTO Entrevista (Ent_Per_ID,Ent_Turno,Ent_Fecha,Ent_Hora,Ent_Asistio,Ent_Estado,Ent_Sic_ID)
	VALUES ($Ent_Per_ID,'$Ent_Turno','$Ent_Fecha','$Ent_Hora','$Ent_Asistio','$Ent_Estado',$Ent_Sic_ID) ";
    //echo $sql;
	consulta_mysql_2022($sql1,basename(__FILE__),__LINE__);

	echo "Los datos de la Entrevista han sido insertados correctamente.";
}//fin del esle

if ($opcion=="actualizarEntrevista"){
    
    $Ent_Per_ID= $_POST['Ent_Per_ID'];
    $Ent_Lec_ID = $_POST['EntLecID'];

    //$Ent_Sic_ID= $_POST['Ent_Sic_ID'];
  //  $Ent_Uni_ID= $_POST['Ent_Uni_ID'];

    $Ent_Turno= $_POST['Ent_Turno'];
    $Ent_Fecha= $_POST['Ent_Fecha'];
    $Ent_Hora= $_POST['Ent_Hora'];
    $Ent_Asistio= $_POST['Ent_Asistio'];
    $Ent_Estado= $_POST['Ent_Estado'];

    // se comento  Ent_Sic_ID = $Ent_Sic_ID,
	
    $sql="UPDATE Entrevista SET  
    Ent_Lec_ID =$Ent_Lec_ID,
    Ent_Turno = '$Ent_Turno',
    Ent_Fecha = '$Ent_Fecha',
    Ent_Hora = '$Ent_Hora',
    Ent_Asistio = '$Ent_Asistio',
    Ent_Estado = '$Ent_Estado'
   WHERE Ent_Per_ID = $Ent_Per_ID";
   //echo $sql;
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    echo "Los datos de la Entrevista han sido actualizados correctamente.";
}


?>