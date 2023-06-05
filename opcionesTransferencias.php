<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");
session_name("sesion_abierta");
// incia sessiones
session_start();	

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

?>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#tabla_deb td, th {
    height: 25px;
}
</style>
<?php

$opcion = $_POST['opcion'];

switch ($opcion) {

	case "cargarSinIdentificar";
		cargarSinIdentificar();
		break;
	case "cargarVinculadas";
        cargarVinculadas();
        break;
    case "cargarAnuladas";
        cargarAnuladas();
        break;  
    case "cargarImputadas";
        cargarImputadas();
        break;      	
    case "anularPago":
        anularPago();
        break;  
    case "restaurarPago":
        restaurarPago();
        break;
    case "imputarPago":
        imputarPago();
        break;    

	default:
        echo "La opción elegida no es válida";
}//fin switch

function cargarSinIdentificar(){

    echo '<script language="javascript">

    $(document).ready(function(){
    $("a[id^=\'btnAnular\']").click(function(evento){
        evento.preventDefault();            
        id = this.id.substr(9,10);
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "anularPago", id: id},
            url: "opcionesTransferencias.php",
            success: function(data){ 
                if (data){
                    //alert(data);
                    $("#fila"+id).hide();
                    //mostrarAlerta(data, "Resultado de la operación");                        
                }
            }
        });//fin ajax////*/
    });

    });
    </script>'; 

    echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="tabla_deb">
        <tr>
            <th class="fila_titulo">Nº</th>            
            <th class="fila_titulo">Fecha Movimiento</th>
            <th class="fila_titulo">Importe</th> 
            <th class="fila_titulo">Referencia</th>
            <th class="fila_titulo">Concepto</th>            
            <th class="fila_titulo">Se cargó</th>
            <th class="fila_titulo">Acciones</th>
        </tr>';
    $sql = "SELECT * FROM transferencias WHERE sin_identificar = 1 AND anulado=0";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $i=0;
        while ($row = mysqli_fetch_array($result)){
            $i++;
            if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
            echo '<tr class="'.$clase.'" id="fila'.$row['id'].'">';
            echo '<td>'.$row['id'].'</td>
                <td>'.cfecha($row['fecha_movimiento']).'</td>
                <td>$'.number_format($row['importe'],2,',','.').'</td>            
                <td>'.$row['referencia'].'</td>
                <td>'.$row['concepto'].'</td>                
                <td>'.cfecha($row['fecha_creacion']).'</td>
                <td><a href="#" title="Vincular a una persona." id=btnVincular'.$row['id'].'><img width="24px" src="imagenes/bullet_blue.png"></a> <a href="#" title="Eliminar esta referencia" id=btnAnular'.$row['id'].'><img width="24px" src="imagenes/bullet_delete.png"></a></td>
            </tr>';          
        }
    }//fin if    
    
    echo '</table>';

}//fin funcion

function cargarVinculadas(){

    echo '<script language="javascript">

    $(document).ready(function(){
    $("a[id^=\'btnImputar\']").click(function(evento){
        evento.preventDefault();            
        id = this.id.substr(10,10);
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "imputarPago", id: id},
            url: "opcionesTransferencias.php",
            success: function(data){ 
                if (data){
                    //alert(data);
                    $("#divdetalle"+id).html(data);
                    //mostrarAlerta(data, "Resultado de la operación");                        
                }
            }
        });//fin ajax////*/
    });

    });
    </script>'; 

    echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla display" id="tabla_deb">
        <tr>
            <th class="fila_titulo">Nº</th>            
            <th class="fila_titulo">Fecha Movimiento</th>
            <th class="fila_titulo">Importe</th> 
            <th class="fila_titulo">Referencia</th>
            <th class="fila_titulo">Concepto</th>
            <th class="fila_titulo">DNI/CUIL originante</th>
            <th class="fila_titulo">Persona</th>
            <th class="fila_titulo">Grupo Familiar</th>
            <th class="fila_titulo">Se cargó</th> 
            <th class="fila_titulo">Acciones</th>           
        </tr>';
    $sql = "SELECT * FROM transferencias WHERE vinculado = 1 AND anulado=0";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $i=0;
        while ($row = mysqli_fetch_array($result)){
            $i++;
            if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
            echo '<tr class="'.$clase.'" id="fila'.$row['id'].'">';
            echo '<td>'.$row['id'].'</td>
                <td>'.cfecha($row['fecha_movimiento']).'</td>
                <td>$'.number_format($row['importe'],2,',','.').'</td>            
                <td>'.$row['referencia'].'</td>
                <td>'.$row['concepto'].'</td>
                <td>'.$row['dni'].'<br>'.$row['cuil'].'</td>
                <td>'.$row['persona'].'</td>
                <td>'.$row['grupo_familiar'].'</td>
                <td>'.cfecha($row['fecha_creacion']).'</td>
                <td><a href="#" title="Imputar este pago" id=btnImputar'.$row['id'].'><img width="24px" src="imagenes/pagar.png"></a></td>
            </tr>';
            echo '<tr class="'.$clase.'" id="filadetalle'.$row['id'].'">
            <td colspan="10">
            <div id="divdetalle'.$row['id'].'"></div>
            </td>
            </tr>';          
        }
    }//fin if    
    
    echo '</table>';

}//fin funcion

function cargarAnuladas(){

    echo '<script language="javascript">

    $(document).ready(function(){
    $("a[id^=\'btnRestaurar\']").click(function(evento){
        evento.preventDefault();            
        id = this.id.substr(12,10);
        $.ajax({
            type: "POST",
            cache: false,
            async: false,
            error: function (XMLHttpRequest, textStatus){
                alert(textStatus);},
            data: {opcion: "restaurarPago", id: id},
            url: "opcionesTransferencias.php",
            success: function(data){ 
                if (data){
                    //alert(data);
                    $("#fila"+id).hide();
                    //mostrarAlerta(data, "Resultado de la operación");                        
                }
            }
        });//fin ajax////*/
    });

    });
    </script>'; 

    echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla" id="tabla_deb">
        <tr>
            <th class="fila_titulo">Nº</th>            
            <th class="fila_titulo">Fecha Movimiento</th>
            <th class="fila_titulo">Importe</th> 
            <th class="fila_titulo">Referencia</th>
            <th class="fila_titulo">Concepto</th>            
            <th class="fila_titulo">Se cargó</th>
            <th class="fila_titulo">Acciones</th>
        </tr>';
    $sql = "SELECT * FROM transferencias WHERE anulado=1";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $i=0;
        while ($row = mysqli_fetch_array($result)){
            $i++;
            if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
            echo '<tr class="'.$clase.'" id="fila'.$row['id'].'">';
            echo '<td>'.$row['id'].'</td>
                <td>'.cfecha($row['fecha_movimiento']).'</td>
                <td>$'.number_format($row['importe'],2,',','.').'</td>            
                <td>'.$row['referencia'].'</td>
                <td>'.$row['concepto'].'</td>                
                <td>'.cfecha($row['fecha_creacion']).'</td>
                <td><a href="#" title="Restaurar esta referencia" id=btnRestaurar'.$row['id'].'><img width="24px" src="imagenes/go-jump.png"></a></td>
            </tr>';          
        }
    }//fin if    
    
    echo '</table>';

}//fin funcion

function cargarImputadas(){

    echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla display" id="tabla_deb">
        <tr>
            <th class="fila_titulo">Nº</th>            
            <th class="fila_titulo">Fecha Movimiento</th>
            <th class="fila_titulo">Importe</th> 
            <th class="fila_titulo">Referencia</th>
            <th class="fila_titulo">Concepto</th>
            <th class="fila_titulo">DNI/CUIL originante</th>
            <th class="fila_titulo">Persona</th>
            <th class="fila_titulo">Grupo Familiar</th>
            <th class="fila_titulo">Se cargó</th>            
        </tr>';
    $sql = "SELECT * FROM transferencias WHERE imputado = 1 AND anulado=0";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $i=0;
        while ($row = mysqli_fetch_array($result)){
            $i++;
            if (($i%2)==0) $clase = "fila"; else $clase = "fila2";
            echo '<tr class="'.$clase.'" id="fila'.$row['id'].'">';
            echo '<td>'.$row['id'].'</td>
                <td>'.cfecha($row['fecha_movimiento']).'</td>
                <td>$'.number_format($row['importe'],2,',','.').'</td>            
                <td>'.$row['referencia'].'</td>
                <td>'.$row['concepto'].'</td>
                <td>'.$row['dni'].'/'.$row['cuil'].'</td>
                <td>'.$row['persona'].'</td>
                <td>'.$row['grupo_familiar'].'</td>
                <td>'.cfecha($row['fecha_creacion']).'</td>
            </tr>';          
        }
    }//fin if    
    
    echo '</table>';

}//fin funcion

function anularPago(){

    $id = $_POST['id']; 
    $sql = "UPDATE transferencias SET anulado = 1 WHERE id = $id";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    echo "Se anuló correctamente esta referencia.";

}//fin funcion

function restaurarPago(){

    $id = $_POST['id']; 
    $sql = "UPDATE transferencias SET anulado = 0 WHERE id = $id";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    echo "Se restauró correctamente esta referencia.";

}//fin funcion

function imputarPago(){

    $id = $_POST['id'];

    $sql = "SELECT * FROM transferencias WHERE id = $id";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        $i=0;
        $row = mysqli_fetch_array($result);        
        $dni_originante = $row['dni'];
        $sql = "SELECT * FROM Persona WHERE Per_DNI = '$dni_originante'";
        $result_dni = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if (mysqli_num_rows($result_dni)>0){
            $row_dni = mysqli_fetch_array($result_dni);
            $Persona = $row_dni['Per_Apellido'].', '.$row_dni['Per_Nombre'];
            $Per_ID = $row_dni['Per_ID'];
            $Per_Sexo = $row_dni['Per_Sexo'];
            if ($Per_Sexo=='M') $tutor = 'Papá';else $tutor = 'Mamá';
            echo "<h3>$tutor: <strong>$Persona</strong></h3>";
            $sql = "SELECT * FROM Familia
            INNER JOIN FamiliaTipo 
                ON (Familia.Fam_FTi_ID = FamiliaTipo.FTi_ID)
            INNER JOIN Persona 
                ON (Familia.Fam_Per_ID = Persona.Per_ID) WHERE Fam_Vin_Per_ID = $Per_ID AND Fam_FTi_ID = 1 ORDER BY FTi_ID, Per_Apellido, Per_Nombre;";
            $result_hijos = consulta_mysql_2022($sql,basename(__FILE__),__LINE__); 
            $mostrar='';           
            while ($row_hijos = mysqli_fetch_array($result_hijos)){
                $dni_hijo = $row_hijos['Per_DNI'];
                $per_hijo = $row_hijos['Per_ID'];
                if ($row_hijos['Per_Sexo']=='M'){
                    $texto_relacion = 'Hijo';//$row_hijos['FTi_M'];
                }else{
                    $texto_relacion = 'Hija';//$row_hijos['FTi_F'];
                }
                $mostrar .= "$texto_relacion: <strong>$row_hijos[Per_Apellido], $row_hijos[Per_Nombre]</strong><br />D.N.I.: $dni_hijo<br>";

                //buscamos las cuotas pendientes de pago
                $sqlOpcional = " AND CTi_Recibo = 1 AND Cuo_Pagado = 0 AND Cuo_Anulado = 0 AND Cuo_Cancelado = 0";
                $sql = "SELECT * FROM CuotaPersona
                INNER JOIN Lectivo 
                    ON (Cuo_Lec_ID = Lec_ID)
                INNER JOIN Colegio_Nivel 
                    ON (Cuo_Niv_ID = Niv_ID)
                INNER JOIN CuotaTipo 
                    ON (Cuo_CTi_ID = CTi_ID)
                INNER JOIN CuotaAlternativa 
                    ON (Cuo_Alt_ID = Alt_ID)
                INNER JOIN CuotaBeneficio 
                    ON (Cuo_Ben_ID = Ben_ID)
                INNER JOIN Usuario 
                    ON (Cuo_Usu_ID = Usu_ID)
            WHERE (Cuo_Per_ID = $per_hijo $sqlOpcional) ORDER BY Cuo_Pagado DESC, Cuo_Anulado DESC, Cuo_CTi_ID, Cuo_1er_Vencimiento, Cuo_Alt_ID  ASC, Cuo_Anio, Cuo_Mes;";
                $result_cuotas = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
                while ($row_cuotas = mysqli_fetch_array($result_cuotas)){
                    $datosCuota = $row_cuotas['Cuo_Lec_ID'].";".$row_cuotas['Cuo_Per_ID'].";".$row_cuotas['Cuo_Niv_ID'].";".$row_cuotas['Cuo_CTi_ID'].";".$row_cuotas['Cuo_Alt_ID'].";".$row_cuotas['Cuo_Numero'];
                    $importe = $row_cuotas['Cuo_Importe'];        
                    $fechaCuota = cfecha($row_cuotas['Cuo_1er_Vencimiento']);            
                    recalcularImporteCuota($datosCuota, $importe);
                    if ($row_cuotas['Cuo_Pagado']==1) $importe = buscarPagosTotales($datosCuota);
                    $recargo2 = obtenerRecargoCuota($row_cuotas['Cuo_Per_ID'], $datosCuota);
                    $mostrar .= '<input type="checkbox" id="Nuevo'.$i.'" name="Nuevo'.$i.'" value="'.$datosCuota.'" class="activo">';
                    $mostrar .= $row_cuotas['CTi_Nombre'].' '.buscarMes($row_cuotas['Cuo_Mes']).'/'.$row_cuotas['Cuo_Anio'].' Vence: '.$fechaCuota;
                    if($row_cuotas['Cuo_Ben_ID']!=1){
                        $mostrar .= $row_cuotas['Ben_Nombre'];
                    }
                    $total = $importe + $recargo2;
                    $mostrar .= ". Importe $".$importe.'. Recargo: '.$recargo2.'. Total: '.$total;

                    $mostrar .= '<br>';

                }//fin while

                $mostrar .= '---------------------------------------------------<br>';
            
            }//fin while
            echo $mostrar;
        }
    }//fin if
    
    

}//fin funcion
?>