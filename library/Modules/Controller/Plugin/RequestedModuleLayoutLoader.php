<?php
/**
 *
 */
class Modules_Controller_Plugin_RequestedModuleLayoutLoader extends Zend_Controller_Plugin_Abstract {
        
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $moduleName = $request->getModuleName();
      
        if (isset($config[$moduleName]['resources']['layout']['layout'])) {
            try{
            $layoutScript = $config[$moduleName]['resources']['layout']['layout'];
              Zend_Layout::getMvcInstance()->setLayout($layoutScript);
            }catch(Exception $ex){
              $layoutScript = $config['resources']['layout']['layout'];
              Zend_Layout::getMvcInstance()->setLayout($layoutScript);
              print_r("Aqui");
            }
    
        }
        
        if (isset($config[$moduleName]['resources']['layout']['layoutPath'])) {
            try{
              $layoutPath = $config[$moduleName]['resources']['layout']['layoutPath'];
              $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
              Zend_Layout::getMvcInstance()->setLayoutPath($moduleDir. DIRECTORY_SEPARATOR .$layoutPath);
            }catch(Exception $ex){
              print_r("Aca");
              $layoutPath = $config['resources']['layout']['layoutPath'];
              $moduleDir =  Zend_Controller_Front::getInstance()->getModuleDirectory();
              //print_r($moduleDir);
              Zend_Layout::getMvcInstance()->setLayoutPath($moduleDir. DIRECTORY_SEPARATOR .$layoutPath);
            }
        }
    }
}
