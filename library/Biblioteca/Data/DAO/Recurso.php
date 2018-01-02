<?php
class Biblioteca_Data_DAO_Recurso {
    
    private $tableRecurso;
    private $tableTipoRecurso;
    private $tableEstatusRecurso;
    
    private $tableAutor;
    private $tableMaterial;
    private $tableColeccion;
    private $tableClasificacion;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
        //$this->tableTipoRecurso = new Biblioteca_Data_DbTable_TipoRecurso($config);
        $this->tableEstatusRecurso = new Biblioteca_Data_DbTable_EstatusRecurso($config);
        
        $this->tableAutor = new Biblioteca_Data_DbTable_Autor(array('db'=>$dbAdapter));
        $this->tableColeccion =new Biblioteca_Data_DbTable_Coleccion(array('db'=>$dbAdapter));
        $this->tableClasificacion = new Biblioteca_Data_DbTable_Clasificacion(array('db'=>$dbAdapter));
        $this->tableMaterial = new Biblioteca_Data_DbTable_Material(array('db'=>$dbAdapter));
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
    
    public function getRecursoByParams(array $params) {
        $tRecurso = $this->tableRecurso;
        $select = $tRecurso->select()->from($tRecurso);
        $rowsRecursos = "";
        if(!empty($params)){
            foreach ($params as $key => $value) {
                if($key == 'recurso'){
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
}