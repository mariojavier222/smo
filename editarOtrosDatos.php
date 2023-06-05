<?php
//sleep(3);


if($_POST['mensaje'] != ''){
    echo $_POST['mensaje'];
}



$DNI = $_POST['DNI']; 

if (empty($DNI))
	echo "La persona no existe el DNI es: ." ;
	// aca va la consulta para grabar los datos que la persona cargue o edite 
	
else
	{

//Eliminamos el registro que haya tenido el alumno
//$sql = "DELETE FROM PersonaDatos WHERE Dat_Per_ID = $ID";
//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
//Luego insertamos el nuevo registro con las modificaciones incluidas
//$sql = "INSERT INTO PersonaDatos (Dat_Per_ID, Dat_Per_ID, Dat_Per_ID, Dat_Per_, Dat_Per_, Dat_Per_, Dat_Per_,Dat_Per_, Dat_Per_, Dat_Per_, Dat_Per_, Dat_Per_, Dat_Per_, Dat_Per_,Dat_Per_, Dat_Per_, Dat_Per_,Dat_Per_) values(".$ID.",
// "$_POST['']",
// "$_POST['']",
 //"$_POST['']",
// "$_POST['']",
 //"$_POST['']",
 //"$_POST['']",
 //"$_POST['']",
 //"$_POST['']",
 //"$_POST['']",
 //"$_POST['']",
 //"$_POST['']",
 //"$_POST['']",
 //"$_POST['']", "$_POST['']", "$_POST['']", "$_POST['']", "$_POST['']",  date('Y-m-d'), date('H:i:s')";


//consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	echo "Los datos son correctos"; //hacer boton volver 
	echo $_POST['mensaje'];
	echo $_POST['DNI'];
	};
?>
