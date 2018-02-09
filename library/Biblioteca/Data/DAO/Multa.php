<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_Multa {
    
    private $tableMulta;
    private $tableEstatusMulta;
    private $tableTipoMulta;
    
    private $tableInventario;
    private $tablePrestamo;
    private $tableEjemplar;
    private $tableRecurso;
    
    public function __construct($dbAdapter) {
        $config = array('db'=>$dbAdapter);
        
        $this->tableMulta = new Biblioteca_Data_DbTable_Multa($config);
        $this->tableEstatusMulta = new Biblioteca_Data_DbTable_EstatusMulta($config);
        $this->tableTipoMulta = new Biblioteca_Data_DbTable_TipoMulta($config);
        
        $this->tableInventario = new Biblioteca_Data_DbTable_Inventario($config);
        $this->tablePrestamo = new Biblioteca_Data_DbTable_Prestamo($config);
        $this->tableEjemplar = new Biblioteca_Data_DbTable_Ejemplar($config);
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
    }
    
    public function getAllEstatusMulta() {
        $tEM = $this->tableEstatusMulta;
        return $tEM->fetchAll()->toArray();
    }
    
    public function getRowMulta($idMulta) {
        $tM = $this->tableMulta;
        $select = $tM->select()->from($tM)->where('idMulta=?',$idMulta);
        $rowM = $tM->fetchRow($select);
        
        return $rowM->toArray();
    }
    
    public function getObjectMulta($idMulta) {
        $arrMulta = $this->getRowMulta($idMulta);
        
        $obj = array();
        $obj['multa'] = $arrMulta;
        // Agregando estatus multa
        $tEM = $this->tableEstatusMulta;
        $select = $tEM->select()->from($tEM)->where('idEstatusMulta=?',$arrMulta['idEstatusMulta']);
        $rowEMulta = $tEM->fetchRow($select)->toArray();
        $obj['estatus'] = $rowEMulta;
        // Agregando tipo multa
        $tTM = $this->tableTipoMulta;
        $select = $tTM->select()->from($tTM)->where('idTipoMulta=?',$arrMulta['idTipoMulta']);
        $rowTMulta = $tTM->fetchRow($select)->toArray();
        $obj['tipo'] = $rowTMulta;
        
        // Agregando prestamo
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)->where('idPrestamo=?',$arrMulta['idPrestamo']);
        $rowP = $tP->fetchRow($select)->toArray();
        $obj['prestamo'] = $rowP;
        // Agregando inventario
        $tI = $this->tableInventario;
        $select = $tI->select()->from($tI)->where('idInventario=?',$rowP['idInventario']);
        $rowI = $tI->fetchRow($select)->toArray();
        // Agregando ejemplar
        $tE = $this->tableEjemplar;
        $select = $tE->select()->from($tE)->where('idEjemplar=?',$rowI['idEjemplar']);
        $rowE = $tE->fetchRow($select)->toArray();
        // Agregando recurso
        $tR = $this->tableRecurso;
        $select = $tR->select()->from($tR)->where('idRecurso=?',$rowE['idRecurso']);
        $rowRecurso = $tR->fetchRow($select)->toArray();
        $obj['recurso'] = $rowRecurso;
        
        return $obj;
    }
    
    public function getMultaByIdPrestamo($idPrestamo){
        $tEM = $this->tableEstatusMulta;
        $select = $tEM->select()->from($tEM)->where('estatus=?','ACTIVA');
        $rowEM = $tEM->fetchRow($select)->toArray();
        
        $tM = $this->tableMulta;
        $select = $tM->select()->from($tM)->where('idPrestamo=?',$idPrestamo)
            ->where('idEstatusMulta=?',$rowEM['idEstatusMulta']);
        
        $rowM = $tM->fetchRow($select);
        
        if (is_null($rowM)) {
            return null;
        }else {
            $rowM->toArray();
            return $this->getObjectMulta($rowM['idMulta']) ;
        }
    }
    
    public function getMultasByEstatusMulta($idEstatusMulta) {
        $tM = $this->tableMulta;
        $select = $tM->select()->from($tM)->where('idEstatusMulta=?',$idEstatusMulta);
    }
    
    public function addMulta($idPrestamo) {
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)->where('idPrestamo=?',$idPrestamo);
        $rowP = $tP->fetchRow($select)->toArray();
        
        $tEM = $this->tableEstatusMulta;
        $select = $tEM->select()->from($tEM)->where('estatus=?','ACTIVA');
        $rowEM = $tEM->fetchRow($select)->toArray();
        
        $tTM = $this->tableTipoMulta;
        $select = $tTM->select()->from($tTM)->where('tipoMulta=?','MULTA_LIBRO');
        $rowTM = $tTM->fetchRow($select)->toArray();
        
        $fechaPrestamo = new DateTime($rowP['fechaPrestamo']);
        $fechaDevolucion = new DateTime($rowP['fechaDevolucion']);
        $fechaHoy = new DateTime('now');
        
        //echo $fechaHoy->format('Y-m-d');
        print_r('<br /><br />');
        
        $diasMulta = $fechaHoy->diff($fechaDevolucion)->format('%R%a');
        //print_r($diasMulta); 
        /*
        print_r('<br /><br />');
        $diferencia = $fechaDevolucion->diff($fechaPrestamo);
        //$diferencia = $fechaPrestamo->diff($fechaDevolucion);
        $dias = $diferencia->format('%R%a');
        print_r($dias);
        //print_r($diferencia->format('%R%a'));
        //print_r($diferencia->days);
        */
        $dataMulta = array(
            'idTipoMulta' => $rowTM['idTipoMulta'],
            'idEstatusMulta' => $rowEM['idEstatusMulta'],
        );
        
        
        if ($diasMulta <= 0 ) {
            // No se agrega Multa
        }else{
            $dMulta = abs($diasMulta);
            // Se agrega multa
            
            
            
        }
    }
    
    public function pagarMulta($idMulta) {
        $tM = $this->tableMulta;
        
        $tEM = $this->tableEstatusMulta;
        $select = $tEM->select()->from($tEM)->where('estatus=?','PAGADA');
        $rowEM = $tEM->fetchRow($select)->toArray();
        
        $data = array(
            'idEstatusMulta' => $rowEM['idEstatusMulta']
        );
        
        $where = $tM->getAdapter()->quoteInto('idMulta=?', $idMulta);
        
        return $tM->update($data, $where);
    }
    
}
