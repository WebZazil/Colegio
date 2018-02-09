<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_Prestamo {
    
    private $tablePrestamo;
    private $tableRecurso;
    private $tableInventario;
    private $tableEstatusPrestamo;
    private $tableEjemplar;
    
    private $tableMulta;
    private $tableTipoMulta;
    private $tableEstatusMulta;
    private $tableEstatusEjemplar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePrestamo = new Biblioteca_Data_DbTable_Prestamo($config);
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
        $this->tableInventario = new Biblioteca_Data_DbTable_Inventario($config);
        $this->tableEjemplar = new Biblioteca_Data_DbTable_Ejemplar($config);
        
        $this->tableEstatusPrestamo = new Biblioteca_Data_DbTable_EstatusPrestamo($config);
        $this->tableEstatusEjemplar = new Biblioteca_Data_DbTable_EstatusEjemplar($config);
        
        $this->tableEstatusMulta = new Biblioteca_Data_DbTable_EstatusMulta($config);
        $this->tableTipoMulta = new Biblioteca_Data_DbTable_TipoMulta($config);
        $this->tableMulta = new Biblioteca_Data_DbTable_Multa($config);
    }
    
    public function getEstatusPrestamoById($idEstatusPrestamo) {
        $tEP = $this->tableEstatusPrestamo;
        $select = $tEP->select()->from($tEP)->where('idEstatusPrestamo=?',$idEstatusPrestamo);
        $rowEstatus = $tEP->fetchRow($select)->toArray();
        
        return $rowEstatus;
    }
    
    public function getEstatusPrestamoByEstatus($estatus) {
        $tEP = $this->tableEstatusPrestamo;
        $select = $tEP->select()->from($tEP)->where('estatusPrestamo=?',$estatus);
        $rowEstatus = $tEP->fetchRow($select)->toArray();
        
        return $rowEstatus;
    }
    
    public function getAllEstatusPrestamo() {
        $tEP = $this->tableEstatusPrestamo;
        return $tEP->fetchAll()->toArray();
    }
    
    public function setEstatusInventarioEjemplar($idInventario, $estatus) {
        $tEE = $this->tableEstatusEjemplar;
        $select = $tEE->select()->from($tEE)->where('estatusEjemplar=?',$estatus);
        $rowE = $tEE->fetchRow($select);
        
        $tI = $this->tableInventario;
        $where = $tI->getAdapter()->quoteInto('idInventario=?', $idInventario);
        $data = array('idEstatusEjemplar'=>$rowE['idEstatusEjemplar']);
        
        return $tI->update($data, $where);
    }
    
    /**
     * 
     * @param int $idUsuario
     * @return array
     */
    public function getPrestamosUsuario($idUsuario) {
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)->where('idUsuario=?',$idUsuario);
        $rowsPrestamos = $tP->fetchAll($select);
        
        return $rowsPrestamos->toArray();
    }
    
    /**
     * 
     * @param int $idUsuario
     * @return array[][]|NULL[][]|mixed[][]
     */
    public function getObjectPrestamosUsuario($idUsuario, $idEstatus = 1) {
        $tP = $this->tablePrestamo;
        
        $select = $tP->select()->from($tP)->where('idUsuario=?',$idUsuario)
            ->where('idEstatusPrestamo = ?', $idEstatus);
        
        $rowsPrestamos = $tP->fetchAll($select)->toArray();
        
        foreach ($rowsPrestamos as $rowPrestamo){
            $this->validarPrestamo($rowPrestamo['idPrestamo']);
        }
        // Traemos de nuevo los registros despues de la validacion, ya con datos actualizados
        $rowsPrestamos = $tP->fetchAll($select)->toArray();
        
        $tEP = $this->tableEstatusPrestamo;
        $tI = $this->tableInventario;
        $tE = $this->tableEjemplar;
        $tR = $this->tableRecurso;
        
        $contenedor = array();
        
        foreach ($rowsPrestamos as $rowPrestamo){
            $obj = array();
            
            $obj['prestamo'] = $rowPrestamo;
            // Agregamos Estatus Prestamo
            $select = $tEP->select()->from($tEP)->where('idEstatusPrestamo=?',$rowPrestamo['idEstatusPrestamo']);
            $rowEP = $tEP->fetchRow($select)->toArray();
            $obj['estatus'] = $rowEP;
            // Agregamos Copia de Inventario
            $select = $tI->select()->from($tI)->where('idInventario=?',$rowPrestamo['idInventario']);
            $rowI = $tI->fetchRow($select)->toArray();
            $obj['copia'] = $rowI;
            // Agregamos Ejemplar relacionado
            $select = $tE->select()->from($tE)->where('idEjemplar=?',$rowI['idEjemplar']);
            $rowE = $tE->fetchRow($select)->toArray();
            $obj['ejemplar'] = $rowE;
            // Agregamos Recurso relacionado
            $select = $tR->select()->from($tR)->where('idRecurso=?',$rowE['idRecurso']);
            $rowR = $tR->fetchRow($select)->toArray();
            $obj['recurso'] = $rowR;
            
            $contenedor[] = $obj;
        }
        
        return $contenedor;
    }
    
    /**
     * 
     * @param array $datosPrestamo
     * @return mixed|array
     */
    public function agregarPrestamoUsuario($datosPrestamo) {
        $tP = $this->tablePrestamo;
        return $tP->insert($datosPrestamo);
    }
    
    public function buscarRecurso($token) {
        $tR = $this->tableRecurso;
    }
    
    /**
     * 
     * @param array $prestamos
     */
    public function procesarPrestamos($prestamos) {
        $tP = $this->tablePrestamo;
        
        foreach ($prestamos as $prestamo) {
            $fechaActual = date('Y-m-d',time());
            
            $fechaDevolucion = strtotime($prestamo['fechaDevolucion']);
            //$fechaVencimiento = date('Y-m-d', $prestamo['fechaVencimiento']);
            $diferencia = date_diff($fechaActual, $fechaDevolucion);
            
            echo $diferencia->format("%R%a days");
            print_r('<br /><br />');
        }
    }
    
    /**
     * 
     * @param int $idPrestamo
     * @return array
     */
    public function validarPrestamo($idPrestamo) {
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)->where('idPrestamo=?',$idPrestamo);
        $rowPrestamo = $tP->fetchRow($select)->toArray();
        
        $fechaPrestamo = new DateTime($rowPrestamo['fechaPrestamo']);
        $fechaDevolucion = new DateTime($rowPrestamo['fechaDevolucion']);
        $hoy = new DateTime('now');
        
        $diasMulta = $hoy->diff($fechaDevolucion)->format('%R%a');
        //print_r($diasMulta); print_r('<br /><br />');
        
        if ($diasMulta >= 0 ) { // No hay multa, entrega en x dias restantes
            //print_r('No hay multa, prestamo vigente'); print_r('<br /><br />');
        }else{ // Hay multa de x dias
            //print_r('Hay multa, prestamo caduco'); print_r('<br /><br />');
            // Verificamos si no ya esta dada de alta
            $tM = $this->tableMulta;
            $select = $tM->select()->from($tM)->where('idPrestamo=?',$idPrestamo);
            $rowM = $tM->fetchRow($select);
            
            if (is_null($rowM)) { // No hay multa aun agregada
                $diasMulta = abs($diasMulta);
                $tTM = $this->tableTipoMulta;
                $select = $tTM->select()->from($tTM)->where('tipoMulta=?','MULTA_LIBRO');
                $rowTM = $tTM->fetchRow($select)->toArray();
                
                $tEM = $this->tableEstatusMulta;
                $select = $tEM->select()->from($tEM)->where('estatus=?','ACTIVA');
                $rowEM = $tEM->fetchRow($select)->toArray();
                
                $importeMulta = $diasMulta * $rowTM['importeUnitario'];
                
                $arrMulta = array(
                    'idPrestamo' => $rowPrestamo['idPrestamo'],
                    'idTipoMulta' => $rowTM['idTipoMulta'],
                    'idEstatusMulta' => $rowEM['idEstatusMulta'],
                    'importe' => $importeMulta,
                    'creacion' => date('Y-m-d H:i:s', time())
                );
                
                $idMulta = $tM->insert($arrMulta);
                $select = $tM->select()->from($tM)->where('idMulta=?',$idMulta);
                $rowM = $tM->fetchRow($select);
                // Actualizamos estatus del prestamo
                $tEP = $this->tableEstatusPrestamo;
                $select = $tEP->select()->from($tEP)->where('estatusPrestamo=?','VENCIMIENTO');
                $rowEP = $tEP->fetchRow($select)->toArray();
                
                $arrPrestamo = array(
                    'idEstatusPrestamo' => $rowEP['idEstatusPrestamo']
                );
                
                //$where = $tP->select()->from($tP)->where('idPrestamo=?',$idPrestamo);
                $where = $tP->getAdapter()->quoteInto('idPrestamo=?', $idPrestamo);
                $tP->update($arrPrestamo, $where);
                
            }
            
            return $rowM->toArray();
        }
        
    }
    
    /**
     * 
     * @param int $idPrestamo
     * @return array
     */
    public function getRowPrestamoById($idPrestamo){
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)->where('idPrestamo=?',$idPrestamo);
        $rowP = $tP->fetchRow($select);
        
        return $rowP->toArray();
    }
    
    public function getPrestamoByIdCopia($idInventario) {
        $tP = $this->tablePrestamo;
        $select = $tP->select()->from($tP)->where('idInventario=?',$idInventario);
        $rowP = $tP->fetchRow($select);
        
        return $rowP->toArray();
    }
    
    /**
     * 
     * @param int $idPrestamo
     */
    public function devolverPrestamo($idPrestamo) {
        print_r('idPrestamo: <strong>'.$idPrestamo.'</strong>');
        $tEP = $this->tableEstatusPrestamo;
        $select = $tEP->select()->from($tEP)->where('estatusPrestamo=?','ENTREGADO');
        $rowEP = $tEP->fetchRow($select)->toArray();
        
        $tP = $this->tablePrestamo;
        $where = $tP->getAdapter()->quoteInto('idPrestamo=?',$idPrestamo);
        
        $data = array(
            'idEstatusPrestamo'=>$rowEP['idEstatusPrestamo'],
            'fechaVencimiento' => date('Y-m-d',time()),
        );
        
        $tP->update($data, $where);
        
    }
    
}