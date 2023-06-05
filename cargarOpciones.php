<?php
header("Cache-Control: no-cache, must-revalidate");
//error_reporting(E_ALL); ini_set('display_errors', 1);

include_once("comprobar_sesion.php");
require_once("conexion.php");
require_once("listas.php");
require_once("funciones_generales.php");
require_once("cargarFunciones.php");	

$opcion = $_POST['opcion'];
$Nombre = $_POST['Nombre'];
switch ($opcion) {
	//Diego
	case "guardarPagosVarios";
		guardarPagosVarios();
		break;
		//fin diego
		//NAHUEL 08-04-2013	
		
	case "VerDetallesFactura";
        VerDetallesFactura();
		 break;	
		//FIN NAHUEL 08-04-2013	
	   //INICIO NAHUEL 09-04-2013
	   	case "Anular_Factura";
        Anular_Factura();
		 break;
//FIN NAHUEL 09-04-2013	
//NAHUEL 16-04-2013	
	case "editarConfiguracionCuotas";
	editarConfiguracionCuotas();
	break;
	case "actualizarConfiguracionCuotas";
	actualizarConfiguracionCuotas();
	break;
	
	
	//FIN NAHUEL 16-04-2013
//NAHUEL 18-04-2013	
	case "mostrarVistaPrevia";
		mostrarVistaPrevia();
		break;
	case "mostrarVistaPreviaPlanPagos";
		mostrarVistaPreviaPlanPagos();
		break;	
	case "GuardarVistaPrevia";
		GuardarVistaPrevia();
		break;
	case "GuardarPlanPago";
		GuardarPlanPago();
		break;
	
	//FIN NAHUEL 18-04-2013	
	
	//22-0-2013 NAHUEL222
	
	case "guardarTraspasoDinero";
	guardarTraspasoDinero();
	break;
	
	case "IngresoDineroEfectivo";
	IngresoDineroEfectivo();
	break;

	//22-02-2013 FIN NAHUEL222
	//23-02-2013 NAHUEL
	case "mostrarDetalleAuditada";
	mostrarDetalleAuditada();
	break;
	//23-02-2013 FUN NAHUEL
	
	
	//24-02-2013 NAHUEL
	case "guardarCuotaPorcentaje";
	guardarCuotaPorcentaje();
	break;
	
	
    //guardarCuotaPorcentaje
	//24-02-2013 FIN NAHUEL
	
	//24-02-2013 SEGUNDA PARTE NAHUEL MODIFICACION
	
	//guardarCuotaPorcentaje
	
	//24-02-2013 SEGUNDA PARTE NAHUEL
	
	//25-02-2013
	case "agregarBeneficioCuota";
	agregarBeneficioCuota();
	break;
	
	
	case "vaciarColaPagoUsuario22";
	vaciarColaPagoUsuario();
	break;
	//25-02-2013 FIN NAHUEL
	
	//26-02-2013 FIN NAHUEL
	case "mostrarDatosPersonales1";
	mostrarDatosPersonales1();
	break;
	
	case "mostrarDatosAdiccionales";
	mostrarDatosAdiccionales();
	break;
	//FIN NAHUEL 26-02-2013
	//NAHUEL 27-02-2013
	case "editarDatosPersonales";
	editarDatosPersonales();
	break;
	
	case "guardarDatosPersonales";
	guardarDatosPersonales();
	break;
	
	case "editarDatosAdiccionales";
	editarDatosAdiccionales();
	break;
	
	//NAHUEL FIN 27-02-2013
	
	//NAHUEL 29-02-2013
	case "GuardarDatosAdicionales";
	GuardarDatosAdicionales();
	break;
	//FIN NAHUEL 29-02-2013
	
	//NAHUEL 29-02-2013 PARTE DOS SIN PASAR
	case "guardarEntrevista";
	guardarEntrevista();
	break;
	//FIN NAHUEL 29-02-2013 PARTE DOS SIN PASAR
	
	/*NAHUEL 30-04-2013
	
	editarDatosAdiccionales
	
	FIN NAHUEL 30-04-2013*/
	
	
	//NAHUEL 02-05-2013
	case "anularCuotaPersona";
	anularCuotaPersona();
	break;
	//FIN NAHUEL 02-05-2013
	//NAHUEL 04-05-2013
	case "EditarCuotas";
	EditarCuotas();
	break;
	
	case "GuardarEditarCambio";
	GuardarEditarCambio();
	break;
	
	//FIN NAHUEL 04-05-2013
    case "generarClave":
        $pass = substr(md5($_SERVER['REMOTE_ADDR'] . microtime() . rand(1, 100000)), 0, 6);
        echo $pass;
        break;
    case "comprobarClave":
        comprobarClave();
        break;

    case "guardarPais":
        guardarPais($Nombre);
        break;
	case "buscarUltimoDNI":
        buscarUltimoDNI($valor);
        break;	
    case "eliminarPais":
        eliminarPais();
        break;

    case "guardarProvincia":
        guardarProvincia();
        break;
    case "eliminarProvincia":
        eliminarProvincia();
        break;
		
	case "buscarNivReq":
        buscarNivReq($PerID);
        break;	

    case "guardarLocalidad":
        guardarLocalidad();
        break;
    case "eliminarLocalidad":
        eliminarLocalidad();
        break;
    case "guardarEntidadEducativa":
        guardarEntidadEducativa();
        break;
    case "eliminarEntidadEducativa":
        eliminarEntidadEducativa();
        break;
    case "mostrarNivelEstudios":
        mostrarNivelEstudios();
        break;
    case "guardarNivelEstudio":
        guardarNivelEstudio();
        break;
    case "cargaListado":
        cargaListado();
        break;
    case "eliminarNivelEstudio":
        eliminarNivelEstudio();
        break;
    case "mostrarEntidadEducativa":
        mostrarEntidadEducativa();
        break;
    case "guardarEstudio":
        guardarEstudio();
        break;
    case "buscarEntPaiProLoc":
        buscarEntPaiProLoc();
        break;
    case "llenarNiveles":
        $EntID = $_POST['ID'];
        cargarListaNivelEstudioRelacionados($EntID);
        break;
    case "buscarDNI":
        buscarDNI();
        break;
    case "buscarPerID":
        buscarPerID();
        break;
    case "obtenerApellidoNombre":
        obtenerApellidoNombre();
        break;
//Mario 28/02/11		
    case "obtenerApellidoNombreSIUCC":
        obtenerApellidoNombreSIUCC();
        break;
    case "obtenerTipoDoc":
        obtenerTipoDoc();
        break;
    case "obtenerFotoSIUCC":
        obtenerFotoSIUCC();
        break;
//*****

    case "obtenerApellidoNombreDocente":
        obtenerApellidoNombreDocente();
        break;

    case "llenarOpciones":
        $Menu = $_POST['Menu'];
        cargarListaOpcion2($Menu);
        break;
    case "llenarMenu":
        cargarListaMenu2();
        break;
    case "ordenarMenuArriba":
        ordenarMenuArriba();
        break;
    case "ordenarMenuAbajo":
        ordenarMenuAbajo();
        break;
    case "mostrarMenuOpciones":
        mostrarMenuOpciones();
        break;
    case "guardarMenu":
        guardarMenu();
        break;
    case "guardarOpcion":
        guardarOpcion();
        break;
    case "llenarOpcionesTabla":
        llenarOpcionesTabla();
        break;
    case "ordenarOpcionArriba":
        ordenarOpcionArriba();
        break;
    case "ordenarOpcionAbajo":
        ordenarOpcionAbajo();
        break;
    case "guardarUsuario":
        guardarUsuario();
        break;
    case "actualizarDatosUsuario":
        actualizarDatosUsuario();
        break;

    case "guardarRol":
        guardarRol();
        break;
    case "actualizarRoles":
        actualizarRoles();
        break;

    case "llenarRolesUsuario":
        llenarRolesUsuario();
        break;
    case "cargarPermisosSimples":
        cargarPermisosSimples();
        break;
    case "cargarPermisosRoles":
        cargarPermisosRoles();
        break;

    case "guadarPermisoUsuario":
        guadarPermisoUsuario();
        break;
    case "guadarPermisoRol":
        guadarPermisoRol();
        break;

    case "eliminarPermisoUsuario":
        eliminarPermisoUsuario();
        break;
    case "eliminarPermisoRol":
        eliminarPermisoRol();
        break;

    case "buscarOtrosDatos":
        buscarOtrosDatos();
        break;
    case "buscarDatosLegajo":
        buscarDatosLegajo();
        break;
    case "llenarArbolPermisoUsuario":
        $Usuario = $_POST['Usuario'];
        cargarArbolPermisos($Usuario);
        break;
    case "llenarArbolPermisoRoles":
        $Rol = $_POST['Rol'];
        cargarArbolPermisosRoles($Rol);
        break;
    case "guardarFamilia":
        $DNI = $_POST['DNI'];
		$_SESSION['sesion_ultimoDNI'] = $DNI;
		$DNI_Vinc = $_POST['DNI_Vinc'];
		$FTiID = $_POST['FTiID'];
		$PerID = gbuscarPerID($DNI);
		$PerID_Vinc = gbuscarPerID($DNI_Vinc);
		$UsuID = $_POST['UsuID'];
		guardarFamilia($PerID, $PerID_Vinc, $FTiID, $UsuID);
        break;

    case "armarFamilia":
        armarFamilia();
        break;
    case "cargarListaFamilia":
        $DNI = $_POST['DNI'];
        cargarListaFamilia($DNI);
        break;
    case "eliminarFamilia":
        eliminarFamilia();
        break;
    case "enviarMensajeUsuario":
        enviarMensajeUsuario();
        break;
    case "mostrarMensajeUsuario":
        mostrarMensajeUsuario();
        break;

    case "cargarMensajeParaIE":
        cargarMensajeParaIE();
        break;
    case "mostrarDetalleCuota":
        mostrarDetalleCuota();
        break;
    case "GenerarLegajoColegio":
        generarLegajoColegio();
        break;
    case "GenerarLegajoTerciario":
        GenerarLegajoTerciario();
        break;

    case "cargarCuentaUsuarioAlumno":
        cargarCuentaUsuarioAlumno();
        break;
    case "cargarCuentaUsuarioDocente":
        cargarCuentaUsuarioDocente();
        break;
    case "cargarFamiliaresSeguroAlumno":
        cargarFamiliaresSeguroAlumno();
        break;
    case "cargarBeneficiosAlumno":
        cargarBeneficiosAlumno();
        break;
    case "verificarBeneficiosAlumno":
        verificarBeneficiosAlumno();
        break;
    case "validarContratoGuardado":
        validarContratoGuardado();
        break;
    case "validarContratoGuardadoTerciario":
        validarContratoGuardadoTerciario();
        break;


    case "validarContratoGuardadoCursillo":
        validarContratoGuardadoCursillo();
        break;

    case "buscarDatosInscripcionLectivo":
        buscarDatosInscripcionLectivo();
        break;

    case "buscarDatosInscripcionLectivoCursillo":
        buscarDatosInscripcionLectivoCursillo();
        break;

    case "buscarDatosInscripcionLectivoTerciario":
        buscarDatosInscripcionLectivoTerciario();
        break;

    case "buscarLegajoColegio":
        buscarLegajoColegio();
        break;
	case "buscarLegajoColegioInstituto":
        buscarLegajoColegioInstituto();
        break;	
    case "buscarLegajoTerciario":
        buscarLegajoTerciario();
        break;
    case "obtenerDeuda":
        $PerID = $_POST['PerID'];
        echo Obtener_Deuda_Sistema($PerID);
        break;
    case "guardarInscripcionLectivo":
        guardarInscripcionLectivo();
        break;
    case "guardarInscripcionLectivoTerciario":
        guardarInscripcionLectivoTerciario();
        break;
    case "guardarInscripcionMateriaTerciario":
        guardarInscripcionMateriaTerciario();
        break;
    case "guardarInscripcionLectivoCursillo":
        guardarInscripcionLectivoCursillo();
        break;

    case "mostrarConstanciaInscripcion":
        mostrarConstanciaInscripcion();
        break;
    case "mostrarConstanciaInscripcionCursillo":
        mostrarConstanciaInscripcionCursillo();
        break;
    case "mostrarConstanciaInscripcionTerciario":
        mostrarConstanciaInscripcionTerciario();
        break;
    case "cargarListaInscriptos":
        $LecID = $_POST['LecID'];
        $CurID = $_POST['CurID'];
        $NivID = $_POST['NivID'];
        $DivID = $_POST['DivID'];
        cargarListaInscriptos($LecID, $CurID, $NivID, $DivID);
        break;
    case "cambiarDivisionAlumnos":
        cambiarDivisionAlumnos();
        break;
    case "VerUsuarioConectado":
        VerUsuarioConectado();
        break;
    case "revisarContadorInscripcion":
        revisarContadorInscripcion();
        break;
    case "obtenerIdUsuario":
        obtenerIdUsuario($Usu_ID);
        break;
    case "obtenerDetalleAccesoOpcion":
        obtenerDetalleAccesoOpcion();
        break;
    case "arreglarDNI":
        arreglarDNI();
        break;
    case "mostrarNoticiaSola":
        $ID = $_POST['ID'];
        mostrarNoticiaSola($ID);
        break;
    case "guardarRequisitos":
        guardarRequisitos();
        break;
    case "guardarCarrera":
        guardarCarrera();
        break;
    case "eliminarCarrera":
        eliminarCarrera();
        break;
    case "guardarPlan":
        guardarPlan();
        break;
    case "eliminarPlan":
        eliminarPlan();
        break;
    case "guardarMateria":
        guardarMateria();
        break;
    case "guardarMateriaTitulo":
        guardarMateriaTitulo();
        break;
    case "eliminarMateriasTitulo":
        eliminarMateriasTitulo();
        break;

    case "eliminarRequisito":
        eliminarRequisito();
        break;
    case "guardarRequisitoPersona":
        guardarRequisitoPersona();
        break;
    case "eliminarRequisitoPersona":
        eliminarRequisitoPersona();
        break;

    case "cargarListaTipoChequeraSIUCC2":
        $LecID = $_POST['LecID'];
        $FacID = $_POST['FacID'];
        cargarListaTipoChequeraSIUCC2($LecID, $FacID);
        break;
    case "cargarListaTipoChequeraColegioSIUCC2":
        $LecID = $_POST['LecID'];
        $FacID = $_POST['FacID'];
        cargarListaTipoChequeraColegioSIUCC2($LecID, $FacID);
        break;

    case "mostrarDeudaCuotasSIUCC":
        mostrarDeudaCuotasSIUCC();
        break;

    case "mostrarDetallePlanPagoSIUCC":
        mostrarDetallePlanPagoSIUCC();
        break;

    case "revisarRequisitosPersona":
        revisarRequisitosPersona();
        break;

    case "verificarInscripcionDefinitiva":
        verificarInscripcionDefinitiva();
        break;

    case "grabarInscripcionDefinitiva":
        grabarInscripcionDefinitiva();
        break;

    case "guardarAltaDocente":
        guardarAltaDocente();
        break;

    case "buscarDatosPersona":
        buscarDatosPersona();
        break;

    case "buscarDatosDocente":
        buscarDatosDocente();
        break;

    case "guardarMateriaColegio":
        guardarMateriaColegio();
        break;

    case "eliminarMateriaColegio":
        eliminarMateriaColegio();
        break;
    case "buscarFoto":
        if (isset($_POST['usuario']))
            echo buscarFoto($_POST['DNI'], $_POST['DocID'], $_POST['ancho'], true);
        else
            echo buscarFoto($_POST['DNI'], $_POST['DocID'], $_POST['ancho'], false);
        break;
    case "cargarListaCursos2":
        echo cargarListaCursos2($_POST['Nombre'], true, $_POST['NivID']);
        break;
	case "cargarListaCursosInstituto":
        echo cargarListaCursosInstituto($_POST['Leg_Colegio']);
        break;	
    case "guardarClaseDocente":
        guardarClaseDocente();
        break;
    case "mostrarClaseDocente":
        mostrarClaseDocente();
        break;
    case "eliminarClaseDocente":
        eliminarClaseDocente();
        break;
    case "cambiarUnidadUsuario":
        cambiarUnidadUsuario();
        break;
    case "actualizarCuotasAlumnoCursado":
        actualizarCuotasAlumnoCursado($_POST['PerID'], $_POST['LecID'], $_POST['NivID'], $_POST['masivo']);
        break;

    case "actualizarCuotasAlumnoCursadoMensual":
        actualizarCuotasAlumnoCursadoMensual($_POST['PerID'], $_POST['LecID'], $_POST['NivID'], $_POST['masivo']);
        break;

	case "actualizarImporteCuotasAlumno":
        actualizarImporteCuotasAlumno($_POST['PerID'], $_POST['LecID'], $_POST['NivID']);
        break;
    case "verificarLongitudTexto":
        verificarLongitudTexto();
        break;
    case "buscarDatosCarreraPlan":
        buscarDatosCarreraPlan();
        break;
    case "buscarDatosTituloTerciario":
        buscarDatosTituloTerciario();
        break;
    case "listarMateriaporTitulo":
        listarMateriaporTitulo();
        break;
    case "llenarMateriasTitulo":
        $TitID = $_POST['TitID'];
        cargarListaMateriasTitulo2($TitID, true);
        break;
    case "marcarMensajeLeido":
        $DesID = $_POST['DesID'];
        marcarMensajeLeido($DesID);
        break;
    case "guardarAutoridad":
        guardarAutoridad();
        break;
    case "EnviarCorreoAutoridad":
        EnviarCorreoAutoridad();
        break;
    case "agregarHorarioClaseDocente":
        agregarHorarioClaseDocente();
        break;
    case "guardarHorarioClase":
        guardarHorarioClase();
        break;
    case "guardarTrimestre":
        guardarTrimestre();
        break;
    case "guardarConfiguracionCuota":
        guardarConfiguracionCuota();
        break;
    case "guardarAsignacionCuota":
        guardarAsignacionCuota();
        break;
    case "eliminarTrimestre":
        eliminarTrimestre();
        break;
    case "obtenerTrimestreLectivo":
        $TriID = $_POST['TriID'];
        obtenerTrimestreLectivo($TriID);
        break;
    case "obtenerTrimestreNivel":
        $TriID = $_POST['TriID'];
        obtenerTrimestreNivel($TriID);
        break;
    case "guardarInstanciaTrimestre":
        guardarInstanciaTrimestre();
        break;
    case "eliminarInstanciaTrimestre":
        eliminarInstanciaTrimestre();
        break;
    case "guardarNotaColegio":
        guardarNotaColegio();
        break;
    case "eliminarNotaColegio":
        eliminarNotaColegio();
        break;
    case "obtenerDatosClaseDocente":
        obtenerDatosClaseDocente();
        break;
    case "obtenerNotaTipoArray":
        obtenerNotaTipoArray();
        break;
    case "guardarNotaClase":
        guardarNotaClase();
        break;
    case "cargarNotaClaseAlumnoTabla":
        $LegID = $_POST['LegID'];
        $LecID = $_POST['LecID'];
        $ClaID = $_POST['ClaID'];
        $CiaID = $_POST['CiaID'];
        cargarNotaClaseAlumnoTabla($LegID, $LecID, $ClaID, $CiaID);
        break;
    case "guardarInasistencia":
        guardarInasistencia();
        break;
	case "eliminarConfigurarCuota":
        eliminarConfigurarCuota();
        break;
	case "agregarCuotaColaPago":
        agregarCuotaColaPago();
        break;
	case "quitarCuotaColaPago":
        quitarCuotaColaPago();
        break;
	case "cargarDetalleFormaPago":
        cargarDetalleFormaPago();
        break;
	case "buscarFacturaUltima":
        buscarFacturaUltima();
        break;
	case "buscarReciboUltima":
        buscarReciboUltima();
        break;
	case "validarNumeroFactura":
        validarNumeroFactura();
        break;
	case "generarFactura":
        generarFactura();
        break;
    case "guardarDetallePago";
        guardarDetallePago();
        break;
	case "guardarCajaApertura";
        guardarCajaApertura();
        break;
	case "guardarCajaRendicion";
        guardarCajaRendicion();
        break;
	case "guardarSuperCajaApertura";
        guardarSuperCajaApertura();
        break;
	case "guardarSuperCajaCierre";
        guardarSuperCajaCierre();
        break;
	case "guardarRetiroDinero";
        guardarRetiroDinero();
        break;
	case "guardarIngresoDineroSuperCaja";
        guardarIngresoDineroSuperCaja();
        break;	
	case "guardarCajaAuditada";
        guardarCajaAuditada();
        break;
	case "bloquearPersona";
        bloquearPersona();
        break;
	case "obtenerBloqueo";
        obtenerBloqueo();
        break;	
	case "levantarBloqueo";
        levantarBloqueo();
        break;
	case "controlarNivel";
        $CurID = $_POST["ID"];
		cargarListaControlarNiveles($CurID);
        break;
	case "guardarCursoDivisionLectivo";
        guardarCursoDivisionLectivo();
        break;	
		
	//---------------------------
	case "guardarCounting";
	  guardarCounting();
	  break;
	case "buscarCounting";
	  buscarCounting();
	  break;
	case "buscarDatosCounting":
	  buscarDatosCounting();
	  break;
	case "eliminarCounting":
	  eliminarCounting();
	  break; 		
	case "revisarVacantes":
	  $Lec_ID = $_POST[Lec_ID];
	  $Cur_ID = $_POST[Cur_ID];
	  echo revisarVacantes($Lec_ID, $Cur_ID, $Inscriptos);
	  break; 	
	case "guardarAspirante";
		guardarAspirante();
		break;
	case "buscarAspirante";
		buscarAspirante();
		break;
	case "buscarDatosAspirante":
		buscarDatosAspirante();
		break;
	case "eliminarAspirante":
		eliminarAspirante();
		break; 	
	//--------------------------
	case "guardarPersonaNueva";
		guardarPersonaNueva();
		break;
	case "buscarPersonaNueva";
		buscarPersonaNueva();
		break;
	case "buscarDatosPersonaNueva":
		buscarDatosPersonaNueva();
		break;
	case "eliminarPersonaNueva":
		eliminarPersonaNueva();
		break; 	
	case "buscarDNIRepetido":
		buscarDNIRepetido();
		break; 	
	//--------------------------
	case "guardarNuevaEntrevista":
		guardarNuevaEntrevista();
		break;	
	case "buscarDatosArregloEntrevista":
		$Ent_per_ID = $_POST['Ent_per_ID'];
		//$Ent_Fecha = $_POST['Ent_Fecha'];
		buscarDatosArregloEntrevista($Ent_per_ID);
		break;	
	//------------------------------
	case "generarLegajoAspirante":
		generarLegajoAspirante();
		break;	
	case "buscarCursoDivision":
		$Per_ID = $_POST['PerID'];
		$CursoDiv = buscarCursoDivisionPersona($Per_ID, false);
		echo $CursoDiv;
		break;	
    case "buscarCursoDivisionActual":
        $Per_ID = $_POST['PerID'];
        $CursoDiv = buscarCursoDivisionActual($Per_ID, false);
        echo $CursoDiv;
        break;  	
	//------------------------------
	case "llenarCursoTurno":
		$NivID = $_POST[NivID];
		$TurID = $_POST[TurID];
		llenarCursoTurno("CurID", $NivID, $TurID, true);
		break;	
	//-------BACKUPS----------------
	case "mostrarBackups":		
		mostrarBackups();
		break;	
	case "mostrarRestore":		
		mostrarRestore();
		break;	
	case "mostrarRestoreWeb":		
		mostrarRestoreWeb();
		break;		
	//--------VALIDACIONES------------
	case "validarPadreDatos":		
		validarPadreDatos();
		break;	
	//--------------------------
	case "guardarDimension";
		guardarDimension();
		break;
	case "buscarColegio_Dimension";
		buscarColegio_Dimension();
		break;
	case "buscarDatosColegio_Dimension":
		buscarDatosColegio_Dimension();
		break;
	case "eliminarColegio_Dimension":
		eliminarColegio_Dimension();
		break; 	
	//---------------------------
	case "guardarColegio_Ambito";
		guardarColegio_Ambito();
		break;
	case "buscarColegio_Ambito";
		buscarColegio_Ambito();
		break;
	case "buscarDatosColegio_Ambito":
		buscarDatosColegio_Ambito();
		break;
	case "eliminarColegio_Ambito":
		eliminarColegio_Ambito();
		break;  	
	//---------------------------
	case "guardarColegio_AmbitoInforme";
		guardarColegio_AmbitoInforme();
		break;
	case "buscarColegio_AmbitoInforme";
		buscarColegio_AmbitoInforme();
		break;
	case "cambiarEstadoColegio_AmbitoInforme";
		cambiarEstadoColegio_AmbitoInforme();
		break;
	case "buscarDatosColegio_AmbitoInforme":
		buscarDatosColegio_AmbitoInforme();
		break;
	case "eliminarColegio_AmbitoInforme":
		eliminarColegio_AmbitoInforme();
		break; 	
	case "cargarListaAmbito":
		$Nombre = $_POST[Nombre];
		$DimID = $_POST[ID];
		$Valor = $_POST[Valor];
		cargarListaAmbito($Nombre, $DimID, $Valor);
		break;		
	//---------------------------
	case "guardarColegio_DocenteCurso";
		guardarColegio_DocenteCurso();
		break;
	case "buscarColegio_DocenteCurso";
		buscarColegio_DocenteCurso();
		break;
	case "buscarDatosColegio_DocenteCurso":
		buscarDatosColegio_DocenteCurso();
		break;
	case "eliminarColegio_DocenteCurso":
		eliminarColegio_DocenteCurso();
		break; 
	case "cargarListaAlumnosClase2":
		$Nombre = $_POST[Nombre];
		$Curso = $_POST[ID];
		list($Cur_ID, $Div_ID) = explode(",", $Curso);		
		$Inf_Lec_ID = $_POST['Inf_Lec_ID'];
		cargarListaAlumnosClase2($Nombre, $Cur_ID, $Div_ID, $Inf_Lec_ID);
		break;
	//------------------------------
	case "guardarEgreso_Cuenta";
		guardarEgreso_Cuenta();
		break;
	case "buscarEgreso_Cuenta";
		buscarEgreso_Cuenta();
		break;
	case "buscarDatosEgreso_Cuenta":
		buscarDatosEgreso_Cuenta();
		break;
	case "eliminarEgreso_Cuenta":
		eliminarEgreso_Cuenta();
		break; 
	//------------------------------
	case "guardarEgreso_Recibo";
		guardarEgreso_Recibo();
		break;
	case "guardarAdelantoEgreso_Recibo";
		guardarAdelantoEgreso_Recibo();
		break;
	case "buscarEgreso_Recibo";
		buscarEgreso_Recibo();
		break;
	case "buscarDatosEgreso_Recibo":
		buscarDatosEgreso_Recibo();
		break;
	case "eliminarEgreso_Recibo":
		eliminarEgreso_Recibo();
		break; 
	//------------------------------
	case "guardarLibro";
		guardarLibro();
		break;
	case "buscarLibro";
		buscarLibro();
		break;
	case "buscarDatosLibro":
		buscarDatosLibro();
		break;
	case "eliminarLibro":
		eliminarLibro();
		break; 
	//------------------------------
	case "mostrarVistaPreviaLibro";
		mostrarVistaPreviaLibro();
		break;
	case "GuardarVistaPreviaLibro";
		GuardarVistaPreviaLibro();
		break;
	case "reservarLibro";
		reservarLibro();
		break;
	case "entregarLibro";
		entregarLibro();
		break;
	case "buscarLibrosTabla";
		buscarLibrosTabla();
		break;
	case "buscarLibroCuotaPersona";
		buscarLibroCuotaPersona();
		break;
	case "guardarLibroVenta";
		guardarLibroVenta();
		break;
	case "BuscarLibroVentaNumero";
		BuscarLibroVentaNumero();
		break;	
	case "buscarLibroVenta";
		buscarLibroVenta();
		break;
	case "buscarDatosLibroVenta":
		buscarDatosLibroVenta();
		break;
	case "eliminarLibroVenta":
		eliminarLibroVenta();
		break; 
	case "guardarLibroCuotaPago";
		guardarLibroCuotaPago();
		break;
	case "eliminarLibroCuotaPago":
		eliminarLibroCuotaPago();
		break; 	
	case "GenerarLibroReciboPago":
		GenerarLibroReciboPago();
		break; 
		
	case "buscarDatosPersonaBaja":
		buscarDatosPersonaBaja();
		break;
	case "guardarPersonaBaja":
		guardarPersonaBaja();
		break;
	case "eliminarClaseHorarioDocente":
		eliminarClaseHorarioDocente();
		break;
	case "cargarListaMateriasOrientacion2":		
		echo cargarListaMateriasOrientacion2($_POST['Nombre'], $_POST['NivID'], false);
		break;
	case "asignarCuotasEspecial":
	 //echo $_POST['CMoID'];exit;
        asignarCuotasEspecial($_POST['PerID'], $_POST['CMoID']);
        break;
    case "enviarEmailPadres":
        enviarEmailPadres();
        break;
    case "obtenerDetalleInasistencia":
         obtenerDetalleInasistencia();
        break;
    case "eliminarAsistencia":
         eliminarAsistencia();
        break;
                
    case "obtenerCuadroAutoriza":
        obtenerCuadroAutoriza();
        break;
    case "importarEmpleadoDocente":
        importarEmpleadoDocente();
        break; 
    case "eliminarDocentesDuplicados":
        eliminarDocentesDuplicados();
        break;      
    //Eze
    case "guardarLectivo":
        guardarLectivo();
        break;
    case "eliminarLectivo":
        eliminarLectivo();
        break;
    case "guardarCurso":
        guardarCurso();
        break;     
    case "eliminarCurso":
        eliminarCurso();
        break;  
    case "guardarDivision":
        guardarDivision();
        break; 
    case "eliminarDivision":
        eliminarDivision();
        break; 
    case "guardarTipoEmpleado":
        guardarTipoEmpleado();
        break; 
    case "eliminarTipoEmpleado":
        eliminarTipoEmpleado();
        break; 
    case "guardarCuota":
        guardarCuota();
        break; 
    case "eliminarCuota":
        eliminarCuota();
        break;     
    case "Editar_Factura":
        Editar_Factura();
        break; 
    case "Guardar_Factura":
        Guardar_Factura();
        break; 
    case "guardarDebito":
        guardarDebito();
        break;
    case "buscarClasesAlumno":
        buscarClasesAlumno();
        break;
    case "eliminarClasesAlumno":
        eliminarClasesAlumno();
        break; 
    case "buscarNdecUltima":
        buscarNdecUltima();
        break;
    case "Cargar_Ndec":
        Cargar_Ndec();
        break;    
    //Eze      

    //Cuentas Contables
    case "guardarCuentaTipo";
        guardarCuentaTipo();
        break;
    case "buscarCuentaTipo";
        buscarCuentaTipo();
        break;
    case "buscarDatosCuentaTipo":
        buscarDatosCuentaTipo();
        break;
    case "eliminarCuentaTipo":
        eliminarCuentaTipo();
        break;
    case "cargarListaCuentaTipoInterior":
        $orden = $_POST['orden'];
        cargarListaCuentaTipoInterior($orden);
        break;
    case "guardarCuenta";
        guardarCuenta();
        break;
    case "buscarCuenta";
        buscarCuenta();
        break;
    case "buscarDatosCuenta":
        buscarDatosCuenta();
        break;
    case "eliminarCuenta":
        eliminarCuenta();
        break;        
    //Fin Cuentas Contables	
    case "traerDatosProveedor":
        traerDatosProveedor();
        break;
    case "traerSaldoCuentaContable":
        traerSaldoCuentaContable();
        break;
    case "guardarOrdenPago":
        guardarOrdenPago();
        break;   
    case "cargarDetalleFormaPago2":
        cargarDetalleFormaPago2();
        break; 
    case "buscarDNIPadre":
        buscarDNIPadre();
        break;
    case "guardarAjusteCaja":
        guardarAjusteCaja();
        break;    
    case "migrarInscripcionesAlumnos":
        migrarInscripcionesAlumnos();
        break;
    //------------------------------
    case "guardarDeudor";
        guardarDeudor();
        break;
    case "buscarDeudor";
        buscarDeudor();
        break;
    case "buscarDatosDeudor":
        buscarDatosDeudor();
        break;
    case "eliminarDeudor":
        eliminarDeudor();
        break; 
    //------------------------------
    case "guardarDeudorRecibo";
        guardarDeudorRecibo();
        break;    
    case "buscarDeudorRecibo";
        buscarDeudorRecibo();
        break;
    case "buscarDatosDeudorRecibo":
        buscarDatosDeudorRecibo();
        break;
    case "eliminarDeudorRecibo":
        eliminarDeudorRecibo();
        break; 
     case "traerDatosDeudor":
        traerDatosDeudor();
        break; 
     case "guardarOrdenIngreso":
        guardarOrdenIngreso();
        break;     
    //------------------------------
    case "cargarListaConfigCuota2":
        $LecID = $_POST['LecID'];
        cargarListaConfigCuota2($LecID, "");
    case "buscarAutorizacion":
        buscarAutorizacion();
        break; 
    case "buscarCodigoAutorizacion":
        buscarCodigoAutorizacion();
        break;
    //------------------------------
    case "listarMovimientosCuenta":        
        listarMovimientosCuenta(); 
    //------------------------------
    case "cargarListadoDocentesActivo":        
        cargarListadoDocentesActivo($_POST['i'], $_POST['Doc_ID']);
        break;
    case "cambiarDocenteClase":        
        cambiarDocenteClase();
        break;
    case "cargarListaCursos3":        
        cargarListaCursos3($_POST['Nombre'], true, $_POST['OriID']);
        break;
    case "cargarListaMateriasOrientacion3":        
        cargarListaMateriasOrientacion3($_POST['Nombre'], $_POST['OriID']);
        break;
    case "buscarNivID":        
        buscarNivID();
        break;
    case "cargarListaDivisionOrientacion3":        
        cargarListaDivisionOrientacion3($_POST['Nombre'], $_POST['OriID']);
        break;    

    case "borrarInscripcionLectivo":        
        borrarInscripcionLectivo();

    case "buscarPersonaDeuda";
        buscarPersonaDeuda();
        break;
    
	default:
//        echo "La opción elegida no es válida";
}//fin switch

?>