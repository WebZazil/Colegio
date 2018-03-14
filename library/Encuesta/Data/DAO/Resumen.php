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
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableResumenEvaluacion = new Encuesta_Data_DbTable_ResumenEvaluacion($config);
        $this->tableEvaluacionRealizada = new Encuesta_Data_DbTable_EvaluacionRealizada($config);
        $this->tableEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tableAsignacionGrupo = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        $this->tableOpcionCategoria = new Encuesta_Data_DbTable_OpcionCategoria($config);
        $this->tableCategoriasRespuesta = new Encuesta_Data_DbTable_CategoriasRespuesta($config);
    }
    
    public function obtenerResumen($idAsignacionGrupo, $idEncuesta) {
        $tRE = $this->tableResumenEvaluacion;
        $select = $tRE->select()->from($tRE)->where('idAsignacionGrupo=?',$idAsignacionGrupo);
        $rowRE = $tRE->fetchRow($select)->toArray();
        
        return $rowRE;
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
        if (is_null($rowRE) || $rowRE['numEvals'] != $numEvaluadores) {
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
            $promedio = (10 * $contenedor['total']) / $contenedor['valorMaximo'];
            $fecha = date('Y-m-d H:i:s');
            
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
    
    public function verificaResumen($idAsignacionGrupo, $idEvaluacion) {
        ;
    }
    
    /**
     * 
     * @param int $rows
     */
    private function makeContainer($rows) {
        $tCR = $this->tableCategoriasRespuesta;
        $rowsCR = $tCR->fetchAll()->toArray();
        
        $tOC = $this->tableOpcionCategoria;
        $rowsOC = $tOC->fetchAll()->toArray();
        
        $valorTotal = 0;
        $valorMaximo = 0;
        $numPreguntas = 0;
        //print_r($rows);
        $container = array();
        foreach ($rows as $row){
            $stringJson = $row['json'];
            $stringJson = substr($stringJson, 1, -1);
            
            $arrayJson = json_decode($stringJson,true);
            $respuestas = array();
            foreach ($arrayJson as $fase){
                foreach ($fase as $idPregunta => $idOpcion) {
                    $opcion = null;
                    $maxOpcion = null;
                    foreach ($rowsOC as $rowOC) {
                        $categoria = null;
                        // Obtener categoria para buscar max opcion o min opcion despues
                        foreach ($rowsCR as $rowCR){
                            if ($rowOC['idCategoriasRespuesta'] == $rowCR['idCategoriasRespuesta']) {
                                $categoria = $rowCR;
                                continue;
                            }
                        }
                        // Obtenemos la opcion a procesar
                        if ($rowOC['idOpcionCategoria'] == $idOpcion) {
                            $opcion = $rowOC;
                        }
                        // Obtenemos la opcion mayor, con la categoria antes obtenida
                        if ($categoria['idOpcionMayor'] == $rowOC['idOpcionCategoria']) {
                            $maxOpcion = $rowOC;
                        }
                    }
                    
                    if (!is_null($opcion)) {
                        $tipo = $opcion['tipoValor'];
                        $valor = 0;
                        $maxValor = 0;
                        switch ($tipo) {
                            case 'EN' : 
                                $valor = $opcion['valorEntero']; 
                                $maxValor = $maxOpcion['valorEntero'];
                                break;
                            case 'DC' : 
                                $valor = $opcion['valorDecimal'];
                                $maxValor = $maxOpcion['valorDecimal'];
                                break;
                        }
                        
                        if (array_key_exists($idPregunta, $respuestas)) {
                            $respuestas[$idPregunta] += $valor;
                        }else{
                            $respuestas[$idPregunta] = $valor;
                            $numPreguntas++;
                        }
                        
                        $valorTotal += $valor;
                        $valorMaximo += $maxValor;
                        // ============================================================= Conseguir max puntaje
                        
                    }
                    
                }
            }
            
            $container['respuestas'] = $respuestas;
            $container['total'] = $valorTotal;
            $container['valorMaximo'] = $valorMaximo;
            $container['numPreguntas'] = $numPreguntas;
            return $container;
        }
    }
    
}