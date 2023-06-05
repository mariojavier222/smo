<?php
header("Cache-Control: no-cache, must-revalidate"); 
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");

$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch($opcion){

	case "guardarBeneficioPersona":
		guardarBeneficioPersona();
		break;	

	case "eliminarBeneficioPersona":
		eliminarBeneficioPersona();
		break;	

	case "listarBeneficioPersona":
		listarBeneficioPersona();
		break;	

	default: 
		echo "La opción elegida no es válida";
}//fin switch


function guardarBeneficioPersona(){
	
	$CBeID = $_POST['CBeID'];
	$PerID = $_POST['PerID'];
	$LecID = $_POST['LecID'];
	$Desde = $_POST['Desde'];
	$Hasta = $_POST['Hasta'];
	$BenID = $_POST['BenID'];
	$CTiID = $_POST['CTiID'];
/*
foreach($_POST as $campo => $valor){
  echo "- ". $campo ." = ". $valor;
}
*/	
	$sql = "SET NAMES UTF8;";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

	if ($CBeID == 0){//es nuevo el beneficio
		//busco si la persona tiene otro beneficio en el ciclo lectivo
		$sql = "SELECT * FROM PersonaBeneficio WHERE 
		CBe_Per_ID = $PerID AND 
		CBe_Lec_ID = $LecID AND
		CBe_Activo=1";
		$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($result)==0){//no hay. Inserto		
			$sql="INSERT INTO PersonaBeneficio (
			CBe_Ben_ID,
			CBe_Lec_ID,
			CBe_Per_ID,
			CBe_Desde,
			CBe_Hasta,
			CBe_CTi_ID)	VALUES (
			'$BenID',
			'$LecID',
			'$PerID',
			'$Desde',
			'$Hasta',
			'$CTiID');";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "BENEFICIO GUARDADO ";
		}else {
			if (mysqli_num_rows($result)==1){
				$sqlb = "SELECT * FROM PersonaBeneficio WHERE 
				CBe_Per_ID = $PerID AND 
				CBe_Lec_ID = $LecID AND
				CBe_CTi_ID = $CTiID AND 
				CBe_Activo=1";
				$resultb = consulta_mysql_2022($sqlb,basename(__FILE__),__LINE__);
				if (mysqli_num_rows($resultb)==0){
					$sql="INSERT INTO PersonaBeneficio (
					CBe_Ben_ID,
					CBe_Lec_ID,
					CBe_Per_ID,
					CBe_Desde,
					CBe_Hasta,
					CBe_CTi_ID)	VALUES (
					'$BenID',
					'$LecID',
					'$PerID',
					'$Desde',
					'$Hasta',
					'$CTiID');";
					consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
					echo "BENEFICIO GUARDADO ";
				}else echo "YA TIENE UN BENEFICIO EN ESE TIPO DE CUOTA EN ESTE CICLO LECTIVO!";

			}else echo "YA TIENE EL LIMITE DE BENEFICIOS EN ESTE CICLO LECTIVO!";
		}

	}else {//ya existe actualizo SIEMPRE QUE NO EXISTA UNO CON ESE TIPO DE CUOTA
		$sqlb = "SELECT * FROM PersonaBeneficio WHERE 
		CBe_Per_ID = $PerID AND 
		CBe_Lec_ID = $LecID AND
		CBe_CTi_ID = $CTiID AND 
		CBe_ID != $CBeID AND 
		CBe_Activo=1";
		$resultb = consulta_mysql_2022($sqlb,basename(__FILE__),__LINE__);
		if (mysqli_num_rows($resultb)==0){
			$sql="UPDATE PersonaBeneficio SET
			CBe_Ben_ID = '$BenID',
			CBe_Lec_ID = '$LecID',
			CBe_Per_ID = '$PerID',
			CBe_Desde = '$Desde',
			CBe_Hasta = '$Hasta',
			CBe_CTi_ID = '$CTiID'
			WHERE CBe_ID = '$CBeID';";
			consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
			echo "BENEFICIO ACTUALIZADO ";
		}else echo "YA TIENE UN BENEFICIO EN ESE TIPO DE CUOTA EN ESTE CICLO LECTIVO!";
	}
	
}//fin funcion

function eliminarBeneficioPersona(){

	$ID = $_POST['ID'];
	
	$sql = "SELECT * FROM PersonaBeneficio WHERE CBe_ID = $ID;";
	$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	if (mysqli_num_rows($result)==0){//no existe
		echo "El beneficio no existe o ya fue eliminado.";
	}else{
		$sql = "UPDATE PersonaBeneficio SET CBe_Activo = 0 WHERE CBe_ID = $ID;";
		consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
		echo "Se eliminó el beneficio ".$ID;
	}

}//fin funcion

function listarBeneficioPersona(){
	$Per_ID = $_POST['PerID'];
?>

	<table width="98%" border="0" align="center" class="borde_recuadro">
		<tr>
			<td><div align="center" class="titulo_noticia"> Listado de Beneficios otorgados por Ciclo Lectivo</div></td>
		</tr>
		<tr>
			<td align="center" class="texto">
				<?php
				$sql = "SET NAMES UTF8;";
				consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				$sql = "SELECT PersonaBeneficio.*, Ben_Nombre, Lec_Nombre, CTi_Nombre 
				FROM PersonaBeneficio 
				INNER JOIN CuotaBeneficio ON (CBe_Ben_ID = Ben_ID)    
				INNER JOIN CuotaTipo ON (CBe_CTi_ID = CTi_ID)    
				INNER JOIN Lectivo ON (CBe_Lec_ID = Lec_ID)    
				WHERE CBe_Per_ID = $Per_ID AND CBe_Activo=1";
				$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
				if (mysqli_num_rows($result) > 0) {
					?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
						<thead>
							<tr class="ui-widget-header">
								<th align="center">Lectivo</th>
								<th align="center">Tipo de cuota</th>
								<th align="center">Desde</th>
								<th align="center">Hasta</th>
								<th align="center">Beneficio</th>
								<th align="center">Acci&oacute;n</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 0;
						while ($row = mysqli_fetch_array($result)) {
							$i++;
							?>
							<tr>
								<td align="center">
									<input name="CBe_ID<?php echo $i;?>" type="hidden" id="CBe_ID<?php echo $i;?>" value="<?php echo $row['CBe_ID']; ?>" />
									<input name="CBe_Per_ID<?php echo $i;?>" type="hidden" id="CBe_Per_ID<?php echo $i;?>" value="<?php echo $row['CBe_Per_ID']; ?>" />			
									<input name="CBe_Lec_ID<?php echo $i;?>" type="hidden" id="CBe_Lec_ID<?php echo $i;?>" value="<?php echo $row['CBe_Lec_ID']; ?>" />
									<input name="CBe_Desde<?php echo $i;?>" type="hidden" id="CBe_Desde<?php echo $i;?>" value="<?php echo $row['CBe_Desde']; ?>" />
									<input name="CBe_Hasta<?php echo $i;?>" type="hidden" id="CBe_Hasta<?php echo $i;?>" value="<?php echo $row['CBe_Hasta']; ?>" />
									<input name="CBe_Ben_ID<?php echo $i;?>" type="hidden" id="CBe_Ben_ID<?php echo $i;?>" value="<?php echo $row['CBe_Ben_ID']; ?>" />
									<input name="CBe_CTi_ID<?php echo $i;?>" type="hidden" id="CBe_CTi_ID<?php echo $i;?>" value="<?php echo $row['CBe_CTi_ID']; ?>" />
									<?php echo $row['Lec_Nombre']; ?> 
								</td>
								<td align="center"><?php echo $row['CTi_Nombre']; ?></td>
								<td align="center"><?php echo cfecha($row['CBe_Desde']); ?></td>
								<td align="center"><?php echo cfecha($row['CBe_Hasta']); ?></td>
								<td align="center"><?php echo $row['Ben_Nombre']; ?></td>
								<td align="center">                                    
									<a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar" title="Editar" width="32" height="32" border="0" /></a>
									<a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a>
								</td>
							</tr>
						<?php
                            }//fin while
                        ?>
                        </tbody>
                    </table>

                    <?php
                } else {
                	?>
                	El alumno no tiene beneficios cargados.
                	<?php
                }
                ?>

            </td>
        </tr>
    </table>	
<?php    
}
?>