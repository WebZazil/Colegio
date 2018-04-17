<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Resumen {
    
    private $tableResumenEvaluacion;
    private $tableEvaluacionRealizada;
    private $tableEncuesta;
    private $tableAsignacionGrupo;
    private $tableOpcionCategoria;
    private $tableCategoriasRespuesta;
    private $tableEncuestasRealizadas;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableResumenEvaluacion = new Encuesta_Data_DbTable_ResumenEvaluacion($config);
        $this->tableEvaluacionRealizada = new Encuesta_Data_DbTable_EvaluacionRealizada($config);
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        $this->tableOpcionCategoria = new Encuesta_Data_DbTable_OpcionCategoria($config);
        $this->tableCategoriasRespuesta = new Encuesta_Data_DbTable_CategoriasRespuesta($config);
        
        $this->tableEncuestasRealizadas = new Encuesta_Data_DbTable_EncuestasRealizadas($config);
    }
    
    public function obtenerResumen($idAsignacionGrupo, $idEncuesta) {
        $tRE = $this->tableResumenEvaluacion;
        $select = $tRE->select()->from($tRE)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
        $rowRE = $tRE->fetchRow($select);
        
        if (is_null($rowRE)) {
            return null;
        }else{
            return $rowRE->toArray();
        }
    }
    
    /**
     * 
     * @param int $idAsignacionGrupo
     * @param int $idEvaluacion
     */
    public function crearResumen($idAsignacionGrupo, $idEncuesta) {
        // ============================================================= ResumenEvaluacion
        $tRE = $this->tableResumenEvaluacion;
        $select = $tRE->select()->from($tRE)->where('idEncuesta=?',$idEncuesta)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
        $rowRE = $tRE->fetchRow($select);
        // ============================================================= EvaluacionesRealizadas
        $tER = $this->tableEvaluacionRealizada;
        $select = $tER->select()->from($tER)->where('idEncuesta=?',$idEncuesta)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
        $rowsER = $tER->fetchAll($select)->toArray();
        $numEvaluadores = count($rowsER);
        
        // No existe registro
        if (is_null($rowRE) || $rowRE->numEvals != $numEvaluadores) {
            //print_r('Evaluadores: '.$numEvaluadores.'<br />');
            // ============================================================= Encuesta
            $tEn = $this->tableEncuesta;
            $select = $tEn->select()->from($tEn)->where('idEncuesta=?',$idEncuesta);
            $rowEncuesta = $tEn->fetchRow($select)->toArray();
            // ============================================================= Asignacion
            $tAG = $this->tableAsignacionGrupo;
            $select = $tAG->select()->from($tAG)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
            $rowAG = $tAG->fetchRow($select)->toArray();
            
            // ============================================================= Asignacion
            $contenedor = $this->makeContainer($rowsER);
            $promedio = (10 * $contenedor['valorTotal']) / $contenedor['valorMaximo'];
            $fecha = date('Y-m-d H:i:s', time());
            
            $data = array(
                'idEncuesta' => $idEncuesta,
                'idAsignacionGrupo' => $idAsignacionGrupo,
                'resumen' => json_encode($contenedor['respuestas']),
                'numEvals' => $numEvaluadores,
                'promedio' => $promedio,
                'actualizaciones' => 0,
                'ultimoActualizado' => $fecha,
                'creacion' => $fecha
            );
            
            $tRE->insert($data);
        }else {
            throw new Exception('Resumen ya realizado, actualizar?');
        }
    }
    
    /**
     * 
     * @param int $idAsignacionGrupo
     * @param int $idEvaluacion
     * @return boolean existe o no resumen realizado
     */
    public function existeResumen($idAsignacionGrupo, $idEvaluacion) {
        $tRE = $this->tableResumenEvaluacion;
        $select = $tRE->select()->from($tRE)->where('idEncuesta=?',$idEvaluacion)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
        $rowRE = $tRE->fetchRow($select);
        
        if (is_null($rowRE)) {
            return false;
        }else{
            return true;
        }
    }
    
    public function verificaEncuestaRealizada($idEncuesta, $idAsignacionGrupo) {
        // Verificar si hay evaluaciones realizadas
        $tEvRe = $this->tableEvaluacionRealizada;
        $select = $tEvRe->select()->from($tEvRe)->where('idEncuesta=?',$idEncuesta)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
        $rowsEvRe = $tEvRe->fetchAll($select);
        
        $tEnRe = $this->tableEncuestasRealizadas;
        $select = $tEnRe->select()->from($tEnRe)->where('idEncuesta=?',$idEncuesta)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
        $rowEnRe = $tEnRe->fetchRow($select);
        
        $totalRealizadas = count($rowsEvRe);
        // Inserta valor si no encuentra registro previo, actualiza si ya existe
        if (is_null($rowEnRe)) {
            $datos = array(
                'idEncuesta' => $idEncuesta,
                'idAsignacionGrupo' => $idAsignacionGrupo,
                'realizadas' => $totalRealizadas
            );
            
            $tEnRe->insert($datos);
        }else{
            if ($rowEnRe->realizadas != $totalRealizadas) {
                $rowEnRe->realizadas = $totalRealizadas;
                $rowEnRe->save();
            }
        }
    }
    
    /**
     * 
     * @param int $rows
     */
    private function makeContainer($rows) {
        // Categorias Respuesta
        $tCR = $this->tableCategoriasRespuesta;
        $rowsCR = $tCR->fetchAll()->toArray();
        // Opcion Categoria
        $tOC = $this->tableOpcionCategoria;
        $rowsOC = $tOC->fetchAll()->toArray();
        
        // Valor de la encuesta
        $valorTotal = 0;
        // Valor de la encuesta con promedio de 10
        $valorMaximo = 0;
        // Total de preguntas
        $numeroPreguntas = 0;
        //print_r($rows);
        // Contenedor General de Evaluacion: $pregunta => SumaRespuestas(OpcionCategoria)
        // $container = array();
        
        $respuestas = array();
        foreach ($rows as $row){
            $string = str_replace("\\", "", $row['json']);
            //print_r($row); print_r('<br />');
            $json = substr($string, 1, -1);
            //$json = $row['json'];
            //print_r($json); print_r('<br />');
            $arrayJson = json_decode($json,true);
            //print_r($arrayJson); print_r('<br />');
            // Compactar en un solo contenedor la evaluacion 
            $evaluacion = array();
            foreach ($arrayJson as $fase) {
                foreach ($fase as $idPregunta => $idOpcion){
                    $evaluacion[$idPregunta] = $idOpcion;
                }
            }
            //print_r('<br />');
            //print_r($evaluacion);
            // ================================================ Una Evaluacion
            foreach ($evaluacion as $idPregunta => $idOpcion) {
                $categoria = null;
                // Recorremos opciones para obtener la opcion en cuestion
                foreach ($rowsOC as $rowOC){
                    if ($idOpcion == $rowOC['idOpcionCategoria']) {
                        
                        $tipo = $rowOC['tipoValor'];
                        $valor = 0;
                        
                        switch ($tipo) {
                            case 'EN' :
                                $valor = $rowOC['valorEntero'];
                                break;
                            case 'DC' :
                                $valor = $rowOC['valorDecimal'];
                                break;
                        }
                        
                        if (array_key_exists($idPregunta, $respuestas)) {
                            $respuestas[$idPregunta] += $valor;
                        }else{
                            $respuestas[$idPregunta] = $valor;
                        }
                        
                        $valorTotal += $valor;
                        //Obtenemos la categoria
                        foreach ($rowsCR as $rowCR){
                            if ($rowOC['idCategoriasRespuesta'] == $rowCR['idCategoriasRespuesta']) {
                                $categoria = $rowCR;
                                break;
                            }
                        }
                        break;
                    }
                }
                // Recorremos opciones para obtener la opcion maxima
                $opcionMayor = null;
                foreach ($rowsOC as $rowOC) {
                    // Encontrando opcion maxima
                    if ($categoria['idOpcionMayor'] == $rowOC['idOpcionCategoria']) {
                        $opcionMayor = $rowOC;
                        break;
                    }
                }
                
                $tipo = $opcionMayor['tipoValor'];
                $valor = 0;
                
                switch ($tipo) {
                    case 'EN' :
                        $valor = $opcionMayor['valorEntero'];
                        break;
                    case 'DC' :
                        $valor = $opcionMayor['valorDecimal'];
                        break;
                }
                $valorMaximo += $valor; 
            }
            
        }
        // Crear contenedor
        $container = array();
        $container['respuestas'] = $respuestas;
        $container['valorTotal'] = $valorTotal;
        $container['valorMaximo'] = $valorMaximo;
        $container['numeroPreguntas'] = count($respuestas);
        //print_r($container);
        return $container;
    }
    
}