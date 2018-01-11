<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_Prestamo {
    
    private $tablePrestamo;
    private $tableRecurso;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tablePrestamo = new Biblioteca_Data_DbTable_Prestamo($config);
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
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
     * @param unknown $prestamos
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