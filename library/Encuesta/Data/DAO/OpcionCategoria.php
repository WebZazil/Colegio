<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_OpcionCategoria {
    
    private $tableCategoriaRespuesta;
    private $tableOpcionCategoria;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableOpcionCategoria = new Encuesta_Data_DbTable_OpcionCategoria($config);
        $this->tableCategoriaRespuesta = new Encuesta_Data_DbTable_CategoriasRespuesta($config);
    }
    
    public function getOpcionById($idOpcion){
        $tOC = $this->tableOpcionCategoria;
        $select = $tOC->select()->from($tOC)->where('idOpcionCategoria=?',$idOpcion);
        $rowOC = $tOC->fetchRow($select);
        
        return $rowOC->toArray();
    }
    
    public function getOpcionesByIdCategoria($idCategoria) {
        $tOC = $this->tableOpcionCategoria;
        $select = $tOC->select()->from($tOC)->where('idCategoriasRespuesta=?',$idCategoria);
        $rowOC = $tOC->fetchAll($select);
        
        return $rowOC->toArray();
    }
    
    public function addOpcionCategoria($datos) {
        $rowsOC = $this->getOpcionesByIdCategoria($datos['idCategoriasRespuesta']);
        $orden = count($rowsOC);
        
        $datos['orden'] = $orden+1;
        $tOC = $this->tableOpcionCategoria;
        $idOpcionCategoria = $tOC->insert($datos);
        
        $tCR = $this->tableCategoriaRespuesta;
        $select = $tCR->select()->from($tCR)->where('idCategoriasRespuesta=?',$datos['idCategoriasRespuesta']);
        $rowCategoria = $tCR->fetchRow($select);
        
        $idsOC = explode(',', $rowCategoria->idsOpciones);
        
        if(!in_array($idOpcionCategoria, $idsOC)){
            $idsOC[] = $idOpcionCategoria;
            $rowCategoria->idsOpciones = implode(',', $idsOC);
            $rowCategoria->save();
        }
        
    }
    
    public function updateOpcion($idOpcion, $datos) {
        $tOC = $this->tableOpcionCategoria;
        $where = $tOC->getAdapter()->quoteInto('idOpcionCategoria=?', $idOpcion);
        $tOC->update($datos, $where);
    }
    
    /**
     * Re-Ordena las opciones de la categoria en base a la fecha de alta
     * @param int $idCategoria
     */
    public function reordenarOpciones($idCategoria) {
        $tOC = $this->tableOpcionCategoria;
        $select = $tOC->select()->from($tOC)->where('idCategoriasRespuesta=?',$idCategoria)->order(array('fecha ASC'));
        $rowsOC = $tOC->fetchAll($select);
        // print_r($select->__toString());
        $orden = 1;
        foreach ($rowsOC as $rowOC){
            $rowOC->orden = $orden;
            $rowOC->save();
            $orden++;
        }
    }
    
    /**
     * En usos avanzados de opciones puede generarnos error y
     * no permitir el borrado de opciones en respuestas de
     * encuestas que ya esten usando los valores de esta opcion.
     * @param int $idOpcion
     */
    public function eliminarOpcion($idOpcion) {
        $tOC = $this->tableOpcionCategoria;
        $where = $tOC->getAdapter()->quoteInto('idOpcionCategoria=?', $idOpcion);
        $tOC->delete($where);
    }
    
}