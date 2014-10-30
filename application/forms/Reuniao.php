<?php

class Application_Form_Reuniao extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('nome');
        $id = new Zend_Form_Element_Hidden('id_reuniao');
        $id->addFilter('Int');

        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
            
        $date = new Zend_Form_Element_Text('date');
        $date->setLabel('Date')
           ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
        
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'))
         ->addPrefixPath('Aplicacao_Validate', 'Aplicacao/Validate/', 'validate')
			->addValidator(new Aplicacao_Validate_Data()); 
			
        $comentarios = new Zend_Form_Element_Textarea('comentarios');
        $comentarios->setLabel('Observação')
          ->setRequired(true)  

            ->setAttrib('cols', '55') 
    ->setAttrib('rows', '20');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $nome,$date, $comentarios,$submit));
     
    }


}

