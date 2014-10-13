<?php

class Application_Form_UploadArquivo extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setAttrib('enctype', 'multipart/form-data');
    	    	
    	$this->setName('FormularioProduto');
     
        
        $nomeImagem = new Zend_Form_Element_Hidden('nomeImagem');
        
       
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
            
          $fileUpload = new Zend_Form_Element_File('fileUpload');
		  $fileUpload->setLabel('Arquivo XML')
		   ->setRequired(true)
		->addValidator('Extension', false, array('xml'))
	->addValidator('Size', false, 4024000);
   
       
        
        $this->addElements(array($nomeImagem,$fileUpload,$submit)); 
       
    }


}

