<?php

class Application_Form_AlterarPerfil extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id_usuario');
        $id->addFilter('Int');
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
            
      $element = new Zend_Form_Element_File('fileUpload');
$element->setLabel('Arquivo')
	->addValidator('Extension', false, array('jpg', 'png', 'gif'))
	->addValidator('Size', false, 102400);
;
            
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $nome, $email,$senha,$repetirSenha,$element, $submit));
     // $this->addElements(array($id, $nome, $email,$senha, $submit));
    }


}

