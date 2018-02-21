<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Encuesta_Data_DAO_Categoria implements Encuesta_Data_DAO_Interface_ICategoria {
    
    private $tableCategoriasRespuesta;
    private $tableOpcionCategoria;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableCategoriasRespuesta = new Encuesta_Data_DbTable_CategoriasRespuesta($config);
        $this->tableOpcionCategoria = new Encuesta_Data_DbTable_OpcionCategoria($config);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see Encuesta_Data_DAO_Interface_ICategoria::getAllCategorias()
     */
    public function getAllCategorias() {
        return $this->tableCategoriasRespuesta->fetchAll()->toArray();
    }
    
    public function addCategoria($datos) {
        $tC = $this->tableCategoriasRespuesta;
        return $tC->insert($datos);
    }

    public function getMaxOpcionCategoria($idCategoria, $tipoVal) {
        
    }

    public function getCategoriaById($idCategoria) {
        $tCR = $this->tableCategoriasRespuesta;
        $select = $tCR->select()->from($tCR)->where('idCategoriasRespuesta=?', $idCategoria);
        $rowCategoria = $tCR->fetchRow($select);
        
        return $rowCategoria->toArray();
    }

    public function getMinOpcionCategoria($idCategoria, $tipoVal) {
       $tOp = $this->tableOpcionCategoria;
       $select = $tOp->select()->from($tOp)->where('idCategoriasRespuesta=?',$idCategoria)->where('tipoValor=?',$tipoVal);
       $rowsOpcion = $tOp->fetchAll($select)->toArray();
       
       
       
       //return $rowsOpcion->toArray();
    }

    /**
     * 
     * {@inheritDoc}
     * @see Encuesta_Data_DAO_Interface_ICategoria::getOpcionesCategoria()
     */
    public function getOpcionesCategoria($idCategoria) {
        $tOp = $this->tableOpcionCategoria;
        $select = $tOp->select()->from($tOp)->where('idCategoriasRespuesta=?',$idCategoria);
        $rowsOpciones = $tOp->fetchAll($select);
        
        return $rowsOpciones->toArray();
    }

    /**
     * 
     * {@inheritDoc}
     * @see Encuesta_Data_DAO_Interface_ICategoria::getOpcionById()
     */
    public function getOpcionById($idOpcion) {
        $tOp = $this->tableOpcionCategoria;
        $select = $tOp->select()->from($tOp)->where('idOpcionCategoria=?',$idOpcion);
        $rowOpcion = $tOp->fetchRow($select);
        
        return $rowOpcion->toArray();
    }
    
    public function normalizeCategoria($idCategoria) {
        $tOC = $this->tableOpcionCategoria;
        $select = $tOC->select()->from($tOC)->where('idCategoriasRespuesta=?',$idCategoria);
        $rowsOC = $tOC->fetchAll($select)->toArray();
        
        $ids = array();
        
        foreach ($rowsOC as $rowOC){
            $ids[] = $rowOC['idOpcionCategoria'];
        }
        
       
        
        $tCR = $this->tableCategoriasRespuesta;
        $select = $tCR->select()->from($tCR)->where('idCategoriasRespuesta',$idCategoria);
        $rowCR = $tCR->fetchRow($select);
        $rowCR->idsOpciones = implode(',', $ids);
        $rowCR->save();
    }
    
    public function updateCategoria($idCategoria,$datos) {
        $tCR = $this->tableCategoriasRespuesta;
        $where = $tCR->getAdapter()->quoteInto('idCategoriasRespuesta=?', $idCategoria);
        $tCR->update($datos, $where);
    }

}