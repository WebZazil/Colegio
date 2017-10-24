<?php

class Evento_RegistroController extends Zend_Controller_Action
{

    private $eventoDAO = null;

    private $registroDAO = null;

    public function init()
    {
        /* Initialize action controller here */
        $identity = Zend_Auth::getInstance()->getIdentity();
        if (is_null($identity)) {
            // Redirect to login page
            $this->_helper->redirector->gotoSimple("index", "index", "evento");
        }
        
        $this->registroDAO = new Evento_Model_DAO_Registro($identity['adapter']);
        $this->eventoDAO = new Evento_Model_DAO_Evento($identity['adapter']);
    }

    public function indexAction()
    {
        // action body
        
    }

    public function altaAction()
    {
        // action body
        $idEvento = $this->getParam("ev");
        //print_r($_SERVER["SERVER_ADDR"]);
        //print_r($_SERVER["SERVER_NAME"]);
        //print_r(gethostbyname());
        //print_r($_SERVER["SERVER_PROTOCOL"]);
        //print_r(gethostname());
        //if (is_null($idEvento)) $this->_helper->redirector->gotoSimple("alta", "dashboard", "evento");
        
        $evento = $this->eventoDAO->getEventoById($idEvento);
        $this->view->evento = $evento;
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            // @TODO Validar via JavaScript y PHP que el correo sea correcto via RegExp
            $datos = $request->getPost();
            //print_r($datos);
            try{
                
                // ================================================================
                $datos['creacion'] = date('Y-m-d H:i:s', time());
                if(array_key_exists('mailist', $datos)){
                    $datos['mailist'] = 'S';
                }else{
                    $datos['mailist'] = 'N';
                }
                
                $imgPath = QR_PATH . "/evento/1775418046/evts/usr/";
                $contents = implode(",", $datos);
                $filename = md5($contents).".png";
                
                //$urlPath = $this->view->baseUrl()."/colegio/evento/registro/ev/".$idEvento."/ky/".md5($contents);
                $urlPath = "http://".$_SERVER["SERVER_NAME"].$this->view->url(array("module"=>"evento","controller"=>"registro","action"=>"confirm","ev"=>$idEvento,"ky"=>md5($contents)),null,true);
                
                $absolutePath = $imgPath.$filename;
                if (! file_exists($absolutePath)) {
                    QRcode::png($urlPath, $absolutePath, QR_ECLEVEL_L, 5);
                    // print_r("Archivo generado");
                }else{
                    print_r("Archivo existe!");
                }
                
                $datos['clave'] = $filename;
                $datos['archivo'] = $absolutePath;
                
                $txt = "Detalle de registro:"."\r\n".
                    "Asistente: ".$datos["nombres"]." ".$datos["apaterno"]." ". $datos["amaterno"]."\r\n".
                    "email: ".$datos["email"];
                
                $idAsistente = $this->registroDAO->saveAsistente($datos);
                
                 
                 // ob_start("callback");
                 // $text = implode(",", $datos);
                 // $debuglog = ob_get_contents();
                 // ob_end_clean();
                 // QRcode::png($text);
                 // ================================================================
                 
                $this->registroDAO->saveAsistenteEvento($idAsistente, $idEvento);
                // Enviar Email
                $config = array(
                    //'auth' => 'login',
                    'username' => 'dev.bugzilla@zazil.net',
                    'password' => 'Godzilla#2017',
                    //'ssl' => 'tls',
                    //'port' => 26
                );
                 
                 //$transport = new Zend_Mail_Transport_Smtp('mail.zazil.net',$config);
                 $transport = new Zend_Mail_Transport_Smtp();
                 $protocol = new Zend_Mail_Protocol_Smtp('mail.zazil.net',26, $config);
                 $protocol->connect();
                 $protocol->helo('mail.zazil.net');
                 $transport->setConnection($protocol);
                 
                 
                 $mail = new Zend_Mail();
                 $mail->addTo($datos["email"], "Giovanni Rodriguez");
                 $mail->setFrom("dev.bugzilla@zazil.net", "Dev Bugzilla");
                 $mail->setSubject("CSC Mexico Aviso de Registro a Evento");
                 $mail->setBodyText($txt);
                 
                 $arch = fopen($absolutePath, 'rb');
                 
                 $attachment = new Zend_Mime_Part($arch);
                 $attachment->type = 'image/png';
                 $attachment->disposition = Zend_Mime::DISPOSITION_INLINE;
                 $attachment->encoding = Zend_Mime::ENCODING_BASE64;
                 $attachment->filename = 'acceso.png';
                 
                 //fclose($arch);
                 
                 $mail->addAttachment($attachment);
                 $protocol->rset();
                 $mail->send($transport);
                 $protocol->quit();
                 $protocol->disconnect();
                 fclose($arch);
            }catch (Exception $ex){
                print_r($ex->getMessage());
            }
            
        }
    }

    public function confirmAction()
    {
        // action body
        $idEvento = $this->getParam("ev");
        $clave = $this->getParam("ky");
        
        $this->eventoDAO->confirmAsistEvento($idEvento, $idAsistente);
    }


}





