<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Biblioteca_Data_DAO_Inventario {
    
    private $tableInventario;
    private $tableEjemplar;
    private $tableRecurso;
    
    private $tableTipoLibro;
    private $tableEditorial;
    private $tableIdioma;
    private $tablePais;
    
    private $tableMaterial;
    private $tableColeccion;
    private $tableClasificacion;
    private $tableAutor;
    private $tableSerieEjemplar;
    
    public function __construct($dbAdapter) {
        $config = array('db' => $dbAdapter);
        
        $this->tableInventario = new Biblioteca_Data_DbTable_Inventario($config);
        $this->tableEjemplar = new Biblioteca_Data_DbTable_Ejemplar($config);
        $this->tableRecurso = new Biblioteca_Data_DbTable_Recurso($config);
        
        $this->tableTipoLibro = new Biblioteca_Data_DbTable_TipoLibro($config);
        $this->tableEditorial = new Biblioteca_Data_DbTable_Editorial($config);
        $this->tableIdioma = new Biblioteca_Data_DbTable_Idioma($config);
        $this->tablePais = new Biblioteca_Data_DbTable_Pais($config);
        
        $this->tableMaterial = new Biblioteca_Data_DbTable_Material($config);
        $this->tableColeccion = new Biblioteca_Data_DbTable_Coleccion($config);
        $this->tableClasificacion = new Biblioteca_Data_DbTable_Clasificacion($config);
        $this->tableAutor = new Biblioteca_Data_DbTable_Autor($config);
        $this->tableSerieEjemplar = new Biblioteca_Data_DbTable_SeriesEjemplar($config);
    }
    
    /**
     * Obtiene objetos recurso
     * @param int $idRecurso
     * @return array[]
     */
    public function getObjectRecurso($idRecurso) {
        $tR = $this->tableRecurso;
        $select = $tR->select()->from($tR)->where('idRecurso=?',$idRecurso);
        $rowRecurso = $tR->fetchRow($select)->toArray();
        
        $obj = array();
        $obj['recurso'] = $rowRecurso;
        # Obtenemos Material Asociado
        $tM = $this->tableMaterial;
        $select = $tM->select()->from($tM)->where('idMaterial=?',$rowRecurso['idMaterial']);
        $rowMaterial = $tM->fetchRow($select)->toArray();
        $obj['material'] = $rowMaterial;
        # Obtenemos Coleccion Asociada
        $tCol = $this->tableColeccion;
        $select = $tCol->select()->from($tCol)->where('idColeccion=?',$rowRecurso['idColeccion']);
        $rowColeccion = $tCol->fetchRow($select)->toArray();
        $obj['coleccion'] = $rowColeccion;
        # Obtenemos Clasificacion Asociada
        $tCla = $this->tableClasificacion;
        $select = $tCla->select()->from($tCla)->where('idClasificacion=?',$rowRecurso['idClasificacion']);
        $rowClasificacion = $tCla->fetchRow($select)->toArray();
        $obj['clasificacion'] = $rowClasificacion;
        # Obtenemos Autores Asociados
        $tA = $this->tableAutor;
        $idAutor = $rowRecurso['idAutor'];
        $select = $tA->select()->from($tA)->where('idAutor = ?', $idAutor);
        $rowsAutores = $tA->fetchAll($select)->toArray();
        $obj['autor'] = $rowsAutores;
        
        $tE = $this->tableEjemplar;
        $select = $tE->select()->from($tE)->where('idRecurso=?', $idRecurso);
        $rowsEjemplar = $tE->fetchAll($select)->toArray();
        $objsEjemplar = array();
        
        foreach ($rowsEjemplar as $rowEjemplar){
            $objEjemplar = $this->getObjectEjemplar($rowEjemplar['idEjemplar']);
            $objsEjemplar[] = $objEjemplar;
        }
        
        $obj['ejemplares'] = $objsEjemplar;
        
        //$rowsEjemplar = 
        
        return $obj;
    }
    
    function getObjectEjemplar($idEjemplar) {
        $tE = $this->tableEjemplar;
        $select = $tE->select()->from($tE)->where('idEjemplar=?',$idEjemplar);
        $rowEjemplar = $tE->fetchRow($select)->toArray();
        
        $obj = array();
        $obj['ejemplar'] = $rowEjemplar;
        # Obtenemos Recurso Asociado
        $tR = $this->tableRecurso;
        $select = $tR->select()->from($tR)->where('idRecurso=?',$rowEjemplar['idRecurso']);
        $rowRecurso = $tR->fetchRow($select)->toArray();
        $obj['recurso'] = $rowRecurso;
        # Obtenemos TipoLibro Asociado
        $tTL = $this->tableTipoLibro;
        $select = $tTL->select()->from($tTL)->where('idTipoLibro=?',$rowEjemplar['idTipoLibro']);
        $rowTipoLibro = $tTL->fetchRow($select)->toArray();
        $obj['tipoLibro'] = $rowTipoLibro;
        # Obtenemos Editorial Asociada
        $tEd = $this->tableEditorial;
        $select = $tEd->select()->from($tEd)->where('idEditorial=?',$rowEjemplar['idEditorial']);
        $rowEditorial = $tEd->fetchRow($select)->toArray();
        $obj['editorial'] = $rowEditorial;
        # Obtenemos Idioma Asociado
        $tI = $this->tableIdioma;
        $select = $tI->select()->from($tI)->where('idIdioma=?',$rowEjemplar['idIdioma']);
        $rowIdioma = $tI->fetchRow($select)->toArray();
        $obj['idioma'] = $rowIdioma;
        # Obtenemos Pais Asociado
        $tP = $this->tablePais;
        $select = $tP->select()->from($tP)->where('idPais=?',$rowEjemplar['idPais']);
        $rowPais = $tP->fetchRow($select)->toArray();
        $obj['pais'] = $rowPais;
        # Obtenemos Serie Asociada
        $tSE = $this->tableSerieEjemplar;
        $select = $tSE->select()->from($tSE)->where('idSeriesEjemplar=?',$rowEjemplar['idSeriesEjemplar']);
        $rowSeriesE = $tSE->fetchRow($select)->toArray();
        $obj['seriesEjemplar'] = $rowSeriesE;
        
        # Obtenemos Ejemplares
        $obj['ejemplares'] = $this->getObjectsInventario($rowEjemplar['idEjemplar']);
        
        return $obj;
    }
    
    function getObjectsInventario($idEjemplar) {
        $tI = $this->tableInventario;
        $select = $tI->select()->from($tI)->where('idEjemplar=?',$idEjemplar);
        $rowsInventario = $tI->fetchAll($select)->toArray();
        
        //$objs = array();
        return $rowsInventario;
    }
    
}