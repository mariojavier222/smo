<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("conexion.php");
require_once("listas.php"); 
require_once("funciones_generales.php");
include_once("comprobar_sesion.php"); 

//error_reporting(E_ALL); ini_set('display_errors', 1);

date_default_timezone_set('America/Argentina/San_Juan');

function guardarDivisionCurso() {
    $ID= $_POST['ID'];
    if ($ID == '') $ID = '-1';
    $LecID= $_POST['LecID'];
    $NivID= $_POST['NivID'];
    $CurID= $_POST['CurID'];
    $DivID= $_POST['DivID'];

    $sql = "SELECT * FROM division_curso WHERE 
    DC_Div_ID = '$DivID' AND 
    DC_Cur_ID = '$CurID' AND 
    DC_Lec_ID = '$LecID'";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {//ya existe
        echo "Ya existe este registro!";
        
    } else {

        $sql = "INSERT INTO division_curso (
            DC_Div_ID, 
            DC_Cur_ID, 
            DC_Lec_ID) 
        VALUES (
            '$DivID', 
            '$CurID', 
            '$LecID')" ;
        $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($res == true) echo "Se insertó correctamente el registro!";
        else echo "Error!";
    }
} //fin funcion

function eliminarDivisionCurso() {
    
    $ID = $_POST['ID'];
    //me fijo si hay datos relacionados
    $sql="SELECT * FROM Colegio_Inscripcion 
    INNER JOIN division_curso ON 
    (Colegio_Inscripcion.Ins_Div_ID = division_curso.DC_Div_ID) AND 
    (Colegio_Inscripcion.Ins_Cur_ID = division_curso.DC_Cur_ID) AND 
    (Colegio_Inscripcion.Ins_Lec_ID = division_curso.DC_Lec_ID) AND division_curso.DC_ID='$ID';";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        echo "No se puede eliminar este registro. Hay alumnos inscriptos!";    
    }else {
        $sql = "DELETE FROM division_curso WHERE DC_ID='$ID';";
        $res=consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
        if ($res== true) echo "Se eliminó correctamente el registro!";
        else echo "Error!";
    }

} //fin funcion

function llenarCursoTurnoST(){
    $NivID= $_POST['NivID'];
    $TurID= $_POST['TurID'];
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if ($NivID!=999999) $where.=" AND Cur_Niv_ID = '$NivID' ";
    if ($TurID!=999999) $where.=" AND Cur_Turno = '$TurID' ";   
    $sql = "SELECT * FROM Curso WHERE 1 $where ORDER BY Cur_Curso";
    //echo $sql;
    
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        while ($row = mysqli_fetch_array($result)){
            echo "<option value='$row[Cur_ID]'>$row[Cur_Nombre]</option>";
        }//fin del while
    }else{
        echo "<option value='-1'>NO HAY CURSOS DISPONIBLES</option>";
    }
}

function llenarListaCursoDivision(){
    $LecID= $_POST['LecID2'];
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT * FROM Division
INNER JOIN division_curso 
ON (Division.Div_ID = division_curso.DC_Div_ID)
INNER JOIN Curso 
ON (division_curso.DC_Cur_ID = Curso.Cur_ID)
INNER JOIN Colegio_Nivel 
ON (Curso.Cur_Niv_ID = Colegio_Nivel.Niv_ID) WHERE DC_Lec_ID = '$LecID' ORDER BY Niv_ID,Cur_ID,Div_ID";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result) > 0) {
        ?>

    <link href="css/general.css" rel="stylesheet" type="text/css" />
    <link href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
    <script language="javascript">
        $(document).ready(function(){
            $("a[id^='botBorrar']").click(function(evento){                                           
                evento.preventDefault();
                var i = this.id.substr(9,10);
                vID = $("#ID" + i).val();
                opcion = '';

                jConfirm('¿Está seguro que desea eliminar el registro?', 'Confirmar la eliminación', function(r){
                    if (r){//eligió eliminar
                        $.post("cargarOpcionesDivision.php", {opcion: "eliminarDivisionCurso", ID:vID }, function(data){
                            jAlert(data, 'Resultado de la eliminación');
                            recargarPagina();
                        });//fin post                   
                    }//fin if
                });//fin del confirm//*/
            
            });//fin evento click//*/

            function recargarPagina(){
                $.ajax({
                    cache: false,
                    async: false,           
                    url: "/cargarDivisionCurso.php",
                    success: function (data){
                            $("#principal").html(data);
                            $("#cargando").hide();
                            }
                });//fin ajax
            }//fin function
        });    
    </script>

        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabla">
            <thead>
                <tr class="ui-widget-header">
                    <th align="center">Nombre</th>
                    <th align="center">Nivel</th>
                    <th align="center">Curso</th>
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
                         <input name="DivID" type="hidden" id="DivID<?php echo $i;?>" value="<?php echo $row['Div_ID']; ?>" />
                         <input name="DivNombre<?php echo $i;?>" type="hidden" id="DivNombre<?php echo $i;?>" value="<?php echo $row['Div_Nombre']; ?>" />
                         <input name="CurID<?php echo $i;?>" type="hidden" id="CurID<?php echo $i;?>" value="<?php echo $row['Cur_ID']; ?>" />
                         <input name="NivID<?php echo $i;?>" type="hidden" id="NivID<?php echo $i;?>" value="<?php echo $row['Niv_ID']; ?>" />
                         <input name="LecID<?php echo $i;?>" type="hidden" id="LecID<?php echo $i;?>" value="<?php echo $row['DC_Lec_ID']; ?>" />
                         <input name="ID<?php echo $i;?>" type="hidden" id="ID<?php echo $i;?>" value="<?php echo $row['DC_ID']; ?>" />
                         
                          <?php echo $row['Div_Nombre']; ?> </td>
                        <td align="center"><?php echo $row['Niv_Nombre']; ?></td>
                        <td align="center"><?php echo $row['Cur_Nombre']; ?></td>
                        <td align="center">                                    
                      <!--      <a href="#" id="botEditar<?php echo $i;?>"><img src="imagenes/editar_activo.png" alt="Editar Division" title="Editar Division" width="32" height="32" border="0" /></a> -->
                        <a href="#" id="botBorrar<?php echo $i;?>"><img src="imagenes/borrar_activo.png" alt="Borrar registro" title="Borrar registro" width="32" height="32" border="0" /></a>
                      </td>
                    </tr>

                    <?php
                }//fin while
                ?>
            </tbody>
        </table>

        <?php
    } else echo "No existen datos cargados.";

}


function cargarListaDivisionCurso(){
    $CurID= $_POST['CurID']; 
    $LecID= $_POST['LecID']; 

    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $sql = "SELECT Division.Div_Nombre, division_curso.DC_Div_ID, division_curso.DC_Cur_ID, division_curso.DC_Lec_ID 
    FROM Division INNER JOIN division_curso ON 
    (Division.Div_ID = division_curso.DC_Div_ID) where DC_Cur_ID='$CurID' AND DC_Lec_ID='$LecID';";
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    if (mysqli_num_rows($result)>0){
        while ($row = mysqli_fetch_array($result)){
            echo "<option value='$row[DC_Div_ID]'>$row[Div_Nombre]</option>";
        }//fin del while
    }else{
        echo "<option value='-1'>NO HAY DIVISIONES DISPONIBLES</option>";
    }

}//fin function

function cargarListaCursosColegioTraerNivel($nombre){
    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $sql = "SELECT * FROM Curso INNER JOIN Colegio_Nivel ON (Cur_Niv_ID = Niv_ID)
    AND Niv_ID <> 0 AND  Niv_ID <> 4 AND Niv_ID <> 5 ORDER BY Niv_Nombre, Cur_Curso";//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    echo "<select name='$nombre' id='$nombre' style='position:relative;z-index:1'>";
    echo "<option value='-1'>Seleccione una opci&oacute;n</option>";    
    $Nivel = "";
    while ($row = mysqli_fetch_array($result)){
        if ($Nivel!= $row['Niv_Nombre']){
            if ($Nivel!="") echo "</optgroup>";
            $Nivel = $row['Niv_Nombre'];             
            echo "<optgroup label='$row[Niv_Nombre]'>";
        }
        echo "<option value='".$row[Cur_ID]."'>$row[Cur_Nombre]</option>";
    }//fin del while

    echo "</optgroup></select>";

}//fin function

function llenarListaCursosColegioTraerNivel(){

    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    $sql = "SELECT * FROM Curso INNER JOIN Colegio_Nivel ON (Cur_Niv_ID = Niv_ID)
    AND Niv_ID <> 0 AND  Niv_ID <> 4 AND Niv_ID <> 5 ORDER BY Niv_Nombre, Cur_Curso";//echo $sql;
    $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);
    $Nivel = "";
    echo "<option value='-1'>Seleccione una opci&oacute;n</option>";  
    while ($row = mysqli_fetch_array($result)){
        if ($Nivel!= $row['Niv_Nombre']){
            if ($Nivel!="") echo "</optgroup>";
            $Nivel = $row['Niv_Nombre'];             
            echo "<optgroup label='$row[Niv_Nombre]'>";
        }
        echo "<option value='".$row[Cur_ID]."'>$row[Cur_Nombre]</option>";
    }//fin del while

    echo "</optgroup>";

}//fin function
?>