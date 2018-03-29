<?php
class Biblioteca_Data_DAO_Recurso {
    
    private $tableRecurso;
    private $tableTipoRecurso;
    // private $tableEstatusRecurso;
    
    private $tableAutor;
    private $tableMaterial;
    private $tableColeccion;
    private $tableClasificacion;
    
    public function __construct($dbAdapter) {
        
        $config = array('db' => $dbAdapter);
        
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
        //$this->tableEstatusRecurso = new Biblioteca_Data_DbTable_EstatusRecurso($config);
        
        $this->tableAutor = new Biblioteca_Data_DbTable_Autor($config);
        $this->tableColeccion =new Biblioteca_Data_DbTable_Coleccion($config);
        $this->tableClasificacion = new Biblioteca_Data_DbTable_Clasificacion($config);
        $this->tableMaterial = new Biblioteca_Data_DbTable_Material($config);
    }
    
    public function getEstatusRecurso() {
        return $this->tableEstatusRecurso->fetchAll()->toArray();
    }
    
    public function getRecursosByName($tokenName) {
        $tR = $this->tableRecurso;
        $select = $tR->select()->from($tR)->where('recurso like ?','%'.$tokenName.'%');
        $rowsRecursos = $tR->fetchAll($select);
        
        return $rowsRecursos->toArray();
    }
    
    public function setEstatusRecurso($estatus,$idRecurso) {
        $teR = $this->tableEstatusRecurso;
        $select = $teR->select()->from($teR)->where('estatusRecurso=?',$estatus);
        $rowEstatus = $teR->fetchRow($select)->toArray();
        
        $tR = $this->tableRecurso;
        $where = $tR->getAdapter()->quoteInto('idRecurso=?', $idRecurso);
        $data = array('idEstatusRecurso' => $rowEstatus['idEstatusRecurso']);
        $tR->update($data, $where);
    }
    
    public function getAllRecursos() {
        
        $tablaRecurso = $this->tableRecurso;
        $rowsRecurso = $tablaRecurso->fetchAll();
        
        if(!is_null($rowsRecurso)){
            $arrRecursos = $rowsRecurso->toArray();
            $arrModelRecurso = array();
            foreach ($arrRecursos as $arrRecurso) {
                $modelRecurso = new Biblioteca_Model_Recurso($arrRecurso);
                $arrModelRecurso[] = $modelRecurso;
            }
            return $arrModelRecurso;
        }else{
            return array();
        }
    }
    
    /**
     * @TODO cambiar nombre de metodo a getAllRecursos
     * @return array
     */
    public function getAllTableRecursos(){
        
        $tablaRecurso = $this->tableRecurso;
        $rowsRecurso = $tablaRecurso->fetchAll();
        
        return $rowsRecurso->toArray();
    }
    
    public function getRecursoById($idRecurso){
        $tR = $this->tableRecurso;
        $select = $tR->select()->from($tR)->where('idRecurso=?',$idRecurso);
        $rowRecurso = $tR->fetchRow($select);
        
        return $rowRecurso->toArray();
    }
    
    /**
     * Obtenemos los registros de la T.Recurso mediante los parametros
     * enviados por $params
     * @param array $params
     * @return array
     */
    public function getRecursoByParams(array $params) {
        $tRecurso = $this->tableRecurso;
        $select = $tRecurso->select()->from($tRecurso);
        $rowsRecursos = "";
        if(!empty($params)){
            foreach ($params as $key => $value) {
                if($key == 'titulo' || $key == 'subtitulo'){
                    $select->where($key.' LIKE ?',"%{$value}%");
                }else{
                    $select->where($key."=?", $value);
                }
            }
            $rowsRecursos = $tRecurso->fetchAll($select);
        }else{
            $rowsRecursos = $tRecurso->fetchAll();
        }
        
        return $rowsRecursos->toArray();
    }
    
    /**
     * 
     * @param array $datos
     * @return mixed|array
     */
    public function agregarRecurso($datos) {
        $tR = $this->tableRecurso;
        return $tR->insert($datos);
    }
    // ========================================================================================= >> Mejoras 5 enero 2018
    
    /**
     * 
     * @param int $idRecurso recurso a buscar en tabla
     * @return array que contiene el 
     */
    public function getObjectRecurso($idRecurso){
        $contenedor = array();
        
        $rowRecurso = $this->getRecursoById($idRecurso);
        $contenedor['recurso'] = $rowRecurso;
        # Table Material
        $tM = $this->tableMaterial;
        $select = $tM->select()->from($tM)->where('idMaterial=?',$rowRecurso['idMaterial']);
        $rowMaterial = $tM->fetchRow($select)->toArray();
        $contenedor['material'] = $rowMaterial;
        # Table Coleccion
        $tColeccion = $this->tableColeccion;
        $select = $tColeccion->select()->from($tColeccion)->where('idColeccion=?',$rowRecurso['idColeccion']);
        $rowColeccion = $tColeccion->fetchRow($select)->toArray();
        $contenedor['coleccion'] = $rowColeccion;
        # Table Clasificacion
        $tClasificacion = $this->tableClasificacion;
        $select = $tClasificacion->select()->from($tClasificacion)->where('idClasificacion=?',$rowRecurso['idClasificacion']);
        $rowClasificacion = $tClasificacion->fetchRow($select)->toArray();
        $contenedor['clasificacion'] = $rowClasificacion;
        # Table Autor
        $tAutor = $this->tableAutor;
        $select = $tAutor->select()->from($tAutor)->where('idAutor=?',$rowRecurso['idAutor']);
        $rowAutor = $tAutor->fetchRow($select)->toArray();
        $contenedor['autor'] = $rowAutor;
        
        return $contenedor;
    }
    
    public function getObjectsRecurso() {
        $tR = $this->tableRecurso;
        $rowsRecursos = $tR->fetchAll()->toArray();
        
        $tCol = $this->tableColeccion;
        $tCla = $this->tableClasificacion;
        $tM = $this->tableMaterial;
        $tA = $this->tableAutor;
        
        $container = array();
        
        foreach ($rowsRecursos as $rowRecurso) {
            $obj = array();
            $obj['recurso'] = $rowRecurso;
            #Obtenemos Coleccion;
        }
    }
    
    private function getTablesItems($tableName, $index, $indexVal) {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $select = $db->select()->from($tableName)->where($index.'=?',$indexVal);
        $rows = $db->fetchAll($select)->toArray();
        
        return $rows;
    }
    
}