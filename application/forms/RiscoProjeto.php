<?php

class Application_Form_RiscoProjeto extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	
    	$this->setName('formularioRiscoProjeto');
    	
        
    	$fk_projeto = new Zend_Form_Element_Hidden('fk_projeto');
        $fk_projeto->addFilter('Int');
        
        $id_risco_projeto = new Zend_Form_Element_Hidden('id_risco_projeto');
        $id_risco_projeto->addFilter('Int');

        $issue = new Zend_Form_Element_Text('issue');
        $issue->setLabel('ISSUE')      
            ->addFilter('StripTags')
            ->addFilter('StringTrim');
            
        $agency = new Zend_Form_Element_Text('agency');
        $agency->setLabel('AGENCY')      
            ->addFilter('StripTags')
            ->addFilter('StringTrim');

		$listaPrioridade=array("Low"=>"Low","Medium"=>"Medium","High"=>"High");
		$priority= new Zend_Form_Element_Select('priority');
		$priority->setLabel('PRIORITY');
      	$priority->setMultiOptions( $listaPrioridade );
      	
      	
      	$opened_on = new Zend_Form_Element_Text('opened_on', array("disabled" => "disabled"));
        $opened_on->setLabel('OPENED ON')
           ->addFilter('StripTags')
           ->setRequired(true)
            ->addFilter('StringTrim')       
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'));
            	
        $usuarios = new Application_Model_DbTable_Usuario();
        $listaUsuarios=$usuarios->getUsuarioCombo();
    	$opened_by= new Zend_Form_Element_Select('opened_by', array("disabled" => "disabled"));
		$opened_by->setLabel('OPENED BY');
      	$opened_by->setMultiOptions( $listaUsuarios );
        
      	
       	$closed_on = new Zend_Form_Element_Text('closed_on', array("disabled" => "disabled"));
        $closed_on->setLabel('CLOSED ON')
           ->addFilter('StripTags')
            ->addFilter('StringTrim')       
            ->addValidator( new Zend_Validate_Date('dd/mm/aaaa'));
            

    	$closed_by= new Zend_Form_Element_Select('closed_by', array("disabled" => "disabled"));
		$closed_by->setLabel('CLOSED BY');
      	$closed_by->setMultiOptions( $listaUsuarios );

       $mitigation_plan = new Zend_Form_Element_Text('mitigation_plan');
        $mitigation_plan->setLabel('MITIGATION PLAN')      
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

            
            
		$comments = new Zend_Form_Element_Textarea('comments');
        $comments->setLabel('COMMENTS') 
        ->setAttrib('cols', '55') 
    	->setAttrib('rows', '20');   
        
        
    
		
        
        
        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->removeDecorator('DtDdWrapper'); 
        $cancel->setAttrib('id', 'cancel');  
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
         $submit->removeDecorator('DtDdWrapper'); 
        //$this->addElements(array($cancel, $submit)); 

	 
		$this->addElements(array($id_risco_projeto,$fk_projeto,$issue,$agency,$priority,$opened_on,$opened_by,$closed_on,$closed_by,$mitigation_plan,$comments,$cancel, $submit));
		
			$this->addDisplayGroup(array('cancel', 'submit'), 'buttons', array('disableLoadDefaultDecorators' => true)); 

		$group = $this->getDisplayGroup('buttons'); 

		$group->addDecorators(array( array('FormElements'), array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')), ));
     
    }


}

