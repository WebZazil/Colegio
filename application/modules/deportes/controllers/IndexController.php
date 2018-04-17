<?php
/**
 * 
 * @author EnginnerRodriguez
 *
 */
class Deportes_IndexController extends Zend_Controller_Action
{

    private $loginDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('homeDeportes');
        
        $this->loginDAO = new App_Data_DAO_Login();
        $testData = array('nickname' =>'test', 'password' => sha1('zazil'));
        $testConnector = $this->loginDAO->getTestConnector($testData, 'colsagcor16', 'MOD_DEPORTES');
    }

    public function indexAction()
    {
        // action body
        
    }

    public function loginAction()
    {
        // action body
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost();
            // print_r($datos);
            try{
                $this->loginDAO->simpleLogin($datos, 'colsagcor16', 'MOD_DEPORTES');
                $this->_helper->redirector->gotoSimple("index", "dashboard", "deportes");
            }catch(Exception $ex){
                $this->view->errorMessage = $ex->getMessage();
            }
        }
    }


}
