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
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePrestamo = new Biblioteca_Data_DbTable_Prestamo($config);
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
        $this->tableInventario = new Biblioteca_Data_DbTable_Inventario($config);
        
        $this->tableEstatusPrestamo = new Biblioteca_Data_DbTable_EstatusPrestamo($config);
    }
    
    public function getEstatusPrestamoByEstatus($estatus) {
        $tEP = $this->tableEstatusPrestamo;
        $select = $tEP->select()->from($tEP)->where('estatusPrestamo=?',$estatus);
        $rowEstatus = $tEP->fetchRow($select)->toArray();
        
        return $rowEstatus;
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
    
    public function getObjectPrestamosUsuario($idUsuario) {
        $tP = $this->tablePrestamo;
        $tEP = $this->tableEstatusPrestamo;
        $select = $tP->select()->from($tP)->where('idUsuario=?',$idUsuario);
        $rowsPrestamos = $tP->fetchAll($select);
        
        $contenedor = array();
        
        foreach ($rowsPrestamos as $rowPrestamo){
            $obj = array();
            $obj['prestamo'] = $rowPrestamo;
            $select = $tEP->select()->from($tEP)->where('idEstatusPrestamo=?',$rowPrestamo['idEstatusPrestamo']);
            $rowEP = $tEP->fetchRow($select)->toArray();
            $obj['estatus'] = $rowEP;
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
    
    
   
    
}