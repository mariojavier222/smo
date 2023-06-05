<?php
include_once("comprobar_sesion.php");
?>
 <script language="javascript">
    $(function(){
                
		$("a[id^='btnMarcar']").click(function(evento){
            evento.preventDefault();            
            id = this.id.substr(9,10);
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'marcarPago', id: id},
                url: 'cargarOpcionesPlataforma.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
                        $("#fila"+id).hide();
                        mostrarAlerta(data, "Resultado de guardar los datos");						
                    }
                }
            });//fin ajax////*/
        });
        $("a[id^='btnAnular']").click(function(evento){
            evento.preventDefault();            
            id = this.id.substr(9,10);
            $.ajax({
                type: "POST",
                cache: false,
                async: false,
                error: function (XMLHttpRequest, textStatus){
                    alert(textStatus);},
                data: {opcion: 'anularPago', id: id},
                url: 'cargarOpcionesPlataforma.php',
                success: function(data){ 
                    if (data){
                        //alert(data);
                        $("#fila"+id).hide();
                        mostrarAlerta(data, "Resultado de la operación");                        
                    }
                }
            });//fin ajax////*/
        });	
	});	
	</script>
<?php
require_once("conexion.php");
require_once("globalesConstantes.php");
require_once("funciones_generales.php");
$sql = "SET NAMES UTF8;";
consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
$where = "ORDER BY id DESC";
if (isset($_POST['tipo'])){
    if ($_POST['tipo']=='faltantes'){
        $where = "WHERE anulado=0 AND marcado=0 ORDER BY id";
    }
    if ($_POST['tipo']=='todos'){
        $where = "ORDER BY id DESC";
    }
}
$sql = "SELECT * FROM informar_pagos $where;";
$result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
//echo $sql;
if (mysqli_num_rows($result)>0){
    echo '<h2>Listado de Pagos informados</h2>';
    echo '<table width="100%" border="0" id="listadoTabla" class="display">';
    echo '<tr>';
    echo '<th align="center" class="fila_titulo">Fecha pago</th>';
    echo '<th align="center" class="fila_titulo">DNI</th>';
    echo '<th align="center" class="fila_titulo">Alumno</th>';
    echo '<th align="center" class="fila_titulo">Forma de Pago</th>';
    echo '<th align="center" class="fila_titulo">Detalle de cuotas</th>';
    echo '<th width="70px" align="center" class="fila_titulo">Importe total</th>';
    echo '<th align="center" class="fila_titulo">Comprobante</th>';
    echo '<th width="70px" align="center" class="fila_titulo">Acciones</th>';
    echo '</tr>';
    $i=0;
    while ($row = mysqli_fetch_array($result)){
        $i++;
        $dominio = 'https://naptacolegios.com.ar/';
        if ($row['id']>17) $dominio = 'https://naptacolegios.ar/';
        if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
        echo '<tr class="'.$clase.'" id="fila'.$row['id'].'">';
        echo '<td align="center">'.cfecha($row['fecha_pago']).'</td>';
        echo '<td align="center">'.$row['dni_alumno'].'</td>';
        echo '<td>'.$row['alumno'].'</td>';
        echo '<td>'.$row['medio_pago'].'</td>';
        echo '<td>'.$row['detalle'].'</td>';
        echo '<td align="right">$ '.$row['importe_total'].'</td>';
        echo '<td align="center"><a href="'.$dominio.COLEGIO_SIGLAS.'_app/'.$row['adjunto'].'" target="_blank">Descargar</a></td>';
        if ($_POST['tipo']=='todos'){
            $estado = ($row['marcado']==1)?'marcado':'';
            if (empty($estado)) $estado = ($row['anulado']==1)?'anulado':'';
            if ($row['marcado']==0 && $row['anulado']==0){
                echo '<td><a href="#" title="Marcar este pago como cargado en la plataforma. Al recargar la página desaparecerá." id=btnMarcar'.$row['id'].'><img width="24px" src="imagenes/ins_definitiva.png"></a> <a href="#" title="Anular este pago informado" id=btnAnular'.$row['id'].'><img width="24px" src="imagenes/ins_provisoria.png"></a></td>';
            }else{
                echo "<td align='center'>$estado</td>";    
            }
            
        }
        if ($_POST['tipo']=='faltantes'){
            echo '<td><a href="#" title="Marcar este pago como cargado en la plataforma. Al recargar la página desaparecerá." id=btnMarcar'.$row['id'].'><img width="24px" src="imagenes/ins_definitiva.png"></a> <a href="#" title="Anular este pago informado" id=btnAnular'.$row['id'].'><img width="24px" src="imagenes/ins_provisoria.png"></a></td>';
        }
        echo '</tr>';
    }//fin while
    echo '</table>';
}//fin if
?>    
