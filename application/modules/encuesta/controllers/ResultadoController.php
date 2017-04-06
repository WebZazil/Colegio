<?php

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

    public function init()
    {
        /* Initialize action controller here */
        $dbAdapter = Zend_Registry::get("dbmodquery");
        
        $this->evaluacionDAO = new Encuesta_DAO_Evaluacion($dbAdapter);
        $this->grupoDAO = new Encuesta_DAO_Grupos($dbAdapter);
        $this->cicloDAO = new Encuesta_DAO_Ciclo($dbAdapter);
        $this->encuestaDAO = new Encuesta_DAO_Encuesta($dbAdapter);
        
        $this->materiaDAO = new Encuesta_DAO_Materia($dbAdapter);
        $this->registroDAO = new Encuesta_DAO_Registro($dbAdapter);
        $this->asignacionDAO = new Encuesta_DAO_AsignacionGrupo($dbAdapter);
        
        $this->preguntaDAO = new Encuesta_DAO_Pregunta($dbAdapter);
        $this->respuestaDAO = new Encuesta_DAO_Respuesta($dbAdapter);
        
        $this->opcionDAO = new Encuesta_DAO_Opcion($dbAdapter);
    }

    public function indexAction()
    {
        // action body
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
        $grupos = $this->grupoDAO->getAllGruposByIdCicloEscolar($cicloActual->getIdCiclo());
        $this->view->grupos = $grupos;
    }
    
    public function evalsgrAction()
    {
        // action body
        $idGrupo = $this->getParam("gr");
        $grupo = $this->grupoDAO->obtenerGrupo($idGrupo);
        $this->view->grupo = $grupo;
        //print_r($grupo);
        //$asignaciones = $this->evaluacionDAO->getAsignacionesByIdGrupo($idGrupo);
        //print_r($asignaciones);print_r("<br /><br />");
        $this->evaluacionDAO->getAsignacionesConjuntosByIdGrupo($idGrupo);
        print_r("<br /><br />");
        $conjuntos = $this->evaluacionDAO->getConjuntosByIdGrupoEscolar($idGrupo);
        
        foreach ($conjuntos as $conjunto) {
            print_r($conjunto);print_r("<br />");
            $evaluaciones = $this->evaluacionDAO->getEvaluacionesByIdConjunto($conjunto["idConjuntoEvaluador"]);
            
            
            print_r($evaluaciones);print_r("<br /><br />");
        }
        
        //print_r($conjuntos);
        //$asignaciones = $this->asignacionDAO->obtenerAsignacionesGrupo($idGrupo);
        //print_r($asignaciones);
        
    }

    public function resasignAction()
    {
        // action body
    }

    public function resgraphAction()
    {
        // action body
    }


}




