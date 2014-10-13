<?php

class Application_Form_ProjetoHasAtividade extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('nome');
        $fk_projeto = new Zend_Form_Element_Hidden('fk_projeto');
        $fk_projeto->addFilter('Int');
        
        $fk_atividade = new Zend_Form_Element_Hidden('fk_atividade');
        $fk_atividade->addFilter('Int');

        $nome_atividade = new Zend_Form_Element_Text('nome_atividade', array("disabled" => "disabled"));
        $nome_atividade->setLabel('ACTIVITY')      
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
        
        $data_inicio = new Zend_Form_Element_Text('data_inicio');
        $data_inicio->setLabel('START DATE')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')       
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'));
         //->addPrefixPath('Aplicacao_Validate', 'Aplicacao/Validate/', 'validate')
			//->addValidator(new Aplicacao_Validate_Data());
			
		$eta = new Zend_Form_Element_Text('eta');
		
        $eta->setLabel('ETA')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')       
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'));
         //->addPrefixPath('Aplicacao_Validate', 'Aplicacao/Validate/', 'validate')
			//->addValidator(new Aplicacao_Validate_Data());
			
		$data_fim = new Zend_Form_Element_Text('data_fim');
        $data_fim->setLabel('CLOSURE DATE')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')       
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'));
         //->addPrefixPath('Aplicacao_Validate', 'Aplicacao/Validate/', 'validate')
			//->addValidator(new Aplicacao_Validate_Data());

		$comentario = new Zend_Form_Element_Textarea('comentario');
        $comentario->setLabel('Observação')
        //->setRequired(true)  
        ->setAttrib('cols', '55') 
    	->setAttrib('rows', '20');   
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        
        
         $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->removeDecorator('DtDdWrapper'); 
        $cancel->setAttrib('id', 'cancel');  
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper'); 
        //$this->addElements(array($cancel, $submit)); 

	 
	$this->addElements(array($fk_projeto, $fk_atividade,$nome_atividade,$data_inicio, $eta,$data_fim,$comentario,$cancel,$submit));
		
			$this->addDisplayGroup(array('cancel', 'submit'), 'buttons', array('disableLoadDefaultDecorators' => true)); 

		$group = $this->getDisplayGroup('buttons'); 

		$group->addDecorators(array( array('FormElements'), array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')), ));
        
     
    }


}

