<?php

class Application_Form_Arquivo extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('nome');
    	$this->setAttrib('enctype', 'multipart/form-data');
        
        $fk_arquivo = new Zend_Form_Element_Hidden('fk_arquivo');
        $fk_arquivo->addFilter('Int');
        
     
    
            
       
       $element = new Zend_Form_Element_File('fileUpload');
$element->setLabel('Arquivo')
	->addValidator('Extension', false, array('jpg', 'png', 'gif','txt','pdf','doc','docx','ppt','pptx','xls','xlsx','odt'))
	->addValidator('Size', false, 4024000);
;
       
       $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($element, $submit));
     // $this->addElements(array($id, $nome, $email,$senha, $submit));
    }


}

