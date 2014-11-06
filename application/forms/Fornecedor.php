<?php

class Application_Form_Fornecedor extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setAttrib('enctype', 'multipart/form-data');
    	    	
    	$this->setName('FormularioFornecedor');
        $id = new Zend_Form_Element_Hidden('idFornecedor');
        $id->addFilter('Int');
        
        
        
	$codFornecedor = new Zend_Form_Element_Text('codFornecedor');
        $codFornecedor->setLabel('Código Fornecedor')
                      ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
         ->setAttrib('class', 'form-control')
         ->setAttrib('placeholder', 'Código Fornecedor');
            
       $nomFornecedor = new Zend_Form_Element_Text('nomFornecedor');
        $nomFornecedor->setLabel('Nome Fornecedor')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            $nomFornecedor->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'form-control')
         ->setAttrib('placeholder', 'Nome do Fornecedor');     
            
		
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel("Adiconar");
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper')
         ->setAttrib('class', 'btn btn-primary button')
        ->removeDecorator('HtmlTag')
        ->removeDecorator('Label');
         
       
    
        
        $this->addElements(array($id,$codFornecedor,$nomFornecedor,$submit)); 
    }


}

