<?php

include_once("conexion.php");
require_once("funciones_generales.php");

$existe=0;
$dni_alumno=0;

$lec=21;//2022
$anio=2022;

$validar = true;

if (!isset($_GET['ID']) || (!isset($lec))){
  $validar=false;
}

if (($_GET['ID']==0) || ($lec==0)){
  $validar=false;
}

if (!$validar){

  echo"<p>Error intentanto acceder a este contenido<br>
      Usted no tiene permiso para acceder a esta sección o faltan datos. Error nro: ".$_GET['ID']."</p>";

}else{

    $PerID=$_GET['ID'];
    $LecID=$lec;

    $sql = "SET NAMES UTF8;";
    consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

    //Buscamos si el alumno ya se encuentra inscripto al ciclo lectivo
    $sql = "SELECT * FROM Colegio_Inscripcion
    INNER JOIN Legajo 
        ON (Colegio_Inscripcion.Ins_Leg_ID = Legajo.Leg_ID)
    INNER JOIN Colegio_Nivel 
        ON (Colegio_Inscripcion.Ins_Niv_ID = Colegio_Nivel.Niv_ID)
    INNER JOIN Curso 
        ON (Colegio_Inscripcion.Ins_Cur_ID = Curso.Cur_ID)
    INNER JOIN Persona 
        ON (Legajo.Leg_Per_ID = Persona.Per_ID) AND (Curso.Cur_Niv_ID = Colegio_Nivel.Niv_ID)
         WHERE Leg_Per_ID = '$PerID' AND Ins_Lec_ID = $LecID";
  $result = consulta_mysql_2022($sql,basename(__FILE__),__LINE__);

  if (mysqli_num_rows($result) > 0) {//existe seguimos
    $existe=1;
    $row = mysqli_fetch_array($result); 
    
    $nivel = $row['Niv_Nombre'];
    $curso = utf8_encode($row['Cur_Nombre']);

    if ($row[Per_Sexo]=='F') {
        $sexo_letra='a';
        $sexo_del='de la';
        $sexo_el='la';
        $sexo_al='a la';
    }else{
        $sexo_letra='o';
        $sexo_del='del';
        $sexo_el='el';
        $sexo_al='al';
    }

    //datos del tutor
    obtenerTutores($PerID, $arrayTutores, $cant);
    //datos del padre
    $padre_dni=$arrayTutores[1]['Per_DNI'];                             
    $padre_nombre=$arrayTutores[1]['Per_Nombre']." ".$arrayTutores[1]['Per_Apellido'];
    $padre_domicilio=$arrayTutores[1]['Domicilio'];
    $padre_domicilio=substr($padre_domicilio, 10); 
    if (strlen($padre_domicilio)==0) $padre_domicilio=".....................................................................................................";
    $padre_domicilio=utf8_encode($padre_domicilio);
    $padre_tipo=$arrayTutores[1]['FTi_Tipo'];
    $padre_sexo=$arrayTutores[1]['Per_Sexo'];

    if (empty($padre_dni)) $padre_dni="..........................................";
    if ($padre_nombre==' ') $padre_nombre=".................................................................................";

    if ($padre_sexo=='F') {
        $padre_letra="a";
        $padre_sexo_del='la Sra.';
    }
    if ($padre_sexo=='M') {
        $padre_letra="o";
        $padre_sexo_del='el Sr.';
    }
    if ($padre_sexo=='') {
       $padre_letra="o/a";
       $padre_sexo_del='el Sr./Sra.';
    } 
    
    //datos del alumno
    $nombre_alumno = $row[Per_Nombre].' '.$row[Per_Apellido];
    $dni_alumno = $row[Per_DNI];

    $fecha=date("d/m/Y");
   
  }//de la inscripcion al lectivo

  //desde acá se arma el pdf
  require_once('tcpdf/config/tcpdf_config.php');
  require_once('tcpdf/tcpdf.php');

  // extend TCPF with custom functions
  class MYPDF extends TCPDF {

   //Page header
    public function Header() {
       // Set font
        $this->SetFont('helvetica', '', 10);
        $enc='<table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td align="center" valign="middle" width="100%"><strong>'.COLEGIO_NOMBRE.'</strong></td>
        </tr>
        <tr>  
            <td align="center" valign="middle" width="100%"><br/><strong>CONTRATO EDUCATIVO 2022</strong> </td>
        </tr>
        </table>';

        // Title
        $this->writeHTML($enc, true, false, true, false, 'C'); 
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Hoja '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
  }//del extends

  // create new PDF document
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('NAPTA');
  $pdf->SetTitle('Contrato de servicios educativos');
  $pdf->SetSubject('Contrato');
  $pdf->SetKeywords('contrato');

  // set default header data
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

  // set header and footer fonts
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

  $pdf->SetMargins(15, 20, 7);
  // set margins
 // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

  // set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

  // set image scale factor
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

  // Add a page
  // This method has several options, check the source code documentation for more information.
  $pdf->AddPage();

  //controlo que exista el id de la inscripción
  if ($existe>0){

    $firma_rp = "logos/firma_rp.jpg";

    $pdf->SetFont('helvetica', '', 9);
    $pdf->writeHTML($html, true, false, true, false, 'C');          

    $html='<span style="text-align:justify;">';
    
    $html.="Entre el “".COLEGIO_NOMBRE."”, representado en este acto por su Representante Legal, <strong>".COLEGIO_REPRESENTANTE_LEGAL."</strong>, con domicilio en Calle Mendoza 1640 Norte – Chimbas – San Juan, por una parte, en adelante “el Colegio” y ".$padre_sexo_del." <strong>".$padre_nombre."</strong>, D.N.I. Nº ".$padre_dni.", domiciliad".$padre_letra." en ".$padre_domicilio.", por la otra parte, en adelante “el Padre”, se conviene en celebrar el siguiente contrato de Prestación de Servicios Educacionales, de conformidad a las siguientes cláusulas:<br/><br/>

    <strong>PRIMERA:</strong> El Padre/Madre/Tutor solicita la matriculación, para el Ciclo Lectivo ".$anio." de su hij".$sexo_letra." <strong>".$nombre_alumno."</strong>, en calidad de alumn".$sexo_letra.", en ".$curso." de ".$nivel." y el Colegio l".$sexo_letra." acepta.<br/><br/>

    <strong>SEGUNDA:</strong> El Colegio, como entidad formativa, se compromete a:<br/>
    Dispensar la atención necesaria para que ".$sexo_el." alumn".$sexo_letra." desarrolle el proceso educativo dentro de un adecuado y exigente nivel.<br/>
    Impartir el proceso de enseñanza-aprendizaje de conformidad con la Ley de Educación Nacional Nº 26.206, la Ley  de la Provincia de San Juan N° 1327-H, el Proyecto Educativo Institucional (PEI) y el Proyecto Curricular Institucional (PCI) del Colegio.<br/>
     Difundir el contenido del Proyecto Educativo y del Acuerdo de Convivencia del Colegio y velar por su cumplimiento.<br/>
     Proporcionar ".$sexo_al." alumn".$sexo_letra.", de acuerdo a las normas internas, la infraestructura que se requiera para el desarrollo del Proyecto Curricular, sea en el aula, como en la biblioteca, laboratorio y otras dependencias.<br/>
    Promover actividades complementarias que estimulen el desarrollo espiritual, intelectual y físico ".$sexo_del." alumn".$sexo_letra.".<br/><br/>

    <strong>TERCERA:</strong> Los Padres quienes eligen libremente este Colegio para su hij".$sexo_letra." dentro de las opciones que ofrece el medio, se comprometen a:<br/>
    Presentar toda la documentación solicitada por el colegio al momento de la inscripción. Caso contrario, el Colegio se reserva el derecho de Admisión.<br/> 
    Asumir su rol de primer y principal educador de su hij".$sexo_letra.".<br/>
    Procurar que exista la mayor coherencia entre la vida familiar y los valores éticos  y religiosos de la Iglesia Católica, Apostólica y Romana que sustentan el ámbito escolar.<br/>
    Respetar el Proyecto Educativo del Colegio, el que declara conocer.<br/>
    Coadyuvar a las tareas educativas y formativas que, en beneficio ".$sexo_del." alumn".$sexo_letra.", conciba y desarrolle el Colegio y llevar a cabo las acciones que con este objetivo recomiende el establecimiento (estudios solicitados por Gabinete Interdisciplinario).<br/>
    Acatar el Acuerdo Escolar de Convivencia y las modificaciones que la misma sufra durante el ciclo lectivo, debiendo ser debidamente notificado de las mismas.<br/>
    Asistir a las reuniones para las que fuese convocado con motivo del proceso de enseñanza-aprendizaje de su hij".$sexo_letra.".<br/>
    Participar en las actividades de Catequesis Familiar y Misas Mensuales (Nivel Inicial, Primario y Secundario). Si los padres no asisten regularmente a las reuniones de catequesis familiar en 4° y 5° grado, sus hijos no recibirán la comunión. Tendrán la posibilidad en 6° grado habiendo cumplido los padres el 80% de asistencia en las reuniones de catequesis familiar en el nuevo curso lectivo. No cumplida estas exigencias el Colegio se reserva el derecho de admisión para el ingreso al nivel secundario.<br/>
    Garantizar por parte de su hij".$sexo_letra.":<br/>
    i.1. El cumplimiento de lo establecido en el Proyecto Educativo del Colegio y en el Acuerdo Escolar de Convivencia publicado en el cuaderno de comunicaciones.<br/>
    i.2. La asistencia puntual y regular a clases y actividades planificadas por el Colegio.<br/>
    i.3. Un comportamiento y presentación personal de acuerdo a las exigencias del Colegio, dentro y fuera de la Institución.<br/>
    i.4. La coherencia con el estilo de formación que recibe del Colegio en las diversas relaciones con la comunidad.<br/>
    i.5. La participación en actividades litúrgicas y de espiritualidad (jornadas, retiros, campamentos, misas mensuales etc.) y otras actividades organizadas por el Colegio.<br/>
    i.6. La abstención de llevar al colegio celulares, netbook, notebook o cualquier otro elemento no requerido para el proceso de enseñanza-aprendizaje, deslindando el Colegio toda responsabilidad por pérdida, sustracción y/o deterioro total o parcial de los mismos.<br/>
    i.7. El abstenerse de actividades de “presentación de camperas, remeras, etc.” en el interior del colegio o en cualquier sitio bajo jurisdicción del mismo.<br/>
    j) Respetar puntualmente los horarios de entrada y salida del Colegio. En caso   contrario, deberán hacerse cargo de los mayores costos que origine el cuidado de su hij".$sexo_letra." fuera de los horarios de funcionamiento del Colegio, abonando en Administración la suma de PESOS ochenta ($80) por día y por hora o fracción mayor a quince (15) minutos de demora.<br/>
    j) Colaborar con eventos, bonos contribución u otros beneficios que disponga el Colegio con el fin de contribuir a arreglos y/o construcciones para procurar equipamiento y mayor confort a las tareas educativas.<br/>
    k) Acatar las disposiciones del Colegio en cuanto a la distribución horaria de la carga curricular y extracurricular, sea en turno o contra turno, como así también los lugares que se destinan a las actividades educativas, aunque impliquen modificaciones de las condiciones iniciales del ciclo lectivo.<br/>
    l) Responder el requerimiento de DAI o auxiliar terapeuta que se solicite a los alumnos integrados, cumpliendo con la normativa vigente.<br/>
    m) Responder patrimonialmente por los daños producidos por su hij".$sexo_letra.".<br/>
    n) Esperar, un tiempo prudencial, las resoluciones de las autoridades a sus peticiones, conforme los procedimientos escolares establecidos y abstenerse de darles estado público antes de agotar la instancia interna.<br/><br/>

    <strong>CUARTA:</strong> La matriculación confiere ".$sexo_al." alumn".$sexo_letra." todos los derechos que le otorga la normativa vigente y, en particular:<br/>
    Participar en todas las actividades académicas, curriculares y extracurriculares, propias de su nivel, y de las demás que el Colegio promueva y lleve a cabo.
    Utilizar la infraestructura del Colegio, según las normas internas, para el normal desarrollo de su formación personal y del régimen curricular.<br/><br/>
      
    <strong>QUINTA:</strong> El Padre se compromete a cumplir con los pagos de matrícula, escolaridad mensual y materiales a utilizar en las actividades escolares,  en la forma y plazos fijados en este Contrato. En particular, se obliga a pagar puntualmente las diez cuotas mensuales (de marzo a diciembre) del ciclo lectivo ".$anio.", las que deberán ser abonadas en el domicilio del Colegio o a través de la plataforma virtual de cobro, habilitada por el colegio, entre el 1 y 10 de cada mes, por mes adelantado. Vencido este término se producirá la mora automática y se aplicará un recargo de $100.- por cuota mensual atrasada, además si el pago de deuda se realiza con tarjeta de crédito o débito, tiene un recargo de financiación por parte de la tarjeta. Si la mora supera los treinta (30) días, el Colegio podrá iniciar las acciones extrajudiciales y judiciales pertinentes, tendientes al cobro de la suma adeudada, en cuyo caso se aplicará la tasa de interés correspondiente. En tal caso, se remitirá las actuaciones al Estudio Jurídico asesor, ante quien se deberán realizar los pagos pertinentes. Se deja expresamente establecido que el Colegio podrá incrementar el monto de la cuota y o recargo de la misma, durante la vigencia del presente si se producen aumentos en los salarios, insumos, equipamientos o cualquier otro rubro inherente al funcionamiento del mismo que así lo justifiquen.<br/><br/>

    <strong>SEXTA:</strong> El Colegio se reserva expresamente el derecho de admisión ".$sexo_del." alumn".$sexo_letra." con respecto a su matriculación en el ciclo lectivo siguiente. Será especialmente atendida, a los efectos del ejercicio de este derecho, la actitud ".$sexo_del." alumn".$sexo_letra." y de sus padres con relación al Colegio y a la comunidad educativa. Este derecho podrá ser ejercido aun cuando ".$sexo_el." alumn".$sexo_letra." haya presentado la documentación requerida oportunamente y/o haya pagado total o parcialmente el importe fijado por matrícula. <br/><br/>

    <strong>SÉPTIMA:</strong> La no inscripción ".$sexo_del." alumn".$sexo_letra." para el año siguiente en el plazo que el Colegio determine y comunique oportunamente, implicará la libre disponibilidad de  la vacante por parte del mismo.<br/><br/>

    <strong>OCTAVA:</strong> El presente contrato mantendrá su vigencia durante el Ciclo Lectivo ".$anio.", siendo necesaria la suscripción de un nuevo contrato para ciclos lectivos posteriores, incluye la presentación completa de requisitos que se exigen para el Legajo Personal ".$sexo_del." alumn".$sexo_letra.".<br/><br/>

    <strong>NOVENA:</strong> Las partes constituyen domicilio en los consignados en el encabezamiento del presente.<br/><br/>";
    
    $html.="Se firman dos ejemplares de un mismo tenor en la Ciudad de San Juan, Provincia del mismo nombre, en fecha ".$fecha.".-<br/><br/><br/>";

    $html.='</span>';

    $html.='<table width="100%" border="0" align="center">
      <tr>
        <td width="45%" align="center"></td>
        <td width="10%">&nbsp;</td>
        <td width="45%" align="center"><img src="'.$firma_rp.'" width="80" height="102"></td>
       </tr>
      <tr>
        <td width="45%" align="center">_____________________________________________<br>Padre/Madre/Tutor</td>
        <td width="10%">&nbsp;</td>
        <td width="45%" align="center">_____________________________________________<br>Representante Legal</td>
      </tr>
      <tr>
        <td width="45%" align="left">Aclaraci&oacute;n: ______________________________</td>
        <td width="10%">&nbsp;</td>
        <td width="45%">&nbsp;</td>
      </tr>
      <tr>
        <td width="45%"align="left">DNI: ______________________</td>
        <td width="10%">&nbsp;</td>
        <td width="45%">&nbsp;</td>
      </tr>
    </table><br/><br/><br/><br/>';

    $html.='_______________________________________________<br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma y Aclaración  Docente Actuante '; 

    
    
    $pdf->SetFont('helvetica', '', 9);
    $pdf->writeHTML($html, true, 0, true, true);

  }else {//el id de inscripción viene erroneo
    $dni_alumno=0;
    
    $html='<span style="text-align:justify;">';
    $html='Error. Alumno no inscripto en el ciclo Lectivo '.$anio.'. NO se encontraron datos de contrato!<br/><br/>';
    $html.='</span>';
      
    $pdf->SetFont('helvetica', '', 9);
    $pdf->writeHTML($html, true, 0, true, true);
  }

  //Close and output PDF document
  $pdf->Output('contrato_'.$anio.'_'.$dni_alumno.'.pdf', 'I');

}

?>