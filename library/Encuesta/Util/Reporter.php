<?php
/**
 * 
 */
class Encuesta_Util_Reporter {
	
	private $tablaRegistro;
	
	private $tablaEncuesta;
	private $tablaSeccion;
	private $tablaGrupo;
	private $tablaPregunta;
	
	private $tablaRespuesta;
	private $tablaPreferenciaS;
	private $tablaOpcion;
	
	private $tablaPlanE;
	private $tablaCicloE;
	private $tablaNivelE;
	private $tablaGradoE;
	
	private $tablaMateriaE;
	private $tablaGrupoE;
	private $tablaAsignacionG;
	
	private $tablaERealizadas;
	private $reporteDAO;
    private $tablaReportesEncuesta;
    
    private $reporterDAO = null;
    private $organizacion = null;
	
	public function __construct($dbAdapter) {
	    $auth = Zend_Auth::getInstance();
        $dataIdentity = $auth->getIdentity();
        $this->organizacion = $dataIdentity["organizacion"];
		
		$this->tablaRegistro = new Encuesta_Model_DbTable_Registro(array('db'=>$dbAdapter));
		
		$this->tablaEncuesta = new Encuesta_Model_DbTable_Encuesta(array('db'=>$dbAdapter));
		$this->tablaSeccion = new Encuesta_Model_DbTable_SeccionEncuesta(array('db'=>$dbAdapter));
		$this->tablaGrupo = new Encuesta_Model_DbTable_GrupoSeccion(array('db'=>$dbAdapter));
		$this->tablaPregunta = new Encuesta_Model_DbTable_Pregunta(array('db'=>$dbAdapter));
		
		$this->tablaRespuesta = new Encuesta_Model_DbTable_Respuesta(array('db'=>$dbAdapter));
		$this->tablaPreferenciaS = new Encuesta_Model_DbTable_PreferenciaSimple(array('db'=>$dbAdapter));
		$this->tablaOpcion = new Encuesta_Model_DbTable_OpcionCategoria(array('db'=>$dbAdapter));
		
		$this->tablaPlanE = new Encuesta_Model_DbTable_PlanEducativo(array('db'=>$dbAdapter));
		$this->tablaCicloE = new Encuesta_Model_DbTable_CicloEscolar(array('db'=>$dbAdapter));
		$this->tablaNivelE = new Encuesta_Model_DbTable_NivelEducativo(array('db'=>$dbAdapter));
		$this->tablaGradoE = new Encuesta_Model_DbTable_GradoEducativo(array('db'=>$dbAdapter));
		
		$this->tablaMateriaE = new Encuesta_Model_DbTable_MateriaEscolar(array('db'=>$dbAdapter));
		$this->tablaGrupoE = new Encuesta_Model_DbTable_GrupoEscolar(array('db'=>$dbAdapter));
		$this->tablaAsignacionG = new Encuesta_Model_DbTable_AsignacionGrupo(array('db'=>$dbAdapter));
		
		$this->tablaERealizadas = new Encuesta_Model_DbTable_EncuestasRealizadas(array('db'=>$dbAdapter));
        $this->tablaReportesEncuesta = new Encuesta_Model_DbTable_ReportesEncuesta(array('db'=>$dbAdapter));
        
		$this->reporteDAO = new Encuesta_DAO_Reporte($dbAdapter);
        
        $this->reporterDAO = new Encuesta_DAO_Reporter($dbAdapter);
	}
	/**
	 * Genera un reporte de evaluacion de las preguntas de simple seleccion
	 */
	public function generarReporteGrupalEvaluacionAsignacion($idEncuesta, $idAsignacion)
	{
		// ========================================================== >>> Obtenemos los objetos estaticos basicos
		$reporterDAO = $this->reporterDAO;
		$select = $this->tablaEncuesta->select()->from($this->tablaEncuesta)->where("idEncuesta=?",$idEncuesta);
		$rowEncuesta = $this->tablaEncuesta->fetchRow($select);
		
		$select = $this->tablaAsignacionG->select()->from($this->tablaAsignacionG)->where("idAsignacionGrupo=?",$idAsignacion);
		$rowAsignacion = $this->tablaAsignacionG->fetchRow($select);
		
		$select = $this->tablaGrupoE->select()->from($this->tablaGrupoE)->where("idGrupoEscolar=?",$rowAsignacion->idGrupoEscolar);
		$rowGrupoE = $this->tablaGrupoE->fetchRow($select);
		
		$select = $this->tablaMateriaE->select()->from($this->tablaMateriaE)->where("idMateriaEscolar=?",$rowAsignacion->idMateriaEscolar);
		$rowMateriaE = $this->tablaMateriaE->fetchRow($select);
		
		$select = $this->tablaRegistro->select()->from($this->tablaRegistro)->where("idRegistro=?",$rowAsignacion->idRegistro);
		$rowDocente = $this->tablaRegistro->fetchRow($select);
		
		//$nombreArchivo = $rowGrupoE->grupo."-".str_replace(' ', '', $rowMateriaE->materia)."-".mb_strlen(str_replace(' ', '', $rowEncuesta->nombre)).".pdf";
		$nombreArchivo = $rowGrupoE->grupoEscolar."-".$idAsignacion."-".$idEncuesta."-RGP.pdf";
		
		//print_r("Cargando plantilla");
		//print_r("<br />");
		// ========================================================== >>> Generamos el reporte a partir de plantilla
		//print_r(PDF_PATH);
		$pdfTemplate = My_Pdf_Document::load(PDF_PATH . '/reports/bases/reporteBaseEncuestas.pdf');
		$pages = $pdfTemplate->pages;
		$pdfReport = new My_Pdf_Document($nombreArchivo, PDF_PATH . '/reports/encuesta/grupal/');
		$pdfReport->setYHeaderOffset(160);
		// Clonamos 
		$pageZ = clone $pages[0];
		$page = new My_Pdf_Page($pageZ);
		$font = Zend_Pdf_Font::fontWithPath(PDF_PATH . "/fonts/microsoft/GOTHIC.TTF");
		$fontBold = Zend_Pdf_Font::fontWithPath(PDF_PATH . "/fonts/microsoft/GOTHICB.TTF");
		$page->setFont($font, 10);
		
		//print_r("Generando contenido :: Header");
		//print_r("<br />");
		// ========================================================== >>> Generamos header del reporte.
		$tableHeader = new My_Pdf_Table(2);
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
		$colthA1->setWidth(60);
		$colthA2->setText($rowEncuesta->nombre);
		$colthB1->setText("Docente: ");
		$colthB1->setWidth(60);
		$colthB2->setText($rowDocente->apellidos.", ".$rowDocente->nombres);
		$colthC1->setText("Materia: ");
		$colthC1->setWidth(60);
		$colthC2->setText($rowMateriaE->materiaEscolar);
		$colthD1->setText("Grupo: ");
		$colthD1->setWidth(60);
		$colthD2->setText($rowGrupoE->grupoEscolar);
		
		$rowTable1->setColumns(array($colthA1,$colthA2));
		$rowTable1->setCellPaddings(array(5,5,5,5));
		$rowTable1->setFont($font,10);
		$rowTable2->setColumns(array($colthB1,$colthB2));
		$rowTable2->setCellPaddings(array(5,5,5,5));
		$rowTable2->setFont($font,10);
		$rowTable3->setColumns(array($colthC1,$colthC2));
		$rowTable3->setCellPaddings(array(5,5,5,5));
		$rowTable3->setFont($font,10);
		$rowTable4->setColumns(array($colthD1,$colthD2));
		$rowTable4->setCellPaddings(array(5,5,5,5));
		$rowTable4->setFont($font,10);
		
		$tableHeader->addRow($rowTable1);
		$tableHeader->addRow($rowTable2);
		$tableHeader->addRow($rowTable3);
		$tableHeader->addRow($rowTable4);
		
		$page->addTable($tableHeader, 98, 120);
		
		//print_r("Generando contenido :: Cuerpo");
		//print_r("<br />");
		// ========================================================== >>> Generamos el cuerpo del reporte
		$tablaContenidoR = new My_Pdf_Table(2);
		$promedioFinal = 0;
		$sumaFinal = 0;
		$numCategorias = 0;
		$tablaSeccion = $this->tablaSeccion;
		$tablaGrupo = $this->tablaGrupo;
		$tablaPregunta = $this->tablaPregunta;
		$tablaRespuesta = $this->tablaRespuesta;
		$tablaOpcion = $this->tablaOpcion;
		$tablaPreferenciaS = $this->tablaPreferenciaS;
		
		//$select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta=?",$rowEncuesta->idEncuesta);
		$select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta=?",$idEncuesta);
		
		$rowsSecciones = $tablaSeccion->fetchAll($select);
		//$grupos = array();
		//$tablaGrupo = $this->tablaGrupo;
		//$select = $tablaGrupo->select()->from($tablaGrupo)->where("idGrupo=?",)
		
		foreach ($rowsSecciones as $rowSeccion) {
			
			//print_r("PASO1:HeaderSeccion Agregado");
			//print_r("<br />");
			$select = $tablaGrupo->select()->from($tablaGrupo)->where("idSeccionEncuesta=?", $rowSeccion->idSeccionEncuesta);
			$rowsGrupos = $tablaGrupo->fetchAll($select);
			// Iteramos sobre 
			foreach ($rowsGrupos as $rowGrupo) {
				
				// Procesamos la encuesta solo si es de tipo SS
				if($rowGrupo->tipo == "SS") {
					$numCategorias++;
					//Puntajes maximos obtenidos
					$puntajeMaximo = 0;
					$puntajeObtenido = 0;
					$calificacionCategoria = 0;
					$arrayIdsOpciones = array();
					
					$rowContent = new My_Pdf_Table_Row;
					$colrc1 = new My_Pdf_Table_Column;
					$colrc1->setText($rowGrupo->nombre);
					$colrc2 = new My_Pdf_Table_Column;
					$colrc2->setText("");
					$rowContent->setColumns(array($colrc1,$colrc2));
					$rowContent->setFont($font);
					$rowContent->setCellPaddings(array(5,5,5,5));
					$tablaContenidoR->addRow($rowContent);
					
					$arrayIdsOpciones = explode(",", $rowGrupo->opciones);
					
					//print_r($arrayIdsOpciones);
					//print_r("<br />");
					$select = $tablaOpcion->select()->from($tablaOpcion)->where("idOpcionCategoria IN (?)",$arrayIdsOpciones)->order(array("valorEntero"));
					//print_r($select->__toString());
					//print_r("<br />");
					// ----
					$rowsOpciones = $tablaOpcion->fetchAll($select);
					$arrOpciones = $rowsOpciones->toArray();
					$mayorOpcion = end($arrOpciones);
                    //print_r($rowsOpciones->toArray());
					//print_r($mayorOpcion);
					//print_r("<br />");
					$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen=?","G")->where("idOrigen=?",$rowGrupo->idGrupoSeccion);
					$rowsPreguntasG = $tablaPregunta->fetchAll($select);
					$idsPreguntas = array();
					
					foreach ($rowsPreguntasG as $rowPreguntaG) {
						// 
						$select = $tablaRespuesta->select()->from($tablaRespuesta)->where("idEncuesta=?",$idEncuesta)->where("idAsignacionGrupo=?",$idAsignacion)->where("idPregunta=?",$rowPreguntaG->idPregunta);
						//print_r($select->__toString());
						$rowsRespuesta = $tablaRespuesta->fetchAll($select);
						
						foreach ($rowsRespuesta as $rowRespuesta) {
							foreach ($rowsOpciones as $rowOpcion) {
								if($rowOpcion->idOpcion == $rowRespuesta->respuesta){
									$puntajeObtenido += $rowOpcion->valorEntero;
									$puntajeMaximo += $mayorOpcion["valorEntero"];
								}
							}
						}
						
						$idsPreguntas[] = $rowPreguntaG->idPregunta;
						$select = $tablaPreferenciaS->select()->from($tablaPreferenciaS)->where("idPregunta IN (?)",$idsPreguntas);
						$preferenciasPregunta = $tablaPreferenciaS->fetchAll($select);
						$totalPregunta = 0;
						foreach ($preferenciasPregunta as $preferencia) {
							$totalPregunta += $preferencia->total;
						}
					}
                    if ($puntajeMaximo == 0) {
                        $calificacionCategoria = 0;
                    }else{
                        $calificacionCategoria = (10 * $puntajeObtenido) / $puntajeMaximo;
                    }
					
					//print_r("<strong>Puntaje Obtenido: ".sprintf('%.2f', $puntajeObtenido)."</strong><br />");
					//print_r("<strong>Puntaje Maximo: ".sprintf('%.2f', $puntajeMaximo)."</strong><br />");
					//print_r("<strong>Calificación: ".sprintf('%.2f', $calificacionCategoria)."</strong><br />");
					
					$rowContent2 = new My_Pdf_Table_Row;
					$colrc21 = new My_Pdf_Table_Column;
					$colrc21->setText("Puntaje Máximo: " .sprintf('%.2f', $puntajeMaximo));
					$colrc22 = new My_Pdf_Table_Column;
					$colrc22->setText("Puntaje Obtenido: " .sprintf('%.2f', $puntajeObtenido));
					$rowContent2->setColumns(array($colrc21,$colrc22));
					
					$rowContent3 = new My_Pdf_Table_Row;
					$colrc31 = new My_Pdf_Table_Column;
					$colrc31->setText("");
					$colrc32 = new My_Pdf_Table_Column;
					$colrc32->setText("Calificación: " .sprintf('%.2f', $calificacionCategoria));
					$rowContent3->setColumns(array($colrc31,$colrc32));
					
					$rowContent2->setFont($font);
					$rowContent2->setCellPaddings(array(5,5,5,5));
					$rowContent3->setFont($font);
					$rowContent3->setCellPaddings(array(5,5,5,5));
					
					$tablaContenidoR->addRow($rowContent2);
					$tablaContenidoR->addRow($rowContent3);
					//$tableContent->addRow($rowContent2);
					//$tableContent->addRow($rowContent3);
					$sumaFinal += $calificacionCategoria;
				}// fin de if($rowGrupo == "SS")
				
			}// fin de foreach $rowsGrupos
			
		}// fin de foreach $rowsSecciones
		$promedioFinal = $sumaFinal / $numCategorias;
		//print_r("Contenido creado, agregando...");
		//print_r("<br />");
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
		$page->drawText("PROMEDIO: ".sprintf('%.2f', $promedioFinal) . " - " . $resultado, 215, 215);
		$page->addTable($tablaContenidoR, 55, 215);
		//$page->addTable($tableContent, 30, 150);
		//print_r("Contenido Agregado, guardando...");
		//print_r("<br />");
		
		// ========================================================== >>> Guardamos el reporte
		$pdfReport->addPage($page);
		//$pdfReport->save();
		$pdfReport->saveDocument();
		//$pdfReport->save($nombreArchivo,false);
		//print_r("Archivo guardado");
		//print_r("<br />");
		$idReporte = $this->reporteDAO->agregarReporteGrupal($nombreArchivo, $idEncuesta, $idAsignacion);
		return $idReporte;
	}
	
    /**
     * 
     */
    public function generarReporteGrupalEvaluacionAsignacionDos($idEncuesta, $idAsignacion){
        // ========================================================== >>> Obtenemos los objetos estaticos basicos
        $textUtil = new App_Util_Text;
        $reporterDAO = $this->reporterDAO;
        
        $encuesta = $reporterDAO->getEncuestaById($idEncuesta);
        $asignacion = $reporterDAO->getAsignacionGrupoById($idAsignacion);
        
        $grupoEscolar = $reporterDAO->getGrupoEscolarById($asignacion["idGrupoEscolar"]);
        $materiaEscolar = $reporterDAO->getMateriaEscolarById($asignacion["idMateriaEscolar"]);
        $docente = $reporterDAO->getDocenteById($asignacion["idRegistro"]);
        $realizadas = $reporterDAO->getEncuestasRealizadas($idEncuesta, $idAsignacion);
        $grado = $reporterDAO->getGradoEducativoById($grupoEscolar["idGradoEducativo"]);
        $nivel = $reporterDAO->getNivelEducativoById($grado["idNivelEducativo"]);
        //setlocale($category, $locale);
        
        $nombreArchivo = str_replace(" ", "", $docente["apellidos"].$docente["nombres"])."-". $grupoEscolar['grupoEscolar'] ."-".$idAsignacion."-".$idEncuesta."-RGPH.pdf";
		$name = utf8_encode($nombreArchivo);
		$nombreArchivo = $textUtil->normalize_special_characters($name);
		
		//print_r("NombreArchivo: ".$nombreArchivo."<br />");
		//$titulo = str_replace(" ", "", $textUtil->normalize_special_characters($docente["apellidos"].$docente["nombres"]))."-". $grupoEscolar['grupoEscolar'] ."-".$idAsignacion."-".$idEncuesta."-RGPH.pdf";
        //$string = $textUtil->normalize_special_characters($nombreArchivo);
        //$titulo = utf8_encode($titulo);
        //print_r("Titulo: ".$textUtil->normalize_special_characters($titulo)."<br />");
        //print_r("NombreArchivo: ".$nombreArchivo."<br />");
        //print_r("NombreArchivo: ".iconv("UTF-8", "ASCII//TRANSLIT", $nombreArchivo)."<br />");
        //mb_convert_encoding($str, 'UTF-8', 'UTF-8');
        //setlocale(LC_ALL, 'en_US.UTF-8');
        //print_r("NombreArchivo: ".iconv("UTF-8", "ASCII//TRANSLIT", $nombreArchivo )."<br />");
        //print_r("NombreArchivo: ".iconv("UTF-8", "ISO-8859-1//TRANSLIT//IGNORE", $nombreArchivo )."<br />");
        //$string = mb_convert_encoding($nombreArchivo, "HTML-ENTITIES", "UTF-8"); 
        //$string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $nombreArchivo), ENT_QUOTES, 'UTF-8');
        
        //mb_convert_encoding($nombreArchivo, 'ASCII');
        
        // ========================================================== >>> Generamos el reporte a partir de plantilla
        $pdfTemplate = My_Pdf_Document::load(PDF_PATH . '/reports/bases/reporteHBE.pdf');
        $pages = $pdfTemplate->pages;
        $pdfReport = new My_Pdf_Document($nombreArchivo, PDF_PATH . '/reports/Encuesta/grupal/'.$this->organizacion["directorio"]);
        $pdfReport->setYHeaderOffset(160);
        // Clonamos para editar el nuevo documento
        $pageZ = clone $pages[0];
        //$pageZ = Zend_Pdf_Page
        //$page2 = new My_Pdf_Page($pageZ);
        //$page = $page2->clonePage();
        $page = new My_Pdf_Page($pageZ);
        $fontDefault = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        //$font = Zend_Pdf_Font::fontWithPath(PDF_PATH . "/fonts/microsoft/GOTHIC.TTF");
        //$fontBold = Zend_Pdf_Font::fontWithPath(PDF_PATH . "/fonts/microsoft/GOTHICB.TTF");
        //$page->setFont($font, 10);
        $styleDefault = new Zend_Pdf_Style;
        $styleDefault->setFont($fontDefault, 10);
        $page->setStyle($styleDefault);
        $page->setFont($fontDefault, 10);
        
        
        //print_r("Generando contenido :: Header");
        //print_r("<br />");
        // ========================================================== >>> Generamos header del reporte.
        $tableHeader = new My_Pdf_Table(2);
        $cellWidth = 200;
        
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
        $colthB2->setText(utf8_encode($docente['apellidos'].", ".$docente['nombres']));
        $colthC1->setText("Nivel, Grado, Grupo Y Materia: ");
        $colthC1->setWidth($cellWidth);
        $colthC2->setText(utf8_encode($nivel["nivelEducativo"].", ".$grado["gradoEducativo"].", Grupo. ".$grupoEscolar["grupoEscolar"].", ".$materiaEscolar['materiaEscolar']));
        
        $colthD1->setText("Grupo: ");
        $colthD1->setWidth($cellWidth);
        $colthD2->setText(utf8_encode($grupoEscolar['grupoEscolar']));
        
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
        //$tableHeader->addRow($rowTable4);
        
        $page->addTable($tableHeader, 120, 120);
        
        //print_r("Generando contenido :: Cuerpo");
        //print_r("<br />");
        // ========================================================== >>> Generamos el cuerpo del reporte
        $tablaContenidoR = new My_Pdf_Table(2);
        
        $promedioFinal = 0;
        $sumaFinal = 0;
        $numCategorias = 0;
        
        $tablaSeccion = $this->tablaSeccion;
        $tablaGrupo = $this->tablaGrupo;
        $tablaPregunta = $this->tablaPregunta;
        $tablaRespuesta = $this->tablaRespuesta;
        $tablaOpcion = $this->tablaOpcion;
        $tablaPreferenciaS = $this->tablaPreferenciaS;
        
        //$select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta=?",$rowEncuesta->idEncuesta);
        //$select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta=?",$idEncuesta);
        //$rowsSecciones = $tablaSeccion->fetchAll($select);
        // Obtenemos secciones de encuesta
        $secciones = $reporterDAO->getSeccionesByIdEncuesta($idEncuesta);
        // Normalizamos las preferencias antes de crear el reporte
        $reporterDAO->normalizePreferenciaAsignacion($idAsignacion);
         // Iteramos secciones y renderizamos tabla de grupos por seccion.
        foreach ($secciones as $seccion) {
            // Obtenemos grupos de la seccion
            $gruposSeccion = $reporterDAO->getGruposByIdSeccion($seccion["idSeccionEncuesta"]);
            $totalGrupos = count($gruposSeccion);   // Este numero + 1 nos dara las columnas de la tabla
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
            foreach ($gruposSeccion as $grupoSeccion) {
                // Si la seccion es de tipo simple seleccion la procesamos
                
                if ($grupoSeccion['tipo'] == 'SS') {
                    $columnHeader = new My_Pdf_Table_Column;
                    $column = new My_Pdf_Table_Column;
                    $emptyColumn = new My_Pdf_Table_Column;
                    $valorMaximo = $grupoSeccion['valorMaximo'];
                    $valorMinimo = $grupoSeccion['valorMinimo'];
                    
                    $select = $tablaPregunta->select()->from($tablaPregunta)->where("origen=?","G")->where("idOrigen=?",$grupoSeccion["idGrupoSeccion"]);
                    $rowsPreguntas = $tablaPregunta->fetchAll($select);
                    // PuntajeMaximo = valorMayorDeOpcionMultiple * numeroEncuestasRealizadas * numeroPreguntasEnGrupo
                    $puntajeMaximo = $valorMaximo * $realizadas['realizadas'] * count($rowsPreguntas);
                    //print_r("Puntaje maximo: ".$puntajeMaximo."<br />");
                    
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
                    //print_r("Total: " . $promedio);
                    $columnHeader->setText(utf8_encode($grupoSeccion["nombre"]));
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
                    $sumaFinal += $promedio;
                    $numCategorias++;
                }
            }
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
        }
        
        $promedioFinal = $sumaFinal / $numCategorias;
        //print_r("Contenido creado, agregando...");
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
        $pdfReport->addPage($page);
        $pdfReport->saveDocument();
        $tablaReportesEncuesta = $this->tablaReportesEncuesta;
        
        $select = $tablaReportesEncuesta->select()->from($tablaReportesEncuesta)->where("idEncuesta=?",$idEncuesta)->where("idAsignacionGrupo=?",$idAsignacion);
        $rowReporte = $tablaReportesEncuesta->fetchRow($select);
        $idReporte = 0;
        if(is_null($rowReporte)){
            
            $datos = array();
            $datos["idEncuesta"] = $idEncuesta;
            $datos["idAsignacionGrupo"] = $idAsignacion;
            $datos["nombreReporte"] = $nombreArchivo;
            $datos["tipoReporte"] = "RGRU";
            $datos["rutaReporte"] = '/reports/Encuesta/grupal/'.$this->organizacion["directorio"].'/';
            $datos["fecha"] = date("Y-m-d H:i:s", time());
            $idReporte = $tablaReportesEncuesta->insert($datos);
        }else{
            $idReporte = $rowReporte->idReporte;
        }
        
        return $idReporte;
    }

    public function getPlantillaBase($tipoPlantilla='H') {
        
    }
    
	/**
	 * Genera Reporte General del Docente para una Encuesta Identificada.
	 * Este Reporte no hace diferencia entre materias. 
	 * Se centra en el contenido de la encuesta.
	 */
	public function generarReporteGeneralEvaluacionDocente($idDocente, $idEncuesta)
	{
		// ---------------------------------------------------------------------------- Obtenemos la Encuesta en cuestion
		$tablaEncuesta = $this->tablaEncuesta;
		$select = $tablaEncuesta->select()->from($tablaEncuesta)->where("idEncuesta=?",$idEncuesta);
		$rowEncuesta = $tablaEncuesta->fetchRow($select);
		
		// ---------------------------------------------------------------------------- Obtenemos las Secciones de la Encuesta
		$tablaSeccion = $this->tablaSeccion;
		$select = $tablaSeccion->select()->from($tablaSeccion)->where("idEncuesta=?",$idEncuesta);
		$rowsSecciones = $tablaSeccion->fetchAll($select);
		
		// ---------------------------------------------------------------------------- Obtenemos al Docente a Evaluar
		$tablaRegistro = $this->tablaRegistro;
		$select = $tablaRegistro->select()->from($tablaRegistro)->where("idRegistro=?",$idDocente);
		$rowDocente = $tablaRegistro->fetchRow($select);
		
		// ---------------------------------------------------------------------------- Obtenemos todas las Asignaciones del Docente
		
		$tablaAsignacion = $this->tablaAsignacionG;
		$select = $tablaAsignacion->select()->from($tablaAsignacion)->where("idRegistro=?",$idDocente);
		//print_r("Query: " . $select->__toString());
		//print_r("<br />");
		$rowsAsignaciones = $tablaAsignacion->fetchAll($select);
		//Nos enfocamos a extraer los IdAsignacion
		$idAsignacionArray = array();
		foreach ($rowsAsignaciones as $rowAsignacion) {
			$idAsignacionArray[] = $rowAsignacion->idAsignacion;
		} 
		// ---------------------------------------------------------------------------- Reporte
		$nombreArchivo = str_replace(' ', '_', $rowDocente->apellidos).str_replace(' ', '', $rowDocente->nombres)."-".$idEncuesta."-".$idDocente."-"."RGRAL.pdf";
		
		//print_r("Nombre: " . $nombreArchivo);
		//print_r("<br />");
		//print_r("<br />");
		// ========================================================== >>> Generamos el reporte a partir de plantilla
		$pdfTemplate = My_Pdf_Document::load(PDF_PATH . '/reports/bases/reporteHBE.pdf');
		$pages = $pdfTemplate->pages;
		$pdfReport = new My_Pdf_Document($nombreArchivo, PDF_PATH . '/reports/encuesta/general');
		$pdfReport->setYHeaderOffset(160);
		// Clonamos la pagina para resetear el numero de seguridad que genera Zend. 
		$pageZ = clone $pages[0];
		$page = new My_Pdf_Page($pageZ);
		//$page = new My_Pdf_Page(Zend_Pdf_Page::SIZE_LETTER_LANDSCAPE);
		$font = Zend_Pdf_Font::fontWithPath(PDF_PATH . "/fonts/microsoft/GOTHIC.TTF");
		$fontBold = Zend_Pdf_Font::fontWithPath(PDF_PATH . "/fonts/microsoft/GOTHICB.TTF");
		$page->setFont($font, 10);
		
		//print_r("Generando contenido :: Header");
		//print_r("<br />");
		// ========================================================== >>> Generamos header del reporte.
		$tableHeader = new My_Pdf_Table(2);
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
		
		$colthA1->setText("Evaluacion: ");
		$colthA1->setWidth(60);
		$colthA2->setText($rowEncuesta->nombre);
		$colthB1->setText("Docente: ");
		$colthB1->setWidth(60);
		$colthB2->setText($rowDocente->apellidos.", ".$rowDocente->nombres);
		
		$rowTable1->setColumns(array($colthA1,$colthA2));
		$rowTable1->setCellPaddings(array(5,5,5,5));
		$rowTable1->setFont($font,10);
		$rowTable2->setColumns(array($colthB1,$colthB2));
		$rowTable2->setCellPaddings(array(5,5,5,5));
		$rowTable2->setFont($font,10);
		
		$tableHeader->addRow($rowTable1);
		$tableHeader->addRow($rowTable2);
		//$tableHeader->addRow($rowTable3);
		//$tableHeader->addRow($rowTable4);
		
		$page->addTable($tableHeader, 150, 135);
		$encuestadosTotales = 0;
		$puntajesCategoria = array(); // idCategoria => $sumaPuntaje
		// ========================================================== >>> Generamos content del reporte.
		$numeroColumnas = 0;
		$categorias = array();
		$headersCategorias = array();
		// Ancho estandar de las celdas de la tabla en el documento PDF
		$widthGeneral = 65;
		$hc1 = new My_Pdf_Table_Column;
		$hc1->setText("Grupo");
		$hc1->setWidth($widthGeneral);
		$hc2 = new My_Pdf_Table_Column;
		$hc2->setText("Alumnas");
		$hc2->setWidth($widthGeneral);
		
		$headersCategorias[] = $hc1;
		$headersCategorias[] = $hc2;
		
		$tablaGrupo = $this->tablaGrupo;
		$tablaPregunta = $this->tablaPregunta;
		$tablaRespuesta = $this->tablaRespuesta;
		$tablaPreferenciaSimple = $this->tablaPreferenciaS;
		$tablaOpcion = $this->tablaOpcion;
		
		foreach ($rowsSecciones as $rowSeccion) {
			$select = $tablaGrupo->select()->from($tablaGrupo)->where("idSeccion=?",$rowSeccion->idSeccion);
			$gruposSeccion = $tablaSeccion->fetchAll($select);
			$numeroColumnas += count($gruposSeccion);
			foreach ($gruposSeccion as $grupoSeccion) {
				$categorias[$grupoSeccion->idGrupo] = $grupoSeccion->nombre;
				$puntajesCategoria[$grupoSeccion->idGrupo] = 0;
				$hc = new My_Pdf_Table_Column;
				$hc->setText($grupoSeccion->nombre);
				$hc->setWidth($widthGeneral);
				$headersCategorias[] = $hc;
			}
		}
		
		$tableContent = new My_Pdf_Table($numeroColumnas+2);
		//print_r($categorias);
		$rowHeaderTableContent = new My_Pdf_Table_HeaderRow;
		//$nombresCategorias = array_values($categorias);
		$rowHeaderTableContent->setColumns($headersCategorias);
		$rowHeaderTableContent->setFont($font,8);
		$rowHeaderTableContent->setCellPaddings(array(2,2,2,2));
		$tableContent->addRow($rowHeaderTableContent);
		// ========================================================== >>> Iteramos a traves de las asignaciones
		
		$tablaERealizadas = $this->tablaERealizadas;
		$tablaGrupoE = $this->tablaGrupoE;
		// Solo iteramos en las Asignaciones para la encuesta proporcionada
		$select = $tablaERealizadas->select()->from($tablaERealizadas)->where("idEncuesta=?",$idEncuesta)->where("idAsignacion IN (?)",$idAsignacionArray);
		//print_r("<br />");
		//print_r("Select: ".$select->__toString());
		//print_r("<br />");
		$rowsRealizadas = $tablaERealizadas->fetchAll($select);
		$numeroGrupos = count($rowsRealizadas);
		foreach ($rowsRealizadas as $rowRealizada) {
			$rA = null;
			foreach ($rowsAsignaciones as $rowAsignacion) {
				if($rowRealizada->idAsignacion == $rowAsignacion->idAsignacion){
					$rA = $rowAsignacion;
					break;
				} 
			}
			$rowTableContent = new My_Pdf_Table_Row;
			$columnsContent = array();
			//	Obtengo el GrupoE
			$select = $tablaGrupoE->select()->from($tablaGrupoE)->where("idGrupo=?",$rA->idGrupo);
			$rowGrupo = $tablaGrupoE->fetchRow($select);
			$cc1 = new My_Pdf_Table_Column;
			$cc1->setText($rowGrupo->grupo);
			$cc1->setWidth($widthGeneral);
			$columnsContent[] = $cc1;
			//	Obtengo # de encuestas contestadas
			$cc2 = new My_Pdf_Table_Column;
			$cc2->setText($rowRealizada->realizadas);
			$cc2->setWidth($widthGeneral);
			$columnsContent[] = $cc2;
			$encuestadosTotales += $rowRealizada->realizadas;
			// Itero a traves de las categorias y obtengo los respectivos puntajes
			foreach ($categorias as $idGrupo => $nombreGrupo) {
				//	Obtenemos la Opcion mas grande
				$select = $tablaGrupo->select()->where("idGrupo=?",$idGrupo);
				$grupo = $tablaGrupo->fetchRow($select);
				$ids = explode(",", $grupo->opciones);
				$select = $tablaOpcion->select()->from($tablaOpcion,array("idOpcion", "valor"=>"MAX(vreal)"))->where("idOpcion IN (?)",$ids);
				$rowOpcion = $tablaOpcion->fetchRow($select);
				
				//$numeroPreguntas = count($grupoDAO->obtenerPreguntas($categoria->getIdGrupo()));
				$tablaPregunta = $this->tablaPregunta;
				$select = $tablaPregunta->select()->from($tablaPregunta)->where("origen = ?", "G")->where("idOrigen = ?", $idGrupo);
				$rowsPreguntas = $tablaPregunta->fetchAll($select);
				$numeroPreguntas = count($rowsPreguntas);
				$idsPreguntas = array();
				foreach ($rowsPreguntas as $rowPregunta) {
					$idsPreguntas[] = $rowPregunta->idPregunta;
				}
				//	Obtenemos el puntaje maximo
				$maximo = $rowRealizada->realizadas * $numeroPreguntas * $rowOpcion->valor;
				//	La formula para calcular la puntuacion total es: $calif = ($pObtenido * 10) / $pMax
				
				//	Obtenemos el puntaje obtenido para la categoria en cuestion: 
				$select = $tablaPreferenciaSimple->select()->from($tablaPreferenciaSimple)->where("idAsignacion=?",$rowRealizada->idAsignacion)->where("idPregunta IN (?)",$idsPreguntas);
				$rowsPreferencias = $tablaPreferenciaSimple->fetchAll($select);
				$obtenido = 0;
				foreach ($rowsPreferencias as $rowPreferencia) {
					$select = $tablaOpcion->select()->from($tablaOpcion)->where("idOpcion=?",$rowPreferencia->idOpcion);
					$rOpcion = $tablaOpcion->fetchRow($select);
					$total = $rOpcion->vreal * $rowPreferencia->preferencia;
					$obtenido += $total; 
					if($rowPreferencia->total != $total){
						$rowPreferencia->total = $total;
						$rowPreferencia->save();
					}
				}
				
				if($maximo == 0){
					$calificacion = 0;
				}else{
					$calificacion = ($obtenido * 10) / $maximo;
				}
				
				
				$cc = new My_Pdf_Table_Column;
				$cc->setText(sprintf('%.2f', $calificacion));
				$cc->setWidth($widthGeneral);
				$columnsContent[] = $cc;
				$puntajesCategoria[$idGrupo] += $calificacion;
			}
			
			$rowTableContent->setColumns($columnsContent);
			$rowTableContent->setFont($font,8);
			$rowTableContent->setBorder(My_Pdf::TOP, new Zend_Pdf_Style());
			$rowTableContent->setBorder(My_Pdf::RIGHT, new Zend_Pdf_Style());
			$rowTableContent->setBorder(My_Pdf::BOTTOM, new Zend_Pdf_Style());
			$rowTableContent->setBorder(My_Pdf::LEFT, new Zend_Pdf_Style());
			$rowTableContent->setCellPaddings(array(2,2,2,2));
			$tableContent->addRow($rowTableContent);
		}
		// ======================================================================== Fila de promedios
		$rowPromedio = new My_Pdf_Table_Row();
		$cols = array();
		$colEmpty = new My_Pdf_Table_Column;//Primera columna vacia
		//$colEmpty->setWidth($widthGeneral);
		$cols[] = $colEmpty;
		
		$colTotalEncuestados = new My_Pdf_Table_Column;
		$colTotalEncuestados->setText($encuestadosTotales);
		$colTotalEncuestados->setWidth($widthGeneral);
		
		foreach ($categorias as $idGrupo => $nombreGrupo) {
			$colProm = new My_Pdf_Table_Column;
			$promedio = $puntajesCategoria[$idGrupo] / $numeroGrupos;
			$colProm->setText(sprintf('%.2f', $calificacion));
			$colProm->setWidth($widthGeneral);
			$cols[] = $colProm;
			//print_r("<br />");
			//print_r("Promedio: ".$promedio);
			//print_r("<br />");
		}
		
		$rowPromedio->setColumns($cols);
		$rowPromedio->setFont($fontBold,8);
		$rowPromedio->setCellPaddings(array(2,2,2,2));
		//$tableContent->addRow($rowPromedio);
		
		
		
		$page->addTable($tableContent, 40, 215);
		$pdfReport->addPage($page);
		$pdfReport->saveDocument();
	}
	
	public function generarReportePAGrupalEvaluacionDocente($value='')
	{
		
	}
	
	public function generaReporteVerticalBase($nombreArchivo = "template.pdf", $path = ".")
	{
		$properties = array();
		$properties["imgHeader"];
		$properties["imgFooter"];
		$pdfTemplate = new My_Pdf_Document($nombreArchivo,$path);
		
		$pdfTemplate->save();
	}
}
