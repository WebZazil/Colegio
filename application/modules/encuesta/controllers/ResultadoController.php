<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_ResultadoController extends Zend_Controller_Action
{

    private $evaluacionDAO = null;

    private $grupoDAO = null;

    private $cicloDAO = null;

    private $encuestaDAO = null;

    private $materiaDAO = null;

    private $asignacionDAO = null;

    private $preguntaDAO = null;

    private $respuestaDAO = null;

    private $opcionDAO = null;

    private $reporter = null;

    private $utilJSON = null;

    private $utilText = null;
    
    private $nivelDAO;
    
    private $docenteDAO;
    
    private $resumenDAO;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_helper->redirector->gotoSimple("index", "index", "encuesta");
        }
        $identity = $auth->getIdentity();
        $dbAdapter = $identity['adapter'];
        
        //$this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
        $this->evaluacionDAO = new Encuesta_Data_DAO_Evaluacion($dbAdapter);
        
        $this->grupoDAO = new Encuesta_DAO_Grupos($dbAdapter);
        $this->cicloDAO = new Encuesta_DAO_Ciclo($dbAdapter);
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($dbAdapter);
        
        $this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
        $this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
        $this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($dbAdapter);
        
        $this->preguntaDAO = new Encuesta_DAO_Pregunta($dbAdapter);
        $this->respuestaDAO = new Encuesta_DAO_Respuesta($dbAdapter);
        
        $this->opcionDAO = new Encuesta_DAO_Opcion($dbAdapter);
        
        $this->reporter = new Encuesta_Util_Reporteador($dbAdapter);
        $this->utilJSON = new Encuesta_Util_Json;
        $this->utilText = new Encuesta_Util_Text;
        
        $this->nivelDAO = new Encuesta_Data_DAO_NivelEducativo($dbAdapter);
        $this->docenteDAO = new Encuesta_Data_DAO_Docente($dbAdapter);
        
        $this->resumenDAO = new Encuesta_Data_DAO_Resumen($dbAdapter);
    }

    public function indexAction()
    {
        // action body
        $niveles = $this->nivelDAO->getAllNivelesEducativos();
        $this->view->niveles = $niveles;
    }

    public function graficaAction()
    {
        // action body
    }

    public function resconAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
        $idEvaluacion = $this->getParam("ev");
        $idAsignacion = $this->getParam("as");
        
        $evaluacion = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        $asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        //
        $docente = $this->registroDAO->obtenerRegistro($asignacion["idRegistro"]);
        $materia = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
        
        $this->view->docente = $docente;
        $this->view->materia = $materia;
        // Obtener todas las evaluaciones de la asignacion en el conjunto
        $resultados = $this->evaluacionDAO->getResultadoEvaluacionAsignacionByIdConjunto($idConjunto, $idEvaluacion, $idAsignacion);
        //print_r($resultados);
        $results = array();
        // Transformamos los json obtenidos a arrays
        foreach ($resultados as $resultado) {
            //print_r($resultado);
            //$json = $resultado["json"];
            $obj = str_replace("\\", "", $resultado["json"]);
            $str = substr($obj, 1, -1);
            //print_r($str); print_r("<br /><br />");
            $str = json_decode($str,true);
            //print_r($str);
            $results[] = $str;
            //break;
        }
        
        //print_r($results);
        $resT = array(); // todas las encuestas
        // Simplificamos solo obteniendo los conjuntos de arrays
        foreach ($results as $fases) {
            $contenedor = array();
            foreach ($fases as $fase) {
                //print_r($fase); print_r("<br />");
                foreach ($fase as $key => $value) {
                    //print_r($key."-".$value); print_r("<br />");
                    $contenedor[$key] = $value;
                }
            }
            $resT[] = $contenedor;
        }
        // Creamos un Array de resultado, solo sumando el total de respuestas
        //print_r($resT);
        $rPreferencia = array();
        foreach ($resT as $rests) {
            //print_r($rest);
            
            foreach ($rests as $idPregunta => $idOpcion) {
                $opcion = $this->opcionDAO->obtenerOpcion($idOpcion);
                $opcionMayor = $this->opcionDAO->obtenerOpcionMayor($idOpcion);
                // Obtener la opcion mayor
                
                $valor = null;
                $obj = array();
                switch ($opcion->getTipoValor()) {
                    case 'EN':
                        $valor = $opcion->getValorEntero();
                        break;
                    case 'DC':
                        $valor = $opcion->getValorDecimal();
                        break;
                }
                
                if (array_key_exists($idPregunta, $rPreferencia)) {
                    //$valAnterior = $rPreferencia[$idPregunta];
                    $rPreferencia[$idPregunta]["preferencia"] = $rPreferencia[$idPregunta]["preferencia"] + $valor;
                    //$rPreferencia[$idPregunta]["opcion"] + $opcion;
                }else{
                    //La primera insercion
                    $obj["preferencia"] = $valor;
                    $obj["opcionMayor"] = $opcionMayor;
                    $rPreferencia[$idPregunta] = $obj;
                }
            }
        }
        
        foreach ($rPreferencia as $rPref) {
            //print_r($rPref); print_r("<br /><br />");
        }
        
        $this->view->preferencias = $rPreferencia;
        
        $this->view->conjunto = $conjunto;
        $this->view->asignacion = $asignacion;
        $this->view->evaluacion = $evaluacion;
        $this->view->resultados = $results;
        
        $this->view->preguntaDAO = $this->preguntaDAO;
        $this->view->respuestaDAO = $this->respuestaDAO;
        $this->view->opcionDAO = $this->opcionDAO;
    }

    public function evalsAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
    }

    public function evalscoAction()
    {
        // action body
        $idConjunto = $this->getParam("co");
        $conjunto = $this->evaluacionDAO->getConjuntoById($idConjunto);
        //$evaluacionesConjunto = $this->evaluacionDAO->getEvaluacionesByIdConjunto($idConjunto);
        $asignacionesConjunto = $this->evaluacionDAO->getAsignacionesByIdConjunto($idConjunto);
        
        
        //print_r($asignacionesConjunto);
        $this->view->conjunto = $conjunto;
        $this->view->asignacionesConjunto = $asignacionesConjunto;
        $this->view->encuestaDAO = $this->encuestaDAO;
        $this->view->registroDAO = $this->registroDAO;
        $this->view->materiaDAO = $this->materiaDAO;
    }

    public function conjuntosAction()
    {
        // action body
        $conjuntos = $this->evaluacionDAO->getAllConjuntos();
        $this->view->conjuntos = $conjuntos;
    }

    public function gruposAction()
    {
        // action body
        $cicloDAO = $this->cicloDAO;
        $cicloActual = $cicloDAO->getCurrentCiclo();
        $grupos = $this->grupoDAO->getAllGruposByIdCicloEscolar($cicloActual['idCicloEscolar']);
        $this->view->grupos = $grupos;
    }

    public function asignsgrAction()
    {
        // action body
        $idGrupo = $this->getParam("gr");
        $grupo = $this->grupoDAO->obtenerGrupo($idGrupo);
        $this->view->grupo = $grupo;
        
        $idsAsignacionesGrupo = $this->evaluacionDAO->getAsignacionesConjuntosByIdGrupo($idGrupo);
        //print_r($idsAsignacionesGrupo);print_r("<br /><br />");
        //return;
        $evaluaciones = array();
        
        foreach ($idsAsignacionesGrupo as $key => $idAsignacion) {
            $asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
            $contenedor = array();
            $contenedor["materia"] = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
            $contenedor["docente"] = $this->docenteDAO->getDocenteById($asignacion['idDocente']); //$this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
            //$contenedor["evaluaciones"] = null;
            //$this->evaluacionDAO->getTiposEvaluacionByIdAsignacion($idAsignacion);
            //print_r("<br />");
            $evaluaciones[$idAsignacion] = $contenedor;
        }
        
        //print_r($evaluaciones);
        
        $this->view->evaluaciones = $evaluaciones;
    }

    public function evalsasAction()
    {
        // action body
        $idAsignacionGrupo = $this->getParam("as");
        //$idGrupo
        //$asignacion = $this->
        $asignacion = $this->asignacionDAO->getAsignacionById($idAsignacionGrupo);
        //print_r($asignacion); print_r("<br />");
        $this->view->asignacion = $asignacion;
        
        $materia = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
        //print_r($materia); print_r("<br />");
        $docente = $this->docenteDAO->getDocenteById($asignacion['idDocente']); //$this->registroDAO->obtenerRegistro($asignacion["idRegistro"])->toArray();
        //print_r($docente); print_r("<br />");
        $grupoE = $this->grupoDAO->obtenerGrupo($asignacion["idGrupoEscolar"]);
        //print_r($grupoE); print_r("<br />");
        $this->view->materia = $materia;
        $this->view->docente = $docente;
        $this->view->grupoE = $grupoE;
        
        $tiposEvaluacion = $this->evaluacionDAO->getTiposEvaluacionByIdAsignacion($idAsignacionGrupo);
        $this->view->evaluaciones = $tiposEvaluacion;
        $this->view->encuestaDAO = $this->encuestaDAO;
    }

    public function resgrasAction()
    {
        // function resultados grupo asignacion: res gr as
        // ========================================================== Obtenemos parametros
        $idAsignacion = $this->getParam("as");
        $idEvaluacion = $this->getParam("ev");
        // ========================================================== Obtenemos objetos
        $asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
        $encuesta = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        // ========================================================== Obtenemos preguntas de encuesta
        $preguntasEncuesta = $this->preguntaDAO->getPreguntasByIdEncuesta($idEvaluacion);
        
        $resumen = null;
        // ========================================================== Resolvemos conflicto con tabla
        $this->resumenDAO->verificaEncuestaRealizada($idEvaluacion, $idAsignacion);
        // ========================================================== Verificamos resumen y creamos si no existe
        if (! $this->resumenDAO->existeResumen($idAsignacion, $idEvaluacion)) {
            $this->resumenDAO->crearResumen($idAsignacion, $idEvaluacion);
        }
        // ========================================================== Obtenemos resumen
        $resumen = $this->resumenDAO->obtenerResumen($idAsignacion, $idEvaluacion);
        // ========================================================== Enviamos resumen a vista
        $this->view->resumen = $resumen;
        $this->view->encuesta = $encuesta;
        $this->view->asignacion = $asignacion;
        
        $this->view->preguntasEncuesta = $preguntasEncuesta;
        
        $reporteador = $this->reporter;
        
        $docente = $this->docenteDAO->getDocenteById($asignacion['idDocente']);
        $materia = $this->materiaDAO->getMateriaById($asignacion["idMateriaEscolar"]);
        $grupoE = $this->grupoDAO->obtenerGrupo($asignacion["idGrupoEscolar"]);
        
        $evaluaciones = $this->evaluacionDAO->getEvaluacionesByAsignacionAndEvaluacion($idAsignacion, $idEvaluacion);
        $numeroEvaluadores = count($evaluaciones);
        
        $this->view->totalEvaluadores = $numeroEvaluadores;
        $jsonArrays = array();
        //print_r($evaluaciones);
        //$this->utilJSON->processJsonEncuestaDos($json);
        $resT = array();
        $idReporte = 0;
        switch ($idEvaluacion) {
            case '1':
                
                break;
            case '2':
                $idReporte = $reporteador->generarReporteDocenteOrientadora($idAsignacion, $idEvaluacion);
                foreach ($evaluaciones as $evaluacion) {
                    //print_r($evaluacion["json"]); print_r("<br /><br />");
                    //print_r($this->utilJSON->processJsonEncuestaDos($evaluacion["json"])); print_r("<br /><br />");
                    $jsonArrays[] = $this->utilJSON->processJsonEncuestaDos($evaluacion["json"]);
                }
                break;
            case '3':
                //$idReporte = $reporteador->generarReporteGrupalAsignacion($asignacion["idGrupoEscolar"], $idAsignacion, $idEvaluacion);
                $idReporte = $reporteador->generarReporteGrupal($idAsignacion, $idEvaluacion);
                foreach ($evaluaciones as $evaluacion) {
                    $jsonArrays[] = $this->utilJSON->processJsonEncuestaTres($evaluacion["json"]);
                }
                break;
            case '4':
                foreach ($evaluaciones as $evaluacion) {
                    //print_r($evaluacion); print_r("<br /><br />");
                    $jsonArrays[$evaluacion["idEvaluador"]] = $this->utilJSON->processJsonEncuestaCuatro($evaluacion["json"]);
                }
                break;
        }
        
        // Creamos un Array de resultado, solo sumando el total de respuestas
        //print_r($results);
        $respuestasAbiertas = array();
        
        $preferencias = array();
        foreach ($jsonArrays as $idEvaluador => $jsonArray) {
            //print_r($rest);
            foreach ($jsonArray as $idPregunta => $idOpcion) {
                $pregunta = $this->preguntaDAO->getPreguntaById($idPregunta);
                $contenedor = array();
                $valorOpcion = 0;
                if($pregunta->getTipo() == "SS"){
                    $opcion = $this->opcionDAO->obtenerOpcion($idOpcion);
                    $opcionMayor = $this->opcionDAO->obtenerOpcionMayor($idOpcion);
                    $valorOpcion = $opcion->getValorEntero();
                    $contenedor["opcionMayor"] = $opcionMayor;
                    $contenedor["preferencia"] = $valorOpcion; 
                }elseif($pregunta->getTipo() == "AB"){
                    $contenedor["abiertas"][] = $idOpcion;
                }
                
                // Si ya hay una ocurrencia de esta pregunta insertada en el array
                if (array_key_exists($idPregunta, $preferencias)) {
                    
                    if($pregunta->getTipo() == "AB"){
                        $preferencias[$idPregunta]["abiertas"][] = $idOpcion;
                    }else{
                        $preferencias[$idPregunta]["preferencia"] += $valorOpcion;
                    }
                }else{ // Si es la primera insercion de esta pregunta en el array
                    $preferencias[$idPregunta] = $contenedor;
                }
                
            }
        }

        $this->view->grupoE = $grupoE;
        
        $this->view->docente = $docente;
        $this->view->materia = $materia;
        $this->view->idReporte = $idReporte;
        
        $this->view->preferencias = $preferencias;
        $this->view->preguntaDAO = $this->preguntaDAO;
        $this->view->respuestaDAO = $this->respuestaDAO;
        
    }

    public function resasignAction()
    {
        // action body
    }

    public function resgraphAction()
    {
        // action body
    }

    public function resautoevAction()
    {
        // action body
        $idAsignacion = $this->getParam("as");
        $idEvaluacion = $this->getParam("ev");
        
        $asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
        $encuesta = $this->encuestaDAO->getEncuestaById($idEvaluacion);
        
        $evaluadores = array();
        $evaluaciones = $this->evaluacionDAO->getEvaluacionesByAsignacionAndEvaluacion($idAsignacion, $idEvaluacion);
        
        foreach ($evaluaciones as $index => $evaluacion) {
            $evaluadores[] = $this->evaluacionDAO->getEvaluadorById($evaluacion["idEvaluador"]);
        }
        
        $this->view->asignacion = $asignacion;
        $this->view->evaluacion = $encuesta->toArray();
        $this->view->evaluadores = $evaluadores;
        
        $this->view->evaluacionDAO = $this->evaluacionDAO;
    }

    public function autoevalAction()
    {
        // action body
        $idAsignacion = $this->getParam("as");
        $idEvaluacion = $this->getParam("ev");
        $idEvaluador = $this->getParam("er");
        
        $asignacion = $this->asignacionDAO->getAsignacionById($idAsignacion);
        $evaluacion = $this->encuestaDAO->getEncuestaById($idEvaluacion)->toArray();
        $evaluador = $this->evaluacionDAO->getEvaluadorById($idEvaluador);
        
        $resultado = $this->evaluacionDAO->getResultadoIndividual($idAsignacion, $idEvaluacion, $idEvaluador);
        $arrResultados = $this->utilJSON->processJsonEncuestaCuatro($resultado["json"]);
        
        $encuesta = array();
        
        foreach ($arrResultados as $idPregunta => $idOpcion) {
            $pregunta = $this->preguntaDAO->getPreguntaById($idPregunta)->toArray();
            $opcion = $this->opcionDAO->obtenerOpcion($idOpcion)->toArray();
            $maxOpcion = $this->opcionDAO->obtenerOpcionMayor($idOpcion);
            
            $encuesta[$idPregunta] = array("pregunta" => $pregunta, "opcion" => $opcion, "maxOpcion"=>$maxOpcion);
        }
        
        $idReporte = $this->reporter->obtenerReporteDocenteAutoevaluacion($idAsignacion, $idEvaluacion, $idEvaluador);
        
        $this->view->asignacion = $asignacion;
        $this->view->evaluador = $evaluador;
        $this->view->evaluacion = $evaluacion;
        $this->view->resultado = $encuesta;
        $this->view->idReporte = $idReporte;
    }


}





