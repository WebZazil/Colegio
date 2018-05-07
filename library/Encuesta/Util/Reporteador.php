<?php
/**
 * 
 * @author EnginnerRodriguez
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
    private $tableDocente;
    private $tableEncuestasRealizadas;
    
    private $organizacion;
    private $tableResumenEvaluacion;
    private $tableCategoriaRespuesta;
	
	public function __construct($dbAdapter) {
	    $config = array('db' => $dbAdapter);
	    
	    $auth = Zend_Auth::getInstance();
	    $identity = $auth->getIdentity();
	    
	    $this->organizacion = $identity['organizacion'];
	    
		$this->tablaEncuesta = new Encuesta_Data_DbTable_Encuesta($config);
        $this->tablaSeccionEncuesta = new Encuesta_Data_DbTable_SeccionEncuesta($config);
        $this->tablaGrupoSeccion = new Encuesta_Data_DbTable_GrupoSeccion($config);
        $this->tablaPregunta = new Encuesta_Data_DbTable_Pregunta($config);
        $this->tablaOpcion = new Encuesta_Data_DbTable_OpcionCategoria($config);
        
        $this->tablaAsignacion = new Encuesta_Data_DbTable_AsignacionGrupo($config);
        $this->tablaMateriaEscolar = new Encuesta_Data_DbTable_MateriaEscolar($config);
        $this->tablaRegistro = new Encuesta_Data_DbTable_Registro($config);
        $this->tablaGrupoEscolar = new Encuesta_Data_DbTable_GrupoEscolar($config);
        $this->tablaGradoEscolar = new Encuesta_Data_DbTable_GradoEducativo($config);
        $this->tablaNivelEducativo = new Encuesta_Data_DbTable_NivelEducativo($config);
        
        $this->tablaConjunto = new Encuesta_Data_DbTable_ConjuntoEvaluador($config);
        $this->tablaEvaluacionConjunto = new Encuesta_Data_DbTable_EvaluacionConjunto($config);
        $this->tablaEvaluacionRealizada = new Encuesta_Data_DbTable_EvaluacionRealizada($config);
        
        $this->utilJson = new Encuesta_Util_Json;
        $this->utilText = new Encuesta_Util_Text;
        $this->opcionDAO = new Encuesta_DAO_Opcion($dbAdapter);
        $this->preguntaDAO = new Encuesta_DAO_Pregunta($dbAdapter);
        $this->grupoDAO = new Encuesta_DAO_Grupo($dbAdapter);
        
        $this->tablaEvaluador = new Encuesta_Data_DbTable_Evaluador($config);
        $this->tableDocente = new Encuesta_Data_DbTable_Docente($config);
        $this->tablaReportesEncuesta = new Encuesta_Data_DbTable_ReportesEncuesta($config);
        $this->tableEncuestasRealizadas = new Encuesta_Data_DbTable_EncuestasRealizadas($config);
        // ========================================================================================================== Improvements
        $this->tableResumenEvaluacion = new Encuesta_Data_DbTable_ResumenEvaluacion($config);
        $this->tableCategoriaRespuesta = new Encuesta_Data_DbTable_CategoriasRespuesta($config);
	}

    /**
     * 
     * @param int $idAsignacion 
     * @param int $idEncuesta 
     * @return number|mixed|array 
     */
    public function generarReporteGrupal($idAsignacion, $idEncuesta) {
        $tEn = $this->tablaEncuesta;
        $tSE = $this->tablaSeccionEncuesta;
        $tGS = $this->tablaGrupoSeccion;
        $tP = $this->tablaPregunta;
        
        $tAG = $this->tablaAsignacion;
        $tD = $this->tableDocente;
        $tME = $this->tablaMateriaEscolar;
        $tGE = $this->tablaGrupoEscolar;
        
        $tGrEd = $this->tablaGradoEscolar;
        $tNE = $this->tablaNivelEducativo;
        
        $tReEv = $this->tableResumenEvaluacion;
        // ========================================================================================================== Datos
        $select = $tEn->select()->from($tEn)->where("idEncuesta=?",$idEncuesta);
        $encuesta = $tEn->fetchRow($select)->toArray();
        
        $select = $tSE->select()->from($tSE)->where('idEncuesta=?',$encuesta['idEncuesta']);
        $secciones = $tSE->fetchAll($select)->toArray();
        
        $contenedorGrupos = array();
        foreach ($secciones as $seccion){
            $select = $tGS->select()->from($tGS)->where('idSeccionEncuesta=?',$seccion['idSeccionEncuesta']);
            $gruposSeccion = $tGS->fetchAll($select)->toArray();
            $contenedorGrupos[$seccion['idSeccionEncuesta']]['seccion'] = $seccion;
            $contenedorGrupos[$seccion['idSeccionEncuesta']]['grupos'] = $gruposSeccion;
            // print_r($gruposSeccion); print_r('<br /><br />');
        }
        
        $select = $tP->select()->from($tP)->where('idEncuesta=?',$encuesta['idEncuesta']);
        $preguntasEncuesta = $tP->fetchAll($select)->toArray();
        
        // Obtenemos Asignacion y sus componentes: Docente,MateriaEscolar y GrupoEscolar
        $select = $tAG->select()->from($tAG)->where("idAsignacionGrupo=?",$idAsignacion);
        $asignacion = $tAG->fetchRow($select)->toArray();
        
        $select = $tD->select()->from($tD)->where('idDocente=?',$asignacion['idDocente']);
        $docente = $tD->fetchRow($select)->toArray();
        
        $select = $tME->select()->from($tME)->where('idMateriaEscolar=?',$asignacion['idMateriaEscolar']);
        $materiaEscolar = $tME->fetchRow($select)->toArray();
        
        $select = $tGE->select()->from($tGE)->where('idGrupoEscolar=?',$asignacion['idGrupoEscolar']);
        $grupoEscolar = $tGE->fetchRow($select)->toArray();
        
        
        $select = $tGrEd->select()->from($tGrEd)->where('idGradoEducativo=?',$grupoEscolar['idGradoEducativo']);
        $gradoEducativo = $tGrEd->fetchRow($select)->toArray();
        
        $select = $tNE->select()->from($tNE)->where('idNivelEducativo=?',$gradoEducativo['idNivelEducativo']);
        $nivelEducativo = $tNE->fetchRow($select)->toArray();
        
        $tCatRes = $this->tableCategoriaRespuesta;
        $categorias = $tCatRes->fetchAll()->toArray();
        
        $tOpCat = $this->tablaOpcion;
        
        $contenedorCategorias = array();
        
        foreach ($categorias as $categoria) {
            $obj = array();
            $obj['categoria'] = $categoria;
            
            $select = $tOpCat->select()->from($tOpCat)->where('idOpcionCategoria IN (?)',explode(',', $categoria['idsOpciones']));
            $opcionesCategoria = $tOpCat->fetchAll($select)->toArray();
            $obj['opciones'] = $opcionesCategoria;
            // Calcular maxOpcion y minOpcion
            foreach ($opcionesCategoria as $opcionCategoria){
                if ($opcionCategoria['idOpcionCategoria'] == $categoria['idOpcionMayor']) {
                    $obj['maxOpcion'] = $opcionCategoria;
                }
                
                if ($opcionCategoria['idOpcionCategoria'] == $categoria['idOpcionMenor']) {
                    $obj['minOpcion'] = $opcionCategoria;
                }
            }
            
            $contenedorCategorias[] = $obj;
        }
        
        // Obtenemos resumen
        $select = $tReEv->select()->from($tReEv)->where('idEncuesta=?',$idEncuesta)->where('idAsignacionGrupo=?',$idAsignacion);
        $resumen = $tReEv->fetchRow($select)->toArray();
        // ========================================================================================================== Reporte
        $patronNombre = $grupoEscolar['grupoEscolar']."-".$idEncuesta."-".$docente['apellidos'].$docente['nombres']."-".$idAsignacion.".pdf";
        $nombreArchivo = $this->utilText->cleanString(str_replace(" ", "", $patronNombre));
        
        $directorio = $this->organizacion["directorio"];
        // $rutaReporte = '/reports/idModule/grupal/1775418046/6010-3-rodriguez-juanita-12343.pdf'
        $rutaReporte = '/reports/Encuesta/grupal/'.$directorio;
        $arch = PDF_PATH . $rutaReporte.'/'.$nombreArchivo;
        // print_r('<br />');
        // print_r($arch);
        // print_r('<br />');
        
        $tRepEnc = $this->tablaReportesEncuesta;
        $idReporte = 0;
        $select = $tRepEnc->select()->from($tRepEnc)->where("idAsignacionGrupo=?",$idAsignacion)->where("idEncuesta=?",$idEncuesta);
        $rowRepEnc = $tRepEnc->fetchRow($select);
        
        if (!file_exists($arch)) {
            print_r('No existe');
            $reportePDF = new My_Pdf_Document($nombreArchivo, PDF_PATH . $rutaReporte);
            $fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $anchoCelda = 75;
            $styleDefault = new Zend_Pdf_Style;
            $styleDefault->setFont($fontDefault, 12);
            
            $pagina = new My_Pdf_Page('letter-landscape');
            $pagina->setStyle($styleDefault);
            $pagina->setFont($fontDefault, 12);
            $imagenEncabezado = Zend_Pdf_Image::imageWithPath(IMAGES_PATH . '/Logo.png');
            //$page->drawImage($imgEncabezado, 30, 40, 0, 0);
            //Imagen reducida al 65%
            //$page->drawImage($imgEncabezado, 35, 20, 720, 97.5);
            $pagina->drawImage($imagenEncabezado, 140, 30, 145, 89);
            // ========================================================================================================== Reporte Membrete
            // Tabla Membrete
            $tMembreteTitulos = new My_Pdf_Table(1);
            // Fila Header
            $rowMembreteHeader = new My_Pdf_Table_HeaderRow();
            $rowMembreteHeader->setFont($fontDefault, 28);
            
            // Fila Content
            $rowContent = new My_Pdf_Table_Row();
            $rowContent->setFont($fontDefault, 28);
            
            // Columnas del header
            $colMH1 = new My_Pdf_Table_Column;
            $colMH2 = new My_Pdf_Table_Column;
            
            $colMH1->setText("Evaluación de desempeño");
            $colMH1->setWidth(405);
            $colMH2->setText("2017 - 2018");
            $colMH2->setWidth(405);
            // Contenedores de columnas de headers y contents de la tabla
            $columnsHeaders = array($colMH1);
            $columnsContents = array($colMH2);
            
            $rowMembreteHeader->setColumns($columnsHeaders);
            $rowMembreteHeader->setCellPaddings(array(5,5,5,55));
            
            $rowContent->setColumns($columnsContents);
            $rowContent->setCellPaddings(array(5,5,5,100));
            
            $tMembreteTitulos->setHeader($rowMembreteHeader);
            $tMembreteTitulos->addRow($rowContent);
            // Columnas del content
            $pagina->addTable($tMembreteTitulos, 330, 40);
            
            // ========================================================================================================== Reporte Header
            // Tabla Header
            $tableHeader = new My_Pdf_Table(2);
            $cellWidth = 200;
            
            $rowTable1 = new My_Pdf_Table_Row;
            $rowTable2 = new My_Pdf_Table_Row;
            $rowTable3 = new My_Pdf_Table_Row;
            $rowTable4 = new My_Pdf_Table_Row;
            
            $colthA1 = new My_Pdf_Table_Column;
            $colthA2 = new My_Pdf_Table_Column;
            $colthB1 = new My_Pdf_Table_Column;
            $colthB2 = new My_Pdf_Table_Column;
            $colthC1 = new My_Pdf_Table_Column;
            $colthC2 = new My_Pdf_Table_Column;
            $colthD1 = new My_Pdf_Table_Column;
            $colthD2 = new My_Pdf_Table_Column;
            
            $colthA1->setText("Evaluación: ");
            $colthA1->setWidth($cellWidth);
            $colthA2->setText(utf8_encode($encuesta['nombre']));
            
            $colthB1->setText("Docente: ");
            $colthB1->setWidth($cellWidth);
            $colthB2->setText($docente['apellidos'].", ".$docente['nombres']);
            
            $colthC1->setText("Nivel, Grado: ");
            $colthC1->setWidth($cellWidth);
            $colthC2->setText($nivelEducativo["nivelEducativo"].", ".$gradoEducativo["gradoEducativo"]);
            
            $colthD1->setText("Grupo, Materia: ");
            $colthD1->setWidth($cellWidth);
            $colthD2->setText("Grupo: ".$grupoEscolar["grupoEscolar"].", ".$materiaEscolar['materiaEscolar']);
            
            $rowTable1->setColumns(array($colthA1,$colthA2));
            $rowTable1->setCellPaddings(array(5,5,5,25));
            $rowTable1->setFont($fontDefault,12);
            
            $rowTable2->setColumns(array($colthB1,$colthB2));
            $rowTable2->setCellPaddings(array(5,5,5,25));
            $rowTable2->setFont($fontDefault,12);
            
            $rowTable3->setColumns(array($colthC1,$colthC2));
            $rowTable3->setCellPaddings(array(5,5,5,25));
            $rowTable3->setFont($fontDefault,12);
            
            $rowTable4->setColumns(array($colthD1,$colthD2));
            $rowTable4->setCellPaddings(array(5,5,5,25));
            $rowTable4->setFont($fontDefault,12);
            
            $tableHeader->addRow($rowTable1);
            $tableHeader->addRow($rowTable2);
            $tableHeader->addRow($rowTable3);
            $tableHeader->addRow($rowTable4);
            
            $pagina->addTable($tableHeader, 120, 130);
            // ========================================================================================================== Reporte Content
            $totalGrupos = 0;
            $maxPuntaje = 0;
            $puntajeObtenido = 0;
            $promedioFinal = 0;
            
            foreach ($contenedorGrupos as $contenedorGrupo) {
                $seccion = $contenedorGrupo['seccion'];
                $grupos = $contenedorGrupo['grupos'];
                $totalGrupos = count($grupos);
                
                $tableContent = new My_Pdf_Table($totalGrupos+1);
                
                $rowHeader = new My_Pdf_Table_HeaderRow();
                $rowHeader->setFont($fontDefault, 12);
                
                $rowContent = new My_Pdf_Table_Row();
                $rowContent->setFont($fontDefault, 12);
                // =========================================================================== Obtener nombres por grupo
                $contenedorHeaders = array();
                foreach ($grupos as $grupo) {
                    $colHeader = new My_Pdf_Table_Column;
                    $colHeader->setText($grupo['nombre']);
                    $colHeader->setWidth($anchoCelda);
                    
                    $contenedorHeaders[] = $colHeader;
                }
                // =========================================================================== Obtener puntaje por grupo
                $arrayResumen = json_decode($resumen['resumen'],true); // Obtenemos puntaje grupal obtenido de la evaluacion 
                $puntajesGrupo = array();
                // Iteramos grupos para encontrar los puntajes por grupo
                foreach ($grupos as $grupo) {
                    // Obtendremos la categoria del grupo
                    $categoriaGrupo = null;
                    
                    foreach ($contenedorCategorias as $contCategoria) {
                        // si el grupo tiene las mismas opciones que la categoria la habremos encontrado (esto puede fallar si el grupo no tiene todas las opciones de categoria)
                        if ($contCategoria['categoria']['idsOpciones'] == $grupo['opciones']) {
                            $categoriaGrupo = $contCategoria;
                        }
                    }
                    print_r($contCategoria); print_r('<br />');
                    
                    $objGrupo = array();
                    $objGrupo['grupo'] = $grupo;
                    $preguntasGrupo = array();
                    $puntajeGrupo = 0.0;
                    // ======================================================================= Filtrar preguntas del grupo
                    foreach ($preguntasEncuesta as $preguntaEncuesta) {
                        if ($preguntaEncuesta['idOrigen'] == $grupo['idGrupoSeccion']) {
                            $preguntasGrupo[] = $preguntaEncuesta;
                        }
                    }
                    
                    $totalPreguntasGrupo = count($preguntasGrupo);
                    print_r('TotalPreguntas: '.$totalPreguntasGrupo); print_r('<br />');
                    // ======================================================================= Sumar puntajes de grupo
                    foreach ($arrayResumen as $idPregunta => $puntaje) {
                        foreach ($preguntasGrupo as $preguntaGrupo){
                            if ($preguntaGrupo['idPregunta'] == $idPregunta) {
                                $puntajeGrupo += $puntaje;
                            }
                        }
                    }
                    // =======================================================================
                    print_r('PuntajeGrupo: '.$puntajeGrupo); print_r('<br />');
                    $puntajeObtenido += $puntajeGrupo;
                    $objGrupo['puntaje'] = $puntajeGrupo;
                    print_r('MaxOpcion: '.$categoriaGrupo['maxOpcion']['valorEntero']); print_r('<br />');
                    $max = $totalPreguntasGrupo * $categoriaGrupo['maxOpcion']['valorEntero'] * $resumen['numEvals'];
                    $maxPuntaje += $max;
                    print_r("Max Puntaje Grupo: ". $max);
                    $objGrupo['maxPuntaje'] = $max;
                    
                    $puntajesGrupo[] = $objGrupo;
                }
                
                
                // ===========================================================================
                $contenedorContent = array();
                foreach ($puntajesGrupo as $puntajeGrupo) {
                    $puntaje = ($puntajeGrupo['puntaje'] * 10) / $puntajeGrupo['maxPuntaje'];
                    $colContent = new My_Pdf_Table_Column;
                    $colContent->setText(sprintf('%.2f', $puntaje));
                    $colContent->setWidth($anchoCelda);
                    
                    $contenedorContent[] = $colContent;
                }
                // ===========================================================================
                $rowHeader->setColumns($contenedorHeaders);
                $rowHeader->setCellPaddings(array(5,5,5,5));
                
                $rowContent->setColumns($contenedorContent);
                $rowContent->setCellPaddings(array(5,5,5,25));
                
                $tableContent->setHeader($rowHeader);
                $tableContent->addRow($rowContent);
                
                $pagina->addTable($tableContent, 60, 240);
                
            }
            // ========================================================================================================== Total
            $promedioFinal = (10 * $puntajeObtenido) / $maxPuntaje;
            
            $resultado = "";
            if($promedioFinal >= 8.5){
                $resultado = "EXCELENTE";
            }elseif($promedioFinal >= 7.0){
                $resultado = "ADECUADO";
            }elseif($promedioFinal >= 5.0){
                $resultado = "INSUFICIENTE";
            }elseif($promedioFinal >= 4.0){
                $resultado = "DEFICIENTE";
            }elseif($promedioFinal < 4.0){
                $resultado = "MARGINAL";
            }
            
            $tableTotal = new My_Pdf_Table(2);
            $cellWidth = 100;
            
            $rowTT1 = new My_Pdf_Table_Row;
            
            $colThT1 = new My_Pdf_Table_Column;
            $colThT2 = new My_Pdf_Table_Column;
            
            $colThT1->setText("Promedio: ");
            $colThT1->setWidth($cellWidth);
            
            $colThT2->setText(sprintf('%.2f', $promedioFinal) .' ' . $resultado);
            $colThT2->setWidth($cellWidth);
            
            $rowTT1->setColumns(array($colThT1,$colThT2));
            $rowTT1->setCellPaddings(array(5,5,5,5));
            $rowTT1->setFont($fontDefault,16);
            // $rowTT1->setBorder(My_Pdf::TOP);
            // $rowTT1->setBorder(My_Pdf::BOTTOM);
            // $rowTT1->setBorder(My_Pdf::RIGHT);
            // $rowTT1->setBorder(My_Pdf::LEFT);
            $tableTotal->setHeader($rowTT1);
            $pagina->addTable($tableTotal, 240, 340);
            
            // ========================================================================================================== Vendor Name
            
            $tableVendor = new My_Pdf_Table(1);
            $cellWidth = 800;
            $rowVT1 = new My_Pdf_Table_Row;
            
            $colThV1 = new My_Pdf_Table_Column;
            
            $colThV1->setText("Reportes Generados por Zazil Consultores S.A. de C.V. para Colegio Sagrado Corazón México");
            $colThV1->setWidth($cellWidth);
            
            $rowVT1->setColumns(array($colThV1));
            $rowVT1->setCellPaddings(array(5,5,5,25));
            $rowVT1->setFont($fontDefault,12);
            $tableVendor->setHeader($rowVT1);
            $pagina->addTable($tableVendor, 120, 560);
            // ========================================================================================================== Save Document and Insert in DB
            $reportePDF->addPage($pagina);
            $reportePDF->saveDocument();
            
            $datos = array();
            
            $datos["idEncuesta"] = $idEncuesta;
            $datos["idAsignacionGrupo"] = $idAsignacion;
            $datos["nombreReporte"] = $nombreArchivo;
            $datos["tipoReporte"] = "RGRU";
            $datos["rutaReporte"] = $rutaReporte."/";
            $datos["creacion"] = date("Y-m-d H:i:s", time());
            
            print_r($datos);
            
            $idReporte = $tRepEnc->insert($datos);
            return $idReporte;
        }else{
            print_r('Existe');
            return $rowRepEnc['idReporte'];
        }
    }
    
    /**
     * 
     */
    public function generarReporteGrupalAsignacion($idGrupo, $idAsignacion, $idEncuesta) {
        print_r('En generarReporteGrupalAsignacion');
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
        
        //Lo guardamos en la tabla Reportes Grupo
        //$tablaReportesGrupo = $this->tablaReportesGrupo;
        $nombreReporte = $this->utilText->cleanString(str_replace(" ", "", $this->nombreReporte));
        $archivo = $this->rutaReporte.'/'.$nombreReporte;
        
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
            $datos["nombreReporte"] = $nombreReporte;
            $datos["tipoReporte"] = "RGRU";
            $datos["rutaReporte"] = $this->rutaReporte."/";
            $datos["creacion"] = date("Y-m-d H:i:s", time());
            print_r($datos);
            $idReporte = $tablaReportesEncuesta->insert($datos);
        }else{
            $idReporte = $rowReporte->idReporte;
        }
        
        if (!file_exists($archivo)) {
            $reporte->saveDocument();
            print_r($archivo);
        }
            
        return $idReporte;
    }
    
    /**
     * 
     * @param array $infoTitulos
     * @param array $infoContent
     */
    public function generarMembreteReporte(array $infoTitulos, array $infoContent, My_Pdf_Page $pagina) {
        print_r('En: generarMembreteReporte <br />');
        $fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $anchoCelda = 75;
        // Tabla
        $tMembreteTitulos = new My_Pdf_Table(3);
        // Fila Header
        $rowMembreteHeader = new My_Pdf_Table_HeaderRow();
        $rowMembreteHeader->setFont($fontDefault, 32);
        // Fila Content
        $rowContent = new My_Pdf_Table_Row();
        $rowContent->setFont($fontDefault, 10);
        
        // Columnas del header
        $colMH1 = new My_Pdf_Table_Column;
        $colMH2 = new My_Pdf_Table_Column;
        
        $colMH1->setText("Mi titulo de membrete feliz: ");
        // $colMH1->setWidth($anchoCelda);
        $colMH2->setText("Mi sub-titulo de membrete feliz: ");
        // Contenedores de columnas de headers y contents de la tabla
        $columnsHeaders = array($colMH1);
        $columnsContents = array($colMH2);
        
        $rowMembreteHeader->setColumns($columnsHeaders);
        $rowMembreteHeader->setCellPaddings(array(5,5,5,5));
        
        $rowContent->setColumns($columnsContents);
        $rowContent->setCellPaddings(array(5,5,5,5));
        
        //$tMembreteTitulos->addRow($rowMembreteHeader);
        $tMembreteTitulos->setHeader($rowMembreteHeader);
        $tMembreteTitulos->addRow($rowContent);
        // Columnas del content
        $pagina->addTable($tMembreteTitulos, 0, 0);
        
        return $pagina;
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
        $tD = $this->tableDocente;
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
        
        //$select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro=?",$rowAsignacion["idRegistro"]);
        $select = $tD->select()->from($tD)->where("idDocente=?",$rowAsignacion["idDocente"]);
        $rowD = $tD->fetchRow($select)->toArray();
        
        $select = $tablaGrado->select()->from($tablaGrado)->where("idGradoEducativo=?",$rowMateria["idGradoEducativo"]);
        $rowGrado = $tablaGrado->fetchRow($select)->toArray();
        
        $select = $tablaNivel->select()->from($tablaNivel)->where("idNivelEducativo=?",$rowGrado["idNivelEducativo"]);
        $rowNivelE = $tablaNivel->fetchRow($select)->toArray();
        ##### Creamos un documento con el constructor de la libreria PDF
        //$nombreArchivo = "testOrientadora.pdf";
        $nombreArchivo = $rowGrupoE["grupoEscolar"]."-".$idEncuesta."-".str_replace(" ", "", $rowD["apellidos"].$rowD["nombres"])."-".$idAsignacion."-RGPO.pdf";
        $nombreArchivo = $this->utilText->cleanString(str_replace(" ", "", $nombreArchivo));
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
        $colthB2->setText($rowD['apellidos'].", ".$rowD['nombres']);
        
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
        $select = $tablaEvalReal->select()->from($tablaEvalReal)->where("idEncuesta=?",$idEncuesta)->where("idAsignacionGrupo=?",$idAsignacion);
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
            $datos["nombreReporte"] = str_replace(" ", "", $nombreArchivo);
            $datos["tipoReporte"] = "RPAB";
            $datos["rutaReporte"] = $rutaReporte;
            $datos["creacion"] = date("Y-m-d H:i:s", time());
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
        //$page->drawRectangle(0, 0, 555, 785);
        
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
