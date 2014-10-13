<?php

class UploadController extends Zend_Controller_Action
{

  private $caminhoImagem;
  private $caminhoMiniImagem;
  private $caminhoUrlMini;
  private $caminhoUrlGrande;
  private $caminhoDeleteUrl;
  private $idEvento=1;
    public function init() {
//  	$this->caminhoImagem=APPLICATION_PATH."/../public/files/";
//   	$this->caminhoMiniImagem=APPLICATION_PATH."/../public/thumbnails/";
//   	$this->caminhoUrlMini="/projects/clientes/temperomidia/projeto-ciroc/zf-tutorial/public/thumbnails/";
//    	$this->caminhoUrlGrande="/projects/clientes/temperomidia/projeto-ciroc/zf-tutorial/public/files/";
//   	$this->caminhoDeleteUrl="/projects/clientes/temperomidia/projeto-ciroc/zf-tutorial/public/index.php?file=";
    
//    $this->caminhoImagem=APPLICATION_PATH."/../public/files/";
//     		$this->caminhoMiniImagem=APPLICATION_PATH."/../public/thumbnails/";
//     		$this->caminhoUrlMini="/zf-tutorial/public/thumbnails/";
//     		$this->caminhoUrlGrande="/zf-tutorial/public/files/";
//     	$this->caminhoDeleteUrl="/zf-tutorial/public/index.php?file=";
     	
     	
     	 $this->caminhoImagem=APPLICATION_PATH."/../files/";
     		$this->caminhoMiniImagem=APPLICATION_PATH."/../thumbnails/";
     	$this->caminhoUrlMini="/cirocontrade/thumbnails/";
     		$this->caminhoUrlGrande="/cirocontrade/files/";
     	$this->caminhoDeleteUrl="/cirocontrade/index.php?file=";
    	
    	//$logger = Zend_Registry::get("logger");
  //  $logger->log("Entrou init!".$this->idEvento, Zend_Log::INFO);
    Zend_Registry::get('logger')->log("init index", Zend_Log::INFO);
        if (! Zend_Auth::getInstance()->hasIdentity()) {
            $controlador2 = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
            $index2 = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
            Zend_Registry::get('logger')->log($controlador2, Zend_Log::INFO);
            Zend_Registry::get('logger')->log($index2, Zend_Log::INFO);
            if ($controlador2 != 'index' || $index2 != 'login') {
               // return $this->_helper->redirector('login');
            }
		
		//return $this->_helper->redirector('index');
	}else{
		$usuarioLogado=new Aplicacao_Plugin_Auth();
       $this->user = $usuarioLogado->_auth->getIdentity();
         $this->view->fk_perfil = $this->user->getFKPerfil();
         $this->view->usuario = $this->user->getUserName();
         Zend_Registry::get('logger')->log( $this->user, Zend_Log::INFO);
          Zend_Registry::get('logger')->log( $this->user->getUserName(), Zend_Log::INFO);
        
	}
	  /* Initialize action controller here */
    	$evento = new Application_Model_DbTable_Evento();
        $ultimoEvento = $evento->getUltimaAtualizacao();
        $date = new Zend_Date($ultimoEvento["data"]);
        $datasaida = $date->toString('HH:mm:ss dd/MM/YYYY ');
        $this->view->dataAtualizacao = $datasaida;
    }

  public function indexAction() {

  }
public function videosAction() {
		$idEvento=$this->_request->getParam("idEvento");
        $form = new Application_Form_Video();
        $form->submit->setLabel('Adicionar novo vídeo ');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $nome = $form->getValue('nome');
                $url = $form->getValue('url');
              
                //$form->getValue('interior_capital');
                $casaNoturnas = new Application_Model_DbTable_Video();
                $casaNoturnas->addVideo($nome, $url, $idEvento);
             
                $this->_redirect('/upload/lista-videos/idEvento/'.$idEvento);
                
            } else {
                $form->populate($formData);
            }
        }
  }
  public function listaVideosAction(){
  	$idEvento=$this->_request->getParam("idEvento");
  	$listaVideos = new Application_Model_DbTable_Video();
  	$this->view->idEvento=$idEvento;
  	
        $this->view->listaVideos = $listaVideos->getVideosEvento($idEvento);
  }
public function deleteVideoAction()
    {
        // action body
        $idEvento=$this->_request->getParam("idEvento");
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
                $video = new Application_Model_DbTable_Video();
        
                    $video->deleteVideo($id);
                   
                    $this->_redirect('/upload/lista-videos/idEvento/'.$idEvento);
            
				
            }
        } else {
            $id = $this->_getParam('id', 0);
            $video = new Application_Model_DbTable_Video();
            $this->view->videos = $video->getVideo($id);
        }
    }
	public function editVideoAction()
    {
    		$idEvento=$this->_request->getParam("idEvento");
        $form = new Application_Form_Video();
        $form->submit->setLabel('Salvar video');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int) $form->getValue('id');
                 $nome = $form->getValue('nome');
                $url = $form->getValue('url');
              $fk_evento=$form->getValue('fk_evento');
                //$form->getValue('interior_capital');
                $videos = new Application_Model_DbTable_Video();
                $videos->updateVideo($id, $nome, $url, $fk_evento);
             
                $this->_redirect('/upload/lista-videos/idEvento/'.$idEvento);            
            
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $videos= new Application_Model_DbTable_Video();
                $form->populate($videos->getVideo($id));
            }
        }
    }
  public function upload($user_id, $email) {

  }

  public function delete($user_id, $email) {
     
  }
  public function mediaAction()
    {
      
	  $idEvento=$this->_request->getParam("id");
	  $usuarioLogado=new Aplicacao_Plugin_Auth();
	  $user = $usuarioLogado->_auth->getIdentity();   
	  
	  $eventos = new Application_Model_DbTable_Evento();
	  $this->view->evento = $eventos->getEvento($idEvento);
	  $fk_usuario_produtor=$this->view->evento["fk_usuario_produtor"];
	  if(($this->user->getFKPerfil()==2 && $fk_usuario_produtor==$this->user->getId())||$this->user->getFKPerfil()==1){
		  $this->idEvento= $this->_request->getParam("id");
		  
		  $this->view->idEvento=$this->idEvento;
		  $this->_helper->layout()->setLayout('media');
	  }else{
		  $this->_redirect('index/lista-fotos-evento/id/'.$idEvento);
		  
      
	  }
    }
	  public function uploadjqAction()
		  { 
		  $this->idEvento= $this->_request->getParam("id");	
		  //echo "teste".$this->idEvento;
		  $this->getHelper('Layout')->disableLayout();
		  
		  $this->getHelper('ViewRenderer')->setNoRender();
		  
		  Zend_Loader::loadClass('Upload',
			  array(
				  "../".APPLICATION_PATH."\public")
		  );
		  
		  $upload_handler = new Upload();
		  
		  header('Pragma: no-cache');
		  header('Cache-Control: private, no-cache');
		  header('Content-Disposition: inline; filename="files.json"');
		  header('X-Content-Type-Options: nosniff');
		  
		  switch ($_SERVER['REQUEST_METHOD']) {
			  case 'HEAD':
			  case 'GET':
				  //$upload_handler->get();
				  $evento = new Application_Model_DbTable_Evento();
				  $ultimoEvento = $evento->getImagemEvento($this->idEvento);
				  
				  $retornoImagens=array();
    	
    	foreach ( $ultimoEvento as $value){
    		
    		$obj["name"]=$value["nome"];
    		$obj["size"]="11";
//    		$obj["url"]="/projects/clientes/temperomidia/projeto-ciroc/zf-tutorial/public/files/".$value["nome"];
//    		$obj["thumbnail_url"]="/projects/clientes/temperomidia/projeto-ciroc/zf-tutorial/public/thumbnails/".$value["nome"];
//    		$obj["delete_url"]="/projects/clientes/temperomidia/projeto-ciroc/zf-tutorial/public/index.php?file=".$value["nome"];
//    		$obj["DELETE"]="/projects/clientes/temperomidia/projeto-ciroc/zf-tutorial/public/index.php?file=".$value["nome"];

			$obj["name"]=$value["nome"];
    		$obj["size"]="11";
    		$obj["url"]="/cirocontrade/files/".$value["nome"];
    		$obj["thumbnail_url"]="/cirocontrade/thumbnails/".$value["nome"];
    		$obj["delete_url"]="/cirocontrade/index.php?file=".$value["nome"];
    		$obj["DELETE"]="/cirocontrade/index.php?file=".$value["nome"];
    		$obj["delete_type"]="DELETE";
    		
    		
    		$retornoImagens[]=$obj;
    	}
    	$info=json_encode($retornoImagens);
    	echo $info;
				break;
			case 'POST':
				$resposta=$upload_handler->post();
				$obj=json_decode($resposta);
				$arquivo = new Application_Model_DbTable_Arquivo();
				foreach($obj as $value){
					$nome=$value->name;
					$type=$value->type;
					$url=$value->url;
					$extensao=pathinfo($url);

					
					$id=$arquivo->addArquivo("", $type, $this->idEvento);
					$value->name=$id."_imagem.". $extensao['extension'];
					$value->thumbnail_url=$this->caminhoUrlMini.$value->name;
					$value->url=$this->caminhoUrlGrande.$value->name;
					
					rename($this->caminhoImagem.$nome,$this->caminhoImagem.$id."_imagem.". $extensao['extension']);
					rename($this->caminhoMiniImagem.$nome,$this->caminhoMiniImagem.$id."_imagem.". $extensao['extension']);
					
					$value->delete_url=$this->caminhoDeleteUrl.$value->name;
					$arquivo->updateArquivo($id,$value->name, $type, $this->idEvento);
					
				}
				echo json_encode($obj);

				break;
			case 'DELETE':
				$arquivo = new Application_Model_DbTable_Arquivo();
				$resposta=$upload_handler->delete();
				$obj=json_decode($resposta);
				$value=$_GET["file"];
				//foreach($obj as $value){
					
					$parte = explode("_",$value);
					$arquivo->deleteArquivo($parte[0]);
			
				//}
				echo $resposta;
				break;
			case 'OPTIONS':
				break;
			default:
				header('HTTP/1.0 405 Method Not Allowed');
		}
 
		exit;
	
	}
	 

}