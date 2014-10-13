<?php

class Application_Form_Ocorrencia extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setAttrib('enctype', 'multipart/form-data');
    	    	
    	$this->setName('FormularioOcorrencia');
        $id = new Zend_Form_Element_Hidden('id_ocorrencia');
        $id->addFilter('Int');
        
        
        
		$descricao = new Zend_Form_Element_Textarea('descricao');
        $descricao->setLabel('Descricao')
            ->setRequired(true);
		$descricao->removeDecorator('DtDdWrapper')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label')
         ->setAttrib('class', 'form-control')
    	->setAttrib('rows', '5');
            
		
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
       
    
        
        $this->addElements(array($id,$descricao,$submit)); 
        
        $this->setDecorators( array( array('ViewScript', array('viewScript' => 'formularioOcorrencia.phtml'))));
    }


}

