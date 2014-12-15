<?php

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', true);
class IndexController extends Zend_Controller_Action {

    public $user;
    public $urlCompleta = 'http:\\\www.tm1.com.br\\dotz\\upload\\';
    public $session;
    public $log;
    public $caminhoPastaFtp;

    public function init() {
        $this->caminhoPastaFtp = BASE_PATH . '/uploadXml/';
        $this->log = new Application_Model_DbTable_LogArquivosGerados();
        $this->session = new Zend_Session_Namespace();

        if ($this->session->urlAtual <> $_SERVER['REQUEST_URI']) {
            $this->session->urlAnterior = $this->session->urlAtual;
            $this->session->urlAtual = $_SERVER['REQUEST_URI'];
        }
        $url = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
        Zend_Registry::get('logger')->log($this->session->urlAnterior, Zend_Log::INFO);
        Zend_Registry::get('logger')->log($this->session->urlAtual, Zend_Log::INFO);
        if (!Zend_Auth::getInstance()->hasIdentity()) {

            $controlador2 = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
            $index2 = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
            if ($controlador2 != 'index' || $index2 != 'login') {
                // return $this->_helper->redirector('login');
            }
        } else {

            $usuarioLogado = new Aplicacao_Plugin_Auth();
            $this->view->user = $usuarioLogado->_auth->getIdentity();
            $this->user = $this->view->user;
            $this->view->fk_perfil = $this->user->getFKPerfil();
            $this->view->usuario1 = $this->user->getUserName();
        }
    }

    public function indexAction() {
        
    }

    public function deleteUsuarioAction() {


        $id = $this->_getParam('id', 0);
        $usuarios = new Application_Model_DbTable_Usuario();
        $this->view->usuarios = $usuarios->getUsuario($id);
    }

    public function editUsuarioAction() {
        // action body
        $form = new Application_Form_Usuario();
        $form->submit->setLabel('Salvar usuário');

        //$form->getElement("login")->setVisible(false);
        //$form->removeElement($form->getElement("s"));
        //$form->getElement("login")->setAttrib("disable", array(1));
        $form->removeElement("login");
        $this->view->form = $form;
        Zend_Registry::get('logger')->log($form->getValues(), Zend_Log::INFO);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int) $form->getValue('id_usuario');
                $nome = $form->getValue('nome');
                $email = $form->getValue('email');
                $senha = $form->getValue('senha');
                $jobrole = $form->getValue('jobrole');
                $cellphone = $form->getValue('cellphone');
                $repetirSenha = $form->getValue('repetirSenha');
                $fk_perfil = $form->getValue('fk_perfil');
                $fk_empresa = $form->getValue('fk_empresa');
                $usuarios = new Application_Model_DbTable_Usuario();
                $imageAdapter = new Zend_File_Transfer_Adapter_Http();
                $imageAdapter->setDestination(BASE_PATH . '/upload');
                $_FILES['fileUpload']['name'] = "teste.jpg";
                $nomedaimagem = $_FILES['fileUpload']['name'];
                $fk_arquivo = "-1";
                $extensao = pathinfo($nomedaimagem, PATHINFO_EXTENSION);

                if (is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
                    Zend_Registry::get('logger')->log("Entrou if is upload", Zend_Log::INFO);
                    $nomeArquivo = md5(uniqid()) . '.' . $extensao;
                    $extension = pathinfo($nomedaimagem, PATHINFO_EXTENSION);

                    $imageAdapter->addFilter('Rename', $nomeArquivo);
                    if (!$imageAdapter->receive('fileUpload')) {
                        $messages = $imageAdapter->getMessages['fileUpload'];
                        //A Imagem NÃ£o Foi Recebida Corretamente
                        Zend_Registry::get('logger')->log("A Imagem Não Foi Recebida Corretamente", Zend_Log::INFO);
                    } else {
                        //Arquivo Enviado Com Sucesso
                        //Realize As AÃ§Ãµes NecessÃ¡rias Com Os Dados
                        Zend_Registry::get('logger')->log("A Imagem  Recebida Corretamente", Zend_Log::INFO);


                        $arquivo = new Application_Model_DbTable_Arquivo();
                        $fk_arquivo = $arquivo->addArquivo($nomeArquivo, $extensao);
                        Zend_Registry::get('logger')->log("Id arquivo =" . $fk_arquivo, Zend_Log::INFO);
                    }
                } else {

                    Zend_Registry::get('logger')->log("O Arquivo Não Foi Enviado Corretamente", Zend_Log::INFO);
                    //O Arquivo NÃ£o Foi Enviado Corretamente
                }


                try {
                    if ($repetirSenha != $senha)
                        throw new Exception(
                        "AtenÃ§Ã£o senha e repetir senha tem que ser iguais");

                    try {
                        if ($fk_arquivo == "-1") {
                            $usuarios->updateUsuarioSemArquivo($id, $nome, $senha, $email, $fk_perfil, $fk_empresa, $jobrole, $cellphone);
                        } else {
                            $usuarios->updateUsuario($id, $nome, $senha, $email, $fk_perfil, $fk_empresa, $fk_arquivo, $jobrole, $cellphone);
                        }


                        $this->view->mensagem = "Atualizado com sucesso";
                        $this->view->erro = 0;

                        //$this->_helper->redirector('lista-usuario');
                    } // catch (pega exceÃ§Ã£o)
                    catch (Exception $e) {
                        $this->view->mensagem = "Atualizar usuário";
                        $this->view->erro = 1;
                        $this->view->mensagemExcecao = $e->getMessage();
                        //  echo ($e->getCode()."teste".$e->getMessage() );
                    }
                } catch (Exception $e) {
                    // echo  ;
                    $this->view->mensagem = "Atualizar usuário";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    //exit();
                }
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);

            if ($id > 0) {
                $usuarios = new Application_Model_DbTable_Usuario();
                Zend_Registry::get('logger')->log("Id usuario =" . $id, Zend_Log::INFO);
                $form->populate($usuarios->getUsuario($id));
            }
        }
    }

    public function addUsuarioAction() {
        // action body
        $form = new Application_Form_Usuario();
        $form->submit->setLabel('Adicionar usuário');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $nome = $form->getValue('nome');
                $email = $form->getValue('email');
                $senha = $form->getValue('senha');
                $login = $form->getValue('login');
                //$jobrole = $form->getValue('jobrole');
                //$cellphone=$form->getValue('cellphone');
                $fk_perfil = $form->getValue('fk_perfil');
                $repetirSenha = $form->getValue('repetirSenha');
                $fk_empresa = $form->getValue('fk_empresa');
                $usuarios = new Application_Model_DbTable_Usuario();

                $imageAdapter = new Zend_File_Transfer_Adapter_Http();
                $imageAdapter->setDestination(BASE_PATH . '/upload');


                Zend_Registry::get('logger')->log("is_uploaded_file", Zend_Log::INFO);
                //$arquivo=$form->getValues('fileUpload');
                $_FILES['fileUpload']['name'] = "teste.jpg";
                Zend_Registry::get('logger')->log($_FILES['fileUpload'], Zend_Log::INFO);
                $nomedaimagem = $_FILES['fileUpload']['name'];
                $fk_arquivo = "";
                $extensao = pathinfo($nomedaimagem, PATHINFO_EXTENSION);

                if (is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
                    Zend_Registry::get('logger')->log("Entrou if is upload", Zend_Log::INFO);
                    //$filename = $imageAdapter->getFileName('fileUpload');
                    $nomeArquivo = md5(uniqid()) . '.' . $extensao;
                    //$filename  = pathinfo($nomedaimagem, PATHINFO_FILENAME);
                    $extension = pathinfo($nomedaimagem, PATHINFO_EXTENSION);

                    $imageAdapter->addFilter('Rename', $nomeArquivo);
                    if (!$imageAdapter->receive('fileUpload')) {
                        $messages = $imageAdapter->getMessages['fileUpload'];
                        //A Imagem NÃ£o Foi Recebida Corretamente
                        Zend_Registry::get('logger')->log("A Imagem Não Foi Recebida Corretamente", Zend_Log::INFO);
                        try {
                            Zend_Registry::get('logger')->log("fk_arquivo" . $fk_arquivo, Zend_Log::INFO);
                            $usuarios->addUsuarioSemFoto($nome, $senha, $email, $fk_perfil, $login);
                            $this->view->mensagem = "Cadastrado com sucesso";
                            $this->view->erro = 0;
                        } catch (Exception $e) {
                            $this->view->mensagem = "Login $login já existe, cadastrar novo login <br> " . $e->getMessage();

                            $this->view->erro = 1;
                            $this->view->mensagemExcecao = $e->getMessage();
                        }
                    } else {
                        //Arquivo Enviado Com Sucesso
                        //Realize As AÃ§Ãµes NecessÃ¡rias Com Os Dados
                        Zend_Registry::get('logger')->log("A Imagem  Recebida Corretamente", Zend_Log::INFO);



                        try {
                            if ($repetirSenha != $senha)
                                throw new Exception(
                                "Atenção senha e repetir senha tem que ser iguais");
                            try {
                                $arquivo = new Application_Model_DbTable_Arquivo();
                                $fk_arquivo = $arquivo->addArquivo($nomeArquivo, $extensao);
                                $usuarios->addUsuario($nome, $senha, $email, $fk_perfil, $login, $fk_arquivo);
                                $this->view->mensagem = "Cadastrado com sucesso";
                                $this->view->erro = 0;
                                $form->reset();
                            } // catch (pega exceÃ§Ã£o)
                            catch (Exception $e) {
                                $this->view->erro = 1;
                                if ($e->getCode() == "23000") {
                                    $this->view->mensagem = "Login existe no sistema.Cadastre outro login";
                                }
                                $this->view->mensagemExcecao = $e->getMessage();
                            }
                        } catch (Exception $e) {
                            $this->view->mensagem = " ";
                            $this->view->erro = 1;
                            $this->view->mensagemExcecao = $e->getMessage();
                        }
                    }
                } else {

                    Zend_Registry::get('logger')->log("O Arquivo Não Foi Enviado Corretamente", Zend_Log::INFO);
                    try {
                        Zend_Registry::get('logger')->log("fk_arquivo" . $fk_arquivo, Zend_Log::INFO);
                        $usuarios->addUsuarioSemFoto($nome, $senha, $email, $fk_perfil, $login);
                        $this->view->mensagem = "Cadastrado com sucesso";
                        $this->view->erro = 0;
                        $form->reset();
                    } catch (Exception $e) {
                        $this->view->mensagem = "Login $login já existe, cadastrar novo login  ";

                        $this->view->erro = 1;
                        $this->view->mensagemExcecao = $e->getMessage();
                    }
                }
            } else {
                $form->populate($formData);
            }
        }
    }

    public function listaUsuarioAction() {
        // action body
        $usuarios = new Application_Model_DbTable_Usuario();
        //Zend_Registry::get('logger')->log($usuarios->getUsuariosComPerfil(), Zend_Log::INFO);


        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
                $usuarios = new Application_Model_DbTable_Usuario();
                try {
                    $usuarios->deleteUsuario($id);

                    $this->view->mensagem = "Excluí­do com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . " Deletar usuário";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    if ($e->getCode() == "23000") {
                        $this->view->mensagem = $e->getCode() . " Não permitido excluir usuário com reuniões agendadas";
                    }
                }
            }
        }
        try {
            $this->view->usuarios = $usuarios->getUsuariosComPerfil();
        } catch (Exception $e) {
            $this->view->mensagem = "Usuários nao encontrado";
            $this->view->erro = 1;
            $this->view->mensagemExcecao = $e->getMessage();
        }
    }

    public function loginAction() {
        $this->_helper->layout->setLayout('login');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages = $this->_flashMessenger->getMessages();
        $form = new Application_Form_Login();
        $this->view->form = $form;
        //Verifica se existem dados de POST
        Zend_Registry::get('logger')->log("antes verificacao loginAction", Zend_Log::INFO);
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            //FormulÃ¡rio corretamente preenchido?
            if ($form->isValid($data)) {
                $login = $form->getValue('login');
                $senha = $form->getValue('senha');
                Zend_Registry::get('logger')->log("senha valida", Zend_Log::INFO);
                try {
                    Application_Model_Auth::login($login, $senha);

                    //Redireciona para o Controller protegido
                    return $this->_helper->redirector->goToRoute(array('controller' => 'index'), null, true);
                } catch (Exception $e) {
                    //Dados invÃ¡lidos
                    //$this->_helper->FlashMessenger($e->getMessage());
                    $this->view->mensagem = "User or password incorrect";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    //$this->_redirect('/index/login');
                }
            } else {
                //FormulÃ¡rio preenchido de forma incorreta
                $form->populate($data);
            }
        }
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        return $this->_helper->redirector('index');
    }

    public function editAlterarPerfilAction() {
        $this->view->titulo = "Alterar Usuário";
        Zend_Registry::get('logger')->log($this->view->titulo, Zend_Log::INFO);

        // action body
        $form = new Application_Form_AlterarPerfil();
        $form->submit->setLabel('Salvar usuário');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
            if ($form->isValid($formData)) {

                $id = (int) $form->getValue('id_usuario');
                $nome = $form->getValue('nome');
                $email = $form->getValue('email');
                $senha = $form->getValue('senha');
                $repetirSenha = $form->getValue('repetirSenha');


                $usuarios = new Application_Model_DbTable_Usuario();
                // Zend_Registry::get('logger')->log($fk_perfil."perfil".$id, Zend_Log::INFO);
                try {
                    if ($repetirSenha != $senha)
                        throw new Exception(
                        "Atenção senha e repetir senha tem que ser iguais");


                    $imageAdapter = new Zend_File_Transfer_Adapter_Http();
                    $imageAdapter->setDestination(BASE_PATH . '/upload');
                    $nomedaimagem = $_FILES['fileUpload']['name'];
                    $fk_arquivo = "";
                    $extensao = pathinfo($nomedaimagem, PATHINFO_EXTENSION);
                    Zend_Registry::get('logger')->log($_FILES['fileUpload']['tmp_name'], Zend_Log::INFO);
                    Zend_Registry::get('logger')->log($_FILES['fileUpload'], Zend_Log::INFO);
                    if (is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
                        Zend_Registry::get('logger')->log("Entrou if is upload", Zend_Log::INFO);
                        //$filename = $imageAdapter->getFileName('fileUpload');
                        $nomeArquivo = md5(uniqid()) . '.' . $extensao;
                        //$filename  = pathinfo($nomedaimagem, PATHINFO_FILENAME);
                        $extension = pathinfo($nomedaimagem, PATHINFO_EXTENSION);

                        $imageAdapter->addFilter('Rename', $nomeArquivo);
                        if (!$imageAdapter->receive('fileUpload')) {
                            $messages = $imageAdapter->getMessages['fileUpload'];
                            //A Imagem NÃ£o Foi Recebida Corretamente
                            Zend_Registry::get('logger')->log("A Imagem NÃ£o Foi Recebida Corretamente", Zend_Log::INFO);
                        } else {
                            //Arquivo Enviado Com Sucesso
                            //Realize As AÃ§Ãµes NecessÃ¡rias Com Os Dados
                            Zend_Registry::get('logger')->log("A Imagem  Recebida Corretamente", Zend_Log::INFO);


                            $arquivo = new Application_Model_DbTable_Arquivo();
                            $fk_arquivo = $arquivo->addArquivo($nomeArquivo, $extensao);
                            Zend_Registry::get('logger')->log("Id arquivo =" . $fk_arquivo, Zend_Log::INFO);

                            $usuarios->updateAlterarPerfil($id, $nome, $senha, $email, $fk_arquivo);
                        }
                    } else {

                        Zend_Registry::get('logger')->log("O Arquivo Não Foi Enviado Corretamente", Zend_Log::INFO);
                        //O Arquivo NÃ£o Foi Enviado Corretamente
                    }
                    $usuarios->updateAlterarPerfilSemFoto($id, $nome, $senha, $email);


                    //$this->_helper->redirector('index');
                } catch (Exception $erro) {
                    // echo  ;
                    $this->view->mensagem = $erro->getMessage();
                    //exit();
                }
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->user->getId();
            if ($id > 0) {
                $usuarios = new Application_Model_DbTable_Usuario();

                $form->populate($usuarios->getUsuario($id));
            }
        }
    }

    public function viewProjectPhaseAction() {
        $id_projeto = $this->_getParam('id_projeto', 0);
        $id_atividade = $this->_getParam('id_atividade', 0);
        if ($id_projeto > 0 && $id_atividade > 0) {
            $projetoHasAtividade = new Application_Model_DbTable_ProjetoHasAtividade();
            $formData = $projetoHasAtividade->getProjetoHasAtividade2($id_projeto, $id_atividade);
            $formData['eta'] = $formData['eta'] == "00/00/0000" ? " " : $formData['eta'];
            $formData['data_fim'] = $formData['data_fim'] == "00/00/0000" ? " " : $formData['data_fim'];
            $formData['data_inicio'] = $formData['data_inicio'] == "00/00/0000" ? " " : $formData['data_inicio'];
            $this->view->atividade = $formData;
            Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
        }
    }

    public function addProdutoAction() {
        $form = new Application_Form_Produto();
        $form->submit->setLabel('Adicionar produto');
        $this->view->form = $form;
        $form->getElement("vincularReferencia")->setAttrib("disable", array(1));
        $form->nomeImagem->setValue("semfoto.png");
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
            if (isset($formData["vincularReferencia"])) {//clicou vincular
                $this->_redirect('/index/vincular-referencia/id/' . $formData['id_produto']);
                //Zend_Registry::get('logger')->log("Clicou vincularReferencia ".$formData['id_referencia'], Zend_Log::INFO);
            } else {//clicou adicionar
            }

            $listaNomeCaract = array();
            $listaValorCaract = $formData["valorCaracteristica"];
            Zend_Registry::get('logger')->log(count($listaValorCaract), Zend_Log::INFO);
            if (!count($listaValorCaract) >= 1) {
                $form->getElement('nomeCaracteristica')->setErrors(array('nomeCaracteristica' => 'CaracterÃ­stica do produto'));
                $form->getElement('valorCaracteristica')->setErrors(array('valorCaracteristica' => 'Valor referente Ã  caracterÃ­stica do produto'));
            }

            if ($form->isValid($formData) and (count($listaValorCaract) >= 1)) {

                try {

                    $imageAdapter = new Zend_File_Transfer_Adapter_Http();
                    $imageAdapter->setDestination(BASE_PATH . '/upload');
                    $nomedaimagem = $_FILES['fileUpload']['name'];
                    $fk_arquivo = "";
                    $extensao = pathinfo($nomedaimagem, PATHINFO_EXTENSION);
                    //
                    if (is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
                        $nome = $form->getValue('nome');
                        $fk_fornecedor = $form->getValue('fk_fornecedor');
                        $descricao = $form->getValue('descricao');
                        $palavrachave = $form->getValue('palavrachave');
                        $disponivel = $form->getValue('disponivel');
                        $ativo = $form->getValue('ativo');
                        $precode = $form->getValue('precode');
                        $precopor = $form->getValue('precopor');
                        $fretemedio = $form->getValue('fretemedio');
                        $codigoean = $form->getValue('codigoean');
                        $saldo = $form->getValue('saldo');
                        $nomeArquivo = md5(uniqid()) . '.' . $extensao;
                        $extension = pathinfo($nomedaimagem, PATHINFO_EXTENSION);
                        $imageAdapter->addFilter('Rename', $nomeArquivo);
                        if (!$imageAdapter->receive('fileUpload')) {
                            $messages = $imageAdapter->getMessages['fileUpload'];
                            //A Imagem NÃ£o Foi Recebida Corretamente
                            Zend_Registry::get('logger')->log("A Imagem Não Foi Recebida Corretamente", Zend_Log::INFO);
                        } else {
                            //Arquivo Enviado Com Sucesso
                            Zend_Registry::get('logger')->log("A Imagem  Recebida Corretamente", Zend_Log::INFO);
                            $urlGeral = $this->urlCompleta . $nomeArquivo;
                            $produto = new Application_Model_DbTable_Produto();
                            $listaValorCaract = array();
                            $listaNomeCaract = array();
                            $listaValorCaract = $formData["valorCaracteristica"];
                            $listaNomeCaract = $formData["nomeCaracteristica"];


                            $id_produto = $produto->addProdutoReferencia($listaNomeCaract, $listaValorCaract, $nome, $fk_fornecedor, $descricao, $urlGeral, $palavrachave, $nomeArquivo, $extensao, $ativo, $precode, $precopor, $fretemedio, $disponivel, $saldo, $codigoean);
                            $form->nomeImagem->setValue($nomeArquivo);
                        }
                    } else {

                        Zend_Registry::get('logger')->log("O Arquivo Não Foi Enviado Corretamente", Zend_Log::INFO);
                        //O Arquivo NÃ£o Foi Enviado Corretamente
                    }
                    //$usuarios->updateAlterarPerfilSemFoto ($id,$nome,$senha,$email);

                    $form->getElement("vincularReferencia")->setAttrib("disable", false);
                    $form->getElement("id_produto")->setValue($id_produto);
                    $form->getElement("submit")->setAttrib("disable", array(1));

                    //$this->_helper->redirector('index');
                    $this->view->erro = 0;
                    $this->view->mensagem = "Adicionado com sucesso";
                } catch (Exception $erro) {
                    Zend_Registry::get('logger')->log("Erroooooooooooooooo", Zend_Log::INFO);
                    $this->view->mensagem = $erro->getMessage();
                    $this->view->erro = 1;
                    //exit;
                }
            } else {
                Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                $form->populate($formData);

                $arrMessages = $form->getMessages();


                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
        }
    }

    public function vincularReferenciaAction() {

        $form = new Application_Form_Produto();
        $form->submit->setLabel('Salvar referência');
        //$form->removeElement("submit");
        $form->getElement("nome")->setAttrib("disable", array(1));
        $form->getElement("descricao")->setRequired(FALSE);
        $form->getElement("palavrachave")->setRequired(FALSE);
        $form->getElement("nome")->setRequired(FALSE);
        $form->getElement("descricao")->setAttrib("disable", array(1));
        $form->getElement("palavrachave")->setAttrib("disable", array(1));
        $this->view->desabilitarPalavraChave = 1;
        $form->getElement("vincularReferencia")->setAttrib("disable", array(1));
        $form->nomeImagem->setValue("semfoto.png");
        $id_produto = $this->_getParam('id', 0);

        if ($id_produto > 0) {
            $produto = new Application_Model_DbTable_Produto();
            $produto = $produto->getProduto($id_produto);
            Zend_Registry::get('logger')->log($produto, Zend_Log::INFO);
            $form->populate($produto);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
                if (isset($formData["vincularReferencia"])) {//clicou vincular
                    $this->_redirect('/index/vincular-referencia/id/' . $formData['id_produto']);
                    //Zend_Registry::get('logger')->log("Clicou vincularReferencia ".$formData['id_referencia'], Zend_Log::INFO);
                } else {//clicou adicionar
                }
                if ($form->isValid($formData)) {

                    try {

                        $imageAdapter = new Zend_File_Transfer_Adapter_Http();
                        $imageAdapter->setDestination(BASE_PATH . '/upload');
                        $nomedaimagem = $_FILES['fileUpload']['name'];
                        $fk_arquivo = "";
                        $extensao = pathinfo($nomedaimagem, PATHINFO_EXTENSION);
                        //
                        if (is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
                            $nome = $form->getValue('nome');
                            $descricao = $form->getValue('descricao');
                            $palavrachave = $form->getValue('palavrachave');
                            $disponivel = $form->getValue('disponivel');
                            $ativo = $form->getValue('ativo');
                            $precode = $form->getValue('precode');
                            $precopor = $form->getValue('precopor');
                            $fretemedio = $form->getValue('fretemedio');
                            $codigoean = $form->getValue('codigoean');
                            $saldo = $form->getValue('saldo');
                            $nomeArquivo = md5(uniqid()) . '.' . $extensao;
                            $extension = pathinfo($nomedaimagem, PATHINFO_EXTENSION);
                            $imageAdapter->addFilter('Rename', $nomeArquivo);
                            if (!$imageAdapter->receive('fileUpload')) {
                                $messages = $imageAdapter->getMessages['fileUpload'];
                                //A Imagem NÃ£o Foi Recebida Corretamente
                                Zend_Registry::get('logger')->log("A Imagem NÃ£o Foi Recebida Corretamente", Zend_Log::INFO);
                            } else {
                                //Arquivo Enviado Com Sucesso
                                Zend_Registry::get('logger')->log("A Imagem  Recebida Corretamente", Zend_Log::INFO);
                                $urlGeral = $this->urlCompleta . $nomeArquivo;
                                $produto = new Application_Model_DbTable_Produto();
                                $listaValorCaract = array();
                                $listaNomeCaract = array();
                                $listaValorCaract = $formData["valorCaracteristica"];
                                $listaNomeCaract = $formData["nomeCaracteristica"];
                                $id_produto = $produto->addReferenciaAoProduto($listaNomeCaract, $listaValorCaract, $id_produto, $nomeArquivo, $extensao, $ativo, $precode, $precopor, $fretemedio, $disponivel, $saldo, $codigoean);
                            }
                        } else {

                            Zend_Registry::get('logger')->log("O Arquivo NÃ£o Foi Enviado Corretamente", Zend_Log::INFO);
                            //O Arquivo NÃ£o Foi Enviado Corretamente
                        }
                        //$usuarios->updateAlterarPerfilSemFoto ($id,$nome,$senha,$email);

                        $form->getElement("vincularReferencia")->setAttrib("disable", false);
                        $form->getElement("id_produto")->setValue($id_produto);
                        $form->getElement("submit")->setAttrib("disable", array(1));
                        //$this->_helper->redirector('index');
                        $this->view->erro = 0;
                        $this->view->mensagem = "Adicionado com sucesso";
                    } catch (Exception $erro) {
                        Zend_Registry::get('logger')->log("Erroooooooooooooooo", Zend_Log::INFO);
                        $this->view->mensagem = $erro->getMessage();
                        $this->view->erro = 1;
                        //exit;
                    }
                } else {
                    Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                    $form->populate($formData);

                    $arrMessages = $form->getMessages();


                    foreach ($arrMessages as $field => $arrErrors) {
                        $this->view->erro = 1;
                        $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                    }
                }
            }
        } else {
            exit;
        }
    }

    public function listaProdutoAction() {
        $form = new Application_Form_BuscaProdutos();
        $form->submit->setLabel('Buscar');
        $this->view->form = $form;
        
        
        $produto = new Application_Model_DbTable_Produto();
        
        $ativo = $this->_getParam('ativo');

        if (isset($ativo) && !is_null($ativo) && ($ativo == "0" || $ativo == "1")) {

            $listaProdutos = $produto->getProdutosReferenciaInativoAtivo($ativo);
        } else {
            $listaProdutos = $produto->getProdutosReferencia();
        }
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $fornecedor=$form->getValue('fk_fornecedor'); 
                $listaProdutos = $produto->getProdutoFornecedor($fornecedor);
            } else {
                Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                $form->populate($formData);

                $arrMessages = $form->getMessages();
                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
        }
        Zend_Registry::get('logger')->log($listaProdutos, Zend_Log::INFO);
        $this->view->listaProdutos = $listaProdutos;
    }

    public function listaPedidoTrocaAction() {
        $produto = new Application_Model_DbTable_Produto();
        $listaProdutos = $produto->getProdutosReferencia();
        Zend_Registry::get('logger')->log($listaProdutos, Zend_Log::INFO);
        $this->view->listaProdutos = $listaProdutos;
    }

    public function vizualizaProduto() {
        
    }

    public function editProdutoAction() {



        $form = new Application_Form_Produto();
        //$this->view->desabilitar="0";
        // $form->fileUpload->setRequired(false);
        $form->removeElement("fileUpload");
        //desabilita obrigatoriedade para enviar foto
        //$form->removeElement("submit");
        //$form->getElement("nome")->setAttrib("disable", array(1));
        //$form->getElement("adicionarCaract")->setAttrib("disable", array(1));
        // $form->getElement("fileUpload")->setAttrib("disable", array(1));
        //$form->getElement("nomecaract")->setAttrib("disable", array(1));
        //$form->getElement("valorcaract")->setAttrib("disable", array(1));

        $form->getElement("fk_fornecedor")->setRequired(TRUE);
        $form->getElement("descricao")->setRequired(FALSE);
        $form->getElement("palavrachave")->setRequired(FALSE);
        $form->getElement("nome")->setRequired(FALSE);
        //$form->getElement("descricao")->setAttrib("disable", array(1));
        //$form->getElement("palavrachave")->setAttrib("disable", array(1));
        //$this->view->desabilitarPalavraChave="1";

        $form->submit->setLabel('Voltar');
        $form->vincularReferencia->setLabel('Alterar');

        $form->nomeImagem->setValue("semfoto.png");
        $id_referencia = $this->_getParam('idReferencia', 0);

        if ($id_referencia > 0) {
            $produto = new Application_Model_DbTable_Produto();
            $referencia = new Application_Model_DbTable_Referencia();
            $listaCaracteristicas = $referencia->retornaCaracteristicas($id_referencia);
            Zend_Registry::get('logger')->log($listaCaracteristicas, Zend_Log::INFO);
            $produtoAux = $produto->getProdutoReferencia($id_referencia);
            Zend_Registry::get('logger')->log("busca produto por referencia=" . $id_referencia, Zend_Log::INFO);
            $produtoAux = array_merge($produtoAux, $listaCaracteristicas);
            Zend_Registry::get('logger')->log($produtoAux, Zend_Log::INFO);
            $form->populate($produtoAux);
            $form->nomeImagem->setValue($produtoAux["nomeArquivo"]);
            Zend_Registry::get('logger')->log("populou", Zend_Log::INFO);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
                if (isset($formData["submit"])) {//clicou submit voltar
                    //$this->_redirect('/index/lista-produto');
                    $this->_redirect($this->session->urlAnterior, array('prependBase' => false));
                } else {//clicou adicionar
                }
                if ($form->isValid($formData)) {

                    try {

                        $imageAdapter = new Zend_File_Transfer_Adapter_Http();
                        $imageAdapter->setDestination(BASE_PATH . '/upload');
                        $nomedaimagem = $_FILES['fileUpload']['name'];
                        $fk_arquivo = "";
                        $id_produto = $form->getValue('id_produto');
                        $nome = $form->getValue('nome');
                        $fk_fornecedor = $form->getValue('fk_fornecedor');
                        $descricao = $form->getValue('descricao');
                        $palavrachave = $form->getValue('palavrachave');
                        $disponivel = $form->getValue('disponivel');
                        $ativo = $form->getValue('ativo');
                        $precode = $form->getValue('precode');
                        $precopor = $form->getValue('precopor');
                        $fretemedio = $form->getValue('fretemedio');
                        $codigoean = $form->getValue('codigoean');
                        $saldo = $form->getValue('saldo');
                        $extensao = pathinfo($nomedaimagem, PATHINFO_EXTENSION);
                        $referencia = new Application_Model_DbTable_Referencia();
                        $listaValorCaract = array();
                        $listaNomeCaract = array();
                        $listaValorCaract = $formData["valorCaracteristica"];
                        $listaNomeCaract = $formData["nomeCaracteristica"];

                        $produto->updateProduto($id_produto, $nome, $fk_fornecedor, $descricao, $url, $palavrachave);
                        $referencia->updateReferenciaSemFoto($id_referencia, $listaNomeCaract, $listaValorCaract, $ativo, $precode, $precopor, $fretemedio, $disponivel, $saldo, $codigoean);


                        $form->getElement("vincularReferencia")->setAttrib("disable", false);
                        $form->getElement("id_produto")->setValue($id_produto);
                        //$form->getElement("submit")->setAttrib("disable", array(1));
                        $listaCaracteristicas = $referencia->retornaCaracteristicas($id_referencia);
                        $produtoAux = $produto->getProdutoReferencia($id_referencia);
                        $produtoAux = array_merge($produtoAux, $listaCaracteristicas);
                        $form->nomeImagem->setValue($produtoAux["nomeArquivo"]);
                        $form->populate($produtoAux);


                        $this->view->form = $form;
                        $this->view->erro = 0;
                        $this->view->mensagem = "Alterado com sucesso";
                    } catch (Exception $erro) {

                        $this->view->mensagem = $erro->getMessage();
                        $this->view->erro = 1;
                        //exit;
                    }
                } else {
                    Zend_Registry::get('logger')->log("formulario invÃ¡lido", Zend_Log::INFO);
                    $form->populate($formData);

                    $arrMessages = $form->getMessages();


                    foreach ($arrMessages as $field => $arrErrors) {
                        $this->view->erro = 1;
                        $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                    }
                }
            }
        } else {
            exit;
        }
        Zend_Registry::get('logger')->log($this->view->nomeArquivo, Zend_Log::INFO);
    }

    public function visualizaProdutoAction() {



        $form = new Application_Form_Produto();

        $form->fileUpload->setRequired(false);
        //desabilita obrigatoriedade para enviar foto
        //$form->removeElement("submit");
        $form->getElement("nome")->setAttrib("disable", array(1));
        $form->getElement("disponivel")->setAttrib("disable", array(1));
        $form->getElement("ativo")->setAttrib("disable", array(1));

        $form->getElement("adicionarCaract")->setAttrib("disable", array(1));
        $form->getElement("precode")->setAttrib("disable", array(1));
        $form->getElement("precopor")->setAttrib("disable", array(1));
        $form->getElement("fretemedio")->setAttrib("disable", array(1));
        $form->getElement("codigoean")->setAttrib("disable", array(1));
        $form->getElement("saldo")->setAttrib("disable", array(1));
        $form->getElement("descricao")->setRequired(FALSE);
        $form->getElement("palavrachave")->setRequired(FALSE);
        $form->getElement("nome")->setRequired(FALSE);
        $form->getElement("fk_fornecedor")->setRequired(FALSE);
        $form->getElement("descricao")->setAttrib("disable", array(1));
        $form->getElement("palavrachave")->setAttrib("disable", array(1));
        $this->view->desabilitarPalavraChave = 1;

        $form->submit->setLabel('Voltar');
        if ($this->getRequest()->isPost()) {
            $this->_redirect($this->session->urlAnterior, array('prependBase' => false));
        }
        $form->removeElement("vincularReferencia");
        $form->removeElement("fileUpload");
        $form->nomeImagem->setValue("semfoto.png");
        $id_referencia = $this->_getParam('idReferencia', 0);

        if ($id_referencia > 0) {
            $produto = new Application_Model_DbTable_Produto();
            $referencia = new Application_Model_DbTable_Referencia();
            $listaCaracteristicas = $referencia->retornaCaracteristicas($id_referencia);
            Zend_Registry::get('logger')->log($listaCaracteristicas, Zend_Log::INFO);
            $produtoAux = $produto->getProdutoReferencia($id_referencia);
            Zend_Registry::get('logger')->log("busca produto por referencia=" . $id_referencia, Zend_Log::INFO);
            $produtoAux = array_merge($produtoAux, $listaCaracteristicas);
            Zend_Registry::get('logger')->log($produtoAux, Zend_Log::INFO);
            $form->populate($produtoAux);
            $form->nomeImagem->setValue($produtoAux["nomeArquivo"]);
            Zend_Registry::get('logger')->log("populou", Zend_Log::INFO);
            $this->view->form = $form;
        } else {
            exit;
        }
        Zend_Registry::get('logger')->log($this->view->nomeArquivo, Zend_Log::INFO);
    }

    /*  Envia produtos novos para a plataforma dotz */

    public function enviarNovoProdutoAction() {
        $produto = new Application_Model_DbTable_Produto();
        $referencia = new Application_Model_DbTable_Referencia();

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            $enviarDotz = $this->getRequest()->getPost('enviarDotz');
            if (isset($enviarDotz)) {
                Zend_Registry::get('logger')->log("Enviar novo produto dotz", Zend_Log::INFO);
                try {
                    $this->log->gerarLayout950X();
                    $this->view->mensagem = "Enviado com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getMessage();
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                }
            } elseif ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');

                try {
                    $referencia->deleteReferencia($id);

                    $this->view->mensagem = "Excluído com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getMessage();
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                }
            }
        }

        $listaProdutos = $produto->getProdutosNovos();
        $this->view->listaProdutos = $listaProdutos;
        Zend_Registry::get('logger')->log($listaProdutos, Zend_Log::INFO);
    }

    public function gerarXmlAction() {

        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
        Zend_Layout::getMvcInstance()->disableLayout();
        $id = $this->_getParam('id', 0);
        $nomeArquivo = $this->_getParam('n', 0);

        switch ($id) {
            case "950X": $retornoAddArquivo = $this->log->gerarLayout("950X");
                break;
            case "11X":
                //header('Content-Disposition: attachment;filename=' . "upload/$nomeArquivo");
                //header("Content-Type: text/xml");
                //$this->getResponse()->setHeader('Content-Disposition: attachment;filename=' . "upload/$nomeArquivo")
                //->setHeader('Content-type', 'text/xml');

                header('Content-type: application/xml');

                header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"');

                readfile("upload/" . $nomeArquivo);
                break;
            default:
                ;
                break;
        }



        //$retornoAddArquivo=$this->log->addLogArquivosGerados("950X","1","20140822_TemperoMidia_950X_1.XML");
    }

    /*  Atualiza produtos  para a plataforma dotz */

    public function enviarProdutoAtualizarAction() {
        $produto = new Application_Model_DbTable_Produto();
        Zend_Registry::get('logger')->log($_POST, Zend_Log::INFO);
        Zend_Registry::get('logger')->log($_POST, Zend_Log::INFO);

        //Verifica se Ã© post  
        if (isset($_POST) && !empty($_POST)) {    // echo "Os nÃºmeros de sua preferÃªncia sÃ£o:<BR>";          
            // Verifica se usuÃ¡rio escolheu algum nÃºmero   
            if (isset($_POST["atualizar_dotz"])) {
                try {
                    $this->log->atualizarLayout950X($_POST["atualizar_dotz"]);
                    $this->view->mensagem = "Enviado com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getMessage();
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                }
            } else {
                $this->view->mensagem = "Favor escolher algum produto para ser enviado para dotz";
                $this->view->erro = 1;
                $this->view->mensagemExcecao = "";
            }
        }
        $listaProdutos = $produto->getProdutosAlterar();

        Zend_Registry::get('logger')->log($listaProdutos, Zend_Log::INFO);

        $this->view->listaProdutos = $listaProdutos;
    }

    public function deleteReferenciaAction() {
        $id = $this->_getParam('id', 0);
        $produto = new Application_Model_DbTable_Produto();

        $this->view->referencia = $produto->getProdutoReferencia($id);
        Zend_Registry::get('logger')->log($this->view->referencia, Zend_Log::INFO);
    }

    public function listaArquivosFtpAction() {
        $produto = new Application_Model_DbTable_Produto();
        $listaArquivos = $this->log->getListaArquivos();
        $listaProdutos = $produto->getProdutosReferencia();
        Zend_Registry::get('logger')->log($listaProdutos, Zend_Log::INFO);
        Zend_Registry::get('logger')->log($listaArquivos, Zend_Log::INFO);
        $this->view->listaArquivos = $listaArquivos;
    }

    public function uploadFtpAction() {
        $form = new Application_Form_UploadArquivo();
        $this->view->form = $form;
        // $this->log->gerarConfirmacaoRecebimento(1,"20140828_TemperoMidia_860X_1.xml");

        try {
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {


                    $imageAdapter = new Zend_File_Transfer_Adapter_Http();
                    $imageAdapter->setDestination($this->caminhoPastaFtp);
                    $nomeArquivo = $_FILES['fileUpload']['name'];
                    $fk_arquivo = "";
                    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);

                    $er = '/(^[0-9]{8}\_TemperoMidia\_(950X|955X|860X|861X|865X|870X|880X|710X|950x|955x|860x|861x|865x|870x|880x|710x)\_([0-9]+)\.(xml|XML))/';
                    if (preg_match($er, $nomeArquivo)) {

                        if (file_exists($this->caminhoPastaFtp . $nomeArquivo)) {
                            $this->view->mensagem = "Existe arquivo cadastrado no sistema";
                            $this->view->erro = 1;
                            return false;
                        } elseif (is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
                            $auxLayout = explode("_", $nomeArquivo);
                            $layout = $auxLayout[2];
                            $xml = simplexml_load_file($_FILES["fileUpload"]["tmp_name"], 'SimpleXMLElement', LIBXML_NOCDATA);

                            /* if (!$imageAdapter->receive('fileUpload')){

                              throw new Exception("Arquivo nÃ£o recebido corretamente");

                              Zend_Registry::get('logger')->log("A Imagem NÃ£o Foi Recebida Corretamente", Zend_Log::INFO);

                              }else{ */
                            //Arquivo Enviado Com Sucesso
                            $objRetorno = $this->log->validarXSD($xml, "860X.xsd");
                            switch ($layout) {
                                case "950X"://Layout 950X - â€œCatÃ¡logo de Produtos Completoâ€�

                                    break;
                                case "955X"://Layout 955X - â€œCatÃ¡logo de Produtos Incrementalâ€�
                                    break;
                                case "860X"://Layout 860X - â€œPedidos de Trocaâ€�
                                    //Zend_Registry::get('logger')->log($objRetorno, Zend_Log::INFO);
                                    $this->log->addPedido($xml, $nomeArquivo);
                                    //$this->log->gerarConfirmacaoRecebimento(0,$nomeArquivo);
                                    break;
                                case "861X":
                                    break;
                                case "865X":
                                    $objRetorno = $this->log->validarXSD($xml, "865X.xsd");
                                    break;
                                case "870X":
                                    break;
                                case "880X":
                                    break;
                                case "710X":
                                    break;

                                default:
                                    ;
                                    break;
                            }
                            Zend_Registry::get('logger')->log("O arquivo foi Recebido Corretamente", Zend_Log::INFO);
                            $this->view->mensagem = "Upload com sucesso";
                            $this->view->erro = 0;
                            $form->nomeImagem->setValue($nomeArquivo);
                            //}
                        } else {

                            Zend_Registry::get('logger')->log("O Arquivo Não Foi Enviado Corretamente", Zend_Log::INFO);
                            //O Arquivo NÃ£o Foi Enviado Corretamente
                            $this->view->mensagem = "Arquivo não recebido corretamente";
                            $this->view->erro = 1;
                        }
                    } else {
                        Zend_Registry::get('logger')->log("arquivo não esta no formato padrao", Zend_Log::INFO);
                        $this->view->mensagem = "Arquivo fora do padrão AAAAMMDD_Identificação do Parceiro_Tipo de Registro_Sequencial.EXTENSÃƒO";
                        $this->view->erro = 1;
                        return false;
                    }
                    if (count($auxLayout) <> 4) {
                        $this->view->mensagem = "Arquivo fora do padrão AAAAMMDD_Identificação do Parceiro_Tipo de Registro_Sequencial.EXTENSÃƒO";
                        $this->view->erro = 1;
                        return false;
                    }
                } else {
                    $form->populate($formData);
                    $arrMessages = $form->getMessages();
                    foreach ($arrMessages as $field => $arrErrors) {
                        $this->view->erro = 1;
                        $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                    }
                }
            }
        } catch (Exception $e) {
            //
            if (file_exists($this->caminhoPastaFtp . $nomeArquivo)) {
                unlink($this->caminhoPastaFtp . $nomeArquivo);
            }
            // $this->log->gerarConfirmacaoRecebimento(1,$nomeArquivo);
            //Zend_Registry::get('logger')->log($this->caminhoPastaFtp.$nomeArquivo, Zend_Log::INFO);
            if ($e->getCode() == "1") {
                $this->view->erro = 3;
            } else {
                $this->view->erro = 1;
            }

            $this->view->mensagem = $e->getCode() . $e->getMessage();
        }
    }

    public function addOcorrenciaAction() {
        $form = new Application_Form_Ocorrencia();
        $form->submit->setLabel('Adicionar ocorrencia');
        $this->view->form = $form;
        //$form->submit->setLabel('Voltar');
        /* if ($this->getRequest()->isPost()) {
          $this->_redirect($this->session->urlAnterior, array('prependBase' => false));
          } */
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
            if (isset($formData["vincularReferencia"])) {//clicou vincular
                //$this->_redirect('/index/vincular-referencia/id/'.$formData['id_produto']);
                //Zend_Registry::get('logger')->log("Clicou vincularReferencia ".$formData['id_referencia'], Zend_Log::INFO);
            } else {//clicou adicionar
            }
            if ($form->isValid($formData)) {
                $descricao = $form->getValue('descricao');
                try {

                    $ocorrencia = new Application_Model_DbTable_Ocorrencia();
                    //$descricao=utf8_decode($descricao);
                    $ocorrencia->addOcorrencia($descricao);
                    //$this->_helper->redirector('index');
                    $this->view->erro = 0;
                    $this->view->mensagem = "Adicionado com sucesso";
                } catch (Exception $erro) {
                    Zend_Registry::get('logger')->log("Erroooooooooooooooo", Zend_Log::INFO);
                    $this->view->mensagem = $erro->getMessage();
                    $this->view->erro = 1;
                    //exit;
                }
            } else {
                Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                $form->populate($formData);

                $arrMessages = $form->getMessages();


                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
        }
    }

    public function addOcorrenciaItemAction() {
        $form = new Application_Form_Ocorrencia();
        $form->submit->setLabel('Adicionar ocorrencia');
        $this->view->form = $form;
        //$form->submit->setLabel('Voltar');
        /* if ($this->getRequest()->isPost()) {
          $this->_redirect($this->session->urlAnterior, array('prependBase' => false));
          } */
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
            if (isset($formData["vincularReferencia"])) {//clicou vincular
                //$this->_redirect('/index/vincular-referencia/id/'.$formData['id_produto']);
                //Zend_Registry::get('logger')->log("Clicou vincularReferencia ".$formData['id_referencia'], Zend_Log::INFO);
            } else {//clicou adicionar
            }
            if ($form->isValid($formData)) {
                $descricao = $form->getValue('descricao');
                try {

                    $ocorrencia = new Application_Model_DbTable_Ocorrencia();
                    $ocorrencia->addOcorrencia($descricao);
                    //$this->_helper->redirector('index');
                    $this->view->erro = 0;
                    $this->view->mensagem = "Adicionado com sucesso";
                } catch (Exception $erro) {
                    Zend_Registry::get('logger')->log("Erroooooooooooooooo", Zend_Log::INFO);
                    $this->view->mensagem = $erro->getMessage();
                    $this->view->erro = 1;
                    //exit;
                }
            } else {
                Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                $form->populate($formData);

                $arrMessages = $form->getMessages();


                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
        }
    }

    public function listaOcorrenciaAction() {
        $ocorrencia = new Application_Model_DbTable_Ocorrencia();

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Sim') {
                $id = $this->getRequest()->getPost('id');
                $ocorrencia = new Application_Model_DbTable_Ocorrencia();
                try {
                    $ocorrencia->deleteOcorrencia($id);

                    $this->view->mensagem = "Excluí­do com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . " Deletar ocorrÃªncia";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    if ($e->getCode() == "23000") {
                        $this->view->mensagem = $e->getCode() . " Não permitido excluir ocorrência com pedidos associados ";
                    }
                }
            }
        }

        $listaOcorrencia = $ocorrencia->getListaOcorrencia();
        Zend_Registry::get('logger')->log($listaOcorrencia, Zend_Log::INFO);

        $this->view->listaOcorrencia = $listaOcorrencia;
    }

    public function deleteOcorrenciaAction() {
        $id = $this->_getParam('id', 0);
        $ocorrencia = new Application_Model_DbTable_Ocorrencia();
        $this->view->ocorrencia = $ocorrencia->getOcorrencia($id);
        Zend_Registry::get('logger')->log($this->view->ocorrencia, Zend_Log::INFO);
    }

    public function deleteOcorrenciaItemAction() {
        $id = $this->_getParam('id', 0);
        $item_has_ocorrencia = new Application_Model_DbTable_ItemHasOcorrencia();
        $this->view->ocorrencia = $item_has_ocorrencia->getItemHasOcorrencia($id);
        Zend_Registry::get('logger')->log($this->view->ocorrencia, Zend_Log::INFO);
    }

    public function listaPedidoAction() {
        $form = new Application_Form_BuscaPedidos();
        $form->submit->setLabel('Buscar');
        $this->view->form = $form;
        $form->exportarPedidos->setLabel('Exportar');
        
        $pedido = new Application_Model_DbTable_Pedido();
        $listaPedido = $pedido->getListaPedido();
        
        if ($this->getRequest()->isPost()) {
          $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $start_date = date("Y-m-d", strtotime($form->getValue('start_date')));
                $end_date = date("Y-m-d", strtotime($form->getValue('end_date')));
                $fornecedor=$form->getValue('fk_fornecedor'); 
                $listaPedido = $pedido->getPedidos($start_date, $end_date,$fornecedor);
                if (isset($formData["exportarPedidos"])) {//clicou submit voltar
                    if (count($listaPedido) > 0) {
                        $this->exportPedidosXlsAction($listaPedido);
                    }
                } 
            } else {
                Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                $form->populate($formData);

                $arrMessages = $form->getMessages();


                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
			$del = $this->getRequest()->getPost('del');
            if ($del == 'Sim') {
                $id = $this->getRequest()->getPost('id');

                try {
                    $ocorrencia->deleteOcorrencia($id);

                    $this->view->mensagem = "Excluído com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . " Deletar pedido";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    if ($e->getCode() == "23000") {
                        $this->view->mensagem = $e->getCode() . " Não permitido excluir pedido  ";
                    }
                }
            }
        }

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Sim') {
                $id = $this->getRequest()->getPost('id');

                try {
                    $ocorrencia->deleteOcorrencia($id);

                    $this->view->mensagem = "Excluído com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . " Deletar pedido";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    if ($e->getCode() == "23000") {
                        $this->view->mensagem = $e->getCode() . " Não permitido excluir pedido  ";
                    }
                }
            }
        }

        Zend_Registry::get('logger')->log($listaPedido, Zend_Log::INFO);

        $this->view->listaPedido = $listaPedido;
    }

    public function listaPedidoItemAction() {
        $pedido = new Application_Model_DbTable_Pedido();

        $id_pedido = $this->_getParam('id', 0);


        $formDestinatario = new Application_Form_VisualizarDestinatario();
        $this->view->formDestinatario = $formDestinatario;

        //
        $formData = $pedido->getDestinatario($id_pedido);
        Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);


        $formDestinatario->populate($formData);
        $form = new Application_Form_ListaPedido();
        $form->removeElement("cupon_ingresso");
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);

            if ($formData["cupon_ingresso"]) {
                try {


                    $this->view->mensagem = "Cupons e Ingressos enviado com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . " Erro ao enviar Cupons e Ingressos " . $e->getMessage();
                    $this->view->erro = 1;
                    //$this->view->mensagemExcecao=$e->getMessage();
                }
            }
            if ($formData["nota_fiscal"]) {
                try {
                    $this->log->gerarLayout880X($id_pedido);

                    $this->view->mensagem = "Conciliação de Nota Fiscal enviado com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . " Erro ao enviar Conciliação de Nota Fiscal " . $e->getMessage();
                    $this->view->erro = 1;
                    //$this->view->mensagemExcecao=$e->getMessage();
                }
            }
            /* $del = $this->getRequest()->getPost('del');
              if ($del == 'Sim') {
              $id = $this->getRequest()->getPost('id');

              try {
              //$ocorrencia->deleteOcorrencia($id);

              $this->view->mensagem = "ExcluÃ­do com sucesso";
              $this->view->erro=0;
              } catch (Exception $e) {
              $this->view->mensagem = $e->getCode()." Deletar pedido";
              $this->view->erro=1;
              $this->view->mensagemExcecao=$e->getMessage();
              if($e->getCode()=="23000"){
              $this->view->mensagem = $e->getCode()." NÃ£o permitido excluir pedido  ";
              }
              }
              } */
        } else {
            
        }

        $pedidoFinalizado = $pedido->pedidoFinalizado($id_pedido);
        if (!$pedidoFinalizado) {//se pedido nao foi finalizado desabilita opÃ§Ã£o
            $form->getElement("nota_fiscal")->setAttrib("disable", array(1));
            //$form->getElement("cupon_ingresso")->setAttrib("disable", array(1));
        }


        $listaItemPedido = $pedido->getItemPedido($id_pedido);
        if (count($listaItemPedido) > 0) {
            if ($listaItemPedido[0]["nota_fiscal"] == "1") {
                $form->getElement("nota_fiscal")->setAttrib("disable", array(1));
            }
        }
        //$form->getElement("nota_fiscal")->setAttrib("disable", array(1));
        Zend_Registry::get('logger')->log($listaItemPedido, Zend_Log::INFO);

        $this->view->listaItemPedido = $listaItemPedido;
    }

    public function imprimirEtiquetaAction() {
        require_once __DIR__ . '../../../exemplos/bootstrap-exemplos.php';

        // ***  DADOS DA ENCOMENDA QUE SERÃ� DESPACHADA *** //
        $dimensao = new \PhpSigep\Model\Dimensao();
        $dimensao->setAltura(20);
        $dimensao->setLargura(20);
        $dimensao->setComprimento(20);
        $dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

        $dimensao = new \PhpSigep\Model\Dimensao();
        $dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);
        $dimensao->setAltura(15); // em centÃ­metros
        $dimensao->setComprimento(17); // em centÃ­metros
        $dimensao->setLargura(12); // em centÃ­metros

        $dbdestinatario = new Application_Model_DbTable_Destinatario();
        $id = $this->_getParam('id', 0);
        $dbdestinatario = $dbdestinatario->find($id);

        $destinatario = array();
        $destinatario = new \PhpSigep\Model\Destinatario();
        $destino = new \PhpSigep\Model\DestinoNacional();
        foreach ($dbdestinatario as $row) {
            $destinatario->setNome($row->nome);
            $destinatario->setLogradouro($row->rua);
            $destinatario->setNumero($row->numero);
            $destinatario->setComplemento($row->compl);
            $destino->setBairro($row->bairro);
            $destino->setCep($row->cep);
            $destino->setCidade($row->cidade);
            $destino->setUf($row->uf);
        }

        // Estamos criando uma etique falsa, mas em um ambiente real voÃ§Ãª deve usar o mÃ©todo 
        // {@link \PhpSigep\Services\SoapClient\Real::solicitaEtiquetas() } para gerar o nÃºmero das etiquetas 
        $etiqueta = new \PhpSigep\Model\Etiqueta();
        $etiqueta->setEtiquetaSemDv('PD73958096BR');

        $servicoAdicional = new \PhpSigep\Model\ServicoAdicional();
        $servicoAdicional->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);

        $encomenda = new \PhpSigep\Model\ObjetoPostal();
        $encomenda->setServicosAdicionais(array($servicoAdicional));
        $encomenda->setDestinatario($destinatario);
        $encomenda->setDestino($destino);
        $encomenda->setDimensao($dimensao);
        $encomenda->setEtiqueta($etiqueta);
        $encomenda->setPeso(0.500); // 500 gramas
        $encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_CARTA_REGISTRADA));
        // ***  FIM DOS DADOS DA ENCOMENDA QUE SERÃ� DESPACHADA *** //
        // *** DADOS DO REMETENTE *** //
        $remetente = new \PhpSigep\Model\Remetente();
        $remetente->setNome('FBL Comercio On Line de Bebidas LTDA');
        $remetente->setLogradouro('Rua da Paisagem');
        $remetente->setNumero('240');
        $remetente->setComplemento('sala 417');
        $remetente->setBairro('Vila da Serra');
        $remetente->setCep('34.000-000');
        $remetente->setUf('MG');
        $remetente->setCidade('Nova Lima');
        // *** FIM DOS DADOS DO REMETENTE *** //

        $plp = new \PhpSigep\Model\PreListaDePostagem();
        $plp->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
        $plp->setEncomendas(array($encomenda));
        $plp->setRemetente($remetente);
        $this->view->imprimirEtiqueta = $plp;
    }

    public function rastreamentoEntregaAction() {
        $form = new Application_Form_OcorrenciaItem();
        $form->submit->setLabel('Adicionar ocorrencia');
        $this->view->form = $form;
        $fk_item = $this->_getParam('idItem', 0);
        $itemHasOcorrencia = new Application_Model_DbTable_ItemHasOcorrencia();
        $item = new Application_Model_DbTable_Item();


        try {
            if ($this->getRequest()->isPost()) {


                $formData = $this->getRequest()->getPost();
                $del = $this->getRequest()->getPost('del');
                $enviarDotz = $this->getRequest()->getPost('enviarDotz');
                $arrayItem['fk_item'] = $fk_item;
                $form->populate($arrayItem);
                Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
                if (isset($enviarDotz)) {
                    Zend_Registry::get('logger')->log("Enviar dotz", Zend_Log::INFO);

                    $this->log->gerarLayout870X($fk_item);
                    $this->view->mensagem = "Enviado com sucesso para Dotz";
                    $this->view->erro = 0;
                } elseif (isset($del)) {
                    if ($del == 'Sim') {
                        $id = $this->getRequest()->getPost('id');

                        try {
                            $itemHasOcorrencia->deleteItemHasOcorrencia($id);
                            //$ocorrencia->deleteOcorrencia($id);

                            $this->view->mensagem = "Excluí­do com sucesso";
                            $this->view->erro = 0;
                        } catch (Exception $e) {
                            $this->view->mensagem = $e->getCode() . " Deletar OcorrÃªncia";
                            $this->view->erro = 1;
                            $this->view->mensagemExcecao = $e->getMessage();
                            if ($e->getCode() == "23000") {
                                $this->view->mensagem = $e->getCode() . " Não permitido excluir OcorrÃªncia  ";
                            }
                        }
                    }


                    Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
                } elseif ($form->isValid($formData)) {

                    Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);



                    $fk_item = $form->getValue('fk_item');
                    $fk_ocorrencia = $form->getValue('fk_ocorrencia');
                    $observacao = $form->getValue('observacao');
                    $final = $form->getValue('final');

                    //$nr_rastreio = $form->getValue('nr_rastreio');
                    //$numero_nf= $form->getValue('numero_nf');
                    //$numero_linha_nf = $form->getValue('numero_linha_nf');
                    //$peso = $form->getValue('peso');



                    try {

                        $itemHasOcorrencia->addItemHasOcorrencia($fk_item, $fk_ocorrencia, $observacao, $final);
                        $this->view->mensagem = "Adicionado com sucesso";
                        $this->view->erro = 0;
                    } catch (Exception $e) {
                        $this->view->mensagem = $e->getCode() . "Erro adicionar ocorrência";
                        $this->view->erro = 1;
                        $this->view->mensagemExcecao = $e->getMessage();
                    }
                } else {

                    $form->populate($formData);
                    $arrMessages = $form->getMessages();
                    foreach ($arrMessages as $field => $arrErrors) {
                        $this->view->erro = 1;
                        $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                    }
                }
            } else {

                $arrayItem['fk_item'] = $fk_item;
                $form->populate($arrayItem);

                /* if ($id_pedido > 0) {

                  $listaItemPedido=$pedido->getRastreamento($id_pedido,$id_item);

                  Zend_Registry::get('logger')->log($listaItemPedido, Zend_Log::INFO);
                  $form->populate($listaItemPedido);

                  $this->view->form = $form;

                  }else{
                  exit;
                  } */
            }
        } catch (Exception $e) {
            //
            /* if (file_exists($this->caminhoPastaFtp.$nomeArquivo)) {
              unlink($this->caminhoPastaFtp.$nomeArquivo);
              } */
            // $this->log->gerarConfirmacaoRecebimento(1,$nomeArquivo);
            //Zend_Registry::get('logger')->log($this->caminhoPastaFtp.$nomeArquivo, Zend_Log::INFO);
            if ($e->getCode() == "1") {
                $this->view->erro = 3;
            } else {
                $this->view->erro = 1;
            }

            $this->view->mensagem = $e->getCode() . " " . $e->getMessage();
        }

        $listaHasOcorrencia = $itemHasOcorrencia->getItemHasOcorrencias($fk_item);
        Zend_Registry::get('logger')->log($listaHasOcorrencia, Zend_Log::INFO);
        Zend_Registry::get('logger')->log("testeee", Zend_Log::INFO);
        $this->view->listaHasOcorrencia = $listaHasOcorrencia;


        $auxItem = $item->getItem($fk_item);


        $this->view->item_atualizado_dotz = $auxItem["item_atualizado_dotz"];
        Zend_Registry::get('logger')->log($this->view->item_atualizado_dotz, Zend_Log::INFO);
        $this->view->fk_item = $fk_item;
        $possuiItemFinal = $itemHasOcorrencia->possuiItemFinal($fk_item);
        Zend_Registry::get('logger')->log($possuiItemFinal, Zend_Log::INFO);
        if ($possuiItemFinal) {
            $form->getElement("submit")->setAttrib("disable", array(1));
        }
    }

    public function detalheItemAction() {
        $pedido = new Application_Model_DbTable_Pedido();
        $form = new Application_Form_DetalheItem();
        //$form->submit->setLabel('Adicionar produto');
        //$this->view->form = $form;



        $id_pedido = $this->_getParam('id', 0);
        $id_item = $this->_getParam('idItem', 0);



        $this->view->form = $form;

        //$form->getElement("id_pedido_dotz")->setAttrib("disable", false);
        $form->getElement("id_pedido_dotz")->setAttrib("disable", array(1));
        $form->getElement("id_pedido")->setAttrib("disable", array(1));
        $form->getElement("produtoiddotz")->setAttrib("disable", array(1));
        $form->getElement("id_item")->setAttrib("disable", array(1));
        $form->getElement("preco")->setAttrib("disable", array(1));
        $form->getElement("frete")->setAttrib("disable", array(1));

        $form->getElement("id_pedido_dotz")->setRequired(FALSE);
        $form->getElement("id_pedido")->setRequired(FALSE);
        $form->getElement("produtoiddotz")->setRequired(FALSE);
        $form->getElement("id_item")->setRequired(FALSE);
        $form->getElement("preco")->setRequired(FALSE);
        $form->getElement("frete")->setRequired(FALSE);

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();
            if (isset($formData["voltar"])) {//clicou submit voltar
                $this->_redirect($this->session->urlAnterior, array('prependBase' => false));
            } elseif ($form->isValid($formData)) {
                $item = new Application_Model_DbTable_Item();
                $nr_rastreio = $form->getValue('nr_rastreio');
                $numero_nf = $form->getValue('numero_nf');
                $u_chave = $form->getValue('u_chave');
                $numero_linha_nf = $form->getValue('numero_linha_nf');
                $peso = $form->getValue('peso');

                Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
                //updateItemReferencia

                try {
                    $item->updateItemReferencia($id_item, $nr_rastreio, $u_chave, $numero_nf, $numero_linha_nf, $peso);

                    $this->view->mensagem = "Alterado com sucesso";
                    $this->view->erro = 0;
                    $listaItemPedido = $pedido->getRastreamento($id_pedido, $id_item);
                    $form->populate($listaItemPedido);
                    $form->populate($listaItemPedido);
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . "Erro alteração pedido";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                }
            } else {
                $listaItemPedido = $pedido->getRastreamento($id_pedido, $id_item);
                $form->populate($listaItemPedido);
                $form->populate($listaItemPedido);
                $form->populate($formData);
                $arrMessages = $form->getMessages();
                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
        } else {


            if ($id_pedido > 0) {

                $listaItemPedido = $pedido->getRastreamento($id_pedido, $id_item);

                Zend_Registry::get('logger')->log($listaItemPedido, Zend_Log::INFO);
                $form->populate($listaItemPedido);

                $this->view->form = $form;
            } else {
                exit;
            }
        }
    }

    public function ftpEntradaAction() {
        $listaArquivos = $this->log->buscaArquivosPastaEntrada();
        Zend_Registry::get('logger')->log($listaArquivos, Zend_Log::INFO);
    }

    public function buscarPedidosDotzAction() {
        //$listaArquivos=$this->log->buscaArquivosPastaEntrada();
        //Zend_Registry::get('logger')->log($listaArquivos, Zend_Log::INFO);
        //$xml = simplexml_load_file("SAIDA/20140812_TM1_860X_6.xml", 'SimpleXMLElement', LIBXML_NOCDATA);
        //$xml = simplexml_load_file("SAIDA/20140812_TM1_860X_2.xml", 'SimpleXMLElement', LIBXML_NOCDATA);
        //$objDom = new DomDocument();
        //$objDom->loadXML($xml->asXML());
        //$objDom->saveXML();
        //Zend_Registry::get('logger')->log($xml->asXML(), Zend_Log::INFO);
        //$this->log->validarXSD($objDom,"860X.xsd");
        if ($this->getRequest()->isPost()) {
            try {



                $mensagem = $this->log->buscaPedidos();
                $this->view->mensagem = "Busca  sucesso <br>" . $mensagem;
                $this->view->erro = 3;
            } catch (Exception $e) {
                $this->view->mensagem = "Buscar pedidos <br>" . $e->getMessage();
                $this->view->erro = $e->getCode();
                $this->view->mensagemExcecao = $e->getMessage();
            }
        }
    }

    public function romaneioEletronicoAction() {
        //$listaArquivos=$this->log->buscaArquivosPastaEntrada();

        $form = new Application_Form_BuscaRomaneioEletronico();
        $form->submit->setLabel('Gerar');
        $this->view->form = $form;

        $pedido = new Application_Model_DbTable_Pedido();


        $mes_atual = date("m");
        Zend_Registry::get('logger')->log($mes_atual, Zend_Log::INFO);
        Zend_Registry::get('logger')->log("Romaneio Eletronico", Zend_Log::INFO);

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $mes = (int) $form->getValue('mes');
                $ano = $form->getValue('ano');
                $this->view->nfvenda = $form->getValue('nfvenda');
                $listaRomaneioEletronico = $pedido->getRomaneioEletronico($mes, $ano);
                $this->view->listaRomaneioEletronico = $listaRomaneioEletronico;
                if (count($listaRomaneioEletronico) > 0) {
                    $this->exportXlsAction($listaRomaneioEletronico, $this->view->nfvenda);
                }

                Zend_Registry::get('logger')->log($listaRomaneioEletronico, Zend_Log::INFO);
            } else {
                Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                $form->populate($formData);

                $arrMessages = $form->getMessages();


                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
        }
    }

    public function exportXlsAction($listaRomaneioEletronico, $nfvenda) {
        set_time_limit(0);

        //$model = new Default_Model_SomeModel();
        $data = $listaRomaneioEletronico;

        $filename = APPLICATION_PATH . "/../romaneio_eletronico/excel-" . date("m-d-Y") . ".xls";

        $realPath = realpath($filename);

        if (false === $realPath) {
            touch($filename);
            chmod($filename, 0777);
        }

        $filename = realpath($filename);
        $handle = fopen($filename, "w");
        $finalData = array();
        $finalData[] = array("NFVENDA", "QTDPROD", "COD PRODUTO NO FORNECEDOR", "DESCRICAO", "VLPROD", "VLFRETE", "COMBO", "VLRPEDIDO", "NUM PEDIDO NA DOTZ","CHAVE");
        foreach ($data AS $row) {
            $aspas = "'";
            $finalData[] = array($nfvenda, "1",
                utf8_decode($row["fk_referencia"]),
                utf8_decode($row["nome"]),
                utf8_decode($row["preco"]),
                utf8_decode($row["frete"]),
                "",
                utf8_decode($row["vlrpedido"]),
                utf8_decode($row["id_pedido_dotz"]),
                $row["u_chave"]
            );
        }
        Zend_Registry::get('logger')->log($finalData, Zend_Log::INFO);
        foreach ($finalData AS $finalRow) {
            Zend_Registry::get('logger')->log($finalRow, Zend_Log::INFO);
            fputcsv($handle, $finalRow, "\t");
        }

        fclose($handle);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $this->getResponse()->setRawHeader("Content-Type: application/vnd.ms-excel; charset=UTF-8")
                ->setRawHeader("Content-Disposition: attachment; filename=excel.xls")
                ->setRawHeader("Content-Transfer-Encoding: binary")
                ->setRawHeader("Expires: 0")
                ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
                ->setRawHeader("Pragma: public")
                ->setRawHeader("Content-Length: " . filesize($filename))
                ->sendResponse();

        readfile($filename);
        exit();
    }

    public function exportPedidosXlsAction($listaPedidos) {
        set_time_limit(0);

        //$model = new Default_Model_SomeModel();
        $data = $listaPedidos; 

        $filename = APPLICATION_PATH . "/../pedidos/excel-" . date("d-m-Y") . ".xls";

		$realPath = realpath($filename);
        if (false === $realPath) {
            touch($filename);
            chmod($filename, 0777);
        }

        $filename = realpath($filename);
        $handle = fopen($filename, "w");
        $finalData = array();
        $finalData[] = array("Codigo pedido TM1", "Codigo pedido dotz", "Data Criação", "Cod. Produto TM1", "Cod. Produto DOTZ", "Cod. Item DOTZ", "CPF", "Tipo Pessoa", "Nome", "Email", "Rua", "Numero", "Complemento", "Bairro", "Cidade", "Estado", "CEP", "DDD", "Telefone", "Ponto de Referencia", "Cód. Identificaçao Usuario", "Nome do Produto","Fornecedor");
        foreach ($data AS $row) {
            $finalData[] = array(
                utf8_decode($row["id_pedido"]),
                utf8_decode($row["id_pedido_dotz"]),
                utf8_decode($row["datacriacao"]),
                utf8_decode($row["fk_referencia"]),
                utf8_decode($row["produtoiddotz"]),
                utf8_decode($row["itemid"]),
                utf8_decode($row["documento"]),
                utf8_decode($row["tipopessoa"]),
                utf8_decode($row["nome"]),
                utf8_decode($row["email"]),
                utf8_decode($row["rua"]),
                utf8_decode($row["numero"]),
                utf8_decode($row["compl"]),
                utf8_decode($row["bairro"]),
                utf8_decode($row["cidade"]),
                utf8_decode($row["uf"]),
                utf8_decode($row["cep"]),
                utf8_decode($row["ddd"]),
                utf8_decode($row["telefone"]),
                utf8_decode($row["pontoreferencia"]),
                utf8_decode($row["codigoident"]),
                utf8_decode($row["nomeproduto"]),
                utf8_decode($row["fk_fornecedor"])     
            );
        }
        Zend_Registry::get('logger')->log($finalData, Zend_Log::INFO);
        foreach ($finalData AS $finalRow) {
            Zend_Registry::get('logger')->log($finalRow, Zend_Log::INFO);
            fputcsv($handle, $finalRow, "\t");
        }

        fclose($handle);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $this->getResponse()->setRawHeader("Content-Type: application/vnd.ms-excel; charset=UTF-8")
                ->setRawHeader("Content-Disposition: attachment; filename=excel.xls")
                ->setRawHeader("Content-Transfer-Encoding: binary")
                ->setRawHeader("Expires: 0")
                ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
                ->setRawHeader("Pragma: public")
                ->setRawHeader("Content-Length: " . filesize($filename))
                ->sendResponse();

        readfile($filename);
        exit();
    }

    public function addFornecedorAction() {
        $form = new Application_Form_Fornecedor();
        $form->submit->setLabel('Adicionar fornecedor');
        $this->view->form = $form;
        //$form->submit->setLabel('Voltar');
        /* if ($this->getRequest()->isPost()) {
          $this->_redirect($this->session->urlAnterior, array('prependBase' => false));
          } */
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            Zend_Registry::get('logger')->log($formData, Zend_Log::INFO);
            if ($form->isValid($formData)) {
                $codFornecedor = $form->getValue('codFornecedor');
                $nomFornecedor = $form->getValue('nomFornecedor');
                try {

                    $fornecedor = new Application_Model_DbTable_Fornecedor();
                    //$descricao=utf8_decode($descricao);
                    $fornecedor->addFornecedor($codFornecedor, $nomFornecedor);
                    //$this->_helper->redirector('index');
                    $this->view->erro = 0;
                    $this->view->mensagem = "Adicionado com sucesso";
                } catch (Exception $erro) {
                    Zend_Registry::get('logger')->log("Erroooooooooooooooo", Zend_Log::INFO);
                    $this->view->mensagem = $erro->getMessage();
                    $this->view->erro = 1;
                    //exit;
                }
            } else {
                Zend_Registry::get('logger')->log("formulario inválido", Zend_Log::INFO);
                $form->populate($formData);

                $arrMessages = $form->getMessages();


                foreach ($arrMessages as $field => $arrErrors) {
                    $this->view->erro = 1;
                    $this->view->mensagem = $this->view->mensagem . $form->getElement($field)->getLabel() . $this->view->formErrors($arrErrors) . "<br>";
                }
            }
        }
    }

    public function editFornecedorAction() {
        // action body
        $form = new Application_Form_Fornecedor();
        $form->submit->setLabel('Salvar fornecedor');

        $this->view->form = $form;
        Zend_Registry::get('logger')->log($form->getValues(), Zend_Log::INFO);
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int) $form->getValue('idFornecedor');
                $codFornecedor = $form->getValue('codFornecedor');
                $nomFornecedor = $form->getValue('nomFornecedor');
                $fornecedores = new Application_Model_DbTable_Fornecedor();


                if (is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
                    Zend_Registry::get('logger')->log("Entrou if is upload", Zend_Log::INFO);
                    $nomeArquivo = md5(uniqid()) . '.' . $extensao;
                    $extension = pathinfo($nomedaimagem, PATHINFO_EXTENSION);

                    $imageAdapter->addFilter('Rename', $nomeArquivo);
                    if (!$imageAdapter->receive('fileUpload')) {
                        $messages = $imageAdapter->getMessages['fileUpload'];
                        //A Imagem NÃ£o Foi Recebida Corretamente
                        Zend_Registry::get('logger')->log("A Imagem NÃ£o Foi Recebida Corretamente", Zend_Log::INFO);
                    } else {
                        //Arquivo Enviado Com Sucesso
                        //Realize As AÃ§Ãµes NecessÃ¡rias Com Os Dados
                        Zend_Registry::get('logger')->log("A Imagem  Recebida Corretamente", Zend_Log::INFO);


                        $arquivo = new Application_Model_DbTable_Arquivo();
                        $fk_arquivo = $arquivo->addArquivo($nomeArquivo, $extensao);
                        Zend_Registry::get('logger')->log("Id arquivo =" . $fk_arquivo, Zend_Log::INFO);
                    }
                } else {

                    Zend_Registry::get('logger')->log("O Arquivo NÃ£o Foi Enviado Corretamente", Zend_Log::INFO);
                    //O Arquivo NÃ£o Foi Enviado Corretamente
                }


                try {
                    $fornecedores->updateFornecedor($id, $codFornecedor, $nomFornecedor);
                    $this->view->mensagem = "Atualizado com sucesso";
                    $this->view->erro = 0;

                    //$this->_helper->redirector('lista-usuario');
                } // catch (pega exceÃ§Ã£o)
                catch (Exception $e) {
                    $this->view->mensagem = "Atualizar fornecedor";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    //  echo ($e->getCode()."teste".$e->getMessage() );
                }
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);

            if ($id > 0) {
                $fornecedores = new Application_Model_DbTable_Fornecedor();
                Zend_Registry::get('logger')->log("Id fornecedor =" . $id, Zend_Log::INFO);
                $form->populate($fornecedores->getFornecedor($id));
            }
        }
    }

    public function listaFornecedorAction() {
        $fornecedor = new Application_Model_DbTable_Fornecedor();

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Sim') {
                $id = $this->getRequest()->getPost('id');
                $fornecedor = new Application_Model_DbTable_Fornecedor();
                try {
                    $fornecedor->deleteFornecedor($id);

                    $this->view->mensagem = "Excluí­do com sucesso";
                    $this->view->erro = 0;
                } catch (Exception $e) {
                    $this->view->mensagem = $e->getCode() . " Deletar fornecedor";
                    $this->view->erro = 1;
                    $this->view->mensagemExcecao = $e->getMessage();
                    if ($e->getCode() == "23000") {
                        $this->view->mensagem = $e->getCode() . " Não permitido excluir fornecedor com produtos associados ";
                    }
                }
            }
        }

        $listaFornecedor = $fornecedor->getListaFornecedor();
        Zend_Registry::get('logger')->log($listaFornecedor, Zend_Log::INFO);

        $this->view->listaFornecedor = $listaFornecedor;
    }

    public function deleteFornecedorAction() {
        $id = $this->_getParam('id', 0);
        $fornecedor = new Application_Model_DbTable_Fornecedor();
        $this->view->fornecedor = $fornecedor->getFornecedor($id);
        Zend_Registry::get('logger')->log($this->view->fornecedor, Zend_Log::INFO);
    }

}

