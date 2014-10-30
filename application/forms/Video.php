<?php

class Application_Form_Video extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $fk_evento = new Zend_Form_Element_Hidden('fk_evento');
        $fk_evento->addFilter('Int');
        
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $url = new Zend_Form_Element_Text('url');
        $url->setLabel('Url youtube')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id,$fk_evento, $nome, $url, $submit));
     // $this->addElements(array($id, $nome, $email,$senha, $submit));
    }


}

