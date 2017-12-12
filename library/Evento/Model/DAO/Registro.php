<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Evento_Model_DAO_Registro {
    
    private $tableAsistente;
    private $tableAsistentesEvento;
    
    function __construct($dbAdapter) {
        $this->tableAsistente = new Evento_Model_DbTable_Asistente(array("db"=>$dbAdapter));
        $this->tableAsistentesEvento = new Evento_Model_DbTable_AsistentesEvento(array("db"=>$dbAdapter));
    }
    
    public function getAsistenteById($idAsistente) {
        $tAsis = $this->tableAsistente;
        $select = $tAsis->select()->from($tAsis)->where("id=?",$idAsistente);
        $rowAsis = $tAsis->fetchRow($select);
        
        return $rowAsis->toArray();
    }
    
    public function getAsistentesByParams($params) {
        $tAsis = $this->tableAsistente;
        $select = $tAsis->select()->from($tAsis);
        
        foreach ($params as $col => $val){
            $select->where($col."=?",$val);
        }
        
        print_r($select->__toString());
    }
    
    public function saveAsistente(array $datos) {
        $tAsis = $this->tableAsistente;
        $idAsistente = $tAsis->insert($datos);
        
        return $idAsistente;
    }
    
    public function saveAsistenteEvento($idAsistente, $idEvento) {
        $tAE = $this->tableAsistentesEvento;
        $select = $tAE->select()->from($tAE)->where("idEvento=?",$idEvento)->where("idAsistente=?",$idAsistente);
        $rowsAE = $tAE->fetchRow($select);
        
        $tAsis = $this->tableAsistente;
        $select = $tAsis->select()->from($tAsis)->where("id=?",$idAsistente);
        $rowAsistente = $tAsis->fetchRow($select);
        
        $data = array();
        $data['idEvento'] = $idEvento;
        $data['idAsistente'] = $idAsistente;
        $data['clave'] = $rowAsistente->clave;
        $data['creacion'] = date('Y-m-d H:i:s', time());
        
        $tAE->insert($data);
    }
    
}