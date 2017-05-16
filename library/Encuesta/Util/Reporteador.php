<?php
/**
 * 
 */
class Encuesta_Util_Reporteador {
    
    private $tablaEncuesta;
    private $tablaSeccionEncuesta;
    private $tablaGrupoSeccion;
    private $tablaPregunta;
    private $tablaOpcion;
    
    private $tablaAsignacion;
    private $tablaRegistro;
    private $tablaMateriaEscolar;
    private $tablaGrupoEscolar;
    private $tablaGradoEscolar;
    private $tablaNivelEducativo;
    
    private $tablaConjunto;
    private $tablaEvaluacionConjunto;
    private $tablaEvaluacionRealizada;
    private $tablaEvaluador;
    
    private $reportTemplateHorizontal = null;
    private $reporteActiveHorizontal = null;
    private $paginaActiva = null;
    private $nombreReporte = null;
    private $rutaReporte = null;
    
    private $utilJson;
    private $utilText;
    //DAOS
    private $opcionDAO;
    private $preguntaDAO;
    private $grupoDAO;
    
    private $tablaReportesEncuesta;
    private $tablaReportesConjunto;
    private $tablaReportesGrupo;
	
	function __construct($dbAdapter) {
		$this->tablaEncuesta = new Encuesta_Model_DbTable_Encuesta(array("db"=>$dbAdapter));
        $this->tablaSeccionEncuesta = new Encuesta_Model_DbTable_SeccionEncuesta(array("db"=>$dbAdapter));
        $this->tablaGrupoSeccion = new Encuesta_Model_DbTable_GrupoSeccion(array("db"=>$dbAdapter));
        $this->tablaPregunta = new Encuesta_Model_DbTable_Pregunta(array("db"=>$dbAdapter));
        $this->tablaOpcion = new Encuesta_Model_DbTable_OpcionCategoria(array("db"=>$dbAdapter));
        
        $this->tablaAsignacion = new Encuesta_Model_DbTable_AsignacionGrupo(array("db"=>$dbAdapter));
        $this->tablaMateriaEscolar = new Encuesta_Model_DbTable_MateriaEscolar(array("db"=>$dbAdapter));
        $this->tablaRegistro = new Encuesta_Model_DbTable_Registro(array("db"=>$dbAdapter));
        $this->tablaGrupoEscolar = new Encuesta_Model_DbTable_GrupoEscolar(array("db"=>$dbAdapter));
        $this->tablaGradoEscolar = new Encuesta_Model_DbTable_GradoEducativo(array("db"=>$dbAdapter));
        $this->tablaNivelEducativo = new Encuesta_Model_DbTable_NivelEducativo(array("db"=>$dbAdapter));
        
        $this->tablaConjunto = new Encuesta_Model_DbTable_ConjuntoEvaluador(array("db"=>$dbAdapter));
        $this->tablaEvaluacionConjunto = new Encuesta_Model_DbTable_EvaluacionConjunto(array("db"=>$dbAdapter));
        $this->tablaEvaluacionRealizada = new Encuesta_Model_DbTable_EvaluacionRealizada(array("db"=>$dbAdapter));
        
        $this->utilJson = new Encuesta_Util_Json;
        $this->utilText = new Encuesta_Util_Text;
        $this->opcionDAO = new Encuesta_DAO_Opcion($dbAdapter);
        $this->preguntaDAO = new Encuesta_DAO_Pregunta($dbAdapter);
        $this->grupoDAO = new Encuesta_DAO_Grupo($dbAdapter);
        
        $this->tablaReportesConjunto = new Encuesta_Model_DbTable_ReportesConjunto(array("db"=>$dbAdapter));
        $this->tablaReportesGrupo = new Encuesta_Model_DbTable_ReportesGrupo(array("db"=>$dbAdapter));
        $this->tablaReportesEncuesta = new Encuesta_Model_DbTable_ReportesEncuesta(array("db"=>$dbAdapter));
        
        $this->tablaEvaluador = new Encuesta_Model_DbTable_Evaluador(array("db"=>$dbAdapter));
	}
    
    /**
     * 
     */
    public function generarReporteGrupalAsignacion($idGrupo, $idAsignacion, $idEncuesta) {
        $tablaEncuesta = $this->tablaEncuesta;
        $tablaAsignacion = $this->tablaAsignacion;
        $tablaConjunto = $this->tablaConjunto;
        $tablaEvalReal = $this->tablaEvaluacionRealizada;
        
        $select = $tablaEncuesta->select()->from($tablaEncuesta)->where("idEncuesta=?",$idEncuesta);
        $rowEncuesta = $tablaEncuesta->fetchRow($select);
        $arrEncuesta = $rowEncuesta->toArray();
        
        $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$idAsignacion);
        $rowAsignacion = $tablaAsignacion->fetchRow($select);
        $arrAsignacion = $rowAsignacion->toArray();
        
        $select = $tablaEvalReal->select()->from($tablaEvalReal)->where("idAsignacionGrupo=?",$idAsignacion);
        $rowsEvalsReal = $tablaEvalReal->fetchAll($select);
        $arrEvalsReal = $rowsEvalsReal->toArray();
        
        //print_r($arrEvalsReal);
        // Pagina Reporte
        $pagina = $this->obtenerReporteBaseGrupalHorizontal($idEncuesta, $idAsignacion);
        $pagina = $this->generarContentGrupalHorizontal($idEncuesta, $idAsignacion, $pagina);
        
        $reporte = $this->reporteActiveHorizontal;
        $reporte->addPage($pagina);
        $reporte->saveDocument();
        //Lo guardamos en la tabla Reportes Grupo
        //$tablaReportesGrupo = $this->tablaReportesGrupo;
        $tablaReportesEncuesta = $this->tablaReportesEncuesta;
        $idReporte = 0;
        $select = $tablaReportesEncuesta->select()->from($tablaReportesEncuesta)->where("idAsignacionGrupo=?",$idAsignacion)->where("idEncuesta=?",$idEncuesta);
        $rowReporte = $tablaReportesEncuesta->fetchRow($select);
        if (is_null($rowReporte)) {
            $datos = array();
            //$datos["idGrupoEscolar"] = $idGrupo;
            $datos["idEncuesta"] = $idEncuesta;
            $datos["idAsignacionGrupo"] = $idAsignacion;
            //$datos["idsEvaluadores"]="";
            $datos["nombreReporte"] = $this->nombreReporte;
            $datos["tipoReporte"] = "RGRU";
            $datos["rutaReporte"] = $this->rutaReporte."/";
            $datos["fecha"] = date("Y-m-d H:i:s", time());
            print_r($datos);
            $idReporte = $tablaReportesEncuesta->insert($datos);
        }else{
            $idReporte = $rowReporte->idReporte;
        }
        //print_r("idReporte: ".$idReporte);
        return $idReporte;
    }
    
    /**
     * 
     */
    public function obtenerReporteBaseGrupalHorizontal($idEncuesta, $idAsignacion) {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $organizacion = $identity["organizacion"];
        //print_r("Organizacion: ");
        //print_r($identity["organizacion"]); print_r("<br /><br />");
        $tablaAsignacion = $this->tablaAsignacion;
        $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$idAsignacion);
        $rowAsignacion = $tablaAsignacion->fetchRow($select);
        
        $tablaGrupoE = $this->tablaGrupoEscolar;
        $select = $tablaGrupoE->select()->from($tablaGrupoE)->where("idGrupoEscolar=?", $rowAsignacion->idGrupoEscolar);
        $rowGrupoE = $tablaGrupoE->fetchRow($select);
        
        $tablaRegistro = $this->tablaRegistro;
        $select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro=?",$rowAsignacion->idRegistro);
        $rowRegistro = $tablaRegistro->fetchRow($select);
        
        $nombreArchivo = $rowGrupoE->grupoEscolar."-".$idEncuesta."-".str_replace(" ", "", $rowRegistro->apellidos.$rowRegistro->nombres)."-".$idAsignacion."-RGPH.pdf";
        //print_r("Nombre Archivo Antes: ");
        //print_r($nombreArchivo); print_r("<br /><br />");
        //print_r("Nombre Archivo Despues: ");
        //print_r($this->utilText->cleanString($nombreArchivo)); print_r("<br /><br />");
        //$directorio = $this->organizacion["directorio"];
        $directorio = $organizacion["directorio"];
        $rutaReporte = '/reports/Encuesta/grupal/'.$directorio;
        //$nombreArchivo = "test.pdf";
        $nombreArchivo = $this->utilText->cleanString($nombreArchivo);
        $this->nombreReporte = $nombreArchivo;
        $this->rutaReporte = $rutaReporte;
        // Obtenemos Template para obtener pagina base
        $pdfTemplate = My_Pdf_Document::load(PDF_PATH . '/reports/bases/reporteHBE.pdf');
        $this->reportTemplateHorizontal = $pdfTemplate;
        $pages = $pdfTemplate->pages;
        $pdfReport = new My_Pdf_Document($nombreArchivo, PDF_PATH . $rutaReporte);
        $pdfReport->setYHeaderOffset(160);
        //Pagina Activa
        $pageZ = clone $pages[0];
        $page = new My_Pdf_Page($pageZ);
        
        $fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $styleDefault = new Zend_Pdf_Style;
        $styleDefault->setFont($fontDefault, 10);
        
        $page->setStyle($styleDefault);
        $page->setFont($fontDefault, 10);
        $page->addTable($this->generarHeaderGrupalHorizontal($idEncuesta, $idAsignacion), 120, 120);
        //$this->generarContentGrupalHorizontal($idEncuesta, $idAsignacion);
        $this->reporteActiveHorizontal = $pdfReport;
        
        return $page;
    }
    
    /**
     * 
     */
    private function generarHeaderGrupalHorizontal($idEncuesta, $idAsignacion) {
        // Encuesta
        $tablaEncuesta = $this->tablaEncuesta;
        $select = $tablaEncuesta->select()->from($tablaEncuesta)->where("idEncuesta=?",$idEncuesta);
        $encuesta = $tablaEncuesta->fetchRow($select)->toArray();
        // Asignacion
        $tablaAsignacion = $this->tablaAsignacion;
        $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?",$idAsignacion);
        $asignacion = $tablaAsignacion->fetchRow($select)->toArray();
        // Registro
        $tablaRegistro = $this->tablaRegistro;
        $select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro=?",$asignacion["idRegistro"]);
        $docente = $tablaRegistro->fetchRow($select)->toArray();
        // Materia
        $tablaMateriaEscolar = $this->tablaMateriaEscolar;
        $select = $tablaMateriaEscolar->select()->from($tablaMateriaEscolar)->where("idMateriaEscolar=?",$asignacion["idMateriaEscolar"]);
        $materia = $tablaMateriaEscolar->fetchRow($select)->toArray();
        // Grupo
        $tablaGrupo = $this->tablaGrupoEscolar;
        $select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupoEscolar=?",$asignacion["idGrupoEscolar"]);
        $grupoEscolar = $tablaGrupo->fetchRow($select)->toArray();
        // Grado
        $tablaGrado = $this->tablaGradoEscolar;
        $select = $tablaGrado->select()->from($tablaGrado)->where("idGradoEducativo=?", $grupoEscolar["idGradoEducativo"]);
        $gradoEducativo = $tablaGrado->fetchRow($select)->toArray();
        // Nivel
        $tablaNivel = $this->tablaNivelEducativo;
        $select = $tablaNivel->select()->from($tablaNivel)->where("idNivelEducativo=?",$gradoEducativo["idNivelEducativo"]);
        $nivel = $tablaNivel->fetchRow($select)->toArray();
        
        $tableHeader = new My_Pdf_Table(2);
        $cellWidth = 200;
        
        $fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        
        $rowTable1 = new My_Pdf_Table_Row;
        $rowTable2 = new My_Pdf_Table_Row;
        $rowTable3 = new My_Pdf_Table_Row;
        $rowTable4 = new My_Pdf_Table_Row;
        //$rowTable1 = new My_Pdf_Table_Row;
        
        $colthA1 = new My_Pdf_Table_Column;
        $colthA2 = new My_Pdf_Table_Column;
        $colthB1 = new My_Pdf_Table_Column;
        $colthB2 = new My_Pdf_Table_Column;
        $colthC1 = new My_Pdf_Table_Column;
        $colthC2 = new My_Pdf_Table_Column;
        $colthD1 = new My_Pdf_Table_Column;
        $colthD2 = new My_Pdf_Table_Column;
        
        $colthA1->setText("Evaluacion: ");
        $colthA1->setWidth($cellWidth);
        $colthA2->setText(utf8_encode($encuesta['nombre']));
        $colthB1->setText("Docente: ");
        $colthB1->setWidth($cellWidth); //utf8_encode($docente['apellidos'].", ".$docente['nombres'])
        $colthB2->setText($docente['apellidos'].", ".$docente['nombres']);
        $colthC1->setText("Nivel, Grado, Grupo Y Materia: ");
        $colthC1->setWidth($cellWidth);
        $colthC2->setText($nivel["nivelEducativo"].", ".$gradoEducativo["gradoEducativo"].", Grupo. ".$grupoEscolar["grupoEscolar"].", ".$materia['materiaEscolar']);
        
        $colthD1->setText("Grupo: ");
        $colthD1->setWidth($cellWidth);
        $colthD2->setText($grupoEscolar['grupoEscolar']);
        
        $rowTable1->setColumns(array($colthA1,$colthA2));
        $rowTable1->setCellPaddings(array(5,5,5,5));
        $rowTable1->setFont($fontDefault,10);
        
        $rowTable2->setColumns(array($colthB1,$colthB2));
        $rowTable2->setCellPaddings(array(5,5,5,5));
        $rowTable2->setFont($fontDefault,10);
        
        $rowTable3->setColumns(array($colthC1,$colthC2));
        $rowTable3->setCellPaddings(array(5,5,5,5));
        $rowTable3->setFont($fontDefault,10);
        
        $rowTable4->setColumns(array($colthD1,$colthD2));
        $rowTable4->setCellPaddings(array(5,5,5,5));
        $rowTable4->setFont($fontDefault,10);
        
        $tableHeader->addRow($rowTable1);
        $tableHeader->addRow($rowTable2);
        $tableHeader->addRow($rowTable3);
        
        return $tableHeader;
    }
    
    /**
     * 
     */
    public function generarContentGrupalHorizontal($idEncuesta, $idAsignacion, $page) {
        $fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        // ========================================================== >>> Generamos el cuerpo del reporte
        $tablaContenidoR = new My_Pdf_Table(2);
        
        $promedioFinal = 0;
        $sumaFinal = 0;
        $numCategorias = 0;
        
        $tablaSeccion = $this->tablaSeccionEncuesta;
        $tablaGrupo = $this->tablaGrupoSeccion;
        $tablaPregunta = $this->tablaPregunta;
        //$tablaRespuesta = $this->tablaRespuest;
        $tablaOpcion = $this->tablaOpcion;
        //$tablaPreferenciaS = $this->tablaPreferenciaS;
        $tablaEvalReal = $this->tablaEvaluacionRealizada;
        
        $select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta=?", $idEncuesta);
        $secciones = $tablaSeccion->fetchAll($select)->toArray();
        //$secciones = $
        $select = $tablaEvalReal->select()->from($tablaEvalReal)->where("idAsignacionGrupo=?",$idAsignacion);
        $rowsEvalsReal = $tablaEvalReal->fetchAll($select);
        $arrEvalsReal = $rowsEvalsReal->toArray();
        $totalEvaluadores = count($arrEvalsReal);
        
        //print_r($arrEvalsReal); 
        $utilJson = $this->utilJson;
        
        $arrayGeneral = array();
        
        foreach ($arrEvalsReal as $arrEval) {
            //print_r($arrEval); print_r("<br /><br />");
            $arrayJson = $utilJson->processJsonEncuestaTres($arrEval["json"]);
            //print_r($arrayJson); print_r("<br /><br />");
            //break;
            $arrayGeneral[] = $arrayJson;
            
        }
        
        $rPreferencia = array();
        foreach ($arrayGeneral as $rests) {
            //print_r($rest);
            foreach ($rests as $idPregunta => $idOpcion) {
                $opcion = $this->opcionDAO->obtenerOpcion($idOpcion);
                $opcionMayor = $this->opcionDAO->obtenerOpcionMayor($idOpcion);
                // Obtener la opcion mayor
                
                $valor = null;
                $obj = array();
                switch ($opcion->getTipoValor()) {
                    case 'EN':
                        $valor = $opcion->getValorEntero();
                        break;
                    case 'DC':
                        $valor = $opcion->getValorDecimal();
                        break;
                }
                
                if (array_key_exists($idPregunta, $rPreferencia)) {
                    //$valAnterior = $rPreferencia[$idPregunta];
                    $rPreferencia[$idPregunta]["preferencia"] = $rPreferencia[$idPregunta]["preferencia"] + $valor;
                    //$rPreferencia[$idPregunta]["opcion"] + $opcion;
                }else{
                    //La primera insercion
                    $obj["preferencia"] = $valor;
                    $obj["opcionMayor"] = $opcionMayor;
                    $rPreferencia[$idPregunta] = $obj;
                }
            }
        }

        //print_r($rPreferencia);

        $gruposSeccion = array();
        foreach ($rPreferencia as $idPregunta => $preferencia) {
            $pregunta = $this->preguntaDAO->getPreguntaById($idPregunta)->toArray();
            if (array_key_exists($pregunta["idOrigen"], $gruposSeccion)) {
                $gruposSeccion[$pregunta["idOrigen"]]["preferencia"] += $preferencia["preferencia"];
                $gruposSeccion[$pregunta["idOrigen"]]["totalPreguntas"] += 1;
            }else{
                $gruposSeccion[$pregunta["idOrigen"]]["preferencia"] = $preferencia["preferencia"];
                $gruposSeccion[$pregunta["idOrigen"]]["totalPreguntas"] = 1;
                $gruposSeccion[$pregunta["idOrigen"]]["valorMayor"] = $preferencia["opcionMayor"];
                
            }
            //$gruposSeccion[$pregunta["idOrigen"]] += $preferencia["preferencia"];
            //print_r($pregunta);print_r("<br /><br />");
            //print_r($preferencia); print_r("<br /><br />");
        }
        //print_r($gruposSeccion);
        /*
        foreach ($gruposSeccion as $idGrupo => $grupoSeccion) {
            print_r($idGrupo); print_r("<br />");
            print_r($grupoSeccion); print_r("<br /><br />");
        }*/
        
        $totalGrupos = count($gruposSeccion);
        $tableContent = new My_Pdf_Table($totalGrupos+1);
        $anchoCelda = 75;
        $rowHeader = new My_Pdf_Table_HeaderRow();
        $rowHeader->setFont($fontDefault, 10);
        $rowContent = new My_Pdf_Table_Row();
        $rowContent->setFont($fontDefault, 10);
        $rowEmpty = new My_Pdf_Table_Row();
        $columnsHeaders = array();
        $columns = array();
        $emptyColumns = array();
        $totalPromedios = 0;
        foreach ($gruposSeccion as $idGrupo => $obj) {
            $grupoSeccion = $this->grupoDAO->getGrupoById($idGrupo)->toArray();
            if ($grupoSeccion['tipo'] == 'SS') {
                $columnHeader = new My_Pdf_Table_Column;
                $column = new My_Pdf_Table_Column;
                $emptyColumn = new My_Pdf_Table_Column;
                //$valorMaximo = $grupoSeccion['valorMaximo'];
                //$valorMinimo = $grupoSeccion['valorMinimo'];
                
                //$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen=?","G")->where("idOrigen=?",$grupoSeccion["idGrupoSeccion"]);
                //$rowsPreguntas = $tablaPregunta->fetchAll($select);
                // PuntajeMaximo = valorMayorDeOpcionMultiple * numeroEncuestasRealizadas * numeroPreguntasEnGrupo
                //$puntajeMaximo = $valorMaximo * $realizadas['realizadas'] * count($rowsPreguntas);
                //print_r("Puntaje maximo: ".$puntajeMaximo."<br />");
                
                $puntajeMaximo = $obj["totalPreguntas"] * $totalEvaluadores * $obj["valorMayor"]["valorEntero"];
                $promedio = $obj["preferencia"] * 10 / $puntajeMaximo;
                //print_r("Promedio: "); print_r($promedio); print_r("<br />");
                $totalPromedios += $promedio; 
                /*
                $idsPreguntas = array();
                foreach ($rowsPreguntas as $rowPregunta) {
                    $idsPreguntas[] = $rowPregunta->idPregunta;
                }
                
                $select = $tablaPreferenciaS->select()->from($tablaPreferenciaS)->where("idAsignacionGrupo=?",$idAsignacion)->where("idPregunta IN (?)",$idsPreguntas);
                $rowsPreferencias = $tablaPreferenciaS->fetchAll($select);
                $totalPreferencia = 0;
                // Se suman las preferencias totales por preguntas
                foreach ($rowsPreferencias as $rowPreferencia) {
                    $totalPreferencia += $rowPreferencia->total;
                }
                //print_r("TotalPreferencia: ".$totalPreferencia);
                //print_r("<br />");
                //print_r($select->__toString());
                $promedio = (10 * $totalPreferencia) / $puntajeMaximo;
                //print_r("Total: " . $promedio);*/
                $columnHeader->setText($grupoSeccion["nombre"]);
                $columnHeader->setWidth($anchoCelda);
                //$columnHeader->setAlignment();
                
                $column->setText(sprintf('%.2f', $promedio));
                $column->setWidth($anchoCelda);
                $columnsHeaders[] = $columnHeader;
                $columns[] = $column;
                $emptyColumn->setText("");
                $emptyColumn->setWidth($anchoCelda);
                $emptyColumns[] = $emptyColumn;
                //$columnsHeaders
                //$sumaFinal += $promedio;
                //$numCategorias++;
            }
        }/**/
        
        
        $rowHeader->setColumns($columnsHeaders);
        $rowContent->setColumns($columns);
        $rowEmpty->setColumns($emptyColumns);
        //$rowTable1->setColumns(array($colthA1,$colthA2));
        $rowHeader->setCellPaddings(array(5,5,5,5));
        //$rowHeader->setFont($font,8);
        $rowContent->setCellPaddings(array(5,5,5,5));
        //$rowContent->setFont($font,8);
        $tableContent->setHeader($rowHeader);
        //$tableContent->addRow($rowHeader);
        //$tableContent->addRow($rowHeader);
        $tableContent->addRow($rowContent);
        $tableContent->addRow($rowEmpty);
        
        $page->addTable($tableContent, 60, 220);
        
        $promedioFinal = $totalPromedios / $totalGrupos;
        //print_r("Promedio Final: "); print_r($promedioFinal);
        
        $resultado = "";
        if($promedioFinal >= 8.5){
            $resultado = "EXCELENTE";
        }elseif($promedioFinal > 7.0){
            $resultado = "ADECUADO";
        }elseif($promedioFinal > 5.0){
            $resultado = "INSUFICIENTE";
        }elseif($promedioFinal > 4.0){
            $resultado = "DEFICIENTE";
        }elseif($promedioFinal < 4.0){
            $resultado = "MARGINAL";
        }
        
        $page->drawText("PROMEDIO: ".sprintf('%.2f', $promedioFinal) . " - " . $resultado, 570, 300);
        $page->drawText("Reporte generado por Zazil Consultores para: Colegio Sagrado Corazón México", 280, 575);
        //print_r($this->organizacion);
        //$this->reporteActiveHorizontal->addPage($page);
        //$pdfReport->addPage($page);
        
        
        return $page;
    }

    /**
     * 
     */
    public function obtenerReporteGrupal($idReporte){
       $tablaReporteGrupo = $this->tablaReportesGrupo;
       $select = $tablaReporteGrupo->select()->from($tablaReporteGrupo)->where("idReporteGrupo=?", $idReporte);
       $reporte = $tablaReporteGrupo->fetchRow($tablaReporteGrupo);
       
       return $reporte;
    }
    
    /**
     * 
     */
    public function generarReporteDocenteOrientadora($idAsignacion, $idEncuesta) {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $organizacion = $identity["organizacion"];
        
        $tablaEncuesta = $this->tablaEncuesta;
        $tablaAsignacion = $this->tablaAsignacion;
        $tablaGrupoE = $this->tablaGrupoEscolar;
        $tablaMateriaE = $this->tablaMateriaEscolar;
        $tablaRegistro = $this->tablaRegistro;
        $tablaPregunta = $this->tablaPregunta;
        
        $tablaGrado = $this->tablaGradoEscolar;
        $tablaNivel = $this->tablaNivelEducativo;
        
        $select = $tablaEncuesta->select()->from($tablaEncuesta)->where("idEncuesta=?",$idEncuesta);
        $rowEncuesta = $tablaEncuesta->fetchRow($select)->toArray();
        
        $select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idAsignacionGrupo=?", $idAsignacion);
        $rowAsignacion = $tablaAsignacion->fetchRow($select)->toArray();
        
        $select = $tablaGrupoE->select()->from($tablaGrupoE)->where("idGrupoEscolar=?",$rowAsignacion["idGrupoEscolar"]);
        $rowGrupoE = $tablaGrupoE->fetchRow($select)->toArray();
        
        $select = $tablaMateriaE->select()->from($tablaMateriaE)->where("idMateriaEscolar=?", $rowAsignacion["idMateriaEscolar"]);
        $rowMateria = $tablaMateriaE->fetchRow($select)->toArray();
        
        $select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro=?",$rowAsignacion["idRegistro"]);
        $rowRegistro = $tablaRegistro->fetchRow($select)->toArray();
        
        $select = $tablaGrado->select()->from($tablaGrado)->where("idGradoEducativo=?",$rowMateria["idGradoEducativo"]);
        $rowGrado = $tablaGrado->fetchRow($select)->toArray();
        
        $select = $tablaNivel->select()->from($tablaNivel)->where("idNivelEducativo=?",$rowGrado["idNivelEducativo"]);
        $rowNivelE = $tablaNivel->fetchRow($select)->toArray();
        ##### Creamos un documento con el constructor de la libreria PDF
        //$nombreArchivo = "testOrientadora.pdf";
        $nombreArchivo = $rowGrupoE["grupoEscolar"]."-".$idEncuesta."-".str_replace(" ", "", $rowRegistro["apellidos"].$rowRegistro["nombres"])."-".$idAsignacion."-RGPO.pdf";
        $directorio = $organizacion["directorio"];
        $rutaReporte = '/reports/Encuesta/grupal/'.$directorio.'/Orientadora/';
        
        $pdfReport = new My_Pdf_Document($nombreArchivo, PDF_PATH . $rutaReporte);
        //$pdfReport->setYHeaderOffset(160);
        
        $page = $pdfReport->createPage();
        
        //$fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        //$fontDefault = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/jura_font_6126/JuraBook.ttf');
        $fontDefault = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHIC.TTF');
        $fontDefaultBold = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHICB.TTF');
        $fontDefaultItalic = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHICI.TTF');
        $fontDefaultItalicBold = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHICI.TTF');
        
        $fontMinSize = 8;
        $fontMedSize = 8;
        $fontBigSize = 12;
        $fontTitleSize = 16;
        $fontFooterSize = 10;
        
        $styleDefault = new Zend_Pdf_Style;
        $styleDefault->setFont($fontDefault, $fontMedSize);
        
        $page->setStyle($styleDefault);
        $page->setFont($fontDefault, $fontMedSize);
        $page->drawRectangle(0, 0, 555, 785);
        
        $imgEncabezado = Zend_Pdf_Image::imageWithPath(IMAGES_PATH . '/Logo.png');
        $page->drawImage($imgEncabezado, 35, 20, 96, 58);
        //$page->addTable($this->generarHeaderGrupalHorizontal($idEncuesta, $idAsignacion), 120, 120);
        ##### Pagina configurada, generando Header
        $tableHeader = new My_Pdf_Table(2);
        $cellWidth = 200;
        
        //$fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        
        $rowTable1 = new My_Pdf_Table_Row;
        $rowTable2 = new My_Pdf_Table_Row;
        $rowTable3 = new My_Pdf_Table_Row;
        $rowTable4 = new My_Pdf_Table_Row;
        //$rowTable1 = new My_Pdf_Table_Row;
        
        $colthA1 = new My_Pdf_Table_Column;
        $colthA2 = new My_Pdf_Table_Column;
        
        $colthB1 = new My_Pdf_Table_Column;
        $colthB2 = new My_Pdf_Table_Column;
        
        $colthC1 = new My_Pdf_Table_Column;
        $colthC2 = new My_Pdf_Table_Column;
        
        $colthD1 = new My_Pdf_Table_Column;
        $colthD2 = new My_Pdf_Table_Column;
        
        $colthA1->setText("Evaluacion: ");
        $colthA1->setWidth($cellWidth);
        $colthA1->setFont($fontDefaultBold,$fontMedSize);
        $colthA2->setText(utf8_encode($rowEncuesta['nombre']));
        
        $colthB1->setText("Docente: ");
        $colthB1->setWidth($cellWidth); //utf8_encode($docente['apellidos'].", ".$docente['nombres'])
        $colthB1->setFont($fontDefaultBold,$fontMedSize);
        $colthB2->setText($rowRegistro['apellidos'].", ".$rowRegistro['nombres']);
        
        $colthC1->setText("Nivel y Grado: ");
        $colthC1->setWidth($cellWidth);
        $colthC1->setFont($fontDefaultBold,$fontMedSize);
        $colthC2->setText($rowNivelE["nivelEducativo"].", ".$rowGrado["gradoEducativo"]);
        
        $colthD1->setText("Grupo y Materia: ");
        $colthD1->setWidth($cellWidth);
        $colthD1->setFont($fontDefaultBold,$fontMedSize);
        $colthD2->setText("Grupo: ".$rowGrupoE["grupoEscolar"].", ".$rowMateria['materiaEscolar']);
        
        $rowTable1->setColumns(array($colthA1,$colthA2));
        $rowTable1->setCellPaddings(array(5,5,5,5));
        $rowTable1->setFont($fontDefault,$fontMedSize);
        
        $rowTable2->setColumns(array($colthB1,$colthB2));
        $rowTable2->setCellPaddings(array(5,5,5,5));
        $rowTable2->setFont($fontDefault,$fontMedSize);
        
        $rowTable3->setColumns(array($colthC1,$colthC2));   
        $rowTable3->setCellPaddings(array(5,5,5,5));
        $rowTable3->setFont($fontDefault,$fontMedSize);
        
        $rowTable4->setColumns(array($colthD1,$colthD2));
        $rowTable4->setCellPaddings(array(5,5,5,5));
        $rowTable4->setFont($fontDefault,$fontMedSize);
        
        $tableHeader->addRow($rowTable1);
        $tableHeader->addRow($rowTable2);
        $tableHeader->addRow($rowTable3);
        $tableHeader->addRow($rowTable4);
        
        $page->addTable($tableHeader, 150, -10);
        ##### Agregando Contenido
        $tablaContenidoR = new My_Pdf_Table(3);
        
        $promedioFinal = 0;
        $sumaFinal = 0;
        $numCategorias = 0;
        
        $select = $tablaPregunta->select()->from($tablaPregunta)->where("idEncuesta=?", $rowEncuesta["idEncuesta"]);
        $rowsPreguntasEncuesta = $tablaPregunta->fetchAll($select);
        
        $tablaEvalReal = $this->tablaEvaluacionRealizada;
        $select = $tablaEvalReal->select()->from($tablaEvalReal)->where("idEvaluacion=?",$idEncuesta)->where("idAsignacionGrupo=?",$idAsignacion);
        $rowsEvalReal = $tablaEvalReal->fetchAll($select)->toArray();
        //print_r(count($rowsEvalReal));
        $numeroAlumnas = count($rowsEvalReal);
        $maxOpcion = 5;
        $maxPuntaje = $numeroAlumnas * $maxOpcion;
        
        $respuestas = array();
        $jsonObjs = array();
        foreach ($rowsEvalReal as $rowEvalR) {
            $jsonObjs[] = $this->utilJson->processJsonEncuestaDos($rowEvalR["json"]);
        }
        //print_r($jsonObjs);
        $preferencias = array();
        
        foreach ($jsonObjs as $index => $jsonObj) {
            //print_r($index);
            
            foreach ($jsonObj as $idPregunta => $idOpcion) {
                
                $pregunta = $this->preguntaDAO->getPreguntaById($idPregunta)->toArray();
                //print_r($pregunta);
                $valorOpcion = null;
                $preferencia = array();
                // Si es la primera insercion
                if ($index == 0) {
                    $preferencia["pregunta"] = $pregunta;
                    
                    if ($pregunta["tipo"] == "SS") {
                        $opcion = $this->opcionDAO->obtenerOpcion($idOpcion);
                        $valorOpcion = $opcion->getValorEntero();
                        
                        $preferencia["puntaje"] = $valorOpcion;
                        //$preferencia["maxPuntaje"] = $maxPuntaje;
                    }elseif($pregunta["tipo"] == "AB"){
                        $valorOpcion = $idOpcion;
                        $preferencia["pab"][] = $valorOpcion;
                    }
                    
                    $preferencias[$idPregunta] = $preferencia;
                // Si es la segunda insercion
                }else{
                    
                    if ($pregunta["tipo"] == "SS") {
                        $opcion = $this->opcionDAO->obtenerOpcion($idOpcion);
                        $valorOpcion = $opcion->getValorEntero();
                        
                        $preferencias[$idPregunta]["puntaje"] += $valorOpcion;
                    }elseif($pregunta["tipo"] == "AB"){
                        $valorOpcion = $idOpcion;
                        $preferencias[$idPregunta]["pab"][] = $valorOpcion;
                    }
                    
                    //$preferencias[$idPregunta] = $preferencia;
                }
                
            }
        }

        // Iniciamos la insercion de las preguntas
        $tableContent = new My_Pdf_Table(3);
        
        $rowHeaderTable = new My_Pdf_Table_Row;
        $colHeaderPregunta = new My_Pdf_Table_Column;
        $colHeaderPuntaje = new My_Pdf_Table_Column;
        $colHeaderCalificacion = new My_Pdf_Table_Column;
        
        $colHeaderPregunta->setText("Pregunta");
        $colHeaderPregunta->setWidth(400);
        $colHeaderPregunta->setFont($fontDefaultBold,$fontMedSize);
        $colHeaderPuntaje->setText("Puntaje");
        $colHeaderPuntaje->setWidth(50);
        $colHeaderPuntaje->setFont($fontDefaultBold,$fontMedSize);
        $colHeaderCalificacion->setText("Calificacion");
        $colHeaderCalificacion->setWidth(50);
        $colHeaderCalificacion->setFont($fontDefaultBold,$fontMedSize);
        
        $rowHeaderTable->setColumns(array($colHeaderPregunta,$colHeaderPuntaje,$colHeaderCalificacion));
        $rowHeaderTable->setCellPaddings(array(5,5,5,5));
        $rowHeaderTable->setFont($fontDefault,$fontMinSize);
        //$rowHeaderTable->setBorder(BOTTOM, null);
        $tableContent->addRow($rowHeaderTable);
        // Solo las preguntas que se pueden calcular, es decir de tipo SS
        $numeroPreguntas = 0;
        
        foreach ($preferencias as $idPregunta => $container) {
            $rowTable = new My_Pdf_Table_Row;
            $colPregunta = new My_Pdf_Table_Column;
            $colPuntaje = new My_Pdf_Table_Column;
            $colCalificacion = new My_Pdf_Table_Column;
            
            $colPregunta->setText($container["pregunta"]["nombre"]);
            $colPregunta->setWidth(400);
            //$colPregunta;
            
            if ($container["pregunta"]["tipo"] == "SS") {
                $calificacion = ($container["puntaje"] * 10) / $maxPuntaje;
                
                $colPuntaje->setText($container["puntaje"]);
                $sumaFinal += $calificacion;
                $numeroPreguntas++;
                $colCalificacion->setText(sprintf('%.2f', $calificacion));
                
            }else{
                $colCalificacion->setText("");
                $colPuntaje->setText("");
            }
            $colCalificacion->setWidth(50);
            $colPuntaje->setWidth(50);
            
            
            //$colCalificacion;
            $rowTable->setColumns(array($colPregunta,$colPuntaje,$colCalificacion));
            $rowTable->setCellPaddings(array(5,5,5,5));
            $rowTable->setFont($fontDefault, $fontMinSize);
            $tableContent->addRow($rowTable);
        }
        
        //print_r("SumaFinal :" .$sumaFinal);
        //print_r("NumeroPreguntas: ".$numeroPreguntas);
        
        //print_r("TotalEncuesta :".$sumaFinal/$numeroPreguntas);
        
        $promedioFinal = $sumaFinal/$numeroPreguntas;
        $resultado = "";
        
        if($promedioFinal >= 8.5){
            $resultado = "EXCELENTE";
        }elseif($promedioFinal > 7.0){
            $resultado = "ADECUADO";
        }elseif($promedioFinal > 5.0){
            $resultado = "INSUFICIENTE";
        }elseif($promedioFinal > 4.0){
            $resultado = "DEFICIENTE";
        }elseif($promedioFinal < 4.0){
            $resultado = "MARGINAL";
        }
        
        $page->drawText("Calificación: ".sprintf('%.2f', $sumaFinal/$numeroPreguntas) . " - " . $resultado, 175, 110);
        $page->drawText("Reporte generado por Zazil Consultores para: Colegio Sagrado Corazón México", 100, 800);
        
        $page->addTable($tableContent, 10, 80);
        ##### Respuestas de preguntas abiertas
        $respuestasPA = $preferencias[32]["pab"];
        
        $tableRPAB = new My_Pdf_Table(1);
        
        foreach ($respuestasPA as $key => $value) {
            $row = new My_Pdf_Table_Row;
            $colRes = new My_Pdf_Table_Column;
            $colRes->setText(utf8_decode($value));
            $colRes->setText($this->utilText->replaceHTMLSpecialChars($value));
            $colRes->setWidth(400);
            
            $row->setColumns(array($colRes));
            $row->setCellPaddings(array(5,5,5,5));
            $row->setFont($fontDefault, $fontMinSize);
            $tableRPAB->addRow($row);
        }
        
        $page->addTable($tableRPAB, 50, 350);
        
        $pdfReport->addPage($page);
        $pdfReport->saveDocument();
        
        $tablaReportesEncuesta = $this->tablaReportesEncuesta;
        $idReporte = 0;
        $select = $tablaReportesEncuesta->select()->from($tablaReportesEncuesta)->where("idAsignacionGrupo=?",$idAsignacion)->where("idEncuesta=?",$idEncuesta);
        $rowReporte = $tablaReportesEncuesta->fetchRow($select);
        if (is_null($rowReporte)) {
            $datos = array();
            //$datos["idGrupoEscolar"] = $idGrupo;
            $datos["idEncuesta"] = $idEncuesta;
            $datos["idAsignacionGrupo"] = $idAsignacion;
            //$datos["idsEvaluadores"]="";
            $datos["nombreReporte"] = $nombreArchivo;
            $datos["tipoReporte"] = "RPAB";
            $datos["rutaReporte"] = $rutaReporte;
            $datos["fecha"] = date("Y-m-d H:i:s", time());
            print_r($datos);
            $idReporte = $tablaReportesEncuesta->insert($datos);
        }else{
            $idReporte = $rowReporte->idReporte;
        }
        //print_r("idReporte: ".$idReporte);
        return $idReporte;
    }

    /**
     * 
     */
    public function obtenerReporteDocenteAutoevaluacion($idAsignacion, $idEvaluacion, $idEvaluador) {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $organizacion = $identity["organizacion"];
        
        $tablaEncuesta = $this->tablaEncuesta;
        $select = $tablaEncuesta->select()->from($tablaEncuesta)->where("idEncuesta=?",$idEvaluacion);
        $rowEncuesta = $tablaEncuesta->fetchRow($select)->toArray();
        
        // Obtenemos Evaluador
        $tablaEvaluador = $this->tablaEvaluador;
        $select = $tablaEvaluador->select()->from($tablaEvaluador)->where("idEvaluador=?",$idEvaluador);
        $rowEvaluador = $tablaEvaluador->fetchRow($select)->toArray();
        
        // Obtenemos Evaluacion
        $tablaEvaluacion = $this->tablaEvaluacionRealizada;
        $select = $tablaEvaluacion->select()->from($tablaEvaluacion)->where("idAsignacionGrupo=?", $idAsignacion)
            ->where("idEvaluacion=?", $idEvaluacion)->where("idEvaluador=?", $idEvaluador);
        
        $rowEvaluacion = $tablaEvaluacion->fetchRow($select);
        
        $resultado = $this->utilJson->processJsonEncuestaCuatro($rowEvaluacion->json);
        
        $preguntas = array();
        
        foreach ($resultado as $idPregunta => $idOpcion) {
            $pregunta = $this->preguntaDAO->getPreguntaById($idPregunta);
            $preguntas[] = $pregunta->toArray();
        }
        
        //print_r($resultado);
        
        ##### Creamos un documento con el constructor de la libreria PDF
        //$nombreArchivo = "testAutoeval.pdf";
        $nombreArchivo = "AutoEval-".$idEvaluacion."-".str_replace(" ", "", $rowEvaluador["apellidos"].$rowEvaluador["nombres"])."-".$idAsignacion."-RAUTO.pdf";
        $directorio = $organizacion["directorio"];
        $rutaReporte = '/reports/Encuesta/grupal/'.$directorio.'/Autoevaluacion';
        
        $pdfReport = new My_Pdf_Document($nombreArchivo, PDF_PATH . $rutaReporte);
        //$pdfReport->setYHeaderOffset(160);
        
        $page = $pdfReport->createPage();
        
        $fontDefault = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHIC.TTF');
        $fontDefaultBold = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHICB.TTF');
        $fontDefaultItalic = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHICI.TTF');
        $fontDefaultItalicBold = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/CenturyGothic/GOTHICI.TTF');
        
        $fontMinSize = 8;
        $fontMedSize = 8;
        $fontBigSize = 12;
        $fontTitleSize = 16;
        $fontFooterSize = 10;
        
        $styleDefault = new Zend_Pdf_Style;
        $styleDefault->setFont($fontDefault, $fontMedSize);
        
        $page->setStyle($styleDefault);
        $page->setFont($fontDefault, $fontMedSize);
        $page->drawRectangle(0, 0, 555, 785);
        
        $imgEncabezado = Zend_Pdf_Image::imageWithPath(IMAGES_PATH . '/Logo.png');
        $page->drawImage($imgEncabezado, 35, 20, 96, 58);
        //$page->addTable($this->generarHeaderGrupalHorizontal($idEncuesta, $idAsignacion), 120, 120);
        ##### Pagina configurada, generando Header
        $tableHeader = new My_Pdf_Table(2);
        $cellWidth = 200;
        
        $rowTable1 = new My_Pdf_Table_Row;
        $rowTable2 = new My_Pdf_Table_Row;
        
        $colthA1 = new My_Pdf_Table_Column;
        $colthA2 = new My_Pdf_Table_Column;
        
        $colthB1 = new My_Pdf_Table_Column;
        $colthB2 = new My_Pdf_Table_Column;
        
        $colthA1->setText("Evaluacion: ");
        $colthA1->setWidth(100);
        $colthA1->setFont($fontDefaultBold,$fontMedSize);
        $colthA2->setText($rowEncuesta['nombre']);
        
        $colthB1->setText("Evaluador: ");
        $colthB1->setWidth(100); //utf8_encode($docente['apellidos'].", ".$docente['nombres'])
        $colthB1->setFont($fontDefaultBold,$fontMedSize);
        $colthB2->setText($rowEvaluador['apellidos'].", ".$rowEvaluador['nombres']);
        
        $rowTable1->setColumns(array($colthA1,$colthA2));
        $rowTable1->setCellPaddings(array(5,5,5,5));
        $rowTable1->setFont($fontDefault,$fontMedSize);
        
        $rowTable2->setColumns(array($colthB1,$colthB2));
        $rowTable2->setCellPaddings(array(5,5,5,5));
        $rowTable2->setFont($fontDefault,$fontMedSize);
        
        $tableHeader->addRow($rowTable1);
        $tableHeader->addRow($rowTable2);
        //$tableHeader->addRow($rowTable3);
        //$tableHeader->addRow($rowTable4);
        
        $page->addTable($tableHeader, 150, 10);
        ##### Header generado y agregado, Generando información para Content
        // Traemos las secciones de la encuesta
        $tablaSeccion = $this->tablaSeccionEncuesta;
        $select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta=?", $idEvaluacion);
        $rowSecciones = $tablaSeccion->fetchAll($select)->toArray();
        
        $tablaGrupoS = $this->tablaGrupoSeccion;
        
        $container = array();
        
        foreach ($rowSecciones as $rowSeccion) {
            $select = $tablaGrupoS->select()->from($tablaGrupoS)->where("idSeccionEncuesta=?", $rowSeccion["idSeccionEncuesta"]);
            $rowsGrupos = $tablaGrupoS->fetchAll($select)->toArray();
            
            foreach ($rowsGrupos as $rowGrupo) {
                // Pregunta
                //$container[$rowGrupo["idGrupoSeccion"]][] =   
                
                foreach ($preguntas as $pregunta) {
                    if ($pregunta["origen"] == 'G' AND $pregunta["idOrigen"] == $rowGrupo["idGrupoSeccion"]) {
                        
                        $opcion = $this->opcionDAO->obtenerOpcion($resultado[$pregunta["idPregunta"]])->toArray();
                        $maxOpcion = $this->opcionDAO->obtenerOpcionMayor($opcion["idOpcionCategoria"]);
                        
                        $container[$rowGrupo["idGrupoSeccion"]][] = array("pregunta"=>$pregunta,"opcion"=>$opcion,"maxOpcion"=>$maxOpcion);
                    }
                }
            }
            
        }
        //print_r("<br /><br />");
        //print_r($container);
        
        ##### Información generada, creando content
        $tableContent = new My_Pdf_Table(3);
        
        $rowHeaderTable = new My_Pdf_Table_Row;
        $colHeaderGrupo = new My_Pdf_Table_Column;
        $colHeaderPuntaje = new My_Pdf_Table_Column;
        $colHeaderCalificacion = new My_Pdf_Table_Column;
        
        $colHeaderGrupo->setText("Área: ");
        $colHeaderGrupo->setWidth(400);
        $colHeaderGrupo->setFont($fontDefaultBold,$fontMedSize);
        $colHeaderPuntaje->setText("Puntaje");
        $colHeaderPuntaje->setWidth(50);
        $colHeaderPuntaje->setFont($fontDefaultBold,$fontMedSize);
        $colHeaderCalificacion->setText("Calificación");
        $colHeaderCalificacion->setWidth(50);
        $colHeaderCalificacion->setFont($fontDefaultBold,$fontMedSize);
        
        $rowHeaderTable->setColumns(array($colHeaderGrupo,$colHeaderPuntaje,$colHeaderCalificacion));
        $rowHeaderTable->setCellPaddings(array(5,5,5,5));
        $rowHeaderTable->setFont($fontDefault,$fontMinSize);
        //$rowHeaderTable->setBorder(BOTTOM, null);
        $tableContent->addRow($rowHeaderTable);
        
        $numeroGrupos = count($container);
        $sumaFinal = 0;
        
        foreach ($container as $idGrupo => $preguntas) {
            $rowTable = new My_Pdf_Table_Row;
            $colGrupo = new My_Pdf_Table_Column;
            $colPuntaje = new My_Pdf_Table_Column;
            $colCalificacion = new My_Pdf_Table_Column;
            
            $grupo = $this->grupoDAO->getGrupoById($idGrupo);
            
            $colGrupo->setText($grupo->getNombre());
            $colGrupo->setWidth(300);
            //$colPregunta;
            $puntaje = 0;
            $numeroPreguntas = count($preguntas);
            $valorMaximo = 5 * $numeroPreguntas; // cambiar!!! por valor en base
            
            foreach ($preguntas as $obj) {
                $puntaje += $obj["opcion"]["valorEntero"]; 
            }
            
            /**
            if ($container["pregunta"]["tipo"] == "SS") {
                $calificacion = ($container["puntaje"] * 10) / $maxPuntaje;
                
                $colPuntaje->setText($container["puntaje"]);
                $sumaFinal += $calificacion;
                $numeroPreguntas++;
                $colCalificacion->setText(sprintf('%.2f', $calificacion));
                
            }else{
                $colCalificacion->setText("");
                $colPuntaje->setText("");
            }
            */
            $calificacion = (10 * $puntaje) / $valorMaximo;
            $sumaFinal += $calificacion;
            $colCalificacion->setText(sprintf('%.2f', $calificacion));
            $colPuntaje->setText($puntaje);
            $colCalificacion->setWidth(50);
            $colPuntaje->setWidth(50);
            
            
            //$colCalificacion;
            $rowTable->setColumns(array($colGrupo,$colPuntaje,$colCalificacion));
            $rowTable->setCellPaddings(array(5,5,5,5));
            $rowTable->setFont($fontDefault, $fontMinSize);
            $tableContent->addRow($rowTable);
        }
        
        $page->addTable($tableContent, 30, 100);
        
        $promedioFinal = $sumaFinal/$numeroGrupos;
        $resultado = "";
        
        if($promedioFinal >= 8.5){
            $resultado = "EXCELENTE";
        }elseif($promedioFinal > 7.0){
            $resultado = "ADECUADO";
        }elseif($promedioFinal > 5.0){
            $resultado = "INSUFICIENTE";
        }elseif($promedioFinal > 4.0){
            $resultado = "DEFICIENTE";
        }elseif($promedioFinal < 4.0){
            $resultado = "MARGINAL";
        }
        
        $page->drawText("Calificación: ".sprintf('%.2f', $promedioFinal) . " - " . $resultado, 175, 110);
        $page->drawText("Reporte generado por Zazil Consultores para: Colegio Sagrado Corazón México", 100, 800);
        
        $pdfReport->addPage($page);
        $pdfReport->saveDocument();
        
        $tablaReportesEncuesta = $this->tablaReportesEncuesta;
        $idReporte = 0;
        $select = $tablaReportesEncuesta->select()->from($tablaReportesEncuesta)->where("idAsignacionGrupo=?",$idAsignacion)->where("idEncuesta=?",$idEvaluacion)
            ->where("nombreReporte=?", $nombreArchivo);
        $rowReporte = $tablaReportesEncuesta->fetchRow($select);
        if (is_null($rowReporte)) {
            $datos = array();
            //$datos["idGrupoEscolar"] = $idGrupo;
            $datos["idEncuesta"] = $idEvaluacion;
            $datos["idAsignacionGrupo"] = $idAsignacion;
            //$datos["idsEvaluadores"]="";
            $datos["nombreReporte"] = $nombreArchivo;
            $datos["tipoReporte"] = "RAUT";
            $datos["rutaReporte"] = $rutaReporte;
            $datos["fecha"] = date("Y-m-d H:i:s", time());
            print_r($datos);
            $idReporte = $tablaReportesEncuesta->insert($datos);
        }else{
            $idReporte = $rowReporte->idReporte;
        }
        //print_r("idReporte: ".$idReporte);
        return $idReporte;
        
    }
}
