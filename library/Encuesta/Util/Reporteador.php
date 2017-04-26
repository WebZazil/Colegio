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
        ##### Creamos un documento con el constructor de la libreria PDF
        $nombreArchivo = "testOrientadora.pdf";
        $directorio = $organizacion["directorio"];
        $rutaReporte = '/reports/Encuesta/grupal/'.$directorio.'/Orientadora/';
        
        $pdfReport = new My_Pdf_Document($nombreArchivo, PDF_PATH . $rutaReporte);
        $pdfReport->setYHeaderOffset(160);
        
        $page = $pdfReport->createPage();
        
        //$fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $fontDefault = Zend_Pdf_Font::fontWithPath(FONT_PATH.'/jura_font_6126/JuraBook.ttf');
        $styleDefault = new Zend_Pdf_Style;
        $styleDefault->setFont($fontDefault, 10);
        
        $page->setStyle($styleDefault);
        $page->setFont($fontDefault, 10);
        $page->addTable($this->generarHeaderGrupalHorizontal($idEncuesta, $idAsignacion), 120, 120);
        ##### Pagina configurada, generando Header
        
        
    }
}
