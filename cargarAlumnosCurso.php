<?php
include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
include_once("guardarAccesoOpcion.php");


//echo "Hola";exit;
	$sql = "SET NAMES UTF8";
	consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
	
	Obtener_LectivoActual($Lec_ID, $Lec_Nombre);
	
	$sql = "SELECT DISTINCTROW Ins_Niv_ID, Ins_Cur_ID, Ins_Div_ID, Niv_Nombre, Cur_Nombre, Div_Nombre FROM Curso
    INNER JOIN Colegio_Inscripcion 
        ON (Curso.Cur_ID = Colegio_Inscripcion.Ins_Cur_ID) AND (Curso.Cur_Niv_ID = Colegio_Inscripcion.Ins_Niv_ID) 
	INNER JOIN Colegio_Nivel 
        ON (Curso.Cur_Niv_ID = Niv_ID)
    INNER JOIN Division 
        ON (Colegio_Inscripcion.Ins_Div_ID = Division.Div_ID)
		WHERE Ins_Lec_ID = $Lec_ID
		ORDER BY  Ins_Niv_ID, Ins_Cur_ID, Ins_Div_ID";
	 $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0){
?>
<script language="javascript">
$(document).ready(function(){
	
	$("a[id^='botEditar']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(9,10);		
		Cou_Lec_ID = $("#Cou_Lec_ID" + i).val();
		Cou_Cur_ID = $("#Cou_Cur_ID" + i).val();
		//alert(vCliID);
		buscarDatos(Cou_Lec_ID, Cou_Cur_ID);
		
		$("#divBuscador").hide();
		$("#mostrarNuevo").fadeIn();
		$("#barraGuardar").show();
	 });//fin evento click//*/	
	 /*$("a[id^='botEntrevista']").click(function(evento){											  
		evento.preventDefault();
		var i = this.id.substr(13,10);
		CCT_ID = $("#CCT_ID" + i).val();
		
		vNombre = $("#Nombre" + i).text();
		
		jConfirm('Est&aacute; seguro que desea eliminar ' + vNombre + '?', 'Confirmar la eliminación', function(r){
    			if (r){//eligió eliminar
					$.post("cargarOpciones.php", { opcion: "eliminar<?php echo $Tabla;?>", CCT_ID: CCT_ID }, function(data){
						jAlert(data, 'Resultado de la eliminación');
						$("#fila" + i).hide();
						//recargarPagina();
					});//fin post					
				}//fin if
			});//fin del confirm///
	
	 });//fin evento click//*/	
	 
});//fin domready
function buscarDatos(Cou_Lec_ID, Cou_Cur_ID){
		$.post("cargarOpciones.php",{opcion: 'buscarDatos<?php echo $Tabla;?>', Cou_Lec_ID: Cou_Lec_ID, Cou_Cur_ID: Cou_Cur_ID}, function(data){
			//alert(data);
			//return;
			if (data!="{}"){
				var obj = $.parseJSON(data);
				//alert(obj.total_legajos);
				$("#Cou_Cur_ID").val(obj.Cou_Cur_ID);
				$("#Cou_Lec_ID").val(obj.Cou_Lec_ID);
				$("#Cou_Total").val(obj.Cou_Total);
				
			}//fin if
		});		
	}//fin function
</script>
<div align="center" class="titulo_noticia"><img src="imagenes/group.png" width="32" height="32" align="absmiddle" /> Cantidad de Alumnos por Curso para el Ciclo Lectivo <?php echo $Lec_Nombre;?></div>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
               <thead>
                <tr class="fila_titulo">
                  
                  <th align="center">Curso/División</th>
                  <th align="center">Alumnos inscriptos</th>
                  <th align="center">Con Baja</th>
                  <th align="center">Total</th>
                 </tr>
                </thead>
                <tbody>
                <?php
				$cantInscriptos = 0;
				$cantInscriptosBaja = 0;
				$cantTotal = 0;
                while ($row=mysqli_fetch_array($result)){
					$i++;
					if ($i%2==0) $clase = "fila";else $clase = "fila2";
					$Inscriptos = obtenerAlumnosCurso($Lec_ID, $row[Ins_Niv_ID], $row[Ins_Cur_ID], $row[Ins_Div_ID], $InscriptosBaja);
					$total = $Inscriptos + $InscriptosBaja;
					$cantInscriptos += $Inscriptos;
					$cantInscriptosBaja += $InscriptosBaja;
					$cantTotal += $total;
				?>
                <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td>
                  <span id="Nombre<?php echo $i;?>"><?php echo "$row[Cur_Nombre] \"$row[Div_Nombre]\"";?></span></td>
                 <td align="center"><?php echo $Inscriptos;?></td>
                 <td align="center"><?php echo $InscriptosBaja;?></td>
                 <td align="center"><?php echo $total;?></td>
                  </tr>
              
              <?php
				}//fin while
			  ?>
              <tr class="<?php echo $clase;?>" id="fila<?php echo $i;?>">                 
                  <td>
                  <span id="Nombre<?php echo $i;?>"><strong>TOTALES</strong></span></td>
                 <td align="center"><strong><?php echo $cantInscriptos;?></strong></td>
                 <td align="center"><strong><?php echo $cantInscriptosBaja;?></strong></td>
                 <td align="center"><strong><?php echo $cantTotal;?></strong></td>
                  </tr>
            </tbody>
            </table>
<?php
	}else{
		echo "No existen datos relacionados con la consulta hecha";
	}//fin if
