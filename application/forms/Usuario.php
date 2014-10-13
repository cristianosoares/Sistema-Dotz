<?php

class Application_Form_Usuario extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('nome');
    	$this->setAttrib('enctype', 'multipart/form-data');
        $id = new Zend_Form_Element_Hidden('id_usuario');
        $id->addFilter('Int');
        
        
        
        
        $jobrole = new Zend_Form_Element_Text('jobrole');
        $jobrole->setLabel('Jobrole')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
        $cellphone = new Zend_Form_Element_Text('cellphone');
        $cellphone->setLabel('Cellphone')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        
        
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress')
            ->addValidator('NotEmpty');

        $login = new Zend_Form_Element_Text('login');
        $login->setLabel('Login')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');    
            
        $senha = new Zend_Form_Element_Password('senha');
        $senha->setLabel('Senha')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $repetirSenha = new Zend_Form_Element_Password('repetirSenha');
        $repetirSenha->setLabel('Repetir senha')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
       $fk_perfil= new Zend_Form_Element_Select('fk_perfil');
       $perfil = new Application_Model_DbTable_Perfil();
       $fk_perfil->setLabel('Perfil');
       $fk_perfil->setMultiOptions( $perfil->getPerfil() );
       
       

      /* $fk_arquivo= new Zend_Form_Element_File('fk_arquivo');
	   $fk_arquivo->setLabel('Arquivo')
	->addValidator('Extension', false, array('jpg', 'png', 'gif'))
	->addValidator('Size', false, 102400)
	->setDestination(BASE_PATH . '/upload');
       Zend_Registry::get('logger')->log(BASE_PATH . '/upload', Zend_Log::INFO);

       */
       $element = new Zend_Form_Element_File('fileUpload');
$element->setLabel('Arquivo')
	->addValidator('Extension', false, array('jpg', 'png', 'gif'))
	->addValidator('Size', false, 102400);
;
       
       $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $nome, $email,$login,$senha,$repetirSenha,$fk_perfil,$element, $submit));
     // $this->addElements(array($id, $nome, $email,$senha, $submit));
    }


}

