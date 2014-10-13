<?php

class ErrorController extends Zend_Controller_Action
{
 public function forbiddenAction(){
 	$this->_helper->layout->setLayout('semAcesso');

    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->messages = $this->_flashMessenger->getMessages();
		$form = new Application_Form_Login();
		$this->view->form = $form;
		//Verifica se existem dados de POST
		if ( $this->getRequest()->isPost() ) {
			$data = $this->getRequest()->getPost();
			//Formulário corretamente preenchido?
			if ( $form->isValid($data) ) {
				$login = $form->getValue('login');
				$senha = $form->getValue('senha');

				try {
					Application_Model_Auth::login($login, $senha);
					//Redireciona para o Controller protegido
					return $this->_helper->redirector->goToRoute( array('controller' => 'index'), null, true);
				} catch (Exception $e) {
					//Dados inválidos
					$this->_helper->FlashMessenger($e->getMessage());
					$this->_redirect('/index/login');
				}
			} else {
				//Formulário preenchido de forma incorreta
				$form->populate($data);
			}
		}
 }
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        if (!$errors) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

