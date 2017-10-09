<?php

/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Soporte_DAO_Equipo {
	
    private $tableEquipo2;
    
    private $tableEquipo;
    private $tableTipo;
    private $tableUbicacion;
    private $tableUsuario;
    
    private $tableSisOpera;
    private $tableOffice;
    private $tableAntivirus;
    
	function __construct($dbAdapter) {
	    
		$this->tableEquipo2 = new Soporte_Models_DbTable_Equipo2(array("db"=>$dbAdapter));
		
		$this->tableEquipo = new Soporte_Models_DbTable_Equipo(array("db"=>$dbAdapter));
		$this->tableTipo = new Soporte_Models_DbTable_TipoEquipo(array("db"=>$dbAdapter));
		$this->tableUbicacion = new Soporte_Models_DbTable_Ubicacion(array("db"=>$dbAdapter));
		$this->tableUsuario = new Soporte_Models_DbTable_Usuario(array("db"=>$dbAdapter));
		
		$this->tableSisOpera = new Soporte_Models_DbTable_SistemaOperativo(array("db"=>$dbAdapter));
		$this->tableOffice = new Soporte_Models_DbTable_Office(array("db"=>$dbAdapter));
	}
	
	// ========================= Funciones de recuperacion primitiva de datos (datos de tabla origen)
	
	/**
	 *
	 * @return array
	 */
	public function getAllEquipos() {
	    $tablaEquipo2 = $this->tableEquipo2;
	    $rowsEquipos = $tablaEquipo2->fetchAll();
	    
	    return $rowsEquipos->toArray();
	}
	
	/**
	 *
	 * @return array
	 */
	public function getAllUbicaciones() {
	    $tablaEquipo2 = $this->tableEquipo2;
	    $select = $tablaEquipo2->select()->distinct()->from($tablaEquipo2,'ubicacion');
	    $rowsUbicaciones = $tablaEquipo2->fetchAll($select);
	    
	    return $rowsUbicaciones->toArray();
	}
	
	/**
	 *
	 * @return array
	 */
	public function getAllUsuarios() {
	    $tablaEquipo2 = $this->tableEquipo2;
	    $select = $tablaEquipo2->select()->distinct()->from($tablaEquipo2,'Usuario');
	    $rowsUsuarios = $tablaEquipo2->fetchAll($select);
	    
	    return $rowsUsuarios->toArray();
	}
	
	/**
	 *
	 * @return array
	 */
	function getAllSistemasOperativos() {
	    $tablaEquipo2 = $this->tableEquipo2;
	    $select = $tablaEquipo2->select()->distinct()->from($tablaEquipo2,'sisOperativo');
	    $rowsOperativos = $tablaEquipo2->fetchAll($select);
	    
	    return $rowsOperativos->toArray();
	}
	
	/**
	 *
	 * @return array
	 */
	function getAllOffice() {
	    $tablaEquipo2 = $this->tableEquipo2;
	    $select = $tablaEquipo2->select()->distinct()->from($tablaEquipo2,'versionOffice');
	    $rowsOffice = $tablaEquipo2->fetchAll($select);
	    
	    return $rowsOffice->toArray();
	}
	
	/**
	 *
	 * @param int $ubicacion
	 * @param int $usuario
	 * @return array
	 */
	public function getEquiposByParams($ubicacion = null, $usuario = null) {
	    $tE2 = $this->tableEquipo2;
	    $tUb = $this->tableUbicacion;
	    $tUs = $this->tableUsuario;
	    
	    $query = $tE2->select()->from($tE2);
	    
	    if ($ubicacion != null && $ubicacion != "") {
	        $select = $tUb->select()->from($tUb)->where("id=?",$ubicacion);
	        $rowUb = $tUb->fetchRow($select)->toArray();
	        
	        $query->where("ubicacion=?", $rowUb["ubicacion"]);
	    }
	    
	    if ($usuario != null && $usuario != "") {
	        $select = $tUs->select()->from($tUs)->where("id=?",$usuario);
	        $rowUs = $tUs->fetchRow($select)->toArray();
	        
	        $query->where("Usuario=?", $rowUs["usuario"]);
	    }
	    
	    $rowsEquipos = $tE2->fetchAll($query);
	    
	    return $rowsEquipos->toArray();
	}
	
	// ========================= Funciones de recuperacion de datos (nuevo esquema)
	
	/**
	 *
	 * @return array
	 */
	function getTiposEquipo() {
	    $tablaTipo = $this->tableTipo;
	    $rowsTipo = $tablaTipo->fetchAll();
	    
	    return $rowsTipo->toArray();
	}
	
	/**
	 *
	 * @return array
	 */
	function getUbicacionesEquipos() {
	    $tablaUbicaciones = $this->tableUbicacion;
	    $rowsUbicaciones = $tablaUbicaciones->fetchAll();
	    
	    return $rowsUbicaciones->toArray();
	}
	
	/**
	 *
	 * @return array
	 */
	function getUsuariosEquipos() {
	    $tablaUsuarios = $this->tableUsuario;
	    $rowsUsuarios = $tablaUsuarios->fetchAll();
	    
	    return $rowsUsuarios->toArray();
	}
	
	// ========================= Chequeo y Restauracion de Informacion Base
	
	/**
	 * 
	 * @return boolean[]
	 */
	public function checkInfo() {
	    $usuarios = $this->getUsuariosEquipos();
	    $ubicaciones = $this->getUbicacionesEquipos();
	    // @TODO Implementar caso para SisOperativos y Office
	    
	    $info = array();
	    if (empty($usuarios)) {
	        print_r("No hay usuarios"); print_r("<br />");
	        $info["usuarios"] = true; // true si esta vacio
	    }else {
	        //print_r($usuarios);
	        $info["usuarios"] = false;
	    }
	    
	    //print_r("<br />");print_r("<br />");
	    
	    if (empty($ubicaciones)) {
	        print_r("No hay ubicaciones"); print_r("<br />");
	        $info["ubicaciones"] = true;
	    }else{
	        //print_r($ubicaciones);
	        $info["ubicaciones"] = false;
	    }
	    
	    return $info;
	}
    
	/**
	 * @TODO cambiar por forma de transaccion de base de datos
	 */
	public function migrateInfo() {
	    // print_r("En MigrateInfo <br />");
	    $identity = Zend_Auth::getInstance()->getIdentity();
	    $bd = $identity["adapter"];
	    $bd->beginTransaction();
	    try {
	        // ======================= Tabla Ubicacion
	        $select = "SELECT DISTINCT ubicacion FROM equipo2";
	        $ubicaciones = $bd->fetchAll($select);
	        
	        $select = "SELECT * FROM Ubicacion";
	        $ubicacionesF = $bd->fetchAll($select);
	        
	        if (empty($ubicacionesF)){
	            foreach ($ubicaciones as $ubicacion){
	                if ($ubicacion["ubicacion"] != "") {
	                    $bd->insert("Ubicacion", array("ubicacion"=>$ubicacion["ubicacion"], "creacion" => date('Y-m-d H:i:s', time())));
	                }else{
	                    $bd->insert("Ubicacion", array("ubicacion"=>"Sin Ubicacion", "creacion" => date('Y-m-d H:i:s', time())));
	                }
	            }
	        }else{
	            // Recorremos la tabla Ubicaciones, si encontramos algun valor diferente lo damos de alta (omitimos los valores de espacios en blanco).
	            $arrUbicaciones = array();
	            foreach ($ubicacionesF as $ubF) {
	                $arrUbicaciones[] = $ubF["ubicacion"];
	            }
	            
	            foreach ($ubicaciones as $ubicacion){
	                if($ubicacion["ubicacion"] != ""){
	                    if (!in_array($ubicacion["ubicacion"], $arrUbicaciones)){
	                        $bd->insert("Ubicacion", array("ubicacion"=>$ubicacion["ubicacion"], "creacion" => date('Y-m-d H:i:s', time())));
	                    }
	                }
	            }
	        }
	        // ======================= Tabla Usuario
	        $select = "SELECT DISTINCT Usuario FROM equipo2";
	        $usuarios = $bd->fetchAll($select);
	        
	        $select = "SELECT * FROM Usuario";
	        $usuariosF = $bd->fetchAll($select);
	        
	        if (empty($usuariosF)) {
	            foreach ($usuarios as $usuario){
	                if ($usuario["Usuario"] != "") {
	                    $bd->insert("Usuario", array("usuario"=>$usuario["Usuario"], "creacion" => date('Y-m-d H:i:s', time())));
	                }else{
	                    $bd->insert("Usuario", array("usuario"=>"Sin Usuario", "creacion" => date('Y-m-d H:i:s', time())));
	                }
	            }
	        }else{
	            $arrUsuarios = array();
	            foreach ($usuarios  as $usF) {
	                $arrUsuarios[] = $usF["ubicacion"];
	            }
	            
	            foreach ($usuarios as $usuario){
	                if($usuario["Usuario"] != ""){
	                    if (!in_array($usuario["Usuario"], $arrUbicaciones)){
	                        $bd->insert("Usuario", array("usuario"=>$usuario["Usuario"], "creacion" => date('Y-m-d H:i:s', time())));
	                    }
	                }
	            }
	        }
	        // ======================= Tabla Sistema Operativo
	        // Origen y posibles nuevos
	        $select = "SELECT DISTINCT sisOperativo FROM equipo2";
	        $sisOperativos = $bd->fetchAll($select);
	        // Destino y elementos ya registrados
	        $select = "SELECT * FROM SistemaOperativo";
	        $sistemasOpers = $bd->fetchAll($select);
	        
	        if (empty($sistemasOpers)) {
	            foreach ($sisOperativos as $sisOp){
	                if ($sisOp["sisOperativo"] != "") {
	                    $bd->insert("SistemaOperativo", array("sistema"=>$sisOp["sisOperativo"], "creacion" => date('Y-m-d H:i:s', time())));
	                }else{
	                    $bd->insert("SistemaOperativo", array("sistema"=>"Sin SistemaOperativo", "creacion" => date('Y-m-d H:i:s', time())));
	                }
	            }
	        }else{
	            // Obtenemos los elementos ya dados de alta en la base de datos
	            $arrSisOps = array();
	            foreach ($sistemasOpers as $sisOpF) {
	                $arrSisOps[] = $sisOpF["ubicacion"];
	            }
	            // Recorremos sobre los elementos nuevos en Tabla equipo2
	            foreach ($sisOperativos as $sistOp) {
	                // Muchos equipos no tienen sistema operativo, nos centramos en los que si lo tienen
	                if($sistOp["sisOperativo"] != ""){
	                    // Si no existe lo insertamos
	                    if (!in_array($sistOp["sisOperativo"], $arrSisOps)){
	                        //print_r("Encontrado!!: <br />");
	                        $bd->insert("SistemaOperativo", array("sistema"=>$sisOp["sisOperativo"], "creacion" => date('Y-m-d H:i:s', time())));
	                    }
	                }
	            }
	            
	        }
	        // ======================= Tabla Sistema Operativo
	        // Origen y posibles nuevos
	        $select = "SELECT DISTINCT versionOffice FROM equipo2";
	        $versOffice = $bd->fetchAll($select);
	        // Destino y elementos ya registrados
	        $select = "SELECT * FROM Office";
	        $offices = $bd->fetchAll($select);
	        // Si la tabla Office esta vacia rellenamos con lo que encontremos en tabla origen
	        if (empty($offices)){
	            foreach ($versOffice as $vOff){
	                if($vOff["versionOffice"] != ""){
	                    $bd->insert("Office", array("office"=>$sisOp["sisOperativo"], "creacion" => date('Y-m-d H:i:s', time())));
	                }else{
	                    $bd->insert("Office", array("office"=>"Sin Office", "creacion" => date('Y-m-d H:i:s', time())));
	                }
	            }
	        }else{
	            $arrOffice = array();
	            foreach ($offices as $offc){
	                $arrOffice[] = $offc["office"];
	            }
	            
	            foreach ($versOffice as $vO) {
	                if ($vO["versionOffice"] != "") {
	                    if(!in_array($vO["versionOffice"], $arrOffice)){
	                        $bd->insert("Office", array("office"=>$vO["office"], "creacion" => date('Y-m-d H:i:s', time())));
	                    }
	                }
	            }
	            
	        }
	        // ======================= Tabla TipoEquipo
	        $bd->insert("TipoEquipo", array("clave"=>"SNTO", "tipo"=>"Sin Tipo" , "creacion" => date('Y-m-d H:i:s', time())));
	        $bd->insert("TipoEquipo", array("clave"=>"REDS", "tipo"=>"Redes de Computadoras" , "creacion" => date('Y-m-d H:i:s', time())));
	        $bd->insert("TipoEquipo", array("clave"=>"ELEC", "tipo"=>"Eléctricos" , "creacion" => date('Y-m-d H:i:s', time())));
	        $bd->insert("TipoEquipo", array("clave"=>"OFIC", "tipo"=>"Oficina" , "creacion" => date('Y-m-d H:i:s', time())));
	        $bd->insert("TipoEquipo", array("clave"=>"VARS", "tipo"=>"Varios" , "creacion" => date('Y-m-d H:i:s', time())));
	        // ======================= Tabla TipoOficina
	        $bd->insert("TipoOficina", array("clave"=>"ST", "tipo"=>"Sin Tipo" , "creacion" => date('Y-m-d H:i:s', time())));
	        // ======================= Tabla Marca
	        $bd->insert("Marca", array("marca"=>"Sin Marca", "creacion" => date('Y-m-d H:i:s', time())));
	        // ======================= Tabla Modelo
	        $bd->insert("Modelo", array("idMarca"=>"1","modelo"=>"Sin Modelo", "creacion" => date('Y-m-d H:i:s', time())));
	        
	        // throw new Exception("Todo ejecutado correctamente");
	        $bd->commit();
	        //print_r("En el metodo <br />");
	    } catch (Exception $e) {
	        // print_r("En la excepcion <br />");
	        // print_r($e->getMessage());
	        $bd->rollBack();
	    }
	    // print_r("De MigrateInfo <br />");
	    return ;
	}
	
	
	
	// ========================= Funciones auxiliares de migracion de datos (de origen a nuevo esquema)
	
	/**
	 * 
	 * @param int $idUsuario
	 * @return array
	 */
	public function getUbicacionesByIdUsuario($idUsuario) {
	    $tablaUsuario = $this->tableUsuario;
	    $select = $tablaUsuario->select()->from($tablaUsuario)->where("id=?",$idUsuario);
	    $rowUsuario = $tablaUsuario->fetchRow($select)->toArray();
	    
	    $tE2 = $this->tableEquipo2;
	    $select = $tE2->select()->distinct()->from($tE2, array("ubicacion"))->where("usuario=?",$rowUsuario["usuario"]);
	    //print_r($select->__toString());
	    $rowsUbicaciones = $tE2->fetchAll($select);
	    
	    return $rowsUbicaciones->toArray();
	}
	
	/**
	 * 
	 * @param array $ubicaciones
	 * @return array
	 */
	public function getUbicacionesByValues(array $ubicaciones) {
	    $tUb = $this->tableUbicacion;
	    $select = $tUb->select()->from($tUb)->where("ubicacion IN (?)", $ubicaciones);
	    $rowsUbicaciones = $tUb->fetchAll($select);
	    
	    return $rowsUbicaciones->toArray();
	}
	
	/**
	 * 
	 * @param int $idEquipo
	 * @param int $idUsuario
	 * @param int $idUbicacion
	 * @param int $idTipo
	 * @throws Exception
	 */
	public function asignarEquipo($idEquipo, $idUsuario, $idUbicacion, $idTipo) {
	    //print_r("Inicio del Metodo <br />");
	    $identity = Zend_Auth::getInstance()->getIdentity();
	    $bd = $identity["adapter"];
	    $bd->beginTransaction();
	    //print_r("Inicia Transaccion <br />");
	    try {
	        // Obtenemos el objeto en la tabla antigua y de ahi filtramos su info a las nuevas tablas
	        $select = "SELECT * FROM equipo2 where idComputadora=?";
	        $equipo = $bd->fetchRow($select,$idEquipo);
	        //Obtenemos el tipo de equipo que estamos asignando
	        $select = "SELECT * FROM TipoEquipo where id=?";
	        $tipo = $bd->fetchRow($select,$idTipo);
	        
	        $data = array();
	        $data["nombre"] = $equipo["equipo"];
	        $data["numSerie"] = $equipo["noSerie"];
	        $data["idModelo"] = 1; // No hay modelo asignado, esto pasa a formar parte de los pendientes
	        $data["idTipoEquipo"] = $idTipo;
	        $data["idUsuario"] = $idUsuario;
	        $data["idUbicacion"] = $idUbicacion;
	        $data["creacion"] = date("Y-m-d H:i:s",time()); // date("YYYY-mm-dd HH:mm:ii", time());
	        //print_r($data);
	        // Insertamos en la tabla equipo
	        $bd->insert("Equipo", $data);
	        
	        // Id lo usamos para insertar en la tabla Oficina, si es el caso
	        $id = $bd->lastInsertId("Equipo");
	        
	        switch ($tipo["clave"]){
	            case "REDS" :
	                break;
	            case "ELEC" :
	                break;
	            case "OFIC" :
	                $datosOficina = array();
	                $datosOficina["idEquipo"] = $id;
	                $datosOficina["idTipoOficina"] = 1;
	                $datosOficina["procesador"] = $equipo["procesador"];
	                $datosOficina["discoDuro"] = $equipo["discoDuro"];
	                $datosOficina["memoria"] = "";
	                $datosOficina["direccionIp"] = $equipo["direccionIP"];
	                $datosOficina["idsSoftwares"] = "";
	                $datosOficina["sisOperativo"] = $equipo["sisOperativo"];
	                $datosOficina["office"] = $equipo["versionOffice"];
	                $datosOficina["antivirus"] = "";
	                $datosOficina["creacion"] = date("Y-m-d H:i:s",time());
	                
	                $bd->insert("Oficina", $datosOficina);
	                
	                break;
	            case "VARS" :
	                break;
	        }
	        // @TODO eliminar registro origen en tabla equipo2
	        $where = "idComputadora=" . $idEquipo;
	        $bd->delete("equipo2", $where);
	        //print_r("Termina Operaciones<br />");
	        //throw new Exception($where);
	        $bd->commit();
	        // print_r("Todo salio bien!");
	    } catch (Exception $e) {
	        //print_r("En Excepcion <br />");
	        $bd->rollBack();
	        //print_r($e->getMessage());
	    }
	    // $bd->closeConnection();
	    /* */
	    
	    // print_r($db);
	    //print_r("Fin del metodo"); print_r("<br />");
	    // return ;
	}

}
